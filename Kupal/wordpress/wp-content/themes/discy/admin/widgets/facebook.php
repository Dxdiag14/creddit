<?php
/* Facebook */
add_action( 'widgets_init', 'discy_widget_facebook_widget' );
function discy_widget_facebook_widget() {
	register_widget( 'Widget_Facebook' );
}
class Widget_Facebook extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'facebook-widget' );
		$control_ops = array( 'id_base' => 'facebook-widget' );
		parent::__construct( 'facebook-widget',discy_theme_name.' - Facebook', $widget_ops, $control_ops );
	}
	
	public function widget( $args, $instance ) {
		extract( $args );
		$title		   = apply_filters('widget_title', $instance['title'] );
		$facebook_link = esc_url($instance['facebook_link']);
		$width         = esc_attr($instance['width']);
		$height        = esc_attr($instance['height']);
		$border_color  = esc_attr($instance['border_color']);
		$background    = esc_attr($instance['background']);
			
		echo ($before_widget);
			if ($title) {
				echo ($title == "empty"?"<div class='empty-title'>":"").($before_title.($title == "empty"?"":esc_attr($title)).$after_title).($title == "empty"?"</div>":"");
			}else {
				echo "<h3 class='screen-reader-text'>".esc_html__("Facebook","discy")."</h3>";
			}?>
			<div class="widget-wrap">
				<div class="facebook_widget">
				    <iframe src="//www.facebook.com/plugins/likebox.php?href=<?php echo esc_url($facebook_link)?>&amp;width=<?php echo esc_attr($width)?>&amp;colorscheme=light&amp;show_faces=true&amp;border_color=%23<?php echo ($border_color)?>&amp;stream=false&amp;header=false&amp;height=<?php echo ($height)?>" style="border:none; overflow:hidden; width:<?php echo ($width)?>px; height:<?php echo ($height)?>px;"></iframe>
				</div>
			</div>
		<?php echo ($after_widget);
	}
	
	public function form( $instance ) {
		/* Save Button */
	}
}?>