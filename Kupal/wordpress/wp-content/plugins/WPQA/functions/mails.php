<?php

/* @author    2codeThemes
*  @package   WPQA/functions
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* Send email */
if (!function_exists('wpqa_sendEmail')) :
	function wpqa_sendEmail($fromEmail = "",$fromEmailName = "",$toEmail = "",$toEmailName = "",$title,$message,$email_code = "") {
		return wpqa_send_mails($fromEmail,$fromEmailName,$toEmail,$toEmailName,$title,$message,$email_code);
	}
endif;
/* Send mails */
if (!function_exists('wpqa_send_mails')) :
	function wpqa_send_mails($args = array()) {
		$defaults = array(
			'fromEmail'     => '',
			'fromEmailName' => '',
			'toEmail'       => '',
			'toEmailName'   => '',
			'title'         => '',
			'message'       => '',
			'email_code'    => '',
		);
		
		$args = wp_parse_args($args,$defaults);
		
		$fromEmail     = $args['fromEmail'];
		$fromEmailName = $args['fromEmailName'];
		$toEmail       = $args['toEmail'];
		$toEmailName   = $args['toEmailName'];
		$title         = $args['title'];
		$message       = $args['message'];
		$email_code    = $args['email_code'];

		$mail_username = wpqa_options("mail_username");
		$mail_smtp = wpqa_options("mail_smtp");
		if ($fromEmail == "") {
			$email_template = wpqa_options("email_template");
			$fromEmail = ($mail_smtp == "on"?$mail_username:$email_template);
		}
		$fromEmail = ($mail_smtp == "on"?$mail_username:$fromEmail);
		$fromEmailName = ($fromEmailName != ""?$fromEmailName:get_bloginfo('name'));
		$toEmail = ($toEmail != ""?$toEmail:wpqa_options("email_template_to"));
		$toEmailName = ($toEmailName != ""?$toEmailName:get_bloginfo('name'));
		
		$fromEmail = apply_filters("wpqa_sendemail_from",$fromEmail);
		$fromEmailName = apply_filters("wpqa_sendemail_fromname",$fromEmailName);
		$toEmail = apply_filters("wpqa_sendemail_to",$toEmail);
		$toEmailName = apply_filters("wpqa_sendemail_toname",$toEmailName);
		if ($email_code == "") {
			$message = wpqa_email_code($message);
		}
		$version = get_bloginfo('version');
		if (!class_exists('PHPMailer')) {
			require_once ABSPATH . WPINC . '/PHPMailer/PHPMailer.php';
			require_once ABSPATH . WPINC . '/PHPMailer/SMTP.php';
			require_once ABSPATH . WPINC . '/PHPMailer/Exception.php';
		}
		$mail = new PHPMailer\PHPMailer\PHPMailer();
		$mail->isSendmail();
		if ($mail_smtp == "on") {
			$mail_host = wpqa_options("mail_host");
			$mail_password = wpqa_options("mail_password");
			$mail_secure = wpqa_options("mail_secure");
			$mail_port = wpqa_options("mail_port");
			$disable_ssl = wpqa_options("disable_ssl");
			$smtp_auth = wpqa_options("smtp_auth");
			if ($mail_host != "" && $mail_port != "" && $mail_username != "" && $mail_password != "" && $mail_secure != "") {
				$mail->isSMTP();
				$mail->Host = $mail_host;
				if ($smtp_auth = "on") {
					$mail->SMTPAuth = true;
				}
				$mail->Username = $mail_username;
				$mail->Password = $mail_password;
				if ($mail_secure != "none") {
					$mail->SMTPSecure = $mail_secure;
				}
				$mail->Port = $mail_port;
			}
			if ($disable_ssl == "on") {
				$mail->SMTPOptions = array(
					'ssl' => array(
						'verify_peer' => false,
						'verify_peer_name' => false,
						'allow_self_signed' => true
					)
				);
			}
		}
		$mail->isHTML(true);
		$mail->setFrom($fromEmail,htmlspecialchars_decode($fromEmailName));
		$mail->addReplyTo($fromEmail,htmlspecialchars_decode($fromEmailName));
		$mail->addAddress($toEmail,$toEmailName);
		$mail->CharSet = 'UTF-8';
		$mail->Subject = htmlspecialchars_decode($title);
		$mail->Body = $message;
		if ($version < 5.5 || !isset($mail) || (isset($mail) && !$mail->Send())) {
			add_filter('wp_mail_content_type','wpqa_set_content_type');
			$headers = 'From: '.$fromEmailName.' <'.$fromEmail.'>' . "\r\n";
			wp_mail($toEmail,htmlspecialchars_decode($title),$message,$headers);
		}
	}
endif;
if (!function_exists('wpqa_set_content_type')) :
	function wpqa_set_content_type(){
		return "text/html";
	}
