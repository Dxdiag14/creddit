<?php

/* @author    2codeThemes
*  @package   WPQA/functions
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* Review posts and questions */
if (!function_exists('wpqa_review_post')) :
	function wpqa_review_post($post,$is_super_admin,$moderators_permissions) {
		$post_type = $post->post_type;
		if ($is_super_admin || (isset($moderators_permissions['delete']) && $moderators_permissions['delete'] == "delete") || (isset($moderators_permissions['approve']) && $moderators_permissions['approve'] == "approve") || (isset($moderators_permissions['edit']) && $moderators_permissions['edit'] == "edit") || (isset($moderators_permissions['ban']) && $moderators_permissions['ban'] == "ban")) {?>
			<a class="meta-answer review-post" href="#"><?php echo ($post_type == "question"?esc_html__("Review the question","wpqa"):esc_html__("Review the post","wpqa"))?></a>
			<?php if ($is_super_admin || (isset($moderators_permissions['approve']) && $moderators_permissions['approve'] == "approve")) {
				echo '<a data-nonce="'.wp_create_nonce("pending_nonce").'" class="meta-answer pending-post-meta a-pending-post" href="#"><i class="icon-cog"></i>'.esc_html__("Approve","wpqa").'</a>';
			}
			if ($is_super_admin || (isset($moderators_permissions['edit']) && $moderators_permissions['edit'] == "edit")) {
				echo '<a class="meta-answer pending-post-meta e-pending-post" href="'.esc_url_raw(add_query_arg(array("page" => "pending"),wpqa_edit_permalink($post->ID,$post_type))).'"><i class="icon-pencil"></i>'.esc_html__("Edit","wpqa").'</a>';
			}
			if ($is_super_admin || (isset($moderators_permissions['delete']) && $moderators_permissions['delete'] == "delete")) {
				echo '<a data-nonce="'.wp_create_nonce("pending_nonce").'" class="meta-answer pending-post-meta d-pending-post delete-pending-'.$post_type.'" href="#"><i class="icon-trash"></i>'.esc_html__("Delete","wpqa").'</a>';
			}
			if ($is_super_admin || (isset($moderators_permissions['ban']) && $moderators_permissions['ban'] == "ban")) {
				$post_author = $post->post_author;
				$not_user = $anonymously_user = 0;
				if ($post_author > 0) {
					$not_user = $post_author;
				}else {
					$anonymously_user = get_post_meta($post->ID,'anonymously_user',true);
				}
				$if_user_id = get_user_by("id",($anonymously_user > 0?$anonymously_user:$not_user));
				$if_ban = (isset($if_user_id->caps["ban_group"]) && $if_user_id->caps["ban_group"] == 1?true:false);
				echo '<a data-nonce="'.wp_create_nonce("pending_nonce").'" class="meta-answer pending-post-meta '.($if_ban?"u-pending-post":"b-pending-post").'" href="#"><i class="'.($if_ban?"icon-back":"icon-cancel-circled").'"></i><span>'.($if_ban?esc_html__("Unban user","wpqa"):esc_html__("Ban user","wpqa")).'</span></a>';
			}
		}
	}
endif;
/* Approve question or post */
if (!function_exists('wpqa_pending_post')) :
	function wpqa_pending_post() {
		check_ajax_referer('pending_nonce','pending_nonce');
		$user_id = get_current_user_id();
		$is_super_admin = is_super_admin($user_id);
		$pending_type = esc_html($_POST['pending_type']);
		$moderators_permissions = wpqa_user_moderator($user_id);
		if ((($pending_type == "ban" || $pending_type == "unban") && ($is_super_admin || (isset($moderators_permissions['ban']) && $moderators_permissions['ban'] == "ban")) || ($pending_type == "delete" && ($is_super_admin || isset($moderators_permissions['delete']) && $moderators_permissions['delete'] == "delete"))) || ($pending_type == "approve" && ($is_super_admin || (isset($moderators_permissions['approve']) && $moderators_permissions['approve'] == "approve")))) {
			$post_id = (int)$_POST['post_id'];
			$get_post = get_post($post_id);
			$post_id = (isset($get_post->ID) && $get_post->ID > 0?$get_post->ID:0);
			$post_type = $get_post->post_type;
			if ($post_id > 0) {
				$post_author = $get_post->post_author;
				$not_user = $anonymously_user = 0;
				if ($post_author > 0) {
					$not_user = $post_author;
				}else {
					$anonymously_user = get_post_meta($post_id,'anonymously_user',true);
				}
			}
		}
		if (($pending_type == "ban" || $pending_type == "unban") && ($is_super_admin || (isset($moderators_permissions['ban']) && $moderators_permissions['ban'] == "ban"))) {
			if ($post_id > 0 && ($post_author > 0 || $anonymously_user > 0)) {
				wpqa_ban_unban_user(($anonymously_user > 0?$anonymously_user:$not_user),$pending_type,$user_id);
			}
		}else if (($pending_type == "delete" && ($is_super_admin || (isset($moderators_permissions['delete']) && $moderators_permissions['delete'] == "delete"))) || ($pending_type == "approve" && ($is_super_admin || (isset($moderators_permissions['approve']) && $moderators_permissions['approve'] == "approve")))) {
			if ($post_id > 0) {
				$post_status = ($pending_type == 'approve'?'publish':'trash');
				$data = array(
					'ID'          => (int)$post_id,
					'post_status' => $post_status,
				);
				remove_action('save_post','wpqa_save_post');
				wp_update_post($data);
				$post_approved_before = get_post_meta($post_id,'post_approved_before',true);
				if ($pending_type == 'approve') {
					if ($post_approved_before != "yes") {
						$post_username = get_post_meta($post_id,$post_type.'_username',true);
						$post_email = get_post_meta($post_id,$post_type.'_email',true);
						if ($post_username == "") {
							$post_no_username = get_post_meta($post_id,$post_type.'_no_username',true);
						}
						if ($post_type == "question") {
							wpqa_notifications_ask_question($post_id,$post_username,get_post_meta($post_id,"user_id",true),$not_user,$anonymously_user,$user_id,true);
						}
						wpqa_post_publish($get_post,$not_user);
					}
					update_post_meta($post_id,'post_approved_before',"yes");
				}
				if ((($post_approved_before != "yes" && $pending_type == 'approve') || $pending_type == 'delete') && (($not_user > 0 && $not_user != $user_id) || ($anonymously_user > 0 && $anonymously_user != $user_id))) {
					wpqa_notifications_activities(($anonymously_user > 0?$anonymously_user:$not_user),$user_id,"",$post_id,"",($pending_type == "approve"?'approved_'.$post_type:'delete_'.$post_type),"notifications","",$post_type);
				}
			}
		}
		die();
	}
