<?php

/* @author    2codeThemes
*  @package   WPQA/functions
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* Get group cover */
add_action("wpqa_group_cover","wpqa_group_cover");
function wpqa_group_cover() {
	if (wpqa_is_edit_groups() || is_singular("group") || wpqa_is_group_requests() || wpqa_is_group_users() || wpqa_is_group_admins() || wpqa_is_blocked_users() || wpqa_is_posts_group() || wpqa_is_view_posts_group() || wpqa_is_edit_posts_group()) {
		$group_cover_activate = "on";
		if ($group_cover_activate == "on") {
			$group_id = wpqa_group_id();
			if ($group_id > 0) {
				$user_id = get_current_user_id();
				$is_super_admin = is_super_admin($user_id);
				$get_group = get_post($group_id);
				$blocked_users_array = get_post_meta($group_id,"blocked_users_array",true);
				$group_moderators = get_post_meta($group_id,"group_moderators",true);
				$blocked_users_array = (is_array($blocked_users_array)?$blocked_users_array:array());
				if ($is_super_admin || (is_array($group_moderators) && in_array($user_id,$group_moderators)) || ($user_id > 0 && $user_id == $get_group->post_author) || !in_array($user_id,$blocked_users_array)) {
					$the_title = $get_group->post_title;
					$group_privacy = get_post_meta($group_id,"group_privacy",true);
					$group_image = get_post_meta($group_id,"group_image",true);
					$group_users = (int)get_post_meta($group_id,"group_users",true);
					$group_posts = (int)get_post_meta($group_id,"group_posts",true);
					$group_users_array = get_post_meta($group_id,"group_users_array",true);
					$group_requests_array = get_post_meta($group_id,"group_requests_array",true);
					$group_invitations = get_post_meta($group_id,"group_invitations",true);
					$group_invitations = (is_array($group_invitations) && !empty($group_invitations)?$group_invitations:array());
					echo '<div class="group_cover">
						<div class="cover-opacity"></div>
						<div class="the-main-container">
							<div class="group_cover_content">
								<div class="group_cover_content_first">
									<div>';
									if ((is_array($group_image) && isset($group_image["id"])) || (!is_array($group_image) && $group_image != "")) {
										echo wpqa_get_aq_resize_img(100,100,"",(is_array($group_image) && isset($group_image["id"])?$group_image["id"]:$group_image),"no",$the_title,"srcset");
									}
									echo '</div>
									<div class="name">
										<h1>'.$the_title.'</h1></a>
										<small>'.($group_privacy == "public"?"<i class='icon-lock-open'></i>".esc_html__("Public group","wpqa"):"<i class='icon-lock'></i>".esc_html__("Private group","wpqa")).'</small>
									</div>
								</div>
								<div class="group_cover_content_second">
									<div class="wpqa-cover-right">';
										if ($is_super_admin || ($user_id > 0 && $user_id == $get_group->post_author)) {
											echo '<div><a href="'.esc_url_raw(add_query_arg(array("delete" => $group_id,"wpqa_delete_nonce" => wp_create_nonce("wpqa_delete_nonce")),get_permalink($group_id))).'" class="button-default delete-group" data-id="'.$group_id.'">'.esc_html__("Delete","wpqa").'</a></div>';
										}else if (!$is_super_admin && $user_id > 0) {
											if (is_array($group_users_array) && in_array($user_id,$group_users_array)) {
												$join_leave_text = esc_html__("Leave","wpqa");
												$join_leave_class = "user_in_group";
											}else {
												if (is_array($group_requests_array) && in_array($user_id,$group_requests_array)) {
													$join_leave_text = esc_html__("Cancel the request","wpqa");
													$join_leave_class = "cancel_request_group";
												}else {
													if (is_array($group_invitations) && in_array($user_id,$group_invitations)) {
														$join_leave_text = esc_html__("Accept invite","wpqa");
														$join_leave_class = "accept_invite";
														$join_leave_text_2 = esc_html__("Decline invite","wpqa");
														$join_leave_class_2 = "decline_invite";
													}else {
														$join_leave_text = esc_html__("Join","wpqa");
														if ($group_privacy == "public") {
															$join_leave_class = "user_out_group";
														}else {
															$join_leave_class = "request_group";
														}
													}
												}
											}
											echo '<div class="group_join">
												<div class="cover_loader wpqa_hide"><div class="small_loader loader_2"></div></div>
												<a href="#" class="button-default hide_button_too '.$join_leave_class.'" data-id="'.$group_id.'">'.$join_leave_text.'</a>';
												if (isset($join_leave_class_2)) {
													echo '<a href="#" class="button-default hide_button_too '.$join_leave_class_2.'" data-id="'.$group_id.'">'.$join_leave_text_2.'</a>';
												}
											echo '</div>';
										}
										echo '<div class="wpqa-cover-buttons wpqa-cover-users"><i class="icon-users"></i><span class="cover-count">'.wpqa_count_number($group_users).'</span>'._n("User","Users",$group_users,"wpqa").'</div>
										<div class="wpqa-cover-buttons wpqa-cover-posts"><i class="icon-book-open"></i><span class="cover-count">'.wpqa_count_number($group_posts).'</span>'._n("Post","Posts",$group_posts,"wpqa").'</div>
									</div>
								</div>
							</div>
						</div>
					</div>';
				}
			}
		}
	}
}
/* Group tabs */
add_action("wpqa_group_tabs","wpqa_group_tabs",1,5);
function wpqa_group_tabs($group_id,$user_id,$is_super_admin,$post_author,$group_moderators) {
	$group_privacy = get_post_meta($group_id,"group_privacy",true);
	$view_users_group = wpqa_options("view_users_group");
	if ($is_super_admin || (isset($view_users_group[$group_privacy]) && $view_users_group[$group_privacy] == $group_privacy) || $post_author == $user_id || (is_array($group_moderators) && in_array($user_id,$group_moderators))) {
		$list_child = "li";
		echo '<div class="wrap-tabs"><div class="menu-tabs"><ul class="menu flex menu-tabs-desktop">';
			do_action("wpqa_group_inner_tabs",$group_id,$list_child,$user_id,$is_super_admin,$post_author,$group_moderators,$group_privacy);
		echo '</ul></div></div>';
		$list_child = "option";
		echo '<div class="wpqa_hide mobile-tabs"><span class="styled-select"><select class="home_categories">';
			do_action("wpqa_group_inner_tabs",$group_id,$list_child,$user_id,$is_super_admin,$post_author,$group_moderators,$group_privacy);
		echo '</select></span></div>';
	}
}
/* Group inner tabs */
add_action("wpqa_group_inner_tabs","wpqa_group_inner_tabs",1,7);
function wpqa_group_inner_tabs($group_id,$list_child,$user_id,$is_super_admin,$post_author,$group_moderators,$group_privacy) {
	if ($is_super_admin || $post_author == $user_id || (is_array($group_moderators) && in_array($user_id,$group_moderators))) {
		$moderators = true;
	}
	if (isset($moderators)) {
		$group_approval = esc_html(get_post_meta($group_id,"group_approval",true));
		$group_pages = array("group","edit","group_requests","pending_posts","group_users","group_admins","blocked_users");
		if ($group_privacy == "public") {
			$group_pages = array_diff($group_pages,array("group_requests"));
		}
		if ($group_approval == "on") {
			$group_pages = array_diff($group_pages,array("pending_posts"));
		}
		if (!$is_super_admin && $user_id != $post_author) {
			$group_pages = array_diff($group_pages,array("group_admins"));
		}
	}else {
		$group_pages = array("group","group_users");
	}
	$group_pages = apply_filters("wpqa_group_pages",$group_pages,$group_id);
	if (isset($group_pages) && is_array($group_pages) && !empty($group_pages)) {
		foreach ($group_pages as $key) {
			do_action("wpqa_action_edit_group_pages",$group_pages,$key);
			if ($key == "group") {
				$last_url = get_permalink($group_id);
				$selected = (is_singular("group")?true:"");
			}else if ($key == "edit") {
				$last_url = wpqa_edit_permalink($group_id,"group");
				$selected = (wpqa_is_edit_groups()?true:"");
			}else if ($key == "group_requests") {
				$last_url = wpqa_custom_permalink($group_id,"group_requests","group_request");
				$selected = (wpqa_is_group_requests()?true:"");
			}else if ($key == "pending_posts") {
				$last_url = wpqa_custom_permalink($group_id,"pending_posts","pending_post");
				$selected = (wpqa_is_posts_group()?true:"");
			}else if ($key == "group_users") {
				$last_url = wpqa_custom_permalink($group_id,"group_users","group_user");
				$selected = (wpqa_is_group_users()?true:"");
			}else if ($key == "group_admins") {
				$last_url = wpqa_custom_permalink($group_id,"group_admins","group_admin");
				$selected = (wpqa_is_group_admins()?true:"");
			}else if ($key == "blocked_users") {
				$last_url = wpqa_custom_permalink($group_id,"blocked_users","blocked_user");
				$selected = (wpqa_is_blocked_users()?true:"");
			}
			if (isset($last_url) && $last_url != "") {
				if ($list_child == "li") {?>
					<li class='<?php echo "li_group_".$key.(isset($selected) && $selected == true?" active-tab":"")?>'>
						<a href="<?php echo esc_url($last_url)?>">
				<?php }else {?>
					<option<?php echo (isset($selected) && $selected == true?" selected='selected'":"")?> value="<?php echo esc_url($last_url)?>">
				<?php }
			}

			if ($key == "group") {
				esc_html_e("Discussion","wpqa");
			}else if ($key == "edit") {
				if ($is_super_admin || $user_id == $post_author) {
					esc_html_e("Edit","wpqa");
				}else {
					esc_html_e("Edit rules","wpqa");
				}
			}else if ($key == "group_requests") {
				esc_html_e("Requests","wpqa");
				$group_requests_array = get_post_meta($group_id,"group_requests_array",true);
				$group_requests_array = (is_array($group_requests_array) && !empty($group_requests_array)?$group_requests_array:array());
				$group_requests_array = count($group_requests_array);
				echo ($group_requests_array > 0?"<span class='notifications-number asked-count".($group_requests_array <= 99?"":" notifications-number-super")."'>".($group_requests_array <= 99?$group_requests_array:"99+")."</span>":"");
			}else if ($key == "pending_posts") {
				esc_html_e("Group Posts","wpqa");
				$count_posts_by_type = wpqa_count_group_posts_by_type("posts","draft",$group_id);
				echo ($count_posts_by_type > 0?"<span class='notifications-number asked-count".($count_posts_by_type <= 99?"":" notifications-number-super")."'>".($count_posts_by_type <= 99?$count_posts_by_type:"99+")."</span>":"");
			}else if ($key == "group_users") {
				esc_html_e("Users","wpqa");
			}else if ($key == "group_admins") {
				esc_html_e("Admins","wpqa");
			}else if ($key == "blocked_users") {
				esc_html_e("Blocked Users","wpqa");
			}
			if (isset($last_url) && $last_url != "") {
				if ($list_child == "li") {?>
						</a>
					</li>
				<?php }else {?>
					</option>
				<?php }
				$last_url = "";
			}
		}
	}
}
/* After added group */
add_action("wpqa_after_added_group","wpqa_after_added_group",1,2);
function wpqa_after_added_group($group_id,$user_id) {
	update_post_meta($group_id,"group_posts",0);
	update_post_meta($group_id,"group_users",1);
	update_post_meta($group_id,"group_users_array",array($user_id));
	update_post_meta($group_id,"group_moderators",array($user_id));
	
	$user_group_join = get_user_meta($user_id,"groups_array",true);
	$user_group_join = (is_array($user_group_join) && !empty($user_group_join)?$user_group_join:array());
	update_user_meta($user_id,"groups_array",array_merge($user_group_join,array($group_id)));
}
/* Redirect to the group */
add_filter('comment_post_redirect','wpqa_group_comment_redirect',1,2);
function wpqa_group_comment_redirect($location,$commentdata) {
	if (!isset($commentdata) || empty($commentdata->comment_post_ID)) {
		return $location;
	}
	$group_id = (int)get_post_meta($commentdata->comment_post_ID,"group_id",true);
	if ($group_id > 0) {
		$location = wpqa_custom_permalink($commentdata->comment_post_ID,"view_posts_group","view_group_post")."#comment-".$commentdata->comment_ID;
	}
	return $location;
}
/* Join group */
if (!function_exists('wpqa_join_group')) :
	function wpqa_join_group() {
		$group_id = (int)$_POST['group_id'];
		$user_id = get_current_user_id();
		$update = wpqa_update_user_group_meta($user_id,"groups_array",$group_id);
		$update = wpqa_update_group_meta($group_id,"group_users_array",$user_id);
		if ($update == true) {
			wpqa_count_group_users($group_id);
		}
	}
