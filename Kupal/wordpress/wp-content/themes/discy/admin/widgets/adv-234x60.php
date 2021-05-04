<?php
/* Adv 234x60 */
add_action( 'widgets_init', 'discy_widget_adv234x60_widget' );
function discy_widget_adv234x60_widget() {
	register_widget( 'Widget_Adv234x60' );
}
class Widget_Adv234x60 extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'adv234x60-widget' );
		$control_ops = array( 'id_base' => 'adv234x60-widget' );
		parent::__construct( 'adv234x60-widget',discy_theme_name.' - Adv 234x60', $widget_ops, $control_ops );
	}
	
	public function widget( $args, $instance ) {
		extract( $args );
		$title    = apply_filters('widget_title', $instance['title'] );
		$show_ads = discy_check_without_ads();
		if ($show_ads == true) {
			$adv_type = esc_attr($instance['adv_type']);
			$adv_href = esc_url($instance['adv_href']);
			$adv_img  = esc_attr(discy_image_url_id($instance['adv_img']));
			$adv_code = $instance['adv_code'];
			echo ($before_widget);
				if ($title) {
					echo ($title == "empty"?"<div class='empty-title'>":"").($before_title.($title == "empty"?"":esc_attr($title)).$after_title).($title == "empty"?"</div>":"");
				}else {
					echo "<h3 class='screen-reader-text'>".esc_html__("Adv 234x60","discy")."</h3>";
				}?>
				<div class="discy-ad-wrap">
					<?php echo discy_widget_ads($adv_type,$adv_href,$adv_img,$adv_code)?>
					<div class="clearfix"></div>
				</div>
			<?php echo ($after_widget);
		}
	}

	public function form( $instance ) {
		/* Save Button */
	}
}?>