endif;
/* Send mail template */
if (!function_exists('wpqa_send_mail')) :
	function wpqa_send_mail($args = array()) {
		$defaults = array(
			'content'            => '',
			'title'              => '',
			'break'              => 'break',
			'user_id'            => 0,
			'post_id'            => 0,
			'comment_id'         => 0,
			'reset_password'     => '',
			'confirm_link_email' => '',
			'item_price'         => '',
			'item_name'          => '',
			'item_currency'      => '',
			'payer_email'        => '',
			'first_name'         => '',
			'last_name'          => '',
			'item_transaction'   => '',
			'date'               => '',
			'time'               => '',
			'category'           => '',
			'custom'             => '',
			'sender_user_id'     => '',
			'received_user_id'   => 0,
			'invitation_link'    => '',
			'request'            => '',
		);
		
		$args = wp_parse_args($args,$defaults);
		
		$content            = $args['content'];
		$title              = $args['title'];
		$break              = $args['break'];
		$user_id            = $args['user_id'];
		$post_id            = $args['post_id'];
		$comment_id         = $args['comment_id'];
		$reset_password     = $args['reset_password'];
		$confirm_link_email = $args['confirm_link_email'];
		$item_price         = $args['item_price'];
		$item_name          = $args['item_name'];
		$item_currency      = $args['item_currency'];
		$payer_email        = $args['payer_email'];
		$first_name         = $args['first_name'];
		$last_name          = $args['last_name'];
		$item_transaction   = $args['item_transaction'];
		$date               = $args['date'];
		$time               = $args['time'];
		$category           = $args['category'];
		$custom             = $args['custom'];
		$sender_user_id     = $args['sender_user_id'];
		$received_user_id   = $args['received_user_id'];
		$invitation_link    = $args['invitation_link'];
		$request            = $args['request'];

		$content = str_ireplace('[%blogname%]', '<span class="mail-class-blogname">'.get_bloginfo('name').'</span>', $content);
		$content = str_ireplace('[%site_url%]', esc_url(home_url('/')), $content);
		
		if ($user_id > 0) {
			$user = new WP_User($user_id);
			$content = str_ireplace('[%messages_url%]' , esc_url(wpqa_get_profile_permalink($user_id,"messages")), $content);
			$content = str_ireplace('[%user_login%]'   , '<span class="mail-class-user_login">'.$user->user_login.'</span>', $content);
			$content = str_ireplace('[%user_name%]'    , '<span class="mail-class-user_name">'.$user->user_login.'</span>', $content);
			$content = str_ireplace('[%user_nicename%]', '<span class="mail-class-user_nicename">'.ucfirst($user->user_nicename).'</span>', $content);
			$content = str_ireplace('[%display_name%]' , '<span class="mail-class-display_name">'.ucfirst($user->display_name).'</span>', $content);
			$content = str_ireplace('[%user_email%]'   , '<span class="mail-class-user_email">'.$user->user_email.'</span>', $content);
			$content = str_ireplace('[%user_profile%]' , wpqa_profile_url($user->ID), $content);
			$content = str_ireplace('[%users_link%]'   , admin_url("users.php?role=wpqa_under_review"), $content);
		}
		
		if ($sender_user_id == "anonymous") {
			$content = str_ireplace('[%user_login_sender%]'   , '<span class="mail-class-user_login_sender">'.esc_html__("Anonymous","wpqa").'</span>', $content);
			$content = str_ireplace('[%user_name_sender%]'    , '<span class="mail-class-user_name_sender">'.esc_html__("Anonymous","wpqa").'</span>', $content);
			$content = str_ireplace('[%user_nicename_sender%]', '<span class="mail-class-user_nicename_sender">'.esc_html__("Anonymous","wpqa").'</span>', $content);
			$content = str_ireplace('[%display_name_sender%]' , '<span class="mail-class-display_name_sender">'.esc_html__("Anonymous","wpqa").'</span>', $content);
			$content = str_ireplace('[%user_email_sender%]'   , '<span class="mail-class-user_email_sender">'.esc_html__("Anonymous","wpqa").'</span>', $content);
			$content = str_ireplace('[%user_profile_sender%]' , esc_url(home_url('/')), $content);
		}else if (is_numeric($sender_user_id) && $sender_user_id > 0) {
			$user = new WP_User($sender_user_id);
			$content = str_ireplace('[%user_login_sender%]'   , '<span class="mail-class-user_login_sender">'.$user->user_login.'</span>', $content);
			$content = str_ireplace('[%user_name_sender%]'    , '<span class="mail-class-user_name_sender">'.$user->user_login.'</span>', $content);
			$content = str_ireplace('[%user_nicename_sender%]', '<span class="mail-class-user_nicename_sender">'.ucfirst($user->user_nicename).'</span>', $content);
			$content = str_ireplace('[%display_name_sender%]' , '<span class="mail-class-display_name_sender">'.ucfirst($user->display_name).'</span>', $content);
			$content = str_ireplace('[%user_email_sender%]'   , '<span class="mail-class-user_email_sender">'.$user->user_email.'</span>', $content);
			$content = str_ireplace('[%user_profile_sender%]' , wpqa_profile_url($user->ID), $content);
		}else {
			if (is_object($sender_user_id)) {
				$content = str_ireplace('[%user_login_sender%]'   , '<span class="mail-class-user_login_sender">'.$sender_user_id->comment_author.'</span>', $content);
				$content = str_ireplace('[%user_name_sender%]'    , '<span class="mail-class-user_name_sender">'.$sender_user_id->comment_author.'</span>', $content);
				$content = str_ireplace('[%user_nicename_sender%]', '<span class="mail-class-user_nicename_sender">'.ucfirst($sender_user_id->comment_author).'</span>', $content);
				$content = str_ireplace('[%display_name_sender%]' , '<span class="mail-class-display_name_sender">'.ucfirst($sender_user_id->comment_author).'</span>', $content);
				$content = str_ireplace('[%user_email_sender%]'   , '<span class="mail-class-user_email_sender">'.$sender_user_id->comment_author_email.'</span>', $content);
				$content = str_ireplace('[%user_profile_sender%]' , esc_url(($sender_user_id->comment_author_url != ''?$sender_user_id->comment_author_url:home_url('/'))), $content);
			}
		}
		
		if ($received_user_id > 0) {
			$user = new WP_User($received_user_id);
			$content = str_ireplace('[%user_login%]'   , '<span class="mail-class-user_login">'.$user->user_login.'</span>', $content);
			$content = str_ireplace('[%user_name%]'    , '<span class="mail-class-user_name">'.$user->user_login.'</span>', $content);
			$content = str_ireplace('[%user_nicename%]', '<span class="mail-class-user_nicename">'.ucfirst($user->user_nicename).'</span>', $content);
			$content = str_ireplace('[%display_name%]' , '<span class="mail-class-display_name">'.ucfirst($user->display_name).'</span>', $content);
			$content = str_ireplace('[%user_email%]'   , '<span class="mail-class-user_email">'.$user->user_email.'</span>', $content);
			$content = str_ireplace('[%user_profile%]' , wpqa_profile_url($user->ID), $content);
		}
		
		if ($reset_password != '') {
			$content = str_ireplace('[%reset_password%]', $reset_password, $content);
		}
		if ($confirm_link_email != '') {
			$content = str_ireplace('[%confirm_link_email%]', $confirm_link_email, $content);
		}
		
		if ($comment_id > 0) {
			$get_comment = get_comment($comment_id);
			$content = str_ireplace('[%comment_link%]', admin_url("edit-comments.php?comment_status=moderated"), $content);
			$content = str_ireplace('[%answer_link%]' , get_permalink($post_id).'#li-comment-'.$comment_id, $content);
			$content = str_ireplace('[%answer_url%]'  , get_permalink($post_id).'#li-comment-'.$comment_id, $content);
			$content = str_ireplace('[%comment_url%]' , get_permalink($post_id).'#li-comment-'.$comment_id, $content);
			$content = str_ireplace('[%the_name%]'    , '<span class="mail-class-the_name">'.$get_comment->comment_author.'</span>', $content);
		}
		
		if ($post_id > 0) {
			$post = get_post($post_id);
			$content = str_ireplace('[%messages_title%]', '<span class="mail-class-messages_title">'.$post->post_title.'</span>', $content);
			$content = str_ireplace('[%question_title%]', '<span class="mail-class-question_title">'.$post->post_title.'</span>', $content);
			$content = str_ireplace('[%post_title%]'    , '<span class="mail-class-post_title">'.$post->post_title.'</span>', $content);
			$content = str_ireplace('[%question_link%]' , ($post->post_status == 'publish'?get_permalink($post_id):admin_url('post.php?post='.$post_id.'&action=edit')), $content);
			$content = str_ireplace('[%post_link%]'     , ($post->post_status == 'publish'?get_permalink($post_id):admin_url('post.php?post='.$post_id.'&action=edit')), $content);
			if ($post->post_author > 0) {
				$get_the_author = get_user_by("id",$post->post_author);
				$the_author_post = $get_the_author->display_name;
			}else {
				$the_author_post = get_post_meta($post_id,($post->post_type == 'question'?'question_username':'post_username'),true);
				$the_author_post = ($the_author_post != ''?$the_author_post:esc_html__("Anonymous","wpqa"));
			}
			$content = str_ireplace('[%the_author_question%]', '<span class="mail-class-the_author_question">'.$the_author_post.'</span>', $content);
			$content = str_ireplace('[%the_author_post%]'    , '<span class="mail-class-the_author_post">'.$the_author_post.'</span>', $content);
		}
		
		if ($item_price != '') {
			$content = str_ireplace('[%item_price%]', '<span class="mail-class-item_price">'.$item_price.'</span>', $content);
		}
		if ($item_name != '') {
			$content = str_ireplace('[%item_name%]', '<span class="mail-class-item_name">'.$item_name.'</span>', $content);
		}
		if ($item_currency != '') {
			$content = str_ireplace('[%item_currency%]', '<span class="mail-class-item_currency">'.$item_currency.'</span>', $content);
		}
		if ($payer_email != '') {
			$content = str_ireplace('[%payer_email%]', '<span class="mail-class-payer_email">'.$payer_email.'</span>', $content);
		}
		if ($first_name != '') {
			$content = str_ireplace('[%first_name%]', '<span class="mail-class-first_name">'.$first_name.'</span>', $content);
		}else if (isset($user) && isset($user->display_name)) {
			$content = str_ireplace('[%first_name%]', '<span class="mail-class-first_name">'.ucfirst($user->display_name).'</span>', $content);
		}else {
			$content = str_ireplace('[%first_name%]', '', $content);
		}
		if ($last_name != '') {
			$content = str_ireplace('[%last_name%]', '<span class="mail-class-last_name">'.$last_name.'</span>', $content);
		}else {
			$content = str_ireplace('[%last_name%]', '', $content);
		}
		if ($item_transaction != '') {
			$content = str_ireplace('[%item_transaction%]', '<span class="mail-class-item_transaction">'.$item_transaction.'</span>', $content);
		}
		if ($date != '') {
			$content = str_ireplace('[%date%]', '<span class="mail-class-date">'.$date.'</span>', $content);
		}
		if ($time != '') {
			$content = str_ireplace('[%time%]', '<span class="mail-class-time">'.$time.'</span>', $content);
		}
		if ($category != '') {
			$content = str_ireplace('[%category_link%]', admin_url('edit.php?post_type=request&request=category'), $content);
			$content = str_ireplace('[%category_name%]', '<span class="mail-class-category_name">'.$category.'</span>', $content);
		}
		if ($request != '') {
			$content = str_ireplace('[%request_link%]', admin_url('edit.php?post_type=request'), $content);
			$content = str_ireplace('[%request_name%]', '<span class="mail-class-request_name">'.$request.'</span>', $content);
		}
		if ($invitation_link != '') {
			$content = str_ireplace('[%invitation_link%]', '<span class="mail-class-invitation_link">'.$invitation_link.'</span>', $content);
		}
		if ($custom != '') {
			$custom_content = apply_filters('wpqa_filter_send_email',false);
			$content = str_ireplace('[%custom_link%]', $custom_content, $content);
			$content = str_ireplace('[%custom_name%]', '<span class="mail-class-custom_name">'.$custom.'</span>', $content);
		}
		$break = apply_filters("wpqa_email_template_break",$break);
		if ($break == "break") {
			$return = nl2br(stripslashes($content));
		}else {
			if ($title == true) {
				$return = strip_tags(stripslashes($content));
			}else {
				$return = stripslashes($content);
			}
		}
		return $return;
	}
