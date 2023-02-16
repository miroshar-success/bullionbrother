<?php
/**
 * The admin advanced settings page functionality of the plugin.
 *
 * @link       https://themehigh.com
 * @since      1.4.4
 *
 * @package    woo-checkout-field-editor-pro
 * @subpackage woo-checkout-field-editor-pro/admin
 */

if(!defined('WPINC')){	die; }

if(!class_exists('THWCFD_Admin_Settings_Themehigh_Plugins')):

class THWCFD_Admin_Settings_Themehigh_Plugins extends THWCFD_Admin_Settings{
	protected static $_instance = null;
	protected $tabs = '';

	private $settings_fields = NULL;
	private $cell_props = array();
	private $cell_props_CB = array();

	public function __construct() {
		parent::__construct();
		$this->page_id = 'themehigh_plugins';
	}
	
	public static function instance() {
		if(is_null(self::$_instance)){
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function render_page(){
		$this->render_tabs();
		$this->render_content();
	}

	private function plugins(){
		return array(
			array(
				'title' => 'Checkout Field Editor (Checkout Manager) for WooCommerce',
				'image' => 'checkout-field-editor.png',
				'content' => 'Checkout Field Editor plugin lets you add and manage the fields of your WooCommerce checkout page and allows you to choose the field display areas.',
				'slug' => 'woo-checkout-field-editor-pro',
				'file' => 'checkout-form-designer.php',
				'featured' => true,
				'skip' => true,
			),
			array(
				'title' => 'Extra Product Options (Product Addons) for WooCommerce',
				'image' => 'extra-product-options.png',
				'content' => 'The Extra Product Options plugin allows you to create additional fields on your product page and helps you to manage them efficiently.',
				'slug' => 'woo-extra-product-options',
				'file' => 'woo-extra-product-options.php',			
			),
			array(
				'title' => 'MultiStep Checkout for WooCommerce',
				'image' => 'multistep-checkout.png',
				'content' => 'Using the compatibility feature of the Multi-step checkout plugin, you can create additional fields to your checkout page and split the default WooCommerce checkout page into simpler steps.',
				'slug' => 'woo-multistep-checkout',
				'file' => 'woo-multistep-checkout.php',				
				'featured' => true,				
			),
			array(
				'title' => 'Email Customizer for WooCommerce',
				'image' => 'email-customizer.png',
				'content' => 'As the Checkout Field editor plugin is compatible with the Email customizer, you can edit the WooCommerce transactional emails with the checkout fields at your desired position in the email.',
				'slug' => 'email-customizer-for-woocommerce',
				'file' => 'email-customizer-for-woocommerce.php',				
				'featured' => true,				
			),					
			array(
				'title' => 'Multiple Addresses for WooCommerce',
				'image' => 'multiple-addresses.png',
				'content' => 'The plugin compatibility helps the shoppers to add custom checkout field data to their customer addresses and lets them choose the desired address while placing the order.',
				'slug' => 'themehigh-multiple-addresses',
				'file' => 'themehigh-multiple-addresses.php',
				'featured' => true,				
			),
			array(
				'title' => 'Order Delivery Date And Time',
				'image' => 'order-delivery.gif',
				'content' => "Order Delivery | Pickup Date and Time Planner allows you to plan your store's delivery and pickup, as well as pre-set specific days and holidays.",
				'slug' => 'order-delivery-date-and-time',
				'file' => 'order-delivery-date-and-time.php',		
				'featured' => true,				
			),
			array(
				'title' => 'Variation Swatches for WooCommerce',
				'image' => 'variation-swatches.png',
				'content' => 'Variation Swatches for Woocommerce plugin lets you display the variable product attributes as attractive swatches of different types.',
				'slug' => 'product-variation-swatches-for-woocommerce',
				'file' => 'product-variation-swatches-for-woocommerce.php',				
			),
			array(
				'title' => 'Job Manager & Career',
				'image' => 'job-manager.png',
				'content' => 'Job Manager & Career is a lightweight WordPress plugin to add and manage job posts on your career page.',
				'slug' => 'job-manager-career',
				'file' => 'job-manager-career.php',		
			),
			array(
				'title' => 'WooCommerce Wishlist and Comparison',
				'image' => 'wishlist-compare.png',
				'content' => 'The plugin helps your customers to move products to the wishlist for future purchases and lets them compare between different products.',
				'slug' => 'wishlist-and-compare',
				'file' => 'wishlist-and-compare.php',
			),
			array(
				'title' => 'Dynamic Pricing and Discount Rules',
				'image' => 'discount-and-dynamic-pricing.png',
				'content' => 'Dynamic Pricing and Discount Rules plugin helps you to define special discount rules for both the product and cart details.',
				'slug' => 'discount-and-dynamic-pricing',
				'file' => 'discount-and-dynamic-pricing.php',
			),
			array(
				'title' => 'Advanced FAQ Manager',
				'image' => 'advanced-faq-manager.png',
				'content' => 'Advanced FAQ Manager plugin lets you easily add and manage the Frequently Asked Questions on your WordPress pages.',
				'slug' => 'advanced-faq-manager',
				'file' => 'advanced-faq-manager.php',
			),	

			array(
				'title' => 'Product Feature Request',
				'image' => 'product-feature-request.png',
				'content' => 'Using the Product Feature Request plugin, you can collect suggestions or ideas from the customers and improve your WooCommerce products.',
				'slug' => 'product-feature-request',
				'file' => 'product-feature-request.php',				
			),				

		);
	}
	
	private function render_content(){
		?>
			<?php 
			$plugins = $this->plugins();
			$featured_plugins = array_filter($plugins, function ($var) {
			    return (isset($var['featured']) && $var['featured'] == true && !(isset($var['skip'])));
			});
			if(!empty($featured_plugins)){ ?>
				<h2><?php _e('Compatible Plugins', 'woo-checkout-field-editor-pro'); ?></h2>
				<div class="th-plugins-wrapper featured">
					<?php
						foreach($featured_plugins as $plugin){
							$title = isset($plugin['title']) ? $plugin['title'] : '';
							$img = isset($plugin['image']) ? $plugin['image'] : '';
							$content = isset($plugin['content']) ? $plugin['content'] : '';
							$link = isset($plugin['download_link']) ? $plugin['download_link'] : '';
							$slug = isset($plugin['slug']) ? $plugin['slug'] : '';
							$file = isset($plugin['file']) ? $plugin['file'] : '';
						?>
						    <div class="th-plugins-child">
						    	<div class="th-title-box">
						    		<?php if($img){ ?>
						    		<img src="<?php echo THWCFD_URL; ?>admin/assets/images/wp-plugins/<?php echo $img; ?>" alt="<?php echo $title; ?>">
						    		<?php } ?>
						    		<h3><a href="https://wordpress.org/plugins/<?php echo esc_attr( $slug ); ?>" target="_blank"><?php echo $title; ?></a></h3>
						    	</div>
						        <?php echo wpautop($content); ?>

								<?php if($slug && $file){
									$this->install_plugin_button($slug, $file, $title);
								} ?>

						    </div>
						<?php } ?>
				</div>		
			<?php } ?>




			<?php
			$plugin = array();
			$regular_plugins = array_filter($plugins, function ($var) {
			    return (!isset($var['featured']) && !isset($var['skip']));
			});
			if(!empty($regular_plugins)){ ?>
				<h2><?php _e('Other Plugins', 'woo-checkout-field-editor-pro'); ?></h2>
				<div class="th-plugins-wrapper">
					<?php
						foreach($regular_plugins as $plugin){
							$title = isset($plugin['title']) ? $plugin['title'] : '';
							$img = isset($plugin['image']) ? $plugin['image'] : '';
							$content = isset($plugin['content']) ? $plugin['content'] : '';
							$link = isset($plugin['download_link']) ? $plugin['download_link'] : '';
							$slug = isset($plugin['slug']) ? $plugin['slug'] : '';
							$file = isset($plugin['file']) ? $plugin['file'] : '';			
						?>
						    <div class="th-plugins-child">
						    	<div class="th-title-box">
						    		<?php if($img){ ?>
						    		<img src="<?php echo THWCFD_URL; ?>admin/assets/images/wp-plugins/<?php echo $img; ?>" alt="<?php echo $title; ?>">
						    		<?php } ?>
						    		<h3><a href="https://wordpress.org/plugins/<?php echo esc_attr( $slug ); ?>" target="_blank"><?php echo $title; ?></a></h3>
						    	</div>
						        <?php echo wpautop($content); ?>

								<?php if($slug && $file){
									$this->install_plugin_button($slug, $file, $title);
								} ?>

						    </div>
						<?php } ?>
				</div>		
			<?php } ?>
		<?php
	}


		/**
		 * Output a button that will install or activate a plugin if it doesn't exist, or display a disabled button if the
		 * plugin is already activated.
		 *
		 * @param string $plugin_slug The plugin slug.
		 * @param string $plugin_file The plugin file.
		 * @param string $plugin_name The plugin name.
		 * @param string $classes CSS classes.
		 * @param string $activated Button activated text.
		 * @param string $activate Button activate text.
		 * @param string $install Button install text.
		 */
		public static function install_plugin_button( $plugin_slug, $plugin_file, $plugin_name, $classes = array(), $activated = '', $activate = '', $install = '' ) {
			if ( current_user_can( 'install_plugins' ) && current_user_can( 'activate_plugins' ) ) {
				if ( is_plugin_active( $plugin_slug . '/' . $plugin_file ) ) {
					// The plugin is already active.
					$button = array(
						'message' => esc_attr__( 'Activated', 'storefront' ),
						'url'     => '#',
						'classes' => array('button', 'disabled' ),
					);

					if ( '' !== $activated ) {
						$button['message'] = esc_attr( $activated );
					}
				} elseif ( self::is_plugin_installed( $plugin_slug ) ) {
					$url = self::is_plugin_installed( $plugin_slug );

					// The plugin exists but isn't activated yet.
					$button = array(
						'message' => esc_attr__( 'Activate', 'storefront' ),
						'url'     => $url,
						'classes' => array( 'activate-now', 'button' ),
					);

					if ( '' !== $activate ) {
						$button['message'] = esc_attr( $activate );
					}
				} else {
					// The plugin doesn't exist.
					$url    = wp_nonce_url(
						add_query_arg(
							array(
								'action' => 'install-plugin',
								'plugin' => $plugin_slug,
							),
							self_admin_url( 'update.php' )
						),
						'install-plugin_' . $plugin_slug
					);
					$button = array(
						'message' => esc_attr__( 'Install now', 'storefront' ),
						'url'     => $url,
						'classes' => array('button-primary', 'install-now', 'install-' . $plugin_slug ),
					);

					if ( '' !== $install ) {
						$button['message'] = esc_attr( $install );
					}
				}

				if ( ! empty( $classes ) ) {
					$button['classes'] = array_merge( $button['classes'], $classes );
				}

				$button['classes'] = implode( ' ', $button['classes'] );

				?>
				<span class="plugin-card-<?php echo esc_attr( $plugin_slug ); ?>">
					<a href="<?php echo esc_url( $button['url'] ); ?>" class="<?php echo esc_attr( $button['classes'] ); ?> th-plugin-action" data-originaltext="<?php echo esc_attr( $button['message'] ); ?>" data-name="<?php echo esc_attr( $plugin_name ); ?>" data-slug="<?php echo esc_attr( $plugin_slug ); ?>" aria-label="<?php echo esc_attr( $button['message'] ); ?>"><?php echo esc_html( $button['message'] ); ?></a>
				</span>
				<?php
			}
		}

	/**
	 * Check if a plugin is installed and return the url to activate it if so.
	 *
	 * @param string $plugin_slug The plugin slug.
	 */
	public static function is_plugin_installed( $plugin_slug ) {
		if ( file_exists( WP_PLUGIN_DIR . '/' . $plugin_slug ) ) {
			$plugins = get_plugins( '/' . $plugin_slug );
			if ( ! empty( $plugins ) ) {
				$keys        = array_keys( $plugins );
				$plugin_file = $plugin_slug . '/' . $keys[0];
				$url         = wp_nonce_url(
					add_query_arg(
						array(
							'action' => 'activate',
							'plugin' => $plugin_file,
						),
						admin_url( 'plugins.php' )
					),
					'activate-plugin_' . $plugin_file
				);
				return $url;
			}
		}
		return false;
	}

	function activate_themehigh_plugins(){
		$plugin_file = isset($_REQUEST['file']) ? $_REQUEST['file'] : '';
		if( $plugin_file && check_ajax_referer( 'activate-plugin_' . $plugin_file ) ){
			if ( current_user_can( 'install_plugins' ) && current_user_can( 'activate_plugins' ) ) {
				if (!is_plugin_active($plugin_file) ) {

					$result = activate_plugin($plugin_file);

			        if( is_wp_error( $result ) ) {
			            wp_send_json(false);
			        }else{
			        	wp_send_json(true);
			        }
				}
			}
		}
		wp_send_json(false);
	}
	
}

endif;