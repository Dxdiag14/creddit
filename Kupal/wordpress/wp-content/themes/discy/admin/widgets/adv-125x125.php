<?php
/* Adv 125x125 */
add_action( 'widgets_init', 'discy_widget_adv125x125_widget' );
function discy_widget_adv125x125_widget() {
	register_widget( 'Widget_Adv125x125' );
}
class Widget_Adv125x125 extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'adv125x125-widget' );
		$control_ops = array( 'id_base' => 'adv125x125-widget' );
		parent::__construct( 'adv125x125-widget',discy_theme_name.' - Adv 125x125', $widget_ops, $control_ops );
	}
	
	public function widget( $args, $instance ) {
		extract( $args );
		$title    = apply_filters('widget_title', $instance['title'] );
		$show_ads = discy_check_without_ads();
		if ($show_ads == true) {
			$adv_type_1 = esc_attr($instance['adv_type_1']);
			$adv_href_1 = esc_url($instance['adv_href_1']);
			$adv_img_1  = esc_attr(discy_image_url_id($instance['adv_img_1']));
			$adv_code_1 = $instance['adv_code_1'];
			
			$adv_type_2 = esc_attr($instance['adv_type_2']);
			$adv_href_2 = esc_url($instance['adv_href_2']);
			$adv_img_2  = esc_attr(discy_image_url_id($instance['adv_img_2']));
			$adv_code_2 = $instance['adv_code_2'];
			
			$adv_type_3 = esc_attr($instance['adv_type_3']);
			$adv_href_3 = esc_url($instance['adv_href_3']);
			$adv_img_3  = esc_attr(discy_image_url_id($instance['adv_img_3']));
			$adv_code_3 = $instance['adv_code_3'];
			
			$adv_type_4 = esc_attr($instance['adv_type_4']);
			$adv_href_4 = esc_url($instance['adv_href_4']);
			$adv_img_4  = esc_attr(discy_image_url_id($instance['adv_img_4']));
			$adv_code_4 = $instance['adv_code_4'];
			echo ($before_widget);
				if ($title) {
					echo ($title == "empty"?"<div class='empty-title'>":"").($before_title.($title == "empty"?"":esc_attr($title)).$after_title).($title == "empty"?"</div>":"");
				}else {
					echo "<h3 class='screen-reader-text'>".esc_html__("Adv 125x125","discy")."</h3>";
				}?>
				<div class="discy-ad-wrap">
					<div class="discy-ad discy-ad-4a">
						<?php echo discy_widget_ads($adv_type_1,$adv_href_1,$adv_img_1,$adv_code_1,'discy-ad-1').
						discy_widget_ads($adv_type_2,$adv_href_2,$adv_img_2,$adv_code_2,'discy-ad-1').
						discy_widget_ads($adv_type_3,$adv_href_3,$adv_img_3,$adv_code_3,'discy-ad-1').
						discy_widget_ads($adv_type_4,$adv_href_4,$adv_img_4,$adv_code_4,'discy-ad-1')?>
					</div><!-- End discy-ad -->
					<div class="clearfix"></div>
				</div>
			<?php echo ($after_widget);
		}
	}

	public function form( $instance ) {
		/* Save Button */
	}
}?>