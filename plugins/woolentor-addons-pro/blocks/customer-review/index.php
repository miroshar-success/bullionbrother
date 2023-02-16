<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'woolentor_block_customer_review' );
!empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';
!empty( $settings['className'] ) ? $areaClasses[] = $settings['className'] : '';

!empty( $settings['columns']['desktop'] ) ? $areaClasses[] = 'woolentor-grid-columns-'.$settings['columns']['desktop'] : 'woolentor-grid-columns-4';
!empty( $settings['columns']['laptop'] ) ? $areaClasses[] = 'woolentor-grid-columns-laptop-'.$settings['columns']['laptop'] : 'woolentor-grid-columns-laptop-3';
!empty( $settings['columns']['tablet'] ) ? $areaClasses[] = 'woolentor-grid-columns-tablet-'.$settings['columns']['tablet'] : 'woolentor-grid-columns-tablet-2';
!empty( $settings['columns']['mobile'] ) ? $areaClasses[] = 'woolentor-grid-columns-mobile-'.$settings['columns']['mobile'] : 'woolentor-grid-columns-mobile-1';

!empty( $settings['reviewStyle'] ) ? $areaClasses[] = 'wl-customer-review wlb-review-style-'.$settings['reviewStyle'] : 'wl-customer-review wlb-review-style-1';

$image_size = $settings['imageSize'] ? $settings['imageSize'] : 'full';
// Generate review
$review_list = [];
if( $settings['reviewType'] === 'custom' ){
    foreach ( $settings['customerReviewList'] as $review ){
        $review_list[] = array(
            'image' => $review['image']['id'] ? wp_get_attachment_image( $review['image']['id'], $image_size ) : '',
            'name' => $review['name'],
            'designation' => $review['designation'],
            'ratting' => $review['rating'],
            'message' => $review['message'],
        );
    }
}else{

    if( $settings['reviewType'] == 'allproduct' ){
        
        $args = array(
            'status'=> 'approve',
            'type'  => 'review',
        );

        if( !empty( $settings['limit'] ) ){
            $args['number'] = $settings['limit'];
        }

        if( !empty( $settings['offset'] ) ){
            $args['offset'] = $settings['offset'];
        }

        // The Query
        $comments_query = new \WP_Comment_Query;
        $comments = $comments_query->query( $args );

    }else if( $settings['reviewType'] == 'dynamic' ){
        if( $block['is_editor'] ){
            $proid = woolentor_get_last_product_id();
        }else{
            global $product;
            $product = wc_get_product();
            if ( empty( $product ) ) { return; }
            $proid = $product->get_id();
        }
        if( empty( $proid ) ){
            echo esc_html__( 'Product not found.', 'woolentor-pro' );
            return;
        }else{
            $comments = get_comments( 'post_id=' . $proid );
        }
    }else{
        $proid = $settings['productIds'];
        if( empty( $proid ) ){
            echo esc_html__( 'Please select product.', 'woolentor-pro' );
            return;
        }else{
            $comments = get_comments( 'post_id=' . $proid );
        }
    }
    if ( !$comments ){
        echo esc_html__( 'No Review Found', 'woolentor-pro' );
        return;
    }
    foreach ( $comments as $comment ) {

        $rating = intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );
        $user_id   = get_comment( $comment->comment_ID )->user_id;
        $user_info = get_userdata( $user_id );

        $review_list[] = array(
            'image' => ( ( !empty( $settings['showImage'] ) && $settings['showImage'] == true ) ? get_avatar( $comment, '150' ) : '' ),
            'name' => get_comment_author( $comment ),
            'designation' => ( !empty( $user_info->roles ) ? implode( ', ', $user_info->roles ): '' ) ,
            'ratting' => $rating,
            'message' => $comment->comment_content,
        );

    }

}
$rowClass = array( 'woolentor-grid' );
if( $settings['noGutter'] ){
    $rowClass[] = 'woolentor-no-gutters';
}
echo '<div class="'.implode(' ', $areaClasses ).'">';
    echo '<div class="'.implode(' ', $rowClass ).'">';
        foreach ( $review_list as $review ){
            ?>
            <div class="woolentor-grid-item">
                <?php if( $settings['reviewStyle'] == '2' || $settings['reviewStyle'] == '3' ): ?>
                    <div class="wl-customer-testimonal">
                        <?php
                            if( $review['image'] ){
                                echo $review['image'];
                            }
                        ?>
                        <div class="content">
                            <?php
                                if( !empty($review['message']) ){
                                    echo '<p>'.esc_html__( $review['message'],'woolentor-pro' ).'</p>';
                                }
                            ?>
                            <div class="clint-info">
                                <?php
                                    if( !empty( $review['name'] ) ){
                                        echo '<h4>'.esc_html__( $review['name'],'woolentor-pro' ).'</h4>';
                                    }
                                    if( !empty( $review['designation'] ) ){
                                        echo '<span>'.esc_html__( $review['designation'],'woolentor-pro' ).'</span>';
                                    }

                                    // Rating
                                    if( !empty( $review['ratting'] ) ){
                                        woolentorBlocks_ratting( $review['ratting'] );
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                <?php elseif( $settings['reviewStyle'] == 4 ): ?>
                    <div class="wl-customer-testimonal">
                        <div class="content">
                            <?php
                                if( !empty($review['message']) ){
                                    echo '<p>'.esc_html__( $review['message'],'woolentor-pro' ).'</p>';
                                }
                            ?>
                            <div class="triangle"></div>
                        </div>
                        <div class="clint-info">
                            <?php
                                if( $review['image'] ){
                                    echo $review['image'];
                                }

                                if( !empty( $review['name'] ) ){
                                    echo '<h4>'.esc_html__( $review['name'],'woolentor-pro' ).'</h4>';
                                }

                                if( !empty( $review['designation'] ) ){
                                    echo '<span>'.esc_html__( $review['designation'],'woolentor-pro' ).'</span>';
                                }

                                // Rating
                                if( !empty( $review['ratting'] ) ){
                                    woolentorBlocks_ratting( $review['ratting'] );
                                }

                            ?>
                        </div>
                    </div>
                <?php else:?>
                    <div class="wl-customer-testimonal">
                        <div class="content">
                            <?php
                                if( $review['image'] ){
                                    echo $review['image'];
                                }
                            ?>
                            <div class="clint-info">
                                <?php
                                    if( !empty( $review['name'] ) ){
                                        echo '<h4>'.esc_html__( $review['name'],'woolentor-pro' ).'</h4>';
                                    }
                                    if( !empty( $review['designation'] ) ){
                                        echo '<span>'.esc_html__( $review['designation'],'woolentor-pro' ).'</span>';
                                    }
                                    
                                    // Rating
                                    if( !empty( $review['ratting'] ) ){
                                        woolentorBlocks_ratting( $review['ratting'] );
                                    }

                                ?>
                            </div>
                        </div>
                        <?php
                            if( !empty($review['message']) ){
                                echo '<p>'.esc_html__( $review['message'],'woolentor-pro' ).'</p>';
                            }
                        ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php
        }
    echo '</div>';
echo '</div>';