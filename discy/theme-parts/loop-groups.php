<?php include locate_template("includes/group-setting.php");
$user_id = get_current_user_id();
$paged = (get_query_var("paged") != ""?(int)get_query_var("paged"):(get_query_var("page") != ""?(int)get_query_var("page"):1));
$group_display = ($group_display == "private" || $group_display == "public"?array("meta_query" => array(array("key" => "group_privacy","value" => $group_display,"compare" => "="))):array());
$group_search = (isset($group_search)?$group_search:array());
$user_groups = (wpqa_is_user_groups()?array("author" => (int)get_query_var(apply_filters('wpqa_user_id','wpqa_user_id'))):array());
$array_data = array_merge($group_search,$user_groups,$group_display,$orderby_array,array("post_type" => "group","paged" => $paged,"posts_per_page" => $group_number));
$wpqa_query = new WP_Query($array_data);
$date_format = wpqa_options("date_format");
$date_format = ($date_format?$date_format:get_option("date_format"));
$site_style = discy_options("site_style");
$site_width = discy_options("site_width");
if ($wpqa_query->have_posts()) :?>
	<section class="content_groups row group-articles post-articles">
		<?php $k_ad_p = -1;
		while ($wpqa_query->have_posts()) : $wpqa_query->the_post();
			if (isset($GLOBALS['post'])) {
				$group_data = $GLOBALS['post'];
			}
			$post_id = $group_data->ID;
			$the_title = get_the_title($post_id);
			$group_privacy = get_post_meta($post_id,"group_privacy",true);
			$group_cover = get_post_meta($post_id,"group_cover",true);
			$group_image = get_post_meta($post_id,"group_image",true);
			$group_users = (int)get_post_meta($post_id,"group_users",true);
			$group_posts = (int)get_post_meta($post_id,"group_posts",true);
			$human_time_diff = human_time_diff(get_the_time('U'), current_time('timestamp'));
			echo '<article class="'.join(' ',get_post_class("col col6",$post_id)).'">
				<div class="group-item">
					<div class="group_cover">';
						if ((is_array($group_cover) && isset($group_cover["id"])) || (!is_array($group_cover) && $group_cover != "")) {
							if ($site_style == "none") {
								$img_width = 290;
							}else {
								$img_width = 315;
							}
							if ($site_width > 1170) {
								$mins_width = ($site_width-1170);
								$img_width = round($img_width+($mins_width/2));
							}
							$img_height = 150;
							echo wpqa_get_aq_resize_img($img_width,$img_height,"",(is_array($group_cover) && isset($group_cover["id"])?$group_cover["id"]:$group_cover),"no",$the_title);
						}
					echo '</div>
					<div class="group_avatar">';
						if ((is_array($group_image) && isset($group_image["id"])) || (!is_array($group_image) && $group_image != "")) {
							echo wpqa_get_aq_resize_img(86,86,"",(is_array($group_image) && isset($group_image["id"])?$group_image["id"]:$group_image),"no",$the_title);
						}else {
							echo '<div class="group_img"></div>';
						}
					echo '</div>
					<div class="group_title">
						<h3><a href="'.get_permalink($post_id).'">'.$the_title.'</a></h3>
						<small>'.($group_privacy == "public"?"<i class='icon-lock-open'></i>".esc_html__("Public group","discy"):"<i class='icon-lock'></i>".esc_html__("Private group","discy")).'</small>
					</div>
					<div class="group_statistics">
						<div class="tooltip-n" title="'.sprintf(_n("%s Post","%s Posts",$group_posts,"discy"),wpqa_count_number($group_posts)).'"><i class="icon-trophy"></i></div>
						<div class="tooltip-n" title="'.sprintf(_n("%s User","%s Users",$group_users,"discy"),wpqa_count_number($group_users)).'"><i class="icon-users"></i></div>
						<div class="tooltip-n" title="'.$human_time_diff." ".esc_html__("ago","discy").'"><i class="icon-lifebuoy"></i></div>
					</div>
				</div>
			</article>';
		endwhile;?>
	</section><!-- End section -->
	<?php wpqa_pagination_load(($group_pagination != ""?$group_pagination:"pagination"),(isset($wpqa_query->max_num_pages)?$wpqa_query->max_num_pages:""),false,"group",$wpqa_query,false);
else:
	echo '<div class="alert-message warning"><i class="icon-flag"></i><p>'.esc_html__("There are no groups yet.","discy").'</p></div>';
endif;?>
<?php wp_reset_postdata();?>