<?php

/*
 * Plugin Name: Product Catalog Plugin for WooCommerce
 * Description: Price products using nFusion Solutions Product Catalog
 * Developer: nFusion Solutions
 * Author URI: https://nfusionsolutions.com
 * WC requires at least: 3.0.0
 * WC tested up to: 6.9.2
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 
 * Version: 2.9.16
*/
define("NFS_CATALOG_PLUGIN_VERSION",   "2.9.16");
define("NFS_CATALOG_REMOTE_TIMEOUT_SECONDS", 2);

function nfs_catalog_plugin_tryGetProduct($skus){
	$productsMap = nfs_catalog_plugin_get_all_products();
	if($productsMap !== false) {
        foreach($skus as $sku) {
            $product = $productsMap[$sku];
            if(isset($product)) {
                return $product;
            }
        }
	}
	return false;
}

function nfs_catalog_plugin_get_all_products(){
	$fetchRemote = false;
	$currency = get_woocommerce_currency();
	$ttlSeconds = 60;//cache timeout in seconds
	$ttlSecondarySeconds = 3600;//very long timeout for secondary cache
	$productsMapKey = 'nfs_catalog_products_all_' . $currency;
	$productsMapSecondaryKey = 'nfs_catalog_products_all_secondary_' . $currency;
	//first check cache
	$productsMap = get_transient($productsMapKey);
	if ($productsMap === false || !isset($productsMap)) {
		//not found in cache, fetch from remote
		$fetchRemote = true;
	}
	else {
		//item found in cache, but we need to double check that it is not too old and stuck in cache
		//we are using a timestamp as a backup for intermittent behavior or WP transients
		//that seems to cause them to convert to infinite expiration on their own
		if(!isset($productsMap['timestamp']) || (time() > ($productsMap['timestamp'] + $ttlSeconds))) {
			$fetchRemote = true;
		}
	}
	
	if ($fetchRemote === true) {
		//we want too prevent many sessions from trying to make the remote call at the same time
		//this can happen around cache expiry boundaries. The problem can become more pronounced if the remote
		//server is slow to reponse, since the remote calls are blocking. An asynchronous approach with a true semaphore
		//might be preferred here, but options are limited in WordPress/PHP stack without plugins (whose existence we cannot guarantee)
		
		//here we will use a second transient as a sort of pseudo-semaphore. It will not truly prevent duplicate requests, but it may reduce them preventing stampede conditions.
		$semaphoreKey = 'nfs_catalog_request_semaphore_' . $currency;
		$semaphoreInUse = get_transient($semaphoreKey);
		if ($semaphoreInUse === false || !isset($semaphoreInUse)) {
			set_transient($semaphoreKey, 1, NFS_CATALOG_REMOTE_TIMEOUT_SECONDS);//set transient ttl same as remote request timeout
			$remoteResult = nfs_catalog_plugin_fetch_products_from_remote($currency);
			if($remoteResult !== false) {//only cache if we got a valid response
				//store new data in first and second level cache
				$productsMap = $remoteResult;
				set_transient($productsMapKey, $productsMap, $ttlSeconds);
				set_transient($productsMapSecondaryKey, $productsMap, $ttlSecondarySeconds);
			}
		}
		
		if ($productsMap === false || !isset($productsMap)) {
			//if we don't have product data at this point, grab from secondary
			$productsMap = get_transient($productsMapSecondaryKey);
		}
	}
	return $productsMap;
}

