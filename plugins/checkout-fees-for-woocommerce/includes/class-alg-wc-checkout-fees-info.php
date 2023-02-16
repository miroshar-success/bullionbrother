<?php
/**
 * Checkout Fees for WooCommerce - Info
 *
 * @version 2.5.0
 * @since   2.3.0
 * @author  Tyche Softwares
 *
 * @package checkout-fees-for-woocommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Alg_WC_Checkout_Fees_Info' ) ) :
	/**
	 * Alg_WC_Checkout_Fees_Info Class
	 *
	 * @class   Alg_WC_Checkout_Fees_Info
	 * @version 1.2.0
	 * @since   1.0.0
	 */
	class Alg_WC_Checkout_Fees_Info {

		/**
		 * Constructor.
		 *
		 * @version 2.5.0
		 * @since   2.3.0
		 */
		public function __construct() {
			if ( 'yes' === get_option( 'alg_woocommerce_checkout_fees_info_enabled', 'no' ) ) {
				add_action(
					get_option( 'alg_woocommerce_checkout_fees_info_hook', 'woocommerce_single_product_summary' ),
					array( $this, 'show_checkout_fees_full_info' ),
					get_option( 'alg_woocommerce_checkout_fees_info_hook_priority', 20 )
				);
			}
			if ( 'yes' === get_option( 'alg_woocommerce_checkout_fees_lowest_price_info_enabled', 'no' ) ) {
				add_action(
					get_option( 'alg_woocommerce_checkout_fees_lowest_price_info_hook', 'woocommerce_single_product_summary' ),
					array( $this, 'show_checkout_fees_full_lowest_price_info' ),
					get_option( 'alg_woocommerce_checkout_fees_lowest_price_info_hook_priority', 20 )
				);
			}
			add_shortcode( 'alg_show_checkout_fees_full_info', array( $this, 'get_checkout_fees_full_info' ) );
			add_shortcode( 'alg_show_checkout_fees_lowest_price_info', array( $this, 'get_checkout_fees_lowest_price_info' ) );
		}

		/**
		 * Show_checkout_fees_full_lowest_price_info.
		 *
		 * @version 2.0.0
		 * @since   2.0.0
		 */
		public function show_checkout_fees_full_lowest_price_info() {
			echo $this->get_checkout_fees_info( true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		/**
		 * Show_checkout_fees_full_info.
		 *
		 * @version 2.0.0
		 * @since   2.0.0
		 */
		public function show_checkout_fees_full_info() {
			echo $this->get_checkout_fees_info( false ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		/**
		 * Get_checkout_fees_lowest_price_info.
		 *
		 * @version 2.0.0
		 * @since   2.0.0
		 */
		public function get_checkout_fees_lowest_price_info() {
			return $this->get_checkout_fees_info( true );
		}

		/**
		 * Get_checkout_fees_full_info.
		 *
		 * @version 2.0.0
		 * @since   2.0.0
		 */
		public function get_checkout_fees_full_info() {
			return $this->get_checkout_fees_info( false );
		}

		/**
		 * Get_checkout_fees_info.
		 *
		 * @param mixed $lowest_price_only Lowest price.
		 * @version 2.5.0
		 * @since   1.2.0
		 */
		public function get_checkout_fees_info( $lowest_price_only ) {

			$product_id  = get_the_ID();
			$the_product = wc_get_product( $product_id );

			$tax_display_mode = get_option( 'woocommerce_tax_display_shop' );

			$products_array = array();
			if ( $the_product->is_type( 'variable' ) ) {
				foreach ( $the_product->get_available_variations() as $product_variation ) {
					$variation_product = wc_get_product( $product_variation['variation_id'] );
					$products_array[]  = ( version_compare( get_option( 'woocommerce_version', null ), '3.0.0', '<' ) ?
					array(
						'variation_atts' => $variation_product->get_formatted_variation_attributes( true ),
						'price_excl_tax' => $variation_product->get_price_excluding_tax(),
						'price_incl_tax' => $variation_product->get_price_including_tax(),
						'display_price'  => $variation_product->get_display_price(),
					) :
					array(
						'variation_atts' => wc_get_formatted_variation( $variation_product, true ),
						'price_excl_tax' => wc_get_price_excluding_tax( $variation_product ),
						'price_incl_tax' => wc_get_price_including_tax( $variation_product ),
						'display_price'  => wc_get_price_to_display( $variation_product ),
					)
					);
				}
			} else {
				$products_array = ( version_compare( get_option( 'woocommerce_version', null ), '3.0.0', '<' ) ?
				array(
					array(
						'variation_atts' => '',
						'price_excl_tax' => $the_product->get_price_excluding_tax(),
						'price_incl_tax' => $the_product->get_price_including_tax(),
						'display_price'  => $the_product->get_display_price(),
					),
				) :
				array(
					array(
						'variation_atts' => '',
						'price_excl_tax' => wc_get_price_excluding_tax( $the_product ),
						'price_incl_tax' => wc_get_price_including_tax( $the_product ),
						'display_price'  => wc_get_price_to_display( $the_product ),
					),
				)
				);
			}

			$gateways_data      = array();
			$lowest_price_array = array();

			foreach ( $products_array as $product_data ) {

				$the_variation_atts = $product_data['variation_atts'];
				$the_price_excl_tax = $product_data['price_excl_tax'];
				$the_price_incl_tax = $product_data['price_incl_tax'];
				$the_display_price  = $product_data['display_price'];

				$single_product_gateways_data = array();

				$lowest_price         = PHP_INT_MAX;
				$lowest_price_gateway = '';

				$available_gateways = WC()->payment_gateways->get_available_payment_gateways();
				foreach ( $available_gateways as $available_gateway_key => $available_gateway ) {

					$current_gateway = $available_gateway_key;

					alg_wc_cf()->core->get_max_ranges();

					// Fee - globally.
					if ( alg_wc_cf()->core->check_countries( $current_gateway ) ) {
						$args       = alg_wc_cf()->core->args_manager->get_the_args_global( $current_gateway );
						$global_fee = alg_wc_cf()->core->get_the_fee( $args, 'fee_both', $the_price_excl_tax, true, $product_id );
					} else {
						$global_fee = 0;
					}

					// Fee - per product.
					$local_fee = 0;
					if ( 'yes' === get_option( 'alg_woocommerce_checkout_fees_per_product_enabled', 'no' ) && ( 'bacs' === $current_gateway || apply_filters( 'alg_wc_checkout_fees_option', false, 'per_product' ) ) ) {
						$args      = alg_wc_cf()->core->args_manager->get_the_args_local( $current_gateway, $product_id, 0, 1 );
						$local_fee = alg_wc_cf()->core->get_the_fee( $args, 'fee_both', $the_price_excl_tax, true, $product_id );
					}

					if ( 'incl' === $tax_display_mode ) {
						$the_price = $the_price_incl_tax;
						if ( 0 != $global_fee ) {
							if ( 'yes' === get_option( 'alg_gateways_fees_is_taxable_' . $current_gateway, 'no' ) ) {
								$tax_class_names = array_merge( array( '' ), WC_Tax::get_tax_classes() );
								$tax_class_name  = get_option( 'alg_gateways_fees_tax_class_id_' . $current_gateway, 0 );
								$tax_class_name  = ( isset( $tax_class_names[ $tax_class_name ] ) ? $tax_class_names[ $tax_class_name ] : '' );
								$tax_rates       = WC_Tax::get_rates( $tax_class_name );
								$fee_taxes       = WC_Tax::calc_tax( $global_fee, $tax_rates, false );
								if ( ! empty( $fee_taxes ) ) {
									$tax         = array_sum( $fee_taxes );
									$global_fee += $tax;
								}
							}
							$the_price += $global_fee;
						}
						if ( 0 != $local_fee ) {
							if ( 'yes' === get_post_meta( $product_id, '_alg_checkout_fees_tax_enabled_' . $current_gateway, true ) ) {
								$tax_class_names = array_merge( array( '' ), WC_Tax::get_tax_classes() );
								$tax_class_name  = get_post_meta( $product_id, '_alg_checkout_fees_tax_class_' . $current_gateway, true );
								$tax_class_name  = ( isset( $tax_class_names[ $tax_class_name ] ) ? $tax_class_names[ $tax_class_name ] : '' );
								$tax_rates       = WC_Tax::get_rates( $tax_class_name );
								$fee_taxes       = WC_Tax::calc_tax( $local_fee, $tax_rates, false );
								if ( ! empty( $fee_taxes ) ) {
									$tax        = array_sum( $fee_taxes );
									$local_fee += $tax;
								}
							}
							$the_price += $local_fee;
						}
						$price_diff         = ( $the_price - $the_price_incl_tax );
						$price_diff_percent = ( 0 != $the_price_incl_tax ? round( ( $price_diff / $the_price_incl_tax ) * 100, 0 ) : 0 );
					} else {
						$the_price          = $the_price_excl_tax;
						$the_price         += $global_fee;
						$the_price         += $local_fee;
						$price_diff         = ( $the_price - $the_price_excl_tax );
						$price_diff_percent = ( 0 != $the_price_excl_tax ? round( ( $price_diff / $the_price_excl_tax ) * 100, 0 ) : 0 );
					}

					if ( false === $lowest_price_only ) {
						// Saving for output.
						$single_product_gateways_data[ $available_gateway_key ] = array(
							'gateway_title'              => $available_gateway->title,
							'gateway_description'        => $available_gateway->get_description(),
							'gateway_icon'               => $available_gateway->get_icon(),
							'product_gateway_price'      => $the_price,
							'product_original_price'     => $the_display_price,
							'product_price_diff'         => $price_diff,
							'product_price_diff_percent' => $price_diff_percent,
							'product_title'              => $the_product->get_title(),
							'product_variation_atts'     => $the_variation_atts,
						);
					} else { // if ( true === $lowest_price_only ) {
						// Saving lowest price data.
						if ( $the_price < $lowest_price ) {
							$lowest_price                     = $the_price;
							$lowest_price_gateway_key         = $available_gateway->id;
							$lowest_price_gateway             = $available_gateway->title;
							$lowest_price_gateway_description = $available_gateway->get_description();
							$lowest_price_gateway_icon        = $available_gateway->get_icon();
							$lowest_price_diff                = $price_diff;
							$lowest_price_diff_percent        = $price_diff_percent;
						}
					}
				}

				$gateways_data[] = $single_product_gateways_data;

				// Saving lowest price info.
				if ( true === $lowest_price_only && '' !== $lowest_price_gateway ) {
					$lowest_price_array[] = array(
						'gateway_id'                 => $lowest_price_gateway_key,
						'gateway_title'              => $lowest_price_gateway,
						'gateway_description'        => $lowest_price_gateway_description,
						'gateway_icon'               => $lowest_price_gateway_icon,
						'product_gateway_price'      => $lowest_price,
						'product_original_price'     => $the_display_price,
						'product_price_diff'         => $lowest_price_diff,
						'product_price_diff_percent' => $lowest_price_diff_percent,
						'product_title'              => $the_product->get_title(),
						'product_variation_atts'     => $the_variation_atts,
					);
				}
			}

			// Outputting results.
			$price_keys = array( 'product_gateway_price', 'product_original_price', 'product_price_diff' );
			$final_html = '';
			if ( 'for_each_variation' === get_option( 'alg_woocommerce_checkout_fees_variable_info', 'for_each_variation' ) ) {
				if ( false === $lowest_price_only && ! empty( $gateways_data ) ) {
					// All gateways.
					foreach ( $gateways_data as $single_product_gateways_data ) {
						$single_product_gateways_data_html = '';
						foreach ( $single_product_gateways_data as $key => $row ) {
							$payment_id = $key;
							$row_html   = get_option(
								'alg_woocommerce_checkout_fees_info_row_template',
								'<tr><td><strong>%gateway_title%</strong></td><td>%product_original_price%</td><td>%product_gateway_price%</td><td>%product_price_diff%</td></tr>'
							);
							foreach ( $row as $key => $value ) {
								if ( in_array( $key, $price_keys ) ) {
									$payment_fees = (int) get_option( 'alg_gateways_fees_value_' . $payment_id, 0 );
									if ( '0' === $the_product->get_price() && 0 >= $payment_fees ) {
										$value = 0;
									}
									$value = wc_price( $value );
								}
								$row_html = str_replace( '%' . $key . '%', $value, $row_html );
							}
							$single_product_gateways_data_html .= $row_html;
						}
						$final_html .= get_option( 'alg_woocommerce_checkout_fees_info_start_template', '<table>' ) .
						$single_product_gateways_data_html . get_option( 'alg_woocommerce_checkout_fees_info_end_template', '</table>' );
					}
				} elseif ( true === $lowest_price_only && ! empty( $lowest_price_array ) ) {
					// Lowest price only.
					foreach ( $lowest_price_array as $lowest_price ) {
						$row_html = get_option(
							'alg_woocommerce_checkout_fees_lowest_price_info_template',
							'<p><strong>%gateway_title%</strong> %product_gateway_price% (%product_price_diff%)</p>'
						);
						foreach ( $lowest_price as $key => $value ) {
							$gateway_key = '';
							if ( 'gateway_id' === $key ) {
								$gateway_key = $value;
							}
							if ( in_array( $key, $price_keys ) ) {
								$payment_fees = (int) get_option( 'alg_gateways_fees_value_' . $gateway_key, 0 );
								if ( '0' === $the_product->get_price() && 0 >= $payment_fees ) {
									$value = 0;
								}
								$value = wc_price( $value );
							}
							$row_html = str_replace( '%' . $key . '%', $value, $row_html );
						}
						$final_html .= $row_html;
					}
				}
			} elseif ( 'ranges' === get_option( 'alg_woocommerce_checkout_fees_variable_info', 'for_each_variation' ) ) {
				if ( false === $lowest_price_only && ! empty( $gateways_data ) ) {
					// All gateways.
					$modified_array = array();
					foreach ( $gateways_data as $i => $single_product_gateways_data ) {
						foreach ( $single_product_gateways_data as $gateway_key => $row ) {
							foreach ( $row as $key => $value ) {
								$modified_array[ $gateway_key ][ $key ][ $i ] = $value;
							}
						}
					}
					foreach ( $modified_array as $gateway_key => $values ) {
						$row_html = get_option(
							'alg_woocommerce_checkout_fees_info_row_template',
							'<tr><td><strong>%gateway_title%</strong></td><td>%product_original_price%</td><td>%product_gateway_price%</td><td>%product_price_diff%</td></tr>'
						);
						foreach ( $values as $key => $values_array ) {
							$values_array = array_unique( $values_array );
							$payment_fees = (int) get_option( 'alg_gateways_fees_value_' . $gateway_key, 0 );
							if ( in_array( $key, $price_keys ) ) {
								if ( count( $values_array ) > 1 ) {
									$value = wc_price( min( $values_array ) ) . '&ndash;' . wc_price( max( $values_array ) );
									if ( '0' === $the_product->get_price() && 0 >= $payment_fees ) {
										$value = wc_price( 0 ) . '&ndash;' . wc_price( 0 );
									}
								} else {
									$value = wc_price( min( $values_array ) );
									if ( '0' === $the_product->get_price() && 0 >= $payment_fees ) {
										$value = wc_price( 0 );
									}
								}
							} else {
								$value = implode( '<br>', $values_array );
							}
							$row_html = str_replace( '%' . $key . '%', $value, $row_html );
						}
						$final_html .= $row_html;
					}
					$final_html = get_option( 'alg_woocommerce_checkout_fees_info_start_template', '<table>' ) .
					$final_html . get_option( 'alg_woocommerce_checkout_fees_info_end_template', '</table>' );
				} elseif ( true === $lowest_price_only && ! empty( $lowest_price_array ) ) {
					// Lowest price only.
					$modified_array = array();
					foreach ( $lowest_price_array as $i => $row ) {
						foreach ( $row as $key => $value ) {
							$gateway_key = '';
							if ( 'gateway_id' === $key ) {
								$gateway_key = $value;
							}
							$modified_array[ $key ][ $i ] = $value;
						}
					}
					$row_html = get_option(
						'alg_woocommerce_checkout_fees_lowest_price_info_template',
						'<p><strong>%gateway_title%</strong> %product_gateway_price% (%product_price_diff%)</p>'
					);
					foreach ( $modified_array as $key => $values_array ) {
						$values_array = array_unique( $values_array );
						$payment_fees = (int) get_option( 'alg_gateways_fees_value_' . $gateway_key, 0 );
						if ( in_array( $key, $price_keys ) ) {
							if ( count( $values_array ) > 1 ) {
								$value = wc_price( min( $values_array ) ) . '&ndash;' . wc_price( max( $values_array ) );
								if ( '0' === $the_product->get_price() && 0 >= $payment_fees ) {
									$value = wc_price( 0 ) . '&ndash;' . wc_price( 0 );
								}
							} else {
								$value = wc_price( min( $values_array ) );
								if ( '0' === $the_product->get_price() && 0 >= $payment_fees ) {
									$value = wc_price( 0 );
								}
							}
						} else {
							$value = implode( '<br>', $values_array );
						}
						$row_html = str_replace( '%' . $key . '%', $value, $row_html );
					}
					$final_html = $row_html;
				}
			}

			return $final_html;
		}

	}

endif;

return new Alg_WC_Checkout_Fees_Info();
