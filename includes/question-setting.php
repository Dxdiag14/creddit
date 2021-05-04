<?php $tax_filter        = apply_filters("discy_before_question_category",false);
$tax_question            = apply_filters("discy_question_category","question-category");
$post_id_main            = (isset($post_id_main)?$post_id_main:"");
$question_columns        = discy_options("question_columns");
$masonry_style           = discy_options("masonry_style");
$question_simple         = discy_options("question_simple");
$question_meta_icon      = discy_options("question_meta_icon");
$question_meta           = discy_options("question_meta");
$author_image            = discy_options("author_image");
$author_image_single     = discy_options("author_image_single");
$vote_question_loop      = discy_options("vote_question_loop");
$question_loop_dislike   = discy_options("question_loop_dislike");
$vote_question_single    = discy_options("vote_question_single");
$question_single_dislike = discy_options("question_single_dislike");
$question_poll_loop      = discy_options("question_poll_loop");
$excerpt_type            = discy_options("excerpt_type");
$question_excerpt        = discy_options("question_excerpt");
$read_more_question      = discy_options("read_more_question");
$read_jquery_question    = discy_options("read_jquery_question");
$answer_question_jquery  = discy_options("answer_question_jquery");
$excerpt_questions       = discy_options("excerpt_questions");
$question_tags_loop      = discy_options("question_tags_loop");
$question_tags           = discy_options("question_tags");
$question_answer_loop    = discy_options("question_answer_loop");
$question_answer_show    = discy_options("question_answer_show");
$question_answer_place   = discy_options("question_answer_place");
$question_favorite       = discy_options("question_favorite");
$question_follow         = discy_options("question_follow");
$question_follow_loop    = discy_options("question_follow_loop");
$question_close          = discy_options("question_close");
$post_share              = discy_options("question_share");
$share_style             = discy_options("share_style");
$question_answers        = discy_options("question_answers");
$post_pagination         = discy_options("question_pagination");
$question_related        = discy_options("question_related");
$related_number_question = discy_options("related_number_question");
$query_related_question  = discy_options("query_related_question");
$related_title_question  = discy_options("related_title_question");
if (isset($wp_page_template) && $wp_page_template == "template-home.php") {
	$page_tamplate        = true;
	$post_pagination      = discy_post_meta("pagination_home",$post_id_main);
	$custom_home_question = discy_post_meta("custom_home_question",$post_id_main);
	if ($custom_home_question == "on") {
		$question_columns       = discy_post_meta("question_columns_h",$post_id_main);
		$masonry_style          = discy_post_meta("masonry_style_h",$post_id_main);
		$author_image           = discy_post_meta("author_image_h",$post_id_main);
		$vote_question_loop     = discy_post_meta("vote_question_loop_h",$post_id_main);
		$question_loop_dislike  = discy_post_meta("question_loop_dislike_h",$post_id_main);
		$question_poll_loop     = discy_post_meta("question_poll_loop_h",$post_id_main);
		$excerpt_questions      = discy_post_meta("excerpt_questions_h",$post_id_main);
		$question_excerpt       = discy_post_meta("question_excerpt_h",$post_id_main);
		$read_more_question     = discy_post_meta("read_more_question_h",$post_id_main);
		$read_jquery_question   = discy_post_meta("read_jquery_question_h",$post_id_main);
		$answer_question_jquery = discy_post_meta("answer_question_jquery_h",$post_id_main);
		$question_tags_loop     = discy_post_meta("question_tags_loop_h",$post_id_main);
		$question_answer_loop   = discy_post_meta("question_answer_loop_h",$post_id_main);
		$question_answer_show   = discy_post_meta("question_answer_show_h",$post_id_main);
		$question_answer_place  = discy_post_meta("question_answer_place_h",$post_id_main);
		$question_meta          = discy_post_meta("question_meta_h",$post_id_main);
		$question_follow_loop   = discy_post_meta("question_follow_loop_h",$post_id_main);
	}
}else if (isset($wp_page_template) && $wp_page_template == "template-question.php") {
	$custom_question_setting = discy_post_meta("custom_question_setting",$post_id_main);
	if ($custom_question_setting == "on") {
		$page_tamplate          = true;
		$question_columns       = discy_post_meta("question_columns",$post_id_main);
		$masonry_style          = discy_post_meta("masonry_style",$post_id_main);
		$author_image           = discy_post_meta("author_image",$post_id_main);
		$vote_question_loop     = discy_post_meta("vote_question_loop",$post_id_main);
		$question_loop_dislike  = discy_post_meta("question_loop_dislike",$post_id_main);
		$question_meta          = discy_post_meta("question_meta_q",$post_id_main);
		$question_tags_loop     = discy_post_meta("question_tags_loop",$post_id_main);
		$question_answer_loop   = discy_post_meta("question_answer_loop",$post_id_main);
		$question_answer_show   = discy_post_meta("question_answer_show",$post_id_main);
		$question_answer_place  = discy_post_meta("question_answer_place",$post_id_main);
		$question_excerpt       = discy_post_meta("question_excerpt",$post_id_main);
		$read_more_question     = discy_post_meta("read_more_question",$post_id_main);
		$read_jquery_question   = discy_post_meta("read_jquery_question",$post_id_main);
		$answer_question_jquery = discy_post_meta("answer_question_jquery",$post_id_main);
		$question_poll_loop     = discy_post_meta("question_poll_loop",$post_id_main);
		$excerpt_questions      = discy_post_meta("excerpt_questions",$post_id_main);
		$post_pagination        = discy_post_meta("question_pagination",$post_id_main);
		$post_number            = discy_post_meta("question_number",$post_id_main);
		$question_follow_loop   = discy_post_meta("question_follow_loop",$post_id_main);
	}
}else if (is_tax("question-category") || $tax_filter == true) {
	$custom_question_setting = discy_term_meta("custom_question_setting",$category_id);
	$page_tamplate  = true;
	if ($custom_question_setting == "on") {
		$question_columns       = discy_term_meta("question_columns",$category_id);
		$masonry_style          = discy_term_meta("masonry_style",$category_id);
		$question_meta          = discy_term_meta("question_meta",$category_id);
		$author_image           = discy_term_meta("author_image",$category_id);
		$vote_question_loop     = discy_term_meta("vote_question_loop",$category_id);
		$question_loop_dislike  = discy_term_meta("question_loop_dislike",$category_id);
		$question_poll_loop     = discy_term_meta("question_poll_loop",$category_id);
		$question_excerpt       = discy_term_meta("question_excerpt",$category_id);
		$question_excerpt       = ($question_excerpt != ""?$question_excerpt:"40");
		$read_more_question     = discy_term_meta("read_more_question",$category_id);
		$read_jquery_question   = discy_term_meta("read_jquery_question",$category_id);
		$answer_question_jquery = discy_term_meta("answer_question_jquery",$category_id);
		$excerpt_questions      = discy_term_meta("excerpt_questions",$category_id);
		$question_tags_loop     = discy_term_meta("question_tags_loop",$category_id);
		$question_answer_loop   = discy_term_meta("question_answer_loop",$category_id);
		$question_answer_show   = discy_term_meta("question_answer_show",$category_id);
		$question_answer_place  = discy_term_meta("question_answer_place",$category_id);
		$post_pagination        = discy_term_meta("question_pagination",$category_id);
		$post_number            = discy_term_meta("question_number",$category_id);
		$question_follow_loop   = discy_term_meta("question_follow_loop",$category_id);
	}
}else if (is_single()) {
	$custom_page_setting = discy_post_meta("custom_page_setting",$post_id_main);
	if ($custom_page_setting == "on") {
		$question_meta           = discy_post_meta("post_meta",$post_id_main);
		$author_image_single     = discy_post_meta("author_image_single",$post_id_main);
		$vote_question_single    = discy_post_meta("vote_question_single",$post_id_main);
		$question_single_dislike = discy_post_meta("question_single_dislike",$post_id_main);
		$question_tags           = discy_post_meta("post_tags",$post_id_main);
		$question_favorite       = discy_post_meta("question_favorite",$post_id_main);
		$question_follow         = discy_post_meta("question_follow",$post_id_main);
		$question_close          = discy_post_meta("question_close",$post_id_main);
		$post_share              = discy_post_meta("post_share",$post_id_main);
		$question_answers        = discy_post_meta("post_comments",$post_id_main);
		$question_related        = discy_post_meta("question_related",$post_id_main);
		$related_number_question = discy_post_meta("related_number",$post_id_main);
		$query_related_question  = discy_post_meta("query_related",$post_id_main);
		$related_title_question  = discy_post_meta("excerpt_related_title",$post_id_main);
	}
}
$post_number       = (isset($post_number) && $post_number != ""?$post_number:get_option("posts_per_page"));
$author_by         = (isset($question_meta["author_by"]) && $question_meta["author_by"] == "author_by"?"on":"");
$question_date     = (isset($question_meta["question_date"]) && $question_meta["question_date"] == "question_date"?"on":(isset($question_meta["post_date"]) && $question_meta["post_date"] == "post_date"?"on":""));
$asked_to          = (isset($question_meta["asked_to"]) && $question_meta["asked_to"] == "asked_to"?"on":"");
$category_question = (isset($question_meta["category_question"]) && $question_meta["category_question"] == "category_question"?"on":(isset($question_meta["category_post"]) && $question_meta["category_post"] == "category_post"?"on":""));
$question_views    = (isset($question_meta["question_views"]) && $question_meta["question_views"] == "question_views"?"on":"");
$question_answer   = (isset($question_meta["question_answer"]) && $question_meta["question_answer"] == "question_answer"?"on":"");
$bump_meta         = (isset($question_meta["bump_meta"]) && $question_meta["bump_meta"] == "bump_meta"?"on":"");
$discy_sidebar_all = $discy_sidebar = discy_sidebars("sidebar_where");
if ($discy_sidebar == "menu_sidebar" || $discy_sidebar == "centered" || $discy_sidebar == "menu_sidebar" || is_single() || (is_page() && (isset($wp_page_template) && $wp_page_template != "template-question.php" && $wp_page_template != "template-home.php"))) {
	$question_columns = "";
}?>