function nfs_catalog_plugin_fetch_products_from_remote($currency){
	if( !class_exists( 'WP_Http' ) ){
		include_once( ABSPATH . WPINC. '/class-http.php' );
	}
	
	$tenantAlias = get_option('nfusion_tenant_alias');
	$salesChannel = get_option('nfusion_sales_channel');
	$token = get_option('nfusion_api_token');
	$catalogUrl = 'https://'.$tenantAlias.'.nfusioncatalog.com/service/price/pricesbychannel?currency='.$currency.'&channel='.$salesChannel.'&withretailtiers=true&token='.$token;
	
	$args = array(
		'timeout' => NFS_CATALOG_REMOTE_TIMEOUT_SECONDS,//timeout in seconds
		'headers' => array(
			'User-Agent' => 'wpwc-'.NFS_CATALOG_PLUGIN_VERSION,
			'Accept' => 'application/json',
			'Accept-Encoding' => 'gzip'
		)
	);
	$request = wp_remote_get( $catalogUrl, $args);
	if( is_wp_error( $request ) ) {
		return false;
	}

	$body = wp_remote_retrieve_body( $request );	
	$jsonData = json_decode($body, true);
 	if( isset($jsonData) && isset($jsonData[0]))
	{
		$productsMap = array();
		foreach ($jsonData as $item) {
			$productsMap[$item['SKU']] = $item;
		}
		
		if(count($productsMap) !== 0)
		{//we added at least one product
			$productsMap['timestamp'] = time();
			return $productsMap;	
		}
	}
	return false;
}

/**
 *
 * Add a custom meta box to the product page
 */
function nfs_catalog_plugin_add_box() {
    add_meta_box( 
        'nfs_catalog_plugin_sectionid',
        'nFusion Solutions Catalog Integration',
        'nfs_catalog_plugin_inner_custom_box',
        'product',
		'advanced'
    );
}
add_action( 'admin_head', 'nfs_catalog_plugin_add_box' );

function nfs_catalog_plugin_inner_custom_box() {
	global $post;
	$nfs_sku = get_post_meta($post->ID, 'nfs_catalog_plugin_sku', true);
  	echo '<div><label for="nfs_catalog_plugin_sku">Product SKU</label><br/>';
  	echo '<input type="text" id="nfs_catalog_plugin_sku" name="nfs_catalog_plugin_sku" value="'.$nfs_sku.'" size="25" /></div>';  	
}

/**
 * Processes the custom options when a post is saved
 */
function nfs_catalog_plugin_save_product($post_id) {
	update_post_meta($post_id, 'nfs_catalog_plugin_sku', sanitize_text_field($_POST['nfs_catalog_plugin_sku']));
}
add_action('woocommerce_process_product_meta', 'nfs_catalog_plugin_save_product', 10, 2);

/**
 * Override the product price from woocommerce with a price from the nfusion catalog
 */
add_filter( 'woocommerce_product_get_price', 'nfs_catalog_plugin_price', 10000, 2 );
add_filter('woocommerce_product_variation_get_price', 'nfs_catalog_plugin_price', 10000, 2);
function nfs_catalog_plugin_price( $price, $product ){
	$nfs_sku = get_post_meta($product->get_id(), 'nfs_catalog_plugin_sku', true);
    $wc_sku = $product->get_sku();
    $nfsProduct = nfs_catalog_plugin_tryGetProduct(array($nfs_sku, $wc_sku));

	$quantity = 1;
	
	if( is_cart() || is_checkout() || ( defined('DOING_AJAX') && DOING_AJAX ) ) {// If Cart/Checkout/Ajax Page
		if( !WC()->cart->is_empty() ) {
			foreach( WC()->cart->get_cart() as $cart_item) {
                $cartItem = $cart_item['data'];
                $cartItemSku = $cartItem->get_sku();
				if($cartItemSku == $product->get_sku()) {
					$quantity = $cart_item['quantity'];
					break;
				}
			}
		}
	}
	
	if( $nfsProduct !== false ) {
		$ask = round($nfsProduct['Ask'], 2);
		if(isset($nfsProduct['RetailTiers']) && !empty($nfsProduct['RetailTiers'])) {
			$nfsPriceTiers = $nfsProduct['RetailTiers'];
			uasort($nfsPriceTiers, 'nfs_catalog_plugin_compareTiers');//must guarantee tiers are sorted lowest to highest by quantity
			foreach($nfsPriceTiers as $aTier) {
				if($quantity >= $aTier['Quantity']){
					$ask = round($aTier['Ask'], 2);
				}
				else{
					break;//stop searching list, all remaining tiers are larger than quantity
				}
			}
		}
		return $ask;
	}

	return $price;
}

