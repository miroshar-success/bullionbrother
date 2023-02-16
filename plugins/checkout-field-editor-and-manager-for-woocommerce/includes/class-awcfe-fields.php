<?php

if (!defined('ABSPATH'))
    exit;


class AWCFE_Fields
{


    private static $_instance = null;

    public $_version;

    public $itemSchema = array(
        'required' => array( // common field that exists for all type of fields
            'type' => 'text',
            'label' => '',
            'custom' => false,
            'active' => true,
            'placeholder' => '',
            'show_in_email' => false,
            'show_in_order_page' => true,
            'class' => array(),
            'priority' => '',
            'elementId' => '',
            'col' => 6,
            'default' => '',
            'custom_class' => '',
        ),
        'optional' => array( // fields which wont be existing for all type of fields
            'name' => '',
            'required' => false,
            'bindingKey' => '',
            'description' => '',
            'maxlength' => '',
            'subtype' => '',
            'rows' => '',
            'options' => array(
                'value' => '',
                'label' => '',
                'selected' => false
            ),
            'min' => '',
            'max' => '',
            'step' => '',
            'makeinline' => false,
            'is_checked' => false,
        )
    );

    public function __construct()
    {

    }

    public function saveFields($request_body)
    {


        $fields = $request_body['fields'];
        $validation = true;
        // now we can actually save the data
        $allowed = array(
            'a' => array(// on allow a tags
                'href' => true, // and those anchors can only have href attribute
                'target' => true,
                'class' => true,// and those anchors can only have href attribute
                'style' => true
            ),
            'b' => array('style' => true, 'class' => true),
            'strong' => array('style' => true, 'class' => true),
            'i' => array('style' => true, 'class' => true),
            'img' => array('style' => true, 'class' => true, 'src' => true),
            'span' => array('style' => true, 'class' => true),
            'p' => array('style' => true, 'class' => true)
        );
        if ($fields && is_array($fields)) {
            foreach ($fields as $secKey => $section) {
                if (isset($section['fields'][0][0])) {  // check if rowXcol arrange ment
                    foreach ($section['fields'] as $row => $col) {
                        foreach ($col as $colIndex => $val) {

                            $filteredValue = [];
                            foreach (array_merge($this->itemSchema['required'], $this->itemSchema['optional']) as $k => $v) {
                                if (isset($val[$k])) {
                                    $submitedVal = $val[$k];

                                    if ($k === 'label' && $val['type'] === 'paragraph') {
                                        $filteredValue[$k] = wp_kses($submitedVal, $allowed);
                                    } else if ($k === 'options') {
                                        $options = array();
                                        foreach ($submitedVal as $kkey => $option) {
                                            // array_push($options,
                                            //     array(
                                            //         'value' => wp_kses($option['value'], array()),
                                            //         'label' => wp_kses($option['label'], array()),
                                            //         'selected' => (isset($option['selected']) && $option['selected'] === true) ? true : false
                                            //     ));

                                          if(  !empty($option['label']) ){
                                            array_push($options,
                                                array(
                                                    'value' => wp_kses($option['value'], array()),
                                                    'label' => wp_kses($option['label'], $allowed),
                                                    'selected' => (isset($option['selected']) && $option['selected'] === true) ? true : false
                                                ));
                                          } else {

                                            array_push($options,
                                                array(
                                                    'value' => wp_kses($kkey, array()),
                                                    'label' => wp_kses($option, $allowed),
                                                    'selected' => (isset($option['selected']) && $option['selected'] === true) ? true : false
                                                ));
                                          }

                                        }
                                        $filteredValue[$k] = $options;
                                    } else if (is_bool($v)) {
                                        $filteredValue[$k] = ($submitedVal === true) ? true : false;
                                    } else if (is_string($v)) {
                                        $filteredValue[$k] = wp_kses($submitedVal, array());
                                    } else if (is_numeric($v)) {
                                        $filteredValue[$k] = $submitedVal;
                                    } else if (is_array($v)) {
                                        $options = array();
                                        foreach ($submitedVal as $option) {
                                            array_push($options, wp_kses($option, array()));
                                        }
                                        $filteredValue[$k] = $options;
                                    }
                                }


                            }
                            if(isset($filteredValue['custom']) && $filteredValue['custom']){
                                if(substr( $filteredValue['name'], 0, strlen($secKey)+1 ) !== $secKey.'_'){
                                    $filteredValue['name'] = $secKey.'_'.$filteredValue['name'];
                                }

                            }
                            $fields[$secKey]['fields'][$row][$colIndex] = $filteredValue;
                        }


                    }
                } else {
                    $fields[$secKey]['fields'][0] = [];
                }
            }

        } else {
            $fields = [];
        }


        $my_post = array(
            'fields' => $fields
        );


        $ml = new AWCFE_Ml();
        $currentLang = false;
        if ($ml->is_active()) {
            $isDefault = false;
            if (isset($request_body['ml'])) {
                $mlData = $request_body['ml'];
                if (isset($mlData['currentLang']) && $mlData['currentLang'] !== ''  && $mlData['currentLang'] !== 'all') {
                    $currentLang = $mlData['currentLang'];
                }
                if (isset($mlData['isDefault']) && $mlData['isDefault'] !== '') {
                    $isDefault = $mlData['isDefault'];
                }
            }
            if ($currentLang) {
                update_option(AWCFE_FIELDS_KEY . '_' . $currentLang, $my_post);
                if ($isDefault) {
                    update_option(AWCFE_FIELDS_KEY, $my_post);
                }
            } else {
                update_option(AWCFE_FIELDS_KEY, $my_post);
            }
        } else {
            update_option(AWCFE_FIELDS_KEY, $my_post);
        }


        return $fields;

    }

