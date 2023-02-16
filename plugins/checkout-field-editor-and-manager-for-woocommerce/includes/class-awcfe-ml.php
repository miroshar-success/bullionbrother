<?php

if (!defined('ABSPATH'))
    exit;

class AWCFE_Ml
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
    public $default_lang;
    public $current_lang;
    private $_active = false;

    public function __construct()
    {

        if (class_exists('SitePress')) {
            $this->_active = 'wpml';
            $this->default_lang = apply_filters('wpml_default_language', NULL);
            $this->current_lang = apply_filters('wpml_current_language', NULL);
        } else if (defined('POLYLANG_VERSION')) {
            $this->_active = 'polylang';
            $this->default_lang = pll_default_language();
            $this->current_lang = pll_current_language();
        }
    }

    /**
     *
     *
     * Ensures only one instance of WCPA is loaded or can be loaded.
     *
     * @since 1.0.0
     * @static
     * @see WordPress_Plugin_Template()
     * @return Main WCPA instance
     */
    public static function instance($file = '', $version = '1.0.0')
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self($file, $version);
        }
        return self::$_instance;
    }

    public function is_active()
    {
        return $this->_active !== false;
    }

    public function is_new_post($post_id)
    {
        if ($this->base_form($post_id) === 0) {
            if ($this->_active === 'wpml') {
                isset($_GET['trid']) ? false : true;
            } else if ($this->_active === 'polylang') {
                return isset($_GET['from_post']) ? false : true;

            }
        }
        return false;
    }

    public function base_form($post_id)
    {
        if ($this->_active === 'wpml') {
            $base_id = apply_filters('wpml_original_element_id', null, $post_id);
        } else if ($this->_active === 'polylang') {
            $base_id = pll_get_post($post_id, pll_default_language());
        }

        return (int)$base_id;
    }

    public function is_default_lan()
    {
        return ($this->current_lang === $this->default_lang);
    }

    public function is_duplicating($post_id)
    {
        if ($this->base_form($post_id) === 0) {
            if ($this->_active === 'wpml' && isset($_GET['trid'])) {
                return true;
            } else if ($this->_active === 'polylang' && isset($_GET['from_post'])) {
                return true;
            }
        }

        return false;
    }

    public function default_fb_meta($post_id)
    {
        $value = null;
        if ($this->_active === 'wpml') {
            $my_duplications = apply_filters('wpml_get_element_translations', null, $_GET['trid']);
            if (isset($my_duplications[$this->default_lang]->element_id)) {
                $value = get_post_meta($my_duplications[$this->default_lang]->element_id, WCPA_FORM_META_KEY, true);
            } else if (is_array($my_duplications)) {
                $value = get_post_meta(array_values($my_duplications)[0]->element_id, WCPA_FORM_META_KEY, true);
            }
        } else if ($this->_active === 'polylang') {
            $base_form = $this->base_form($_GET['from_post']);
            $value = get_post_meta($base_form, WCPA_FORM_META_KEY, true);

            return $value;
        }
        return $value;
    }

    public function default_language()
    {
        return $this->default_lang;
    }

    public function current_language()
    {
        return $this->current_lang;
    }


    public function merge_data($base_data, $trans_data)
    {
        $keys = array(
            'label',
            'description',
            'placeholder',
            'value',
            'wpml_sync'
        );
        $options = array(
            'label',
            'value',
            'image',
            'color',
            'tooltip'
        );
        foreach ($keys as $key => $val) {
            if (isset($trans_data[$val]) && !awcfe_empty($trans_data[$val])) {
                $base_data[$val] = $trans_data[$val];
            }
        }

        if (isset($trans_data['options']) && (!isset($trans_data['wpml_sync']) || !$trans_data['wpml_sync'])) { //$trans_data->values
            foreach ($trans_data['options'] as $k => $v) {  // $trans_data->values as $k=>$v ( )
                foreach ($options as $ke => $va) { //   0=>label, 1=>value,2=>image
                    if (isset($v[$va]) && !awcfe_empty($v[$va])) { // $trans_data->values items, $item->label, $item->value, so on
                        $base_data['options'][$k][$va] = $v[$va];
                    }
                }
            }
        }

        return $base_data;
    }

//    public function merge_settings($base_id, $tran_id)
//    {
//        $original = get_post_meta($base_id, WCPA_META_SETTINGS_KEY, true);
//        $trans = get_post_meta($tran_id, WCPA_META_SETTINGS_KEY, true);
//        $settings = [
//            'options_total_label' => 'text',
//            'options_product_label' => 'text',
//            'total_label' => 'text'
//        ];
//
//        foreach ($settings as $k => $v) {
//            if (isset($trans[$k]) && !awcfe_empty($trans[$k])) {
//                $original[$k] = $trans[$k];
//            }
//        }
//        return $original;
//    }

//    public function settings_to_wpml()
//    {
//        //   WCPA_SETTINGS_KEY
//
//        $settings = [
//            'options_total_label' => 'Options Price Label',
//            'options_product_label' => 'Product Price Label',
//            'total_label' => 'Total Label',
//            'add_to_cart_text' => 'Add to cart button text',
//            'fee_label' => 'Fee Label',
//            'price_prefix_label' => 'Price Prefix'
//        ];
//        //WMPL
//        /**
//         * register strings for translation
//         */
//        if (function_exists('icl_register_string')) {
//            foreach ($settings as $k => $v) {
//                icl_register_string(WCPA_TEXT_DOMAIN, false, wcpa_get_option($k));
//            }
//        }
////        if (function_exists('pll_register_string')) {
////            foreach ($settings as $k => $v) {
////                $string = wcpa_get_option($k);
////                if ($string !== '') {
////                    pll_register_string('wcpa_settings_' . $k, $string, WCPA_PLUGIN_NAME);
////                }
////            }
////        }
//
//
//        //\WMPL
//    }

    // public function settings_to_ml_poly()
    // {
    //     $settings = [
    //         'extra_fields' => 'Extra Fields',
    //         'awcfe_strings_shipping' => 'Shipping',
    //
    //
    //     ];
    //     if (function_exists('pll_register_string')) {
    //         foreach ($settings as $k => $v) {
    //             //$string = wcpa_get_option($k);
    //             //if ($string !== '') {
    //                 pll_register_string('awcfe_strings_' . $k, $v, AWCFE_PLUGIN_NAME);
    //             //}
    //         }
    //     }
    // }

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
