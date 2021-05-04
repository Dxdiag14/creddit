<?php get_header();
	do_action("discy_before_category_action");
	$category_des         = discy_options('category_description');
	$category_rss         = discy_options("category_rss");
	$category_description = category_description();
	$category_id          = (int)get_query_var('wpqa_term_id');
	if ($category_des == "on" && !empty($category_description)) {?>
		<div class="post-section category-description">
			<h4><?php echo esc_html__("Category","discy").": ".esc_attr(single_cat_title("",false));?></h4>
			<?php if ($category_rss == "on") {?>
				<a class="category-rss-i tooltip-n" title="<?php esc_attr_e("Category feed","discy")?>" href="<?php echo esc_url(get_category_feed_link($category_id))?>"><i class="icon-rss"></i></a>
			<?php }
			echo ($category_description);?>
		</div><!-- End post -->
	<?php }
	include locate_template("theme-parts/loop.php");
	do_action("discy_after_category_action");
get_footer();?>