endif;
/* Emails */
if (!function_exists('wpqa_email_code')) :
	function wpqa_email_code($content,$mail = "",$schedule = "",$user_id = "") {
		$active_footer_email = wpqa_options("active_footer_email");
		$social_footer_email = wpqa_options("social_footer_email");
		$copyrights_for_email = wpqa_options("copyrights_for_email");
		$logo_email_template = wpqa_image_url_id(wpqa_options("logo_email_template"));
		$custom_image_mail = wpqa_image_url_id(wpqa_options("custom_image_mail"));
		$background_email = wpqa_options("background_email");
		$background_email = ($background_email != ""?$background_email:"#272930");
		$email_style = wpqa_options("email_style");
		$social_td = $recent_questions = '';
		if ($social_footer_email == "on") {
			$sort_social = wpqa_options("sort_social");
			$social = array(
				array("name" => "Facebook",   "value" => "facebook",   "icon" => "facebook"),
				array("name" => "Twitter",    "value" => "twitter",    "icon" => "twitter"),
				array("name" => "Linkedin",   "value" => "linkedin",   "icon" => "linkedin"),
				array("name" => "Dribbble",   "value" => "dribbble",   "icon" => "dribbble"),
				array("name" => "Youtube",    "value" => "youtube",    "icon" => "play"),
				array("name" => "Vimeo",      "value" => "vimeo",      "icon" => "vimeo"),
				array("name" => "Skype",      "value" => "skype",      "icon" => "skype"),
				array("name" => "WhatsApp",   "value" => "whatsapp",   "icon" => "whatsapp"),
				array("name" => "Flickr",     "value" => "flickr",     "icon" => "flickr"),
				array("name" => "Soundcloud", "value" => "soundcloud", "icon" => "soundcloud"),
				array("name" => "Instagram",  "value" => "instagram",  "icon" => "instagrem"),
				array("name" => "Pinterest",  "value" => "pinterest",  "icon" => "pinterest")
			);
			if (is_array($sort_social) && !empty($sort_social)) {
				$k = 0;
				foreach ($sort_social as $key_r => $value_r) {$k++;
					if (isset($sort_social[$key_r]["value"])) {
						$sort_social_value = $sort_social[$key_r]["value"];
						$social_icon_h = wpqa_options($sort_social_value."_icon_h");
						if ($sort_social_value != "rss" && $social_icon_h != "") {
							$social_url = ($sort_social_value == "skype"?"skype:":"").($sort_social_value == "whatsapp"?"whatsapp://send?abid=":"").($sort_social_value != "skype" && $sort_social_value != "whatsapp"?esc_url($social_icon_h):$social_icon_h).($sort_social_value == "skype"?"?call":"").($sort_social_value == "whatsapp"?"&text=".esc_html__("Hello","wpqa"):"");
							if ($email_style == "style_2") {
								$social_td .= '<a href="'.$social_url.'" title="'.$value_r["name"].'" style="color:#707478; margin-right:10px;font-size:14px;font-weight:400;">'.$value_r["name"].'</a>';
							}else {
								$social_td .= '<a href="'.$social_url.'" title="'.$value_r["name"].'" style="color:#707478; margin-right:10px;font-size:14px;font-weight:400;"><img alt="'.$value_r["name"].'" width="32" height="32" src="'.get_template_directory_uri().'/images/social/'.$value_r["value"].'.png" style="line-height:100%;outline:none;text-decoration:none;border:none"></a>';
							}
						}
					}
				}
			}
		}

		$primary_color = wpqa_options("primary_color");
		if ($primary_color != "") {
			$skin = $primary_color;
		}else {
			$skins = array("skin" => "#2d6ff7","violet" => "#9349b1","blue" => "#00aeef","bright_red" => "#fa4b2a","cyan" => "#058b7b","green" => "#81b441","red" => "#e91802");
			$site_skin = wpqa_options('site_skin');
			if ($site_skin == "skin" || $site_skin == "default" || $site_skin == "") {
				$skin = $skins["skin"];
			}else {
				$skin = $skins[$site_skin];
			}
		}

		if ($schedule != "") {
			global $post;
			$schedule_content = wpqa_options("schedule_content");
			if ($schedule == "daily") {
				$specific_date = "24 hours ago";
			}else if ($schedule == "weekly") {
				$specific_date = "1 week ago";
			}else if ($schedule == "monthly") {
				$specific_date = "1 month ago";
			}
			$recent_questions_query = new WP_Query(array('author' => -$user_id,'date_query' => array(array('after' => $specific_date)),'post_type' => 'question','ignore_sticky_posts' => 1,'cache_results' => false,'no_found_rows' => true,'posts_per_page' => 10));
			if ($recent_questions_query->have_posts()) :
		    	while ( $recent_questions_query->have_posts() ) : $recent_questions_query->the_post();
		    		if ($email_style == "style_2") {
		    			$recent_questions .= '<p><a href="'.get_permalink($post->ID).'" style="font-size:20px; color:#475568;font-weight:bold;margin-top:30px;line-height:24px;text-decoration: none;" class="hover">'.get_the_title($post->ID).'</a></p>';
		    		}else {
				        $recent_questions .= '<tr>
				            <td>
				                <p style="font-size:14px;color:'.$skin.';line-height:120%;margin-top:0;margin-bottom:10px;"><a style="text-decoration:none;color:#26333b" href="'.get_permalink($post->ID).'">'.get_the_title($post->ID).'</a></p>
				            </td>
				        </tr>';
				    }
			       endwhile;
			else :
				return 'no_question';
			endif;
			wp_reset_query();
		}

		$is_rtl = is_rtl();
		
		return '<!doctype html>
		<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
			<head>
				<title></title>
				<!--[if !mso]><!-- -->
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<!--<![endif]-->
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
				<meta name="viewport" content="width=device-width, initial-scale=1">
				<style type="text/css">
					#outlook a {
						padding: 0;
					}
					body {
						margin: 0;
						padding: 0;
						-webkit-text-size-adjust: 100%;
						-ms-text-size-adjust: 100%;
					}
					table,td {
						border-collapse: collapse;
						mso-table-lspace: 0pt;
						mso-table-rspace: 0pt;
					}
					img {
						border: 0;
						height: auto;
						line-height: 100%;
						outline: none;
						text-decoration: none;
						-ms-interpolation-mode: bicubic;
					}
					p {
						display: block;
						margin: 13px 0;
						line-height: 24px;
					}
					a.hover:hover {
						color: '.$skin.' !important;
					}
					/* Ar-Style */
					.rtl-css {
						text-align: right !important;
					}
				</style>
				<!--[if mso]>
				<xml>
					<o:OfficeDocumentSettings>
					<o:AllowPNG/>
					<o:PixelsPerInch>96</o:PixelsPerInch>
					</o:OfficeDocumentSettings>
				</xml>
				<![endif]-->
				<!--[if lte mso 11]>
				<style type="text/css">
					.mj-outlook-group-fix {
						width:100% !important;
					}
				</style>
				<![endif]-->
				<!--[if !mso]><!-->
				<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet" type="text/css">
				<style type="text/css">
					@import url(https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap);
				</style>
				<!--<![endif]-->
				<style type="text/css">
				@media only screen and (min-width:480px) {
					.mj-column-per-100 {
						width: 100% !important;
						max-width: 100%;
					}
				}
				</style>
				<style type="text/css">
				@media only screen and (max-width:480px) {
					table.mj-full-width-mobile {
						width: 100% !important;
					}
					td.mj-full-width-mobile {
						width: auto !important;
					}
					.wrapper {
						margin: 0 10px 0 10px !important;
					}
					p {
						line-height: 26px !important;
					}
				}
				</style>
			</head>
			<body style="background-color:#eeeeee;">
				<div style="background-color:#eeeeee;">
					<!--[if mso | IE]>
					<table align="center" border="0" cellpadding="0" cellspacing="0" style="width:600px;" width="600">
						<tr>
							<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
								<![endif]-->
								<div style="margin:0px auto;max-width:600px;">
									<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
										<tbody>
											<tr>
												<td style="direction:'.($is_rtl?'rtl':'ltr').';font-size:0px;padding:20px 0;text-align:center;">
													<!--[if mso | IE]>
													<table role="presentation" border="0" cellpadding="0" cellspacing="0">
														'.($email_style == "style_2"?'<tr>
															<td width="600px">
																<table align="center" border="0" cellpadding="0" cellspacing="0" style="width:600px;" width="600">
																	<tr>
																		<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
																			<![endif]-->
																			<div style="margin:0px auto;border-radius:12px 12px 0 0;max-width:600px;">
																				<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;border-radius:12px 12px 0 0;">
																					<tbody>
																						<tr>
																							<td style="direction:'.($is_rtl?'rtl':'ltr').';font-size:0px;padding:0 0 0 0;text-align:center;">
																								<!--[if mso | IE]>
																								<table role="presentation" border="0" cellpadding="0" cellspacing="0">
																									<tr>
																										<td style="vertical-align:top;width:600px;">
																											<![endif]-->
																											<div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:'.($is_rtl?'rtl':'ltr').';display:inline-block;vertical-align:top;width:100%;">
																												<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
																													<tbody>
																														<tr>
																															<td style="vertical-align:top;padding:0 0 30px 0;">
																																<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
																																	<tr>
																																		<td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;">
																																			<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;border-spacing:0px;">
																																				<tbody>
																																					<tr>
																																						<td style="width:140px;">
																																							<a href="'.esc_url(home_url('/')).'" target="_blank">'.($logo_email_template != ''?'<img style="border:0;display:block;outline:none;text-decoration:none;height:auto;width:100%;font-size:13px;" width="140" height="auto" alt="'.esc_attr(get_option('blogname')).'" src="'.$logo_email_template.'">':'').'</a>
																																						</td>
																																					</tr>
																																				</tbody>
																																			</table>
																																		</td>
																																	</tr>
																																</table>
																															</td>
																														</tr>
																													</tbody>
																												</table>
																											</div>
																											<!--[if mso | IE]>
																										</td>
																									</tr>
																								</table>
																								<![endif]-->
																							</td>
																						</tr>
																					</tbody>
																				</table>
																			</div>
																			<!--[if mso | IE]>
																		</td>
																	</tr>
																</table>
															</td>
														</tr>':'').'
														<tr>
															<td width="600px">
																<table align="center" border="0" cellpadding="0" cellspacing="0" style="width:600px;" width="600">
																	<tr>
																		<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
																			<![endif]-->
																			<div style="background:#ffffff;background-color:#ffffff;margin:0px auto;'.($email_style == "style_2"?"border-radius:12px;":"").'max-width:600px;" class="wrapper">
																				<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#ffffff;background-color:#ffffff;width:100%;'.($email_style == "style_2"?"border-radius:12px;":"border:solid 1px #d9d9d9;").'">
																					<tbody>
																						<tr>
																							<td style="direction:'.($is_rtl?'rtl':'ltr').';font-size:0px;padding:0;text-align:center;">
																								<!--[if mso | IE]>
																								<table role="presentation" border="0" cellpadding="0" cellspacing="0">
																									<tr>
																										<td style="vertical-align:top;width:600px;">
																											<![endif]-->
																											<div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:'.($is_rtl?'rtl':'ltr').';display:inline-block;vertical-align:top;width:100%;"'.($is_rtl?' class="rtl-css"':'').'>
																												<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
																													<tbody>
																														<tr>
																															<td style="vertical-align:top;'.($email_style == "style_2"?'padding-top: 20px;':'padding: 20px;').'">
																																<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
																																	'.($email_style == "style_2"?'':'
																																	<tr style="padding:0 20px;width:100%;background-color:'.$background_email.';">
																																		<td style="vertical-align:top;width:600px;">
																																			<div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:'.($is_rtl?'rtl':'ltr').';display:inline-block;vertical-align:top;width:100%;">
																																				<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
																																					<tbody>
																																						<tr>
																																							<td style="vertical-align:top;">
																																								<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
																																									<tr>
																																										<td align="center" style="font-size:0px;padding:30px;word-break:break-word;">
																																											<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;border-spacing:0px;">
																																												<tbody>
																																													<tr>
																																														<td style="width:140px;">
																																															<a href="'.esc_url(home_url('/')).'" target="_blank">'.($logo_email_template != ''?'<img style="border:0;display:block;outline:none;text-decoration:none;height:auto;width:100%;font-size:13px;" width="140" height="auto" alt="'.esc_attr(get_option('blogname')).'" src="'.$logo_email_template.'">':'').'</a>
																																														</td>
																																													</tr>
																																												</tbody>
																																											</table>
																																										</td>
																																									</tr>
																																								</table>
																																							</td>
																																						</tr>
																																					</tbody>
																																				</table>
																																			</div>
																																		</td>
																																	</tr>').'
																																	'.($mail == 'email_custom_mail' && $custom_image_mail != ''?'<tr>
																																	<td style="line-height:32px;padding:20px 20px 20px;text-align:center;" valign="baseline"><a href="'.esc_url(home_url('/')).'" target="_blank">'.($custom_image_mail != ''?'<img alt="'.esc_attr(get_option('blogname')).'" src="'.$custom_image_mail.'">':'').'</a></td>
																																	</tr>':'').'
																																	<tr>
																																		<td align="left" style="font-size:0px;padding:10px '.($email_style == "style_2"?"25px":"0").' 20px;word-break:break-word;"'.($is_rtl?' class="rtl-css"':'').'>
																																			<div style="font-family:Roboto, sans-serif;font-size:14px;line-height:25px;text-align:'.($is_rtl?'right':'left').';color:#000000;"'.($is_rtl?' class="rtl-css"':'').'>
																																				'.$content.'
																																			</div>
																																		</td>
																																	</tr>
																																	'.(isset($recent_questions) && $recent_questions != ''?$recent_questions:'').
																																	($schedule != ''?(isset($schedule_content) && $schedule_content != ''?wpqa_send_mail(array('content' => $schedule_content)):''):'').'
																																</table>
																															</td>
																														</tr>
																													</tbody>
																												</table>
																											</div>
																											<!--[if mso | IE]>
																										</td>
																									</tr>
																								</table>
																								<![endif]-->
																							</td>
																						</tr>
																					</tbody>
																				</table>
																			</div>
																			<!--[if mso | IE]>
																		</td>
																	</tr>
																</table>
															</td>
														</tr>
														'.($active_footer_email == 'on'?'
															'.(isset($social_td) && $social_td != ''?'
															<tr>
																<td width="600px">
																	<table align="center" border="0" cellpadding="0" cellspacing="0" style="width:600px;" width="600">
																		<tr>
																			<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
																				<![endif]-->
																				<div style="margin:0px auto;max-width:600px;">
																					<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
																						<tbody>
																							<tr>
																								<td style="direction:'.($is_rtl?'rtl':'ltr').';font-size:0px;padding:0;text-align:center;">
																									<!--[if mso | IE]>
																									<table role="presentation" border="0" cellpadding="0" cellspacing="0">
																										<tr>
																											<td style="vertical-align:top;width:600px;">
																												<![endif]-->
																												<div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:'.($is_rtl?'rtl':'ltr').';display:inline-block;vertical-align:top;width:100%;">
																													<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
																														<tbody>
																															<tr>
																																<td style="vertical-align:top;padding-top:20px;">
																																	<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
																																		<tr>
																																			<td align="left" style="font-size:0px;padding:10px 25px;padding-bottom:5px;word-break:break-word;">
																																				<table cellpadding="0" cellspacing="0" width="100%" border="0" style="color:#000000;font-family:Roboto, sans-serif;font-size:13px;line-height:22px;table-layout:auto;width:100%;border:none;">
																																				<th style="padding:0">'.$social_td.'</th>
																																				</table>
																																			</td>
																																		</tr>
																																	</table>
																																</td>
																															</tr>
																														</tbody>
																													</table>
																												</div>
																												<!--[if mso | IE]>
																											</td>
																										</tr>
																									</table>
																									<![endif]-->
																								</td>
																							</tr>
																						</tbody>
																					</table>
																				</div>
																				<!--[if mso | IE]>
																			</td>
																		</tr>
																	</table>
																</td>
															</tr>
															':'').
															($copyrights_for_email != ""?'
															<tr>
																<td width="600px">
																	<table align="center" border="0" cellpadding="0" cellspacing="0" style="width:600px;" width="600">
																		<tr>
																			<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
																				<![endif]-->
																				<div style="margin:0px auto;max-width:600px;">
																					<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
																						<tbody>
																							<tr>
																								<td style="direction:'.($is_rtl?'rtl':'ltr').';font-size:0px;padding:0;text-align:center;">
																									<!--[if mso | IE]>
																									<table role="presentation" border="0" cellpadding="0" cellspacing="0">
																										<tr>
																											<td style="vertical-align:top;width:600px;">
																												<![endif]-->
																												<div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:'.($is_rtl?'rtl':'ltr').';display:inline-block;vertical-align:top;width:100%;"'.($is_rtl?' class="rtl-css"':'').'>
																													<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
																														<tbody>
																															<tr>
																																<td style="vertical-align:top;padding-top:0;">
																																	<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
																																		<tr>
																																			<td align="center" style="font-size:0px;padding:0;padding-bottom:20px;word-break:break-word;">
																																				<div style="font-family:Roboto, sans-serif;font-size:14px;line-height:25px;text-align:center;color:#707478;">
																																					<p>'.$copyrights_for_email.'</p>
																																				</div>
																																			</td>
																																		</tr>
																																	</table>
																																</td>
																															</tr>
																														</tbody>
																													</table>
																												</div>
																												<!--[if mso | IE]>
																											</td>
																										</tr>
																									</table>
																									<![endif]-->
																								</td>
																							</tr>
																						</tbody>
																					</table>
																				</div>
																				<!--[if mso | IE]>
																			</td>
																		</tr>
																	</table>
																</td>
															</tr>
															':'').'
														':'').'
													</table>
													<![endif]-->
												</td>
											</tr>
										</tbody>
									</table>
								</div>
								<!--[if mso | IE]>
							</td>
						</tr>
					</table>
					<![endif]-->
				</div>
			</body>
		</html>';
	}