    /**
     *
     */
    public function getFields($defaultFields = [])
    {

        $ml = new AWCFE_Ml();
        $currentLang = false;
        if ($ml->is_active()) {
            if (defined('REST_REQUEST') && REST_REQUEST) {
                if (isset($_GET['ml'])) {
                    $mlData = json_decode(stripslashes($_GET['ml']));
                    if (isset($mlData->currentLang) && $mlData->currentLang !== '' && $mlData->currentLang !== 'all') {
                        $currentLang = $mlData->currentLang;
                    }
                }
            } else {
                $currentLang = $ml->current_language();
            }
        }


        $customSections = get_option(AWCFE_FIELDS_KEY);
        // error_log(print_r( $customSections, true));
        if ($customSections && isset($customSections['fields'])) {
            $customSections = $customSections['fields'];
        } else {
            $customSections = [];
        }

        if ($currentLang) {
            $customSections_ml = get_option(AWCFE_FIELDS_KEY . '_' . $currentLang);
            if ($customSections_ml && isset($customSections_ml['fields'])) {
                $customSections_ml = $customSections_ml['fields'];
            } else {
                $customSections_ml = false;
            }
            $customSections_ml_ids = [];
            if ($customSections_ml) {
                foreach ($customSections_ml as $sectionKey => $section) {
                    foreach ($section['fields'] as $row => $col) {
                        foreach ($col as $i => $val) {
                            $customSections_ml_ids[$val['elementId']] = $val;
                        }
                    }
                }
                foreach ($customSections as $sectionKey => $section) {
                    foreach ($section['fields'] as $row => $col) {
                        foreach ($col as $i => $val) {
                            if (isset($customSections_ml_ids[$val['elementId']])) {
                                $customSections[$sectionKey]['fields'][$row][$i] = $ml->merge_data($val, $customSections_ml_ids[$val['elementId']]);
                            }
                        }
                    }
                }
            }

        }

        $customSections = $this->spreadRowsCols($customSections);

        foreach ($defaultFields as $section => $fields) {
            if (isset($customSections[$section])) {
                $this->syncWithDefault($customSections[$section], $fields);
            } else {

                $customSections[$section] = $fields;

            }

            // uasort($customSections[$section], 'wc_checkout_fields_uasort_comparison');
        }

        if (defined('REST_REQUEST') && REST_REQUEST) {
            $customSections = $this->fieldsToRowCol($customSections);
            if( is_array($customSections) ){
              unset($customSections['account']);
              unset($customSections['terms_condition']);
            }

            return $customSections;

        } else {

            $customSections = array_map(
                function ($section) {
                    $newArr = $section;

                    foreach ($section as $key => $field) {

                        if (isset($field['active']) && $field['active'] === false) {

                          if( $field['type'] == 'country' ){

                            $newArr[$key]['type'] = 'hidden';
                            $newArr[$key]['required'] = false;
                            $custom_class = array('awcfe-hidden');
                            $newArr[$key]['class'] = @array_merge($newArr[$key]['class'], $custom_class);


                          } else {
                            unset($newArr[$key]);
                          }

                        } else {

                            if (isset($field['custom_class'])) {
                                $custom_class = preg_split('/[\ \n\,]+/', $field['custom_class']);
                                $newArr[$key]['class'] = @array_merge($newArr[$key]['class'], $custom_class);
                            }

                            $makeinline = ( isset($field['makeinline']) && $field['makeinline'] == 1 ) ? array('awcfe-inline-item') : array();
							if (isset($newArr[$key]['class'])) {
								$newArr[$key]['class'] = array_merge($newArr[$key]['class'], $makeinline);
							}

                            if (isset($field['type'])) {
                                if ($field['type'] === 'tel') {
                                    $newArr[$key]['validate'] = array_merge(isset($newArr[$key]['validate']) ? $newArr[$key]['validate'] : [], ['phone']);
                                }
                                if ($field['type'] === 'email') {
                                    $newArr[$key]['validate'] = array_merge(isset($newArr[$key]['validate']) ? $newArr[$key]['validate'] : [], ['email']);
                                }
                                if ($field['type'] === 'url') {
                                    $newArr[$key]['validate'] = array_merge(isset($newArr[$key]['validate']) ? $newArr[$key]['validate'] : [], ['url']);
                                }
                                //
                            }
                            if (isset($field['options']) && is_array($field['options'])) {

                              if(!empty($field['name']) && ( $field['name'] == 'billing_address_book' || $field['name'] == 'shipping_address_book' ) ){

								  $sect_typ = '';
								  if( $field['name'] == 'billing_address_book' ) {
									  $sect_typ = 'billing';
								  } else if( $field['name'] == 'shipping_address_book' ) {
									  $sect_typ = 'shipping';
								  }
                                unset($newArr[$key]['options']);
                                $addrOpts = $this->awcfe_getAddressBook_plugin($sect_typ);
                                if( !empty( $addrOpts )){
                                  foreach($addrOpts as $ka => $va){
                                    $newArr[$key]['options'][$ka] = $va;
                                  }
                                }
                              } else {
                                $newArr[$key]['options'] = [];
                                foreach ($field['options'] as $option) {
                                    @$newArr[$key]['options'][$option['value']] = @$option['label'];
                                    if (isset($option['selected']) && $option['selected'] === true) {
                                        $newArr[$key]['default'] = $option['value'];
                                    }
                                }

                              }
                            }
                            if (isset($field['rows'])) {
                                $newArr[$key]['custom_attributes'] = array_merge(isset($newArr[$key]['custom_attributes']) ? $newArr[$key]['custom_attributes'] : [], ['rows' => $field['rows']]);
                            }
                        }


                    }

                    return $newArr;

                },
                $customSections
            );
            return $customSections;
        }


    }

