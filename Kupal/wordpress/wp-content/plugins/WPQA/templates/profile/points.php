<?php

/* @author    2codeThemes
*  @package   WPQA/templates/profile
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
   exit; // Exit if accessed directly
}

if (isset($whats_type_result[3]) && $whats_type_result[3] == "+") {
	echo "<i class='icon-thumbs-up'></i>";
}else {
	echo "<i class='icon-thumbs-down'></i>";
}
echo "<div>
	<span class='point-span'>".$whats_type_result[3].$whats_type_result[2]."</span>";
	if (!empty($whats_type_result[5])) {
		$get_the_permalink = get_the_permalink($whats_type_result[5]);
		$get_post_status = get_post_status($whats_type_result[5]);
	}
	if (!empty($whats_type_result[6])) {
		$get_comment = get_comment($whats_type_result[6]);
	}
	if (!empty($whats_type_result["user_id"])) {
		$get_user_url = wpqa_profile_url($whats_type_result["user_id"]);
	}
	
	if (!empty($whats_type_result[5]) && !empty($whats_type_result[6]) && $get_post_status != "trash" && isset($get_comment) && $get_comment->comment_approved != "spam" && $get_comment->comment_approved != "trash") {?>
		<a href="<?php echo get_the_permalink($whats_type_result[5]).(isset($whats_type_result[6])?"#comment-".$whats_type_result[6]:"")?>">
	<?php }else if (!empty($whats_type_result[5]) && (empty($whats_type_result[6])) && $get_post_status != "trash" && isset($get_the_permalink) && $get_the_permalink != "") {?>
		<a href="<?php echo get_the_permalink($whats_type_result[5])?>">
	<?php }else if (!empty($whats_type_result["user_id"]) && isset($get_user_url) && $get_user_url != "") {?>
		<a href="<?php echo esc_url($get_user_url)?>">
	<?php }
		$result_text = apply_filters("wpqa_points_text",false,$whats_type_result[4]);
		if ($result_text != "") {
			$output .= $result_text;
		}else if ($whats_type_result[4] != "add_facebook" && $whats_type_result[4] != "add_twitter" && $whats_type_result[4] != "add_youtube" && $whats_type_result[4] != "add_vimeo" && $whats_type_result[4] != "add_linkedin" && $whats_type_result[4] != "add_instagram" && $whats_type_result[4] != "add_pinterest" && $whats_type_result[4] != "remove_facebook" && $whats_type_result[4] != "remove_twitter" && $whats_type_result[4] != "remove_youtube" && $whats_type_result[4] != "remove_vimeo" && $whats_type_result[4] != "remove_linkedin" && $whats_type_result[4] != "remove_instagram" && $whats_type_result[4] != "remove_pinterest" && $whats_type_result[4] != "sticky_points" && $whats_type_result[4] != "subscribe_points" && $whats_type_result[4] != "ask_points" && $whats_type_result[4] != "answer_points" && $whats_type_result[4] != "buy_questions_points" && $whats_type_result[4] != "post_points" && $whats_type_result[4] != "buy_posts_points" && $whats_type_result[4] != "buy_points" && $whats_type_result[4] != "refund_points" && $whats_type_result[4] != "voting_question" && $whats_type_result[4] != "voting_answer" && $whats_type_result[4] != "rating_question" && $whats_type_result[4] != "rating_answer" && $whats_type_result[4] != "user_unfollow" && $whats_type_result[4] != "user_follow" && $whats_type_result[4] != "bump_question" && $whats_type_result[4] != "select_best_answer" && $whats_type_result[4] != "cancel_best_answer" && $whats_type_result[4] != "answer_question" && $whats_type_result[4] != "add_question" && $whats_type_result[4] != "add_post" && $whats_type_result[4] != "question_point" && $whats_type_result[4] != "gift_site" && $whats_type_result[4] != "points_referral" && $whats_type_result[4] != "referral_membership" && $whats_type_result[4] != "admin_add_points" && $whats_type_result[4] != "admin_remove_points" && $whats_type_result[4] != "point_back" && $whats_type_result[4] != "point_removed" && $whats_type_result[4] != "delete_answer" && $whats_type_result[4] != "delete_best_answer" && $whats_type_result[4] != "delete_follow_user" && $whats_type_result[4] != "delete_question") {
			echo ($whats_type_result[4]);
		}else if ($whats_type_result[4] == "add_facebook") {
			esc_html_e("You have added your Facebook link.","wpqa");
		}else if ($whats_type_result[4] == "add_twitter") {
			esc_html_e("You have added your Twitter link.","wpqa");
		}else if ($whats_type_result[4] == "add_youtube") {
			esc_html_e("You have added your Youtube link.","wpqa");
		}else if ($whats_type_result[4] == "add_vimeo") {
			esc_html_e("You have added your Vimeo link.","wpqa");
		}else if ($whats_type_result[4] == "add_linkedin") {
			esc_html_e("You have added your Linkedin link.","wpqa");
		}else if ($whats_type_result[4] == "add_instagram") {
			esc_html_e("You have added your Instagram link.","wpqa");
		}else if ($whats_type_result[4] == "add_pinterest") {
			esc_html_e("You have added your Pinterest link.","wpqa");
		}else if ($whats_type_result[4] == "remove_facebook") {
			esc_html_e("You have removed your Facebook link.","wpqa");
		}else if ($whats_type_result[4] == "remove_twitter") {
			esc_html_e("You have removed your Twitter link.","wpqa");
		}else if ($whats_type_result[4] == "remove_youtube") {
			esc_html_e("You have removed your Youtube link.","wpqa");
		}else if ($whats_type_result[4] == "remove_vimeo") {
			esc_html_e("You have removed your Vimeo link.","wpqa");
		}else if ($whats_type_result[4] == "remove_linkedin") {
			esc_html_e("You have removed your Linkedin link.","wpqa");
		}else if ($whats_type_result[4] == "remove_instagram") {
			esc_html_e("You have removed your Instagram link.","wpqa");
		}else if ($whats_type_result[4] == "remove_pinterest") {
			esc_html_e("You have removed your Pinterest link.","wpqa");
		}else if ($whats_type_result[4] == "sticky_points") {
			esc_html_e("You have stickied your question by points.","wpqa");
		}else if ($whats_type_result[4] == "ask_points") {
			esc_html_e("You have bought to ask a question by points.","wpqa");
		}else if ($whats_type_result[4] == "answer_points") {
			esc_html_e("You have bought to add an answer by points.","wpqa");
		}else if ($whats_type_result[4] == "buy_questions_points") {
			esc_html_e("You have bought to ask questions by points.","wpqa");
		}else if ($whats_type_result[4] == "post_points") {
			esc_html_e("You have bought to add a post by points.","wpqa");
		}else if ($whats_type_result[4] == "buy_posts_points") {
			esc_html_e("You have bought to add posts by points.","wpqa");
		}else if ($whats_type_result[4] == "subscribe_points") {
			esc_html_e("You have subscribed to paid membership by points.","wpqa");
		}else if ($whats_type_result[4] == "buy_points") {
			esc_html_e("You have bought new points.","wpqa");
		}else if ($whats_type_result[4] == "refund_points") {
			esc_html_e("You got a refund for your buying of the points.","wpqa");
		}else if ($whats_type_result[4] == "voting_question" || $whats_type_result[4] == "rating_question") {
			esc_html_e("Voted your question.","wpqa");
		}else if ($whats_type_result[4] == "voting_answer" || $whats_type_result[4] == "rating_answer") {
			esc_html_e("Voted your answer.","wpqa");
		}else if ($whats_type_result[4] == "user_follow") {
			esc_html_e("User followed You.","wpqa");
		}else if ($whats_type_result[4] == "user_unfollow") {
			esc_html_e("User unfollowed You.","wpqa");
		}else if ($whats_type_result[4] == "bump_question") {
			esc_html_e("Discount points to bump question.","wpqa");
		}else if ($whats_type_result[4] == "select_best_answer") {
			esc_html_e("Chosen your answer as Best answer.","wpqa");
		}else if ($whats_type_result[4] == "cancel_best_answer") {
			esc_html_e("Canceled your answer as the best answer.","wpqa");
		}else if ($whats_type_result[4] == "answer_question") {
			esc_html_e("You have answered the question.","wpqa");
		}else if ($whats_type_result[4] == "add_question") {
			esc_html_e("Added a new question.","wpqa");
		}else if ($whats_type_result[4] == "delete_question") {
			esc_html_e("Deleted your question.","wpqa");
		}else if ($whats_type_result[4] == "add_post") {
			esc_html_e("Added a new post.","wpqa");
		}else if ($whats_type_result[4] == "gift_site") {
			esc_html_e("Gift of the site.","wpqa");
		}else if ($whats_type_result[4] == "points_referral") {
			esc_html_e("Refer a new user.","wpqa");
		}else if ($whats_type_result[4] == "referral_membership") {
			esc_html_e("Refer a new user for paid membership.","wpqa");
		}else if ($whats_type_result[4] == "question_point") {
			esc_html_e("Points have been deducted for asking a question.","wpqa");
		}else if ($whats_type_result[4] == "admin_add_points") {
			esc_html_e("The administrator added points for you.","wpqa");
		}else if ($whats_type_result[4] == "admin_remove_points") {
			esc_html_e("The administrator removed points from you.","wpqa");
		}else if ($whats_type_result[4] == "point_back") {
			esc_html_e("Your points have been added because the Best answer was selected.","wpqa");
		}else if ($whats_type_result[4] == "point_removed") {
			esc_html_e("Your point has been removed because the Best answer was removed.","wpqa");
		}else if ($whats_type_result[4] == "delete_answer") {
			esc_html_e("Your answer was removed.","wpqa");
		}else if ($whats_type_result[4] == "delete_best_answer") {
			esc_html_e("Deleted your best answer.","wpqa");
		}else if ($whats_type_result[4] == "delete_follow_user") {
			esc_html_e("Deleted your following user.","wpqa");
		}
	if ((!empty($whats_type_result[5]) && !empty($whats_type_result[6]) && $get_post_status != "trash" && isset($get_comment) && $get_comment->comment_approved != "spam" && $get_comment->comment_approved != "trash") || (!empty($whats_type_result[5]) && (empty($whats_type_result[6])) && $get_post_status != "trash" && isset($get_the_permalink) && $get_the_permalink != "") || (!empty($whats_type_result["user_id"]) && isset($get_user_url) && $get_user_url != "")) {?>
		</a>
	<?php }?>