function nfs_catalog_plugin_getLowestPrice($nfsProduct){
	if( $nfsProduct !== false ) {
		$ask = $nfsProduct['Ask'];
		if(isset($nfsProduct['RetailTiers']) && !empty($nfsProduct['RetailTiers'])) {
			$nfsPriceTiers = $nfsProduct['RetailTiers'];
			foreach($nfsPriceTiers as $aTier) {
				if($ask > $aTier['Ask']){
					$ask = $aTier['Ask'];
				}
			}
		}
		return round($ask, 2);
	}

	return false;
}

add_filter( 'woocommerce_get_price_html', 'nfs_catalog_plugin_aslowas_price_html', 100, 2 );
function nfs_catalog_plugin_aslowas_price_html( $priceHtml, $product ){
	if( is_cart() || is_checkout()){
		return $priceHtml;
	}
	
	$nfs_sku = get_post_meta($product->get_id(), 'nfs_catalog_plugin_sku', true);
    $wc_sku = $product->get_sku();
    $nfsProduct = nfs_catalog_plugin_tryGetProduct(array($nfs_sku, $wc_sku));
	if($nfsProduct === false){
		return $priceHtml;
	}
	
	$lowPriceLabel = get_option('nfusion_low_price_label');
	if(empty($lowPriceLabel)){
		$lowPriceLabel = "as low as";
	}
	
	return $lowPriceLabel." ".wc_price(nfs_catalog_plugin_getLowestPrice($nfsProduct));
}

add_filter( 'woocommerce_variable_price_html', 'nfs_catalog_plugin_variation_price_html', 100, 2 );
function nfs_catalog_plugin_variation_price_html( $priceHtml, $product ) {
    $variations = $product->get_available_variations();
    $variation_skus = wp_list_pluck( $variations, 'sku' );

    $prices = [];
    foreach($variation_skus as $sku) {
        $nfsProduct = nfs_catalog_plugin_tryGetProduct(array($sku));
        if ($nfsProduct !== false) {
            $prices[$sku] = $nfsProduct['Ask'];
        }
    }

    if(count($prices) === 0) return $priceHtml;

    sort($prices);
	$priceHtml = ( $prices[0] !== $prices[count($prices) - 1] ) ? sprintf( ( '%s - %s'), wc_price( $prices[0] ), wc_price( $prices[count($prices) - 1] )) : wc_price( $prices[0] );

    return $priceHtml;
}

function nfs_catalog_plugin_compareTiers($a, $b) {
    if ($a['Quantity'] == $b['Quantity']) {
        return 0;
    }
    return ($a['Quantity'] < $b['Quantity']) ? -1 : 1;
}

function nfs_catalog_plugin_product_summary_details() {
	global $post;
    $product = wc_get_product( $post );

    if( $product->is_type( 'variable' ) ) {
        $variations = $product->get_available_variations();
        $variation_skus = wp_list_pluck( $variations, 'sku' );

        foreach($variation_skus as $sku) {
            $nfsProduct = nfs_catalog_plugin_tryGetProduct(array($sku));
			if ($nfsProduct !== false) {
				nfs_catalog_plugin_build_details_html($nfsProduct, true);
			}
        }
    } else {
		$nfs_sku = get_post_meta($post->ID, 'nfs_catalog_plugin_sku', true);
		$wc_sku = $product->get_sku();
		$nfsProduct = nfs_catalog_plugin_tryGetProduct(array($nfs_sku, $wc_sku));
		if ($nfsProduct !== false) {
			nfs_catalog_plugin_build_details_html($nfsProduct, false);
		} else {
			return '';
		}
    }
}

