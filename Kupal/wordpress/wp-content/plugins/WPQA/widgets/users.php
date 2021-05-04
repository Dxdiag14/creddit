<?php

/* @author    2codeThemes
*  @package   WPQA/widgets
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
   exit; // Exit if accessed directly
}

/* Users */
add_action( 'widgets_init', 'wpqa_widget_users_widget' );
function wpqa_widget_users_widget() {
	register_widget( 'Widget_Users' );
}

class Widget_Users extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'users-widget' );
		$control_ops = array( 'id_base' => 'users-widget' );
		parent::__construct( 'users-widget',wpqa_widgets.' - Users', $widget_ops, $control_ops );
	}
	
	public function widget( $args, $instance ) {
		global $wpdb;
		$active_points = wpqa_options("active_points");
		extract( $args );
		$start_of_week = get_option("start_of_week");
		if ($start_of_week == 0) {
			$start_of_week = "Sunday";
		}else if ($start_of_week == 1) {
			$start_of_week = "Monday";
		}else if ($start_of_week == 2) {
			$start_of_week = "Tuesday";
		}else if ($start_of_week == 3) {
			$start_of_week = "Wednesday";
		}else if ($start_of_week == 4) {
			$start_of_week = "Thursday";
		}else if ($start_of_week == 5) {
			$start_of_week = "Friday";
		}else if ($start_of_week == 6) {
			$start_of_week = "Saturday";
		}
		$title           = apply_filters('widget_title', $instance['title'] );
		$user_number     = esc_attr($instance['user_number']);
		$user_sort       = esc_attr($instance['user_sort']);
		$specific_points = (isset($instance['specific_points'])?esc_html($instance['specific_points']):"");
		$specific_time   = (isset($instance['specific_time'])?esc_html($instance['specific_time']):"");
		$user_order      = esc_attr($instance['user_order']);
		$user_group      = $instance['user_group'];
		$points_cat      = (isset($instance['points_categories'])?esc_html($instance['points_categories']):"");
		$crown_king      = (isset($instance['crown_king'])?esc_html($instance['crown_king']):"");
		$show_icon       = (isset($instance['show_icon'])?esc_html($instance['show_icon']):"");
		$points_category = wpqa_options("active_points_category");
		$active_points_specific = wpqa_options("active_points_specific");
		if ($points_cat !== "on" || ($points_cat === "on" && $points_category == "on" && is_tax("question-category"))) {
			if ($points_category == "on" && is_tax("question-category")) {
				$user_sort = "points";
			}
			echo ($before_widget);
				if ($title) {
					echo ($title == "empty"?"<div class='empty-title'>":"").($before_title.($title == "empty"?"":esc_attr($title)).$after_title).($title == "empty"?"</div>":"");
				}else {
					echo "<h3 class='screen-reader-text'>".esc_html__("Users","wpqa")."</h3>";
				}?>
				<div class="widget-wrap">
					<?php echo "<div class='user-section user-section-small row user-not-normal".($crown_king == "on"?" widget-user-crown":"")."'>";
						$meta_key_array = array();
						$implode_array  = "";
						$capabilities   = $wpdb->get_blog_prefix(get_current_blog_id()).'capabilities';
						if (!empty($user_group)) {
							foreach ($user_group as $role => $name) {
								if ($name != "0") {
									$all_role_array[] = $name;
									$meta_key_array[] = "($wpdb->usermeta.meta_key = '$capabilities'
									AND CAST($wpdb->usermeta.meta_value AS CHAR) RLIKE '$name')";
								}else {
									unset($user_group[$role]);
								}
							}
							if (!empty($meta_key_array)) {
								$implode_array = "AND (".implode(" OR ",$meta_key_array).")";
							}
						}
						$user_sort = (isset($user_sort) && $user_sort != ""?$user_sort:"user_registered");
						if ($user_sort == "the_best_answer" || $user_sort == "post_count" || $user_sort == "question_count" || $user_sort == "answers" || $user_sort == "comments") {
							if ($user_sort == "the_best_answer") {
								$query = $wpdb->prepare("SELECT DISTINCT SQL_CALC_FOUND_ROWS $wpdb->users.ID FROM $wpdb->users INNER JOIN $wpdb->usermeta ON ($wpdb->users.ID = $wpdb->usermeta.user_id) LEFT OUTER JOIN (SELECT user_id, COUNT(*) as total FROM $wpdb->comments INNER JOIN $wpdb->commentmeta ON ($wpdb->comments.comment_id = $wpdb->commentmeta.comment_id) WHERE $wpdb->comments.comment_approved = 1 AND $wpdb->commentmeta.meta_key = 'best_answer_comment' GROUP BY user_id) c ON ($wpdb->users.ID = c.user_id) WHERE %s=1 ".$implode_array." ORDER BY total $user_order LIMIT 0,$user_number",1);
							}else if ($user_sort == "post_count" || $user_sort == "question_count") {
								$query = $wpdb->prepare("SELECT DISTINCT SQL_CALC_FOUND_ROWS $wpdb->users.ID FROM $wpdb->users INNER JOIN $wpdb->usermeta ON ( $wpdb->users.ID = $wpdb->usermeta.user_id ) LEFT OUTER JOIN ( SELECT post_author, COUNT(*) as post_count FROM $wpdb->posts WHERE ( ( post_type = '".($user_sort == "question_count"?"question":"post")."' AND ( post_status = 'publish' OR post_status = 'private' ) ) ) GROUP BY post_author ) p ON ($wpdb->users.ID = p.post_author) WHERE %s=1 ".$implode_array." ORDER BY post_count $user_order limit 0,$user_number",1);
							}else {
								$query = $wpdb->prepare("SELECT DISTINCT SQL_CALC_FOUND_ROWS $wpdb->users.ID FROM $wpdb->users INNER JOIN $wpdb->usermeta ON ( $wpdb->users.ID = $wpdb->usermeta.user_id ) LEFT OUTER JOIN ( SELECT user_id, COUNT(*) as total FROM $wpdb->comments INNER JOIN $wpdb->posts ON ( $wpdb->comments.comment_post_ID = $wpdb->posts.ID ) WHERE $wpdb->posts.post_type = '".($user_sort == "answers"?"question":"post")."' AND ( $wpdb->posts.post_status = 'publish' OR $wpdb->posts.post_status = 'private' ) GROUP BY user_id ) c ON ($wpdb->users.ID = c.user_id) WHERE %s=1 ".$implode_array." ORDER BY total $user_order limit 0,$user_number",1);
							}
							$query = $wpdb->get_results($query);
						}else if ($user_sort == "points" && $active_points == "on") {
							$meta_query = "points";
							if ($active_points_specific == "on" && $specific_points == "on") {
								if ($specific_time == "day") {
									$meta_query = "points_date_".date("j-n-Y");
								}else if ($specific_time == "week") {
									$meta_query = "points_date_".date("Y-m-d H:i:s",strtotime($start_of_week.' this week'));
								}else if ($specific_time == "month") {
									$meta_query = "points_date_".date("n-Y");
								}else if ($specific_time == "year") {
									$meta_query = "points_date_".date("Y");
								}
							}
							if ($points_category == "on" && is_tax("question-category")) {
								$category  = (int)get_query_var('wpqa_term_id');
								$meta_query = "points_category".$category;
								if ($active_points_specific == "on" && $specific_points == "on") {
									$meta_query = "points_category".$category;
									if ($specific_time == "day") {
										$meta_query = "points_category".$category."_date_".date("j-n-Y");
									}else if ($specific_time == "week") {
										$meta_query = "points_category".$category."_date_".date("Y-m-d H:i:s",strtotime($start_of_week.' this week'));
									}else if ($specific_time == "month") {
										$meta_query = "points_category".$category."_date_".date("n-Y");
									}else if ($specific_time == "year") {
										$meta_query = "points_category".$category."_date_".date("Y");
									}
								}
							}
							$args = array(
								'role__in'    => (isset($user_group) && is_array($user_group)?$user_group:array()),
								'meta_query'  => array(array("key" => $meta_query,"value" => 0,"compare" => ">")),
								'orderby'     => 'meta_value_num',
								'order'       => $user_order,
								'number'      => $user_number,
								'fields'      => 'ID',
								'count_total' => false,
							);
							
							$query = new WP_User_Query($args);
							$get_results = true;
						}else {
							if ($user_sort != "user_registered" && $user_sort != "display_name" && $user_sort != "ID") {
								$user_sort = "user_registered";
							}
							$args = array(
								'role__in'    => (isset($user_group) && is_array($user_group)?$user_group:array()),
								'orderby'     => $user_sort,
								'order'       => $user_order,
								'number'      => $user_number,
								'fields'      => 'ID',
								'count_total' => false,
							);
							
							$query = new WP_User_Query($args);
							$get_results = true;
						}
						
						if (isset($query)) {
							$query = (isset($get_results)?$query->get_results():$query);
							foreach ($query as $user) {
								$user = (isset($user->ID)?$user->ID:$user);
								if ($points_cat !== "on" && $points_category == "on" && is_tax("question-category")) {
									$categories_user_points = get_user_meta($user,"categories_user_points",true);
									if (is_array($categories_user_points) && !empty($categories_user_points)) {
										foreach ($categories_user_points as $category) {
											$points_category_user[$category] = (int)get_user_meta($user,"points_category".$category,true);
										}
										arsort($points_category_user);
										$first_category = (is_array($points_category_user)?key($points_category_user):"");
										$first_points = reset($points_category_user);
									}
								}
								$owner_widget = false;
								if (get_current_user_id() == $user) {
									$owner_widget = true;
								}
								echo "<div class='col col12'>".wpqa_author($user,"small",$owner_widget,($user_sort == "post_count"?"post":$user_sort),"widget","","",(isset($category) && $category !== ""?$category:(isset($first_points) && $first_points !== ""?$first_points:"")),(isset($show_icon) && $show_icon == "on"?$show_icon:""),($active_points_specific == "on" && $specific_points == "on" && $specific_time != ""?$specific_time:""))."</div>";
							}
						}?>
					</div>
				</div>
			<?php echo ($after_widget);
		}
	}

	public function form( $instance ) {
		/* Save Button */
	}
}?>