<?php $tax_filter = apply_filters("discy_before_question_category",false);
$tax_question = apply_filters("discy_question_category","question-category");
$search_type = (has_wpqa() && wpqa_is_search()?wpqa_search_type():"");
$wpqa_group_id = (has_wpqa() && wpqa_plugin_version >= 4.2 && wpqa_group_id() > 0?wpqa_group_id():"");
if (is_author() || (has_wpqa() && wpqa_is_user_profile())) {
	$sidebar_style = discy_options("author_sidebar");
}

$group_sidebar = discy_options("group_sidebar");
$question_sidebar = discy_options("question_sidebar");
$post_sidebar  = discy_options("post_sidebar");
$home_page_sidebar = discy_options("sidebar_home");
if ($home_page_sidebar == "default" || $home_page_sidebar == "" || !is_active_sidebar($home_page_sidebar)) {
	$home_page_sidebar = 'sidebar_default';
}

if (is_category() || is_tax("question-category") || $tax_filter == true) {
	if (is_tax("question-category") || $tax_filter == true) {
		$tax_id      = get_term_by('slug',get_query_var('term'),$tax_question);
		$category_id = (isset($tax_id->term_id)?$tax_id->term_id:0);
	}else {
		$category_id = esc_attr(get_query_var('cat'));
	}
	$sidebar_style = discy_term_meta("cat_sidebar",$category_id);
	$sidebar_style = ($sidebar_style != ""?$sidebar_style:"default");
	if (is_category()) {
		if ($sidebar_style == "" || $sidebar_style == "default") {
			$sidebar_style = $post_sidebar;
		}
	}
	if (is_tax("question-category") || $tax_filter == true) {
		if ($sidebar_style == "" || $sidebar_style == "default") {
			$sidebar_style = $question_sidebar;
		}
	}
}

if (is_single() || $search_type == "posts" || is_page()) {
	$discy_what_sidebar = discy_post_meta("what_sidebar");
	if (is_singular("post") || $search_type == "posts") {
		if ($discy_what_sidebar == "default" || $discy_what_sidebar == "") {
			$discy_what_sidebar = $post_sidebar;
		}
	}
	if ($wpqa_group_id > 0) {
		if ($discy_what_sidebar == "default" || $discy_what_sidebar == "") {
			$discy_what_sidebar = $group_sidebar;
		}
	}
	if (is_singular("question")) {
		if ($discy_what_sidebar == "default" || $discy_what_sidebar == "") {
			$discy_what_sidebar = $question_sidebar;
		}
	}
	if (is_singular("post") || $search_type == "posts" || is_singular("question")) {
		$get_category = wp_get_post_terms($post->ID,(is_singular("question")?'question-category':'category'),array("fields" => "ids"));
		if (isset($get_category[0])) {
			$category_single_id = $get_category[0];
		}
	    if (isset($category_single_id)) {
	    	$setting_single = discy_term_meta("setting_single",$category_single_id);
	    	if ($setting_single == "on") {
	    		$discy_what_sidebar = discy_term_meta("cat_sidebar",$category_single_id);
	    		$discy_what_sidebar = ($discy_what_sidebar != ""?$discy_what_sidebar:"default");
	    	}
	    }
	}
}

$show_sidebar = apply_filters("discy_show_sidebar",true);
if ($show_sidebar == true) {
	if ((is_author() || (has_wpqa() && wpqa_is_user_profile())) && $sidebar_style != "default" && $sidebar_style != "") {
		if ($sidebar_style != "" && is_active_sidebar($sidebar_style)) {
		    dynamic_sidebar(sanitize_title($sidebar_style));
		}else {
		    dynamic_sidebar(sanitize_title($home_page_sidebar));
		}
	}else if ((is_category() || is_tax("question-category") || $tax_filter == true) && $sidebar_style != "default" && $sidebar_style != "") {
		if (is_active_sidebar($sidebar_style)) {
		    dynamic_sidebar(sanitize_title($sidebar_style));
		}else {
		    dynamic_sidebar(sanitize_title($home_page_sidebar));
		}
	}else if ((is_tag() || (is_archive() && !is_post_type_archive("question") && !is_post_type_archive("group") && !is_tax("question_tags"))) && $post_sidebar != "default" && $post_sidebar != "") {
		if (is_active_sidebar($post_sidebar)) {
		    dynamic_sidebar(sanitize_title($post_sidebar));
		}else {
		    dynamic_sidebar(sanitize_title($home_page_sidebar));
		}
	}else if ((is_tax("question_tags") || is_post_type_archive("question")) && $question_sidebar != "default" && $question_sidebar != "") {
		if (is_active_sidebar($question_sidebar)) {
		    dynamic_sidebar(sanitize_title($question_sidebar));
		}else {
		    dynamic_sidebar(sanitize_title($home_page_sidebar));
		}
	}else if (is_post_type_archive("group") && $group_sidebar != "default" && $group_sidebar != "") {
		if (is_active_sidebar($group_sidebar)) {
		    dynamic_sidebar(sanitize_title($group_sidebar));
		}else {
		    dynamic_sidebar(sanitize_title($home_page_sidebar));
		}
	}else if ((is_single() || $search_type == "posts" || is_page()) && $discy_what_sidebar != "default" && $discy_what_sidebar != "") {
		if (is_active_sidebar($discy_what_sidebar)) {
		    dynamic_sidebar(sanitize_title($discy_what_sidebar));
		}else {
		    dynamic_sidebar(sanitize_title($home_page_sidebar));
		}
	}else  {
	    dynamic_sidebar(sanitize_title($home_page_sidebar));
	}
}else {
	do_action("discy_sidebar");
}?>