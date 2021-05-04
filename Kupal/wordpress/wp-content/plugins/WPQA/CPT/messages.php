<?php

/* @author    2codeThemes
*  @package   WPQA/CPT
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* Message post type */
function wpqa_message_post_types_init() {
	$active_message = wpqa_options('active_message');
	if ($active_message == "on") {
	    register_post_type( 'message',
	        array(
		     	'label' => esc_html__('Messages','wpqa'),
		        'labels' => array(
					'name'               => esc_html__('Messages','wpqa'),
					'singular_name'      => esc_html__('Messages','wpqa'),
					'menu_name'          => esc_html__('Messages','wpqa'),
					'name_admin_bar'     => esc_html__('Messages','wpqa'),
					'add_new'            => esc_html__('Add New','wpqa'),
					'add_new_item'       => esc_html__('Add New Message','wpqa'),
					'new_item'           => esc_html__('New Message','wpqa'),
					'edit_item'          => esc_html__('Edit Message','wpqa'),
					'view_item'          => esc_html__('View Message','wpqa'),
					'view_items'         => esc_html__('View Messages','wpqa'),
					'all_items'          => esc_html__('All Messages','wpqa'),
					'search_items'       => esc_html__('Search Messages','wpqa'),
					'parent_item_colon'  => esc_html__('Parent Message:','wpqa'),
					'not_found'          => esc_html__('No Messages Found.','wpqa'),
					'not_found_in_trash' => esc_html__('No Messages Found in Trash.','wpqa'),
				),
		        'description'         => '',
		        'public'              => false,
		        'show_ui'             => true,
		        'capability_type'     => 'post',
		        'capabilities'        => array('create_posts' => 'do_not_allow'),
		        'map_meta_cap'        => true,
		        'publicly_queryable'  => false,
		        'exclude_from_search' => false,
		        'hierarchical'        => false,
		        'query_var'           => false,
		        'show_in_rest'        => false,
		        'has_archive'         => false,
				'menu_position'       => 5,
				'menu_icon'           => "dashicons-email",
		        'supports'            => array('title','editor'),
	        )
	    );
	}
}
add_action( 'wpqa_init', 'wpqa_message_post_types_init', 0 );
function wpqa_message_updated_messages($messages) {
  global $post_ID;
  $messages['message'] = array(
    0 => '',
    1 => '',
  );
  return $messages;
}
add_filter('post_updated_messages','wpqa_message_updated_messages');
/* Admin columns for post types */
function wpqa_message_columns($old_columns){
	$columns = array();
	$columns["cb"]       = "<input type=\"checkbox\">";
	$columns["title"]    = esc_html__("Title","wpqa");
	$columns["content"]  = esc_html__("Content","wpqa");
	$columns["author_m"] = esc_html__("Author","wpqa");
	$columns["to_user"]  = esc_html__("To user/groups/s","wpqa");
	$columns["date"]     = esc_html__("Date","wpqa");
	$columns["delete"]   = esc_html__("User deleted?","wpqa");
	return $columns;
}
add_filter('manage_edit-message_columns', 'wpqa_message_columns');
function wpqa_message_custom_columns($column) {
	global $post;
	$to_user = get_post_meta($post->ID,'message_user_id',true);
	$message_groups_array = get_post_meta($post->ID,'message_groups_array',true);
	$message_user_array = get_post_meta($post->ID,'message_user_array',true);
	$to_user_array = (is_array($message_user_array) && !empty($message_user_array)?$message_user_array:($to_user != ""?array($to_user):""));
	switch ( $column ) {
		case 'author_m' :
			$display_name = get_the_author_meta('display_name',$post->post_author);
			if ($post->post_author > 0) {
				echo '<a href="edit.php?post_type=message&author='.$post->post_author.'">'.$display_name.'</a>';
			}else {
				echo get_post_meta($post->ID,'message_username',true)."<br>".get_post_meta($post->ID,'message_email',true);
			}
		break;
		case 'content' :
			echo esc_html($post->post_content);
		break;
		case 'to_user' :
			if (is_array($message_groups_array) && !empty($message_groups_array)) {
				foreach ($message_groups_array as $key => $value) {
					if ($value != '0') {
						echo ucfirst(str_ireplace("_"," ",$key)).'<br>';
					}
				}
			}else {
				if (is_array($to_user_array) && !empty($to_user_array)) {
					foreach ($to_user_array as $value) {
						$display_name_user = get_the_author_meta('display_name',$value);
						echo '<a href="'.get_author_posts_url($value).'">'.$display_name_user.'</a><br>';
					}
				}
			}
		break;
		case 'delete' :
			$delete_send_message = get_post_meta($post->ID,"delete_send_message",true);
			$delete_inbox_message = get_post_meta($post->ID,"delete_inbox_message",true);
			$message_user_array = get_post_meta($post->ID,'message_user_array',true);
			if ($delete_inbox_message == 1 || $delete_inbox_message == "on") {
				if (is_array($message_user_array)) {
					esc_html_e("One of the users has deleted his inbox message.","wpqa").'<br>';
				}else {
					$display_name_user = get_the_author_meta('display_name',$to_user);
					echo '<a href="'.get_author_posts_url($to_user).'">'.$display_name_user.'</a> '.esc_html__("has deleted his inbox message.","wpqa").'<br>';
				}
			}
			if ($delete_send_message == 1 || $delete_inbox_message == 1 || $delete_send_message == "on" || $delete_inbox_message == "on") {
				if (($delete_send_message == 1 && $delete_inbox_message == 1) || ($delete_send_message == "on" && $delete_inbox_message == "on")) {
					echo '<br>';
				}
				if ($delete_send_message == 1 || $delete_send_message == "on") {
					$display_name = get_the_author_meta('display_name',$post->post_author);
					echo '<a href="'.get_author_posts_url($post->post_author).'">'.$display_name.'</a> '.esc_html__("has delete his sent message.","wpqa");
				}
			}
			if ($delete_inbox_message != 1 && $delete_send_message != 1 && $delete_inbox_message != "on" && $delete_send_message != "on") {
				echo '<span aria-hidden="true">—</span><span class="screen-reader-text">'.esc_html__("Message has not been deleted","wpqa").'</span>';
			}
		break;
	}
}
add_action('manage_message_posts_custom_column','wpqa_message_custom_columns',2);
/* Send message shortcode */
function wpqa_send_message_shortcode($atts, $content = null) {
	global $message_add;
	$a = shortcode_atts( array(
	    'popup' => '',
	), $atts );
	$out = '';
	$send_message = wpqa_options("send_message");
	$send_message_no_register = wpqa_options("send_message_no_register");
	$custom_permission = wpqa_options("custom_permission");
	$user_id = get_current_user_id();
	
	if (is_user_logged_in()) {
		$user_is_login = get_userdata($user_id);
		$user_login_group = (is_array($user_is_login->caps)?key($user_is_login->caps):"");
		$roles = $user_is_login->allcaps;
	}
	
	if (($custom_permission != "on" && ((isset($user_login_group) && $user_login_group == "wpqa_under_review") || (isset($user_login_group) && $user_login_group == "activation"))) || ($custom_permission == "on" && (is_user_logged_in() && !is_super_admin($user_id) && empty($roles["send_message"])) || (!is_user_logged_in() && $send_message != "on"))) {
		$out .= '<div class="alert-message error"><i class="icon-cancel"></i><p>'.esc_html__("Sorry, you do not have a permission to send message.","wpqa").' '.wpqa_paid_subscriptions().'</p></div>';
		if (!is_user_logged_in()) {
			$out .= do_shortcode("[wpqa_login]");
		}
	}else if (!is_user_logged_in() && $send_message_no_register != "on") {
		$out .= '<div class="alert-message error"><i class="icon-cancel"></i><p>'.esc_html__("You must login to send a message.","wpqa").'</p></div>'.do_shortcode("[wpqa_login register_2='yes']");
	}else {
		$post_type = (isset($_POST["post_type"]) && $_POST["post_type"] != ""?esc_html($_POST["post_type"]):"");
		$get_user_id = 0;
		if (wpqa_is_user_profile()) {
			$get_user_id = (int)get_query_var(apply_filters('wpqa_user_id','wpqa_user_id'));
		}
		
		if (!wpqa_is_user_messages() && is_user_logged_in() && $user_id == $get_user_id && $get_user_id > 0) {
			echo '<div class="alert-message error"><i class="icon-cancel"></i><p>'.esc_html__("You can't send message for yourself.","wpqa").'</p></div>';
		}else {
			$comment_message = wpqa_options("comment_message");
			$editor_message_details = wpqa_options("editor_message_details");
			$rand = rand(1,1000);
			$out .= '
			<form class="form-post wpqa_form" method="post" enctype="multipart/form-data">'.apply_filters('wpqa_new_message','').'
				<div class="form-inputs clearfix">';
					if (!is_user_logged_in() && $send_message_no_register == "on") {
						$out .= '<p>
							<label for="message-username-'.$rand.'">'.esc_html__("Username","wpqa").'<span class="required">*</span></label>
							<input name="username" id="message-username-'.$rand.'" class="the-username" type="text" value="'.(isset($message_add['username'])?$message_add['username']:'').'">
							<i class="icon-user"></i>
							<span class="form-description">'.esc_html__("Please type your username.","wpqa").'</span>
						</p>
						
						<p>
							<label for="message-email-'.$rand.'">'.esc_html__("E-Mail","wpqa").'<span class="required">*</span></label>
							<input name="email" id="message-email-'.$rand.'" class="the-email" type="text" value="'.(isset($message_add['email'])?$message_add['email']:'').'">
							<i class="icon-mail"></i>
							<span class="form-description">'.esc_html__("Please type your E-Mail.","wpqa").'</span>
						</p>';
					}
					$out .= '<p>
						<label for="message-title-'.$rand.'">'.esc_html__("Message Title","wpqa").'<span class="required">*</span></label>
						<input name="title" id="message-title-'.$rand.'" class="the-title" type="text" value="'.(isset($message_add['title'])?wpqa_kses_stip($message_add['title']):"").'">
						<i class="icon-chat"></i>
					</p>
					
					<div class="wpqa_textarea'.($editor_message_details == "on"?"":" wpqa_textarea_p").'">
						<label for="message-details-'.$rand.'">'.apply_filters("wpqa_filter_details_message",esc_html__("Details","wpqa")).($comment_message == "on"?'<span class="required">*</span>':'').'</label>';
						if ($editor_message_details == "on") {
							$settings = array("textarea_name" => "comment","media_buttons" => true,"textarea_rows" => 10);
							$settings = apply_filters('wpqa_message_editor_setting',$settings);
							ob_start();
							wp_editor((isset($message_add['comment'])?wpqa_kses_stip($message_add['comment'],"yes"):""),'message-details-'.$rand,$settings);
							$editor_contents = ob_get_clean();
							$out .= '<div class="the-details the-textarea">'.$editor_contents.'</div>';
						}else {
							$out .= '<textarea name="comment" id="message-details-'.$rand.'" class="the-textarea" aria-required="true" cols="58" rows="8">'.(isset($message_add['comment'])?wpqa_kses_stip($message_add['comment'],"yes"):"").'</textarea>
							<i class="icon-pencil"></i>';
						}
						$out .= '<span class="form-description">'.esc_html__("Type the description thoroughly and in details.","wpqa").'</span>
					</div>';
					
					$out .= wpqa_add_captcha(wpqa_options("the_captcha_message"),"message",$rand);
					
				$out .= '</div>
				
				<p class="form-submit">
					<input type="hidden" name="post_type" value="send_message">
					<input type="hidden" name="wpqa_message_nonce" value="'.wp_create_nonce("wpqa_message_nonce").'">';

					if (isset($a["popup"]) && $a["popup"] == "popup") {
						$out .= '<input type="hidden" name="message_popup" value="popup">';
					}
					$out .= '<input type="hidden" name="form_type" value="add_message">';
					if ($get_user_id > 0) {
						$out .= '<input type="hidden" name="user_id" value="'.$get_user_id.'">';
					}
					$out .= '<input type="submit" value="'.esc_html__("Send Your Message","wpqa").'" class="button-default send-message button-hide-click">
					<span class="load_span"><span class="loader_2"></span></span>
				</p>
			
			</form>';
		}
	}
	return $out;
}
/* New message */
function wpqa_new_message() {
	if (isset($_POST["form_type"]) && $_POST["form_type"] == "add_message") :
		$return = wpqa_process_new_messages();
		if (is_wp_error($return)) :
			return '<div class="wpqa_error">'.$return->get_error_message().'</div>';
		else :
			$get_post = get_post($return);
			if ($get_post->post_type == "message") {
				$user_id = get_current_user_id();
				$get_message_user = get_post_meta($return,"message_user_id",true);
				$message_publish = wpqa_options("message_publish");
				$send_email_message = wpqa_options("send_email_message");
				if (is_super_admin($user_id) || $message_publish == "publish") {
					if ($user_id != $get_message_user) {
						$message_username = get_post_meta($return,'message_username',true);
						if ($get_post->post_author != $get_message_user && $get_message_user > 0) {
							$header_messages = wpqa_options("header_messages");
							$header_style = wpqa_options("header_style");
							$show_message_area = ($header_messages == "on" && $header_style == "simple"?"on":0);
							wpqa_notifications_activities($get_message_user,$get_post->post_author,($get_post->post_author == 0?$message_username:""),"","","add_message_user","notifications","","message",($show_message_area === "on"?false:true));
						}
						if ($user_id > 0) {
							wpqa_notifications_activities($user_id,$get_message_user,"","","","add_message","activities","","message");
						}
						
						if ($send_email_message == "on") {
							$user = get_userdata($get_message_user);
							$send_text = wpqa_send_mail(
								array(
									'content'          => wpqa_options("email_new_message"),
									'user_id'          => $get_message_user,
									'post_id'          => $return,
									'sender_user_id'   => $get_post->post_author,
									'received_user_id' => $user->ID,
								)
							);
							$email_title = wpqa_options("title_new_message");
							$email_title = ($email_title != ""?$email_title:esc_html__("New message","wpqa"));
							$email_title = wpqa_send_mail(
								array(
									'content'          => $email_title,
									'title'            => true,
									'break'            => '',
									'user_id'          => $get_message_user,
									'post_id'          => $return,
									'sender_user_id'   => $get_post->post_author,
									'received_user_id' => $user->ID,
								)
							);
							$unsubscribe_mails = get_the_author_meta('unsubscribe_mails',$user->ID);
							$send_message_mail = get_the_author_meta('send_message_mail',$user->ID);
							if ($unsubscribe_mails != "on" && $send_message_mail == "on") {
								wpqa_send_mails(
									array(
										'toEmail'     => esc_html($user->user_email),
										'toEmailName' => esc_html($user->display_name),
										'title'       => $email_title,
										'message'     => $send_text,
									)
								);
							}
						}
					}
					wpqa_session('<div class="alert-message success"><i class="icon-check"></i><p>'.esc_html__("The message has been sent successfully.","wpqa").'</p></div>','wpqa_session');
				}else {
					wpqa_session('<div class="alert-message success"><i class="icon-check"></i><p>'.esc_html__("Your message has been sent successfully, The message is under review.","wpqa").'</p></div>','wpqa_session');
				}
				$redirect_url = apply_filters("wpqa_filter_message_redirect",(wpqa_is_user_messages()?wpqa_get_profile_permalink($user_id,'messages'):wpqa_profile_url($get_message_user)),$return);
				wp_redirect(esc_url($redirect_url));
				exit;
			}
			exit;
		endif;
	endif;
}
add_filter('wpqa_new_message','wpqa_new_message');
/* Process new messages */
function wpqa_process_new_messages() {
	global $message_add;
	set_time_limit(0);
	$errors = new WP_Error();
	$message_add = array();
	
	$post_type = (isset($_POST["post_type"]) && $_POST["post_type"] != ""?$_POST["post_type"]:"");
	
	if ($post_type == "send_message") {
		$fields = array(
			'title','comment','wpqa_captcha','username','email','user_id'
		);

		$fields = apply_filters('wpqa_add_message_fields',$fields);
		
		foreach ($fields as $field) :
			if (isset($_POST[$field])) $message_add[$field] = $_POST[$field]; else $message_add[$field] = '';
		endforeach;

		if (!isset($_POST['wpqa_message_nonce']) || !wp_verify_nonce($_POST['wpqa_message_nonce'],'wpqa_message_nonce')) {
			$errors->add('nonce-error','<strong>'.esc_html__("Error","wpqa").' :&nbsp;</strong> '.esc_html__("There is an error, Please reload the page and try again.","wpqa"));
		}
		
		$custom_permission = wpqa_options("custom_permission");
		$send_message_no_register = wpqa_options("send_message_no_register");
		$send_message = wpqa_options("send_message");
		$user_id = get_current_user_id();
		if (is_user_logged_in()) {
			$user_is_login = get_userdata($user_id);
			$roles = $user_is_login->allcaps;
		}
		
		if (($custom_permission == "on" && is_user_logged_in() && !is_super_admin($user_id) && empty($roles["send_message"])) || ($custom_permission == "on" && !is_user_logged_in() && $send_message != "on")) {
			$errors->add('required','<strong>'.esc_html__("Error","wpqa").' :&nbsp;</strong> '.esc_html__("Sorry, you do not have a permission to send message.","wpqa"));
			if (!is_user_logged_in()) {
				$errors->add('required','<strong>'.esc_html__("Error","wpqa").' :&nbsp;</strong> '.esc_html__("You must login to send a message.","wpqa"));
			}
		}else if (!is_user_logged_in() && $send_message_no_register != "on") {
			$errors->add('required','<strong>'.esc_html__("Error","wpqa").' :&nbsp;</strong> '.esc_html__("You must login to send a message.","wpqa"));
		}else if ($message_add['user_id'] == $user_id) {
			$errors->add('required','<strong>'.esc_html__("Error","wpqa").' :&nbsp;</strong> '.esc_html__("You can't send message for yourself.","wpqa"));
		}else if ($message_add['user_id'] == "") {
			$errors->add('required','<strong>'.esc_html__("Error","wpqa").' :&nbsp;</strong> '.esc_html__("There is an error.","wpqa"));
		}
		
		if (!is_user_logged_in() && $send_message_no_register == "on" && $user_id == 0) {
			if (empty($message_add['username'])) $errors->add('required-field','<strong>'.esc_html__("Error","wpqa").' :&nbsp;</strong> '.esc_html__("There are required fields (username).","wpqa"));
			if (empty($message_add['email'])) $errors->add('required-field','<strong>'.esc_html__("Error","wpqa").' :&nbsp;</strong> '.esc_html__("There are required fields (email).","wpqa"));
			if (!is_email($message_add['email'])) $errors->add('required-field','<strong>'.esc_html__("Error","wpqa").' :&nbsp;</strong> '.esc_html__("Please write correctly email.","wpqa"));
		}
		
		/* Validate Required Fields */
		
		if (empty($message_add['title'])) $errors->add('required-field','<strong>'.esc_html__("Error","wpqa").' :&nbsp;</strong> '.esc_html__("There are required fields (title).","wpqa"));
		
		$comment_message = wpqa_options("comment_message");
		if ($comment_message == "on") {
			if (empty($message_add['comment'])) $errors->add('required-field','<strong>'.esc_html__("Error","wpqa").' :&nbsp;</strong> '.esc_html__("There are required fields (content).","wpqa"));
		}
		
		wpqa_check_captcha(wpqa_options("the_captcha_message"),"message",$message_add,$errors);

		do_action('wpqa_add_message_errors',$errors,$message_add);
		
		if (sizeof($errors->errors) > 0) return $errors;
		
		$message_publish = wpqa_options("message_publish");
		
		/* Create message */
		$data = array(
			'post_content' => ($message_add['comment']),
			'post_title'   => sanitize_text_field($message_add['title']),
			'post_status'  => ($message_publish == "publish" || is_super_admin($user_id)?"publish":"draft"),
			'post_author'  => (!is_user_logged_in() && $send_message_no_register == "on"?0:$user_id),
			'post_type'	   => 'message',
		);
		
		$post_id = wp_insert_post($data);
			
		if ($post_id == 0 || is_wp_error($post_id)) wp_die(esc_html__("Error in message.","wpqa"));
		
		if (!is_user_logged_in() && $send_message_no_register == "on" && $user_id == 0) {
			$message_username = sanitize_text_field($message_add['username']);
			$message_email = sanitize_text_field($message_add['email']);
			update_post_meta($post_id,'message_username',$message_username);
			update_post_meta($post_id,'message_email',$message_email);
		}

		update_post_meta($post_id,'message_user_id',(int)$message_add['user_id']);
		update_post_meta($post_id,'message_new',"on");
		
		do_action('wpqa_new_messages',$post_id);
		do_action('wpqa_finished_add_message',$post_id,$message_add);
	}
	if ($post_type == "send_message") {
		/* Successful */
		return $post_id;
	}
}
/* View message */
function wpqa_message_view() {
	global $post;
	$seen_message = wpqa_options("seen_message");
	$message_id = (int)$_POST["message_id"];
	$user_id = get_current_user_id();
	$the_query = new WP_Query(array("p" => $message_id,"post_type" => "message"));
	if ( $the_query->have_posts() ) {
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			$post_author = $post->post_author;
			$message_user_id = get_post_meta($message_id,'message_user_id',true);
			$message_user_array = get_post_meta($message_id,'message_user_array',true);
			$message_new = get_post_meta($message_id,'message_new',true);
			$message_not_new = get_post_meta($message_id,'message_not_new_'.$user_id,true);
			$message_not_new = (isset($message_not_new) && $message_not_new != "" && $message_not_new != "no"?$message_not_new:"no");
			if ($message_new == 1 || $message_new == "on" || $message_not_new == "no" && ($user_id == $message_user_id || (is_array($message_user_array) && !empty($message_user_array) && in_array($user_id,$message_user_array)))) {
				if (is_array($message_user_array) && !empty($message_user_array) && in_array($user_id,$message_user_array)) {
					update_post_meta($message_id,'message_not_new_'.$user_id,"yes");
				}else {
					delete_post_meta($message_id,'message_new');
				}
				if ($seen_message == "on") {
					wpqa_notifications_activities($post_author,$user_id,"","","","seen_message","notifications","","message");
				}
			}
			echo "<div>";
				the_content();
				do_action("wpqa_after_message_content",$message_id,$post_author,$user_id);
			echo "</div>";
		}
	}
	wp_reset_postdata();
	die();
}
add_action('wp_ajax_wpqa_message_view','wpqa_message_view');
add_action('wp_ajax_nopriv_wpqa_message_view','wpqa_message_view');
/* Reply message */
function wpqa_message_reply() {
	$message_id = (int)$_POST["message_id"];
	echo str_ireplace(esc_html__("RE:","wpqa")." ".esc_html__("RE:","wpqa")." ".esc_html__("RE:","wpqa")." ",esc_html__("RE:","wpqa")." ".esc_html__("RE:","wpqa")." ",esc_html__("RE:","wpqa")." ".get_the_title($message_id));
	die();
}
add_action('wp_ajax_wpqa_message_reply','wpqa_message_reply');
add_action('wp_ajax_nopriv_wpqa_message_reply','wpqa_message_reply');
/* Block message */
function wpqa_block_message() {
	check_ajax_referer('block_message_nonce','block_message_nonce');
	$user_id      = (int)$_POST["user_id"];
	$current_user = get_current_user_id();
	
	$user_block_message = get_user_meta($current_user,"user_block_message",true);
	if (empty($user_block_message)) {
		update_user_meta($current_user,"user_block_message",array($user_id));
	}else {
		update_user_meta($current_user,"user_block_message",array_merge($user_block_message,array($user_id)));
	}
	die();
}
add_action('wp_ajax_wpqa_block_message','wpqa_block_message');
add_action('wp_ajax_nopriv_wpqa_block_message','wpqa_block_message');
/* Unblock message */
function wpqa_unblock_message() {
	check_ajax_referer('block_message_nonce','block_message_nonce');
	$user_id      = (int)$_POST["user_id"];
	$current_user = get_current_user_id();
	
	$user_block_message = get_user_meta($current_user,"user_block_message",true);
	$remove_user_block_message = wpqa_remove_item_by_value($user_block_message,$user_id);
	update_user_meta($current_user,"user_block_message",$remove_user_block_message);
	die();
}
add_action('wp_ajax_wpqa_unblock_message','wpqa_unblock_message');
add_action('wp_ajax_nopriv_wpqa_unblock_message','wpqa_unblock_message');
/* Show messages li */
if (!function_exists('wpqa_get_messages')) :
	function wpqa_get_messages($user_id,$item_number,$more_button,$count = false) {
		global $post;
		$output = '';
		$time_format = wpqa_options("time_format");
		$time_format = ($time_format?$time_format:get_option("time_format"));
		$date_format = wpqa_options("date_format");
		$date_format = ($date_format?$date_format:get_option("date_format"));
		if ($count == true) {
			$num = wpqa_count_new_messages((isset($user_id)?$user_id:0));
			$num = (isset($num) && $num != "" && $num > 0?$num:0);
			if (isset($num) && $num != "" && $num > 0) {
				$num = ($num <= 99?$num:"99+");
				$output .= '<span class="notifications-number">'.$num.'</span>';
			}
		}
		$output .= '<div>
		<ul>';
		$args = array('post_type' => 'message','posts_per_page' => $item_number,"meta_query" => array('relation' => 'AND',array("key" => "delete_inbox_message","compare" => "NOT EXISTS"),array("key" => "message_user_id","compare" => "=","value" => $user_id)));
		$messages_query = new WP_Query( $args );
		if ($messages_query->have_posts()) {
			while ( $messages_query->have_posts() ) { $messages_query->the_post();
				$message_new = get_post_meta($post->ID,'message_new',true);
				$output .= '<li>
					<i class="message_new'.($message_new == 1 || $message_new == "on"?" message-new":"").' icon-mail"></i>
					<div>';
						$display_name = get_the_author_meta('display_name',$post->post_author);
						if ($post->post_author > 0) {
							$output .= '<a href="'.get_author_posts_url($post->post_author).'">'.$display_name.'</a>';
						}else {
							$output .= get_post_meta($post->ID,'message_username',true);
						}
						$output .= ' '.esc_html__("has","wpqa").' <a href="'.esc_url(wpqa_get_profile_permalink($user_id,"messages")).'">'.esc_html__("sent a message for you.","wpqa").'</a>
						<span class="notifications-date">'.sprintf(esc_html__('%1$s at %2$s','wpqa'),get_the_time($date_format),get_the_time($time_format)).'</span>
					</div>
				</li>';
			}
				
			$output .= '</ul>';
			if ($more_button == "on") {
				$output .= '<a href="'.esc_url(wpqa_get_profile_permalink($user_id,"messages")).'">'.esc_html__("Show all messages.","wpqa").'</a>';
			}
		}else {
			$output .= '<li><div>'.esc_html__("There are no messages yet.","wpqa").'</div></li></ul>';
		}
		$output .= '</div>';
		wp_reset_postdata();
		return $output;
	}
endif;?>