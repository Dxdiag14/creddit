<?php $site_users_only = (has_wpqa()?wpqa_site_users_only():"");
$under_construction = (has_wpqa()?wpqa_under_construction():"");
$wp_page_template = discy_post_meta("_wp_page_template","",false);
if ($site_users_only != "yes" && $under_construction != "on" && $wp_page_template != "template-landing.php") {
	include locate_template("includes/footer-code.php");
}

wp_footer();?>
</body>
</html>