<?php

/* @author    2codeThemes
*  @package   WPQA/templates/profile
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
   exit; // Exit if accessed directly
}

$active_points        = wpqa_options("active_points");
$active_moderators    = wpqa_options("active_moderators");
$active_notifications = wpqa_options("active_notifications");
$active_activity_log  = wpqa_options("active_activity_log");
$transactions_page    = wpqa_options("transactions_page");
$active_message       = wpqa_options("active_message");
$active_referral      = wpqa_options("active_referral");
$user_profile_pages   = wpqa_options("user_profile_pages");
$ask_me               = wpqa_options("ask_me");
$rows_per_page        = get_option("posts_per_page");
$paged                = (get_query_var("paged") != ""?(int)get_query_var("paged"):(get_query_var("page") != ""?(int)get_query_var("page"):1));
$current              = max(1,$paged);
$show_custom_error    = true;
$last_one             = "";
$wpqa_user_title      = wpqa_user_title();
$is_super_admin       = is_super_admin($user_id);
$first_one            = (isset($first_one) && $first_one != ""?$first_one:"");
$first_one_filter     = apply_filters("wpqa_filter_profile_user_title",false);
$payment_available = wpqa_payment_available();

if ($wpqa_user_title == "" && isset($user_profile_pages[$first_one]["value"]) && $user_profile_pages[$first_one]["value"] == $first_one) {
	$last_one = $first_one;
}else if ($wpqa_user_title != "" && ($first_one_filter == true || isset($user_profile_pages[$wpqa_user_title]["value"]) && $user_profile_pages[$wpqa_user_title]["value"] == $wpqa_user_title)) {
	$last_one = $wpqa_user_title;
}else if ($first_one == "") {
	$no_pages = true;
}
$profile_last_one = apply_filters("wpqa_content_profile_last_one",false,$last_one);
if (($profile_last_one == true || $last_one == "questions" || $last_one == "posts" || ($last_one == "favorites" && ($show_point_favorite == "on" || wpqa_is_user_owner())) || ($last_one == "asked" && $ask_question_to_users == "on") || ($last_one == "asked-questions" && $ask_question_to_users == "on" && wpqa_is_user_owner()) || ($pay_ask == "on" && $last_one == "paid-questions") || ($last_one == "followed" && ($show_point_favorite == "on" || wpqa_is_user_owner())) || $last_one == "polls" || $last_one == "followers-questions" || $last_one == "followers-posts")) {
	if ($profile_last_one == true || $last_one == "questions" || ($last_one == "favorites" && ($show_point_favorite == "on" || wpqa_is_user_owner())) || ($last_one == "followed" && ($show_point_favorite == "on" || wpqa_is_user_owner())) || $last_one == "polls" || $last_one == "followers-questions" || ($last_one == "asked" && $ask_question_to_users == "on") || ($last_one == "asked-questions" && $ask_question_to_users == "on" && wpqa_is_user_owner()) || ($pay_ask == "on" && $last_one == "paid-questions")) {
		$its_question = "question";
	}
	$is_questions_sticky = false;
	include locate_template("theme-parts/loop.php");
}else if (wpqa_is_user_followers() || wpqa_is_user_following()) {
	include wpqa_get_template("follow.php","profile/");
}else if (wpqa_is_user_groups()) {
	include locate_template("theme-parts/loop-groups.php");
}else if (wpqa_is_user_referrals() && $active_referral == "on" && wpqa_is_user_owner()) {
	include wpqa_get_template("referrals.php","profile/");
}else if (wpqa_is_user_messages() && $active_message == "on" && wpqa_is_user_owner()) {
	include wpqa_get_template("messages.php","profile/");
}else if ($transactions_page == "on" && $payment_available == true && wpqa_is_user_transactions_profile() && wpqa_is_user_owner()) {
	$the_currency = get_option("the_currency");
	if (isset($the_currency) && is_array($the_currency)) {
		$count = $k = 0;
		foreach ($the_currency as $key => $currency) {
			if (isset($currency) && $currency != "") {
				$_all_my_payment = get_user_meta($wpqa_user_id,$wpqa_user_id."_all_my_payment_".$currency,true);
				if ($_all_my_payment > 0) {
					$_all_my_payment_array[$currency] = $_all_my_payment;
					$count++;
				}
			}
		}
		if (isset($_all_my_payment_array) && is_array($_all_my_payment_array)) {
			foreach ($_all_my_payment_array as $currency => $money) {
				$k++;
				if ($money > 0) {
					if ($k == 1) {
						echo '<div class="alert-message alert-message-money"><i class="icon-basket"></i>';
					}else {
						echo '<div><i class="icon-basket"></i>';
					}
					echo '<span>'.esc_html__("You spent:","wpqa").'</span> '.(isset($money) && $money != ""?$money:0)." ".$currency;
					if ($k != 1) {
						echo '</div>';
					}
					if ($k == $count) {
						echo '</div>';
					}
				}
			}
		}
	}
	$args = array('author' => $wpqa_user_id,'post_type' => 'statement','posts_per_page' => $rows_per_page,'paged' => $paged);
	$statements_query = new WP_Query( $args );
	if ($statements_query->have_posts()) {
		echo '<div id="section-'.$wpqa_user_title.'" class="user-notifications user-profile-area section-page-div"><div><ul>';
			while ( $statements_query->have_posts() ) { $statements_query->the_post();
				$statement_post = $statements_query->post;?>
				<li>
					<?php 
					echo "<div>";
						$payment_method = get_post_meta($statement_post->ID,"payment_method",true);
						$item_price = get_post_meta($statement_post->ID,"payment_item_price",true);
						$item_currency = get_post_meta($statement_post->ID,"payment_item_currency",true);
						$coupon = get_post_meta($statement_post->ID,"payment_coupon",true);
						$points = get_post_meta($statement_post->ID,"payment_points",true);
						$item_transaction = get_post_meta($statement_post->ID,"payment_item_transaction",true);
						$statement_type = get_post_meta($statement_post->ID,"statement_type",true);
						$payment_refund = get_post_meta($statement_post->ID,"payment_refund",true);
						$payment_canceled = get_post_meta($statement_post->ID,"payment_canceled",true);
						$original_transaction = get_post_meta($statement_post->ID,"payment_original_transaction",true);
						echo '<i class="icon-export"></i>
						<div>
							<span class="point-span price-span'.($payment_refund == "refund" || $statement_type == "refund"?" refund-span":"").'">'.($item_price > 0?($payment_refund == "refund" || $statement_type == "refund"?"-":"").esc_html($item_price)." ".$item_currency:($points != ""?$points." ".esc_html__("points","wpqa"):"")).(isset($coupon) && $coupon != ""?" - (".$coupon.")":"").'</span>';
							the_title();
						echo '</div>
					</div>';
					if ($payment_method != "" || $item_transaction != "") {
						echo '<div>
							<i class="icon-basket"></i>
								<div>
									<span class="point-span transaction-span">'.($payment_method != ""?esc_html($payment_method):"").'</span>'.($item_transaction != ""?esc_html($item_transaction):"").($original_transaction != ""?" - ".esc_html($original_transaction):"").'
								</div>
						</div>';
					}
					$time_format = wpqa_options("time_format");
					$time_format = ($time_format?$time_format:get_option("time_format"));
					$date_format = wpqa_options("date_format");
					$date_format = ($date_format?$date_format:get_option("date_format"));
					$human_time_diff = human_time_diff(get_the_time('U'), current_time('timestamp'));
					echo '<div>
						<i class="icon-clock"></i>
						<div>
							'.sprintf(esc_html__('%1$s at %2$s','wpqa'),mysql2date($date_format,date($date_format,get_the_time('U'))),mysql2date($time_format,date($time_format,get_the_time('U')))).'
						</div>';?>
					</div>
				</li>
			<?php }
		echo '</div></ul></div>';
		if ($statements_query->max_num_pages > 1) :
			$current = max(1,$paged);
			$pagination_args = array(
				'total'     => $statements_query->max_num_pages,
				'base'      => esc_url_raw(add_query_arg('page','%#%')),
				'current'   => $current,
				'show_all'  => false,
				'prev_text' => '<i class="icon-left-open"></i>',
				'next_text' => '<i class="icon-right-open"></i>',
			);
			$paginate_links = paginate_links($pagination_args);?>
			<div class="main-pagination"><div class='comment-pagination pagination'><?php echo ($paginate_links != ""?$paginate_links:"")?></div></div>
		<?php endif;
		wp_reset_postdata();
	}else {
		echo '<div class="alert-message warning"><i class="icon-flag"></i><p>'.esc_html__("There are no transactions yet.","wpqa").'</p></div>';
	}
}else if ((wpqa_is_user_notifications() && $active_notifications == "on" && wpqa_is_user_owner()) || (wpqa_is_user_activities() && $active_activity_log == "on" && wpqa_is_user_owner())) {
	if (wpqa_is_user_notifications()) {
		$whats_type = "notification";
		$post_type = "notification";
		$message_found = esc_html__("There are no notifications yet.","wpqa");
	}else {
		$whats_type = "activitie";
		$post_type = "activity";
		$message_found = esc_html__("There are no activities yet.","wpqa");
	}
	$args = array('author' => $wpqa_user_id,'post_type' => $post_type,'posts_per_page' => $rows_per_page,'paged' => $paged);
	$types_query = new WP_Query( $args );
	if ($types_query->have_posts()) {
		if (wpqa_is_user_notifications()) {
			update_user_meta($wpqa_user_id,$wpqa_user_id.'_new_notification',0);
		}
		echo '<div id="section-'.$wpqa_user_title.'" class="user-notifications user-profile-area section-page-div"><div><ul>';
			while ( $types_query->have_posts() ) { $types_query->the_post();
				$type_post = $types_query->post;
				if (wpqa_is_user_notifications()) {
					update_post_meta($type_post,"notification_new",0);
				}?>
				<li>
					<?php $type_result = wpqa_notification_activity_result($type_post,$post_type);
					if (wpqa_is_user_notifications()) {
						echo wpqa_show_notifications($type_result);
					}else {
						echo wpqa_show_activities($type_result);
					}
					if (!isset($not_show_date) && isset($type_result['time'])) {?>
						<span class='notifications-date'><?php echo ($type_result['time'])?></span>
					<?php }?>
					</div>
				</li>
			<?php }
		echo '</div></ul></div>';
		if ($types_query->max_num_pages > 1) :
			$current = max(1,$paged);
			$pagination_args = array(
				'total'     => $types_query->max_num_pages,
				'base'      => esc_url_raw(add_query_arg('page','%#%')),
				'current'   => $current,
				'show_all'  => false,
				'prev_text' => '<i class="icon-left-open"></i>',
				'next_text' => '<i class="icon-right-open"></i>',
			);
			$paginate_links = paginate_links($pagination_args);?>
			<div class="main-pagination"><div class='comment-pagination pagination'><?php echo ($paginate_links != ""?$paginate_links:"")?></div></div>
		<?php endif;
		wp_reset_postdata();
	}else {
		echo '<div class="alert-message warning"><i class="icon-flag"></i><p>'.$message_found.'</p></div>';
	}
}else if ((wpqa_is_pending_questions() || wpqa_is_pending_posts()) && ($is_super_admin || $active_moderators == "on") && wpqa_is_user_owner()) {
	$user_moderator = get_user_meta($user_id,prefix_author."user_moderator",true);
	if ($is_super_admin || $user_moderator == "on") {
		$moderator_categories = get_user_meta($user_id,prefix_author."moderator_categories",true);
		if ($is_super_admin || is_array($moderator_categories) && !empty($moderator_categories)) {
			if (wpqa_is_pending_questions()) {
				$its_question = "question";
			}
			include locate_template("theme-parts/loop.php");
		}else if (!$is_super_admin && (!is_array($moderator_categories) || (is_array($moderator_categories) && empty($moderator_categories)))) {
			echo '<div class="alert-message warning"><i class="icon-flag"></i><p>'.esc_html__("Sorry, You are not moderator yet.","wpqa").'</p></div>';
		}
	}else {
		echo '<div class="alert-message warning"><i class="icon-flag"></i><p>'.esc_html__("Sorry, this is a private page.","wpqa").'</p></div>';
	}
}else if (wpqa_is_user_points() && $active_points == "on" && ($show_point_favorite == "on" || wpqa_is_user_owner())) {
	$time_format = wpqa_options("time_format");
	$time_format = ($time_format?$time_format:get_option("time_format"));
	$date_format = wpqa_options("date_format");
	$date_format = ($date_format?$date_format:get_option("date_format"));
	if (wpqa_is_user_points() && $active_points == "on") {
		$whats_type = "point";
		if ($ask_me == "on") {
			$user_login = get_userdata($wpqa_user_id);
			$old_points = get_user_meta($wpqa_user_id,$user_login->user_login."_points",true);
			if (isset($old_points) && $old_points != "") {
				update_user_meta($wpqa_user_id,$wpqa_user_id."_".$whats_type."s",$old_points);
				delete_user_meta($wpqa_user_id,$user_login->user_login."_points");
			}
		}
		wpqa_get_user_stats($wpqa_user_id,wpqa_options('user_stats'),$active_points,$show_point_favorite);
	}
	
	$_whats_types = get_user_meta($wpqa_user_id,$wpqa_user_id."_".$whats_type."s",true);
	
	if (isset($_whats_types) && $_whats_types > 0) {
		echo '<div id="section-'.$wpqa_user_title.'" class="user-notifications user-profile-area section-page-div"><div><ul>';
			$pagination_args = array(
				'base'      => esc_url_raw(add_query_arg('page','%#%')),
				'total'     => ceil($_whats_types/$rows_per_page),
				'current'   => $current,
				'show_all'  => false,
				'prev_text' => '<i class="icon-left-open"></i>',
				'next_text' => '<i class="icon-right-open"></i>',
			);
			
			$start = ($current - 1) * $rows_per_page;
			$end = $start + $rows_per_page;
			$end = ($_whats_types < $end) ? $_whats_types : $end;
			for ($i = $_whats_types-$start; $i > $_whats_types-$end; $i--) {
				if (wpqa_is_user_points() && $active_points == "on" && $ask_me == "on") {
					$points_one = get_user_meta($wpqa_user_id,$user_login->user_login."_points_".$i,true);
					if (isset($points_one) && !empty($points_one)) {
						update_user_meta($wpqa_user_id,$wpqa_user_id."_points_".$i,$points_one);
						delete_user_meta($wpqa_user_id,$user_login->user_login."_points_".$i);
					}
				}
				$whats_type_result = get_user_meta($wpqa_user_id,$wpqa_user_id."_".$whats_type."s_".$i,true);?>
				<li>
					<?php 
					if (wpqa_is_user_points() && $active_points == "on") {
						include wpqa_get_template("points.php","profile/");
					}
					if (!isset($not_show_date)) {?>
						<span class='notifications-date'><?php echo (isset($whats_type_result['time'])?sprintf(esc_html__('%1$s at %2$s','wpqa'),mysql2date($date_format,date($date_format,$whats_type_result["time"])),mysql2date($time_format,date($time_format,$whats_type_result["time"]))):$whats_type_result[0]."&nbsp;&nbsp;-&nbsp;&nbsp;".$whats_type_result[1])?></span>
					<?php }?>
					</div>
				</li>
			<?php }
		echo '</div></ul></div>';
		if (isset($_whats_types) && $_whats_types > 0 && $pagination_args["total"] > 1) {?>
			<div class="main-pagination"><div class='pagination'><?php echo (paginate_links($pagination_args) != ""?paginate_links($pagination_args):"")?></div></div>
		<?php }
	}else {
		if (wpqa_is_user_points()) {
			$message_found = esc_html__("There are no points yet.","wpqa");
		}
		echo '<div class="alert-message warning"><i class="icon-flag"></i><p>'.$message_found.'</p></div>';
	}
}else if ($last_one == "answers" || $last_one == "best-answers" || ($last_one == "comments") || $last_one == "followers-answers" || $last_one == "followers-comments") {
	if ($last_one == "answers" || $last_one == "comments" || $last_one == "followers-answers" || $last_one == "followers-comments") {
		if ($last_one == "followers-answers" || $last_one == "followers-comments") {
			$following_me = get_user_meta($wpqa_user_id,"following_me",true);
		}
		$comments_all = get_comments(array(($last_one == "followers-answers" || $last_one == "followers-comments"?"author__in":"user_id") => ($last_one == "followers-answers" || $last_one == "followers-comments"?$following_me:$wpqa_user_id),"status" => "approve",'post_type' => ($last_one == "answers" || $last_one == "followers-answers"?"question":"post"),"meta_query" => array(array("key" => "answer_question_user","compare" => "NOT EXISTS"))));
		if (($last_one == "followers-answers" || $last_one == "followers-comments") && empty($following_me)) {
			$comments_all = array();
		}
	}else {
		$comments_all = get_comments(array('user_id' => $wpqa_user_id,"status" => "approve",'post_type' => 'question',"meta_query" => array('relation' => 'AND',array("key" => "best_answer_comment","compare" => "=","value" => "best_answer_comment"),array("key" => "answer_question_user","compare" => "NOT EXISTS"))));
	}
	if (!empty($comments_all)) {
		$k_ad = -1;
		$pagination_args = array(
			'base'      => esc_url_raw(add_query_arg('page','%#%')),
			'total'     => ceil(sizeof($comments_all)/$rows_per_page),
			'current'   => $current,
			'show_all'  => false,
			'prev_text' => '<i class="icon-left-open"></i>',
			'next_text' => '<i class="icon-right-open"></i>',
		);
		
		$start = ($current - 1) * $rows_per_page;
		$end = $start + $rows_per_page;
		?>
		<div<?php echo ($last_one == "answers" || $last_one == "best-answers" || $last_one == "comments" || $last_one == "followers-answers" || $last_one == "followers-comments"?" id='section-".$wpqa_user_title."'":"")?> class="page-content commentslist section-page-div">
			<ol class="commentlist clearfix">
				<?php $end = (sizeof($comments_all) < $end) ? sizeof($comments_all) : $end;
				for ($k = $start;$k < $end ;++$k ) {$k_ad++;
					if ($last_one == "answers" || $last_one == "comments" || $last_one == "followers-answers" || $last_one == "followers-comments") {
						$comment_item = $comments_all[$k];
					}else {
						$comment_item = get_comment($comments_all[$k]);
					}
					if ($last_one == "answers" || $last_one == "followers-answers") {
						$yes_private = wpqa_private($comment_item->comment_post_ID,get_post($comment_item->comment_post_ID)->post_author,get_current_user_id());
					}else {
						$yes_private = 1;
					}
					if ($yes_private == 1) {
							$comment_id = esc_attr($comment_item->comment_ID);
							wpqa_comment($comment_item,"","",($last_one == "answers" || $last_one == "best-answers" || $last_one == "followers-answers"?"answer":"comment"),wpqa_is_user_owner(),$k_ad,($last_one == "best-answers"?"not show":""),array("comment_with_title" => true));?>
						</li>
					<?php }else {?>
						<li class="comment">
							<div class="comment-body clearfix">
								<?php echo '<div class="alert-message warning"><i class="icon-flag"></i><p>'.esc_html__("Sorry it is a private answer.","wpqa").'</p></div>';?>
							</div>
						</li>
					<?php }
				}?>
			</ol>
		</div>
	<?php }else {
		echo '<div class="alert-message warning"><i class="icon-flag"></i><p>'.($last_one == "best-answers"?esc_html__("There are no best answers yet.","wpqa"):($last_one == "answers" || $last_one == "followers-answers"?esc_html__("There are no answers yet","wpqa"):esc_html__("There are no comments yet","wpqa"))).'</p></div>';
	}
	if ($comments_all && $pagination_args["total"] > 1) {?>
		<div class="main-pagination"><div class='pagination'><?php echo (paginate_links($pagination_args) != ""?paginate_links($pagination_args):"")?></div></div>
	<?php }
}else {
	if (!is_author()) {
		if (wpqa_is_user_notifications() || ($transactions_page == "on" && wpqa_is_user_transactions_profile()) || wpqa_is_user_referrals() || wpqa_is_user_messages() || wpqa_is_user_activities() || (((wpqa_is_user_points() && $active_points == "on") || $last_one == "followed" || $last_one == "favorites") && $show_point_favorite != "on" && !wpqa_is_user_owner())) {
			echo '<div class="alert-message warning"><i class="icon-flag"></i><p>'.esc_html__("Sorry, this is a private page.","wpqa").'</p></div>';
		}else if ($first_one_filter == false && (!isset($no_pages) || ($wpqa_user_title != "" && isset($no_pages)))) {
			echo '<div class="alert-message error"><i class="icon-cancel"></i><p>'.esc_html__("Sorry, this page is not found.","wpqa").'</p></div>';
		}
	}
	do_action("wpqa_action_after_profile_content");
}?>