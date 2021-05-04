<?php

/* @author    2codeThemes
*  @package   WPQA/widgets
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
   exit; // Exit if accessed directly
}

/* Notifications */
add_action( 'widgets_init', 'wpqa_widget_notifications_widget' );
function wpqa_widget_notifications_widget() {
	$active_notifications = wpqa_options("active_notifications");
	if ($active_notifications == "on") {
		register_widget( 'Widget_Notifications' );
	}
}
class Widget_Notifications extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'notifications-widget' );
		$control_ops = array( 'id_base' => 'notifications-widget' );
		parent::__construct( 'notifications-widget',wpqa_widgets.' - Notifications', $widget_ops, $control_ops );
	}
	
	public function widget( $args, $instance ) {
		if (is_user_logged_in()) {
			extract( $args );
			$title       = apply_filters('widget_title', $instance['title'] );
			$item_number = esc_attr($instance['item_number']);
			$more_button = esc_attr($instance['more_button']);
			
			if (empty($more_button) || $more_button != "on") {
				$before_widget = str_replace('class="','class="widget-no-button ',$before_widget);
			}
			
			echo ($before_widget);
				if ($title) {
					echo ($title == "empty"?"<div class='empty-title'>":"").($before_title.($title == "empty"?"":esc_attr($title)).$after_title).($title == "empty"?"</div>":"");
				}else {
					echo "<h3 class='screen-reader-text'>".esc_html__("Notifications","wpqa")."</h3>";
				}?>
				<div class="widget-wrap">
				    <div class="user-notifications user-profile-area">
				    	<?php 
				    	$user_id = get_current_user_id();
				    	echo wpqa_get_notifications($user_id,$item_number,$more_button)?>
				    </div><!-- End user-notifications -->
				</div>
			<?php echo ($after_widget);
		}
	}

	public function form( $instance ) {
		/* Save Button */
	}
}?>