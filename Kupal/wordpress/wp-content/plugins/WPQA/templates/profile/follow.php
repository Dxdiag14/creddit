<?php

/* @author    2codeThemes
*  @package   WPQA/templates/profile
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
   exit; // Exit if accessed directly
}

$wpqa_sidebar = wpqa_sidebars("sidebar_where");
$last_one = (isset($last_one) && $last_one != ""?$last_one:"");
if (wpqa_is_user_followers() || wpqa_is_user_following() || $last_one == "followers" || $last_one == "following") {
	$user_style_pages = wpqa_options("user_style_pages");
	$masonry_user_style = wpqa_options("masonry_user_style");
	$follow_user = get_user_meta($wpqa_user_id,(wpqa_is_user_followers() || $last_one == "followers"?"following_you":"following_me"),true);
	if (isset($follow_user) && is_array($follow_user) && !empty($follow_user)) {
		echo "<div id='section-".wpqa_user_title()."' class='section-page-div user-section user-section-".$user_style_pages.($user_style_pages == "grid" || $user_style_pages == "small" || $user_style_pages == "columns" || $user_style_pages == "small_grid"?" row".($masonry_user_style == "on"?" users-masonry":""):"").($user_style_pages != "normal"?" user-not-normal":"")."'>";
			$number        = wpqa_options("users_per_page");
			$number        = (isset($number) && $number > 0?$number:get_option('posts_per_page'));
			$number        = apply_filters('users_per_page',$number);
			$paged         = (get_query_var("paged") != ""?(int)get_query_var("paged"):(get_query_var("page") != ""?(int)get_query_var("page"):1));
			$offset		   = ($paged-1)*$number;
			$users		   = get_users(array('include' => $follow_user,'orderby' => 'registered'));
			$query         = get_users(array('offset' => $offset,'number' => $number,'include' => $follow_user,'orderby' => 'registered'));
			$total_users   = count($users);
			$total_query   = count($query);
			$total_pages   = (int)ceil($total_users/$number);
			$current       = max(1,$paged);
			$user_col = "col6";
			if (($user_style_pages == "columns" && ($wpqa_sidebar == "sidebar" || $wpqa_sidebar == "centered" || $wpqa_sidebar == "menu_left")) || ($user_style_pages == "small_grid" && $wpqa_sidebar != "full")) {
				$user_col = "col4";
			}else if ($wpqa_sidebar == "full") {
				$user_col = "col3";
			}
			foreach ($query as $user) {
				$owner_follow = false;
				if (get_current_user_id() == $user->ID) {
					$owner_follow = true;
				}
				echo ($user_style_pages == "grid" || $user_style_pages == "small" || $user_style_pages == "columns" || $user_style_pages == "small_grid"?"<div class='col ".$user_col.($masonry_user_style == "on"?" user-masonry":"")."'>":"");
					do_action("wpqa_author",array("user_id" => $user->ID,"author_page" => $user_style_pages,"owner" => $owner_follow));
				echo ($user_style_pages == "grid" || $user_style_pages == "small" || $user_style_pages == "columns" || $user_style_pages == "small_grid"?"</div>":"");
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
			echo '<div class="'.($user_style_pages == "grid" || $user_style_pages == "small" || $user_style_pages == "columns" || $user_style_pages == "small_grid"?"pagination-users ":"").'main-pagination"><div class="pagination">'.paginate_links($pagination_args).'</div></div>';
		}
		if (empty($query)) {
			echo '<div class="alert-message warning"><i class="icon-flag"></i><p>'.(wpqa_is_user_followers() || $last_one == "followers"?esc_html__("User doesn't have any followers yet.","wpqa"):esc_html__("User doesn't follow anyone.","wpqa")).'</p></div>';
		}
	}else {
		echo '<div class="alert-message warning"><i class="icon-flag"></i><p>'.(wpqa_is_user_followers() || $last_one == "followers"?esc_html__("User doesn't have any followers yet.","wpqa"):esc_html__("User doesn't follow anyone.","wpqa")).'</p></div>';
	}
}?>