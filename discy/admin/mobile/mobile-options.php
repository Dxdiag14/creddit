<?php /* Mobile options */
function discy_other_plugin() {
	if (is_admin()) {
		if (!function_exists('mobile_options')) {
			add_filter("discy_options_after_general_setting","discy_mobile_setting_options");
		}
	}
}
add_action('init','discy_other_plugin');
function discy_mobile_setting_options($options) {
	$directory_uri = get_template_directory_uri();
	$imagepath_theme =  $directory_uri.'/images/';

	$more_info = '<a href="https://2code.info/mobile-apps/" target="_blank">'.esc_html__('For more information and buying the mobile APP','discy').'</a>';

	// Pull all the pages into an array
	$not_template_pages = array();
	$args = array('post_type' => 'page','nopaging' => true,"meta_query" => array('relation' => 'OR',array("key" => "_wp_page_template","compare" => "NOT EXISTS"),array("key" => "_wp_page_template","compare" => "=","value" => ''),array("key" => "_wp_page_template","compare" => "=","value" => 'default')));
	$not_template_pages[''] = 'Select a page:';
	$the_query = new WP_Query($args);
	if ( $the_query->have_posts() ) {
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			$page_post = $the_query->post;
			$not_template_pages[$page_post->ID] = $page_post->post_title;
		}
	}
	wp_reset_postdata();

	// Pull all the pages into an array
	$options_pages = array();
	$options_pages_obj = get_pages('sort_column=post_parent,menu_order');
	$options_pages[''] = 'Select a page:';
	foreach ($options_pages_obj as $page) {
		$options_pages[$page->ID] = $page->post_title;
	}

	// Pull all the roles into an array
	global $wp_roles;
	$new_roles = array();
	foreach ($wp_roles->roles as $key => $value) {
		$new_roles[$key] = $value['name'];
	}

	$array_std = array(
		"category"        => "category",
		"date"            => "date",
		"author"          => "author",
		"author_badge"    => "author_badge",
		"question_vote"   => "question_vote",
		"tags"            => "tags",
		"answer_button"   => "answer_button",
		"answers_count"   => "answers_count",
		"views_count"     => "views_count",
		"followers_count" => "followers_count",
		"favourite"       => "favourite",
	);

	$array_options = array(
		"category"        => esc_html__('Category','discy'),
		"date"            => esc_html__('Date','discy'),
		"author"          => esc_html__('Author','discy'),
		"author_badge"    => esc_html__('Author badge','discy'),
		"question_vote"   => esc_html__('Question vote','discy'),
		"tags"            => esc_html__('Tags','discy'),
		"answer_button"   => esc_html__('Answer button','discy'),
		"answers_count"   => esc_html__('Answers count','discy'),
		"views_count"     => esc_html__('Views count','discy'),
		"followers_count" => esc_html__('Followers count','discy'),
		"favourite"       => esc_html__('Favourite','discy'),
	);

	$array_single_std = array(
		"category"        => "category",
		"date"            => "date",
		"author"          => "author",
		"author_badge"    => "author_badge",
		"question_vote"   => "question_vote",
		"tags"            => "tags",
		"answer_button"   => "answer_button",
		"answers_count"   => "answers_count",
		"views_count"     => "views_count",
		"followers_count" => "followers_count",
		"favourite"       => "favourite",
		"share"           => "share",
	);

	$array_single_options = array(
		"category"        => esc_html__('Category','discy'),
		"date"            => esc_html__('Date','discy'),
		"author"          => esc_html__('Author','discy'),
		"author_badge"    => esc_html__('Author badge','discy'),
		"question_vote"   => esc_html__('Question vote','discy'),
		"tags"            => esc_html__('Tags','discy'),
		"answer_button"   => esc_html__('Answer button','discy'),
		"answers_count"   => esc_html__('Answers count','discy'),
		"views_count"     => esc_html__('Views count','discy'),
		"followers_count" => esc_html__('Followers count','discy'),
		"favourite"       => esc_html__('Favourite','discy'),
		"share"           => esc_html__('Share','discy'),
	);

	$options[] = array(
		'name'    => esc_html__('Mobile APP','discy'),
		'id'      => 'mobile_applications',
		'type'    => 'heading',
		'icon'    => 'phone',
		'new'     => true,
		'std'     => 'general_mobile',
		'options' => array(
			"general_mobile"    => esc_html__('General settings','discy'),
			"guide_pages"       => esc_html__('Guide pages','discy'),
			"setting_page"      => esc_html__('Setting page','discy'),
			"header_mobile"     => esc_html__('Mobile header','discy'),
			"side_navbar"       => esc_html__('Side navbar','discy'),
			"bottom_bar"        => esc_html__('Bottom bar','discy'),
			"mobile_question"   => esc_html__('Ask questions','discy'),
			"ads_mobile"        => esc_html__('Advertising','discy'),
			"app_notifications" => esc_html__('Notifications','discy'),
			"captcha_mobile"    => esc_html__('Captcha settings','discy'),
			"home_mobile"       => esc_html__('Home settings','discy'),
			"categories_mobile" => esc_html__('Categories settings','discy'),
			"search_mobile"     => esc_html__('Search settings','discy'),
			"favourites_mobile" => esc_html__('Favourites settings','discy'),
			"single_mobile"     => esc_html__('Single question settings','discy'),
			"answers_mobile"    => esc_html__('Answers settings','discy'),
			"styling_mobile"    => esc_html__('Mobile styling','discy'),
			"lang_mobile"       => esc_html__('Language settings','discy')
		)
	);
	
	$options[] = array(
		'name' => esc_html__('General settings','discy'),
		'id'   => 'general_mobile',
		'type' => 'heading-2'
	);

	$options[] = array(
		'name' => $more_info,
		'type' => 'info'
	);

	$options[] = array(
		'name'  => esc_html__('All the options on this page, if you do not buy the app, will not work.','discy'),
		'type'  => 'info',
		'alert' => 'alert-message-warning'
	);
	/*
	$options[] = array(
		'name'    => esc_html__('APP skin','discy'),
		'id'      => 'app_skin',
		'std'     => 'light',
		'type'    => 'radio',
		'options' => array("light" => esc_html__("Light","discy"),"dark" => esc_html__("Dark","discy"))
	);
	*/

	$options[] = array(
		'name' => esc_html__('Activate a custom URL for your site different than the main URL','discy'),
		'desc' => esc_html__('Something like with www or without it, or with https or with http','discy'),
		'id'   => 'activate_custom_baseurl',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name'      => esc_html__('Type your custom URL for your site different than the main URL','discy'),
		'id'        => 'custom_baseurl',
		'std'       => esc_url(home_url('/')),
		'condition' => 'activate_custom_baseurl:not(0)',
		'type'      => 'text'
	);

	$options[] = array(
		'name' => esc_html__('Enable or disable to show the parent categories with child category','discy'),
		'desc' => esc_html__('Show the parent categories with child category, in following categories page, ask question form, and categories page','discy'),
		'id'   => 'mobile_parent_categories',
		'std'  => 'on',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => esc_html__('Write the number of categories which show in the categories page or add 0 to show all of them','discy'),
		'id'   => 'mobile_categories_page',
		'std'  => 0,
		'type' => 'text'
	);

	$options[] = array(
		'name' => esc_html__('Write the number of categories which show in the following steps in the register and edit profile pages or add 0 to show all of them','discy'),
		'id'   => 'mobile_following_categories',
		'std'  => 0,
		'type' => 'text'
	);

	$options[] = array(
		'name'    => esc_html__("Choose the roles you need to show for the users in the following steps in the register and edit profile pages.","discy"),
		'id'      => 'mobile_following_users',
		'type'    => 'multicheck',
		'options' => $new_roles,
		'std'     => array('administrator' => 'administrator','editor' => 'editor','contributor' => 'contributor','subscriber' => 'subscriber','author' => 'author'),
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);

	$options[] = array(
		'name' => esc_html__('Guide pages','discy'),
		'id'   => 'guide_pages',
		'type' => 'heading-2'
	);

	$options[] = array(
		'name' => $more_info,
		'type' => 'info'
	);
	
	$options[] = array(
		'name' => esc_html__('Enable or disable the guide pages','discy'),
		'id'   => 'onboardmodels_mobile',
		'std'  => 'on',
		'type' => 'checkbox'
	);

	$options[] = array(
		'div'       => 'div',
		'condition' => 'onboardmodels_mobile:not(0)',
		'type'      => 'heading-2'
	);

	$options[] = array(
		'name' => esc_html__('Upload the image for first guide page','discy'),
		'id'   => 'onboardmodels_img_1_mobile',
		'std'  => $imagepath_theme."1.png",
		'type' => 'upload',
	);

	$options[] = array(
		'name' => esc_html__('Add the title for first guide page','discy'),
		'id'   => 'onboardmodels_title_1_mobile',
		'std'  => "Welcome",
		'type' => 'text',
	);

	$options[] = array(
		'name' => esc_html__('Add the sub title for first guide page','discy'),
		'id'   => 'onboardmodels_subtitle_1_mobile',
		'std'  => "Lorem Ipsum is simply dummy text of the printing and typesetting industry",
		'type' => 'text',
	);

	$options[] = array(
		'name' => esc_html__('Upload the image for second guide page','discy'),
		'id'   => 'onboardmodels_img_2_mobile',
		'std'  => $imagepath_theme."2.png",
		'type' => 'upload',
	);

	$options[] = array(
		'name' => esc_html__('Add the title for second guide page','discy'),
		'id'   => 'onboardmodels_title_2_mobile',
		'std'  => "You are here",
		'type' => 'text',
	);

	$options[] = array(
		'name' => esc_html__('Add the sub title for second guide page','discy'),
		'id'   => 'onboardmodels_subtitle_2_mobile',
		'std'  => "Lorem Ipsum is simply dummy text of the printing and typesetting industry",
		'type' => 'text',
	);

	$options[] = array(
		'name' => esc_html__('Upload the image for third guide page','discy'),
		'id'   => 'onboardmodels_img_3_mobile',
		'std'  => $imagepath_theme."3.png",
		'type' => 'upload',
	);

	$options[] = array(
		'name' => esc_html__('Add the title for third guide page','discy'),
		'id'   => 'onboardmodels_title_3_mobile',
		'std'  => "Continue to Discy",
		'type' => 'text',
	);

	$options[] = array(
		'name' => esc_html__('Add the sub title for third guide page','discy'),
		'id'   => 'onboardmodels_subtitle_3_mobile',
		'std'  => "Lorem Ipsum is simply dummy text of the printing and typesetting industry",
		'type' => 'text',
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);

	$options[] = array(
		'name' => esc_html__('Setting page','discy'),
		'id'   => 'setting_page',
		'type' => 'heading-2'
	);

	$options[] = array(
		'name' => $more_info,
		'type' => 'info'
	);

	$options[] = array(
		'name' => esc_html__('Enable or disable the text size','discy'),
		'id'   => 'text_size_app',
		'std'  => 'on',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => esc_html__('Enable or disable the rate app','discy'),
		'id'   => 'rate_app',
		'std'  => 'on',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => esc_html__('Enable or disable the edit profile page','discy'),
		'id'   => 'edit_profile_app',
		'std'  => 'on',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => esc_html__('Enable or disable the about us page','discy'),
		'id'   => 'about_us_app',
		'std'  => 'on',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name'      => esc_html__('Choose the about us page','discy'),
		'id'        => 'about_us_page_app',
		'type'      => 'select',
		'condition' => 'about_us_app:is(on)',
		'options'   => $not_template_pages
	);

	$options[] = array(
		'name' => esc_html__('Enable or disable the privacy policy page','discy'),
		'id'   => 'privacy_policy_app',
		'std'  => 'on',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name'  => esc_html__('You must choose 4 items only to show in the bottom bar.','discy'),
		'class' => 'home_page_display',
		'type'  => 'info'
	);

	$options[] = array(
		'name'  => esc_html__('You must choose the privacy page.','discy'),
		'class' => 'home_page_display',
		'type'  => 'info'
	);

	$options[] = array(
		'name'      => esc_html__('Choose the privacy policy page','discy'),
		'id'        => 'privacy_policy_page_app',
		'type'      => 'select',
		'condition' => 'privacy_policy_app:is(on)',
		'options'   => $not_template_pages
	);

	$options[] = array(
		'name'  => esc_html__('You must choose the terms page.','discy'),
		'class' => 'home_page_display',
		'type'  => 'info'
	);

	$options[] = array(
		'name'    => esc_html__('Choose the terms and conditions page','discy'),
		'id'      => 'terms_page_app',
		'type'    => 'select',
		'options' => $not_template_pages
	);

	$options[] = array(
		'name' => esc_html__('Enable or disable the FAQs page','discy'),
		'id'   => 'faqs_app',
		'std'  => 'on',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name'      => esc_html__('Choose the FAQs page','discy'),
		'id'        => 'faqs_page_app',
		'type'      => 'select',
		'condition' => 'faqs_app:is(on)',
		'options'   => $options_pages
	);

	$options[] = array(
		'name' => esc_html__('Enable or disable the contact us page','discy'),
		'id'   => 'contact_us_app',
		'std'  => 'on',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => esc_html__('Enable or disable the share app','discy'),
		'id'   => 'share_app',
		'std'  => 'on',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => esc_html__('Share title','discy'),
		'id'   => 'share_title',
		'std'  => 'Discy',
		'type' => 'text',
	);

	$options[] = array(
		'name' => esc_html__('Share image','discy'),
		'id'   => 'share_image',
		'std'  => $directory_uri."/screenshot.png",
		'type' => 'upload',
	);

	$options[] = array(
		'name' => esc_html__('Share android URL','discy'),
		'id'   => 'share_android',
		'std'  => esc_url(home_url('/')),
		'type' => 'text',
	);

	$options[] = array(
		'name' => esc_html__('Share IOS URL','discy'),
		'id'   => 'share_ios',
		'std'  => esc_url(home_url('/')),
		'type' => 'text',
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);

	$options[] = array(
		'name' => esc_html__('Mobile header','discy'),
		'id'   => 'header_mobile',
		'type' => 'heading-2'
	);

	$options[] = array(
		'name' => $more_info,
		'type' => 'info'
	);

	$options[] = array(
		'name'    => esc_html__('Logo position','discy'),
		'id'      => 'mobile_logo_position',
		'std'     => 'start',
		'type'    => 'radio',
		'options' => array("start" => esc_html__("Left","discy"),"center" => esc_html__("Center","discy"))
	);

	$options[] = array(
		'name' => esc_html__('Upload the logo','discy'),
		'id'   => 'mobile_logo',
		'std'  => $imagepath_theme."logo-light-2x.png",
		'type' => 'upload',
	);
	/*
	$options[] = array(
		'name' => esc_html__('Upload the dark logo','discy'),
		'id'   => 'mobile_logo_dark',
		'std'  => $imagepath_theme."logo-colored.png",
		'type' => 'upload',
	);
	*/
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);

	$options[] = array(
		'name' => esc_html__('Side navbar','discy'),
		'id'   => 'side_navbar',
		'type' => 'heading-2'
	);

	$options[] = array(
		'name' => $more_info,
		'type' => 'info'
	);

	$options[] = array(
		'name' => esc_html__('Enable or disable the side navbar','discy'),
		'id'   => 'side_navbar_activate',
		'std'  => 'on',
		'type' => 'checkbox'
	);

	$options[] = array(
		'div'       => 'div',
		'condition' => 'side_navbar_activate:not(0)',
		'type'      => 'heading-2'
	);

	$options[] = array(
		'name'     => esc_html__('Choose the categories show','discy'),
		'id'       => 'side_navbar_categories',
		'type'     => 'custom_addition',
		'taxonomy' => 'question-category',
		'addto'    => 'mobile_side_navbar',
		'toadd'    => 'yes',
	);

	$options[] = array(
		'name'     => esc_html__('Choose the pages show','discy'),
		'id'       => 'side_navbar_pages',
		'type'     => 'custom_addition',
		'addition' => 'page',
		'addto'    => 'mobile_side_navbar',
		'toadd'    => 'yes',
		'button'   => esc_html__("Add page","discy"),
		'options'  => $not_template_pages
	);
	
	$side_navbar = array(
		"home"       => array("sort" => esc_html__('Home','discy'),"value" => "home"),
		"ask"        => array("sort" => esc_html__('Ask Question','discy'),"value" => "ask"),
		"categories" => array("sort" => esc_html__('Categories','discy'),"value" => "categories"),
		"favorite"   => array("sort" => esc_html__('Favorite','discy'),"value" => "favorite"),
		"settings"   => array("sort" => esc_html__('Settings','discy'),"value" => "settings"),
		"points"     => array("sort" => esc_html__('Badges and points','discy'),"value" => "points"),
	);
	
	$options[] = array(
		'name'    => esc_html__('Select the side navbar pages','discy'),
		'id'      => 'mobile_side_navbar',
		'type'    => 'multicheck',
		'sort'    => 'yes',
		'std'     => $side_navbar,
		'options' => $side_navbar
	);

	$options[] = array(
		'name'      => esc_html__('Name of the ask question tab in the side navbar','discy'),
		'id'        => 'side_navbar_ask',
		'condition' => 'mobile_side_navbar:has(ask)',
		'type'      => 'text',
		'std'       => 'Ask Question'
	);

	$options[] = array(
		'name'      => esc_html__('Name of the home tab in the side navbar','discy'),
		'id'        => 'side_navbar_home',
		'condition' => 'mobile_side_navbar:has(home)',
		'type'      => 'text',
		'std'       => 'Home'
	);

	$options[] = array(
		'name'      => esc_html__('Name of the categories tab in the side navbar','discy'),
		'id'        => 'side_navbar_categories',
		'condition' => 'mobile_side_navbar:has(categories)',
		'type'      => 'text',
		'std'       => 'Categories'
	);

	$options[] = array(
		'name'      => esc_html__('Name of the favorite tab in the side navbar','discy'),
		'id'        => 'side_navbar_favorite',
		'condition' => 'mobile_side_navbar:has(favorite)',
		'type'      => 'text',
		'std'       => 'Favorite'
	);

	$options[] = array(
		'name'      => esc_html__('Name of the settings tab in the side navbar','discy'),
		'id'        => 'side_navbar_settings',
		'condition' => 'mobile_side_navbar:has(settings)',
		'type'      => 'text',
		'std'       => 'Settings'
	);

	$options[] = array(
		'name'      => esc_html__('Name of the badges and points tab in the side navbar','discy'),
		'id'        => 'side_navbar_points',
		'condition' => 'mobile_side_navbar:has(points)',
		'type'      => 'text',
		'std'       => 'Badges and points'
	);

	$options[] = array(
		'name' => esc_html__('Name of the all categories tab in the side navbar','discy'),
		'id'   => 'side_navbar_all_categories',
		'type' => 'text',
		'std'  => 'All categories'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);

	$options[] = array(
		'name' => esc_html__('Bottom bar','discy'),
		'id'   => 'bottom_bar',
		'type' => 'heading-2'
	);

	$options[] = array(
		'name' => $more_info,
		'type' => 'info'
	);
	
	$options[] = array(
		'name' => esc_html__('Enable or disable the bottom bar','discy'),
		'id'   => 'bottom_bar_activate',
		'std'  => 'on',
		'type' => 'checkbox'
	);

	$options[] = array(
		'div'       => 'div',
		'condition' => 'bottom_bar_activate:not(0)',
		'type'      => 'heading-2'
	);

	$options[] = array(
		'name'  => esc_html__('You must choose 4 items only to show in the bottom bar.','discy'),
		'class' => 'home_page_display',
		'type'  => 'info'
	);

	$options[] = array(
		'name'    => esc_html__('Select the bottom bar pages','discy'),
		'id'      => 'mobile_bottom_bar',
		'type'    => 'multicheck',
		'std'     => array(
			"home"       => "home",
			"ask"        => "ask",
			"categories" => "categories",
			"favorite"   => "favorite",
			"settings"   => "settings",
			"points"     => "points",
		),
		'options' => array(
			"home"       => esc_html__('Home','discy'),
			"ask"        => esc_html__('Ask Question','discy'),
			"categories" => esc_html__('Categories','discy'),
			"favorite"   => esc_html__('Favorite','discy'),
			"settings"   => esc_html__('Settings','discy'),
			"points"     => esc_html__('Badges and points','discy'),
		)
	);

	$options[] = array(
		'name'      => esc_html__('Name of the home tab in the bottom bar','discy'),
		'id'        => 'bottom_bar_home',
		'condition' => 'mobile_bottom_bar:has(home)',
		'type'      => 'text',
		'std'       => 'Home'
	);

	$options[] = array(
		'name'      => esc_html__('Name of the ask question tab in the bottom bar','discy'),
		'id'        => 'bottom_bar_ask',
		'condition' => 'mobile_bottom_bar:has(ask)',
		'type'      => 'text',
		'std'       => 'Ask Question'
	);

	$options[] = array(
		'name'      => esc_html__('Name of the categories tab in the bottom bar','discy'),
		'id'        => 'bottom_bar_categories',
		'condition' => 'mobile_bottom_bar:has(categories)',
		'type'      => 'text',
		'std'       => 'Categories'
	);

	$options[] = array(
		'name'      => esc_html__('Name of the favorite tab in the bottom bar','discy'),
		'id'        => 'bottom_bar_favorite',
		'condition' => 'mobile_bottom_bar:has(favorite)',
		'type'      => 'text',
		'std'       => 'Favorite'
	);

	$options[] = array(
		'name'      => esc_html__('Name of the settings tab in the bottom bar','discy'),
		'id'        => 'bottom_bar_settings',
		'condition' => 'mobile_bottom_bar:has(settings)',
		'type'      => 'text',
		'std'       => 'Settings'
	);

	$options[] = array(
		'name'      => esc_html__('Name of the badges and points tab in the bottom bar','discy'),
		'id'        => 'bottom_bar_points',
		'condition' => 'mobile_bottom_bar:has(points)',
		'type'      => 'text',
		'std'       => 'Badges and points'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);

	$options[] = array(
		'name' => esc_html__('Ask questions','discy'),
		'id'   => 'mobile_question',
		'type' => 'heading-2'
	);

	$options[] = array(
		'name' => esc_html__('Enable or disable the ask a question','discy'),
		'id'   => 'addaction_mobile',
		'std'  => 'on',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => esc_html__('Write the number of categories which show in the ask question form or add 0 to show all of them','discy'),
		'id'   => 'mobile_question_categories',
		'std'  => 0,
		'type' => 'text'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);

	$options[] = array(
		'name' => esc_html__('Advertising','discy'),
		'id'   => 'ads_mobile',
		'type' => 'heading-2'
	);

	$options[] = array(
		'name' => $more_info,
		'type' => 'info'
	);

	$options[] = array(
		'name' => esc_html__('Acivate the advertising','discy'),
		'id'   => 'mobile_adv',
		'type' => 'checkbox'
	);

	$options[] = array(
		'div'       => 'div',
		'condition' => 'mobile_adv:not(0)',
		'type'      => 'heading-2'
	);

	$options[] = array(
		'name' => esc_html__('Add the adMob Android id','discy'),
		'id'   => 'ad_mob_android',
		'type' => 'text',
	);

	$options[] = array(
		'name' => esc_html__('Add the adMob IOS id','discy'),
		'id'   => 'ad_mob_ios',
		'type' => 'text',
	);

	$options[] = array(
		'name' => esc_html__('Activate the mobile banner adv','discy'),
		'id'   => 'mobile_banner_adv',
		'type' => 'checkbox'
	);

	$options[] = array(
		'div'       => 'div',
		'condition' => 'mobile_banner_adv:not(0)',
		'type'      => 'heading-2'
	);

	$options[] = array(
		'name' => esc_html__('Add the adMob Android id for the banner','discy'),
		'id'   => 'ad_banner_android',
		'type' => 'text',
	);

	$options[] = array(
		'name' => esc_html__('Add the adMob IOS id for the banner','discy'),
		'id'   => 'ad_banner_ios',
		'type' => 'text',
	);

	$options[] = array(
		'name' => esc_html__('Activate the banner adv in the top','discy'),
		'id'   => 'banner_top',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => esc_html__('Activate the banner adv in the bottom','discy'),
		'id'   => 'banner_bottom',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => esc_html__('Activate the banner adv after the post','discy'),
		'id'   => 'banner_after_post',
		'std'  => 'on',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);

	$options[] = array(
		'name' => esc_html__('Captcha settings','discy'),
		'id'   => 'captcha_mobile',
		'type' => 'heading-2'
	);

	$options[] = array(
		'name' => $more_info,
		'type' => 'info'
	);

	$options[] = array(
		'name' => esc_html__('Enable or disable reCaptcha','discy'),
		'id'   => 'activate_captcha_mobile',
		'type' => 'checkbox'
	);

	$options[] = array(
		'div'       => 'div',
		'condition' => 'activate_captcha_mobile:not(0)',
		'type'      => 'heading-2'
	);

	$options[] = array(
		'name'    => esc_html__('Select where do you need to activate the captcha','discy'),
		'id'      => 'captcha_positions',
		'type'    => 'multicheck',
		'std'     => array(
			"login"    => "login",
			"register" => "register",
		),
		'options' => array(
			"login"    => esc_html__('Sign in','discy'),
			"register" => esc_html__('Sign up','discy'),
			"answer"   => esc_html__('Add a new answer','discy'),
			"question" => esc_html__('Ask a new question','discy'),
		)
	);

	$options[] = array(
		'name'  => sprintf(esc_html__('You can get the reCaptcha v2 site and secret keys from: %s','discy'),'<a href="https://www.google.com/recaptcha/admin/" target="_blank">'.esc_html__('here','discy').'</a> > <a href="https://ahmed.d.pr/DUAKq5" target="_blank">'.esc_html__('like that','discy').'</a>'),
		'class' => 'home_page_display',
		'type'  => 'info'
	);

	$options[] = array(
		'name'  => sprintf(esc_html__('Add this in the domain option: %s','discy'),'recaptcha-flutter-plugin.firebaseapp.com'),
		'class' => 'home_page_display',
		'type'  => 'info'
	);
	
	$options[] = array(
		'name' => esc_html__('Site key reCaptcha','discy'),
		'id'   => 'site_key_recaptcha_mobile',
		'type' => 'text',
	);
	
	$options[] = array(
		'name' => esc_html__('Secret key reCaptcha','discy'),
		'id'   => 'secret_key_recaptcha_mobile',
		'type' => 'text',
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);

	$options[] = array(
		'name' => esc_html__('Notifications','discy'),
		'id'   => 'app_notifications',
		'type' => 'heading-2'
	);

	$options[] = array(
		'name' => esc_html__('Enable or disable push notifications','discy'),
		'id'   => 'push_notifications',
		'type' => 'checkbox'
	);

	$options[] = array(
		'div'       => 'div',
		'condition' => 'push_notifications:not(0)',
		'type'      => 'heading-2'
	);

	$options[] = array(
		'name'  => '<a href="https://2code.info/docs/mobile/google-firebase/" target="_blank">'.esc_html__('You can get the key from here.','discy').'</a>',
		'class' => 'home_page_display',
		'type'  => 'info'
	);

	$options[] = array(
		'name' => esc_html__('Add the app key','discy'),
		'id'   => 'app_key',
		'type' => 'text',
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);

	$options[] = array(
		'name' => esc_html__('Home settings','discy'),
		'id'   => 'home_mobile',
		'type' => 'heading-2'
	);

	$options[] = array(
		'name' => $more_info,
		'type' => 'info'
	);

	$options[] = array(
		'name' => esc_html__('Items per page in the homepage','discy'),
		'id'   => 'count_posts_home',
		'std'  => "10",
		'type' => 'text',
	);

	$options[] = array(
		'name'    => esc_html__('Select the home options','discy'),
		'id'      => 'mobile_setting_home',
		'type'    => 'multicheck',
		'std'     => $array_std,
		'options' => $array_options
	);

	$options[] = array(
		'name'      => esc_html__('Activate the adMob in the first tab in the top','discy'),
		'id'        => 'ads_mobile_top',
		'condition' => 'mobile_adv:not(0)',
		'type'      => 'checkbox'
	);

	$options[] = array(
		'name'      => esc_html__('Activate the adMob in the first tab in the bottom','discy'),
		'id'        => 'ads_mobile_bottom',
		'condition' => 'mobile_adv:not(0)',
		'type'      => 'checkbox'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);

	$options[] = array(
		'name' => esc_html__('Categories settings','discy'),
		'id'   => 'categories_mobile',
		'type' => 'heading-2'
	);

	$options[] = array(
		'name' => $more_info,
		'type' => 'info'
	);

	$options[] = array(
		'name' => esc_html__('Items per page in the categories','discy'),
		'id'   => 'count_posts_categories',
		'std'  => "6",
		'type' => 'text',
	);

	$options[] = array(
		'name'    => esc_html__('Select the categories options','discy'),
		'id'      => 'mobile_setting_categories',
		'type'    => 'multicheck',
		'std'     => $array_std,
		'options' => $array_options
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);

	$options[] = array(
		'name' => esc_html__('Search settings','discy'),
		'id'   => 'search_mobile',
		'type' => 'heading-2'
	);

	$options[] = array(
		'name' => $more_info,
		'type' => 'info'
	);

	$options[] = array(
		'name' => esc_html__('Items per page in the search','discy'),
		'id'   => 'count_posts_search',
		'std'  => "3",
		'type' => 'text',
	);

	$options[] = array(
		'name'    => esc_html__('Select the search options','discy'),
		'id'      => 'mobile_setting_search',
		'type'    => 'multicheck',
		'std'     => $array_std,
		'options' => $array_options
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);

	$options[] = array(
		'name' => esc_html__('Favourites settings','discy'),
		'id'   => 'favourites_mobile',
		'type' => 'heading-2'
	);

	$options[] = array(
		'name' => $more_info,
		'type' => 'info'
	);

	$options[] = array(
		'name' => esc_html__('Items per page in the favourite page','discy'),
		'id'   => 'count_posts_favourites',
		'std'  => "10",
		'type' => 'text',
	);

	$options[] = array(
		'name'    => esc_html__('Select the setting of the favourite page','discy'),
		'id'      => 'mobile_setting_favourites',
		'type'    => 'multicheck',
		'std'     => $array_std,
		'options' => $array_options
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);

	$options[] = array(
		'name' => esc_html__('Single question settings','discy'),
		'id'   => 'single_mobile',
		'type' => 'heading-2'
	);

	$options[] = array(
		'name' => $more_info,
		'type' => 'info'
	);

	$options[] = array(
		'name'    => esc_html__('Select the the setting of the single question page','discy'),
		'id'      => 'mobile_setting_single',
		'type'    => 'multicheck',
		'std'     => $array_single_std,
		'options' => $array_single_options
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);

	$options[] = array(
		'name' => esc_html__('Answers settings','discy'),
		'id'   => 'answers_mobile',
		'type' => 'heading-2'
	);

	$options[] = array(
		'name' => $more_info,
		'type' => 'info'
	);

	$options[] = array(
		'name'    => esc_html__('Answer sort','discy'),
		'id'      => 'mobile_answers_sort',
		'std'     => 'voted',
		'type'    => 'radio',
		'options' => array("voted" => esc_html__("Voted","discy"),"oldest" => esc_html__("Oldest","discy"),"recent" => esc_html__("Recent","discy"))
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);

	$options[] = array(
		'name' => esc_html__('Mobile styling','discy'),
		'id'   => 'styling_mobile',
		'type' => 'heading-2'
	);

	$options[] = array(
		'name' => $more_info,
		'type' => 'info'
	);

	$options[] = array(
		'name' => esc_html__('Do you need to activate the border bottom color only for the inputs?','discy'),
		'id'   => 'activate_input_border_bottom',
		'type' => 'checkbox'
	);

	$options[] = array(
		'div'       => 'div',
		'condition' => 'app_skin:not(dark)',
		'type'      => 'heading-2'
	);

	$options[] = array(
		'name'      => esc_html__('Input background color','discy'),
		'id'        => 'inputsbackgroundcolor',
		'type'      => 'color',
		'condition' => 'activate_input_border_bottom:is(0)',
		'std'       => '#000000'
	);

	$options[] = array(
		'name'      => esc_html__('Input border bottom color','discy'),
		'id'        => 'input_border_bottom_color',
		'type'      => 'color',
		'condition' => 'activate_input_border_bottom:not(0)',
		'std'       => '#000000'
	);

	$options[] = array(
		'name' => esc_html__('Login, signup, and forgot password background color','discy'),
		'id'   => 'loginbackground',
		'type' => 'color',
		'std'  => '#ffffff'
	);

	$options[] = array(
		'name' => esc_html__('Header Background color','discy'),
		'id'   => 'appbarbackgroundcolor',
		'type' => 'color',
		'std'  => '#ffffff'
	);

	$options[] = array(
		'name' => esc_html__('Tabs Background color','discy'),
		'id'   => 'tabbarbackgroundcolor',
		'type' => 'color',
		'std'  => '#ffffff'
	);

	$options[] = array(
		'name' => esc_html__('Bottom bar Background color','discy'),
		'id'   => 'bottombarbackgroundcolor',
		'type' => 'color',
		'std'  => '#ffffff'
	);

	$options[] = array(
		'name' => esc_html__('Header Text color','discy'),
		'id'   => 'appbarcolor',
		'type' => 'color',
		'std'  => '#283952'
	);

	$options[] = array(
		'name' => esc_html__('Tabs Active color','discy'),
		'id'   => 'tabbaractivetextcolor',
		'type' => 'color',
		'std'  => '#283952'
	);

	$options[] = array(
		'name' => esc_html__('Tabs underline/border color','discy'),
		'id'   => 'tabbarindicatorcolor',
		'type' => 'color',
		'std'  => '#2d6ff7'
	);

	$options[] = array(
		'name' => esc_html__('Tabs text color','discy'),
		'id'   => 'tabbartextcolor',
		'type' => 'color',
		'std'  => '#6D737C'
	);

	$options[] = array(
		'name' => esc_html__('Bottom bar Active color','discy'),
		'id'   => 'bottombaractivecolor',
		'type' => 'color',
		'std'  => '#2d6ff7'
	);

	$options[] = array(
		'name' => esc_html__('Bottom bar text color','discy'),
		'id'   => 'bottombarinactivecolor',
		'type' => 'color',
		'std'  => '#6D737C'
	);

	$options[] = array(
		'name' => esc_html__('Primary color','discy'),
		'id'   => 'mobile_primary',
		'type' => 'color',
		'std'  => '#2d6ff7'
	);

	$options[] = array(
		'name' => esc_html__('Secondary color','discy'),
		'id'   => 'mobile_secondary',
		'type' => 'color',
		'std'  => '#283952'
	);

	$options[] = array(
		'name' => esc_html__('Meta color','discy'),
		'id'   => 'secondaryvariant',
		'type' => 'color',
		'std'  => '#6D737C'
	);

	$options[] = array(
		'name' => esc_html__('Side navbar background','discy'),
		'id'   => 'mobile_background',
		'type' => 'color',
		'std'  => '#ffffff'
	);

	$options[] = array(
		'name' => esc_html__('Side navbar color','discy'),
		'id'   => 'sidemenutextcolor',
		'type' => 'color',
		'std'  => '#333739'
	);

	$options[] = array(
		'name' => esc_html__('Background','discy'),
		'id'   => 'scaffoldbackgroundcolor',
		'type' => 'color',
		'std'  => '#ffffff'
	);

	$options[] = array(
		'name' => esc_html__('Button color','discy'),
		'id'   => 'buttontextcolor',
		'type' => 'color',
		'std'  => '#ffffff'
	);

	$options[] = array(
		'name' => esc_html__('Divider color','discy'),
		'id'   => 'dividercolor',
		'type' => 'color',
		'std'  => '#EEEEEE'
	);

	$options[] = array(
		'name' => esc_html__('Shadow color','discy'),
		'id'   => 'shadowcolor',
		'type' => 'color',
		'std'  => '#000000'
	);

	$options[] = array(
		'name' => esc_html__('Button background color','discy'),
		'id'   => 'buttonsbackgroudcolor',
		'type' => 'color',
		'std'  => '#2d6ff7'
	);

	$options[] = array(
		'name' => esc_html__('Error background color','discy'),
		'id'   => 'errorcolor',
		'type' => 'color',
		'std'  => '#ff0000'
	);

	$options[] = array(
		'name' => esc_html__('Error text color','discy'),
		'id'   => 'errortextcolor',
		'type' => 'color',
		'std'  => '#ffffff'
	);

	$options[] = array(
		'name' => esc_html__('Success background color','discy'),
		'id'   => 'successcolor',
		'type' => 'color',
		'std'  => '#4be1ab'
	);

	$options[] = array(
		'name' => esc_html__('Success text color','discy'),
		'id'   => 'successtextcolor',
		'type' => 'color',
		'std'  => '#ffffff'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);

	$options[] = array(
		'div'       => 'div',
		'condition' => 'app_skin:is(dark)',
		'type'      => 'heading-2'
	);

	$options[] = array(
		'name'      => esc_html__('Input background color','discy'),
		'id'        => 'inputsbackgroundcolor_dark',
		'type'      => 'color',
		'condition' => 'activate_input_border_bottom:is(0)',
		'std'       => '#000000'
	);

	$options[] = array(
		'name'      => esc_html__('Input border bottom color','discy'),
		'id'        => 'input_border_bottom_color_dark',
		'type'      => 'color',
		'condition' => 'activate_input_border_bottom:not(0)',
		'std'       => '#000000'
	);

	$options[] = array(
		'name' => esc_html__('Login, signup, and forgot password background color','discy'),
		'id'   => 'loginbackground_dark',
		'type' => 'color',
		'std'  => '#ffffff'
	);

	$options[] = array(
		'name' => esc_html__('Header Background color','discy'),
		'id'   => 'appbarbackgroundcolor_dark',
		'type' => 'color',
		'std'  => '#003821'
	);

	$options[] = array(
		'name' => esc_html__('Tabs Background color','discy'),
		'id'   => 'tabbarbackgroundcolor_dark',
		'type' => 'color',
		'std'  => '#1d9300'
	);

	$options[] = array(
		'name' => esc_html__('Bottom bar Background color','discy'),
		'id'   => 'bottombarbackgroundcolor_dark',
		'type' => 'color',
		'std'  => '#33aa00'
	);

	$options[] = array(
		'name' => esc_html__('Header Text color','discy'),
		'id'   => 'appbarcolor_dark',
		'type' => 'color',
		'std'  => '#009e8e'
	);

	$options[] = array(
		'name' => esc_html__('Tabs Active color','discy'),
		'id'   => 'tabbaractivetextcolor_dark',
		'type' => 'color',
		'std'  => '#5d00ba'
	);

	$options[] = array(
		'name' => esc_html__('Tabs underline/border color','discy'),
		'id'   => 'tabbarindicatorcolor_dark',
		'type' => 'color',
		'std'  => '#006b1a'
	);

	$options[] = array(
		'name' => esc_html__('Tabs text color','discy'),
		'id'   => 'tabbartextcolor_dark',
		'type' => 'color',
		'std'  => '#0023b2'
	);

	$options[] = array(
		'name' => esc_html__('Bottom bar Active color','discy'),
		'id'   => 'bottombaractivecolor_dark',
		'type' => 'color',
		'std'  => '#007523'
	);

	$options[] = array(
		'name' => esc_html__('Bottom bar text color','discy'),
		'id'   => 'bottombarinactivecolor_dark',
		'type' => 'color',
		'std'  => '#0011ad'
	);

	$options[] = array(
		'name' => esc_html__('General color','discy'),
		'id'   => 'mobile_primary_dark',
		'type' => 'color',
		'std'  => '#005b00'
	);

	$options[] = array(
		'name' => esc_html__('Primary color','discy'),
		'id'   => 'mobile_secondary_dark',
		'type' => 'color',
		'std'  => '#ce0090'
	);

	$options[] = array(
		'name' => esc_html__('Meta color','discy'),
		'id'   => 'secondaryvariant_dark',
		'type' => 'color',
		'std'  => '#829300'
	);

	$options[] = array(
		'name' => esc_html__('Side navbar background','discy'),
		'id'   => 'mobile_background_dark',
		'type' => 'color',
		'std'  => '#6d4c00'
	);

	$options[] = array(
		'name' => esc_html__('Side navbar color','discy'),
		'id'   => 'sidemenutextcolor_dark',
		'type' => 'color',
		'std'  => '#7f3087'
	);

	$options[] = array(
		'name' => esc_html__('Background','discy'),
		'id'   => 'scaffoldbackgroundcolor_dark',
		'type' => 'color',
		'std'  => '#aa9900'
	);

	$options[] = array(
		'name' => esc_html__('Button color','discy'),
		'id'   => 'buttontextcolor_dark',
		'type' => 'color',
		'std'  => '#007a0c'
	);

	$options[] = array(
		'name' => esc_html__('Divider color','discy'),
		'id'   => 'dividercolor_dark',
		'type' => 'color',
		'std'  => '#7baf00'
	);

	$options[] = array(
		'name' => esc_html__('Shadow color','discy'),
		'id'   => 'shadowcolor_dark',
		'type' => 'color',
		'std'  => '#c60006'
	);

	$options[] = array(
		'name' => esc_html__('Button background color','discy'),
		'id'   => 'buttonsbackgroudcolor_dark',
		'type' => 'color',
		'std'  => '#0088ff'
	);

	$options[] = array(
		'name' => esc_html__('Error background color','discy'),
		'id'   => 'errorcolor_dark',
		'type' => 'color',
		'std'  => '#ff0000'
	);

	$options[] = array(
		'name' => esc_html__('Error text color','discy'),
		'id'   => 'errortextcolor_dark',
		'type' => 'color',
		'std'  => '#ffffff'
	);

	$options[] = array(
		'name' => esc_html__('Success background color','discy'),
		'id'   => 'successcolor_dark',
		'type' => 'color',
		'std'  => '#4be1ab'
	);

	$options[] = array(
		'name' => esc_html__('Success text color','discy'),
		'id'   => 'successtextcolor_dark',
		'type' => 'color',
		'std'  => '#ffffff'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);

	$options[] = array(
		'name' => esc_html__('Language settings','discy'),
		'id'   => 'lang_mobile',
		'type' => 'heading-2'
	);

	$options[] = array(
		'name' => $more_info,
		'type' => 'info'
	);

	$options = apply_filters("mobile_language_options",$options);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);

	return $options;
}?>