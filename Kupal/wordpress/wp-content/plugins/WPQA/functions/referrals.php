<?php

/* @author    2codeThemes
*  @package   WPQA/functions
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* Send invitation */
if (!function_exists('wpqa_send_invitation')) :
	function wpqa_send_invitation() {
		check_ajax_referer('invitation_nonce','invitation_nonce');
		$email = esc_html($_POST["email"]);
		$user_id = get_current_user_id();
		$user = get_user_by('email',$email);
		if (isset($user->first_name)) {
			echo "email_exist";
		}else {
			$rand = wpqa_token(15);
			$invitation_link = add_query_arg(array("invite" => $rand),esc_url(home_url('/')));
			$send_text = wpqa_send_mail(
				array(
					'content'         => wpqa_options("email_new_invitation"),
					'user_id'         => $user_id,
					'invitation_link' => $invitation_link,
				)
			);
			$email_title = wpqa_options("title_new_invitation");
			$email_title = ($email_title != ""?$email_title:esc_html__("New invitation","wpqa"));
			$email_title = wpqa_send_mail(
				array(
					'content'         => $email_title,
					'title'           => true,
					'break'           => '',
					'user_id'         => $user_id,
					'invitation_link' => $invitation_link,
				)
			);
			$the_author = get_the_author_meta("display_name",$user_id);
			wpqa_send_mails(
				array(
					'toEmail'     => $email,
					'toEmailName' => $the_author,
					'title'       => $email_title,
					'message'     => $send_text,
				)
			);
			update_user_meta($user_id,$rand,array("email" => $email,"status" => "sent","points" => 0,"resend" => 0));
			$points_referrals_meta = get_user_meta($user_id,"points_referrals",true);
			if (empty($points_referrals_meta)) {
				$update = update_user_meta($user_id,"points_referrals",array($rand));
			}else if (is_array($points_referrals_meta) && !in_array($rand,$points_referrals_meta)) {
				$update = update_user_meta($user_id,"points_referrals",array_merge($points_referrals_meta,array($rand)));
			}
			wpqa_add_points($user_id,1,"+","",0,0,0,"invitations_sent",false);
		}
		die();
	}
endif;
add_action("wp_ajax_nopriv_wpqa_send_invitation","wpqa_send_invitation");
add_action("wp_ajax_wpqa_send_invitation","wpqa_send_invitation");
/* Resend invitation */
if (!function_exists('wpqa_resend_invitation')) :
	function wpqa_resend_invitation() {
		check_ajax_referer('invitation_resend_nonce','invitation_resend_nonce');
		$email = esc_html($_POST["email"]);
		$invite = esc_html($_POST["invite"]);
		$user_id = get_current_user_id();
		$invitation_link = add_query_arg(array("invite" => $invite),esc_url(home_url('/')));
		$send_text = wpqa_send_mail(
			array(
				'content'         => wpqa_options("email_new_invitation"),
				'user_id'         => $user_id,
				'invitation_link' => $invitation_link,
			)
		);
		$email_title = wpqa_options("title_new_invitation");
		$email_title = ($email_title != ""?$email_title:esc_html__("New invitation","wpqa"));
		$email_title = wpqa_send_mail(
			array(
				'content'         => $email_title,
				'title'           => true,
				'break'           => '',
				'user_id'         => $user_id,
				'invitation_link' => $invitation_link,
			)
		);
		$the_author = get_the_author_meta("display_name",$user_id);
		wpqa_send_mails(
			array(
				'toEmail'     => $email,
				'toEmailName' => $the_author,
				'title'       => $email_title,
				'message'     => $send_text,
			)
		);
		$invite_meta = get_user_meta($user_id,$invite,true);
		if (is_array($invite_meta) && !empty($invite_meta) && isset($invite_meta["status"]) && $invite_meta["status"] != "completed") {
			$invite_meta["resend"] = (int)(isset($invite_meta["resend"]) && $invite_meta["resend"] > 0?($invite_meta["resend"]+1):1);
			update_user_meta($user_id,$invite,$invite_meta);
		}
		die();
	}
endif;
add_action("wp_ajax_nopriv_wpqa_resend_invitation","wpqa_resend_invitation");
add_action("wp_ajax_wpqa_resend_invitation","wpqa_resend_invitation");?>