endif;
/* Send admin notification */
if (!function_exists('wpqa_send_admin_notification')) :
	function wpqa_send_admin_notification($post_id,$post_title) {
		$blogname = get_option('blogname');
		$email = get_option('admin_email');
		$headers = "MIME-Version: 1.0\r\n" . "From: ".$blogname." "."<".$email.">\n" . "Content-Type: text/HTML; charset=\"" . get_option('blog_charset') . "\"\r\n";
		$message = esc_html__('Hello there,','wpqa').'<br/><br/>'. 
		esc_html__('A new post has been submitted in','wpqa').' '.$blogname.' site. '.esc_html__('Please find details below:','wpqa').'<br/><br/>'.
		
		'Post title: '.$post_title.'<br/><br/>';
		$post_author_name = get_post_meta($post_id,'ap_author_name',true);
		$post_author_email = get_post_meta($post_id,'ap_author_email',true);
		$post_author_url = get_post_meta($post_id,'ap_author_url',true);
		if ($post_author_name != ''){
			$message .= 'Post Author Name: '.$post_author_name.'<br/><br/>';
		}
		if ($post_author_email != ''){
			$message .= 'Post Author Email: '.$post_author_email.'<br/><br/>';
		}
		if ($post_author_url != ''){
			$message .= 'Post Author URL: '.$post_author_url.'<br/><br/>';
		}
		
		$message .= '____<br/><br/>
		'.esc_html__('To take action (approve/reject)- please go here:','wpqa').'<br/>'
		.admin_url().'post.php?post='.$post_id.'&action=edit <br/><br/>
		
		'.esc_html__('Thank You','wpqa');
		$title = esc_html__('New Post Submission','wpqa');
		wp_mail($email,$title,$message,$headers);
	}
