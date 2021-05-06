<?php
if (is_admin() && isset($_GET['activated']) && $pagenow == "themes.php") {
	flush_rewrite_rules(true);
	wp_redirect(admin_url('admin.php?page=registration'));
	exit;
}
define('discy_framework_dir',get_template_directory_uri().'/admin/');

/* Require files */
require_once locate_template("admin/plugins/class-tgm-plugin-activation.php");
require_once locate_template("admin/plugins/plugins.php");
require_once locate_template("admin/includes/fields.php");
require_once locate_template("admin/options.php");
require_once locate_template("admin/terms.php");
require_once locate_template("admin/meta.php");
require_once locate_template("admin/author.php");
require_once locate_template("admin/widgets.php");
require_once locate_template("admin/functions/admin_ajax.php");
require_once locate_template("admin/functions/main_functions.php");
require_once locate_template("admin/options-class.php");
require_once locate_template("admin/functions/resizer.php");
require_once locate_template("admin/functions/widget_functions.php");
require_once locate_template("admin/functions/nav_menu.php");
require_once locate_template("admin/functions/register_post.php");
require_once locate_template("admin/functions/meta_setting.php");

/* Updater */
require_once get_template_directory().'/admin/updater/elitepack-config.php';

/* Mobile */
require_once get_template_directory().'/admin/mobile/mobile-options.php';

/* Demo */
require get_template_directory().'/admin/demos/one-click-demo-import/one-click-demo-import.php';
require_once get_template_directory().'/admin/demos/demos.php';

/* Widgets */
include locate_template("admin/widgets/about.php");
include locate_template("admin/widgets/adv-120x240.php");
include locate_template("admin/widgets/adv-120x600.php");
include locate_template("admin/widgets/adv-125x125.php");
include locate_template("admin/widgets/adv-234x60.php");
include locate_template("admin/widgets/adv-250x250.php");
include locate_template("admin/widgets/counter.php");
include locate_template("admin/widgets/facebook.php");
include locate_template("admin/widgets/social.php");
include locate_template("admin/widgets/subscribe.php");
include locate_template("admin/widgets/twitter.php");
include locate_template("admin/widgets/video.php");

