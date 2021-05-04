<?php

/* @author    2codeThemes
*  @package   WPQA/CPT
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* Notifications post type */
function wpqa_notification_post_types_init() {
	$active_notifications = wpqa_options("active_notifications");
	if ($active_notifications == "on") {
	    register_post_type( 'notification',
	        array(
		     	'label' => esc_html__('Notifications','wpqa'),
		        'labels' => array(
					'name'               => esc_html__('Notifications','wpqa'),
					'singular_name'      => esc_html__('Notifications','wpqa'),
					'menu_name'          => esc_html__('Notifications','wpqa'),
					'name_admin_bar'     => esc_html__('Notifications','wpqa'),
					'edit_item'          => esc_html__('Edit Notification','wpqa'),
					'all_items'          => esc_html__('All Notifications','wpqa'),
					'search_items'       => esc_html__('Search Notifications','wpqa'),
					'parent_item_colon'  => esc_html__('Parent Notification:','wpqa'),
					'not_found'          => esc_html__('No Notifications Found.','wpqa'),
					'not_found_in_trash' => esc_html__('No Notifications Found in Trash.','wpqa'),
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
				'menu_icon'           => "dashicons-buddicons-pm",
		        'supports'            => array('title','editor'),
	        )
	    );
	}
}
add_action( 'wpqa_init', 'wpqa_notification_post_types_init', 2 );
/* Admin columns for post types */
function wpqa_notification_columns($old_columns){
	$columns = array();
	$columns["cb"]        = "<input type=\"checkbox\">";
	$columns["content_n"] = esc_html__("Notification","wpqa");
	$columns["author_n"]  = esc_html__("Author","wpqa");
	$columns["date_n"]    = esc_html__("Date","wpqa");
	return $columns;
}
add_filter('manage_edit-notification_columns','wpqa_notification_columns');
function wpqa_notification_custom_columns($column) {
	global $post;
	switch ( $column ) {
		case 'content_n' :
			$notification_result = wpqa_notification_activity_result($post,"notification","admin");
			echo wpqa_show_notifications($notification_result,"","");
			if (!empty($notification_result["comment_id"])) {
				$get_comment = get_comment($notification_result["comment_id"]);
				if (!empty($get_comment)) {
					echo '<a target="_blank" href="'.get_comment_link($notification_result["comment_id"]).'"><a class="tooltip_s" data-title="'.esc_html__("View answer","wpqa").'" href="'.get_comment_link($notification_result["comment_id"]).'"><i class="dashicons dashicons-admin-comments"></i></a></a>';
				}
			}else if (!empty($notification_result["post_id"])) {
				$get_the_permalink = get_the_permalink($notification_result["post_id"]);
				$get_post_status = get_post_status($notification_result["post_id"]);
				if ($get_post_status != "trash" && !empty($get_the_permalink)) {
					echo '<a target="_blank" href="'.get_the_permalink($notification_result["post_id"]).'"><a class="tooltip_s" data-title="'.esc_html__("View question","wpqa").'" href="'.get_the_permalink($notification_result["post_id"]).'"><i class="dashicons dashicons-editor-help"></i></a></a>';
				}
			}
		break;
		case 'author_n' :
			$user_name = get_the_author_meta('display_name',$post->post_author);
			if ($user_name != "") {
				echo '<a target="_blank" href="'.wpqa_profile_url((int)$post->post_author).'"><strong>'.$user_name.'</strong></a><a class="tooltip_s" data-title="'.esc_html__("View notifications","wpqa").'" href="'.admin_url('edit.php?post_type=notification&author='.$post->post_author).'"><i class="dashicons dashicons-buddicons-pm"></i></a>';
			}else {
				esc_html_e("Deleted user","wpqa");
			}
		break;
		case 'date_n' :
			$date_format = wpqa_options("date_format");
			$date_format = ($date_format?$date_format:get_option("date_format"));
			$human_time_diff = human_time_diff(get_the_time('U'), current_time('timestamp'));
			echo ($human_time_diff." ".esc_html__("ago","wpqa")." - ".esc_html(get_the_time($date_format)));
		break;
	}
}
add_action('manage_notification_posts_custom_column','wpqa_notification_custom_columns',2);
function wpqa_notification_primary_column($default,$screen) {
	if ('edit-notification' === $screen) {
		$default = 'content_n';
	}
	return $default;
}
add_filter('list_table_primary_column','wpqa_notification_primary_column',10,2);
add_filter('manage_edit-notification_sortable_columns','wpqa_notification_sortable_columns');
function wpqa_notification_sortable_columns($defaults) {
	$defaults['date_n'] = 'date';
	return $defaults;
}
/* Notification details */
add_filter('bulk_actions-edit-notification','wpqa_bulk_actions_notification');
function wpqa_bulk_actions_notification($actions) {
	unset($actions['edit']);
	return $actions;
}
add_filter('bulk_post_updated_messages','wpqa_bulk_updated_messages_notification',1,2);
function wpqa_bulk_updated_messages_notification($bulk_messages,$bulk_counts) {
	if (get_current_screen()->post_type == "notification") {
		$bulk_messages['post'] = array(
			'deleted' => _n('%s notification permanently deleted.','%s notifications permanently deleted.',$bulk_counts['deleted'],'wpqa'),
			'trashed' => _n('%s notification trashed.','%s notifications trashed.',$bulk_counts['trashed'],'wpqa'),
		);
	}
	return $bulk_messages;
}
add_filter('post_row_actions','wpqa_row_actions_notification',1,2);
function wpqa_row_actions_notification($actions,$post) {
	if ($post->post_type == "notification") {
		unset($actions['trash']);
		unset($actions['view']);
		unset($actions['edit']);
		$actions['inline hide-if-no-js'] = "";
	}
	return $actions;
}
function wpqa_notification_filter() {
	global $post_type;
	if ($post_type == 'notification') {
		$from = (isset($_GET['date-from']) && $_GET['date-from'])?$_GET['date-from'] :'';
		$to = (isset($_GET['date-to']) && $_GET['date-to'])?$_GET['date-to']:'';
		$data_js = " data-js='".json_encode(array("changeMonth" => true,"changeYear" => true,"yearRange" => "2018:+00","dateFormat" => "yy-mm-dd"))."'";

		echo '<span class="site-form-date"><input class="site-date" type="text" name="date-from" placeholder="'.esc_html__("Date From","wpqa").'" value="'.esc_attr($from).'" '.$data_js.'></span>
		<span class="site-form-date"><input class="site-date" type="text" name="date-to" placeholder="'.esc_html__("Date To","wpqa").'" value="'.esc_attr($to).'" '.$data_js.'></span>';
	}
}
add_action('restrict_manage_posts','wpqa_notification_filter');
function wpqa_notification_posts_query($query) {
	global $post_type,$pagenow;
	if ($pagenow == 'edit.php' && $post_type == 'notification') {
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
		if ($orderby == 'date_n') {
			$query->query_vars('orderby','date');
		}
	}
}
add_action('pre_get_posts','wpqa_notification_posts_query');
function wpqa_months_dropdown_notification($return,$post_type) {
	if ($post_type == "notification") {
		$return = true;
	}
	return $return;
}
add_filter("disable_months_dropdown","wpqa_months_dropdown_notification",1,2);
/* Remove filter */
function wpqa_manage_notification_tablenav($which) {
	if ($which == "top") {
		global $post_type,$pagenow;
		if ($pagenow == 'edit.php' && $post_type == 'notification') {
			$date_from = (isset($_GET['date-from'])?esc_attr($_GET['date-from']):'');
			$date_to = (isset($_GET['date-to'])?esc_attr($_GET['date-to']):'');
			if ($date_from != "" || $date_to != "") {
				echo '<a class="button" href="'.admin_url('edit.php?post_type=notification').'">'.esc_html__("Remove filters","wpqa").'</a>';
			}
		}
	}
}
add_filter("manage_posts_extra_tablenav","wpqa_manage_notification_tablenav");
/* Insert a new notification or activity */
function wpqa_notifications_activities($user_id = "",$another_user_id = "",$username = "",$post_id = "",$comment_id = "",$text = "",$type = "notifications",$more_text = "",$type_of_item = "",$new = true,$array = array()) {
	$active_notifications = wpqa_options("active_notifications");
	$active_activity_log = wpqa_options("active_activity_log");
	if (($type != "notifications" && $type != "activities")) {
		/* Number of my types */
		$_types = get_user_meta($user_id,$user_id."_".$type,true);
		if ($_types == "") {
			$_types = 0;
		}
		$_types++;
		update_user_meta($user_id,$user_id."_".$type,$_types);
		$array = (is_array($array) && !empty($array)?$array:
			array(
				"date_years"      => date_i18n('Y/m/d',current_time('timestamp')),
				"date_hours"      => date_i18n('g:i a',current_time('timestamp')),
				"time"            => current_time('timestamp'),
				"user_id"         => $user_id,
				"another_user_id" => $another_user_id,
				"post_id"         => $post_id,
				"comment_id"      => $comment_id,
				"text"            => $text,
				"username"        => $username,
				"more_text"       => $more_text,
				"type_of_item"    => $type_of_item
			)
		);
		
		add_user_meta($user_id,$user_id."_".$type."_".$_types,$array);
		
		if ($new == true) {
			/* New */
			$_new_types = get_user_meta($user_id,$user_id."_new_".$type,true);
			if (isset($_new_types) && $_new_types != "" && $_new_types > 0) {
				$_new_types++;
			}else {
				$_new_types = 1;
			}
			update_user_meta($user_id,$user_id.'_new_'.$type,$_new_types);
		}
	}else if (($type == "notifications" && $active_notifications == "on") || ($type == "activities" && $active_activity_log == "on")) {
		$type = ($type == "notifications"?"notification":$type);
		$type = ($type == "activities"?"activity":$type);
		$data = array(
			'post_title'  => $text,
			'post_status' => "publish",
			'post_author' => $user_id,
			'post_type'   => $type
		);
		$post_type_id = wp_insert_post($data);
		if ($post_type_id == 0 || is_wp_error($post_type_id)) {
			error_log(esc_html__("Error in post.","wpqa"));
		}else {
			if ($type == "notification") {
				$variables = array(
					$type."_new" => 1,
				);
			}else {
				$variables = array();
			}
			if ($another_user_id != "") {
				$variables[$type."_another_user_id"] = $another_user_id;
			}
			if ($username != "") {
				$variables[$type."_username"] = $username;
			}
			if ($post_id != "") {
				$variables[$type."_post_id"] = $post_id;
			}
			if ($comment_id != "") {
				$variables[$type."_comment_id"] = $comment_id;
			}
			if ($more_text != "") {
				$variables[$type."_more_text"] = $more_text;
			}
			if ($type_of_item != "") {
				$variables[$type."_type_of_item"] = $type_of_item;
			}
			if (is_array($variables) && !empty($variables)) {
				foreach ($variables as $key => $value) {
					update_post_meta($post_type_id,$key,$value);
				}
			}
			do_action("wpqa_action_notifications_activities",$post_type_id,$user_id,$another_user_id,$username,$post_id,$comment_id,$text,$type,$more_text,$type_of_item,$new,$array);
		}
		
		if ($new == true) {
			/* New */
			$_new_types = get_user_meta($user_id,$user_id."_new_".$type,true);
			if (isset($_new_types) && $_new_types != "" && $_new_types > 0) {
				$_new_types++;
			}else {
				$_new_types = 1;
			}
			update_user_meta($user_id,$user_id.'_new_'.$type,$_new_types);
		}
	}
}
/* Show notifications li */
if (!function_exists('wpqa_get_notifications')) :
	function wpqa_get_notifications($user_id,$item_number,$more_button,$count = false,$more_button_ul = false) {
		$output = '';
		if ($count == true) {
			$num = get_user_meta($user_id,$user_id.'_new_notification',true);
			$num = (isset($num) && $num != "" && $num > 0?$num:0);
			if (isset($num) && $num != "" && $num > 0) {
				$num = ($num <= 99?$num:"99+");
				$output .= '<span class="notifications-number">'.$num.'</span>';
			}
		}
		if ($more_button_ul == false) {
			$output .= '<div>';
		}
		$output .= '<ul>';
		$args = array('author' => $user_id,'post_type' => 'notification','posts_per_page' => $item_number);
		$notifications_query = new WP_Query( $args );
		if ($notifications_query->have_posts()) {
			while ( $notifications_query->have_posts() ) { $notifications_query->the_post();
				$notification_post = $notifications_query->post;
				$notification_result = wpqa_notification_activity_result($notification_post,"notification");
				$output .= wpqa_show_notifications($notification_result,"on");
			}
			if ($more_button == "on" && $more_button_ul == true) {
				$output .= "<li><a href='".esc_url(wpqa_get_profile_permalink($user_id,"notifications"))."'>".esc_html__("Show all notifications.","wpqa")."</a></li>";
			}
			$output .= "</ul>";
			if ($more_button == "on" && $more_button_ul == false) {
				$output .= "<a href='".esc_url(wpqa_get_profile_permalink($user_id,"notifications"))."'>".esc_html__("Show all notifications.","wpqa")."</a>";
			}
		}else {
			$output .= "<li><div>".esc_html__("There are no notifications yet.","wpqa")."</div></li></ul>";
		}
		if ($more_button_ul == false) {
			$output .= '</div>';
		}
		wp_reset_postdata();
		return $output;
	}
endif;
/* Get notification and activity result */
function wpqa_notification_activity_result($post,$type = "notification",$admin = "") {
	$another_user_id = get_post_meta($post->ID,$type."_another_user_id",true);
	$username = get_post_meta($post->ID,$type."_username",true);
	$post_id = get_post_meta($post->ID,$type."_post_id",true);
	$comment_id = get_post_meta($post->ID,$type."_comment_id",true);
	$more_text = get_post_meta($post->ID,$type."_more_text",true);
	$type_of_item = get_post_meta($post->ID,$type."_type_of_item",true);

	$type_result = array();
	$type_result["text"] = $post->post_title;
	$type_result["user_id"] = $post->post_author;
	$date_format = wpqa_options("date_format");
	$date_format = ($date_format?$date_format:get_option("date_format"));
	$time_format = wpqa_options("time_format");
	$time_format = ($time_format?$time_format:get_option("time_format"));
	$type_result["time"] = sprintf(esc_html__('%1$s at %2$s','wpqa'),get_the_time($date_format,$post->ID),get_the_time($time_format,$post->ID));
	$type_result["another_user_id"] = $another_user_id;
	if ($username != "") {
		$type_result["username"] = $username;
	}
	if ($post_id != "") {
		$type_result["post_id"] = $post_id;
	}
	if ($comment_id != "") {
		$type_result["comment_id"] = $comment_id;
	}
	if ($more_text != "") {
		$type_result["more_text"] = $more_text;
	}
	if ($type_of_item != "") {
		$type_result["type_of_item"] = $type_of_item;
	}
	if ($admin != "admin" && $type == "notification" && wpqa_is_user_notifications()) {
		update_post_meta($post->ID,$type.'_new',0);
	}
	return $type_result;
}
/* Show notifications */
if (!function_exists('wpqa_show_notifications')) :
	function wpqa_show_notifications($notification_array,$show_date = "",$show_icon = "on") {
		$output = "";
		if ($show_date == "on") {
			$output .= "<li>";
		}
		
		if ($show_icon == "on") {
			$result_icon = apply_filters("wpqa_notifications_icon",false,$notification_array["text"]);
			if ($result_icon != "") {
				$output .= "<i class='".$result_icon."'></i>";
			}else if ($notification_array["text"] == "accepted_category" || $notification_array["text"] == "canceled_category") {
				$output .= "<i class='icon-folder'></i>";
			}else if ($notification_array["text"] == "question_vote_up" || $notification_array["text"] == "answer_vote_up") {
				$output .= "<i class='icon-up-dir'></i>";
			}else if ($notification_array["text"] == "question_vote_down" || $notification_array["text"] == "answer_vote_down") {
				$output .= "<i class='icon-down-dir'></i>";
			}else if ($notification_array["text"] == "gift_site" || $notification_array["text"] == "points_referral" || $notification_array["text"] == "referral_membership" || $notification_array["text"] == "admin_add_points") {
				$output .= "<i class='icon-bucket'></i>";
			}else if ($notification_array["text"] == "admin_remove_points" || $notification_array["text"] == "question_remove_favorites") {
				$output .= "<i class='icon-star-empty'></i>";
			}else if ($notification_array["text"] == "add_message_user" || $notification_array["text"] == "seen_message") {
				$output .= "<i class='icon-mail'></i>";
			}else if ($notification_array["text"] == "question_favorites") {
				$output .= "<i class='icon-star'></i>";
			}else if ($notification_array["text"] == "follow_question" || $notification_array["text"] == "user_follow") {
				$output .= "<i class='icon-plus'></i>";
			}else if ($notification_array["text"] == "unfollow_question" || $notification_array["text"] == "user_unfollow") {
				$output .= "<i class='icon-minus'></i>";
			}else if ($notification_array["text"] == "answer_asked_question" || $notification_array["text"] == "select_best_answer" || $notification_array["text"] == "cancel_best_answer" || $notification_array["text"] == "answer_question" || $notification_array["text"] == "reply_answer" || $notification_array["text"] == "answer_question_follow" || $notification_array["text"] == "approved_answer" || $notification_array["text"] == "approved_comment") {
				$output .= "<i class='icon-comment'></i>";
			}else if ($notification_array["text"] == "rejected_withdrawal_points" || $notification_array["text"] == "accepted_withdrawal_points" || $notification_array["text"] == "requested_money") {
				$output .= "<i class='icon-bucket'></i>";
			}else if ($notification_array["text"] == "request_group" || $notification_array["text"] == "approved_group" || $notification_array["text"] == "approved_posts") {
				$output .= "<i class='icon-network'></i>";
			}else if ($notification_array["text"] == "add_group_moderator" || $notification_array["text"] == "approve_request_group" || $notification_array["text"] == "add_group_invitations" || $notification_array["text"] == "unblocked_group") {
				$output .= "<i class='icon-plus'></i>";
			}else if ($notification_array["text"] == "remove_group_moderator" || $notification_array["text"] == "decline_request_group" || $notification_array["text"] == "removed_user_group" || $notification_array["text"] == "blocked_group") {
				$output .= "<i class='icon-minus'></i>";
			}else if ($notification_array["text"] == "posts_like") {
				$output .= "<i class='icon-heart'></i>";
			}else if (!empty($notification_array["post_id"])) {
				$output .= "<i class='icon-sound'></i>";
			}else if (!empty($notification_array["comment_id"])) {
				$output .= "<i class='icon-comment'></i>";
			}else if ((!empty($notification_array["another_user_id"]) || !empty($notification_array["username"])) && $notification_array["text"] != "admin_add_points" && $notification_array["text"] != "admin_remove_points") {
				$output .= "<i class='icon-user'></i>";
			}else if ($notification_array["text"] == "action_comment" || $notification_array["text"] == "action_post" || $notification_array["text"] == "delete_reason" || $notification_array["text"] == "delete_question" || $notification_array["text"] == "delete_post" || $notification_array["text"] == "delete_answer" || $notification_array["text"] == "delete_comment") {
				$output .= "<i class='icon-cancel'></i>";
			}else {
				$output .= "<i class='icon-check'></i>";
			}
		}
		
		$output .= "<div>";
		if (!empty($notification_array["another_user_id"])) {
			$wpqa_profile_url = wpqa_profile_url($notification_array["another_user_id"]);
			$display_name = get_the_author_meta('display_name',$notification_array["another_user_id"]);
		}
		
		if ($notification_array["text"] != "accepted_category" && $notification_array["text"] != "approved_answer" && $notification_array["text"] != "approved_comment" && $notification_array["text"] != "approved_question" && $notification_array["text"] != "approved_message" && $notification_array["text"] != "approved_post") {
			if ((((isset($notification_array["comment_id"]) && $notification_array["comment_id"] != "") || $notification_array["text"] == "add_question_user" || $notification_array["text"] == "add_question" || $notification_array["text"] == "poll_question") && (empty($notification_array["username"]) || $notification_array["username"] == "unlogged") && isset($notification_array["another_user_id"]) && $notification_array["another_user_id"] == 0) || (!empty($notification_array["another_user_id"]) || !empty($notification_array["username"])) && $notification_array["text"] != "admin_add_points" && $notification_array["text"] != "admin_remove_points") {
				if ((((isset($notification_array["comment_id"]) && $notification_array["comment_id"] != "") || $notification_array["text"] == "add_question_user" || $notification_array["text"] == "add_question" || $notification_array["text"] == "poll_question") && isset($notification_array["another_user_id"]) && $notification_array["another_user_id"] == 0) || (isset($display_name) && $display_name != "")) {
					if (!empty($notification_array["another_user_id"])) {
						$output .= '<a href="'.esc_url($wpqa_profile_url).'">'.esc_html($display_name).'</a> ';
					}
					if (!empty($notification_array["username"]) && $notification_array["username"] != "unlogged") {
						$output .= esc_attr($notification_array["username"])." ";
					}
					if (((isset($notification_array["comment_id"]) && $notification_array["comment_id"] != "") || $notification_array["text"] == "add_question_user" || $notification_array["text"] == "add_question") && empty($notification_array["username"]) && isset($notification_array["another_user_id"]) && $notification_array["another_user_id"] == 0) {
						$output .= esc_html__("Anonymous","wpqa")." ";
					}
					if ($notification_array["text"] == "poll_question" && (empty($notification_array["username"]) || $notification_array["username"] == "unlogged") && isset($notification_array["another_user_id"]) && $notification_array["another_user_id"] == 0) {
						$output .= esc_html__("A non-registered user","wpqa")." ";
					}
					$output .= esc_html__("has","wpqa");
				}else if (!empty($notification_array["username"])) {
					$output .= esc_attr($notification_array["username"])." ";
				}else {
					$output .= esc_html__("Deleted user","wpqa")." -";
				}
			}
		}
		
		$output .= " ";
		if (!empty($notification_array["post_id"])) {
			$get_the_permalink = get_the_permalink($notification_array["post_id"]);
			$get_post_status = get_post_status($notification_array["post_id"]);
		}
		if (!empty($notification_array["comment_id"])) {
			$get_comment = get_comment($notification_array["comment_id"]);
		}
		if (!empty($notification_array["post_id"]) && !empty($notification_array["comment_id"]) && $get_post_status != "trash" && isset($get_comment) && $get_comment->comment_approved != "spam" && $get_comment->comment_approved != "trash") {
			$output .= '<a href="'.esc_url($get_the_permalink.(isset($notification_array["comment_id"])?"#comment-".$notification_array["comment_id"]:"")).'">';
		}
		if (!empty($notification_array["post_id"]) && empty($notification_array["comment_id"]) && $get_post_status != "trash" && isset($get_the_permalink) && $get_the_permalink != "") {
			$output .= '<a href="'.esc_url($get_the_permalink).'">';
		}
			$result_text = apply_filters("wpqa_notifications_text",false,$notification_array["text"]);
			if ($result_text != "") {
				$output .= $result_text;
			}else if ($notification_array["text"] == "accepted_category") {
				$output .= esc_html__("The administrator approved your category.","wpqa");
			}else if ($notification_array["text"] == "canceled_category") {
				$output .= esc_html__("The administrator rejected your category.","wpqa");
			}else if ($notification_array["text"] == "poll_question") {
				$output .= esc_html__("polled at your question","wpqa");
			}else if ($notification_array["text"] == "gift_site") {
				$output .= esc_html__("Gift of the site","wpqa");
			}else if ($notification_array["text"] == "points_referral") {
				$output .= esc_html__("referred a new user","wpqa");
			}else if ($notification_array["text"] == "referral_membership") {
				$output .= esc_html__("referral a new user for paid membership","wpqa");
			}else if ($notification_array["text"] == "admin_add_points") {
				$output .= esc_html__("The administrator added points for you.","wpqa");
			}else if ($notification_array["text"] == "admin_remove_points") {
				$output .= esc_html__("The administrator removed points from you.","wpqa");
			}else if ($notification_array["text"] == "question_vote_up") {
				$output .= esc_html__("voted up your question.","wpqa");
			}else if ($notification_array["text"] == "question_vote_down") {
				$output .= esc_html__("voted down your question.","wpqa");
			}else if ($notification_array["text"] == "answer_vote_up") {
				$output .= esc_html__("voted up your answer.","wpqa");
			}else if ($notification_array["text"] == "answer_vote_down") {
				$output .= esc_html__("voted down your answer.","wpqa");
			}else if ($notification_array["text"] == "user_follow") {
				$output .= esc_html__("followed you.","wpqa");
			}else if ($notification_array["text"] == "user_unfollow") {
				$output .= esc_html__("unfollowed you.","wpqa");
			}else if ($notification_array["text"] == "point_back") {
				$output .= esc_html__('Your points are back because the "Best answer" was selected.','wpqa');
			}else if ($notification_array["text"] == "select_best_answer") {
				$output .= esc_html__("Chosen your answer as Best answer.","wpqa");
			}else if ($notification_array["text"] == "point_removed") {
				$output .= esc_html__('Your points removed because the "Best answer" was removed.','wpqa');
			}else if ($notification_array["text"] == "cancel_best_answer") {
				$output .= esc_html__("canceled your answer as the best answer.","wpqa");
			}else if ($notification_array["text"] == "answer_asked_question") {
				$output .= esc_html__("answered the question you asked.","wpqa");
			}else if ($notification_array["text"] == "answer_question") {
				$output .= esc_html__("answered your question.","wpqa");
			}else if ($notification_array["text"] == "answer_question_follow") {
				$output .= esc_html__("answered the question you followed.","wpqa");
			}else if ($notification_array["text"] == "reply_answer") {
				$output .= esc_html__("replied to your answer.","wpqa");
			}else if ($notification_array["text"] == "add_question") {
				$output .= esc_html__("added a new question.","wpqa");
			}else if ($notification_array["text"] == "add_question_user") {
				$output .= esc_html__("asked you a question.","wpqa");
			}else if ($notification_array["text"] == "question_favorites") {
				$output .= esc_html__("added your question to favorites.","wpqa");
			}else if ($notification_array["text"] == "question_remove_favorites") {
				$output .= esc_html__("removed your question from favorites.","wpqa");
			}else if ($notification_array["text"] == "follow_question") {
				$output .= esc_html__("followed your question.","wpqa");
			}else if ($notification_array["text"] == "unfollow_question") {
				$output .= esc_html__("unfollowed your question.","wpqa");
			}else if ($notification_array["text"] == "approved_answer") {
				$output .= esc_html__("The administrator approved your answer.","wpqa");
			}else if ($notification_array["text"] == "approved_comment") {
				$output .= esc_html__("The administrator approved your comment.","wpqa");
			}else if ($notification_array["text"] == "approved_question") {
				if (!empty($notification_array["another_user_id"])) {
					$output .= esc_html__("approved your question.","wpqa");
				}else {
					$output .= esc_html__("The administrator approved your question.","wpqa");
				}
			}else if ($notification_array["text"] == "approved_message") {
				$output .= "<a href='".esc_url(wpqa_get_profile_permalink(get_current_user_id(),"messages"))."'>".esc_html__("The administrator approved your message.","wpqa")."</a>";
			}else if ($notification_array["text"] == "answer_review") {
				$output .= esc_html__("The administrator will review your answer.","wpqa");
			}else if ($notification_array["text"] == "question_review") {
				$output .= esc_html__("The administrator will review your question.","wpqa");
			}else if ($notification_array["text"] == "approved_post") {
				$output .= esc_html__("The administrator approved your post.","wpqa");
			}else if ($notification_array["text"] == "action_comment") {
				$output .= sprintf(esc_html__("The administrator %s your %s.","wpqa"),$notification_array["more_text"],(isset($notification_array["type_of_item"]) && $notification_array["type_of_item"] == "answer"?esc_html__("answer","wpqa"):esc_html__("comment","wpqa")));
			}else if ($notification_array["text"] == "action_post") {
				$output .= sprintf(esc_html__("The administrator %s your %s.","wpqa"),$notification_array["more_text"],(isset($notification_array["type_of_item"]) && $notification_array["type_of_item"] == "question"?esc_html__("question","wpqa"):esc_html__("post","wpqa")));
			}else if ($notification_array["text"] == "delete_reason") {
				$output .= sprintf(esc_html__("The administrator reason: %s.","wpqa"),$notification_array["more_text"]);
			}else if ($notification_array["text"] == "delete_question" || $notification_array["text"] == "delete_post") {
				if (isset($notification_array["type_of_item"]) && $notification_array["type_of_item"] == "question") {
					if (!empty($notification_array["another_user_id"])) {
						$output .= esc_html__("deleted your question.","wpqa");
					}else {
						$output .= esc_html__("Your question was deleted.","wpqa");
					}
				}else {
					$output .= esc_html__("Your post was deleted.","wpqa");
				}
			}else if ($notification_array["text"] == "delete_answer" || $notification_array["text"] == "delete_comment") {
				if (isset($notification_array["type_of_item"]) && $notification_array["type_of_item"] == "answer") {
					$output .= esc_html__("Your answer was deleted.","wpqa");
				}else {
					$output .= esc_html__("Your comment was deleted.","wpqa");
				}
			}else if ($notification_array["text"] == "add_message_user") {
				$output .= "<a href='".esc_url(wpqa_get_profile_permalink(get_current_user_id(),"messages"))."'>".esc_html__("sent a message for you.","wpqa")."</a>";
			}else if ($notification_array["text"] == "seen_message") {
				$output .= esc_html__("seen your message.","wpqa");
			}else if ($notification_array["text"] == "rejected_withdrawal_points") {
				$output .= esc_html__("The administrator rejected your money request.","wpqa");
			}else if ($notification_array["text"] == "accepted_withdrawal_points") {
				$output .= esc_html__("The administrator approved your money request.","wpqa");
			}else if ($notification_array["text"] == "requested_money") {
				$output .= esc_html__("requested his money.","wpqa");
			}else if ($notification_array["text"] == "request_group") {
				$output .= esc_html__("asked to join the group.","wpqa");
			}else if ($notification_array["text"] == "posts_like") {
				$output .= esc_html__("liked your group post.","wpqa");
			}else if ($notification_array["text"] == "add_group_moderator") {
				$output .= esc_html__("You added as a moderator in the group.","wpqa");
			}else if ($notification_array["text"] == "remove_group_moderator") {
				$output .= esc_html__("You removed as a moderator in the group.","wpqa");
			}else if ($notification_array["text"] == "approve_request_group") {
				if (!empty($notification_array["another_user_id"])) {
					$output .= esc_html__("accepted your join request.","wpqa");
				}else {
					$output .= esc_html__("The administrator accepted your join request.","wpqa");
				}
			}else if ($notification_array["text"] == "decline_request_group") {
				if (!empty($notification_array["another_user_id"])) {
					$output .= esc_html__("declined your request.","wpqa");
				}else {
					$output .= esc_html__("The administrator declined your request.","wpqa");
				}
			}else if ($notification_array["text"] == "add_group_invitations") {
				$output .= esc_html__("invited you to the group.","wpqa");
			}else if ($notification_array["text"] == "approved_group") {
				if (!empty($notification_array["another_user_id"])) {
					$output .= esc_html__("approved your group.","wpqa");
				}else {
					$output .= esc_html__("The administrator approved your group.","wpqa");
				}
			}else if ($notification_array["text"] == "approved_posts") {
				if (!empty($notification_array["another_user_id"])) {
					$output .= esc_html__("approved your group post.","wpqa");
				}else {
					$output .= esc_html__("The administrator approved your group post.","wpqa");
				}
			}else if ($notification_array["text"] == "blocked_group") {
				$output .= esc_html__("blocked you from the group.","wpqa");
			}else if ($notification_array["text"] == "unblocked_group") {
				$output .= esc_html__("unblocked you from the group.","wpqa");
			}else if ($notification_array["text"] == "removed_user_group") {
				$output .= esc_html__("removed you from the group.","wpqa");
			}else {
				$output .= $notification_array["text"];
			}
			if ((!empty($notification_array["post_id"]) && !empty($notification_array["comment_id"]) && $get_post_status != "trash" && isset($get_comment) && $get_comment->comment_approved != "spam" && $get_comment->comment_approved != "trash") || (!empty($notification_array["post_id"]) && empty($notification_array["comment_id"]) && $get_post_status != "trash" && isset($get_the_permalink) && $get_the_permalink != "")) {
			$output .= '</a>';
		}
		if (is_super_admin($notification_array["user_id"]) && !empty($notification_array["post_id"]) && !empty($notification_array["comment_id"])) {
			if (isset($get_comment) && $get_comment->comment_approved == "spam") {
				$output .= " ".esc_html__('( Spam )','wpqa');
			}else if ($get_post_status == "trash" || (isset($get_comment) && $get_comment->comment_approved == "trash")) {
				$output .= " ".esc_html__('( Trashed )','wpqa');
			}else if (empty($get_comment)) {
				$output .= " ".esc_html__('( Deleted )','wpqa');
			}
			if ($notification_array["text"] == "delete_reason") {
				$output .= " - ".(isset($notification_array["type_of_item"]) && $notification_array["type_of_item"] == "answer"?esc_html__("answer","wpqa"):esc_html__("comment","wpqa"));
			}
		}
		if (is_super_admin($notification_array["user_id"]) && !empty($notification_array["post_id"]) && empty($notification_array["comment_id"])) {
			if ($get_post_status == "trash") {
				$output .= " ".esc_html__('( Trashed )','wpqa');
			}else if (empty($get_the_permalink)) {
				$output .= " ".esc_html__('( Deleted )','wpqa');
			}
		}
		if (!empty($notification_array["more_text"]) && $notification_array["more_text"] != "admin_add_points" && $notification_array["text"] != "action_post" && $notification_array["text"] != "action_comment" && $notification_array["text"] != "delete_reason") {
			$output .= " - ".esc_attr($notification_array["more_text"]).".";
		}
		
		if ($show_date == "on") {
			$output .= "<span class='notifications-date'>".$notification_array["time"]."</span>
			</div></li>";
		}
		return $output;	
	}
endif;?>