endif;
/* Schedule mail */
function wpqa_schedule_mails($schedule) {
	$question_schedules = wpqa_options("question_schedules");
	if ($question_schedules == "on") {
		$question_schedules_groups = wpqa_options("question_schedules_groups");
		$email_title = wpqa_options("title_question_schedules");
		$email_title = ($email_title != ""?$email_title:esc_html__("Recent questions","wpqa"));
		$users = get_users(array("meta_query" => array("relation" => "AND",array("key" => "question_schedules","compare" => "=","value" => "on"),array('relation' => 'OR',array("key" => "unsubscribe_mails","compare" => "NOT EXISTS"),array("key" => "unsubscribe_mails","compare" => "!=","value" => "on"))),"role__not_in" => array("wpqa_under_review","activation","ban_group"),"role__in" => (isset($question_schedules_groups) && is_array($question_schedules_groups)?$question_schedules_groups:array()),"fields" => array("ID","user_email","display_name")));
		if (isset($users) && is_array($users) && !empty($users)) {
			foreach ($users as $key => $value) {
				$user_id = $value->ID;
				$send_text = wpqa_send_mail(
					array(
						'content'          => wpqa_options("email_question_schedules"),
						'received_user_id' => $user_id,
					)
				);
				$email_title = wpqa_send_mail(
					array(
						'content'          => $email_title,
						'title'            => true,
						'break'            => '',
						'received_user_id' => $user_id,
					)
				);
				$last_message_email = wpqa_email_code($send_text,"email_question_schedules",$schedule,$user_id);
				if ($last_message_email != "no_question") {
					wpqa_send_mails(
						array(
							'toEmail'     => esc_html($value->user_email),
							'toEmailName' => esc_html($value->display_name),
							'title'       => $email_title,
							'message'     => $last_message_email,
							'email_code'  => 'code',
						)
					);
				}
			}
		}
	}
}
/* Cron schedules */
add_filter("cron_schedules","wpqa_cron_schedules");
if (!function_exists('wpqa_cron_schedules')) :
	function wpqa_cron_schedules($schedules) {
		$schedules['weekly'] = array(
			'interval' => WEEK_IN_SECONDS,
			'display'  => esc_html__('Once Weekly','wpqa'),
		);
		$schedules['monthly'] = array(
			'interval' => MONTH_IN_SECONDS,
			'display'  => esc_html__('Once Monthly','wpqa'),
		);
		return $schedules;
	}
