<?php get_header();
	do_action("discy_before_archive_action");
	include locate_template("theme-parts/loop.php");
	do_action("discy_after_archive_action");
get_footer();?>