endif;
add_action('wp_ajax_wpqa_join_group','wpqa_join_group');
add_action('wp_ajax_nopriv_wpqa_join_group','wpqa_join_group');
/* Leave group */
if (!function_exists('wpqa_leave_group')) :
	function wpqa_leave_group() {
		$group_id = (int)$_POST['group_id'];
		$user_id = get_current_user_id();
		$update = wpqa_update_user_group_meta($user_id,"groups_array",$group_id,"remove");
		$update = wpqa_update_group_meta($group_id,"group_users_array",$user_id,"remove");
		if ($update == true) {
			wpqa_update_moderator_group($group_id,$user_id,"remove");
			wpqa_count_group_users($group_id,"remove");
		}
	}
endif;
add_action('wp_ajax_wpqa_leave_group','wpqa_leave_group');
add_action('wp_ajax_nopriv_wpqa_leave_group','wpqa_leave_group');
/* Request to join to group */
if (!function_exists('wpqa_request_group')) :
	function wpqa_request_group() {
		$group_id = (int)$_POST['group_id'];
		$user_id = get_current_user_id();
		$update = wpqa_update_user_group_meta($user_id,"group_requests_array",$group_id);
		$update = wpqa_update_group_meta($group_id,"group_requests_array",$user_id);
		if ($update == true) {
			$group_moderators = get_post_meta($group_id,"group_moderators",true);
			foreach ($group_moderators as $value) {
				wpqa_notifications_activities($value,$user_id,"",$group_id,"","request_group","notifications","","group");
			}
			$group_requests = (int)get_post_meta($group_id,"group_requests",true);
			$group_requests = ($group_requests != "" || $group_requests > 0?$group_requests:0);
			$group_requests++;
			$group_requests = update_post_meta($group_id,"group_requests",$group_requests);
		}
	}
