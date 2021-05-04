<?php
/* load template */
function alx_ext_sharrre_footer_actions() {
	add_action( 'alx_ext_sharrre_footer', 'alx_ext_sharrre_footer_template' );
}
add_action( 'plugins_loaded', 'alx_ext_sharrre_footer_actions' );

/* template */
function alx_ext_sharrre_footer_template() {

	$enable_social_share = get_theme_mod( 'enable-social-share', true );
	if ( true !== $enable_social_share ) {
		return;
	}
	?>

	<div class="sharrre-footer group">
		<div id="facebook-footer" data-url="<?php echo the_permalink(); ?>" data-text="<?php echo the_title(); ?>" data-title="<?php esc_attr_e('Share', 'alx'); ?>"></div>
		<div id="twitter-footer" data-url="<?php echo the_permalink(); ?>" data-text="<?php echo the_title(); ?>" data-title="<?php esc_attr_e('Share', 'alx'); ?>"></div>
	</div><!--/.sharrre-footer-->

	<script type="text/javascript">
		// Sharrre
		jQuery(document).ready(function(){
			jQuery('#twitter-footer').sharrre({
				share: {
					twitter: true
				},
				template: '<a class="box group" href="#"><div class="share"><i class="fab fa-twitter"></i><?php esc_html_e('Share', 'alx'); ?> <span><?php esc_html_e('on Twitter', 'alx'); ?></span><div class="count" href="#"><i class="fas fa-plus"></i></div></div></a>',
				enableHover: false,
				enableTracking: true,
				buttons: { twitter: {via: '<?php echo esc_attr( get_theme_mod('twitter-username') ); ?>'}},
				click: function(api, options){
					api.simulateClick();
					api.openPopup('twitter');
				}
			});
			jQuery('#facebook-footer').sharrre({
				share: {
					facebook: true
				},
				template: '<a class="box group" href="#"><div class="share"><i class="fab fa-facebook-square"></i><?php esc_html_e('Share', 'alx'); ?> <span><?php esc_html_e('on Facebook', 'alx'); ?></span><div class="count" href="#"><i class="fas fa-plus"></i></div></div></a>',
				enableHover: false,
				enableTracking: true,
				buttons:{layout: 'box_count'},
				click: function(api, options){
					api.simulateClick();
					api.openPopup('facebook');
				}
			});
		});
	</script>
	<?php
}
