<?php

/* @author    2codeThemes
*  @package   WPQA/templates
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
   exit; // Exit if accessed directly
}

do_action("wpqa_before_group_requests");

$group_id = (int)get_query_var(apply_filters('wpqa_group_requests','group_request'));
$group_privacy = get_post_meta($group_id,"group_privacy",true);
if ($group_privacy != "public") {
	$post = get_post($group_id);
	$user_id = get_current_user_id();
	$is_super_admin = is_super_admin($user_id);
	$group_moderators = get_post_meta($group_id,"group_moderators",true);
	if ($is_super_admin || $post->post_author == $user_id || (is_array($group_moderators) && in_array($user_id,$group_moderators))) {
		do_action("wpqa_group_tabs",$group_id,$user_id,$is_super_admin,$post->post_author,$group_moderators);
	}
}
echo "<div class='wpqa-templates wpqa-group-requests-template wpqa-default-template'>";
	if ($group_privacy == "public") {
		echo '<div class="alert-message error"><i class="icon-cancel"></i><p>'.esc_html__("Sorry, this page is not found.","wpqa").'</p></div>';
	}else {
		if ($is_super_admin || $post->post_author == $user_id || (is_array($group_moderators) && in_array($user_id,$group_moderators))) {
			$wpqa_sidebar = wpqa_sidebars("sidebar_where");
			$group_requests_array = get_post_meta($group_id,"group_requests_array",true);
			if (isset($group_requests_array) && is_array($group_requests_array) && !empty($group_requests_array)) {
				echo "<div class='group_approve_decline group_approve_decline_all'>
					<div class='cover_loader wpqa_hide'><div class='small_loader loader_2'></div></div>
					<a href='#' class='button-default approve_request_all_group' data-id='".$group_id."'>".esc_html__("Approve All","wpqa")."</a>
					<a href='#' class='button-default decline_request_all_group' data-id='".$group_id."'>".esc_html__("Decline All","wpqa")."</a>
				</div>
				<div id='section-group-requests' class='user-section row user-not-normal user-section-columns'>";
					$number        = wpqa_options("users_per_page");
					$number        = (isset($number) && $number > 0?$number:get_option('posts_per_page'));
					$number        = apply_filters('users_per_page',$number);
					$paged         = (get_query_var("paged") != ""?(int)get_query_var("paged"):(get_query_var("page") != ""?(int)get_query_var("page"):1));
					$offset		   = ($paged-1)*$number;
					$users		   = get_users(array('include' => $group_requests_array));
					$query         = get_users(array('offset' => $offset,'number' => $number,'include' => $group_requests_array));
					$total_users   = count($users);
					$total_query   = count($query);
					$total_pages   = (int)ceil($total_users/$number);
					$current       = max(1,$paged);
					$user_col = "col6";
					if ($wpqa_sidebar == "sidebar" || $wpqa_sidebar == "centered" || $wpqa_sidebar == "menu_left") {
						$user_col = "col4";
					}else if ($wpqa_sidebar == "full") {
						$user_col = "col3";
					}
					foreach ($query as $user) {
						echo "<div class='col ".$user_col."'>".wpqa_author($user->ID,"columns","","","","","","","","",$group_id)."</div>";
					}
				echo "</div>";
				
				if ($total_users > $total_query) {
					$pagination_args = array(
						'current'   => $current,
						'show_all'  => false,
						'total'     => $total_pages,
						'prev_text' => '<i class="icon-left-open"></i>',
						'next_text' => '<i class="icon-right-open"></i>',
					);
					if (!get_option('permalink_structure')) {
						$pagination_args['base'] = esc_url_raw(add_query_arg('paged','%#%'));
					}
					echo '<div class="pagination-users main-pagination"><div class="pagination">'.paginate_links($pagination_args).'</div></div>';
				}
				if (empty($query)) {
					echo '<div class="alert-message warning"><i class="icon-flag"></i><p>'.esc_html__("Group doesn't have any user requests yet.","wpqa").'</p></div>';
				}
			}else {
				echo '<div class="alert-message warning"><i class="icon-flag"></i><p>'.esc_html__("Group doesn't have any user requests yet.","wpqa").'</p></div>';
			}
		}else {
			echo '<div class="alert-message warning"><i class="icon-flag"></i><p>'.esc_html__("Sorry, this is a private page.","wpqa").'</p></div>';
		}
	}
echo "</div>";

do_action("wpqa_after_group_requests");?>