    public function spreadRowsCols($sections)
    {

        $sections = array_map(
            function ($section) {
                $newAr = array();
                if (isset($section['fields'][0][0])) {// check if rowXcol arrange ment
                    foreach ($section['fields'] as $row => $col) {
                        foreach ($col as $i => $v) {
                            // check if disabled and exclude
                            if (isset($v['name'])) {
                                $key = $v['name'];
                            } else if (isset($v['elementId'])) {
                                $key = $v['elementId'];
                            } else {
                                continue;
                            }
                            if (empty($key)) {
                                continue;
                            }
                            $index = $i;
                            $newAr[$key] = $v;
                            $newAr[$key]['class'] = $this->generateClass($index, $v);
                            if ($v['type'] === 'text') {
                                $newAr[$key]['type'] = $v['subtype'];
                            }

                        }

                    }
                }


                return $newAr;
            },
            $sections
        );

        return $sections;
    }

    public function generateClass($index, $field)
    {

        $classes = [
            'form-row-wide' => 6,
            'form-row-last' => 3,
            'form-row-first' => 3
        ];
        if ($field['col'] === 6) {
            $class = 'form-row-wide';
        } else {
            if ($index > 0) {
                $class = 'form-row-last';
            } else {
                $class = 'form-row-first';
            }
        }
        $classes = array();
        if (isset($field['class']) && is_array($field['class'])) {
            $classes = $field['class'];
        } else if (isset($field['class'])) {
            $classes = [$field['class']];
        }

        foreach ($classes as $c => $v) {
            unset($classes[$c]);
        }

        return array_merge($classes, [$class]);

    }

