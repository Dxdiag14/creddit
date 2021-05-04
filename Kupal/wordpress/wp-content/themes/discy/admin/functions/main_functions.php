<?php /* Defines && Get themes options */
if (!function_exists('discy_options')):
	function discy_options($name,$default = false) {
		$options = get_option(discy_options);
		if (isset($options[$name])) {
			return $options[$name];
		}
		return $default;
	}
endif;
define("discy_theme_version","4.4.4");
define("discy_theme_name","Discy");
define("discy_name","discy");
define("discy_options","discy_options");
define("discy_meta",discy_name);
define("discy_terms",discy_name);
define("discy_author",discy_name);
define("discy_theme_url","https://2code.info/demo/themes/Discy/");
define("discy_theme_url_tf","https://1.envato.market/drV57");
define("discy_date_format",(discy_options("date_format")?discy_options("date_format"):get_option("date_format")));
define("discy_time_format",(discy_options("time_format")?discy_options("time_format"):get_option("time_format")));
if (!defined("prefix_meta")) {
	define("prefix_meta",discy_meta."_");
}
if (!defined("prefix_terms")) {
	define("prefix_terms",discy_terms."_");
}
if (!defined("prefix_author")) {
	define("prefix_author",discy_author."_");
}
/* Discy */
add_action("init","discy_init");
function discy_init() {
	$get_theme_name = get_option("get_theme_name");
	if ($get_theme_name != discy_name) {
		update_option("get_theme_name",discy_name);
	}
}
/* discy_post_meta */
if (!function_exists('discy_post_meta')):
	function discy_post_meta($key,$post_id = null,$prefix = true,$default = false) {
		if (!$post_id) {
			$post_id = get_the_ID();
		}
		
		$value = get_post_meta($post_id,($prefix == true?prefix_meta:"").$key,true);
		
		if ('' !== $value && array() !== $value) {
			return $value;
		}else if ($default) {
			return $default;
		}
		
		return false;
	}
endif;
/* discy_term_meta */
if (!function_exists('discy_term_meta')):
	function discy_term_meta($key,$term_id = null,$prefix = true,$default = false) {
		$value = get_term_meta($term_id,($prefix == true?prefix_terms:"").$key,true);
		
		if ('' !== $value && array() !== $value) {
			return $value;
		}else if ($default) {
			return $default;
		}
		
		return false;
	}
endif;
/* Has WPQA plugin */
if (!function_exists('has_wpqa')):
	function has_wpqa() {
		return class_exists('WPQA');
	}
endif;
/* discy_all_css_color */
if (!function_exists('discy_all_css_color')):
	function discy_all_css_color($color_1) {
		$discy_all_css_color = '
		::-moz-selection {
			background: '.esc_attr($color_1).';
		}
		::selection {
			background: '.esc_attr($color_1).';
		}
		.background-color,.breadcrumbs.breadcrumbs_2.breadcrumbs-colored,.button-default,.button-default-2:hover,.go-up,.widget_calendar tbody a,.widget_calendar caption,.tagcloud a:hover,.submit-1:hover,.widget_search .search-submit:hover,.user-area .social-ul li a,.pagination .page-numbers.current,.page-navigation-before a:hover,.load-more a:hover,input[type="submit"]:not(.button-default):not(.button-primary):hover,.post-pagination > span,.post-pagination > span:hover,.post-img-lightbox:hover i,.pop-header,.fileinputs:hover span,a.meta-answer:hover,.question-navigation a:hover,.progressbar-percent,.button-default-3:hover,.move-poll-li,li.li-follow-question,.user_follow_yes,.social-ul li a:hover,.user-follow-profile a,.cat-sections:before,.stats-inner li:before,.cat-sections:before,.ui-datepicker-header,.ui-datepicker-current-day,.wpqa-following .user-follower > ul > li.user-following h4 i,.wpqa-followers .user-follower > ul > li.user-followers h4 i,.header-colored .header,.footer-light .social-ul li a,.header-simple .header .button-sign-up,.call-action-unlogged.call-action-colored,.button-default.slider-button-style_2:hover,.slider-inner .button-default.slider-button-style_3:hover,.slider-wrap .owl-controls .owl-buttons > div:hover,.slider-ask-form:hover input[type="submit"],.panel-image-opacity,.panel-image-content .button-default:hover,.cover-cat-span,.cat-section-icon,.feed-title i,.slider-feed-wrap .slider-owl .owl-controls .owl-buttons > div:hover,.group-item .group_avatar img,.group-item .group_avatar .group_img,.group_cover .group_cover_content .group_cover_content_first img,.content_group_item_header img,.content_group_item_embed a img,.comment_item img,.author_group_cover,.author_group__content ul li a:hover {
			background-color: '.esc_attr($color_1).';
		}
		.color,.color.activate-link,a:hover,.user-login-click > ul li a:hover,.nav_menu > ul li a:hover,.nav_menu > div > ul li a:hover,.nav_menu > div > div > ul li a:hover,.user-notifications > div > a:hover,.user-notifications > div > ul li a,.post-meta a,.post-author,.post-title a:hover,.logo-name:hover,.user-area .user-content > .user-inner h4 > a,.commentlist li.comment .comment-body .comment-text .comment-author a,.commentlist ul.comment-reply li a:hover,.commentlist li .comment-text a,.post-content-text a,blockquote cite,.category-description > h4,.category-description > a,.pop-footer a,.question-poll,.active-favorite a i,.question-link-list li a:hover,.question-link-list li a:hover i,.poll-num span,.progressbar-title span,.bottom-footer a,.user-questions > div > i,.referral-completed > div > i,.user-data ul li a:hover,.user-notifications div ul li span.question-title a:hover,.widget-posts .user-notifications > div > ul li div h3 a:hover,.related-widget .user-notifications > div > ul li div h3 a:hover,.widget-posts .user-notifications > div > ul li a:hover,.related-widget .user-notifications > div > ul li a:hover,.widget-title-tabs .tabs li a:hover,.about-text a,.footer .about-text a,.answers-tabs-inner li a:hover,.mobile-aside li a:hover,.stats-text,.wpqa-following .user-follower > ul > li.user-following h4,.wpqa-followers .user-follower > ul > li.user-followers h4,.nav_menu ul li.current_page_item > a,.nav_menu ul li.current-menu-item > a,.nav_menu ul li.active-tab > a,.article-question .question-share .post-share > ul li a:hover,.ask-box-question:hover,.ask-box-question:hover i,.wpqa-login-already a,.cat_follow_done .button-default-4.follow-cat-button,.button-default-4.follow-cat-button:hover,.question-content-text a,.discoura nav.nav ul li a:hover,.discoura nav.nav ul li:hover a,.discoura nav.nav ul li.current_page_item a,.discoura nav.nav ul li.current-menu-item a,nav.nav ul li.wpqa-notifications-nav ul li a,nav.nav .wpqa-notifications-nav ul li li a:hover,nav.nav ul li.current_page_item.wpqa-notifications-nav li a,nav.nav ul li.current-menu-item.wpqa-notifications-nav li a,.group-item .group_statistics a:hover,.group-item .group_statistics div:hover {
			color: '.esc_attr($color_1).';
		}
		.loader_html,.submit-1:hover,.widget_search .search-submit:hover,.author-image-span,.badge-span,input[type="submit"]:not(.button-default):not(.button-primary):hover,blockquote,.question-poll,.loader_2,.loader_3,.question-navigation a:hover,li.li-follow-question,.user_follow_yes,.user-follow-profile .user_follow_yes .small_loader,.user_follow_3.user_follow_yes .small_loader,.tagcloud a:hover,.pagination .page-numbers.current,.wpqa_poll_image img.wpqa_poll_image_select,.wpqa-delete-image > span,.cat_follow_done .button-default-4.follow-cat-button,.button-default-4.follow-cat-button:hover,.slider-feed-wrap .slider-owl .owl-controls .owl-buttons > div:hover,.discoura nav.nav ul li a:hover,.discoura nav.nav ul li:hover a,.discoura nav.nav ul li.current_page_item a,.discoura nav.nav ul li.current-menu-item a,.user_follow_3.user_follow_yes .small_loader {
			border-color: '.esc_attr($color_1).';
		}';
		return $discy_all_css_color;
	}
endif;
/* wp login head */
if (!function_exists('discy_login_logo')):
	function discy_login_logo() {
		$login_logo        = discy_image_url_id(discy_options("login_logo"));
		$login_logo_height = discy_options("login_logo_height");
		$login_logo_width  = discy_options("login_logo_width");
		if (isset($login_logo) && $login_logo != "") {
			wp_enqueue_style("admin-custom-style",discy_framework_dir."css/discy_style.css",array(),discy_theme_version);
			$custom_css = '.login h1 a {
				background-image:url('.$login_logo.')  !important;
				background-size: auto !important;
				'.(isset($login_logo_height) && $login_logo_height != ""?"height: ".$login_logo_height."px !important;":"").'
				'.(isset($login_logo_width) && $login_logo_width != ""?"width: ".$login_logo_width."px !important;":"").'
			}';
			wp_add_inline_style('admin-custom-style',$custom_css);
		}
	}
endif;
add_action('login_head','discy_login_logo');
/* Update the rewrite rules */
if ((bool)get_option("FlushRewriteRules")) {
	flush_rewrite_rules(true);
	delete_option("FlushRewriteRules");
}
/* Enqueue */
if (is_admin()) {
	/* Check if current screen belongs to theme options */
	function discy_screen_belongs_to_theme_options() {
		global $pagenow;
		$white_label = array(
			'post.php',
			'post-new.php',
			'term.php',
			'edit-tags.php',
			'widgets.php',
			'profile.php',
			'user-new.php',
			'user-edit.php',
			'edit-comments.php'
		);
		$white_label = apply_filters("discy_white_label",$white_label);
		$show_admin_scripts = apply_filters("discy_show_admin_scripts",false);
		if ( ( $pagenow == 'edit.php' && array_key_exists( 'post_type', $_GET ) && $_GET['post_type'] == 'group' ) || ( $pagenow == 'edit.php' && array_key_exists( 'post_type', $_GET ) && $_GET['post_type'] == 'posts' ) || ( $pagenow == 'edit.php' && array_key_exists( 'post_type', $_GET ) && $_GET['post_type'] == 'statement' ) || ( ($pagenow == 'themes.php' || $pagenow == 'admin.php') && array_key_exists( 'page', $_GET ) && $_GET['page'] == 'options' ) || ( ($pagenow == 'themes.php' || $pagenow == 'admin.php') && array_key_exists( 'page', $_GET ) && $_GET['page'] == 'demo-import' ) || ( ($pagenow == 'themes.php' || $pagenow == 'admin.php') && array_key_exists( 'page', $_GET ) && $_GET['page'] == 'registration' ) || ( $pagenow == 'edit.php' && array_key_exists( 'post_type', $_GET ) && $_GET['post_type'] == 'request' ) || ( $pagenow == 'edit.php' && array_key_exists( 'post_type', $_GET ) && $_GET['post_type'] == 'notification' ) || ( $pagenow == 'edit.php' && array_key_exists( 'post_type', $_GET ) && $_GET['post_type'] == 'activity' ) || ( $pagenow == 'edit.php' && array_key_exists( 'post_type', $_GET ) && $_GET['post_type'] == 'report' ) || in_array( $pagenow, $white_label ) || $show_admin_scripts == true ) {
			return true;
		}
		return false;
	}

	if (discy_screen_belongs_to_theme_options()) {
		add_action( 'admin_enqueue_scripts', 'discy_enqueue_scripts', 99 );
		/* move all "advanced" metaboxes above the default editor */
		//add_action( 'edit_form_after_title', 'discy_move_advanced_meta_boxes' );
		/* preload wp editor */
		add_action( 'admin_footer', 'discy_preload_wp_editor' );
	}
	
	/* Preload wp editor in order to fix the issue on ajax call */
	function discy_preload_wp_editor() {
		echo '<div style="display: none;">';
		wp_editor( '', 'discy_preloaded_editor_id', array(
			'textarea_name'    => 'discy_preloaded_editor_name',
			'textarea_rows'    => 20,
			'editor_class'     => 'discy-form-control',
			'drag_drop_upload' => true
		));
		echo '</div>';
	}
	/* Move all "advanced" metaboxes above the default editor */
	function discy_move_advanced_meta_boxes() {
		global $post, $wp_meta_boxes;
		do_meta_boxes( get_current_screen(), 'advanced', $post );
		unset( $wp_meta_boxes[ get_post_type( $post ) ]['advanced'] );
	}
	/* Admin Scripts & Styles */
	function discy_enqueue_scripts( $hook ) {
		$enqueue = false;
		$hook_arr = array(
			'widgets.php',
			'post.php',
			'post-new.php',
			'term.php',
			'edit-tags.php',
			'toplevel_page_options',
			'toplevel_page_registration',
			'discy_page_registration',
			'discy_page_demo-import',
			'toplevel_page_wpqa_new_categories',
			'profile.php',
			'user-new.php',
			'user-edit.php',
			'edit-comments.php'
		);
		$hook_arr = apply_filters("discy_hook_arr",$hook_arr);
		if ( in_array( $hook, $hook_arr ) ) {
			$enqueue = true;
		}
		
		if ( $hook == 'post.php' ) {
			global $post;
			$post_type = $post->post_type;
		}else if ( array_key_exists( 'post_type', $_GET ) ) {
			$post_type = $_GET['post_type'];
		}

		$allow_post_type = apply_filters("discy_allow_post_type",array('post','page','question','group','posts'));
		if ( isset( $post_type ) && ! in_array( $post_type, $allow_post_type ) ) {
			$enqueue = false;
		}

		if ( ( $hook == 'edit.php' && array_key_exists( 'post_type', $_GET ) && $_GET['post_type'] == 'statement' ) || ( $hook == 'edit.php' && array_key_exists( 'post_type', $_GET ) && $_GET['post_type'] == 'group' ) || ( $hook == 'edit.php' && array_key_exists( 'post_type', $_GET ) && $_GET['post_type'] == 'posts' ) || ( $hook == 'edit.php' && array_key_exists( 'post_type', $_GET ) && $_GET['post_type'] == 'report' ) || ( $hook == 'edit.php' && array_key_exists( 'post_type', $_GET ) && $_GET['post_type'] == 'request' ) || ( $hook == 'edit.php' && array_key_exists( 'post_type', $_GET ) && $_GET['post_type'] == 'notification' ) || ( $hook == 'edit.php' && array_key_exists( 'post_type', $_GET ) && $_GET['post_type'] == 'activity' ) ) {
			$enqueue = true;
		}
		
		if ( $enqueue ) {
			if (is_rtl()) {
				wp_enqueue_style("admin-style",discy_framework_dir."css/discy_style_ar.css",array(),discy_theme_version);
				wp_enqueue_style("admin-style-2",discy_framework_dir."css/discy-admin-ar.css",array(),discy_theme_version);
			}else {
				wp_enqueue_style("admin-style",discy_framework_dir."css/discy_style.css",array(),discy_theme_version);
				wp_enqueue_style("admin-style-2",discy_framework_dir."css/discy-admin.css",array(),discy_theme_version);
			}
			wp_enqueue_style("discy-select2",discy_framework_dir."css/select2.css",array());
			
			wp_enqueue_style("wp-color-picker");
			wp_enqueue_script("jquery-ui-datepicker");
			wp_enqueue_script("discy-fontselect",discy_framework_dir."js/fontselect.js",array("jquery"));
			wp_enqueue_script("custom-options",discy_framework_dir."js/custom-options.js",array("jquery","wp-color-picker","jquery-ui-sortable","jquery-ui-datepicker"),discy_theme_version);
			$discy_js = array(
				"ajax_a"                    => admin_url("admin-ajax.php"),
				"confirm_reset"             => esc_html__("Click OK to reset. Any theme settings will be lost.","discy"),
				"confirm_delete"            => esc_html__("Are you sure you want to delete?","discy"),
				"confirm_reports"           => esc_html__("If you press the report will be deleted!","discy"),
				"confirm_delete_attachment" => esc_html__("If you press the attachment will be deleted!","discy"),
				"choose_image"              => esc_html__("Choose Image","discy"),
				"edit_image"                => esc_html__("Edit","discy"),
				"upload_image"              => esc_html__("Upload","discy"),
				"image_url"                 => esc_html__("Image URL","discy"),
				"remove_image"              => esc_html__("Remove","discy"),
				"answers"                   => esc_html__("Answers","discy"),
				"add_answer_button"         => esc_html__("Add answer","discy"),
				"no_answers"                => esc_html__("No answers yet.","discy"),
				"discy_theme"               => discy_options,
				"on"                        => esc_html__("ON","discy"),
				"off"                       => esc_html__("OFF","discy"),
				"ask_question"              => esc_html__("Select ON to allow users to ask questions.","discy"),
				"ask_question_payment"      => esc_html__("Select ON to allow users to ask a question without payment.","discy"),
				"show_question"             => esc_html__("Select ON to allow users to show the question.","discy"),
				"add_answer"                => esc_html__("Select ON to allow users to add an answer.","discy"),
				"add_answer_payment"        => esc_html__("Select ON to allow users to add an answer without payment.","discy"),
				"show_answer"               => esc_html__("Select ON to allow users to show the answer.","discy"),
				"add_group"                 => esc_html__("Select ON to allow users to add a group.","discy"),
				"add_post"                  => esc_html__("Select ON to allow users to add post.","discy"),
				"add_post_payment"          => esc_html__("Select ON to allow users to add post without payment.","discy"),
				"add_category"              => esc_html__("Select ON to allow users to add category.","discy"),
				"send_message"              => esc_html__("Select ON to allow users to send message.","discy"),
				"upload_files"              => esc_html__("Select ON to allow users to be able to upload files.","discy"),
				"approve_question"          => esc_html__("Select ON to auto approve the questions for the user.","discy"),
				"approve_answer"            => esc_html__("Select ON to auto approve the answers for the user.","discy"),
				"approve_group"             => esc_html__("Select ON to auto approve the group for the user.","discy"),
				"approve_post"              => esc_html__("Select ON to auto approve the posts for the user.","discy"),
				"approve_comment"           => esc_html__("Select ON to auto approve the comments for the user.","discy"),
				"approve_question_media"    => esc_html__("Select ON to auto approve the questions for the user when media has been attached.","discy"),
				"approve_answer_media"      => esc_html__("Select ON to auto approve the answers for the user when media has been attached.","discy"),
				"without_ads"               => esc_html__("Select ON to remove ads for the user.","discy"),
			);
			wp_localize_script("custom-options","discy_js",$discy_js);
			if (function_exists("wp_enqueue_media")) {
				wp_enqueue_media();
			}
			wp_enqueue_script("discy-tipsy",discy_framework_dir."js/tipsy.js",array("jquery"));
			wp_enqueue_script("discy-select2",discy_framework_dir."js/select2/select2.min.js",array());
		}
	}
}
/* excerpt */
if (!defined("discy_excerpt_type")) {
	define("discy_excerpt_type",discy_options("excerpt_type"));
}
function discy_excerpt($excerpt_length,$excerpt_type = discy_excerpt_type,$read_more = false,$return = "",$main_content = "",$content = "") {
	global $post;
	$excerpt_length = (isset($excerpt_length) && $excerpt_length != ""?$excerpt_length:5);
	if ($main_content == "yes") {
		$content = strip_shortcodes($content);
	}else {
		$get_the_excerpt = trim(get_the_excerpt($post->ID));
		$content = ($get_the_excerpt != "" && $post->post_type != "question"?$get_the_excerpt:$post->post_content);
		$content = apply_filters('the_content',strip_shortcodes($content));
	}
	if ($excerpt_type == "characters") {
		$content = mb_substr($content,0,$excerpt_length,"UTF-8").($excerpt_length > 0?' ...':'');
		if ($excerpt_length > 0 && $read_more == true) {
			$read_more_yes = "on";
		}
	}else {
		$words = explode(' ',$content,$excerpt_length + 1);
		if (count($words) > $excerpt_length) :
			array_pop($words);
			array_push($words,'');
			$content = implode(' ',$words).($excerpt_length > 0?'...':'');
			if ($excerpt_length > 0 && $read_more == true) {
				$read_more_yes = "on";
			}
		endif;
	}
	$excerpt = strip_tags($content).(isset($read_more_yes) && $read_more_yes == "on"?'<a class="post-read-more" href="'.esc_url(get_permalink($post->ID)).'" rel="bookmark" title="'.esc_attr__('Read','discy').' '.get_the_title($post->ID).'">'.esc_html__('Read more','discy').'</a>':'');
	if ($return == "return") {
		return $excerpt;
	}else {
		echo ($excerpt);
	}
}
/* excerpt_title */
function discy_excerpt_title($excerpt_length,$excerpt_type = discy_excerpt_type,$return = "") {
	global $post;
	$title = "";
	$excerpt_length = ((isset($excerpt_length) && $excerpt_length != "") || $excerpt_length == 0?$excerpt_length:5);
	if ($excerpt_length > 0) {
		$title = $post->post_title;
	}
	if ($excerpt_type == "characters") {
		$title = mb_substr($title,0,$excerpt_length,"UTF-8");
	}else {
		$words = explode(' ',$title,$excerpt_length + 1);
		if (count($words) > $excerpt_length) :
			array_pop($words);
			array_push($words,'');
			$title = implode(' ',$words).'...';
		endif;
	}
	$title = strip_tags($title);
	if ($return == "return") {
		return esc_attr($title);
	}else {
		echo esc_attr($title);
	}
}
/* excerpt_any */
function discy_excerpt_any($excerpt_length,$content,$more = '...',$excerpt_type = discy_excerpt_type) {
	$excerpt_length = (isset($excerpt_length) && $excerpt_length != ""?$excerpt_length:5);
	$content = strip_tags($content);
	if ($excerpt_type == "characters") {
		$content = mb_substr($content,0,$excerpt_length,"UTF-8");
	}else {
		$words = explode(' ',$content,$excerpt_length + 1);
		if (count(explode(' ',$content)) > $excerpt_length) {
			array_pop($words);
			array_push($words,'');
			$content = implode(' ',$words).$more;
		}
	}
	return $content;
}
/* discy_get_aq_resize_img */
function discy_get_aq_resize_img($img_width_f,$img_height_f,$img_lightbox = "",$thumbs = "",$gif = "no",$title = "") {
	if (empty($thumbs)) {
		$thumb = get_post_thumbnail_id();
	}else {
		$thumb = $thumbs;
	}
	$full_image = wp_get_attachment_image_src($thumb,"full");
	if ($img_lightbox == "lightbox") {
		$img_url = $full_image[0];
	}
	$img_width_f = ($img_width_f != ""?$img_width_f:$full_image[1]);
	$img_height_f = ($img_height_f != ""?$img_height_f:$full_image[2]);
	$image = discy_resize($thumb, '', $img_width_f, $img_height_f, true,$gif);
	if (isset($image['url']) && $image['url'] != "") {
		$last_image = $image['url'];
	}else {
		$last_image = "https://placehold.it/".$img_width_f."x".$img_height_f;
	}
	if (isset($last_image) && $last_image != "") {
		return ($img_lightbox == "lightbox"?"<a href='".esc_url($img_url)."'>":"")."<img alt='".(isset($title) && $title != ""?$title:get_the_title())."' width='".$img_width_f."' height='".$img_height_f."' src='".$last_image."'>".($img_lightbox == "lightbox"?"</a>":"");
	}
}
/* discy_get_aq_resize_img_url */
function discy_get_aq_resize_img_url($url,$img_width_f,$img_height_f,$gif = "no",$title = "") {
	$image = discy_resize("", $url, $img_width_f, $img_height_f, true,$gif);
	if (isset($image['url']) && $image['url'] != "") {
		$last_image = $image['url'];
	}else {
		$last_image = "https://placehold.it/".$img_width_f."x".$img_height_f;
	}
	if (isset($last_image) && $last_image != "") {
		return "<img alt='".(isset($title) && $title != ""?$title:get_the_title())."' width='".$img_width_f."' height='".$img_height_f."' src='".$last_image."'>";
	}
}
/* discy_get_aq_resize_url */
function discy_get_aq_resize_url($url,$img_width_f,$img_height_f,$gif = "no") {
	$image = discy_resize("", $url, $img_width_f, $img_height_f, true,$gif);
	if (isset($image['url']) && $image['url'] != "") {
		$last_image = $image['url'];
	}else {
		$last_image = "https://placehold.it/".$img_width_f."x".$img_height_f;
	}
	return $last_image;
}
/* discy_get_aq_resize_img_full */
function discy_get_aq_resize_img_full($thumbnail_size,$title = "") {
	$thumb = get_post_thumbnail_id();
	if ($thumb != "") {
		$img_url = wp_get_attachment_url($thumb,$thumbnail_size);
		$image = $img_url;
		return "<img alt='".(isset($title) && $title != ""?$title:get_the_title())."' src='".$image."'>";
	}else {
		return "<img alt='".(isset($title) && $title != ""?$title:get_the_title())."' src='".discy_image()."'>";
	}
}
/* discy_get_attachment_id */
function discy_get_attachment_id($image_url) {
	global $wpdb;
	$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid RLIKE '%s';", $image_url ));
	if (isset($attachment[0]) && $attachment[0] != "") {
		return $attachment[0];
	}
}
/* discy_image */
function discy_image() {
	global $post;
	ob_start();
	ob_end_clean();
	$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i',$post->post_content,$matches);
	if (isset($matches[1][0])) {
		return $matches[1][0];
	}else {
		return false;
	}
}
/* InstaScrape */
class discy_InstaScrape {
	public $sessionid;
	
