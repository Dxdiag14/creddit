<?php get_header();
	$breadcrumbs     = discy_options('breadcrumbs');
	$tag_des         = discy_options('question_tag_description');
	$tag_rss         = discy_options("question_tag_rss");
	$tag_description = tag_description();
	$tax_id          = get_term_by('slug',get_query_var('term'),esc_attr(get_query_var('taxonomy')));
	$category_id     = $tax_id->term_id;
	if ($tag_des == "on" && !empty($tag_description)) {?>
		<div class="question-category post-section category-description">
			<h4><?php echo esc_html__("Tag","discy").": ".esc_attr(single_tag_title("", false));?></h4>
			<?php if ($tag_rss == "on") {?>
				<a class="category-rss-i tooltip-n" title="<?php esc_attr_e("Tag feed","discy")?>" href="<?php echo esc_url(get_tag_feed_link(esc_attr(get_query_var('tag_id'))))?>"><i class="icon-rss"></i></a>
			<?php }
			echo ($tag_description);?>
		</div><!-- End post -->
	<?php }
	if ($breadcrumbs != "on") {
		echo "<div class='follow-tag'>".wpqa_follow_cat_button($category_id,get_current_user_id(),'tag')."</div>";
	}
	$its_question  = "question";
	$paged         = (get_query_var("paged") != ""?(int)get_query_var("paged"):(get_query_var("page") != ""?(int)get_query_var("page"):1));
	$active_sticky = true;
	$custom_args   = array('tax_query' => array(array('taxonomy' => 'question_tags','field' => 'id','terms' => $category_id)));
	$show_sticky   = true;
	include locate_template("theme-parts/loop.php");
get_footer();?>