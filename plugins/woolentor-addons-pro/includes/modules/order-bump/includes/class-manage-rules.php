<?php
namespace Woolentor\Modules\Order_Bump;

// If this file is accessed directly, exit
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Manage_Rules{
    protected static $_instance = null;
    
    /**
     * Instance
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Fetch available offers.
     */
    public function fetch_offers( ){
        $available_offers = array();
        $offers = get_posts( array(
            'post_type'      => 'woolentor-template',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'meta_query'     => array(
                array(
					'key'       => 'woolentor_template_meta_type',
					'value'     => 'order-bump',
					'compare'   => '=',
				),
            ),
        ) );

        if( !empty( $offers ) ){
            foreach( $offers as $offer ){
                // Ensure the offer qualifies for showing
                if ( $this->validate_order_bump( $offer->ID ) ){
                    $available_offers[ $offer->ID ] = $offer->post_title;
                }
            }
        }

        return $available_offers;
    }

    /**
     * Fetch offer by product id.
     */
    public function fetch_offer_by_product_id( $product_id ){
        // Availabel offers
        $available_offers = Manage_Rules::instance()->fetch_offers();

        foreach( $available_offers as $order_bump_id => $title ){
            $meta_data  = get_post_meta( $order_bump_id, '_woolentor_order_bump', true );
            $offer_product_id   = !empty( $meta_data['product'] ) ? $meta_data['product'] : 0; // Either simple, variable, variation or grouped product id

            if( $offer_product_id != $product_id ){
                continue;
            }

            return $order_bump_id;
        }

        return false;
    }


    /**
     * Ensure the offer qualifies for showing. If not, return false.
     * 
     * @param order_bump_id The ID of the order bump.
     */
    public function validate_order_bump( $order_bump_id ){
        $rules_meta     = get_post_meta( $order_bump_id, '_woolentor_order_bump_rules', true );
        $ignoe_rules    = isset( $rules_meta['ignore_rules'] ) ? $rules_meta['ignore_rules'] : false;

        if( $ignoe_rules ){
            return true;
        }

        if( $this->validate_general_step( $order_bump_id ) !== true ){
            return false;
        }

        $matched_group = $this->get_matched_rules_group( $order_bump_id );
        if( $matched_group ){
            return true;
        }

        return false; // No match found.
    }
    
    /**
     * Ensure the offer qualifies for showing. If not, return false.
     * 
     * @param order_bump_id The ID of the order bump.
     */
    public function validate_general_step( $order_bump_id ){
        $status 	         = get_post_status( $order_bump_id );
        $order_bump_meta     = get_post_meta( $order_bump_id, '_woolentor_order_bump', true );
        $offer_product_id    = !empty( $order_bump_meta['product'] ) ? $order_bump_meta['product'] : 0;
        $offer_product       = wc_get_product( $offer_product_id );
        $discount_amount     = !empty( $order_bump_meta['discount_amount'] ) ? $order_bump_meta['discount_amount'] : 0;

        // Show plaing text instead of the Quick Status change for the exteptions below.
		if( $status === 'future' ){
            return array(
                'status'              => __('Scheduled', 'woolentor-pro'),
                'case'                => 'scheduled',
                'message'             => __( 'This order bump will be activated on the selected date.', 'woolentor-pro' ),
            );
		}

        if( !$offer_product_id || !is_object($offer_product) ){
            return array(
                'status'              => __('Inactive', 'woolentor-pro'),
                'case'                => 'product_type_not_support',
                'message'             => __( 'Order bump is Inactive. Because the offer product is not set.', 'woolentor-pro' ),
            );
        }

        // Right now only support for simple, variable & variation product
        if( !in_array( $offer_product->get_type(), array('simple', 'variable', 'variation') ) ){
            return array(
                'status'              => __('Inactive', 'woolentor-pro'),
                'case'                => 'product_type_not_support',
                'message'             => __( 'Order bump is Inactive. Because the offer product type is not supported.', 'woolentor-pro' ),
            );
        }
        
        
        // If offer product or discount amount is not defined return false
        if( empty( $offer_product_id ) || empty( $discount_amount ) ){
            return array(
                'status'                      => __('Inactive', 'woolentor-pro'),
                'case'                        => 'discount_amount_not_defined',
                'message'                     => __( 'Order bump is Inactive. Because the Amount (Discount) is not set.', 'woolentor-pro' ),
            );
        }

        // If product is out of stock return false
        if( !$offer_product->is_in_stock() ){
            return array(
                'status'        => __('Inactive', 'woolentor-pro'),
                'case'          => 'out_of_stock',
                'message'       => __( 'Order bump is Inactive. Because the offer product is out-of-stock.', 'woolentor-pro' ),
            );
        }

        // If product is free
        $exclude_free_product = apply_filters('woolentor_order_bump_exclude_free_product', true ); // @todo - Add support to change from option.
        if( $offer_product->get_price() < 1 && $exclude_free_product ){
            return array(
                'status'        => __('Inactive', 'woolentor-pro'),
                'case'          => 'price_is_zero',
                'message'       => __( 'Order bump is Inactive. Because the offer product is a free product.', 'woolentor-pro' ),
            );
        }

        return true;
    }

    /**
     * Check if a condition group qualifies/matched.
     * 
     * @param int The order bump id.
     * @return array Matched rules group.
     */
    public function get_matched_rules_group( $order_bump_id ){
        $rules_meta     = get_post_meta( $order_bump_id, '_woolentor_order_bump_rules', true );
        $rules_group    = isset( $rules_meta['rules'] ) ? $rules_meta['rules'] : [];

        // Loop through each condition groups (AND / OR).
        foreach( $rules_group as $i => $group ){

            // If this condtion group qualifies/matched, the bump offer is valid dont' need to check other groups and return true.
            // If the gorup doesn't qualify, check the next group, because the groups are connected with OR.
            if( $this->check_condition_group( $group ) ){
                // @todo - Write debug log here.
                return $group;
            }
        }

        return array();
    }

    /**
     * Check if a condition group qualifies/matched.
     * 
     * @param group The group of rules to check.
     */
    public function check_condition_group( $group ){
        $mathced = true; // Assume the group qualifies.

        // Loop through each rules inside the group.
        foreach( $group as $rule ){

            // Skip rule that has not defined value.
            // If the rule has no value, skip this and check the next rule.
            if( empty($rule['value']) ){
                $mathced = false;
                continue;
            }

            switch( $rule['base'] ){
                case 'customer':
                    $mathced = $this->check_user( $rule );
                    break;

                case 'customer_user_role':
                    $mathced = $this->check_user_role( $rule );
                    break;

                case 'customer_login_status':
                    $mathced = $this->check_login_status( $rule );
                    break;

                case 'checkout_shipping_address_country':
                    $mathced = $this->check_country( $rule, 'shipping' );
                    break;
                
                case 'checkout_billing_address_country':
                    $mathced = $this->check_country( $rule, 'billing' );
                    break;
                
                case 'cart_items_categories':
                    $mathced = $this->check_categories( $rule );
                    break;

                case 'cart_items_products':
                    $mathced = $this->check_products( $rule );
                    break;
                
                case 'cart_applied_coupons':
                    $mathced = $this->check_coupons( $rule );
                    break;

                case 'cart_total':
                    $mathced = $this->check_cart_total( $rule );
                    break;

                case 'cart_subtotal':
                    $mathced = $this->check_cart_subtotal( $rule );
                    break;
            }
            
            // Check the current rule if it matched.
            // If the rule doesn't qualify, the group doesn't qualify.
            // Because the group is an AND group, if one rule doesn't qualify, the group doesn't qualify.
            if( !$mathced ){
                return false;
            }
        }

        return $mathced;
    }

    /**
     * Check current user login status.
     * 
     * @param rule The rule array.
     */
    public function check_login_status( $rule ){
        $is_logged_in = is_user_logged_in();

        $rule_name       = isset( $rule['base'] ) ? $rule['base'] : '';
        $rule_value      = isset( $rule['value'] ) ? $rule['value'] : 'logged_in';

        if( $rule_name === 'customer_login_status' ){
            if( $rule_value === 'logged_in' ){
                if( $is_logged_in ){
                    return true;
                }
            } elseif( $rule_value === 'not_logged_in' ){
                if( !$is_logged_in ){
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check if the current user is in the defined users or not.
     * 
     * @param rule The rule object.
     */
    public function check_user( $rule ){
        $current_user_id = get_current_user_id();

        $rule_name       = isset( $rule['base'] ) ? $rule['base'] : '';
        $rule_operator   = isset( $rule['operator'] ) ? $rule['operator'] : '';
        $rule_value      = isset( $rule['value'] ) ? $rule['value'] : array(); // Defined uses
        
        if( $rule_name === 'customer' ){
            // Check wheter the current user is in the defined users or not
            if( $rule_operator === 'any' ){

                if( in_array( $current_user_id, $rule_value ) ){
                    return true;
                }
                
            } elseif( $rule_name === 'none' ){

                if( !in_array( $current_user_id, $rule_value ) ){
                    return true;
                }
            }
        }
        
        return false;
    }

    /**
     * Check if the current user has any of the defined roles
     * 
     * @param rule The rule object.
     */
    public function check_user_role( $rule ){
        $user = wp_get_current_user();
        $user_roles = (array) $user->roles;

        $rule_name       = isset( $rule['base'] ) ? $rule['base'] : '';
        $rule_operator   = isset( $rule['operator'] ) ? $rule['operator'] : '';
        $rule_value      = isset( $rule['value'] ) ? $rule['value'] : array(); // Defined user roles

        if( $rule_name === 'customer_user_role' ){
            // Check if there is any common role between defined roles and user roles
            $common_roles = array_intersect( $rule_value, $user_roles );

            if( $rule_operator === 'any' ){
                if( is_array( $common_roles ) && count( $common_roles ) > 0  ){
                    return true;
                }
            } elseif( $rule_operator === 'none' ){
                if( is_array( $common_roles ) && count( $common_roles ) < 1  ){
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check Billing / Shipping country.
     * 
     * @param rule The rule array
     * @param fieldset_key The fieldset key, either 'billing' or 'shipping'
     */
    public function check_country( $rule, $fieldset_key ){
        if( $fieldset_key === 'billing' ){
            $country = WC()->customer->get_billing_country();
        } elseif( $fieldset_key === 'shipping' ){
            $country = WC()->customer->get_shipping_country();
        }
        
        $rule_name       = isset( $rule['base'] ) ? $rule['base'] : '';
        $rule_operator   = isset( $rule['operator'] ) ? $rule['operator'] : '';
        $rule_value      = isset( $rule['value'] ) ? $rule['value'] : array(); // Countries

        if( $rule_name === 'checkout_'. $fieldset_key .'_address_country' ){;
            if( $rule_operator === 'any' ){
                // Check if country is in the list
                if( in_array( $country, $rule_value ) ){
                    return true;
                }
            } elseif( $rule_operator === 'none' ){
                // Check if country is not in the list
                if( !in_array( $country, $rule_value ) ){
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check if both cart item categories and defined categories are matched or not.
     * 
     * @param rule The rule array
     */
    public function check_categories( $rule ){
        $rule_name       = isset( $rule['base'] ) ? $rule['base'] : '';
        $rule_operator   = isset( $rule['operator'] ) ? $rule['operator'] : '';
        $rule_value      = isset( $rule['value'] ) ? $rule['value'] : array(); // Defined categories

        if( $rule_name === 'cart_items_categories' ){
            $cart_items = WC()->cart->get_cart();
            $cart_categories = array();

            foreach( $cart_items as $cart_item ){
                $product_id = $cart_item['product_id'];
                $product_categories = get_the_terms( $product_id, 'product_cat' );

                if( !empty( $product_categories ) ){
                    foreach( $product_categories as $product_category ){
                        $cart_categories[] = $product_category->term_id;
                    }
                }
            }

            // Check if there is any common category between defined categories and cart categories
            $common_categories = array_intersect( $rule_value, $cart_categories );

            if( $rule_operator === 'any' ){
                if( is_array( $common_categories ) && count( $common_categories ) > 0  ){
                    return true;
                }
            } elseif( $rule_operator === 'all' ){
                // Check if both cart item categories and defined categories array values are equal
                if( count( $rule_value ) === count( $common_categories ) ){
                    return true;
                }
            } elseif( $rule_operator === 'none' ){
                if( is_array( $common_categories ) && count( $common_categories ) < 1  ){
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check if both cart item product and defined products are matched or not.
     * 
     * @param rule The rule array
     */
    public function check_products( $rule ){
        $rule_name       = isset( $rule['base'] ) ? $rule['base'] : '';
        $rule_operator   = isset( $rule['operator'] ) ? $rule['operator'] : '';
        $rule_value      = isset( $rule['value'] ) ? $rule['value'] : array(); // Defined products
        
        if( $rule_name === 'cart_items_products' ){
            $cart_items = WC()->cart->get_cart();
            $cart_products = array();
            foreach( $cart_items as $cart_item ){
                $cart_products[] = $cart_item['product_id'];
            }
            
            // Check if there is any common product between defined products and cart products
            $common_products = array_intersect( $rule_value, $cart_products );
            
            if( $rule_operator === 'any' ){
                if( is_array( $common_products ) && count( $common_products ) > 0  ){
                    return true;
                }
            } elseif( $rule_operator === 'all' ){
                // Check if both cart item products and defined products array values are equal
                if( count( $rule_value ) === count( $common_products ) ){
                    return true;
                }
            } elseif( $rule_operator === 'none' ){
                if( is_array( $common_products ) && count( $common_products ) < 1  ){
                    return true;
                }
            }
        }
        
        return false;
    }

    /**
     * Check if both cart applied coupons and defined coupons are matched or not.
     * 
     * @param rule The rule array
     */
    public function check_coupons( $rule ){
        $rule_name       = isset( $rule['base'] ) ? $rule['base'] : '';
        $rule_operator   = isset( $rule['operator'] ) ? $rule['operator'] : '';

        // Defined coupons
        if( isset( $rule['value'] ) && is_array( $rule['value'] ) ){
            $rule_value = array_map( function($value){
                return $value = strtolower(wc_get_coupon_code_by_id( $value ));
            }, $rule['value'] );
        } else {
            $rule_value = array();
        }
        
        if( $rule_name === 'cart_applied_coupons' ){
            $cart_coupons_codes = WC()->cart->get_applied_coupons();
            
            // Check if there is any common coupon between defined coupons and cart coupons
            $common_coupons = array_intersect( $rule_value, $cart_coupons_codes );
            
            if( $rule_operator === 'any' ){
                if( is_array( $common_coupons ) && count( $common_coupons ) > 0  ){
                    return true;
                }
            } elseif( $rule_operator === 'all' ){
                // Check if both cart item coupons and defined coupons array values are equal
                if( count( $rule_value ) === count( $common_coupons ) ){
                    return true;
                }
            } elseif( $rule_operator === 'none' ){
                if( is_array( $common_coupons ) && count( $common_coupons ) < 1  ){
                    return true;
                }
            }
        }
        
        return false;
    }

    /**
     * Check cart total amount against defined amount.
     * 
     * @param rule The rule array.
     */
    public function check_cart_total( $rule ){
        $rule_name       = isset( $rule['base'] ) ? $rule['base'] : '';
        $rule_operator   = isset( $rule['operator'] ) ? $rule['operator'] : '';
        $rule_value      = isset( $rule['value'] ) ? $rule['value'] : '';

        if( $rule_name === 'cart_total' ){
            $cart_total = WC()->cart->total; // Total of cart including tax and shipping

            $operators_arr = array(
                'greater_than'             => 'more_than',
                'less_than'                => 'less_than',
                'greater_than_or_equal_to' => 'at_least',
                'less_than_or_equal_to'    => 'not_mroe_than'
            );

            extract($operators_arr);
            
            if( $rule_operator === $greater_than ){
                if( $cart_total > $rule_value ){
                    return true;
                }
            } elseif( $rule_operator === $less_than ){
                if( $cart_total < $rule_value ){
                    return true;
                }
            } elseif( $rule_operator === $greater_than_or_equal_to ){
                if( $cart_total >= $rule_value ){
                    return true;
                }
            } elseif( $rule_operator === $less_than_or_equal_to ){
                if( $cart_total <= $rule_value ){
                    return true;
                }
            }
        }
        
        return false;
    }

    /**
     * Check cart subtotal amount against defined amount.
     * 
     * @param rule The rule array.
     */
    public function check_cart_subtotal( $rule ){
        $rule_name       = isset( $rule['base'] ) ? $rule['base'] : '';
        $rule_operator   = isset( $rule['operator'] ) ? $rule['operator'] : '';
        $rule_value      = isset( $rule['value'] ) ? $rule['value'] : '';
        
        if( $rule_name === 'cart_subtotal' ){
            $cart_subtotal = WC()->cart->subtotal; // Subtotal of cart excluding tax and shipping
            
            $operators_arr = array(
                'greater_than'             => 'more_than',
                'less_than'                => 'less_than',
                'greater_than_or_equal_to' => 'at_least',
                'less_than_or_equal_to'    => 'not_mroe_than'
            );
            
            extract($operators_arr);
            
            if( $rule_operator === $greater_than ){
                if( $cart_subtotal > $rule_value ){
                    return true;
                }
            } elseif( $rule_operator === $less_than ){
                if( $cart_subtotal < $rule_value ){
                    return true;
                }
            } elseif( $rule_operator === $greater_than_or_equal_to ){
                if( $cart_subtotal >= $rule_value ){
                    return true;
                }
            } elseif( $rule_operator === $less_than_or_equal_to ){
                if( $cart_subtotal <= $rule_value ){
                    return true;
                }
            }
        }
        
        return false;
    }
}