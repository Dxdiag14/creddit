<?php

/* @author    2codeThemes
*  @package   WPQA/functions
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* Activation */
add_filter('wpqa_init','wpqa_check_user_account');
if (!function_exists('wpqa_check_user_account')) :
	function wpqa_check_user_account($return = false,$confirm = false) {
		if (is_user_logged_in()) {
			$user_id = get_current_user_id();
			$if_user_id = get_user_by("id",$user_id);
		}
		$activate_review_users = apply_filters("wpqa_activate_review_users",false);
		$user_review = wpqa_options("user_review");
		$active_referral = wpqa_options("active_referral");
		if ($active_referral == "on" && !is_user_logged_in() && isset($_GET['invite']) && $_GET['invite'] != "") {
			$invite = esc_html($_GET['invite']);
			if (isset($_COOKIE[wpqa_options("uniqid_cookie").'wpqa_referral'])) {
				unset($_COOKIE[wpqa_options("uniqid_cookie").'wpqa_referral']);
				setcookie(wpqa_options("uniqid_cookie").'wpqa_referral',null,-1,COOKIEPATH,COOKIE_DOMAIN);
			}
			setcookie(wpqa_options("uniqid_cookie").'wpqa_referral',$invite,time()+MONTH_IN_SECONDS,COOKIEPATH,COOKIE_DOMAIN);
			$user = get_users(array("number" => 1,"count_total" => false,"meta_query" => array("relation" => "OR",array("key" => "wpqa_referral","value" => $invite),array("key" => $invite))));
			$user_id_invite = (int)(isset($user[0]) && isset($user[0]->ID)?$user[0]->ID:0);
			if ($user_id_invite > 0) {
				$invite_meta = get_user_meta($user_id_invite,$invite,true);
				if (is_array($invite_meta) && !empty($invite_meta) && isset($invite_meta["status"]) && $invite_meta["status"] == "sent") {
					$invite_meta["status"] = "pending";
					update_user_meta($user_id_invite,$invite,$invite_meta);
					wpqa_add_points($user_id_invite,1,"+","",0,0,0,"invitations_pending",false);
					wpqa_add_points($user_id_invite,1,"-","",0,0,0,"invitations_sent",false);
				}
			}
			wp_safe_redirect(esc_url(home_url('/')));
			exit();
		}else if (is_user_logged_in() && ($activate_review_users == true || $user_review == "on") && !wpqa_is_user_owner() && !wpqa_is_user_edit_profile() && isset($if_user_id->caps["wpqa_under_review"]) && $if_user_id->caps["wpqa_under_review"] == 1) {
			if ($return == true) {
				echo '<div class="alert-message warning"><i class="icon-flag"></i><p>'.apply_filters("wpqa_filter_membership_under_review",esc_html__("Your account is under review, You will be notified via mail when it has been approved.","wpqa"),$user_id).'</p></div>';
				if (wpqa_users_confirm_mail() == "yes") {
					get_footer();
					die();
				}
			}
		}else if ((is_user_logged_in() && isset($if_user_id->caps["activation"]) && $if_user_id->caps["activation"] == 1) || (!is_user_logged_in() && isset($_GET['u']) && isset($_GET['activate']))) {
			$get_activate = (isset($_GET['activate']) && $_GET['activate'] != ""?esc_html($_GET['activate']):"");
			if (!is_user_logged_in() && isset($_GET['u']) && isset($_GET['activate'])) {
				$user_id = (isset($_GET['u']) && $_GET['u'] != ""?(int)$_GET['u']:"");
			}
			$activation = get_user_meta($user_id,"activation",true);
			if (isset($_GET['u']) && isset($_GET['activate'])) {
				$activation = get_user_meta($user_id,"activation",true);
				if ($activation == $get_activate) {
					$default_group = wpqa_options("default_group");
					$default_group = (isset($default_group) && $default_group != ""?$default_group:"subscriber");
					$default_group = ($activate_review_users == true || $user_review == "on"?"wpqa_under_review":$default_group);
					wp_update_user(array('ID' => $user_id,'role' => $default_group));
					delete_user_meta($user_id,"activation");
					delete_user_meta($user_id,"wpqa_default_group");
					do_action("wpqa_after_registration",$user_id);
					if ($activate_review_users == true || $user_review == "on") {
						$send_email_users_review = wpqa_options("send_email_users_review");
						if ($send_email_users_review == "on") {
							$send_text = wpqa_send_mail(
								array(
									'content' => wpqa_options("email_review_user"),
									'user_id' => $user_id
								)
							);
							$email_title = wpqa_options("title_review_user");
							$email_title = ($email_title != ""?$email_title:esc_html__("New user for review","wpqa"));
							$email_title = wpqa_send_mail(
								array(
									'content' => $email_title,
									'title'   => true,
									'break'   => '',
									'user_id' => $user_id
								)
							);
							wpqa_send_mails(
								array(
									'title'   => $email_title,
									'message' => $send_text,
								)
							);
						}
						wpqa_session('<div class="alert-message success"><i class="icon-check"></i><p>'.esc_html__("Your membership was activated, But it need a review first, You will be notified via mail when it has been approved.","wpqa").'</p></div>','wpqa_session');
					}else {
						update_user_meta($user_id,"user_activated","activated");
						do_action("wpqa_user_activated",$user_id);
						wpqa_session('<div class="alert-message success"><i class="icon-check"></i><p>'.esc_html__("Your membership was activated.","wpqa").'</p></div>','wpqa_session');
					}
					wp_safe_redirect(esc_url(home_url('/')));
					exit();
				}else {
					if ($return == true) {
						if (is_user_logged_in()) {
							echo '<div class="alert-message warning"><i class="icon-flag"></i><p>'.sprintf(esc_html__('Your confirmation link has expired or not valid, Please request a new link, kindly %1$s Click here %2$s to re-send another confirmation mail.','wpqa'),'<a href="'.esc_url_raw(add_query_arg(array('get_activate' => 'do'),esc_url(home_url('/')))).'">','</a>').'</p></div>';
						}else {
							echo '<div class="alert-message warning"><i class="icon-flag"></i><p>'.sprintf(esc_html__('Your confirmation link has expired or not valid, Please request a new link, kindly %1$s Login again %2$s to request another confirmation mail.','wpqa'),'<a data-toggle="modal" class="login-panel'.apply_filters('wpqa_pop_up_class','').apply_filters('wpqa_pop_up_class_login','').'" href="'.wpqa_login_permalink().'">','</a>').'</p></div>';
						}
					}
				}
			}else if (!isset($_GET['activate']) && !isset($_SESSION['wpqa_session'])) {
				if (isset($_GET['get_activate']) && $_GET['get_activate'] == "do") {
					wpqa_resend_confirmation($user_id);
					wpqa_session('<div class="alert-message success"><i class="icon-check"></i><p>'.esc_html__("Check your mail again.","wpqa").'</p></div>','wpqa_session');
					wp_safe_redirect(esc_url(home_url('/')));
					die();
				}else {
					if ($return == true) {
						echo '<div class="alert-message warning"><i class="icon-flag"></i><p>'.sprintf(esc_html__('A confirmation mail has been sent to your registered email account, If you have not received the confirmation mail, kindly %1$s Click here %2$s to re-send another confirmation mail.','wpqa'),'<a href="'.esc_url_raw(add_query_arg(array('get_activate' => 'do'),esc_url(home_url('/')))).'">','</a>').'</p></div>';
					}
				}
			}
			if (wpqa_users_confirm_mail() == "yes" && $confirm == true) {
				get_footer();
				die();
			}
		}else if (is_user_logged_in() && isset($if_user_id->caps["ban_group"]) && $if_user_id->caps["ban_group"] == 1) {
			if ($return == true) {
				echo '<div class="alert-message error"><i class="icon-cancel"></i><p>'.esc_html__("You have been banned from using the site, you can contact the admin to know the reason for the ban and appeal the ban.","wpqa").'</p></div>';
				get_footer();
				die();
			}
		}else {
			if (isset($_GET['u']) && isset($_GET['activate'])) {
				wpqa_session('<div class="alert-message success"><i class="icon-check"></i><p>'.sprintf(esc_html__('You are already logged in, you have activated your membership, go to your %1$s profile. %2$s','wpqa'),"<a href='".wpqa_profile_url($user_id)."'>","</a>").'</p></div>','wpqa_session');
				wp_safe_redirect(esc_url(home_url('/')));
				die();
			}
		}
	}
