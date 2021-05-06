<?php get_header();
	$tag_des         = discy_options('tag_description');
	$tag_rss         = discy_options("tag_rss");
	$tag_description = tag_description();
	if ($tag_des == "on" && !empty($tag_description)) {?>
		<div class="post-section category-description">
			<h4><?php echo esc_html__("Tag","discy").": ".esc_attr(single_tag_title("", false));?></h4>
			<?php if ($tag_rss == "on") {?>
				<a class="category-rss-i tooltip-n" title="<?php esc_attr_e("Tag feed","discy")?>" href="<?php echo esc_url(get_tag_feed_link(esc_attr(get_query_var('tag_id'))))?>"><i class="icon-rss"></i></a>
			<?php }
			echo ($tag_description);?>
		</div><!-- End post -->
	<?php }
	include locate_template("theme-parts/loop.php");
get_footer();?>