endif;
add_action('wp_ajax_wpqa_request_group','wpqa_request_group');
add_action('wp_ajax_nopriv_wpqa_request_group','wpqa_request_group');
/* Cancel request to the group */
if (!function_exists('wpqa_cancel_request_group')) :
	function wpqa_cancel_request_group() {
		$group_id = (int)$_POST['group_id'];
		$user_id = get_current_user_id();
		$update = wpqa_update_user_group_meta($user_id,"group_requests_array",$group_id,"remove");
		$update = wpqa_update_group_meta($group_id,"group_requests_array",$user_id,"remove");
		if ($update == true) {
			$group_requests = (int)get_post_meta($group_id,"group_requests",true);
			$group_requests = ($group_requests != "" || $group_requests > 0?$group_requests:0);
			$group_requests--;
			$group_requests = update_post_meta($group_id,"group_requests",($group_requests <= 0?0:$group_requests));
		}
	}
endif;
add_action('wp_ajax_wpqa_cancel_request_group','wpqa_cancel_request_group');
add_action('wp_ajax_nopriv_wpqa_cancel_request_group','wpqa_cancel_request_group');
/* Approve all the requests */
if (!function_exists('wpqa_approve_request_all_group')) :
	function wpqa_approve_request_all_group() {
		$group_id = (int)$_POST['group_id'];
		$user_id = get_current_user_id();
		$group_requests_array = get_post_meta($group_id,"group_requests_array",true);
		if (is_array($group_requests_array) && !empty($group_requests_array)) {
			foreach ($group_requests_array as $value) {
				$update = wpqa_update_user_group_meta($value,"groups_array",$group_id);
				$update = wpqa_update_group_meta($group_id,"group_users_array",$value);
				if ($update == true) {
					wpqa_notifications_activities($value,$user_id,"",$group_id,"","approve_request_group","notifications","","group");
				}
			}
			if ($update == true) {
				wpqa_count_group_users($group_id,"add",count($group_requests_array));
			}
			delete_post_meta($group_id,"group_requests_array");
		}
	}