endif;
add_action('wp_ajax_wpqa_pending_post','wpqa_pending_post');
add_action('wp_ajax_nopriv_wpqa_pending_post','wpqa_pending_post');
/* Delete question or post */
if (!function_exists('wpqa_delete_question_post')) :
	function wpqa_delete_question_post() {
		check_ajax_referer('wpqa_delete_nonce','wpqa_delete_nonce');
		$data_id = (int)$_POST["data_id"];
		$data_div = esc_attr($_POST["data_div"]);
		$get_post = get_post($data_id);
		$post_author = $get_post->post_author;
		$anonymously_user = get_post_meta($data_id,"anonymously_user",true);
		if ($post_author > 0 || $anonymously_user > 0) {
			wpqa_notifications_activities(($post_author > 0?$post_author:$anonymously_user),"","","","","delete_".$get_post->post_type,"notifications",$data_div,($get_post->post_type == "question"?"question":""));
		}
		wp_delete_post($data_id,true);
		die();
	}
endif;
add_action('wp_ajax_wpqa_delete_question_post','wpqa_delete_question_post');
add_action('wp_ajax_nopriv_wpqa_delete_question_post','wpqa_delete_question_post');
/* Delete comment or answer */
if (!function_exists('wpqa_delete_comment_answer')) :
	function wpqa_delete_comment_answer() {
		check_ajax_referer('wpqa_delete_nonce','wpqa_delete_nonce');
		$data_id = (int)$_POST["data_id"];
		$data_div = esc_html($_POST["data_div"]);
		$comment_type = get_comment_meta($data_id,'comment_type',true);
		$get_comment = get_comment($data_id);
		$anonymously_user = get_comment_meta($data_id,'anonymously_user',true);
		if ($get_comment->user_id > 0 || $anonymously_user > 0) {
			wpqa_notifications_activities(($get_comment->user_id > 0?$get_comment->user_id:$anonymously_user),"","","","","delete_".($comment_type == "question"?"answer":"comment"),"notifications",$data_div,($comment_type == "question"?"answer":"comment"));
		}
		wp_delete_comment($data_id,true);
		die();
	}
endif;
add_action('wp_ajax_wpqa_delete_comment_answer','wpqa_delete_comment_answer');
add_action('wp_ajax_nopriv_wpqa_delete_comment_answer','wpqa_delete_comment_answer');
/* Ban and unban users */
function wpqa_ban_unban_user($user_id,$ban_type,$get_current_user_id) {
	$ban_name = "ban_group";
	if ($ban_type == "ban") {
		$if_user_id = get_user_by("id",$user_id);
		update_user_meta($user_id,$ban_name,(isset($if_user_id->roles) && isset($if_user_id->roles[0])?$if_user_id->roles[0]:""));
		$ban_group = get_role($ban_name);
		if (!isset($ban_group)) {
			add_role($ban_name,esc_html__("Ban group","wpqa"),array('read' => false));
		}
		wp_update_user(array('ID' => $user_id,'role' => $ban_name));
		wpqa_notifications_activities($get_current_user_id,$user_id,"","","","ban_user","activities");
	}else {
		$ban_user = get_user_meta($user_id,$ban_name,true);
		$default_group = wpqa_options("default_group");
		$default_group = ($default_group != ""?$default_group:"subscriber");
		$ban_user = ($ban_user != ""?$ban_user:$default_group);
		wp_update_user(array('ID' => $user_id,'role' => $ban_user));
		wpqa_notifications_activities($get_current_user_id,$user_id,"","","","unban_user","activities");
	}
}?>