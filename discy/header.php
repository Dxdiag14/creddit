<?php $site_users_only = (has_wpqa()?wpqa_site_users_only():"");
$under_construction = (has_wpqa()?wpqa_under_construction():"");
$wp_page_template = discy_post_meta("_wp_page_template","",false);?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="<?php echo ($site_users_only == "yes" || $under_construction == "on" || $wp_page_template == "template-landing.php"?"site-html-login ":"")?>no-js no-svg">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head();?>
</head>
<body <?php body_class();echo (is_singular('question')?' itemscope itemtype="https://schema.org/QAPage"':'')?>>
	<?php $logo_display = discy_options("logo_display");
	$logo_img    = discy_image_url_id(discy_options("logo_img"));
	$retina_logo = discy_image_url_id(discy_options("retina_logo"));
	$logo_height = discy_options("logo_height");
	$logo_width  = discy_options("logo_width");
	if ($site_users_only == "yes" || $under_construction == "on" || $wp_page_template == "template-landing.php") {
		include locate_template("includes/login-page.php");
		get_footer();
		die();
	}else {
		include locate_template("includes/header-code.php");
	}?>