endif;
add_action('wp_ajax_wpqa_approve_request_all_group','wpqa_approve_request_all_group');
add_action('wp_ajax_nopriv_wpqa_approve_request_all_group','wpqa_approve_request_all_group');
/* Decline all the requests */
if (!function_exists('wpqa_decline_request_all_group')) :
	function wpqa_decline_request_all_group() {
		$group_id = (int)$_POST['group_id'];
		$user_id = get_current_user_id();
		$group_requests_array = get_post_meta($group_id,"group_requests_array",true);
		if (is_array($group_requests_array) && !empty($group_requests_array)) {
			foreach ($group_requests_array as $value) {
				wpqa_notifications_activities($value,$user_id,"",$group_id,"","decline_request_group","notifications","","group");
			}
			delete_post_meta($group_id,"group_requests_array");
		}
	}
endif;
add_action('wp_ajax_wpqa_decline_request_all_group','wpqa_decline_request_all_group');
add_action('wp_ajax_nopriv_wpqa_decline_request_all_group','wpqa_decline_request_all_group');
/* Approve the request */
if (!function_exists('wpqa_approve_request_group')) :
	function wpqa_approve_request_group() {
		$group_id = (int)$_POST['group_id'];
		$user_id = (int)$_POST['user_id'];
		$get_current_user_id = get_current_user_id();
		$group_requests_array = get_post_meta($group_id,"group_requests_array",true);
		if (is_array($group_requests_array) && in_array($user_id,$group_requests_array)) {
			$update = wpqa_update_user_group_meta($user_id,"groups_array",$group_id);
			$update = wpqa_update_group_meta($group_id,"group_users_array",$user_id);
			if ($update == true) {
				wpqa_notifications_activities($user_id,$get_current_user_id,"",$group_id,"","approve_request_group","notifications","","group");
				wpqa_count_group_users($group_id);
			}
			$update = wpqa_update_group_meta($group_id,"group_requests_array",$user_id,"remove");
		}
	}
endif;
add_action('wp_ajax_wpqa_approve_request_group','wpqa_approve_request_group');
add_action('wp_ajax_nopriv_wpqa_approve_request_group','wpqa_approve_request_group');
/* Decline the request */
if (!function_exists('wpqa_decline_request_group')) :
	function wpqa_decline_request_group() {
		$group_id = (int)$_POST['group_id'];
		$user_id = (int)$_POST['user_id'];
		$get_current_user_id = get_current_user_id();
		$update = wpqa_update_group_meta($group_id,"group_requests_array",$user_id,"remove");
		if ($update == true) {
			wpqa_notifications_activities($user_id,$get_current_user_id,"",$group_id,"","decline_request_group","notifications","","group");
		}
	}
endif;
add_action('wp_ajax_wpqa_decline_request_group','wpqa_decline_request_group');
add_action('wp_ajax_nopriv_wpqa_decline_request_group','wpqa_decline_request_group');
/* Like the post */
if (!function_exists('wpqa_posts_like')) :
	function wpqa_posts_like() {
		$post_id = (int)$_POST['post_id'];
		$user_id = get_current_user_id();
		$update = wpqa_update_group_meta($post_id,"posts_like",$user_id);
		$update = wpqa_update_user_group_meta($user_id,"posts_likes",$post_id);
		$posts_like = get_post_meta($post_id,"posts_like",true);
		$count = (is_array($posts_like)?count($posts_like):0);
		if ($update == true) {
			$get_post = get_post($post_id);
			$post_author = $get_post->post_author;
			if ($user_id > 0 && $post_author > 0 && $post_author != $user_id) {
				wpqa_notifications_activities($post_author,$user_id,"",$post_id,"","posts_like","notifications","","posts");
			}
			if ($user_id > 0) {
				wpqa_notifications_activities($user_id,"","",$post_id,"","posts_like","activities","","posts");
			}
		}
		echo wpqa_count_number($count);
		die();
	}
