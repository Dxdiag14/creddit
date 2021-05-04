<?php

/* @author    2codeThemes
*  @package   WPQA/shortcodes
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

ob_start();
/* Add & Edit posts */
function wpqa_add_edit_group_posts($type,$group_id) {
	global $posts_add,$posts_edit;
	$user_id = get_current_user_id();
	$is_super_admin = is_super_admin($user_id);
	$rand = rand(1,1000);
	
	if ($type == "edit") {
		$get_posts = (int)get_query_var(apply_filters('wpqa_edit_posts_group','edit_post_group'));
		$g_posts_privacy = get_post_meta($get_posts,"posts_privacy",true);
		$get_post_p = get_post($get_posts);
		$p_sticky   = is_sticky($get_posts);
		$group_id = get_post_meta($get_posts,"group_id",true);
	}
	$group_moderators = get_post_meta($group_id,"group_moderators",true);
	
	$out = apply_filters('wpqa_add_edit_posts_before_form',false,$type,$posts_add,$posts_edit,(isset($get_posts)?$get_posts:0)).
	'<form class="form-post wpqa_form" method="post" enctype="multipart/form-data">'.(isset($_POST["form_type"]) && $_POST["form_type"] == $type."_posts"?apply_filters('wpqa_'.$type.'_group_posts',$type):"").'
		<div class="form-inputs clearfix">';
			$editor_posts_content = wpqa_options("editor_posts_content");
			$out .= '<div class="wpqa_textarea'.($editor_posts_content == "on"?"":" wpqa_textarea_p").'">
				<label for="posts-content-'.$type.'-'.$rand.'">'.esc_html__("Content","wpqa").'<span class="required">*</span></label>';
				if ($editor_posts_content == "on") {
					$settings = array("textarea_name" => "content","media_buttons" => true,"textarea_rows" => 10,array("tinymce" => array("theme_advanced_disable"=> "bold,italic,underline,bullist,numlist,link,unlink,forecolor,undo,redo")));
					$settings = apply_filters('wpqa_posts_editor_setting',$settings);
					ob_start();
					wp_editor(($type == "add" && isset($posts_add['content'])?wpqa_kses_stip($posts_add['content'],"yes","yes"):($type == "edit"?(isset($posts_edit['content'])?wpqa_kses_stip($posts_edit['content'],"yes","yes"):wpqa_kses_stip($get_post_p->post_content,"yes","yes")):"")),"posts-content-".$type.'-'.$rand,$settings);
					$editor_contents = ob_get_clean();
					$out .= '<div class="the-content the-textarea">'.$editor_contents.'</div>';
				}else {
					$out .= '<textarea name="content" id="posts-content-'.$type.'-'.$rand.'" class="the-textarea" aria-required="true" cols="58" rows="8"'.apply_filters("wpqa_posts_content_attrs",false).'>'.($type == "add" && isset($posts_add['content'])?wpqa_kses_stip($posts_add['content'],"yes"):($type == "edit"?(isset($posts_edit['content'])?wpqa_kses_stip($posts_edit['content'],"yes"):wpqa_kses_stip($get_post_p->post_content,"yes")):"")).'</textarea>
					<i class="icon-pencil"></i>';
				}
			$out .= '</div>'.
			apply_filters('wpqa_add_edit_posts_after_content',false,$type,$posts_add,$posts_edit,(isset($get_posts)?$get_posts:0));

			if (isset($posts_image)) {
				if ($type == "edit") {
					$posts_image = get_post_meta($get_posts,"posts_image",true);
					if ((is_array($posts_image) && isset($posts_image["id"])) || (!is_array($posts_image) && $posts_image != "")) {
						$out .= '<div class="clearfix"></div>
						<div class="wpqa-delete-image">
							<span class="wpqa-delete-image-span">'.wpqa_get_aq_resize_img(250,250,"",(is_array($posts_image) && isset($posts_image["id"])?$posts_image["id"]:$posts_image),"no","").'</span>
							<div class="clearfix"></div>
							<div class="button-default wpqa-remove-image" data-name="posts_image" data-type="post_meta" data-id="'.$get_posts.'" data-image="'.(is_array($posts_image) && isset($posts_image["id"]) && $posts_image["id"] != 0?$posts_image['id']:$posts_image).'" data-nonce="'.wp_create_nonce("wpqa_remove_image").'">'.esc_html__("Delete","wpqa").'</div>
							<div class="loader_2 loader_4"></div>
						</div>';
					}
				}
				$out .= '<div class="question-multiple-upload question-upload-featured">
					<label for="posts_image-'.$rand.'">'.esc_html__("Upload the posts photo, that represents this posts","wpqa").'</label>
					<div class="clearfix"></div>
					<div class="fileinputs">
						<input type="file" class="file" name="posts_image" id="posts_image-'.$rand.'">
						<i class="icon-camera"></i>
						<div class="fakefile">
							<button type="button">'.esc_html__("Select file","wpqa").'</button>
							<span>'.esc_html__("Browse","wpqa").'</span>
						</div>
					</div>
					<div class="clearfix"></div>
				</div>'.apply_filters('wpqa_add_edit_posts_after_posts_image',false,$type,$posts_add,$posts_edit,(isset($get_posts)?$get_posts:0));
			}
		$out .= '</div>';
		if ($is_super_admin || (isset($get_post_p->post_author) && $get_post_p->post_author == $user_id) || is_array($group_moderators) && in_array($user_id,$group_moderators)) {
			if ($type == "add" && isset($posts_add['sticky']) && $posts_add['sticky'] == "sticky") {
				$active_sticky = true;
			}else if ($type == "edit" && ((isset($posts_edit['sticky']) && $posts_edit['sticky'] == "sticky") || (!isset($posts_edit['sticky']) && $p_sticky))) {
				$active_sticky = true;
			}
			$out .= '<div class="group-bottom-buttons">
				<p class="wpqa_checkbox_p">
					<label for="sticky-'.$rand.'">
						<span class="wpqa_checkbox"><input type="checkbox" id="sticky-'.$rand.'" class="sticky_input" name="sticky" value="sticky"'.(isset($active_sticky)?" checked='checked'":"").'></span>
						<span class="wpqa_checkbox_span">'.esc_html__("Stick this post","wpqa").'</span>
					</label>
				</p>';
		}
		$out .= '<p class="form-submit">';
			if ($type == "edit") {
				$out .= '<input type="hidden" name="ID" value="'.$get_posts.'">';
			}
			$out .= '<input type="hidden" name="group_id" value="'.$group_id.'">
			<input type="hidden" name="form_type" value="'.$type.'_posts">
			<input type="hidden" name="wpqa_'.$type.'_posts_nonce" value="'.wp_create_nonce("wpqa_".$type."_posts_nonce").'">
			<input type="submit" value="'.($type == "add"?esc_html__("Publish Your Post","wpqa"):esc_html__("Edit Your Post","wpqa")).'" class="button-default button-hide-click">
			<span class="load_span"><span class="loader_2"></span></span>
		</p>';
		if ($is_super_admin || (isset($get_post_p->post_author) && $get_post_p->post_author == $user_id) || is_array($group_moderators) && in_array($user_id,$group_moderators)) {
			$out .= '</div>';
		}
	
	$out .= '</form>';
	return $out;
}
/* Add posts */
function wpqa_add_group_posts($type) {
	if (isset($_POST["form_type"]) && $_POST["form_type"] == "add_posts") :
		$return = wpqa_process_new_group_posts($_POST);
		if (is_wp_error($return)) :
   			return '<div class="wpqa_error">'.$return->get_error_message().'</div>';
   		else :
   			$get_post = get_post($return);
   			if ($get_post->post_type == "posts") {
   				$get_current_user_id = get_current_user_id();
	   			if ($get_post->post_type == "draft") {
	   				$send_email_draft_group_posts = wpqa_options("send_email_draft_group_posts");
	   				if ($send_email_draft_group_posts == "on") {
	   					$send_text = wpqa_send_mail(
	   						array(
								'content' => wpqa_options("email_draft_group_posts"),
								'post_id' => $return,
							)
	   					);
   						$email_title = wpqa_options("title_new_draft_group_posts");
   						$email_title = ($email_title != ""?$email_title:esc_html__("New group post for review","wpqa"));
   						$email_title = wpqa_send_mail(
   							array(
								'content' => $email_title,
								'title'   => true,
								'break'   => '',
								'post_id' => $return,
							)
   						);
   						wpqa_send_mails(
							array(
								'title'   => $email_title,
								'message' => $send_text,
							)
						);
	   				}
					wpqa_session('<div class="alert-message success"><i class="icon-check"></i><p>'.esc_html__("Your group post was successfully added, It's under review.","wpqa").'</p></div>','wpqa_session');
					if ($get_current_user_id > 0) {
						wpqa_notifications_activities($get_current_user_id,"","","","","approved_posts","activities","","posts");
					}
				}else {
					update_post_meta($return,'post_approved_before',"yes");
					wpqa_session('<div class="alert-message success"><i class="icon-check"></i><p>'.esc_html__("Your group post has been added successfully.","wpqa").'</p></div>','wpqa_session');
					if ($get_current_user_id > 0) {
						wpqa_notifications_activities($get_current_user_id,"","",$return,"","add_posts","activities","","posts");
					}
					wpqa_post_publish($get_post,$get_post->post_author);
				}
				wp_redirect(get_permalink(get_post_meta($return,"group_id",true)));
			}
			exit;
   		endif;
	endif;
}
add_filter('wpqa_add_group_posts','wpqa_add_group_posts');
/* Process new posts */
function wpqa_process_new_group_posts($data) {
	global $posts_add;
	set_time_limit(0);
	$errors = new WP_Error();
	$posts_add = array();
	$form_type = (isset($data["form_type"]) && $data["form_type"] != ""?$data["form_type"]:"");
	if ($form_type == "add_posts") {
		$user_id = get_current_user_id();
		
		$fields = array(
			'content','group_id','posts_image','sticky'
		);
		
		$fields = apply_filters("wpqa_add_posts_fields",$fields,"add");
		
		foreach ($fields as $field) :
			if (isset($data[$field])) $posts_add[$field] = $data[$field]; else $posts_add[$field] = '';
		endforeach;

		if (!isset($data['mobile']) && (!isset($data['wpqa_add_posts_nonce']) || !wp_verify_nonce($data['wpqa_add_posts_nonce'],'wpqa_add_posts_nonce'))) {
			$errors->add('nonce-error','<strong>'.esc_html__("Error","wpqa").' :&nbsp;</strong> '.esc_html__("There is an error, Please reload the page and try again.","wpqa"));
		}

		$posts_status = "draft";
		$group_id = (int)$posts_add['group_id'];
		$get_group = get_post($group_id);
		$group_allow_posts = get_post_meta($group_id,"group_allow_posts",true);
		$group_moderators = get_post_meta($group_id,"group_moderators",true);
		$group_users_array = get_post_meta($group_id,"group_users_array",true);
		if (is_user_logged_in() && (is_super_admin($user_id) || $get_group->post_author == $user_id || (($group_allow_posts == "all" || $group_allow_posts == "admin_moderators") && is_array($group_moderators) && in_array($user_id,$group_moderators)) || ($group_allow_posts == "all" && is_array($group_users_array) && in_array($user_id,$group_users_array)))) {
			$group_approval = get_post_meta($group_id,"group_approval",true);
			if (is_user_logged_in() && (is_super_admin($user_id) || $get_group->post_author == $user_id || (($group_allow_posts == "all" || $group_allow_posts == "admin_moderators") && is_array($group_moderators) && in_array($user_id,$group_moderators)) || ($group_allow_posts == "all" && $group_approval == "on" && is_array($group_users_array) && in_array($user_id,$group_users_array)))) {
				$posts_status = "publish";
			}
		}else {
			$errors->add('required','<strong>'.esc_html__("Error","wpqa").' :&nbsp;</strong> '.esc_html__("Sorry, you do not have a permission to add a group post.","wpqa"));
		}
		
		/* Validate Required Fields */
		do_action("wpqa_add_posts_errors",$errors,$posts_add,"add");
		
		if (sizeof($errors->errors) > 0) return $errors;
		
		/* Create posts */
		
		$editor_posts_content = wpqa_options("editor_posts_content");
		$data = array(
			'post_content' => ($editor_posts_content == "on"?wpqa_kses_stip($posts_add['content'],"yes",""):wpqa_kses_stip_wpautop($posts_add['content'])),
			'post_status'  => $posts_status,
			'post_author'  => $user_id,
			'post_type'	   => 'posts',
		);
			
		$posts_id = wp_insert_post($data);
			
		if ($posts_id == 0 || is_wp_error($posts_id)) wp_die(esc_html__("Error in group post.","wpqa"));
		
		/* Posts photo */

		$posts_image = '';

		require_once(ABSPATH . 'wp-admin/includes/image.php');
		require_once(ABSPATH . 'wp-admin/includes/file.php');
		
		if (isset($_FILES['posts_image']) && !empty($_FILES['posts_image']['name'])) :
			$types = array("image/jpeg","image/bmp","image/jpg","image/png","image/gif","image/tiff","image/ico");
			if (!in_array($_FILES['posts_image']['type'],$types)) :
				$errors->add('upload-error',esc_html__("Attachment Error! Please upload image only.","wpqa"));
				return $errors;
			endif;
			
			$posts_image = wp_handle_upload($_FILES['posts_image'],array('test_form' => false),current_time('mysql'));
			
			if (isset($posts_image['error'])) :
				$errors->add('upload-error',esc_html__("Attachment Error: ","wpqa") . $posts_image['error']);
				return $errors;
			endif;
		endif;
		
		if ($posts_image) :
			$posts_image_data = array(
				'post_mime_type' => $posts_image['type'],
				'post_title'	 => preg_replace('/\.[^.]+$/','',basename($posts_image['file'])),
				'post_content'   => '',
				'post_status'	 => 'inherit',
				'post_author'    => $user_id,
			);
			$posts_image_id = wp_insert_attachment($posts_image_data,$posts_image['file'],$posts_id);
			$posts_image_metadata = wp_generate_attachment_metadata($posts_image_id,$posts_image['file']);
			wp_update_attachment_metadata($posts_image_id, $posts_image_metadata);
			update_post_meta($posts_id,"posts_image",$posts_image_id);
		endif;

		$sticky_posts = get_post_meta($group_id,"sticky_posts",true);
		$sticky_main_posts = get_option("sticky_posts");
		if (isset($posts_add['sticky']) && $posts_add['sticky'] == "sticky") {
			update_post_meta($posts_id,'sticky',1);
			if (is_array($sticky_posts)) {
				if (!in_array($posts_id,$sticky_posts)) {
					$array_merge = array_merge($sticky_posts,array($posts_id));
					update_post_meta($group_id,"sticky_posts",$array_merge);
				}
			}else {
				update_post_meta($group_id,"sticky_posts",array($posts_id));
			}
			if (is_array($sticky_main_posts)) {
				if (!in_array($posts_id,$sticky_main_posts)) {
					$array_merge = array_merge($sticky_main_posts,array($posts_id));
					update_option("sticky_posts",$array_merge);
				}
			}else {
				update_option("sticky_posts",array($posts_id));
			}
		}else {
			if (is_array($sticky_posts) && in_array($posts_id,$sticky_posts)) {
				$sticky_posts = wpqa_remove_item_by_value($sticky_posts,$posts_id);
				update_post_meta($group_id,'sticky_posts',$sticky_posts);
			}
			if (is_array($sticky_main_posts) && in_array($posts_id,$sticky_main_posts)) {
				$sticky_main_posts = wpqa_remove_item_by_value($sticky_main_posts,$posts_id);
				update_option('sticky_posts',$sticky_main_posts);
			}
			delete_post_meta($posts_id,'sticky');
		}
		
		update_post_meta($posts_id,"post_from_front","from_front");
		update_post_meta($posts_id,"group_id",$group_id);
		
		do_action("wpqa_finished_add_posts",$posts_id,$posts_add,"add");
		
		/* Successful */
		return $posts_id;
	}
}
/* Posts */
function wpqa_group_posts($atts, $content = null) {
	$a = shortcode_atts( array(
	    'group_id'  => '',
	), $atts );
	return wpqa_add_edit_group_posts("add",$a['group_id']);
}
/* Edit posts attrs */
function wpqa_edit_posts_attr($atts, $content = null) {
	$a = shortcode_atts( array(
	    'post_id'  => '',
	), $atts );
	$out = '';
	if (!is_user_logged_in()) {
		$out .= '<div class="alert-message warning"><i class="icon-flag"></i><p>'.esc_html__("You must login to edit group posts.","wpqa").'</p></div>'.do_shortcode("[wpqa_login]");
	}else {
		$edit_posts = wpqa_options("posts_edit");
		$user_id = get_current_user_id();
		$is_super_admin = is_super_admin($user_id);
		if ($edit_posts == "on" || $is_super_admin) {
			$get_post = (int)get_query_var(apply_filters('wpqa_edit_posts_group','edit_post_group'));
			$get_posts = get_post($get_post);
			if (isset($get_post) && $get_post != 0 && $get_posts && $get_posts->post_type == "posts") {
				$group_id = get_post_meta($get_post,"group_id",true);
	   			if ($group_id > 0) {
		   			$group_moderators = get_post_meta($group_id,"group_moderators",true);
		   		}
		   		$edit_delete_posts_comments = wpqa_options("edit_delete_posts_comments");
				if ($get_posts->post_author > 0 || $is_super_admin || (isset($edit_delete_posts_comments["edit"]) && $edit_delete_posts_comments["edit"] == "edit" && isset($group_moderators) && is_array($group_moderators) && in_array($user_id,$group_moderators))) {
					$allow_to_edit_posts = apply_filters("wpqa_allow_to_edit_posts",true,$get_post);
					if ($allow_to_edit_posts == true && (($get_posts->post_author == $user_id && $user_id != 0 && $get_posts->post_status == "publish") || $is_super_admin || (isset($edit_delete_posts_comments["edit"]) && $edit_delete_posts_comments["edit"] == "edit" && isset($group_moderators) && is_array($group_moderators) && in_array($user_id,$group_moderators)))) {
						$out .= wpqa_add_edit_group_posts("edit",$a['post_id']);
					}else {
						$out .= '<div class="alert-message error"><i class="icon-cancel"></i><p>'.esc_html__("Sorry you can't edit this group posts.","wpqa").'</p></div>';
					}
				}else {
					$out .= '<div class="alert-message error"><i class="icon-cancel"></i><p>'.esc_html__("Sorry you can't edit this group posts.","wpqa").'</p></div>';
				}
			}else {
				$out .= '<div class="alert-message error"><i class="icon-cancel"></i><p>'.esc_html__("Sorry no group posts has been selected or not found.","wpqa").'</p></div>';
			}
		}else {
			$out .= '<div class="alert-message error"><i class="icon-cancel"></i><p>'.esc_html__("Sorry, you do not have a permission to edit a group posts.","wpqa").'</p></div>';
		}
	}
	return $out;
}
/* Edit posts */
function wpqa_edit_group_posts() {
	if (isset($_POST["form_type"]) && $_POST["form_type"] == "edit_posts") :
		$return = wpqa_process_edit_group_posts($_POST);
		if (is_wp_error($return)) :
   			return '<div class="wpqa_error">'.$return->get_error_message().'</div>';
   		else :
   			$posts_approved = wpqa_options("posts_approved");
   			$user_id = get_current_user_id();
   			$is_super_admin = is_super_admin($user_id);
   			$group_id = get_post_meta($return,"group_id",true);
   			if ($group_id > 0) {
	   			$group_moderators = get_post_meta($group_id,"group_moderators",true);
	   		}
			if ($posts_approved == "on" || $is_super_admin || (isset($group_moderators) && is_array($group_moderators) && in_array($user_id,$group_moderators))) {
				wpqa_session('<div class="alert-message success"><i class="icon-check"></i><p>'.esc_html__("Edited successfully.","wpqa").'</p></div>','wpqa_session');
			}else {
				wpqa_session('<div class="alert-message success"><i class="icon-check"></i><p>'.esc_html__("Your group posts has been Edited successfully. The group posts is under review.","wpqa").'</p></div>','wpqa_session');
			}
			$post_approved_before = get_post_meta($return,'post_approved_before',true);
			if ($post_approved_before != "yes") {
				update_post_meta($return,'post_approved_before',"yes");
				$post_author = get_post_field('post_author',$return);
				wpqa_notifications_activities($post_author,$user_id,"",$return,"","approved_posts","notifications","","posts");
			}
			wp_redirect(wpqa_custom_permalink($return,"view_posts_group","view_group_post"));
			exit;
   		endif;
	endif;
}
add_filter('wpqa_edit_group_posts','wpqa_edit_group_posts');
/* Process edit posts */
function wpqa_process_edit_group_posts($data) {
	global $posts_edit;
	set_time_limit(0);
	$errors = new WP_Error();
	$posts_edit = array();
	$form_type = (isset($data["form_type"]) && $data["form_type"] != ""?$data["form_type"]:"");
	if ($form_type == "edit_posts") {
		$get_posts = (int)get_query_var(apply_filters('wpqa_edit_posts_group','edit_post_group'));

		$fields = array(
			'content','posts_image','sticky'
		);
		
		$fields = apply_filters("wpqa_edit_posts_fields",$fields,"edit");
		
		foreach ($fields as $field) :
			if (isset($data[$field])) $posts_edit[$field] = $data[$field]; else $posts_edit[$field] = '';
		endforeach;
		
		/* Validate Required Fields */

		if (!isset($data['mobile']) && (!isset($data['wpqa_edit_posts_nonce']) || !wp_verify_nonce($data['wpqa_edit_posts_nonce'],'wpqa_edit_posts_nonce'))) {
			$errors->add('nonce-error','<strong>'.esc_html__("Error","wpqa").' :&nbsp;</strong> '.esc_html__("There is an error, Please reload the page and try again.","wpqa"));
		}

		$get_post_p = get_post($get_posts);
		$user_id = get_current_user_id();
		$is_super_admin = is_super_admin($user_id);
		if (isset($get_posts) && $get_posts != 0 && $get_post_p && $get_post_p->post_type == "posts") {
			$group_id = get_post_meta($get_posts,"group_id",true);
   			if ($group_id > 0) {
	   			$group_moderators = get_post_meta($group_id,"group_moderators",true);
	   		}
	   		$edit_delete_posts_comments = wpqa_options("edit_delete_posts_comments");
			if (($get_post_p->post_author == $user_id && $user_id > 0 && $get_post_p->post_status == "publish") || $is_super_admin || (isset($edit_delete_posts_comments["edit"]) && $edit_delete_posts_comments["edit"] == "edit" && isset($group_moderators) && is_array($group_moderators) && in_array($user_id,$group_moderators))) {
				// Yes, you can edit.
			}else {
				$errors->add('required-field','<strong>'.esc_html__("Error","wpqa").' :&nbsp;</strong> '.esc_html__("Sorry you can't edit this group posts.","wpqa"));
			}
		}else {
			$errors->add('required-field','<strong>'.esc_html__("Error","wpqa").' :&nbsp;</strong> '.esc_html__("Sorry no group posts selected or not found.","wpqa"));
		}
		
		do_action("wpqa_edit_posts_errors",$errors,$posts_edit,"edit");
		
		if (sizeof($errors->errors) > 0) return $errors;
		
		$posts_id = $get_posts;
		
		$posts_approved = wpqa_options("posts_approved");
		$editor_posts_content = wpqa_options("editor_posts_content");
		
		/* Edit posts */
		
		$data = array(
			'ID'           => (int)sanitize_text_field($posts_id),
			'post_content' => ($editor_posts_content == "on"?wpqa_kses_stip($posts_edit['content'],"yes",""):wpqa_kses_stip_wpautop($posts_edit['content'])),
			'post_status'  => ($posts_approved == "on" || $is_super_admin || (isset($group_moderators) && is_array($group_moderators) && in_array($user_id,$group_moderators))?"publish":"draft"),
			'post_author'  => $get_post_p->post_author,
		);
		
		wp_update_post($data);
		
		/* Posts photo */
		
		$posts_image = '';

		require_once(ABSPATH . 'wp-admin/includes/image.php');
		require_once(ABSPATH . 'wp-admin/includes/file.php');
		
		if(isset($_FILES['posts_image']) && !empty($_FILES['posts_image']['name'])) :
			$types = array("image/jpeg","image/bmp","image/jpg","image/png","image/gif","image/tiff","image/ico");
			if (!in_array($_FILES['posts_image']['type'],$types)) :
				$errors->add('upload-error',esc_html__("Attachment Error! Please upload image only.","wpqa"));
				return $errors;
			endif;
			
			$posts_image = wp_handle_upload($_FILES['posts_image'],array('test_form' => false),current_time('mysql'));
			
			if (isset($posts_image['error'])) :
				$errors->add('upload-error',esc_html__("Attachment Error: ","wpqa") . $posts_image['error']);
				return $errors;
			endif;
			
		endif;
		if ($posts_image) :
			$posts_image_data = array(
				'post_mime_type' => $posts_image['type'],
				'post_title'     => preg_replace('/\.[^.]+$/','',basename($posts_image['file'])),
				'post_content'   => '',
				'post_status'    => 'inherit',
				'post_author'    => $user_id
			);
			$posts_image_id = wp_insert_attachment($posts_image_data,$posts_image['file'],$posts_id);
			$posts_image_metadata = wp_generate_attachment_metadata($posts_image_id,$posts_image['file']);
			wp_update_attachment_metadata($posts_image_id, $posts_image_metadata);
			update_post_meta($posts_id,"posts_image",$posts_image_id);
		endif;

		$sticky_posts = get_post_meta($group_id,'sticky_posts',true);
		$sticky_main_posts = get_option('sticky_posts');
		if (isset($posts_edit['sticky']) && $posts_edit['sticky'] == "sticky") {
			update_post_meta($posts_id,'sticky',1);
			if (is_array($sticky_posts)) {
				if (!in_array($posts_id,$sticky_posts)) {
					$array_merge = array_merge($sticky_posts,array($posts_id));
					update_post_meta($group_id,"sticky_posts",$array_merge);
				}
			}else {
				update_post_meta($group_id,"sticky_posts",array($posts_id));
			}
			if (is_array($sticky_main_posts)) {
				if (!in_array($posts_id,$sticky_main_posts)) {
					$array_merge = array_merge($sticky_main_posts,array($posts_id));
					update_option("sticky_posts",$array_merge);
				}
			}else {
				update_option("sticky_posts",array($posts_id));
			}
		}else {
			if (is_array($sticky_posts) && in_array($posts_id,$sticky_posts)) {
				$sticky_posts = wpqa_remove_item_by_value($sticky_posts,$posts_id);
				update_post_meta($group_id,'sticky_posts',$sticky_posts);
			}
			if (is_array($sticky_main_posts) && in_array($posts_id,$sticky_main_posts)) {
				$sticky_main_posts = wpqa_remove_item_by_value($sticky_main_posts,$posts_id);
				update_option('sticky_posts',$sticky_main_posts);
			}
			delete_post_meta($posts_id,'sticky');
		}

		do_action("wpqa_finished_edit_posts",$posts_id,$posts_edit,"edit");
		
		/* Successful */
		return $posts_id;
	}
}
/* Posts errors */
add_action("wpqa_add_posts_errors","wpqa_add_edit_posts_errors",1,3);
add_action("wpqa_edit_posts_errors","wpqa_add_edit_posts_errors",1,3);
function wpqa_add_edit_posts_errors($errors,$posted,$type) {
	if (empty($posted['content'])) {
		$errors->add('required-field','<strong>'.esc_html__("Error","wpqa").' :&nbsp;</strong> '.esc_html__("There are required fields (content).","wpqa"));
	}
	return $errors;
}
/* Posts count */
add_action("wpqa_after_post_publish","wpqa_after_posts_publish");
function wpqa_after_posts_publish($post) {
	$post_id = (int)$post->ID;
	$group_id = (int)get_post_meta($post_id,"group_id",true);
	$group_posts = get_post_meta($group_id,"group_posts",true);
	if ($group_posts == "" || $group_posts == 0) {
		update_post_meta($group_id,"group_posts",1);
	}else {
		update_post_meta($group_id,"group_posts",$group_posts+1);
	}
}?>