function nfs_catalog_plugin_build_details_html($nfsProduct, $isVariableProd) {
    $variableClass = ($isVariableProd) ? " variable-prod " . $nfsProduct['SKU'] : "";
    if($nfsProduct !== false)
	{
		//Product Bid (buy back price)
		$bid = wc_price(round($nfsProduct['Bid'], 2));
		if(get_option('nfusion_show_buy_price') == 'yes'){
			$buyPriceLabel = get_option('nfusion_buy_price_label');
			if(empty($buyPriceLabel)){
				$buyPriceLabel = "We buy at";
			}
			
			$productBidDiv = "<div class='nfs_catalog_plugin_productbid". $variableClass ."'>".$buyPriceLabel." ".$bid."</div>";
			echo $productBidDiv;
		}
		
		if(isset($nfsProduct['RetailTiers']) && !empty($nfsProduct['RetailTiers']) )
		{
			$cardPriceLabel = get_option('nfusion_tierd_pricing_card_label');
			if(empty($cardPriceLabel)){
				$cardPriceLabel = "Card";
			}

			$checkPriceLabel = get_option('nfusion_tierd_pricing_check_label');
			if(empty($checkPriceLabel)){
				$checkPriceLabel = "Check";
			}

			$nfsPriceTiers = $nfsProduct['RetailTiers'];
			if($nfsPriceTiers and get_option('nfusion_show_tiered_pricing') == 'yes') {
				if( is_array( $nfsPriceTiers ) ) {
					//sort the array so we guarantee the order the table is printed
					uasort($nfsPriceTiers, 'nfs_catalog_plugin_compareTiers');
					$table 	= "<div class='nfs_catalog_plugin_wrapper". $variableClass ."'>";
					$table .= "<h2>Volume Discounts</h2>";
					$table .= "<table class='nfs_catalog_plugin_table' border='1' cellpadding='5'>";
					$table .= "<tr>";
					$table .= "<td>Quantity</td>";
					if(get_option('nfusion_show_credit_card_price') == 'yes'){ 
						$table .= "<td>".$cardPriceLabel."</td>";
					}
					$table .= "<td>".$checkPriceLabel."</td>";
					$table .= "</tr>";
					$firstRow = true;
					foreach($nfsPriceTiers as $aTier) {
						//if the first row has a quantity of greater than 1, then build and artificual first row from the base ask
						if($firstRow){
							if($aTier['Quantity'] > 1){
								$tempRow = nfs_catalog_plugin_build_tier_row_html(1, $nfsProduct['Ask'], 
									get_option('nfusion_show_credit_card_price') == 'yes', get_option('nfusion_cc_price'));
								$table .= $tempRow;
							}
						}
						
						$tempRow = nfs_catalog_plugin_build_tier_row_html($aTier['Quantity'], $aTier['Ask'], 
							get_option('nfusion_show_credit_card_price') == 'yes', get_option('nfusion_cc_price'));
						$table .= $tempRow;
							
						$firstRow = false;
					}
					$table .= "</table></div>";

					echo $table;
				} else {
					return $nfsPriceTiers;
				}
			} else {
				return '';
			}
		}
	} else {
        return '';
    }
}

function nfs_catalog_plugin_build_tier_row_html($quantity, $ask, $showCC, $ccPercent) {
	$row = "<tr>";
	$row .= "<td>".$quantity."+</td>";

	if($showCC){
		$ccAdjust = ($ccPercent/100) + 1;
		$ccAsk = round($ask * $ccAdjust, 2);
		$row .= "<td>".wc_price($ccAsk)."</td>";
	}

	$row .= "<td>".wc_price(round($ask, 2))."</td>";
	$row .= "</tr>";
	return $row;
}

// Print Tier table after product summary if Multiple Tier exists
add_action('woocommerce_single_product_summary', 'nfs_catalog_plugin_product_summary_details', 21);

