<?php

/* @author    2codeThemes
*  @package   WPQA/templates
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
   exit; // Exit if accessed directly
}

do_action("wpqa_before_checkout");

echo "<div class='wpqa-templates wpqa-checkout-template'>
	<h2 class='post-title-3'><i class='icon-basket'></i>".esc_html__("Checkout","wpqa")."</h2>";
	$payment_available = wpqa_payment_available();
	if ($payment_available == true) {
		$logged = apply_filters("wpqa_checkout_logged_in",false);
		if (is_user_logged_in() || $logged == true) {
			$checkout_value = wpqa_checkout();
			if ($checkout_value != "") {
				$checkout_item  = wpqa_checkout_get_item();
				$points_number = $checkout_item;
				$item_id = $checkout_item;
				$user_id = get_current_user_id();
				$activate_currencies = wpqa_options("activate_currencies");
				$multi_currencies = wpqa_options("multi_currencies");
				$currency_code = wpqa_get_currency($user_id);
				$currency = ($activate_currencies == "on"?"_".strtolower($currency_code):"");
				$number_vars = 1;
				$payment_option = "";
				if ($checkout_value == "answer") {
					if ($checkout_item != "") {
						$get_post = get_post($checkout_item);
						if (isset($get_post->ID)) {
							$post_id = $get_post->ID;
							$pay_answer = wpqa_options("pay_answer");
							$custom_pay_answer = get_post_meta($post_id,"custom_pay_answer",true);
							if ($custom_pay_answer == "on") {
								$pay_answer = get_post_meta($post_id,"pay_answer",true);
							}
							$pay_answer = apply_filters('wpqa_pay_answer',$pay_answer);
							if ($pay_answer == "on") {
								$post_author = $get_post->post_author;
								$payment_option = "pay_answer_payment".$currency;
								if ($custom_pay_answer == "on") {
									$price = get_post_meta($post_id,$payment_option,true);
								}
								$price = apply_filters("wpqa_answer_price",(isset($price)?$price:0));
								$custom_message = esc_html__('Please make a payment to be able to add an answer with %s.','wpqa');
								$allow_to_continue = true;
							}
						}
					}
				}else if ($checkout_value == "sticky") {
					if ($checkout_item != "") {
						$get_post = get_post($checkout_item);
						if (isset($get_post->ID)) {
							$post_id = $get_post->ID;
							$post_author = $get_post->post_author;
							$payment_option = "pay_sticky_payment".$currency;
							$number_vars = 2;
							$anonymously_user = get_post_meta($post_id,"anonymously_user",true);
							$custom_message = esc_html__('Please make a payment to be able to make this question "sticky" with %s for %s days.','wpqa');
							$wpqa_allow_to_sticky = wpqa_allow_to_sticky($post_id,$user_id,$anonymously_user,$post_author);
							$days_sticky = (int)wpqa_options("days_sticky");
							$days_sticky = ($days_sticky > 0?$days_sticky:7);
							if ($wpqa_allow_to_sticky == true) {
								$allow_to_continue = true;
							}
						}
					}
				}else if ($checkout_value == "buy_points") {
					$active_points = wpqa_options("active_points");
					$buy_points_payment = wpqa_options("buy_points_payment");
					if ($active_points == "on" && $buy_points_payment == "on") {
						$buy_points = wpqa_options("buy_points");
						if (isset($buy_points) && is_array($buy_points)) {
							$buy_points = array_values($buy_points);
							$found_key = array_search($checkout_item,array_column($buy_points,'package_points'));
							if ($found_key !== "" && isset($buy_points[$found_key]) && is_array($buy_points[$found_key]) && !empty($buy_points[$found_key])) {
								$number_vars = 2;
								$package_name = $buy_points[$found_key]["package_name"];
								$price = $buy_points[$found_key]["package_price".$currency];
								$custom_message = esc_html__("Please make a payment to buy %s points with %s.","wpqa");
								$allow_to_continue = true;
							}
						}
					}
				}else if ($checkout_value == "subscribe") {
					$subscriptions_payment = wpqa_options("subscriptions_payment");
					if ($subscriptions_payment == "on") {
						$subscriptions_options = wpqa_options("subscriptions_options");
						$package_subscribe = get_user_meta($user_id,"package_subscribe",true);
						if (($package_subscribe == "" || ($package_subscribe != "" && $package_subscribe != "lifetime" && $checkout_item != $package_subscribe)) && isset($subscriptions_options[$checkout_item]) && $subscriptions_options[$checkout_item] == $checkout_item) {
							$number_vars = 2;
							$package_name = $checkout_item;
							$payment_option = "subscribe_".$checkout_item.$currency;
							$custom_message = esc_html__("Please make a payment to buy paid %s with %s.","wpqa");
							$allow_to_continue = true;
						}
					}
				}else if ($checkout_value == "ask_question") {
					$payment_style = wpqa_options("ask_payment_style");
					if ($payment_style != "packages" && ($checkout_item == "" || ($checkout_item != "" && is_numeric($checkout_item) && $checkout_item != $user_id))) {
						$allow_to_continue = true;
						if ($checkout_item != "" && is_numeric($checkout_item) && $checkout_item != $user_id) {
							$authordata = get_userdata($checkout_item);
							if (isset($authordata) && isset($authordata->ID)) {
								$number_vars = 2;
							}else {
								$allow_to_continue = false;
							}
						}
						$show_allow_question_message = true;
						$payment_option = "pay_ask_payment".$currency;
						if ($number_vars == 2) {
							$custom_message = esc_html__("Please make a payment to be able to ask %s a question with %s.","wpqa");
						}else {
							$custom_message = esc_html__("Please make a payment to be able to ask a question with %s.","wpqa");
						}
					}
				}else if ($checkout_value == "buy_questions") {
					$payment_style = wpqa_options("ask_payment_style");
					if ($payment_style == "packages") {
						$packages_payment = wpqa_options("ask_packages");
						if (isset($packages_payment) && is_array($packages_payment)) {
							$packages_payment = array_values($packages_payment);
							$found_key = array_search($checkout_item,array_column($packages_payment,'package_posts'));
							if ($found_key !== "" && isset($packages_payment[$found_key]) && is_array($packages_payment[$found_key]) && !empty($packages_payment[$found_key])) {
								$number_vars = 2;
								$package_name = $packages_payment[$found_key]["package_name"];
								$price = $packages_payment[$found_key]["package_price".$currency];
								$allow_to_continue = true;
								$checkout_related  = wpqa_checkout_get_related();
								$points_number = $checkout_related;
								if ($checkout_related != "" && is_numeric($checkout_related) && $checkout_related == $user_id) {
									$allow_to_continue = false;
								}else if ($checkout_related != "" && is_numeric($checkout_related) && $checkout_related != $user_id) {
									$authordata = get_userdata($checkout_related);
									if (isset($authordata) && isset($authordata->ID)) {
										$allow_to_continue = true;
									}else {
										$allow_to_continue = false;
									}
								}
								$show_allow_question_message = true;
								$custom_message = esc_html__("Please make a payment to buy %s with %s.","wpqa");
							}
						}
					}
				}else if ($checkout_value == "add_post") {
					$payment_style = wpqa_options("post_payment_style");
					if ($payment_style != "packages") {
						$allow_to_continue = true;
						$show_allow_post_message = true;
						$payment_option = "pay_post_payment".$currency;
						$custom_message = esc_html__("Please make a payment to be able to add a post with %s.","wpqa");
					}
				}else if ($checkout_value == "buy_posts") {
					$payment_style = wpqa_options("post_payment_style");
					if ($payment_style == "packages") {
						$packages_payment = wpqa_options("post_packages");
						if (isset($packages_payment) && is_array($packages_payment)) {
							$packages_payment = array_values($packages_payment);
							$found_key = array_search($checkout_item,array_column($packages_payment,'package_posts'));
							if ($found_key !== "" && isset($packages_payment[$found_key]) && is_array($packages_payment[$found_key]) && !empty($packages_payment[$found_key])) {
								$number_vars = 2;
								$package_name = $packages_payment[$found_key]["package_name"];
								$price = $packages_payment[$found_key]["package_price".$currency];
								$allow_to_continue = true;
								$checkout_related  = wpqa_checkout_get_related();
								$points_number = $checkout_related;
								if ($checkout_related != "" && is_numeric($checkout_related) && $checkout_related == $user_id) {
									$allow_to_continue = false;
								}else if ($checkout_related != "" && is_numeric($checkout_related) && $checkout_related != $user_id) {
									$authordata = get_userdata($checkout_related);
									if (isset($authordata) && isset($authordata->ID)) {
										$allow_to_continue = true;
									}else {
										$allow_to_continue = false;
									}
								}
								$show_allow_post_message = true;
								$custom_message = esc_html__("Please make a payment to buy %s with %s.","wpqa");
							}
						}
					}
				}
				if (isset($show_allow_question_message)) {
					$_allow_to_ask = (int)get_user_meta($user_id,$user_id."_allow_to_ask",true);
					if ($_allow_to_ask > 0) {
						echo '<div class="alert-message warning"><i class="icon-flag"></i><p>'.sprintf(esc_html__("You can already ask %s you can pay more if you want.","wpqa"),sprintf(_n("%s question","%s questions",$_allow_to_ask,"wpqa"),$_allow_to_ask)).'</p></div>';
					}
					$_allow_to_sticky = (int)get_user_meta($user_id,$user_id."_allow_to_sticky",true);
					if ($_allow_to_sticky > 0) {
						echo '<div class="alert-message warning"><i class="icon-flag"></i><p>'.sprintf(esc_html__('You can already make "sticky" %s you can pay more if you want.','wpqa'),sprintf(_n("%s question","%s questions",$_allow_to_sticky,"wpqa"),$_allow_to_sticky)).'</p></div>';
					}
				}
				if (isset($show_allow_post_message)) {
					$_allow_to_post = (int)get_user_meta($user_id,$user_id."_allow_to_post",true);
					if ($_allow_to_post > 0) {
						echo '<div class="alert-message warning"><i class="icon-flag"></i><p>'.sprintf(esc_html__("You can already add %s you can pay more if you want.","wpqa"),sprintf(_n("%s post","%s posts",$_allow_to_post,"wpqa"),$_allow_to_post)).'</p></div>';
					}
					$_allow_to_sticky_post = (int)get_user_meta($user_id,$user_id."_allow_to_sticky_post",true);
					if ($_allow_to_sticky_post > 0) {
						echo '<div class="alert-message warning"><i class="icon-flag"></i><p>'.sprintf(esc_html__('You can already make "sticky" %s you can pay more if you want.','wpqa'),sprintf(_n("%s post","%s posts",$_allow_to_sticky_post,"wpqa"),$_allow_to_sticky_post)).'</p></div>';
					}
				}
				$price = floatval(isset($price) && $price > 0?$price:(isset($payment_option)?wpqa_options($payment_option):0));
				$price = apply_filters("wpqa_checkout_price",$price,$checkout_value);
				$allow_to_continue = apply_filters("wpqa_allow_to_continue",(isset($allow_to_continue) && $allow_to_continue == true?$allow_to_continue:false),$checkout_value);
				$custom_message = apply_filters("wpqa_custom_message",(isset($custom_message)?$custom_message:""),$checkout_value);
				if (isset($allow_to_continue) && $allow_to_continue == true && $price > 0) {
					$payment_option = apply_filters("wpqa_filter_payment_option",$payment_option,$checkout_value);
					echo wpqa_get_payment_coupons($user_id,$item_id,(isset($days_sticky)?$days_sticky:0),$checkout_value,$points_number,$price,(isset($package_name)?$package_name:""),$custom_message,$number_vars,(isset($found_key)?$found_key:""));
				}else {
					echo '<div class="alert-message error"><i class="icon-cancel"></i><p>'.esc_html__("Sorry, this payment type is not available.","wpqa").'</p></div>';
				}
			}else {
				echo '<div class="alert-message warning"><i class="icon-flag"></i><p>'.esc_html__("Your cart is empty.","wpqa").'</p></div>';
			}
		}else {
			echo '<div class="alert-message"><i class="icon-lamp"></i><p>'.esc_html__("Please login to pay.","wpqa").'</p></div>'.do_shortcode("[wpqa_login]");
		}
	}else {
		echo '<div class="alert-message error"><i class="icon-cancel"></i><p>'.esc_html__("Sorry, this page is not available.","wpqa").'</p></div>';
	}
echo "</div>";

do_action("wpqa_after_checkout");?>