    public function syncWithDefault(&$customSection, $defaultSectionFields)
    {
        //check if this key is existing in $result
        foreach ($defaultSectionFields as $key => $val) {
            $hasField = false;
            foreach ($customSection as $item) {
                if (isset($item['bindingKey']) && $key === $item['bindingKey']) {
                    $hasField = true;
                    $customSection[$key] = array_merge($val, $customSection[$key]);// will overite the keys in $val with, and will append if any thing new
                    if( !empty($val['class']) ){
                      $avail_class = $val['class'];
                    } else {
                      $avail_class = array();
                    }
                    //$avail_class = $val['class'];
                    $chk_class = array('form-row-wide','form-row-first','form-row-last');
                    $nw_class = array_diff($avail_class,$chk_class);
                    $customSection[$key]['class'] = @array_unique(array_merge($customSection[$key]['class'], $nw_class));
                    break;
                }
            }
            if (!$hasField) {
                $customSection[$key] = $val;//$this->newItemWooToCustom($val, $key);

            }
        }

        foreach ($customSection as $k => $val) {
            $hasField = false;
            if (isset($val['bindingKey']) && !empty($val['bindingKey'])) {
                foreach ($defaultSectionFields as $key => $item) {

                    if ($key === $val['bindingKey']) {
                        $hasField = true;
                        break;
                    }
                }
                if (!$hasField) {
                    unset($customSection[$k]);

                }
            }

        }

    }

    public function fieldsToRowCol($sections)
    {
        $newArray = array();
        foreach ($sections as $section => $fields) {
            $newArray[$section] = array(
                "extra" => [
                    'key' => $section,
                    'name' => $this->getSectionDefaultTitle($section),
                    'status' => 1,

                ],
                "fields" => []
            );
            uasort($fields, 'wc_checkout_fields_uasort_comparison');
            $row = 0;
            $col = 0;
            foreach ($fields as $key => $val) {
				if( $key == 'ws_opt_in' ){
					continue;
				}
                $newItem = $this->newItemWooToApi($val, $key);
                $newArray[$section]['fields'][$row][] = $newItem;
                if ($newItem['col'] === 6 || $col > 0) {
                    $row++;
                    $col = 0;
                } else if ($newItem['col'] === 3) {
                    $col++;
                }

            }

        }
        return $newArray;
    }

    /**
     * @param $customSection
     * @param $defaultSectionFields
     */
//    public function syncWithDefault(&$customSection, $defaultSectionFields)
//    {
//
//        //check if this key is existing in $result
//        foreach ($defaultSectionFields as $key => $val) {
//            $hasField = false;
//
//            foreach ($customSection['fields'] as $row) {
//                foreach ($row as $item) {
//                    if (isset($item['bindingKey']) && $key === $item['bindingKey']) {
//                        $hasField = true;
//                        break;
//                    }
//                }
//                if ($hasField) {
//                    break;
//                }
//            }
//            if (!$hasField) {
//                $this->appendNewItem($customSection['fields'], $val, $key);
//
//            }
//        }
//
//        foreach ($customSection as $row => $col) {
//            foreach ($col as $k => $val) {
//                $hasField = false;
//                if (isset($val['bindingKey']) && !empty($val['bindingKey'])) {
//                    foreach ($defaultSectionFields as $key => $item) {
//                        if ($key === $val['bindingKey']) {
//                            $hasField = true;
//                            break;
//                        }
//                    }
//                    if (!$hasField) {
//                        unset($customSection['fields'][$row][$k]);
//
//                    }
//                }
//            }
//
//        }
//
//
//    }
    /**
     * @param $section
     * @return string|void
     */
    public function getSectionDefaultTitle($section)
    {
//        if ($section === 'billing') {
//            return __('Billing Fields', 'woocommerce');
//        } else if ($section === 'shipping') {
//            return __('Shpping Fields', 'woocommerce');
//        } else {
        // return __(ucfirst($section) . ' Fields', 'aco-checkout-field');
        return __(ucfirst($section), 'woocommerce') . ' '. __('Fields', 'checkout-field-editor-and-manager-for-woocommerce');
//        }
    }

