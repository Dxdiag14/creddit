<?php

/* @author    2codeThemes
*  @package   WPQA/widgets
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
   exit; // Exit if accessed directly
}

/* Rules */
add_action( 'widgets_init', 'wpqa_widget_rules_widget' );
function wpqa_widget_rules_widget() {
	register_widget( 'Widget_Rules' );
}

class Widget_Rules extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'rules-widget' );
		$control_ops = array( 'id_base' => 'rules-widget' );
		parent::__construct( 'rules-widget',wpqa_widgets.' - Group Rules', $widget_ops, $control_ops );
	}
	
	public function widget( $args, $instance ) {
		extract( $args );
		if (is_singular("group") || wpqa_is_view_posts_group() || wpqa_is_edit_posts_group()) {
			if (wpqa_is_view_posts_group()) {
				$post_id = (int)get_query_var(apply_filters('wpqa_view_posts_group','view_post_group'));
				$group_id = (int)get_post_meta($post_id,"group_id",true);
			}else if (wpqa_is_edit_posts_group()) {
				$post_id = (int)get_query_var(apply_filters('wpqa_edit_posts_group','edit_post_group'));
				$group_id = (int)get_post_meta($post_id,"group_id",true);
			}else {
				global $post;
				$group_id = $post->ID;
			}
			$group_rules = get_post_meta($group_id,"group_rules",true);
			if ($group_rules != "") {
				$title = apply_filters('widget_title', $instance['title'] );
				echo ($before_widget);
					if ($title) {
						echo ($title == "empty"?"<div class='empty-title'>":"").($before_title.($title == "empty"?"":esc_attr($title)).$after_title).($title == "empty"?"</div>":"");
					}else {
						echo "<h3 class='screen-reader-text'>".esc_html__("Group Rules","wpqa")."</h3>";
					}?>
					<div class="widget-wrap">
						<div class="widget_group_rules">
							<div class="less_group_rules"><?php echo wpqa_excerpt_any(40,do_shortcode(wpqa_kses_stip(nl2br(stripslashes($group_rules)))),'<a class="read_more_rules" href="#">'.esc_html__("See more","wpqa").'</a>','words')?></div>
							<div class="wpqa_hide full_group_rules"><?php echo do_shortcode(wpqa_kses_stip(nl2br(stripslashes($group_rules)))).'<a class="read_less_rules" href="#">'.esc_html__("See less","wpqa").'</a>'?></div>
						</div>
					</div>
				<?php echo ($after_widget);
			}
		}
	}

	public function form( $instance ) {
		/* Save Button */
	}
}?>