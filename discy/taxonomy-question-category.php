<?php get_header();
	$active_cover_category = discy_options('active_cover_category');
	$category_des          = discy_options('question_category_description');
	$category_rss          = discy_options("question_category_rss");
	$category_description  = category_description();
	$category_id           = (int)get_query_var('wpqa_term_id');
	$custom_cat_cover      = get_term_meta($category_id,prefix_terms."custom_cat_cover",true);
	if ($custom_cat_cover == "on") {
		$active_cover_category = get_term_meta($category_id,prefix_terms."cat_cover",true);
	}
	if ($active_cover_category != "on" && $category_des == "on" && !empty($category_description)) {?>
		<div class="question-category post-section category-description">
			<h4><?php echo esc_html__("Category","discy").": ".esc_attr(single_cat_title("",false));?></h4>
			<?php if ($category_rss == "on") {?>
				<a class="category-rss-i tooltip-n" title="<?php esc_attr_e("Category feed","discy")?>" href="<?php echo esc_url(get_term_feed_link($category_id,"question-category"))?>"><i class="icon-rss"></i></a>
			<?php }
			echo ($category_description);?>
		</div><!-- End post -->
	<?php }
	$its_question  = "question";
	$paged         = (get_query_var("paged") != ""?(int)get_query_var("paged"):(get_query_var("page") != ""?(int)get_query_var("page"):1));
	$custom_args   = apply_filters("discy_args_tax_question_category",array('tax_query' => array(array('taxonomy' => 'question-category','field' => 'id','terms' => $category_id))),$category_id);
	$tabs_category = discy_options("tabs_category");
	if ($tabs_category == "on") {
		$exclude_categories = discy_options("exclude_categories");
		$exclude_categories = ($exclude_categories != ""?explode(",",$exclude_categories):array());
		$tab_category = (is_array($exclude_categories) && !in_array($category_id,$exclude_categories)?true:false);
	}
	if (isset($tab_category) && $tab_category == true) {
		include locate_template("theme-parts/tabs.php");
	}else {
		$active_sticky = $show_sticky = true;
		include locate_template("theme-parts/loop.php");
	}
get_footer();?>