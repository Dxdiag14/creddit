<?php

/* @author    2codeThemes
*  @package   WPQA/CPT
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* Activities post type */
function wpqa_activity_post_types_init() {
	$active_activity_log = wpqa_options("active_activity_log");
	if ($active_activity_log == "on") {
	    register_post_type( 'activity',
	        array(
		     	'label' => esc_html__('Activities','wpqa'),
		        'labels' => array(
					'name'               => esc_html__('Activities','wpqa'),
					'singular_name'      => esc_html__('Activities','wpqa'),
					'menu_name'          => esc_html__('Activities','wpqa'),
					'name_admin_bar'     => esc_html__('Activities','wpqa'),
					'edit_item'          => esc_html__('Edit Activity','wpqa'),
					'all_items'          => esc_html__('All Activities','wpqa'),
					'search_items'       => esc_html__('Search Activities','wpqa'),
					'parent_item_colon'  => esc_html__('Parent Activity:','wpqa'),
					'not_found'          => esc_html__('No Activities Found.','wpqa'),
					'not_found_in_trash' => esc_html__('No Activities Found in Trash.','wpqa'),
				),
		        'description'         => '',
		        'public'              => false,
		        'show_ui'             => true,
		        'capability_type'     => 'post',
		        'capabilities'        => array('create_posts' => 'do_not_allow'),
		        'map_meta_cap'        => true,
		        'publicly_queryable'  => false,
		        'exclude_from_search' => false,
		        'hierarchical'        => false,
		        'query_var'           => false,
		        'show_in_rest'        => false,
		        'has_archive'         => false,
				'menu_position'       => 5,
				'menu_icon'           => "dashicons-book-alt",
		        'supports'            => array('title','editor'),
	        )
	    );
	}
}
add_action( 'wpqa_init', 'wpqa_activity_post_types_init', 2 );
/* Admin columns for post types */
function wpqa_activity_columns($old_columns){
	$columns = array();
	$columns["cb"]        = "<input type=\"checkbox\">";
	$columns["content_a"] = esc_html__("Activity","wpqa");
	$columns["author_a"]  = esc_html__("Author","wpqa");
	$columns["date_a"]    = esc_html__("Date","wpqa");
	return $columns;
}
add_filter('manage_edit-activity_columns','wpqa_activity_columns');
function wpqa_activity_custom_columns($column) {
	global $post;
	switch ( $column ) {
		case 'content_a' :
			$activity_result = wpqa_notification_activity_result($post,"activity","admin");
			echo wpqa_show_activities($activity_result,"","");
			if (!empty($activity_result["comment_id"])) {
				$get_comment = get_comment($activity_result["comment_id"]);
				if (!empty($get_comment)) {
					echo '<a target="_blank" href="'.get_comment_link($activity_result["comment_id"]).'"><a class="tooltip_s" data-title="'.esc_html__("View answer","wpqa").'" href="'.admin_url('edit.php?post_type=activity&author='.$post->post_author).'"><i class="dashicons dashicons-admin-comments"></i></a></a>';
				}
			}else if (!empty($activity_result["post_id"])) {
				$get_the_permalink = get_the_permalink($activity_result["post_id"]);
				$get_post_status = get_post_status($activity_result["post_id"]);
				if ($get_post_status != "trash" && !empty($get_the_permalink)) {
					echo '<a target="_blank" href="'.get_the_permalink($activity_result["post_id"]).'"><a class="tooltip_s" data-title="'.esc_html__("View question","wpqa").'" href="'.admin_url('edit.php?post_type=activity&author='.$post->post_author).'"><i class="dashicons dashicons-editor-help"></i></a></a>';
				}
			}
		break;
		case 'author_a' :
			$user_name = get_the_author_meta('display_name',$post->post_author);
			if ($user_name != "") {
				echo '<a target="_blank" href="'.wpqa_profile_url((int)$post->post_author).'"><strong>'.$user_name.'</strong></a><a class="tooltip_s" data-title="'.esc_html__("View activities","wpqa").'" href="'.admin_url('edit.php?post_type=activity&author='.$post->post_author).'"><i class="dashicons dashicons-book-alt"></i></a>';
			}else {
				esc_html_e("Deleted user","wpqa");
			}
		break;
		case 'date_a' :
			$date_format = wpqa_options("date_format");
			$date_format = ($date_format?$date_format:get_option("date_format"));
			$human_time_diff = human_time_diff(get_the_time('U'), current_time('timestamp'));
			echo ($human_time_diff." ".esc_html__("ago","wpqa")." - ".esc_html(get_the_time($date_format)));
		break;
	}
}
add_action('manage_activity_posts_custom_column','wpqa_activity_custom_columns',2);
function wpqa_activity_primary_column($default,$screen) {
	if ('edit-activity' === $screen) {
		$default = 'content_a';
	}
	return $default;
}
add_filter('list_table_primary_column','wpqa_activity_primary_column',10,2);
add_filter('manage_edit-activity_sortable_columns','wpqa_activity_sortable_columns');
function wpqa_activity_sortable_columns($defaults) {
	$defaults['date_a'] = 'date';
	return $defaults;
}
/* Activity details */
add_filter('bulk_actions-edit-activity','wpqa_bulk_actions_activity');
function wpqa_bulk_actions_activity($actions) {
	unset($actions['edit']);
	return $actions;
}
add_filter('bulk_post_updated_messages','wpqa_bulk_updated_messages_activity',1,2);
function wpqa_bulk_updated_messages_activity($bulk_messages,$bulk_counts) {
	if (get_current_screen()->post_type == "activity") {
		$bulk_messages['post'] = array(
			'deleted' => _n('%s activity permanently deleted.','%s activities permanently deleted.',$bulk_counts['deleted'],'wpqa'),
			'trashed' => _n('%s activity trashed.','%s activities trashed.',$bulk_counts['trashed'],'wpqa'),
		);
	}
	return $bulk_messages;
}
add_filter('post_row_actions','wpqa_row_actions_activity',1,2);
function wpqa_row_actions_activity($actions,$post) {
	if ($post->post_type == "activity") {
		unset($actions['trash']);
		unset($actions['view']);
		unset($actions['edit']);
		$actions['inline hide-if-no-js'] = "";
	}
	return $actions;
}
function wpqa_activity_filter() {
	global $post_type;
	if ($post_type == 'activity') {
		$from = (isset($_GET['date-from']) && $_GET['date-from'])?$_GET['date-from'] :'';
		$to = (isset($_GET['date-to']) && $_GET['date-to'])?$_GET['date-to']:'';
		$data_js = " data-js='".json_encode(array("changeMonth" => true,"changeYear" => true,"yearRange" => "2018:+00","dateFormat" => "yy-mm-dd"))."'";

		echo '<span class="site-form-date"><input class="site-date" type="text" name="date-from" placeholder="'.esc_html__("Date From","wpqa").'" value="'.esc_attr($from).'" '.$data_js.'></span>
		<span class="site-form-date"><input class="site-date" type="text" name="date-to" placeholder="'.esc_html__("Date To","wpqa").'" value="'.esc_attr($to).'" '.$data_js.'></span>';
	}
}
add_action('restrict_manage_posts','wpqa_activity_filter');
function wpqa_activity_posts_query($query) {
	global $post_type,$pagenow;
	if ($pagenow == 'edit.php' && $post_type == 'activity') {
		if (!empty($_GET['date-from']) && !empty($_GET['date-to'])) {
			$query->query_vars['date_query'][] = array(
				'after'     => sanitize_text_field($_GET['date-from']),
				'before'    => sanitize_text_field($_GET['date-to']),
				'inclusive' => true,
				'column'    => 'post_date'
			);
		}
		if (!empty($_GET['date-from']) && empty($_GET['date-to'])) {
			$today = sanitize_text_field($_GET['date-from']);
			$today = explode("-",$today);
			$query->query_vars['date_query'] = array(
	            'year'  => $today[0],
	            'month' => $today[1],
	            'day'   => $today[2],
	        );
		}
		if (empty($_GET['date-from']) && !empty($_GET['date-to'])) {
			$today = sanitize_text_field($_GET['date-to']);
			$today = explode("-",$today);
			$query->query_vars['date_query'] = array(
	            'year'  => $today[0],
	            'month' => $today[1],
	            'day'   => $today[2],
	        );
		}
		$orderby = $query->get('orderby');
		if ($orderby == 'date_a') {
			$query->query_vars('orderby','date');
		}
	}
}
add_action('pre_get_posts','wpqa_activity_posts_query');
function wpqa_months_dropdown_activity($return,$post_type) {
	if ($post_type == "activity") {
		$return = true;
	}
	return $return;
}
add_filter("disable_months_dropdown","wpqa_months_dropdown_activity",1,2);
/* Remove filter */
function wpqa_manage_activity_tablenav($which) {
	if ($which == "top") {
		global $post_type,$pagenow;
		if ($pagenow == 'edit.php' && $post_type == 'activity') {
			$date_from = (isset($_GET['date-from'])?esc_attr($_GET['date-from']):'');
			$date_to = (isset($_GET['date-to'])?esc_attr($_GET['date-to']):'');
			if ($date_from != "" || $date_to != "") {
				echo '<a class="button" href="'.admin_url('edit.php?post_type=activity').'">'.esc_html__("Remove filters","wpqa").'</a>';
			}
		}
	}
}
add_filter("manage_posts_extra_tablenav","wpqa_manage_activity_tablenav");
/* Show activities */
if (!function_exists('wpqa_show_activities')) :
	function wpqa_show_activities($whats_type_result,$show_date = "") {
		$output = "";
		if ($show_date == "on") {
			$output .= "<li>";
		}
		
		$result_icon = apply_filters("wpqa_activities_icon",false,$whats_type_result["text"]);
		if ($result_icon != "") {
			$output .= "<i class='".$result_icon."'></i>";
		}else if ($whats_type_result["text"] == "approved_category") {
			$output .= "<i class='icon-folder'></i>";
		}else if ($whats_type_result["text"] == "question_vote_up" || $whats_type_result["text"] == "answer_vote_up") {
			$output .= "<i class='icon-up-dir'></i>";
		}else if ($whats_type_result["text"] == "question_vote_down" || $whats_type_result["text"] == "answer_vote_down") {
			$output .= "<i class='icon-down-dir'></i>";
		}else if ($whats_type_result["text"] == "select_best_answer" || $whats_type_result["text"] == "cancel_best_answer" || $whats_type_result["text"] == "add_answer" || $whats_type_result["text"] == "add_comment" || $whats_type_result["text"] == "report_answer" || $whats_type_result["text"] == "approved_answer" || $whats_type_result["text"] == "approved_comment") {
			$output .= "<i class='icon-comment'></i>";
		}else if (!empty($whats_type_result["post_id"])) {
			$output .= "<i class='icon-sound'></i>";
		}else if (!empty($whats_type_result["comment_id"])) {
			$output .= "<i class='icon-comment'></i>";
		}else if ($whats_type_result["text"] == "add_message") {
			$output .= "<i class='icon-mail'></i>";
		}else if ((!empty($whats_type_result["another_user_id"]) || !empty($whats_type_result["username"])) && $whats_type_result["text"] != "admin_add_points" && $whats_type_result["text"] != "admin_remove_points") {
			$output .= "<i class='icon-user'></i>";
		}else if ($whats_type_result["text"] == "gift_site" || $whats_type_result["text"] == "admin_add_points") {
			$output .= "<i class='icon-bucket'></i>";
		}else if ($whats_type_result["text"] == "admin_remove_points") {
			$output .= "<i class='icon-star-empty'></i>";
		}else if ($whats_type_result["text"] == "delete_inbox_message" || $whats_type_result["text"] == "delete_send_message" || $whats_type_result["text"] == "action_comment" || $whats_type_result["text"] == "action_post" || $whats_type_result["text"] == "delete_reason" || $whats_type_result["text"] == "delete_question" || $whats_type_result["text"] == "delete_post" || $whats_type_result["text"] == "delete_answer" || $whats_type_result["text"] == "delete_comment" || $whats_type_result["text"] == "delete_group" || $whats_type_result["text"] == "delete_posts") {
			$output .= "<i class='icon-cancel'></i>";
		}else if ($whats_type_result["text"] == "posts_like") {
			$output .= "<i class='icon-heart'></i>";
		}else if ($whats_type_result["text"] == "posts_unlike") {
			$output .= "<i class='icon-heart-empty'></i>";
		}else if ($whats_type_result["text"] == "accept_invite") {
			$output .= "<i class='icon-check'></i>";
		}else if ($whats_type_result["text"] == "decline_invite") {
			$output .= "<i class='icon-cancel'></i>";
		}else if ($whats_type_result["text"] == "add_group" || $whats_type_result["text"] == "add_posts" || $whats_type_result["text"] == "approved_group" || $whats_type_result["text"] == "approved_posts") {
			$output .= "<i class='icon-network'></i>";
		}else {
			$output .= "<i class='icon-check'></i>";
		}
		
		$output .= "<div>";
		if (!empty($whats_type_result["another_user_id"])) {
			$wpqa_profile_url = wpqa_profile_url($whats_type_result["another_user_id"]);
			$display_name = get_the_author_meta('display_name',$whats_type_result["another_user_id"]);
		}
		
		if ((!empty($whats_type_result["another_user_id"]) || !empty($whats_type_result["username"])) && $whats_type_result["text"] != "add_message" && $whats_type_result["text"] != "admin_add_points" && $whats_type_result["text"] != "admin_remove_points" && $whats_type_result["text"] != "user_follow" && $whats_type_result["text"] != "ban_user" && $whats_type_result["text"] != "unban_user" && $whats_type_result["text"] != "user_unfollow") {
			if (isset($display_name) && $display_name != "") {
				if (!empty($whats_type_result["another_user_id"])) {
					$output .= '<a href="'.esc_url($wpqa_profile_url).'">'.esc_html($display_name).'</a>';
				}
				if (!empty($whats_type_result["username"])) {
					$output .= esc_attr($whats_type_result["username"])." ";
				}
				$output .= esc_html__("has","wpqa")." ";
			}else {
				$output .= esc_html__("Deleted user","wpqa")." - ";
			}
		}
		
		if (!empty($whats_type_result["post_id"])) {
			$get_the_permalink = get_the_permalink($whats_type_result["post_id"]);
			$get_post_status = get_post_status($whats_type_result["post_id"]);
		}
		if (!empty($whats_type_result["comment_id"])) {
			$get_comment = get_comment($whats_type_result["comment_id"]);
		}
		if (!empty($whats_type_result["post_id"]) && !empty($whats_type_result["comment_id"]) && $get_post_status != "trash" && isset($get_comment) && $get_comment->comment_approved != "spam" && $get_comment->comment_approved != "trash") {
			$output .= '<a href="'.esc_url($get_the_permalink.(isset($whats_type_result["comment_id"])?"#comment-".$whats_type_result["comment_id"]:"")).'">';
		}
		if (!empty($whats_type_result["post_id"]) && empty($whats_type_result["comment_id"]) && $get_post_status != "trash" && isset($get_the_permalink) && $get_the_permalink != "") {
			$output .= '<a href="'.esc_url($get_the_permalink).'">';
		}
			
			$result_text = apply_filters("wpqa_activities_text",false,$whats_type_result["text"]);
			if ($result_text != "") {
				$output .= $result_text;
			}else if ($whats_type_result["text"] == "poll_question") {
				$output .= esc_html__("Poll at question","wpqa");
			}else if ($whats_type_result["text"] == "question_vote_up") {
				$output .= esc_html__("Voted up question.","wpqa");
			}else if ($whats_type_result["text"] == "question_vote_down") {
				$output .= esc_html__("Voted down question.","wpqa");
			}else if ($whats_type_result["text"] == "answer_vote_up") {
				$output .= esc_html__("Voted up answer.","wpqa");
			}else if ($whats_type_result["text"] == "answer_vote_down") {
				$output .= esc_html__("Voted down answer.","wpqa");
			}else if ($whats_type_result["text"] == "user_follow") {
				$output .= esc_html__("You have followed","wpqa");
			}else if ($whats_type_result["text"] == "ban_user") {
				$output .= esc_html__("You have banned user","wpqa");
			}else if ($whats_type_result["text"] == "unban_user") {
				$output .= esc_html__("You have unbanned user","wpqa");
			}else if ($whats_type_result["text"] == "user_unfollow") {
				$output .= esc_html__("You have unfollowed","wpqa");
			}else if ($whats_type_result["text"] == "bump_question") {
				$output .= esc_html__("You have bumped your question.","wpqa");
			}else if ($whats_type_result["text"] == "report_question") {
				$output .= esc_html__("You have reported a question.","wpqa");
			}else if ($whats_type_result["text"] == "report_answer") {
				$output .= esc_html__("You have reported an answer.","wpqa");
			}else if ($whats_type_result["text"] == "select_best_answer") {
				$output .= esc_html__("You have chosen the best answer.","wpqa");
			}else if ($whats_type_result["text"] == "cancel_best_answer") {
				$output .= esc_html__("You have canceled the best answer.","wpqa");
			}else if ($whats_type_result["text"] == "closed_question") {
				$output .= esc_html__("You have closed the question.","wpqa");
			}else if ($whats_type_result["text"] == "opend_question") {
				$output .= esc_html__("You have opend the question.","wpqa");
			}else if ($whats_type_result["text"] == "follow_question") {
				$output .= esc_html__("You have followed the question.","wpqa");
			}else if ($whats_type_result["text"] == "unfollow_question") {
				$output .= esc_html__("You have unfollowed the question.","wpqa");
			}else if ($whats_type_result["text"] == "question_favorites") {
				$output .= esc_html__("You have added a question at favorites.","wpqa");
			}else if ($whats_type_result["text"] == "question_remove_favorites") {
				$output .= esc_html__("You have removed a question from favorites.","wpqa");
			}else if ($whats_type_result["text"] == "add_answer") {
				$output .= esc_html__("You have added an answer.","wpqa");
			}else if ($whats_type_result["text"] == "add_comment") {
				$output .= esc_html__("You have added a comment.","wpqa");
			}else if ($whats_type_result["text"] == "approved_answer") {
				$output .= esc_html__("Your answer is pending for review.","wpqa");
			}else if ($whats_type_result["text"] == "approved_comment") {
				$output .= esc_html__("Your comment is pending for review.","wpqa");
			}else if ($whats_type_result["text"] == "add_question") {
				$output .= esc_html__("Added a new question.","wpqa");
			}else if ($whats_type_result["text"] == "add_post") {
				$output .= esc_html__("Add a new post.","wpqa");
			}else if ($whats_type_result["text"] == "approved_question") {
				$output .= esc_html__("Your question is pending for review.","wpqa");
			}else if ($whats_type_result["text"] == "approved_message") {
				$output .= esc_html__("Your message is pending for review.","wpqa");
			}else if ($whats_type_result["text"] == "approved_post") {
				$output .= esc_html__("Your post is pending for review.","wpqa");
			}else if ($whats_type_result["text"] == "approved_category") {
				$output .= esc_html__("Your category is pending for review.","wpqa");
			}else if ($whats_type_result["text"] == "delete_question") {
				$output .= esc_html__("You have deleted a question.","wpqa");
			}else if ($whats_type_result["text"] == "delete_post") {
				$output .= esc_html__("You have deleted a post.","wpqa");
			}else if ($whats_type_result["text"] == "delete_answer") {
				$output .= esc_html__("You have deleted an answer.","wpqa");
			}else if ($whats_type_result["text"] == "delete_comment") {
				$output .= esc_html__("You have deleted a comment.","wpqa");
			}else if ($whats_type_result["text"] == "add_message") {
				$output .= esc_html__("You have sent a message for","wpqa");
				if (!empty($whats_type_result["another_user_id"]) || !empty($whats_type_result["username"])) {
					if (isset($display_name) && $display_name != "") {
						if (!empty($whats_type_result["another_user_id"])) {
							$output .= ' <a href="'.esc_url($wpqa_profile_url).'">'.esc_html($display_name).'</a>.';
						}
						if (!empty($whats_type_result["username"])) {
							$output .= esc_html($whats_type_result["username"]).".";
						}
					}else {
						$output .= esc_html__("Delete user","wpqa").".";
					}
				}
			}else if ($whats_type_result["text"] == "delete_inbox_message") {
				$output .= esc_html__("You have deleted your inbox message","wpqa");
			}else if ($whats_type_result["text"] == "delete_send_message") {
				$output .= esc_html__("You have deleted your sent message","wpqa");
			}else if ($whats_type_result["text"] == "delete_group") {
				$output .= esc_html__("You have deleted a group.","wpqa");
			}else if ($whats_type_result["text"] == "delete_posts") {
				$output .= esc_html__("You have deleted a group post.","wpqa");
			}else if ($whats_type_result["text"] == "posts_like") {
				$output .= esc_html__("You have liked a group post.","wpqa");
			}else if ($whats_type_result["text"] == "posts_unlike") {
				$output .= esc_html__("You have unliked a group post.","wpqa");
			}else if ($whats_type_result["text"] == "accept_invite") {
				$output .= esc_html__("You have accepted the group invite.","wpqa");
			}else if ($whats_type_result["text"] == "decline_invite") {
				$output .= esc_html__("You have declined the group invite.","wpqa");
			}else if ($whats_type_result["text"] == "add_group") {
				$output .= esc_html__("Added a new group.","wpqa");
			}else if ($whats_type_result["text"] == "add_posts") {
				$output .= esc_html__("Added a new group post.","wpqa");
			}else if ($whats_type_result["text"] == "approved_group") {
				$output .= esc_html__("Your group is pending for review.","wpqa");
			}else if ($whats_type_result["text"] == "approved_posts") {
				$output .= esc_html__("Your group post is pending for review.","wpqa");
			}else {
				$output .= $whats_type_result["text"];
			}
		if ((!empty($whats_type_result["post_id"]) && !empty($whats_type_result["comment_id"]) && $get_post_status != "trash" && isset($get_comment) && $get_comment->comment_approved != "spam" && $get_comment->comment_approved != "trash") || (!empty($whats_type_result["post_id"]) && empty($whats_type_result["comment_id"]) && $get_post_status != "trash" && isset($get_the_permalink) && $get_the_permalink != "")) {
			$output .= '</a>';
		}
		if (is_super_admin($whats_type_result["user_id"]) && !empty($whats_type_result["post_id"]) && !empty($whats_type_result["comment_id"])) {
			if (isset($get_comment) && $get_comment->comment_approved == "spam") {
				$output .= " ".esc_html__('( Spam )','wpqa');
			}else if ($get_post_status == "trash" || (isset($get_comment) && $get_comment->comment_approved == "trash")) {
				$output .= " ".esc_html__('( Trashed )','wpqa');
			}else if (empty($get_comment)) {
				$output .= " ".esc_html__('( Deleted )','wpqa');
			}
		}
		if (is_super_admin($whats_type_result["user_id"]) && !empty($whats_type_result["post_id"]) && empty($whats_type_result["comment_id"])) {
			if ($get_post_status == "trash") {
				$output .= " ".esc_html__('( Trashed )','wpqa');
			}else if (empty($get_the_permalink)) {
				$output .= " ".esc_html__('( Deleted )','wpqa');
			}
		}
		if (!empty($whats_type_result["more_text"])) {
			$output .= " - ".esc_attr($whats_type_result["more_text"]).".";
		}
		if (($whats_type_result["text"] == "ban_user" || $whats_type_result["text"] == "unban_user" || $whats_type_result["text"] == "user_follow" || $whats_type_result["text"] == "user_unfollow") && !empty($whats_type_result["another_user_id"])) {
			$output .= ' <a href="'.wpqa_profile_url($whats_type_result["another_user_id"]).'">'.get_the_author_meta('display_name',$whats_type_result["another_user_id"]).'</a>.';
		}
		
		if ($show_date == "on") {
			$output .= "<span class='notifications-date'>".$whats_type_result["time"]."</span>
			</div></li>";
		}
		return $output;	
	}
endif;
/* Show activities li */
if (!function_exists('wpqa_get_activities')) :
	function wpqa_get_activities($user_id,$item_number,$more_button) {
		$output = '<div>
		<ul>';
		$args = array('author' => $user_id,'post_type' => 'activity','posts_per_page' => $item_number);
		$activities_query = new WP_Query( $args );
		if ($activities_query->have_posts()) {
			while ( $activities_query->have_posts() ) { $activities_query->the_post();
				$activity_post = $activities_query->post;
				$activity_result = wpqa_notification_activity_result($activity_post,"activity");
				$output .= wpqa_show_activities($activity_result,"on");
			}
			$output .= "</ul>";
			if (isset($more_button) && $more_button == "on") {
				$output .= "<a href='".esc_url(wpqa_get_profile_permalink($user_id,"activities"))."'>".esc_html__("Show all activities.","wpqa")."</a>";
			}
		}else {
			$output .= "<li><div>".esc_html__("There are no activities yet.","wpqa")."</div></li></ul>";
		}
		$output .= '</div>';
		wp_reset_postdata();
		return $output;
	}
endif;?>