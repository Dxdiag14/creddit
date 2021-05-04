<?php

/* @author    2codeThemes
*  @package   WPQA/functions
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* Paid subscriptions button */
if (!function_exists('wpqa_paid_subscriptions')) :
	function wpqa_paid_subscriptions($show = '') {
		$subscriptions_payment = wpqa_options("subscriptions_payment");
		if ($subscriptions_payment == "on") {
			$out = ($show == true?'<div class="pop-footer pop-footer-subscriptions">':'').'<a class="subscriptions-link" href="'.wpqa_subscriptions_permalink().'" target="_blank">'.esc_html__("Get the paid membership","wpqa").'<i class="icon-sound"></i></a>'.($show == true?'</div>':'');
			return $out;
		}
	}
endif;
/* Cookie for content */
add_action('parse_query','wpqa_cookie_content');
if (!function_exists('wpqa_cookie_content')) :
	function wpqa_cookie_content() {
		$activate_need_to_login = wpqa_options("activate_need_to_login");
		if ($activate_need_to_login == "on" && !is_user_logged_in()) {
			$need_login_pages = (int)wpqa_options("need_login_pages");
			$wpqa_locked_content = wpqa_options("uniqid_cookie").'wpqa_locked_content';
			if (is_page()) {
				global $post;
				if (isset($post->ID)) {
					$login_only = get_post_meta($post->ID,prefix_meta."login_only",true);
				}
			}
			if (!is_home() && !is_front_page() && ((is_page() && isset($login_only) && $login_only != "on") || is_single())) {
				$wpqa_locked_count = wpqa_options("uniqid_cookie").'wpqa_locked_count';
				$count = 1;
				if (isset($_COOKIE[$wpqa_locked_count]) && $_COOKIE[$wpqa_locked_count] > 0) {
					$count = (int)(($_COOKIE[$wpqa_locked_count])+1);
					setcookie($wpqa_locked_count,$count,time()+YEAR_IN_SECONDS,COOKIEPATH,COOKIE_DOMAIN);
				}else {
					setcookie($wpqa_locked_count,$count,time()+YEAR_IN_SECONDS,COOKIEPATH,COOKIE_DOMAIN);
				}
				if ($need_login_pages > 0 && isset($_COOKIE[$wpqa_locked_count]) && $_COOKIE[$wpqa_locked_count] >= $need_login_pages) {
					setcookie($wpqa_locked_content,'wpqa_locked_content',time()+YEAR_IN_SECONDS,COOKIEPATH,COOKIE_DOMAIN);
				}
			}
		}
	}
