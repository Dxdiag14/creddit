<?php

/* @author    2codeThemes
*  @package   WPQA/templates
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
   exit; // Exit if accessed directly
}

do_action("wpqa_before_posts_group");

$group_id = (int)get_query_var(apply_filters('wpqa_posts_group','post_group'));
$group_approval = esc_html(get_post_meta($group_id,"group_approval",true));
$get_group = get_post($group_id);
$user_id = get_current_user_id();
$is_super_admin = is_super_admin($user_id);
$group_moderators = get_post_meta($group_id,"group_moderators",true);
if ($is_super_admin || $get_group->post_author == $user_id || (is_array($group_moderators) && in_array($user_id,$group_moderators))) {
	do_action("wpqa_group_tabs",$group_id,$user_id,$is_super_admin,$get_group->post_author,$group_moderators);
}
echo "<div class='wpqa-templates wpqa-posts-group-template'>";
	if ($group_approval == "on") {
		echo '<div class="alert-message error"><i class="icon-cancel"></i><p>'.esc_html__("Sorry, this page is not found.","wpqa").'</p></div>';
	}else {
		if ($is_super_admin || $get_group->post_author == $user_id || (is_array($group_moderators) && in_array($user_id,$group_moderators))) {?>
			<section class="content_group row">
				<div class="col col12">
					<div>
						<?php $paged = (get_query_var("paged") != ""?(int)get_query_var("paged"):(get_query_var("page") != ""?(int)get_query_var("page"):1));
						$array_data = array("post_type" => "posts","post_status" => "draft","paged" => $paged,"meta_query" => array(array("type" => "numeric","key" => "group_id","value" => (int)$group_id,"compare" => "=")));
						$wpqa_query = new WP_Query($array_data);
						if ($wpqa_query->have_posts()) :
							$k_ad_p = -1;
							while ($wpqa_query->have_posts()) : $wpqa_query->the_post();
								$k_ad_p++;
								if (isset($GLOBALS['post'])) {
									$post_data = $post = $GLOBALS['post'];
								}
								include locate_template("theme-parts/content-group.php");
							endwhile;
						else:
							echo '<div class="alert-message warning"><i class="icon-flag"></i><p>'.esc_html__("There are no pending posts yet.","wpqa").'</p></div>';
						endif;?>
					</div>
					<?php wpqa_pagination_load("pagination","",false,false,(isset($wpqa_query)?$wpqa_query:null),false);
					wp_reset_postdata();?>
				</div>
			</section><!-- End section -->
		<?php }else {
			echo '<div class="alert-message warning"><i class="icon-flag"></i><p>'.esc_html__("Sorry, this is a private page.","wpqa").'</p></div>';
		}
	}
echo "</div>";

do_action("wpqa_after_posts_group");?>