endif;
/* Resend confirmation */
function wpqa_resend_confirmation($user_id) {
	$user_email = get_the_author_meta("user_email",$user_id);
	$display_name = get_the_author_meta("display_name",$user_id);
	$rand_a = wpqa_token(15);
	update_user_meta($user_id,"activation",$rand_a);
	$confirm_link = esc_url_raw(add_query_arg(array("u" => $user_id,"activate" => $rand_a),esc_url(home_url('/'))));
	$send_text = wpqa_send_mail(
		array(
			'content'            => wpqa_options("email_confirm_link"),
			'user_id'            => $user_id,
			'confirm_link_email' => $confirm_link,
		)
	);
	$email_title = wpqa_options("title_confirm_link");
	$email_title = ($email_title != ""?$email_title:esc_html__("Confirm account","wpqa"));
	$email_title = wpqa_send_mail(
		array(
			'content'            => $email_title,
			'title'              => true,
			'break'              => '',
			'user_id'            => $user_id,
			'confirm_link_email' => $confirm_link,
		)
	);
	$email_template = wpqa_options("email_template");
	$mail_smtp = wpqa_options("mail_smtp");
	$email_template = ($mail_smtp == "on"?wpqa_options("mail_username"):$email_template);
	wpqa_send_mails(
		array(
			'toEmail'     => esc_html($user_email),
			'toEmailName' => esc_html($display_name),
			'title'       => $email_title,
			'message'     => $send_text,
		)
	);
}?>