<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://phoeniixx.com/
 * @since      1.0.0
 *
 * @package    Phoen_login_signup
 * @subpackage Phoen_login_signup/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Phoen_login_signup
 * @subpackage Phoen_login_signup/public
 * @author     phoeniixx <contact@phoeniixx.com>
 */
class Phoen_login_signup_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		// start login shortcode action 
		add_shortcode("lsphe-login-form",array($this,"phoen_add_login_shortcode"));
		add_shortcode("wp-login-form",array($this,"phoen_add_login_shortcode"));

		// start registration shortcode action 
		add_shortcode("lsphe-signup-form",array($this,"phoen_add_signup_shortcode"));
		add_shortcode("wp-signup-form",array($this,"phoen_add_signup_shortcode"));

		//popup login and sign-up action
		add_shortcode("lsphe-header",array($this,"phoen_login_signup_header_shortcode"));
		add_shortcode("wp-header",array($this,"phoen_login_signup_header_shortcode"));

		// ajax header signup
		add_action( 'wp_ajax_val_header', array($this,'header_validate' ));
		add_action( 'wp_ajax_nopriv_val_header', array($this,'header_validate' ));

		// ajax header signup
		add_action( 'wp_ajax_val_header_signup', array($this,'header_validate_signup' ));
		add_action( 'wp_ajax_nopriv_val_header_signup', array($this,'header_validate_signup' ));

		// header login 
		add_action('wp_head',array($this,'phoen_header_login'));

		$auto_load_popupp_home_page = get_option('_lsphe_auto_load_popup');

		if($auto_load_popupp_home_page == 'on'){

			add_action('wp_head', array($this,'phoen_login_signup_auto_load_page'));
		}

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Phoen_login_signup_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Phoen_login_signup_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/phoen_login_signup-public.css', array(), $this->version, 'all' );

		wp_enqueue_style( 'style-colorbox', PLUGINlSPDIRURL.'public/css/style.css' );

		wp_enqueue_style( 'style-popup-form', PLUGINlSPDIRURL.'public/css/phoen_login_signup_style.css' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Phoen_login_signup_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Phoen_login_signup_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/phoen_login_signup-public.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script("phoen-login-signup-js",PLUGINlSPDIRURL."public/js/phoen_login_signup_script.js",array('jquery'),'',true);

		wp_enqueue_script("phoen-login-signup-colorbox",PLUGINlSPDIRURL."public/js/jquery.colorbox.js",array('jquery'),'',true);

	}

	// single login shortcode 
	public function phoen_add_login_shortcode() {

		if(!is_user_logged_in()){ 

			ob_start();
				include_once(PLUGINlSPDIRPATH.'public/phoen-login-signup-form/shortcode/lsphe-login-form.php');
			$content = ob_get_contents();
			ob_end_clean();
	   			echo $content;

		}

	}

	// single registration shortcode
	public function phoen_add_signup_shortcode(){

		if(!is_user_logged_in()){ 

			ob_start();
				include_once(PLUGINlSPDIRPATH.'public/phoen-login-signup-form/shortcode/lsphe-signup-form.php');
			$content = ob_get_contents();
			ob_end_clean();
	   			echo $content;

		}
	}

	/* login and registration header shortcode on click */
	public function phoen_login_signup_header_shortcode(){

		if(!is_user_logged_in()){ 

			ob_start();
				include_once(PLUGINlSPDIRPATH.'public/phoen-login-signup-form/shortcode/lsphe-header.php');
			$content = ob_get_contents();
			ob_end_clean();
	   			echo $content;
		
		}else{

			$user_obj = wp_get_current_user(); ?>

			<p><span class="phoe-span-1">Hello</span> <strong><?php echo $user_obj->user_login; ?></strong> <span class="phoe-span-2">(not <?php echo $user_obj->user_login; ?> </span> 
			  <a href="<?php echo wp_logout_url( get_permalink() );  ?>">Sign out</a> <span class="phoe-span-3">). </span>
			</p><?php
		}
	}

	
	public function phoen_header_login(){

		ob_start();
			include_once(PLUGINlSPDIRPATH.'public/phoen-login-signup-form/classes/phoen_header_login.php');
		$content = ob_get_contents();
		ob_end_clean();
   			echo $content;

   		$object = new Phoen_login_signup_class_form();

		if (!is_user_logged_in()){ 

			$object->phoen_login_signup_show_both_page();

		}else{

			$object->phoen_login_signup_user_not_logged_in();

		}
	}

	/* this function using for auto load login and registration */

	public function phoen_login_signup_auto_load_page(){
        
        if ( is_front_page() ) {

			wp_localize_script( 'login-signup-js', 'woo_log_ajaxurl', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
			
	        
        	if (!is_user_logged_in()){

        		ob_start();
					include_once(PLUGINlSPDIRPATH.'public/phoen-login-signup-form/phoen_login_signup_auto_load.php');
				$content = ob_get_contents();
				ob_end_clean();
		   			echo $content;

	       }
	    }
	}

	/* this function is validation of login page via ajax */

	public function header_validate(){

		if ( !is_user_logged_in() ) {  
			
			global $wpdb;
									
			$username = isset($_POST['username'])?$wpdb->escape(sanitize_text_field($_POST['username'])):'';
			
			$password = isset($_POST['password'])?$_POST['password']:'';
			
			$checkbox_terms = isset($_POST['checkbox_terms'])?$wpdb->escape(sanitize_text_field($_POST['checkbox_terms'])):'off';
			
		
			if($username == ''){
				
				echo $this->phoen_login_signup_print_error('<strong>Error : </strong> Username is Required Field');
			}
			else if($password == ''){
				
				echo $this->phoen_login_signup_print_error('<strong>Error : </strong> Password is Required Field');
			}
			else if($checkbox_terms  != 'on' && get_option('_lsphe_enable_tncond') =='on'){

				echo $this->phoen_login_signup_print_error('<strong>Error : </strong> Terms & Condition is Required Field');
			}
			else{				
					
				if(is_email($username)){
					
					$user= get_user_by('email',$username);
					
					if($user){
						
						if(wp_check_password( $password, $user->user_pass)){
						   
						   echo "1";	
						    
						   wp_set_current_user( $user->ID, $user->user_login );
						   
						   wp_set_auth_cookie( $user->ID );
						   
						   do_action( 'wp_login', $user->user_login,$user );
						   
						   exit;
						}
						else{

							$print = "<strong>Error : </strong> The password you have entered for <strong>".$user->user_login." </strong> is incorrect.";

							echo $this->phoen_login_signup_print_error($print);
						}	
						
					}
					else{
						
						echo $this->phoen_login_signup_print_error('<strong>Error : </strong> A user could not be found with this email address.');	 
					}						
					
				}
				else{
				
					$login_data = array();
					
					$login_data['user_login'] = $username;
					
					$login_data['user_password'] = $password;
					
					$login_data['remember'] = $remember;
					
					$user_verify = wp_signon($login_data,false);  
					 
					if (is_wp_error($user_verify)){
						
						echo $this->phoen_login_signup_print_error($user_verify->get_error_message());
					}
					else{ 

						echo "1";
					  
						wp_set_current_user( $user_verify->ID, $user_verify->user_login );
					    
						wp_set_auth_cookie( $user_verify->ID );
					    
						do_action( 'wp_login', $user_verify->user_login ,$user);
					    
					    exit;
					
					} 
				
				}      
			
			}
			exit;
		}
		else{

			echo $this->phoen_login_signup_print_error('<strong>Error:</strong> A user already loged in, Logout First.');
		}
	}

	/* this function is validation of registration page via ajax */

	public function header_validate_signup(){

		/* check user is logged in or not */
		if (!is_user_logged_in()){ 

			$lsphe_registration_styling_setting = get_option( '_lsphe_registration_setting_style');
	
			$registrated_email = isset($_POST['email'])?sanitize_email($_POST['email']):'';
	
			$registrated_password = isset($_POST['password'])? sanitize_text_field($_POST['password']):'';

			$registrated_first_name = isset($_POST['fname'])? sanitize_text_field($_POST['fname']):'';

			$registrated_last_name = isset($_POST['lname'])? sanitize_text_field($_POST['lname']):'';
			
			$get_user_name = explode("@",$registrated_email);  
			
			$temp = $get_user_name[0];
			
			$user = get_user_by( 'email',$registrated_email );

			

			if($lsphe_registration_styling_setting['lsphe_show_first_name_label'] == '1' && $registrated_first_name == ''){

				echo $this->phoen_login_signup_print_error('<strong>Error :</strong> First Name is Required Field.');

			}
			
			else if($lsphe_registration_styling_setting['lsphe_show_last_name_label'] == '1' && $registrated_last_name == ''){

				echo $this->phoen_login_signup_print_error('<strong>Error :</strong> Last Name is Required Field.');
			}
						   
			else if($registrated_email == ''){
				
				echo $this->phoen_login_signup_print_error('<strong>Error :</strong> Please provide a valid email address.');
			}
		   
		    else if($registrated_password == ''){
	
				echo $this->phoen_login_signup_print_error('<strong>Error :</strong> Please enter an account password.');
		    }

			else{
			   
				if(is_email($registrated_email)){ 
					
					if($user->user_email == $registrated_email){
						
						echo $this->phoen_login_signup_print_error('<strong>Error :</strong> An account is already registered with your email address. Please login.');
					}
					else{             
						
						if ( 'yes' === get_option( 'woocommerce_registration_generate_password' ) && empty( $registrated_password ) ) {
									$registrated_password = wp_generate_password();
									$password_generated = true;

							} elseif ( empty( $registrated_password ) ) {
								return new WP_Error( 'registration-error-missing-password', __( 'Please enter an account password.', 'woocommerce' ) );

							} else {

								$password_generated = false;
							}
								
						$userdata = array(
										"role"=>"customer",
							
										"user_email"=>$registrated_email,
										
										"user_login"=>$temp,
										
										"user_pass"=>$registrated_password
									);
						
						if($user_id = wp_insert_user( $userdata )){
							
							echo "1";
							
							do_action('woocommerce_created_customer', $user_id, $userdata, $password_generated);
							
							$user1 = get_user_by('id',$user_id);
							
							wp_set_current_user( $user1->ID, $user1->user_login );
							
							wp_set_auth_cookie( $user1->ID );
							
							do_action( 'wp_login', $user1->user_login ,$user1);
							
							exit;		 
						}
						
					}
				}
				else{
					
					echo $this->phoen_login_signup_print_error('<strong>Error :</strong> Please provide a valid email address.');
				} 
			
			}		
			
			exit;
		}
		else{
			
			echo $this->phoen_login_signup_print_error('<strong>Error:</strong> A user already loged in, Logout First.');
		}

	}

	/* this function using for showing error */

	public function phoen_login_signup_print_error($value){ ?>
		
		<style>	
		.toast{
		  	position: relative;
		    top: -14px;
		    bottom: 0;
		    left: -27px;
		    width: 100%;
		    border-radius: 0px;
		    box-shadow: #310808 1px 1px 5px;
		    background-color: #231f1f;;
		    padding: 1px;
		    color: white;
		    opacity: 1;
		    animation: toast 500ms cubic-bezier(.23,.82,.16,1.46);
		    animation-iteration-count: 1;
		    font-size: 14px;
		}
		@keyframes toast{
		  0%{
		    opacity:0;
		    transform: translateY(0px);
		  }

		  100%{
		    opacity:1;
		    transform: translateY(0px);
		  }
		}
		</style> <?php

		$message = "<span class='toast'>". $value ."</span>";

		return $message;
	}

	public function phoen_login_signup_error_message($value){

		$message = "<table style='padding: 20px;background-color: #f44336;color: white;'><tr><td>".$value."</td></tr></table>";

		return $message;
	}

}// class Phoen_login_signup_Public closed here
