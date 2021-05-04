<?php

/* @author    2codeThemes
*  @package   WPQA/templates/profile
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
   exit; // Exit if accessed directly
}

if ($list_child == "li") {?>
	<li<?php echo (!wpqa_user_title()?" class='active-tab'":"")?>><a href="<?php echo esc_url(wpqa_profile_url($wpqa_user_id))?>">
<?php }else {?>
	<option<?php echo (!wpqa_user_title()?" selected='selected'":"")?> value="<?php echo esc_url(wpqa_profile_url($wpqa_user_id))?>">
<?php }
		esc_html_e("About","wpqa");
if ($list_child == "li") {?>
	</a></li>
<?php }else {?>
	</option>
<?php }
$user_profile_pages = apply_filters("wpqa_user_profile_pages",$user_profile_pages);
if (isset($user_profile_pages) && is_array($user_profile_pages) && !empty($user_profile_pages)) {
	foreach ($user_profile_pages as $key => $value) {
		do_action("wpqa_action_user_profile_pages",$user_profile_pages,$key,$value);
		if ($key == "questions" && isset($user_profile_pages["questions"]["value"]) && $user_profile_pages["questions"]["value"] == "questions") {
			$selected = (wpqa_is_user_questions() || "questions" == wpqa_user_title()?true:"");
			$last_url = wpqa_get_profile_permalink($wpqa_user_id,"questions");
		}else if ($key == "best-answers" && isset($user_profile_pages["best-answers"]["value"]) && $user_profile_pages["best-answers"]["value"] == "best-answers") {
			$selected = (wpqa_is_best_answers() || "best-answers" == wpqa_user_title()?true:"");
			$last_url = wpqa_get_profile_permalink($wpqa_user_id,"best_answers");
		}else if ($key == "polls" && isset($user_profile_pages["polls"]["value"]) && $user_profile_pages["polls"]["value"] == "polls") {
			$selected = (wpqa_is_user_polls() || "polls" == wpqa_user_title()?true:"");
			$last_url = wpqa_get_profile_permalink($wpqa_user_id,"polls");
		}else if ($key == "answers" && isset($user_profile_pages["answers"]["value"]) && $user_profile_pages["answers"]["value"] == "answers") {
			$selected = (wpqa_is_user_answers() || "answers" == wpqa_user_title()?true:"");
			$last_url = wpqa_get_profile_permalink($wpqa_user_id,"answers");
		}else if ($key == "asked" && isset($user_profile_pages["asked"]["value"]) && $user_profile_pages["asked"]["value"] == "asked" && $ask_question_to_users == "on") {
			$selected = (wpqa_is_user_asked() || "asked" == wpqa_user_title()?true:"");
			$last_url = wpqa_get_profile_permalink($wpqa_user_id,"asked");
		}else if ($key == "asked-questions" && isset($user_profile_pages["asked-questions"]["value"]) && $user_profile_pages["asked-questions"]["value"] == "asked-questions" && $ask_question_to_users == "on" && wpqa_is_user_owner()) {
			$selected = (wpqa_is_asked_questions() || "asked-questions" == wpqa_user_title()?true:"");
			$last_url = wpqa_get_profile_permalink($wpqa_user_id,"asked_questions");
		}else if ($key == "paid-questions" && isset($user_profile_pages["paid-questions"]["value"]) && $user_profile_pages["paid-questions"]["value"] == "paid-questions" && $pay_ask == "on") {
			$selected = (wpqa_is_paid_questions() || "paid-questions" == wpqa_user_title()?true:"");
			$last_url = wpqa_get_profile_permalink($wpqa_user_id,"paid_questions");
		}else if ($key == "favorites" && isset($user_profile_pages["favorites"]["value"]) && $user_profile_pages["favorites"]["value"] == "favorites" && ($show_point_favorite == "on" || wpqa_is_user_owner())) {
			$selected = (wpqa_is_user_favorites() || "favorites" == wpqa_user_title()?true:"");
			$last_url = wpqa_get_profile_permalink($wpqa_user_id,"favorites");
		}else if ($key == "followed" && isset($user_profile_pages["followed"]["value"]) && $user_profile_pages["followed"]["value"] == "followed" && ($show_point_favorite == "on" || wpqa_is_user_owner())) {
			$selected = (wpqa_is_user_followed() || "followed" == wpqa_user_title()?true:"");
			$last_url = wpqa_get_profile_permalink($wpqa_user_id,"followed");
		}else if ($key == "posts" && isset($user_profile_pages["posts"]["value"]) && $user_profile_pages["posts"]["value"] == "posts") {
			$selected = (wpqa_is_user_posts() || "posts" == wpqa_user_title()?true:"");
			$last_url = wpqa_get_profile_permalink($wpqa_user_id,"posts");
		}else if ($key == "comments" && isset($user_profile_pages["comments"]["value"]) && $user_profile_pages["comments"]["value"] == "comments") {
			$selected = (wpqa_is_user_comments() || "comments" == wpqa_user_title()?true:"");
			$last_url = wpqa_get_profile_permalink($wpqa_user_id,"comments");
		}else if ($key == "followers-questions" && isset($user_profile_pages["followers-questions"]["value"]) && $user_profile_pages["followers-questions"]["value"] == "followers-questions") {
			$selected = (wpqa_is_followers_questions() || "followers-questions" == wpqa_user_title()?true:"");
			$last_url = wpqa_get_profile_permalink($wpqa_user_id,"followers_questions");
		}else if ($key == "followers-answers" && isset($user_profile_pages["followers-answers"]["value"]) && $user_profile_pages["followers-answers"]["value"] == "followers-answers") {
			$selected = (wpqa_is_followers_answers() || "followers-answers" == wpqa_user_title()?true:"");
			$last_url = wpqa_get_profile_permalink($wpqa_user_id,"followers_answers");
		}else if ($key == "followers-posts" && isset($user_profile_pages["followers-posts"]["value"]) && $user_profile_pages["followers-posts"]["value"] == "followers-posts") {
			$selected = (wpqa_is_followers_posts() || "followers-posts" == wpqa_user_title()?true:"");
			$last_url = wpqa_get_profile_permalink($wpqa_user_id,"followers_posts");
		}else if ($key == "followers-comments" && isset($user_profile_pages["followers-comments"]["value"]) && $user_profile_pages["followers-comments"]["value"] == "followers-comments") {
			$selected = (wpqa_is_followers_comments() || "followers-comments" == wpqa_user_title()?true:"");
			$last_url = wpqa_get_profile_permalink($wpqa_user_id,"followers_comments");
		}else if ($key == "groups" && isset($user_profile_pages["groups"]["value"]) && $user_profile_pages["groups"]["value"] == "groups") {
			$selected = (wpqa_is_user_groups() || "groups" == wpqa_user_title()?true:"");
			$last_url = wpqa_get_profile_permalink($wpqa_user_id,"groups");
		}
		if (isset($last_url) && $last_url != "") {
			if ($list_child == "li") {?>
				<li<?php echo (isset($selected) && $selected == true?" class='active-tab'":"")?>>
					<a href="<?php echo esc_url($last_url)?>">
			<?php }else {?>
				<option<?php echo (isset($selected) && $selected == true?" selected='selected'":"")?> value="<?php echo esc_url($last_url)?>">
			<?php }
		}
		if ($key == "questions" && isset($user_profile_pages["questions"]["value"]) && $user_profile_pages["questions"]["value"] == "questions") {
			esc_html_e("Questions","wpqa");
		}else if ($key == "best-answers" && isset($user_profile_pages["best-answers"]["value"]) && $user_profile_pages["best-answers"]["value"] == "best-answers") {
			esc_html_e("Best Answers","wpqa");
		}else if ($key == "polls" && isset($user_profile_pages["polls"]["value"]) && $user_profile_pages["polls"]["value"] == "polls") {
			esc_html_e("Polls","wpqa");
		}else if ($key == "answers" && isset($user_profile_pages["answers"]["value"]) && $user_profile_pages["answers"]["value"] == "answers") {
			esc_html_e("Answers","wpqa");
		}else if ($key == "asked" && isset($user_profile_pages["asked"]["value"]) && $user_profile_pages["asked"]["value"] == "asked" && $ask_question_to_users == "on") {
			esc_html_e("Asked Questions","wpqa");;
		}else if ($key == "asked-questions" && isset($user_profile_pages["asked-questions"]["value"]) && $user_profile_pages["asked-questions"]["value"] == "asked-questions" && $ask_question_to_users == "on" && wpqa_is_user_owner()) {
			esc_html_e("Waiting Questions","wpqa");
			echo ($asked_questions > 0 && wpqa_is_user_owner()?"<span class='notifications-number asked-count".($asked_questions <= 99?"":" notifications-number-super")."'>".($asked_questions <= 99?$asked_questions:"99+")."</span>":"");
		}else if ($key == "paid-questions" && isset($user_profile_pages["paid-questions"]["value"]) && $user_profile_pages["paid-questions"]["value"] == "paid-questions" && $pay_ask == "on") {
			esc_html_e("Paid Questions","wpqa");
		}else if ($key == "favorites" && isset($user_profile_pages["favorites"]["value"]) && $user_profile_pages["favorites"]["value"] == "favorites" && ($show_point_favorite == "on" || wpqa_is_user_owner())) {
			esc_html_e("Favorite Questions","wpqa");
		}else if ($key == "followed" && isset($user_profile_pages["followed"]["value"]) && $user_profile_pages["followed"]["value"] == "followed" && ($show_point_favorite == "on" || wpqa_is_user_owner())) {
			esc_html_e("Followed Questions","wpqa");
		}else if ($key == "posts" && isset($user_profile_pages["posts"]["value"]) && $user_profile_pages["posts"]["value"] == "posts") {
			esc_html_e("Posts","wpqa");
		}else if ($key == "comments" && isset($user_profile_pages["comments"]["value"]) && $user_profile_pages["comments"]["value"] == "comments") {
			esc_html_e("Comments","wpqa");
		}else if ($key == "followers-questions" && isset($user_profile_pages["followers-questions"]["value"]) && $user_profile_pages["followers-questions"]["value"] == "followers-questions") {
			esc_html_e("Followers Questions","wpqa");
		}else if ($key == "followers-answers" && isset($user_profile_pages["followers-answers"]["value"]) && $user_profile_pages["followers-answers"]["value"] == "followers-answers") {
			esc_html_e("Followers Answers","wpqa");
		}else if ($key == "followers-posts" && isset($user_profile_pages["followers-posts"]["value"]) && $user_profile_pages["followers-posts"]["value"] == "followers-posts") {
			esc_html_e("Followers Posts","wpqa");
		}else if ($key == "followers-comments" && isset($user_profile_pages["followers-comments"]["value"]) && $user_profile_pages["followers-comments"]["value"] == "followers-comments") {
			esc_html_e("Followers Comments","wpqa");
		}else if ($key == "groups" && isset($user_profile_pages["groups"]["value"]) && $user_profile_pages["groups"]["value"] == "groups") {
			esc_html_e("Groups","wpqa");
		}
		if (isset($last_url) && $last_url != "") {
			if ($list_child == "li") {?>
					</a>
				</li>
			<?php }else {?>
				</option>
			<?php }
			$last_url = "";
		}
	}
}?>