<?php

/* @author    2codeThemes
*  @package   WPQA/widgets
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
   exit; // Exit if accessed directly
}

/* Login */
add_action( 'widgets_init', 'wpqa_widget_login_widget' );
function wpqa_widget_login_widget() {
	register_widget( 'Widget_Login' );
}

class Widget_Login extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'login-widget' );
		$control_ops = array( 'id_base' => 'login-widget' );
		parent::__construct( 'login-widget',wpqa_widgets.' - Login', $widget_ops, $control_ops );
	}
	
	public function widget( $args, $instance ) {
		extract( $args );
		if (!is_user_logged_in()) {
			$title = apply_filters('widget_title', $instance['title'] );
			echo ($before_widget);
				if ($title) {
					echo ($title == "empty"?"<div class='empty-title'>":"").($before_title.($title == "empty"?"":esc_attr($title)).$after_title).($title == "empty"?"</div>":"");
				}else {
					echo "<h3 class='screen-reader-text'>".esc_html__("Login","wpqa")."</h3>";
				}?>
				<div class="widget-wrap">
					<div class="widget_login">
						<div class="comment-form vpanel-form">
							<?php echo do_shortcode("[wpqa_login]")?>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
			<?php echo ($after_widget);
		}
	}

	public function form( $instance ) {
		/* Save Button */
	}
}?>