endif;
/* Paid subscriptions button */
add_action("wpqa_init","wpqa_check_get_subscriptions");
function wpqa_check_get_subscriptions() {
	$subscriptions_payment = wpqa_options("subscriptions_payment");
	if ($subscriptions_payment == "on") {
		/* Stripe webhooks */
		wpqa_stripe_data_webhooks();
		/* Check the subscription */
		wpqa_check_subscription();
	}
}
/* Check the subscription */
function wpqa_check_subscription() {
	if (is_user_logged_in()) {
		$user_id = get_current_user_id();
		$end_subscribe_time = get_user_meta($user_id,"end_subscribe_time",true);
		if ($end_subscribe_time != "" && $end_subscribe_time < strtotime(date("Y-m-d H:i:s"))) {
			if (!is_super_admin($user_id)) {
				$default_group = wpqa_options("default_group");
				$default_group = (isset($default_group) && $default_group != ""?$default_group:"subscriber");
				wp_update_user(array('ID' => $user_id,'role' => $default_group));
				do_action("wpqa_end_subscription_time",$user_id);
			}
			delete_user_meta($user_id,"start_subscribe_time");
			delete_user_meta($user_id,"end_subscribe_time");
			delete_user_meta($user_id,"package_subscribe");
			delete_user_meta($user_id,"reward_subscribe");
			delete_user_meta($user_id,"points_subscribe");
		}
	}
}
/* Check the subscription */
function wpqa_check_if_user_subscribe($user_id = 0) {
	$return = false;
	if (is_user_logged_in()) {
		if ($user_id == 0) {
			$user_id = get_current_user_id();
		}
		$subscriptions_payment = wpqa_options("subscriptions_payment");
		if ($subscriptions_payment == "on") {
			$end_subscribe_time = get_user_meta($user_id,"end_subscribe_time",true);
			$package_subscribe = get_user_meta($user_id,"package_subscribe",true);
			if (!is_super_admin($user_id) && $end_subscribe_time != "" && $end_subscribe_time < strtotime(date("Y-m-d H:i:s"))) {
				$return = false;
			}else if (!is_super_admin($user_id) && ($package_subscribe == "lifetime" || $end_subscribe_time != "" && $end_subscribe_time >= strtotime(date("Y-m-d H:i:s")))) {
				$return = true;
			}
		}
	}
	return $return;
}
/* Move user to subscription */
function wpqa_move_user_to_subscription($user_id,$package_subscribe,$customer = "",$subscr_id = "",$trial = "",$reward = "",$reward_type = "") {
	$subscriptions_group = wpqa_options("subscriptions_group");
	$subscriptions_group = ($subscriptions_group != ""?$subscriptions_group:"author");
	wp_update_user(array('ID' => $user_id,'role' => $subscriptions_group));
	if ($customer != "") {
		update_user_meta($user_id,"wpqa_paypal_customer",esc_html($customer));
	}
	update_user_meta($user_id,"package_subscribe",$package_subscribe);
	if ($subscr_id != "") {
		update_user_meta($user_id,'wpqa_subscr_id',esc_html($subscr_id));
	}
	delete_user_meta($user_id,'wpqa_canceled_subscription');
	if ($package_subscribe == "lifetime") {
		delete_user_meta($user_id,"start_subscribe_time");
		delete_user_meta($user_id,"end_subscribe_time");
	}else {
		if ($reward != "") {
			update_user_meta($user_id,"reward_subscribe",$package_subscribe);
			$interval = $package_subscribe;
			$interval_count = $reward;
			$reward_type_value = (int)get_user_meta($user_id,"wpqa_reward_".$reward_type."s_".date("m"),true);
			$reward_type_value++;
			update_user_meta($user_id,"wpqa_reward_".$reward_type."s_".date("m"),$reward_type_value);
		}else if ($trial != "") {
			update_user_meta($user_id,"trial_subscribe",$package_subscribe);
			update_user_meta($user_id,"trial_rang",$trial);
			$interval = $package_subscribe;
			$interval_count = $trial;
		}else {
			delete_user_meta($user_id,"trial_subscribe");
			delete_user_meta($user_id,"reward_subscribe");
			delete_user_meta($user_id,"points_subscribe");
			$interval = ($package_subscribe == "yearly" || $package_subscribe == "2years"?"year":"month")." +7 hour";
			$interval_count = ($package_subscribe == "monthly" || $package_subscribe == "yearly" || $package_subscribe == "2years"?($package_subscribe == "2years"?2:1):($package_subscribe == "3months"?3:6));
			$user_id_invite = get_user_meta($user_id,"wpqa_invitations",true);
			if ($user_id_invite != "" && $user_id_invite > 0) {
				$referral_membership = wpqa_options("referral_membership");
				wpqa_add_points($user_id_invite,$referral_membership,"+","referral_membership");
				wpqa_notifications_activities($user_id_invite,$user_id,"","","","referral_membership","notifications",$referral_membership." "._n("Point","Points",$referral_membership,"wpqa"));
			}
		}
		$start_subscribe_time = get_user_meta($user_id,"start_subscribe_time",true);
		$end_subscribe_time = get_user_meta($user_id,"end_subscribe_time",true);
		if ($start_subscribe_time != "" && $end_subscribe_time != "") {
			update_user_meta($user_id,"end_subscribe_time",strtotime(date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s",$end_subscribe_time)." +$interval_count $interval"))));
		}else {
			update_user_meta($user_id,"start_subscribe_time",strtotime(date("Y-m-d H:i:s")));
			update_user_meta($user_id,"end_subscribe_time",strtotime(date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." +$interval_count $interval"))));
		}
	}
}
/* Free, trial and reward  subscriptions */
function wpqa_free_subscriptions($user_id) {
	if ($user_id > 0 && ((isset($_POST["process"]) && $_POST["process"] == "subscribe" && isset($_POST["package_subscribe"]) && $_POST["package_subscribe"] != "") || (isset($_GET["reward"]) && $_GET["reward"] == "subscription") || (isset($_GET["trial"]) && $_GET["trial"] == "plan"))) {
		if (isset($_GET["reward"]) && $_GET["reward"] == "subscription") {
			$reward_subscription_plan = wpqa_options("reward_subscription_plan");
			$reward_subscription_rang = wpqa_options("reward_subscription_rang");
			$item_name = esc_html__("Reward membership","wpqa");
			$subscribe = $reward_subscription_plan;
			$reward_type = (isset($_GET["type"])?esc_html($_GET["type"]):"");
		}else if (isset($_GET["trial"]) && $_GET["trial"] == "plan") {
			$trial_subscription_plan = wpqa_options("trial_subscription_plan");
			$trial_subscription_rang = wpqa_options("trial_subscription_rang");
			$item_name = esc_html__("Trial membership","wpqa");
			$subscribe = $trial_subscription_plan;
		}else {
			$package_subscribe = esc_html($_POST["package_subscribe"]);
			$item_name = esc_html__("Paid membership","wpqa")." - ".$package_subscribe." ".esc_html__("membership","wpqa");
			$subscribe = $package_subscribe;
		}
		if (isset($_POST["process"]) && $_POST["process"] == "subscribe" && isset($_POST["package_subscribe"]) && $_POST["package_subscribe"] != "") {
			update_user_meta($user_id,"points_subscribe",$package_subscribe);
			$points_price = wpqa_options("subscribe_".$package_subscribe."_points");
			$points_user = (int)get_user_meta($user_id,"points",true);
			if ($points_price > $points_user) {
				wpqa_not_enough_points();
				wp_safe_redirect(esc_url(wpqa_subscriptions_permalink()));
				die();
			}
		}
		$currency_code = wpqa_get_currency($user_id);
		$array = array (
			'item_no' => 'subscribe',
			'item_name' => $item_name,
			'item_price' => 0,
			'item_currency' => $currency_code,
			'first_name' => get_the_author_meta("first_name",$user_id),
			'last_name' => get_the_author_meta("last_name",$user_id),
			'subscribe' => $subscribe,
			'trial' => (isset($trial_subscription_rang)?$trial_subscription_rang:""),
			'reward' => (isset($reward_subscription_rang)?$reward_subscription_rang:""),
			'reward_type' => (isset($reward_type)?$reward_type:""),
			'points' => (isset($_POST["process"]) && $_POST["process"] == "subscribe" && isset($_POST["package_subscribe"]) && $_POST["package_subscribe"] != "" && isset($_POST["points"])?esc_html($_POST["points"]):""),
			'custom' => 'wpqa_subscribe-'.$subscribe,
		);
		wpqa_payment_succeeded($user_id,$array);
		if (isset($_POST["process"]) && $_POST["process"] == "subscribe" && isset($_POST["package_subscribe"]) && $_POST["package_subscribe"] != "") {
			$points_price = wpqa_options("subscribe_".$package_subscribe."_points");
			wpqa_add_points($user_id,$points_price,"-","subscribe_points");
			$message = esc_html__("You have subscribed to paid membership by points.","wpqa");
		}else {
			$message = esc_html__("You have got a new free membership.","wpqa");
		}
		wpqa_session('<div class="alert-message success"><i class="icon-check"></i><p>'.$message.'</p></div>','wpqa_session');
		wp_safe_redirect(esc_url(wpqa_profile_url($user_id)));
		die();
	}
}
/* Get payment id by subscr_id */
function wpqa_get_payment_id($user_id,$subscr_id) {
	$args = array(
		'author'         => $user_id,
		'meta_key'       => 'payment_subscr_id',
		'meta_value'     => $subscr_id,
		'post_type'      => 'statement',
		'posts_per_page' => -1
	);
	$query = new WP_Query($args);
	if ($query->have_posts()) {
		$post_id = (isset($query->posts[0]->ID)?$query->posts[0]->ID:0);
		if ($post_id > 0) {
			return $post_id;
		}
	}
}
/* Cancel the subscription */
function wpqa_cancel_subscription($user_id,$subscr_id = "") {
	$subscr_id = ($subscr_id != ""?$subscr_id:get_user_meta($user_id,"wpqa_subscr_id",true));
	$post_id = wpqa_get_payment_id($user_id,$subscr_id);
	if ($post_id > 0) {
		$payment_method = get_post_meta($post_id,"payment_method",true);
		update_post_meta($post_id,"payment_canceled","canceled");
		if ($payment_method == "PayPal") {
			wpqa_change_subscription_status($subscr_id,'Cancel');
		}else if ($payment_method == "Stripe") {
			if (strpos($subscr_id,'sub_') !== false) {
				require_once plugin_dir_path(dirname(__FILE__)).'payments/stripe/init.php';
				try {
					\Stripe\Stripe::setApiKey(wpqa_options("secret_key"));
					$subscription = \Stripe\Subscription::retrieve($subscr_id);
					$subscription->delete();
				}catch ( \Stripe\Exception\CardException $e ) {
					error_log(print_r($e->getError()->message,true));
				}catch ( Exception $e ) {
					error_log(print_r($e->getMessage(),true));
				}
			}
		}
	}
	wpqa_delete_subscription("",$user_id);
}
/* Cancel the subscription ajax */
add_action('wp_ajax_wpqa_cancel_subscription_ajax','wpqa_cancel_subscription_ajax');
add_action('wp_ajax_nopriv_wpqa_cancel_subscription_ajax','wpqa_cancel_subscription_ajax');
function wpqa_cancel_subscription_ajax() {
	$user_id = get_current_user_id();
	$subscr_id = get_user_meta($user_id,"wpqa_subscr_id",true);
	wpqa_cancel_subscription($user_id,$subscr_id);
	die();
}
/* Delete the subscription */
function wpqa_delete_subscription($subscr_id,$user_id = 0) {
	if ($user_id > 0) {
		update_user_meta($user_id,"wpqa_canceled_subscription",true);
	}else if ($subscr_id != "") {
		$users = get_users(array('meta_key' => 'wpqa_subscr_id','meta_value' => $subscr_id,'number' => 1,'count_total' => false));
		if (isset($users[0]) && isset($users[0]->ID) && $users[0]->ID > 0) {
			update_user_meta($users[0]->ID,"wpqa_canceled_subscription",true);
		}
	}
}
/* Reward subscription */
add_action("wpqa_action_before_user_stats","wpqa_reward_subscription");
function wpqa_reward_subscription($user_id) {
	if (wpqa_is_home_profile()) {
		$package_subscribe = get_user_meta($user_id,"package_subscribe",true);
		$show_reward = apply_filters("wpqa_show_reward",true,$user_id);
		if ($package_subscribe != "lifetime" && $show_reward == true) {
			$reward_subscription = wpqa_options("reward_subscription");
			if ($reward_subscription == "on" && wpqa_is_user_owner()) {
				$date = array("year" => date("Y"),"month" => date("m"));
				$reward_subscription_plan = wpqa_options("reward_subscription_plan");
				$reward_subscription_rang = wpqa_options("reward_subscription_rang");
				if ($reward_subscription_plan == "week") {
					$plan_name = sprintf(_n("%s week","%s weeks",$reward_subscription_rang,"wpqa"),$reward_subscription_rang);
				}else {
					$plan_name = sprintf(_n("%s month","%s months",$reward_subscription_rang,"wpqa"),$reward_subscription_rang);
				}
				if ($plan_name != "") {
					$reward_questions_subscription = wpqa_options("reward_questions_subscription");
					$reward_answers_subscription = wpqa_options("reward_answers_subscription");
					$reward_best_answers_subscription = wpqa_options("reward_best_answers_subscription");
					$reward_posts_subscription = wpqa_options("reward_posts_subscription");
					
					if ($reward_questions_subscription > 0) {
						$add_questions = wpqa_count_posts_by_user($user_id,"question","publish",0,$date);
						if ($add_questions >= $reward_questions_subscription) {
							$reward_questions = (int)get_user_meta($user_id,"wpqa_reward_questions_".date("m"),true);
							if ($reward_questions == 0 || ($reward_questions > 0 && ($add_questions - ($reward_questions * $reward_questions_subscription)) >= $reward_questions_subscription)) {
								$reward_type = "question";
								$request_subscription = true;
							}
						}
						echo '<div class="alert-message success"><i class="icon-check"></i><p>'.sprintf(esc_html__("This month you have %s, you need to ask %s to join the %s plan","wpqa"),sprintf(_n("%s question","%s questions",$add_questions,"wpqa"),$add_questions),sprintf(_n("%s question","%s questions",$reward_questions_subscription,"wpqa"),$reward_questions_subscription),$plan_name).(isset($reward_questions) && $reward_questions > 0?sprintf(esc_html__(", you earned it %s"),sprintf(_n("%s time before","%s times before",$reward_questions,"wpqa"),$reward_questions)):"").'.</p></div>';
					}
					if ($reward_answers_subscription > 0) {
						$add_answers = count(get_comments(array("post_type" => "question","status" => "approve","user_id" => $user_id,"date_query" => array($date))));
						if (!isset($request_subscription) && $add_answers >= $reward_answers_subscription) {
							$reward_answers = (int)get_user_meta($user_id,"wpqa_reward_answers_".date("m"),true);
							if ($reward_answers == 0 || ($reward_answers > 0 && ($add_answers - ($reward_answers * $reward_answers_subscription)) >= $reward_answers_subscription)) {
								$reward_type = "answer";
								$request_subscription = true;
							}
						}
						echo '<div class="alert-message success"><i class="icon-check"></i><p>'.sprintf(esc_html__("This month you have %s, you need to add %s to join the %s plan","wpqa"),sprintf(_n("%s answer","%s answers",$add_answers,"wpqa"),$add_answers),sprintf(_n("%s answer","%s answers",$reward_answers_subscription,"wpqa"),$reward_answers_subscription),$plan_name).(isset($reward_answers) && $reward_answers > 0?sprintf(esc_html__(", you earned it %s"),sprintf(_n("%s time before","%s times before",$reward_answers,"wpqa"),$reward_answers)):"").'.</p></div>';
					}
					if ($reward_best_answers_subscription > 0) {
						$the_best_answers = count(get_comments(array("user_id" => $user_id,"status" => "approve","post_type" => "question","date_query" => array($date),"meta_query" => array("relation" => "AND",array("key" => "best_answer_comment","compare" => "=","value" => "best_answer_comment"),array("key" => "answer_question_user","compare" => "NOT EXISTS")))));
						if (!isset($request_subscription) && $the_best_answers >= $reward_best_answers_subscription) {
							$reward_best_answers = (int)get_user_meta($user_id,"wpqa_reward_best_answers_".date("m"),true);
							if ($reward_best_answers == 0 || ($reward_best_answers > 0 && ($the_best_answers - ($reward_best_answers * $reward_best_answers_subscription)) >= $reward_best_answers_subscription)) {
								$reward_type = "best_answer";
								$request_subscription = true;
							}
						}
						echo '<div class="alert-message success"><i class="icon-check"></i><p>'.sprintf(esc_html__("This month you have %s, you need to get %s to join the %s plan","wpqa"),sprintf(_n("%s best answer","%s best answers",$the_best_answers,"wpqa"),$the_best_answers),sprintf(_n("%s best answer","%s best answers",$reward_best_answers_subscription,"wpqa"),$reward_best_answers_subscription),$plan_name).(isset($reward_best_answers) && $reward_best_answers > 0?sprintf(esc_html__(", you earned it %s"),sprintf(_n("%s time before","%s times before",$reward_best_answers,"wpqa"),$reward_best_answers)):"").'.</p></div>';
					}
					if ($reward_posts_subscription > 0) {
						$add_posts = wpqa_count_posts_by_user($user_id,"post","publish",0,$date);
						if (!isset($request_subscription) && $add_posts >= $reward_posts_subscription) {
							$reward_posts = (int)get_user_meta($user_id,"wpqa_reward_posts_".date("m"),true);
							if ($reward_posts == 0 || ($reward_posts > 0 && ($add_posts - ($reward_posts * $reward_posts_subscription)) >= $reward_posts_subscription)) {
								$reward_type = "post";
								$request_subscription = true;
							}
						}
						echo '<div class="alert-message success"><i class="icon-check"></i><p>'.sprintf(esc_html__("This month you have %s, you need to add %s to join the %s plan","wpqa"),sprintf(_n("%s post","%s posts",$add_posts,"wpqa"),$add_posts),sprintf(_n("%s post","%s posts",$reward_posts_subscription,"wpqa"),$reward_posts_subscription),$plan_name).(isset($reward_posts) && $reward_posts > 0?sprintf(esc_html__(", you earned it %s"),sprintf(_n("%s time before","%s times before",$reward_posts,"wpqa"),$reward_posts)):"").'.</p></div>';
					}
					if (isset($request_subscription)) {
						if (is_super_admin($user_id)) {
							echo '<div class="alert-message warning"><i class="icon-flag"></i><p>'.esc_html__("You are admin, so you can't subscribe.","wpqa").'</p></div>';
						}else {
							echo '<p class="reward-subscription"><a class="button-default-3'.'" href="'.esc_url_raw(add_query_arg(array('reward' => 'subscription','type' => $reward_type),wpqa_subscriptions_permalink())).'">'.esc_html__("Request your paid membership","wpqa").'</a></p>';
						}
					}
				}
			}
		}
	}
}?>