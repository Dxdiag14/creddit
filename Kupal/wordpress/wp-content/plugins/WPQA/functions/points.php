<?php

/* @author    2codeThemes
*  @package   WPQA/functions
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* Get points for badges page */
function wpqa_get_points() {
	$edit_profile_items_2  = wpqa_options("edit_profile_items_2");
	$show_social_points    = ((isset($edit_profile_items_2["facebook"]) && isset($edit_profile_items_2["facebook"]["value"]) && $edit_profile_items_2["facebook"]["value"] == "facebook") || (isset($edit_profile_items_2["twitter"]) && isset($edit_profile_items_2["twitter"]["value"]) && $edit_profile_items_2["twitter"]["value"] == "twitter") || (isset($edit_profile_items_2["youtube"]) && isset($edit_profile_items_2["youtube"]["value"]) && $edit_profile_items_2["youtube"]["value"] == "youtube") || (isset($edit_profile_items_2["vimeo"]) && isset($edit_profile_items_2["vimeo"]["value"]) && $edit_profile_items_2["vimeo"]["value"] == "vimeo") || (isset($edit_profile_items_2["linkedin"]) && isset($edit_profile_items_2["linkedin"]["value"]) && $edit_profile_items_2["linkedin"]["value"] == "linkedin") || (isset($edit_profile_items_2["instagram"]) && isset($edit_profile_items_2["instagram"]["value"]) && $edit_profile_items_2["instagram"]["value"] == "instagram") || (isset($edit_profile_items_2["pinterest"]) && isset($edit_profile_items_2["pinterest"]["value"]) && $edit_profile_items_2["pinterest"]["value"] == "pinterest")?true:false);
	$active_referral       = wpqa_options("active_referral");
	$point_add_question    = wpqa_options("point_add_question");
	$point_best_answer     = wpqa_options("point_best_answer");
	$point_voting_question = wpqa_options("point_voting_question");
	$point_add_comment     = wpqa_options("point_add_comment");
	$point_voting_answer   = wpqa_options("point_voting_answer");
	$point_following_me    = wpqa_options("point_following_me");
	$point_add_post        = wpqa_options("point_add_post");
	$point_new_user        = wpqa_options("point_new_user");
	$points_referral       = wpqa_options("points_referral");
	$referral_membership   = wpqa_options("referral_membership");
	$points_social         = wpqa_options("points_social");
	$points_array          = array(
								"point_add_question"    => array("points" => $point_add_question),
								"point_best_answer"     => array("points" => $point_best_answer),
								"point_voting_question" => array("points" => $point_voting_question),
								"point_add_comment"     => array("points" => $point_add_comment),
								"point_voting_answer"   => array("points" => $point_voting_answer),
								"point_following_me"    => array("points" => $point_following_me),
								"point_add_post"        => array("points" => $point_add_post),
								"point_new_user"        => array("points" => $point_new_user),
								"points_referral"       => array("points" => ($active_referral == "on"?$points_referral:0)),
								"referral_membership"   => array("points" => ($active_referral == "on"?$referral_membership:0)),
								"points_social"         => array("points" => ($show_social_points == true?$points_social:0)),
							);
	$points_array = apply_filters("wpqa_filter_points_array",$points_array);
	$points = array_column($points_array,'points');
	array_multisort($points,SORT_DESC,$points_array);
	return $points_array;
}
function wpqa_get_points_name($key) {
	if ($key == "point_add_question") {
		$output = esc_html__("For adding a new question.","wpqa");
	}else if ($key == "point_best_answer") {
		$output = esc_html__("When your answer has been chosen as the best answer.","wpqa");
	}else if ($key == "point_voting_question") {
		$output = esc_html__("Your question gets a vote.","wpqa");
	}else if ($key == "point_add_comment") {
		$output = esc_html__("For adding an answer.","wpqa");
	}else if ($key == "point_voting_answer") {
		$output = esc_html__("Your answer gets a vote.","wpqa");
	}else if ($key == "point_following_me") {
		$output = esc_html__("Each time when a user follows you.","wpqa");
	}else if ($key == "point_add_post") {
		$output = esc_html__("For adding a new post.","wpqa");
	}else if ($key == "point_new_user") {
		$output = esc_html__("For Signing up.","wpqa");
	}else if ($key == "points_referral") {
		$output = esc_html__("For referring a new user.","wpqa");
	}else if ($key == "referral_membership") {
		$output = esc_html__("For referring a new user for paid membership.","wpqa");
	}else if ($key == "points_social") {
		$output = esc_html__("For adding your social media links to your profile.","wpqa");
	}else if (isset($value["text"]) && $value["text"] != "") {
		$output = esc_html($value["text"]);
	}
	if (isset($output)) {
		return $output;
	}
}?>