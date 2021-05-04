<?php

/* @author    2codeThemes
*  @package   WPQA/templates
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
   exit; // Exit if accessed directly
}

do_action("wpqa_before_group_admins");

$group_id = (int)get_query_var(apply_filters('wpqa_group_admins','group_admin'));
$post = get_post($group_id);
$user_id = get_current_user_id();
$is_super_admin = is_super_admin($user_id);
$group_moderators = get_post_meta($group_id,"group_moderators",true);
if ($is_super_admin || $post->post_author == $user_id || (is_array($group_moderators) && in_array($user_id,$group_moderators))) {
	do_action("wpqa_group_tabs",$group_id,$user_id,$is_super_admin,$post->post_author,$group_moderators);
}
if ($is_super_admin || $post->post_author == $user_id) {?>
	<div class="page-section add-user-form">
		<h2 class="post-title-2"><i class="icon-vcard"></i><?php esc_html_e("Assign a new moderator","wpqa")?></h2>
		<div class="row">
			<div class="col col9">
				<input data-id="<?php echo (int)$group_id?>" type="text" placeholder="<?php esc_html_e("Type a name or email","wpqa")?>" class="add-new-user add-new-moderator">
				<div class="loader_2 search_loader user_loader"></div>
				<div class="search-results user-results results-empty"></div>
			</div>
			<div class="col col3 button-user-col user-col-not-activate">
				<div></div>
				<a type="text" class="button-default button-hide-click new-user-button new-moderator-button"><?php esc_html_e("Add","wpqa")?></a>
			<span class="load_span"><span class="loader_2"></span></span>
			</div>
		</div>
	</div>
<?php }
echo "<div class='wpqa-templates wpqa-group-admins-template wpqa-default-template'>";
	if ($is_super_admin || $post->post_author == $user_id) {
		$wpqa_sidebar = wpqa_sidebars("sidebar_where");
		if (isset($group_moderators) && is_array($group_moderators) && !empty($group_moderators)) {
			echo "<div id='section-group-admins' class='user-section row user-not-normal user-section-columns'>";
				$number        = wpqa_options("users_per_page");
				$number        = (isset($number) && $number > 0?$number:get_option('posts_per_page'));
				$number        = apply_filters('users_per_page',$number);
				$paged         = (get_query_var("paged") != ""?(int)get_query_var("paged"):(get_query_var("page") != ""?(int)get_query_var("page"):1));
				$offset		   = ($paged-1)*$number;
				$users		   = get_users(array('include' => $group_moderators));
				$query         = get_users(array('offset' => $offset,'number' => $number,'include' => $group_moderators));
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
					echo "<div class='col ".$user_col."'>".wpqa_author($user->ID,"columns","","","","","","","","",$group_id,"moderators",array(),$group_moderators,$post->post_author)."</div>";
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
				echo '<div class="alert-message warning"><i class="icon-flag"></i><p>'.esc_html__("Group doesn't have any admins yet.","wpqa").'</p></div>';
			}
		}else {
			echo '<div class="alert-message warning"><i class="icon-flag"></i><p>'.esc_html__("Group doesn't have any admins yet.","wpqa").'</p></div>';
		}
	}else {
		echo '<div class="alert-message warning"><i class="icon-flag"></i><p>'.esc_html__("Sorry, this is a private page.","wpqa").'</p></div>';
	}
echo "</div>";

do_action("wpqa_after_group_admins");?>