    /**
     *
     */
    public function newItemWooToApi($item, $key)
    {
        $args = wp_parse_args($item, $this->itemSchema['required']);
        $newField = $args;

        foreach ($this->itemSchema['optional'] as $name => $val) {
            if (isset($args[$name])) {
                $newField[$name] = $args[$name];
            }
        }

        $newField['name'] = $key;
        $newField['col'] = $this->colFromClass($args['class']);

        switch ($args['type']) {
            case 'text':
            case 'password':
            case 'datetime':
            case 'datetime-local':
            case 'date':
            case 'month':
            case 'time':
            case 'week':
            case 'number':
            case 'email':
            // case 'url':
            case 'tel':
            case 'hidden':
                $newField['type'] = 'text';
                $newField['subtype'] = $args['type'];
                break;
            default:
                $newField['type'] = $args['type'];
        }

        if (!isset($args['custom']) || $args['custom'] === false) {
            $newField['bindingKey'] = $key;
            $newField['show_in_email'] = true;
            $newField['show_in_order_page'] = true;
        }

        if (isset($item['custom_attributes']['rows'])) {
            $newField['rows'] = $item['custom_attributes']['rows'];
        }


        return $newField;

    }


    function colFromClass($class)
    {
        $classes = [
            'form-row-wide' => 6,
            'form-row-last' => 3,
            'form-row-first' => 3
        ];
        foreach ($classes as $key => $val) {
            if (is_array($class) && in_array($key, $class)) {
                return $val;
            } else if (is_string($class) && $class == $key) {
                return $val;
            }
        }
        return 6;
    }

    public function getDefaultFields()
    {
        if (WC()->customer == null) {
            wc()->frontend_includes();
            WC()->session = new WC_Session_Handler();
            WC()->session->init();
            WC()->customer = new WC_Customer(get_current_user_id(), true);
        }


        $checkout_fields = WC()->checkout()->get_checkout_fields();

        return $checkout_fields;
    }




        /* Address book plugin */

    function awcfe_getAddressBook_plugin( $type ){

      if ( is_user_logged_in() ) {
      $user_id = get_current_user_id();
	  // $address_names = get_user_meta( $user_id, 'wc_address_book', true );
	  $address_names = get_user_meta( $user_id, 'wc_address_book_' . $type, true );
      if( ! empty( $address_names ) ){
        $countries = new WC_Countries();
        if ( ! isset( $country ) ) { $country = $countries->get_base_country(); }
        $address_fields = WC()->countries->get_address_fields( $country, 'shipping_' );
        $address_keys = array_keys( $address_fields );
        foreach ( $address_names as $name ) {
            if ( 'billing' === $name ) { continue; }
           $address = array();
            foreach ( $address_keys as $field ) {
              $field = str_replace( 'shipping', '', $field );
              $address[ $name . $field ] = get_user_meta( $user_id, $name . $field, true );
            }
            $address_book[ $name ] = $address;
        }

        $addrPlug_optns = array();
        if ( ! empty( $address_book ) && false !== $address_book ) {
          foreach ( $address_book as $name => $address ) {
            if ( ! empty( $address[ $name . '_address_1' ] ) ) {
              $addrPlug_optns[ $name ] = $this->address_select_label( $address, $name );
            }
          }
        }

      }

      $addrPlug_optns['add_new'] = __( 'Add New Address', 'woo-address-book' );

      return $addrPlug_optns;
      }

    }

    public function address_select_label( $address, $name ) {
      $label = '';

      $address_nickname = get_user_meta( get_current_user_id(), $name . '_address_nickname', true );
      if ( $address_nickname ) {
        $label .= $address_nickname . ': ';
      }

      if ( ! empty( $address[ $name . '_first_name' ] ) ) {
        $label .= $address[ $name . '_first_name' ];
      }
      if ( ! empty( $address[ $name . '_last_name' ] ) ) {
        if ( ! empty( $label ) ) {
          $label .= ' ';
        }
        $label .= $address[ $name . '_last_name' ];
      }
      if ( ! empty( $address[ $name . '_address_1' ] ) ) {
        if ( ! empty( $label ) ) {
          $label .= ', ';
        }
        $label .= $address[ $name . '_address_1' ];
      }
      if ( ! empty( $address[ $name . '_city' ] ) ) {
        if ( ! empty( $label ) ) {
          $label .= ', ';
        }
        $label .= $address[ $name . '_city' ];
      }
      if ( ! empty( $address[ $name . '_state' ] ) ) {
        if ( ! empty( $label ) ) {
          $label .= ', ';
        }
        $label .= $address[ $name . '_state' ];
      }

      return apply_filters( 'wc_address_book_address_select_label', $label, $address, $name );
    }





}