function nfs_catalog_plugin_tier_table_style() {
?>
<style type="text/css">
.nfs_catalog_plugin_wrapper {margin: 10px 0}
.nfs_catalog_plugin_wrapper .nfs_catalog_plugin_table tr td {padding: 5px; border: 1px solid #808080; text-align: center; width: 10%}
input[type=number] {
    height: 28px;
    line-height: inherit;
    width: 379px;
}

.nfs_catalog_plugin_wrapper.variable-prod, .nfs_catalog_plugin_productbid.variable-prod {display: none}
.nfs_catalog_plugin_wrapper.active, .nfs_catalog_plugin_productbid.active {display: block}
</style>
<?php	
}

// Add Css for Tier table display
add_action('wp_head', 'nfs_catalog_plugin_tier_table_style');

add_filter( 'manage_edit-product_columns', 'nfs_catalog_plugin_set_custom_edit_product_columns' );
add_action( 'manage_product_posts_custom_column' , 'nfs_catalog_plugin_custom_product_column', 10, 2 );

function nfs_catalog_plugin_set_custom_edit_product_columns($columns) {
    $columns['nfs_catalog_plugin_sku'] = __( 'NFS SKU' );

    return $columns;
}

function nfs_catalog_plugin_custom_product_column( $column, $post_id ) {
    switch ( $column ) {
        case 'nfs_catalog_plugin_sku' :
            $nfs_sku = get_post_meta($post_id, 'nfs_catalog_plugin_sku', true);
            if ( empty($nfs_sku) || strlen($nfs_sku) == 0 )
                '-';
            else
                echo $nfs_sku;
            break;
    }
}

function nfs_catalog_enqueue_frontend() {
	// CSS
	wp_enqueue_style( 'nfusion-css', plugin_dir_url( __FILE__ ).'includes/nfusion.css', array(), filemtime(plugin_dir_path( __FILE__ ).'includes/nfusion.css'));
	
	// JS
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( "nfusion-js", plugin_dir_url( __FILE__ ).'includes/nfusion.js', array('jquery'), false, true);
	wp_localize_script( 'nfusion-js', 'nfObj', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' )
    ));
}
add_action('wp_enqueue_scripts', 'nfs_catalog_enqueue_frontend');
add_action('admin_enqueue_scripts', 'nfs_catalog_enqueue_frontend');

add_action( "wp_ajax_cleartransient", "nf_cache_clear_transient" );
add_action( "wp_ajax_nopriv_cleartransient", "nf_cache_clear_transient" );
function nf_cache_clear_transient(){
    $transients = $_POST['transients'];
    $response = new stdClass();

    foreach ($transients as $transient) {
        $response->$transient = new stdClass();
        $response->$transient->name = $transient;

        $productsMap = get_transient($transient);
        $response->$transient->productsMap_before_clear = $productsMap;

        if(isset($productsMap['timestamp'])) {
            $response->$transient->age = secondsToTime($productsMap['timestamp']);
		}

        $response->$transient->cleared = delete_transient($transient);
        $response->$transient->productsMap_after_clear = get_transient($transient);
    }

    $JSONResult = json_encode($response);
    // echo result to client
    echo $JSONResult;

	wp_die(); // ajax call must die to avoid trailing 0 in response
}

/**
 * Evaluates difference between todays date and the given date
 * 
 * @param int $seconds Unix timestamp
 * 
 * @return string Time difference (day(s), hour(s), minute(s), second(s))
 */
function secondsToTime($seconds) {
    $dtFrom = new DateTime("@$seconds");
    $currTime = time();
    $dtTo = new DateTime("@$currTime");
    return $dtFrom->diff($dtTo)->format('%a days, %h hours, %i minutes and %s seconds');
}

/*add global settings start*/

add_action('admin_menu', 'nfs_catalog_plugin_menu_setting_link');
function nfs_catalog_plugin_menu_setting_link(){
	add_options_page('nFusion Settings', 'nFusion Settings', 'manage_options', 'nfusion_global_setting', 't_nfusion_solutions_options');
}

