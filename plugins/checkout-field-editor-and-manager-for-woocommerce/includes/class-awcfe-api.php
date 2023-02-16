<?php

if (!defined('ABSPATH'))
    exit;

class AWCFE_Api
{

    /**
     * @var    object
     * @access  private
     * @since    1.0.0
     */
    private static $_instance = null;

    /**
     * The version number.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $_version;
    private $_active = false;

    public function __construct()
    {
        add_action('rest_api_init', function () {
            register_rest_route('awcfe/v1', '/fields/', array(
                'methods' => 'GET',
                'callback' => array($this, 'get_fields'),
                'permission_callback' => array($this, 'get_permission')
            ));

            register_rest_route('awcfe/v1', '/save/', array(
                'methods' => 'POST',
                'callback' => array($this, 'post_form'),
                'permission_callback' => array($this, 'get_permission')
            ));

            register_rest_route('awcfe/v1', '/awcfe_reset_all/', array(
                'methods' => 'POST',
                'callback' => array($this, 'awcfe_reset_all'),
                'permission_callback' => array($this, 'get_permission')
            ));
        });
    }

    /**
     *
     * Ensures only one instance of AWDP is loaded or can be loaded.
     *
     * @since 1.0.0
     * @static
     * @see WordPress_Plugin_Template()
     * @return Main AWDP instance
     */
    public static function instance($file = '', $version = '1.0.0')
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self($file, $version);
        }
        return self::$_instance;
    }


    function post_form($data)
    {
        $request_body = $data->get_params();

        $fieldObj = new AWCFE_Fields();
        $response = $fieldObj->saveFields($request_body);
        return new WP_REST_Response($response, 200);
    }

    function awcfe_reset_all($data) {
      $data = $data->get_params();

      update_option(AWCFE_FIELDS_KEY, '');

      $result['url'] = admin_url('admin.php?page=awcfe_admin_ui#/');
      $result['success'] = true;
      return new WP_REST_Response($result, 200);
  }


    /**
     * @param $data
     * @return WP_REST_Response
     * @throws Exception
     */
    function get_fields($data)
    {
        wc()->frontend_includes();
        WC()->session = new WC_Session_Handler();
        WC()->session->init();
        WC()->customer = new WC_Customer(get_current_user_id(), true);

        $checkout_fields = WC()->checkout()->get_checkout_fields();
        return new WP_REST_Response($checkout_fields, 200);


    }




    /**
     * Permission Callback
     **/
    public function get_permission()
    {
        if (current_user_can('administrator') || current_user_can('manage_woocommerce')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Cloning is forbidden.
     *
     * @since 1.0.0
     */
    public function __clone()
    {
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?'), $this->_version);
    }

    /**
     * Unserializing instances of this class is forbidden.
     *
     * @since 1.0.0
     */
    public function __wakeup()
    {
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?'), $this->_version);
    }

}
