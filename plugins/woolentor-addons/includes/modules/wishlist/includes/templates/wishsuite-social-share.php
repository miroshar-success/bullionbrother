<?php 
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	$idsString = is_array( $products_ids ) ? implode( ',',$products_ids ) : '';

	$share_link = get_the_permalink() . '?wishsuitepids='.$idsString;
	$share_title = get_the_title();

	$thumb_id = get_post_thumbnail_id();
	$thumb_url = wp_get_attachment_image_src( $thumb_id, 'thumbnail-size', true );

	$social_button_list = [
		'facebook' => [
			'title' => esc_html__( 'Facebook', 'wishsuite' ),
			'url' 	=> 'https://www.facebook.com/sharer/sharer.php?u='.$share_link,
		],
		'twitter' => [
			'title' => esc_html__( 'Twitter', 'wishsuite' ),
			'url' 	=> 'https://twitter.com/share?url=' . $share_link.'&amp;text='.$share_title,
		],
		'pinterest' => [
			'title' => esc_html__( 'Pinterest', 'wishsuite' ),
			'url' 	=> 'https://pinterest.com/pin/create/button/?url='.$share_link.'&media='.$thumb_url[0],
		],
		'linkedin' => [
			'title' => esc_html__( 'Linkedin', 'wishsuite' ),
			'url' 	=> 'https://www.linkedin.com/shareArticle?mini=true&url='.$share_link.'&amp;title='.$share_title,
		],
		'email' => [
			'title' => esc_html__( 'Email', 'wishsuite' ),
			'url' 	=> 'mailto:?subject='.esc_html__('Whislist&body=My whislist:', 'wishsuite') . $share_link,
		],
		'reddit' => [
			'title' => esc_html__( 'Reddit', 'wishsuite' ),
			'url' 	=> 'http://reddit.com/submit?url='.$share_link.'&amp;title='.$share_title,
		],
		'telegram' => [
			'title' => esc_html__( 'Telegram', 'wishsuite' ),
			'url' 	=> 'https://telegram.me/share/url?url=' . $share_link,
		],
		'odnoklassniki' => [
			'title' => esc_html__( 'Odnoklassniki', 'wishsuite' ),
			'url' 	=> 'https://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st._surl=' . $share_link,
		],
		'whatsapp' => [
			'title' => esc_html__( 'WhatsApp', 'wishsuite' ),
			'url' 	=> 'https://wa.me/?text=' . $share_link,
		],
		'vk' => [
			'title' => esc_html__( 'VK', 'wishsuite' ),
			'url' 	=> 'https://vk.com/share.php?url=' . $share_link,
		],
	];


	$default_buttons = [
        'facebook'   => esc_html__( 'Facebook', 'wishsuite' ),
        'twitter'    => esc_html__( 'Twitter', 'wishsuite' ),
        'pinterest'  => esc_html__( 'Pinterest', 'wishsuite' ),
        'linkedin'   => esc_html__( 'Linkedin', 'wishsuite' ),
        'telegram'   => esc_html__( 'Telegram', 'wishsuite' ),
    ];
	$button_list = woolentor_get_option( 'social_share_buttons','wishsuite_table_settings_tabs', $default_buttons );
	$button_text = woolentor_get_option( 'social_share_button_title','wishsuite_table_settings_tabs', 'Share:' );

?>

<div class="wishsuite-social-share">
	<span class="wishsuite-social-title"><?php esc_html_e( $button_text, 'wishsuite' ); ?></span>
	<ul>
		<?php
			foreach ( $button_list as $buttonkey => $button ) {
				?>
				<li>
					<a rel="nofollow" href="<?php echo esc_url( $social_button_list[$buttonkey]['url'] ); ?>" <?php echo ( $buttonkey === 'email' ? '' : 'target="_blank"' ) ?>>
						<span class="wishsuite-social-icon">
							<?php echo wishsuite_icon_list( $buttonkey ); ?>
						</span>
					</a>
				</li>
				<?php
			}
		?>
	</ul>
</div>