<?php
namespace Woolentor\Modules\Order_Bump;

// WP_List_Table is not loaded automatically so we need to load it in our application
if ( !class_exists('WP_List_Table') ) {
    require_once(ABSPATH . 'wp-admin/includes/screen.php');
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class Order_Bumps_List_Table extends \WP_List_Table{
	/**
     * Define table columns
     */
    public function get_columns(){
        $columns = array(
            'name'          => __( 'Title', 'woolentor-pro' ),
            'status'        => __( 'Status', 'woolentor-pro' ),
            'offer_product' => __( 'Offer Product', 'woolentor-pro' ),
            'price' 		=> __( 'Price', 'woolentor-pro' ),
			'discount'      => __( 'Discounted Price', 'woolentor-pro' ),
            'position' 		=> __( 'Display Location', 'woolentor-pro' ),
            'date'          => __( 'Date', 'woolentor-pro' ),
            'id'            => __( 'ID #', 'woolentor-pro' ),
        );

        return $columns;
    }

	/**
     * Bind table with columns, data and all
     */
	public function prepare_items(){
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = array();
		$this->_column_headers = array($columns, $hidden, $sortable);
		
		$this->items = $this->fetch_table_data();
	}

    /**
     * It fetches all the posts of the custom post type 'woolentor-template' and returns an array
     * 
     * @return An array of data.
     */
    private function fetch_table_data(){
        $data = array();
        $args = array(
            'numberposts'   => -1,
            'post_type'     => 'woolentor-template',
			'post_status'   => array('publish', 'draft', 'future'),
            'orderby'       => 'meta_value',
            'order'         => 'ASC',

			// Meta query to fetch only the posts that have the meta key '_woolentor_order_bump'
			'meta_query'    => array(
				array(
					'key'       => '_woolentor_order_bump',
					'compare'   => 'EXISTS',
				),
			),
		);

        $data = get_posts( $args );

        return $data;
    }

    /**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default( $post, $column_name ){
        switch( $column_name ) {
            case 'id':
                return $post->ID;
				
            case 'name':
                return get_the_title($post->ID);

            default:
                return '';
        }
    }

   /**
	* Display the name of order bump.
	* 
	* @param post The post object.
	*/
    public function column_name( $post ) {
		$edit_link = get_edit_post_link( $post );
		$edit_link = add_query_arg( 'template_type', 'order-bump', $edit_link );
        $title = _draft_or_post_title( $post );

		printf(
			'<strong><a class="row-title" href="%s" aria-label="%s">%s</a></strong>',
			$edit_link,
			/* translators: %s: Link name. */
			esc_attr( sprintf( __( 'Edit &#8220;%s&#8221;' ), $title ) ),
			$title
		);
	}

	/**
	 * It outputs a checkbox switcher.
	 * 
	 * @param post The post object.
	 */
	public function column_status( $post ) {
		$status 	= !empty( $post->post_status ) ? $post->post_status : 'draft';
		$meta_data  = get_post_meta( $post->ID, '_woolentor_order_bump', true );
		$post_id 	= !empty( $post->ID ) ? $post->ID : '';
		$checked    = $status === 'publish' ? 'checked="checked"' : '';

		$validate_generate_step = Manage_Rules::instance()->validate_general_step( $post->ID );
		if( $validate_generate_step !== true ){
			$arr = wp_parse_args( $validate_generate_step, array(
				'status' => '',
				'case'  => '',
				'message' => ''
			) );
			
			echo wp_kses_post( $arr['status'] );
			echo wc_help_tip( $arr['message'] );
			
			return;
		}

		// Show the Quick Status change after the above checks.
		printf(
			'<label class="woolentor-order-bump-status-switch" id="woolentor-order-bump-id-%1$s">
				<input class="woolentor-order-bump-status" id="woolentor-order-bump-id-%1$s" type="checkbox" value="%1$s" %4$s>
				<span>
					<span>%2$s</span>	
					<span>%3$s</span>
				</span>
				<a>&nbsp;</a>
			</label>',
			$post_id,
			__( 'Inactive', 'woolentor-pro' ),
			__( 'Active', 'woolentor-pro' ),
			$checked
		);
	}

	/**
	 * It displays the title of the product that is selected as offer product.
	 * 
	 * @param post The post object.
	 */
	public function column_offer_product( $post ) {
		$meta_data = get_post_meta( $post->ID, '_woolentor_order_bump', true );
		$product_id = !empty( $meta_data['product'] ) ? $meta_data['product'] : '0';

		printf(
			'<a href="%s">%s</a>',
			get_edit_post_link( $product_id ),
			get_the_title( $product_id )
		);
	}

	/**
	 * It displays the price of the product in the table
	 * 
	 * @param post The post object.
	 */
	public function column_price( $post ) {
		$meta_data  = get_post_meta( $post->ID, '_woolentor_order_bump', true );
		$product_id = !empty( $meta_data['product'] ) ? $meta_data['product'] : 0;
		$product    = wc_get_product( $product_id );

		// Don't show the price for the exteptions below.
		if( empty($product) && !is_object($product) ){
			echo '-';
			return;
		}
		
		echo wp_kses_post( $product->get_price_html() );
	}

	/**
	 * Displays the type of discount applied for the offer product.
	 * 
	 * @param post The post object.
	 */
	public function column_discount( $post ) {
		$order_bump_id	  = $post->ID;
		$meta_data		  = get_post_meta( $order_bump_id, '_woolentor_order_bump', true );
		$product_id 	  = !empty( $meta_data['product'] ) ? $meta_data['product'] : 0;
		$offer_product 	  = wc_get_product($product_id);
		$discount_type 	  = !empty( $meta_data['discount_type'] ) ? $meta_data['discount_type'] : '';
		$discount_amount  = !empty( $meta_data['discount_amount'] ) ? $meta_data['discount_amount'] : 0;
		$tooltip_text  	  = '';

		// Don't show the discounted price for the exteptions below.
		if( empty($meta_data['product']) || !is_object($offer_product) ){
			echo '-';
			return;
		}

		// If there is no discount type is set
		if( !$discount_type ){
			echo __('No Discount Applied', 'woolentor-pro');
			return;
		}

		if( empty($discount_amount) ){
			echo '-';
			return;
		}

		$discounted_price = Helper::get_discounted_price( $order_bump_id, $offer_product, '' );
		if( $discounted_price == $offer_product->get_price() ){
			echo '-';
			return;
		}

		$discount_base_price = Helper::get_option($order_bump_id, 'discount_base_price', 'regular_price');
		$discount_base_price_text =  ucwords(str_replace('_', ' ', $discount_base_price));

		if( $offer_product->is_type('variable') ){
			$discounted_prices = Helper::get_discounted_prices( $order_bump_id, $offer_product, '' );

			if ( $discounted_prices['min'] !== $discounted_prices['max'] ) {
				$new_price_html = sprintf('<span class="woolentor-order-bump-price-range-new">%s</span>',
                    wc_format_price_range( $discounted_prices['min'], $discounted_prices['max'] )
                );
			} else {
				$new_price_html = wc_price( $discounted_prices['min'] );
			}
			
		} else {
			$new_price_html = wc_price( $discounted_price );
		}

		// Proceed to show the discounted price after the above checks.
		if( $discount_type === 'percent_amount' ){
			$tooltip_text = sprintf(
				__( 'That\'s because you used: <br> Base Price = %s. <br> Type = Percentage Amount. <br> Amount = %s.', 'woolentor-pro' ),
				$discount_base_price_text,
				$discount_amount
			);
		} elseif( $discount_type === 'fixed_amount' ){
			$tooltip_text = sprintf(
				__( 'That\'s because you used: <br> Base Price = %s. <br> Type = Fixed Amount. <br> Amount = %s.', 'woolentor-pro' ),
				$discount_base_price_text,
				$discount_amount
			);
		} elseif( $discount_type === 'fixed_price' ){
			$tooltip_text = sprintf(
				__( 'That\'s because you used: <br> Base Price = %s. <br> Type = Fixed Price. <br> Amount = %s.', 'woolentor-pro' ),
				$discount_base_price_text,
				$discount_amount
			);
		}

		printf(
			'%s %s',
			$new_price_html,
			wc_help_tip( $tooltip_text )
		);
	}

	/**
	 * It displays the title of the product that is selected as offer product.
	 * 
	 * @param post The post object.
	 */
	public function column_position( $post ) {
		$meta_data     = get_post_meta( $post->ID, '_woolentor_order_bump', true );
		$position      = !empty( $meta_data['position'] ) ? $meta_data['position'] : 'before_order_details';
		$postion_hooks = Helper::get_postion_hooks( 'post_column' );

		echo esc_html( $postion_hooks[$position] );
	}

	/**
	 * Row quick actions.
	 */
	protected function handle_row_actions( $post, $column_name, $primary ) {
		if ( $primary !== $column_name ) {
			return '';
		}

		// Restores the more descriptive, specific name for use within this method.
		$edit_link = get_edit_post_link( $post );
		$edit_link = add_query_arg( 'template_type', 'order-bump', $edit_link );
		$title 	   = _draft_or_post_title( $post );

		$actions           = array();
		$actions['edit']   = '<a href="' . $edit_link . '">' . __( 'Edit' ) . '</a>';
		$actions['delete'] = sprintf(
			'<a href="%s" class="submitdelete" onclick="return confirm( \'%s\' );">%s</a>',
			get_delete_post_link( $post->ID, '', true ),
			/* translators: %s: Post title. */
			esc_js( sprintf( __( "You are about to delete this item '%s'\n  'Cancel' to stop, 'OK' to delete.", 'woolentor-pro' ), $title ) ),
			__( 'Delete', 'woolentor-pro' )
		);

		return $this->row_actions( $actions );
	}

    /**
	 * Handles the post date column output.
	 *
	 * @since 4.3.0
	 *
	 * @global string $mode List table view mode.
	 *
	 * @param WP_Post $post The current WP_Post object.
	 */
	public function column_date( $post ) {
		global $mode;

		if ( '0000-00-00 00:00:00' === $post->post_date ) {
			$t_time    = __( 'Unpublished' );
			$time_diff = 0;
		} else {
			$t_time = sprintf(
				/* translators: 1: Post date, 2: Post time. */
				__( '%1$s at %2$s' ),
				/* translators: Post date format. See https://www.php.net/manual/datetime.format.php */
				get_the_time( __( 'Y/m/d' ), $post ),
				/* translators: Post time format. See https://www.php.net/manual/datetime.format.php */
				get_the_time( __( 'g:i a' ), $post )
			);

			$time      = get_post_timestamp( $post );
			$time_diff = time() - $time;
		}

		if ( 'publish' === $post->post_status ) {
			$status = __( 'Published' );
		} elseif ( 'future' === $post->post_status ) {
			if ( $time_diff > 0 ) {
				$status = '<strong class="error-message">' . __( 'Missed schedule', 'woolentor-pro' ) . '</strong>';
			} else {
				$status = __( 'Scheduled', 'woolentor-pro' );
			}
		} else {
			$status = __( 'Last Modified', 'woolentor-pro' );
		}

		/**
		 * Filters the status text of the post.
		 *
		 * @since 4.8.0
		 *
		 * @param string  $status      The status text.
		 * @param WP_Post $post        Post object.
		 * @param string  $column_name The column name.
		 * @param string  $mode        The list display mode ('excerpt' or 'list').
		 */
		$status = apply_filters( 'post_date_column_status', $status, $post, 'date', $mode );

		if ( $status ) {
			echo wp_kses_post($status) . '<br />';
		}

		/**
		 * Filters the published time of the post.
		 *
		 * @since 2.5.1
		 * @since 5.5.0 Removed the difference between 'excerpt' and 'list' modes.
		 *              The published time and date are both displayed now,
		 *              which is equivalent to the previous 'excerpt' mode.
		 *
		 * @param string  $t_time      The published time.
		 * @param WP_Post $post        Post object.
		 * @param string  $column_name The column name.
		 * @param string  $mode        The list display mode ('excerpt' or 'list').
		 */
		echo apply_filters( 'post_date_column_time', $t_time, $post, 'date', $mode );
    }
}