function t_nfusion_solutions_options() {
	$lowPriceLabel = get_option('nfusion_low_price_label');
	if(empty($lowPriceLabel)){
		$lowPriceLabel = "as low as";
	}
	
	$buyPriceLabel = get_option('nfusion_buy_price_label');
	if(empty($buyPriceLabel)){
		$buyPriceLabel = "We buy at";
	}

	$cardPriceLabel = get_option('nfusion_tierd_pricing_card_label');
	if(empty($cardPriceLabel)){
		$cardPriceLabel = "Card";
	}

	$checkPriceLabel = get_option('nfusion_tierd_pricing_check_label');
	if(empty($checkPriceLabel)){
		$checkPriceLabel = "Check";
	}

	?>
    <div class="wrap">
      <?php
        if ( isset($_REQUEST['saved'])) { echo '<div id="message" class="updated fade"><p><strong>settings saved.</strong></p></div>'; }
        ?>
        
        <form method="post" id="myForm" enctype="multipart/form-data">
        
		  <h2><?php _e('nFusion Settings','');?></h2>
		 
		<table class="form-table">
		
		<tr valign="top">
        <th scope="row" style="width: 370px;">
            <label for="nfusion_tenant_alias"><?php _e('Tenant Alias','');?></label>
        </th>
        <td><input type="text" name="nfusion_tenant_alias" size="50" value="<?php echo get_option('nfusion_tenant_alias'); ?>" />
		</td>
		</tr>
		
		<tr valign="top">
        <th scope="row" style="width: 370px;">
            <label for="nfusion_api_token"><?php _e('API Token','');?></label>
        </th>
        <td><input type="text" name="nfusion_api_token" size="50" value="<?php echo get_option('nfusion_api_token'); ?>" />
		</td>
		</tr>
		
		<tr valign="top">
        <th scope="row" style="width: 370px;">
            <label for="nfusion_sales_channel"><?php _e('Sales channel','');?></label>
        </th>
        <td><input type="text" required name="nfusion_sales_channel" size="50" value="<?php echo get_option('nfusion_sales_channel'); ?>" />
		</td>
		</tr>
		
		<tr valign="top">
        <th scope="row" style="width: 370px;">
            <label for="nfusion_cc_price"><?php _e('Credit card price (in %)','');?></label>
        </th>
        <td><input type="number" step="0.01" min="1" max="100" width="370" name="nfusion_cc_price" size="50" value="<?php echo get_option('nfusion_cc_price'); ?>" />
		</td>
		</tr>
		
		<tr valign="top">
        <th scope="row" style="width: 370px;">
            <label for="nfusion_low_price_label"><?php _e('Label for lowest price','');?></label>
        </th>
        <td><input type="text" name="nfusion_low_price_label" size="50" value="<?php echo $lowPriceLabel; ?>" />
		</td>
		</tr>
		
		<tr valign="top">
        <th scope="row" style="width: 370px;">
            <label for="nfusion_buy_price_label"><?php _e('Label for buy price','');?></label>
        </th>
        <td><input type="text" name="nfusion_buy_price_label" size="50" value="<?php echo $buyPriceLabel; ?>" />
		</td>
		</tr>

		<tr valign="top">
        <th scope="row" style="width: 370px;">
            <label for="nfusion_tierd_pricing_card_label"><?php _e('Credit card column label','');?></label>
        </th>
        <td><input type="text" name="nfusion_tierd_pricing_card_label" size="50" value="<?php echo $cardPriceLabel; ?>" />
		</td>
		</tr>

		<tr valign="top">
        <th scope="row" style="width: 370px;">
            <label for="nfusion_tierd_pricing_check_label"><?php _e('Check column label','');?></label>
        </th>
        <td><input type="text" name="nfusion_tierd_pricing_check_label" size="50" value="<?php echo $checkPriceLabel; ?>" />
		</td>
		</tr>
		
		<tr valign="top">
        <th scope="row" style="width: 370px;">
            <label for="nfusion_show_buy_price"><?php _e('Show Buy Price','');?></label>
        </th>
        <td>
	    <input type="checkbox" name="nfusion_show_buy_price" <?php if(get_option('nfusion_show_buy_price') == 'yes'){ ?> checked <?php }?> value="yes" />
       
		</td>
		</tr>
		
		<tr valign="top">
        <th scope="row" style="width: 370px;">
            <label for="nfusion_show_tiered_pricing"><?php _e('Show Tiered Pricing','');?></label>
        </th>
        <td>
	    <input type="checkbox" name="nfusion_show_tiered_pricing" <?php if(get_option('nfusion_show_tiered_pricing') == 'yes'){ ?> checked <?php }?> value="yes" />
       
		</td>
		</tr>
		
		<tr valign="top">
			<th scope="row" style="width: 370px;">
				<label for="nfusion_show_credit_card_price"><?php _e('Show Credit Card Price','');?></label>
			</th>
			<td>
				<input type="checkbox" name="nfusion_show_credit_card_price" <?php if(get_option('nfusion_show_credit_card_price') == 'yes'){ ?> checked <?php }?> value="yes" />
			</td>
		</tr>
		
		
		</table>
		<!--new added end-->
        <p class="submit">
        <input type="submit" name="nfs_options_submit" class="button-primary" value="Save Changes" />
        </p>
 	    <?php 
		//if(function_exists('wp_nonce_field')) wp_nonce_field('nfs_options_submit', 'nfs_options_submit'); 
		?>
       </form>
		
		<div>
			<button type="button" class="clear-cache" value="clear_cache" currency="<?php print get_woocommerce_currency(); ?>">Clear Cache</button>
		</div>
      
    </div>

<?php 
} 

