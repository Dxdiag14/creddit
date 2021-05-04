<?php

/* @author    2codeThemes
*  @package   WPQA/payments
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
   exit; // Exit if accessed directly
}

/* Payments */
if (!function_exists('wpqa_get_payment_coupons')) :
	function wpqa_get_payment_coupons($user_id,$item_id = 0,$days_sticky = 0,$kind_of_payment = "ask_question",$points_number = 0,$price = 0,$package_name = "",$message = "",$number_vars = 1,$found_key = "") {
		$found_key = ($found_key !== ""?$found_key+1:"");
		$output = '';
		$paypal_sandbox = wpqa_options('paypal_sandbox');
		$payment_style = wpqa_options("payment_style");
		$active_coupons = wpqa_options("active_coupons");
		$coupons = wpqa_options("coupons");
		$free_coupons = wpqa_options("free_coupons");
		$activate_currencies = wpqa_options("activate_currencies");
		$multi_currencies = wpqa_options("multi_currencies");
		$currency_code = wpqa_get_currency($user_id);
		$currency = ($activate_currencies == "on"?"_".strtolower($currency_code):"");
		if ($kind_of_payment == "answer") {
			$payment_by_points = "payment_type_answer";
			$points_price = "answer_payment_points";
			$custom_pay_answer = get_post_meta($item_id,"custom_pay_answer",true);
			if ($custom_pay_answer == "on") {
				$points_price = get_post_meta($item_id,$points_price,true);
			}
			$points_price = apply_filters("wpqa_answer_points_price",(isset($points_price)?$points_price:""));
			$payment_description = esc_attr__("Pay to add answer","wpqa");
			$item_number = "pay_answer";
			$return_url = get_the_permalink($item_id);
			$item_process = $kind_of_payment;
			$custom_buttom = $item_id;
		}else if ($kind_of_payment == "sticky") {
			$payment_by_points = "payment_type_sticky";
			$points_price = "sticky_payment_points";
			$payment_description = esc_attr__("Pay to make this Question sticky","wpqa");
			$item_number = "pay_sticky";
			$return_url = get_the_permalink($item_id);
			$item_process = $kind_of_payment;
			$custom_buttom = $item_id;
		}else if ($kind_of_payment == "buy_points") {
			$payment_by_points = $points_price = "";
			$payment_description = esc_attr__("Buy points","wpqa").($package_name != ""?" - ".$package_name:"");
			$item_number = $kind_of_payment;
			$return_url = wpqa_buy_points_permalink();
			$item_process = "points";
			$custom_buttom = $points_number;
		}else if ($kind_of_payment == "subscribe") {
			$array = array(
				"free"     => array("key" => "free","name" => esc_html__("Free membership","wpqa")),
				"monthly"  => array("key" => "monthly","name" => esc_html__("Monthly membership","wpqa")),
				"3months"  => array("key" => "3months","name" => esc_html__("Three months membership","wpqa")),
				"6months"  => array("key" => "6months","name" => esc_html__("Six Months membership","wpqa")),
				"yearly"   => array("key" => "yearly","name" => esc_html__("Yearly membership","wpqa")),
				"2years"   => array("key" => "2years","name" => esc_html__("Two Years membership","wpqa")),
				"lifetime" => array("key" => "lifetime","name" => esc_html__("Lifetime membership","wpqa")),
			);
			$payment_by_points = "payment_type_subscriptions";
			$plan_name = (isset($array[$package_name]["key"]) && $array[$package_name]["key"] != ""?$array[$package_name]["key"]:"");
			$points_price = "subscribe_".$item_id."_points";
			$payment_description = esc_attr__("Paid membership","wpqa").(isset($array[$package_name]["name"]) && $array[$package_name]["name"] != ""?" - ".$array[$package_name]["name"]:"");
			$item_number = $item_process = $kind_of_payment;
			$return_url = wpqa_subscriptions_permalink();
			$custom_buttom = $plan_name;
		}else if ($kind_of_payment == "ask_question") {
			$payment_by_points = "payment_type_ask";
			$points_price = "ask_payment_points";
			if ($item_id != "") {
				$authordata = get_userdata($item_id);
				$author_display_name = (isset($authordata->display_name)?$authordata->display_name:"");
			}
			$payment_description = esc_attr__("Ask a new question","wpqa").($item_id != "" && isset($authordata) && isset($authordata->ID)?" ".esc_attr__("for","wpqa")." ".$author_display_name:"");
			$item_number = $kind_of_payment;
			$return_url = ($item_id != "" && isset($authordata) && isset($authordata->ID)?wpqa_add_question_permalink("user",$item_id):wpqa_add_question_permalink());
			$item_process = "ask";
			$custom_buttom = ($item_id != "" && isset($authordata) && isset($authordata->ID)?$item_id:"");
		}else if ($kind_of_payment == "buy_questions") {
			$packages_payment = wpqa_options("ask_packages");
			$payment_by_points = "payment_type_ask";
			$points_price = "";
			$payment_description = esc_attr__("Buy questions","wpqa").($package_name != ""?" - ".$package_name:"");
			if ($points_number != "") {
				$authordata = get_userdata($points_number);
				$author_display_name = (isset($authordata->display_name)?$authordata->display_name:"");
			}
			$item_number = $kind_of_payment;
			$return_url = ($points_number != "" && isset($authordata) && isset($authordata->ID)?wpqa_add_question_permalink("user",$points_number):wpqa_add_question_permalink());
			$item_process = "buy_questions";
			$custom_buttom = $item_id.($points_number != "" && isset($authordata) && isset($authordata->ID)?"-".$points_number:"");
			$sticky = (isset($packages_payment[$found_key]["sticky"])?$packages_payment[$found_key]["sticky"]:0);
			if ($sticky == "on") {
				$days_sticky = (int)(isset($packages_payment[$found_key]["days_sticky"])?$packages_payment[$found_key]["days_sticky"]:"");
				if ($days_sticky > 0) {
					$sticky_message = " ".esc_html__("and","wpqa")." ".sprintf(_n("sticky for %s day","sticky for %s days",$days_sticky,"wpqa"),$days_sticky);
				}
			}
		}else if ($kind_of_payment == "add_post") {
			$payment_by_points = "payment_type_post";
			$points_price = "post_payment_points";
			$payment_description = esc_attr__("Add a new post","wpqa");
			$item_number = $kind_of_payment;
			$return_url = wpqa_add_post_permalink();
			$item_process = "post";
			$custom_buttom = "";
		}else if ($kind_of_payment == "buy_posts") {
			$packages_payment = wpqa_options("post_packages");
			$payment_by_points = "payment_type_post";
			$points_price = "";
			$payment_description = esc_attr__("Buy posts","wpqa").($package_name != ""?" - ".$package_name:"");
			if ($points_number != "") {
				$authordata = get_userdata($points_number);
				$author_display_name = (isset($authordata->display_name)?$authordata->display_name:"");
			}
			$item_number = $kind_of_payment;
			$item_number_button = $kind_of_payment;
			$return_url = wpqa_add_post_permalink();
			$item_process = "buy_posts";
			$custom_buttom = $item_id;
			$sticky = (isset($packages_payment[$found_key]["sticky"])?$packages_payment[$found_key]["sticky"]:0);
			if ($sticky == "on") {
				$days_sticky = (int)(isset($packages_payment[$found_key]["days_sticky"])?$packages_payment[$found_key]["days_sticky"]:"");
				if ($days_sticky > 0) {
					$sticky_message = " ".esc_html__("and","wpqa")." ".sprintf(_n("sticky for %s day","sticky for %s days",$days_sticky,"wpqa"),$days_sticky);
				}
			}
		}

		$payment_by_points = apply_filters("wpqa_filter_payment_by_points",(isset($payment_by_points)?$payment_by_points:""),$kind_of_payment);
		$points_price = apply_filters("wpqa_filter_points_price",(isset($points_price)?$points_price:""),$kind_of_payment);
		$payment_description = apply_filters("wpqa_filter_payment_description",(isset($payment_description)?$payment_description:""),$kind_of_payment);
		$item_number = apply_filters("wpqa_filter_item_number",(isset($item_number)?$item_number:""),$kind_of_payment);
		$return_url = apply_filters("wpqa_filter_return_url",(isset($return_url)?$return_url:""),$kind_of_payment);
		$item_process = apply_filters("wpqa_filter_item_process",(isset($item_process)?$item_process:""),$kind_of_payment);
		$custom_buttom = apply_filters("wpqa_filter_custom_buttom",(isset($custom_buttom)?$custom_buttom:""),$kind_of_payment);

		$output .= apply_filters("wpqa_filter_before_payment_form",false,$kind_of_payment);

		$payment_by_points = wpqa_options($payment_by_points);
		$payment_methods = wpqa_options("payment_methodes");
		if ($payment_by_points == "payments_points") {
			$payment_methods["points"] = array("sort" => esc_html__("points","wpqa"),"value" => "points");
		}
		foreach ($payment_methods as $key => $value) {
			if ($value["value"] !== '0') {
				$payment_methods_activated = true;
			}
		}
		if ($payment_by_points == "points" || $payment_by_points == "payments_points") {
			$points_price = (int)(is_numeric($points_price)?$points_price:wpqa_options($points_price));
			if ($kind_of_payment == "sticky") {
				$points_message = '<div class="alert-message success"><i class="icon-check"></i><p>'.sprintf(esc_html__('Please pay by points to "sticky question" with %s for %s days.','wpqa'),$points_price." ".esc_html__("points","wpqa"),$days_sticky).'</p></div>';
			}else if ($kind_of_payment == "answer") {
				$points_message = '<div class="alert-message success"><i class="icon-check"></i><p>'.sprintf(esc_html__("Please pay by points to allow to be able to add a answer with %s.","wpqa"),$points_price." ".esc_html__("points","wpqa")).'</p></div>';
			}else if ($kind_of_payment == "subscribe") {
				$points_message = '<div class="alert-message success"><i class="icon-check"></i><p>'.sprintf(esc_html__("Please pay by points to subscribe to %s with %s.","wpqa"),(isset($array[$package_name]["name"]) && $array[$package_name]["name"] != ""?$array[$package_name]["name"]:""),$points_price." ".esc_html__("points","wpqa")).'</p></div>';
			}else if ($kind_of_payment == "ask_question") {
				if (isset($author_display_name) && $author_display_name != "") {
					$points_message = '<div class="alert-message success"><i class="icon-check"></i><p>'.sprintf(esc_html__("Please pay by points to ask %s a question with %s.","wpqa"),$author_display_name,$points_price." ".esc_html__("points","wpqa")).'</p></div>';
				}else {
					$points_message = '<div class="alert-message success"><i class="icon-check"></i><p>'.sprintf(esc_html__("Please pay by points to ask a question with %s.","wpqa"),$points_price." ".esc_html__("points","wpqa")).'</p></div>';
				}
			}else if ($kind_of_payment == "buy_questions") {
				$points_price = (int)(isset($packages_payment[$found_key]) && isset($packages_payment[$found_key]["package_points"])?$packages_payment[$found_key]["package_points"]:0);
				if (isset($author_display_name) && $author_display_name != "") {
					$points_message = '<div class="alert-message success"><i class="icon-check"></i><p>'.sprintf(esc_html__("Please pay by points to ask %s a question %s with %s.","wpqa"),$author_display_name,sprintf(_n("%s question","%s questions",$item_id,"wpqa"),$item_id).(isset($sticky_message)?$sticky_message:""),$points_price." ".esc_html__("points","wpqa")).'</p></div>';
				}else {
					$points_message = '<div class="alert-message success"><i class="icon-check"></i><p>'.sprintf(esc_html__("Please pay by points to ask %s with %s.","wpqa"),sprintf(_n("%s question","%s questions",$item_id,"wpqa"),$item_id).(isset($sticky_message)?$sticky_message:""),' '.$points_price.' '.esc_html__("points","wpqa")).'</p></div>';
				}
			}else if ($kind_of_payment == "add_post") {
				$points_message = '<div class="alert-message success"><i class="icon-check"></i><p>'.sprintf(esc_html__("Please pay by points to add a post with %s.","wpqa"),$points_price." ".esc_html__("points","wpqa")).'</p></div>';
			}else if ($kind_of_payment == "buy_posts") {
				$points_price = (int)(isset($packages_payment[$found_key]) && isset($packages_payment[$found_key]["package_points"])?$packages_payment[$found_key]["package_points"]:0);
				$points_message = '<div class="alert-message success"><i class="icon-check"></i><p>'.sprintf(esc_html__("Please pay by points to add %s with %s.","wpqa"),sprintf(_n("%s post","%s posts",$item_id,"wpqa"),$item_id).(isset($sticky_message)?$sticky_message:""),' '.$points_price.' '.esc_html__("points","wpqa")).'</p></div>';
			}
		}
		if ($payment_by_points == "points" || !isset($payment_methods_activated)) {
			$output .= $points_message;
			if ($user_id > 0) {
				$points_user = (int)get_user_meta($user_id,"points",true);
				if ($points_price <= $points_user) {
					$output .= wpqa_process_points_form($return_url,$item_process,$points_price,($kind_of_payment != "buy_questions" && $kind_of_payment != "buy_posts"?($item_id > 0?$item_id:0):""),"",($kind_of_payment != "buy_questions" && $kind_of_payment != "buy_posts"?$item_id:""),($item_id != ""?$item_id:""));
				}else {
					$buy_points_payment = wpqa_options("buy_points_payment");
					$output .= '<div class="alert-message error"><i class="icon-cancel"></i><p>'.esc_html__("Sorry, you don't have enough points","wpqa").($buy_points_payment == "on"?', <a href="'.wpqa_buy_points_permalink().'">'.esc_html__("You can buy the points from here.","wpqa").'</a>':'.').'</p></div>';
				}
			}
		}else {
			$pay_payment = $last_payment = $price;
			if ($active_coupons == "on") {
				if ((isset($_POST["add_coupon"]) && $_POST["add_coupon"] == "submit") || isset($_GET["coupon_name"])) {
					$coupon_name = (isset($_REQUEST["coupon_name"])?esc_attr($_REQUEST["coupon_name"]):"");
					$coupons_not_exist = "no";
					
					if ($coupon_name == "") {
						$output .= '<div class="alert-message error"><i class="icon-cancel"></i><p>'.esc_html__("Please enter your coupon.","wpqa").'</p></div>';
					}else if (isset($coupons) && is_array($coupons)) {
						foreach ($coupons as $coupons_k => $coupons_v) {
							if (is_array($coupons_v) && in_array($coupon_name,$coupons_v)) {
								$coupons_not_exist = "yes";
								
								if (isset($coupons_v["coupon_date"]) && $coupons_v["coupon_date"] != "") {
									$coupons_v["coupon_date"] = !is_numeric($coupons_v["coupon_date"]) ? strtotime($coupons_v["coupon_date"]):$coupons_v["coupon_date"];
								}
								
								if (isset($coupons_v["coupon_date"]) && $coupons_v["coupon_date"] != "" && current_time( 'timestamp' ) > $coupons_v["coupon_date"]) {
									$output .= '<div class="alert-message error"><i class="icon-cancel"></i><p>'.esc_html__("This coupon has expired.","wpqa").'</p></div>';
								}else if (isset($coupons_v["coupon_type"]) && $coupons_v["coupon_type"] == "percent" && (int)$coupons_v["coupon_amount"] > 100) {
									$output .= '<div class="alert-message error"><i class="icon-cancel"></i><p>'.esc_html__("This coupon is not valid.","wpqa").'</p></div>';
								}else if (isset($coupons_v["coupon_type"]) && $coupons_v["coupon_type"] == "discount" && (int)$coupons_v["coupon_amount"] > $pay_payment) {
									$output .= '<div class="alert-message error"><i class="icon-cancel"></i><p>'.esc_html__("This coupon is not valid.","wpqa").'</p></div>';
								}else {
									if (isset($coupons_v["coupon_type"]) && $coupons_v["coupon_type"] == "percent") {
										$the_discount = ($pay_payment*$coupons_v["coupon_amount"])/100;
										$last_payment = $pay_payment-$the_discount;
									}else if (isset($coupons_v["coupon_type"]) && $coupons_v["coupon_type"] == "discount") {
										$last_payment = $pay_payment-$coupons_v["coupon_amount"];
									}
									if (isset($_POST["coupon_name"])) {
										$output .= '<div class="alert-message success"><i class="icon-check"></i><p>'.sprintf(esc_html__('Coupon "%s" applied successfully.','wpqa'),$coupon_name).'</p></div>';
									}
									
									update_user_meta($user_id,$user_id."_coupon",esc_attr($coupons_v["coupon_name"]));
									update_user_meta($user_id,$user_id."_coupon_value",($last_payment <= 0?"free":$last_payment));
								}
							}
						}
						
						if ($coupons_not_exist == "no") {
							$output .= '<div class="alert-message error"><i class="icon-cancel"></i><p>'.esc_html__("Coupon does not exist!.","wpqa").'</p></div>';
						}else if ($coupons_not_exist == "no") {
							$output .= '<div class="alert-message error"><i class="icon-cancel"></i><p>'.sprintf(esc_html__('Coupon "%s" does not exist!.','wpqa'),$coupon_name).'</p></div>';
						}
					}
				}else {
					delete_user_meta($user_id,$user_id."_coupon");
					delete_user_meta($user_id,$user_id."_coupon_value");
				}
			}

			$rand = rand(1,100000);
			if ($activate_currencies == "on") {
				if (isset($_POST["multi_currencies"]) && $_POST["multi_currencies"] != "") {
					update_user_meta($user_id,"wpqa_currency_code",esc_html($_POST["multi_currencies"]));
				}
				if (is_array($multi_currencies) && !empty($multi_currencies)) {
					foreach ($multi_currencies as $key_currency => $value_currency) {
						if ($value_currency == "0") {
							unset($multi_currencies[$key_currency]);
						}
					}
				}
				if (is_array($multi_currencies) && !empty($multi_currencies)) {
					if (count($multi_currencies) > 1) {
						$select_currencies = '<form class="wpqa_form multi_currencies_form" method="post">
							<p>
								<label for="multi_currencies'.$rand.'">'.esc_html__("Choose your currency","wpqa").'</label>
								<span class="styled-select">
									<select id="multi_currencies'.$rand.'" name="multi_currencies" onchange="this.form.submit()">';
										foreach ($multi_currencies as $key_currencies => $value_currencies) {
											$select_currencies .= '<option '.selected($currency_code,$key_currencies,false).' value="'.$key_currencies.'">'.$value_currencies.'</option>';
										}
									$select_currencies .= '</select>
								</span>
								<i class="icon-basket"></i>
							</p>';
							if (isset($select_currencies) && $select_currencies != "") {
								if ($kind_of_payment == "buy_points") {
									$select_currencies .= '<input type="hidden" name="package_points" value="'.esc_attr($points_number).'">';
								}else if ($kind_of_payment == "subscribe") {
									$select_currencies .= '<input type="hidden" name="package_subscribe" value="'.esc_attr($plan_name).'">';
								}
							}
						$select_currencies .= '</form>';
					}else {
						$currency_code = reset($multi_currencies);
					}
				}
			}
			if (isset($select_currencies)) {
				$select_currencies = apply_filters("wpqa_select_currencies",$select_currencies,$multi_currencies,$rand,$currency_code,$kind_of_payment);
			}
			$currency_code = (isset($_POST["multi_currencies"]) && $_POST["multi_currencies"] != ""?esc_html($_POST["multi_currencies"]):$currency_code);

			$payment_with_currency = ' '.number_format($last_payment).' '.$currency_code;
			if ($number_vars == 2) {
				if ($kind_of_payment == "sticky") {
					$last_message = sprintf($message,$payment_with_currency,$days_sticky);
				}else if ($kind_of_payment == "buy_points") {
					$last_message = sprintf($message,$points_number,$payment_with_currency);
				}else if ($kind_of_payment == "subscribe") {
					$last_message = sprintf($message,(isset($array[$package_name]["name"]) && $array[$package_name]["name"] != ""?$array[$package_name]["name"]:""),$payment_with_currency);
				}else if ($kind_of_payment == "ask_question") {
					$last_message = sprintf($message,(isset($author_display_name) && $author_display_name != ""?$author_display_name:""),$payment_with_currency);
				}else if ($kind_of_payment == "buy_questions") {
					if (isset($author_display_name) && $author_display_name != "") {
						$last_message = sprintf($message,sprintf(_n("%s question","%s questions",$item_id,"wpqa"),$item_id).(isset($sticky_message)?$sticky_message:"")." ".esc_html__("to ask","wpqa")." ".$author_display_name,$payment_with_currency);
					}else {
						$last_message = sprintf($message,sprintf(_n("%s question","%s questions",$item_id,"wpqa"),$item_id).(isset($sticky_message)?$sticky_message:""),$payment_with_currency);
					}
				}else if ($kind_of_payment == "add_post") {
					$last_message = sprintf($message,$payment_with_currency);
				}else if ($kind_of_payment == "buy_posts") {
					$last_message = sprintf($message,sprintf(_n("%s post","%s posts",$item_id,"wpqa"),$item_id).(isset($sticky_message)?$sticky_message:""),$payment_with_currency);
				}
			}else {
				$last_message = sprintf($message,$payment_with_currency);
			}
			$output .= '<div class="alert-message success"><i class="icon-check"></i><p>'.$last_message.'</p></div>';
			
			if (isset($coupons) && is_array($coupons) && $free_coupons == "on" && $active_coupons == "on") {
				foreach ($coupons as $coupons_k => $coupons_v) {
					$pay_payments = $last_payments = $price;
					if (isset($coupons_v["coupon_type"]) && $coupons_v["coupon_type"] == "percent") {
						$the_discount = ($pay_payments*$coupons_v["coupon_amount"])/100;
						$last_payments = $pay_payments-$the_discount;
					}else if (isset($coupons_v["coupon_type"]) && $coupons_v["coupon_type"] == "discount") {
						$last_payments = $pay_payments-$coupons_v["coupon_amount"];
					}
					
					if ($last_payments <= 0) {
						if (isset($coupons_v["coupon_date"]) && $coupons_v["coupon_date"] != "") {
							$coupons_v["coupon_date"] = !is_numeric($coupons_v["coupon_date"]) ? strtotime($coupons_v["coupon_date"]):$coupons_v["coupon_date"];
						}
						if (isset($coupons_v["coupon_type"]) && ($coupons_v["coupon_type"] == "percent" && (int)$coupons_v["coupon_amount"] >= 100  && (isset($coupons_v["coupon_date"]) && ($coupons_v["coupon_date"] == "" || ($coupons_v["coupon_date"] != "" && strtotime(date("M j, Y") <= $coupons_v["coupon_date"]))))) || ($coupons_v["coupon_type"] == "discount" && (int)$coupons_v["coupon_amount"] >= $pay_payments && (isset($coupons_v["coupon_date"]) && ($coupons_v["coupon_date"] == "" || ($coupons_v["coupon_date"] != "" && strtotime(date("M j, Y") <= $coupons_v["coupon_date"])))))) {
							$output .= '<div class="alert-message"><i class="icon-lamp"></i><p>'.sprintf(esc_html__("Take it free? Add this coupon %s.","wpqa"),' "'.$coupons_v["coupon_name"].'"').'</p></div>';
						}
					}
				}
			}

			$wpqa_show_coupons = apply_filters("wpqa_show_coupons",true,$kind_of_payment);
			if ($wpqa_show_coupons == true && $active_coupons == "on") {
				$output .= '<div class="coupon_area">
					<form method="post">
						<input type="text" name="coupon_name" class="coupon_name" value="" placeholder="'.esc_attr__("Coupon code","wpqa").'">
						<input type="submit" class="button-default" value="'.esc_attr__("Apply Coupon","wpqa").'">';
						if ($kind_of_payment == "buy_points") {
							$output .= '<input type="hidden" name="package_points" value="'.esc_attr($points_number).'">';
						}else if ($kind_of_payment == "subscribe") {
							$output .= '<input type="hidden" name="package_subscribe" value="'.esc_attr($plan_name).'">';
						}
						$output .= '<input type="hidden" name="add_coupon" value="submit">
					</form>
				</div>';
			}
			
			$output .= '<div class="clearfix"></div>';
			if ($last_payment > 0 && isset($payment_methods_activated)) {
				if ($kind_of_payment == "subscribe") {
					$interval_count = ($plan_name == "monthly" || $plan_name == "yearly" || $plan_name == "2years"?($plan_name == "2years"?2:1):($plan_name == "3months"?3:6));
					if ($last_payment != $pay_payment && $plan_name != "lifetime") {
						if ($plan_name == "monthly") {
							$time_period = esc_html__("month","wpqa");
						}else if ($plan_name == "3months") {
							$time_period = esc_html__("3 months","wpqa");
						}else if ($plan_name == "6months") {
							$time_period = esc_html__("6 months","wpqa");
						}else if ($plan_name == "yearly") {
							$time_period = esc_html__("year","wpqa");
						}else if ($plan_name == "2years") {
							$time_period = esc_html__("2 years","wpqa");
						}
						$output .= '<div class="alert-message warning"><i class="icon-flag"></i><p>'.sprintf(esc_html__("The price for the first %s %s, Then %s for each %s.","wpqa"),$time_period,$last_payment.' '.$currency_code,$pay_payment.' '.$currency_code,$time_period).'</p></div>';
					}
				}
				$inputs = (isset($coupon_name) && $coupon_name != ''?'<input type="hidden" name="coupon" value="'.$coupon_name.'">':'').'
				<input type="hidden" name="custom" value="wpqa_'.$item_number.'-'.$custom_buttom.'">
				<input type="hidden" name="item_name" value="'.$payment_description.'">
				<input type="hidden" name="item_number" value="'.$item_number.'">'.
				apply_filters('wpqa_filter_payment_button','<button type="submit" class="button-default pay-button">'.esc_html__("Pay","wpqa").' '.$last_payment.' '.$currency_code.'</button>',$kind_of_payment,$last_payment,$currency_code);
				
				$paypal_logo = wpqa_image_url_id(wpqa_options("paypal_logo"));
				$paypal_inputs = '<input type="hidden" name="key" value="'.md5(date("Y-m-d:").rand()).'">
				<input type="hidden" name="rm" value="2">
				<input type="hidden" name="currency_code" value="'.$currency_code.'">
				<input type="hidden" name="business" value="'.wpqa_options('paypal_email'.($paypal_sandbox == "on"?"_sandbox":"")).'">
				<input type="hidden" name="return" value="'.esc_url(home_url('/')).'?action=success">
				<input type="hidden" name="cancel_return" value="'.esc_url(home_url('/')).'?action=cancel">
				<input type="hidden" name="notify_url" value="'.esc_url(home_url('/')).'?action=ipn">
				<input type="hidden" name="cpp_header_image" value="'.$paypal_logo.'">
				<input type="hidden" name="image_url" value="'.$paypal_logo.'">
				<input type="hidden" name="cpp_logo_image" value="'.$paypal_logo.'">';

				$output .= '<div class="payment-methods">';
					if (is_array($payment_methods) && !empty($payment_methods)) {
						$output .= '<h3 class="post-title-3"><i class="icon-credit-card"></i>'.esc_html__("Select Payment Method","wpqa").'</h3>
						<div class="payment-wrap payment-wrap-'.$item_number.'-'.$custom_buttom.($payment_style != "style_2"?" payment-wrap-2":"").'">
							<div class="payment-tabs">
								<ul>';
									$k = 0;
									foreach ($payment_methods as $key => $value) {
										if ($value["value"] !== '0') {
											$k++;
											if ($key == "paypal") {
												$icon = '<i class="icon-paypal"></i>';
												$image = '<img alt="'.$value["sort"].'" width="200" height="54" src="'.plugin_dir_url(dirname(__FILE__)).'images/paypal.svg">';
											}else if ($key == "stripe") {
												$icon = '<i class="icon-credit-card"></i>';
												$image = '<img width="100" height="54" alt="'.$value["sort"].'" src="'.plugin_dir_url(dirname(__FILE__)).'images/mastercard.svg"><img width="100" height="54" alt="'.$value["sort"].'" src="'.plugin_dir_url(dirname(__FILE__)).'images/visa.svg">';
											}else if ($key == "bank") {
												$icon = '<i class="icon-globe"></i>';
												$image = '<img width="32" height="32" alt="'.$value["sort"].'" src="'.plugin_dir_url(dirname(__FILE__)).'images/bank.png">';
											}else if ($key == "custom") {
												$icon = '<i class="icon-credit-card"></i>';
												$image = '<img width="100" height="54" alt="'.($key == "custom"?wpqa_options("custom_payment_tab"):$value["sort"]).'" src="'.plugin_dir_url(dirname(__FILE__)).'images/mastercard.svg">';
											}else if ($key == "points") {
												$icon = '<i class="icon-bucket"></i>';
												$image = '<img width="100" height="54" alt="'.$value["sort"].'" src="'.plugin_dir_url(dirname(__FILE__)).'images/points.png">';
											}else {
												$icon = '<i class="icon-paypal"></i>';
												$image = '<img alt="'.$value["sort"].'" src="'.plugin_dir_url(dirname(__FILE__)).'images/mastercard.svg"><img alt="'.$value["sort"].'" src="'.plugin_dir_url(dirname(__FILE__)).'images/visa.svg">';
											}
											if ($payment_style != "style_2") {
												$class = ($k == 1?"payment-style-activate":"");
											}else {
												$class = ($k == 1?"button-default-3":"button-default-2");
											}
											$output .= '<li class="payment-link-'.$key.'"><a href="payment-'.$key.'" class="'.$class." payment-".$key.'">'.($payment_style != "style_2" && isset($image)?$image:$icon.($key == "custom"?wpqa_options("custom_payment_tab"):$value["sort"])).'</a></li>';
										}
									}
								$output .= '</ul>
							<div class="clearfix"></div></div>';
							$k = 0;
							foreach ($payment_methods as $key => $value) {
								if ($value["value"] !== '0') {
									$k++;
								}
								if ($payment_methods[$key]["value"] == $key) {
									$output .= '<div class="payment-method payment-'.$key.($k == 1?"":" wpqa_hide").'" data-hide="payment-'.$key.'">
									'.apply_filters("wpqa_payment_form_note",false,$key).'
									'.(isset($select_currencies) && $select_currencies != ""?$select_currencies:'');
								}
								if ($key == "points" && $payment_methods["points"]["value"] == "points") {
									if (isset($points_message) && $points_message != "") {
										$output .= $points_message;
									}
									$output .= wpqa_process_points_form($return_url,$item_process,$points_price,($kind_of_payment != "buy_questions" && $kind_of_payment != "buy_posts"?($item_id > 0?$item_id:0):""),"",($kind_of_payment != "buy_questions" && $kind_of_payment != "buy_posts"?$item_id:""),($item_id != ""?$item_id:""));
								}else if ($key == "paypal" && $payment_methods["paypal"]["value"] == "paypal") {
									if ($paypal_sandbox == "on") {
										$paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
									}else {
										$paypal_url = 'https://www.paypal.com/cgi-bin/webscr';
									}
									if ($kind_of_payment == "subscribe") {
										$interval = ($plan_name == "yearly" || $plan_name == "2years"?"Y":"M");
									}

									$output .= apply_filters("wpqa_payment_note_form",false,$kind_of_payment).'<form method="post" action="'.$paypal_url.'">';
										if ($kind_of_payment == "subscribe" && $plan_name != "lifetime") {
											$output .= '<input type="hidden" name="cmd" value="_xclick-subscriptions">
											<input type="hidden" name="bn" value="PP-SubscriptionsBF:btn_subscribeCC_LG.gif:NonHostedGuest">
											<input type="hidden" name="no_note" value="1">
											<input type="hidden" name="src" value="1">
											<input type="hidden" name="sra" value="1">';
											if ($last_payment != $pay_payment) {
												$output .= '<input type="hidden" name="a1" value="'.$last_payment.'">
												<input type="hidden" name="p1" value="1">
												<input type="hidden" name="t1" value="'.$interval.'">';
											}
											$output .= '<input type="hidden" name="a3" value="'.($last_payment != $pay_payment?$pay_payment:$last_payment).'">
											<input type="hidden" name="p3" value="'.$interval_count.'">
											<input type="hidden" name="t3" value="'.$interval.'">
											'.$inputs;
										}else {
											$output .= '<input type="hidden" name="no_shipping" value="1">
	    									<input type="hidden" name="cmd" value="_xclick">
											<input type="hidden" name="quantity" value="1">
											<input type="hidden" name="amount" value="'.apply_filters("wpqa_payment_amount_button",$last_payment,$kind_of_payment).'">
											'.$inputs;
										}
										$output .= $paypal_inputs.'
									</form>';
								}else if ($key == "stripe" && $payment_methods["stripe"]["value"] == "stripe") {
									if ($kind_of_payment == "subscribe") {
										$interval = ($plan_name == "yearly" || $plan_name == "2years"?"year":"month");
									}
									$stripe_address = wpqa_options("stripe_address");
									$stripe_inputs = '';
									if ($stripe_address == "on") {
										$get_countries = apply_filters('wpqa_get_countries',false);
										$line1 = get_the_author_meta("line1",$user_id);
										$postal_code = get_the_author_meta("postal_code",$user_id);
										$country = get_the_author_meta("country",$user_id);
										$city = get_the_author_meta("city",$user_id);
										$state = get_the_author_meta("state",$user_id);
										$stripe_inputs = '
										<div class="row">
											<div class="col col8">
												<p class="line1_field">
													<label for="line1_'.$rand.'">'.esc_html__("Address","wpqa").'</label>
													<input type="text" value="'.esc_attr($line1).'" id="line1_'.$rand.'" name="line1">
													<i class="icon-direction"></i>
												</p>
											</div>
											<div class="col col4">
												<p class="postal_code_field">
													<label for="postal_code_'.$rand.'">'.esc_html__("ZIP","wpqa").'</label>
													<input type="text" value="'.esc_attr($postal_code).'" id="postal_code_'.$rand.'" name="postal_code">
													<i class="icon-box"></i>
												</p>
											</div>
										</div>
										<div class="row">
											<div class="col col4">
												<p class="country_field">
													<label for="country_'.$rand.'">'.esc_html__("Country","wpqa").'</label>
													<span class="styled-select">
														<select name="country" id="country_'.$rand.'">
															<option value="">'.esc_html__( 'Select a country&hellip;', 'wpqa' ).'</option>';
																foreach( $get_countries as $key_country => $value_country ) {
																	$stripe_inputs .= '<option value="' . esc_attr( $key_country ) . '"' . selected(esc_attr($country), esc_attr( $key_country ), false ) . '>' . esc_attr( $value_country ) . '</option>';
																}
														$stripe_inputs .= '</select>
													</span>
													<i class="icon-location"></i>
												</p>
											</div>
											<div class="col col4">
												<p class="city_field">
													<label for="city_'.$rand.'">'.esc_html__("City","wpqa").'</label>
													<input type="text" value="'.esc_attr($city).'" id="city_'.$rand.'" name="city">
													<i class="icon-address"></i>
												</p>
											</div>
											<div class="col col4">
												<p class="state_field">
													<label for="state_'.$rand.'">'.esc_html__("State","wpqa").'</label>
													<input type="text" value="'.esc_attr($state).'" id="state_'.$rand.'" name="state">
													<i class="icon-direction"></i>
												</p>
											</div>
										</div>';
									}
									$output .= '<form action="" method="post" class="wpqa-stripe-payment-form wpqa_form" data-id="payment-stripe'.$rand.'">
										<div class="wpqa_error"></div>
										'.$stripe_inputs.'
										<div class="wpqa-stripe-payment" id="payment-stripe'.$rand.'" data-id="payment-stripe'.$rand.'"></div>
										<div class="form-submit">
											<span class="load_span"><span class="loader_2"></span></span>
											'.$inputs.'
											<input type="hidden" value="'.get_the_author_meta("display_name",$user_id).'" name="name" class="name" required="" autofocus="">
											<input type="hidden" value="'.get_the_author_meta("user_email",$user_id).'" name="email" class="email" required="">
											<input type="hidden" name="payment" value="'.($key == "stripe"?$pay_payment:$last_payment).'">
											<input type="hidden" name="action" value="wpqa_stripe_payment">
											<input type="hidden" name="wpqa_stripe_nonce" value="'.wp_create_nonce("wpqa_stripe_nonce").'">
										</div>
									</form>';
								}else if ($key == "bank" && $payment_methods["bank"]["value"] == "bank") {
									$output .= do_shortcode(nl2br(stripslashes(wpqa_options("bank_transfer_details"))));
								}else if ($key == "custom" && $payment_methods["custom"]["value"] == "custom") {
									$output .= do_shortcode(nl2br(stripslashes(wpqa_options("custom_payment_details"))));
								}
								if ($payment_methods[$key]["value"] == $key) {
									$output .= '</div>';
								}
							}
						$output .= '</div>
						<div class="payment_methods"><img width="546" height="50" src="'.plugin_dir_url(dirname(__FILE__)).'images/payment_methods.png" alt="payment_methods"></div>';
					}
					$custom_text_payment = wpqa_options("custom_text_payment");
					if ($custom_text_payment != "") {
						$output .= do_shortcode(wpqa_kses_stip(nl2br(stripslashes($custom_text_payment))));
					}
				$output .= '</div>';
			}else {
				$wpqa_find_coupons = wpqa_find_coupons($coupons,(isset($_REQUEST["coupon_name"])?esc_html($_REQUEST["coupon_name"]):""));
				$output .= wpqa_process_points_form($return_url,$item_process,0,0,($wpqa_find_coupons != "" && $active_coupons == "on" && isset($_REQUEST["coupon_name"])?$_REQUEST["coupon_name"]:""),(isset($_POST["package_points"])?$_POST["package_points"]:""),(isset($_POST["package_subscribe"])?$_POST["package_subscribe"]:""));
			}
		}
		return apply_filters("wpqa_payment_form",$output,$user_id,$item_id,$days_sticky,$kind_of_payment,$points_number,$price,$package_name,$message,$number_vars,$found_key);
	}