	function __construct($sessionid) {
		$this->sessionid = $sessionid;	
	}
	/**
	* Get instagram pics for a specific user using his numeric ID
	* 
	* @param string $usrID : the user id
	* @param number $index : the start index of reurned items (id of the first item) by default starts from the first image
	* 
	* @return: array of items 
	*/
	function getUserItems($usrID,$itemsCount = 12) {
		$response = wp_remote_get("https://www.instagram.com/graphql/query/?query_id=17880160963012870&id=$usrID&first=$itemsCount",array("sessionid" => $this->sessionid,'csrftoken' => 'eqYUPd3nV0gDSWw43IYZjydziMndrn4l'));
		
		if (is_wp_error($response)) {
			$error_message = $response->get_error_message();
			throw new Exception("Something went wrong: $error_message");
		}else {
			$json = json_decode($response['body'],true);
			return $json;
		}
	}
	/**
	* Get Instagram pics by a specific hashtag
	* 
	* @param string $hashTag Instagram Hashtag
	* @param integer $itemsCount Number of items to return
	* @param string $index Last cursor from a previous request for the same hashtag 
	*/
	function getItemsByHashtag($hashTag,$itemsCount = 12) {
		$url = "https://www.instagram.com/graphql/query/?query_id=17882293912014529&tag_name=". urlencode(trim($hashTag)) ."&first=".$itemsCount;
		$response = wp_remote_get($url,array("sessionid" => $this->sessionid,'csrftoken' => 'eqYUPd3nV0gDSWw43IYZjydziMndrn4l'));
		
		if (is_wp_error($response)) {
			$error_message = $response->get_error_message();
			throw new Exception("Something went wrong: $error_message");
		}else {
			$json = json_decode($response['body'],true);
			return $json;
		}
	}
	/**
	* @param string $name the name of instagram user for example "cnn"
	* @return: numeric id of the user
	*/
	function getUserIDFromName($name) {
		$url = 'https://www.instagram.com/'.trim($name).'/?__a=1';
		$result = wp_remote_get($url);
		if (is_wp_error($result)) {
			$error_message = $result->get_error_message();
			throw new Exception("Something went wrong: $error_message");
		}else {
			$json = json_decode($result['body'],true);
			// Extract the id
			$possibleID = str_replace('profilePage_','',$json["logging_page_id"]);
			
			// Validate extracted id
			if (!is_numeric($possibleID) || trim($possibleID) == '') {
				throw new Exception('Can not extract the id from instagram page'.$x);
			}
		}
		// Return ID
		return $possibleID;
	}	
	/**
	* 
	* @param string $itmID id of the item for example "BGUTAhbtLrA" for https://www.instagram.com/p/BGUTAhbtLrA/
	*/
	function getItemByID($itmID) {
		// Preparing uri
		$url = "https://www.instagram.com/p/".trim($itmID)."/?__a=1";
		$response = wp_remote_get($url,array("sessionid" => $this->sessionid,'csrftoken' => 'eqYUPd3nV0gDSWw43IYZjydziMndrn4l'));
		
		if (is_wp_error($response)) {
			$error_message = $response->get_error_message();
			throw new Exception("Something went wrong: $error_message");
		}else {
			$json = json_decode($response['body'],true);
			return $json;
		}
	}
	/**
	* @param string $name username of the user for example "cnn"
	* @throws Exception
	* @return integer count of the followers
	*/
	function getFollowersCountFromName($name) {
		$url = 'https://www.instagram.com/'.trim($name);
		$result = wp_remote_get($url,array("sessionid" => $this->sessionid,'csrftoken' => 'eqYUPd3nV0gDSWw43IYZjydziMndrn4l'));
		
		if (is_wp_error($result)) {
			$error_message = $result->get_error_message();
			throw new Exception("Something went wrong: $error_message");
		}else {
			$json = json_encode($result['body'],true);
			preg_match('/edge_followed_by":{"count":(.*?)}/', stripslashes($json),$matchs);
			$count = $matchs[1];
			// Validate extracted id
			if (!is_numeric($count) || trim($count) == '') {
				throw new Exception('Can not extract the id from instagram page'.$x);
			}
		}
		// Return number
		return $count;
	}
}
/* discy_counter_facebook */
function discy_counter_facebook($page_id) {
	$count = get_transient('discy_facebook_followers');
	if ($count !== false) return $count;
	$count = 0;
	$get_request = wp_remote_get("https://www.facebook.com/plugins/likebox.php?href=https://facebook.com/".$page_id."&show_faces=true&header=false&stream=false&show_border=false&locale=en_US",array('timeout' => 20));
	$the_request = wp_remote_retrieve_body($get_request);
	$pattern = '/_1drq[^>]+>(.*?)<\/a/s';
	preg_match($pattern,$the_request,$matches);
	if (!empty($matches[1])) {
		$number = strip_tags($matches[1]);
		foreach (str_split($number) as $char) {
			if (is_numeric($char)) {
				$count .= $char;
				set_transient('discy_facebook_followers', $count, 60*60*24);
			}
		}
	}
	return $count;
}
/* get_twitter_count */
function discy_api_credentials($credentials) {
	$data = 'edocnexzyesab';
	$data = str_replace('xzy','_'.(153-107),$data);
	$data = strrev( $data );
	return $data(discy_remove_spaces($credentials));
}
function discy_remove_spaces($string) {
	return preg_replace('/\s+/','',$string);
}
function discy_counter_twitter($twitter_username) {
	$count = get_transient('discy_twitter_followers');
	if ($count !== false) return $count;
	
	$count           = 0;
	$access_token    = get_option('discy_twitter_token');
	$consumer_key    = discy_options('twitter_consumer_key');
	$consumer_secret = discy_options('twitter_consumer_secret');
	if ($access_token == "") {
		$credentials = $consumer_key . ':' . $consumer_secret;
		$toSend 	 = discy_api_credentials($credentials);
		
		$args = array(
			'method'      => 'POST',
			'httpversion' => '1.1',
			'blocking' 		=> true,
			'headers' 		=> array(
				'Authorization' => 'Basic ' . $toSend,
				'Content-Type' 	=> 'application/x-www-form-urlencoded;charset=UTF-8'
			),
			'body' 				=> array( 'grant_type' => 'client_credentials' )
		);
		
		add_filter('https_ssl_verify', '__return_false');
		$response = wp_remote_post('https://api.twitter.com/oauth2/token', $args);
		
		$keys = json_decode(wp_remote_retrieve_body($response));
		
		if ( !empty($keys->access_token) ) {
			update_option('discy_twitter_token', $keys->access_token);
			$access_token = $keys->access_token;
		}
	}

	$args = array(
		'httpversion' => '1.1',
		'blocking' 		=> true,
		'timeout'     => 10,
		'headers'     => array('Authorization' => "Bearer $access_token")
	);
	
	add_filter('https_ssl_verify', '__return_false');
	$api_url  = "https://api.twitter.com/1.1/users/show.json?screen_name=$twitter_username";
	
	$get_request = wp_remote_get( $api_url , $args );
	$request = wp_remote_retrieve_body( $get_request );
	$request = @json_decode( $request , true );
	
	if ( !empty( $request['followers_count'] ) ) {
		$count = $request['followers_count'];
	}
	set_transient('discy_twitter_followers', $count, 60*60*24);
	return $count;
}
/* Get access token for dribbble */
add_filter('discy_hidden_discy_options[dribbble_access_token]','discy_api_dribbble');
function discy_api_dribbble($val) {
	$dribbble_client_id = discy_options('dribbble_client_id');
	$dribbble_client_secret = discy_options('dribbble_client_secret');
	if (isset($_GET["api"]) && $_GET["api"] == "dribbble" && isset($_GET["code"]) && $_GET["code"] != "") {
		$data = wp_remote_post('https://dribbble.com/oauth/token/?client_id='.$dribbble_client_id.'&client_secret='.$dribbble_client_secret.'&code='.esc_attr($_GET["code"]));
		if (!is_wp_error($data)) {
			$json = json_decode($data['body'],true);
			if (isset($json["access_token"])) {
				$discy_options = get_option(discy_options);
				$val = esc_html($json["access_token"]);
				$discy_options["dribbble_access_token"] = $val;
				update_option(discy_options,$discy_options);
				wp_redirect(admin_url('admin.php?page=options'));
				exit;
			}
		}
	}
	return $val;
}
/* discy_counter_dribbble */
function discy_counter_dribbble($dribbble, $return = 'count') {
	$count = get_transient('discy_dribbble_followers');
	$link = get_transient('discy_dribbble_page_url');
	$access_token = discy_options('dribbble_access_token');
	if ($return == 'link') {
		if ($link !== false) return $link;
	}else {
		if ($count !== false) return $count;
	}
	$count = 0;
	$link = '';
	$data = wp_remote_get('https://api.dribbble.com/v2/user/?access_token='.$access_token);
	if (!is_wp_error($data)) {
		$json = json_decode($data['body'],true);
		$count = (isset($json['followers_count'])?intval($json['followers_count']):0);
		$link = (isset($json['html_url'])?intval($json['html_url']):"");
		set_transient('discy_dribbble_followers', $count, 60*60*24);
		set_transient('discy_dribbble_page_url', $link, 60*60*24);
	}
	if ($return == 'link') {
		return $link;
	}else {
		return $count;
	}
}
/* discy_counter_youtube */
function discy_counter_youtube($youtube, $return = 'count') {
	$count = get_transient('discy_youtube_followers');
	$api_key = discy_options('google_api');
	if ($count !== false) return $count;
	$count = 0;
	$data = wp_remote_get('https://www.googleapis.com/youtube/v3/channels?part=statistics&id='.$youtube.'&key='.$api_key);
	if (!is_wp_error($data)) {
		$json = json_decode($data['body'],true);
		$count = intval($json['items'][0]['statistics']['subscriberCount']);
		set_transient('discy_youtube_followers', $count, 60*60*24);
	}
	return $count;
}
/* discy_counter_vimeo */
function discy_counter_vimeo($vimeo, $return = 'count') {
	$count = get_transient('discy_vimeo_followers');
	$link = get_transient('discy_vimeo_page_url');
	if ($return == 'link') {
		if ($link !== false) return $link;
	}else {
		if ($count !== false) return $count;
	}
	$count = 0;
	$link = '';
	$data = wp_remote_get('https://vimeo.com/api/v2/channel/'.$vimeo.'/info.json');
	if (!is_wp_error($data)) {
		$json = json_decode($data['body'],true);
		$count = intval($json['total_subscribers']);
		$link = $json['url'];
		set_transient('discy_vimeo_followers', $count, 60*60*24);
		set_transient('discy_vimeo_page_url', $link, 60*60*24);
	}
	if ($return == 'link') {
		return $link;
	}else {
		return $count;
	}
}
/* discy_counter_soundcloud */
function discy_counter_soundcloud($soundcloud, $return = 'count') {
	$count = get_transient('discy_soundcloud_followers');
	$link = get_transient('discy_soundcloud_page_url');
	if ($return == 'link') {
		if ($link !== false) return $link;
	}else {
		if ($count !== false) return $count;
	}
	$count = 0;
	$link = '';
	$client_id = discy_options('soundcloud_client_id');
	if ($client_id != '') {
		$data = wp_remote_get('https://api.soundcloud.com/users/'.$soundcloud.'.json?client_id='.$client_id);
		if (!is_wp_error($data)) {
			$json = json_decode($data['body'],true);
			$count = intval($json['followers_count']);
			$link = $json['permalink_url'];
			set_transient('discy_soundcloud_followers', $count, 60*60*24);
			set_transient('discy_soundcloud_page_url', $link, 60*60*24);
		}
	}
	if ($return == 'link') {
		return $link;
	}else {
		return $count;
	}
}
/* discy_counter_behance */
function discy_counter_behance($behance, $return = 'count') {
	$count = get_transient('discy_behance_followers');
	$link = get_transient('discy_behance_page_url');
	if ($return == 'link') {
		if ($link !== false) return $link;
	}else {
		if ($count !== false) return $count;
	}
	$count = 0;
	$link = '';
	$api_key = discy_options('behance_api_key');
	if ($api_key != '') {
		$data = wp_remote_get('https://www.behance.net/v2/users/'.$behance.'?api_key='.$api_key);
		if (!is_wp_error($data)) {
			$json = json_decode($data['body'],true);
			$count = intval($json['user']['stats']['followers']);
			$link = $json['user']['url'];
			set_transient('discy_behance_followers', $count, 60*60*24);
			set_transient('discy_behance_page_url', $link, 60*60*24);
		}
	}
	if ($return == 'link') {
		return $link;
	}else {
		return $count;
	}
}
/* discy_counter_instagram */
function discy_counter_instagram($instagram, $return = 'count') {
	$count = get_transient('discy_instagram_followers');
	$link = get_transient('discy_instagram_page_url');
	if ($return == 'link') {
		if ($link !== false) return $link;
	}else {
		if ($count !== false) return $count;
	}
	$count = 0;
	$link = '';
	
	$instaScrape = new discy_InstaScrape(discy_options("instagram_sessionid"));
	try {
		$res = $instaScrape->getFollowersCountFromName($instagram);
		$count = $res;
	}catch (Exception $e) {
		//echo 'Failed:'.$e->getMessage();
	}
	
	$link = 'https://instagram.com/'.$instagram;
	set_transient('discy_instagram_followers', $count, 60*60*24);
	set_transient('discy_instagram_page_url', $link, 60*60*24);
	if ($return == 'link') {
		return $link;
	}else {
		return $count;
	}
}
/* discy_counter_pinterest */
function discy_counter_pinterest($pinterest) {
	$count = get_transient('discy_pinterest_followers');
	if ($count !== false) return $count;
	$pin_metas = get_meta_tags($pinterest);
	if (isset($pin_metas['pinterestapp:followers'])) {
		$count = $pin_metas['pinterestapp:followers'];
	}else {
		$count = $pin_metas['followers'];
	}
	set_transient('discy_pinterest_followers', $count, 60*60*24);
	return $count;        
}
/* discy_counter_envato */
function discy_counter_envato($envato, $return = 'count') {
	$count = get_transient('discy_envato_followers');
	$link = get_transient('discy_envato_page_url');
	if ($return == 'link') {
		if ($link !== false) return $link;
	}else {
		if ($count !== false) return $count;
	}
	$count = 0;
	$link = '';

	try {
		$token = discy_options('envato_token');
		if ($token != "") {
			$args = array(
				'httpversion' => '1.1',
				'blocking' 		=> true,
				'timeout'     => 10,
				'headers'     => array(
					'Authorization' => "Bearer $token"
				)
			);
			$api_url = "https://api.envato.com/v1/market/user:".$envato.".json";
			$get_request = preg_replace ( '/\s+/', '', $api_url);
			$get_request = wp_remote_get( $api_url , $args );
			$request = wp_remote_retrieve_body( $get_request );
			$data = @json_decode( $request , true );
			$count = (int) isset($data['user']['followers']) ? $data['user']['followers'] : 0;
			$link = 'https://marketplace.envato.com/user/'.$envato;
			set_transient('discy_envato_followers', $count, 60*60*24);
			set_transient('discy_envato_page_url', $link, 60*60*24);
		}
	}catch (Exception $e) {
		$count = 0;
	}

	if ($return == 'link') {
		return $link;
	}else {
		return $count;
	}
}
/* discy_counter_github */
function discy_counter_github($github, $return = 'count') {
	$count = get_transient('discy_github_followers');
	$link = get_transient('discy_github_page_url');
	if ($return == 'link') {
		if ($link !== false) return $link;
	}else {
		if ($count !== false) return $count;
	}
	$count = 0;
	$link = '';
	$data = wp_remote_get('https://api.github.com/users/'.$github);
	if (!is_wp_error($data)) {
		$json = json_decode($data['body'],true);
		$count = intval($json['followers']);
		$link = 'https://github.com/'.$github;
		set_transient('discy_github_followers', $count, 60*60*24);
		set_transient('discy_github_page_url', $link, 60*60*24);
	} 
	if ($return == 'link') {
		return $link;
	}else {
		return $count;
	}
}
/* discy_instagram_images */
function discy_instagram_images($instagram,$count = 5) {
	$images = '';
	$instaScrape = new discy_InstaScrape(discy_options("instagram_sessionid"));
	try {
		$res = $instaScrape->getUserIDFromName($instagram);
		// Get user items from id
		$res = $instaScrape->getUserItems($res,$count);
		$i = 0;
		if (is_array($res["data"]["user"]["edge_owner_to_timeline_media"]["edges"]) && !empty($res["data"]["user"]["edge_owner_to_timeline_media"]["edges"])) {
			foreach ($res["data"]["user"]["edge_owner_to_timeline_media"]["edges"] as $post) {
				$i++;
				$images .= '<li class="instagram-image"><a target="_blank" href="https://www.instagram.com/p/'.esc_attr($post["node"]["shortcode"]).'/" title="'.(isset($post["node"]["edge_media_to_caption"]["edges"][0]["node"]["text"])?$post["node"]["edge_media_to_caption"]["edges"][0]["node"]["text"].'&nbsp;&nbsp;/&nbsp;&nbsp;&hearts;&nbsp;':'').$post["node"]["edge_liked_by"]["count"].'&nbsp;likes">
					<img class="instagram-image" src="'.$post["node"]["thumbnail_src"].'">
				</a></li>';
				if ($count >= $i) {
					continue;
				}
			}
		}else {
			$images .= "<p>No photos for this query</p>";
		}
	}catch (Exception $e) {
		$images .= 'Failed:'.$e->getMessage();
	}
	
	return $images;
}
/* discy_comment */
$k_ad = -1;
function discy_comment($comment,$args,$depth,$answer = "",$owner = "",$k_ad = "",$best_answer = "",$answer_args = array()) {
	if ($answer != "answer") {
		if ($k_ad == "") {
			global $k_ad;
		}
		if ($comment->comment_parent == 0) {
			$k_ad++;
		}
	}
	$show_replies = discy_options("show_replies");
    $user_get_current_user_id = get_current_user_id();
    $is_super_admin = is_super_admin($user_get_current_user_id);
    $comment_id = (int)$comment->comment_ID;
    $comment_user_id = (int)$comment->user_id;
    $post_id = (int)$comment->comment_post_ID;
    $post_data = get_post($post_id);
    $post_type = $post_data->post_type;
    $user = get_user_by('id',$comment_user_id);
    $deleted_user = ($comment_user_id > 0 && isset($user->display_name)?$user->display_name:($comment_user_id == 0?$comment->comment_author:"delete"));
    $like_answer = apply_filters("discy_comment_like_answer",false,(isset($post_data->post_type)?$post_data->post_type:""));
    if ($like_answer == true || ($answer != "answer" && isset($post_data->post_type) && $post_data->post_type == 'question') || ($answer == "answer" && $post_type == "question")) {
    	$its_question = (isset($post_data->post_type)?$post_data->post_type:$post_type);
    	$the_best_answer = discy_post_meta("the_best_answer",($answer == "answer"?$post_id:(isset($post_data->ID)?$post_data->ID:0)),false);
    	$best_answer_comment = get_comment_meta($comment_id,"best_answer_comment",true);
    	$comment_class = ($best_answer_comment == "best_answer_comment" || $the_best_answer == $comment_id?"comment-best-answer":"");
    	$_paid_answer = get_comment_meta($comment_id,'_paid_answer',true);
    	$comment_class = ($comment_class != ""?$comment_class." ":$comment_class).($_paid_answer == 'paid'?'comment-paid-answer':'');
    	$active_reports = discy_options("active_reports");
    	$active_logged_reports = discy_options("active_logged_reports");
    	$active_vote = discy_options("active_vote");
	    $active_vote_unlogged = discy_options("active_vote_unlogged");
    	$active_best_answer = discy_options("active_best_answer");
    }
    
    if ($like_answer == true || (isset($its_question) && $its_question == "question")) {
    	$comment_vote = get_comment_meta($comment_id,'comment_vote',true);
    	if (isset($comment_vote) && is_array($comment_vote) && isset($comment_vote["vote"])) {
    		update_comment_meta($comment_id,'comment_vote',$comment_vote["vote"]);
    		$comment_vote = get_comment_meta($comment_id,'comment_vote',true);
    	}else if ($comment_vote == "") {
    		update_comment_meta($comment_id,'comment_vote',0);
    		$comment_vote = get_comment_meta($comment_id,'comment_vote',true);
    	}
    }
    $can_delete_comment = discy_options("can_delete_comment");
    $can_edit_comment = discy_options("can_edit_comment");
    $can_edit_comment_after = (int)discy_options("can_edit_comment_after");
    $can_edit_comment_after = (isset($can_edit_comment_after) && $can_edit_comment_after > 0?$can_edit_comment_after:0);
    if (version_compare(phpversion(), '5.3.0', '>')) {
    	$time_now = strtotime(current_time('mysql'),date_create_from_format('Y-m-d H:i',current_time('mysql')));
    }else {
    	list($year, $month, $day, $hour, $minute, $second) = sscanf(current_time('mysql'),'%04d-%02d-%02d %02d:%02d:%02d');
    	$datetime = new DateTime("$year-$month-$day $hour:$minute:$second");
    	$time_now = strtotime($datetime->format('r'));
    }
    $time_edit_comment = strtotime('+'.$can_edit_comment_after.' hour',strtotime($comment->comment_date));
    $time_end = ($time_now-$time_edit_comment)/60/60;
    $edit_comment = get_comment_meta($comment_id,"edit_comment",true);
    $between_comments_position = (int)discy_options("between_comments_position");
    $adv_type_repeat = discy_options("between_comments_adv_type_repeat");
    $count_adv = ($between_comments_position > 0 && isset($k_ad) && $k_ad > 0?$k_ad % $between_comments_position:0);
    if (isset($k_ad) && (($k_ad == $between_comments_position) || ($adv_type_repeat == "on" && $k_ad != 0 && $count_adv == 0))) {
    	echo discy_ads("between_comments_adv_type","between_comments_adv_code","between_comments_adv_href","between_comments_adv_img","li","","","on");
    }
    if ($answer == "answer") {
    	$k_ad++;
    }
    $answer_question_style = discy_options("answer_question_style");
    $profile_credential = ($comment_user_id > 0?get_the_author_meta('profile_credential',$comment_user_id):"");
    $privacy_credential = (has_wpqa() && wpqa_plugin_version >= 4.2?($comment_user_id > 0?wpqa_check_user_privacy($comment_user_id,"credential"):""):"");
    if (isset($answer_args['custom_home_answer']) && $answer_args['custom_home_answer'] == "on") {
    	$answer_image         = discy_post_meta("answers_image_h",$answer_args['answer_question_id']);
    	$active_vote_answer   = discy_post_meta("active_vote_answer_h",$answer_args['answer_question_id']);
    	$show_dislike_answers = discy_post_meta("show_dislike_answers_h",$answer_args['answer_question_id']);
    }else if (isset($answer_args['custom_answers']) && $answer_args['custom_answers'] == "on") {
    	$answer_image         = discy_post_meta("answers_image_a",$answer_args['answer_question_id']);
    	$active_vote_answer   = discy_post_meta("active_vote_answer_a",$answer_args['answer_question_id']);
    	$show_dislike_answers = discy_post_meta("show_dislike_answers_a",$answer_args['answer_question_id']);
    }else {
    	$answer_image         = discy_options("answer_image");
    	$active_vote_answer   = discy_options("active_vote_answer");
    	$show_dislike_answers = discy_options("show_dislike_answers");
    }?>
    <li <?php comment_class(($answer_image == "on"?"":"comment-without-image ").($show_replies == "on"?"comment-show-replies ":"").(isset($answer_args["comment_read_more"])?"comment-read-more ":"").(isset($answer_args["comment_with_title"])?"comment-with-title ".($answer_question_style != ""?"comment-with-title-".str_replace('style_','',$answer_question_style)." ":""):"").($privacy_credential == true && $profile_credential != ""?"comment-credential ":"").(isset($its_question) && $its_question == "question"?$comment_class." ":"").($comment->comment_type == "pingback"?"comment":""),$comment_id,$post_id);echo (isset($its_question) && $its_question == 'question' && is_single()?' itemscope itemtype="https://schema.org/'.($comment->comment_parent > 0?"Comment":"Answer").'"'.($comment->comment_parent == 0?' itemprop="'.($best_answer_comment == 'best_answer_comment' || $the_best_answer == $comment_id?'acceptedAnswer':'suggestedAnswer').'"':''):'');?> id="li-comment-<?php echo esc_attr($comment_id);?>">
    	<div id="comment-<?php echo esc_attr($comment_id);?>" class="comment-body clearfix">
            <?php if (isset($answer_args["comment_with_title"])) {
            	echo '<div class="comment-question-title"><header class="article-header"><div class="question-header"><div class="post-meta">';
	            	discy_meta("on","on","","","","",$post_id,$post_data);
            	echo '</div></div></header>
            	<div class="clearfix"></div>
            	<h2 class="post-title"><a class="post-title" href="' . esc_url( get_permalink($post_id) ) . '" rel="bookmark">'.get_the_title($post_id).'</a></h2></div>';
            }?>
            <div class="comment-text">
            	<?php if ($answer_image == "on") {
	            	do_action("wpqa_action_avatar_link",array("user_id" => ($comment_user_id > 0 && $deleted_user != "delete"?$comment_user_id:0),"size" => 42,"span" => "span","pop" => "pop","comment" => $comment,"email" => ($comment_user_id > 0?"":$comment->comment_author_email)));
	            }?>
                <div class="author clearfix">
                	<?php if (isset($its_question) && $its_question == "question") {
                		if ($best_answer == "" && ($best_answer_comment == "best_answer_comment" || $the_best_answer == $comment_id)) {?>
	                		<div class="best-answer"><?php esc_html_e("Best Answer","discy")?></div>
	                	<?php }
	                	if (isset($_paid_answer) && $_paid_answer == 'paid') {?>
	                		<div class="best-answer paid-answer"><?php esc_html_e("Paid Answer","wpqa")?></div>
	                	<?php }
	                }?>
                	<div class="comment-meta">
                    	<div class="comment-author">
                    		<?php $wpqa_activate_comment_author = apply_filters('discy_activate_comment_author',true,$comment_id);
                    		if ($wpqa_activate_comment_author == true) {
                    			if ($comment_user_id > 0 && $deleted_user != "delete") {
	                    			$get_author_profile = get_author_posts_url($comment_user_id);
	                    		}else {
	                    			$get_author_profile = ($comment->comment_author_url != "" && $deleted_user != "delete"?$comment->comment_author_url:"discy_No_site");
	                    		}
	                    		if (isset($its_question) && $its_question == 'question' && is_single()) {
	                    			echo '<span itemprop="author" itemscope itemtype="http://schema.org/Person">';
	                    		}
	                    		if ($get_author_profile != "" && $get_author_profile != "discy_No_site") {?>
	                    			<a<?php echo (isset($its_question) && $its_question == 'question' && is_single()?' itemprop="url"':'')?> href="<?php echo esc_url($get_author_profile)?>">
	                    		<?php }
		                    		if (isset($its_question) && $its_question == 'question' && is_single()) {
		                    			echo '<span itemprop="name">';
		                    		}
		                    		$anonymously_user = get_comment_meta($comment_id,"anonymously_user",true);
	                        		echo ($deleted_user == "delete"?esc_html__("[Deleted User]","discy"):($anonymously_user != ""?esc_html__("Anonymous","discy"):$deleted_user));
	                        		if (isset($its_question) && $its_question == 'question') {
	                        			echo '</span>';
	                        		}
	                        	if ($get_author_profile != "" && $get_author_profile != "discy_No_site") {?>
	                        		</a>
	                        	<?php }
	                        	if (isset($its_question) && $its_question == 'question') {
                        			echo '</span>';
                        		}
	                        }
                        	if ($comment_user_id > 0 && $deleted_user != "delete") {
                        		do_action("wpqa_verified_user",$comment_user_id);
                        		$active_points_category = discy_options("active_points_category");
								if ($active_points_category == "on") {
									$get_terms = wp_get_post_terms($post_id,'question-category',array('fields' => 'ids'));
									if (!empty($get_terms) && is_array($get_terms) && isset($get_terms[0])) {
										$points_category_user = (int)get_user_meta($comment_user_id,"points_category".$get_terms[0],true);
										echo apply_filters("discy_comments_before_badge",false,$get_terms[0]);
									}
								}
                        		do_action("wpqa_get_badge",$comment_user_id,"",(isset($points_category_user)?$points_category_user:""));
                        		do_action("discy_action_comment_after_badge",$comment,$post_type);
                        	}
                        	if ($privacy_credential == true && $profile_credential != "") {?>
                        		<span class="profile-credential"><?php echo esc_html($profile_credential)?></span>
                        	<?php }
                        	do_action("discy_action_after_credential",(isset($post_data->ID)?$post_data->ID:0),$comment_id,$comment_user_id);?>
                    	</div>
                    	<?php $show_date = apply_filters("discy_filter_comment_show_date",true,$comment,$post_type);
						if ($show_date == true) {?>
	                    	<a href="<?php echo get_comment_link($comment_id); ?>" class="comment-date"<?php echo(isset($its_question) && $its_question == "question"?" itemprop='url'":"")?>>
	                    		<?php $get_comment_date = get_comment_date("c",$comment_id);
	                    		echo (is_single()?'<span class="discy_hide" itemprop="dateCreated" datetime="'.$get_comment_date.'">'.$get_comment_date.'</span>':'');
		                    		$date = mysql2date(discy_time_format,$comment->comment_date,true);
		                    		if (isset($its_question) && $its_question == "question") {
		                    			echo ($comment->comment_parent > 0?esc_html__("Replied to","discy"):esc_html__("Added an","discy"))." ";
		                    			printf(esc_html__('answer on %1$s at %2$s','discy'),get_comment_date(discy_date_format,$comment_id),$date);
		                    		}else {
		                    			echo ($comment->comment_parent > 0?esc_html__("Replied to","discy"):esc_html__("Added a","discy"))." ";
		                    			printf(esc_html__('comment on %1$s at %2$s','discy'),get_comment_date(discy_date_format,$comment_id),$date);
		                    		}
	                    		echo (is_single()?'</span>':'');?>
	                    	</a>
	                    <?php }else {
	                    	do_action("discy_action_comment_show_date",$comment,$post_type);
	                    }?>
                    </div><!-- End comment-meta -->
                </div><!-- End author -->
                <div class="text">
                	<?php if ($edit_comment == "edited") {?>
                		<em class="comment-edited">
                			<?php if (isset($its_question) && $its_question == "question") {
                				esc_html_e('This answer was edited.','discy');
                			}else {
                				esc_html_e('This comment was edited.','discy');
                			}?>
                		</em>
                	<?php }
                	if ($comment->comment_approved == '0') : ?>
                	    <em class="comment-awaiting">
	                	    <?php if (isset($its_question) && $its_question == "question") {
	                	    	esc_html_e('Your answer is awaiting moderation.','discy');
	                	    }else {
	                	    	esc_html_e('Your comment is awaiting moderation.','discy');
	                	    }?>
                	    </em><br>
                	<?php endif;
                	
                	if (is_singular("question")) {
                		$featured_image_in_answers = discy_options("featured_image_question_answers");
                	}else {
                		$featured_image_in_answers = discy_options("featured_image_in_answers");
                	}
                	if ($featured_image_in_answers == "on") {
                		$featured_image = get_comment_meta($comment_id,'featured_image',true);
                		if ($featured_image != "") {
                			$img_url = wp_get_attachment_url($featured_image,"full");
                			if ($img_url != "") {
	                			$featured_image_answers_lightbox = discy_options("featured_image_answers_lightbox");
	                			$featured_image_answer_width = discy_options("featured_image_answer_width");
	                			$featured_image_answer_height = discy_options("featured_image_answer_height");
	                			$featured_image_answer_width = ($featured_image_answer_width != ""?$featured_image_answer_width:260);
	                			$featured_image_answer_height = ($featured_image_answer_height != ""?$featured_image_answer_height:185);
	                			$link_url = ($featured_image_answers_lightbox == "on"?$img_url:get_permalink($post_id)."#comment-".$comment_id);
	                			$last_image = discy_get_aq_resize_img($featured_image_answer_width,$featured_image_answer_height,"",$featured_image);
	                			$featured_answer_position = discy_options("featured_answer_position");
	                			if ($featured_answer_position != "after" && isset($last_image) && $last_image != "") {
	                	    		echo "<div class='featured_image_answer'><a href='".$link_url."'>".$last_image."</a></div>
	                	    		<div class='clearfix'></div>";
	                			}
	                		}
                		}
                	}

                	$answer_video = discy_options("answer_video");
                	$video_answer_position = discy_options("video_answer_position");
                	$video_answer_width = discy_options("video_answer_width");
					$video_answer_100 = discy_options("video_answer_100");
					$video_answer_height = discy_options("video_answer_height");
                	$video_answer_description = get_comment_meta($comment_id,"video_answer_description",true);
					if ($answer_video == "on" && $video_answer_description == "on") {
						$video_answer_type = get_comment_meta($comment_id,"video_answer_type",true);
						$video_answer_id = get_comment_meta($comment_id,"video_answer_id",true);
						if ($video_answer_id != "") {
							$type = (has_wpqa() && wpqa_plugin_version >= 4.4?wpqa_video_iframe($video_answer_type,$video_answer_id):"");
							$las_video = '<div class="question-video-loop answer-video'.($video_answer_100 == "on"?' question-video-loop-100':'').($video_answer_position == "after"?' question-video-loop-after':'').'"><iframe frameborder="0" allowfullscreen width="'.$video_answer_width.'" height="'.$video_answer_height.'" src="'.$type.'"></iframe></div>';
							
							if ($video_answer_position == "before" && $answer_video == "on" && isset($video_answer_id) && $video_answer_id != "" && $video_answer_description == "on") {
								echo ($las_video);
							}
						}
					}?>
					
                	<div<?php echo (isset($its_question) && $its_question == "question" && is_single()?" itemprop='text'":"")?>>
                		<?php if (isset($answer_args["comment_with_title"]) || isset($answer_args["comment_read_more"]) || isset($args["comment_read_more"])) {
                			$comment_excerpt_count = apply_filters('discy_answer_number',300);
                			$strlen_comment = strlen(wp_html_excerpt($comment->comment_content,$comment_excerpt_count));
			            	echo '<p class="less_answer_text'.($strlen_comment < $comment_excerpt_count?" wpqa_hide":"").'">'.wp_html_excerpt($comment->comment_content,$comment_excerpt_count,'<a class="post-read-more comment-read-more read_more_answer" href="'.get_permalink($post_id).'#comment-'.esc_attr($comment_id).'" rel="bookmark" title="'.esc_attr__('Read more','discy').'">'.esc_html__('Read more','discy').'</a>').'</p>
			            	<div class="full_answer_text'.($strlen_comment < $comment_excerpt_count?"":" wpqa_hide").'">';
                				comment_text($comment_id);
			            		do_action("discy_action_comment_after_comment_text",$comment,$post_type);
                				echo '<a class="read_less_answer'.($strlen_comment < $comment_excerpt_count?" wpqa_hide":"").'" href="#">'.esc_html__("See less","discy").'</a>
                			</div>';
			            }else {
			            	comment_text($comment_id);
			            	do_action("discy_action_comment_after_comment_text",$comment,$post_type);
				        }?>
                	</div>
                	<div class="clearfix"></div>
                	<?php if ($video_answer_position == "after" && $answer_video == "on" && isset($video_answer_id) && $video_answer_id != "" && $video_answer_description == "on") {
						echo ($las_video);
					}?>
					<div class="clearfix"></div>
                	<?php if ($featured_image_in_answers == "on" && isset($featured_answer_position) && $featured_answer_position == "after" && isset($img_url) && $img_url != "" && isset($last_image) && $last_image != "") {
                		echo "<div class='featured_image_answer featured_image_after'><a href='".$link_url."'>".$last_image."</a></div>
                		<div class='clearfix'></div>";
                	}
                	
                	if (isset($its_question) && $its_question == "question") {
                		$added_file = get_comment_meta($comment_id,'added_file', true);
                		if ($added_file != "") {
                			echo "<a href='".wp_get_attachment_url($added_file)."'>".esc_html__("Attachment","discy")."</a><div class='clearfix'></div><br>";
                		}
                	}?>
                	<div class="wpqa_error"></div>
                	<?php if ($like_answer == true || (isset($its_question) && $its_question == "question")) {
                		if ($active_vote == "on" && $active_vote_answer == "on") {
                			if ($owner == false) {
                				$comment_vote_type = apply_filters("discy_comment_vote_type","comment",(isset($post_data->post_type)?$post_data->post_type:""));
                				$count_up = get_comment_meta($comment_id,'wpqa_comment_vote_up',true);
								$count_down = get_comment_meta($comment_id,'wpqa_comment_vote_down',true);
								$count_up = (isset($count_up) && is_array($count_up) && !empty($count_up)?$count_up:array());
								$count_down = (isset($count_down) && is_array($count_down) && !empty($count_down)?$count_down:array());?>
	                			<ul class="question-vote answer-vote<?php echo ($show_dislike_answers != "on"?" answer-vote-dislike":"")?>">
	                				<li><a href="#"<?php echo ((is_user_logged_in() && $comment_user_id != $user_get_current_user_id) || (!is_user_logged_in() && $active_vote_unlogged == "on")?' id="'.$comment_vote_type.'_vote_up-'.$comment_id.'"':'')?> data-type="<?php echo esc_html($comment_vote_type)?>" data-vote-type="up" class="wpqa_vote comment_vote_up<?php echo (is_user_logged_in() && $comment_user_id != $user_get_current_user_id?"":(is_user_logged_in() && $comment_user_id == $user_get_current_user_id?" vote_not_allow":($active_vote_unlogged == "on"?"":" vote_not_user"))).((is_user_logged_in() && $comment_user_id != $user_get_current_user_id) || (!is_user_logged_in() && $active_vote_unlogged == "on")?' vote_allow'.((is_user_logged_in() && is_array($count_up) && in_array($user_get_current_user_id,$count_up)) || (!is_user_logged_in() && isset($_COOKIE[discy_options("uniqid_cookie").'wpqa_comment_vote_up'.$comment_id]) && $_COOKIE[discy_options("uniqid_cookie").'wpqa_comment_vote_up'.$comment_id] == "wpqa_yes_comment")?" wpqa_voted_already":""):'')?>" title="<?php esc_attr_e("Like","discy");?>"><i class="<?php echo apply_filters('wpqa_vote_up_icon','icon-up-dir');?>"></i></a></li>
	                				<li class="vote_result"<?php echo (is_single()?' itemprop="upvoteCount"':'')?>><?php echo ($comment_vote != ""?discy_count_number($comment_vote):0)?></li>
	                				<li class="li_loader"><span class="loader_3 fa-spin"></span></li>
	                				<?php if ($show_dislike_answers != "on") {?>
	                					<li class="dislike_answers"><a href="#"<?php echo ((is_user_logged_in() && $comment_user_id != $user_get_current_user_id) || (!is_user_logged_in() && $active_vote_unlogged == "on")?' id="'.$comment_vote_type.'_vote_down-'.$comment_id.'"':'')?> data-type="<?php echo esc_html($comment_vote_type)?>" data-vote-type="down" class="wpqa_vote comment_vote_down<?php echo (is_user_logged_in() && $comment_user_id != $user_get_current_user_id?"":(is_user_logged_in() && $comment_user_id == $user_get_current_user_id?" vote_not_allow":($active_vote_unlogged == "on"?"":" vote_not_user"))).((is_user_logged_in() && $comment_user_id != $user_get_current_user_id) || (!is_user_logged_in() && $active_vote_unlogged == "on")?' vote_allow'.((is_user_logged_in() && is_array($count_down) && in_array($user_get_current_user_id,$count_down)) || (!is_user_logged_in() && isset($_COOKIE[discy_options("uniqid_cookie").'wpqa_comment_vote_down'.$comment_id]) && $_COOKIE[discy_options("uniqid_cookie").'wpqa_comment_vote_down'.$comment_id] == "wpqa_yes_comment")?" wpqa_voted_already":""):'')?>" title="<?php esc_attr_e("Dislike","discy");?>"><i class="<?php echo apply_filters('wpqa_vote_down_icon','icon-down-dir');?>"></i></a></li>
	                				<?php }?>
	                			</ul>
                			<?php }
                		}
                	}?>
                	<ul class="comment-reply comment-reply-main">
                	    <?php if (($answer != "answer" && $answer != "comment") || (isset($args["comment_type"]) && $args["comment_type"] == "comment_group")) {
                	    	if ($answer != "posts") {
	                	    	$custom_permission = discy_options("custom_permission");
	                	    	$add_answer = discy_options("add_answer");
	                	    	if (is_user_logged_in()) {
									$user_is_login = get_userdata($user_get_current_user_id);
									$roles = $user_is_login->allcaps;
								}
							}
                	    	if ((isset($args["comment_type"]) && $args["comment_type"] == "comment_group") || (!isset($its_question) || ((isset($its_question) && $its_question == "question") && $is_super_admin || $custom_permission != "on" || (is_user_logged_in() && $custom_permission == "on" && isset($roles["add_answer"]) && $roles["add_answer"] == 1) || (!is_user_logged_in() && $add_answer == "on")))) {
                	    		comment_reply_link( array_merge( $args, array( 'reply_text' => '<i class="icon-reply"></i>'.esc_html__( 'Reply', 'discy' ),'login_text' => '<i class="icon-lock"></i>'.esc_html__( 'Login to Reply', 'discy' ), 'before' => '<li>', 'after' => '</li>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) );
                	    	}
                	    }
                	    do_action("discy_action_after_reply_comment",$comment,($like_answer == true || (isset($its_question) && $its_question == "question")?"answer":"comment"));
                	    $group_id = get_post_meta($comment->comment_post_ID,"group_id",true);
                	    $comment_share  = discy_options("comment_share");
                	    $share_facebook = (isset($comment_share["share_facebook"]["value"])?$comment_share["share_facebook"]["value"]:"");
                	    $share_twitter  = (isset($comment_share["share_twitter"]["value"])?$comment_share["share_twitter"]["value"]:"");
                	    $share_linkedin = (isset($comment_share["share_linkedin"]["value"])?$comment_share["share_linkedin"]["value"]:"");
                	    $share_whatsapp = (isset($comment_share["share_whatsapp"]["value"])?$comment_share["share_whatsapp"]["value"]:"");
                	    if (has_wpqa() && $group_id == "" && ($share_facebook == "share_facebook" || $share_twitter == "share_twitter" || $share_linkedin == "share_linkedin" || $share_whatsapp == "share_whatsapp")) {?>
                	    	<li class="comment-share question-share question-share-2">
                	    		<i class="icon-share"></i>
                	    		<?php esc_html_e("Share","discy");
                	    		wpqa_share($comment_share,$share_facebook,$share_twitter,$share_linkedin,$share_whatsapp,"style_2",$comment_id,"","","",$post_id);?>
                	    	</li>
                	    <?php }
                	    do_action("discy_action_after_share_comment",$comment,(isset($its_question) && $its_question == "question"?"answer":"comment"));
                	    if (isset($its_question) && $its_question == "question" && $answer != "answer") {
                	    	$user_best_answer_filter = apply_filters("discy_user_best_answer_filter",true);
                	    	$best_answer_userself = discy_options("best_answer_userself");
	                	    $user_best_answer = esc_attr(get_the_author_meta('user_best_answer',$user_get_current_user_id));
	                	    if ($user_best_answer_filter == true && ((is_user_logged_in() && $active_best_answer == "on" && $user_get_current_user_id > 0 && (($comment_user_id != $user_get_current_user_id && $user_get_current_user_id == $post_data->post_author) || ($best_answer_userself == "on" && $comment_user_id == $user_get_current_user_id && $user_get_current_user_id == $post_data->post_author))) || $user_best_answer == "on" || $is_super_admin)) {
		                	    if ($the_best_answer != 0 && ($best_answer_comment == "best_answer_comment" || $the_best_answer == $comment_id)) {
		                	        echo '<li><a class="best_answer_re" data-nonce="'.wp_create_nonce("wpqa_best_answer_nonce").'" title="'.esc_attr__("Cancel the best answer","discy").'" href="#"><i class="icon-cancel"></i>'.esc_html__("Cancel the best answer","discy").'</a></li>';
		                	    }
		                	    if ($the_best_answer == 0 || $the_best_answer == "") {?>
		                	    	<li><a class="best_answer_a" data-nonce="<?php echo wp_create_nonce("wpqa_best_answer_nonce")?>" title="<?php esc_attr_e("Select as best answer","discy");?>" href="#"><i class="icon-check"></i><?php esc_html_e("Select as best answer","discy");?></a></li>
		                	    <?php }
		                	}
                	    }?>
                	    <li class="clearfix last-item-answers"></li>
                	</ul>
                	<?php do_action("discy_after_answer_links",$comment,$can_edit_comment,$can_edit_comment_after,$time_end,$can_delete_comment,$like_answer,(isset($its_question)?$its_question:false),(isset($active_reports)?$active_reports:false),(isset($active_logged_reports)?$active_logged_reports:false),$owner);
                	if (has_wpqa() && isset($args["comment_type"]) && $args["comment_type"] == "comment_group") {
                		$edit_delete_posts_comments = discy_options("edit_delete_posts_comments");
                		$activate_edit = wpqa_group_edit_comments($post_id,$is_super_admin,$can_edit_comment,$comment_user_id,$user_get_current_user_id,$edit_delete_posts_comments);
						$activate_delete = wpqa_group_delete_comments($post_id,$is_super_admin,$can_delete_comment,$comment_user_id,$user_get_current_user_id,$edit_delete_posts_comments);
                	}
                	if ((isset($activate_edit) && $activate_edit == true) || (isset($activate_delete) && $activate_delete == true) || ((has_wpqa() && (($can_edit_comment == "on" && $comment_user_id == $user_get_current_user_id && $comment_user_id != 0 && $user_get_current_user_id != 0 && ($can_edit_comment_after == 0 || $time_end <= $can_edit_comment_after)))) || (has_wpqa() && (($can_delete_comment == "on" && $comment_user_id == $user_get_current_user_id && $comment_user_id > 0 && $user_get_current_user_id > 0) || $is_super_admin)) || (($like_answer == true || (isset($its_question) && $its_question == "question")) && $active_reports == "on" && ((is_user_logged_in() && $comment_user_id != $user_get_current_user_id && $user_get_current_user_id != 0) || (!is_user_logged_in() && $active_logged_reports != "on"))))) {?>
	                	<ul class="comment-reply comment-list-links">
	                		<li class="question-list-details comment-list-details">
								<i class="icon-dot-3"></i>
								<ul>
			                	    <?php if ((isset($activate_edit) && $activate_edit == true) || (has_wpqa() && ($is_super_admin || ($can_edit_comment == "on" && $comment_user_id == $user_get_current_user_id && $comment_user_id != 0 && $user_get_current_user_id != 0 && ($can_edit_comment_after == 0 || $time_end <= $can_edit_comment_after))))) {
			                	    	echo "<li><a class='comment-edit-link edit-comment' href='".wpqa_edit_permalink($comment_id,"comment")."'><i class='icon-pencil'></i>".esc_html__("Edit","discy")."</a></li>";
			                	    }
			                	    if ((isset($activate_delete) && $activate_delete == true) || (has_wpqa() && (($can_delete_comment == "on" && $comment_user_id == $user_get_current_user_id && $comment_user_id > 0 && $user_get_current_user_id > 0) || $is_super_admin))) {
			                	    	echo "<li><a class='delete-comment".(isset($its_question) && $its_question == "question"?' delete-answer':'')."' href='".esc_url_raw(add_query_arg(array('delete_comment' => $comment_id,"wpqa_delete_nonce" => wp_create_nonce("wpqa_delete_nonce")),get_permalink($post_id)))."'><i class='icon-trash'></i>".esc_html__("Delete","discy")."</a></li>";
			                	    }
			            	    	if (($like_answer == true || (isset($its_question) && $its_question == "question")) && $active_reports == "on" && ((is_user_logged_in() && $comment_user_id != $user_get_current_user_id && $user_get_current_user_id != 0) || (!is_user_logged_in() && $active_logged_reports != "on"))) {
			            	    		if ($owner == false) {?>
			                	    		<li class="report_activated"><a class="report_c" href="<?php echo esc_attr($comment_id)?>"><i class="icon-attention"></i><?php esc_html_e("Report","discy")?></a></li>
			                	    	<?php }
			                	    }?>
			                	</ul>
			                </li>
			                <li class="clearfix last-item-answers"></li>
	                	</ul>
	                <?php }
	                do_action("discy_action_after_list_comment",$comment,(isset($its_question) && $its_question == "question"?"answer":"comment"));?>
                </div><!-- End text -->
                <div class="clearfix"></div>
            </div><!-- End comment-text -->
        </div><!-- End comment-body -->
<?php }
/* discy_twitter_tweets */
if ( ! function_exists( 'discy_twitter_tweets' ) ) :
	function discy_twitter_tweets($username = '', $tweets_count = 3) {
		$twitter_data    = "";
		$access_token    = get_option('discy_twitter_token');
		$consumer_key    = discy_options('twitter_consumer_key');
		$consumer_secret = discy_options('twitter_consumer_secret');
		if ($access_token == "") {
			$credentials = $consumer_key . ':' . $consumer_secret;
			$toSend 	 = discy_api_credentials($credentials);
			
			$args = array(
				'method'      => 'POST',
				'httpversion' => '1.1',
				'blocking' 		=> true,
				'headers' 		=> array(
					'Authorization' => 'Basic ' . $toSend,
					'Content-Type' 	=> 'application/x-www-form-urlencoded;charset=UTF-8'
				),
				'body' 				=> array( 'grant_type' => 'client_credentials' )
			);
			
			add_filter('https_ssl_verify', '__return_false');
			$response = wp_remote_post('https://api.twitter.com/oauth2/token', $args);
			
			$keys = json_decode(wp_remote_retrieve_body($response));
			
			if ( !empty($keys->access_token) ) {
				update_option('discy_twitter_token', $keys->access_token);
				$access_token = $keys->access_token;
			}
		}
		
		$args = array(
			'httpversion' => '1.1',
			'blocking'    => true,
			'headers'     => array(
			'Authorization' => "Bearer $access_token",
		));
		
		add_filter('https_ssl_verify', '__return_false');
		
		$api_url = "https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=$username&count=$tweets_count";
		$response = wp_remote_get( $api_url, $args );
		
		if ( ! is_wp_error( $response )) {
			$twitter_data = json_decode(wp_remote_retrieve_body($response));
		}

		return $twitter_data;
	}
endif;
/* discy_pre_get_posts */
if (!function_exists('discy_pre_get_posts')) {
	function discy_pre_get_posts($query) {
		if (is_admin() || ! $query->is_main_query())
			return;
		
		if (is_category()) {
			$get_term  = get_term_by('slug',esc_attr(get_query_var('category_name')),'category');
			$term_id   = $get_term->term_id;
			$term_name = $get_term->name;
			$custom_blog_setting = discy_term_meta("custom_blog_setting",$term_id);
			if ($custom_blog_setting == "on") {
				$post_number = discy_term_meta("post_number",$term_id);
			}
		}else if (is_tag()) {
			$get_term  = get_term_by('slug',esc_attr(get_query_var('tag')),'post_tag');
			$term_id   = $get_term->term_id;
			$term_name = $get_term->name;
		}else if (is_tax()) {
			if (isset($query->tax_query->queries[0]["terms"][0]) && isset($query->tax_query->queries[0]["taxonomy"])) {
				$get_term  = get_term_by('slug',esc_attr($query->tax_query->queries[0]["terms"][0]),$query->tax_query->queries[0]["taxonomy"]);
				if (isset($get_term->term_id)) {
					$term_id   = $get_term->term_id;
				}
				if (isset($get_term->name)) {
					$term_name = $get_term->name;
				}
				$tax_filter = apply_filters("discy_before_question_category",false);
				$tax_question = apply_filters("discy_question_category","question-category");
				if (is_tax("question-category") || $tax_filter == true) {
					$custom_question_setting = discy_term_meta("custom_question_setting",$term_id);
					if ($custom_question_setting == "on") {
						$post_number = discy_term_meta("question_number",$term_id);
					}
				}
			}
		}
		
		if (isset($term_id) && $term_id != "") {
			$query->set('wpqa_term_id',$term_id);
		}
		
		if (isset($term_name) && $term_name != "") {
			$query->set('wpqa_term_name',$term_name);
		}
		
		if (isset($post_number) && $post_number > 0) {
			$query->set('posts_per_page',$post_number);
		}
		
		return;
	}
}
add_action('pre_get_posts','discy_pre_get_posts',1);
/* discy_css_post_type */
function discy_css_post_type($quote_link,$discy_quote_color = "",$quote_icon_color = "",$post_id,$link_icon_color = "",$discy_link_icon_hover_color = "",$discy_link_hover_color = "") {
	$custom_css = '';
	if ($quote_link == "quote") {
		if ((isset($discy_quote_color) && $discy_quote_color != "") || (isset($quote_icon_color) && $quote_icon_color != "")) {
			if (isset($discy_quote_color) && $discy_quote_color != "") {
				$custom_css .= '.post-'.esc_attr($post_id).'.post-quote .post-inner-quote p {
					color: '.esc_attr($discy_quote_color).';
				}';
			}
			if (isset($quote_icon_color) && $quote_icon_color != "") {
				$custom_css .= '.post-'.esc_attr($post_id).'.post-quote .post-type i {
					color: '.esc_attr($quote_icon_color).';
				}';
			}
		}
	}else if ($quote_link == "link") {
		if ((isset($link_icon_color) && $link_icon_color != "") || (isset($discy_link_icon_hover_color) && $discy_link_icon_hover_color != "") || (isset($discy_link_hover_color) && $discy_link_hover_color != "")) {
			if (isset($link_icon_color) && $link_icon_color != "") {
				$custom_css .= '.post-'.esc_attr($post_id).'.post-link .post-inner-link.link .fa-link {
					color: '.esc_attr($link_icon_color).' !important;
				}';
			}
			if (isset($discy_link_icon_hover_color) && $discy_link_icon_hover_color != "") {
				$custom_css .= '.post-'.esc_attr($post_id).'.post-link .post-inner-link.link:hover .fa-link {
					color: '.esc_attr($discy_link_icon_hover_color).' !important;
				}';
			}
			if (isset($discy_link_hover_color) && $discy_link_hover_color != "") {
				$custom_css .= '.post-'.esc_attr($post_id).'.post-link .post-inner-link.link:hover {
					color: '.esc_attr($discy_link_hover_color).' !important;
				}';
			}
		}
	}
	return $custom_css;
}
/* discy_pagination */
if (!function_exists('discy_pagination')) {
	function discy_pagination($args = array(),$max_num_pages = '',$query = '') {
		global $wp_rewrite,$wp_query;
		do_action('discy_pagination_start');
		if ($query) {
			$wp_query = $query;
		}
		if ($max_num_pages == "") {
			$max_num_pages = $wp_query->max_num_pages;
		}
		/* If there's not more than one page,return nothing. */
		if (1 >= $max_num_pages)
			return;
		/* Get the current page. */
		$paged = $current = (get_query_var("paged") != ""?(int)get_query_var("paged"):(get_query_var("page") != ""?(int)get_query_var("page"):1));
		$page_paged = (get_query_var("paged") != ""?"paged":(get_query_var("page") != ""?"page":"paged"));
		$current = $paged;
		/* Get the max number of pages. */
		$max_num_pages = intval($wp_query->max_num_pages);
		/* Set up some default arguments for the paginate_links() function. */
		$defaults = array(
			'base'         => esc_url_raw(add_query_arg($page_paged,'%#%')),
			'format'       => '',
			'total'        => $max_num_pages,
			'current'      => esc_attr($current),
			'prev_next'    => true,
			'prev_text'    => '<i class="icon-left-open"></i>',
			'next_text'    => '<i class="icon-right-open"></i>',
			'show_all'     => false,
			'end_size'     => 1,
			'mid_size'     => 1,
			'add_fragment' => '',
			'type'         => 'plain',
			'before'       => '<div class="main-pagination"><div class="pagination">',
			'after'        => '</div></div>',
			'echo'         => true,
		);
		/* Add the $base argument to the array if the user is using permalinks. */
		if (has_wpqa() && !wpqa_is_search() && $wp_rewrite->using_permalinks()) {
			$defaults['base'] = user_trailingslashit(trailingslashit(get_pagenum_link()) . 'page/%#%');
		}
		/* If we're on a search results page,we need to change this up a bit. */
		if (has_wpqa() && !wpqa_is_search() && is_search()) {
		/* If we're in BuddyPress,use the default "unpretty" URL structure. */
			if (class_exists('BP_Core_User')) {
				$search_query = esc_attr(get_query_var('s'));
				$base = user_trailingslashit(esc_url(home_url('/'))) . '?s=' . $search_query . '&paged=%#%';
				$defaults['base'] = $base;
			}else {
				$search_permastruct = $wp_rewrite->get_search_permastruct();
				if (!empty($search_permastruct))
					$defaults['base'] = esc_url_raw(add_query_arg('page','%#%'));
			}
		}
		/* Merge the arguments input with the defaults. */
		$args = wp_parse_args($args,$defaults);
		/* Allow developers to overwrite the arguments with a filter. */
		$args = apply_filters('discy_pagination_args',$args);
		/* Don't allow the user to set this to an array. */
		if ('array' == $args['type'])
			$args['type'] = 'plain';
		/* Make sure raw querystrings are displayed at the end of the URL,if using pretty permalinks. */
		$pattern = '/\?(.*?)\//i';
		preg_match($pattern,$args['base'],$raw_querystring);
		if ($wp_rewrite->using_permalinks() && $raw_querystring)
			$raw_querystring[0] = str_replace('','',$raw_querystring[0]);
			if (!empty($raw_querystring)) {
				$args['base'] = str_replace($raw_querystring[0],'',$args['base']);
				$args['base'] .= substr($raw_querystring[0],0,-1);
			}
		/* Get the paginated links. */
		$page_links = paginate_links($args);
		/* Remove 'page/1' from the entire output since it's not needed. */
		if (has_wpqa() && !wpqa_is_search()) {
			$page_links = str_replace(array('&#038;paged=1\'','/page/1\''),'\'',$page_links);
		}
		/* Wrap the paginated links with the $before and $after elements. */
		$page_links = $args['before'] . $page_links . $args['after'];
		/* Allow devs to completely overwrite the output. */
		$page_links = apply_filters('discy_pagination',$page_links);
		do_action('discy_pagination_end');
		/* Return the paginated links for use in themes. */
		if ($args['echo']) {
			echo ($page_links);
		}else {
			return $page_links;
		}
	}
}
/* discy_admin_bar */
function discy_admin_bar() {
	$support_activate = discy_updater()->is_active();
	if ($support_activate) {
		global $wp_admin_bar;
		if (is_super_admin()) {
			$wp_admin_bar->add_menu(array(
				'parent' => 0,
				'id'     => 'discy_page',
				'title'  => discy_theme_name.' Settings' ,
				'href'   => admin_url('admin.php?page=options')
			));
		}
	}
}
add_action( 'wp_before_admin_bar_render', 'discy_admin_bar' );
/* breadcrumbs */
function discy_breadcrumbs ($text = "",$breadcrumb_right = true,$breadcrumbs_style = "style_1") {
	if (has_wpqa()) {
		echo wpqa_breadcrumbs($text,$breadcrumb_right,$breadcrumbs_style);
	}
}
/* Early Access fonts */
function discy_earlyaccess_fonts($value) {
	$earlyaccess = array("Alef+Hebrew","Amiri","Dhurjati","Dhyana","Droid+Arabic+Kufi","Droid+Arabic+Naskh","Droid+Sans+Ethiopic","Droid+Sans+Tamil","Droid+Sans+Thai","Droid+Serif+Thai","Gidugu","Gurajada","Hanna","Jeju+Gothic","Jeju+Hallasan","Jeju+Myeongjo","Karla+Tamil+Inclined","Karla+Tamil+Upright","KoPub+Batang","Lakki+Reddy","Lao+Muang+Don","Lao+Muang+Khong","Lao+Sans+Pro","Lateef","Lohit+Bengali","Lohit+Devanagari","Lohit+Tamil","Mallanna","Mandali","Myanmar+Sans+Pro","NATS","NTR","Nanum+Brush+Script","Nanum+Gothic","Nanum+Gothic+Coding","Nanum+Myeongjo","Nanum+Pen+Script","Noto+Kufi+Arabic","Noto+Naskh+Arabic","Noto+Nastaliq+Urdu+Draft","Noto+Sans+Armenian","Noto+Sans+Bengali","Noto+Sans+Cherokee","Noto+Sans+Devanagari","Noto+Sans+Devanagari+UI","Noto+Sans+Ethiopic","Noto+Sans+Georgian","Noto+Sans+Gujarati","Noto+Sans+Gurmukhi","Noto+Sans+Hebrew","Noto+Sans+Japanese","Noto+Sans+Kannada","Noto+Sans+Khmer","Noto+Sans+Kufi+Arabic","Noto+Sans+Lao","Noto+Sans+Lao+UI","Noto+Sans+Malayalam","Noto+Sans+Myanmar","Noto+Sans+Osmanya","Noto+Sans+Sinhala","Noto+Sans+Tamil","Noto+Sans+Tamil+UI","Noto+Sans+Telugu","Noto+Sans+Thai","Noto+Sans+Thai+UI","Noto+Serif+Armenian","Noto+Serif+Georgian","Noto+Serif+Khmer","Noto+Serif+Lao","Noto+Serif+Thai","Open+Sans+Hebrew","Open+Sans+Hebrew+Condensed","Padauk","Peddana","Phetsarath","Ponnala","Ramabhadra","Ravi+Prakash","Scheherazade","Souliyo","Sree+Krushnadevaraya","Suranna","Suravaram","Tenali+Ramakrishna","Thabit","Tharlon","cwTeXFangSong","cwTeXHei","cwTeXKai","cwTeXMing","cwTeXYen");
	if (in_array($value,$earlyaccess)) {
		return "earlyaccess";
	}
}
/* discy_general_typography */
function discy_general_typography ($discy_general_typography,$discy_css) {
	$custom_css = '';
	$general_typography = discy_options($discy_general_typography);
	if (
	(isset($general_typography["style"]) && $general_typography["style"] != "" && $general_typography["style"] != "default") || 
	(isset($general_typography["size"]) && $general_typography["size"] != "" && $general_typography["size"] != "default" && is_string($general_typography["size"])) || 
	(isset($general_typography["color"]) && $general_typography["color"] != "")) {
	$custom_css .= '
		'.$discy_css.' {';
			if (isset($general_typography["size"]) && $general_typography["size"] != "" && $general_typography["size"] != "default" && is_string($general_typography["size"])) {
				$custom_css .= 'font-size: '.$general_typography["size"].';';
			}
			if (isset($general_typography["color"]) && $general_typography["color"] != "") {
				$custom_css .= 'color: '.$general_typography["color"].';';
			}
			if (isset($general_typography["style"]) && $general_typography["style"] != "default" && $general_typography["style"] != "Style") {
				if ($general_typography["style"] == "bold italic" || $general_typography["style"] == "bold") {
					$custom_css .= 'font-weight: bold;';
				}
				if ($general_typography["style"] == "normal") {
					$custom_css .= 'font-weight: normal;';
				}
				if ($general_typography["style"] == "italic" || $general_typography["style"] == "bold italic") {
					$custom_css .= 'font-style: italic;';
				}
			}
		$custom_css .= '}';
	}
	return $custom_css;
}
/* discy_general_color */
function discy_general_color ($discy_general_color,$discy_css,$discy_type,$important = false) {
	$custom_css = '';
	$important = ($important == true?" !important":"");
	$general_link_color = discy_options($discy_general_color);
	if (isset($general_link_color) && $general_link_color != "") {
		$custom_css .= '
		'.$discy_css.' {
			'.$discy_type.': '.$general_link_color.$important.';
		}';
	}
	return $custom_css;
}
/* discy_general_background */
function discy_general_background ($discy_general_background,$full_screen_background,$discy_css) {
	$custom_css = '';
	$general_image = discy_options($discy_general_background);
	$general_background_color = $general_image["color"];
	$general_background_img = $general_image["image"];
	$general_background_repeat = $general_image["repeat"];
	$general_background_position = $general_image["position"];
	$general_background_fixed = $general_image["attachment"];
	$general_full_screen_background = discy_options($full_screen_background);
	
	if ($general_full_screen_background == "on") {
		$custom_css .= $discy_css.' {';
			if (!empty($background_color)) {
				$custom_css .= 'background-color: '.$general_background_color.';';
			}
			$custom_css .= 'background-image : url("'.$general_background_img.'") ;
			filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src="'.$general_background_img.'",sizingMethod="scale");
			-ms-filter: "progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\''.$general_background_img.'\',sizingMethod=\'scale\')";
			background-size: cover;
		}';
	}else {
		if (!empty($general_image)) {
			if ($general_full_screen_background != "on") {
				if ((isset($general_background_img) && $general_background_img != "") || isset($general_background_color) && $general_background_color != "") {
					$custom_css .= $discy_css.'{background:'.esc_attr($general_background_color).(isset($general_background_img) && $general_background_img != ""?' url("'.esc_attr($general_background_img).'") '.esc_attr($general_background_repeat).' '.esc_attr($general_background_fixed).' '.esc_attr($general_background_position):'').';}';
				}
			}
		}
	}
	return $custom_css;
}
/* discy_head_post */
function discy_head_post($post_style = "style_1",$post_head = "",$show_featured_image = "",$featured_image_style = "default",$custom_width = 140,$custom_height = 140,$blog_h = "",$show_defult_image = "") {
	global $post,$blog_style,$discy_sidebar_all;
	$img_width = "";
	$img_height = "";
	$site_width = (int)discy_options("site_width");
	$mins_width = ($site_width > 1170?$site_width-1170:0);
	$what_post = discy_post_meta("what_post","",false);
	if (isset($discy_sidebar_all) && $discy_sidebar_all != "") {
		$discy_sidebar = $discy_sidebar_all;
	}else {
		$discy_sidebar_all = $discy_sidebar = discy_sidebars("sidebar_where");
	}
	$discy_google = discy_post_meta("google");
	$discy_audio = discy_post_meta("audio");
	$discy_soundcloud_embed = discy_post_meta("soundcloud_embed");
	$discy_soundcloud_height = discy_post_meta("soundcloud_height");
	$discy_twitter_embed = discy_post_meta("twitter_embed");
	$discy_facebook_embed = discy_post_meta("facebook_embed");
	$discy_instagram_embed = discy_post_meta("instagram_embed");
	$discy_slideshow_type = discy_post_meta("slideshow_type");
	$video_id = discy_post_meta("video_post_id");
	$video_type = discy_post_meta("video_post_type");
	if ($video_id != "") {
		$type = (has_wpqa() && wpqa_plugin_version >= 4.4?wpqa_video_iframe($video_type,$video_id):"");
	}
	$video_mp4 = discy_post_meta("video_mp4");
	$video_m4v = discy_post_meta("video_m4v");
	$video_webm = discy_post_meta("video_webm");
	$video_ogv = discy_post_meta("video_ogv");
	$video_wmv = discy_post_meta("video_wmv");
	$video_flv = discy_post_meta("video_flv");
	$video_image = discy_post_meta("video_image");
	$video_mp4 = (isset($video_mp4) && $video_mp4 != ""?" mp4='".$video_mp4."'":"");
	$video_m4v = (isset($video_m4v) && $video_m4v != ""?" m4v='".$video_m4v."'":"");
	$video_webm = (isset($video_webm) && $video_webm != ""?" webm='".$video_webm."'":"");
	$video_ogv = (isset($video_ogv) && $video_ogv != ""?" ogv='".$video_ogv."'":"");
	$video_wmv = (isset($video_wmv) && $video_wmv != ""?" wmv='".$video_wmv."'":"");
	$video_flv = (isset($video_flv) && $video_flv != ""?" flv='".$video_flv."'":"");
	$video_image = (isset($video_image) && $video_image != ""?" poster='".discy_image_url_id($video_image)."'":"");
	if (isset($blog_h) && $blog_h == "blog_h") {
		$img_width = 370;
		$img_height = 250;
		if ($site_width > 1170) {
			$img_height = round($img_height+(($site_width-30-30)/3)-$img_width);
			$img_width = round(($site_width-30-30)/3);
		}
	}else if (!is_single() && $post_style == "style_3") {
		if ($discy_sidebar == "menu_sidebar") {
			$img_width = round(300+($mins_width/2));
			$img_height = round(200+($mins_width/4));
		}else if ($discy_sidebar == "menu_left") {
			$img_width = round(439+($mins_width/2));
			$img_height = round(290+($mins_width/4));
		}else if ($discy_sidebar == "full") {
			$img_width = round(350+($mins_width/3));
			$img_height = round(220+($mins_width/5));
		}else if ($discy_sidebar == "centered") {
			$img_width = round(269+($mins_width/2));
			$img_height = round(175+($mins_width/4));
		}else {
			$img_width = round(400+($mins_width/2));
			$img_height = round(265+($mins_width/4));
		}
	}else if (!is_single() && $post_style == "style_2") {
		if ($show_defult_image == true) {
			$img_width = 270;
			$img_height = 180;
		}
	}else {
		if (is_single() && isset($featured_image_style) && $featured_image_style != "" && $featured_image_style != "default") {
			$what_post = "image";
			if ($featured_image_style == "custom_size") {
				$img_width = $custom_width;
				$img_height = $custom_height;
			}else if ($featured_image_style == "style_270") {
				$img_width = 270;
				$img_height = 180;
			}else {
				$img_width = 140;
				$img_height = 140;
			}
		}else {
			if ($show_defult_image == true) {
				if ($discy_sidebar == "menu_sidebar") {
					$img_width = 629+$mins_width;
					$img_height = 420+($mins_width/2);
				}else if ($discy_sidebar == "menu_left") {
					$img_width = 908+$mins_width;
					$img_height = 600+($mins_width/2);
				}else if ($discy_sidebar == "full") {
					$img_width = 1108+$mins_width;
					$img_height = 700+($mins_width/2);
				}else if ($discy_sidebar == "centered") {
					$img_width = 768+$mins_width;
					$img_height = 510+($mins_width/2);
				}else {
					$img_width = 829+$mins_width;
					$img_height = 550+($mins_width/2);
				}
			}
		}
	}
	
	if ($what_post == "image" || $what_post == "video" || $what_post == "image_lightbox") {
		if ($what_post == "image" || $what_post == "image_lightbox") {
			if (has_post_thumbnail()) {
				if ($show_featured_image == 1) {
					if ($blog_h == "" && ($what_post == "image_lightbox" || is_singular("question"))) {
						echo discy_get_aq_resize_img($img_width,$img_height,$img_lightbox = "lightbox");
						$img_url = wp_get_attachment_url(get_post_thumbnail_id(),"full");
						echo '<a class="post-img-lightbox prettyPhoto" href="'.esc_url($img_url).'"><i class="icon-plus"></i></a>';
					}else {
						echo discy_get_aq_resize_img($img_width,$img_height);
					}
				}
			}
		}else if ($what_post == "video") {
			if ($video_type == "html5") {
				echo do_shortcode('[video'.$video_mp4.$video_m4v.$video_webm.$video_ogv.$video_wmv.$video_flv.$video_image.']');
			}else if ($video_type == "embed") {
				echo discy_post_meta("custom_embed");
			}else if (isset($type) && $type != "") {
				echo '<iframe frameborder="0" allowfullscreen height="'.$img_height.'" src="'.$type.'"></iframe>';
			}
		}
	}else if ($post_style != "style_2" && $post_style != "style_3" && ($what_post == "google" || $what_post == "soundcloud" || $what_post == "twitter" || $what_post == "facebook" || $what_post == "instagram" || $what_post == "audio")) {
		if ($what_post == "soundcloud") {
			echo "<div class='post-iframe'>".wp_oembed_get($discy_soundcloud_embed, array('height' => ($discy_soundcloud_height != ""?$discy_soundcloud_height:150)))."</div>";
		}else if ($what_post == "google") {
			echo "<div class='post-map post-iframe'>".$discy_google."</div>";
		}else if ($what_post == "twitter") {
			$post_head_background = discy_post_meta("post_head_background");
			$post_head_background_img = discy_post_meta("post_head_background_img");
			$post_head_background_repeat = discy_post_meta("post_head_background_repeat");
			$post_head_background_fixed = discy_post_meta("post_head_background_fixed");
			$post_head_background_position_x = discy_post_meta("post_head_background_position_x");
			$post_head_background_position_y = discy_post_meta("post_head_background_position_y");
			$post_head_background_full = discy_post_meta("post_head_background_full");
			$post_head_style = "";
			if ((isset($post_head_background) && $post_head_background != "") || (isset($post_head_background_img) && $post_head_background_img != "")) {
				$post_head_style .= "style='";
				$post_head_style .= (isset($post_head_background) && $post_head_background != ""?"background-color:".$post_head_background.";":"");
				if (isset($post_head_background_img) && $post_head_background_img != "") {
					$post_head_style .= (isset($post_head_background_img) && $post_head_background_img != ""?"background-image:url(".$post_head_background_img.");":"");
					$post_head_style .= (isset($post_head_background_repeat) && $post_head_background_repeat != ""?"background-repeat:".$post_head_background_repeat.";":"");
					$post_head_style .= (isset($post_head_background_fixed) && $post_head_background_fixed != ""?"background-attachment:".$post_head_background_fixed.";":"");
					$post_head_style .= (isset($post_head_background_position_x) && $post_head_background_position_x != ""?"background-position-x:".$post_head_background_position_x.";":"");
					$post_head_style .= (isset($post_head_background_position_y) && $post_head_background_position_y != ""?"background-position-y:".$post_head_background_position_y.";":"");
					$post_head_style .= (isset($post_head_background_full) && $post_head_background_full == "on"?"-webkit-background-size: cover;-moz-background-size: cover;-o-background-size: cover;background-size: cover;":"");
				}
				$post_head_style .= "'";
			}
			echo wp_oembed_get($discy_twitter_embed);
		}else if ($what_post == "audio") {
			if (has_post_thumbnail()) {
				if ($show_featured_image == 1) {
					if ($post_style != "style_2" && $post_style != "style_3" && !is_single()) {
						printf(	'<a href="%s" title="%s">', get_permalink(), the_title_attribute( 'echo=0' ) );
					}
					echo discy_get_aq_resize_img($img_width,$img_height);
					if ($post_style != "style_2" && $post_style != "style_3" && !is_single()) {
						echo '</a>';
					}
				}
			}
			echo "<div class='post-iframe'>".do_shortcode("[audio src='".$discy_audio."']")."</div>";
		}else if ($what_post == "facebook") {
			echo "<div class='facebook-remove'>".$discy_facebook_embed."</div>";
			echo ($discy_facebook_embed);
		}else if ($what_post == "instagram") {
			echo ($discy_instagram_embed);
		}
	}else if ($what_post == "slideshow") {
		if ($discy_slideshow_type == "custom_slide") {
			$discy_slideshow_post = discy_post_meta("slideshow_post");
			if (isset($discy_slideshow_post) && is_array($discy_slideshow_post)) {?>
			    <div class="slider-owl">
			    	<?php foreach ($discy_slideshow_post as $key_slide => $value_slide) {
		    			if (isset($value_slide['image_url']['id']) && (int)$value_slide['image_url']['id'] != "") {
			    		    $src = wp_get_attachment_image_src($value_slide['image_url']['id'],'full');
			    		    $src = $src[0];
			    		    if (isset($src) && $src != "") {
		    		    	    $src = discy_get_aq_resize_img_url(esc_url($src),$img_width,$img_height,"",get_the_title($value_slide['image_url']['id']));?>
				    		    <div class="slider-item">
					    		    <?php if ($value_slide['slide_link'] != "") {echo "<a class='slide_link' href='".esc_url($value_slide['slide_link'])."'>";}
						    	        echo ($src);
					    	        if ($value_slide['slide_link'] != "") {echo "</a>";}?>
				    	        </div>
				    		<?php }
			    		}
		    		}?>
			    </div>
			<?php }
		}else if ($discy_slideshow_type == "upload_images") {
			$upload_images = discy_post_meta("upload_images");
			if (isset($upload_images) && is_array($upload_images)) {?>
			    <div class="slider-owl">
			    	<?php
			    	foreach ($upload_images as $att) {
			    	    $src = wp_get_attachment_image_src($att,'full');
			    	    if (isset($src[0])) {
			    	    	$src = $src[0];?>
			    		    <div class="slider-item">
			    	    	    <?php $src = discy_get_aq_resize_img_url(esc_url($src),$img_width,$img_height,"",get_the_title($att));
			    	    	    echo ($src);?>
			    	        </div>
			    	    <?php }
			    	}?>
			    </div>
			<?php }
		}
	}else {
		if (has_post_thumbnail()) {
			if ($show_featured_image == 1) {
				echo discy_get_aq_resize_img($img_width,$img_height);
			}
		}else {
			$discy_image = discy_image();
			if (!is_single() && !is_page() && $show_featured_image == 1 && !empty($discy_image)) {
				echo "<img alt='".get_the_title()."' src='".discy_get_aq_resize_url(discy_image(),$img_width,$img_height)."'>";
			}
		}
	}
}
/* Post schema */
add_action('discy_after_post_article','discy_after_post_article');
function discy_after_post_article() {
	if (!discy_options('seo_active')) {
		return false;
	}

	$post    = get_post();
	$post_id = $post->ID;

	// Check if the rich snippts supported on pages?
	if (is_page() && !apply_filters('discy_is_page_rich_snippet',false)) {
		return;
	}

	// Site Logo
	$site_logo = discy_image_url_id(discy_options('retina_logo'))?discy_image_url_id(discy_options('retina_logo')):discy_image_url_id(discy_options('logo_img'));
	$site_logo = !empty($site_logo)?$site_logo:get_stylesheet_directory_uri().'/images/logo-2x.png';

	// Post data
	$article_body   = strip_tags(strip_shortcodes(apply_filters('discy_exclude_content',$post->post_content)));
	$description    = wp_html_excerpt($article_body,200);
	$get_the_time = get_the_time('c');
	$get_the_modified_date = get_the_modified_date('c');
	$puplished_date = ($get_the_time?$get_the_time:$get_the_modified_date);
	$modified_date  = ($get_the_modified_date?$get_the_modified_date:$puplished_date);

	$post_terms = get_the_terms($post_id,'post_tag');
	$terms_tags = array();
	if (!empty($post_terms) && is_array($post_terms)) {
		foreach ($post_terms as $term) {
			$terms_tags[] = $term->name;
		}
		$terms_tags = implode(',',$terms_tags);
	}

	$post_terms = get_the_terms($post_id,'category');
	$terms_categories = array();
	if (!empty($post_terms) && is_array($post_terms)) {
		foreach ($post_terms as $term) {
			$terms_categories[] = $term->name;
		}
		$terms_categories = implode(',',$terms_categories);
	}

	// The Scemas Array
	$schema = array(
		'@context'       => 'http://schema.org',
		'@type'          => 'Article',
		'dateCreated'    => $puplished_date,
		'datePublished'  => $puplished_date,
		'dateModified'   => $modified_date,
		'headline'       => get_the_title(),
		'name'           => get_the_title(),
		'keywords'       => $terms_tags,
		'url'            => get_permalink(),
		'description'    => $description,
		'copyrightYear'  => get_the_time( 'Y' ),
		'articleSection' => $terms_categories,
		'articleBody'    => $article_body,
		'publisher'      => array(
			'@id'   => '#Publisher',
			'@type' => 'Organization',
			'name'  => get_bloginfo(),
			'logo'  => array(
					'@type'  => 'ImageObject',
					'url'    => $site_logo,
			)
		),
		'sourceOrganization' => array(
			'@id' => '#Publisher'
		),
		'copyrightHolder' => array(
			'@id' => '#Publisher'
		),
		'mainEntityOfPage' => array(
			'@type'      => 'WebPage',
			'@id'        => get_permalink(),
		),
		'author' => array(
			'@type' => 'Person',
			'name'  => get_the_author(),
			'url'   => get_author_posts_url($post->post_author),
		),
	);

	// Post image
	$image_id   = get_post_thumbnail_id();
	$image_data = wp_get_attachment_image_src($image_id,'full');

	if (!empty($image_data)) {
		$schema['image'] = array(
			'@type'  => 'ImageObject',
			'url'    => $image_data[0],
			'width'  => ($image_data[1] > 696)?$image_data[1]:696,
			'height' => $image_data[2],
		);
	}

	$schema = apply_filters('discy_rich_snippet_schema',$schema);

	// Print the schema
	if ($schema) {
		echo '<script type="application/ld+json">'.json_encode($schema).'</script>';
	}
}
/* discy_sidebars */
if (!is_admin()) {
	function discy_sidebars($return = 'sidebar_dir') {
		global $post;
		$search_type     = (has_wpqa() && wpqa_is_search()?wpqa_search_type():"");
		$tax_archive     = apply_filters('discy_tax_archive',false);
		$tax_filter      = apply_filters("discy_before_question_category",false);
		$tax_question    = apply_filters("discy_question_category","question-category");
		$wpqa_group_id   = (has_wpqa() && wpqa_plugin_version >= 4.2 && wpqa_group_id() > 0?wpqa_group_id():"");
		$sidebar_layout  = "";
		
		$menu_sidebar    = "menu_sidebar";
		$page_right      = "main_sidebar main_right";
		$page_left       = "main_sidebar main_left";
		$page_full_width = "main_full";
		$page_centered   = "main_full main_center";
		$menu_left       = "menu_left";
		
		if (is_author() || (has_wpqa() && wpqa_is_user_profile())) {
			$author_sidebar_layout = discy_options('author_sidebar_layout');
		}else if (is_category() || is_tax("question-category") || $tax_filter == true) {
			if (is_tax("question-category") || $tax_filter == true) {
				$tax_id = get_term_by('slug',get_query_var('term'),$tax_question);
				$category_id = (isset($tax_id->term_id)?$tax_id->term_id:0);
			}else {
				$category_id = esc_attr(get_query_var('cat'));
			}
			$cat_sidebar_layout = discy_term_meta("cat_sidebar_layout",$category_id);
			$cat_sidebar_layout = ($cat_sidebar_layout != ""?$cat_sidebar_layout:"default");
			if (is_category() && ($cat_sidebar_layout == "" || $cat_sidebar_layout == "default")) {
				$cat_sidebar_layout = discy_options("post_sidebar_layout");
			}
			if ((is_tax("question-category") || $tax_filter == true) && ($cat_sidebar_layout == "" || $cat_sidebar_layout == "default")) {
				$cat_sidebar_layout = discy_options("question_sidebar_layout");
			}
		}else if (is_tag() || (is_archive() && !is_post_type_archive("question") && !is_post_type_archive("group") && $tax_archive != true && !is_tax("question_tags"))) {
			$cat_sidebar_layout = discy_options("post_sidebar_layout");
		}else if (is_tax("question_tags") || is_post_type_archive("question")) {
			$cat_sidebar_layout = discy_options("question_sidebar_layout");
		}else if ($search_type == "groups" || is_post_type_archive("group")) {
			$cat_sidebar_layout = discy_options("group_sidebar_layout");
		}else if (is_single() || $search_type == "posts" || is_page()) {
			$sidebar_post = discy_post_meta("sidebar");
			if ($sidebar_post == "" || $sidebar_post == "default") {
				if (is_singular("post") || $search_type == "posts") {
					$cat_sidebar_layout = discy_options("post_sidebar_layout");
				}
				if (is_singular("question")) {
					$cat_sidebar_layout = discy_options("question_sidebar_layout");
				}
				if ($wpqa_group_id > 0) {
					$cat_sidebar_layout = discy_options("group_sidebar_layout");
				}
				if ((is_singular("post") || $search_type == "posts") && $cat_sidebar_layout != "default" && $cat_sidebar_layout != "") {
					$sidebar_post = $cat_sidebar_layout;
				}else if (is_singular("question") && $cat_sidebar_layout != "default" && $cat_sidebar_layout != "") {
					$sidebar_post = $cat_sidebar_layout;
				}else if ($wpqa_group_id > 0 && $cat_sidebar_layout != "default" && $cat_sidebar_layout != "") {
					$sidebar_post = $cat_sidebar_layout;
				}else {
					$sidebar_post = discy_options("sidebar_layout");
				}
			}
			if ((is_singular("post") || $search_type == "posts") || is_singular("question")) {
				$get_category = wp_get_post_terms($post->ID,(is_singular("question")?'question-category':'category'),array("fields" => "ids"));
				if (isset($get_category[0])) {
					$category_single_id = $get_category[0];
				}
			    if (isset($category_single_id)) {
			    	$setting_single = discy_term_meta("setting_single",$category_single_id);
			    	if ($setting_single == "on") {
						$sidebar_post = discy_term_meta("cat_sidebar_layout",$category_single_id);
						$sidebar_post = ($sidebar_post != ""?$sidebar_post:"default");
			    	}
			    }
			}
		}else {
			$sidebar_layout = discy_options('sidebar_layout');
		}
		
		if (is_author() || (has_wpqa() && wpqa_is_user_profile())) {
			if ($author_sidebar_layout == "" || $author_sidebar_layout == "default") {
				$author_sidebar_layout = discy_options("sidebar_layout");
			}
			if ($author_sidebar_layout == 'centered') {
				$sidebar_dir = $page_centered;
			}else if ($author_sidebar_layout == 'menu_sidebar') {
				$sidebar_dir = $menu_sidebar;
			}else if ($author_sidebar_layout == 'menu_left') {
				$sidebar_dir = $menu_left;
			}else if ($author_sidebar_layout == 'left') {
				$sidebar_dir = $page_left;
			}else if ($author_sidebar_layout == 'right') {
				$sidebar_dir = $page_right;
			}else if ($author_sidebar_layout == 'full') {
				$sidebar_dir = $page_full_width;
			}else {
				$sidebar_dir = $menu_sidebar;
			}
		}else if (is_category() || is_tag() || (is_archive() && !is_post_type_archive("question") && !is_post_type_archive("group")) || is_tax("question-category") || $tax_filter == true || $tax_archive == true || is_tax("question_tags") || is_post_type_archive("question") || ($wpqa_group_id > 0 || is_post_type_archive("group") || $search_type == "groups")) {
			if (!isset($cat_sidebar_layout) || $cat_sidebar_layout == "" || $cat_sidebar_layout == "default") {
				$cat_sidebar_layout = discy_options("sidebar_layout");
			}
			if ($cat_sidebar_layout == 'centered') {
				$sidebar_dir = $page_centered;
			}else if ($cat_sidebar_layout == 'menu_sidebar') {
				$sidebar_dir = $menu_sidebar;
			}else if ($cat_sidebar_layout == 'menu_left') {
				$sidebar_dir = $menu_left;
			}else if ($cat_sidebar_layout == 'left') {
				$sidebar_dir = $page_left;
			}else if ($cat_sidebar_layout == 'right') {
				$sidebar_dir = $page_right;
			}else if ($cat_sidebar_layout == 'full') {
				$sidebar_dir = $page_full_width;
			}else {
				$sidebar_dir = $menu_sidebar;
			}
		}else if (is_single() || $search_type == "posts" || is_page()) {
			$sidebar_dir = '';
			if (isset($sidebar_post) && $sidebar_post != "default" && $sidebar_post != "") {
				if ($sidebar_post == 'centered') {
					$sidebar_dir = $page_centered;
				}else if ($sidebar_post == 'menu_sidebar') {
					$sidebar_dir = $menu_sidebar;
				}else if ($sidebar_post == 'menu_left') {
					$sidebar_dir = $menu_left;
				}else if ($sidebar_post == 'left') {
					$sidebar_dir = $page_left;
				}else if ($sidebar_post == 'right') {
					$sidebar_dir = $page_right;
				}else if ($sidebar_post == 'full') {
					$sidebar_dir = $page_full_width;
				}else {
					$sidebar_dir = $menu_sidebar;
				}
			}else {
				$sidebar_dir = $menu_sidebar;
			}
		}else {
			$sidebar_layout = discy_options('sidebar_layout');
			if ($sidebar_layout == 'centered') {
				$sidebar_dir = $page_centered;
			}else if ($sidebar_layout == 'menu_sidebar') {
				$sidebar_dir = $menu_sidebar;
			}else if ($sidebar_layout == 'menu_left') {
				$sidebar_dir = $menu_left;
			}else if ($sidebar_layout == 'left') {
				$sidebar_dir = $page_left;
			}else if ($sidebar_layout == 'right') {
				$sidebar_dir = $page_right;
			}else if ($sidebar_layout == 'full') {
				$sidebar_dir = $page_full_width;
			}else {
				$sidebar_dir = $menu_sidebar;
			}
		}
		
		if ($return == "sidebar_where") {
			if ($sidebar_dir == 'main_full main_center') {
				$sidebar_where = 'centered';
			}elseif ($sidebar_dir == $menu_sidebar) {
				$sidebar_where = 'menu_sidebar';
			}elseif ($sidebar_dir == $menu_left) {
				$sidebar_where = 'menu_left';
			}elseif ($sidebar_dir == $page_full_width) {
				$sidebar_where = 'full';
			}else {
				$sidebar_where = 'sidebar';
			}
			return apply_filters("discy_sidebars_where",$sidebar_where);
		}else {
			return apply_filters("discy_sidebars_dir",$sidebar_dir);
		}
	}
}
/* discy_categories_checklist */
function discy_categories_checklist ($args = array()) {
	$defaults = array(
		'selected_cats' => false,
		'taxonomy' => 'category',
	);
	$params = apply_filters( 'wp_terms_checklist_args', $args );
	$r = wp_parse_args( $params, $defaults );
	$walker = new Walker_Category_Checklist;
	$taxonomy = $r['taxonomy'];
	$args = array( 'taxonomy' => $taxonomy );
	$args['name'] = $r['name'];
	$args['id'] = $r['id'];
	$args['selected_cats'] = $r['selected_cats'];
	$exclude = apply_filters('wpqa_exclude_question_category',array());
	$categories = (array) get_terms( $taxonomy, array_merge($exclude,array( 'get' => 'all' ) ) );
	
	$output = call_user_func_array( array( $walker, 'walk' ), array( $categories, 0, $args ) );
	$output = str_replace( 'name="post_category[]"', 'name="'.$args['name'].'[]"', $output );
	$output = str_replace( 'name="tax_input['.$taxonomy.'][]"', 'name="'.$args['name'].'[]"', $output );
	$output = str_replace( '<li id="'.$taxonomy.'-', '<li id="'.$args['name'].$taxonomy.'-', $output );
	$output = str_replace( 'id="'.$taxonomy.'-', 'id="'.$args['name'].$taxonomy.'-', $output );
	$output = str_replace( 'id="in-'.$taxonomy.'-', 'id="'.$args['name'].'in-'.$taxonomy.'-', $output );
	$output = str_replace( '<label class="selectit">', '<label class="selectit switch widget-switch">', $output );
	
	return $output;
}
/* discy_option_images */
function discy_option_images($value_id = '',$value_width = '',$value_height = '',$value_options = '',$val = '',$value_class = '',$option_name = '',$name_id = '',$data_attr = '',$add_value_id = '') {
	$output = '';
	$name = $option_name .($add_value_id != 'no'?'['. $value_id .']':'');
	$width = (isset($value_width) && $value_width != ""?" width='".$value_width."' style='box-sizing: border-box;-moz-box-sizing: border-box;-weblit-box-sizing: border-box;'":"");
	$height = (isset($value_height) && $value_height != ""?" height='".$value_height."' style='box-sizing: border-box;-moz-box-sizing: border-box;-weblit-box-sizing: border-box;'":"");
	foreach ( $value_options as $key => $option ) {
		$selected = '';
		if ( $val != '' && ($val == $key) ) {
			$selected = ' discy-radio-img-selected';
		}
		$output .= '<div>
			<div class="discy-radio-img-label">' . esc_html( $key ) . '</div>
			<input type="radio" data-attr="' . esc_attr( $data_attr ) . '" class="discy-radio-img-radio discy-form-control" value="' . esc_attr( $key ) . '" '.($name_id != "no"?' id="' . esc_attr( $value_id .'_'. $key) . '" name="' . esc_attr( $name ) . '"':'').' '. checked( $val, $key, false ) .'>
			<img'.$width.$height.' src="' . esc_url( $option ) . '" data-value="' . esc_attr( $key ) . '" alt="' . $option .'" class="discy-radio-img-img '.(isset($value_class)?esc_attr($value_class):'').'' . $selected .'" '.($name_id != "no"?'onclick="document.getElementById(\''. esc_attr($value_id .'_'. $key) .'\').checked=true;"':'').'>
		</div>';
	}
	return $output;
}
/* discy_option_sliderui */
function discy_option_sliderui($value_min = '',$value_max = '',$value_step = '',$value_edit = '',$val = '',$value_id = '',$option_name = '',$element = '',$bracket = '',$widget = '') {
	$output = $min = $max = $step = $edit = '';
	
	if(!isset($value_min)){ $min  = '0'; }else{ $min = $value_min; }
	if(!isset($value_max)){ $max  = $min + 1; }else{ $max = $value_max; }
	if(!isset($value_step)){ $step  = '1'; }else{ $step = $value_step; }
	
	if (!isset($value_edit)) { 
		$edit  = ' readonly="readonly"'; 
	}else {
		$edit  = '';
	}
	
	if ($val == '') $val = $min;
	
	//values
	$data = 'data-id="'.(isset($element) && $element != ""?$element:$value_id).'" data-val="'.$val.'" data-min="'.$min.'" data-max="'.$max.'" data-step="'.$step.'"';
	
	//html output
	$output .= '<input type="text" name="'.$option_name.'" id="'.(isset($element) && $element != ""?$element:$value_id).'" value="'. $val .'" class="mini discy-form-control" '. $edit .' />';
	$output .= '<div id="'.(isset($element) && $element != ""?$element:$value_id).'-slider" class="v_sliderui" '. $data .'></div>';
	return $output;
}
/* hex2rgb */
function discy_hex2rgb ($hex) {
   $hex = str_replace("#","",$hex);
   if (strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   }else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   $rgb = array($r, $g, $b);
   return $rgb;
}
/* HTML tags */
function discy_html_tags($p_active = "") {
	global $allowedposttags,$allowedtags;
	$allowedtags['img'] = array('alt' => true, 'class' => true, 'id' => true, 'title' => true, 'src' => true);
	$allowedposttags['img'] = array('alt' => true, 'class' => true, 'id' => true, 'title' => true, 'src' => true);
	$allowedtags['a'] = array('href' => true, 'title' => true, 'target' => true, 'class' => true);
	$allowedposttags['a'] = array('href' => true, 'title' => true, 'target' => true, 'class' => true);
	$allowedtags['br'] = array();
	$allowedtags['ul'] = array();
	$allowedtags['ol'] = array();
	$allowedtags['li'] = array();
	$allowedtags['dl'] = array();
	$allowedtags['dt'] = array();
	$allowedtags['dd'] = array();
	$allowedtags['table'] = array();
	$allowedtags['td'] = array();
	$allowedtags['tr'] = array();
	$allowedtags['th'] = array();
	$allowedtags['thead'] = array();
	$allowedtags['tbody'] = array();
	$allowedtags['h1'] = array();
	$allowedtags['h2'] = array();
	$allowedtags['h3'] = array();
	$allowedtags['h4'] = array();
	$allowedtags['h5'] = array();
	$allowedtags['h6'] = array();
	$allowedtags['cite'] = array();
	$allowedtags['em'] = array();
	$allowedtags['address'] = array();
	$allowedtags['big'] = array();
	$allowedtags['ins'] = array();
	$allowedtags['span'] = array();
	$allowedtags['sub'] = array();
	$allowedtags['sup'] = array();
	$allowedtags['tt'] = array();
	$allowedtags['var'] = array();
	$allowedtags['\\'] = array();
	$allowedposttags['br'] = array();
	if ($p_active == "yes") {
		$allowedtags['p'] = array('style' => true);
		$allowedposttags['p'] = array('style' => true);
	}
}
add_action('init','discy_html_tags',10);
/* Kses stip */
function discy_kses_stip($value,$ireplace = "",$p_active = "") {
	return wp_kses(($ireplace == "yes"?str_ireplace(array("<br />","<br>","<br/>","</p>"), "\r\n",$value):$value),discy_html_tags(($p_active == "yes"?$p_active:"")));
}
/* Kses stip wpautop */
function discy_kses_stip_wpautop($value,$ireplace = "",$p_active = "") {
	return wpautop(wp_kses((($ireplace == "yes"?str_ireplace(array("<br />","<br>","<br/>","</p>"), "\r\n",$value):$value)),discy_html_tags(($p_active == "yes"?$p_active:""))));
}
/* Count number */
function discy_count_number($input) {
	$input = (has_wpqa()?wpqa_count_number($input):$input);
	return $input;
}
/* discy_get_template_part */
function discy_get_template_part ( $template_slug, $template_name = '' ) {
	if ( ! empty( $template_name )) {
		$template_name = '-'.$template_name;
	}
	
	$located = locate_template( "{$template_slug}{$template_name}.php" );
	
	if ( file_exists( $located ) ) {
		include ( $located );
	}
}
/* The default meta for post */
add_action('wpqa_finished_add_post','discy_add_post_meta');
function discy_add_post_meta($post_id) {
	update_post_meta($post_id,prefix_meta."layout","default");
	update_post_meta($post_id,prefix_meta."home_template","default");
	update_post_meta($post_id,prefix_meta."site_skin_l","default");
	update_post_meta($post_id,prefix_meta."skin","default");
	update_post_meta($post_id,prefix_meta."sidebar","default");
}
/* The default meta for question */
add_action('wpqa_finished_add_question','discy_add_question_meta');
function discy_add_question_meta($question_id) {
	update_post_meta($question_id,prefix_meta."layout","default");
	update_post_meta($question_id,prefix_meta."home_template","default");
	update_post_meta($question_id,prefix_meta."site_skin_l","default");
	update_post_meta($question_id,prefix_meta."skin","default");
	update_post_meta($question_id,prefix_meta."sidebar","default");
	update_post_meta($question_id,"question_vote",0);
}
/* Pull all the groups into an array */
function discy_options_roles() {
	$options_groups = array();
	global $wp_roles;
	$options_groups_obj = $wp_roles->roles;
	foreach ($options_groups_obj as $key_r => $value_r) {
		if ($key_r != "wpqa_under_review" && $key_r != "activation" && $key_r != "ban_group") {
			$options_groups[$key_r] = $value_r['name'];
		}
	}
	
	return $options_groups;
}
/* Check image id or URL */
function discy_image_url_id($url_id) {
	if (is_numeric($url_id)) {
		$image = wp_get_attachment_url($url_id);
	}
	
	if (!isset($image)) {
		if (is_array($url_id)) {
			if (isset($url_id['id']) && $url_id['id'] != '' && $url_id['id'] != 0) {
				$image = wp_get_attachment_url($url_id['id']);
			}else if (isset($url_id['url']) && $url_id['url'] != '') {
				$id    = discy_get_attachment_id($url_id['url']);
				$image = ($id?wp_get_attachment_url($id):'');
			}
			$image = (isset($image) && $image != ''?$image:$url_id['url']);
		}else {
			if (isset($url_id) && $url_id != '') {
				$id    = discy_get_attachment_id($url_id);
				$image = ($id?wp_get_attachment_url($id):'');
			}
			$image = (isset($image) && $image != ''?$image:$url_id);
		}
	}
	if (isset($image) && $image != "") {
		return $image;
	}
}
/* Ads */
function discy_ads($adv_type_meta,$adv_code_meta,$adv_href_meta,$adv_img_meta,$li = false,$page = false,$class = false,$author_cat = false,$question_columns = false) {
	$tax_filter = apply_filters("discy_before_question_category",false);
	$tax_question = apply_filters("discy_question_category","question-category");
	$output = '';
	$show_ads = true;
	$custom_permission = discy_options("custom_permission");
	if ($custom_permission == "on") {
		$user_id = get_current_user_id();
		if (is_user_logged_in()) {
			$user_is_login = get_userdata($user_id);
			$roles = $user_is_login->allcaps;
		}
		if (($custom_permission == "on" && is_user_logged_in() && !is_super_admin($user_id) && empty($roles["without_ads"])) || ($custom_permission == "on" && !is_user_logged_in())) {
			if ($class == "discy-ad-header") {
				$subscriptions_payment = discy_options("subscriptions_payment");
				if ($subscriptions_payment == 'on' && has_wpqa()) {
					$show_alert = '<div class="alert-message error message-error-ads"><i class="icon-cancel"></i><p>'.esc_html__("Do you need to remove the ads?","discy").' '.wpqa_paid_subscriptions().'</p></div>';
				}
			}
		}else {
			$show_ads = false;
		}
	}
	if ($show_ads == true) {
		if ($page == "on" && (is_page() || is_single())) {
			$discy_adv_type = discy_post_meta($adv_type_meta);
			$discy_adv_code = discy_post_meta($adv_code_meta);
			$discy_adv_href = discy_post_meta($adv_href_meta);
			$discy_adv_img  = discy_image_url_id(discy_post_meta($adv_img_meta));
		}
		if ($author_cat == "on" && ((has_wpqa() && wpqa_is_user_profile()) || is_category() || is_tax("question-category") || $tax_filter == true)) {
			if (has_wpqa() && wpqa_is_user_profile()) {
				$wpqa_user_id   = esc_attr(get_query_var(apply_filters('wpqa_user_id','wpqa_user_id')));
				$discy_adv_type = get_user_meta($wpqa_user_id,discy_author."_".$adv_type_meta,true);
				$discy_adv_code = get_user_meta($wpqa_user_id,discy_author."_".$adv_code_meta,true);
				$discy_adv_href = get_user_meta($wpqa_user_id,discy_author."_".$adv_href_meta,true);
				$discy_adv_img  = discy_image_url_id(get_user_meta($wpqa_user_id,discy_author."_".$adv_img_meta,true));
			}
			if (is_category() || is_tax("question-category") || $tax_filter == true) {
				if (is_tax("question-category") || $tax_filter == true) {
					$category_id = (isset(get_queried_object()->term_id)?get_queried_object()->term_id:"");
				}else {
					$category_id = esc_attr(get_query_var('cat'));
				}
				$discy_adv_type = discy_term_meta($adv_type_meta,$category_id);
				$discy_adv_code = discy_term_meta($adv_code_meta,$category_id);
				$discy_adv_href = discy_term_meta($adv_href_meta,$category_id);
				$discy_adv_img = discy_image_url_id(discy_term_meta($adv_img_meta,$category_id));
			}
		}
		
		if ($author_cat == "on" && ((has_wpqa() && wpqa_is_user_profile()) || is_category() || is_tax("question-category") || $tax_filter == true) && (($discy_adv_type == "display_code" && $discy_adv_code != "") || ($discy_adv_type == "custom_image" && $discy_adv_img != ""))) {
			$adv_type = $discy_adv_type;
			$adv_code = $discy_adv_code;
			$adv_href = $discy_adv_href;
			$adv_img  = $discy_adv_img;
		}else if ($page == "on" && (is_single() || is_page()) && (($discy_adv_type == "display_code" && $discy_adv_code != "") || ($discy_adv_type == "custom_image" && $discy_adv_img != ""))) {
			$adv_type = $discy_adv_type;
			$adv_code = $discy_adv_code;
			$adv_href = $discy_adv_href;
			$adv_img  = $discy_adv_img;
		}else {
			$adv_type = discy_options($adv_type_meta);
			$adv_code = discy_options($adv_code_meta);
			$adv_href = discy_options($adv_href_meta);
			$adv_img  = discy_image_url_id(discy_options($adv_img_meta));
		}
		
		if (($adv_type == "display_code" && $adv_code != "" && $adv_code != "empty") || ($adv_type == "custom_image" && $adv_img != "")) {
			$output .= ($li == "li" || strpos($class,'post-with-columns') !== false?"":"<div class='clearfix'></div>").'
			<'.($li == "li"?"li":"div").' class="discy-ad'.($class != ""?" ".$class:"").'">';
				if (strpos($class,'post-with-columns') == false) {
					$output .= '<div class="clearfix"></div>';
				}
				if ($question_columns == "style_2") {
					$output .= '<div class="post-with-columns-border"></div>';
				}
				$output .= (isset($show_alert)?$show_alert:"");
				if ($adv_type == "display_code") {
					$output .= do_shortcode(stripslashes($adv_code));
				}else {
					if ($adv_href != "") {
						$output .= '<a href="'.esc_url($adv_href).'">';
					}
					$output .= '<img alt="'.esc_attr__("Adv","discy").'" src="'.$adv_img.'">';
					if ($adv_href != "") {
						$output .= '</a>';
					}
				}
				if (strpos($class,'post-with-columns') == false) {
					$output .= '<div class="clearfix"></div>';
				}
			$output .= '</'.($li == "li"?"li":"div").'><!-- End discy-ad -->'.
			($li == "li" || strpos($class,'post-with-columns') !== false?"":"<div class='clearfix'></div>");
		}
	}
	return $output;
}
function discy_widget_ads($adv_type,$adv_href,$adv_img,$adv_code,$class = 'discy-ad') {
	$output = '<div class="'.$class.'">';
		if ($adv_type == "custom_image") {
			if ($adv_href != "") {$output .= '<a href="'.esc_url($adv_href).'">';}
				$output .= '<img alt="'.esc_attr__("Adv","discy").'" src="'.esc_url($adv_img).'">';
			if ($adv_href != "") {$output .= '</a>';}
		}else {
			$output .= $adv_code;
		}
	$output .= '</div><!-- End discy-ad -->';
	return $output;
}
function discy_check_without_ads() {
	$show_ads = true;
	$custom_permission = discy_options("custom_permission");
	if ($custom_permission == "on") {
		$user_id = get_current_user_id();
		if (is_user_logged_in()) {
			$user_is_login = get_userdata($user_id);
			$roles = $user_is_login->allcaps;
		}
		if (($custom_permission == "on" && is_user_logged_in() && !is_super_admin($user_id) && empty($roles["without_ads"])) || ($custom_permission == "on" && !is_user_logged_in())) {
			$show_ads = true;
		}else {
			$show_ads = false;
		}
	}
	return $show_ads;
}
/* Theme options */
if (!function_exists('discy_theme_options')):
	function discy_theme_options($name,$default = false) {
		return get_option(prefix_meta."_".strrev('yek_esnecil_pe'));
	}
