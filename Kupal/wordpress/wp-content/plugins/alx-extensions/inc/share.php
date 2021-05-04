<?php
/* load template */
function alx_ext_sharrre_actions() {
	add_action( 'alx_ext_sharrre', 'alx_ext_sharrre_template' );
}
add_action( 'plugins_loaded', 'alx_ext_sharrre_actions' );

/* template */
function alx_ext_sharrre_template() {

	$enable_social_share = get_theme_mod( 'enable-social-share', true );
	if ( true !== $enable_social_share ) {
		return;
	}
	?>

	<div class="sharrre-container sharrre-header group">
		<span><?php esc_html_e('Share','alx'); ?></span>
		<div id="twitter" data-url="<?php the_permalink(); ?>" data-text="<?php echo the_title_attribute(); ?>" data-title="<?php esc_attr_e('Tweet', 'alx'); ?>"></div>
		<div id="facebook" data-url="<?php the_permalink(); ?>" data-text="<?php echo the_title_attribute(); ?>" data-title="<?php esc_attr_e('Like', 'alx'); ?>"></div>
		<div id="pinterest" data-url="<?php the_permalink(); ?>" data-text="<?php echo the_title_attribute(); ?>" data-title="<?php esc_attr_e('Pin It', 'alx'); ?>"></div>
		<div id="linkedin" data-url="<?php the_permalink(); ?>" data-text="<?php echo the_title_attribute(); ?>" data-title="<?php esc_attr_e('Share on LinkedIn', 'alx'); ?>"></div>
	</div><!--/.sharrre-container-->

	<script type="text/javascript">
		// Sharrre
		jQuery(document).ready(function(){
			jQuery('#twitter').sharrre({
				share: {
					twitter: true
				},
				template: '<a class="box group" href="#"><div class="count" href="#"><i class="fas fa-plus"></i></div><div class="share"><i class="fab fa-twitter"></i></div></a>',
				enableHover: false,
				enableTracking: true,
				buttons: { twitter: {via: '<?php echo esc_attr( get_theme_mod('twitter-username') ); ?>'}},
				click: function(api, options){
					api.simulateClick();
					api.openPopup('twitter');
				}
			});
			jQuery('#facebook').sharrre({
				share: {
					facebook: true
				},
				template: '<a class="box group" href="#"><div class="count" href="#"><i class="fas fa-plus"></i></div><div class="share"><i class="fab fa-facebook-square"></i></div></a>',
				enableHover: false,
				enableTracking: true,
				buttons:{layout: 'box_count'},
				click: function(api, options){
					api.simulateClick();
					api.openPopup('facebook');
				}
			});
			jQuery('#pinterest').sharrre({
				share: {
					pinterest: true
				},
				template: '<a class="box group" href="#"><div class="count" href="#"><i class="fas fa-plus"></i></div><div class="share"><i class="fab fa-pinterest"></i></div></a>',
				enableHover: false,
				enableTracking: true,
				buttons: {
				pinterest: {
					description: '<?php echo the_title(); ?>'<?php if( has_post_thumbnail() ){ ?>,media: '<?php echo wp_get_attachment_url( get_post_thumbnail_id() ); ?>'<?php } ?>
					}
				},
				click: function(api, options){
					api.simulateClick();
					api.openPopup('pinterest');
				}
			});
			jQuery('#linkedin').sharrre({
				share: {
					linkedin: true
				},
				template: '<a class="box group" href="#"><div class="count" href="#"><i class="fas fa-plus"></i></div><div class="share"><i class="fab fa-linkedin"></i></div></a>',
				enableHover: false,
				enableTracking: true,
				buttons: {
				linkedin: {
					description: '<?php echo the_title(); ?>'<?php if( has_post_thumbnail() ){ ?>,media: '<?php echo wp_get_attachment_url( get_post_thumbnail_id() ); ?>'<?php } ?>
					}
				},
				click: function(api, options){
					api.simulateClick();
					api.openPopup('linkedin');
				}
			});

		});
	</script>
	<?php
}