endif;
add_action('wp_ajax_wpqa_posts_like','wpqa_posts_like');
add_action('wp_ajax_nopriv_wpqa_posts_like','wpqa_posts_like');
/* Unlike the post */
if (!function_exists('wpqa_posts_unlike')) :
	function wpqa_posts_unlike() {
		$post_id = (int)$_POST['post_id'];
		$user_id = get_current_user_id();
		$update = wpqa_update_group_meta($post_id,"posts_like",$user_id,"remove");
		$update = wpqa_update_user_group_meta($user_id,"posts_likes",$post_id,"remove");
		$posts_like = get_post_meta($post_id,"posts_like",true);
		$count = (is_array($posts_like)?count($posts_like):0);
		if ($update == true && $user_id > 0) {
			wpqa_notifications_activities($user_id,"","",$post_id,"","posts_unlike","activities","","posts");
		}
		echo wpqa_count_number($count);
		die();
	}
endif;
add_action('wp_ajax_wpqa_posts_unlike','wpqa_posts_unlike');
add_action('wp_ajax_nopriv_wpqa_posts_unlike','wpqa_posts_unlike');
/* Block users */
if (!function_exists('wpqa_block_user_group')) :
	function wpqa_block_user_group() {
		$group_id = (int)$_POST['group_id'];
		$user_id = (int)$_POST['user_id'];
		$update = wpqa_update_group_meta($group_id,"blocked_users_array",$user_id);
		if ($update == true) {
			wpqa_notifications_activities($user_id,"","",$group_id,"","blocked_group","notifications","","group");
		}
	}
endif;
add_action('wp_ajax_wpqa_block_user_group','wpqa_block_user_group');
add_action('wp_ajax_nopriv_wpqa_block_user_group','wpqa_block_user_group');
/* Unblock users */
if (!function_exists('wpqa_unblock_user_group')) :
	function wpqa_unblock_user_group() {
		$group_id = (int)$_POST['group_id'];
		$user_id = (int)$_POST['user_id'];
		$update = wpqa_update_group_meta($group_id,"blocked_users_array",$user_id,"remove");
		if ($update == true) {
			wpqa_notifications_activities($user_id,"","",$group_id,"","unblocked_group","notifications","","group");
		}
	}
endif;
add_action('wp_ajax_wpqa_unblock_user_group','wpqa_unblock_user_group');
add_action('wp_ajax_nopriv_wpqa_unblock_user_group','wpqa_unblock_user_group');
/* Remove user */
if (!function_exists('wpqa_remove_user_group')) :
	function wpqa_remove_user_group() {
		$group_id = (int)$_POST['group_id'];
		$user_id = (int)$_POST['user_id'];
		$update = wpqa_update_user_group_meta($user_id,"groups_array",$group_id,"remove");
		$update = wpqa_update_group_meta($group_id,"group_users_array",$user_id,"remove");
		if ($update == true) {
			wpqa_update_moderator_group($group_id,$user_id,"remove");
			wpqa_notifications_activities($user_id,"","",$group_id,"","removed_user_group","notifications","","group");
			wpqa_count_group_users($group_id,"remove");
		}
	}
endif;
add_action('wp_ajax_wpqa_remove_user_group','wpqa_remove_user_group');
add_action('wp_ajax_nopriv_wpqa_remove_user_group','wpqa_remove_user_group');
/* Search to add a new moderator or invite user */
if (!function_exists('wpqa_new_user_group')) :
	function wpqa_new_user_group() {
		$user_value = wp_unslash(sanitize_text_field($_POST["user_value"]));
		$group_id = (int)$_POST["group_id"];
		$invite = sanitize_text_field($_POST["invite"]);
		$group_users_array = get_post_meta($group_id,"group_users_array",true);
		$group_users_array = (is_array($group_users_array) && !empty($group_users_array)?$group_users_array:array());
		$group_invitations = get_post_meta($group_id,"group_invitations",true);
		$group_invitations = (is_array($group_invitations) && !empty($group_invitations)?$group_invitations:array());
		$group_moderators = get_post_meta($group_id,"group_moderators",true);
		$group_moderators = (is_array($group_moderators) && !empty($group_moderators)?$group_moderators:array());
		$result_number = 10;
		$k_search      = 0;
		if ($user_value != "") {
			echo "<div class='result-div'>
				<ul>";
					$number = (isset($result_number) && $result_number > 0?$result_number:apply_filters('users_per_page',get_option('posts_per_page')));
					$args = array(
						'orderby'    => "user_registered",
						'order'      => "DESC",
						'search'     => '*'.$user_value.'*',
						'number'     => $number,
						'fields'     => 'ID',
					);
					$invite_array = ($invite == "invite"?array('exclude' => array_merge($group_invitations,$group_users_array)):array('include' => $group_users_array));
					$query = new WP_User_Query(array_merge($invite_array,$args));
					$query = $query->get_results();
					if ($invite != "invite") {
						$query = array_diff($query,$group_moderators);
					}
					if (isset($query) && !empty($query)) {
						foreach ($query as $user) {
							$k_search++;
							if ($result_number >= $k_search) {
								$display_name = get_the_author_meta('display_name',$user);
								echo '<li>
									<a class="get-results add-user-to-input" data-id="'.$user.'" href="#" title="'.$display_name.'">'.wpqa_get_user_avatar(array("user_id" => $user,"size" => 29,"user_name" => $display_name)).'</a>
									<a class="add-user-to-input" data-id="'.$user.'" href="#" title="'.$display_name.'">'.str_ireplace($user_value,"<strong>".$user_value."</strong>",$display_name).'</a>
								</li>';
							}
						}
					}else {
						$show_no_found = true;
					}
					if (isset($show_no_found) && $show_no_found == true) {
						echo "<li class='no-search-result'>".esc_html__("No results found.","wpqa")."</li>";
					}
				echo "</ul>
			</div>";
		}
		die();
	}
