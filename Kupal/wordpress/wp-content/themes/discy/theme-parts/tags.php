<?php if (is_page()) {
	$tag_sort  = discy_post_meta("tag_sort");
	$tag_style = discy_post_meta("tag_style");
	$tag_order = discy_post_meta("tag_order");
	$tags_tax  = discy_post_meta("tags_tax");
	$number    = discy_post_meta("tags_per_page");
}else {
	$tag_style = discy_options("tag_style_pages");
	$tag_sort  = (isset($_GET["tag_filter"]) && $_GET["tag_filter"] != ""?esc_html($_GET["tag_filter"]):"count");
	$tag_order = "DESC";
	$tags_tax  = wpqa_search_type();
}

if ($tags_tax == 'post' || $tags_tax == 'post_tag') {
	$tag_type = 'post_tag';
	$post_type_tags = 'post';
}else {
	$tag_type = 'question_tags';
	$post_type_tags = 'question';
}

$tag_sort = (isset($_GET["tag_filter"]) && $_GET["tag_filter"] != ""?esc_html($_GET["tag_filter"]):(isset($tag_sort) && $tag_sort != ""?$tag_sort:"count"));

$discy_sidebar = discy_sidebars("sidebar_where");
$follow_category = discy_options("follow_category");

$search_value = wpqa_search();
if ($search_value != "") {
	$search_args = array('search' => $search_value);
}else {
	$search_args = array();
}

$number = (isset($number) && $number > 0?$number:apply_filters('discy_tags_per_page',4*get_option('posts_per_page',10)));
$paged  = (get_query_var("paged") != ""?(int)get_query_var("paged"):(get_query_var("page") != ""?(int)get_query_var("page"):1));
$offset     = ($paged-1)*$number;
$tag_order  = (isset($_GET["tag_filter"]) && $_GET["tag_filter"] == "name"?"ASC":$tag_order);
$tag_sort   = ($tag_sort == "followers"?"meta_value_num":$tag_sort);
$meta_query = ($tag_sort == "meta_value_num"?array('meta_query' => array("relation" => "or",array("key" => "tag_follow_count","compare" => "NOT EXISTS"),array("key" => "tag_follow_count","value" => 0,"compare" => ">="))):array());
$tags       = get_terms($tag_type,array_merge($search_args,$meta_query,array('hide_empty' => 0)));
$terms      = get_terms($tag_type,array_merge($search_args,$meta_query,array(
	'orderby'    => $tag_sort,
	'order'      => $tag_order,
	'number'     => $number,
	'offset'     => $offset,
	'hide_empty' => 0
)));

$all_tag_pages = ceil(count($tags)/$number);
if (!empty($terms) && !is_wp_error($terms)) {
	$term_list = '<div class="'.($follow_category == "on" && $tag_style == "simple_follow" && ($tags_tax == "question_tags" || $tags_tax == "question")?'row cats-sections tags-sections':'tagcloud '.($tag_style == "advanced"?"row":"tagcloud-simple")).'">';
		foreach ($terms as $term) {
			if ($follow_category == "on" && $tag_style == "simple_follow" && ($tags_tax == "question_tags" || $tags_tax == "question")) {
				$tax_id = $term->term_id;
				$tag_follow = get_term_meta($tax_id,"tag_follow",true);
				$tags_follwers = (int)(is_array($tag_follow)?count($tag_follow):0);
				$term_list .= '<div class="col '.($tags_tax == 'question_tags' && ($tag_style == "with_icon_1" || $tag_style == "with_icon_2" || $tag_style == "with_icon_3" || $tag_style == "with_icon_4")?($discy_sidebar == "full"?"col3":"col4"):($discy_sidebar == "full"?"col4":"col6")).'">
					<div class="cat-sections-follow">
					<div class="cat-sections">
						<a href="'.esc_url(get_term_link($term)).'" title="'.esc_attr(sprintf(esc_html__('View all questions under %s','discy'),$term->name)).'"><i class="icon-tag"></i>'.$term->name.'</a>
					</div>
					<div class="cat-section-follow">
						<div class="cat-follow-button"><i class="icon-users"></i><span class="follow-cat-count">'.discy_count_number($tags_follwers)."</span>"._n("Follower","Followers",$tags_follwers,"discy").'</div>
						'.(has_wpqa()?wpqa_follow_cat_button($tax_id,$user_id,'tag',true,'button-default-4','cat-section-follow','follow-cat-count'):"").'
						<div class="clearfix"></div>
					</div></div>
				</div>';
			}else {
				if ($tag_style == "advanced") {
					$term_list .= '<div class="col '.($discy_sidebar == "full"?"col3":"col4").'">
						<div class="tag-sections">
							<div class="tag-counter">';
				}
								$term_list .= '<a href="'.esc_url(get_term_link($term)).'" title="'.esc_attr(sprintf(($tags_tax == 'post' || $tags_tax == 'post_tag'?esc_html__('View all posts under %s','discy'):esc_html__('View all questions under %s','discy')),$term->name)).'">'.$term->name.'</a>';
				if ($tag_style == "advanced") {
								$term_list .= '<span> x '.discy_count_number($term->count).'</span>
							</div>
							<div class="tag-section">';
								$today = getdate();
								$tag = $term->term_id;
								$today_query = new WP_Query(array('post_type' => $post_type_tags,'year' => $today["year"],'monthnum' => $today["mon"],'day' => $today["mday"],'tax_query' => array(array('taxonomy' => $tag_type,'field' => 'term_id','terms' => $tag))));
								$week  = date('W');
								$year  = date('Y');
								$month = date('m');
								$week_query   = new WP_Query(array('post_type' => $post_type_tags,'year' => $year,'w' => $week,'tax_query' => array(array('taxonomy' => $tag_type,'field' => 'term_id','terms' => $tag))));
								$month_query  = new WP_Query(array('post_type' => $post_type_tags,'year' => $year,'monthnum' => $month,'tax_query' => array(array('taxonomy' => $tag_type,'field' => 'term_id','terms' => $tag))));
								$term_list .= "<span>".sprintf(esc_html__('%s asked today','discy'),discy_count_number($today_query->found_posts))."</span>";
								$term_list .= "<span>".sprintf(esc_html__('%s this week','discy'),discy_count_number($week_query->found_posts))."</span>";
								$term_list .= "<span>".sprintf(esc_html__('%s this month','discy'),discy_count_number($month_query->found_posts))."</span>";
							$term_list .= '</div>
						</div>
					</div>';
				}
			}
		}
	$term_list .= '</div>';
	echo ($term_list);
	if ($all_tag_pages > 1) {
		$pagination_args = array(
	    	'current'   => max(1, $paged),
	    	'total'     => $all_tag_pages,
	    	'prev_text' => '<i class="icon-left-open"></i>',
	    	'next_text' => '<i class="icon-right-open"></i>',
	    );
		if (!get_option('permalink_structure')) {
			$pagination_args['base'] = esc_url_raw(add_query_arg('paged','%#%'));
		}
		if (has_wpqa() && wpqa_is_search()) {
			$pagination_args['format'] = '?page=%#%';
		}
		echo '<div class="main-pagination"><div class="pagination">'.paginate_links($pagination_args).'</div></div>';
	}
}else {
	$no_tags = true;
}

if ($search_value != "" && isset($no_tags) && $no_tags == true) {
	include locate_template("theme-parts/search-none.php");
}?>