if(isset($_POST["nfs_options_submit"])){
//if(sanitize_text_field($_POST['nfs_options_submit'])){

	if(sanitize_text_field($_POST['nfusion_tenant_alias'])!='' ) {
		update_option('nfusion_tenant_alias', sanitize_text_field($_POST['nfusion_tenant_alias']));
	}

	if(sanitize_text_field($_POST['nfusion_api_token'])!='' ) {
		update_option('nfusion_api_token', sanitize_text_field($_POST['nfusion_api_token']));
	}

	if(sanitize_text_field($_POST['nfusion_sales_channel'])!='' ) {
		update_option('nfusion_sales_channel', sanitize_text_field($_POST['nfusion_sales_channel']));
	}
	
	if(sanitize_text_field($_POST['nfusion_low_price_label'])!='' ) {
		update_option('nfusion_low_price_label', sanitize_text_field($_POST['nfusion_low_price_label']));
	}

	if(sanitize_text_field($_POST['nfusion_tierd_pricing_card_label'])!='' ) {
		update_option('nfusion_tierd_pricing_card_label', sanitize_text_field($_POST['nfusion_tierd_pricing_card_label']));
	}

	if(sanitize_text_field($_POST['nfusion_tierd_pricing_check_label'])!='' ) {
		update_option('nfusion_tierd_pricing_check_label', sanitize_text_field($_POST['nfusion_tierd_pricing_check_label']));
	}
	
	if(sanitize_text_field($_POST['nfusion_buy_price_label'])!='' ) {
		update_option('nfusion_buy_price_label', sanitize_text_field($_POST['nfusion_buy_price_label']));
	}

	if(sanitize_text_field($_POST['nfusion_show_buy_price'])!='' ) {
		update_option('nfusion_show_buy_price', sanitize_text_field($_POST['nfusion_show_buy_price']));
	}else{
		update_option('nfusion_show_buy_price', 'no');
	}

	if(sanitize_text_field($_POST['nfusion_cc_price'])!='' ) {
		update_option('nfusion_cc_price', sanitize_text_field($_POST['nfusion_cc_price']));
	}

	if(sanitize_text_field($_POST['nfusion_show_credit_card_price'])!='' ) {
		update_option('nfusion_show_credit_card_price', sanitize_text_field($_POST['nfusion_show_credit_card_price']));
	}else{
		update_option('nfusion_show_credit_card_price', 'no');
	}

	if(sanitize_text_field($_POST['nfusion_show_tiered_pricing'])!='' ) {
		update_option('nfusion_show_tiered_pricing', sanitize_text_field($_POST['nfusion_show_tiered_pricing']));
	}else{
	   update_option('nfusion_show_tiered_pricing', 'no');
	}

	if($_POST['saved']==true) {
		$location = $_SERVER['REQUEST_URI'];
	} 
  header("Location: $location");
}
?>