/* Body classes */
add_filter('body_class','discy_body_classes');
if (!function_exists('discy_body_classes')) {
	function discy_body_classes($classes) {
		if (is_single() || is_page()) {
			$custom_page_setting = discy_post_meta("custom_page_setting");
			if (is_singular("question")) {
				$question_answers = discy_options("question_answers");
				if ($custom_page_setting == "on") {
					$question_answers = discy_post_meta("post_comments");
				}
				
				if ((comments_open() || get_comments_number()) && $question_answers == "on") {
					// Answers
				}else {
					$classes[] = 'question-no-answers';
				}
			}
			
			if (isset($custom_page_setting) && $custom_page_setting == "on") {
				$breadcrumbs = discy_post_meta("breadcrumbs");
			}else {
				$breadcrumbs = discy_options("breadcrumbs");
			}
			
			$classes[] = ($breadcrumbs == "on"?"page-with-breadcrumbs":"page-no-breadcrumbs");
		}
		
		if ((is_page() || is_single()) && !is_home() && !is_front_page()) {
			$classes[] = 'single_page';
			if (!is_page_template()) {
				$classes[] = 'single_page_no';
			}
		}
		$site_users_only = (has_wpqa()?wpqa_site_users_only():"");
		$under_construction = (has_wpqa()?wpqa_under_construction():"");
		$wp_page_template = discy_post_meta("_wp_page_template","",false);
		$classes[] = ($wp_page_template == "template-landing.php" || $under_construction == "on" || $site_users_only == "yes"?"main_users_only":"main_for_all");
		$active_lightbox = discy_options("active_lightbox");
		if ($active_lightbox == "on") {
			$classes[] = 'active-lightbox';
		}
		
		$discoura_style = discy_options("discoura_style");
		if ($discoura_style == "on") {
			$classes[] = "discoura";
		}
		$site_style = discy_options("site_style");
		if ($site_style == "none") {
			$classes[] = "discy-not-boxed";
		}
		if ($site_style == "style_1" || $site_style == "style_2" || $site_style == "style_3" || $site_style == "style_4") {
			$classes[] = "discy-boxed";
		}
		if ($site_style == "style_1") {
			$classes[] = "discy-boxed-1";
		}
		if ($site_style == "style_2") {
			$classes[] = "discy-boxed-2";
		}
		if ($site_style == "style_3" || $site_style == "style_4") {
			$classes[] = "discy-boxed-3";
		}
		if ($site_style == "style_4") {
			$classes[] = "discy-boxed-4";
		}
		if ($site_style == "style_2" || $site_style == "style_4") {
			$classes[] = "discy-boxed-mix";
		}
		$site_width = discy_options("site_width");
		if ($site_width >= 1180) {
			$classes[] = "discy-custom-width";
		}
		$left_area = discy_options("left_area");
		if ($left_area == "sidebar") {
			$classes[] = "discy-left-sidebar";
		}
		return $classes;
	}
}
/* discy_scripts_styles */
function discy_fonts_url() {
	$font_url = '';
	$show_fonts = apply_filters("discy_show_fonts",true);
	if ($show_fonts == true) {
		if ('off' !== _x('on','Google font: on or off','discy')) {
			$main_font   = discy_options("main_font");
			$second_font = discy_options("second_font");
			$earlyaccess_main = discy_earlyaccess_fonts($main_font["face"]);
			$earlyaccess_second = discy_earlyaccess_fonts($second_font["face"]);
			$safe_fonts  = array(
				'arial'      => 'Arial',
				'verdana'    => 'Verdana',
				'trebuchet'  => 'Trebuchet',
				'times'      => 'Times New Roman',
				'tahoma'     => 'Tahoma',
				'geneva'     => 'Geneva',
				'georgia'    => 'Georgia',
				'palatino'   => 'Palatino',
				'helvetica'  => 'Helvetica',
				'museo_slab' => 'Museo Slab'
			);
			if ((isset($second_font["face"]) && $earlyaccess_second != "earlyaccess" && (($second_font["face"] != "Default font" && $second_font["face"] != "default" && $second_font["face"] != "") || $second_font["face"] == "default" || $second_font["face"] == "Default font" || $second_font["face"] == "") && !in_array($second_font["face"],$safe_fonts)) || (isset($main_font["face"]) && $earlyaccess_main != "earlyaccess" && (($main_font["face"] != "Default font" && $main_font["face"] != "default" && $main_font["face"] != "") || $main_font["face"] == "default" || $main_font["face"] != "Default font" || $main_font["face"] == "") && !in_array($main_font["face"],$safe_fonts))) {
				$font_url = add_query_arg('family',urlencode((is_rtl()?"'Droid Arabic Kufi',":"")."'".(isset($second_font["face"]) && $second_font["face"] != "Default font" && $second_font["face"] != "default" && $second_font["face"] != ""?str_ireplace("+"," ",$second_font["face"]):'Open Sans').':100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i|'.(isset($main_font["face"]) && $main_font["face"] != "Default font" && $main_font["face"] != "default" && $main_font["face"] != ""?str_ireplace("+"," ",$main_font["face"]):'Roboto').':100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&amp;subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese&amp;display=swap' ),"//fonts.googleapis.com/css");
			}
		}
	}
	return $font_url;
}
/* discy_scripts_styles */
if (!function_exists('discy_scripts_styles')) {
	function discy_scripts_styles() {
		do_action("discy_scripts_styles");
		$search_type = (has_wpqa() && wpqa_is_search()?wpqa_search_type():"");
		$protocol = is_ssl() ? 'https' : 'http';
		wp_enqueue_style('discy-entypo',get_template_directory_uri().'/css/entypo/entypo.css');
		wp_enqueue_style('prettyPhoto',get_template_directory_uri().'/css/prettyPhoto.css');
		$active_awesome = discy_options("active_awesome");
		if ($active_awesome == "on") {
			wp_enqueue_style('discy-font-awesome',get_template_directory_uri( __FILE__ ).'/css/fontawesome/css/fontawesome-all.min.css');
		}
		wp_enqueue_style('discy-main-style',get_template_directory_uri().'/style.css','',null,'all');
		$main_font = discy_options("main_font");
		$second_font = discy_options("second_font");
		if (isset($main_font["face"])) {
			$earlyaccess_main = discy_earlyaccess_fonts($main_font["face"]);
			if ($earlyaccess_main == "earlyaccess") {
				$main_font_style = strtolower(str_replace("+","",$main_font["face"]));
				wp_enqueue_style('discy-'.$main_font_style, $protocol.'://fonts.googleapis.com/earlyaccess/'.$main_font_style.'.css');
			}else {
				wp_enqueue_style('discy-fonts',discy_fonts_url(),array(),discy_theme_version);
			}
		}
		if (isset($second_font["face"])) {
			$earlyaccess_second = discy_earlyaccess_fonts($second_font["face"]);
			if ($earlyaccess_second == "earlyaccess") {
				$second_font_style = strtolower(str_replace("+","",$second_font["face"]));
				wp_enqueue_style('discy-'.$second_font_style, $protocol.'://fonts.googleapis.com/earlyaccess/'.$second_font_style.'.css');
			}else {
				wp_enqueue_style('discy-fonts',discy_fonts_url(),array(),discy_theme_version);
			}
		}
		$discoura_style = discy_options("discoura_style");
		$site_style = discy_options("site_style");
		$active_groups = discy_options("active_groups");
		$activate_pay_to_users = discy_options("activate_pay_to_users");
		if (is_rtl()) {
			wp_enqueue_style('discy-basic-css',get_template_directory_uri().'/css/rtl-basic.css',array(),discy_theme_version);
			wp_enqueue_style('discy-main-css',get_template_directory_uri().'/css/rtl.css',array(),discy_theme_version);
			wp_enqueue_style('discy-vars-css',get_template_directory_uri().'/css/rtl-vars.css',array(),discy_theme_version);
			if ($active_groups == "on" && has_wpqa() && wpqa_plugin_version >= 4.2 && (wpqa_is_add_groups() || wpqa_is_edit_groups() || is_singular("group") || wpqa_is_view_posts_group() || wpqa_is_edit_posts_group() || wpqa_is_group_requests() || wpqa_is_group_users() || wpqa_is_group_admins() || wpqa_is_blocked_users() || wpqa_is_posts_group() || wpqa_is_user_groups() || is_post_type_archive("group") || is_page_template("template-groups.php") || $search_type == "groups")) {
				wp_enqueue_style('discy-groups-css',get_template_directory_uri().'/css/rtl-groups.css',array(),discy_theme_version);
			}
			if ($activate_pay_to_users == "on" && has_wpqa() && wpqa_plugin_version >= 4.2 && (wpqa_is_user_financial_profile() || wpqa_is_user_withdrawals_profile()) && wpqa_is_user_owner()) {
				wp_enqueue_style('discy-edit-css',get_template_directory_uri().'/css/rtl-edit.css',array(),discy_theme_version);
			}
			if ($discoura_style == "on") {
				wp_enqueue_style('discy-discoura-css',get_template_directory_uri().'/css/rtl-discoura.css',array(),discy_theme_version);
			}
			if ($site_style == "style_1" || $site_style == "style_2" || $site_style == "style_3" || $site_style == "style_4") {
				wp_enqueue_style('discy-boxed-css',get_template_directory_uri().'/css/rtl-boxed-style.css',array(),discy_theme_version);
			}
			wp_enqueue_style('discy-responsive',get_template_directory_uri()."/css/rtl-responsive.css",array(),discy_theme_version);
			wp_enqueue_style('discy-arrows-css',get_template_directory_uri().'/css/rtl-arrows.css',array(),discy_theme_version);
		}else {
			wp_enqueue_style('discy-basic-css',get_template_directory_uri().'/css/basic.css',array(),discy_theme_version);
			wp_enqueue_style('discy-main-css',get_template_directory_uri().'/css/main.css',array(),discy_theme_version);
			wp_enqueue_style('discy-vars-css',get_template_directory_uri().'/css/vars.css',array(),discy_theme_version);
			if ($active_groups == "on" && has_wpqa() && wpqa_plugin_version >= 4.2 && (wpqa_is_add_groups() || wpqa_is_edit_groups() || is_singular("group") || wpqa_is_view_posts_group() || wpqa_is_edit_posts_group() || wpqa_is_group_requests() || wpqa_is_group_users() || wpqa_is_group_admins() || wpqa_is_blocked_users() || wpqa_is_posts_group() || wpqa_is_user_groups() || is_post_type_archive("group") || is_page_template("template-groups.php") || $search_type == "groups")) {
				wp_enqueue_style('discy-groups-css',get_template_directory_uri().'/css/groups.css',array(),discy_theme_version);
			}
			if ($activate_pay_to_users == "on" && has_wpqa() && wpqa_plugin_version >= 4.2 && (wpqa_is_user_financial_profile() || wpqa_is_user_withdrawals_profile()) && wpqa_is_user_owner()) {
				wp_enqueue_style('discy-edit-css',get_template_directory_uri().'/css/edit.css',array(),discy_theme_version);
			}
			if ($discoura_style == "on") {
				wp_enqueue_style('discy-discoura-css',get_template_directory_uri().'/css/discoura.css',array(),discy_theme_version);
			}
			if ($site_style == "style_1" || $site_style == "style_2" || $site_style == "style_3" || $site_style == "style_4") {
				wp_enqueue_style('discy-boxed-css',get_template_directory_uri().'/css/boxed-style.css',array(),discy_theme_version);
			}
			wp_enqueue_style('discy-responsive',get_template_directory_uri()."/css/responsive.css",array(),discy_theme_version);
		}
		if (is_category()) {
			$category_id = esc_attr(get_query_var('cat'));
		}
		
		$site_skin = discy_options('site_skin');
		discy_skin($site_skin);

		$tax_archive = apply_filters('discy_tax_archive',false);
		$tax_filter = apply_filters("discy_before_question_category",false);
		$tax_question = apply_filters("discy_question_category","question-category");
		$wpqa_group_id = (has_wpqa() && wpqa_plugin_version >= 4.2 && wpqa_group_id() > 0?wpqa_group_id():"");
		$search_type = (has_wpqa() && wpqa_is_search()?wpqa_search_type():"");
		$custom_css = $background_color  = $background_pattern = $background_type = $background_full = '';
		$custom_background               = array();
		
		$post_background_type            = discy_options('post_background_type');
		$post_background_pattern         = discy_options('post_background_pattern');
		$post_custom_background          = discy_options('post_custom_background');
		$post_full_screen_background     = discy_options('post_full_screen_background');
		$post_background_color           = discy_options('post_background_color');
		
		$question_background_type        = discy_options('question_background_type');
		$question_background_pattern     = discy_options('question_background_pattern');
		$question_custom_background      = discy_options('question_custom_background');
		$question_full_screen_background = discy_options('question_full_screen_background');
		$question_background_color       = discy_options('question_background_color');
		
		$group_background_type           = discy_options('group_background_type');
		$group_background_pattern        = discy_options('group_background_pattern');
		$group_custom_background         = discy_options('group_custom_background');
		$group_full_screen_background    = discy_options('group_full_screen_background');
		$group_background_color          = discy_options('group_background_color');
		
		if (is_category() || is_tag() || (is_archive() && !is_post_type_archive("question") && !is_post_type_archive("group")) || is_tax("question-category") || $tax_filter == true || $tax_archive == true || is_tax("question_tags") || is_post_type_archive("question") || is_post_type_archive("group") || $search_type == "groups" || $wpqa_group_id > 0) {
			if (is_category()) {
				$category_id = esc_attr(get_query_var('cat'));
			}else {
				$category_id = (int)get_query_var('wpqa_term_id');
			}
			if (is_category() || is_tag() || (is_archive() && !is_post_type_archive("question") && !is_post_type_archive("group") && $tax_archive != true && !is_tax("question_tags"))) {
				$background_type = $post_background_type;
			}
			if (is_tax("question-category") || $tax_filter == true || is_tax("question_tags") || is_post_type_archive("question")) {
				$background_type = $question_background_type;
			}
			if (is_post_type_archive("group") || $search_type == "groups" || $wpqa_group_id > 0) {
				$background_type = $group_background_type;
			}
			if (is_tag() || (is_archive() && !is_category() && !is_tax("question-category") && $tax_filter == true && !is_post_type_archive("question") && !is_post_type_archive("group") && $tax_archive != true && !is_tax("question_tags"))) {
				$cat_skin           = discy_options('post_skin');
				$primary_color_c    = discy_options('post_primary_color');
				$background_type    = $post_background_type;
				$background_pattern = $post_background_pattern;
				$custom_background  = $post_custom_background;
				$background_full    = $post_full_screen_background;
				$background_color   = $post_background_color;
			}else if (is_post_type_archive("group") || $search_type == "groups" || $wpqa_group_id > 0) {
				$cat_skin           = discy_options('group_skin');
				$primary_color_c    = discy_options('group_primary_color');
				$background_type    = $group_background_type;
				$background_pattern = $group_background_pattern;
				$custom_background  = $group_custom_background;
				$background_full    = $group_full_screen_background;
				$background_color   = $group_background_color;
			}else if (is_tax("question_tags") || is_post_type_archive("question")) {
				$cat_skin           = discy_options('question_skin');
				$primary_color_c    = discy_options('question_primary_color');
				$background_type    = $question_background_type;
				$background_pattern = $question_background_pattern;
				$custom_background  = $question_custom_background;
				$background_full    = $question_full_screen_background;
				$background_color   = $question_background_color;
			}else {
				$cat_skin        = discy_term_meta("cat_skin",$category_id);
				$cat_skin        = ($cat_skin != ""?$cat_skin:"default");
				$primary_color_c = discy_term_meta("cat_primary_color",$category_id);
				$background_type = discy_term_meta("cat_background_type",$category_id);
				if ($background_type == "custom_background" || $background_type == "patterns") {
					$background_type     = discy_term_meta("cat_background_type",$category_id);
					$background_pattern  = discy_term_meta("cat_background_pattern",$category_id);
					$custom_background   = discy_term_meta("cat_custom_background",$category_id);
					$background_img      = (isset($custom_background["image"])?$custom_background["image"]:"");
					$background_color    = ($background_type == "patterns"?discy_term_meta("cat_background_color",$category_id):(isset($custom_background["color"])?$custom_background["color"]:""));
					$background_repeat   = (isset($custom_background["repeat"])?$custom_background["repeat"]:"");
					$background_fixed    = (isset($custom_background["attachment"])?$custom_background["attachment"]:"");
					$background_position = (isset($custom_background["position"])?$custom_background["position"]:"");
					$background_full     = discy_term_meta("cat_full_screen_background",$category_id);
				}else if (is_category() && ($background_type == "default" || $background_type == "")) {
					$background_type    = $post_background_type;
					$background_pattern = $post_background_pattern;
					$custom_background  = $post_custom_background;
					$background_full    = $post_full_screen_background;
					$background_color   = $post_background_color;
				}else if (is_post_type_archive("group") || $search_type == "groups" || $wpqa_group_id > 0) {
					$background_type    = $group_background_type;
					$background_pattern = $group_background_pattern;
					$custom_background  = $group_custom_background;
					$background_full    = $group_full_screen_background;
					$background_color   = $group_background_color;
				}else if ((is_tax("question-category") || $tax_filter == true) && ($background_type == "default" || $background_type == "")) {
					$background_type    = $question_background_type;
					$background_pattern = $question_background_pattern;
					$custom_background  = $question_custom_background;
					$background_full    = $question_full_screen_background;
					$background_color   = $question_background_color;
				}
				
				if (is_category() || is_tax("question-category") || $tax_filter == true) {
					if (is_category()) {
						if ($primary_color_c == "" && ($cat_skin == "" || $cat_skin == "default")) {
							$primary_color_c = discy_options('post_primary_color');
						}
						if ($cat_skin == "" || $cat_skin == "default") {
							$cat_skin = discy_options('post_skin');
						}
					}
					
					if (is_tax("question-category") || $tax_filter == true) {
						if ($primary_color_c == "" && ($cat_skin == "" || $cat_skin == "default")) {
							$primary_color_c = discy_options('question_primary_color');
						}
						if ($cat_skin == "" || $cat_skin == "default") {
							$cat_skin = discy_options('question_skin');
						}
					}
				}
			}
		}else if (is_author() || (has_wpqa() && wpqa_is_user_profile())) {
			$discy_skin         = discy_options('author_skin');
			$primary_color_a    = discy_options('author_primary_color');
			$background_type    = discy_options("author_background_type");
			$custom_background  = discy_options("author_custom_background");
			$background_pattern = discy_options("author_background_pattern");
			$background_color   = discy_options("author_background_color");
			$background_full    = discy_options("author_full_screen_background");
		}else if (is_single() || $search_type == "posts" || is_page() || $wpqa_group_id > 0) {
			global $post;
			$primary_color_p        = discy_post_meta("primary_color");
			$discy_skin             = discy_post_meta("skin");
			$post_primary_color     = discy_options("post_primary_color");
			$group_primary_color    = discy_options("group_primary_color",$wpqa_group_id);
			$question_primary_color = discy_options("question_primary_color");
			$post_skin              = discy_options("post_skin");
			$group_skin             = discy_options("group_skin",$wpqa_group_id);
			$question_skin          = discy_options("question_skin");
			$background_type        = discy_post_meta("background_type");
			$background_pattern     = discy_post_meta("background_pattern");
			$custom_background      = discy_post_meta("custom_background");
			if (is_singular("post") || $search_type == "posts") {
				if ($post_primary_color != "" && $post_primary_color != "default") {
					$primary_color_p = $post_primary_color;
				}
				if ($post_skin != "" && $post_skin != "default") {
					$discy_skin = $post_skin;
				}
			}
			if ($wpqa_group_id > 0) {
				if ($group_primary_color != "" && $group_primary_color != "default") {
					$primary_color_p = $group_primary_color;
				}
				if ($group_skin != "" && $group_skin != "default") {
					$discy_skin = $group_skin;
				}
			}
			if (is_singular("question")) {
				if ($question_primary_color != "" && $question_primary_color != "default") {
					$primary_color_p = $question_primary_color;
				}
				if ($question_skin != "" && $question_skin != "default") {
					$discy_skin = $question_skin;
				}
			}
			if ($background_type != "" && $background_type != "default" && $background_type != "none") {
				$background_color   = discy_post_meta("background_color");
				$background_full    = discy_post_meta("full_screen_background");
			}else if ((is_singular("post") || $search_type == "posts") && ($background_type == "default" || $background_type == "")) {
				$background_type    = $post_background_type;
				$background_pattern = $post_background_pattern;
				$custom_background  = $post_custom_background;
				$background_full    = $post_full_screen_background;
				$background_color   = $post_background_color;
			}else if (($wpqa_group_id > 0 || $search_type == "groups" || is_post_type_archive("group")) && ($background_type == "default" || $background_type == "")) {
				$background_type    = $group_background_type;
				$background_pattern = $group_background_pattern;
				$custom_background  = $group_custom_background;
				$background_full    = $group_full_screen_background;
				$background_color   = $group_background_color;
			}else if (is_singular("question") && ($background_type == "default" || $background_type == "")) {
				$background_type    = $question_background_type;
				$background_pattern = $question_background_pattern;
				$custom_background  = $question_custom_background;
				$background_full    = $question_full_screen_background;
				$background_color   = $question_background_color;
			}
			if (is_singular("post") || $search_type == "posts" || is_singular("question")) {
				$get_category = wp_get_post_terms($post->ID,(is_singular("question")?'question-category':'category'),array("fields" => "ids"));
				if (isset($get_category[0]) && $get_category[0] != "") {
			    	$category_single_id = $get_category[0];
				}
			    if (isset($category_single_id)) {
			    	$setting_single = discy_term_meta("setting_single",$category_single_id);
			    	if ($setting_single == "on") {
			    		$discy_skin      = discy_term_meta("cat_skin",$category_single_id);
			    		$discy_skin      = ($discy_skin != ""?$discy_skin:"default");
			    		$primary_color_p = discy_term_meta("cat_primary_color",$category_single_id);
						$background_type = discy_term_meta("cat_background_type",$category_single_id);
						if ($background_type == "custom_background" || $background_type == "patterns") {
							$background_pattern  = discy_term_meta("cat_background_pattern",$category_single_id);
							$custom_background   = discy_term_meta("cat_custom_background",$category_single_id);
							$background_img      = (isset($custom_background["image"])?$custom_background["image"]:"");
							$background_color    = ($background_type == "patterns"?discy_term_meta("cat_background_color",$category_single_id):(isset($custom_background["color"])?$custom_background["color"]:""));
							$background_repeat   = (isset($custom_background["repeat"])?$custom_background["repeat"]:"");
							$background_fixed    = (isset($custom_background["attachment"])?$custom_background["attachment"]:"");
							$background_position = (isset($custom_background["position"])?$custom_background["position"]:"");
							$background_full     = discy_term_meta("cat_full_screen_background",$category_single_id);
						}
			    	}
			    }
			}
		}
		
		if ($background_type != "default" && $background_type != "") {
			$custom_css .= discy_backgrounds($custom_background,$background_type,$background_pattern,$background_color,$background_full);
		}else {
			$custom_css .= discy_backgrounds(discy_options("custom_background"),discy_options("background_type"),discy_options("background_pattern"),discy_options("background_color"),discy_options("full_screen_background"));
		}
		
		if ((is_category() || is_tag() || (is_archive() && !is_post_type_archive("question") && !is_post_type_archive("group")) || is_tax("question-category") || $tax_filter == true || $tax_archive == true || is_tax("question_tags") || is_post_type_archive("question") || is_post_type_archive("group") || $search_type == "groups" || $wpqa_group_id > 0) && $primary_color_c == "") {
			if ($cat_skin != "default" && $cat_skin != "") {
				discy_skin($cat_skin);
			}else {
				$primary_color = discy_options("primary_color");
				if ($primary_color != "") {
					$custom_css .= discy_all_css_color($primary_color);
				}
			}
		}else if ((is_category() || is_tag() || (is_archive() && !is_post_type_archive("question") && !is_post_type_archive("group")) || is_tax("question-category") || $tax_filter == true || $tax_archive == true || is_tax("question_tags") || is_post_type_archive("question") || is_post_type_archive("group") || $search_type == "groups" || $wpqa_group_id > 0) && $primary_color_c != "") {
			$custom_css .= discy_all_css_color($primary_color_c);
		}else if ((is_author() || (has_wpqa() && wpqa_is_user_profile())) && $primary_color_a == "") {
			if ($discy_skin != "default" && $discy_skin != "") {
				discy_skin($discy_skin);
			}else {
				$primary_color = discy_options("primary_color");
				if ($primary_color != "") {
					$custom_css .= discy_all_css_color($primary_color);
				}
			}
		}else if ((is_author() || (has_wpqa() && wpqa_is_user_profile())) && $primary_color_a != "") {
			$custom_css .= discy_all_css_color($primary_color_a);
		}else if ((is_single() || $search_type == "posts" || is_page()) && $primary_color_p == "") {
			if ($discy_skin != "default" && $discy_skin != "") {
				discy_skin($discy_skin);
			}else {
				$primary_color = discy_options("primary_color");
				if ($primary_color != "") {
					$custom_css .= discy_all_css_color($primary_color);
				}
			}
		}else if ((is_single() || $search_type == "posts" || is_page()) && $primary_color_p != "") {
			$custom_css .= discy_all_css_color($primary_color_p);
		}else {
			$primary_color = discy_options("primary_color");
			if ($primary_color != "") {
				$custom_css .= discy_all_css_color($primary_color);
			}
		}
		
		$site_users_only = (has_wpqa()?wpqa_site_users_only():"");
		$under_construction = (has_wpqa()?wpqa_under_construction():"");
		$wp_page_template = discy_post_meta("_wp_page_template","",false);
		if ($under_construction == "on") {
			$register_background = discy_options("construction_background");
		}else if ($wp_page_template == "template-landing.php") {
			$register_background = discy_post_meta("register_background");
		}else {
			$register_background = discy_options("register_background");
		}
		if (($site_users_only == "yes" || $under_construction == "on" || $wp_page_template == "template-landing.php") && !empty($register_background)) {
			if ($under_construction == "on") {
				$register_opacity = (int)discy_post_meta("construction_opacity");
			}else if ($wp_page_template == "template-landing.php") {
				$register_opacity = (int)discy_post_meta("register_opacity");
			}else {
				$register_opacity = (int)discy_options("register_opacity");
			}
			$register_background_color = (isset($register_background["color"])?$register_background["color"]:"");
			$register_background_image = $register_background["image"];
			if ((!empty($register_background_image) && discy_image_url_id($register_background_image) != "") || $register_background_color != "") {
				$custom_css .= '.login-page-cover {';
					if ($register_background_color != "") {
						$custom_css .= 'background-color: '.esc_attr($register_background_color).';';
					}
					if (!empty($register_background_image) && discy_image_url_id($register_background_image) != "") {
						$custom_css .= 'background-image: url("'.esc_attr(discy_image_url_id($register_background_image)).'") ;
						filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src="'.esc_attr(discy_image_url_id($register_background_image)).'",sizingMethod="scale");
						-ms-filter: "progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\''.esc_attr(discy_image_url_id($register_background_image)).'\',sizingMethod=\'scale\')";';
					}
				$custom_css .= '}';
			}
			if ($register_background_color != '') {
				$custom_css .= '.login-opacity {
					background-color: '.esc_attr($register_background_color).';';
					if ($register_opacity != '') {
						$custom_css .= '-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=esc_attr($register_opacity))";
						filter: alpha(opacity=esc_attr($register_opacity));
						-moz-opacity: '.esc_attr($register_opacity/100).';
						-khtml-opacity: '.esc_attr($register_opacity/100).';
						opacity: '.esc_attr($register_opacity/100).';';
					}
				$custom_css .= '}';
			}
		}

		$custom_call_action = discy_post_meta("custom_call_action");
		if ((is_page() || is_single()) && $custom_call_action == "on") {
			$action_image_video = discy_post_meta("action_image_video");
			$action_background = discy_post_meta("action_background");
			$action_logged = discy_post_meta("action_logged");
		}else {
			$action_image_video = discy_options("action_image_video");
			$action_background = discy_options("action_background");
			$action_logged = discy_options("action_logged");
		}

		if ($action_image_video != "video" && !empty($action_background) && ((is_user_logged_in() && ($action_logged == "logged" || $action_logged == "both")) || (!is_user_logged_in() && ($action_logged == "unlogged" || $action_logged == "both")))) {
			$action_opacity = (int)discy_options("action_opacity");
			$action_background_color = (isset($action_background["color"])?$action_background["color"]:"");
			$action_background_image = $action_background["image"];
			if ((!empty($action_background_image) && discy_image_url_id($action_background_image) != "") || $action_background_color != "") {
				$custom_css .= '.call-action-unlogged {';
					if ($action_background_color != "") {
						$custom_css .= 'background-color: '.esc_attr($action_background_color).' !important;';
					}
					if (!empty($action_background_image) && discy_image_url_id($action_background_image) != "") {
						$custom_css .= 'background-image: url("'.esc_attr(discy_image_url_id($action_background_image)).'") ;
						filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src="'.esc_attr(discy_image_url_id($action_background_image)).'",sizingMethod="scale");
						-ms-filter: "progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\''.esc_attr(discy_image_url_id($action_background_image)).'\',sizingMethod=\'scale\')";
						background-size: cover;';
					}
				$custom_css .= '}';
			}
			if ($action_background_color != '') {
				$custom_css .= '.call-action-opacity {
					'.($action_background_color != ''?'':'').'background-color: '.esc_attr($action_background_color).';';
					if ($action_opacity != '') {
						$custom_css .= '-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=esc_attr($action_opacity))";
						filter: alpha(opacity=esc_attr($action_opacity));
						-moz-opacity: '.esc_attr($action_opacity/100).';
						-khtml-opacity: '.esc_attr($action_opacity/100).';
						opacity: '.esc_attr($action_opacity/100).';';
					}
				$custom_css .= '}';
			}
		}
		
		if (discy_options("header_fixed_responsive") == "on") {
			$custom_css .= '@media only screen and (max-width: 479px) {
				.header.fixed-nav {
					position: initial !important;
				}
			}';
		}
		
		$site_width = (int)discy_options("site_width");
		if ($site_width >= 1180) {
			$custom_css .= '@media (min-width: '.($site_width+30).'px) {
				.discy-custom-width .the-main-container,
				.discy-custom-width .main_center .the-main-inner,
				.discy-custom-width .main_center .hide-main-inner,
				.discy-custom-width .main_center main.all-main-wrap,
				.discy-custom-width .main_right main.all-main-wrap,
				.discy-custom-width .main_full main.all-main-wrap,
				.discy-custom-width .main_full .the-main-inner,
				.discy-custom-width .main_full .hide-main-inner,
				.discy-custom-width .main_left main.all-main-wrap {
					width: '.$site_width.'px;
				}
				.discy-custom-width main.all-main-wrap,.discy-custom-width .menu_left .the-main-inner,.discy-custom-width .menu_left .hide-main-inner {
					width: '.(970+$site_width-1170).'px;
				}
				.discy-custom-width .the-main-inner,.discy-custom-width .hide-main-inner {
					width: '.(691+$site_width-1170).'px;
				}
				.discy-custom-width .left-header {
					width: '.(890+$site_width-1170).'px;
				}
				.discy-custom-width .mid-header {
					width: '.((685+$site_width-1170)).'px;
				}
				.discy-custom-width .main_sidebar .hide-main-inner,.discy-custom-width .main_right .hide-main-inner,.discy-custom-width .main_right .the-main-inner,.discy-custom-width .main_left .the-main-inner,.discy-custom-width .main_left .hide-main-inner,.discy-custom-width .main_left .hide-main-inner {
					width: '.(891+$site_width-1170).'px;
				}
				.discy-custom-width.discy-left-sidebar .menu_sidebar main.all-main-wrap,.discy-custom-width.discy-left-sidebar .menu_left .the-main-inner,.discy-custom-width.discy-left-sidebar .menu_left .hide-main-inner,.discy-custom-width.discy-left-sidebar .menu_left main.all-main-wrap {
					width: '.((970+$site_width-1170)-30).'px;
				}
				.discy-custom-width.discy-left-sidebar .menu_sidebar .the-main-inner,.discy-custom-width.discy-left-sidebar .menu_sidebar .hide-main-inner,.discy-custom-width.discy-left-sidebar .menu_left .hide-main-inner {
					width: '.((691+$site_width-1170)-30).'px;
				}
				.discy-custom-width.discy-left-sidebar .menu_sidebar .mid-header,.discy-custom-width.discy-left-sidebar .menu_left .mid-header {
					width: '.((685+$site_width-1170)-30).'px;
				}
			}';
		}

		$custom_sliders = discy_post_meta("custom_sliders");
		if ((is_single() || is_page()) && $custom_sliders == "on") {
			$slider_h_logged = apply_filters("discy_slider_logged",discy_post_meta("slider_h_logged"));
		}else {
			$slider_h_logged = apply_filters("discy_slider_logged",discy_options("slider_h_logged"));
		}
		if ((is_user_logged_in() && ($slider_h_logged == "logged" || $slider_h_logged == "both")) || (!is_user_logged_in() && ($slider_h_logged == "unlogged" || $slider_h_logged == "both"))) {
			$custom_sliders = discy_post_meta("custom_sliders");
			if ((is_single() || is_page()) && $custom_sliders == "on") {
				$slider_h = apply_filters("discy_slider",discy_post_meta("slider_h"));
			}else {
				$slider_h = apply_filters("discy_slider",discy_options("slider_h"));
				$slider_h_home_pages = apply_filters("discy_slider_h_home_pages",discy_options("slider_h_home_pages"));
				$slider_h_pages = apply_filters("discy_slider_home_pages",discy_options("slider_h_pages"));
				$slider_h_pages = explode(",",$slider_h_pages);
			}
			if ($slider_h == "on" && ($custom_sliders == "on" || (((is_front_page() || is_home()) && $slider_h_home_pages == "home_page") || $slider_h_home_pages == "all_pages" || ($slider_h_home_pages == "all_posts" && is_singular("post")) || ($slider_h_home_pages == "all_questions" && is_singular("question")) || ($slider_h_home_pages == "custom_pages" && is_page() && isset($slider_h_pages) && is_array($slider_h_pages) && isset($post->ID) && in_array($post->ID,$slider_h_pages))))) {
				if ((is_single() || is_page()) && $custom_sliders == "on") {
					$custom_slider = discy_post_meta("custom_slider");
				}else {
					$custom_slider = discy_options("custom_slider");
				}
				if ($custom_slider != "custom") {
					if ((is_single() || is_page()) && $custom_sliders == "on") {
						$slider_height = apply_filters("discy_slider_height",discy_post_meta("slider_height"));
					}else {
						$slider_height = apply_filters("discy_slider_height",discy_options("slider_height"));
					}
					$custom_css .= '.slider-wrap,.slider-inner {
						min-height: '.$slider_height.'px;
					}';
					if ((is_single() || is_page()) && $custom_sliders == "on") {
						$add_slides = apply_filters("discy_sliders",discy_post_meta("add_slides"));
					}else {
						$add_slides = apply_filters("discy_sliders",discy_options("add_slides"));
					}
					if (is_array($add_slides) && !empty($add_slides)) {
						foreach ($add_slides as $key => $value) {
							$color   = (isset($value["color"])?$value["color"]:"");
							$image   = (isset($value["image"])?$value["image"]:"");
							$opacity = (isset($value["opacity"])?$value["opacity"]:"");
							if ($color != '' || (!empty($image) && isset($image["id"]))) {
								$custom_css .= '.slider-item-'.$key.' .slider-inner {
									'.($color != ''?'':'').'background-color: '.esc_attr($color).';
									'.(discy_image_url_id($image) != ''?'background-image: url('.discy_image_url_id($image).');':'').
								'}';
							}
							if ($color != '' && $opacity != '' && $opacity > 0) {
								$custom_css .= '.slider-item-'.$key.' .slider-opacity {
									'.($color != ''?'':'').'background-color: '.esc_attr($color).';';
									if ($opacity != '') {
										$custom_css .= '-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=esc_attr($opacity))";
										filter: alpha(opacity=esc_attr($opacity));
										-moz-opacity: '.esc_attr($opacity/100).';
										-khtml-opacity: '.esc_attr($opacity/100).';
										opacity: '.esc_attr($opacity/100).';';
									}
								$custom_css .= '}';
							}
						}
					}
				}
			}
		}

		$cover_image = discy_options("cover_image");
		if ($cover_image == "on" && has_wpqa() && wpqa_is_user_profile() && !wpqa_is_user_edit_profile()) {
			$user_id = (int)get_query_var(apply_filters('wpqa_user_id','wpqa_user_id'));
			$cover_link = wpqa_get_user_cover_link(array("user_id" => $user_id));
			if ($cover_link != "") {
				$custom_css .= '.wpqa-cover-background {background-image: url('.$cover_link.');}';
			}
		}

		if (is_tax("question-category")) {
			$tax_id = (int)get_query_var('wpqa_term_id');
			$custom_cat_cover = get_term_meta($tax_id,prefix_terms."custom_cat_cover",true);
			if ($custom_cat_cover == "on") {
				$cat_cover = get_term_meta($tax_id,prefix_terms."cat_cover",true);
			}else {
				$cat_cover = discy_options("active_cover_category");
			}
			if (has_wpqa() && $cat_cover == "on") {
				$category_color = get_term_meta($tax_id,prefix_terms."category_color",true);
				$cover_link = wpqa_get_cat_cover_link(array("tax_id" => $tax_id,"cat_name" => esc_html(get_query_var('wpqa_term_name'))));
				if ($cover_link != "") {
					$custom_css .= '.wpqa-cover-background {background-image: url('.$cover_link.');}';
				}
				if ($category_color != "") {
					$custom_css .= '.cover-cat-span {background-color: '.$category_color.'}';
				}
			}
		}

		if (has_wpqa() && wpqa_plugin_version >= 4.2 && (wpqa_is_edit_groups() || is_singular("group") || wpqa_is_group_requests() || wpqa_is_group_users() || wpqa_is_group_admins() || wpqa_is_blocked_users() || wpqa_is_posts_group() || wpqa_is_view_posts_group() || wpqa_is_edit_posts_group())) {
			$wpqa_group_id = wpqa_group_id();
			$group_cover = discy_post_meta("group_cover",$wpqa_group_id,false);
			if (($group_cover && !is_array($group_cover)) || (is_array($group_cover) && isset($group_cover["id"]) && $group_cover["id"] != 0)) {
				$group_cover_img = wpqa_get_cover_url($group_cover,"","");
			}
			if (isset($group_cover_img) && $group_cover_img != "") {
				$custom_css .= '.group_cover {background-image: url('.$group_cover_img.');}';
			}
		}
		
		if (!is_user_logged_in()) {
			$login_style = discy_options("login_style");
			if ($login_style == "style_2") {
				$login_image = discy_image_url_id(discy_options("login_image"));
				if ($login_image != "") {
					$custom_css .= '#login-panel .panel-image-content {background-image: url('.$login_image.');}';
				}
			}

			$signup_style = discy_options("signup_style");
			if ($signup_style == "style_2") {
				$signup_image = discy_image_url_id(discy_options("signup_image"));
				if ($signup_image != "") {
					$custom_css .= '#signup-panel .panel-image-content {background-image: url('.$signup_image.');}';
				}
			}

			$pass_style = discy_options("pass_style");
			if ($pass_style == "style_2") {
				$pass_image = discy_image_url_id(discy_options("pass_image"));
				if ($pass_image != "") {
					$custom_css .= '#lost-password .panel-image-content {background-image: url('.$pass_image.');}';
				}
			}
		}
		/* Fonts */
		
		if (isset($main_font["face"]) && $main_font["face"] != "default" && $main_font["face"] != "Default font" && $main_font["face"] != "") {
			$main_font["face"] = str_replace("+"," ",$main_font["face"]);
			$custom_css .= '
			h1,h2,h3,h4,h5,h6,.post-title,.post-title-2,.post-title-3,.widget-posts .user-notifications > div > ul li div h3,.related-widget .user-notifications > div > ul li div h3,.widget-posts .user-notifications > div > ul li div h3 a,.related-widget .user-notifications > div > ul li div h3 a,.accordion .accordion-title,.button-sign-in,.button-sign-up,nav.nav ul li,.menu-tabs > ul > li > a,.nav_menu > ul li a,.nav_menu > div > ul li a,.nav_menu > div > div > ul li a,.question-content-text,.widget-title,.user-not-normal .user-area .user-content > .user-inner h4,.about-text,.widget li,.credits,.post-content-text,.button-default,.button-default-2,.button-default-3,a.meta-answer,.load-more a,.post-read-more,.question-read-less,.edit-link,.delete-link,.pop-footer,.post-contact form .form-input span,.pagination-wrap .no-comments,.user-follow-profile,.user-area .user-content > .user-inner p,.user-area .bio_editor,.category-description > p,.social-ul li .user_follow_3 a,.social-ul li.ban-unban-user a,.social-ul li a.block_message,.answers-tabs .section-title,.answers-tabs-inner li,.mobile-menu,.mobile-aside li a,.vote_result,.stats-value,.user-stats > ul > li > div > div span,.cat-sections a,.commentlist li.comment .comment-body .comment-text .comment-author a,.point-div span,.ed_button.poll_results,.ed_button.poll_polls,.comment-respond > .section-title,.fileinputs span,.no-results p,.post-author,.related-post .section-title,.navigation-content a,.alert-message,.wpqa-open-click,.question-author-un,.call-action-unlogged p,.panel-image-inner p {
				font-family: "'.$main_font["face"].'";
			}';
		}
		
		if (isset($second_font["face"]) && $second_font["face"] != "default" && $second_font["face"] != "Default font" && $second_font["face"] != "") {
			$second_font["face"] = str_replace("+"," ",$second_font["face"]);
			$custom_css .= '
			body,.section-title,textarea,input[type="text"],input[type="password"],input[type="datetime"],input[type="datetime-local"],input[type="date"],input[type="month"],input[type="time"],input[type="week"],input[type="number"],input[type="email"],input[type="url"],input[type="search"],input[type="tel"],input[type="color"],.post-meta,.article-question .post-meta,.article-question .footer-meta li,.badge-span,.widget .user-notifications > div > ul li a,.users-widget .user-section-small .user-data ul li,.user-notifications > div > ul li span.notifications-date,.tagcloud a,.wpqa_form label,.wpqa_form .lost-password,.post-contact form p,.post-contact form .form-input,.follow-count,.progressbar-title span,.poll-num span,.social-followers,.notifications-number,.widget .widget-wrap .stats-inner li .stats-text,.breadcrumbs,.points-section ul li p,.progressbar-title,.poll-num,.badges-section ul li p {
				font-family: "'.$second_font["face"].'";
			}';
		}
		
		/* General typography */
		
		$custom_css .= discy_general_typography("general_typography","body,p");
		$custom_css .= discy_general_color('general_link_color','a','color');
		
		for ($i = 1; $i <= 6; $i++) {
			$custom_css .= discy_general_typography("h".$i,"h".$i);
		}
		
		/* Post type */
		if (is_singular("post")) {
			global $post;
			
			$discy_quote_color = discy_post_meta("quote_color");
			$discy_quote_icon_color = discy_post_meta("quote_icon_color");
			$quote_icon_color = (isset($discy_quote_icon_color) && $discy_quote_icon_color != ""?"style='color:".$discy_quote_icon_color.";'":(isset($post_head_background) && $post_head_background != "" && empty($post_head_background_img)?"style='color:#FFF;'":""));
			$discy_link_icon_color = discy_post_meta("link_icon_color");
			$link_icon_color = (isset($discy_link_icon_color) && $discy_link_icon_color != ""?"style='color:".$discy_link_icon_color.";'":(isset($post_head_background) && $post_head_background != "" && empty($post_head_background_img)?"style='color:#FFF;'":""));
			$discy_link_icon_hover_color = discy_post_meta("link_icon_hover_color");
			$discy_link_hover_color = discy_post_meta("link_hover_color");
		
			$custom_css .= discy_css_post_type("quote",$discy_quote_color,$quote_icon_color,$post->ID);
			$custom_css .= discy_css_post_type("link","","",$post->ID,$link_icon_color,$discy_link_icon_hover_color,$discy_link_hover_color);
		}
		
		/* Custom CSS */
		if (is_single() || is_page()) {
			$custom_css .= discy_kses_stip(discy_post_meta("footer_css"));
		}
		
		wp_enqueue_style('discy-custom-css',get_template_directory_uri().'/css/custom.css',array(),discy_theme_version);
		wp_add_inline_style('discy-custom-css',$custom_css);
		
		wp_enqueue_script("html5",get_template_directory_uri()."/js/html5.js",array("jquery"),'1.0.0',true);
		wp_enqueue_script("modernizr",get_template_directory_uri()."/js/modernizr.js",array("jquery"),'1.0.0',true);
		wp_enqueue_script("discy-flex-menu",get_template_directory_uri()."/js/flexMenu.js",array("jquery"),'1.0.0',true);
		wp_enqueue_script("discy-scrollbar",get_template_directory_uri()."/js/scrollbar.js",array("jquery"),'1.0.0',true);
		wp_enqueue_script("discy-imagesloaded",get_template_directory_uri()."/js/imagesloaded.js",array("jquery"),'1.0.0',true);
		wp_enqueue_script("discy-theia",get_template_directory_uri()."/js/theia.js",array("jquery"),'1.0.0',true);
		wp_enqueue_script("discy-owl",get_template_directory_uri()."/js/owl.js",array("jquery"),'1.0.0',true);
		wp_enqueue_script("discy-custom-scrollbar",get_template_directory_uri()."/js/mCustomScrollbar.js",array("jquery"),'1.0.0',true);
		wp_enqueue_script("discy-match-height",get_template_directory_uri()."/js/matchHeight.js",array("jquery"),'1.0.0',true);
		wp_enqueue_script("discy-pretty-photo",get_template_directory_uri()."/js/prettyPhoto.js",array("jquery"),'1.0.0',true);
		wp_enqueue_script("discy-tabs",get_template_directory_uri()."/js/tabs.js",array("jquery"),'1.0.0',true);
		wp_enqueue_script("discy-tipsy",get_template_directory_uri()."/js/tipsy.js",array("jquery"),'1.0.0',true);
		wp_enqueue_script("discy-isotope",get_template_directory_uri()."/js/isotope.js",array("jquery"),'1.0.0',true);
		$captcha_style = discy_options("captcha_style");
		if ($captcha_style == "google_recaptcha") {
			$recaptcha_langauge = discy_options("recaptcha_langauge");
			wp_enqueue_script("discy-recaptcha", "https://www.google.com/recaptcha/api.js".($recaptcha_langauge != ""?"?hl=".$recaptcha_langauge:""),array("jquery"),'1.0.0',true);
		}
		wp_enqueue_script("discy-custom-js",get_template_directory_uri()."/js/custom.js",array("jquery"),discy_theme_version,true);
		
		if (is_singular() && comments_open() && get_option('thread_comments')) {
			wp_enqueue_script('comment-reply');
		}
	}
}
add_action('wp_enqueue_scripts','discy_scripts_styles');
function discy_backgrounds($custom_background = "",$background_type = "",$background_pattern = "",$background_color = "",$background_full = "") {
	$custom_css = '';
	if ($background_type != "none") {
		if ($background_full == "on" && $background_type != "patterns" && $background_type != "") {
			$custom_css .= '.background-cover,.main-content {';
				$background_color_s = (isset($custom_background["color"])?$custom_background["color"]:"");
				if (!empty($background_color_s)) {
					$custom_css .= 'background-color: '.esc_attr($background_color_s) .';';
				}
				if (discy_image_url_id($custom_background["image"]) != "") {
					$custom_css .= 'background-image: url("'.esc_attr(discy_image_url_id($custom_background["image"])).'") ;
					filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src="'.esc_attr(discy_image_url_id($custom_background["image"])).'",sizingMethod="scale");
					-ms-filter: "progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\''.esc_attr(discy_image_url_id($custom_background["image"])).'\',sizingMethod=\'scale\')";';
				}
			$custom_css .= '}';
		}else {
			if ($background_type == "patterns" || !empty($custom_background)) {
				$custom_css .= 'body,.main-content {
					background:';
					if ($background_type == "patterns") {
						if ($background_pattern != "default" && $background_pattern != "") {
							$custom_css .= $background_color.' url('.get_template_directory_uri().'/images/patterns/'.$background_pattern.'.png) repeat;';
						}
					}
					if (!empty($custom_background)) {
						if ($background_full != "on") {
							$custom_css .= esc_attr((isset($custom_background["color"])?$custom_background["color"]:"")).' url('.esc_attr(discy_image_url_id($custom_background["image"])).') '.esc_attr($custom_background["repeat"]).' '.esc_attr($custom_background["position"]).' '.esc_attr($custom_background["attachment"]).';';
						}
					}
				$custom_css .= '}';
			}
		}
	}
	return $custom_css;
}
function discy_skin($skin) {
	if (!empty($skin) && $skin != "skin" && $skin != "default" && $skin != "") {
		wp_enqueue_style('discy-skin-'.$skin,get_template_directory_uri()."/css/skins/".$skin.".css",array(),discy_theme_version);
	}else if ($skin == "skin" || $skin == "default" || $skin == "") {
		wp_enqueue_style('discy-skin-default',get_template_directory_uri()."/css/skins/skins.css",array(),discy_theme_version);
	}
}
/* discy_load_theme */
if (!function_exists('discy_load_theme')) {
	function discy_load_theme() {
		/* Default RSS feed links */
		add_theme_support('automatic-feed-links');
		/* Post Thumbnails */
		if (function_exists('add_theme_support')) {
		    add_theme_support('post-thumbnails');
		    set_post_thumbnail_size(830, 550, true);
		    set_post_thumbnail_size(330, 250, true);
		    set_post_thumbnail_size(1080, 565, true);
		    set_post_thumbnail_size(690, 430, true);
		    set_post_thumbnail_size(360, 202, true);
		}
	    add_image_size('discy_img_1', 830, 550, array( 'center', 'top' ));
	    add_image_size('discy_img_2', 330, 250, array( 'center', 'top' ));
	    add_image_size('discy_img_3', 1080, 565, array( 'center', 'top' ));
	    add_image_size('discy_img_4', 690, 430, array( 'center', 'top' ));
	    add_image_size('discy_img_5', 360, 202, array( 'center', 'top' ));
		/* Valid HTML5 */
		add_theme_support('html5', array('search-form', 'comment-form', 'comment-list'));
		/* This theme uses its own gallery styles */
		add_filter('use_default_gallery_style', '__return_false');
		/* add title-tag */
		add_theme_support('title-tag');
		/* Load lang languages */
		load_theme_textdomain("discy",get_template_directory().'/languages');
		/* add post-thumbnails */
		add_theme_support('post-thumbnails');
	}
}
add_action('after_setup_theme','discy_load_theme');
/* wp head */
add_action('wp_head', 'discy_head');
if (!function_exists('discy_head')) {
	function discy_head() {
		if (!function_exists('wp_site_icon') || !has_site_icon()) {
		    $default_favicon    = get_template_directory_uri()."/images/favicon.png";
		    $favicon            = discy_image_url_id(discy_options("favicon"));
		    $iphone_icon        = discy_image_url_id(discy_options("iphone_icon"));
		    $iphone_icon_retina = discy_image_url_id(discy_options("iphone_icon_retina"));
		    $ipad_icon          = discy_image_url_id(discy_options("ipad_icon"));
		    $ipad_icon_retina   = discy_image_url_id(discy_options("ipad_icon_retina"));
		    
			echo '<link rel="shortcut icon" href="'.esc_url((isset($favicon) && $favicon != ""?$favicon:$default_favicon)).'" type="image/x-icon">' ."\n";
		
		    /* Favicon iPhone */
		    if (isset($iphone_icon) && $iphone_icon != "") {
		        echo '<link rel="apple-touch-icon-precomposed" href="'.esc_url($iphone_icon).'">' ."\n";
		    }
		
		    /* Favicon iPhone 4 Retina display */
		    if (isset($iphone_icon_retina) && $iphone_icon_retina != "") {
		        echo '<link rel="apple-touch-icon-precomposed" sizes="114x114" href="'.esc_url($iphone_icon_retina).'">' ."\n";
		    }
		
		    /* Favicon iPad */
		    if (isset($ipad_icon) && $ipad_icon != "") {
		        echo '<link rel="apple-touch-icon-precomposed" sizes="72x72" href="'.esc_url($ipad_icon).'">' ."\n";
		    }
		
		    /* Favicon iPad Retina display */
		    if (isset($ipad_icon_retina) && $ipad_icon_retina != "") {
		        echo '<link rel="apple-touch-icon-precomposed" sizes="144x144" href="'.esc_url($ipad_icon_retina).'">' ."\n";
		    }
		}

		$primary_color = discy_options("primary_color");
		if ($primary_color != "") {
			$skin = $primary_color;
		}else {
			$skins = array("skin" => "#2d6ff7","violet" => "#9349b1","blue" => "#00aeef","bright_red" => "#fa4b2a","cyan" => "#058b7b","green" => "#81b441","red" => "#e91802");
			$site_skin = discy_options('site_skin');
			if ($site_skin == "skin" || $site_skin == "default" || $site_skin == "") {
				$skin = $skins["skin"];
			}else {
				$skin = $skins[$site_skin];
			}
		}
		if (isset($skin) && $skin != "") {
			echo '<meta name="theme-color" content="'.$skin.'">
			<meta name="msapplication-navbutton-color" content="'.$skin.'">
			<meta name="apple-mobile-web-app-capable" content="yes">
			<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">';
		}
		
		/* Seo */
		$the_keywords = discy_options("the_keywords");
		$seo_active   = discy_options("seo_active");
		$seo_active_filter = apply_filters("discy_filter_seo_active",true);
		if ($seo_active == "on" && $seo_active_filter == true) {
			$tax_filter   = apply_filters("discy_before_question_category",false);
			$tax_question = apply_filters("discy_question_category","question-category");
			$wpqa_group_id = (has_wpqa() && wpqa_plugin_version >= 4.2 && wpqa_group_id() > 0?wpqa_group_id():"");
			echo '<meta property="og:site_name" content="'.htmlspecialchars(get_bloginfo('name')).'">'."\n";
			echo '<meta property="og:type" content="website">'."\n";
			
		    if (!is_home() && !is_front_page() && (is_single() || is_page())) {
		    	global $post;
		    	$get_post = get_post($post->ID);
		    	$title = $get_post->post_title;
	    		$php_version = explode('.', phpversion());
	    		if (count($php_version) && $php_version[0] >= 5) {
	    			$title = html_entity_decode($title,ENT_QUOTES,'UTF-8');
	    		}else {
	    			$title = html_entity_decode($title,ENT_QUOTES);
	    		}
	    		$description = discy_excerpt(40,discy_excerpt_type,false,"return","yes",$get_post->post_content);
	    		$og_title = htmlspecialchars($title);
    			$og_url = get_permalink($post->ID);
    			$og_description = htmlspecialchars($description);
    			$og_image = (has_wpqa()?wpqa_image_for_share():"");
	    	    if (is_singular("question")) {
	    	    	if ($terms = wp_get_object_terms($post->ID, 'question_tags')) {
	    	    		$the_tags_post = '';
    	    			$terms_array = array();
    	    			foreach ($terms as $term) :
    	    				$the_tags_post .= $term->name . ',';
    	    			endforeach;
    	    			$og_keywords = trim($the_tags_post,',');
	    	    	}
	    	    }else {
	    	    	$posttags = get_the_tags($post->ID);
	    		    if ($posttags) {
	    		        $the_tags_post = '';
	    		        foreach ($posttags as $tag) {
	    		            $the_tags_post .= $tag->name . ',';
	    		        }
	    		        $og_keywords = trim($the_tags_post,',');
	    		    }
	    	    }
		    }else if ($wpqa_group_id > 0) {
		    	$og_title = get_the_title($wpqa_group_id);
		    	$og_url = get_permalink($wpqa_group_id);
		    	$group_cover_activate = "on";
		    	if ($group_cover_activate == "on") {
			    	$wpqa_group_id = (has_wpqa() && wpqa_plugin_version >= 4.2 && wpqa_group_id() > 0?wpqa_group_id():"");
					$group_cover = discy_post_meta("group_cover",$wpqa_group_id,false);
					if (has_wpqa() && (($group_cover && !is_array($group_cover)) || (is_array($group_cover) && isset($group_cover["id"]) && $group_cover["id"] != 0))) {
						$group_cover_img = wpqa_get_cover_url($group_cover,"","");
					}
					if (isset($group_cover_img) && $group_cover_img != "") {
						$og_image = ($group_cover_img != ""?$group_cover_img:"");
					}
				}
		    }else if (is_tax("question-category") || $tax_filter == true) {
		    	$tax_id = (int)get_query_var("wpqa_term_id");
		    	$og_title = esc_html(get_query_var("wpqa_term_name"));
		    	$og_url = get_term_link($tax_id,$tax_filter);
		    	$custom_cat_cover = get_term_meta($tax_id,prefix_terms."custom_cat_cover",true);
				if ($custom_cat_cover == "on") {
					$cat_cover = get_term_meta($tax_id,prefix_terms."cat_cover",true);
				}else {
					$cat_cover = discy_options("active_cover_category");
				}
				if (has_wpqa() && $cat_cover == "on") {
					$cover_link = wpqa_get_cat_cover_link(array("tax_id" => $tax_id,"cat_name" => esc_html(get_query_var('wpqa_term_name'))));
					$og_image = ($cover_link != ""?$cover_link:"");
				}
		    }else if (has_wpqa() && wpqa_is_user_profile() && !wpqa_is_user_edit_profile()) {
		    	$user_id = (int)get_query_var(apply_filters('wpqa_user_id','wpqa_user_id'));
		    	$display_name = get_the_author_meta('display_name',$user_id);
		    	$og_title = ($display_name != ""?$display_name:"");
		    	$og_url = wpqa_profile_url($user_id);
		    	$cover_image = discy_options("cover_image");
		    	if ($cover_image == "on") {
					$cover_link = wpqa_get_user_cover_link(array("user_id" => $user_id,"user_name" => get_the_author_meta('display_name',$user_id)));
					$og_image = ($cover_link != ""?$cover_link:"");
				}
			}else {
		    	$og_title = get_bloginfo('name');
		    	$og_url = esc_url(home_url('/'));
		    	$og_description = get_bloginfo('description');
		    	$og_keywords = discy_kses_stip($the_keywords);
		    }
		    if (isset($og_title) && $og_title != "") {
			    echo '<meta property="og:title" content="'.$og_title.'">'."\n";
			    echo '<meta name="twitter:title" content="'.$og_title.'">'."\n";
			}
			if (isset($og_description) && $og_description != "") {
			    echo '<meta name="description" content="'.$og_description.'">'."\n";
			    echo '<meta property="og:description" content="'.$og_description.'">'."\n";
			    echo '<meta name="twitter:description" content="'.$og_description.'"">'."\n";
			}
			if (isset($og_keywords) && $og_keywords != "") {
			    echo "<meta name='keywords' content='".discy_kses_stip($the_keywords)."'>" ."\n";
			}
			if (isset($og_url) && $og_url != "" && is_string($og_url)) {
				echo '<meta property="og:url" content="'.$og_url.'">'."\n";
			}
			if (!isset($og_image) || (isset($og_image) && $og_image == "")) {
				$fb_share_image = discy_image_url_id(discy_options("fb_share_image"));
		    	$last_og_image = (!empty($fb_share_image)?$fb_share_image:"");
		    	$last_og_image = apply_filters("discy_filter_og_image",$last_og_image);
		    	$og_image = ($last_og_image != ""?$last_og_image:"");
			}
			$og_image = apply_filters("discy_og_image",$og_image);
			if (isset($og_image) && $og_image != "") {
			    echo '<meta property="og:image" content="'.$og_image.'">' . "\n";
			    echo '<meta name="twitter:image" content="'.$og_image.'">' . "\n";
			}
		}
		
	    /* head_code */
	    if (discy_options("head_code")) {
	        echo stripslashes(discy_options("head_code"));
	    }
	}
}
/* footer_code */
if (!function_exists('discy_footer')) {
	function discy_footer() {
	    if (discy_options("footer_code")) {
	        echo stripslashes(discy_options("footer_code"));
	    }
	}
}
add_action('wp_footer', 'discy_footer');
/* Content Width */
if (!isset($content_width)) {
	$content_width = 1170;
}
/* discy_meta */
if (!function_exists('discy_meta')) {
	function discy_meta($date = "",$category = "",$comment = "",$asked = "",$icons = "",$views = "",$post_id = 0,$post = object) {
		$post_id = ($post_id > 0?$post_id:get_the_ID());
		$post_type = (isset($post->post_type)?$post->post_type:get_post_type($post_id));
		if ($date == "on") {
			$time_string = '<time class="entry-date published">%1$s</time>';
			$time_string = sprintf($time_string,esc_html(get_the_time(discy_date_format,$post_id)));
			if ('question' === $post_type) {
				$data_string = esc_html__("Asked:","discy");
			}else {
				$data_string = esc_html__("On:","discy");
			}
			$posted_on = $data_string.'<span class="date-separator"></span> '.('question' === $post_type?'<a href="'.get_the_permalink().'" itemprop="url">':'').$time_string.('question' === $post_type?'</a>':'');
			echo '<span class="post-date">'.$posted_on;
			if (is_single() && 'question' === $post_type) {
				$get_the_time = get_the_time('c',$post_id);
				$puplished_date = ($get_the_time?$get_the_time:get_the_modified_date('c',$post_id));
				echo '<span class="discy_hide" itemprop="dateCreated" datetime="'.$puplished_date.'">'.$puplished_date.'</span>
				<span class="discy_hide" itemprop="datePublished" datetime="'.$puplished_date.'">'.$puplished_date.'</span>';
			}
			echo '</span>';
		}
		
		if ($category == "on" && 'post' === $post_type) {
			$categories_list = get_the_category_list(', ');
			if ($categories_list) {
				$posted_in = sprintf('<span class="post-cat">'.esc_html__('Posted in %1$s','discy').'</span>',$categories_list);
				echo '<span class="byline"> '.$posted_in.'</span>';
			}
		}
		if ('question' === $post_type) {
			if ($asked == "on") {
				$get_question_user_id = discy_post_meta("user_id","",false);
				if ($get_question_user_id != "") {
					$display_name = get_the_author_meta('display_name',$get_question_user_id);
					if (isset($display_name) && $display_name != "") {
						echo '<span class="asked-to">'.esc_html__("Asked to","discy").': <a href="'.get_author_posts_url($get_question_user_id).'">'.esc_html($display_name).'</a></span>';
					}
				}
			}
			if ($category == "on") {
				$first_span = '<span class="byline"><span class="post-cat">'.esc_html__('In:','discy').' ';
				$second_span = '</span></span>';
				$get_the_term_list = get_the_term_list($post_id,'question-category',$first_span,', ',$second_span);
				if (!isset($get_the_term_list->errors) && $get_the_term_list != "") {
					echo ($get_the_term_list);
				}else {
					$category_meta = discy_post_meta("category_meta","",false);
					$term = get_term_by('slug',esc_html($category_meta),'question-category');
					if (isset($term->slug)) {
						echo ($first_span);
						$get_term_link = get_term_link($term->slug,'question-category');
						if (is_string($get_term_link)) {
							echo '<a href="'.$get_term_link.'">'.$term->name.'</a>';
						}
						echo ($second_span);
					}else if ($category_meta != "") {
						echo ($first_span).esc_html($category_meta).($second_span);
					}
				}
				do_action("discy_after_question_category",$post_id);
			}
		}
		do_action("discy_meta_before_comment",$post_id,$post_type,$category);
		$count_post_all = (int)(has_wpqa()?wpqa_count_comments($post_id):get_comments_number());
		if ($comment == "on" && !post_password_required() && ((isset($post->comment_status) && $post->comment_status == "open") || $count_post_all > 0)) {
			if ('question' === $post_type) {
				echo "<span".(is_singular('question')?' itemprop="answerCount"':'')." class='number".($icons != "on"?" discy_hide":"")."'>".discy_count_number($count_post_all).'</span>';
				if ($icons != "on") {
					echo " <span class='question-span'>".sprintf(_n("%s Answer","%s Answers",$count_post_all,"discy"),$count_post_all)."</span>";
				}
			}else {?>
				<span class="post-comment">
					<?php esc_html_e('Comments: ','discy');
					discy_count_number(comments_popup_link(0,1,'%'))?>
				</span>
			<?php }
		}
		$active_post_stats = discy_options("active_post_stats");
		if ('post' === $post_type && $views == "on" && $active_post_stats == "on") {
			global $post;
			$post_meta_stats = discy_options("post_meta_stats");
			$post_meta_stats = ($post_meta_stats != ""?$post_meta_stats:"post_stats")?>
			<span class="post-views">
				<?php echo esc_html__('Views:','discy').' ';
				$cache_post_stats = discy_options("cache_post_stats");
				if ($cache_post_stats == "on") {
					$post_stats = get_transient($post_meta_stats.$post->ID);
					$post_stats = ($post_stats !== false?$post_stats:discy_post_meta($post_meta_stats,"",false));
				}else {
					$post_stats = discy_post_meta($post_meta_stats,"",false);
				}
				$post_stats = (int)$post_stats;
				echo discy_count_number($post_stats)?>
			</span>
		<?php }
	}
}?>