endif;
add_action('wp_ajax_wpqa_new_user_group','wpqa_new_user_group');
add_action('wp_ajax_nopriv_wpqa_new_user_group','wpqa_new_user_group');
/* Assign a new moderator or invite user */
if (!function_exists('wpqa_add_group_user')) :
	function wpqa_add_group_user() {
		$group_id = (int)(isset($_POST["group_id"])?$_POST["group_id"]:0);
		$user_id = (int)(isset($_POST["user_id"])?$_POST["user_id"]:0);
		if ($group_id > 0 && $user_id > 0) {
			$moderator = sanitize_text_field($_POST["moderator"]);
			if ($moderator == "moderator") {
				wpqa_update_moderator_group($group_id,$user_id,"add");
				wpqa_session('<div class="alert-message success"><i class="icon-check"></i><p>'.esc_html__("The moderator has added successfully.","wpqa").'</p></div>','wpqa_session');
			}else {
				$update = wpqa_update_group_meta($group_id,"group_invitations",$user_id);
				$update = wpqa_update_user_group_meta($user_id,"group_invitations",$group_id);
				if ($update == true) {
					wpqa_notifications_activities($user_id,get_current_user_id(),"",$group_id,"","add_group_invitations","notifications","","group");
					wpqa_session('<div class="alert-message success"><i class="icon-check"></i><p>'.esc_html__("The invite has sent successfully.","wpqa").'</p></div>','wpqa_session');
				}
			}
		}
		die();
	}
endif;
add_action('wp_ajax_wpqa_add_group_user','wpqa_add_group_user');
add_action('wp_ajax_nopriv_wpqa_add_group_user','wpqa_add_group_user');
/* Remove moderator */
if (!function_exists('wpqa_remove_moderator_group')) :
	function wpqa_remove_moderator_group() {
		$group_id = (int)$_POST['group_id'];
		$user_id = (int)$_POST['user_id'];
		$update = wpqa_update_moderator_group($group_id,$user_id,"remove");
	}
endif;
add_action('wp_ajax_wpqa_remove_moderator_group','wpqa_remove_moderator_group');
add_action('wp_ajax_nopriv_wpqa_remove_moderator_group','wpqa_remove_moderator_group');
/* Accept invite */
if (!function_exists('wpqa_accept_invite')) :
	function wpqa_accept_invite() {
		$group_id = (int)(isset($_POST["group_id"])?$_POST["group_id"]:0);
		$user_id = get_current_user_id();
		if ($group_id > 0 && $user_id > 0) {
			$update = wpqa_update_user_group_meta($user_id,"groups_array",$group_id);
			$update = wpqa_update_group_meta($group_id,"group_users_array",$user_id);
			$update = wpqa_update_user_group_meta($user_id,"group_invitations",$group_id,"remove");
			$update = wpqa_update_group_meta($group_id,"group_invitations",$user_id,"remove");
			if ($update == true) {
				wpqa_count_group_users($group_id);
				wpqa_notifications_activities($user_id,"","",$group_id,"","accept_invite","activities","","group");
			}
		}
		die();
	}
endif;
add_action('wp_ajax_wpqa_accept_invite','wpqa_accept_invite');
add_action('wp_ajax_nopriv_wpqa_accept_invite','wpqa_accept_invite');
/* Decline Invite */
if (!function_exists('wpqa_decline_invite')) :
	function wpqa_decline_invite() {
		$group_id = (int)$_POST['group_id'];
		$user_id = get_current_user_id();
		$update = wpqa_update_user_group_meta($user_id,"group_invitations",$group_id,"remove");
		$update = wpqa_update_group_meta($group_id,"group_invitations",$user_id,"remove");
		if ($update == true) {
			wpqa_notifications_activities($user_id,"","",$group_id,"","decline_invite","activities","","group");
		}
	}
