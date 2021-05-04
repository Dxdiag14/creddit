<?php get_header();
	$its_question  = "question";
	$paged         = (get_query_var("paged") != ""?(int)get_query_var("paged"):(get_query_var("page") != ""?(int)get_query_var("page"):1));
	$active_sticky = true;
	$custom_args   = array("post_type" => "question");
	$show_sticky   = true;
	include locate_template("theme-parts/loop.php");
get_footer();?>