endif;
/* Get points form */
function wpqa_process_points_form($return_url,$item_process,$points_price = 0,$post_id = 0,$coupon_name = "",$package_points = "",$package_subscribe = "") {
	$output = '<div class="process_area">
		<form method="post" action="'.$return_url.'">
			<input type="submit" class="button" value="'.esc_attr__("Process","wpqa").'">
			<input type="hidden" name="process" value="'.$item_process.'">';
			if ($item_process == "points" && $package_points != "") {
				$output .= '<input type="hidden" name="package_points" value="'.esc_attr($package_points).'">';
			}else if ($item_process == "subscribe" && $package_subscribe != "") {
				$output .= '<input type="hidden" name="package_subscribe" value="'.esc_attr($package_subscribe).'">';
			}else if (($item_process == "buy_questions" || $item_process == "buy_posts") && $package_subscribe != "") {
				$output .= '<input type="hidden" name="buy_package" value="'.esc_attr($package_subscribe).'">';
			}
			if ($coupon_name != "") {
				$output .= '<input type="hidden" name="coupon" value="'.esc_attr($coupon_name).'">';
			}
			if (is_numeric($points_price) && $points_price > 0) {
				$output .= '<input type="hidden" name="points" value="'.$points_price.'">';
			}
			if (is_numeric($post_id) && $post_id > 0) {
				$output .= '<input type="hidden" name="post_id" value="'.$post_id.'">';
			}
			if ($package_subscribe != "") {
				$output .= '<input type="hidden" name="item_id" value="'.$package_subscribe.'">';
			}
		$output .= '</form>
	</div>';
	return $output;
}?>