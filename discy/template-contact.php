<?php /* Template Name: Contact us */
get_header();
	$page_id = $post_id_main = $post->ID;
	$wp_page_template = discy_post_meta("_wp_page_template",$post_id_main,false);
	echo "<div class='post-content-text'>";
		include locate_template("theme-parts/loop.php");
	echo "</div>";
get_footer();?>