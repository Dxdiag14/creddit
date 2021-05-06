<?php if ($under_construction == "on") {
	$register_style     = "style_2";
	$register_headline  = discy_options("construction_headline");
	$register_paragraph = discy_options("construction_paragraph");
	$construction_redirect = discy_options("construction_redirect");
	if ($construction_redirect != "") {
		wp_redirect(esc_url($construction_redirect));
		die();
	}
}else if (isset($wp_page_template) && $wp_page_template == "template-landing.php") {
	$home_page = (int)discy_post_meta("home_page");
	if (is_user_logged_in()) {
		if (is_home() || is_front_page()) {
			if ($home_page != "" && $home_page > 0) {
				wp_redirect(esc_url(get_permalink($home_page)));
				exit;
			}
		}else {
			wp_redirect(esc_url(home_url('/')));
			exit;
		}
	}
	$register_style     = discy_post_meta("register_style");
	$register_menu      = discy_post_meta("register_menu");
	$register_headline  = discy_post_meta("register_headline");
	$register_paragraph = discy_post_meta("register_paragraph");

	$custom_logo = discy_post_meta("custom_logo");
	if ($custom_logo == "on") {
		$logo_display = "custom_image";
		$logo_img     = discy_image_url_id(discy_post_meta("logo_landing"));
		$retina_logo  = discy_image_url_id(discy_post_meta("logo_landing_retina"));
		$logo_height  = discy_post_meta("logo_landing_height");
		$logo_width   = discy_post_meta("logo_landing_width");
	}
}else {
	$register_style     = discy_options("register_style");
	$register_menu      = discy_options("register_menu");
	$register_headline  = discy_options("register_headline");
	$register_paragraph = discy_options("register_paragraph");
}
$its_not_login = true;
$footer_copyrights = discy_options("footer_copyrights");?>
<div class="login-page-cover"></div>
<div class="login-opacity"></div>
<div class="the-main-container">
	<?php if ($under_construction != "on") {?>
		<header class="header-login">
			<?php include locate_template("theme-parts/logo.php");?>
			<nav class="nav float_r" itemscope="" itemtype="https://schema.org/SiteNavigationElement">
				<h3 class="screen-reader-text"><?php echo esc_attr(get_bloginfo('name','display'))?> <?php esc_html_e('Navigation','discy')?></h3>
				<?php wp_nav_menu(array('container' => '','container_class' => 'nav top-nav clearfix','menu' => $register_menu));?>
			</nav><!-- End nav -->
			<div class="mobile-menu">
				<div class="mobile-menu-click" data-menu="mobile-menu-main">
					<i class="icon-menu"></i>
				</div>
			</div><!-- End mobile-menu -->
		</header>
		<?php include locate_template("includes/mobile-menu.php");
	}
	$confirm_email = (has_wpqa()?wpqa_users_confirm_mail():"");?>
	<main class="discy-login-wrap<?php echo (isset($_POST["form_type"]) && $_POST["form_type"] == "wpqa-signup"?" discy-signup-wrap":"").($confirm_email == "yes" || $register_style == "style_2"?" discy-login-2":"").($under_construction == "on"?" under-construction":"")?>">
		<?php do_action("wpqa_show_session");
		if ($under_construction != "on" && $confirm_email == "yes") {
			wpqa_check_user_account(true);
		}else {?>
			<div class="<?php echo ($register_style == "style_2"?"":"centered")?>">
				<div class="login-text-col <?php echo ($register_style == "style_2"?"col12":"col8")?>">
					<?php if ($register_headline != "") {?>
						<h2><?php echo stripslashes($register_headline)?></h2>
					<?php }
					if ($register_paragraph != "") {?>
						<p><?php echo do_shortcode(stripslashes($register_paragraph))?></p>
					<?php }?>
				</div>
				<?php if ($under_construction != "on") {?>
					<div class="login-forms-col col4<?php echo ($register_style != "style_2"?"":" col4-offset")?>">
						<?php if (has_wpqa()) {
							wpqa_head_content("login",$its_not_login);
						}?>
					</div>
				<?php }?>
			</div>
		<?php }?>
	</main>
	<footer class="footer-login"><p class="copyrights"><?php echo stripslashes($footer_copyrights)?></p></footer>
</div>