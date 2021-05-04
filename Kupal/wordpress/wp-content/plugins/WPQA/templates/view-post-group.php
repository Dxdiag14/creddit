<?php

/* @author    2codeThemes
*  @package   WPQA/templates
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
   exit; // Exit if accessed directly
}

do_action("wpqa_before_view_post_group");
$post_id = (int)get_query_var(apply_filters('wpqa_view_posts_group','view_post_group'));
$group_id = (int)get_post_meta($post_id,"group_id",true);

echo "<div class='wpqa-templates wpqa-view-post-group-template".($group_id > 0?"":" wpqa-default-template")."'>";
	if ($group_id > 0) {
		$user_id = get_current_user_id();
		$is_super_admin = is_super_admin($user_id);
		$group_moderators = get_post_meta($group_id,"group_moderators",true);
		$post_data = get_post($post_id);
		setup_postdata($post_data);
		include locate_template("theme-parts/content-group.php");
	}else {
		echo '<div class="alert-message error"><i class="icon-cancel"></i><p>'.esc_html__("Sorry no post has been selected or not found.","wpqa").'</p></div>';
	}
echo "</div>";

do_action("wpqa_after_view_post_group");?>