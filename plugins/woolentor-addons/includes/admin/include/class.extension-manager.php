<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

class Woolentor_Extension_Manager{

	// Get Instance
    private static $_instance = null;
    public static function instance(){
        if( is_null( self::$_instance ) ){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    function __construct(){
    	if( is_admin() ){
    		add_action( 'admin_menu', [ $this, 'admin_menu' ], 226 );
    		add_action( 'admin_enqueue_scripts', [ $this, 'scripts' ] );
    	}
    }

    // Menu for Extension
    public function admin_menu() {
        add_submenu_page(
            'woolentor_page', 
            esc_html__( 'Extension', 'woolentor' ),
            esc_html__( 'Extension', 'woolentor' ), 
            'manage_options', 
            'woolentor_extension', 
            [ $this, 'render_html' ] 
        );
    }

    public function scripts( $hook ) {
        if( 'shoplentor_page_woolentor_extension' == $hook ){
        	
        	//JS
            wp_enqueue_script( 'woolentor-install-manager' );
            wp_enqueue_script( 'woolentor-admin-main' );

        }
    }

    // Extension Menu HTML
    public function render_html(){

    	if ( ! function_exists('plugins_api') ){ include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' ); }

    	$htplugins_plugins_list = !empty( $this->get_plugins() ) ? $this->get_plugins() : array();
    	$palscode_plugins_list = !empty( $this->get_plugins( 'palscode' ) ) ? $this->get_plugins( 'palscode' ) : array();

    	$org_plugins_list = array_merge( $htplugins_plugins_list, $palscode_plugins_list );

    	$prepare_plugin = array();
    	foreach ( $org_plugins_list as $key => $plugin ) {
			$plugin = (array) $plugin;
    		$prepare_plugin[$plugin['slug']] = $plugin;
    	}

    	$plugins_list = array(

    		'free' => array(
				
	    		array(
	    			'slug'		=> 'whols',
	    			'location'	=> 'whols.php',
	    			'name'		=> esc_html__( 'Whols', 'woolentor' )
	    		),
	    		array(
	    			'slug'		=> 'just-tables',
	    			'location'	=> 'just-tables.php',
	    			'name'		=> esc_html__( 'JustTables', 'woolentor' )
	    		),
	    		array(
	    			'slug'		=> 'wc-multi-currency',
	    			'location'	=> 'wcmilticurrency.php',
	    			'name'		=> esc_html__( 'Multi Currency', 'woolentor' )
	    		)

    		),

    		'pro' => array(

    			array(
	    			'slug'		=> 'just-tables-pro',
	    			'location'	=> 'just-tables-pro.php',
	    			'name'		=> esc_html__( 'JustTables Pro', 'woolentor' ),
	    			'link'		=> 'https://hasthemes.com/wp/justtables/',
	    			'author_link'=> 'https://hasthemes.com/',
	    			'description'=> esc_html__( 'JustTables is an incredible WordPress plugin that lets you showcase all your WooCommerce products in a sortable and filterable table view. It allows your customers to easily navigate through different attributes of the products and compare them on a single page. This plugin will be of great help if you are looking for an easy solution that increases the chances of landing a sale on your online store.', 'woolentor' ),
	    		),

    			array(
	    			'slug'		=> 'whols-pro',
	    			'location'	=> 'whols-pro.php',
	    			'name'		=> esc_html__( 'Whols Pro – WooCommerce Wholesale Prices', 'woolentor' ),
	    			'link'		=> 'https://hasthemes.com/plugins/whols-woocommerce-wholesale-prices/',
	    			'author_link'=> 'https://hasthemes.com/',
	    			'description'=> esc_html__( 'Whols is an outstanding WordPress plugin for WooCommerce that allows store owners to set wholesale prices for the products of their online stores. This plugin enables you to show special wholesale prices to the wholesaler. Users can easily request to become a wholesale customer by filling out a simple online registration form. Once the registration is complete, the owner of the store will be able to review the request and approve the request either manually or automatically.', 'woolentor' ),
	    		),

    			array(
	    			'slug'		=> 'multicurrencypro',
	    			'location'	=> 'multicurrencypro.php',
	    			'name'		=> esc_html__( 'Multi Currency Pro for WooCommerce', 'woolentor' ),
	    			'link'		=> 'https://hasthemes.com/plugins/multi-currency-pro-for-woocommerce/',
	    			'author_link'=> 'https://hasthemes.com/',
	    			'description'=> esc_html__( 'Multi-Currency Pro for WooCommerce is a prominent currency switcher plugin for WooCommerce. This plugin allows your website or online store visitors to switch to their preferred currency or their country’s currency.', 'woolentor' ),
	    		)

    		),
    	);

    	echo '<div class="wrap"><h2>'.get_admin_page_title().'</h2>';

    		?>
    			<style>
    				.woolentor-admin-tab-pane{
					  display: none;
					}
					.woolentor-admin-tab-pane.wlactive{
					  display: block;
					}
					.extension-admin-tab-area .filter-links li>a:focus, .extension-admin-tab-area .filter-links li>a:hover {
					    color: inherit;
					    box-shadow: none;
					}
					.filter-links .wlactive{
					    box-shadow: none;
					    border-bottom: 4px solid #646970;
					    color: #1d2327;
					}
    			</style>
    			<div class="extension-admin-tab-area wp-filter">
	                <ul class="woolentor-admin-tabs filter-links">
	                    <li class="wlactive"><a href="#free-extension" class="wlactive"><?php echo esc_html__( 'Free extension', 'woolentor' ); ?></a></li>
	                    <li><a href="#pro-extension"><?php echo esc_html__( 'Pro extension', 'woolentor' ); ?></a></li>
	                </ul>
	            </div>

	            <div id="pro-extension" class="woolentor-admin-tab-pane">
					<div class="woolentor-admin-extensions woolentor-extension-col-3">
						<?php
							foreach ( $plugins_list['pro'] as $key => $plugin ) {

								$data = array(
									'slug'      => isset( $plugin['slug'] ) ? $plugin['slug'] : '',
									'location'  => isset( $plugin['location'] ) ? $plugin['slug'].'/'.$plugin['location'] : '',
									'name'      => isset( $plugin['name'] ) ? $plugin['name'] : '',
									'image'     => isset( $plugin['icon'] ) ? $plugin['icon'] : WOOLENTOR_ADDONS_PL_URL.'/includes/admin/assets/images/extension/'.$plugin['slug'].'.png',
								);

								if ( ! is_wp_error( $data ) ) {

									// Installed but Inactive.
									if ( file_exists( WP_PLUGIN_DIR . '/' . $data['location'] ) && is_plugin_inactive( $data['location'] ) ) {

										$button_classes = 'button activate-now button-primary';
										$button_text    = esc_html__( 'Activate', 'woolentor' );

									// Not Installed.
									} elseif ( ! file_exists( WP_PLUGIN_DIR . '/' . $data['location'] ) ) {

										$button_classes = 'button install-now';
										$button_text    = esc_html__( 'Install Now', 'woolentor' );

									// Active.
									} else {
										$button_classes = 'button disabled';
										$button_text    = esc_html__( 'Activated', 'woolentor' );
									}

									?>

									<!-- Extension Start -->
									<div class="woolentor-admin-extension htwptemplata-plugin-<?php echo $data['slug']; ?>">
										<div class="woolentor-admin-extension-top">
											<div class="woolentor-admin-extension-image">
												<img src="<?php echo $data['image']; ?>" alt="<?php echo $plugin['name']; ?>">
											</div>
											<div class="woolentor-admin-extension-content">
												<h4 class="woolentor-admin-extension-title"><?php echo $plugin['name']; ?></h4>
												<p class="woolentor-admin-extension-text"><?php echo wp_trim_words( $plugin['description'], 23, '....'); ?></p>
											</div>
										</div>
										<div class="woolentor-admin-extension-bottom">
											<p class="woolentor-admin-extension-info"><a href="<?php echo esc_url( $plugin['link'] ); ?>" target="_blank"><?php echo esc_html__( 'More Details', 'woolentor' ); ?></a></p>
											<?php
												if (! file_exists( WP_PLUGIN_DIR . '/' . $data['location'] ) ) {
													echo '<a class="hover-effect-1 button button-primary" href="'.esc_url( $plugin['link'] ).'" target="_blank">'.esc_html__( 'Buy Now', 'woolentor' ).'</a>';
												}else{
											?>
												<button class="hover-effect-1 <?php echo $button_classes; ?>" data-pluginopt='<?php echo wp_json_encode( $data ); ?>'><?php echo $button_text; ?></button>
											<?php } ?>
										</div>
									</div>
									<!-- Extension End -->

									<?php

								}

							}
						?>
					</div>
	            </div>

    		<?php

	    	echo '<div id="free-extension" class="woolentor-admin-tab-pane wlactive"><div class="woolentor-admin-extensions woolentor-extension-col-3">';

		    	foreach ( $plugins_list['free'] as $key => $plugin ) {

		            $data = array(
		                'slug'      => isset( $plugin['slug'] ) ? $plugin['slug'] : '',
		                'location'  => isset( $plugin['location'] ) ? $plugin['slug'].'/'.$plugin['location'] : '',
		                'name'      => isset( $plugin['name'] ) ? $plugin['name'] : '',
		            );

		            if ( ! is_wp_error( $data ) ) {

		                // Installed but Inactive.
		                if ( file_exists( WP_PLUGIN_DIR . '/' . $data['location'] ) && is_plugin_inactive( $data['location'] ) ) {

		                    $button_classes = 'button activate-now button-primary';
		                    $button_text    = esc_html__( 'Activate', 'woolentor' );

		                // Not Installed.
		                } elseif ( ! file_exists( WP_PLUGIN_DIR . '/' . $data['location'] ) ) {

		                    $button_classes = 'button install-now';
		                    $button_text    = esc_html__( 'Install Now', 'woolentor' );

		                // Active.
		                } else {
		                    $button_classes = 'button disabled';
		                    $button_text    = esc_html__( 'Activated', 'woolentor' );
		                }

		                if( !empty( $data['slug'] ) && isset( $prepare_plugin[$data['slug']] ) ){

			                ?>
								<!-- Extension Start -->
								<div class="woolentor-admin-extension htwptemplata-plugin-<?php echo $data['slug']; ?>">
									<div class="woolentor-admin-extension-top">
										<div class="woolentor-admin-extension-image">
											<img src="<?php echo $prepare_plugin[$data['slug']]['icons']['1x']; ?>" alt="<?php echo $prepare_plugin[$data['slug']]['name']; ?>">
										</div>
										<div class="woolentor-admin-extension-content">
											<h4 class="woolentor-admin-extension-title"><?php echo $prepare_plugin[$data['slug']]['name']; ?></h4>
											<p class="woolentor-admin-extension-text"><?php echo wp_trim_words( $prepare_plugin[$data['slug']]['description'], 23, '....'); ?></p>
										</div>
									</div>
									<div class="woolentor-admin-extension-bottom">
										<p class="woolentor-admin-extension-info"><i class="wli wli-info"></i><?php printf( __( '%s Active Installations' ), $this->active_install_count( $prepare_plugin[$data['slug']]['active_installs'] ) ); ?> <a href="<?php echo esc_url( admin_url() ) ?>/plugin-install.php?tab=plugin-information&plugin=<?php echo $data['slug']; ?>&TB_iframe=true&width=772&height=577" class="thickbox open-plugin-details-modal"><?php echo esc_html__( 'More Details', 'woolentor' ); ?></a></p>
										<button class="hover-effect-1 <?php echo $button_classes; ?>" data-pluginopt='<?php echo wp_json_encode( $data ); ?>'><?php echo $button_text; ?></button>
									</div>
								</div>
								<!-- Extension End -->
			                <?php
			            }

		            }

		        }

	        echo '</div></div>';

        echo '</div>';


    }

    /**
     * [active_install_count] Manage Active install count
     * @param  [int] $active_installs
     * @return [string]
     */
    public function active_install_count( $active_installs ){

        if ( $active_installs >= 1000000 ) {
            $active_installs_millions = floor( $active_installs / 1000000 );
            $active_installs_text     = sprintf(
                /* translators: %s: Number of millions. */
                _nx( '%s+ Million', '%s+ Million', $active_installs_millions, 'Active plugin installations' ),
                number_format_i18n( $active_installs_millions )
            );
        } elseif ( 0 === $active_installs ) {
            $active_installs_text = _x( 'Less Than 10', 'Active plugin installations' );
        } else {
            $active_installs_text = number_format_i18n( $active_installs ) . '+';
        }
        return $active_installs_text;

    }

    /* Get Plugins list from wp.prg */
    public function get_plugins( $username = 'htplugins' ){
    	$transient_var = 'woolentor_htplugins_list_'.$username;
    	$org_plugins_list = get_transient( $transient_var );
    	if ( false === $org_plugins_list ) {
    		$plugins_list_by_authoir = plugins_api( 'query_plugins', array( 'author' => $username, 'per_page'=>100 ) );
    		set_transient( $transient_var, $plugins_list_by_authoir->plugins, 1 * DAY_IN_SECONDS );
    		$org_plugins_list = $plugins_list_by_authoir->plugins;
    	}
    	return $org_plugins_list;
    }


}

Woolentor_Extension_Manager::instance();