<?php

/* @author    2codeThemes
*  @package   WPQA/shortcodes
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* wpqa_edit_comment_attr */
function wpqa_edit_comment_attr() {
	$comment_id = (int)get_query_var(apply_filters('wpqa_edit_comments','edit_comment'));
	$get_comment = get_comment($comment_id);
	$user_id = get_current_user_id();
	$get_post = array();
	if (isset($comment_id) && $comment_id != 0 && is_object($get_comment)) {
		$post_id = $get_comment->comment_post_ID;
		$comment_user_id = $get_comment->user_id;
		$get_post = get_post($post_id);
		$can_edit_comment = wpqa_options("can_edit_comment");
		$can_edit_comment_after = wpqa_options("can_edit_comment_after");
		$can_edit_comment_after = (int)(isset($can_edit_comment_after) && $can_edit_comment_after > 0?$can_edit_comment_after:0);
		if (version_compare(phpversion(), '5.3.0', '>')) {
			$time_now = strtotime(current_time( 'mysql' ),date_create_from_format('Y-m-d H:i',current_time( 'mysql' )));
		}else {
			list($year, $month, $day, $hour, $minute, $second) = sscanf(current_time( 'mysql' ), '%04d-%02d-%02d %02d:%02d:%02d');
			$datetime = new DateTime("$year-$month-$day $hour:$minute:$second");
			$time_now = strtotime($datetime->format('r'));
		}
		$time_edit_comment = strtotime('+'.$can_edit_comment_after.' hour',strtotime($get_comment->comment_date));
		$time_end = ($time_now-$time_edit_comment)/60/60;
	}
	$out = '';
	if (isset($comment_id) && $comment_id != 0 && $get_post) {
		$is_super_admin = is_super_admin($user_id);
		$edit_delete_posts_comments = wpqa_options("edit_delete_posts_comments");
		$activate_edit = wpqa_group_edit_comments($post_id,$is_super_admin,$can_edit_comment,$comment_user_id,$user_id,$edit_delete_posts_comments);
		if ($activate_edit == true || $is_super_admin || ($can_edit_comment == "on" && $get_comment->comment_approved == 1 && $comment_user_id == $user_id && $comment_user_id > 0 && ($can_edit_comment_after == 0 || $time_end <= $can_edit_comment_after))) {
			$attachment_answer = wpqa_options("attachment_answer");
			$featured_image_answer = wpqa_options("featured_image_answer");
			$out .= '<form class="wpqa_form edit-comment-form" method="post"'.($attachment_answer == "on" || $featured_image_answer == "on"?' enctype="multipart/form-data"':'').'>'.apply_filters('wpqa_edit_comment','edit_comment').
				apply_filters('wpqa_edit_comment_fields',false,"edit",$comment_id,$get_post).'
				<div class="form-inputs clearfix">
					<p>
						<label>'.esc_html__("Comment","wpqa").'<span class="required">*</span></label>';
						$comment_editor = wpqa_options(($get_post->post_type == "question"?"answer_editor":"comment_editor"));
						if ($comment_editor == "on") {
							$settings = array("textarea_name" => "comment","media_buttons" => true,"textarea_rows" => 10);
							$settings = apply_filters('wpqa_edit_comment_editor_setting',$settings);
							ob_start();
							wp_editor((isset($posted['comment'])?wpqa_kses_stip_wpautop(nl2br($posted['comment'])):wpqa_kses_stip($get_comment->comment_content,"yes")),"comment",$settings);
							$comment_contents = ob_get_clean();
						}else {
							$comment_contents = '<textarea name="comment" aria-required="true">'.(isset($posted['comment'])?wpqa_kses_stip($posted['comment'],"yes"):wpqa_kses_stip($get_comment->comment_content,"yes")).'</textarea>';
						}
						$out .= $comment_contents;
					$out .= '</p>
				</div>
				
				<p class="form-submit">
					<input type="hidden" name="form_type" value="edit_comment">
					<input type="hidden" name="comment_id" value="'.$comment_id.'">
					<input type="hidden" name="wpqa_comment_nonce" value="'.wp_create_nonce("wpqa_comment_nonce").'">
					<input type="submit" value="'.esc_attr__("Edit Comment","wpqa").'" class="button-default edit-comment button-hide-click">
					<span class="load_span"><span class="loader_2"></span></span>
				</p>
			</form>';
		}else {
			$out .= '<div class="alert-message warning"><i class="icon-flag"></i><p>'.esc_html__("You are not allowed to edit this comment.","wpqa").'</p></div>';
		}
	}else {
		$out .= '<div class="alert-message warning"><i class="icon-flag"></i><p>'.esc_html__("Sorry, no comment has been selected or not found.","wpqa").'</p></div>';
	}
	
	return $out;
}?>