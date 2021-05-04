<?php
/* Subscribe */
add_action( 'widgets_init', 'discy_widget_subscribe_widget' );
function discy_widget_subscribe_widget() {
	register_widget( 'Widget_Subscribe' );
}
class Widget_Subscribe extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'subscribe-widget' );
		$control_ops = array( 'id_base' => 'subscribe-widget' );
		parent::__construct( 'subscribe-widget',discy_theme_name.' - Subscribe', $widget_ops, $control_ops );
	}
	
	public function widget( $args, $instance ) {
		extract( $args );
		$title      = apply_filters('widget_title', $instance['title'] );
		$feedburner = esc_attr($instance['feedburner']);

		echo ($before_widget);
			if ($title) {
				echo ($title == "empty"?"<div class='empty-title'>":"").($before_title.($title == "empty"?"":esc_attr($title)).$after_title).($title == "empty"?"</div>":"");
			}else {
				echo "<h3 class='screen-reader-text'>".esc_html__("Subscribe","discy")."</h3>";
			}?>
			<div class="widget-wrap">
			    <form action="https://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open('https://feedburner.google.com/fb/a/mailverify?uri=<?php echo esc_attr($feedburner)?>', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true">
			        <input name="email" type="text" value="<?php esc_attr_e("Type Your Email","discy");?>" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;">
			        <input type="hidden" value="<?php echo esc_attr($feedburner); ?>" name="uri">
			        <input type="hidden" name="loc" value="en_US">
			        <button name="submit" type="submit" class="button-default"><i class="icon-right-open"></i></button>
			    </form>
			</div>
		<?php echo ($after_widget);
	}

	public function form( $instance ) {
		/* Save Button */
	}
}?>