endif;
/* Schedule mails */
add_action("wpqa_init","wpqa_action_schedule_mails");
function wpqa_action_schedule_mails() {
	$question_schedules = wpqa_options("question_schedules");
	if ($question_schedules == "on") {
		$wpqa_schedules_time = get_option("wpqa_schedules_time");
		$time_hour_option = get_option("schedules_time_hour");
		$time_day_option = get_option("schedules_time_day");

		$schedules_time_hour = wpqa_options("schedules_time_hour");
		$schedules_time_day = wpqa_options("schedules_time_day");
		if ($wpqa_schedules_time == "") {
			wp_clear_scheduled_hook("wpqa_scheduled_mails_daily");
			wp_clear_scheduled_hook("wpqa_scheduled_mails_weekly");
			wp_clear_scheduled_hook("wpqa_scheduled_mails_monthly");
			$wpqa_schedules_time = ($schedules_time_day != "" && $schedules_time_hour != ""?strtotime(date("Y").'-'.date("m")." next ".$schedules_time_day." ".$schedules_time_hour.":00"):time());
			update_option("wpqa_schedules_time",$wpqa_schedules_time);
		}
		
		if (!wp_next_scheduled('wpqa_scheduled_mails_daily')) {
			wp_schedule_event($wpqa_schedules_time,'daily','wpqa_scheduled_mails_daily');
		}
		if (!wp_next_scheduled('wpqa_scheduled_mails_weekly')) {
			wp_schedule_event($wpqa_schedules_time,'weekly','wpqa_scheduled_mails_weekly');
		}
		if (!wp_next_scheduled('wpqa_scheduled_mails_monthly')) {
			wp_schedule_event($wpqa_schedules_time,'monthly','wpqa_scheduled_mails_monthly');
		}
	}
}
/* Daily mails */
add_action('wpqa_scheduled_mails_daily','wpqa_scheduled_mails_daily');
function wpqa_scheduled_mails_daily() {
	$question_schedules = wpqa_options("question_schedules");
	$question_schedules_time = wpqa_options("question_schedules_time");
	if ($question_schedules == "on" && is_array($question_schedules_time) && in_array("daily",$question_schedules_time)) {
		wpqa_schedule_mails("daily");
	}
}
/* Weekly mails */
add_action('wpqa_scheduled_mails_weekly','wpqa_scheduled_mails_weekly');
function wpqa_scheduled_mails_weekly() {
	$question_schedules = wpqa_options("question_schedules");
	$question_schedules_time = wpqa_options("question_schedules_time");
	if ($question_schedules == "on" && is_array($question_schedules_time) && in_array("weekly",$question_schedules_time)) {
		wpqa_schedule_mails("weekly");
	}
}
/* Monthly mails */
add_action('wpqa_scheduled_mails_monthly','wpqa_scheduled_mails_monthly');
function wpqa_scheduled_mails_monthly() {
	$question_schedules = wpqa_options("question_schedules");
	$question_schedules_time = wpqa_options("question_schedules_time");
	if ($question_schedules == "on" && is_array($question_schedules_time) && in_array("monthly",$question_schedules_time)) {
		wpqa_schedule_mails("monthly");
	}
}?>