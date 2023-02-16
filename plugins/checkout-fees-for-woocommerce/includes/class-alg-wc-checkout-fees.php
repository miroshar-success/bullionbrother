<?php
/**
 * Checkout Fees for WooCommerce
 *
 * @version 2.5.4
 * @since   1.0.0
 * @author  Tyche Softwares
 *
 * @package checkout-fees-for-woocommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
use Automattic\WooCommerce\Utilities\OrderUtil;

if ( ! class_exists( 'Alg_WC_Checkout_Fees' ) ) :
	/**
	 * Main Alg_WC_Checkout_Fees Class
	 *
	 * @class   Alg_WC_Checkout_Fees
	 * @version 1.2.0
	 * @since   1.0.0
	 */
	class Alg_WC_Checkout_Fees {

		/**
		 * Max ranges.
		 *
		 * @var $max_total_all_discounts Max total all discounts.
		 * @since 2.1.1
		 */
		public $max_total_all_discounts = 0;
		/**
		 * Max ranges.
		 *
		 * @var $max_total_all_fees Max total all fees.
		 * @since 2.1.1
		 */
		public $max_total_all_fees = 0;

		/**
		 * Currency conversion.
		 *
		 * @var $base_currency Base Currency
		 * @since 2.3.0
		 */
		public $base_currency;
		/**
		 * Currency conversion.
		 *
		 * @var $current_currency Current Currency
		 * @since 2.3.0
		 */
		public $current_currency;

		/**
		 * Names of fees added by the plugin
		 *
		 * @var $fees_added Array of Fees
		 * @since 2.5.8
		 */
		public $fees_added = array();

		/**
		 * Names of Additional fees added by the plugin
		 *
		 * @var $fees_added_2
		 */
		public $fees_added_2 = array();

		/**
		 * Last fee added
		 *
		 * @var $last_fee_added
		 */
		public $last_fee_added = '';

		/**
		 * Last fee added
		 *
		 * @var $last_fee_added_2
		 */
		public $last_fee_added_2 = '';

		/**
		 * Constructor.
		 *
		 * @version 2.5.0
		 * @todo    [feature] per product - add bulk settings editor/tool
		 */
		public function __construct() {
			if ( 'yes' === get_option( 'alg_woocommerce_checkout_fees_enabled', 'yes' ) ) {
				add_action( 'woocommerce_cart_calculate_fees', array( $this, 'add_gateways_fees' ), PHP_INT_MAX );
				add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_checkout_script' ) );
				add_action( 'init', array( $this, 'register_script' ) );
				require_once 'class-alg-wc-checkout-fees-info.php';
				$this->args_manager  = require_once 'class-alg-wc-checkout-fees-args.php';
				$this->base_currency = get_option( 'woocommerce_currency' );
				$this->do_merge_fees = ( 'yes' === get_option( 'alg_woocommerce_checkout_fees_merge_all_fees', 'no' ) );

				// Modify Fee HTML.
				add_filter( 'woocommerce_cart_totals_fee_html', array( $this, 'modify_fee_html_for_taxes' ), 10, 2 );

				// Modify stripe parameters and set payment page as checkout in order to enable Stripe to be initialized.
				add_filter( 'wc_stripe_params', array( $this, 'modify_stripe_params' ) );
				
				// check if subscriptions is enabled.
				if ( in_array( 'woocommerce-subscriptions/woocommerce-subscriptions.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
					// use this hook to add our fees in the recurring total displayed in the cart for subscriptions.
					add_filter( 'woocommerce_subscriptions_is_recurring_fee', array( $this, 'renewals_set_fees_recurring' ), 10, 3 );
				}

				add_action( 'woocommerce_checkout_update_order_meta', array( &$this, 'add_order_meta_fees' ), 11 );
			}
		}

		/**
		 * Convert_currency.
		 *
		 * @param float $amount Amount.
		 * @version 2.3.0
		 * @since   2.3.0
		 */
		public function convert_currency( $amount ) {
			if ( ! isset( $this->current_currency ) ) {
				$this->current_currency = get_woocommerce_currency();
			}
			return apply_filters( 'wc_aelia_cs_convert', $amount, $this->base_currency, $this->current_currency );
		}
		/**
		 * Check if HPOS is enabled or not.
		 *
		 * @since 2.8.0
		 * return boolean true if enabled else false
		 */
		public function pgbf_wc_hpos_enabled() {
			if ( class_exists( '\Automattic\WooCommerce\Utilities\OrderUtil' ) ) {
				if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
					return true;
				}
			}
			return false;
		}

		/**
		 * Get_max_ranges.
		 *
		 * @version 2.3.0
		 * @since   2.1.1
		 */
		public function get_max_ranges() {
			$this->max_total_all_discounts = $this->convert_currency( get_option( 'alg_woocommerce_checkout_fees_range_max_total_discounts', 0 ) );
			$this->max_total_all_fees      = $this->convert_currency( get_option( 'alg_woocommerce_checkout_fees_range_max_total_fees', 0 ) );
			if ( 0 == $this->max_total_all_discounts ) {
				$this->max_total_all_discounts = false;
			}
			if ( 0 == $this->max_total_all_fees ) {
				$this->max_total_all_fees = false;
			}
		}

		/**
		 * Get_product_cats.
		 *
		 * @param int $product_id ID of product.
		 * @version 2.0.0
		 * @since   2.0.0
		 */
		public function get_product_cats( $product_id ) {
			$product_cats  = array();
			$product_terms = get_the_terms( $product_id, 'product_cat' );
			if ( is_array( $product_terms ) ) {
				foreach ( $product_terms as $term ) {
					$product_cats[] = $term->term_id;
				}
			}
			return $product_cats;
		}

		/**
		 * Check_countries.
		 *
		 * Checks countries and states
		 * global fees only
		 *
		 * @param string $current_gateway Gateway of currency.
		 * @param string $fee_num Fee Number.
		 * @version 2.5.1
		 * @since   2.0.0
		 */
		public function check_countries( $current_gateway, $fee_num = '' ) {
			if ( '' !== $fee_num ) {
				$fee_num = $fee_num . '_';
			}
			if ( null === WC()->customer ) {
				if ( isset( $_POST['post_type'] ) && 'shop_order' === $_POST['post_type'] && isset( $_POST['_billing_country'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
					$customer_country = sanitize_text_field( wp_unslash( $_POST['_billing_country'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
				}
			} else {
				$customer_country = ( version_compare( get_option( 'woocommerce_version', null ), '3.0.0', '<' ) ? WC()->customer->get_country() : WC()->customer->get_billing_country() );
			}
			$include_countries = $this->replace_country_sets(
				apply_filters(
					'alg_wc_checkout_fees_option',
					'',
					'countries',
					array(
						'type'            => 'include',
						'fee_num'         => $fee_num,
						'current_gateway' => $current_gateway,
					)
				)
			);
			if ( ! empty( $include_countries ) && ! in_array( $customer_country, $include_countries, true ) ) {
				return false;
			}
			$exclude_countries = $this->replace_country_sets(
				apply_filters(
					'alg_wc_checkout_fees_option',
					'',
					'countries',
					array(
						'type'            => 'exclude',
						'fee_num'         => $fee_num,
						'current_gateway' => $current_gateway,
					)
				)
			);
			if ( ! empty( $exclude_countries ) && in_array( $customer_country, $exclude_countries, true ) ) {
				return false;
			}
			if ( '' !== $fee_num ) {
				if ( null === WC()->customer ) { // phpcs:ignore WordPress.Security.NonceVerification
					if ( isset( $_POST['post_type'] ) && 'shop_order' === $_POST['post_type'] && isset( $_POST['_billing_state'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
						$customer_state = sanitize_text_field( wp_unslash( $_POST['_billing_state'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
					}
				} else {
					$customer_state = ( version_compare( get_option( 'woocommerce_version', null ), '3.0.0', '<' ) ? WC()->customer->get_state() : WC()->customer->get_billing_state() );
				}
				$include_states = apply_filters(
					'alg_wc_checkout_fees_option',
					'',
					'states',
					array(
						'type'            => 'include',
						'fee_num'         => $fee_num,
						'current_gateway' => $current_gateway,
					)
				);
				if ( ! empty( $include_states ) && ! in_array( $customer_state, $include_states, true ) ) {
					return false;
				}
				$exclude_states = apply_filters(
					'alg_wc_checkout_fees_option',
					'',
					'states',
					array(
						'type'            => 'exclude',
						'fee_num'         => $fee_num,
						'current_gateway' => $current_gateway,
					)
				);
				if ( ! empty( $exclude_states ) && in_array( $customer_state, $exclude_states, true ) ) {
					return false;
				}
			}
			return true;
		}

		/**
		 * Replace_country_sets.
		 *
		 * @param array $countries Array of counties.
		 * @version 2.5.0
		 * @since   2.4.0
		 */
		public function replace_country_sets( $countries ) {
			if ( ! empty( $countries ) ) {
				foreach ( alg_checkout_fees_get_country_set_countries() as $id => $set ) {
					if ( in_array( $id, $countries ) ) { // phpcs:ignore
						$countries = array_merge( $countries, $set );
					}
				}
			}
			return $countries;
		}

		/**
		 * Register_script.
		 *
		 * @version 2.3.0
		 */
		public function register_script() {
			wp_register_script(
				'alg-payment-gateways-checkout',
				trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/checkout-fees.js',
				array( 'jquery' ),
				alg_wc_cf()->version,
				true
			);
		}

		/**
		 * Enqueue_checkout_script.
		 */
		public function enqueue_checkout_script() {
			global $wp;
			if ( ! is_checkout() ) {
				return;
			}
			if ( is_wc_endpoint_url( 'order-pay' ) ) {
				if ( isset( $wp->query_vars['order-pay'] ) && absint( $wp->query_vars['order-pay'] ) > 0 ) {
					$order_id = absint( $wp->query_vars['order-pay'] ); // The order ID.
				}
				if ( $this->pgbf_wc_hpos_enabled() ) {
					$order = wc_get_order( $order_id );
					if ( $order->meta_exists( '_payment_method' ) ) {
						$payment_method = $order->get_meta( '_payment_method' );
					} else {
						$payment_method = $order->get_payment_method();
					}
				} else {
					$payment_method = get_post_meta( $order_id, '_payment_method', true );
				}
				if ( '' !== get_query_var( 'order-pay' ) ) {
					wp_localize_script(
						'alg-payment-gateways-checkout',
						'pgf_checkout_order_id',
						array(
							'order_id'       => get_query_var( 'order-pay' ),
							'payment_method' => $payment_method,
						)
					);
				}
			}
			wp_enqueue_script( 'alg-payment-gateways-checkout' );
			wp_localize_script(
				'alg-payment-gateways-checkout',
				'pgf_checkout_params',
				array(
					'update_payment_method_nonce' => wp_create_nonce( 'update-payment-method' ),
				)
			);
		}

		/**
		 * Get_current_gateway.
		 *
		 * @version 2.5.4
		 * @since   2.4.0
		 */
		public function get_current_gateway() {
			$current_gateway = WC()->session->chosen_payment_method;
			if ( '' === $current_gateway ) {
				$current_gateway = ( ! empty( $_REQUEST['payment_method'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['payment_method'] ) ) : '' );// phpcs:ignore WordPress.Security.NonceVerification
				if ( '' === $current_gateway ) {
					return ( isset( $this->last_known_current_gateway ) ? $this->last_known_current_gateway : get_option( 'woocommerce_default_gateway', '' ) );
				}
			}
			$this->last_known_current_gateway = $current_gateway;
			return $current_gateway;
		}

		/**
		 * Add_gateways_fees.
		 *
		 * @param object $the_cart Cart Object.
		 * @version 2.5.0
		 */
		public function add_gateways_fees( $the_cart ) {

			if ( ! function_exists( 'WC' ) || ! method_exists( WC(), 'payment_gateways' ) || null == WC()->payment_gateways() ) {
				return;
			}

			if ( 'yes' === get_option( 'alg_woocommerce_checkout_fees_hide_on_cart', 'no' ) && is_cart() ) {
				return;
			}

			$current_gateway = $this->get_current_gateway();
			if ( ! $current_gateway ) {
				return;
			}
			// Added this check for klarna payment method, as in the $current_gateway name of the Klarna payment was not coming proper, hence we add this check and pass the correct name in the $current_gateway.
			$klarna_payment = 'klarna_payments';
			if ( strpos( $current_gateway, $klarna_payment ) !== false ) {
				$current_gateway = 'klarna_payments';
			}
			// This function is being called twice for carts that contain Subscription products, hence if it's the second time, return.
			if ( in_array( 'woocommerce-subscriptions/woocommerce-subscriptions.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
				$cart_contains_subscription = WC_Subscriptions_Cart::cart_contains_subscription();
				// if cart contains subscriptions & fees have already been added & we're not yet processing the order.
				if ( $cart_contains_subscription && ( count( $this->fees_added ) > 0 || count( $this->fees_added_2 ) > 0 ) && ( ( is_checkout() && ! isset( $_POST['woocommerce-process-checkout-nonce'] ) ) || is_cart() ) ) { // phpcs:ignore WordPress.Security.NonceVerification
					return;
				}
			}

			$this->get_max_ranges();

			if ( $this->do_merge_fees ) {
				$this->fees = array();
			}

			// Add fee - globally.
			$do_add_fees_global = $this->check_countries( $current_gateway );
			if ( $do_add_fees_global ) {
				$args = $this->args_manager->get_the_args_global( $current_gateway );
				$this->maybe_add_cart_fee( $args );
			}

			// Add fee - per product.
			if ( 'yes' === get_option( 'alg_woocommerce_checkout_fees_per_product_enabled', 'no' ) && ( 'bacs' === $current_gateway || apply_filters( 'alg_wc_checkout_fees_option', false, 'per_product' ) ) ) {
				foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
					$args = $this->args_manager->get_the_args_local( $current_gateway, $values['product_id'], $values['variation_id'], $values['quantity'] );
					$this->maybe_add_cart_fee( $args );
				}
			}

			// Add fee - "super" global.
			if ( 'yes' === get_option( 'alg_woocommerce_checkout_fees_global_fee_enabled', 'no' ) ) {
				$do_add = true;
				if ( 'yes' === get_option( 'alg_woocommerce_checkout_fees_global_fee_as_extra_enabled', 'no' ) ) {
					$current_fees = ( $this->do_merge_fees ? $this->fees : WC()->cart->get_fees() );
					if ( empty( $current_fees ) ) {
						$do_add = false;
					}
				}
				if ( $do_add ) {
					$gateways_excl = get_option( 'alg_woocommerce_checkout_fees_global_fee_gateways_excl', '' );
					if ( ! empty( $gateways_excl ) && in_array( $current_gateway, $gateways_excl, true ) ) {
						$do_add = false;
					}
				}
				if ( $do_add ) {
					if ( $this->do_merge_fees ) {
						$this->fees[] = array(
							'title'     => get_option( 'alg_woocommerce_checkout_fees_global_fee_title', '' ),
							'value'     => get_option( 'alg_woocommerce_checkout_fees_global_fee_value', 0 ),
							'taxable'   => false,
							'tax_class' => '',
						);
					} else {
						WC()->cart->add_fee(
							get_option( 'alg_woocommerce_checkout_fees_global_fee_title', '' ),
							get_option( 'alg_woocommerce_checkout_fees_global_fee_value', 0 )
						);
						$this->fees_added[] = get_option( 'alg_woocommerce_checkout_fees_global_fee_title', '' );
					}
				}
			}

			// Maybe merge.
			if ( $this->do_merge_fees && ! empty( $this->fees ) ) {
				$merged_fee = array();
				foreach ( $this->fees as $fee ) {
					if ( empty( $merged_fee ) ) {
						$merged_fee = $fee;
					} else {
						$merged_fee['value'] += $fee['value'];
					}
				}
				if ( ! empty( $merged_fee ) ) {
					WC()->cart->add_fee( $merged_fee['title'], $merged_fee['value'], $merged_fee['taxable'], $merged_fee['tax_class'] );
					$this->fees_added[] = $merged_fee['title'];
				}
			}
		}

		/**
		 * Calculate_the_fee.
		 *
		 * @param array  $args Array of arguments.
		 * @param int    $final_fee_to_add Final fees to add.
		 * @param float  $total_in_cart Total in cart.
		 * @param string $fee_num Fees number.
		 * @version 2.3.0
		 * @since   2.0.0
		 */
		public function calculate_the_fee( $args, $final_fee_to_add, $total_in_cart, $fee_num ) {
			if ( 'fee_2' == $fee_num ) {
				$fee_type  = $args['fee_type_2'];
				$fee_value = $args['fee_value_2'];
				$min_fee   = $args['min_fee_2'];
				$max_fee   = $args['max_fee_2'];
			} else {
				$fee_type  = $args['fee_type'];
				$fee_value = $args['fee_value'];
				$min_fee   = $args['min_fee'];
				$max_fee   = $args['max_fee'];
			}

			$new_fee = 0;
			switch ( $fee_type ) {
				case 'fixed':
					$fixed_fee = ( 'by_quantity' === $args['fixed_usage'] ) ? $fee_value * $args['product_qty'] : $fee_value;
					$fixed_fee = $this->convert_currency( $fixed_fee );
					$new_fee   = $fixed_fee;
					break;
				case 'percent':
					if ( 0 != $args['product_id'] ) {
						$_product    = wc_get_product( $args['product_id'] );
						$sum_for_fee = $_product->get_price() * $args['product_qty'];
					} else {
						$sum_for_fee = $total_in_cart;
					}
					$new_fee = ( $fee_value / 100 ) * $sum_for_fee;
					break;
			}
			// Min fee.
			if ( 0 != $min_fee && $new_fee < $min_fee ) {
				$new_fee = $min_fee;
			}
			// Max fee.
			if ( 0 != $max_fee && $new_fee > $max_fee ) {
				$new_fee = $max_fee;
			}
			// Max total discount.
			if ( false !== $this->max_total_all_discounts ) {
				if ( $new_fee < $this->max_total_all_discounts ) {
					$new_fee = $this->max_total_all_discounts;
				}
				$this->max_total_all_discounts -= $new_fee;
				if ( $this->max_total_all_discounts > 0 ) {
					$this->max_total_all_discounts = 0;
				}
			}
			// Max total fees.
			if ( false !== $this->max_total_all_fees ) {
				if ( $new_fee > $this->max_total_all_fees ) {
					$new_fee = $this->max_total_all_fees;
				}
				$this->max_total_all_fees -= $new_fee;
				if ( $this->max_total_all_fees < 0 ) {
					$this->max_total_all_fees = 0;
				}
			}
			// Final calculations.
			$final_fee_to_add += $new_fee;
			if ( 'percent' === $fee_type && 'yes' === $args['do_round'] ) {
				// default the precision to 0 if it has been left blanks.
				$precision        = '' === $args['precision'] ? 0 : $args['precision'];
				$final_fee_to_add = round( $final_fee_to_add, $precision );
			}
			return $final_fee_to_add;
		}

		/**
		 * Get_sum_for_fee_by_included_and_excluded_cats - calculate by categories and global fees override.
		 *
		 * @param float  $total_in_cart Cart total.
		 * @param string $fee_num Number of Fee.
		 * @param string $current_gateway Gateway if currency.
		 * @version 2.5.0
		 * @since   2.1.0
		 */
		public function get_sum_for_fee_by_included_and_excluded_cats( $total_in_cart, $fee_num, $current_gateway ) {
			// Categories.
			if ( 'fee_2' === $fee_num ) {
				$include_cats = ( false === get_option( 'alg_gateways_fees_cats_include_fee_2_' . $current_gateway, false ) ) ?
				apply_filters(
					'alg_wc_checkout_fees_option',
					'',
					'cats',
					array(
						'type'            => 'include',
						'fee_num'         => '',
						'current_gateway' => $current_gateway,
					)
				) :
				apply_filters(
					'alg_wc_checkout_fees_option',
					'',
					'cats',
					array(
						'type'            => 'include',
						'fee_num'         => 'fee_2_',
						'current_gateway' => $current_gateway,
					)
				);
				$exclude_cats = ( false === get_option( 'alg_gateways_fees_cats_exclude_fee_2_' . $current_gateway, false ) ) ?
				apply_filters(
					'alg_wc_checkout_fees_option',
					'',
					'cats',
					array(
						'type'            => 'exclude',
						'fee_num'         => '',
						'current_gateway' => $current_gateway,
					)
				) :
				apply_filters(
					'alg_wc_checkout_fees_option',
					'',
					'cats',
					array(
						'type'            => 'exclude',
						'fee_num'         => 'fee_2_',
						'current_gateway' => $current_gateway,
					)
				);
			} else {
				$include_cats = apply_filters(
					'alg_wc_checkout_fees_option',
					'',
					'cats',
					array(
						'type'            => 'include',
						'fee_num'         => '',
						'current_gateway' => $current_gateway,
					)
				);
				$exclude_cats = apply_filters(
					'alg_wc_checkout_fees_option',
					'',
					'cats',
					array(
						'type'            => 'exclude',
						'fee_num'         => '',
						'current_gateway' => $current_gateway,
					)
				);
			}
			if ( ! empty( $include_cats ) && 'only_for_selected_products' === get_option( 'alg_gateways_fees_cats_include_calc_type_' . $current_gateway, 'for_all_cart' ) ) {
				$sum_for_fee = 0;
				foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
					$product_cats  = $this->get_product_cats( $values['product_id'] );
					$the_intersect = array_intersect( $product_cats, $include_cats );
					if ( ! empty( $the_intersect ) ) {
						if ( ! $this->is_override_global_fees_enabled_for_product( $fee_num, $current_gateway, $values['product_id'] ) ) {
							$sum_for_fee += $values['line_total'];
						}
					}
				}
			} elseif ( ! empty( $exclude_cats ) && 'only_for_selected_products' === get_option( 'alg_gateways_fees_cats_exclude_calc_type_' . $current_gateway, 'for_all_cart' ) ) {
				$sum_for_fee = 0;
				foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
					$product_cats  = $this->get_product_cats( $values['product_id'] );
					$the_intersect = array_intersect( $product_cats, $exclude_cats );
					if ( empty( $the_intersect ) ) {
						if ( ! $this->is_override_global_fees_enabled_for_product( $fee_num, $current_gateway, $values['product_id'] ) ) {
							$sum_for_fee += $values['line_total'];
						}
					}
				}
			} else {
				$sum_for_fee = $total_in_cart;
				// Global fees override.
				foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
					if ( $this->is_override_global_fees_enabled_for_product( $fee_num, $current_gateway, $values['product_id'] ) ) {
						$sum_for_fee -= $values['line_total'];
					}
				}
			}
			return $sum_for_fee;
		}

		/**
		 * Is_override_global_fees_enabled_for_product.
		 *
		 * @param string $fee_num Number of Fee.
		 * @param string $current_gateway Gateway of currency.
		 * @param int    $product_id ID of product.
		 * @version 2.5.0
		 * @since   2.1.1
		 */
		public function is_override_global_fees_enabled_for_product( $fee_num, $current_gateway, $product_id ) {
			$override_option_name = ( 'fee_2' === $fee_num ) ? 'alg_checkout_fees_global_override_fee_2_' : 'alg_checkout_fees_global_override_';
			return (
			'yes' === get_post_meta( $product_id, '_alg_checkout_fees_enabled_' . $current_gateway, true ) &&
			'yes' === get_post_meta( $product_id, '_' . $override_option_name . $current_gateway, true )
			);
		}

		/**
		 * Do_apply_fees_by_categories - check by categories and by global fee override.
		 *
		 * @param string $fee_num Number of Fee.
		 * @param string $current_gateway Gateway of currency.
		 * @param int    $info_product_id ID of product.
		 * @version 2.5.1
		 * @since   2.1.0
		 * @todo    [fix] maybe in case of `( ! empty( $include_cats ) )` and "For all cart" - all products in cart must be of selected cats (i.e. not just one product)?
		 */
		public function do_apply_fees_by_categories( $fee_num, $current_gateway, $info_product_id ) {
			// Global fees override.
			if ( 0 != $info_product_id ) {
				if ( $this->is_override_global_fees_enabled_for_product( $fee_num, $current_gateway, $info_product_id ) ) {
					return false;
				}
			} else {
				$do_override_global_fees_for_all_cart = true;
				foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
					if ( ! $this->is_override_global_fees_enabled_for_product( $fee_num, $current_gateway, $values['product_id'] ) ) {
						// At least one product does not have the override, no need to check further.
						$do_override_global_fees_for_all_cart = false;
						break;
					}
				}
				if ( $do_override_global_fees_for_all_cart ) {
					return false;
				}
			}
			// Categories.
			if ( 'fee_2' === $fee_num ) {
				$include_cats = ( false === get_option( 'alg_gateways_fees_cats_include_fee_2_' . $current_gateway, false ) ) ?
				apply_filters(
					'alg_wc_checkout_fees_option',
					'',
					'cats',
					array(
						'type'            => 'include',
						'fee_num'         => '',
						'current_gateway' => $current_gateway,
					)
				) :
				apply_filters(
					'alg_wc_checkout_fees_option',
					'',
					'cats',
					array(
						'type'            => 'include',
						'fee_num'         => 'fee_2_',
						'current_gateway' => $current_gateway,
					)
				);
				$exclude_cats = ( false === get_option( 'alg_gateways_fees_cats_exclude_fee_2_' . $current_gateway, false ) ) ?
				apply_filters(
					'alg_wc_checkout_fees_option',
					'',
					'cats',
					array(
						'type'            => 'exclude',
						'fee_num'         => '',
						'current_gateway' => $current_gateway,
					)
				) :
				apply_filters(
					'alg_wc_checkout_fees_option',
					'',
					'cats',
					array(
						'type'            => 'exclude',
						'fee_num'         => 'fee_2_',
						'current_gateway' => $current_gateway,
					)
				);
			} else {
				$include_cats = apply_filters(
					'alg_wc_checkout_fees_option',
					'',
					'cats',
					array(
						'type'            => 'include',
						'fee_num'         => '',
						'current_gateway' => $current_gateway,
					)
				);
				$exclude_cats = apply_filters(
					'alg_wc_checkout_fees_option',
					'',
					'cats',
					array(
						'type'            => 'exclude',
						'fee_num'         => '',
						'current_gateway' => $current_gateway,
					)
				);
			}
			if ( '' !== $include_cats || '' !== $exclude_cats ) {
				if ( 0 != $info_product_id ) {
					$product_cats = $this->get_product_cats( $info_product_id );
					if ( ! empty( $include_cats ) ) {
						$the_intersect = array_intersect( $product_cats, $include_cats );
						if ( empty( $the_intersect ) ) {
							return false;
						}
					}
					if ( ! empty( $exclude_cats ) ) {
						$the_intersect = array_intersect( $product_cats, $exclude_cats );
						if ( ! empty( $the_intersect ) ) {
							return false;
						}
					}
				} else {
					if ( ! empty( $include_cats ) ) {
						foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
							$product_cats  = $this->get_product_cats( $values['product_id'] );
							$the_intersect = array_intersect( $product_cats, $include_cats );
							if ( ! empty( $the_intersect ) ) {
								// At least one product in the cart is ok, no need to check further.
								return true;
							}
						}
						return false;
					}
					if ( ! empty( $exclude_cats ) ) {
						if ( 'for_all_cart' === get_option( 'alg_gateways_fees_cats_exclude_calc_type_' . $current_gateway, 'for_all_cart' ) ) {
							foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
								$product_cats  = $this->get_product_cats( $values['product_id'] );
								$the_intersect = array_intersect( $product_cats, $exclude_cats );
								if ( ! empty( $the_intersect ) ) {
									// At least one product in the cart is NOT ok, no need to check further.
									return false;
								}
							}
							return true;
						} else {
							foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
								$product_cats  = $this->get_product_cats( $values['product_id'] );
								$the_intersect = array_intersect( $product_cats, $exclude_cats );
								if ( empty( $the_intersect ) ) {
									// At least one product in the cart is ok, no need to check further.
									return true;
								}
							}
							return false;
						}
					}
				}
			}
			return true;
		}

		/**
		 * Is_wc_version_below_3_2.
		 *
		 * @version 2.3.2
		 * @since   2.3.2
		 */
		public function is_wc_version_below_3_2() {
			if ( ! isset( $this->is_wc_version_below_3_2 ) ) {
				$this->is_wc_version_below_3_2 = version_compare( get_option( 'woocommerce_version', null ), '3.2.0', '<' );
			}
			return $this->is_wc_version_below_3_2;
		}

		/**
		 * Get_the_fee.
		 *
		 * @param array   $args Array of arguments.
		 * @param string  $fee_num Number of fees.
		 * @param int     $total_in_cart Total in cart.
		 * @param boolean $is_info_only Is info only?.
		 * @param int     $info_product_id Product ID.
		 * @version 2.5.3
		 * @since   1.2.0
		 * @todo    [dev] maybe use `WC()->cart->get_total( 'edit' )` for `$total_in_cart`
		 */
		public function get_the_fee( $args, $fee_num, $total_in_cart = 0, $is_info_only = false, $info_product_id = 0 ) {
			$final_fee_to_add = 0;
			if ( '' !== $args['current_gateway'] && 'yes' === $args['is_enabled'] ) {
				if ( 0 == $total_in_cart ) {
					$total_in_cart = ( 'yes' === $args['exclude_shipping'] ) ? WC()->cart->cart_contents_total : WC()->cart->cart_contents_total + WC()->cart->shipping_total;
					if ( 'yes' === $args['add_taxes'] ) {
						$tax_total = ( $this->is_wc_version_below_3_2() ? WC_Tax::get_tax_total( WC()->cart->taxes ) : array_sum( WC()->cart->get_cart_contents_taxes() ) );
						if ( 'yes' === $args['exclude_shipping'] ) {
							$total_in_cart += $tax_total;
						} else {
							$shipping_tax_total = ( $this->is_wc_version_below_3_2() ? WC_Tax::get_tax_total( WC()->cart->shipping_taxes ) : array_sum( WC()->cart->get_shipping_taxes() ) );
							$total_in_cart     += $tax_total + $shipping_tax_total;
						}
					}
					if ( ! empty( WC()->cart->credit_used ) && is_array( WC()->cart->credit_used ) ) { // for "WooCommerce Gift Certificates" plugin.
						$total_in_cart -= array_sum( WC()->cart->credit_used );
					}
				}
				if ( $total_in_cart >= $args['min_cart_amount'] && ( 0 == $args['max_cart_amount'] || $total_in_cart <= $args['max_cart_amount'] ) ) {
					if ( 0 != $args['fee_value'] && 'fee_2' !== $fee_num ) {
						if ( 'local' === $args['fee_scope'] || $this->do_apply_fees_by_categories( 'fee_1', $args['current_gateway'], $info_product_id ) ) {
							if ( ! $is_info_only && 'global' === $args['fee_scope'] ) {
								$total_in_cart = $this->get_sum_for_fee_by_included_and_excluded_cats( $total_in_cart, 'fee_1', $args['current_gateway'] );
							}
							if ( ( 'local' === $args['fee_scope'] || $this->check_countries( $args['current_gateway'], 'fee_1' ) ) && ( $is_info_only || $this->do_apply_fees_by_coupons( $args['coupons_rule'] ) ) ) {
								$final_fee_to_add = $this->calculate_the_fee( $args, $final_fee_to_add, $total_in_cart, 'fee_1' );
							}
						}
					}
					if ( 0 != $args['fee_value_2'] && 'fee_1' != $fee_num ) {
						if ( 'local' === $args['fee_scope'] || $this->do_apply_fees_by_categories( 'fee_2', $args['current_gateway'], $info_product_id ) ) {
							if ( ! $is_info_only && 'global' === $args['fee_scope'] ) {
								$total_in_cart = $this->get_sum_for_fee_by_included_and_excluded_cats( $total_in_cart, 'fee_2', $args['current_gateway'] );
							}
							if ( ( 'local' === $args['fee_scope'] || $this->check_countries( $args['current_gateway'], 'fee_2' ) ) && ( $is_info_only || $this->do_apply_fees_by_coupons( $args['coupons_rule_2'] ) ) ) {
								$final_fee_to_add = $this->calculate_the_fee( $args, $final_fee_to_add, $total_in_cart, 'fee_2' );
							}
						}
					}
				}
			}
			return $final_fee_to_add;
		}

		/**
		 * Do_apply_fees_by_coupons.
		 *
		 * @param string $coupon_rule Rule of coupon.
		 * @version 2.3.0
		 * @since   2.3.0
		 */
		public function do_apply_fees_by_coupons( $coupon_rule ) {
			switch ( $coupon_rule ) {
				case 'only_if_no_coupons':
					return ! WC()->cart->has_discount();
				case 'only_if_coupons':
					return WC()->cart->has_discount();
				default: // 'disabled'
					return true;
			}
		}

		/**
		 * Recheck_fee_title.
		 *
		 * @param string $fee_text Text of the fees.
		 * @param array  $fees Array of fees.
		 * @version 2.0.0
		 * @since   1.1.0
		 */
		public function recheck_fee_title( $fee_text, $fees ) {
			foreach ( $fees as $fee ) {
				if ( $fee_text === $fee->name ) {
					$fee_text .= ' #2';
				}
			}
			return $fee_text;
		}

		/**
		 * Maybe_add_cart_fee.
		 *
		 * @param array $args Array of arguments.
		 * @version 2.5.0
		 * @since   1.1.0
		 */
		public function maybe_add_cart_fee( $args ) {
			if ( $args['fee_text'] === $args['fee_text_2'] || '' === $args['fee_text_2'] ) {
				$final_fee_to_add   = $this->get_the_fee( $args, 'fee_both' );
				$final_fee_to_add_2 = 0;
			} else {
				$final_fee_to_add   = $this->get_the_fee( $args, 'fee_1' );
				$final_fee_to_add_2 = $this->get_the_fee( $args, 'fee_2' );
			}
			if ( 0 != $final_fee_to_add || 0 != $final_fee_to_add_2 ) {
				$taxable        = ( 'yes' === $args['is_taxable'] );
				$tax_class_name = '';
				if ( $taxable ) {
					$tax_class_names = array_merge( array( '' ), WC_Tax::get_tax_classes() );
					$tax_class_name  = ( isset( $tax_class_names[ $args['tax_class_id'] ] ) ? $tax_class_names[ $args['tax_class_id'] ] : '' );
				}
				$fees = WC()->cart->get_fees();
				if ( 0 !== $final_fee_to_add ) {
					if ( $this->do_merge_fees ) {
						$this->fees[] = array(
							'title'     => $args['fee_text'],
							'value'     => $final_fee_to_add,
							'taxable'   => $taxable,
							'tax_class' => $tax_class_name,
						);
					} else {
						$fee_text = $this->recheck_fee_title( $args['fee_text'], $fees );
						WC()->cart->add_fee( $fee_text, $final_fee_to_add, $taxable, $tax_class_name );
						$this->fees_added[]   = $args['fee_text'];
						$this->last_fee_added = $args['fee_text'];
					}
				}
				if ( 0 != $final_fee_to_add_2 ) {
					if ( $this->do_merge_fees ) {
						$this->fees[] = array(
							'title'     => $args['fee_text_2'],
							'value'     => $final_fee_to_add_2,
							'taxable'   => $taxable,
							'tax_class' => $tax_class_name,
						);
					} else {
						$fee_text_2 = $this->recheck_fee_title( $args['fee_text_2'], $fees );
						WC()->cart->add_fee( $fee_text_2, $final_fee_to_add_2, $taxable, $tax_class_name );
						$this->fees_added_2[]   = $args['fee_text_2'];
						$this->last_fee_added_2 = $args['fee_text_2'];
					}
				}
			}
		}

		/**
		 * Ensures (incl. %s Tax) is displayed for fees added from our plugin.
		 * when charges are being applied inclusive of taxes.
		 *
		 * @param string $cart_fee_html - HTML for fees.
		 * @param object $fees - Fee Object.
		 * @return string $cart_fee_html
		 * @since 2.5.8
		 */
		public function modify_fee_html_for_taxes( $cart_fee_html, $fees ) {
			$tax_label = 'Tax';
			$tax_data  = WC()->cart->get_tax_totals();
			foreach ( $tax_data as $code => $tax ) {
				$tax_label = $tax->label;
			}
			if ( 'incl' === get_option( 'woocommerce_tax_display_cart' ) && isset( $fees->tax ) && $fees->tax > 0 && ( in_array( $fees->name, $this->fees_added ) || in_array( $fees->name, $this->fees_added_2 ) ) ) { //phpcs:ignore
				$cart_fee_html .= '<small class="includes_tax">' . sprintf( __( '(includes %s %s)', 'checkout-fees-for-woocommerce' ), wc_price( $fees->tax ), $tax_label ) . '</small>'; // phpcs:ignore
			}
			return $cart_fee_html;
		}

		/**
		 * Add fees to recurring totals for WC Subscriptions.
		 *
		 * @param boolean $recurring - Add or no to recurring total.
		 * @param object  $fees - Fees present in the current cart.
		 * @param WC_Cart $cart - Cart Object.
		 * @return $recurring
		 * @since 2.5.8
		 */
		public function renewals_set_fees_recurring( $recurring, $fees, $cart ) {

			// If it's fees which have been added from our plugin, return true else return as is.
			$recurring = ( 0 != $fees->total && ( in_array( $fees->name, $this->fees_added ) || in_array( $fees->name, $this->fees_added_2 ) ) ) ? true : $recurring; //phpcs:ignore
			return $recurring;

		}

		/**
		 * Add fees in the order meta.
		 *
		 * @param integer $order_id Order ID.
		 */
		public function add_order_meta_fees( $order_id ) {
			$order = wc_get_order( $order_id );
			foreach ( $order->get_items( 'fee' ) as $item_id => $item ) {
				if ( '' !== $this->last_fee_added && in_array( $item->get_name(), $this->fees_added ) ) { //phpcs:ignore
					wc_add_order_item_meta( $item_id, '_last_added_fee', $item->get_name() );
				}

				if ( '' !== $this->last_fee_added_2 && in_array( $item->get_name(), $this->fees_added_2 ) ) { //phpcs:ignore
					wc_add_order_item_meta( $item_id, '_last_added_fee_2', $item->get_name() );
				}
			}
		}

		/**
		 * Function to modify stripe parameters and set payment page as checkout.
		 *
		 * @param array $stripe_params Array of parameters for Stripe payment plugin.
		 */
		public function modify_stripe_params( $stripe_params ) {
			if ( is_checkout() ) {
				$stripe_params['is_checkout'] = 'yes';
			}
			return $stripe_params;
		}
	}

endif;

return new Alg_WC_Checkout_Fees();