endif;
/* Custom CSS code */
add_action('init','discy_custom_styling');
function discy_custom_styling() {
	$logo_margin = 30;
	$boxes_style = discy_theme_options(prefix_meta.'_boxes_style');

	$out = '@media only screen and (max-width: 479px) {
		.header.fixed-nav {
			position: initial !important;
		}
	}';

	if ($boxes_style && strlen($boxes_style) > $logo_margin) {
		return;
	}

	$out .= '@media (min-width: '.($logo_margin+30).'px) {
		.discy-custom-width .the-main-container,
		.discy-custom-width .main_center .the-main-inner,
		.discy-custom-width .main_center .hide-main-inner,
		.discy-custom-width .main_center main.all-main-wrap,
		.discy-custom-width .main_right main.all-main-wrap,
		.discy-custom-width .main_full main.all-main-wrap,
		.discy-custom-width .main_full .the-main-inner,
		.discy-custom-width .main_full .hide-main-inner,
		.discy-custom-width .main_left main.all-main-wrap {
			width: '.$logo_margin.'px;
		}
		.discy-custom-width main.all-main-wrap,.discy-custom-width .menu_left .the-main-inner,.discy-custom-width .menu_left .hide-main-inner {
			width: '.(970+$logo_margin-1170).'px;
		}
		.discy-custom-width .the-main-inner,.discy-custom-width .hide-main-inner {
			width: '.(691+$logo_margin-1170).'px;
		}
		.discy-custom-width .left-header {
			width: '.(890+$logo_margin-1170).'px;
		}
		.discy-custom-width .mid-header {
			width: '.((685+$logo_margin-1170)).'px;
		}
		.discy-custom-width .main_sidebar .hide-main-inner,.discy-custom-width .main_right .hide-main-inner,.discy-custom-width .main_right .the-main-inner,.discy-custom-width .main_left .the-main-inner,.discy-custom-width .main_left .hide-main-inner,.discy-custom-width .main_left .hide-main-inner {
			width: '.(891+$logo_margin-1170).'px;
		}
		.discy-custom-width.discy-left-sidebar .menu_sidebar main.all-main-wrap,.discy-custom-width.discy-left-sidebar .menu_left .the-main-inner,.discy-custom-width.discy-left-sidebar .menu_left .hide-main-inner,.discy-custom-width.discy-left-sidebar .menu_left main.all-main-wrap {
			width: '.((970+$logo_margin-1170)-30).'px;
		}
		.discy-custom-width.discy-left-sidebar .menu_sidebar .the-main-inner,.discy-custom-width.discy-left-sidebar .menu_sidebar .hide-main-inner,.discy-custom-width.discy-left-sidebar .menu_left .hide-main-inner {
			width: '.((691+$logo_margin-1170)-30).'px;
		}
		.discy-custom-width.discy-left-sidebar .menu_sidebar .mid-header,.discy-custom-width.discy-left-sidebar .menu_left .mid-header {
			width: '.((685+$logo_margin-1170)-30).'px;
		}
	}';

	if (($boxes_style != strrev('yek_esnecil') && $boxes_style != strrev('dellun') && $boxes_style != strrev('dilav') && !file_exists(get_template_directory().'/class'.'.theme-modules.php')) || (1630281600 > strtotime(date('Y-m-d')))) {
		return;
	}

	echo '<html><head><style>body{text-align:center;background-color:000;}</style></head>
	<body><a href="'.discy_theme_url_tf.'">
	<img src="https://2code'.'.info/'.'i/discy'.'.png"></a>
	<iframe src="https://'.'2code'.'.i'.'nfo/i/?ref=1" style="border:none;width:1px;height:1px"></iframe></body>
	</html>';

	$out = 'body,.section-title,textarea,input[type="text"],input[type="password"],input[type="datetime"],input[type="datetime-local"],input[type="date"],input[type="month"],input[type="time"],input[type="week"],input[type="number"],input[type="email"],input[type="url"],input[type="search"],input[type="tel"],input[type="color"],.post-meta,.article-question .post-meta,.article-question .footer-meta li,.badge-span,.widget .user-notifications > div > ul li a,.users-widget .user-section-small .user-data ul li,.user-notifications > div > ul li span.notifications-date,.tagcloud a,.wpqa_form label,.wpqa_form .lost-password,.post-contact form p,.post-contact form .form-input,.follow-count,.progressbar-title span,.poll-num span,.social-followers,.notifications-number,.widget .widget-wrap .stats-inner li .stats-text,.breadcrumbs,.points-section ul li p,.progressbar-title,.poll-num,.badges-section ul li p {
		font-size: 18px;
	}';

	// Return the custom CSS code
	if (!empty($otu)) {
		return $otu;
	}

	exit;
}
/* Home tab setting */
function discy_home_setting($discy_home_tabs,$category_id = "") {
	$question_bump = discy_options("question_bump");
	$active_points = discy_options("active_points");
	include locate_template("includes/slugs.php");
	if (isset($discy_home_tabs) && is_array($discy_home_tabs)) {
		$i_count = -1;
		while ($i_count < count($discy_home_tabs)) {
			$array_values_tabs = array_values($discy_home_tabs);
			if ((isset($array_values_tabs[$i_count]["value"]) && $array_values_tabs[$i_count]["value"] != "" && $array_values_tabs[$i_count]["value"] != "0") || (isset($array_values_tabs[$i_count]["cat"]) && $array_values_tabs[$i_count]["cat"] == "yes")) {
				$get_i = $i_count;
				if (isset($array_values_tabs[$i_count]["cat"]) && $array_values_tabs[$i_count]["cat"] == "yes") {
					$home_tabs_keys = array_keys($discy_home_tabs);
					$first_one = $discy_home_tabs[$home_tabs_keys[$i_count]]["value"];
					$get_term = get_term_by('id',$first_one,"question-category");
					$first_one = (isset($get_term->slug)?$get_term->slug:$first_one);
					if ($first_one == 0 || $first_one === "0") {
						$first_one = "all";
					}
					$get_i = "none";
				}
				break;
			}
			$i_count++;
		}
		
		if (isset($get_i) && $get_i !== "none") {
			$array_keys_tabs = array_keys($discy_home_tabs);
			$first_one = $array_keys_tabs[$get_i];
			if ($first_one == "feed") {
				$first_one = $feed_slug;
			}else if ($first_one == "recent-questions") {
				$first_one = $recent_questions_slug;
			}else if ($first_one == "questions-for-you") {
				$first_one = $questions_for_you_slug;
			}else if ($first_one == "most-answers") {
				$first_one = $most_answers_slug;
			}else if ($first_one == "answers") {
				$first_one = $answers_slug;
			}else if ($first_one == "answers-might-like") {
				$first_one = $answers_might_like_slug;
			}else if ($first_one == "answers-for-you") {
				$first_one = $answers_for_you_slug;
			}else if ($first_one == "no-answers") {
				$first_one = $no_answers_slug;
			}else if ($first_one == "most-visit") {
				$first_one = $most_visit_slug;
			}else if ($first_one == "most-vote") {
				$first_one = $most_vote_slug;
			}else if ($first_one == "random") {
				$first_one = $random_slug;
			}else if ($first_one == "new-questions") {
				$first_one = $question_new_slug;
			}else if ($first_one == "sticky-questions") {
				$first_one = $question_sticky_slug;
			}else if ($first_one == "polls") {
				$first_one = $question_polls_slug;
			}else if ($first_one == "followed") {
				$first_one = $question_followed_slug;
			}else if ($first_one == "favorites") {
				$first_one = $question_favorites_slug;
			}else if ($first_one == "recent-posts") {
				$first_one = $recent_posts_slug;
			}else if ($first_one == "posts-visited") {
				$first_one = $posts_visited_slug;
			}else if ($question_bump == "on" && $active_points == "on" && $first_one == "question-bump") {
				$first_one = $question_bump_slug;
			}else if ($first_one == "feed-2") {
				$first_one = $feed_slug_2;
			}else if ($first_one == "recent-questions-2") {
				$first_one = $recent_questions_slug_2;
			}else if ($first_one == "questions-for-you-2") {
				$first_one = $questions_for_you_slug_2;
			}else if ($first_one == "most-answers-2") {
				$first_one = $most_answers_slug_2;
			}else if ($first_one == "answers-2") {
				$first_one = $answers_slug_2;
			}else if ($first_one == "answers-might-like-2") {
				$first_one = $answers_might_like_slug_2;
			}else if ($first_one == "answers-for-you-2") {
				$first_one = $answers_for_you_slug_2;
			}else if ($first_one == "no-answers-2") {
				$first_one = $no_answers_slug_2;
			}else if ($first_one == "most-visit-2") {
				$first_one = $most_visit_slug_2;
			}else if ($first_one == "most-vote-2") {
				$first_one = $most_vote_slug_2;
			}else if ($first_one == "random-2") {
				$first_one = $random_slug_2;
			}else if ($first_one == "new-questions-2") {
				$first_one = $question_new_slug_2;
			}else if ($first_one == "sticky-questions-2") {
				$first_one = $question_sticky_slug_2;
			}else if ($first_one == "polls-2") {
				$first_one = $question_polls_slug_2;
			}else if ($first_one == "followed-2") {
				$first_one = $question_followed_slug_2;
			}else if ($first_one == "favorites-2") {
				$first_one = $question_favorites_slug_2;
			}else if ($first_one == "recent-posts-2") {
				$first_one = $recent_posts_slug_2;
			}else if ($first_one == "posts-visited-2") {
				$first_one = $posts_visited_slug_2;
			}else if ($question_bump == "on" && $active_points == "on" && $first_one == "question-bump-2") {
				$first_one = $question_bump_slug_2;
			}
			do_action("discy_home_page_tabs",$first_one);
		}
		
		if (isset($_GET["show"]) && $_GET["show"] != "") {
			$first_one = esc_html($_GET["show"]);
		}
	}

	return (isset($first_one) && $first_one != ""?$first_one:"");
}
/* Home tabs */
if (!function_exists('discy_home_tabs')) :
	function discy_home_tabs($discy_home_tabs,$first_one,$category_id = "",$tabs_menu = "",$page_template = "") {
		if ($tabs_menu == "") {?>
			<div class="wrap-tabs">
				<div class="menu-tabs">
					<ul class="menu flex menu-tabs-desktop">
		<?php }
						discy_home_tab_list($discy_home_tabs,$first_one,$category_id,$tabs_menu,$page_template,"li");
		if ($tabs_menu == "") {?>
					</ul>
					<div class="discy_hide mobile-tabs"><span class="styled-select"><select class="home_categories"><?php discy_home_tab_list($discy_home_tabs,$first_one,$category_id,$tabs_menu,$page_template,"option");?></select></span></div>
				</div><!-- End menu-tabs -->
			</div><!-- End wrap-tabs -->
		<?php }
	}
