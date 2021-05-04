<?php

/* @author    2codeThemes
*  @package   WPQA/templates/profile
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
   exit; // Exit if accessed directly
}

include wpqa_get_template("edit-head.php","profile/");

echo "<div class='wpqa-templates wpqa-edit-group-template'>";
	$activate_pay_to_users = wpqa_options("activate_pay_to_users");
	if ($activate_pay_to_users == "on") {
		$pay_minimum_points = (int)wpqa_options("pay_minimum_points");
		$pay_minimum_money = floatval(wpqa_options("pay_minimum_money"));
		$currency_code = wpqa_options("currency_code");
		$points_user = (int)get_user_meta($wpqa_user_id,"points",true);
		if (isset($_POST["process"]) && $_POST["process"] == "request_money") {
			$pay_to_user_points = (int)wpqa_options("pay_to_user_points");
			$custom_points_value = (isset($_POST["choose_points"]) && $_POST["choose_points"] == "all"?$points_user:$_POST["custom_points"]);
			if ($custom_points_value > 0 && $custom_points_value >= $pay_minimum_points) {
				if ($points_user >= $custom_points_value) {
					$last_money = floatval($custom_points_value/$pay_minimum_points);
					if ($last_money >= $pay_minimum_money) {
						$success = true;
					}else {
						$error = esc_html__("You don't get the minimum money to can request your payment.","wpqa");
					}
				}else {
					$error = esc_html__("You don't have these points.","wpqa");
				}
			}else {
				$error = esc_html__("You don't get the minimum points to can request your payment.","wpqa");
			}
			if (isset($success)) {
				$users = get_users(array("role" => "administrator","blog_id" => 1,"fields" => array("ID")));
				$email_title = wpqa_options("title_new_request");
				$email_title = ($email_title != ""?$email_title:esc_html__("New request for review","wpqa"));
				if (isset($users) && is_array($users) && !empty($users)) {
					foreach ($users as $key => $value) {
						$another_user_id = $value->ID;
						if ($another_user_id > 0 && $wpqa_user_id != $another_user_id) {
							$user = get_userdata($another_user_id);
							$send_text = wpqa_send_mail(
								array(
									'content'            => wpqa_options("email_new_request"),
									'received_user_id'   => $wpqa_user_id,
									'request'            => 'request',
								)
							);
							$email_title = wpqa_send_mail(
								array(
									'content'          => $email_title,
									'title'            => true,
									'break'            => '',
									'received_user_id' => $wpqa_user_id,
									'request'          => 'request',
								)
							);
							wpqa_send_mails(
								array(
									'toEmail'       => esc_html($user->user_email),
									'toEmailName'   => esc_html($user->display_name),
									'title'         => $email_title,
									'message'       => $send_text,
								)
							);
							wpqa_notifications_activities($another_user_id,$wpqa_user_id,"","","","requested_money","notifications");
						}
					}
				}

				wpqa_add_points($wpqa_user_id,$custom_points_value,"-","withdrawal_points");
				wpqa_new_request($wpqa_user_id,"withdrawal_points",$custom_points_value,(isset($last_money)?$last_money:0),"withdrawal_points");

				$_SESSION['wpqa_session'] = '<div class="alert-message success"><i class="icon-check"></i><p>'.esc_html__("You have just requested your money.","wpqa").'</p></div>';
			}else {
				$_SESSION['wpqa_session'] = '<div class="alert-message error"><i class="icon-cancel"></i><p>'.$error.'</p></div>';
			}
			wp_safe_redirect(wpqa_get_profile_permalink($wpqa_user_id,"withdrawals"));
			die();
		}?>
		<div class="withdrew_content page-section">
			<h2 class="post-title-2"><i class="icon-vcard"></i><?php esc_html_e("Withdrawals","wpqa")?></h2>
			<?php $financial_payments = get_user_meta($wpqa_user_id,"financial_payments",true);
			if ($financial_payments == "") {
				$show_payment_message = true;
			}else if ($financial_payments != "") {
				if ($financial_payments == "paypal" && get_user_meta($wpqa_user_id,'paypal_email',true) == "") {
					$show_payment_message = true;
				}else if ($financial_payments == "payoneer" && get_user_meta($wpqa_user_id,'payoneer_email',true) == "") {
					$show_payment_message = true;
				}else if ($financial_payments == "bank") {
					if (get_user_meta($wpqa_user_id,'bank_account_holder',true) == "" || get_user_meta($wpqa_user_id,'bank_your_address',true) == "" || get_user_meta($wpqa_user_id,'bank_name',true) == "" || get_user_meta($wpqa_user_id,'bank_address',true) == "" || get_user_meta($wpqa_user_id,'bank_swift_iban',true) == "" || get_user_meta($wpqa_user_id,'bank_account_number',true) == "") {
						$show_payment_message = true;
					}
				}
			}
			if (isset($show_payment_message)) {
				echo '<div class="alert-message warning"><i class="icon-flag"></i><p>'.sprintf(esc_html__("You have not set any payment methods yet, Please %s click here %s to can request a new payment.","wpqa"),"<a href='".wpqa_get_profile_permalink($wpqa_user_id,"financial")."'>","</a>").'</p></div>';
			}
			if ($points_user > 0 && $points_user >= $pay_minimum_points) {
				$last_money = floatval($points_user/$pay_minimum_points);
				if ($last_money >= $pay_minimum_money) {
					$user_money = $last_money;
				}
				echo '<div class="alert-message success"><i class="icon-check"></i><p>'.sprintf(esc_html__("By getting %s you will receive %s.","wpqa"),"<span>".sprintf(_n("%s Point","%s Points",$pay_minimum_points,"wpqa"),$pay_minimum_points)."</span>","<span>1 ".$currency_code."</span>").'</p></div>';
			}?>
			<div class="current_balance">
				<span><?php echo sprintf(esc_html__("We pay for %s, and your current your balance","wpqa"),"<span>".number_format($pay_minimum_money)." ".$currency_code."</span>")?></span>
				<strong><?php echo "<span>".(isset($last_money)?$last_money:0)."</span> ".$currency_code?></strong>
			</div>
			<?php if (!isset($show_payment_message)) {?>
			<div class="wpqa_error"></div>
				<strong><?php esc_html_e("Amount","wpqa")?></strong>
				<form method="post" action="">
					<div class="row points">
						<div class="col col4">
							<div class="points_chooseWay points_chooseCustom">
								<label class="points_radio points_radio_first" for="points_custom">
									<input type="radio" name="choose_points" id="points_custom" value="custom">
								</label>
								<label class="points_label" for="points_custom">
									<input class="custom_points" type="number" name="custom_points" placeholder="<?php esc_html_e("Custom points","wpqa")?>">
								</label>
							</div>
						</div>
						<div class="col col4">
							<div class="points_chooseWay">
								<label class="points_radio" for="points_all">
									<input type="radio" name="choose_points" id="points_all" value="all" checked="checked">
								</label>
								<label for="points_all"><?php esc_html_e("All points","wpqa")?></label>
							</div>
						</div>
						<div class="col col4">
							<span class="load_span"><span class="loader_2"></span></span>
							<input type="submit" value="<?php esc_html_e("Submit request","wpqa")?>" class="button-default button-hide-click submit submit-request">
							<input type="hidden" name="process" value="request_money">
						</div>
					</div>
				</form>
			<?php }?>
			<div class="clearfix"></div>
			<p><?php esc_html_e("If you choose the all money option, you must still meet the minimum withdrawal amounts below. Your withdrawal request will not be processed unless it meets the minimum requirements.","wpqa")?></p>
			<p><?php esc_html_e("Please note that the request must be renewed each month. This means clicking will not result in automatic withdrawals for the coming months.","wpqa")?></p>
			<?php $paged = (get_query_var("paged") != ""?(int)get_query_var("paged"):(get_query_var("page") != ""?(int)get_query_var("page"):1));
			$array_data = array("post_type" => "request","author" => $wpqa_user_id,"meta_query" => array(array("key" => "request_type","value" => "withdrawal_points")),"paged" => $paged,"posts_per_page" => get_option("posts_per_page"));
			$wpqa_query = new WP_Query($array_data);
			if ($wpqa_query->have_posts()) :?>
				<div class="history_transfer">
					<strong><?php esc_html_e("History Transfer","wpqa")?></strong>
					<table>
						<tr>
							<th><i class="icon-calendar"></i><?php esc_html_e("Transfer date","wpqa")?></th>
							<th><i class="icon-briefcase"></i><?php esc_html_e("Transaction Amount","wpqa")?></th>
							<th class="wpqa_hide_mobile_600"><i class="icon-help"></i><?php esc_html_e("Transaction Status","wpqa")?></th>
						</tr>
						<?php while ($wpqa_query->have_posts()) : $wpqa_query->the_post();
							if (isset($GLOBALS['post'])) {
								$post_data = $GLOBALS['post'];
							}
							$post_id = $post_data->ID;
							$request_status = get_post_meta($post_id,"request_status",true);
							if ($request_status == 1) {
								$request_status_last = esc_html__("Accepted","wpqa");
							}else if ($request_status == 2) {
								$request_status_last = esc_html__("Rejected","wpqa");
							}else {
								$request_status_last = esc_html__("Pending","wpqa");
							}
							$human_time_diff = human_time_diff(get_the_time('U'), current_time('timestamp'));?>
							<tr>
								<td><?php echo ($human_time_diff." ".esc_html__("ago","wpqa"));?></td>
								<td><?php echo get_post_meta($post_id,"request_related_item",true)." ".$currency_code?></td>
								<td class="wpqa_hide_mobile_600"><?php echo ($request_status_last)?></td> 
							</tr>
						<?php endwhile;?>
					</table>
				</div>
				<?php wpqa_pagination_load("pagination",(isset($wpqa_query->max_num_pages)?$wpqa_query->max_num_pages:""));
			else:
				echo '<div class="alert-message warning"><i class="icon-flag"></i><p>'.esc_html__("There are no withdrawals yet.","wpqa").'</p></div>';
			endif;?>
			<?php wp_reset_postdata();?>
		</div>
	<?php }else {
		echo '<div class="alert-message error"><i class="icon-cancel"></i><p>'.esc_html__("Sorry, this page is not available.","wpqa").'</p></div>';
	}
echo "</div>";?>