endif;
add_action('wp_ajax_wpqa_decline_invite','wpqa_decline_invite');
add_action('wp_ajax_nopriv_wpqa_decline_invite','wpqa_decline_invite');
/* Agree the posts */
if (!function_exists('wpqa_agree_posts_group')) :
	function wpqa_agree_posts_group() {
		$post_id = (int)$_POST['group_id'];
		$group_id = get_post_meta($post_id,"group_id",true);
		$user_id = (int)$_POST['user_id'];
		$get_current_user_id = get_current_user_id();
		$group_moderators = get_post_meta($group_id,"group_moderators",true);
		if (is_super_admin($get_current_user_id) || (is_array($group_moderators) && !empty($group_moderators) && in_array($get_current_user_id,$group_moderators))) {
			remove_action('save_post','wpqa_save_post');
			$data = array(
				'ID'          => $post_id,
				'post_status' => 'publish',
			);
			wp_update_post($data);
			$post_approved_before = get_post_meta($post_id,"post_approved_before",true);
			if ($post_approved_before != "yes") {
				update_post_meta($post_id,'post_approved_before',"yes");
				wpqa_notifications_activities($user_id,$get_current_user_id,"",$post_id,"","approved_posts","notifications","","posts");
			}
		}
	}
endif;
add_action('wp_ajax_wpqa_agree_posts_group','wpqa_agree_posts_group');
add_action('wp_ajax_nopriv_wpqa_agree_posts_group','wpqa_agree_posts_group');
/* Update group meta */
function wpqa_update_group_meta($group_id,$meta,$value,$type = "add") {
	$update_meta = get_post_meta($group_id,$meta,true);
	if ($type == "remove") {
		if (isset($update_meta) && !empty($update_meta) && in_array($value,$update_meta)) {
			update_post_meta($group_id,$meta,wpqa_remove_item_by_value($update_meta,$value));
			$update = true;
		}
	}else {
		if (empty($update_meta)) {
			update_post_meta($group_id,$meta,array($value));
			$update = true;
		}else if (is_array($update_meta) && !in_array($value,$update_meta)) {
			update_post_meta($group_id,$meta,array_merge($update_meta,array($value)));
			$update = true;
		}
	}
	return (isset($update) && $update == true?$update:false);
}
/* Update user group meta */
function wpqa_update_user_group_meta($user_id,$meta,$value,$type = "add") {
	$update_meta = get_user_meta($user_id,$meta,true);
	if ($type == "remove") {
		if (isset($update_meta) && !empty($update_meta) && in_array($value,$update_meta)) {
			update_user_meta($user_id,$meta,wpqa_remove_item_by_value($update_meta,$value));
			$update = true;
		}
	}else {
		if (empty($update_meta)) {
			update_user_meta($user_id,$meta,array($value));
			$update = true;
		}else if (is_array($update_meta) && !in_array($value,$update_meta)) {
			update_user_meta($user_id,$meta,array_merge($update_meta,array($value)));
			$update = true;
		}
	}
	return (isset($update) && $update == true?$update:false);
}
/* Update moderator */
function wpqa_update_moderator_group($group_id,$user_id,$type = "add") {
	if ($type == "add") {
		$update = wpqa_update_group_meta($group_id,"group_moderators",$user_id);
		if ($update == true) {
			wpqa_notifications_activities($user_id,"","",$group_id,"","add_group_moderator","notifications","","group");
		}
	}else {
		$update = wpqa_update_group_meta($group_id,"group_moderators",$user_id,"remove");
		if ($update == true) {
			wpqa_notifications_activities($user_id,"","",$group_id,"","remove_group_moderator","notifications","","group");
		}
	}
	return (isset($update) && $update == true?$update:false);
}
/* Count group users */
function wpqa_count_group_users($group_id,$type = "add",$count = 1) {
	$group_users = (int)get_post_meta($group_id,"group_users",true);
	$group_users = ($group_users != "" || $group_users > 0?$group_users:0);
	if ($type == "remove") {
		$group_users = $group_users-$count;
	}else {
		$group_users = $group_users+$count;
	}
	$group_users = update_post_meta($group_id,"group_users",($group_users <= 0?0:$group_users));
}
/* Check if can edit comments */
function wpqa_group_edit_comments($post_id,$is_super_admin,$can_edit_comment,$comment_user_id,$get_current_user_id,$edit_delete_posts_comments) {
	$group_id = get_post_meta($post_id,"group_id",true);
	$group_moderators = get_post_meta($group_id,"group_moderators",true);
	if ($is_super_admin || ($can_edit_comment == "on" && $comment_user_id > 0 && $comment_user_id == $get_current_user_id) || (isset($edit_delete_posts_comments["edit"]) && $edit_delete_posts_comments["edit"] == "edit" && is_array($group_moderators) && in_array($get_current_user_id,$group_moderators))) {
		return true;
	}
}
/* Check if can delete comments */
function wpqa_group_delete_comments($post_id,$is_super_admin,$can_delete_comment,$comment_user_id,$get_current_user_id,$edit_delete_posts_comments) {
	$group_id = get_post_meta($post_id,"group_id",true);
	$group_moderators = get_post_meta($group_id,"group_moderators",true);
	if ($is_super_admin || ($can_delete_comment == "on" && $comment_user_id > 0 && $comment_user_id == $get_current_user_id) || (isset($edit_delete_posts_comments["delete"]) && $edit_delete_posts_comments["delete"] == "delete" && is_array($group_moderators) && in_array($get_current_user_id,$group_moderators))) {
		return true;
	}
}
/* Count group posts by type and id */
if (!function_exists('wpqa_count_group_posts_by_type')) :
	function wpqa_count_group_posts_by_type($post_type = 'post',$post_status = "publish",$group_id = 0) {
		$args = array(
			'post_type'   => $post_type,
			'post_status' => $post_status
		);
		$group_array = ($group_id > 0?array("meta_query" => array(array("type" => "numeric","key" => "group_id","value" => (int)$group_id,"compare" => "="))):array());
		$the_query = new WP_Query(array_merge($group_array,$args));
		return $the_query->found_posts;
		wp_reset_postdata();
	}