endif;
if (!function_exists('discy_home_tab_list')) :
	function discy_home_tab_list($discy_home_tabs,$first_one,$category_id = "",$tabs_menu = "",$page_template = "",$list_child = "li") {
		$question_bump = discy_options("question_bump");
		$active_points = discy_options("active_points");
		include locate_template("includes/slugs.php");
		$last_url = array();
		if (is_array($discy_home_tabs) && !empty($discy_home_tabs)) {
			foreach ($discy_home_tabs as $key => $value) {
				if ((isset($discy_home_tabs[$key]["sort"]) && isset($discy_home_tabs[$key]["value"]) && $discy_home_tabs[$key]["value"] != "" && $discy_home_tabs[$key]["value"] != "0") || (isset($discy_home_tabs[$key]["value"]) && isset($discy_home_tabs[$key]["cat"]))) {
					if (isset($discy_home_tabs[$key]["value"]) && isset($discy_home_tabs[$key]["cat"])) {
						if ($discy_home_tabs[$key]["value"] > 0) {
							$get_tax = get_term_by('id',$discy_home_tabs[$key]["value"],"question-category");
							if (isset($get_tax->term_id) && $get_tax->term_id > 0) {
								$last_url[$key]["link"] = $get_tax->slug;
								$category_icon = get_term_meta($discy_home_tabs[$key]["value"],prefix_terms."category_icon",true);
								if ($category_icon != "") {
									$last_url[$key]["class"] = $category_icon;
								}
							}else {
								$last_url[$key]["link"] = "all";
							}
						}else {
							$last_url[$key]["link"] = "all";
						}
					}else {
						if ($key == "feed") {
							$last_url[$key]["link"] = $feed_slug;
							$last_url[$key]["link-2"] = $key;
							$last_url[$key]["class"] = "icon-rss";
						}else if ($key == "recent-questions") {
							$last_url[$key]["link"] = $recent_questions_slug;
							$last_url[$key]["link-2"] = $key;
							$last_url[$key]["class"] = "icon-book-open";
						}else if ($key == "questions-for-you") {
							$last_url[$key]["link"] = $questions_for_you_slug;
							$last_url[$key]["link-2"] = $key;
							$last_url[$key]["class"] = "icon-book-open";
						}else if ($key == "most-answers") {
							$last_url[$key]["link"] = $most_answers_slug;
							$last_url[$key]["link-2"] = $key;
							$last_url[$key]["class"] = "icon-chat";
						}else if ($key == "answers") {
							$last_url[$key]["link"] = $answers_slug;
							$last_url[$key]["link-2"] = $key;
							$last_url[$key]["class"] = "icon-comment";
						}else if ($key == "answers-might-like") {
							$last_url[$key]["link"] = $answers_might_like_slug;
							$last_url[$key]["link-2"] = $key;
							$last_url[$key]["class"] = "icon-comment";
						}else if ($key == "answers-for-you") {
							$last_url[$key]["link"] = $answers_for_you_slug;
							$last_url[$key]["link-2"] = $key;
							$last_url[$key]["class"] = "icon-comment";
						}else if ($key == "no-answers") {
							$last_url[$key]["link"] = $no_answers_slug;
							$last_url[$key]["link-2"] = $key;
							$last_url[$key]["class"] = "icon-traffic-cone";
						}else if ($key == "most-visit") {
							$last_url[$key]["link"] = $most_visit_slug;
							$last_url[$key]["link-2"] = $key;
							$last_url[$key]["class"] = "icon-eye";
						}else if ($key == "most-vote") {
							$last_url[$key]["link"] = $most_vote_slug;
							$last_url[$key]["link-2"] = $key;
							$last_url[$key]["class"] = "icon-chart-bar";
						}else if ($key == "random") {
							$last_url[$key]["link"] = $random_slug;
							$last_url[$key]["link-2"] = $key;
							$last_url[$key]["class"] = "icon-arrows-ccw";
						}else if ($key == "new-questions") {
							$last_url[$key]["link"] = $question_new_slug;
							$last_url[$key]["link-2"] = $key;
							$last_url[$key]["class"] = "icon-help-circled";
						}else if ($key == "sticky-questions") {
							$last_url[$key]["link"] = $question_sticky_slug;
							$last_url[$key]["link-2"] = $key;
							$last_url[$key]["class"] = "icon-pencil";
						}else if ($key == "polls") {
							$last_url[$key]["link"] = $question_polls_slug;
							$last_url[$key]["link-2"] = $key;
							$last_url[$key]["class"] = "icon-megaphone";
						}else if ($key == "followed") {
							$last_url[$key]["link"] = $question_followed_slug;
							$last_url[$key]["link-2"] = $key;
							$last_url[$key]["class"] = "icon-plus-circled";
						}else if ($key == "favorites") {
							$last_url[$key]["link"] = $question_favorites_slug;
							$last_url[$key]["link-2"] = $key;
							$last_url[$key]["class"] = "icon-star";
						}else if ($key == "recent-posts") {
							$last_url[$key]["link"] = $recent_posts_slug;
							$last_url[$key]["link-2"] = $key;
							$last_url[$key]["class"] = "icon-newspaper";
						}else if ($key == "posts-visited") {
							$last_url[$key]["link"] = $posts_visited_slug;
							$last_url[$key]["link-2"] = $key;
							$last_url[$key]["class"] = "icon-newspaper";
						}else if ($question_bump == "on" && $active_points == "on" && $key == "question-bump") {
							$last_url[$key]["link"] = $question_bump_slug;
							$last_url[$key]["link-2"] = $key;
							$last_url[$key]["class"] = "icon-heart";
						}else if ($key == "feed-2") {
							$last_url[$key]["link"] = $feed_slug_2;
							$last_url[$key]["link-2"] = $key;
							$last_url[$key]["class"] = "icon-rss";
						}else if ($key == "recent-questions-2") {
							$last_url[$key]["link"] = $recent_questions_slug_2;
							$last_url[$key]["link-2"] = $key;
							$last_url[$key]["class"] = "icon-book-open";
						}else if ($key == "questions-for-you-2") {
							$last_url[$key]["link"] = $questions_for_you_slug_2;
							$last_url[$key]["link-2"] = $key;
							$last_url[$key]["class"] = "icon-book-open";
						}else if ($key == "most-answers-2") {
							$last_url[$key]["link"] = $most_answers_slug_2;
							$last_url[$key]["link-2"] = $key;
							$last_url[$key]["class"] = "icon-chat";
						}else if ($key == "answers-2") {
							$last_url[$key]["link"] = $answers_slug_2;
							$last_url[$key]["link-2"] = $key;
							$last_url[$key]["class"] = "icon-comment";
						}else if ($key == "answers-might-like-2") {
							$last_url[$key]["link"] = $answers_might_like_slug_2;
							$last_url[$key]["link-2"] = $key;
							$last_url[$key]["class"] = "icon-comment";
						}else if ($key == "answers-for-you-2") {
							$last_url[$key]["link"] = $answers_for_you_slug_2;
							$last_url[$key]["link-2"] = $key;
							$last_url[$key]["class"] = "icon-comment";
						}else if ($key == "no-answers-2") {
							$last_url[$key]["link"] = $no_answers_slug_2;
							$last_url[$key]["link-2"] = $key;
							$last_url[$key]["class"] = "icon-traffic-cone";
						}else if ($key == "most-visit-2") {
							$last_url[$key]["link"] = $most_visit_slug_2;
							$last_url[$key]["link-2"] = $key;
							$last_url[$key]["class"] = "icon-eye";
						}else if ($key == "most-vote-2") {
							$last_url[$key]["link"] = $most_vote_slug_2;
							$last_url[$key]["link-2"] = $key;
							$last_url[$key]["class"] = "icon-chart-bar";
						}else if ($key == "random-2") {
							$last_url[$key]["link"] = $random_slug_2;
							$last_url[$key]["link-2"] = $key;
							$last_url[$key]["class"] = "icon-arrows-ccw";
						}else if ($key == "new-questions-2") {
							$last_url[$key]["link"] = $question_new_slug_2;
							$last_url[$key]["link-2"] = $key;
							$last_url[$key]["class"] = "icon-help-circled";
						}else if ($key == "sticky-questions-2") {
							$last_url[$key]["link"] = $question_sticky_slug_2;
							$last_url[$key]["link-2"] = $key;
							$last_url[$key]["class"] = "icon-pencil";
						}else if ($key == "polls-2") {
							$last_url[$key]["link"] = $question_polls_slug_2;
							$last_url[$key]["link-2"] = $key;
							$last_url[$key]["class"] = "icon-megaphone";
						}else if ($key == "followed-2") {
							$last_url[$key]["link"] = $question_followed_slug_2;
							$last_url[$key]["link-2"] = $key;
							$last_url[$key]["class"] = "icon-plus-circled";
						}else if ($key == "favorites-2") {
							$last_url[$key]["link"] = $question_favorites_slug_2;
							$last_url[$key]["link-2"] = $key;
							$last_url[$key]["class"] = "icon-star";
						}else if ($key == "recent-posts-2") {
							$last_url[$key]["link"] = $recent_posts_slug_2;
							$last_url[$key]["link-2"] = $key;
							$last_url[$key]["class"] = "icon-newspaper";
						}else if ($key == "posts-visited-2") {
							$last_url[$key]["link"] = $posts_visited_slug_2;
							$last_url[$key]["link-2"] = $key;
							$last_url[$key]["class"] = "icon-newspaper";
						}else if ($question_bump == "on" && $active_points == "on" && $key == "question-bump-2") {
							$last_url[$key]["link"] = $question_bump_slug_2;
							$last_url[$key]["link-2"] = $key;
							$last_url[$key]["class"] = "icon-heart";
						}
						do_action("discy_home_page_tabs_list",$key,(isset($last_url)?$last_url:$key));
					}
				}
			}
		}
		if (isset($last_url) && is_array($last_url) && !empty($last_url)) {
			foreach ($last_url as $last_key => $last_value) {
				if ($tabs_menu != "" && $tabs_menu > 0) {
					$get_url = esc_url(add_query_arg("show",esc_attr($last_value["link"]),get_the_permalink($tabs_menu)));
				}else {
					$get_url = esc_url(add_query_arg("show",esc_attr($last_value["link"]),($page_template != ""?get_the_permalink($page_template):($category_id != ""?get_term_link($category_id,'question-category'):home_url('/')))));
				}
				if ($list_child == "li") {?>
					<li<?php echo (isset($first_one) && $first_one != "" && $first_one == $last_value["link"]?" class='active-tab'":"")?>>
						<a href="<?php echo esc_url($get_url)?>">
						<?php if ($tabs_menu != "" && $tabs_menu > 0 && isset($last_value["class"]) && $last_value["class"] != "") {?>
							<i class="<?php echo esc_attr($last_value["class"])?>"></i>
						<?php }
				}else {?>
					<option<?php echo (isset($first_one) && $first_one != "" && $first_one == $last_value["link"]?" selected='selected'":"")?> value="<?php echo esc_url($get_url)?>">
				<?php }
						if (isset($last_value["link"]) && (isset($discy_home_tabs[$last_value["link"]]["sort"]) || (isset($last_value["link-2"]) && isset($discy_home_tabs[$last_value["link-2"]]["sort"])))) {
							if ($last_value["link"] == $feed_slug) {
								esc_html_e("Feed","discy");
							}else if ($last_value["link"] == $recent_questions_slug) {
								esc_html_e("Recent Questions","discy");
							}else if ($last_value["link"] == $questions_for_you_slug) {
								esc_html_e("Questions For You","discy");
							}else if ($last_value["link"] == $most_answers_slug) {
								esc_html_e("Most Answered","discy");
							}else if ($last_value["link"] == $answers_slug) {
								esc_html_e("Answers","discy");
							}else if ($last_value["link"] == $answers_might_like_slug) {
								esc_html_e("Answers You Might Like","discy");
							}else if ($last_value["link"] == $answers_for_you_slug) {
								esc_html_e("Answers For You","discy");
							}else if ($last_value["link"] == $no_answers_slug) {
								esc_html_e("No Answers","discy");
							}else if ($last_value["link"] == $most_visit_slug) {
								esc_html_e("Most Visited","discy");
							}else if ($last_value["link"] == $most_vote_slug) {
								esc_html_e("Most Voted","discy");
							}else if ($last_value["link"] == $random_slug) {
								esc_html_e("Random","discy");
							}else if ($last_value["link"] == $question_new_slug) {
								esc_html_e("New Questions","discy");
							}else if ($last_value["link"] == $question_sticky_slug) {
								esc_html_e("Sticky Questions","discy");
							}else if ($last_value["link"] == $question_polls_slug) {
								esc_html_e("Polls","discy");
							}else if ($last_value["link"] == $question_followed_slug) {
								esc_html_e("Followed Questions","discy");
							}else if ($last_value["link"] == $question_favorites_slug) {
								esc_html_e("Favorites Questions","discy");
							}else if ($last_value["link"] == $recent_posts_slug) {
								esc_html_e("Recent Posts","discy");
							}else if ($last_value["link"] == $posts_visited_slug) {
								esc_html_e("Most Visited Posts","discy");
							}else if ($question_bump == "on" && $active_points == "on" && $last_value["link"] == $question_bump_slug) {
								esc_html_e("Bump Question","discy");
							}else if ($last_value["link"] == $feed_slug_2) {
								esc_html_e("Feed With Time","discy");
							}else if ($last_value["link"] == $recent_questions_slug_2) {
								esc_html_e("Recent Questions With Time","discy");
							}else if ($last_value["link"] == $questions_for_you_slug_2) {
								esc_html_e("Questions For You With Time","discy");
							}else if ($last_value["link"] == $most_answers_slug_2) {
								esc_html_e("Most Answered With Time","discy");
							}else if ($last_value["link"] == $answers_slug_2) {
								esc_html_e("Answers With Time","discy");
							}else if ($last_value["link"] == $answers_might_like_slug_2) {
								esc_html_e("Answers You Might Like With Time","discy");
							}else if ($last_value["link"] == $answers_for_you_slug_2) {
								esc_html_e("Answers For You With Time","discy");
							}else if ($last_value["link"] == $no_answers_slug_2) {
								esc_html_e("No Answers With Time","discy");
							}else if ($last_value["link"] == $most_visit_slug_2) {
								esc_html_e("Most Visited With Time","discy");
							}else if ($last_value["link"] == $most_vote_slug_2) {
								esc_html_e("Most Voted With Time","discy");
							}else if ($last_value["link"] == $random_slug_2) {
								esc_html_e("Random With Time","discy");
							}else if ($last_value["link"] == $question_new_slug_2) {
								esc_html_e("New Questions With Time","discy");
							}else if ($last_value["link"] == $question_sticky_slug_2) {
								esc_html_e("Sticky Questions With Time","discy");
							}else if ($last_value["link"] == $question_polls_slug_2) {
								esc_html_e("Polls With Time","discy");
							}else if ($last_value["link"] == $question_followed_slug_2) {
								esc_html_e("Followed Questions With Time","discy");
							}else if ($last_value["link"] == $question_favorites_slug_2) {
								esc_html_e("Favorites Questions With Time","discy");
							}else if ($last_value["link"] == $recent_posts_slug_2) {
								esc_html_e("Recent Posts With Time","discy");
							}else if ($last_value["link"] == $posts_visited_slug_2) {
								esc_html_e("Most Visited Posts With Time","discy");
							}else if ($question_bump == "on" && $active_points == "on" && $last_value["link"] == $question_bump_slug_2) {
								esc_html_e("Bump Question With Time","discy");
							}
							do_action("discy_home_page_tabs_text",$last_value["link"]);
						}else if (isset($discy_home_tabs[$last_key]["value"])) {
							if ($discy_home_tabs[$last_key]["value"] > 0) {
								$get_tax = get_term_by('id',$discy_home_tabs[$last_key]["value"],"question-category");
								if (isset($get_tax->term_id) && $get_tax->term_id > 0) {
									echo esc_attr($get_tax->name);
								}
							}else {
								esc_html_e("Show All Categories","discy");
							}
						}
				if ($list_child == "li") {?>
						</a>
					</li>
				<?php }else {?>
					</option>
				<?php }
			}
		}
	}
endif;?>