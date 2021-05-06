<?php /* Template Name: Categories */
get_header();
	$page_id = $post_id_main = $post->ID;
	$wp_page_template = discy_post_meta("_wp_page_template",$post_id_main,false);
	include locate_template("theme-parts/loop.php");
get_footer();?>