endif;
/* Invite users */
add_action("wpqa_group_invite_users","wpqa_group_invite_users",1,7);
function wpqa_group_invite_users($group_id,$is_super_admin,$post_author,$user_id,$group_invitation,$group_moderators,$group_users_array) {
	if (is_user_logged_in() && ($is_super_admin || $post_author == $user_id || (($group_invitation == "all" || $group_invitation == "admin_moderators") && is_array($group_moderators) && in_array($user_id,$group_moderators)) || ($group_invitation == "all" && is_array($group_users_array) && in_array($user_id,$group_users_array)))) {?>
		<div class="page-section add-user-form">
			<h2 class="post-title-2"><i class="icon-vcard"></i><?php esc_html_e("Invite a new user","wpqa")?></h2>
			<div class="row">
				<div class="col col9">
					<input data-id="<?php echo (int)$group_id?>" type="text" placeholder="<?php esc_html_e("Type a name or email","wpqa")?>" class="add-new-user">
					<div class="loader_2 search_loader user_loader"></div>
					<div class="search-results user-results results-empty"></div>
				</div>
				<div class="col col3 button-user-col user-col-not-activate">
					<div></div>
					<a type="text" class="button-default button-hide-click new-user-button"><?php esc_html_e("Send invite","wpqa")?></a>
				<span class="load_span"><span class="loader_2"></span></span>
				</div>
			</div>
		</div>
	<?php }
}
/* In group posts page */
function wpqa_is_group_posts() {
	if (wpqa_is_edit_groups() || is_singular("group") || wpqa_is_group_requests() || wpqa_is_group_users() || wpqa_is_group_admins() || wpqa_is_blocked_users() || wpqa_is_posts_group() || wpqa_is_view_posts_group() || wpqa_is_edit_posts_group()) {
		return true;
	}
}
/* Get group id */
function wpqa_group_id() {
	if (is_singular("group") || wpqa_is_edit_groups() || wpqa_is_group_requests() || wpqa_is_group_users() || wpqa_is_group_admins() || wpqa_is_blocked_users() || wpqa_is_posts_group() || wpqa_is_view_posts_group() || wpqa_is_edit_posts_group()) {
		if (wpqa_is_edit_groups()) {
			$group_id = (int)get_query_var(apply_filters('wpqa_edit_groups','edit_group'));
		}else if (wpqa_is_group_requests()) {
			$group_id = (int)get_query_var(apply_filters('wpqa_group_requests','group_request'));
		}else if (wpqa_is_group_users()) {
			$group_id = (int)get_query_var(apply_filters('wpqa_group_users','group_user'));
		}else if (wpqa_is_group_admins()) {
			$group_id = (int)get_query_var(apply_filters('wpqa_group_admins','group_admin'));
		}else if (wpqa_is_blocked_users()) {
			$group_id = (int)get_query_var(apply_filters('wpqa_blocked_users','blocked_user'));
		}else if (wpqa_is_posts_group()) {
			$group_id = (int)get_query_var(apply_filters('wpqa_posts_group','post_group'));
		}else if (wpqa_is_view_posts_group()) {
			$post_id = (int)get_query_var(apply_filters('wpqa_view_posts_group','view_post_group'));
			$group_id = (int)get_post_meta($post_id,"group_id",true);
		}else if (wpqa_is_edit_posts_group()) {
			$post_id = (int)get_query_var(apply_filters('wpqa_edit_posts_group','edit_post_group'));
			$group_id = (int)get_post_meta($post_id,"group_id",true);
		}else {
			global $post;
			$group_id = $post->ID;
		}
		return $group_id;
	}
}
/* Pagination in group page */
add_action('template_redirect','wpqa_group_pagination',0);
function wpqa_group_pagination() {
	if (is_singular('group')) {
		global $wp_query;
		$page = (int)$wp_query->get('page');
		if ($page > 1) {
			$wp_query->set('page',1);
			$wp_query->set('paged',$page);
		}
		remove_action('template_redirect','redirect_canonical');
	}
}?>