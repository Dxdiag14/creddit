<?php /* Backup settings */
add_action("discy_options_page","discy_backup_settings");
function discy_backup_settings() {
	if (isset($_GET['backup']) && $_GET['backup'] == 'settings') {
		$json = "backup-".date('Y-m-d',time()).".txt";
		$file = fopen($json,'w');
		$export = array(discy_options);
		$current_options = array();
		foreach ($export as $option) {
			$get_option_ = get_option($option);
			if ($get_option_) {
				$current_options[$option] = $get_option_;
			}else {
				$current_options[$option] = array();
			}
		}
		$array_json = json_encode($current_options);
		$array_json = base64_encode($array_json);
		fwrite($file,$array_json);
		fclose($file);
		header('Content-disposition: attachment; filename='.$json);
		header('Content-type: text/xml');
		ob_clean();
		flush();
		readfile($json);
		exit();
	}
}
/* Admin options */
function discy_admin_options() {
	$activate_currencies = discy_options("activate_currencies");
	$multi_currencies = discy_options("multi_currencies");
	$wp_editor_settings = array("media_buttons" => true,"textarea_rows" => 10);
	// Background Defaults
	$background_defaults = array(
		'color'      => '',
		'image'      => '',
		'repeat'     => 'repeat',
		'position'   => 'top center',
		'attachment' =>'scroll' 
	);

	// Pull all the pages into an array
	$options_pages = array();
	$options_pages_obj = get_pages('sort_column=post_parent,menu_order');
	$options_pages[''] = 'Select a page:';
	foreach ($options_pages_obj as $page) {
		$options_pages[$page->ID] = $page->post_title;
	}
	
	// Pull all the sidebars into an array
	$new_sidebars = array('default'=> 'Default');
	foreach ($GLOBALS['wp_registered_sidebars'] as $sidebar) {
		$new_sidebars[$sidebar['id']] = $sidebar['name'];
	}
	
	// Menus
	$menus = array();
	$all_menus = get_terms('nav_menu',array('hide_empty' => true));
	foreach ($all_menus as $menu) {
	    $menus[$menu->term_id] = $menu->name;
	}
	
	// Pull all the roles into an array
	global $wp_roles;
	$new_roles = array();
	foreach ($wp_roles->roles as $key => $value) {
		$new_roles[$key] = $value['name'];
	}
	
	// Share
	$share_array = array(
		"share_facebook" => array("sort" => "Facebook","value" => "share_facebook"),
		"share_twitter"  => array("sort" => "Twitter","value" => "share_twitter"),
		"share_linkedin" => array("sort" => "LinkedIn","value" => "share_linkedin"),
		"share_whatsapp" => array("sort" => "WhatsApp","value" => "share_whatsapp"),
	);

	$currencies = array(
		'USD' => 'USD',
		'EUR' => 'EUR',
		'GBP' => 'GBP',
		'JPY' => 'JPY',
		'CAD' => 'CAD',
		'INR' => 'INR',
		'TRY' => 'TRY',
		'BRL' => 'BRL',
		'HUF' => 'HUF',
		'BDT' => 'BDT',
		'AUD' => 'AUD',
		'IDR' => 'IDR'
	);

	$currencies = apply_filters("wpqa_currencies",$currencies);
	
	// Export
	$export = array(discy_options);
	$current_options = array();
	foreach ($export as $option) {
		$get_option_ = get_option($option);
		if ($get_option_) {
			$current_options[$option] = $get_option_;
		}else {
			$current_options[$option] = array();
		}
	}
	$current_options_e = json_encode($current_options);
	$current_options_e = base64_encode($current_options_e);
	
	// If using image radio buttons, define a directory path
	$imagepath =  get_template_directory_uri().'/admin/images/';
	$imagepath_theme =  get_template_directory_uri().'/images/';

	$options = array();
	
	$options[] = array(
		'name' => esc_html__('General settings','discy'),
		'id'   => 'general',
		'icon' => 'admin-site',
		'type' => 'heading'
	);
	
	$options[] = array(
		'type' => 'heading-2'
	);

	$options = apply_filters('discy_options_before_general_setting',$options);
	
	$options[] = array(
		'name' => esc_html__('Activate the lightbox at the site','discy'),
		'desc' => esc_html__('Select ON if you want to active the lightbox at the site.','discy'),
		'id'   => 'active_lightbox',
		'std'  => 'on',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Activate scroll to up button at the site','discy'),
		'desc' => esc_html__('Select ON if you want to activate scroll to top button at the site.','discy'),
		'id'   => 'go_up_button',
		'std'  => 'on',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Activate the ask question button at the site','discy'),
		'desc' => esc_html__('Select ON if you want to activate the ask question button at the site next to scroll to top button.','discy'),
		'id'   => 'ask_button',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Activate the font awesome at the site','discy'),
		'desc' => esc_html__('Select ON if you want to active the font awesome at the site.','discy'),
		'id'   => 'active_awesome',
		'std'  => 'on',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Enable loader','discy'),
		'desc' => esc_html__('Select ON to enable loader.','discy'),
		'id'   => 'loader',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => sprintf(esc_html__('Type the date format %1$s see this link %2$s.','discy'),'<a href="https://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">','</a>'),
		'desc' => esc_html__('Type here your date format.','discy'),
		'id'   => 'date_format',
		'std'  => 'F j, Y',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => sprintf(esc_html__('Type the time format %1$s see this link %2$s.','discy'),'<a href="https://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">','</a>'),
		'desc' => esc_html__('Type here your time format.','discy'),
		'id'   => 'time_format',
		'std'  => 'g:i a',
		'type' => 'text'
	);
	
	$options[] = array(
		'name'    => esc_html__('Excerpt type ','discy'),
		'desc'    => esc_html__('Choose form here the excerpt type.','discy'),
		'id'      => 'excerpt_type',
		'std'     => 'words',
		'type'    => "select",
		'options' => array(
			'words'      => esc_html__('Words','discy'),
			'characters' => esc_html__('Characters','discy')
		)
	);
	
	$options[] = array(
		'name' => esc_html__('Hide the top bar for WordPress','discy'),
		'desc' => esc_html__('Select ON if you want to hide the top bar for WordPress.','discy'),
		'id'   => 'top_bar_wordpress',
		'std'  => 'on',
		'type' => 'checkbox'
	);
	
	$roles_no_admin = $new_roles;
	unset($roles_no_admin["administrator"]);
	
	$options[] = array(
		'name'      => esc_html__("Choose the roles you don't want to show the WordPress admin top bar.","discy"),
		'id'        => 'top_bar_groups',
		'type'      => 'multicheck',
		'options'   => $roles_no_admin,
		'condition' => 'top_bar_wordpress:not(0)',
		'std'       => array('wpqa_under_review' => 'wpqa_under_review','ban_group' => 'ban_group','activation' => 'activation','subscriber' => 'subscriber','author' => 'author'),
	);
	
	$options[] = array(
		'name' => esc_html__('Do you like to redirect unlogged users from WordPress admin?','discy'),
		'desc' => esc_html__('Select ON if you want to redirect the unlogged users from the WordPress admin to the theme login page.','discy'),
		'id'   => 'redirect_wp_admin_unlogged',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Do you need to redirect user from WordPress admin?','discy'),
		'desc' => esc_html__('Select ON if you want to redirect the user from the WordPress admin.','discy'),
		'id'   => 'redirect_wp_admin',
		'std'  => 'on',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name'      => esc_html__("Choose the roles you don't want to show the WordPress admin.","discy"),
		'id'        => 'redirect_groups',
		'type'      => 'multicheck',
		'options'   => $roles_no_admin,
		'condition' => 'redirect_wp_admin:not(0)',
		'std'       => array('wpqa_under_review' => 'wpqa_under_review','ban_group' => 'ban_group','activation' => 'activation','subscriber' => 'subscriber','author' => 'author'),
	);
	
	$options[] = array(
		'name' => esc_html__('Enable SEO options','discy'),
		'desc' => esc_html__('Select ON to enable SEO options.','discy'),
		'id'   => 'seo_active',
		'std'  => 'on',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name'      => esc_html__('Share image','discy'),
		'desc'      => esc_html__('This is the share image','discy'),
		'id'        => 'fb_share_image',
		'condition' => 'seo_active:not(0)',
		'type'      => 'upload'
	);
	
	$options[] = array(
		'name' => esc_html__('Head code','discy'),
		'desc' => esc_html__('Paste your Google analytics code in the box','discy'),
		'id'   => 'head_code',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => esc_html__('Footer code','discy'),
		'desc' => esc_html__('Paste footer code in the box','discy'),
		'id'   => 'footer_code',
		'type' => 'textarea'
	);
	
	$options[] = array(
		'name' => esc_html__('SEO keywords','discy'),
		'desc' => esc_html__('Paste your keywords in the box','discy'),
		'id'   => 'the_keywords',
		'type' => 'textarea'
	);
	
	$options[] = array(
		'name' => esc_html__('WordPress login logo','discy'),
		'desc' => esc_html__('This is the logo that appears on the default WordPress login page','discy'),
		'id'   => 'login_logo',
		'std'  => $imagepath_theme."logo-footer.png",
		'type' => 'upload'
	);
	
	$options[] = array(
		'name' => esc_html__('WordPress login logo height','discy'),
		"id"   => "login_logo_height",
		"type" => "sliderui",
		'std'  => '45',
		"step" => "1",
		"min"  => "0",
		"max"  => "300"
	);
	
	$options[] = array(
		'name' => esc_html__('WordPress login logo width','discy'),
		"id"   => "login_logo_width",
		"type" => "sliderui",
		'std'  => '166',
		"step" => "1",
		"min"  => "0",
		"max"  => "300"
	);
	
	if (!function_exists('wp_site_icon') || !has_site_icon()) {
		$options[] = array(
			'name' => esc_html__('Custom favicon','discy'),
			'desc' => esc_html__("Upload the site's favicon here , You can create new favicon here favicon.cc","discy"),
			'id'   => 'favicon',
			'std'  => $imagepath_theme."favicon.png",
			'type' => 'upload'
		);
		
		$options[] = array(
			'name' => esc_html__('Custom favicon for iPhone','discy'),
			'desc' => esc_html__('Upload your custom iPhone favicon','discy'),
			'id'   => 'iphone_icon',
			'type' => 'upload'
		);
		
		$options[] = array(
			'name' => esc_html__('Custom iPhone retina favicon','discy'),
			'desc' => esc_html__('Upload your custom iPhone retina favicon','discy'),
			'id'   => 'iphone_icon_retina',
			'type' => 'upload'
		);
		
		$options[] = array(
			'name' => esc_html__('Custom favicon for iPad','discy'),
			'desc' => esc_html__('Upload your custom iPad favicon','discy'),
			'id'   => 'ipad_icon',
			'type' => 'upload'
		);
		
		$options[] = array(
			'name' => esc_html__('Custom iPad retina favicon','discy'),
			'desc' => esc_html__('Upload your custom iPad retina favicon','discy'),
			'id'   => 'ipad_icon_retina',
			'type' => 'upload'
		);
	}
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);

	$options = apply_filters("discy_options_after_general_setting",$options);
	
	$options[] = array(
		'name' => esc_html__('Under construction','discy'),
		'id'   => 'construction',
		'type' => 'heading',
		'icon' => 'admin-tools',
	);

	$options[] = array(
		'type' => 'heading-2'
	);
	
	$options[] = array(
		'name' => esc_html__('Activate under construction','discy'),
		'desc' => esc_html__('Select ON to enable under construction.','discy'),
		'id'   => 'under_construction',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'under_construction:not(0)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'name'    => esc_html__('Upload the background','discy'),
		'desc'    => esc_html__('Upload the background for the under construction page','discy'),
		'id'      => 'construction_background',
		'type'    => 'background',
		'options' => array('color' => '','image' => ''),
		'std'     => array(
			'color' => '#272930',
			'image' => $imagepath_theme."register.png"
		)
	);
	
	$options[] = array(
		"name" => esc_html__('Choose the background opacity','discy'),
		"desc" => esc_html__('Choose the background opacity from here','discy'),
		"id"   => "construction_opacity",
		"type" => "sliderui",
		'std'  => 30,
		"step" => "5",
		"min"  => "0",
		"max"  => "100"
	);
	
	$options[] = array(
		'name' => esc_html__('The headline','discy'),
		'desc' => esc_html__('Type the Headline from here','discy'),
		'id'   => 'construction_headline',
		'type' => 'text',
		'std'  => 'Coming soon'
	);
	
	$options[] = array(
		'name' => esc_html__('The paragraph','discy'),
		'desc' => esc_html__('Type the Paragraph from here','discy'),
		'id'   => 'construction_paragraph',
		'type' => 'textarea',
		'std'  => 'The site is under construction and something great is coming soon.'
	);
	
	$options[] = array(
		'name' => esc_html__('Construction redirect','discy'),
		'desc' => esc_html__('Type the link of the construction redirect','discy'),
		'id'   => 'construction_redirect',
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

	$header_settings = array(
		"header_s"     => esc_html__('Header setting','discy'),
		"call_action"  => esc_html__('Call to action','discy'),
		"breadcrumb_s" => esc_html__('Breadcrumbs','discy'),
		"posts_header" => esc_html__('Posts at header or footer','discy'),
		"slider"       => esc_html__('Slider','discy'),
	);
	
	$options[] = array(
		'name'    => esc_html__('Header settings','discy'),
		'id'      => 'header',
		'type'    => 'heading',
		'icon'    => 'menu',
		'std'     => 'header_s',
		'options' => apply_filters("discy_header_settings",$header_settings)
	);
	
	$options[] = array(
		'name' => esc_html__('Header setting','discy'),
		'id'   => 'header_s',
		'type' => 'heading-2'
	);
	
	$options[] = array(
		'name'    => esc_html__('Header height','discy'),
		'desc'    => esc_html__('Choose the header height.','discy'),
		'id'      => 'header_height',
		'std'     => 'style_1',
		'type'    => 'radio',
		'options' => array("style_1" => esc_html__("Large","discy"),"style_2" => esc_html__("Small","discy"))
	);
	
	$options[] = array(
		'name'    => esc_html__('Header style','discy'),
		'desc'    => esc_html__('Choose the header style.','discy'),
		'id'      => 'header_style',
		'std'     => 'normal',
		'type'    => 'radio',
		'options' => array("normal" => esc_html__("Normal","discy"),"simple" => esc_html__("Simple","discy"))
	);
	
	$options[] = array(
		'name'    => esc_html__('Header skin','discy'),
		'desc'    => esc_html__('Choose the header skin.','discy'),
		'id'      => 'header_skin',
		'std'     => 'dark',
		'type'    => 'radio',
		'options' => array("dark" => esc_html__("Dark","discy"),"light" => esc_html__("Light","discy"),"colored" => esc_html__("Colored","discy"))
	);
	
	$options[] = array(
		'name'    => esc_html__('Logo display','discy'),
		'desc'    => esc_html__('Choose the logo display.','discy'),
		'id'      => 'logo_display',
		'std'     => 'custom_image',
		'type'    => 'radio',
		'options' => array("display_title" => esc_html__("Display site title","discy"),"custom_image" => esc_html__("Custom Image","discy"))
	);
	
	$options[] = array(
		'name'      => esc_html__('Logo upload','discy'),
		'desc'      => esc_html__('Upload your custom logo.','discy'),
		'id'        => 'logo_img',
		'std'       => $imagepath_theme."logo.png",
		'type'      => 'upload',
		'condition' => 'logo_display:is(custom_image)',
		'options'   => array("height" => "logo_height","width" => "logo_width"),
	);
	
	$options[] = array(
		'name'      => esc_html__('Logo retina upload','discy'),
		'desc'      => esc_html__('Upload your custom logo retina.','discy'),
		'id'        => 'retina_logo',
		'std'       => $imagepath_theme."logo-2x.png",
		'type'      => 'upload',
		'condition' => 'logo_display:is(custom_image)'
	);
	
	$options[] = array(
		'name'      => esc_html__('Logo height','discy'),
		"id"        => "logo_height",
		"type"      => "sliderui",
		'std'       => '45',
		"step"      => "1",
		"min"       => "0",
		"max"       => "80",
		'condition' => 'logo_display:is(custom_image)'
	);
	
	$options[] = array(
		'name'      => esc_html__('Logo width','discy'),
		"id"        => "logo_width",
		"type"      => "sliderui",
		'std'       => '137',
		"step"      => "1",
		"min"       => "0",
		"max"       => "170",
		'condition' => 'logo_display:is(custom_image)'
	);
	
	$options[] = array(
		'name' => esc_html__('Header search option','discy'),
		'desc' => esc_html__('Select ON to enable header search.','discy'),
		'id'   => 'header_search',
		'std'  => 'on',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name'      => esc_html__('Activate the bigger search bar?','discy'),
		'desc'      => esc_html__('Select ON to enable header bigger search bar.','discy'),
		'id'        => 'big_search',
		'condition' => 'header_search:not(0)',
		'type'      => 'checkbox'
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'header_style:is(simple)',
		'type'      => 'heading-2'
	);

	$options[] = array(
		'name'    => esc_html__('Simple header button','discy'),
		'desc'    => esc_html__('Choose simple header button style from here.','discy'),
		'id'      => 'header_button',
		'options' => array(
			'question' => esc_html__('Ask A Question','discy'),
			'post'     => esc_html__('Add A Post','discy'),
			'custom'   => esc_html__('Custom link','discy'),
		),
		'std'     => 'question',
		'type'    => 'radio'
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'header_button:is(custom)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'name'    => esc_html__('Open the page in same page or a new page?','discy'),
		'id'      => 'header_button_target',
		'std'     => "new_page",
		'type'    => 'select',
		'options' => array("same_page" => esc_html__("Same page","discy"),"new_page" => esc_html__("New page","discy"))
	);
	
	$options[] = array(
		'name' => esc_html__('Type the button link','discy'),
		'id'   => 'header_button_link',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('Type the button text','discy'),
		'id'   => 'header_button_text',
		'type' => 'text'
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
		'name' => esc_html__('Header user login','discy'),
		'desc' => esc_html__('Select ON to enable header user login.','discy'),
		'id'   => 'header_user_login',
		'std'  => 'on',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'header_user_login:not(0)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'name'    => esc_html__('Header user login style','discy'),
		'desc'    => esc_html__('Choose header user login style.','discy'),
		'id'      => 'user_login_style',
		'std'     => 'style_1',
		'type'    => 'radio',
		'options' => array("style_1" => "Style 1","style_2" => "Style 2")
	);
	
	$user_login_links = array(
		"user-profile"        => array("sort" => esc_html__('User Profile','discy'),"value" => "user-profile"),
		"edit-profile"        => array("sort" => esc_html__('Edit Profile','discy'),"value" => "edit-profile"),
		"pending-questions"   => array("sort" => esc_html__('Pending Questions','discy'),"value" => "pending-questions"),
		"pending-posts"       => array("sort" => esc_html__('Pending Posts','discy'),"value" => "pending-posts"),
		"referrals"           => array("sort" => esc_html__('Referrals','discy'),"value" => "referrals"),
		"messages"            => array("sort" => esc_html__('Messages','discy'),"value" => "messages"),
		"questions"           => array("sort" => esc_html__('Questions','discy'),"value" => ""),
		"polls"               => array("sort" => esc_html__('Polls','discy'),"value" => ""),
		"questions"           => array("sort" => esc_html__('Questions','discy'),"value" => ""),
		"answers"             => array("sort" => esc_html__('Answers','discy'),"value" => ""),
		"followed"            => array("sort" => esc_html__('Followed','discy'),"value" => ""),
		"favorites"           => array("sort" => esc_html__('Favorites','discy'),"value" => ""),
		"posts"               => array("sort" => esc_html__('Posts','discy'),"value" => ""),
		"comments"            => array("sort" => esc_html__('Comments','discy'),"value" => ""),
		"followers-questions" => array("sort" => esc_html__('Followers Questions','discy'),"value" => ""),
		"followers-answers"   => array("sort" => esc_html__('Followers Answers','discy'),"value" => ""),
		"followers-posts"     => array("sort" => esc_html__('Followers Posts','discy'),"value" => ""),
		"followers-comments"  => array("sort" => esc_html__('Followers Comments','discy'),"value" => ""),
		"groups"              => array("sort" => esc_html__('Groups','discy'),"value" => ""),
		"paid-questions"      => array("sort" => esc_html__('Paid Questions','discy'),"value" => ""),
		"asked-questions"     => array("sort" => esc_html__('Asked Questions','discy'),"value" => "asked-questions"),
		"best-answers"        => array("sort" => esc_html__('Best Answers','discy'),"value" => "best-answers"),
		"points"              => array("sort" => esc_html__('Points','discy'),"value" => "points"),
		"following"           => array("sort" => esc_html__('Following','discy'),"value" => ""),
		"followers"           => array("sort" => esc_html__('Followers','discy'),"value" => ""),
		"activities"          => array("sort" => esc_html__('Activity Log','discy'),"value" => "activities"),
		"notifications"       => array("sort" => esc_html__('Notifications','discy'),"value" => ""),
		"subscriptions"       => array("sort" => esc_html__('Subscriptions','discy'),"value" => ""),
		"log-out"             => array("sort" => esc_html__('Log out','discy'),"value" => "log-out"),
	);
	
	$options[] = array(
		'name'         => esc_html__('Select the pages to show at the login area','discy'),
		'id'           => 'user_login_links',
		'type'         => 'multicheck',
		'sort'         => 'yes',
		'limit-height' => 'yes',
		'std'          => $user_login_links,
		'options'      => $user_login_links
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'div'       => 'div',
		'type'      => 'heading-2',
		'condition' => 'active_message:not(0),header_style:is(simple)'
	);
	
	$options[] = array(
		'name' => esc_html__('Header messages','discy'),
		'desc' => esc_html__('Select ON to enable header messages.','discy'),
		'id'   => 'header_messages',
		'std'  => 'on',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'div'       => 'div',
		'type'      => 'heading-2',
		'condition' => 'active_message:not(0),header_style:is(simple),header_messages:not(0)'
	);
	
	$options[] = array(
		'name'    => esc_html__('Header messages style','discy'),
		'desc'    => esc_html__('Choose header messages style.','discy'),
		'id'      => 'messages_style',
		'std'     => 'style_1',
		'type'    => 'radio',
		'options' => array("style_1" => "Style 1","style_2" => "Style 2")
	);
	
	$options[] = array(
		'name' => esc_html__('Header messages number','discy'),
		'desc' => esc_html__('Put the header messages number.','discy'),
		'id'   => 'messages_number',
		'std'  => 5,
		'type' => 'text'
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
		'name' => esc_html__('Header notifications','discy'),
		'desc' => esc_html__('Select ON to enable header notifications.','discy'),
		'id'   => 'header_notifications',
		'std'  => 'on',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'div'       => 'div',
		'type'      => 'heading-2',
		'condition' => 'header_notifications:not(0)'
	);
	
	$options[] = array(
		'name'    => esc_html__('Header notifications style','discy'),
		'desc'    => esc_html__('Choose header notifications style.','discy'),
		'id'      => 'notifications_style',
		'std'     => 'style_1',
		'type'    => 'radio',
		'options' => array("style_1" => "Style 1","style_2" => "Style 2")
	);
	
	$options[] = array(
		'name' => esc_html__('Header notifications number','discy'),
		'desc' => esc_html__('Put the header notifications number.','discy'),
		'id'   => 'notifications_number',
		'std'  => 5,
		'type' => 'text'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name' => esc_html__('Fixed header option','discy'),
		'desc' => esc_html__('Select ON to enable fixed header.','discy'),
		'id'   => 'header_fixed',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name' => esc_html__('Call to action','discy'),
		'id'   => 'call_action',
		'type' => 'heading-2'
	);
	
	$options[] = array(
		'name' => esc_html__('Activate the call to action','discy'),
		'desc' => esc_html__('Select ON to enable the call to action.','discy'),
		'id'   => 'call_action',
		'std'  => 'on',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'div'       => 'div',
		'type'      => 'heading-2',
		'condition' => 'call_action:not(0)'
	);
	
	$options[] = array(
		'name'    => esc_html__('The call to action works at all the pages, custom pages, or home page only?','discy'),
		'id'      => 'action_home_pages',
		'options' => array(
			'home_page'     => esc_html__('Home page','discy'),
			'all_pages'     => esc_html__('All site pages','discy'),
			'all_posts'     => esc_html__('All single post pages','discy'),
			'all_questions' => esc_html__('All single quepage pages','discy'),
			'custom_pages'  => esc_html__('Custom pages','discy'),
		),
		'std'     => 'home_page',
		'type'    => 'radio'
	);

	$options[] = array(
		'name'      => esc_html__('Page ids','discy'),
		'desc'      => esc_html__('Type from here the page ids','discy'),
		'id'        => 'action_pages',
		'type'      => 'text',
		'condition' => 'action_home_pages:is(custom_pages)'
	);
	
	$options[] = array(
		'name'    => esc_html__('Action skin','discy'),
		'desc'    => esc_html__('Choose the action skin.','discy'),
		'id'      => 'action_skin',
		'std'     => 'dark',
		'type'    => 'radio',
		'options' => array("light" => esc_html__("Light","discy"),"dark" => esc_html__("Dark","discy"),"colored" => esc_html__("Colored","discy"))
	);
	
	$options[] = array(
		'name'    => esc_html__('Action style','discy'),
		'desc'    => esc_html__('Choose action style from here.','discy'),
		'id'      => 'action_style',
		'options' => array(
			'style_1'  => 'Style 1',
			'style_2'  => 'Style 2',
		),
		'std'     => 'style_1',
		'type'    => 'radio'
	);
	
	$options[] = array(
		'name'    => esc_html__('Action image or video','discy'),
		'id'      => 'action_image_video',
		'options' => array(
			'image' => esc_html__('Image','discy'),
			'video' => esc_html__('Video','discy'),
		),
		'std'     => 'image',
		'type'    => 'radio'
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'action_image_video:not(video)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'name'    => esc_html__('Upload the background','discy'),
		'id'      => 'action_background',
		'type'    => 'background',
		'options' => array('color' => '','image' => ''),
		'std'     => array(
			'image' => $imagepath_theme."action.png"
		)
	);
	
	$options[] = array(
		"name" => esc_html__('Choose the background opacity','discy'),
		"desc" => esc_html__('Choose the background opacity from here','discy'),
		"id"   => "action_opacity",
		"type" => "sliderui",
		'std'  => 50,
		"step" => "5",
		"min"  => "0",
		"max"  => "100"
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'action_image_video:is(video)',
		'type'      => 'heading-2'
	);

	$options[] = array(
		'name'    => esc_html__('Video type','discy'),
		'id'      => 'action_video_type',
		'type'    => 'select',
		'options' => array(
			'youtube'  => esc_html__("Youtube","discy"),
			'vimeo'    => esc_html__("Vimeo","discy"),
			'daily'    => esc_html__("Dailymotion","discy"),
			'facebook' => esc_html__("Facebook video","discy"),
			'html5'    => esc_html__("HTML 5","discy"),
			'embed'    => esc_html__("Custom embed","discy"),
		),
		'std'     => 'youtube',
		'desc'    => esc_html__('Choose from here the video type','discy'),
	);
	
	$options[] = array(
		'name'      => esc_html__('Custom embed','discy'),
		'desc'      => esc_html__('Put your Custom embed html','discy'),
		'id'        => "action_custom_embed",
		'type'      => 'textarea',
		'cols'      => "40",
		'rows'      => "8",
		'condition' => 'action_video_type:is(embed)'
	);
	
	$options[] = array(
		'name'      => esc_html__('Video ID','discy'),
		'id'        => 'action_video_id',
		'desc'      => esc_html__('Put the Video ID here: https://www.youtube.com/watch?v=JuyB7NO0EYY Ex: "JuyB7NO0EYY"','discy'),
		'type'      => 'text',
		'operator'  => 'or',
		'condition' => 'action_video_type:is(youtube),'.'action_video_type:is(vimeo),'.'action_video_type:is(daily),'.'action_video_type:is(facebook)'
	);
	
	$options[] = array(
		'name'      => esc_html__('Mp4 video','discy'),
		'id'        => 'action_video_mp4',
		'desc'      => esc_html__('Put mp4 video here','discy'),
		'type'      => 'text',
		'condition' => 'action_video_type:is(html5)'
	);
	
	$options[] = array(
		'name'      => esc_html__('M4v video','discy'),
		'id'        => 'action_video_m4v',
		'desc'      => esc_html__('Put m4v video here','discy'),
		'type'      => 'text',
		'condition' => 'action_video_type:is(html5)'
	);
	
	$options[] = array(
		'name'      => esc_html__('Webm video','discy'),
		'id'        => 'action_video_webm',
		'desc'      => esc_html__('Put webm video here','discy'),
		'type'      => 'text',
		'condition' => 'action_video_type:is(html5)'
	);
	
	$options[] = array(
		'name'      => esc_html__('Ogv video','discy'),
		'id'        => 'action_video_ogv',
		'desc'      => esc_html__('Put ogv video here','discy'),
		'type'      => 'text',
		'condition' => 'action_video_type:is(html5)'
	);
	
	$options[] = array(
		'name'      => esc_html__('Wmv video','discy'),
		'id'        => 'action_video_wmv',
		'desc'      => esc_html__('Put wmv video here','discy'),
		'type'      => 'text',
		'condition' => 'action_video_type:is(html5)'
	);
	
	$options[] = array(
		'name'      => esc_html__('Flv video','discy'),
		'id'        => 'action_video_flv',
		'desc'      => esc_html__('Put flv video here','discy'),
		'type'      => 'text',
		'condition' => 'action_video_type:is(html5)'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name' => esc_html__('The headline','discy'),
		'desc' => esc_html__('Type the Headline from here','discy'),
		'id'   => 'action_headline',
		'type' => 'text',
		'std'  => "Share & grow the world's knowledge!"
	);
	
	$options[] = array(
		'name'     => esc_html__('The paragraph','discy'),
		'desc'     => esc_html__('Type the Paragraph from here','discy'),
		'id'       => 'action_paragraph',
		'type'     => apply_filters('discy_action_paragraph','textarea'),
		'std'      => 'We want to connect the people who have knowledge to the people who need it, to bring together people with different perspectives so they can understand each other better, and to empower everyone to share their knowledge.'
	);
	
	$options[] = array(
		'name'    => esc_html__('Action button','discy'),
		'desc'    => esc_html__('Choose Action button style from here.','discy'),
		'id'      => 'action_button',
		'options' => array(
			'signup'   => esc_html__('Create A New Account','discy'),
			'login'    => esc_html__('Login','discy'),
			'question' => esc_html__('Ask A Question','discy'),
			'post'     => esc_html__('Add A Post','discy'),
			'custom'   => esc_html__('Custom link','discy'),
		),
		'std'     => 'signup',
		'type'    => 'radio'
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'action_button:is(custom)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'name'    => esc_html__('Open the page in same page or a new page?','discy'),
		'id'      => 'action_button_target',
		'std'     => "new_page",
		'type'    => 'select',
		'options' => array("same_page" => esc_html__("Same page","discy"),"new_page" => esc_html__("New page","discy"))
	);
	
	$options[] = array(
		'name' => esc_html__('Type the button link','discy'),
		'id'   => 'action_button_link',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('Type the button text','discy'),
		'id'   => 'action_button_text',
		'type' => 'text'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name'    => esc_html__('The call to action works for "Unlogged users", "Logged users" or both','discy'),
		'desc'    => esc_html__('Choose the call to action works for "Unlogged users", "Logged users" or both.','discy'),
		'id'      => 'action_logged',
		'options' => array(
			'unlogged' => esc_html__('Unlogged users','discy'),
			'logged'   => esc_html__('Logged users','discy'),
			'both'     => esc_html__('Both','discy'),
		),
		'std'     => 'unlogged',
		'type'    => 'radio',
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
		'name' => esc_html__('Breadcrumbs','discy'),
		'id'   => 'breadcrumb_s',
		'type' => 'heading-2'
	);
	
	$options[] = array(
		'name' => esc_html__('Breadcrumbs','discy'),
		'desc' => esc_html__('Select ON to enable the breadcrumbs.','discy'),
		'id'   => 'breadcrumbs',
		'std'  => 'on',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'breadcrumbs:not(0)',
		'type'      => 'heading-2'
	);

	$options[] = array(
		'name'    => esc_html__('Breadcrumbs style','discy'),
		'id'      => 'breadcrumbs_style',
		'options' => array(
			'style_1' => esc_html__('Style 1','discy'),
			'style_2' => esc_html__('Style 2','discy'),
		),
		'std'     => 'style_1',
		'type'    => 'radio',
	);

	$options[] = array(
		'name'      => esc_html__('Breadcrumbs skin','discy'),
		'desc'      => esc_html__('Choose the breadcrumbs skin.','discy'),
		'id'        => 'breadcrumbs_skin',
		'std'       => 'light',
		'type'      => 'radio',
		'condition' => 'breadcrumbs_style:is(style_2)',
		'options'   => array("light" => esc_html__("Light","discy"),"dark" => esc_html__("Dark","discy"),"colored" => esc_html__("Colored","discy"))
	);

	$options[] = array(
		'name' => esc_html__("Remove the h1 title for the posts and questions on the inner page to don't duplicate it","discy"),
		'desc' => esc_html__("Select ON to enable to remove the h1 title for the posts and questions on the inner page to don't duplicate it.","discy"),
		'id'   => 'breadcrumbs_content_title',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Breadcrumbs separator','discy'),
		'desc' => esc_html__('Add your breadcrumbs separator.','discy'),
		'id'   => 'breadcrumbs_separator',
		'std'  => '/',
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
		'name' => esc_html__('Posts at header or footer','discy'),
		'id'   => 'posts_header',
		'type' => 'heading-2'
	);
	
	$options[] = array(
		'name' => esc_html__('Activate the posts area or not','discy'),
		'desc' => esc_html__('Select ON to enable the posts area.','discy'),
		'id'   => 'blog_h',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'blog_h:not(0)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'name'    => esc_html__('The posts area works after header or before footer?','discy'),
		'id'      => 'blog_h_where',
		'options' => array(
			'header' => esc_html__('After header','discy'),
			'footer' => esc_html__('Before footer','discy'),
		),
		'std'     => 'footer',
		'type'    => 'radio'
	);
	
	$options[] = array(
		'name'    => esc_html__('The posts area works at all the pages, custom pages, or home page only?','discy'),
		'id'      => 'blog_h_home_pages',
		'options' => array(
			'home_page'     => esc_html__('Home page','discy'),
			'all_pages'     => esc_html__('All site pages','discy'),
			'all_posts'     => esc_html__('All single post pages','discy'),
			'all_questions' => esc_html__('All single quepage pages','discy'),
			'custom_pages'  => esc_html__('Custom pages','discy'),
		),
		'std'     => 'home_page',
		'type'    => 'radio'
	);

	$options[] = array(
		'name'      => esc_html__('Page ids','discy'),
		'desc'      => esc_html__('Type from here the page ids','discy'),
		'id'        => 'blog_h_pages',
		'type'      => 'text',
		'condition' => 'blog_h_home_pages:is(custom_pages)'
	);
	
	$options[] = array(
		'name' => esc_html__('The title','discy'),
		'desc' => esc_html__('Type from here the title','discy'),
		'id'   => 'blog_h_title',
		'type' => 'text',
		'std'  => 'Latest News & Updates'
	);
	
	$options[] = array(
		'name' => esc_html__('Activate the more post button','discy'),
		'desc' => esc_html__('Select ON to enable the button.','discy'),
		'id'   => 'blog_h_button',
		'std'  => 'on',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'blog_h_button:not(0)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'name' => esc_html__('The text for the button','discy'),
		'desc' => esc_html__('Type from here the text for the button','discy'),
		'id'   => 'blog_h_button_text',
		'type' => 'text',
		'std'  => 'Explore Our Blog'
	);
	
	$options[] = array(
		'name'    => esc_html__('Blog page','discy'),
		'desc'    => esc_html__('Select the blog page','discy'),
		'id'      => 'blog_h_page',
		'type'    => 'select',
		'options' => $options_pages
	);
	
	$options[] = array(
		'name' => esc_html__("Type the blog link if you don't like a page","discy"),
		'id'   => 'blog_h_link',
		'type' => 'text'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name' => esc_html__('Posts number','discy'),
		'id'   => 'blog_h_post_number',
		'std'  => 3,
		'type' => 'text'
	);
	
	$options[] = array(
		'name'    => esc_html__('Post style','discy'),
		'desc'    => esc_html__('Choose post style from here.','discy'),
		'id'      => 'blog_h_post_style',
		'options' => array(
			'style_1' => esc_html__('1 column','discy'),
			'style_2' => esc_html__('List style','discy'),
			'style_3' => esc_html__('Columns','discy'),
		),
		'std'   => 'style_3',
		'type'  => 'radio',
	);
	
	$options[] = array(
		'name' => esc_html__('Choose a custom setting for the posts','discy'),
		'id'   => 'blog_h_custom_home_blog',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'blog_h_custom_home_blog:not(0)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'name' => esc_html__('Hide the featured image in the loop','discy'),
		'desc' => esc_html__('Select ON to hide the featured image in the loop.','discy'),
		'id'   => 'blog_h_featured_image',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'id'        => "blog_h_sort_meta_title_image",
		'condition' => 'blog_h_post_style:is(style_3)',
		'std'       => array(
						array("value" => "image",'name' => esc_html__('Image','discy'),"default" => "yes"),
						array("value" => "meta_title",'name' => esc_html__('Meta and title','discy'),"default" => "yes"),
					),
		'type'      => "sort",
		'options'   => array(
						array("value" => "image",'name' => esc_html__('Image','discy'),"default" => "yes"),
						array("value" => "meta_title",'name' => esc_html__('Meta and title','discy'),"default" => "yes"),
					)
	);
	
	$options[] = array(
		'name' => esc_html__('Read more enable or disable','discy'),
		'id'   => 'blog_h_read_more',
		'std'  => "on",
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Excerpt post','discy'),
		'desc' => esc_html__('Put here the excerpt post.','discy'),
		'id'   => 'blog_h_post_excerpt',
		'std'  => 40,
		'type' => 'text'
	);
	
	$options[] = array(
		'name'    => esc_html__('Select the meta options','discy'),
		'id'      => 'blog_h_post_meta',
		'type'    => 'multicheck',
		'std'     => array(
			"category_post" => "category_post",
			"title_post"    => "title_post",
			"author_by"     => "author_by",
			"post_date"     => "post_date",
			"post_comment"  => "post_comment",
			"post_views"    => "post_views",
		),
		'options' => array(
			"category_post" => esc_html__('Category post - Work at 1 column only','discy'),
			"title_post"    => esc_html__('Title post','discy'),
			"author_by"     => esc_html__('Author by - Work at 1 column only','discy'),
			"post_date"     => esc_html__('Date meta','discy'),
			"post_comment"  => esc_html__('Comment meta','discy'),
			"post_views"    => esc_html__("Views stats","discy"),
		)
	);
	
	$options[] = array(
		'name'      => esc_html__('Select the share options','discy'),
		'id'        => 'blog_h_post_share',
		'condition' => 'blog_h_post_style:not(style_3)',
		'type'      => 'multicheck',
		'sort'      => 'yes',
		'std'       => $share_array,
		'options'   => $share_array
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
		'name' => esc_html__('Slider','discy'),
		'id'   => 'slider',
		'type' => 'heading-2'
	);
	
	$options[] = array(
		'name' => esc_html__('Activate the slider or not','discy'),
		'desc' => esc_html__('Select ON to enable the posts area.','discy'),
		'id'   => 'slider_h',
		'type' => 'checkbox',
		'std'  => 'on'
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'slider_h:not(0)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'name'    => esc_html__('Slider works at all the pages, custom pages, or home page only?','discy'),
		'id'      => 'slider_h_home_pages',
		'options' => array(
			'home_page'     => esc_html__('Home page','discy'),
			'all_pages'     => esc_html__('All site pages','discy'),
			'all_posts'     => esc_html__('All single post pages','discy'),
			'all_questions' => esc_html__('All single quepage pages','discy'),
			'custom_pages'  => esc_html__('Custom pages','discy'),
		),
		'std'     => 'home_page',
		'type'    => 'radio'
	);

	$options[] = array(
		'name'      => esc_html__('Page ids','discy'),
		'desc'      => esc_html__('Type from here the page ids','discy'),
		'id'        => 'slider_h_pages',
		'type'      => 'text',
		'condition' => 'slider_h_home_pages:is(custom_pages)'
	);

	$options[] = array(
		'name'    => esc_html__('Slider works for "Unlogged users", "Logged users" or both','discy'),
		'id'      => 'slider_h_logged',
		'options' => array(
			'unlogged' => esc_html__('Unlogged users','discy'),
			'logged'   => esc_html__('Logged users','discy'),
			'both'     => esc_html__('Both','discy'),
		),
		'std'     => 'both',
		'type'    => 'radio',
	);

	$options[] = array(
		'name'    => esc_html__('Choose the slider that works with the theme or add your custom slider by inserting the code or shortcodes','discy'),
		'id'      => 'custom_slider',
		'options' => array(
			'slider' => esc_html__('Theme slider','discy'),
			'custom' => esc_html__('Custom slider','discy'),
		),
		'std'     => 'slider',
		'type'    => 'radio',
	);

	$options[] = array(
		'div'       => 'div',
		'condition' => 'custom_slider:is(slider)',
		'type'      => 'heading-2'
	);

	$options[] = array(
		'name' => esc_html__('Slider height','discy'),
		"id"   => "slider_height",
		"type" => "sliderui",
		"step" => "50",
		"min"  => "400",
		"max"  => "1000",
		"std"  => "500"
	);

	$slide_elements = array(
		array(
			"type" => "color",
			"id"   => "color",
			"name" => esc_html__('Color','discy')
		),
		array(
			"type" => "upload",
			"id"   => "image",
			"name" => esc_html__('Image','discy')
		),
		array(
			"type"  => "slider",
			"name"  => esc_html__('Choose the background opacity','discy'),
			"id"    => "opacity",
			"std"   => "0",
			"step"  => "1",
			"min"   => "0",
			"max"   => "100",
			"value" => "0"
		),
		array(
			"type"    => "radio",
			"id"      => "align",
			"name"    => esc_html__('Align','discy'),
			'options' => array(
				'left'   => esc_html__('Left','discy'),
				'center' => esc_html__('Center','discy'),
				'right'  => esc_html__('Right','discy'),
			),
			'std'     => 'left',
		),
		array(
			"type"      => "radio",
			"id"        => "login",
			"name"      => esc_html__('Login or Signup','discy'),
			'options'   => array(
				'none'   => esc_html__('None','discy'),
				'login'  => esc_html__('Login','discy'),
				'signup' => esc_html__('Signup','discy'),
			),
			'condition' => '[%id%]align:not(center),[%id%]button_block:not(block)',
			'std'       => 'login',
		),
		array(
			"type" => "text",
			"id"   => "title",
			"name" => esc_html__('Title','discy')
		),
		array(
			"type" => "text",
			"id"   => "title_2",
			"name" => esc_html__('Second title','discy')
		),
		array(
			"type" => "textarea",
			"id"   => "paragraph",
			"name" => esc_html__('Paragraph','discy')
		),
		array(
			"type"    => "radio",
			"id"      => "button_block",
			"name"    => esc_html__('Button or Block','discy'),
			'options' => array(
				'none'   => esc_html__('None','discy'),
				'button' => esc_html__('button','discy'),
				'block'  => esc_html__('Block','discy'),
			),
			'std'     => 'none',
		),
		array(
			"type"      => "radio",
			"id"        => "block",
			"name"      => esc_html__('Block','discy'),
			'options'   => array(
				'search'   => esc_html__('Search','discy'),
				'question' => esc_html__('Ask A Question','discy'),
			),
			'condition' => '[%id%]button_block:is(block)',
			'std'       => 'search',
		),
		array(
			"type"      => "radio",
			"id"        => "button",
			"name"      => esc_html__('Button','discy'),
			'options'   => array(
				'signup'   => esc_html__('Create A New Account','discy'),
				'login'    => esc_html__('Login','discy'),
				'question' => esc_html__('Ask A Question','discy'),
				'post'     => esc_html__('Add A Post','discy'),
				'custom'   => esc_html__('Custom link','discy'),
			),
			'condition' => '[%id%]button_block:is(button)',
			'std'       => 'signup',
		),
		array(
			"type"      => "radio",
			"id"        => "button_style",
			"name"      => esc_html__('Button style','discy'),
			'options'   => array(
				'style_1' => esc_html__('Style 1','discy'),
				'style_2' => esc_html__('Style 2','discy'),
				'style_3' => esc_html__('Style 3','discy'),
			),
			'condition' => '[%id%]button_block:is(button)',
			'std'       => 'style_1',
		),
		array(
			'div'       => 'div',
			'condition' => '[%id%]button:is(custom),[%id%]button_block:is(button)',
			'type'      => 'heading-2'
		),
		array(
			'name'    => esc_html__('Open the page in same page or a new page?','discy'),
			'id'      => 'button_target',
			'std'     => "new_page",
			'type'    => 'select',
			'options' => array("same_page" => esc_html__("Same page","discy"),"new_page" => esc_html__("New page","discy"))
		),
		array(
			'name' => esc_html__('Type the button link','discy'),
			'id'   => 'button_link',
			'type' => 'text'
		),
		array(
			'name' => esc_html__('Type the button text','discy'),
			'id'   => 'button_text',
			'type' => 'text'
		),
		array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		),
	);
	
	$options[] = array(
		'id'      => "add_slides",
		'type'    => "elements",
		'button'  => esc_html__('Add a new slide','discy'),
		'hide'    => "yes",
		'options' => $slide_elements,
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'id'        => "custom_slides",
		'type'      => "textarea",
		'name'      => esc_html__('Add your custom slide or shortcode','discy'),
		'condition' => 'custom_slider:is(custom)',
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

	$options = apply_filters('discy_options_after_slider_setting',$options);
	
	$options[] = array(
		'name' => esc_html__('Responsive settings','discy'),
		'id'   => 'responsive',
		'icon' => 'smartphone',
		'type' => 'heading'
	);
	
	$options[] = array(
		'type' => 'heading-2'
	);
	
	$options[] = array(
		'name'      => esc_html__('Stop fixed header in mobile','discy'),
		'desc'      => esc_html__('Select ON to stop fixed header in mobile.','discy'),
		'id'        => 'header_fixed_responsive',
		'condition' => 'header_fixed:not(0)',
		'type'      => 'checkbox'
	);

	$options[] = array(
		'name' => esc_html__('Icon for the login or signup in the mobile header','discy'),
		'desc' => esc_html__('Type from here the icon of the login or signup button in the mobile header.','discy'),
		'id'   => 'header_responsive_icon',
		'std'  => 'icon-lock',
		'type' => 'text'
	);

	$options[] = array(
		'name'    => esc_html__('Button at mobile for the unlogged case','discy'),
		'desc'    => esc_html__('Choose button type at the mobile display for the unlogged case from here.','discy'),
		'id'      => 'mobile_sign',
		'options' => array(
			'login'  => esc_html__('Login','discy'),
			'signup' => esc_html__('Signup','discy'),
		),
		'std'     => 'login',
		'type'    => 'radio'
	);
	
	$options[] = array(
		'name' => esc_html__('Choose the mobile menu skin','discy'),
		'id'   => "mobile_menu",
		'std'  => "dark",
		'type' => "images",
		'options' => array(
			'dark'  => $imagepath.'menu_dark.jpg',
			'gray'  => $imagepath.'sidebar_no.jpg',
			'light' => $imagepath.'menu_light.jpg',
		)
	);
	
	$options[] = array(
		'name' => esc_html__('Mobile bar enable or disable?','discy'),
		'desc' => esc_html__('Select ON to enable the mobile bar.','discy'),
		'id'   => 'mobile_bar',
		'std'  => 'on',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'mobile_bar:not(0)',
		'type'      => 'heading-2'
	);

	$options[] = array(
		'name'    => esc_html__('Mobile button','discy'),
		'desc'    => esc_html__('Choose mobile button style from here.','discy'),
		'id'      => 'mobile_button',
		'options' => array(
			'question' => esc_html__('Ask A Question','discy'),
			'post'     => esc_html__('Add A Post','discy'),
			'custom'   => esc_html__('Custom link','discy'),
		),
		'std'     => 'question',
		'type'    => 'radio'
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'mobile_button:is(custom)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'name'    => esc_html__('Open the page in same page or a new page?','discy'),
		'id'      => 'mobile_button_target',
		'std'     => "new_page",
		'type'    => 'select',
		'options' => array("same_page" => esc_html__("Same page","discy"),"new_page" => esc_html__("New page","discy"))
	);
	
	$options[] = array(
		'name' => esc_html__('Type the button link','discy'),
		'id'   => 'mobile_button_link',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('Type the button text','discy'),
		'id'   => 'mobile_button_text',
		'type' => 'text'
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
		'name' => esc_html__('Activate a custom mobile menu or not?','discy'),
		'desc' => esc_html__('Select ON to enable the custom mobile menu.','discy'),
		'id'   => 'active_mobile_menu',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name'      => esc_html__('Sort the mobile menus','discy'),
		'id'        => "sort_mobile_menus",
		'condition' => 'active_mobile_menu:is(0)',
		'std'       => array(
						array("value" => "left",'name' => esc_html__('Left menu','discy'),"default" => "yes"),
						array("value" => "top",'name' => esc_html__('Top menu','discy'),"default" => "yes"),
					),
		'type'      => "sort",
		'options'   => array(
						array("value" => "left",'name' => esc_html__('Left menu','discy'),"default" => "yes"),
						array("value" => "top",'name' => esc_html__('Top menu','discy'),"default" => "yes"),
					)
	);
	
	$options[] = array(
		'name'      => esc_html__('Choose from here which menu will show at mobile for "unlogged users".','discy'),
		'id'        => 'mobile_menu',
		'type'      => 'select',
		'condition' => 'active_mobile_menu:not(0)',
		'options'   => $menus
	);
	
	$options[] = array(
		'name'      => esc_html__('Choose from here which menu will show at mobile for "logged in users".','discy'),
		'id'        => 'mobile_menu_logged',
		'type'      => 'select',
		'condition' => 'active_mobile_menu:not(0)',
		'options'   => $menus
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name'    => esc_html__('Post settings','discy'),
		'id'      => 'posts',
		'icon'    => 'admin-page',
		'type'    => 'heading',
		'std'     => 'post_loop',
		'options' => array(
			"post_loop"         => esc_html__('Posts & Loop setting','discy'),
			"add_edit_delete_p" => esc_html__('Add - Edit - Delete','discy'),
			"post_meta"         => esc_html__('Post meta settings','discy'),
			"inner_pages"       => esc_html__('Inner page settings','discy'),
			"share_setting"     => esc_html__('Share setting','discy'),
			"related_setting"   => esc_html__('Related setting','discy'),
			"posts_layouts"     => esc_html__('Posts layouts','discy')
		)
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'id'   => 'post_loop',
		'name' => esc_html__('Posts & Loop setting','discy')
	);
	
	$options[] = array(
		'name'    => esc_html__('Post style','discy'),
		'desc'    => esc_html__('Choose post style from here.','discy'),
		'id'      => 'post_style',
		'options' => array(
			'style_1' => esc_html__('1 column','discy'),
			'style_2' => esc_html__('List style','discy'),
			'style_3' => esc_html__('Columns','discy'),
		),
		'std'     => 'style_1',
		'type'    => 'radio'
	);
	
	$options[] = array(
		'name' => esc_html__('Category description enable or disable','discy'),
		'desc' => esc_html__('Select ON to enable the category description in the category page.','discy'),
		'id'   => 'category_description',
		'std'  => 'on',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name'      => esc_html__('Category rss enable or disable','discy'),
		'desc'      => esc_html__('Select ON to enable the category rss in the category page.','discy'),
		'id'        => 'category_rss',
		'std'       => 'on',
		'condition' => 'category_description:not(0)',
		'type'      => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Tag description enable or disable','discy'),
		'desc' => esc_html__('Select ON to enable the tag description in the tag page.','discy'),
		'id'   => 'tag_description',
		'std'  => 'on',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name'      => esc_html__('Tag rss enable or disable','discy'),
		'desc'      => esc_html__('Select ON to enable the tag rss in the tag page.','discy'),
		'id'        => 'tag_rss',
		'std'       => 'on',
		'condition' => 'tag_description:not(0)',
		'type'      => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Hide the featured image in the loop','discy'),
		'desc' => esc_html__('Select ON to hide the featured image in the loop.','discy'),
		'id'   => 'featured_image_loop_post',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'id'        => "sort_meta_title_image",
		'condition' => 'post_style:is(style_3)',
		'std'       => array(
						array("value" => "image",'name' => esc_html__('Image','discy'),"default" => "yes"),
						array("value" => "meta_title",'name' => esc_html__('Meta and title','discy'),"default" => "yes"),
					),
		'type'      => "sort",
		'options'   => array(
						array("value" => "image",'name' => esc_html__('Image','discy'),"default" => "yes"),
						array("value" => "meta_title",'name' => esc_html__('Meta and title','discy'),"default" => "yes"),
					)
	);
	
	$options[] = array(
		'name' => esc_html__('Read more enable or disable','discy'),
		'id'   => 'read_more',
		'std'  => 'on',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Excerpt post','discy'),
		'desc' => esc_html__('Put here the excerpt post.','discy'),
		'id'   => 'post_excerpt',
		'std'  => 40,
		'type' => 'text'
	);
	
	$options[] = array(
		'name'    => esc_html__('Pagination style','discy'),
		'desc'    => esc_html__('Choose pagination style from here.','discy'),
		'id'      => 'post_pagination',
		'options' => array(
			'standard'        => esc_html__('Standard','discy'),
			'pagination'      => esc_html__('Pagination','discy'),
			'load_more'       => esc_html__('Load more','discy'),
			'infinite_scroll' => esc_html__('Infinite scroll','discy'),
			'none'            => esc_html__('None','discy'),
		),
		'std'     => 'pagination',
		'type'    => 'radio'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'id'   => 'add_edit_delete_p',
		'name' => esc_html__('Add, edit and delete post','discy')
	);
	
	$options[] = array(
		'name' => esc_html__('Add posts','discy'),
		'type' => 'info'
	);
	
	$options[] = array(
		'name' => esc_html__('Add post slug','discy'),
		'desc' => esc_html__('Put the add post slug.','discy'),
		'id'   => 'add_posts_slug',
		'std'  => 'add-post',
		'type' => 'text'
	);
	
	if (has_wpqa()) {
		$options[] = array(
			'name' => '<a href="'.wpqa_add_post_permalink().'" target="_blank">'.esc_html__('The Link For The Add Post Page.','discy').'</a>',
			'type' => 'info'
		);
	}
	
	$options[] = array(
		'name' => esc_html__('Activate the add post with popup','discy'),
		'desc' => esc_html__('Add post with popup enable or disable.','discy'),
		'id'   => 'active_post_popup',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Any one can add post without register','discy'),
		'desc' => esc_html__('Any one can add post without register enable or disable.','discy'),
		'id'   => 'add_post_no_register',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name'    => esc_html__('Choose post status for users only','discy'),
		'desc'    => esc_html__('Choose post status after user publish the post.','discy'),
		'id'      => 'post_publish',
		'options' => array("publish" => esc_html__("Publish","discy"),"draft" => esc_html__("Draft","discy")),
		'std'     => 'draft',
		'type'    => 'select'
	);
	
	$options[] = array(
		'name'      => esc_html__('Choose post status for "unlogged user" only','discy'),
		'desc'      => esc_html__('Choose post status after "unlogged user" publish the post.','discy'),
		'id'        => 'post_publish_unlogged',
		'options'   => array("publish" => esc_html__("Publish","discy"),"draft" => esc_html__("Draft","discy")),
		'std'       => 'draft',
		'type'      => 'select',
		'condition' => 'add_post_no_register:not(0)',
	);
	
	$options[] = array(
		'name'      => esc_html__('Send mail when the post needs a review','discy'),
		'desc'      => esc_html__('Mail for posts review enable or disable.','discy'),
		'id'        => 'send_email_draft_posts',
		'std'       => 'on',
		'operator'  => 'or',
		'condition' => 'post_publish:not(publish),post_publish_unlogged:not(publish)',
		'type'      => 'checkbox'
	);
	
	$options[] = array(
		'name'      => esc_html__('Auto Approve posts for the users that have a previously approved posts.','discy'),
		'id'        => 'approved_posts',
		'condition' => 'post_publish:not(publish)',
		'type'      => 'checkbox'
	);

	$add_post_items = array(
		"tags_post"      => array("sort" => esc_html__('Post Tags','discy'),"value" => "tags_post"),
		"featured_image" => array("sort" => esc_html__('Post featured image','discy'),"value" => "featured_image"),
		"content_post"   => array("sort" => esc_html__('Post content','discy'),"value" => "content_post"),
		"terms_active"   => array("sort" => esc_html__('Terms of Service and Privacy Policy','discy'),"value" => ""),
	);
	
	$options[] = array(
		'name'    => esc_html__("Select what to show at Add post form","discy"),
		'id'      => 'add_post_items',
		'type'    => 'multicheck',
		'sort'    => 'yes',
		'std'     => $add_post_items,
		'options' => $add_post_items
	);
	
	$options[] = array(
		'name'      => esc_html__('Enable or disable the editor for details in add post form','discy'),
		'id'        => 'editor_post_details',
		'condition' => 'add_post_items:has(content_post)',
		'type'      => 'checkbox'
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'add_post_items:has(terms_active)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'name' => esc_html__('Terms of Service and Privacy Policy','discy'),
		'type' => 'info'
	);
	
	$options[] = array(
		'name' => esc_html__('Select the checked by default option','discy'),
		'desc' => esc_html__('Select ON if you want to checked it by default.','discy'),
		'id'   => 'terms_checked_post',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name'    => esc_html__('Open the page in same page or a new page?','discy'),
		'id'      => 'terms_active_target_post',
		'std'     => "new_page",
		'type'    => 'select',
		'options' => array("same_page" => esc_html__("Same page","discy"),"new_page" => esc_html__("New page","discy"))
	);
	
	$options[] = array(
		'name'    => esc_html__('Terms page','discy'),
		'desc'    => esc_html__('Select the terms page','discy'),
		'id'      => 'terms_page_post',
		'type'    => 'select',
		'options' => $options_pages
	);
	
	$options[] = array(
		'name' => esc_html__("Type the terms link if you don't like a page","discy"),
		'id'   => 'terms_link_post',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('Activate Privacy Policy','discy'),
		'desc' => esc_html__('Select ON if you want to activate Privacy Policy.','discy'),
		'id'   => 'privacy_policy_post',
		'std'  => "on",
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'privacy_policy_post:not(0)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'name'    => esc_html__('Open the page in same page or a new page?','discy'),
		'id'      => 'privacy_active_target_post',
		'std'     => "new_page",
		'type'    => 'select',
		'options' => array("same_page" => esc_html__("Same page","discy"),"new_page" => esc_html__("New page","discy"))
	);
	
	$options[] = array(
		'name'    => esc_html__('Privacy Policy page','discy'),
		'desc'    => esc_html__('Select the privacy policy page','discy'),
		'id'      => 'privacy_page_post',
		'type'    => 'select',
		'options' => $options_pages
	);
	
	$options[] = array(
		'name' => esc_html__("Type the privacy policy link if you don't like a page","discy"),
		'id'   => 'privacy_link_post',
		'type' => 'text'
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
		'name' => esc_html__('Edit posts','discy'),
		'type' => 'info'
	);
	
	$options[] = array(
		'name' => esc_html__('The users can edit the posts?','discy'),
		'id'   => 'can_edit_post',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'can_edit_post:not(0)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'name' => esc_html__('Edit post slug','discy'),
		'desc' => esc_html__('Put the edit post slug.','discy'),
		'id'   => 'edit_posts_slug',
		'std'  => 'edit-post',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('After the edit post, auto approve the post or need to be approved again.','discy'),
		'desc' => esc_html__('Press ON to auto approve','discy'),
		'id'   => 'post_approved',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('After edit post change the URL from the title?','discy'),
		'desc' => esc_html__('Press ON to edit the URL','discy'),
		'id'   => 'change_post_url',
		'std'  => 'on',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name' => esc_html__('Delete posts','discy'),
		'type' => 'info'
	);
	
	$options[] = array(
		'name' => esc_html__('Activate user can delete the posts','discy'),
		'desc' => esc_html__('Select ON if you want the user can delete the posts.','discy'),
		'id'   => 'post_delete',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name'      => esc_html__('When the user deleted a post, trash it or delete it for ever?','discy'),
		'id'        => 'delete_post',
		'options'   => array(
			'delete' => esc_html__('Delete','discy'),
			'trash'  => esc_html__('Trash','discy'),
		),
		'std'       => 'delete',
		'condition' => 'post_delete:not(0)',
		'type'      => 'radio'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name' => esc_html__('Post meta settings','discy'),
		'id'   => 'post_meta',
		'type' => 'heading-2'
	);
	
	$options[] = array(
		'name'    => esc_html__('Select the meta options','discy'),
		'id'      => 'post_meta',
		'type'    => 'multicheck',
		'std'     => array(
			"category_post" => "category_post",
			"title_post"    => "title_post",
			"author_by"     => "author_by",
			"post_date"     => "post_date",
			"post_comment"  => "post_comment",
			"post_views"    => "post_views",
		),
		'options' => array(
			"category_post" => esc_html__('Category post - Work at 1 column only','discy'),
			"title_post"    => esc_html__('Title post','discy'),
			"author_by"     => esc_html__('Author by - Work at 1 column only','discy'),
			"post_date"     => esc_html__('Date meta','discy'),
			"post_comment"  => esc_html__('Comment meta','discy'),
			"post_views"    => esc_html__("Views stats","discy"),
		)
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'id'   => 'inner_pages',
		'name' => esc_html__('Inner page settings','discy')
	);
	
	$order_sections = array(
		"author"        => array("sort" => esc_html__('About the author','discy'),"value" => "author"),
		"next_previous" => array("sort" => esc_html__('Next and Previous articles','discy'),"value" => "next_previous"),
		"advertising"   => array("sort" => esc_html__('Advertising','discy'),"value" => "advertising"),
		"related"       => array("sort" => esc_html__('Related articles','discy'),"value" => "related"),
		"comments"      => array("sort" => esc_html__('Comments','discy'),"value" => "comments"),
	);
	
	$options[] = array(
		'name'    => esc_html__('Sort your sections','discy'),
		'id'      => 'order_sections',
		'type'    => 'multicheck',
		'sort'    => 'yes',
		'std'     => $order_sections,
		'options' => $order_sections
	);
	
	$options[] = array(
		'name' => esc_html__('Hide the featured image in the single post','discy'),
		'desc' => esc_html__('Select ON to hide the featured image in the single post.','discy'),
		'id'   => 'featured_image',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name'    => esc_html__('Featured image style','discy'),
		'desc'    => esc_html__('Featured image style from here.','discy'),
		'id'      => 'featured_image_style',
		'std'     => 'default',
		'options' => array(
			'default' => 'Default',
			'style_270'   => '270x180',
			'style_140'   => '140x140',
			'custom_size' => esc_html__('Custom size','discy'),
		),
		'type'    => 'radio'
	);
	
	$options[] = array(
		'type'      => 'heading-2',
		'condition' => 'featured_image_style:is(custom_size)',
		'div'       => 'div'
	);
		
	$options[] = array(
		'name' => esc_html__('Featured image width','discy'),
		"id"   => "featured_image_width",
		"type" => "sliderui",
		"step" => "1",
		"min"  => "140",
		"max"  => "500"
	);
	
	$options[] = array(
		'name' => esc_html__('Featured image height','discy'),
		"id"   => "featured_image_height",
		"type" => "sliderui",
		"step" => "1",
		"min"  => "140",
		"max"  => "500"
	);
		
		$options[] = array(
			'type' => 'heading-2',
			'end'  => 'end',
			'div'  => 'div'
	);
	
	$options[] = array(
		'name' => esc_html__('Tags enable or disable','discy'),
		'id'   => 'post_tags',
		'std'  => 'on',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name'      => esc_html__('Navigation post for the same category only?','discy'),
		'desc'      => esc_html__('Navigation post (next and previous posts) for the same category only?','discy'),
		'id'        => 'post_nav_category',
		'condition' => 'order_sections:has(next_previous)',
		'std'       => 'on',
		'type'      => 'checkbox'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name' => esc_html__('Share setting','discy'),
		'id'   => 'share_setting',
		'type' => 'heading-2'
	);
	
	$options[] = array(
		'name'    => esc_html__('Select the share options','discy'),
		'id'      => 'post_share',
		'type'    => 'multicheck',
		'sort'    => 'yes',
		'std'     => $share_array,
		'options' => $share_array
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name' => esc_html__('Related setting','discy'),
		'id'   => 'related_setting',
		'type' => 'heading-2'
	);
	
	$options[] = array(
		'name'      => esc_html__('Activate it first from Inner page settings.','discy'),
		'condition' => 'order_sections:has_not(related)',
		'type'      => 'info'
	);
	
	$options[] = array(
		'type'      => 'heading-2',
		'condition' => 'order_sections:has(related)',
		'div'       => 'div'
	);
	
	$options[] = array(
		'name'    => esc_html__('Related style','discy'),
		'desc'    => esc_html__('Type related post style from here.','discy'),
		'id'      => 'related_style',
		'std'     => 'style_1',
		'options' => array(
			'style_1' => 'Style 1',
			'links'   => 'Style 2',
		),
		'type'    => 'radio'
	);
	
	$options[] = array(
		'name' => esc_html__('Related posts number','discy'),
		'desc' => esc_html__('Type the number of related posts from here.','discy'),
		'id'   => 'related_number',
		'std'  => 2,
		'type' => 'text'
	);
	
	$options[] = array(
		'name'      => esc_html__('Related posts number at sidebar','discy'),
		'desc'      => esc_html__('Type related posts number at sidebar from here.','discy'),
		'id'        => 'related_number_sidebar',
		'std'       => 3,
		'condition' => 'related_style:not(links)',
		'type'      => 'text'
	);
	
	$options[] = array(
		'name'      => esc_html__('Related posts number at full width','discy'),
		'desc'      => esc_html__('Type related posts number at full width from here.','discy'),
		'id'        => 'related_number_full',
		'std'       => 4,
		'condition' => 'related_style:not(links)',
		'type'      => 'text'
	);
	
	$options[] = array(
		'name'    => esc_html__('Query type','discy'),
		'desc'    => esc_html__('Select what will the related posts show.','discy'),
		'id'      => 'query_related',
		'std'     => 'categories',
		'options' => array(
			'categories' => esc_html__('Posts in the same categories','discy'),
			'tags'       => esc_html__('Posts in the same tags (If not find any tags will show by the same categories)','discy'),
			'author'     => esc_html__('Posts by the same author','discy'),
		),
		'type'    => 'radio'
	);
	
	$options[] = array(
		'name' => esc_html__('Excerpt title in related posts','discy'),
		'desc' => esc_html__('Type excerpt title in related posts from here.','discy'),
		'id'   => 'excerpt_related_title',
		'std'  => '10',
		'type' => 'text'
	);
	
	$options[] = array(
		'name'      => esc_html__('Comment in related enable or disable','discy'),
		'id'        => 'comment_in_related',
		'std'       => 'on',
		'condition' => 'related_style:not(links)',
		'type'      => 'checkbox'
	);
	
	$options[] = array(
		'name'      => esc_html__('Date in related enable or disable','discy'),
		'id'        => 'date_in_related',
		'std'       => 'on',
		'condition' => 'related_style:not(links)',
		'type'      => 'checkbox'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end',
		'div'  => 'div'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name' => esc_html__('Archive, categories, tags and inner post','discy'),
		'id'   => 'posts_layouts',
		'type' => 'heading-2'
	);
	
	$options[] = array(
		'name' => esc_html__('Post sidebar layout','discy'),
		'id'   => "post_sidebar_layout",
		'std'  => "default",
		'type' => "images",
		'options' => array(
			'default'      => $imagepath.'sidebar_default.jpg',
			'menu_sidebar' => $imagepath.'menu_sidebar.jpg',
			'right'        => $imagepath.'sidebar_right.jpg',
			'full'         => $imagepath.'sidebar_no.jpg',
			'left'         => $imagepath.'sidebar_left.jpg',
			'centered'     => $imagepath.'centered.jpg',
			'menu_left'    => $imagepath.'menu_left.jpg',
		)
	);
	
	$options[] = array(
		'name'      => esc_html__('Post Page sidebar','discy'),
		'id'        => "post_sidebar",
		'options'   => $new_sidebars,
		'type'      => 'select',
		'condition' => 'post_sidebar_layout:not(full),post_sidebar_layout:not(centered),post_sidebar_layout:not(menu_left)'
	);
	
	$options[] = array(
		'name'      => esc_html__('Post Page sidebar 2','discy'),
		'id'        => "post_sidebar_2",
		'options'   => $new_sidebars,
		'type'      => 'select',
		'operator'  => 'or',
		'condition' => 'post_sidebar_layout:is(menu_sidebar),post_sidebar_layout:is(menu_left)'
	);
	
	$options[] = array(
		'name'    => esc_html__('Choose Your Skin','discy'),
		'class'   => "site_skin",
		'id'      => "post_skin",
		'std'     => "default",
		'type'    => "images",
		'options' => array(
			'default'    => $imagepath.'default_color.jpg',
			'skin'       => $imagepath.'default.jpg',
			'violet'     => $imagepath.'violet.jpg',
			'bright_red' => $imagepath.'bright_red.jpg',
			'green'      => $imagepath.'green.jpg',
			'red'        => $imagepath.'red.jpg',
			'cyan'       => $imagepath.'cyan.jpg',
			'blue'       => $imagepath.'blue.jpg',
		)
	);
	
	$options[] = array(
		'name' => esc_html__('Primary Color','discy'),
		'id'   => 'post_primary_color',
		'type' => 'color'
	);
	
	$options[] = array(
		'name'    => esc_html__('Background Type','discy'),
		'id'      => 'post_background_type',
		'std'     => 'default',
		'type'    => 'radio',
		'options' => 
			array(
				"default"           => esc_html__("Default","discy"),
				"none"              => esc_html__("None","discy"),
				"patterns"          => esc_html__("Patterns","discy"),
				"custom_background" => esc_html__("Custom Background","discy")
			)
	);

	$options[] = array(
		'name'      => esc_html__('Background Color','discy'),
		'id'        => 'post_background_color',
		'type'      => 'color',
		'condition' => 'post_background_type:is(patterns)'
	);
		
	$options[] = array(
		'name'      => esc_html__('Choose Pattern','discy'),
		'id'        => "post_background_pattern",
		'std'       => "bg13",
		'type'      => "images",
		'condition' => 'post_background_type:is(patterns)',
		'class'     => "pattern_images",
		'options'   => array(
			'bg1'  => $imagepath.'bg1.jpg',
			'bg2'  => $imagepath.'bg2.jpg',
			'bg3'  => $imagepath.'bg3.jpg',
			'bg4'  => $imagepath.'bg4.jpg',
			'bg5'  => $imagepath.'bg5.jpg',
			'bg6'  => $imagepath.'bg6.jpg',
			'bg7'  => $imagepath.'bg7.jpg',
			'bg8'  => $imagepath.'bg8.jpg',
			'bg9'  => $imagepath_theme.'patterns/bg9.png',
			'bg10' => $imagepath_theme.'patterns/bg10.png',
			'bg11' => $imagepath_theme.'patterns/bg11.png',
			'bg12' => $imagepath_theme.'patterns/bg12.png',
			'bg13' => $imagepath.'bg13.jpg',
			'bg14' => $imagepath.'bg14.jpg',
			'bg15' => $imagepath_theme.'patterns/bg15.png',
			'bg16' => $imagepath_theme.'patterns/bg16.png',
			'bg17' => $imagepath.'bg17.jpg',
			'bg18' => $imagepath.'bg18.jpg',
			'bg19' => $imagepath.'bg19.jpg',
			'bg20' => $imagepath.'bg20.jpg',
			'bg21' => $imagepath_theme.'patterns/bg21.png',
			'bg22' => $imagepath.'bg22.jpg',
			'bg23' => $imagepath_theme.'patterns/bg23.png',
			'bg24' => $imagepath_theme.'patterns/bg24.png',
		)
	);

	$options[] = array(
		'name'      => esc_html__('Custom Background','discy'),
		'id'        => 'post_custom_background',
		'std'       => $background_defaults,
		'type'      => 'background',
		'options'   => $background_defaults,
		'condition' => 'post_background_type:is(custom_background)'
	);
		
	$options[] = array(
		'name'      => esc_html__('Full Screen Background','discy'),
		'desc'      => esc_html__('Select ON to enable Full Screen Background','discy'),
		'id'        => 'post_full_screen_background',
		'type'      => 'checkbox',
		'condition' => 'post_background_type:is(custom_background)'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);
	
	if (has_wpqa()) {
		$questions_settings = array(
			"general_setting"   => esc_html__('General settings','discy'),
			"question_slug"     => esc_html__('Question slugs','discy'),
			"add_edit_delete"   => esc_html__('Add - Edit - Delete','discy'),
			"question_meta"     => esc_html__('Question meta settings','discy'),
			"question_category" => esc_html__('Questions category settings','discy'),
			"questions_loop"    => esc_html__('Questions & Loop settings','discy'),
			"inner_question"    => esc_html__('Inner question','discy'),
			"share_setting_q"   => esc_html__('Share setting','discy'),
			"questions_layout"  => esc_html__('Questions layout','discy')
		);

		$options[] = array(
			'name'    => esc_html__('Question settings','discy'),
			'id'      => 'question',
			'icon'    => 'editor-help',
			'type'    => 'heading',
			'std'     => 'general_setting',
			'options' => apply_filters("discy_questions_settings",$questions_settings)
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'id'   => 'general_setting',
			'name' => esc_html__('General settings','discy')
		);

		$options = apply_filters('discy_options_before_question_general_setting',$options);
		
		$options[] = array(
			'name' => esc_html__('Select ON if you need to choose the question at simple layout','discy'),
			'id'   => 'question_simple',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name'    => esc_html__('Ajax file load from admin or theme','discy'),
			'desc'    => esc_html__('Choose ajax file load from admin or theme.','discy'),
			'id'      => 'ajax_file',
			'std'     => 'admin',
			'type'    => 'select',
			'options' => array("admin" => esc_html__("Admin","discy"),"theme" => esc_html__("Theme","discy"))
		);
		
		$options[] = array(
			'name' => esc_html__('Show filter at categories and archive pages','discy'),
			'desc' => esc_html__('Select ON to enable the filter at categories and archive pages.','discy'),
			'id'   => 'category_filter',
			'std'  => 'on',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name' => esc_html__('Tag description enable or disable','discy'),
			'desc' => esc_html__('Select ON to enable the tag description in the tag page.','discy'),
			'id'   => 'question_tag_description',
			'std'  => 'on',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name'      => esc_html__('Tag rss enable or disable','discy'),
			'desc'      => esc_html__('Select ON to enable the tag rss in the tag page.','discy'),
			'id'        => 'question_tag_rss',
			'std'       => 'on',
			'condition' => 'question_tag_description:not(0)',
			'type'      => 'checkbox'
		);
		
		$options[] = array(
			'name' => esc_html__('Do you need to activate you might like options?','discy'),
			'desc' => esc_html__('Select ON if you want to activate you might like for the questions and answers.','discy'),
			'id'   => 'might_like',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name' => esc_html__('Do you need to show the questions based on the date and answers updated?','discy'),
			'desc' => esc_html__('Select ON if you want to display the questions based on recently added and recent answers added.','discy'),
			'id'   => 'updated_answers',
			'type' => 'checkbox'
		);

		$options[] = array(
			'name'      => esc_html__('After new answer has been added, move this question to the top. It works for the recent questions, feed, and questions for you tabs or pages.','discy'),
			'condition' => 'updated_answers:not(0)',
			'type'      => 'info'
		);
		
		$options[] = array(
			'name' => esc_html__('Do you need to hide the content only for the private question?','discy'),
			'desc' => esc_html__('Select ON if you want to hide the content only for the private question.','discy'),
			'id'   => 'private_question_content',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name' => esc_html__('Activate the best answer for the normal users in site?','discy'),
			'desc' => esc_html__('Best answer enable or disable.','discy'),
			'id'   => 'active_best_answer',
			'std'  => "on",
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name'      => esc_html__('Activate user can choose own answer as the best answer','discy'),
			'desc'      => esc_html__('User can choose own answer as the best answer enable or disable.','discy'),
			'id'        => 'best_answer_userself',
			'std'       => "on",
			'condition' => 'active_best_answer:not(0)',
			'type'      => 'checkbox'
		);
		
		$options[] = array(
			'name' => esc_html__('Activate the points system in site?','discy'),
			'desc' => esc_html__('The points system enable or disable.','discy'),
			'id'   => 'active_points',
			'std'  => "on",
			'type' => 'checkbox'
		);

		$options[] = array(
			'div'       => 'div',
			'condition' => 'active_points:not(0)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name' => esc_html__('Activate the points sort with specific days?','discy'),
			'desc' => esc_html__('The points sort with day, week, month or year enable or disable.','discy'),
			'id'   => 'active_points_specific',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name' => esc_html__('Activate the bump question','discy'),
			'desc' => esc_html__('Select ON if you want the bump question.','discy'),
			'id'   => 'question_bump',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'question_bump:not(0)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name' => esc_html__('Add the points users must add to allow them to bump up the question.','discy'),
			'id'   => 'question_bump_points',
			'std'  => 0,
			'type' => 'text'
		);
		
		$options[] = array(
			'name' => esc_html__('Make the points for the bump question go to the user who has the best answer','discy'),
			'id'   => 'bump_best_answer',
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
			'name' => esc_html__('When the question or answer is deleted, if it has the best answer - remove it from the stats and user points?','discy'),
			'desc' => esc_html__('Select ON if you want to remove the best answer from the user points.','discy'),
			'id'   => 'remove_best_answer_stats',
			'type' => 'checkbox'
		);

		/*
		$options[] = array(
			'name' => esc_html__('Activate the extract link data?','discy'),
			'desc' => esc_html__('The extract link data enable or disable.','discy'),
			'id'   => 'extract_link',
			'std'  => "on",
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name' => esc_html__('Activate the extract link data to save at cache?','discy'),
			'id'   => 'extract_link_cache',
			'std'  => "on",
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name'      => esc_html__("Choose the cache limit for the links.","discy"),
			'id'        => 'extract_link_cache_limit',
			'std'       => 'month',
			'type'      => 'radio',
			'condition' => 'extract_link_cache:not(0)',
			'options'   => 
				array(
					"day"   => esc_html__("Day","discy"),
					"week"  => esc_html__("Week","discy"),
					"month" => esc_html__("Month","discy"),
					"year"  => esc_html__("Year","discy")
			)
		);
		*/
		
		$options[] = array(
			'name' => esc_html__('Activate the mention in site?','discy'),
			'desc' => esc_html__('Activate the mention enable or disable.','discy'),
			'id'   => 'active_mention',
			'std'  => 'on',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name' => esc_html__('Activate the reports in site?','discy'),
			'desc' => esc_html__('Activate the reports enable or disable.','discy'),
			'id'   => 'active_reports',
			'std'  => 'on',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'active_reports:not(0)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name' => esc_html__('Activate the reports in site for the "logged in users" only?','discy'),
			'desc' => esc_html__('Activate the reports in site for the "logged in users" only enable or disable.','discy'),
			'id'   => 'active_logged_reports',
			'std'  => "on",
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'active_points:not(0)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name' => esc_html__('Activate the users having certain points can move the question or answer to trash or draft by reporting.','discy'),
			'id'   => 'active_trash_reports',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'active_trash_reports:not(0)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name'    => esc_html__('Move the question or answer to trash or draft when reported.','discy'),
			'id'      => 'trash_draft_reports',
			'options' => array("trash" => esc_html__("Trash","discy"),"draft" => esc_html__("Draft","discy")),
			'type'    => 'select'
		);
		
		$options[] = array(
			'name' => esc_html__('Add the points to allow the users which will let them move the question or answer to trash or draft when reported.','discy'),
			'id'   => 'trash_reports_points',
			'type' => 'text'
		);
		
		$options[] = array(
			'name' => esc_html__('Add minimum of the points if anyone which have them, their questions or answers will not move to trash or draft.','discy'),
			'id'   => 'reports_min_points',
			'type' => 'text'
		);
		
		$options[] = array(
			'name' => esc_html__('Whitelist questions.','discy'),
			'desc' => esc_html__('Add here the whitelist question, Any questions here will not move to trash or draft.','discy'),
			'id'   => 'whitelist_questions',
			'type' => 'textarea'
		);
		
		$options[] = array(
			'name' => esc_html__('Whitelist answers.','discy'),
			'desc' => esc_html__('Add here the whitelist answers, Any answers here will not move to trash or draft.','discy'),
			'id'   => 'whitelist_answers',
			'type' => 'textarea'
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
			'div'  => 'div',
			'end'  => 'end'
		);
		
		$options[] = array(
			'name' => esc_html__('Activate poll for user only?','discy'),
			'desc' => esc_html__('Select ON if you want to allow poll to users only.','discy'),
			'id'   => 'poll_user_only',
			'type' => 'checkbox'
		);

		$options[] = array(
			'name' => esc_html__('Activate the vote in the site?','discy'),
			'desc' => esc_html__('The vote for questions and answers in the site enable or disable.','discy'),
			'id'   => 'active_vote',
			'std'  => "on",
			'type' => 'checkbox'
		);

		$options[] = array(
			'name'      => esc_html__('Activate the vote in the site for the "unlogged users"?','discy'),
			'desc'      => esc_html__('The vote for questions and answers in the site for the "unlogged users" enable or disable.','discy'),
			'id'        => 'active_vote_unlogged',
			'std'       => "on",
			'type'      => 'checkbox',
			'condition' => 'active_vote:not(0)'
		);
		
		$options[] = array(
			'name' => esc_html__('Activate the pop up at the author image in the site?','discy'),
			'desc' => esc_html__('Pop up at the author image in site enable or disable.','discy'),
			'id'   => 'author_image_pop',
			'std'  => "on",
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name' => esc_html__('Activate the separator for the numbers at the site?','discy'),
			'id'   => 'active_separator',
			'type' => 'checkbox'
		);
	
		$options[] = array(
			'name'      => esc_html__('Number separator','discy'),
			'desc'      => esc_html__('Add your number separator.','discy'),
			'id'        => 'number_separator',
			'std'       => ',',
			'type'      => 'text',
			'condition' => 'active_separator:not(0)'
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'end'  => 'end'
		);
		
		$options[] = array(
			'name' => esc_html__('Question slugs','discy'),
			'id'   => 'question_slug',
			'type' => 'heading-2'
		);
		
		$options[] = array(
			'name' => esc_html__('Questions archive slug','discy'),
			'desc' => esc_html__('Add your questions archive slug.','discy'),
			'id'   => 'archive_question_slug',
			'std'  => 'questions',
			'type' => 'text'
		);

		$options[] = array(
			'name' => esc_html__('Click ON, if you need to remove the question slug and choose "Post name" from WordPress Settings/Permalinks.','discy'),
			'id'   => 'remove_question_slug',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name'      => esc_html__('Question slug','discy'),
			'desc'      => esc_html__('Add your question slug.','discy'),
			'id'        => 'question_slug',
			'std'       => 'question',
			'condition' => 'remove_question_slug:not(on)',
			'type'      => 'text'
		);
		
		$options[] = array(
			'name' => esc_html__('Question category slug','discy'),
			'desc' => esc_html__('Add your question category slug.','discy'),
			'id'   => 'category_question_slug',
			'std'  => 'question-category',
			'type' => 'text'
		);
		
		$options[] = array(
			'name' => esc_html__('Question tag slug','discy'),
			'desc' => esc_html__('Add your question tag slug.','discy'),
			'id'   => 'tag_question_slug',
			'std'  => 'question-tag',
			'type' => 'text'
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'end'  => 'end'
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'id'   => 'add_edit_delete',
			'name' => esc_html__('Add, edit and delete question','discy')
		);
		
		$options[] = array(
			'name' => esc_html__('Any one can ask question without register','discy'),
			'desc' => esc_html__('Any one can ask question without register enable or disable.','discy'),
			'id'   => 'ask_question_no_register',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name' => esc_html__('Charge points for question settings','discy'),
			'desc' => esc_html__('Select ON if you want to charge points from users for asking questions.','discy'),
			'id'   => 'question_points_active',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'question_points_active:not(0)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name' => esc_html__('Charge points for questions','discy'),
			'desc' => esc_html__("How many points should be taken from the user's account for asking questions.","discy"),
			'id'   => 'question_points',
			'std'  => '5',
			'type' => 'text'
		);
		
		$options[] = array(
			'name' => esc_html__('Point back to the user when they select the best answer','discy'),
			'id'   => 'point_back',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name'      => esc_html__('Or type here the point the user should get back','discy'),
			'desc'      => esc_html__('Or type here the point user should get back. Type 0 to return all the points.','discy'),
			'id'        => 'point_back_number',
			'condition' => 'point_back:not(0)',
			'std'       => '0',
			'type'      => 'text'
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);
		
		$options[] = array(
			'name'    => esc_html__('Choose question status for users only','discy'),
			'desc'    => esc_html__('Choose question status after the user publishes the question.','discy'),
			'id'      => 'question_publish',
			'options' => array("publish" => esc_html__("Publish","discy"),"draft" => esc_html__("Draft","discy")),
			'std'     => 'publish',
			'type'    => 'select'
		);
		
		$options[] = array(
			'name'      => esc_html__('Choose question status for "unlogged user" only','discy'),
			'desc'      => esc_html__('Choose question status after "unlogged user" publish the question.','discy'),
			'id'        => 'question_publish_unlogged',
			'options'   => array("publish" => esc_html__("Publish","discy"),"draft" => esc_html__("Draft","discy")),
			'std'       => 'draft',
			'type'      => 'select',
			'condition' => 'ask_question_no_register:not(0)',
		);
		
		$options[] = array(
			'name'      => esc_html__('Send mail when the question needs a review','discy'),
			'desc'      => esc_html__('Mail for questions review enable or disable.','discy'),
			'id'        => 'send_email_draft_questions',
			'std'       => 'on',
			'operator'  => 'or',
			'condition' => 'question_publish:not(publish),question_publish_unlogged:not(publish)',
			'type'      => 'checkbox'
		);
		
		$options[] = array(
			'name'      => esc_html__('Auto approve for the users who have a previously approved question.','discy'),
			'id'        => 'approved_questions',
			'condition' => 'question_publish:not(publish)',
			'type'      => 'checkbox'
		);
		
		$options[] = array(
			'name' => esc_html__('Send schedule mails for the users as a list with recent questions','discy'),
			'id'   => 'question_schedules',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'question_schedules:not(0)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name'    => esc_html__('Schedule mails time','discy'),
			'id'      => 'question_schedules_time',
			'type'    => 'multicheck',
			'std'     => array("daily" => "daily","weekly" => "weekly","monthly" => "monthly"),
			'options' => array("daily" => esc_html__("Daily","discy"),"weekly" => esc_html__("Weekly","discy"),"monthly" => esc_html__("Monthly","discy"))
		);

		$options[] = array(
			"name" => esc_html__("Set the hour to send the mail at this hour","discy"),
			"id"   => "schedules_time_hour",
			"type" => "sliderui",
			'std'  => 12,
			"step" => "1",
			"min"  => "1",
			"max"  => "24"
		);

		$options[] = array(
			'name'    => esc_html__('Select the day to send the mail at this day','discy'),
			'id'      => 'schedules_time_day',
			'type'    => "select",
			'std'     => "saturday",
			'options' => array(
				'saturday'  => esc_html__('Saturday','discy'),
				'sunday'    => esc_html__('Sunday','discy'),
				'monday'    => esc_html__('Monday','discy'),
				'tuesday'   => esc_html__('Tuesday','discy'),
				'wednesday' => esc_html__('Wednesday','discy'),
				'thursday'  => esc_html__('Thursday','discy'),
				'friday'    => esc_html__('Friday','discy')
			)
		);
		
		$options[] = array(
			'name'    => esc_html__('Send schedule mails for custom roles to send a list with recent questions','discy'),
			'id'      => 'question_schedules_groups',
			'type'    => 'multicheck',
			'std'     => array("editor" => "editor","administrator" => "administrator","author" => "author","contributor" => "contributor","subscriber" => "subscriber"),
			'options' => discy_options_roles()
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);
		
		$options[] = array(
			'name' => esc_html__('Send mail to the users about the notification of a new question','discy'),
			'desc' => esc_html__('Send mail enable or disable.','discy'),
			'id'   => 'send_email_new_question',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name'      => esc_html__('Send mail for custom roles about the notification of a new question','discy'),
			'id'        => 'send_email_question_groups',
			'type'      => 'multicheck',
			'condition' => 'send_email_new_question:not(0)',
			'std'       => array("editor" => "editor","administrator" => "administrator","author" => "author","contributor" => "contributor","subscriber" => "subscriber"),
			'options'   => discy_options_roles()
		);
		
		$options[] = array(
			'name' => esc_html__('Ask questions','discy'),
			'type' => 'info'
		);
		
		$options[] = array(
			'name' => esc_html__('Make the ask question form works with popup','discy'),
			'desc' => esc_html__('Select ON if you want to make the ask question form works with popup.','discy'),
			'id'   => 'ask_question_popup',
			'std'  => 'on',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name' => esc_html__('Ask question slug','discy'),
			'desc' => esc_html__('Put the ask question slug.','discy'),
			'id'   => 'add_questions_slug',
			'std'  => 'add-question',
			'type' => 'text'
		
		);
		
		if (has_wpqa()) {
			$options[] = array(
				'name' => '<a href="'.wpqa_add_question_permalink().'" target="_blank">'.esc_html__('Link For The Ask Question Page.','discy').'</a>',
				'type' => 'info'
			);
		}

		$options = apply_filters('discy_options_after_question_link',$options);
		
		$ask_question_items = array(
			"title_question"       => array("sort" => esc_html__('Question Title','discy'),"value" => "title_question"),
			"categories_question"  => array("sort" => esc_html__('Question Categories','discy'),"value" => "categories_question"),
			"tags_question"        => array("sort" => esc_html__('Question Tags','discy'),"value" => "tags_question"),
			"poll_question"        => array("sort" => esc_html__('Question Poll','discy'),"value" => "poll_question"),
			"attachment_question"  => array("sort" => esc_html__('Question Attachment','discy'),"value" => "attachment_question"),
			"featured_image"       => array("sort" => esc_html__('Featured image','discy'),"value" => "featured_image"),
			"comment_question"     => array("sort" => esc_html__('Question content','discy'),"value" => "comment_question"),
			"anonymously_question" => array("sort" => esc_html__('Ask Anonymously','discy'),"value" => "anonymously_question"),
			"video_desc_active"    => array("sort" => esc_html__('Video Description','discy'),"value" => "video_desc_active"),
			"private_question"     => array("sort" => esc_html__('Private Question','discy'),"value" => "private_question"),
			"remember_answer"      => array("sort" => esc_html__('Remember Answer','discy'),"value" => "remember_answer"),
			"terms_active"         => array("sort" => esc_html__('Terms of Service and Privacy Policy','discy'),"value" => "terms_active"),
		);
		
		$ask_question_items_std = $ask_question_items;
		unset($ask_question_items_std["attachment_question"]);
		
		$options[] = array(
			'name'    => esc_html__("Select what to show at ask question form","discy"),
			'id'      => 'ask_question_items',
			'type'    => 'multicheck',
			'sort'    => 'yes',
			'std'     => $ask_question_items_std,
			'options' => $ask_question_items
		);
		
		$options[] = array(
			'name'      => esc_html__('Activate suggested questions in the title when user is typing the question','discy'),
			'id'        => 'suggest_questions',
			'condition' => 'ask_question_items:has(title_question)',
			'type'      => 'checkbox'
		);

		$options[] = array(
			'div'       => 'div',
			'condition' => 'ask_question_items:has_not(title_question)',
			'type'      => 'heading-2'
		);

		$options[] = array(
			'name'    => esc_html__('Excerpt type for title from the content','discy'),
			'desc'    => esc_html__('Choose form here the excerpt type.','discy'),
			'id'      => 'title_excerpt_type',
			'type'    => "select",
			'options' => array(
				'words'      => esc_html__('Words','discy'),
				'characters' => esc_html__('Characters','discy')
			)
		);

		$options[] = array(
			'name' => esc_html__('Excerpt title from the content','discy'),
			'desc' => esc_html__('Put here the excerpt title from the content.','discy'),
			'id'   => 'title_excerpt',
			'std'  => 10,
			'type' => 'text'
		);

		$options[] = array(
			'type' => 'heading-2',
			'end'  => 'end',
			'div'  => 'div'
		);

		$options[] = array(
			'name'    => esc_html__('Select the checked by default options at ask a new question','discy'),
			'id'      => 'add_question_default',
			'type'    => 'multicheck',
			'std'     => array(
				"notified" => "notified",
			),
			'options' => array(
				"poll"        => esc_html__('Poll','discy'),
				"video"       => esc_html__('Video','discy'),
				"notified"    => esc_html__('Notified','discy'),
				"private"     => esc_html__("Private question","discy"),
				"anonymously" => esc_html__("Ask anonymously","discy"),
				"terms"       => esc_html__("Terms","discy"),
				"sticky"      => esc_html__("Sticky","discy"),
			)
		);
		
		$options[] = array(
			'name'      => esc_html__("Category at ask question form single, multi, ajax 1 or ajax 2","discy"),
			'desc'      => esc_html__("Choose how category is shown at ask question form single, multi or ajax","discy"),
			'id'        => 'category_single_multi',
			'std'       => 'single',
			'type'      => 'radio',
			'condition' => 'ask_question_items:has(categories_question)',
			'options'   => 
				array(
					"single" => "Single",
					"multi"  => "Multi",
					"ajax"   => "Ajax 1",
					"ajax_2" => "Ajax 2"
			)
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'ask_question_items:has(poll_question)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name' => esc_html__('Poll setting','discy'),
			'type' => 'info'
		);
		
		$options[] = array(
			'name' => esc_html__('Activate the poll for specific roles','discy'),
			'id'   => 'custom_poll_groups',
			'type' => 'checkbox'
		);

		$options[] = array(
			'name'      => esc_html__("Choose the roles to allow them to add poll.","discy"),
			'id'        => 'poll_groups',
			'condition' => 'custom_poll_groups:not(0)',
			'type'      => 'multicheck',
			'options'   => $new_roles
		);
		
		$options[] = array(
			'name' => esc_html__('Activate image in the poll','discy'),
			'id'   => 'poll_image',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'poll_image:not(0)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name' => esc_html__('Activate the title in the poll images','discy'),
			'id'   => 'poll_image_title',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name'      => esc_html__('Make the title in the poll images required','discy'),
			'id'        => 'poll_image_title_required',
			'condition' => 'poll_image_title:not(0)',
			'type'      => 'checkbox'
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
			'div'       => 'div',
			'condition' => 'ask_question_items:has(comment_question)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name' => esc_html__('Question content setting','discy'),
			'type' => 'info'
		);
		
		$options[] = array(
			'name' => esc_html__('Details in ask question form is required','discy'),
			'id'   => 'comment_question',
			'std'  => "on",
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name' => esc_html__('Enable or disable the editor for details in ask question form','discy'),
			'id'   => 'editor_question_details',
			'std'  => "on",
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'ask_question_items:has(terms_active)',
			'type'      => 'heading-2'
		);

		$options[] = array(
			'name' => esc_html__('Terms of Service and Privacy Policy','discy'),
			'type' => 'info'
		);
		
		$options[] = array(
			'name'    => esc_html__('Open the page in same page or a new page?','discy'),
			'id'      => 'terms_active_target',
			'std'     => "new_page",
			'type'    => 'select',
			'options' => array("same_page" => esc_html__("Same page","discy"),"new_page" => esc_html__("New page","discy"))
		);
		
		$options[] = array(
			'name'    => esc_html__('Terms page','discy'),
			'desc'    => esc_html__('Select the terms page','discy'),
			'id'      => 'terms_page',
			'type'    => 'select',
			'options' => $options_pages
		);
		
		$options[] = array(
			'name' => esc_html__("Type the terms link if you don't like a page","discy"),
			'id'   => 'terms_link',
			'type' => 'text'
		);
		
		$options[] = array(
			'name' => esc_html__('Activate Privacy Policy','discy'),
			'desc' => esc_html__('Select ON if you want to activate Privacy Policy.','discy'),
			'id'   => 'privacy_policy',
			'std'  => "on",
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'privacy_policy:not(0)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name'    => esc_html__('Open the page in same page or a new page?','discy'),
			'id'      => 'privacy_active_target',
			'std'     => "new_page",
			'type'    => 'select',
			'options' => array("same_page" => esc_html__("Same page","discy"),"new_page" => esc_html__("New page","discy"))
		);
		
		$options[] = array(
			'name'    => esc_html__('Privacy Policy page','discy'),
			'desc'    => esc_html__('Select the privacy policy page','discy'),
			'id'      => 'privacy_page',
			'type'    => 'select',
			'options' => $options_pages
		);
		
		$options[] = array(
			'name' => esc_html__("Type the privacy policy link if you don't like a page","discy"),
			'id'   => 'privacy_link',
			'type' => 'text'
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
			'div'       => 'div',
			'condition' => 'ask_question_items:has(title_question)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name' => esc_html__('Limitations for title','discy'),
			'type' => 'info'
		);

		$options[] = array(
			'name' => esc_html__('Add minimum limit for the number of letters for the question title, like 15, 20, if you leave it empty, it will be not important','discy'),
			'id'   => 'question_title_min_limit',
			'type' => 'text'
		);
		
		$options[] = array(
			'name' => esc_html__('Add limit for the number of letters for the question title, like 140, 200, if you leave it empty, it will be unlimited','discy'),
			'id'   => 'question_title_limit',
			'type' => 'text'
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'ask_question_items:has(tags_question)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name' => esc_html__('Limitations for tags','discy'),
			'type' => 'info'
		);

		$options[] = array(
			'name' => esc_html__('Add minimum limit for the number of letters for the question tag word, like 15, 20, if you leave it empty it will be not important','discy'),
			'id'   => 'question_tags_min_limit',
			'type' => 'text'
		);
		
		$options[] = array(
			'name' => esc_html__('Add word limit for the number of letters for the question tag, like 140, 200, if you leave it empty will be unlimited','discy'),
			'id'   => 'question_tags_limit',
			'type' => 'text'
		);

		$options[] = array(
			'name' => esc_html__('Add minimum limit for the number of items for the question tags, like 2, 4, if you leave it empty will be not important','discy'),
			'id'   => 'question_tags_number_min_limit',
			'type' => 'text'
		);
		
		$options[] = array(
			'name' => esc_html__('Add limit for the number of items for the question tags, like 4, 6, if you leave it empty it will be unlimited','discy'),
			'id'   => 'question_tags_number_limit',
			'type' => 'text'
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'ask_question_items:has(poll_question)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name' => esc_html__('Limitations for poll','discy'),
			'type' => 'info'
		);

		$options[] = array(
			'name' => esc_html__('Add limit for the number of letters for the question poll title, like 140, 200, if you leave it empty it will be unlimited','discy'),
			'id'   => 'question_poll_min_limit',
			'type' => 'text'
		);
		
		$options[] = array(
			'name' => esc_html__('Add limit for the number of letters for the question poll title, like 140, 200, if you leave it empty it will be unlimited','discy'),
			'id'   => 'question_poll_limit',
			'type' => 'text'
		);

		$options[] = array(
			'name' => esc_html__('Add minimum limit for the number of items for the question poll title, like 2, 4, if you leave it empty it will be not important','discy'),
			'id'   => 'question_poll_number_min_limit',
			'type' => 'text'
		);
		
		$options[] = array(
			'name' => esc_html__('Add limit for the number of items for the question poll title, like 4, 6, if you leave it empty it will be unlimited','discy'),
			'id'   => 'question_poll_number_limit',
			'type' => 'text'
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'ask_question_items:has(comment_question)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name' => esc_html__('Limitations for content','discy'),
			'type' => 'info'
		);

		$options[] = array(
			'name' => esc_html__('Add minimum limit for the number of letters for the question content, like 15, 20, if you leave it empty it will be not important','discy'),
			'id'   => 'question_content_min_limit',
			'type' => 'text'
		);
		
		$options[] = array(
			'name' => esc_html__('Add limit for the number of letters for the question content, like 140, 200, if you leave it empty it will be unlimited','discy'),
			'id'   => 'question_content_limit',
			'type' => 'text'
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);
		
		$options[] = array(
			'name' => esc_html__('Edit questions','discy'),
			'type' => 'info'
		);
		
		$options[] = array(
			'name' => esc_html__('Activate user can edit the questions','discy'),
			'desc' => esc_html__('Select ON if you want the user to be able to edit the questions.','discy'),
			'id'   => 'question_edit',
			'std'  => "on",
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'question_edit:not(0)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name' => esc_html__('Edit question slug','discy'),
			'desc' => esc_html__('Put the edit question slug.','discy'),
			'id'   => 'edit_questions_slug',
			'std'  => 'edit-question',
			'type' => 'text'
		);
		
		$options[] = array(
			'name' => esc_html__('After edit auto approve question or need to be approved again?','discy'),
			'desc' => esc_html__('Press ON to auto approve','discy'),
			'id'   => 'question_approved',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name' => esc_html__('After the question is edited change the URL from the title?','discy'),
			'desc' => esc_html__('Press ON to edit the URL','discy'),
			'id'   => 'change_question_url',
			'std'  => 'on',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);
		
		$options[] = array(
			'name' => esc_html__('Delete questions','discy'),
			'type' => 'info'
		);
		
		$options[] = array(
			'name' => esc_html__('Activate user can delete the questions','discy'),
			'desc' => esc_html__('Select ON if you want the user to be able to delete the questions.','discy'),
			'id'   => 'question_delete',
			'std'  => "on",
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name'      => esc_html__('When the users delete the question send to the trash or delete it forever?','discy'),
			'id'        => 'delete_question',
			'options'   => array(
				'delete' => esc_html__('Delete','discy'),
				'trash'  => esc_html__('Trash','discy'),
			),
			'std'       => 'delete',
			'condition' => 'question_delete:not(0)',
			'type'      => 'radio'
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'end'  => 'end'
		);
		
		$options[] = array(
			'name' => esc_html__('Question meta settings','discy'),
			'id'   => 'question_meta',
			'type' => 'heading-2'
		);
		
		$options[] = array(
			'name' => esc_html__('Select ON if you want to activate the vote with meta.','discy'),
			'id'   => 'question_meta_vote',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name' => esc_html__('Select ON if you want icons only at the question meta.','discy'),
			'id'   => 'question_meta_icon',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name'    => esc_html__('Select the meta options','discy'),
			'id'      => 'question_meta',
			'type'    => 'multicheck',
			'std'     => array(
				"author_by"         => "author_by",
				"question_date"     => "question_date",
				"asked_to"          => "asked_to",
				"category_question" => "category_question",
				"question_answer"   => "question_answer",
				"question_views"    => "question_views",
				"bump_meta"         => "bump_meta",
			),
			'options' => array(
				"author_by"         => esc_html__('Author by','discy'),
				"question_date"     => esc_html__('Date meta','discy'),
				"asked_to"          => esc_html__('Asked to meta','discy'),
				"category_question" => esc_html__('Category question','discy'),
				"question_answer"   => esc_html__('Answer meta','discy'),
				"question_views"    => esc_html__('Views stats','discy'),
				"bump_meta"         => esc_html__('Bump question meta','discy'),
			)
		);
		
		$options[] = array(
			'name' => esc_html__('Activate user can add the question to favorites','discy'),
			'desc' => esc_html__('Select ON if you want the user can add the questions to favorites.','discy'),
			'id'   => 'question_favorite',
			'std'  => "on",
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name' => esc_html__('Activate user can follow the questions','discy'),
			'desc' => esc_html__('Select ON if you want the user can follow the questions.','discy'),
			'id'   => 'question_follow',
			'std'  => "on",
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name' => esc_html__('Activate the follow button at questions loop','discy'),
			'desc' => esc_html__('Select ON if you want to activate the follow button at questions loop.','discy'),
			'id'   => 'question_follow_loop',
			'type' => 'checkbox'
		);

		$options = apply_filters('discy_options_after_question_follow_loop',$options);
		
		$options[] = array(
			'type' => 'heading-2',
			'end'  => 'end'
		);
		
		$options[] = array(
			'name' => esc_html__('Questions category settings','discy'),
			'id'   => 'question_category',
			'type' => 'heading-2'
		);

		$options[] = array(
			'name' => esc_html__('Category description enable or disable','discy'),
			'desc' => esc_html__('Select ON to enable the category description in the category page.','discy'),
			'id'   => 'question_category_description',
			'std'  => 'on',
			'type' => 'checkbox'
		);

		$options[] = array(
			'name'      => esc_html__('Category rss enable or disable','discy'),
			'desc'      => esc_html__('Select ON to enable the category rss in the category page.','discy'),
			'id'        => 'question_category_rss',
			'std'       => 'on',
			'condition' => 'question_category_description:not(0)',
			'type'      => 'checkbox'
		);

		$options[] = array(
			'name' => esc_html__('Activate the points by category?','discy'),
			'desc' => esc_html__('The points for categories enable or disable.','discy'),
			'id'   => 'active_points_category',
			'type' => 'checkbox'
		);

		$options[] = array(
			'name' => esc_html__('Activate the follow for categories and tags?','discy'),
			'desc' => esc_html__('Follow for categories and tags enable or disable.','discy'),
			'id'   => 'follow_category',
			'std'  => 'on',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name'    => esc_html__('Categories style at home and search pages','discy'),
			'desc'    => esc_html__('Choose the categories style.','discy'),
			'id'      => 'cat_style_pages',
			'options' => array(
				"with_icon"     => esc_html__("With icon","discy"),
				"icon_color"    => esc_html__("With icon and colors","discy"),
				'with_icon_1'   => esc_html__('With icon 2','discy'),
				'with_icon_2'   => esc_html__('With colored icon','discy'),
				'with_icon_3'   => esc_html__('With colored icon and box','discy'),
				'with_icon_4'   => esc_html__('With colored icon and box 2','discy'),
				'with_cover_1'  => esc_html__('With cover','discy'),
				'with_cover_2'  => esc_html__('With cover and icon','discy'),
				'with_cover_3'  => esc_html__('With cover and small icon','discy'),
				'with_cover_4'  => esc_html__('With big cover','discy'),
				'with_cover_5'  => esc_html__('With big cover and icon','discy'),
				'with_cover_6'  => esc_html__('With big cover and small icon','discy'),
				'simple_follow' => esc_html__('Simple with follow','discy'),
				'simple'        => esc_html__('Simple','discy'),
			),
			'std'     => 'simple_follow',
			'type'    => 'radio'
		);
		
		$options[] = array(
			'name' => esc_html__('Request a new category','discy'),
			'type' => 'info'
		);

		$options[] = array(
			'name' => esc_html__('Activate the users to request a new category','discy'),
			'id'   => 'allow_user_to_add_category',
			'std'  => 'on',
			'type' => 'checkbox'
		);

		$options[] = array(
			'div'       => 'div',
			'condition' => 'allow_user_to_add_category:not(0)',
			'type'      => 'heading-2'
		);

		$options[] = array(
			'name' => esc_html__('Activate the unlogged users to request a new category.','discy'),
			'id'   => 'add_category_no_register',
			'type' => 'checkbox'
		);

		$options[] = array(
			'name' => esc_html__('Add category slug','discy'),
			'desc' => esc_html__('Put the add category slug.','discy'),
			'id'   => 'add_category_slug',
			'std'  => 'add-category',
			'type' => 'text'
		);

		if (has_wpqa()) {
			$options[] = array(
				'name' => '<a href="'.wpqa_add_category_permalink().'" target="_blank">'.esc_html__('The Link For The Add Category Page.','discy').'</a>',
				'type' => 'info'
			);
		}

		$options[] = array(
			'name' => esc_html__('Send mail when the category needs a review','discy'),
			'desc' => esc_html__('Mail for category review enable or disable.','discy'),
			'id'   => 'send_email_add_category',
			'std'  => 'on',
			'type' => 'checkbox'
		);

		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);
		
		$options[] = array(
			'name' => esc_html__('Category cover','discy'),
			'type' => 'info'
		);

		$options[] = array(
			'name' => esc_html__('Activate the cover for categories?','discy'),
			'desc' => esc_html__('Cover for categories enable or disable.','discy'),
			'id'   => 'active_cover_category',
			'type' => 'checkbox'
		);

		$options[] = array(
			'div'       => 'div',
			'condition' => 'active_cover_category:not(0)',
			'type'      => 'heading-2'
		);
	
		$options[] = array(
			'name'    => esc_html__('Cover full width or fixed','discy'),
			'desc'    => esc_html__('Choose the cover to make it work with full width or fixed.','discy'),
			'id'      => 'cover_category_fixed',
			'options' => array(
				'normal' => esc_html__('Full width','discy'),
				'fixed'  => esc_html__('Fixed','discy'),
			),
			'std'     => 'normal',
			'type'    => 'radio'
		);
		
		$options[] = array(
			'name'    => esc_html__('Select the share options','discy'),
			'id'      => 'cat_share',
			'type'    => 'multicheck',
			'sort'    => 'yes',
			'std'     => $share_array,
			'options' => $share_array
		);

		$options[] = array(
			'name' => esc_html__('Default cover enable or disable.','discy'),
			'desc' => esc_html__("Select ON to upload your default cover for the categories which doesn't have cover.","discy"),
			'id'   => 'default_cover_cat_active',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name'      => esc_html__('Upload default cover for the categories.','discy'),
			'id'        => 'default_cover_cat',
			'condition' => 'default_cover_cat_active:not(0)',
			'type'      => 'upload'
		);

		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);
		
		$options[] = array(
			'name' => esc_html__('Category tabs','discy'),
			'type' => 'info'
		);

		$options[] = array(
			'name' => esc_html__('Activate the tabs for questions categories?','discy'),
			'desc' => esc_html__('The tabs for questions categories enable or disable.','discy'),
			'id'   => 'tabs_category',
			'type' => 'checkbox'
		);

		$options[] = array(
			'div'       => 'div',
			'condition' => 'tabs_category:not(0)',
			'type'      => 'heading-2'
		);

		$options[] = array(
			'name' => esc_html__('Put here the exclude categories ids','discy'),
			'id'   => 'exclude_categories',
			'type' => 'text'
		);

		$category_tabs = array(
			"recent-questions"   => array("sort" => esc_html__('Recent Questions','discy'),"value" => "recent-questions"),
			"most-answers"       => array("sort" => esc_html__('Most Answered','discy'),"value" => "most-answers"),
			"answers"            => array("sort" => esc_html__('Answers','discy'),"value" => "answers"),
			"no-answers"         => array("sort" => esc_html__('No Answers','discy'),"value" => "no-answers"),
			"most-visit"         => array("sort" => esc_html__('Most Visited','discy'),"value" => "most-visit"),
			"most-vote"          => array("sort" => esc_html__('Most Voted','discy'),"value" => "most-vote"),
			"random"             => array("sort" => esc_html__('Random Questions','discy'),"value" => "random"),
			"question-bump"      => array("sort" => esc_html__('Bump Question','discy'),"value" => ""),
			"new-questions"      => array("sort" => esc_html__('New Questions','discy'),"value" => ""),
			"sticky-questions"   => array("sort" => esc_html__('Sticky Questions','discy'),"value" => ""),
			"polls"              => array("sort" => esc_html__('Poll Questions','discy'),"value" => ""),
			"followed"           => array("sort" => esc_html__('Followed Questions','discy'),"value" => ""),
			"favorites"          => array("sort" => esc_html__('Favorites Questions','discy'),"value" => ""),
			
			"recent-questions-2" => array("sort" => esc_html__('Recent Questions With Time','discy'),"value" => ""),
			"most-answers-2"     => array("sort" => esc_html__('Most Answered With Time','discy'),"value" => ""),
			"answers-2"          => array("sort" => esc_html__('Answers With Time','discy'),"value" => ""),
			"no-answers-2"       => array("sort" => esc_html__('No Answers With Time','discy'),"value" => ""),
			"most-visit-2"       => array("sort" => esc_html__('Most Visited With Time','discy'),"value" => ""),
			"most-vote-2"        => array("sort" => esc_html__('Most Voted With Time','discy'),"value" => ""),
			"random-2"           => array("sort" => esc_html__('Random Questions With Time','discy'),"value" => ""),
			"question-bump-2"    => array("sort" => esc_html__('Bump Question With Time','discy'),"value" => ""),
			"new-questions-2"    => array("sort" => esc_html__('New Questions With Time','discy'),"value" => ""),
			"sticky-questions-2" => array("sort" => esc_html__('Sticky Questions With Time','discy'),"value" => ""),
			"polls-2"            => array("sort" => esc_html__('Poll Questions With Time','discy'),"value" => ""),
			"followed-2"         => array("sort" => esc_html__('Followed Questions With Time','discy'),"value" => ""),
			"favorites-2"        => array("sort" => esc_html__('Favorites Questions With Time','discy'),"value" => ""),
		);

		$options[] = array(
			'name'    => esc_html__('Select the tabs you want to show','discy'),
			'id'      => 'category_tabs',
			'type'    => 'multicheck',
			'sort'    => 'yes',
			'std'     => $category_tabs,
			'options' => $category_tabs
		);

		$options[] = array(
			'div'       => 'div',
			'condition' => 'category_tabs:has(recent-questions-2),category_tabs:has(most-answers-2),category_tabs:has(question-bump-2),category_tabs:has(new-questions-2),category_tabs:has(sticky-questions-2),category_tabs:has(polls-2),category_tabs:has(followed-2),category_tabs:has(favorites-2),category_tabs:has(answers-2),category_tabs:has(most-visit-2),category_tabs:has(most-vote-2),category_tabs:has(random-2),category_tabs:has(no-answers-2)',
			'operator'  => 'or',
			'type'      => 'heading-2'
		);

		$options[] = array(
			'name'      => esc_html__('Order by','discy'),
			'desc'      => esc_html__('Select the answers order by.','discy'),
			'id'        => "orderby_answers",
			'std'       => "recent",
			'condition' => 'category_tabs:has(answers)',
			'type'      => "radio",
			'options'   => array(
				'recent' => esc_html__('Recent','discy'),
				'oldest' => esc_html__('Oldest','discy'),
				'votes'  => esc_html__('Voted','discy'),
			)
		);

		$options[] = array(
			'type' => 'info',
			'name' => esc_html__('Time frame for the tabs','discy')
		);

		$options[] = array(
			'name'      => esc_html__('Specific date for recent questions tab.','discy'),
			'desc'      => esc_html__('Select the specific date for recent questions tab.','discy'),
			'id'        => "date_recent_questions",
			'std'       => "all",
			'type'      => "radio",
			'condition' => 'category_tabs:has(recent-questions-2)',
			'options'   => array(
				'all'   => esc_html__('All The Time','discy'),
				'24'    => esc_html__('Last 24 Hours','discy'),
				'48'    => esc_html__('Last 2 Days','discy'),
				'72'    => esc_html__('Last 3 Days','discy'),
				'96'    => esc_html__('Last 4 Days','discy'),
				'120'   => esc_html__('Last 5 Days','discy'),
				'144'   => esc_html__('Last 6 Days','discy'),
				'week'  => esc_html__('Last Week','discy'),
				'month' => esc_html__('Last Month','discy'),
				'year'  => esc_html__('Last Year','discy'),
			)
		);

		$options[] = array(
			'name'      => esc_html__('Specific date for most answered tab.','discy'),
			'desc'      => esc_html__('Select the specific date for most answered tab.','discy'),
			'id'        => "date_most_answered",
			'std'       => "all",
			'type'      => "radio",
			'condition' => 'category_tabs:has(most-answers-2)',
			'options'   => array(
				'all'   => esc_html__('All The Time','discy'),
				'24'    => esc_html__('Last 24 Hours','discy'),
				'48'    => esc_html__('Last 2 Days','discy'),
				'72'    => esc_html__('Last 3 Days','discy'),
				'96'    => esc_html__('Last 4 Days','discy'),
				'120'   => esc_html__('Last 5 Days','discy'),
				'144'   => esc_html__('Last 6 Days','discy'),
				'week'  => esc_html__('Last Week','discy'),
				'month' => esc_html__('Last Month','discy'),
				'year'  => esc_html__('Last Year','discy'),
			)
		);

		$options[] = array(
			'name'      => esc_html__('Specific date for bump question tab.','discy'),
			'desc'      => esc_html__('Select the specific date for bump question tab.','discy'),
			'id'        => "date_question_bump",
			'std'       => "all",
			'type'      => "radio",
			'condition' => 'category_tabs:has(question-bump-2)',
			'options'   => array(
				'all'   => esc_html__('All The Time','discy'),
				'24'    => esc_html__('Last 24 Hours','discy'),
				'48'    => esc_html__('Last 2 Days','discy'),
				'72'    => esc_html__('Last 3 Days','discy'),
				'96'    => esc_html__('Last 4 Days','discy'),
				'120'   => esc_html__('Last 5 Days','discy'),
				'144'   => esc_html__('Last 6 Days','discy'),
				'week'  => esc_html__('Last Week','discy'),
				'month' => esc_html__('Last Month','discy'),
				'year'  => esc_html__('Last Year','discy'),
			)
		);

		$options[] = array(
			'name'      => esc_html__('Specific date for answers tab.','discy'),
			'desc'      => esc_html__('Select the specific date for answers tab.','discy'),
			'id'        => "date_answers",
			'std'       => "all",
			'type'      => "radio",
			'condition' => 'category_tabs:has(answers-2)',
			'options'   => array(
				'all'   => esc_html__('All The Time','discy'),
				'24'    => esc_html__('Last 24 Hours','discy'),
				'48'    => esc_html__('Last 2 Days','discy'),
				'72'    => esc_html__('Last 3 Days','discy'),
				'96'    => esc_html__('Last 4 Days','discy'),
				'120'   => esc_html__('Last 5 Days','discy'),
				'144'   => esc_html__('Last 6 Days','discy'),
				'week'  => esc_html__('Last Week','discy'),
				'month' => esc_html__('Last Month','discy'),
				'year'  => esc_html__('Last Year','discy'),
			)
		);

		$options[] = array(
			'name'      => esc_html__('Specific date for most visited tab.','discy'),
			'desc'      => esc_html__('Select the specific date for most visited tab.','discy'),
			'id'        => "date_most_visited",
			'std'       => "all",
			'type'      => "radio",
			'condition' => 'category_tabs:has(most-visit-2)',
			'options'   => array(
				'all'   => esc_html__('All The Time','discy'),
				'24'    => esc_html__('Last 24 Hours','discy'),
				'48'    => esc_html__('Last 2 Days','discy'),
				'72'    => esc_html__('Last 3 Days','discy'),
				'96'    => esc_html__('Last 4 Days','discy'),
				'120'   => esc_html__('Last 5 Days','discy'),
				'144'   => esc_html__('Last 6 Days','discy'),
				'week'  => esc_html__('Last Week','discy'),
				'month' => esc_html__('Last Month','discy'),
				'year'  => esc_html__('Last Year','discy'),
			)
		);

		$options[] = array(
			'name'      => esc_html__('Specific date for most voted tab.','discy'),
			'desc'      => esc_html__('Select the specific date for most voted tab.','discy'),
			'id'        => "date_most_voted",
			'std'       => "all",
			'type'      => "radio",
			'condition' => 'category_tabs:has(most-vote-2)',
			'options'   => array(
				'all'   => esc_html__('All The Time','discy'),
				'24'    => esc_html__('Last 24 Hours','discy'),
				'48'    => esc_html__('Last 2 Days','discy'),
				'72'    => esc_html__('Last 3 Days','discy'),
				'96'    => esc_html__('Last 4 Days','discy'),
				'120'   => esc_html__('Last 5 Days','discy'),
				'144'   => esc_html__('Last 6 Days','discy'),
				'week'  => esc_html__('Last Week','discy'),
				'month' => esc_html__('Last Month','discy'),
				'year'  => esc_html__('Last Year','discy'),
			)
		);

		$options[] = array(
			'name'      => esc_html__('Specific date for no answers tab.','discy'),
			'desc'      => esc_html__('Select the specific date for no answers tab.','discy'),
			'id'        => "date_no_answers",
			'std'       => "all",
			'type'      => "radio",
			'condition' => 'category_tabs:has(no-answers-2)',
			'options'   => array(
				'all'   => esc_html__('All The Time','discy'),
				'24'    => esc_html__('Last 24 Hours','discy'),
				'48'    => esc_html__('Last 2 Days','discy'),
				'72'    => esc_html__('Last 3 Days','discy'),
				'96'    => esc_html__('Last 4 Days','discy'),
				'120'   => esc_html__('Last 5 Days','discy'),
				'144'   => esc_html__('Last 6 Days','discy'),
				'week'  => esc_html__('Last Week','discy'),
				'month' => esc_html__('Last Month','discy'),
				'year'  => esc_html__('Last Year','discy'),
			)
		);

		$options[] = array(
			'name'      => esc_html__('Specific date for random questions tab.','discy'),
			'desc'      => esc_html__('Select the specific date for random questions tab.','discy'),
			'id'        => "date_random_questions",
			'std'       => "all",
			'type'      => "radio",
			'condition' => 'category_tabs:has(random-2)',
			'options'   => array(
				'all'   => esc_html__('All The Time','discy'),
				'24'    => esc_html__('Last 24 Hours','discy'),
				'48'    => esc_html__('Last 2 Days','discy'),
				'72'    => esc_html__('Last 3 Days','discy'),
				'96'    => esc_html__('Last 4 Days','discy'),
				'120'   => esc_html__('Last 5 Days','discy'),
				'144'   => esc_html__('Last 6 Days','discy'),
				'week'  => esc_html__('Last Week','discy'),
				'month' => esc_html__('Last Month','discy'),
				'year'  => esc_html__('Last Year','discy'),
			)
		);

		$options[] = array(
			'name'      => esc_html__('Specific date for new questions tab.','discy'),
			'desc'      => esc_html__('Select the specific date for new questions tab.','discy'),
			'id'        => "date_new_questions",
			'std'       => "all",
			'type'      => "radio",
			'condition' => 'category_tabs:has(new-questions-2)',
			'options'   => array(
				'all'   => esc_html__('All The Time','discy'),
				'24'    => esc_html__('Last 24 Hours','discy'),
				'48'    => esc_html__('Last 2 Days','discy'),
				'72'    => esc_html__('Last 3 Days','discy'),
				'96'    => esc_html__('Last 4 Days','discy'),
				'120'   => esc_html__('Last 5 Days','discy'),
				'144'   => esc_html__('Last 6 Days','discy'),
				'week'  => esc_html__('Last Week','discy'),
				'month' => esc_html__('Last Month','discy'),
				'year'  => esc_html__('Last Year','discy'),
			)
		);

		$options[] = array(
			'name'      => esc_html__('Specific date for sticky questions tab.','discy'),
			'desc'      => esc_html__('Select the specific date for sticky questions tab.','discy'),
			'id'        => "date_sticky_questions",
			'std'       => "all",
			'type'      => "radio",
			'condition' => 'category_tabs:has(sticky-questions-2)',
			'options'   => array(
				'all'   => esc_html__('All The Time','discy'),
				'24'    => esc_html__('Last 24 Hours','discy'),
				'48'    => esc_html__('Last 2 Days','discy'),
				'72'    => esc_html__('Last 3 Days','discy'),
				'96'    => esc_html__('Last 4 Days','discy'),
				'120'   => esc_html__('Last 5 Days','discy'),
				'144'   => esc_html__('Last 6 Days','discy'),
				'week'  => esc_html__('Last Week','discy'),
				'month' => esc_html__('Last Month','discy'),
				'year'  => esc_html__('Last Year','discy'),
			)
		);

		$options[] = array(
			'name'      => esc_html__('Specific date for poll questions tab.','discy'),
			'desc'      => esc_html__('Select the specific date for poll questions tab.','discy'),
			'id'        => "date_poll_questions",
			'std'       => "all",
			'type'      => "radio",
			'condition' => 'category_tabs:has(polls-2)',
			'options'   => array(
				'all'   => esc_html__('All The Time','discy'),
				'24'    => esc_html__('Last 24 Hours','discy'),
				'48'    => esc_html__('Last 2 Days','discy'),
				'72'    => esc_html__('Last 3 Days','discy'),
				'96'    => esc_html__('Last 4 Days','discy'),
				'120'   => esc_html__('Last 5 Days','discy'),
				'144'   => esc_html__('Last 6 Days','discy'),
				'week'  => esc_html__('Last Week','discy'),
				'month' => esc_html__('Last Month','discy'),
				'year'  => esc_html__('Last Year','discy'),
			)
		);

		$options[] = array(
			'name'      => esc_html__('Specific date for followed questions tab.','discy'),
			'desc'      => esc_html__('Select the specific date for followed questions tab.','discy'),
			'id'        => "date_followed_questions",
			'std'       => "all",
			'type'      => "radio",
			'condition' => 'category_tabs:has(followed-2)',
			'options'   => array(
				'all'   => esc_html__('All The Time','discy'),
				'24'    => esc_html__('Last 24 Hours','discy'),
				'48'    => esc_html__('Last 2 Days','discy'),
				'72'    => esc_html__('Last 3 Days','discy'),
				'96'    => esc_html__('Last 4 Days','discy'),
				'120'   => esc_html__('Last 5 Days','discy'),
				'144'   => esc_html__('Last 6 Days','discy'),
				'week'  => esc_html__('Last Week','discy'),
				'month' => esc_html__('Last Month','discy'),
				'year'  => esc_html__('Last Year','discy'),
			)
		);

		$options[] = array(
			'name'      => esc_html__('Specific date for favorites questions tab.','discy'),
			'desc'      => esc_html__('Select the specific date for favorites questions tab.','discy'),
			'id'        => "date_favorites_questions",
			'std'       => "all",
			'type'      => "radio",
			'condition' => 'category_tabs:has(favorites-2)',
			'options'   => array(
				'all'   => esc_html__('All The Time','discy'),
				'24'    => esc_html__('Last 24 Hours','discy'),
				'48'    => esc_html__('Last 2 Days','discy'),
				'72'    => esc_html__('Last 3 Days','discy'),
				'96'    => esc_html__('Last 4 Days','discy'),
				'120'   => esc_html__('Last 5 Days','discy'),
				'144'   => esc_html__('Last 6 Days','discy'),
				'week'  => esc_html__('Last Week','discy'),
				'month' => esc_html__('Last Month','discy'),
				'year'  => esc_html__('Last Year','discy'),
			)
		);

		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);

		$options[] = array(
			'div'       => 'div',
			'condition' => 'category_tabs:has(recent-questions),category_tabs:has(most-answers),category_tabs:has(question-bump),category_tabs:has(new-questions),category_tabs:has(sticky-questions),category_tabs:has(polls),category_tabs:has(followed),category_tabs:has(favorites),category_tabs:has(answers),category_tabs:has(most-visit),category_tabs:has(most-vote),category_tabs:has(random),category_tabs:has(no-answers),category_tabs:has(recent-questions-2),category_tabs:has(most-answers-2),category_tabs:has(question-bump-2),category_tabs:has(new-questions-2),category_tabs:has(sticky-questions-2),category_tabs:has(polls-2),category_tabs:has(followed-2),category_tabs:has(favorites-2),category_tabs:has(answers-2),category_tabs:has(most-visit-2),category_tabs:has(most-vote-2),category_tabs:has(random-2),category_tabs:has(no-answers-2)',
			'operator'  => 'or',
			'type'      => 'heading-2'
		);

		$options[] = array(
			'type' => 'info',
			'name' => esc_html__('Custom setting for the slugs','discy')
		);

		$options[] = array(
			'name'      => esc_html__('Recent questions slug','discy'),
			'id'        => 'recent_questions_slug',
			'std'       => 'recent-questions',
			'condition' => 'category_tabs:has(recent-questions)',
			'type'      => 'text'
		);

		$options[] = array(
			'name'      => esc_html__('Most answered slug','discy'),
			'id'        => 'most_answers_slug',
			'std'       => 'most-answered',
			'condition' => 'category_tabs:has(most-answers)',
			'type'      => 'text'
		);

		$options[] = array(
			'name'      => esc_html__('Bump question slug','discy'),
			'id'        => 'question_bump_slug',
			'std'       => 'question-bump',
			'condition' => 'category_tabs:has(question-bump)',
			'type'      => 'text'
		);

		$options[] = array(
			'name'      => esc_html__('New questions slug','discy'),
			'id'        => 'question_new_slug',
			'std'       => 'new',
			'condition' => 'category_tabs:has(new-questions)',
			'type'      => 'text'
		);

		$options[] = array(
			'name'      => esc_html__('Question sticky slug','discy'),
			'id'        => 'question_sticky_slug',
			'std'       => 'sticky',
			'condition' => 'category_tabs:has(sticky-questions)',
			'type'      => 'text'
		);

		$options[] = array(
			'name'      => esc_html__('Question polls slug','discy'),
			'id'        => 'question_polls_slug',
			'std'       => 'polls',
			'condition' => 'category_tabs:has(polls)',
			'type'      => 'text'
		);

		$options[] = array(
			'name'      => esc_html__('Question followed slug','discy'),
			'id'        => 'question_followed_slug',
			'std'       => 'followed',
			'condition' => 'category_tabs:has(followed)',
			'type'      => 'text'
		);

		$options[] = array(
			'name'      => esc_html__('Question favorites slug','discy'),
			'id'        => 'question_favorites_slug',
			'std'       => 'favorites',
			'condition' => 'category_tabs:has(favorites)',
			'type'      => 'text'
		);

		$options[] = array(
			'name'      => esc_html__('Answers slug','discy'),
			'id'        => 'category_answers_slug',
			'std'       => 'answers',
			'condition' => 'category_tabs:has(answers)',
			'type'      => 'text'
		);

		$options[] = array(
			'name'      => esc_html__('Most visited slug','discy'),
			'id'        => 'most_visit_slug',
			'std'       => 'most-visited',
			'condition' => 'category_tabs:has(most-visit)',
			'type'      => 'text'
		);

		$options[] = array(
			'name'      => esc_html__('Most voted slug','discy'),
			'id'        => 'most_vote_slug',
			'std'       => 'most-voted',
			'condition' => 'category_tabs:has(most-vote)',
			'type'      => 'text'
		);

		$options[] = array(
			'name'      => esc_html__('Random slug','discy'),
			'id'        => 'random_slug',
			'std'       => 'random',
			'condition' => 'category_tabs:has(random)',
			'type'      => 'text'
		);

		$options[] = array(
			'name'      => esc_html__('No answers slug','discy'),
			'id'        => 'no_answers_slug',
			'std'       => 'no-answers',
			'condition' => 'category_tabs:has(no-answers)',
			'type'      => 'text'
		);

		$options[] = array(
			'name'      => esc_html__('Recent questions with time slug','discy'),
			'id'        => 'recent_questions_slug_2',
			'std'       => 'recent-questions-time',
			'condition' => 'category_tabs:has(recent-questions-2)',
			'type'      => 'text'
		);

		$options[] = array(
			'name'      => esc_html__('Most answered with time slug','discy'),
			'id'        => 'most_answers_slug_2',
			'std'       => 'most-answered-time',
			'condition' => 'category_tabs:has(most-answers-2)',
			'type'      => 'text'
		);

		$options[] = array(
			'name'      => esc_html__('Bump question with time slug','discy'),
			'id'        => 'question_bump_slug_2',
			'std'       => 'question-bump-time',
			'condition' => 'category_tabs:has(question-bump-2)',
			'type'      => 'text'
		);

		$options[] = array(
			'name'      => esc_html__('New questions with time slug','discy'),
			'id'        => 'question_new_slug_2',
			'std'       => 'new-time',
			'condition' => 'category_tabs:has(new-questions-2)',
			'type'      => 'text'
		);

		$options[] = array(
			'name'      => esc_html__('Question sticky with time slug','discy'),
			'id'        => 'question_sticky_slug_2',
			'std'       => 'sticky-time',
			'condition' => 'category_tabs:has(sticky-questions-2)',
			'type'      => 'text'
		);

		$options[] = array(
			'name'      => esc_html__('Question polls with time slug','discy'),
			'id'        => 'question_polls_slug_2',
			'std'       => 'polls-time',
			'condition' => 'category_tabs:has(polls-2)',
			'type'      => 'text'
		);

		$options[] = array(
			'name'      => esc_html__('Question followed with time slug','discy'),
			'id'        => 'question_followed_slug_2',
			'std'       => 'followed-time',
			'condition' => 'category_tabs:has(followed-2)',
			'type'      => 'text'
		);

		$options[] = array(
			'name'      => esc_html__('Question favorites with time slug','discy'),
			'id'        => 'question_favorites_slug_2',
			'std'       => 'favorites-time',
			'condition' => 'category_tabs:has(favorites-2)',
			'type'      => 'text'
		);

		$options[] = array(
			'name'      => esc_html__('Answers with time slug','discy'),
			'id'        => 'answers_slug_2',
			'std'       => 'answers-time',
			'condition' => 'category_tabs:has(answers-2)',
			'type'      => 'text'
		);

		$options[] = array(
			'name'      => esc_html__('Most visited with time slug','discy'),
			'id'        => 'most_visit_slug_2',
			'std'       => 'most-visited-time',
			'condition' => 'category_tabs:has(most-visit-2)',
			'type'      => 'text'
		);

		$options[] = array(
			'name'      => esc_html__('Most voted with time slug','discy'),
			'id'        => 'most_vote_slug_2',
			'std'       => 'most-voted-time',
			'condition' => 'category_tabs:has(most-vote-2)',
			'type'      => 'text'
		);

		$options[] = array(
			'name'      => esc_html__('Random with time slug','discy'),
			'id'        => 'random_slug_2',
			'std'       => 'random-time',
			'condition' => 'category_tabs:has(random-2)',
			'type'      => 'text'
		);

		$options[] = array(
			'name'      => esc_html__('No answers with time slug','discy'),
			'id'        => 'no_answers_slug_2',
			'std'       => 'no-answers-time',
			'condition' => 'category_tabs:has(no-answers-2)',
			'type'      => 'text'
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
			'type' => 'heading-2',
			'id'   => 'questions_loop',
			'name' => esc_html__('Questions & Loop settings','discy')
		);
		
		$options[] = array(
			'name'      => esc_html__('Columns in the archive, taxonomy and tags pages','discy'),
			'id'		=> "question_columns",
			'type'		=> 'radio',
			'options'	=> array(
				'style_1' => esc_html__('1 column','discy'),
				'style_2' => esc_html__('2 columns','discy')." - ".esc_html__('Works with sidebar, full width, and left menu only.','discy'),
			),
			'std'		=> 'style_1'
		);
		
		$options[] = array(
			'name'      => esc_html__("Activate the masonry style?","discy"),
			'id'        => 'masonry_style',
			'type'      => 'checkbox',
			'condition' => 'question_columns:is(style_2)',
		);
		
		$options[] = array(
			'name' => esc_html__('Activate the author image in questions loop?','discy'),
			'desc' => esc_html__('Enable or disable author image in questions loop?','discy'),
			'id'   => 'author_image',
			'std'  => "on",
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name' => esc_html__('Activate the vote in loop?','discy'),
			'desc' => esc_html__('Enable or disable vote in loop?','discy'),
			'id'   => 'vote_question_loop',
			'std'  => "on",
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name'      => esc_html__('Select ON to hide the dislike at questions loop','discy'),
			'desc'      => esc_html__('If you put it ON the dislike will not show.','discy'),
			'id'        => 'question_loop_dislike',
			'condition' => 'vote_question_loop:not(0)',
			'type'      => 'checkbox'
		);
		
		$options[] = array(
			'name' => esc_html__('Select ON to show the poll in questions loop','discy'),
			'id'   => 'question_poll_loop',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name' => esc_html__('Select ON to hide the excerpt in questions','discy'),
			'id'   => 'excerpt_questions',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'excerpt_questions:is(0)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name' => esc_html__('Excerpt question','discy'),
			'desc' => esc_html__('Put here the excerpt question.','discy'),
			'id'   => 'question_excerpt',
			'std'  => 40,
			'type' => 'text'
		);
		
		$options[] = array(
			'name' => esc_html__('Select ON to active the read more button in questions','discy'),
			'id'   => 'read_more_question',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name'      => esc_html__('Select ON to activate the read more by jQuery in questions','discy'),
			'id'        => 'read_jquery_question',
			'type'      => 'checkbox',
			'condition' => 'read_more_question:not(0)',
		);
		
		$options[] = array(
			'name' => esc_html__('Select ON to activate to see some answers and add a new answer by jQuery in questions','discy'),
			'id'   => 'answer_question_jquery',
			'type' => 'checkbox',
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);

		$options[] = array(
			'name' => esc_html__('Video','discy'),
			'type' => 'info'
		);
		
		$options[] = array(
			'name' => esc_html__('Video description settings at the question loop','discy'),
			'desc' => esc_html__('Select ON if you want to let users to add video with their question.','discy'),
			'id'   => 'video_desc_active_loop',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'video_desc_active_loop:not(0)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name'    => esc_html__('Video description position at the question loop','discy'),
			'desc'    => esc_html__('Choose the video description position.','discy'),
			'id'      => 'video_desc_loop',
			'options' => array("before" => "Before content","after" => "After content"),
			'std'     => 'after',
			'type'    => 'select'
		);
		
		$options[] = array(
			'name' => esc_html__('Set the video description to 100%?','discy'),
			'desc' => esc_html__('Select ON if you want to set the video description to 100%.','discy'),
			'id'   => 'video_desc_100_loop',
			'std'  => "on",
			'type' => 'checkbox'
		);
		
		$options[] = array(
			"name"      => esc_html__("Set the width for the video description for the questions","discy"),
			"id"        => "video_description_width",
			'condition' => 'video_desc_100_loop:not(on)',
			"type"      => "sliderui",
			'std'       => 260,
			"step"      => "1",
			"min"       => "50",
			"max"       => "600"
		);
		
		$options[] = array(
			"name" => esc_html__("Set the height for the video description for the questions","discy"),
			"id"   => "video_description_height",
			"type" => "sliderui",
			'std'  => 500,
			"step" => "1",
			"min"  => "50",
			"max"  => "600"
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'end'  => 'end',
			'div'  => 'div'
		);

		$options[] = array(
			'name' => esc_html__('Featured image','discy'),
			'type' => 'info'
		);
		
		$options[] = array(
			'name' => esc_html__('Select ON to show featured image in the questions','discy'),
			'id'   => 'featured_image_loop',
			'std'  => 'on',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'featured_image_loop:not(0)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name' => esc_html__('Select ON to enable the lightbox for featured image','discy'),
			'id'   => 'featured_image_question_lightbox',
			'std'  => "on",
			'type' => 'checkbox'
		);
		
		$options[] = array(
			"name" => esc_html__("Set the width for the featured image for the questions","discy"),
			"id"   => "featured_image_question_width",
			"type" => "sliderui",
			'std'  => 260,
			"step" => "1",
			"min"  => "50",
			"max"  => "600"
		);
		
		$options[] = array(
			"name" => esc_html__("Set the height for the featured image for the questions","discy"),
			"id"   => "featured_image_question_height",
			"type" => "sliderui",
			'std'  => 185,
			"step" => "1",
			"min"  => "50",
			"max"  => "600"
		);
		
		$options[] = array(
			'name'    => esc_html__('Featured image position','discy'),
			'desc'    => esc_html__('Choose the featured image position.','discy'),
			'id'      => 'featured_position',
			'options' => array("before" => "Before content","after" => "After content"),
			'std'     => 'before',
			'type'    => 'select'
		);
		
		$options[] = array(
			'name'      => esc_html__('Poll position','discy'),
			'desc'      => esc_html__('Choose the poll position.','discy'),
			'id'        => 'poll_position',
			'condition' => 'featured_position:not(after)',
			'options'   => array("before" => "Before featured image","after" => "After featured image"),
			'std'       => 'before',
			'type'      => 'select'
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'end'  => 'end',
			'div'  => 'div'
		);
		
		$options[] = array(
			'name' => esc_html__('Enable or disable Tags at loop?','discy'),
			'id'   => 'question_tags_loop',
			'std'  => 'on',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name' => esc_html__('Activate the answer at the loop by best answer, most voted, last answer or first answer','discy'),
			'id'   => 'question_answer_loop',
			'type' => 'checkbox'
		);

		$options[] = array(
			'div'       => 'div',
			'condition' => 'question_answer_loop:not(0)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name'    => esc_html__('Answer type','discy'),
			'desc'    => esc_html__("Choose what's the answer you need to show from here.","discy"),
			'id'      => 'question_answer_show',
			'options' => array(
				'best'   => esc_html__('Best answer','discy'),
				'vote'   => esc_html__('Most voted','discy'),
				'last'   => esc_html__('Last answer','discy'),
				'oldest' => esc_html__('First answer','discy'),
			),
			'std'     => 'best',
			'type'    => 'radio'
		);

		$options[] = array(
			'name'    => esc_html__('Answer place','discy'),
			'desc'    => esc_html__("Choose where's the answer to be placed - before or after question meta.","discy"),
			'id'      => 'question_answer_place',
			'options' => array(
				'before' => esc_html__('Before question meta','discy'),
				'after'  => esc_html__('After question meta','discy'),
			),
			'std'     => 'before',
			'type'    => 'radio'
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);
		
		$options[] = array(
			'name'    => esc_html__('Pagination style','discy'),
			'desc'    => esc_html__('Choose pagination style from here.','discy'),
			'id'      => 'question_pagination',
			'options' => array(
				'standard'        => esc_html__('Standard','discy'),
				'pagination'      => esc_html__('Pagination','discy'),
				'load_more'       => esc_html__('Load more','discy'),
				'infinite_scroll' => esc_html__('Infinite scroll','discy'),
				'none'            => esc_html__('None','discy'),
			),
			'std'     => 'pagination',
			'type'    => 'radio'
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'end'  => 'end'
		);
		
		$options[] = array(
			'name' => esc_html__('Inner question','discy'),
			'id'   => 'inner_question',
			'type' => 'heading-2'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'ask_question_items:has(video_desc_active)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name'    => esc_html__('video description position','discy'),
			'desc'    => esc_html__('Choose the video description position.','discy'),
			'id'      => 'video_desc',
			'options' => array("before" => esc_html__("Before content","discy"),"after" => esc_html__("After content","discy")),
			'std'     => 'after',
			'type'    => 'select'
		);
		
		$options[] = array(
			"name" => esc_html__("Set the height for the video description for the questions","discy"),
			"id"   => "video_desc_height",
			"type" => "sliderui",
			'std'  => 500,
			"step" => "1",
			"min"  => "50",
			"max"  => "600"
		);

		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);
		
		$options[] = array(
			'name' => esc_html__('Select ON to show featured image in the single question','discy'),
			'id'   => 'featured_image_single',
			'std'  => 'on',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'featured_image_single:not(0)',
			'type'      => 'heading-2'
		);

		$options[] = array(
			"name" => esc_html__("Set the width for the featured image for the questions","discy"),
			"id"   => "featured_image_inner_question_width",
			"type" => "sliderui",
			'std'  => 260,
			"step" => "1",
			"min"  => "50",
			"max"  => "600"
		);
		
		$options[] = array(
			"name" => esc_html__("Set the height for the featured image for the questions","discy"),
			"id"   => "featured_image_inner_question_height",
			"type" => "sliderui",
			'std'  => 185,
			"step" => "1",
			"min"  => "50",
			"max"  => "600"
		);

		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);
		
		$options[] = array(
			'name' => esc_html__('Activate the author image in single?','discy'),
			'desc' => esc_html__('Author image in single enable or disable.','discy'),
			'id'   => 'author_image_single',
			'std'  => "on",
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name' => esc_html__('Activate the vote in single?','discy'),
			'desc' => esc_html__('Vote in single enable or disable.','discy'),
			'id'   => 'vote_question_single',
			'std'  => "on",
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name'      => esc_html__('Select ON to hide the dislike at questions single','discy'),
			'desc'      => esc_html__('If you put it ON the dislike will not show.','discy'),
			'id'        => 'question_single_dislike',
			'condition' => 'vote_question_single:not(0)',
			'type'      => 'checkbox'
		);
		
		$options[] = array(
			'name' => esc_html__('Activate close and open questions','discy'),
			'desc' => esc_html__('Select ON if you want activate close and open questions.','discy'),
			'id'   => 'question_close',
			'std'  => "on",
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name' => esc_html__('Activate close and open questions for the admin only','discy'),
			'desc' => esc_html__('Select ON if you want activate close and open questions for the admin only.','discy'),
			'id'   => 'question_close_admin',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name'      => esc_html__('Share style at the inner question page.','discy'),
			'id'        => 'share_style',
			'std'       => 'style_1',
			'type'      => 'radio',
			'condition' => 'question_simple:not(on)',
			'options'   => 
				array(
					"style_1" => esc_html__("Style 1","discy"),
					"style_2" => esc_html__("Style 2","discy"),
				)
		);
		
		$options[] = array(
			'name' => esc_html__('Tags at single question enable or disable','discy'),
			'desc' => esc_html__('Select ON if you want active tags at single question.','discy'),
			'id'   => 'question_tags',
			'std'  => 'on',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name' => esc_html__('Navigation question enable or disable','discy'),
			'desc' => esc_html__('Navigation question (next and previous questions) enable or disable.','discy'),
			'id'   => 'question_navigation',
			'std'  => 'on',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name'      => esc_html__('Navigation question for the same category only?','discy'),
			'desc'      => esc_html__('Navigation question (next and previous questions) for the same category only?','discy'),
			'id'        => 'question_nav_category',
			'condition' => 'question_navigation:not(0)',
			'std'       => 'on',
			'type'      => 'checkbox'
		);
		
		$options[] = array(
			'name' => esc_html__('Answers enable or disable','discy'),
			'desc' => esc_html__('Select ON if you want activate the answers.','discy'),
			'id'   => 'question_answers',
			'std'  => 'on',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name' => esc_html__('Related questions','discy'),
			'type' => 'info'
		);
		
		$options[] = array(
			'name' => esc_html__('Related questions after content enable or disable','discy'),
			'desc' => esc_html__('Select ON if you want to activate the related questions after the content.','discy'),
			'id'   => 'question_related',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'question_related:not(0)',
			'type'      => 'heading-2'
		);

		$options[] = array(
			'name' => esc_html__('Number of items to show','discy'),
			'id'   => 'related_number_question',
			'type' => 'text',
			'std'  => '5'
		);
		
		$options[] = array(
			'name'    => esc_html__('Query type','discy'),
			'id'      => 'query_related_question',
			'options' => array(
				'categories' => esc_html__('Questions in the same categories','discy'),
				'tags'       => esc_html__('Questions in the same tags (If not found, questions with the same categories will be shown)','discy'),
				'author'     => esc_html__('Questions by the same author','discy'),
			),
			'std'     => 'categories',
			'type'    => 'radio'
		);
		
		$options[] = array(
			'name' => esc_html__('Excerpt title in related questions','discy'),
			'desc' => esc_html__('Type excerpt title in related questions from here.','discy'),
			'id'   => 'related_title_question',
			'std'  => '20',
			'type' => 'text'
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
			'name' => esc_html__('Share setting','discy'),
			'id'   => 'share_setting_q',
			'type' => 'heading-2'
		);
		
		$options[] = array(
			'name'    => esc_html__('Select the share options','discy'),
			'id'      => 'question_share',
			'type'    => 'multicheck',
			'sort'    => 'yes',
			'std'     => $share_array,
			'options' => $share_array
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'end'  => 'end'
		);
		
		$options[] = array(
			'name' => esc_html__('Questions layout','discy'),
			'id'   => 'questions_layout',
			'type' => 'heading-2'
		);
		
		$options[] = array(
			'name' => esc_html__('Question sidebar layout','discy'),
			'id'   => "question_sidebar_layout",
			'std'  => "default",
			'type' => "images",
			'options' => array(
				'default'      => $imagepath.'sidebar_default.jpg',
				'menu_sidebar' => $imagepath.'menu_sidebar.jpg',
				'right'        => $imagepath.'sidebar_right.jpg',
				'full'         => $imagepath.'sidebar_no.jpg',
				'left'         => $imagepath.'sidebar_left.jpg',
				'centered'     => $imagepath.'centered.jpg',
				'menu_left'    => $imagepath.'menu_left.jpg',
			)
		);
		
		$options[] = array(
			'name'      => esc_html__('Question Page sidebar','discy'),
			'id'        => "question_sidebar",
			'std'       => '',
			'options'   => $new_sidebars,
			'type'      => 'select',
			'condition' => 'question_sidebar_layout:not(full),question_sidebar_layout:not(centered),question_sidebar_layout:not(menu_left)'
		);
		
		$options[] = array(
			'name'      => esc_html__('Question Page sidebar 2','discy'),
			'id'        => "question_sidebar_2",
			'std'       => '',
			'options'   => $new_sidebars,
			'type'      => 'select',
			'operator'  => 'or',
			'condition' => 'question_sidebar_layout:is(menu_sidebar),question_sidebar_layout:is(menu_left)'
		);
		
		$options[] = array(
			'name'    => esc_html__('Choose Your Skin','discy'),
			'class'   => "site_skin",
			'id'      => "question_skin",
			'std'     => "default",
			'type'    => "images",
			'options' => array(
				'default'    => $imagepath.'default_color.jpg',
				'skin'       => $imagepath.'default.jpg',
				'violet'     => $imagepath.'violet.jpg',
				'bright_red' => $imagepath.'bright_red.jpg',
				'green'      => $imagepath.'green.jpg',
				'red'        => $imagepath.'red.jpg',
				'cyan'       => $imagepath.'cyan.jpg',
				'blue'       => $imagepath.'blue.jpg',
			)
		);
		
		$options[] = array(
			'name' => esc_html__('Primary Color','discy'),
			'id'   => 'question_primary_color',
			'type' => 'color'
		);
		
		$options[] = array(
			'name'    => esc_html__('Background Type','discy'),
			'id'      => 'question_background_type',
			'std'     => 'default',
			'type'    => 'radio',
			'options' => 
				array(
					"default"           => esc_html__("Default","discy"),
					"none"              => esc_html__("None","discy"),
					"patterns"          => esc_html__("Patterns","discy"),
					"custom_background" => esc_html__("Custom Background","discy")
				)
		);
	
		$options[] = array(
			'name'      => esc_html__('Background Color','discy'),
			'id'        => 'question_background_color',
			'type'      => 'color',
			'condition' => 'question_background_type:is(patterns)'
		);
			
		$options[] = array(
			'name'      => esc_html__('Choose Pattern','discy'),
			'id'        => "question_background_pattern",
			'std'       => "bg13",
			'type'      => "images",
			'condition' => 'question_background_type:is(patterns)',
			'class'     => "pattern_images",
			'options'   => array(
				'bg1'  => $imagepath.'bg1.jpg',
				'bg2'  => $imagepath.'bg2.jpg',
				'bg3'  => $imagepath.'bg3.jpg',
				'bg4'  => $imagepath.'bg4.jpg',
				'bg5'  => $imagepath.'bg5.jpg',
				'bg6'  => $imagepath.'bg6.jpg',
				'bg7'  => $imagepath.'bg7.jpg',
				'bg8'  => $imagepath.'bg8.jpg',
				'bg9'  => $imagepath_theme.'patterns/bg9.png',
				'bg10' => $imagepath_theme.'patterns/bg10.png',
				'bg11' => $imagepath_theme.'patterns/bg11.png',
				'bg12' => $imagepath_theme.'patterns/bg12.png',
				'bg13' => $imagepath.'bg13.jpg',
				'bg14' => $imagepath.'bg14.jpg',
				'bg15' => $imagepath_theme.'patterns/bg15.png',
				'bg16' => $imagepath_theme.'patterns/bg16.png',
				'bg17' => $imagepath.'bg17.jpg',
				'bg18' => $imagepath.'bg18.jpg',
				'bg19' => $imagepath.'bg19.jpg',
				'bg20' => $imagepath.'bg20.jpg',
				'bg21' => $imagepath_theme.'patterns/bg21.png',
				'bg22' => $imagepath.'bg22.jpg',
				'bg23' => $imagepath_theme.'patterns/bg23.png',
				'bg24' => $imagepath_theme.'patterns/bg24.png',
			)
		);
	
		$options[] = array(
			'name'      => esc_html__('Custom Background','discy'),
			'id'        => 'question_custom_background',
			'std'       => $background_defaults,
			'type'      => 'background',
			'options'   => $background_defaults,
			'condition' => 'question_background_type:is(custom_background)'
		);
			
		$options[] = array(
			'name'      => esc_html__('Full Screen Background','discy'),
			'desc'      => esc_html__('Select ON to enable Full Screen Background','discy'),
			'id'        => 'question_full_screen_background',
			'type'      => 'checkbox',
			'condition' => 'question_background_type:is(custom_background)'
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'end'  => 'end'
		);

		$options = apply_filters('discy_options_after_questions_layout',$options);

		$options[] = array(
			'name'    => esc_html__('Popup share','discy'),
			'id'      => 'popup_share',
			'icon'    => 'share',
			'type'    => 'heading',
		);

		$options[] = array(
			'type' => 'heading-2',
		);

		$options[] = array(
			'name' => esc_html__('Activate the popup share for the posts and questions?','discy'),
			'desc' => esc_html__('Popup share for the posts and questions enable or disable.','discy'),
			'id'   => 'active_popup_share',
			'type' => 'checkbox'
		);

		$options[] = array(
			'div'       => 'div',
			'condition' => 'active_popup_share:not(0)',
			'type'      => 'heading-2'
		);

		$options[] = array(
			'name'    => esc_html__('Select which page do you need it to work','discy'),
			'id'      => 'popup_share_pages',
			'type'    => 'multicheck',
			'std'     => array(
				"questions" => "questions",
				"posts"     => "posts",
			),
			'options' => array(
				"questions" => esc_html__('Questions','discy'),
				"posts"     => esc_html__("Posts","discy"),
			)
		);

		$options[] = array(
			'name'    => esc_html__('Popup share works for "unlogged users", "logged in users", or "unlogged users" and "logged in users"','discy'),
			'id'      => 'popup_share_users',
			'std'     => 'both',
			'type'    => 'radio',
			'options' => 
				array(
					"unlogged" => esc_html__('Unlogged users','discy'),
					"logged"   => esc_html__('Logged users','discy'),
					"both"     => esc_html__('Unlogged and logged in users','discy')
			)
		);

		$options[] = array(
			'name'    => esc_html__('Popup share shows only for the owner only or for all','discy'),
			'id'      => 'popup_share_type',
			'std'     => 'all',
			'type'    => 'radio',
			'options' => 
				array(
					"all"   => esc_html__('For all','discy'),
					"owner" => esc_html__('Owner','discy')
			)
		);

		$options[] = array(
			'name'    => esc_html__('Popup share works when visiting the questions and posts or when scroll down to comments or to the adding comment box','discy'),
			'id'      => 'popup_share_visits',
			'std'     => 'visit',
			'type'    => 'radio',
			'options' => 
				array(
					"visit"  => esc_html__('Visiting','discy'),
					"scroll" => esc_html__('Scroll down','discy')
			)
		);

		$options[] = array(
			"name"      => esc_html__("How many seconds to show the popup share for?","discy"),
			"desc"      => esc_html__("Type here the seconds to show the popup share and leave it to 0 to show when open the question or post.","discy"),
			"id"        => "popup_share_seconds",
			"type"      => "sliderui",
			'std'       => "30",
			"step"      => "1",
			"min"       => "0",
			"max"       => "60",
			"condition" => "popup_share_visits:is(visit)",
		);

		$options[] = array(
			'name'    => esc_html__('Popup share shows per day, week, month, or forever','discy'),
			'id'      => 'popup_share_shows',
			'std'     => 'day',
			'type'    => 'radio',
			'options' => 
				array(
					"day"     => esc_html__('Day','discy'),
					"week"    => esc_html__('Week','discy'),
					"month"   => esc_html__('Month','discy'),
					"forever" => esc_html__('Forever','discy')
			)
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
			'name'    => esc_html__('Category moderators','discy'),
			'id'      => 'category_moderators',
			'icon'    => 'businessperson',
			'type'    => 'heading',
		);

		$options[] = array(
			'type' => 'heading-2',
		);

		$options[] = array(
			'name' => esc_html__('Activate the moderators for categories?','discy'),
			'desc' => esc_html__('Moderators for categories enable or disable.','discy'),
			'id'   => 'active_moderators',
			'type' => 'checkbox'
		);

		$options[] = array(
			'div'       => 'div',
			'condition' => 'active_moderators:not(0)',
			'type'      => 'heading-2'
		);

		$options[] = array(
			'name' => esc_html__('User pending questions slug','discy'),
			'desc' => esc_html__('Put the user pending questions slug.','discy'),
			'id'   => 'pending_questions_slug',
			'std'  => 'pending-questions',
			'type' => 'text'
		);

		$options[] = array(
			'name' => esc_html__('User pending posts slug','discy'),
			'desc' => esc_html__('Put the user pending posts slug.','discy'),
			'id'   => 'pending_posts_slug',
			'std'  => 'pending-posts',
			'type' => 'text'
		);

		$options[] = array(
			'name'    => esc_html__('Select the moderators permissions','discy'),
			'id'      => 'moderators_permissions',
			'type'    => 'multicheck',
			'std'     => array(
				"delete"  => "delete",
				"approve" => "approve",
				"edit"    => "edit",
				"ban"     => "ban",
			),
			'options' => array(
				"delete"  => esc_html__('Delete questions or posts','discy'),
				"approve" => esc_html__('Approve questions or posts','discy'),
				"edit"    => esc_html__('Edit questions or posts','discy'),
				"ban"     => esc_html__("Ban users","discy"),
			)
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

		$paymeny_setting = array(
			"payments_settings" => esc_html__('Payment setting','discy'),
			"pay_to_ask"        => esc_html__('Pay to ask','discy'),
			"pay_to_sticky"     => esc_html__('Pay to sticky question','discy'),
			"pay_to_answer"     => esc_html__('Pay to answer','discy'),
			"subscriptions"     => esc_html__('Subscriptions','discy'),
			"pay_to_post"       => esc_html__('Pay to post','discy'),
			"buy_points"        => esc_html__('Buy points','discy'),
			"pay_to_users"      => esc_html__('Pay to users','discy'),
			"coupons_setting"   => esc_html__('Coupon settings','discy'),
		);

		$options[] = array(
			'name'    => esc_html__('Payment settings','discy'),
			'id'      => 'payment_setting',
			'icon'    => 'tickets-alt',
			'type'    => 'heading',
			'std'     => 'payments_settings',
			'options' => apply_filters("discy_payment_setting",$paymeny_setting)
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'id'   => 'payments_settings',
			'name' => esc_html__('Payment setting','discy')
		);

		$options[] = array(
			'name' => esc_html__('Checkout slug','discy'),
			'desc' => esc_html__('Put the checkout slug.','discy'),
			'id'   => 'checkout_slug',
			'std'  => 'checkout',
			'type' => 'text'
		);
		
		$options[] = array(
			'name' => esc_html__('Enable the transactions page for the users','discy'),
			'desc' => esc_html__('Click ON to activate the transactions page for the users to show their transactions on the site.','discy'),
			'id'   => 'transactions_page',
			'std'  => 'on',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name' => '<a href="'.wpqa_get_checkout_permalink().'" target="_blank">'.esc_html__('The Link For The Checkout Page.','discy').'</a>',
			'type' => 'info'
		);
		
		$options[] = array(
			'name' => esc_html__('Enable the transactions of the payments with points saved in the statements','discy'),
			'desc' => esc_html__('Click ON to activate the transactions of the payments with points saved in the statements.','discy'),
			'id'   => 'save_pay_by_points',
			'std'  => 'on',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name'    => esc_html__('Payment style','discy'),
			'desc'    => esc_html__('Choose the payment style for the design.','discy'),
			'id'      => 'payment_style',
			'std'     => 'style_1',
			'type'    => 'radio',
			'options' => 
				array(
					"style_1" => esc_html__('Style 1','discy'),
					"style_2" => esc_html__('Style 2','discy')
			)
		);

		$options[] = array(
			'name'     => esc_html__('Custom text after the payment button','discy'),
			'id'       => 'custom_text_payment',
			'type'     => 'editor',
			'settings' => $wp_editor_settings
		);

		$payment_methods = array(
			"paypal" => array("sort" => esc_html__('PayPal','discy'),"value" => "paypal"),
			"stripe" => array("sort" => esc_html__('Stripe','discy'),"value" => "stripe"),
			"bank"   => array("sort" => esc_html__('Bank Transfer','discy'),"value" => "bank"),
			"custom" => array("sort" => esc_html__('Custom Payment','discy'),"value" => "custom"),
		);

		$payment_methods_std = array(
			"paypal" => array("sort" => esc_html__('PayPal','discy'),"value" => "paypal"),
			"stripe" => array("sort" => esc_html__('Stripe','discy'),"value" => "stripe"),
		);
		
		$options[] = array(
			'name'    => esc_html__('Select the payment methods','discy'),
			'id'      => 'payment_methodes',
			'type'    => 'multicheck',
			'sort'    => 'yes',
			'std'     => $payment_methods_std,
			'options' => $payment_methods,
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'payment_methodes:has(paypal)',
			'type'      => 'heading-2'
		);

		$options[] = array(
			'name' => esc_html__('PayPal','discy'),
			'type' => 'info'
		);

		$options[] = array(
			'name' => esc_html__('Upload your PayPal logo','discy'),
			'desc' => esc_html__('Upload your custom logo for the PayPal.','discy'),
			'id'   => 'paypal_logo',
			'std'  => $imagepath_theme."logo.png",
			'type' => 'upload',
		);

		$options[] = array(
			'std'      => esc_url(home_url('/'))."?action=paypal",
			'name'     => esc_html__("Put this link at IPN","discy"),
			'readonly' => 'readonly',
			'type'     => 'text'
		);
		
		$options[] = array(
			'name' => esc_html__('Enable PayPal sandbox','discy'),
			'desc' => esc_html__('PayPal sandbox can be used to test payments.','discy'),
			'id'   => 'paypal_sandbox',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'paypal_sandbox:is(on)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name' => esc_html__("PayPal email","discy"),
			'desc' => esc_html__("put your PayPal email","discy"),
			'id'   => 'paypal_email_sandbox',
			'std'  => get_bloginfo("admin_email"),
			'type' => 'text'
		);
		
		$options[] = array(
			'name' => esc_html__("PayPal Identity Token","discy"),
			'desc' => esc_html__("Add your PayPal Identity Token","discy"),
			'id'   => 'identity_token_sandbox',
			'type' => 'text'
		);

		$options[] = array(
			'name' => sprintf(__('Enter your PayPal API credentials. Learn how to access your <a target="_blank" href="%s">PayPal API Credentials</a>.','discy'),'https://developer.paypal.com/webapps/developer/docs/classic/api/apiCredentials/#create-an-api-signature'),
			'type' => 'info'
		);
		
		$options[] = array(
			'name' => esc_html__("Live API username","discy"),
			'desc' => esc_html__("Add your PayPal live API username","discy"),
			'id'   => 'paypal_api_username_sandbox',
			'type' => 'text'
		);
		
		$options[] = array(
			'name' => esc_html__("Live API password","discy"),
			'desc' => esc_html__("Add your PayPal live API password","discy"),
			'id'   => 'paypal_api_password_sandbox',
			'type' => 'password'
		);
		
		$options[] = array(
			'name' => esc_html__("Live API signature","discy"),
			'desc' => esc_html__("Add your PayPal live API signature","discy"),
			'id'   => 'paypal_api_signature_sandbox',
			'type' => 'password'
		);

		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'paypal_sandbox:is(0)',
			'type'      => 'heading-2'
		);

		$options[] = array(
			'name' => esc_html__("PayPal email","discy"),
			'desc' => esc_html__("put your PayPal email","discy"),
			'id'   => 'paypal_email',
			'std'  => get_bloginfo("admin_email"),
			'type' => 'text'
		);
		
		$options[] = array(
			'name' => esc_html__("PayPal Identity Token","discy"),
			'desc' => esc_html__("Add your PayPal Identity Token","discy"),
			'id'   => 'identity_token',
			'type' => 'text'
		);

		$options[] = array(
			'name' => sprintf(__('Enter your PayPal API credentials. Learn how to access your <a target="_blank" href="%s">PayPal API Credentials</a>.','discy'),'https://developer.paypal.com/docs/archive/nvp-soap-api/apiCredentials/#create-an-api-signature'),
			'type' => 'info'
		);
		
		$options[] = array(
			'name' => esc_html__("Live API username","discy"),
			'desc' => esc_html__("Add your PayPal live API username","discy"),
			'id'   => 'paypal_api_username',
			'type' => 'text'
		);
		
		$options[] = array(
			'name' => esc_html__("Live API password","discy"),
			'desc' => esc_html__("Add your PayPal live API password","discy"),
			'id'   => 'paypal_api_password',
			'type' => 'password'
		);
		
		$options[] = array(
			'name' => esc_html__("Live API signature","discy"),
			'desc' => esc_html__("Add your PayPal live API signature","discy"),
			'id'   => 'paypal_api_signature',
			'type' => 'password'
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
			'div'       => 'div',
			'condition' => 'payment_methodes:has(stripe)',
			'type'      => 'heading-2'
		);

		$options[] = array(
			'name' => esc_html__('Stripe','discy'),
			'type' => 'info'
		);
		
		$options[] = array(
			'name' => esc_html__('Publishable key','discy'),
			'id'   => 'publishable_key',
			'type' => 'text'
		);

		$options[] = array(
			'name' => esc_html__('Secret key','discy'),
			'id'   => 'secret_key',
			'type' => 'text'
		);

		$options[] = array(
			'std'      => esc_url(home_url('/'))."?action=stripe",
			'name'     => esc_html__("Put this link at webhooks","discy"),
			'readonly' => 'readonly',
			'type'     => 'text'
		);

		$options[] = array(
			'name' => esc_html__('Activate the address info','discy'),
			'desc' => esc_html__("Select ON to active the address info, it's very important for some countries to activate it.","discy"),
			'id'   => 'stripe_address',
			'type' => 'checkbox'
		);

		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'payment_methodes:has(bank)',
			'type'      => 'heading-2'
		);

		$options[] = array(
			'name' => esc_html__('Bank transfer','discy'),
			'type' => 'info'
		);
		
		$options[] = array(
			'name'     => esc_html__('Bank transfer details','discy'),
			'id'       => 'bank_transfer_details',
			'type'     => 'editor',
			'settings' => $wp_editor_settings
		);

		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'payment_methodes:has(custom)',
			'type'      => 'heading-2'
		);

		$options[] = array(
			'name' => esc_html__('Custom Payment','discy'),
			'type' => 'info'
		);
		
		$options[] = array(
			"name" => esc_html__("Custom payment tab name","discy"),
			"id"   => "custom_payment_tab",
			"type" => "text",
			'std'  => "Custom payment"
		);
		
		$options[] = array(
			'name'     => esc_html__('Custom payment details','discy'),
			'id'       => 'custom_payment_details',
			'type'     => 'editor',
			'settings' => $wp_editor_settings
		);

		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);

		$options[] = array(
			'name' => esc_html__('Currencies','discy'),
			'type' => 'info'
		);

		$options[] = array(
			'name'    => esc_html__('Default currency code','discy'),
			'desc'    => esc_html__('Choose form here the default currency code.','discy'),
			'id'      => 'currency_code',
			'std'     => 'USD',
			'type'    => "select",
			'options' => $currencies
		);
		
		$options[] = array(
			'name' => esc_html__('Activate the multi currencies','discy'),
			'desc' => esc_html__('Select ON to activate multi currencies.','discy'),
			'id'   => 'activate_currencies',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name'       => esc_html__('Select the multi currencies','discy'),
			'id'         => 'multi_currencies',
			'type'       => 'multicheck',
			'strtolower' => 'not',
			'condition'  => 'activate_currencies:not(0)',
			'options'    => $currencies,
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'end'  => 'end'
		);
		
		$options[] = array(
			'name' => esc_html__('Pay to ask','discy'),
			'id'   => 'pay_to_ask',
			'type' => 'heading-2'
		);
		
		$options[] = array(
			'name' => esc_html__('Pay to ask question','discy'),
			'desc' => esc_html__('Select ON to activate pay to ask question.','discy'),
			'id'   => 'pay_ask',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'pay_ask:not(0)',
			'type'      => 'heading-2'
		);

		$options[] = array(
			'name'    => esc_html__('Payment way','discy'),
			'desc'    => esc_html__('Choose the payment way for the ask question','discy'),
			'id'      => 'payment_type_ask',
			'std'     => 'payments',
			'type'    => 'radio',
			'options' => array(
				"payments"        => esc_html__('Payment methods','discy'),
				"points"          => esc_html__('By points','discy'),
				"payments_points" => esc_html__('Payment methods and points','discy')
			)
		);

		$options[] = array(
			'name'    => esc_html__('Question payment style','discy'),
			'desc'    => esc_html__('Choose the asking question payment style','discy'),
			'id'      => 'ask_payment_style',
			'std'     => 'once',
			'type'    => 'radio',
			'options' => array(
				"once"     => esc_html__('Once payment','discy'),
				"packages" => esc_html__('Packages payment','discy')
			)
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'ask_payment_style:is(packages)',
			'type'      => 'heading-2'
		);

		$options[] = array(
			'div'       => 'div',
			'condition' => 'payment_type_ask:not(points),activate_currencies:not(0)',
			'type'      => 'heading-2'
		);

		if (is_array($multi_currencies) && !empty($multi_currencies)) {
			foreach ($multi_currencies as $key_currency => $value_currency) {
				if ($value_currency != "0") {
					$ask_packages_price[] = array(
						"name" => esc_html__("With price for","discy")." ".$value_currency,
						"id"   => "package_price_".strtolower($value_currency),
						"type" => "text",
					);
				}
			}
		}

		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);

		if ($activate_currencies != "on" || ($activate_currencies == "on" && !isset($ask_packages_price))) {
			$ask_packages_price = array(array(
				"type" => "text",
				"id"   => "package_price",
				"name" => esc_html__('With price','discy')
			));
		}

		$ask_packages_array = array(
			array(
				"type" => "text",
				"id"   => "package_name",
				"name" => esc_html__('Package name','discy')
			),
			array(
				"type" => "text",
				"id"   => "package_description",
				"name" => esc_html__('Package description','discy')
			),
			array(
				"type" => "text",
				"id"   => "package_posts",
				"name" => esc_html__('Package questions','discy')
			),
			array(
				"type" => "text",
				"id"   => "package_points",
				"name" => esc_html__('With points','discy')
			),
			array(
				'type' => 'checkbox',
				"id"   => "sticky",
				"name" => esc_html__('Make any question in this package sticky','discy')
			),
			array(
				"type"      => "slider",
				"name"      => esc_html__("How many days would you like to make the question sticky?","discy"),
				"id"        => "days_sticky",
				"std"       => "7",
				"step"      => "1",
				"min"       => "1",
				"max"       => "365",
				"value"     => "1",
				'condition' => '[%id%]sticky:is(on)',
			),
		);

		$ask_packages_elements = array_merge($ask_packages_array,$ask_packages_price);

		$options[] = array(
			'id'      => "ask_packages",
			'type'    => "elements",
			'sort'    => "no",
			'hide'    => "yes",
			'button'  => esc_html__('Add a new package','discy'),
			'options' => $ask_packages_elements,
		);

		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);

		$options[] = array(
			'div'       => 'div',
			'condition' => 'ask_payment_style:not(packages)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			"name"      => esc_html__("What's the price to ask a new question?","discy"),
			"desc"      => esc_html__("Type here price to ask a new question","discy"),
			"id"        => "pay_ask_payment",
			"type"      => "text",
			'condition' => 'payment_type_ask:not(points),activate_currencies:is(0)',
			'std'       => 10
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'payment_type_ask:not(points),activate_currencies:not(0)',
			'type'      => 'heading-2'
		);
		
		if (is_array($multi_currencies) && !empty($multi_currencies)) {
			$options[] = array(
				'name' => esc_html__("What's the price to ask a new question?","discy"),
				'type' => 'info'
			);
			foreach ($multi_currencies as $key_currency => $value_currency) {
				if ($value_currency != "0") {
					$options[] = array(
						"name" => esc_html__("Price for","discy")." ".$value_currency,
						"id"   => "pay_ask_payment_".strtolower($value_currency),
						"type" => "text",
						'std'  => 10
					);
				}
			}
		}

		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);
		
		$options[] = array(
			"name"      => esc_html__("How many points to ask a new question?","discy"),
			"desc"      => esc_html__("Type here points of the payment to ask a new question","discy"),
			"id"        => "ask_payment_points",
			"type"      => "text",
			'condition' => 'payment_type_ask:has(points),payment_type_ask:has(payments_points)',
			'operator'  => 'or',
			'std'       => 20
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
			'name' => esc_html__('Pay to sticky question','discy'),
			'id'   => 'pay_to_sticky',
			'type' => 'heading-2'
		);
		
		$options[] = array(
			'name' => esc_html__('Pay to sticky question at the top','discy'),
			'desc' => esc_html__('Select ON to active the pay to sticky question.','discy'),
			'id'   => 'pay_to_sticky',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'pay_to_sticky:not(0)',
			'type'      => 'heading-2'
		);

		$options[] = array(
			'name'    => esc_html__('Payment way','discy'),
			'desc'    => esc_html__('Choose the payment way for the sticky the question','discy'),
			'id'      => 'payment_type_sticky',
			'std'     => 'payments',
			'type'    => 'radio',
			'options' => array(
				"payments"        => esc_html__('Payment methods','discy'),
				"points"          => esc_html__('By points','discy'),
				"payments_points" => esc_html__('Payment methods and points','discy')
			)
		);

		$options[] = array(
			"name"      => esc_html__("What is the price to make the question sticky?","discy"),
			"desc"      => esc_html__("Type here the price of the payment to make the question sticky.","discy"),
			"id"        => "pay_sticky_payment",
			"type"      => "text",
			'condition' => 'payment_type_sticky:not(points),activate_currencies:is(0)',
			'std'       => 5
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'payment_type_ask:not(points),activate_currencies:not(0)',
			'type'      => 'heading-2'
		);
		
		if (is_array($multi_currencies) && !empty($multi_currencies)) {
			$options[] = array(
				'name' => esc_html__("What is the price to make the question sticky?","discy"),
				'type' => 'info'
			);
			foreach ($multi_currencies as $key_currency => $value_currency) {
				if ($value_currency != "0") {
					$options[] = array(
						"name" => esc_html__("Price for","discy")." ".$value_currency,
						"id"   => "pay_sticky_payment_".strtolower($value_currency),
						"type" => "text",
						'std'  => 5
					);
				}
			}
		}

		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);
		
		$options[] = array(
			"name"      => esc_html__("How many points to make the question sticky?","discy"),
			"desc"      => esc_html__("Type here points of the payment to sticky the question","discy"),
			"id"        => "sticky_payment_points",
			"type"      => "text",
			'condition' => 'payment_type_sticky:has(points),payment_type_sticky:has(payments_points)',
			'operator'  => 'or',
			'std'       => 10
		);
		
		$options[] = array(
			"name" => esc_html__("How many days would you like to make the question sticky?","discy"),
			"desc" => esc_html__("Type here days of the payment to sticky the question.","discy"),
			"id"   => "days_sticky",
			"type" => "sliderui",
			'std'  => "7",
			"step" => "1",
			"min"  => "1",
			"max"  => "365"
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
			'name' => esc_html__('Pay to answer','discy'),
			'id'   => 'pay_to_answer',
			'type' => 'heading-2'
		);
		
		$options[] = array(
			'name' => esc_html__('Pay to add answer','discy'),
			'desc' => esc_html__('Select ON to activate pay to answer.','discy'),
			'id'   => 'pay_answer',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'pay_answer:not(0)',
			'type'      => 'heading-2'
		);

		$options[] = array(
			'name'    => esc_html__('Payment way','discy'),
			'desc'    => esc_html__('Choose the payment way for the answer','discy'),
			'id'      => 'payment_type_answer',
			'std'     => 'payments',
			'type'    => 'radio',
			'options' => array(
				"payments"        => esc_html__('Payment methods','discy'),
				"points"          => esc_html__('By points','discy'),
				"payments_points" => esc_html__('Payment methods and points','discy')
			)
		);
		
		$options[] = array(
			"name"      => esc_html__("What's the price to add a new answer?","discy"),
			"desc"      => esc_html__("Type here price to add a new answer","discy"),
			"id"        => "pay_answer_payment",
			"type"      => "text",
			'condition' => 'payment_type_answer:not(points),activate_currencies:is(0)',
			'std'       => 10
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'payment_type_answer:not(points),activate_currencies:not(0)',
			'type'      => 'heading-2'
		);
		
		if (is_array($multi_currencies) && !empty($multi_currencies)) {
			$options[] = array(
				'name' => esc_html__("What's the price to add a new answer?","discy"),
				'type' => 'info'
			);
			foreach ($multi_currencies as $key_currency => $value_currency) {
				if ($value_currency != "0") {
					$options[] = array(
						"name" => esc_html__("Price for","discy")." ".$value_currency,
						"id"   => "pay_answer_payment_".strtolower($value_currency),
						"type" => "text",
						'std'  => 10
					);
				}
			}
		}
		
		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);
		
		$options[] = array(
			"name"      => esc_html__("How many points to add a new answer?","discy"),
			"desc"      => esc_html__("Type here points of the payment to add a new answer","discy"),
			"id"        => "answer_payment_points",
			"type"      => "text",
			'condition' => 'payment_type_answer:has(points),payment_type_answer:has(payments_points)',
			'operator'  => 'or',
			'std'       => 20
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
			'name' => esc_html__('Subscriptions','discy'),
			'id'   => 'subscriptions',
			'type' => 'heading-2'
		);

		$options[] = array(
			'name' => esc_html__('Subscriptions','discy'),
			'desc' => esc_html__('Select ON to activate subscriptions.','discy'),
			'id'   => 'subscriptions_payment',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'subscriptions_payment:not(0)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name' => esc_html__('Subscriptions slug','discy'),
			'desc' => esc_html__('Put the subscriptions slug.','discy'),
			'id'   => 'subscriptions_slug',
			'std'  => 'subscriptions',
			'type' => 'text'
		);

		$options[] = array(
			'name' => '<a href="'.wpqa_subscriptions_permalink().'" target="_blank">'.esc_html__('The Link For The Subscriptions Page.','discy').'</a>',
			'type' => 'info'
		);

		$options[] = array(
			'name' => '<a href="https://2code.info/docs/discy/subscription/" target="_blank">'.esc_html__('To make the paid subscriptions work well, check this link.','discy').'</a>',
			'type' => 'info'
		);

		$options[] = array(
			'name'    => esc_html__('Payment way','discy'),
			'desc'    => esc_html__('Choose the payment way for the subscriptions','discy'),
			'id'      => 'payment_type_subscriptions',
			'std'     => 'payments',
			'type'    => 'radio',
			'options' => array(
				"payments"        => esc_html__('Payment methods','discy'),
				"points"          => esc_html__('By points','discy'),
				"payments_points" => esc_html__('Payment methods and points','discy')
			)
		);

		$options[] = array(
			'name'    => esc_html__('Paid role for the subscriptions','discy'),
			'desc'    => esc_html__('Select the paid role for the subscriptions','discy'),
			'id'      => 'subscriptions_group',
			'std'     => 'author',
			'type'    => 'select',
			'options' => discy_options_roles()
		);

		$options[] = array(
			'name' => esc_html__('Cancel the subscription','discy'),
			'desc' => esc_html__('Select ON to active the cancel subscription button for the users.','discy'),
			'id'   => 'cancel_subscription',
			'type' => 'checkbox'
		);

		$options[] = array(
			'name' => esc_html__('Change the subscription plans','discy'),
			'desc' => esc_html__('Select ON to activate the change subscription plans for the users.','discy'),
			'id'   => 'change_subscription',
			'type' => 'checkbox'
		);

		$options[] = array(
			'name' => esc_html__('Trial subscription plans','discy'),
			'type' => 'info'
		);

		$options[] = array(
			'name' => esc_html__('Allow the users to try the subscription plans','discy'),
			'desc' => esc_html__('Select ON to activate to allow the users to try the subscription plans.','discy'),
			'id'   => 'trial_subscription',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'trial_subscription:not(0)',
			'type'      => 'heading-2'
		);

		$options[] = array(
			'name'    => esc_html__('Select the options for the free trial subscriptions','discy'),
			'id'      => 'trial_subscription_plan',
			'type'    => 'radio',
			'std'     => 'hour',
			'options' => array(
				"hour"  => esc_html__('Hour','discy'),
				"week"  => esc_html__('Week','discy'),
				"month" => esc_html__('Month','discy'),
			)
		);

		$options[] = array(
			'name' => esc_html__('Choose the number of hours, weeks, or months for the trial plan','discy'),
			"id"   => "trial_subscription_rang",
			"type" => "sliderui",
			'std'  => '2',
			"step" => "1",
			"min"  => "1",
			"max"  => "10"
		);

		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);

		$options[] = array(
			'name' => esc_html__('Reward subscription','discy'),
			'type' => 'info'
		);

		$options[] = array(
			'name' => esc_html__('Allow the users to join the subscription plans based on the activities','discy'),
			'desc' => esc_html__('Select ON to allow the users to join the subscription plans based on activities like asking questions and adding answers.','discy'),
			'id'   => 'reward_subscription',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'reward_subscription:not(0)',
			'type'      => 'heading-2'
		);

		$options[] = array(
			'name'    => esc_html__('Select the plan to allow the user to join it automatically based on the activities','discy'),
			'id'      => 'reward_subscription_plan',
			'type'    => 'radio',
			'std'     => 'month',
			'options' => array(
				"week"  => esc_html__('Week','discy'),
				"month" => esc_html__('Month','discy'),
			)
		);

		$options[] = array(
			'name' => esc_html__('Choose the number of weeks, or months for the reward plan','discy'),
			"id"   => "reward_subscription_rang",
			"type" => "sliderui",
			'std'  => '1',
			"step" => "1",
			"min"  => "1",
			"max"  => "12"
		);

		$options[] = array(
			'name' => esc_html__("Note: anything you don't need for the reward subscription only put on it 0","discy"),
			'type' => 'info'
		);

		$options[] = array(
			'name' => esc_html__('Choose the number of questions in the month to join the paid subscription plan','discy'),
			"id"   => "reward_questions_subscription",
			"type" => "text",
			'std'  => 40,
		);

		$options[] = array(
			'name' => esc_html__('Choose the number of answers in the month to join the paid subscription plan','discy'),
			"id"   => "reward_answers_subscription",
			"type" => "text",
			'std'  => 100,
		);

		$options[] = array(
			'name' => esc_html__('Choose the number of best answers in the month to join the paid subscription plan','discy'),
			"id"   => "reward_best_answers_subscription",
			"type" => "text",
			'std'  => 20,
		);

		$options[] = array(
			'name' => esc_html__('Choose the number of posts in the month to join the paid subscription plan','discy'),
			"id"   => "reward_posts_subscription",
			"type" => "text",
			'std'  => 30,
		);

		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);

		$options[] = array(
			'name' => esc_html__('Subscription plans','discy'),
			'type' => 'info'
		);

		$options[] = array(
			'name'    => esc_html__('Select the options for the subscriptions','discy'),
			'id'      => 'subscriptions_options',
			'type'    => 'multicheck',
			'std'     => array(
				"monthly"  => "monthly",
				"3months"  => "3months",
				"6months"  => "6months",
				"yearly"   => "yearly",
				"lifetime" => "lifetime",
			),
			'options' => array(
				"monthly"  => esc_html__('Monthly','discy'),
				"3months"  => esc_html__('Three months','discy'),
				"6months"  => esc_html__('Six months','discy'),
				"yearly"   => esc_html__('Yearly','discy'),
				"2years"  => esc_html__('Two years','discy'),
				"lifetime" => esc_html__('Lifetime','discy'),
			)
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'activate_currencies:is(0)',
			'type'      => 'heading-2'
		);

		$options[] = array(
			"name"      => esc_html__("What's the price to subscribe monthly?",'discy'),
			"id"        => "subscribe_monthly",
			"type"      => "text",
			'condition' => 'subscriptions_options:has(monthly)',
			'std'       => 10
		);

		$options[] = array(
			"name"      => esc_html__("What's the price to subscribe for three months?",'discy'),
			"id"        => "subscribe_3months",
			"type"      => "text",
			'condition' => 'subscriptions_options:has(3months)',
			'std'       => 25
		);

		$options[] = array(
			"name"      => esc_html__("What's the price to subscribe for six months?",'discy'),
			"id"        => "subscribe_6months",
			"type"      => "text",
			'condition' => 'subscriptions_options:has(6months)',
			'std'       => 45
		);

		$options[] = array(
			"name"      => esc_html__("What's the price to subscribe yearly?",'discy'),
			"id"        => "subscribe_yearly",
			"type"      => "text",
			'condition' => 'subscriptions_options:has(yearly)',
			'std'       => 80
		);

		$options[] = array(
			"name"      => esc_html__("What's the price to subscribe for two years?",'discy'),
			"id"        => "subscribe_2years",
			"type"      => "text",
			'condition' => 'subscriptions_options:has(2years)',
			'std'       => 80
		);

		$options[] = array(
			"name"      => esc_html__("What's the price to subscribe lifetime?",'discy'),
			"id"        => "subscribe_lifetime",
			"type"      => "text",
			'condition' => 'subscriptions_options:has(lifetime)',
			'std'       => 200
		);

		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'activate_currencies:not(0)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'subscriptions_options:has(monthly)',
			'type'      => 'heading-2'
		);
		
		if (is_array($multi_currencies) && !empty($multi_currencies)) {
			$options[] = array(
				'name' => esc_html__("What's the price to subscribe monthly?","discy"),
				'type' => 'info'
			);
			foreach ($multi_currencies as $key_currency => $value_currency) {
				if ($value_currency != "0") {
					$options[] = array(
						"name" => esc_html__("Price for","discy")." ".$value_currency,
						"id"   => "subscribe_monthly_".strtolower($value_currency),
						"type" => "text",
						'std'  => 10
					);
				}
			}
		}

		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'subscriptions_options:has(3months)',
			'type'      => 'heading-2'
		);
		
		if (is_array($multi_currencies) && !empty($multi_currencies)) {
			$options[] = array(
				'name' => esc_html__("What's the price to subscribe three months?","discy"),
				'type' => 'info'
			);
			foreach ($multi_currencies as $key_currency => $value_currency) {
				if ($value_currency != "0") {
					$options[] = array(
						"name" => esc_html__("Price for","discy")." ".$value_currency,
						"id"   => "subscribe_3months_".strtolower($value_currency),
						"type" => "text",
						'std'  => 25
					);
				}
			}
		}

		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'subscriptions_options:has(6months)',
			'type'      => 'heading-2'
		);
		
		if (is_array($multi_currencies) && !empty($multi_currencies)) {
			$options[] = array(
				'name' => esc_html__("What's the price to subscribe six months?","discy"),
				'type' => 'info'
			);
			foreach ($multi_currencies as $key_currency => $value_currency) {
				if ($value_currency != "0") {
					$options[] = array(
						"name" => esc_html__("Price for","discy")." ".$value_currency,
						"id"   => "subscribe_6months_".strtolower($value_currency),
						"type" => "text",
						'std'  => 45
					);
				}
			}
		}

		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'subscriptions_options:has(yearly)',
			'type'      => 'heading-2'
		);
		
		if (is_array($multi_currencies) && !empty($multi_currencies)) {
			$options[] = array(
				'name' => esc_html__("What's the price to subscribe yearly?","discy"),
				'type' => 'info'
			);
			foreach ($multi_currencies as $key_currency => $value_currency) {
				if ($value_currency != "0") {
					$options[] = array(
						"name" => esc_html__("Price for","discy")." ".$value_currency,
						"id"   => "subscribe_yearly_".strtolower($value_currency),
						"type" => "text",
						'std'  => 80
					);
				}
			}
		}

		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);

		$options[] = array(
			'div'       => 'div',
			'condition' => 'subscriptions_options:has(2years)',
			'type'      => 'heading-2'
		);
		
		if (is_array($multi_currencies) && !empty($multi_currencies)) {
			$options[] = array(
				'name' => esc_html__("What's the price to subscribe for two years?","discy"),
				'type' => 'info'
			);
			foreach ($multi_currencies as $key_currency => $value_currency) {
				if ($value_currency != "0") {
					$options[] = array(
						"name" => esc_html__("Price for","discy")." ".$value_currency,
						"id"   => "subscribe_2years_".strtolower($value_currency),
						"type" => "text",
						'std'  => 80
					);
				}
			}
		}

		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);

		$options[] = array(
			'div'       => 'div',
			'condition' => 'subscriptions_options:has(lifetime)',
			'type'      => 'heading-2'
		);
		
		if (is_array($multi_currencies) && !empty($multi_currencies)) {
			$options[] = array(
				'name' => esc_html__("What's the price to subscribe lifetime?","discy"),
				'type' => 'info'
			);
			foreach ($multi_currencies as $key_currency => $value_currency) {
				if ($value_currency != "0") {
					$options[] = array(
						"name" => esc_html__("Price for","discy")." ".$value_currency,
						"id"   => "subscribe_lifetime_".strtolower($value_currency),
						"type" => "text",
						'std'  => 200
					);
				}
			}
		}

		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);

		$options = apply_filters("discy_filter_after_subscription",$options);

		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);

		$options[] = array(
			'div'       => 'div',
			'condition' => 'payment_type_subscriptions:has(points),payment_type_subscriptions:has(payments_points)',
			'operator'  => 'or',
			'type'      => 'heading-2'
		);

		$options[] = array(
			'name' => esc_html__("Price with points to allow the users to subscribe","discy"),
			'type' => 'info'
		);

		$options[] = array(
			"name"      => esc_html__("What's the points to subscribe monthly?",'discy'),
			"id"        => "subscribe_monthly_points",
			"type"      => "text",
			'condition' => 'subscriptions_options:has(monthly)',
			'std'       => 100
		);

		$options[] = array(
			"name"      => esc_html__("What's the points to subscribe for three months?",'discy'),
			"id"        => "subscribe_3months_points",
			"type"      => "text",
			'condition' => 'subscriptions_options:has(3months)',
			'std'       => 250
		);

		$options[] = array(
			"name"      => esc_html__("What's the points to subscribe for six months?",'discy'),
			"id"        => "subscribe_6months_points",
			"type"      => "text",
			'condition' => 'subscriptions_options:has(6months)',
			'std'       => 400
		);

		$options[] = array(
			"name"      => esc_html__("What's the points to subscribe yearly?",'discy'),
			"id"        => "subscribe_yearly_points",
			"type"      => "text",
			'condition' => 'subscriptions_options:has(yearly)',
			'std'       => 700
		);

		$options[] = array(
			"name"      => esc_html__("What's the points to subscribe for two years?",'discy'),
			"id"        => "subscribe_2years_points",
			"type"      => "text",
			'condition' => 'subscriptions_options:has(2years)',
			'std'       => 700
		);

		$options[] = array(
			"name"      => esc_html__("What's the points to subscribe for lifetime?",'discy'),
			"id"        => "subscribe_lifetime_points",
			"type"      => "text",
			'condition' => 'subscriptions_options:has(lifetime)',
			'std'       => 2000
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
			'name' => esc_html__('Pay to post','discy'),
			'id'   => 'pay_to_post',
			'type' => 'heading-2'
		);
		
		$options[] = array(
			'name' => esc_html__('Pay to add post','discy'),
			'desc' => esc_html__('Select ON to activate the pay to add post.','discy'),
			'id'   => 'pay_post',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'pay_post:not(0)',
			'type'      => 'heading-2'
		);

		$options = apply_filters("discy_filter_inner_pay_post",$options);

		$options[] = array(
			'name'    => esc_html__('Payment way','discy'),
			'desc'    => esc_html__('Choose the payment way for the add post','discy'),
			'id'      => 'payment_type_post',
			'std'     => 'payments',
			'type'    => 'radio',
			'options' => array(
				"payments"        => esc_html__('Payment methods','discy'),
				"points"          => esc_html__('By points','discy'),
				"payments_points" => esc_html__('Payment methods and points','discy')
			)
		);

		$options[] = array(
			'name'    => esc_html__('Post payment style','discy'),
			'desc'    => esc_html__('Choose the adding post payment style','discy'),
			'id'      => 'post_payment_style',
			'std'     => 'once',
			'type'    => 'radio',
			'options' => array(
				"once"     => esc_html__('Once payment','discy'),
				"packages" => esc_html__('Packages payment','discy')
			)
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'post_payment_style:is(packages)',
			'type'      => 'heading-2'
		);

		$options[] = array(
			'div'       => 'div',
			'condition' => 'payment_type_post:not(points),activate_currencies:not(0)',
			'type'      => 'heading-2'
		);

		if (is_array($multi_currencies) && !empty($multi_currencies)) {
			foreach ($multi_currencies as $key_currency => $value_currency) {
				if ($value_currency != "0") {
					$post_packages_price[] = array(
						"name" => esc_html__("With price for","discy")." ".$value_currency,
						"id"   => "package_price_".strtolower($value_currency),
						"type" => "text",
					);
				}
			}
		}

		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);

		if ($activate_currencies != "on" || ($activate_currencies == "on" && !isset($post_packages_price))) {
			$post_packages_price = array(array(
				"type" => "text",
				"id"   => "package_price",
				"name" => esc_html__('With price','discy')
			));
		}

		$post_packages_array = array(
			array(
				"type" => "text",
				"id"   => "package_name",
				"name" => esc_html__('Package name','discy')
			),
			array(
				"type" => "text",
				"id"   => "package_description",
				"name" => esc_html__('Package description','discy')
			),
			array(
				"type" => "text",
				"id"   => "package_posts",
				"name" => esc_html__('Package posts','discy')
			),
			array(
				"type" => "text",
				"id"   => "package_points",
				"name" => esc_html__('With points','discy')
			),
			array(
				'type' => 'checkbox',
				"id"   => "sticky",
				"name" => esc_html__('Make any post in this package sticky','discy')
			),
			array(
				"type"      => "slider",
				"name"      => esc_html__("How many days would you like to make the post sticky?","discy"),
				"id"        => "days_sticky",
				"std"       => "7",
				"step"      => "1",
				"min"       => "1",
				"max"       => "365",
				"value"     => "1",
				'condition' => '[%id%]sticky:is(on)',
			),
		);

		$post_packages_elements = array_merge($post_packages_array,$post_packages_price);

		$options[] = array(
			'id'      => "post_packages",
			'type'    => "elements",
			'sort'    => "no",
			'hide'    => "yes",
			'button'  => esc_html__('Add a new package','discy'),
			'options' => $post_packages_elements,
		);

		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);

		$options[] = array(
			'div'       => 'div',
			'condition' => 'post_payment_style:not(packages)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			"name"      => esc_html__("What's the price to add a new post?","discy"),
			"desc"      => esc_html__("Type here price to add a new post","discy"),
			"id"        => "pay_post_payment",
			"type"      => "text",
			'condition' => 'payment_type_post:not(points),activate_currencies:is(0)',
			'std'       => 10
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'payment_type_post:not(points),activate_currencies:not(0)',
			'type'      => 'heading-2'
		);
		
		if (is_array($multi_currencies) && !empty($multi_currencies)) {
			$options[] = array(
				'name' => esc_html__("What's the price to add a new post?","discy"),
				'type' => 'info'
			);
			foreach ($multi_currencies as $key_currency => $value_currency) {
				if ($value_currency != "0") {
					$options[] = array(
						"name" => esc_html__("Price for","discy")." ".$value_currency,
						"id"   => "pay_post_payment_".strtolower($value_currency),
						"type" => "text",
						'std'  => 10
					);
				}
			}
		}

		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);
		
		$options[] = array(
			"name"      => esc_html__("How many points to add a new post?","discy"),
			"desc"      => esc_html__("Type here points of the payment to add a new post","discy"),
			"id"        => "post_payment_points",
			"type"      => "text",
			'condition' => 'payment_type_post:has(points),payment_type_post:has(payments_points)',
			'operator'  => 'or',
			'std'       => 20
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
			'name' => esc_html__('Buy points','discy'),
			'id'   => 'buy_points',
			'type' => 'heading-2'
		);

		$options[] = array(
			'name' => esc_html__('Buy points','discy'),
			'desc' => esc_html__('Select ON to activate buy points.','discy'),
			'id'   => 'buy_points_payment',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'buy_points_payment:not(0)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name' => esc_html__('Buy points slug','discy'),
			'desc' => esc_html__('Put the buy points slug.','discy'),
			'id'   => 'buy_points_slug',
			'std'  => 'buy-points',
			'type' => 'text'
		);

		if (has_wpqa()) {
			$options[] = array(
				'name' => '<a href="'.wpqa_buy_points_permalink().'" target="_blank">'.esc_html__('The Link For The Buy Points Page.','discy').'</a>',
				'type' => 'info'
			);
		}
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'payment_type_ask:not(points),activate_currencies:not(0)',
			'type'      => 'heading-2'
		);
		
		if (is_array($multi_currencies) && !empty($multi_currencies)) {
			foreach ($multi_currencies as $key_currency => $value_currency) {
				if ($value_currency != "0") {
					$buy_points_price[] = array(
						"name" => esc_html__("Price for","discy")." ".$value_currency,
						"id"   => "package_price_".strtolower($value_currency),
						"type" => "text",
					);
				}
			}
		}

		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);

		if ($activate_currencies != "on" || ($activate_currencies == "on" && !isset($buy_points_price))) {
			$buy_points_price = array(array(
				"type" => "text",
				"id"   => "package_price",
				"name" => esc_html__('Price','discy')
			));
		}

		$buy_points_array = array(
			array(
				"type" => "text",
				"id"   => "package_name",
				"name" => esc_html__('Package name','discy')
			),
			array(
				"type" => "text",
				"id"   => "package_points",
				"name" => esc_html__('Points','discy')
			),
			array(
				"type" => "text",
				"id"   => "package_description",
				"name" => esc_html__('Package description','discy')
			)
		);

		$buy_points_elements = array_merge($buy_points_array,$buy_points_price);
		
		$options[] = array(
			'id'      => "buy_points",
			'type'    => "elements",
			'sort'    => "no",
			'hide'    => "yes",
			'button'  => esc_html__('Add a new package','discy'),
			'options' => $buy_points_elements,
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
			'name' => esc_html__('Pay to users','discy'),
			'id'   => 'pay_to_users',
			'type' => 'heading-2'
		);

		$options[] = array(
			'name' => esc_html__('Pay money to users','discy'),
			'desc' => esc_html__('Select ON to activate pay money to users.','discy'),
			'id'   => 'activate_pay_to_users',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'activate_pay_to_users:not(0)',
			'type'      => 'heading-2'
		);

		$edit_profile_items_5 = array(
			'paypal'   => array('sort' => esc_html__('PayPal','discy'),'value' => 'paypal'),
			'payoneer' => array('sort' => esc_html__('Payoneer','discy'),'value' => 'payoneer'),
			'bank'     => array('sort' => esc_html__('Bank Transfer','discy'),'value' => 'bank'),
		);
		
		$options[] = array(
			'name'    => esc_html__('Select what to show at edit profile to pay money for the users section','discy'),
			'id'      => 'edit_profile_items_5',
			'type'    => 'multicheck',
			'sort'    => 'yes',
			'std'     => $edit_profile_items_5,
			'options' => $edit_profile_items_5
		);

		$options[] = array(
			'name' => esc_html__("How many points to change them with x money?","discy"),
			'id'   => 'pay_minimum_points',
			'type' => 'text',
			'std'  => 100
		);

		$options[] = array(
			'name' => esc_html__("What's the minimum money to allow the user to make the payment?","discy"),
			'id'   => 'pay_minimum_money',
			'type' => 'text',
			'std'  => 50
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

		$options = apply_filters('discy_options_before_coupons_setting',$options);
		
		$options[] = array(
			'name' => esc_html__('Coupon settings','discy'),
			'id'   => 'coupons_setting',
			'type' => 'heading-2'
		);
		
		$options[] = array(
			'name' => esc_html__('Activate the Coupons','discy'),
			'desc' => esc_html__('Select ON to activate the coupons.','discy'),
			'id'   => 'active_coupons',
			'std'  => 'on',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'active_coupons:not(0)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name' => esc_html__('Show the free coupons when making any payment?','discy'),
			'desc' => esc_html__('Select ON to show the free coupons.','discy'),
			'id'   => 'free_coupons',
			'type' => 'checkbox'
		);
		
		$coupon_elements = array(
			array(
				"type" => "text",
				"id"   => "coupon_name",
				"name" => esc_html__('Coupons name','discy')
			),
			array(
				"type"    => "select",
				"id"      => "coupon_type",
				"name"    => esc_html__('Discount type','discy'),
				"options" => array("discount" => esc_html__("Discount","discy"),"percent" => esc_html__("% Percent","discy"))
			),
			array(
				"type" => "text",
				"id"   => "coupon_amount",
				"name" => esc_html__('Amount','discy')
			),
			array(
				"type" => "date",
				"id"   => "coupon_date",
				"name" => esc_html__('Expiry date','discy')
			)
		);
		
		$options[] = array(
			'id'      => "coupons",
			'type'    => "elements",
			'sort'    => "no",
			'hide'    => "yes",
			'button'  => esc_html__('Add a new coupon','discy'),
			'options' => $coupon_elements,
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

		$options = apply_filters('discy_options_after_coupons_setting',$options);
	}
	
	$options[] = array(
		'name' => esc_html__('Captcha settings','discy'),
		'id'   => 'captcha',
		'icon' => 'admin-network',
		'type' => 'heading'
	);
	
	$options[] = array(
		'type' => 'heading-2'
	);
	
	$options[] = array(
		'name' => esc_html__('Captcha enable or disable (in ask question form)','discy'),
		'id'   => 'the_captcha',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Captcha enable or disable (in add group form)','discy'),
		'id'   => 'the_captcha_group',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Captcha enable or disable (in add post form)','discy'),
		'id'   => 'the_captcha_post',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Captcha enable or disable (in register form)','discy'),
		'id'   => 'the_captcha_register',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Captcha enable or disable (in login form)','discy'),
		'id'   => 'the_captcha_login',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Captcha enable or disable (in forgot password form)','discy'),
		'id'   => 'the_captcha_password',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Captcha enable or disable (in answer form)','discy'),
		'id'   => 'the_captcha_answer',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Captcha enable or disable (in comment form)','discy'),
		'id'   => 'the_captcha_comment',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Captcha enable or disable (in send message form)','discy'),
		'id'   => 'the_captcha_message',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Captcha enable or disable (in add a new category form)','discy'),
		'id'   => 'the_captcha_category',
		'type' => 'checkbox'
	);

	$options = apply_filters('discy_options_captcha',$options);
	$captcha_condition_users = apply_filters('discy_captcha_condition_users','the_captcha:not(0),the_captcha_post:not(0),the_captcha_category:not(0),the_captcha_answer:not(0),the_captcha_comment:not(0),the_captcha_message:not(0)');
	$captcha_condition = apply_filters('discy_captcha_condition','the_captcha:not(0),the_captcha_post:not(0),the_captcha_category:not(0),the_captcha_register:not(0),the_captcha_login:not(0),the_captcha_password:not(0),the_captcha_answer:not(0),the_captcha_comment:not(0),the_captcha_message:not(0)');
	
	$options[] = array(
		'name'      => esc_html__('Captcha works for "unlogged users" or "unlogged and logged" users','discy'),
		'id'        => 'captcha_users',
		'std'       => 'unlogged',
		'operator'  => 'or',
		'condition' => $captcha_condition_users,
		'type'      => 'radio',
		'options'   => 
			array(
				"unlogged" => esc_html__('Unlogged users','discy'),
				"both"     => esc_html__('Unlogged and logged in users','discy')
		)
	);
	
	$options[] = array(
		'div'       => 'div',
		'operator'  => 'or',
		'condition' => $captcha_condition,
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'name'    => esc_html__('Captcha style','discy'),
		'desc'    => esc_html__('Choose the captcha style','discy'),
		'id'      => 'captcha_style',
		'std'     => 'question_answer',
		'type'    => 'radio',
		'options' => 
			array(
				"question_answer"  => esc_html__('Question and answer','discy'),
				"normal_captcha"   => esc_html__('Normal captcha','discy'),
				"google_recaptcha" => esc_html__('Google reCaptcha','discy')
		)
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'captcha_style:is(google_recaptcha)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'name' => sprintf(esc_html__('You can get the reCaptcha v2 site and secret keys from: %s','discy'),'<a href="https://www.google.com/recaptcha/admin/" target="_blank">'.esc_html__('here','discy').'</a>'),
		'type' => 'info'
	);
	
	$options[] = array(
		'name' => esc_html__('Site key reCaptcha','discy'),
		'id'   => 'site_key_recaptcha',
		'type' => 'text',
	);
	
	$options[] = array(
		'name' => esc_html__('Secret key reCaptcha','discy'),
		'id'   => 'secret_key_recaptcha',
		'type' => 'text',
	);
	
	$options[] = array(
		'name' => sprintf(esc_html__('You can get the reCaptcha langauge code from: %s','discy'),'<a href="https://developers.google.com/recaptcha/docs/language/" target="_blank">'.esc_html__('here','discy').'</a>'),
		'type' => 'info'
	);
	
	$options[] = array(
		'name' => esc_html__('ReCaptcha langauge','discy'),
		'id'   => 'recaptcha_langauge',
		'type' => 'text',
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'captcha_style:is(question_answer)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'name' => esc_html__('Captcha answer enable or disable in forms','discy'),
		'id'   => 'show_captcha_answer',
		'std'  => "on",
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Captcha question','discy'),
		'desc' => esc_html__('put the Captcha question','discy'),
		'id'   => 'captcha_question',
		'type' => 'text',
		'std'  => "What is the capital of Egypt?"
	);
	
	$options[] = array(
		'name' => esc_html__('Captcha answer','discy'),
		'desc' => esc_html__('put the Captcha answer','discy'),
		'id'   => 'captcha_answer',
		'type' => 'text',
		'std'  => "Cairo"
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
		'name' => esc_html__('User settings','discy'),
		'id'   => 'user',
		'icon' => 'admin-users',
		'type' => 'heading',
		'std'     => 'setting_profile',
		'options' => array(
			"setting_profile"    => esc_html__('General Setting','discy'),
			"user_slugs"         => esc_html__('User Slugs','discy'),
			"login_setting"      => esc_html__('Login Setting','discy'),
			"register_setting"   => esc_html__('Register Setting','discy'),
			"edit_profile"       => esc_html__('Edit Profile','discy'),
			"ask_users"          => esc_html__('Ask Users','discy'),
			"referral_setting"   => esc_html__('Referral setting','discy'),
			"popup_notification" => esc_html__('Popup Notification','discy'),
			"permissions"        => esc_html__('Permissions','discy'),
			"author_setting"     => esc_html__('Author Setting','discy')
		)
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'id'   => 'setting_profile',
		'name' => esc_html__('General Setting','discy')
	);
	
	$options[] = array(
		'name' => esc_html__('Author info box enable or disable.','discy'),
		'id'   => 'author_box',
		'std'  => 'on',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Profile picture setting','discy'),
		'type' => 'info'
	);

	$options[] = array(
		'name' => esc_html__('Default image profile enable or disable.','discy'),
		'desc' => esc_html__("Select ON to upload your default image for the user who has not uploaded the image profile.","discy"),
		'id'   => 'default_image_active',
		'type' => 'checkbox'
	);

	$options[] = array(
		'div'       => 'div',
		'condition' => 'default_image_active:not(0)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'name' => esc_html__('Upload default image profile for the user.','discy'),
		'id'   => 'default_image',
		'std'  => $imagepath_theme."default-image.png",
		'type' => 'upload'
	);
	
	$options[] = array(
		'name' => esc_html__('Upload default image profile for the user females.','discy'),
		'id'   => 'default_image_females',
		'std'  => $imagepath_theme."default-image-females.png",
		'type' => 'upload'
	);
	
	$options[] = array(
		'name' => esc_html__('Upload default image profile for the anonymous users.','discy'),
		'id'   => 'default_image_anonymous',
		'type' => 'upload'
	);

	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);

	$options[] = array(
		'name'      => esc_html__('Add the maximum size for the profile picture, Add it with KB, for 1 MB add 1024.','discy'),
		'desc'      => esc_html__('Add the maximum size for the profile picture, Leave it empty if you need it unlimited size.','discy'),
		'id'        => 'profile_picture_size',
		'condition' => 'register_items:has(image_profile),edit_profile_items_1:has(image_profile)',
		'operator'  => 'or',
		'type'      => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('Cover picture setting','discy'),
		'type' => 'info'
	);
	
	$options[] = array(
		'name' => esc_html__('Cover image enable or disable.','discy'),
		'id'   => 'cover_image',
		'std'  => 'on',
		'type' => 'checkbox'
	);

	$options[] = array(
		'div'       => 'div',
		'condition' => 'cover_image:is(on)',
		'type'      => 'heading-2'
	);

	$options[] = array(
		'name' => esc_html__('Default cover enable or disable.','discy'),
		'desc' => esc_html__("Select ON to upload your default cover for the user who has not uploaded the cover profile.","discy"),
		'id'   => 'default_cover_active',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name'      => esc_html__('Upload default cover for the user.','discy'),
		'id'        => 'default_cover',
		'condition' => 'default_cover_active:not(0)',
		'type'      => 'upload'
	);
	
	$options[] = array(
		'name'      => esc_html__('Upload default cover for the user females.','discy'),
		'id'        => 'default_cover_females',
		'condition' => 'default_cover_active:not(0)',
		'type'      => 'upload'
	);
	
	$options[] = array(
		'name'      => esc_html__('Add the maximum size for the profile picture, Add it with KB, for 1 MB add 1024.','discy'),
		'desc'      => esc_html__('Add the maximum size for the profile picture, Leave it empty if you need it unlimited size.','discy'),
		'id'        => 'profile_cover_size',
		'condition' => 'register_items:has(cover),edit_profile_items_1:has(cover)',
		'operator'  => 'or',
		'type'      => 'text'
	);
	
	$options[] = array(
		'name'      => esc_html__('Cover full width or fixed','discy'),
		'desc'      => esc_html__('Choose the cover to make it work with full width or fixed.','discy'),
		'id'        => 'cover_fixed',
		'options'   => array(
			'normal' => esc_html__('Full width','discy'),
			'fixed'  => esc_html__('Fixed','discy'),
		),
		'std'       => 'normal',
		'condition' => 'cover_image:is(on)',
		'type'      => 'radio'
	);

	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name'      => esc_html__('Activate other at the gender.','discy'),
		'id'        => 'gender_other',
		'condition' => 'register_items:has(gender),edit_profile_items_1:has(gender)',
		'operator'  => 'or',
		'type'      => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Profile by login or nicename','discy'),
		'desc' => esc_html__('Choose the user profile page work by login or nicename.','discy'),
		'id'   => 'profile_type',
		'options' => array(
			'nicename' => esc_html__('Nicename','discy'),
			'login'    => esc_html__('Login name','discy'),
		),
		'std'     => 'nicename',
		'type'    => 'radio'
	);
	
	$options[] = array(
		'name' => esc_html__('Activate the notifications system in site?','discy'),
		'desc' => esc_html__('Activate the notifications system enable or disable.','discy'),
		'id'   => 'active_notifications',
		'std'  => "on",
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'active_notifications:not(0)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'name'    => esc_html__('Choose the roles or users for the custom notification','discy'),
		'desc'    => esc_html__('Choose from here which roles or users you want to send the custom notification.','discy'),
		'id'      => 'notification_groups_users',
		'options' => array(
			'groups' => esc_html__('Roles','discy'),
			'users'  => esc_html__('Users','discy'),
		),
		'std'     => 'groups',
		'type'    => 'radio'
	);

	$options[] = array(
		'name'      => esc_html__("Choose the roles that you want to send the custom notification.","discy"),
		'id'        => 'custom_notification_groups',
		'condition' => 'notification_groups_users:not(users)',
		'type'      => 'multicheck',
		'options'   => $new_roles,
		'std'       => array('administrator' => 'administrator','editor' => 'editor','contributor' => 'contributor','subscriber' => 'subscriber','author' => 'author'),
	);

	$options[] = array(
		'name'      => esc_html__('Specific user ids','discy'),
		'id'        => 'notification_specific_users',
		'condition' => 'notification_groups_users:is(users)',
		'type'      => 'text'
	);

	$options[] = array(
		'name' => esc_html__('Custom notification','discy'),
		'id'   => 'custom_notification',
		'std'  => 'Welcome',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('You must save your options before sending the notification.','discy'),
		'type' => 'info'
	);

	$options[] = array(
		'name' => '<a href="#" class="button button-primary send-custom-notification">'.esc_html__('Send the custom notification','discy').'</a>',
		'type' => 'info'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name' => esc_html__('Activate the activity log site?','discy'),
		'desc' => esc_html__('Activate the activity log enable or disable.','discy'),
		'id'   => 'active_activity_log',
		'std'  => "on",
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name'    => esc_html__('Select the user stats','discy'),
		'id'      => 'user_stats',
		'type'    => 'multicheck',
		'std'     => array(
			"questions"    => "questions",
			"answers"      => "answers",
			"best_answers" => "best_answers",
			"points"       => "points",
		),
		'options' => array(
			"questions"    => esc_html__('Questions','discy'),
			"answers"      => esc_html__('Answers','discy'),
			"best_answers" => esc_html__('Best Answers','discy'),
			"points"       => esc_html__('Points','discy'),
		)
	);
	
	$options[] = array(
		'name'    => "",
		"margin"  => "-15px 0 0",
		'id'      => 'user_stats_2',
		'type'    => 'multicheck',
		'std'     => array(
			"i_follow"     => "i_follow",
			"followers"    => "followers",
		),
		'options' => array(
			"i_follow"     => esc_html__('Authors I Follow','discy'),
			"followers"    => esc_html__('Followers','discy'),
		)
	);
	
	$user_profile_pages = array(
		"questions"           => array("sort" => esc_html__('Questions','discy'),"value" => "questions"),
		"polls"               => array("sort" => esc_html__('Polls','discy'),"value" => "polls"),
		"answers"             => array("sort" => esc_html__('Answers','discy'),"value" => "answers"),
		"best-answers"        => array("sort" => esc_html__('Best Answers','discy'),"value" => "best-answers"),
		"asked"               => array("sort" => esc_html__('Asked Questions','discy'),"value" => ""),
		"asked-questions"     => array("sort" => esc_html__('Waiting Questions','discy'),"value" => ""),
		"paid-questions"      => array("sort" => esc_html__('Paid Questions','discy'),"value" => ""),
		"followed"            => array("sort" => esc_html__('Followed Questions','discy'),"value" => "followed"),
		"favorites"           => array("sort" => esc_html__('Favorite Questions','discy'),"value" => "favorites"),
		"groups"              => array("sort" => esc_html__('Groups','discy'),"value" => "groups"),
		"posts"               => array("sort" => esc_html__('Posts','discy'),"value" => ""),
		"comments"            => array("sort" => esc_html__('Comments','discy'),"value" => ""),
		"followers-questions" => array("sort" => esc_html__('Followers Questions','discy'),"value" => ""),
		"followers-answers"   => array("sort" => esc_html__('Followers Answers','discy'),"value" => ""),
		"followers-posts"     => array("sort" => esc_html__('Followers Posts','discy'),"value" => ""),
		"followers-comments"  => array("sort" => esc_html__('Followers Comments','discy'),"value" => ""),
	);
	
	$options[] = array(
		'name'         => esc_html__('Select the pages to show at the user profile page','discy'),
		'id'           => 'user_profile_pages',
		'type'         => 'multicheck',
		'sort'         => 'yes',
		'limit-height' => 'yes',
		'std'          => $user_profile_pages,
		'options'      => $user_profile_pages
	);
	
	$options[] = array(
		'name'    => esc_html__('Select the columns in the user admin','discy'),
		'id'      => 'user_meta_admin',
		'type'    => 'multicheck',
		'options' => array(
			"phone"      => esc_html__('Phone','discy'),
			"country"    => esc_html__('Country','discy'),
			"age"        => esc_html__('Age','discy'),
			"points"     => esc_html__('Points','discy'),
			"invitation" => esc_html__('Invitation','discy'),
		)
	);
	
	$options[] = array(
		'name'    => esc_html__('Users style at followed and search pages','discy'),
		'desc'    => esc_html__('Choose the users style at followed and search pages.','discy'),
		'id'      => 'user_style_pages',
		'options' => array(
			'small_grid'    => esc_html__('Small grid with follow','discy'),
			'columns'       => esc_html__('Columns','discy'),
			'simple_follow' => esc_html__('Simple with follow','discy'),
			'small'         => esc_html__('Small','discy'),
			'grid'          => esc_html__('Grid','discy'),
			'normal'        => esc_html__('Normal','discy'),
		),
		'std'     => 'small_grid',
		'type'    => 'radio'
	);
	
	$options[] = array(
		'name'      => esc_html__("Activate the masonry style?","discy"),
		'id'        => 'masonry_user_style',
		'type'      => 'checkbox',
		'condition' => 'user_style_pages:is(small_grid),user_style_pages:is(columns),user_style_pages:is(small),user_style_pages:is(grid)',
		'operator'  => 'or',
	);
	
	$options[] = array(
		'name' => esc_html__('Users per page at home, followed and search pages','discy'),
		'desc' => esc_html__('Put the users per page at home, followed and search pages.','discy'),
		'id'   => 'users_per_page',
		'std'  => '10',
		'type' => 'text'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'id'   => 'user_slugs',
		'name' => esc_html__('User Slugs','discy')
	);

	$options = apply_filters('discy_options_user_slugs',$options);
	
	$options[] = array(
		'name'      => esc_html__('User profile slug','discy'),
		'desc'      => esc_html__('Put the user profile slug.','discy'),
		'id'        => 'profile_slug',
		'std'       => 'profile',
		'type'      => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('Login slug','discy'),
		'desc' => esc_html__('Put the login slug.','discy'),
		'id'   => 'login_slug',
		'std'  => 'log-in',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('Signup slug','discy'),
		'desc' => esc_html__('Put the signup slug.','discy'),
		'id'   => 'signup_slug',
		'std'  => 'sign-up',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('Lost password slug','discy'),
		'desc' => esc_html__('Put the lost password slug.','discy'),
		'id'   => 'lost_password_slug',
		'std'  => 'lost-password',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('Edit profile slug','discy'),
		'desc' => esc_html__('Put the edit profile slug.','discy'),
		'id'   => 'edit_slug',
		'std'  => 'edit',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('Change password profile slug','discy'),
		'desc' => esc_html__('Put the change password slug.','discy'),
		'id'   => 'password_slug',
		'std'  => 'change-password',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('Privacy profile slug','discy'),
		'desc' => esc_html__('Put the privacy profile slug.','discy'),
		'id'   => 'privacy_slug',
		'std'  => 'privacy',
		'type' => 'text'
	);

	$options[] = array(
		'name' => esc_html__('Withdrawals profile slug','discy'),
		'desc' => esc_html__('Put the withdrawals profile slug.','discy'),
		'id'   => 'withdrawals_slug',
		'std'  => 'withdrawals',
		'type' => 'text'
	);

	$options[] = array(
		'name' => esc_html__('Financial profile slug','discy'),
		'desc' => esc_html__('Put the financial profile slug.','discy'),
		'id'   => 'financial_slug',
		'std'  => 'financial',
		'type' => 'text'
	);

	$options[] = array(
		'name' => esc_html__('Transactions profile slug','discy'),
		'desc' => esc_html__('Put the transactions profile slug.','discy'),
		'id'   => 'transactions_slug',
		'std'  => 'transactions',
		'type' => 'text'
	);

	$options[] = array(
		'name' => esc_html__('Mails profile slug','discy'),
		'desc' => esc_html__('Put the mails profile slug.','discy'),
		'id'   => 'mails_slug',
		'std'  => 'mails',
		'type' => 'hidden'
	);
	
	$options[] = array(
		'name' => esc_html__('Delete profile slug','discy'),
		'desc' => esc_html__('Put the delete profile slug.','discy'),
		'id'   => 'delete_slug',
		'std'  => 'delete',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('User followers slug','discy'),
		'desc' => esc_html__('Put the user followers slug.','discy'),
		'id'   => 'followers_slug',
		'std'  => 'followers',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('User following slug','discy'),
		'desc' => esc_html__('Put the user following slug.','discy'),
		'id'   => 'following_slug',
		'std'  => 'following',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('User notifications slug','discy'),
		'desc' => esc_html__('Put the user notifications slug.','discy'),
		'id'   => 'notifications_slug',
		'std'  => 'notifications',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('User activities slug','discy'),
		'desc' => esc_html__('Put the user activities slug.','discy'),
		'id'   => 'activities_slug',
		'std'  => 'activities',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('User questions slug','discy'),
		'desc' => esc_html__('Put the user questions slug.','discy'),
		'id'   => 'questions_slug',
		'std'  => 'questions',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('User answers slug','discy'),
		'desc' => esc_html__('Put the user answers slug.','discy'),
		'id'   => 'answers_slug',
		'std'  => 'answers',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('User best answers slug','discy'),
		'desc' => esc_html__('Put the user best answers slug.','discy'),
		'id'   => 'best_answers_slug',
		'std'  => 'best-answers',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('User groups slug','discy'),
		'desc' => esc_html__('Put the user groups slug.','discy'),
		'id'   => 'groups_slug',
		'std'  => 'groups',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('User points slug','discy'),
		'desc' => esc_html__('Put the user points slug.','discy'),
		'id'   => 'points_slug',
		'std'  => 'points',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('User polls slug','discy'),
		'desc' => esc_html__('Put the user polls slug.','discy'),
		'id'   => 'polls_slug',
		'std'  => 'polls',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('User asked slug','discy'),
		'desc' => esc_html__('Put the user asked slug.','discy'),
		'id'   => 'asked_slug',
		'std'  => 'asked',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('User asked questions slug','discy'),
		'desc' => esc_html__('Put the user asked questions slug.','discy'),
		'id'   => 'asked_questions_slug',
		'std'  => 'asked-questions',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('User paid questions slug','discy'),
		'desc' => esc_html__('Put the user paid questions slug.','discy'),
		'id'   => 'paid_questions_slug',
		'std'  => 'paid-questions',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('User followed slug','discy'),
		'desc' => esc_html__('Put the user followed slug.','discy'),
		'id'   => 'followed_slug',
		'std'  => 'followed',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('User favorites slug','discy'),
		'desc' => esc_html__('Put the user favorites slug.','discy'),
		'id'   => 'favorites_slug',
		'std'  => 'favorites',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('User posts slug','discy'),
		'desc' => esc_html__('Put the user posts slug.','discy'),
		'id'   => 'posts_slug',
		'std'  => 'posts',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('User comments slug','discy'),
		'desc' => esc_html__('Put the user comments slug.','discy'),
		'id'   => 'comments_slug',
		'std'  => 'comments',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('User followers questions slug','discy'),
		'desc' => esc_html__('Put the user followers questions slug.','discy'),
		'id'   => 'followers_questions_slug',
		'std'  => 'followers-questions',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('User followers answers slug','discy'),
		'desc' => esc_html__('Put the user followers answers slug.','discy'),
		'id'   => 'followers_answers_slug',
		'std'  => 'followers-answers',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('User followers posts slug','discy'),
		'desc' => esc_html__('Put the user followers posts slug.','discy'),
		'id'   => 'followers_posts_slug',
		'std'  => 'followers-posts',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('User followers comments slug','discy'),
		'desc' => esc_html__('Put the user followers comments slug.','discy'),
		'id'   => 'followers_comments_slug',
		'std'  => 'followers-comments',
		'type' => 'text'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'id'   => 'login_setting',
		'name' => esc_html__('Login Setting','discy')
	);
	
	$options[] = array(
		'name' => esc_html__('Make the login works without ajax','discy'),
		'desc' => esc_html__('Select ON if you want to make the login works without ajax to avoid the problems with the cache plugin.','discy'),
		'id'   => 'stop_login_ajax',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Make the login works with popup','discy'),
		'desc' => esc_html__('Select ON if you want to make the login works with popup.','discy'),
		'id'   => 'login_popup',
		'std'  => 'on',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name'    => esc_html__('After login go to?','discy'),
		'id'      => 'after_login',
		'std'     => "same_page",
		'type'    => 'select',
		'options' => array("same_page" => esc_html__("Same page","discy"),"home" => esc_html__("Home","discy"),"profile" => esc_html__("Profile","discy"),"custom_link" => esc_html__("Custom link","discy"))
	);
	
	$options[] = array(
		'name'      => esc_html__("Type the link if you don't like above","discy"),
		'id'        => 'after_login_link',
		'condition' => 'after_login:is(custom_link)',
		'type'      => 'text'
	);
	
	$options[] = array(
		'name'    => esc_html__('After Log out go to?','discy'),
		'id'      => 'after_logout',
		'std'     => "same_page",
		'type'    => 'select',
		'options' => array("same_page" => esc_html__("Same page","discy"),"home" => esc_html__("Home","discy"),"custom_link" => esc_html__("Custom link","discy"))
	);
	
	$options[] = array(
		'name'      => esc_html__("Type the link if you don't like above","discy"),
		'id'        => 'after_logout_link',
		'condition' => 'after_logout:is(custom_link)',
		'type'      => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('Activate after x view of pages will need to login','discy'),
		'id'   => 'activate_need_to_login',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name'      => esc_html__('After how many view pages will need to login?','discy'),
		"id"        => "need_login_pages",
		'condition' => 'activate_need_to_login:not(0)',
		"type"      => "sliderui",
		'std'       => '2',
		"step"      => "1",
		"min"       => "1",
		"max"       => "10"
	);
	
	$options[] = array(
		'name'    => esc_html__('Login popup style','discy'),
		'desc'    => esc_html__('Choose login popup style from here.','discy'),
		'id'      => 'login_style',
		'options' => array(
			'style_1' => esc_html__('Style 1','discy'),
			'style_2' => esc_html__('Style 2','discy'),
		),
		'std'     => 'style_1',
		'type'    => 'radio'
	);

	$options[] = array(
		'div'       => 'div',
		'condition' => 'login_style:not(style_2)',
		'type'      => 'heading-2'
	);

	$options[] = array(
		'name'    => esc_html__('Logo for the login popup','discy'),
		'id'      => 'logo_login',
		'type'    => 'upload',
		'options' => array("height" => "logo_login_height","width" => "logo_login_width"),
	);

	$options[] = array(
		'name' => esc_html__('Logo retina for the login popup','discy'),
		'id'   => 'logo_login_retina',
		'type' => 'upload'
	);
	
	$options[] = array(
		'name' => esc_html__('Logo height','discy'),
		"id"   => "logo_login_height",
		"type" => "sliderui",
		'std'  => '45',
		"step" => "1",
		"min"  => "0",
		"max"  => "80"
	);
	
	$options[] = array(
		'name' => esc_html__('Logo width','discy'),
		"id"   => "logo_login_width",
		"type" => "sliderui",
		'std'  => '137',
		"step" => "1",
		"min"  => "0",
		"max"  => "170"
	);

	$options[] = array(
		'name' => esc_html__('Text for the login popup after the logo or the normal text','discy'),
		'id'   => 'text_login',
		'type' => 'text'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);

	$options[] = array(
		'div'       => 'div',
		'condition' => 'login_style:is(style_2)',
		'type'      => 'heading-2'
	);

	$options[] = array(
		'name' => esc_html__('Login image','discy'),
		'id'   => 'login_image',
		'type' => 'upload'
	);
	
	$options[] = array(
		"type" => "textarea",
		"id"   => "login_details",
		"std"  => "Login to our social questions & Answers Engine to ask questions answer people's questions & connect with other people.",
		"name" => esc_html__('Details for login popup','discy')
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
		'type' => 'heading-2',
		'id'   => 'register_setting',
		'name' => esc_html__('Register Setting','discy')
	);
	
	$options[] = array(
		'name' => esc_html__('Make the register works without ajax','discy'),
		'desc' => esc_html__('Select ON if you want to make the register works without ajax to avoid the problems with the cache plugin.','discy'),
		'id'   => 'stop_register_ajax',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Make the register works with popup','discy'),
		'desc' => esc_html__('Select ON if you want to make the register works with popup.','discy'),
		'id'   => 'register_popup',
		'std'  => 'on',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name'    => esc_html__('Register in default role','discy'),
		'desc'    => esc_html__('Select the default role when users registered.','discy'),
		'id'      => 'default_group',
		'std'     => 'subscriber',
		'type'    => 'select',
		'options' => discy_options_roles()
	);
	
	$options[] = array(
		'name'    => esc_html__('Add the black list emails or any domain to stop them from registering into the site','discy'),
		'id'      => "black_list_emails",
		'type'    => "elements",
		'sort'    => "no",
		'hide'    => "yes",
		'button'  => esc_html__('Add a new email','discy'),
		'options' => array(
			array(
				"type" => "text",
				"id"   => "email",
				"name" => esc_html__("Email or domain","discy")
			)
		),
	);
	
	$options[] = array(
		'name'    => esc_html__('After register go to?','discy'),
		'id'      => 'after_register',
		'std'     => "same_page",
		'type'    => 'select',
		'options' => array("same_page" => esc_html__("Same page","discy"),"home" => esc_html__("Home","discy"),"profile" => esc_html__("Profile","discy"),"custom_link" => esc_html__("Custom link","discy"))
	);
	
	$options[] = array(
		'name'      => esc_html__("Type the link if you don't like above","discy"),
		'id'        => 'after_register_link',
		'condition' => 'after_register:is(custom_link)',
		'type'      => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('Send a welcome email when the user is registered','discy'),
		'desc' => esc_html__('Welcome mail for user review enable or disable.','discy'),
		'id'   => 'send_welcome_mail',
		'std'  => 'on',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => esc_html__('The membership under review?','discy'),
		'desc' => esc_html__('Select ON to review the users before the registration is completed.','discy'),
		'id'   => 'user_review',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name'      => esc_html__('Send mail when user needs a review','discy'),
		'desc'      => esc_html__('Mail for user review enable or disable.','discy'),
		'id'        => 'send_email_users_review',
		'std'       => 'on',
		'condition' => 'user_review:not(0)',
		'type'      => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Confirm email enable or disable','discy'),
		'id'   => 'confirm_email',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => esc_html__('Make this site for the registered users only?','discy'),
		'desc' => esc_html__('Select ON to activate the site for the registered users only.','discy'),
		'id'   => 'site_users_only',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'site_users_only:not(0)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'type' => 'info',
		'name' => esc_html__('Un-register page setting','discy')
	);
	
	$options[] = array(
		'name'    => esc_html__('Page style','discy'),
		'desc'    => esc_html__('Choose page style from here.','discy'),
		'id'      => 'register_style',
		'options' => array(
			'style_1'  => 'Style 1',
			'style_2'  => 'Style 2',
		),
		'std'     => 'style_1',
		'type'    => 'radio'
	);
	
	$options[] = array(
		'name'    => esc_html__('Upload the background','discy'),
		'desc'    => esc_html__('Upload the background for the un-register page','discy'),
		'id'      => 'register_background',
		'type'    => 'background',
		'options' => array('color' => '','image' => ''),
		'std'     => array(
			'color' => '#272930',
			'image' => $imagepath_theme."register.png"
		)
	);
	
	$options[] = array(
		"name" => esc_html__('Choose the background opacity','discy'),
		"desc" => esc_html__('Choose the background opacity from here','discy'),
		"id"   => "register_opacity",
		"type" => "sliderui",
		'std'  => 30,
		"step" => "5",
		"min"  => "0",
		"max"  => "100"
	);
	
	$options[] = array(
		'name'    => esc_html__("Choose from here which menu will show for un-registered users.","discy"),
		'id'      => 'register_menu',
		'type'    => 'select',
		'options' => $menus
	);
	
	$options[] = array(
		'name' => esc_html__('The headline','discy'),
		'desc' => esc_html__('Type the Headline from here','discy'),
		'id'   => 'register_headline',
		'type' => 'text',
		'std'  => "Join the world's biggest Q & A network!"
	);
	
	$options[] = array(
		'name' => esc_html__('The paragraph','discy'),
		'desc' => esc_html__('Type the Paragraph from here','discy'),
		'id'   => 'register_paragraph',
		'type' => 'textarea',
		'std'  => "Login to our social questions & Answers Engine to ask questions answer people's questions & connect with other people."
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name' => esc_html__('Click ON to remove the confirm password from the register form.','discy'),
		'id'   => 'comfirm_password',
		'type' => 'checkbox'
	);

	$register_items = array(
		"username"      => array("sort" => esc_html__('Username','discy'),"value" => "username","default" => "yes"),
		"email"         => array("sort" => esc_html__('E-mail','discy'),"value" => "email","default" => "yes"),
		"password"      => array("sort" => esc_html__('Password','discy'),"value" => "username","default" => "yes"),
		"nickname"      => array("sort" => esc_html__('Nickname','discy'),"value" => "nickname"),
		"first_name"    => array("sort" => esc_html__('First Name','discy'),"value" => "first_name"),
		"last_name"     => array("sort" => esc_html__('Last Name','discy'),"value" => "last_name"),
		"display_name"  => array("sort" => esc_html__('Display Name','discy'),"value" => "display_name"),
		"image_profile" => array("sort" => esc_html__('Image Profile','discy'),"value" => "image_profile"),
		"cover"         => array("sort" => esc_html__('Cover','discy'),"value" => "cover"),
		"country"       => array("sort" => esc_html__('Country','discy'),"value" => "country"),
		"city"          => array("sort" => esc_html__('City','discy'),"value" => "city"),
		"phone"         => array("sort" => esc_html__('Phone','discy'),"value" => "phone"),
		"gender"        => array("sort" => esc_html__('Gender','discy'),"value" => "gender"),
		"age"           => array("sort" => esc_html__('Age','discy'),"value" => "age"),
	);
	$register_items_std = array(
		"username"      => array("sort" => esc_html__('Username','discy'),"value" => "username","default" => "yes"),
		"email"         => array("sort" => esc_html__('E-mail','discy'),"value" => "email","default" => "yes"),
		"password"      => array("sort" => esc_html__('Password','discy'),"value" => "username","default" => "yes"),
		"nickname"      => array("sort" => esc_html__('Nickname','discy'),"value" => ""),
		"first_name"    => array("sort" => esc_html__('First Name','discy'),"value" => ""),
		"last_name"     => array("sort" => esc_html__('Last Name','discy'),"value" => ""),
		"display_name"  => array("sort" => esc_html__('Display Name','discy'),"value" => ""),
		"image_profile" => array("sort" => esc_html__('Image Profile','discy'),"value" => ""),
		"cover"         => array("sort" => esc_html__('Cover','discy'),"value" => ""),
		"country"       => array("sort" => esc_html__('Country','discy'),"value" => ""),
		"city"          => array("sort" => esc_html__('City','discy'),"value" => ""),
		"phone"         => array("sort" => esc_html__('Phone','discy'),"value" => ""),
		"gender"        => array("sort" => esc_html__('Gender','discy'),"value" => ""),
		"age"           => array("sort" => esc_html__('Age','discy'),"value" => ""),
	);

	$options[] = array(
		'name'    => esc_html__("Select what to show at register form","discy"),
		'id'      => 'register_items',
		'type'    => 'multicheck',
		'sort'    => 'yes',
		'options' => $register_items,
		'std'     => $register_items_std
	);

	$options[] = array(
		'div'       => 'div',
		'condition' => 'register_items:has(first_name),register_items:has(last_name),register_items:has(display_name),register_items:has(image_profile),register_items:has(cover),register_items:has(gender),register_items:has(country),register_items:has(city),register_items:has(phone),register_items:has(age)',
		'operator'  => 'or',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'name' => esc_html__('Required setting','discy'),
		'type' => 'info'
	);
	
	$options[] = array(
		'name'      => esc_html__('First name in register is required.','discy'),
		'id'        => 'first_name_required_register',
		'condition' => 'register_items:has(first_name)',
		'type'      => 'checkbox'
	);
	
	$options[] = array(
		'name'      => esc_html__('Last name in register is required.','discy'),
		'id'        => 'last_name_required_register',
		'condition' => 'register_items:has(last_name)',
		'type'      => 'checkbox'
	);
	
	$options[] = array(
		'name'      => esc_html__('Display name in register is required.','discy'),
		'id'        => 'display_name_required_register',
		'condition' => 'register_items:has(display_name)',
		'type'      => 'checkbox'
	);
	
	$options[] = array(
		'name'      => esc_html__('Profile picture in register is required','discy'),
		'id'        => 'profile_picture_required_register',
		'condition' => 'register_items:has(image_profile)',
		'type'      => 'checkbox'
	);
	
	$options[] = array(
		'name'      => esc_html__('Profile cover in register is required','discy'),
		'id'        => 'profile_cover_required_register',
		'condition' => 'register_items:has(cover),cover_image:is(on)',
		'type'      => 'checkbox'
	);
	
	$options[] = array(
		'name'      => esc_html__('Gender in register is required.','discy'),
		'id'        => 'gender_required_register',
		'condition' => 'register_items:has(gender)',
		'type'      => 'checkbox'
	);
	
	$options[] = array(
		'name'      => esc_html__('Country in register is required.','discy'),
		'id'        => 'country_required_register',
		'condition' => 'register_items:has(country)',
		'type'      => 'checkbox'
	);
	
	$options[] = array(
		'name'      => esc_html__('City in register is required.','discy'),
		'id'        => 'city_required_register',
		'condition' => 'register_items:has(city)',
		'type'      => 'checkbox'
	);
	
	$options[] = array(
		'name'      => esc_html__('Phone in register is required.','discy'),
		'id'        => 'phone_required_register',
		'condition' => 'register_items:has(phone)',
		'type'      => 'checkbox'
	);
	
	$options[] = array(
		'name'      => esc_html__('Age in register is required.','discy'),
		'id'        => 'age_required_register',
		'condition' => 'register_items:has(age)',
		'type'      => 'checkbox'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name' => esc_html__('Terms of Service and Privacy Policy','discy'),
		'type' => 'info',
	);
	
	$options[] = array(
		'name' => esc_html__('Activate Terms of Service and privacy policy page?','discy'),
		'desc' => esc_html__('Select ON if you want active Terms of Service and privacy policy page.','discy'),
		'id'   => 'terms_active_register',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'terms_active_register:not(0)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'name' => esc_html__('Select the checked by default option','discy'),
		'desc' => esc_html__('Select ON if you want to checked it by default.','discy'),
		'id'   => 'terms_checked_register',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name'    => esc_html__('Open the page in same page or a new page?','discy'),
		'id'      => 'terms_active_target_register',
		'std'     => "new_page",
		'type'    => 'select',
		'options' => array("same_page" => esc_html__("Same page","discy"),"new_page" => esc_html__("New page","discy"))
	);
	
	$options[] = array(
		'name'    => esc_html__('Terms page','discy'),
		'desc'    => esc_html__('Select the terms page','discy'),
		'id'      => 'terms_page_register',
		'type'    => 'select',
		'options' => $options_pages
	);
	
	$options[] = array(
		'name' => esc_html__("Type the terms link if you don't like a page","discy"),
		'id'   => 'terms_link_register',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('Activate Privacy Policy','discy'),
		'desc' => esc_html__('Select ON if you want to activate Privacy Policy.','discy'),
		'id'   => 'privacy_policy_register',
		'std'  => "on",
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'privacy_policy_register:not(0)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'name'    => esc_html__('Open the page in same page or a new page?','discy'),
		'id'      => 'privacy_active_target_register',
		'std'     => "new_page",
		'type'    => 'select',
		'options' => array("same_page" => esc_html__("Same page","discy"),"new_page" => esc_html__("New page","discy"))
	);
	
	$options[] = array(
		'name'    => esc_html__('Privacy Policy page','discy'),
		'desc'    => esc_html__('Select the privacy policy page','discy'),
		'id'      => 'privacy_page_register',
		'type'    => 'select',
		'options' => $options_pages
	);
	
	$options[] = array(
		'name' => esc_html__("Type the privacy policy link if you don't like a page","discy"),
		'id'   => 'privacy_link_register',
		'type' => 'text'
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
		'name' => esc_html__('Select ON to allow for the users register with space','discy'),
		'id'   => 'allow_spaces',
		'std'  => 'on',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name'    => esc_html__('Select the default options when registered','discy'),
		'id'      => 'register_default_options',
		'type'    => 'multicheck',
		'std'     => array(
			"show_point_favorite"     => "show_point_favorite",
			"question_schedules"      => "question_schedules",
			"received_email"          => "received_email",
			"received_message"        => "received_message",
			"new_payment_mail"        => "new_payment_mail",
			"send_message_mail"       => "send_message_mail",
			"answer_on_your_question" => "answer_on_your_question",
			"answer_question_follow"  => "answer_question_follow",
			"notified_reply"          => "notified_reply",
		),
		'options' => array(
			"show_point_favorite"     => esc_html__('Show the private pages','discy'),
			"question_schedules"      => esc_html__('Send schedule mails for the users as a list with recent questions','discy'),
			"received_email"          => esc_html__('Send mail when user ask a new question','discy'),
			"received_message"        => esc_html__("Received message from another users","discy"),
			"new_payment_mail"        => esc_html__("Send mail when made new payment","discy"),
			"send_message_mail"       => esc_html__("Send mail when any user send message","discy"),
			"answer_on_your_question" => esc_html__("Send mail when any user answer on your question","discy"),
			"answer_question_follow"  => esc_html__("Send mail when any user answer on your following question","discy"),
			"notified_reply"          => esc_html__("Send mail when any user reply on your answer","discy"),
			"unsubscribe_mails"       => esc_html__("Unsubscribe form all the mails","discy"),
		)
	);
	
	$options[] = array(
		'name' => esc_html__('Signup setting','discy'),
		'type' => 'info'
	);
	
	$options[] = array(
		'name'    => esc_html__('Signup popup style','discy'),
		'desc'    => esc_html__('Choose signup pop up style from here.','discy'),
		'id'      => 'signup_style',
		'options' => array(
			'style_1' => esc_html__('Style 1','discy'),
			'style_2' => esc_html__('Style 2','discy'),
		),
		'std'     => 'style_1',
		'type'    => 'radio'
	);

	$options[] = array(
		'div'       => 'div',
		'condition' => 'signup_style:not(style_2)',
		'type'      => 'heading-2'
	);

	$options[] = array(
		'name'    => esc_html__('Logo for the signup pop up','discy'),
		'id'      => 'logo_signup',
		'type'    => 'upload',
		'options' => array("height" => "logo_signup_height","width" => "logo_signup_width"),
	);

	$options[] = array(
		'name' => esc_html__('Logo retina for the signup pop up','discy'),
		'id'   => 'logo_signup_retina',
		'type' => 'upload'
	);
	
	$options[] = array(
		'name' => esc_html__('Logo height','discy'),
		"id"   => "logo_signup_height",
		"type" => "sliderui",
		'std'  => '45',
		"step" => "1",
		"min"  => "0",
		"max"  => "80"
	);
	
	$options[] = array(
		'name' => esc_html__('Logo width','discy'),
		"id"   => "logo_signup_width",
		"type" => "sliderui",
		'std'  => '137',
		"step" => "1",
		"min"  => "0",
		"max"  => "170"
	);

	$options[] = array(
		'name' => esc_html__('Text for the signup popup after the logo or the normal text','discy'),
		'id'   => 'text_signup',
		'type' => 'text'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);

	$options[] = array(
		'div'       => 'div',
		'condition' => 'signup_style:is(style_2)',
		'type'      => 'heading-2'
	);

	$options[] = array(
		'name' => esc_html__('Signup image','discy'),
		'id'   => 'signup_image',
		'type' => 'upload'
	);
	
	$options[] = array(
		"type" => "textarea",
		"id"   => "signup_details",
		"std"  => "Sign Up to our social questions and Answers Engine to ask questions, answer people's questions, and connect with other people.",
		"name" => esc_html__('Details for signup pop up','discy')
	);

	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name' => esc_html__('Forgot password setting','discy'),
		'type' => 'info'
	);
	
	$options[] = array(
		'name' => esc_html__('Make the forgot password works with popup','discy'),
		'desc' => esc_html__('Select ON if you want to make the forgot password works with popup.','discy'),
		'id'   => 'lost_pass_popup',
		'std'  => 'on',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name'    => esc_html__('Forgot password pop up style','discy'),
		'desc'    => esc_html__('Choose Forgot password pop up style from here.','discy'),
		'id'      => 'pass_style',
		'options' => array(
			'style_1' => esc_html__('Style 1','discy'),
			'style_2' => esc_html__('Style 2','discy'),
		),
		'std'     => 'style_1',
		'type'    => 'radio'
	);

	$options[] = array(
		'div'       => 'div',
		'condition' => 'pass_style:not(style_2)',
		'type'      => 'heading-2'
	);

	$options[] = array(
		'name'    => esc_html__('Logo for the forgot password pop up','discy'),
		'id'      => 'logo_pass',
		'type'    => 'upload',
		'options' => array("height" => "logo_pass_height","width" => "logo_pass_width"),
	);

	$options[] = array(
		'name' => esc_html__('Logo retina for the forgot password pop up','discy'),
		'id'   => 'logo_pass_retina',
		'type' => 'upload'
	);
	
	$options[] = array(
		'name' => esc_html__('Logo height','discy'),
		"id"   => "logo_pass_height",
		"type" => "sliderui",
		'std'  => '45',
		"step" => "1",
		"min"  => "0",
		"max"  => "80"
	);
	
	$options[] = array(
		'name' => esc_html__('Logo width','discy'),
		"id"   => "logo_pass_width",
		"type" => "sliderui",
		'std'  => '137',
		"step" => "1",
		"min"  => "0",
		"max"  => "170"
	);

	$options[] = array(
		'name' => esc_html__('Text for the forgot password pop up after the logo or the normal text','discy'),
		'id'   => 'text_pass',
		'type' => 'text'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);

	$options[] = array(
		'div'       => 'div',
		'condition' => 'pass_style:is(style_2)',
		'type'      => 'heading-2'
	);

	$options[] = array(
		'name' => esc_html__('Forgot password image','discy'),
		'id'   => 'pass_image',
		'type' => 'upload'
	);
	
	$options[] = array(
		"type" => "textarea",
		"id"   => "pass_details",
		"std"  => "Lost your password? Please enter your email address. You will receive a link and will create a new password via email.",
		"name" => esc_html__('Details for forgot password pop up','discy')
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
		'type' => 'heading-2',
		'id'   => 'edit_profile',
		'name' => esc_html__('Edit Profile','discy')
	);
	
	$edit_profile_items_1 = array(
		"nickname"      => array("sort" => esc_html__('Nickname','discy'),"value" => "nickname"),
		"first_name"    => array("sort" => esc_html__('First Name','discy'),"value" => "first_name"),
		"last_name"     => array("sort" => esc_html__('Last Name','discy'),"value" => "last_name"),
		"display_name"  => array("sort" => esc_html__('Display Name','discy'),"value" => "display_name"),
		"image_profile" => array("sort" => esc_html__('Image Profile','discy'),"value" => "image_profile"),
		"cover"         => array("sort" => esc_html__('Cover','discy'),"value" => "cover"),
		"country"       => array("sort" => esc_html__('Country','discy'),"value" => "country"),
		"city"          => array("sort" => esc_html__('City','discy'),"value" => "city"),
		"phone"         => array("sort" => esc_html__('Phone','discy'),"value" => "phone"),
		"gender"        => array("sort" => esc_html__('Gender','discy'),"value" => "gender"),
		"age"           => array("sort" => esc_html__('Age','discy'),"value" => "age"),
	);
	
	$options[] = array(
		'name'    => esc_html__("Select what to show at edit profile at the Basic Information section","discy"),
		'id'      => 'edit_profile_items_1',
		'type'    => 'multicheck',
		'sort'    => 'yes',
		'std'     => $edit_profile_items_1,
		'options' => $edit_profile_items_1
	);
	
	$options[] = array(
		'name'      => esc_html__('You need to activate the cover option from the User settings/General Setting','discy'),
		'condition' => 'cover_image:not(on),edit_profile_items_1:has(cover)',
		'type'      => 'info'
	);

	$options[] = array(
		'div'       => 'div',
		'condition' => 'edit_profile_items_1:has(first_name),edit_profile_items_1:has(last_name),edit_profile_items_1:has(display_name),edit_profile_items_1:has(image_profile),edit_profile_items_1:has(cover),edit_profile_items_1:has(gender),edit_profile_items_1:has(country),edit_profile_items_1:has(city),edit_profile_items_1:has(phone),edit_profile_items_1:has(age)',
		'operator'  => 'or',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'name' => esc_html__('Required setting','discy'),
		'type' => 'info'
	);
	
	$options[] = array(
		'name'      => esc_html__('First name in edit profile is required.','discy'),
		'id'        => 'first_name_required',
		'condition' => 'edit_profile_items_1:has(first_name)',
		'type'      => 'checkbox'
	);
	
	$options[] = array(
		'name'      => esc_html__('Last name in edit profile is required.','discy'),
		'id'        => 'last_name_required',
		'condition' => 'edit_profile_items_1:has(last_name)',
		'type'      => 'checkbox'
	);
	
	$options[] = array(
		'name'      => esc_html__('Display name in edit profile is required.','discy'),
		'id'        => 'display_name_required',
		'condition' => 'edit_profile_items_1:has(display_name)',
		'type'      => 'checkbox'
	);
	
	$options[] = array(
		'name'      => esc_html__('Profile picture in edit profile is required','discy'),
		'id'        => 'profile_picture_required',
		'condition' => 'edit_profile_items_1:has(image_profile)',
		'type'      => 'checkbox'
	);
	
	$options[] = array(
		'name'      => esc_html__('Profile cover in edit profile is required','discy'),
		'id'        => 'profile_cover_required',
		'condition' => 'edit_profile_items_1:has(cover)',
		'type'      => 'checkbox'
	);
	
	$options[] = array(
		'name'      => esc_html__('Gender in edit profile is required.','discy'),
		'id'        => 'gender_required',
		'condition' => 'edit_profile_items_1:has(gender)',
		'type'      => 'checkbox'
	);
	
	$options[] = array(
		'name'      => esc_html__('Country in edit profile is required.','discy'),
		'id'        => 'country_required',
		'condition' => 'edit_profile_items_1:has(country)',
		'type'      => 'checkbox'
	);
	
	$options[] = array(
		'name'      => esc_html__('City in edit profile is required.','discy'),
		'id'        => 'city_required',
		'condition' => 'edit_profile_items_1:has(city)',
		'type'      => 'checkbox'
	);
	
	$options[] = array(
		'name'      => esc_html__('Phone in edit profile is required.','discy'),
		'id'        => 'phone_required',
		'condition' => 'edit_profile_items_1:has(phone)',
		'type'      => 'checkbox'
	);
	
	$options[] = array(
		'name'      => esc_html__('Age in edit profile is required.','discy'),
		'id'        => 'age_required',
		'condition' => 'edit_profile_items_1:has(age)',
		'type'      => 'checkbox'
	);

	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name' => esc_html__('Social Profiles section','discy'),
		'type' => 'info',
	);
	
	$edit_profile_items_2 = array(
		"facebook"  => array("sort" => esc_html__('Facebook','discy'),"value" => "facebook"),
		"twitter"   => array("sort" => esc_html__('Twitter','discy'),"value" => "twitter"),
		"youtube"   => array("sort" => esc_html__('Youtube','discy'),"value" => "youtube"),
		"vimeo"     => array("sort" => esc_html__('Vimeo','discy'),"value" => "vimeo"),
		"linkedin"  => array("sort" => esc_html__('Linkedin','discy'),"value" => "linkedin"),
		"instagram" => array("sort" => esc_html__('Instagram','discy'),"value" => "instagram"),
		"pinterest" => array("sort" => esc_html__('Pinterest','discy'),"value" => "pinterest"),
	);
	
	$options[] = array(
		'name'    => esc_html__("Select what to show at edit profile at the Social Profiles section","discy"),
		'id'      => 'edit_profile_items_2',
		'type'    => 'multicheck',
		'sort'    => 'yes',
		'std'     => $edit_profile_items_2,
		'options' => $edit_profile_items_2
	);
	
	$options[] = array(
		'name' => esc_html__('About Me section','discy'),
		'type' => 'info',
	);
	
	$edit_profile_items_3 = array(
		"website"            => array("sort" => esc_html__('Website','discy'),"value" => "website"),
		"bio"                => array("sort" => esc_html__('Professional Bio','discy'),"value" => "bio"),
		"profile_credential" => array("sort" => esc_html__('Profile credential','discy'),"value" => "profile_credential"),
		"private_pages"      => array("sort" => esc_html__('Private Pages','discy'),"value" => "private_pages"),
		"received_message"   => array("sort" => esc_html__('Received message from the users','discy'),"value" => "received_message"),
	);
	
	$options[] = array(
		'name'    => esc_html__("Select what to show at edit profile at the About Me section","discy"),
		'id'      => 'edit_profile_items_3',
		'type'    => 'multicheck',
		'sort'    => 'yes',
		'std'     => $edit_profile_items_3,
		'options' => $edit_profile_items_3
	);
	
	$options[] = array(
		'name'      => esc_html__('Editor enable or disable for professional bio','discy'),
		'id'        => 'bio_editor',
		'condition' => 'edit_profile_items_3:has(bio)',
		'type'      => 'checkbox'
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'edit_profile_items_3:has(profile_credential)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'name' => esc_html__('Profile credential in edit profile is required.','discy'),
		'id'   => 'profile_credential_required',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Add the maximum length for the profile credential, leave it empty if you need it unlimited.','discy'),
		'id'   => 'profile_credential_maximum',
		'type' => 'text'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name' => esc_html__('Custom categories at the left menu','discy'),
		'type' => 'info',
	);

	$options[] = array(
		'name' => esc_html__('Do you need to allow the users to add a custom categories at the left menu?','discy'),
		'desc' => esc_html__('Select ON if you need the users to add a custom categories at the left menu.','discy'),
		'id'   => 'custom_left_menu',
		'std'  => 'on',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name'      => esc_html__('Put from here after which number of the items you need to show the custom categories for the left menu','discy'),
		'id'        => 'left_menu_category_after',
		'std'       => '2',
		'condition' => 'custom_left_menu:not(0)',
		'type'      => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('Mails section','discy'),
		'type' => 'info',
	);

	$edit_profile_items_4 = array(
		"question_schedules"      => array("sort" => esc_html__('Send schedule mails for the users as a list with recent questions','discy'),"value" => "question_schedules"),
		"send_emails"             => array("sort" => esc_html__('Send mail when any users ask question','discy'),"value" => "send_emails"),
		"new_payment_mail"        => array("sort" => esc_html__('Send mail when made new payment','discy'),"value" => "new_payment_mail"),
		"send_message_mail"       => array("sort" => esc_html__('Send mail when any user send message','discy'),"value" => "send_message_mail"),
		"answer_on_your_question" => array("sort" => esc_html__('Send mail when any user answer on your question','discy'),"value" => "answer_on_your_question"),
		"answer_question_follow"  => array("sort" => esc_html__('Send mail when any user answer on your following question','discy'),"value" => "answer_question_follow"),
		"notified_reply"          => array("sort" => esc_html__('Send mail when any user reply on your answer','discy'),"value" => "notified_reply"),
		"unsubscribe_mails"       => array("sort" => esc_html__('Unsubscribe form all the mails','discy'),"value" => "unsubscribe_mails"),
	);
	
	$options[] = array(
		'name'    => esc_html__("Select what to show at edit profile at the mails section","discy"),
		'id'      => 'edit_profile_items_4',
		'type'    => 'multicheck',
		'sort'    => 'yes',
		'std'     => $edit_profile_items_4,
		'options' => $edit_profile_items_4
	);
	
	$options[] = array(
		'name' => esc_html__('Privacy account','discy'),
		'type' => 'info',
	);

	$options[] = array(
		'name' => esc_html__('Do you like to allow the users to choose their privacy?','discy'),
		'id'   => 'privacy_account',
		'std'  => 'on',
		'type' => 'checkbox'
	);

	$privacy_options = array(
		"public"  => esc_html__('Public','discy'),
		"members" => esc_html__("All members","discy"),
		"me"      => esc_html__("Only me","discy"),
	);

	$privacy_array = array(
		"email"      => esc_html__("Email","discy"),
		"country"    => esc_html__("Country","discy"),
		"city"       => esc_html__("City","discy"),
		"phone"      => esc_html__("Phone","discy"),
		"gender"     => esc_html__("Gender","discy"),
		"age"        => esc_html__("Age","discy"),
		"social"     => esc_html__("Social links","discy"),
		"website"    => esc_html__("Website","discy"),
		"bio"        => esc_html__("Biography","discy"),
		"credential" => esc_html__("Profile credential","discy")
	);

	foreach ($privacy_array as $key_privacy => $value_privacy) {
		$options[] = array(
			'name'    => $value_privacy,
			'id'      => 'privacy_'.$key_privacy,
			'type'    => 'select',
			'options' => $privacy_options
		);
	}

	$options[] = array(
		'name' => esc_html__('Delete account','discy'),
		'type' => 'info',
	);

	$options[] = array(
		'name' => esc_html__('Do you like to allow the users to delete their account?','discy'),
		'id'   => 'delete_account',
		'std'  => 'on',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Profile Strength / To use this feature you need to add widget "Profile Strength"','discy'),
		'type' => 'info',
	);

	$profile_strength_std = apply_filters("discy_filter_profile_strength_std",array(
			"avatar"       => "avatar",
			"cover"        => "cover",
			"credential"   => "credential",
			"follow_cats"  => "follow_cats",
			"follow_user"  => "follow_user",
			"ask_question" => "ask_question",
			"answer"       => "answer",
		)
	);

	$profile_strength = apply_filters("discy_filter_profile_strength",array(
			"avatar"       => esc_html__('User Avatar','discy'),
			"cover"        => esc_html__('User Cover','discy'),
			"credential"   => esc_html__('Profile Credential','discy'),
			"follow_cats"  => esc_html__("Follow Categories","discy"),
			"follow_user"  => esc_html__("Follow Users","discy"),
			"ask_question" => esc_html__("Ask first question","discy"),
			"answer"       => esc_html__("Add Answers","discy"),
		)
	);

	$options[] = array(
		'name'    => esc_html__('Select the items for the profile strength','discy'),
		'id'      => 'profile_strength',
		'type'    => 'multicheck',
		'std'     => $profile_strength_std,
		'options' => $profile_strength
	);

	$options[] = array(
		'name'      => esc_html__('Select the number for the profile strength for the following categories','discy'),
		'id'        => 'profile_follow_cats',
		'type'      => 'text',
		'std'       => '3',
		'condition' => 'profile_strength:has(follow_cats)',
	);

	$options[] = array(
		'name'      => esc_html__('Select the number for the profile strength for the following users','discy'),
		'id'        => 'profile_follow_users',
		'type'      => 'text',
		'std'       => '3',
		'condition' => 'profile_strength:has(follow_user)',
	);

	$options[] = array(
		'name'      => esc_html__('Select the number for the profile strength for the answers on the questions','discy'),
		'id'        => 'profile_answer',
		'type'      => 'text',
		'std'       => '3',
		'condition' => 'profile_strength:has(answer)',
	);
	
	$options[] = array(
		"name"      => esc_html__('The percent for avatar','discy'),
		"id"        => "percent_avatar",
		"type"      => "sliderui",
		'std'       => 10,
		"step"      => "1",
		"min"       => "1",
		"condition" => "profile_strength:has(avatar)",
		"max"       => "100"
	);
	
	$options[] = array(
		"name"      => esc_html__('The percent for cover','discy'),
		"id"        => "percent_cover",
		"type"      => "sliderui",
		'std'       => 10,
		"step"      => "1",
		"min"       => "1",
		"condition" => "profile_strength:has(cover)",
		"max"       => "100"
	);
	
	$options[] = array(
		"name"      => esc_html__('The percent for credential','discy'),
		"id"        => "percent_credential",
		"type"      => "sliderui",
		'std'       => 10,
		"step"      => "1",
		"min"       => "1",
		"condition" => "profile_strength:has(credential)",
		"max"       => "100"
	);
	
	$options[] = array(
		"name"      => esc_html__('The percent for follow cats','discy'),
		"id"        => "percent_follow_cats",
		"type"      => "sliderui",
		'std'       => 20,
		"step"      => "1",
		"min"       => "1",
		"condition" => "profile_strength:has(follow_cats)",
		"max"       => "100"
	);
	
	$options[] = array(
		"name"      => esc_html__('The percent for follow user','discy'),
		"id"        => "percent_follow_user",
		"type"      => "sliderui",
		'std'       => 20,
		"step"      => "1",
		"min"       => "1",
		"condition" => "profile_strength:has(follow_user)",
		"max"       => "100"
	);
	
	$options[] = array(
		"name"      => esc_html__('The percent for ask question','discy'),
		"id"        => "percent_ask_question",
		"type"      => "sliderui",
		'std'       => 10,
		"step"      => "1",
		"min"       => "1",
		"condition" => "profile_strength:has(ask_question)",
		"max"       => "100"
	);
	
	$options[] = array(
		"name"      => esc_html__('The percent for answer','discy'),
		"id"        => "percent_answer",
		"type"      => "sliderui",
		'std'       => 20,
		"step"      => "1",
		"min"       => "1",
		"condition" => "profile_strength:has(answer)",
		"max"       => "100"
	);

	$options = apply_filters('discy_options_after_percent_answer',$options);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'id'   => 'ask_users',
		'name' => esc_html__('Ask Users','discy')
	);
	
	$options[] = array(
		'name' => esc_html__('Ask question to the users','discy'),
		'desc' => esc_html__('Any one can ask question to the users enable or disable.','discy'),
		'id'   => 'ask_question_to_users',
		'std'  => "on",
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'ask_question_to_users:not(0)',
		'type'      => 'heading-2'
	);
	
	$ask_user_items = array(
		"title_question"       => array("sort" => esc_html__('Question Title','discy'),"value" => "title_question"),
		"comment_question"     => array("sort" => esc_html__('Question content','discy'),"value" => "comment_question"),
		"anonymously_question" => array("sort" => esc_html__('Ask Anonymously','discy'),"value" => "anonymously_question"),
		"private_question"     => array("sort" => esc_html__('Private Question','discy'),"value" => "private_question"),
		"remember_answer"      => array("sort" => esc_html__('Remember Answer','discy'),"value" => "remember_answer"),
		"terms_active"         => array("sort" => esc_html__('Terms of Service and Privacy Policy','discy'),"value" => "terms_active"),
	);
	
	$options[] = array(
		'name'    => esc_html__("Select what to show at ask user question form","discy"),
		'id'      => 'ask_user_items',
		'type'    => 'multicheck',
		'sort'    => 'yes',
		'std'     => $ask_user_items,
		'options' => $ask_user_items
	);

	$options[] = array(
		'div'       => 'div',
		'condition' => 'ask_user_items:has_not(title_question)',
		'type'      => 'heading-2'
	);

	$options[] = array(
		'name'    => esc_html__('Excerpt type for title from the content','discy'),
		'desc'    => esc_html__('Choose form here the excerpt type.','discy'),
		'id'      => 'title_excerpt_type_user',
		'type'    => "select",
		'options' => array(
			'words'      => esc_html__('Words','discy'),
			'characters' => esc_html__('Characters','discy')
		)
	);

	$options[] = array(
		'name' => esc_html__('Excerpt title from the content','discy'),
		'desc' => esc_html__('Put here the excerpt title from the content.','discy'),
		'id'   => 'title_excerpt_user',
		'std'  => 10,
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end',
		'div'  => 'div'
	);

	$options[] = array(
		'name'      => esc_html__('Select the checked by default options at ask user question form','discy'),
		'id'        => 'add_question_default_user',
		'type'      => 'multicheck',
		'condition' => 'ask_user_items:has(anonymously_question),ask_user_items:has(private_question),ask_user_items:has(remember_answer),ask_user_items:has(terms_active)',
		'operator'  => 'or',
		'std'       => array(
			"notified" => "notified",
		),
		'options' => array(
			"notified"    => esc_html__('Notified','discy'),
			"private"     => esc_html__("Private question","discy"),
			"anonymously" => esc_html__("Ask anonymously","discy"),
			"terms"       => esc_html__("Terms","discy"),
		)
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'ask_user_items:has(comment_question)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'name' => esc_html__('Details in ask question form is required','discy'),
		'id'   => 'content_ask_user',
		'std'  => "on",
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Enable or disable the editor for details in ask question form','discy'),
		'id'   => 'editor_ask_user',
		'std'  => "on",
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'ask_user_items:has(terms_active)',
		'type'      => 'heading-2'
	);

	$options[] = array(
		'name' => esc_html__('Terms of Service and Privacy Policy','discy'),
		'type' => 'info'
	);
	
	$options[] = array(
		'name'    => esc_html__('Open the page in same page or a new page?','discy'),
		'id'      => 'terms_active_user_target',
		'std'     => "new_page",
		'type'    => 'select',
		'options' => array("same_page" => esc_html__("Same page","discy"),"new_page" => esc_html__("New page","discy"))
	);
	
	$options[] = array(
		'name'    => esc_html__('Terms page','discy'),
		'desc'    => esc_html__('Select the terms page','discy'),
		'id'      => 'terms_page_user',
		'type'    => 'select',
		'options' => $options_pages
	);
	
	$options[] = array(
		'name' => esc_html__("Type the terms link if you don't like a page","discy"),
		'id'   => 'terms_link_user',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('Activate Privacy Policy','discy'),
		'desc' => esc_html__('Select ON if you want to activate Privacy Policy.','discy'),
		'id'   => 'privacy_policy_user',
		'std'  => "on",
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'privacy_policy_user:not(0)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'name'    => esc_html__('Open the page in same page or a new page?','discy'),
		'id'      => 'privacy_active_target_user',
		'std'     => "new_page",
		'type'    => 'select',
		'options' => array("same_page" => esc_html__("Same page","discy"),"new_page" => esc_html__("New page","discy"))
	);
	
	$options[] = array(
		'name'    => esc_html__('Privacy Policy page','discy'),
		'desc'    => esc_html__('Select the privacy policy page','discy'),
		'id'      => 'privacy_page_user',
		'type'    => 'select',
		'options' => $options_pages
	);
	
	$options[] = array(
		'name' => esc_html__("Type the privacy policy link if you don't like a page","discy"),
		'id'   => 'privacy_link_user',
		'type' => 'text'
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
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'id'   => 'referral_setting',
		'name' => esc_html__('Referral Setting','discy')
	);
	
	$options[] = array(
		'name' => esc_html__('Activate referrals to the users','discy'),
		'desc' => esc_html__('Any one can send referral to the users enable or disable.','discy'),
		'id'   => 'active_referral',
		'std'  => 'on',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'active_referral:not(0)',
		'type'      => 'heading-2'
	);

	$options[] = array(
		'name' => esc_html__("Referrals slug","discy"),
		'desc' => esc_html__("Select the referrals slug","discy"),
		'id'   => 'referrals_slug',
		'type' => 'text',
		'std'  => 'referrals'
	);
	
	$options[] = array(
		'name' => esc_html__('The headline','discy'),
		'desc' => esc_html__('Type the Headline from here','discy'),
		'id'   => 'referrals_headline',
		'type' => 'text',
		'std'  => 'Spread the word. Earn points.'
	);
	
	$options[] = array(
		'name' => esc_html__('The paragraph','discy'),
		'desc' => esc_html__('Type the Paragraph from here','discy'),
		'id'   => 'referrals_paragraph',
		'type' => 'textarea',
		'std'  => 'We have a number of ways to help spread the word to your friends and family, Choose whatever works best for you.'
	);
	
	$options[] = array(
		'name' => esc_html__('Activate the share on referrals','discy'),
		'desc' => esc_html__('Share enable or disable for referrals','discy'),
		'id'   => 'referrals_share_on',
		'std'  => 'on',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name'      => esc_html__('Select the share options','discy'),
		'id'        => 'referrals_share',
		'condition' => 'referrals_share_on:not(0)',
		'type'      => 'multicheck',
		'sort'      => 'yes',
		'std'       => $share_array,
		'options'   => $share_array
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
		'type' => 'heading-2',
		'id'   => 'popup_notification',
		'name' => esc_html__('Popup Notification','discy')
	);
	
	$options[] = array(
		'name' => esc_html__('Note: the last popup notification only will show not all the popup notifications.','discy'),
		'type' => 'info'
	);

	$options[] = array(
		'name'    => esc_html__('Show the popup notification for the user one time only or forever','discy'),
		'id'      => 'popup_notification_time',
		'options' => array(
			'one_time' => esc_html__('One time','discy'),
			'for_ever' => esc_html__('Forever','discy'),
		),
		'std'     => 'one_time',
		'type'    => 'radio'
	);

	$options[] = array(
		'name'    => esc_html__('Show the popup notification at all the pages, custom pages, or home page only?','discy'),
		'id'      => 'popup_notification_home_pages',
		'options' => array(
			'home_page'     => esc_html__('Home page','discy'),
			'all_pages'     => esc_html__('All site pages','discy'),
			'all_posts'     => esc_html__('All single post pages','discy'),
			'all_questions' => esc_html__('All single quepage pages','discy'),
			'custom_pages'  => esc_html__('Custom pages','discy'),
		),
		'std'     => 'home_page',
		'type'    => 'radio'
	);

	$options[] = array(
		'name'      => esc_html__('Page ids','discy'),
		'desc'      => esc_html__('Type from here the page ids','discy'),
		'id'        => 'popup_notification_pages',
		'type'      => 'text',
		'condition' => 'popup_notification_home_pages:is(custom_pages)'
	);

	$options[] = array(
		'name'    => esc_html__('Choose the roles or users for the popup notification','discy'),
		'desc'    => esc_html__('Choose from here which roles or users you want to send the popup notification.','discy'),
		'id'      => 'popup_notification_groups_users',
		'options' => array(
			'groups' => esc_html__('Roles','discy'),
			'users'  => esc_html__('Users','discy'),
		),
		'std'     => 'groups',
		'type'    => 'radio'
	);

	$options[] = array(
		'name'      => esc_html__("Choose the roles you need to send the popup notification.","discy"),
		'id'        => 'popup_notification_groups',
		'condition' => 'popup_notification_groups_users:not(users)',
		'type'      => 'multicheck',
		'options'   => $new_roles,
		'std'       => array('administrator' => 'administrator','editor' => 'editor','contributor' => 'contributor','subscriber' => 'subscriber','author' => 'author'),
	);

	$options[] = array(
		'name'      => esc_html__('Specific user ids','discy'),
		'id'        => 'popup_notification_specific_users',
		'condition' => 'popup_notification_groups_users:is(users)',
		'type'      => 'text'
	);
	
	$options[] = array(
		'name'     => esc_html__('Popup notification text','discy'),
		'id'       => 'popup_notification_text',
		'type'     => 'editor',
		'settings' => $wp_editor_settings
	);

	$options[] = array(
		'name'    => esc_html__('Open the page in same page or a new page?','discy'),
		'id'      => 'popup_notification_button_target',
		'std'     => "new_page",
		'type'    => 'select',
		'options' => array("same_page" => esc_html__("Same page","discy"),"new_page" => esc_html__("New page","discy"))
	);
	
	$options[] = array(
		'name' => esc_html__('Type the button link','discy'),
		'id'   => 'popup_notification_button_url',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('Type the button text','discy'),
		'id'   => 'popup_notification_button_text',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('You must save your options before sending the popup notification.','discy'),
		'type' => 'info'
	);
	
	$options[] = array(
		'name' => '<a href="#" class="button button-primary send-popup-notification">'.esc_html__('Send the popup notification','discy').'</a>',
		'type' => 'info'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'id'   => 'permissions',
		'name' => esc_html__('Permissions','discy')
	);
	
	$options[] = array(
		'name' => esc_html__('Select ON to be able to add a custom permission.','discy'),
		'id'   => 'custom_permission',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'custom_permission:not(0)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'name' => esc_html__('Unlogged users','discy'),
		'type' => 'info'
	);
	
	$options[] = array(
		'name' => esc_html__('Select ON to be able to ask a question.','discy'),
		'id'   => 'ask_question',
		'std'  => "on",
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Select ON to be able to show other questions.','discy'),
		'id'   => 'show_question',
		'std'  => "on",
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Select ON to be able to add an answer.','discy'),
		'id'   => 'add_answer',
		'std'  => "on",
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Select ON to be able to show other answers.','discy'),
		'id'   => 'show_answer',
		'std'  => "on",
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => esc_html__('Select ON to be able to add a group.','discy'),
		'id'   => 'add_group',
		'std'  => "on",
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Select ON to be able to add a post.','discy'),
		'id'   => 'add_post',
		'std'  => "on",
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Select ON to be able to add a category.','discy'),
		'id'   => 'add_category',
		'std'  => "on",
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Select ON to be able to send message.','discy'),
		'id'   => 'send_message',
		'std'  => "on",
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Select ON to auto approve the questions when media has been attached.','discy'),
		'id'   => 'approve_question_media',
		'std'  => "on",
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Select ON to auto approve the answers when media has been attached.','discy'),
		'id'   => 'approve_answer_media',
		'std'  => "on",
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Setting for the user roles & Add a new role','discy'),
		'type' => 'info'
	);
	
	$options[] = array(
		'id'   => "roles",
		'type' => 'roles'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end',
		'div'  => 'div'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'id'   => 'author_setting',
		'name' => esc_html__('Author Setting','discy')
	);
	
	$options[] = array(
		'name'    => esc_html__('Post style','discy'),
		'desc'    => esc_html__('Choose post style from here.','discy'),
		'id'      => 'author_post_style',
		'options' => array(
			'default'  => esc_html__('Default','discy'),
			'style_1'  => esc_html__('1 column','discy'),
			'style_2'  => esc_html__('List style','discy'),
			'style_3'  => esc_html__('Columns','discy'),
		),
		'std'     => 'default',
		'type'    => 'radio'
	);
	
	$options[] = array(
		'id'        => "author_sort_meta_title_image",
		'condition' => 'author_post_style:is(style_3)',
		'std'       => array(
						array("value" => "image",'name' => esc_html__('Image','discy'),"default" => "yes"),
						array("value" => "meta_title",'name' => esc_html__('Meta and title','discy'),"default" => "yes"),
					),
		'type'      => "sort",
		'options'   => array(
						array("value" => "image",'name' => esc_html__('Image','discy'),"default" => "yes"),
						array("value" => "meta_title",'name' => esc_html__('Meta and title','discy'),"default" => "yes"),
					)
	);
	
	$options[] = array(
		'name'    => esc_html__('Author sidebar layout','discy'),
		'id'      => "author_sidebar_layout",
		'std'     => "default",
		'type'    => "images",
		'options' => array(
			'default'      => $imagepath.'sidebar_default.jpg',
			'menu_sidebar' => $imagepath.'menu_sidebar.jpg',
			'right'        => $imagepath.'sidebar_right.jpg',
			'full'         => $imagepath.'sidebar_no.jpg',
			'left'         => $imagepath.'sidebar_left.jpg',
			'centered'     => $imagepath.'centered.jpg',
			'menu_left'    => $imagepath.'menu_left.jpg',
		)
	);
	
	$options[] = array(
		'name'      => esc_html__('Author Page sidebar','discy'),
		'id'        => "author_sidebar",
		'options'   => $new_sidebars,
		'type'      => 'select',
		'condition' => 'author_sidebar_layout:not(full),author_sidebar_layout:not(centered),author_sidebar_layout:not(menu_left)'
	);
	
	$options[] = array(
		'name'      => esc_html__('Author Page sidebar 2','discy'),
		'id'        => "author_sidebar_2",
		'options'   => $new_sidebars,
		'type'      => 'select',
		'operator'  => 'or',
		'condition' => 'author_sidebar_layout:is(menu_sidebar),author_sidebar_layout:is(menu_left)'
	);
	
	$options[] = array(
		'name'    => esc_html__('Choose Your Skin','discy'),
		'class'   => "site_skin",
		'id'      => "author_skin",
		'std'     => "default",
		'type'    => "images",
		'options' => array(
			'default'    => $imagepath.'default_color.jpg',
			'skin'       => $imagepath.'default.jpg',
			'violet'     => $imagepath.'violet.jpg',
			'bright_red' => $imagepath.'bright_red.jpg',
			'green'      => $imagepath.'green.jpg',
			'red'        => $imagepath.'red.jpg',
			'cyan'       => $imagepath.'cyan.jpg',
			'blue'       => $imagepath.'blue.jpg',
		)
	);
	
	$options[] = array(
		'name' => esc_html__('Primary Color','discy'),
		'id'   => 'author_primary_color',
		'type' => 'color'
	);
	
	$options[] = array(
		'name'    => esc_html__('Background Type','discy'),
		'id'      => 'author_background_type',
		'std'     => 'default',
		'type'    => 'radio',
		'options' => 
			array(
				"default"           => esc_html__("Default","discy"),
				"none"              => esc_html__("None","discy"),
				"patterns"          => esc_html__("Patterns","discy"),
				"custom_background" => esc_html__("Custom Background","discy")
			)
	);

	$options[] = array(
		'name'      => esc_html__('Background Color','discy'),
		'id'        => 'author_background_color',
		'type'      => 'color',
		'condition' => 'author_background_type:is(patterns)'
	);
		
	$options[] = array(
		'name'      => esc_html__('Choose Pattern','discy'),
		'id'        => "author_background_pattern",
		'std'       => "bg13",
		'type'      => "images",
		'condition' => 'author_background_type:is(patterns)',
		'class'     => "pattern_images",
		'options'   => array(
			'bg1'  => $imagepath.'bg1.jpg',
			'bg2'  => $imagepath.'bg2.jpg',
			'bg3'  => $imagepath.'bg3.jpg',
			'bg4'  => $imagepath.'bg4.jpg',
			'bg5'  => $imagepath.'bg5.jpg',
			'bg6'  => $imagepath.'bg6.jpg',
			'bg7'  => $imagepath.'bg7.jpg',
			'bg8'  => $imagepath.'bg8.jpg',
			'bg9'  => $imagepath_theme.'patterns/bg9.png',
			'bg10' => $imagepath_theme.'patterns/bg10.png',
			'bg11' => $imagepath_theme.'patterns/bg11.png',
			'bg12' => $imagepath_theme.'patterns/bg12.png',
			'bg13' => $imagepath.'bg13.jpg',
			'bg14' => $imagepath.'bg14.jpg',
			'bg15' => $imagepath_theme.'patterns/bg15.png',
			'bg16' => $imagepath_theme.'patterns/bg16.png',
			'bg17' => $imagepath.'bg17.jpg',
			'bg18' => $imagepath.'bg18.jpg',
			'bg19' => $imagepath.'bg19.jpg',
			'bg20' => $imagepath.'bg20.jpg',
			'bg21' => $imagepath_theme.'patterns/bg21.png',
			'bg22' => $imagepath.'bg22.jpg',
			'bg23' => $imagepath_theme.'patterns/bg23.png',
			'bg24' => $imagepath_theme.'patterns/bg24.png',
		)
	);

	$options[] = array(
		'name'      => esc_html__('Custom Background','discy'),
		'id'        => 'author_custom_background',
		'std'       => $background_defaults,
		'type'      => 'background',
		'options'   => $background_defaults,
		'condition' => 'author_background_type:is(custom_background)'
	);
		
	$options[] = array(
		'name'      => esc_html__('Full Screen Background','discy'),
		'desc'      => esc_html__('Select ON to enable Full Screen Background','discy'),
		'id'        => 'author_full_screen_background',
		'type'      => 'checkbox',
		'condition' => 'author_background_type:is(custom_background)'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);

	if (has_wpqa()) {
		$groups_settings = array(
			"general_setting_g" => esc_html__('General settings','discy'),
			"group_slugs"       => esc_html__('Group slugs','discy'),
			"add_edit_delete_g" => esc_html__('Add - Edit - Delete','discy'),
			"group_posts"       => esc_html__('Group posts','discy'),
			"groups_layout"     => esc_html__('Groups layout','discy')
		);

		$options[] = array(
			'name'    => esc_html__('Group settings','discy'),
			'id'      => 'group',
			'icon'    => 'groups',
			'type'    => 'heading',
			'std'     => 'general_setting_g',
			'options' => apply_filters("discy_groups_settings",$groups_settings)
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'id'   => 'general_setting_g',
			'name' => esc_html__('General settings','discy')
		);

		$options[] = array(
			'name' => esc_html__('Activate the groups','discy'),
			'id'   => 'active_groups',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'active_groups:not(0)',
			'type'      => 'heading-2'
		);

		$options[] = array(
			'name' => esc_html__('Activate the group rules in the top page of the group','discy'),
			'id'   => 'active_rules_groups',
			'type' => 'checkbox'
		);

		$options[] = array(
			'name'    => esc_html__('Pagination style','discy'),
			'desc'    => esc_html__('Choose pagination style from here.','discy'),
			'id'      => 'group_pagination',
			'options' => array(
				'standard'        => esc_html__('Standard','discy'),
				'pagination'      => esc_html__('Pagination','discy'),
				'load_more'       => esc_html__('Load more','discy'),
				'infinite_scroll' => esc_html__('Infinite scroll','discy'),
				'none'            => esc_html__('None','discy'),
			),
			'std'     => 'pagination',
			'type'    => 'radio'
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
			'type' => 'heading-2',
			'id'   => 'group_slugs',
			'name' => esc_html__('Group slugs','discy')
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'active_groups:not(0)',
			'type'      => 'heading-2'
		);

		$options[] = array(
			'name' => esc_html__('Groups archive slug','discy'),
			'desc' => esc_html__('Add your groups archive slug.','discy'),
			'id'   => 'archive_group_slug',
			'std'  => 'groups',
			'type' => 'text'
		);

		$options[] = array(
			'name' => esc_html__('Group slug','discy'),
			'desc' => esc_html__('Add your group slug.','discy'),
			'id'   => 'group_slug',
			'std'  => 'group',
			'type' => 'text'
		);

		$options[] = array(
			'name' => esc_html__('User requests slug','discy'),
			'desc' => esc_html__('Add your user requests slug.','discy'),
			'id'   => 'group_requests_slug',
			'std'  => 'user-requests',
			'type' => 'text'
		);

		$options[] = array(
			'name' => esc_html__('Group posts slug','discy'),
			'desc' => esc_html__('Add your group posts slug.','discy'),
			'id'   => 'posts_group_slug',
			'std'  => 'pending-posts',
			'type' => 'text'
		);

		$options[] = array(
			'name' => esc_html__('Group users slug','discy'),
			'desc' => esc_html__('Add your group users slug.','discy'),
			'id'   => 'group_users_slug',
			'std'  => 'group-users',
			'type' => 'text'
		);

		$options[] = array(
			'name' => esc_html__('Group admins slug','discy'),
			'desc' => esc_html__('Add your group admins slug.','discy'),
			'id'   => 'group_admins_slug',
			'std'  => 'group-admins',
			'type' => 'text'
		);

		$options[] = array(
			'name' => esc_html__('Blocked user slug','discy'),
			'desc' => esc_html__('Add your blocked user slug.','discy'),
			'id'   => 'blocked_users_slug',
			'std'  => 'blocked-users',
			'type' => 'text'
		);

		$options[] = array(
			'name' => esc_html__('View group post slug','discy'),
			'desc' => esc_html__('Add your view group post slug.','discy'),
			'id'   => 'view_posts_group_slug',
			'std'  => 'view-post-group',
			'type' => 'text'
		);

		$options[] = array(
			'name' => esc_html__('Edit group post slug','discy'),
			'desc' => esc_html__('Add your edit group post slug.','discy'),
			'id'   => 'edit_posts_group_slug',
			'std'  => 'edit-post-group',
			'type' => 'text'
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
			'type' => 'heading-2',
			'id'   => 'add_edit_delete_g',
			'name' => esc_html__('Add - Edit - Delete','discy')
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'active_groups:not(0)',
			'type'      => 'heading-2'
		);

		$options[] = array(
			'name' => esc_html__('Add groups','discy'),
			'type' => 'info'
		);
		
		$options[] = array(
			'name' => esc_html__('Add group slug','discy'),
			'desc' => esc_html__('Put the add group slug.','discy'),
			'id'   => 'add_groups_slug',
			'std'  => 'add-group',
			'type' => 'text'
		
		);
		
		if (has_wpqa() && wpqa_plugin_version >= 4.2) {
			$options[] = array(
				'name' => '<a href="'.wpqa_add_group_permalink().'" target="_blank">'.esc_html__('Link For The Add Group Page.','discy').'</a>',
				'type' => 'info'
			);
		}

		$options[] = array(
			'name'    => esc_html__('Choose group status for users only','discy'),
			'desc'    => esc_html__('Choose group status after the user publishes the group.','discy'),
			'id'      => 'group_publish',
			'options' => array("publish" => esc_html__("Publish","discy"),"draft" => esc_html__("Draft","discy")),
			'std'     => 'publish',
			'type'    => 'select'
		);
		
		$options[] = array(
			'name'      => esc_html__('Send mail when the group needs a review','discy'),
			'desc'      => esc_html__('Mail for groups review enable or disable.','discy'),
			'id'        => 'send_email_draft_groups',
			'std'       => 'on',
			'condition' => 'group_publish:not(publish)',
			'type'      => 'checkbox'
		);

		$options[] = array(
			'name' => esc_html__('Activate Terms of Service and privacy policy page?','discy'),
			'desc' => esc_html__('Select ON if you want active Terms of Service and privacy policy page.','discy'),
			'id'   => 'terms_active_group',
			'type' => 'checkbox'
		);

		$options[] = array(
			'div'       => 'div',
			'condition' => 'terms_active_group:is(on)',
			'type'      => 'heading-2'
		);

		$options[] = array(
			'name' => esc_html__('Terms of Service and Privacy Policy','discy'),
			'type' => 'info'
		);
		
		$options[] = array(
			'name'    => esc_html__('Open the page in same page or a new page?','discy'),
			'id'      => 'terms_active_target_group',
			'std'     => "new_page",
			'type'    => 'select',
			'options' => array("same_page" => esc_html__("Same page","discy"),"new_page" => esc_html__("New page","discy"))
		);
		
		$options[] = array(
			'name'    => esc_html__('Terms page','discy'),
			'desc'    => esc_html__('Select the terms page','discy'),
			'id'      => 'terms_page_group',
			'type'    => 'select',
			'options' => $options_pages
		);
		
		$options[] = array(
			'name' => esc_html__("Type the terms link if you don't like a page","discy"),
			'id'   => 'terms_link_group',
			'type' => 'text'
		);
		
		$options[] = array(
			'name' => esc_html__('Activate Privacy Policy','discy'),
			'desc' => esc_html__('Select ON if you want to activate Privacy Policy.','discy'),
			'id'   => 'privacy_policy_group',
			'std'  => "on",
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'privacy_policy_group:not(0)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name'    => esc_html__('Open the page in same page or a new page?','discy'),
			'id'      => 'privacy_active_target_group',
			'std'     => "new_page",
			'type'    => 'select',
			'options' => array("same_page" => esc_html__("Same page","discy"),"new_page" => esc_html__("New page","discy"))
		);
		
		$options[] = array(
			'name'    => esc_html__('Privacy Policy page','discy'),
			'desc'    => esc_html__('Select the privacy policy page','discy'),
			'id'      => 'privacy_page_group',
			'type'    => 'select',
			'options' => $options_pages
		);
		
		$options[] = array(
			'name' => esc_html__("Type the privacy policy link if you don't like a page","discy"),
			'id'   => 'privacy_link_group',
			'type' => 'text'
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
			'name' => esc_html__('Edit groups','discy'),
			'type' => 'info'
		);
		
		$options[] = array(
			'name' => esc_html__('Activate user can edit the groups','discy'),
			'desc' => esc_html__('Select ON if you want the user to be able to edit the groups.','discy'),
			'id'   => 'group_edit',
			'std'  => "on",
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'group_edit:not(0)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name' => esc_html__('Edit group slug','discy'),
			'desc' => esc_html__('Put the edit group slug.','discy'),
			'id'   => 'edit_groups_slug',
			'std'  => 'edit-group',
			'type' => 'text'
		);
		
		$options[] = array(
			'name' => esc_html__('After edit auto approve group or need to be approved again?','discy'),
			'desc' => esc_html__('Press ON to auto approve','discy'),
			'id'   => 'group_approved',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name' => esc_html__('After the group is edited change the URL from the title?','discy'),
			'desc' => esc_html__('Press ON to edit the URL','discy'),
			'id'   => 'change_group_url',
			'std'  => 'on',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);

		$options[] = array(
			'name' => esc_html__('Delete groups','discy'),
			'type' => 'info'
		);
		
		$options[] = array(
			'name' => esc_html__('Activate user can delete the groups','discy'),
			'desc' => esc_html__('Select ON if you want the user to be able to delete the groups.','discy'),
			'id'   => 'group_delete',
			'std'  => "on",
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name'      => esc_html__('When the users delete the group send to the trash or delete it forever?','discy'),
			'id'        => 'delete_group',
			'options'   => array(
				'delete' => esc_html__('Delete','discy'),
				'trash'  => esc_html__('Trash','discy'),
			),
			'std'       => 'delete',
			'condition' => 'group_delete:not(0)',
			'type'      => 'radio'
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
			'type' => 'heading-2',
			'id'   => 'group_posts',
			'name' => esc_html__('Group posts','discy')
		);

		$options[] = array(
			'name'      => esc_html__('Send mail when the posts on the group needs a review','discy'),
			'desc'      => esc_html__('Mail for posts on the group review enable or disable.','discy'),
			'id'        => 'send_email_draft_group_posts',
			'std'       => 'on',
			'type'      => 'checkbox'
		);

		$options[] = array(
			'name' => esc_html__('Activate user can edit the group posts','discy'),
			'desc' => esc_html__('Select ON if you want the user to be able to edit the group posts.','discy'),
			'id'   => 'posts_edit',
			'std'  => "on",
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'posts_edit:not(0)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name' => esc_html__('After edit auto approve group posts or need to be approved again?','discy'),
			'desc' => esc_html__('Press ON to auto approve','discy'),
			'id'   => 'posts_approved',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name'    => esc_html__("Choose if you want to allow the users to see the users on the group for public or private groups.","discy"),
			'id'      => 'view_users_group',
			'type'    => 'multicheck',
			'options' => array(
				"public"  => esc_html__("Public","discy"),
				"private" => esc_html__("Private","discy"),
			),
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);

		$options[] = array(
			'name' => esc_html__('Edit group posts and comments','discy'),
			'type' => 'info'
		);

		$options[] = array(
			'name'    => esc_html__("Choose the roles you allow for the owner of the group and moderators.","discy"),
			'id'      => 'edit_delete_posts_comments',
			'type'    => 'multicheck',
			'options' => array(
				"edit"   => esc_html__("Edit posts and comments","discy"),
				"delete" => esc_html__("Delete posts and comments","discy"),
			),
		);

		$options[] = array(
			'name' => esc_html__('Delete group posts','discy'),
			'type' => 'info'
		);
		
		$options[] = array(
			'name' => esc_html__('Activate user can delete the group posts','discy'),
			'desc' => esc_html__('Select ON if you want the user to be able to delete the group posts.','discy'),
			'id'   => 'posts_delete',
			'std'  => "on",
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name'      => esc_html__('When the users delete the group posts send to the trash or delete it forever?','discy'),
			'id'        => 'delete_posts',
			'options'   => array(
				'delete' => esc_html__('Delete','discy'),
				'trash'  => esc_html__('Trash','discy'),
			),
			'std'       => 'delete',
			'condition' => 'posts_delete:not(0)',
			'type'      => 'radio'
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'end'  => 'end'
		);

		$options[] = array(
			'type' => 'heading-2',
			'id'   => 'groups_layout',
			'name' => esc_html__('Group layout','discy')
		);

		$options[] = array(
			'name' => esc_html__('Group sidebar layout','discy'),
			'id'   => "group_sidebar_layout",
			'std'  => "default",
			'type' => "images",
			'options' => array(
				'default'      => $imagepath.'sidebar_default.jpg',
				'menu_sidebar' => $imagepath.'menu_sidebar.jpg',
				'right'        => $imagepath.'sidebar_right.jpg',
				'full'         => $imagepath.'sidebar_no.jpg',
				'left'         => $imagepath.'sidebar_left.jpg',
				'centered'     => $imagepath.'centered.jpg',
				'menu_left'    => $imagepath.'menu_left.jpg',
			)
		);
		
		$options[] = array(
			'name'      => esc_html__('Group Page sidebar','discy'),
			'id'        => "group_sidebar",
			'std'       => '',
			'options'   => $new_sidebars,
			'type'      => 'select',
			'condition' => 'group_sidebar_layout:not(full),group_sidebar_layout:not(centered),group_sidebar_layout:not(menu_left)'
		);
		
		$options[] = array(
			'name'      => esc_html__('Group Page sidebar 2','discy'),
			'id'        => "group_sidebar_2",
			'std'       => '',
			'options'   => $new_sidebars,
			'type'      => 'select',
			'operator'  => 'or',
			'condition' => 'group_sidebar_layout:is(menu_sidebar),group_sidebar_layout:is(menu_left)'
		);
		
		$options[] = array(
			'name'    => esc_html__('Choose Your Skin','discy'),
			'class'   => "site_skin",
			'id'      => "group_skin",
			'std'     => "default",
			'type'    => "images",
			'options' => array(
				'default'    => $imagepath.'default_color.jpg',
				'skin'       => $imagepath.'default.jpg',
				'violet'     => $imagepath.'violet.jpg',
				'bright_red' => $imagepath.'bright_red.jpg',
				'green'      => $imagepath.'green.jpg',
				'red'        => $imagepath.'red.jpg',
				'cyan'       => $imagepath.'cyan.jpg',
				'blue'       => $imagepath.'blue.jpg',
			)
		);
		
		$options[] = array(
			'name' => esc_html__('Primary Color','discy'),
			'id'   => 'group_primary_color',
			'type' => 'color'
		);
		
		$options[] = array(
			'name'    => esc_html__('Background Type','discy'),
			'id'      => 'group_background_type',
			'std'     => 'default',
			'type'    => 'radio',
			'options' => 
				array(
					"default"           => esc_html__("Default","discy"),
					"none"              => esc_html__("None","discy"),
					"patterns"          => esc_html__("Patterns","discy"),
					"custom_background" => esc_html__("Custom Background","discy")
				)
		);
	
		$options[] = array(
			'name'      => esc_html__('Background Color','discy'),
			'id'        => 'group_background_color',
			'type'      => 'color',
			'condition' => 'group_background_type:is(patterns)'
		);
			
		$options[] = array(
			'name'      => esc_html__('Choose Pattern','discy'),
			'id'        => "group_background_pattern",
			'std'       => "bg13",
			'type'      => "images",
			'condition' => 'group_background_type:is(patterns)',
			'class'     => "pattern_images",
			'options'   => array(
				'bg1'  => $imagepath.'bg1.jpg',
				'bg2'  => $imagepath.'bg2.jpg',
				'bg3'  => $imagepath.'bg3.jpg',
				'bg4'  => $imagepath.'bg4.jpg',
				'bg5'  => $imagepath.'bg5.jpg',
				'bg6'  => $imagepath.'bg6.jpg',
				'bg7'  => $imagepath.'bg7.jpg',
				'bg8'  => $imagepath.'bg8.jpg',
				'bg9'  => $imagepath_theme.'patterns/bg9.png',
				'bg10' => $imagepath_theme.'patterns/bg10.png',
				'bg11' => $imagepath_theme.'patterns/bg11.png',
				'bg12' => $imagepath_theme.'patterns/bg12.png',
				'bg13' => $imagepath.'bg13.jpg',
				'bg14' => $imagepath.'bg14.jpg',
				'bg15' => $imagepath_theme.'patterns/bg15.png',
				'bg16' => $imagepath_theme.'patterns/bg16.png',
				'bg17' => $imagepath.'bg17.jpg',
				'bg18' => $imagepath.'bg18.jpg',
				'bg19' => $imagepath.'bg19.jpg',
				'bg20' => $imagepath.'bg20.jpg',
				'bg21' => $imagepath_theme.'patterns/bg21.png',
				'bg22' => $imagepath.'bg22.jpg',
				'bg23' => $imagepath_theme.'patterns/bg23.png',
				'bg24' => $imagepath_theme.'patterns/bg24.png',
			)
		);
	
		$options[] = array(
			'name'      => esc_html__('Custom Background','discy'),
			'id'        => 'group_custom_background',
			'std'       => $background_defaults,
			'type'      => 'background',
			'options'   => $background_defaults,
			'condition' => 'group_background_type:is(custom_background)'
		);
			
		$options[] = array(
			'name'      => esc_html__('Full Screen Background','discy'),
			'desc'      => esc_html__('Select ON to enable Full Screen Background','discy'),
			'id'        => 'group_full_screen_background',
			'type'      => 'checkbox',
			'condition' => 'group_background_type:is(custom_background)'
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'end'  => 'end'
		);

		$options = apply_filters('discy_options_after_groups_layout',$options);
	}
	
	$options[] = array(
		'name' => esc_html__('Message settings','discy'),
		'icon' => 'email-alt',
		'type' => 'heading'
	);
	
	$options[] = array(
		'type' => 'heading-2',
	);
	
	$options[] = array(
		'name' => esc_html__('Activate messages to the users','discy'),
		'desc' => esc_html__('Any one can send message to the users enable or disable.','discy'),
		'id'   => 'active_message',
		'std'  => 'on',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'active_message:not(0)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'name' => esc_html__("Messages slug","discy"),
		'desc' => esc_html__("Select the messages slug","discy"),
		'id'   => 'messages_slug',
		'type' => 'text',
		'std'  => 'messages'
	);
	
	$options[] = array(
		'name'    => esc_html__('Choose message status','discy'),
		'desc'    => esc_html__('Choose message status after user publish the message.','discy'),
		'id'      => 'message_publish',
		'options' => array("publish" => "Publish","draft" => "Draft"),
		'std'     => 'draft',
		'type'    => 'select'
	);
	
	$options[] = array(
		'name' => esc_html__('Any one can send message without register','discy'),
		'desc' => esc_html__('Any one can send message without register enable or disable.','discy'),
		'id'   => 'send_message_no_register',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Details in send message form is required','discy'),
		'id'   => 'comment_message',
		'std'  => 'on',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Enable or disable the editor for details in send message form','discy'),
		'id'   => 'editor_message_details',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Send mail after message has been sent?','discy'),
		'id'   => 'send_email_message',
		'std'  => 'on',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Activate user can delete the messages','discy'),
		'desc' => esc_html__('Select ON if you want the user to be able to delete the messages.','discy'),
		'id'   => 'message_delete',
		'std'  => 'on',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Activate user can see the message in the notification','discy'),
		'desc' => esc_html__('Select ON if you want the user to be notified if the message is seen.','discy'),
		'id'   => 'seen_message',
		'std'  => 'on',
		'type' => 'checkbox'
	);

	$roles_user_only = $new_roles;
	unset($roles_user_only["wpqa_under_review"]);
	unset($roles_user_only["ban_group"]);
	unset($roles_user_only["activation"]);

	$options[] = array(
		'name'    => esc_html__('Choose the roles or users for the custom message','discy'),
		'desc'    => esc_html__('Choose from here which roles or users you want to send the custom message.','discy'),
		'id'      => 'message_groups_users',
		'options' => array(
			'groups' => esc_html__('Roles','discy'),
			'users'  => esc_html__('Users','discy'),
		),
		'std'     => 'groups',
		'type'    => 'radio'
	);

	$options[] = array(
		'name'      => esc_html__("Choose the roles you need to send the custom message.","discy"),
		'id'        => 'custom_message_groups',
		'condition' => 'message_groups_users:not(users)',
		'type'      => 'multicheck',
		'options'   => $new_roles,
		'std'       => array('administrator' => 'administrator','editor' => 'editor','contributor' => 'contributor','subscriber' => 'subscriber','author' => 'author'),
	);

	$options[] = array(
		'name'      => esc_html__('Specific user ids','discy'),
		'id'        => 'message_specific_users',
		'condition' => 'message_groups_users:is(users)',
		'type'      => 'text'
	);

	$options[] = array(
		'name' => esc_html__('Custom message title','discy'),
		'id'   => 'title_custom_message',
		'std'  => 'Welcome',
		'type' => 'text'
	);
	
	$options[] = array(
		'name'     => esc_html__('Custom message','discy'),
		'id'       => 'custom_message',
		'type'     => 'editor',
		'settings' => $wp_editor_settings
	);
	
	$options[] = array(
		'name' => esc_html__('You must save your options before send the message.','discy'),
		'type' => 'info'
	);
	
	$options[] = array(
		'name' => '<a href="#" class="button button-primary send-custom-message">'.esc_html__('Send the custom message','discy').'</a>',
		'type' => 'info'
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

	$badges_setting = array(
		"badges" => esc_html__('Badge settings','discy'),
		"points" => esc_html__('Point settings','discy')
	);
	
	$options[] = array(
		'name'    => esc_html__('Badges & Point settings','discy'),
		'id'      => 'badges',
		'icon'    => 'star-filled',
		'type'    => 'heading',
		'std'     => 'badges',
		'options' => apply_filters("discy_badges_setting",$badges_setting)
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'id'   => 'badges',
		'name' => esc_html__('Badge settings','discy')
	);
	
	$options[] = array(
		"type" => "textarea",
		"id"   => "badges_details",
		"name" => esc_html__('Details for badges','discy')
	);
	
	$options[] = array(
		'name'    => esc_html__('Choose the badges style','discy'),
		'desc'    => esc_html__('Choose from here the badges style.','discy'),
		'id'      => 'badges_style',
		'options' => array("by_points" => esc_html__("By points","discy"),"by_groups" => esc_html__("By roles","discy"),"by_groups_points" => esc_html__("By roles and points","discy")),
		'std'     => 'by_points',
		'type'    => 'select'
	);
	
	$badge_elements = array(
		array(
			"type" => "text",
			"id"   => "badge_name",
			"name" => esc_html__('Badge name','discy')
		),
		array(
			"type" => "text",
			"id"   => "badge_points",
			"name" => esc_html__('Points','discy')
		),
		array(
			"type" => "color",
			"id"   => "badge_color",
			"name" => esc_html__('Color','discy')
		),
		array(
			"type" => "textarea",
			"id"   => "badge_details",
			"name" => esc_html__('Details','discy')
		)
	);
	
	$options[] = array(
		'id'        => "badges",
		'type'      => "elements",
		'sort'      => "no",
		'hide'      => "yes",
		'button'    => esc_html__('Add a new badge','discy'),
		'options'   => $badge_elements,
		'condition' => 'badges_style:is(by_points)',
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'badges_style:is(by_groups)',
		'type'      => 'heading-2'
	);
	
	$badges_roles = $new_roles;
	unset($badges_roles["activation"]);
	unset($badges_roles["wpqa_under_review"]);
	unset($badges_roles["ban_group"]);
	
	$badge_elements = array(
		array(
			"type"    => "select",
			"id"      => "badge_name",
			"options" => $badges_roles,
			"name"    => esc_html__('Badge name','discy')
		),
		array(
			"type" => "color",
			"id"   => "badge_color",
			"name" => esc_html__('Color','discy')
		),
	);
	
	$options[] = array(
		'id'      => "badges_groups",
		'type'    => "elements",
		'sort'    => "no",
		'hide'    => "yes",
		'button'  => esc_html__('Add a new badge','discy'),
		'options' => $badge_elements,
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'badges_style:is(by_groups_points)',
		'type'      => 'heading-2'
	);
	
	$badge_elements = array(
		array(
			"type"    => "text",
			"id"      => "badge_name",
			"name"    => esc_html__('Badge name','discy')
		),
		array(
			"type"    => "select",
			"id"      => "badge_group",
			"options" => $badges_roles,
			"name"    => esc_html__('Badge role','discy')
		),
		array(
			"type" => "text",
			"id"   => "badge_points",
			"name" => esc_html__('Points','discy')
		),
		array(
			"type" => "color",
			"id"   => "badge_color",
			"name" => esc_html__('Color','discy')
		),
		array(
			"type" => "textarea",
			"id"   => "badge_details",
			"name" => esc_html__('Details','discy')
		)
	);
	
	$options[] = array(
		'id'      => "badges_groups_points",
		'type'    => "elements",
		'sort'    => "no",
		'hide'    => "yes",
		'button'  => esc_html__('Add a new badge','discy'),
		'options' => $badge_elements,
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
		'type' => 'heading-2',
		'id'   => 'points',
		'name' => esc_html__('Point settings','discy')
	);
	
	$options[] = array(
		'name'      => esc_html__('You must activate the points at your site to see the options from "Question settings/General settings".','discy'),
		'type'      => 'info',
		'condition' => 'active_points:not(on)'
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'active_points:not(0)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		"type" => "textarea",
		"id"   => "points_details",
		"name" => esc_html__('Details for points','discy')
	);
	
	$options[] = array(
		'name' => esc_html__('Points for ask a new question (put it 0 for off the option)','discy'),
		'desc' => esc_html__('put the points choose for ask a new question','discy'),
		'id'   => 'point_add_question',
		'type' => 'text',
		'std'  => 0
	);
	
	$options[] = array(
		'name' => esc_html__('Points for add a new post (put it 0 for off the option)','discy'),
		'desc' => esc_html__('put the points choose for add a new post','discy'),
		'id'   => 'point_add_post',
		'type' => 'text',
		'std'  => 0
	);
	
	$options[] = array(
		'name' => esc_html__('Points for choosing the best answer','discy'),
		'desc' => esc_html__('put the points for choosing the best answer','discy'),
		'id'   => 'point_best_answer',
		'type' => 'text',
		'std'  => 5
	);
	
	$options[] = array(
		'name' => esc_html__('Points voting question','discy'),
		'desc' => esc_html__('put the points voting question','discy'),
		'id'   => 'point_voting_question',
		'type' => 'text',
		'std'  => 1
	);
	
	$options[] = array(
		'name' => esc_html__('Points add answer','discy'),
		'desc' => esc_html__('put the points add answer','discy'),
		'id'   => 'point_add_comment',
		'type' => 'text',
		'std'  => 2
	);
	
	$options[] = array(
		'name' => esc_html__('Points voting answer','discy'),
		'desc' => esc_html__('put the points voting answer','discy'),
		'id'   => 'point_voting_answer',
		'type' => 'text',
		'std'  => 1
	);
	
	$options[] = array(
		'name' => esc_html__('Points following user','discy'),
		'desc' => esc_html__('put the points following user','discy'),
		'id'   => 'point_following_me',
		'type' => 'text',
		'std'  => 1
	);
	
	$options[] = array(
		'name' => esc_html__('Points for a new user','discy'),
		'desc' => esc_html__('put the points for a new user','discy'),
		'id'   => 'point_new_user',
		'type' => 'text',
		'std'  => 20
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'active_referral:not(0)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'name' => esc_html__('Points for a new referral','discy'),
		'desc' => esc_html__('put the points for a new referral','discy'),
		'id'   => 'points_referral',
		'type' => 'text',
		'std'  => 10
	);
	
	$options[] = array(
		'name' => esc_html__('Points for a new referral for paid membership','discy'),
		'desc' => esc_html__('put the points for a new for paid membership','discy'),
		'id'   => 'referral_membership',
		'type' => 'text',
		'std'  => 20
	);

	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name'      => esc_html__('Points to add one of your each social media accounts.','discy'),
		'desc'      => esc_html__('put the points to add one of your each social media accounts','discy'),
		'id'        => 'points_social',
		'operator'  => 'or',
		'condition' => 'edit_profile_items_2:has(facebook),edit_profile_items_2:has(twitter),edit_profile_items_2:has(youtube),edit_profile_items_2:has(vimeo),edit_profile_items_2:has(linkedin),edit_profile_items_2:has(instagram),edit_profile_items_2:has(pinterest)',
		'type'      => 'text',
		'std'       => 1
	);

	$options = apply_filters('discy_options_end_of_points',$options);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);

	$options = apply_filters('discy_options_after_points_setting',$options);
		
	$options[] = array(
		'name'    => esc_html__('Comments & Answers','discy'),
		'id'      => 'comment_answer',
		'icon'    => 'admin-comments',
		'type'    => 'heading',
		'std'     => 'comments_setting',
		'options' => array(
			"comments_setting" => esc_html__('Comments','discy'),
			"answers_setting"  => esc_html__('Answers','discy')
		)
	);
	
	$options[] = array(
		'name' => esc_html__('Comment settings','discy'),
		'id'   => 'comments_setting',
		'type' => 'heading-2'
	);
	
	$options[] = array(
		'name' => esc_html__('Enable or disable to hide replies and show them by jQuery','discy'),
		'id'   => 'show_replies',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Enable or disable to count the comments or answers only with replies or not','discy'),
		'id'   => 'count_comment_only',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name'    => esc_html__('Select the share options to show at the comments/answers','discy'),
		'id'      => 'comment_share',
		'type'    => 'multicheck',
		'sort'    => 'yes',
		'std'     => $share_array,
		'options' => $share_array
	);
	
	$options[] = array(
		'name' => esc_html__('Enable or disable the editor for details in comment form','discy'),
		'id'   => 'comment_editor',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Enable or disable the editor for the replies on comments/answers','discy'),
		'id'   => 'activate_editor_reply',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Add your minimum limit of the number of letters for the comment, like 15, 20, if you leave it empty it will make it not important','discy'),
		'id'   => 'comment_min_limit',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('Add your limit of the number of letters for the comment, like 140, 200, if you leave it empty it will make it unlimited','discy'),
		'id'   => 'comment_limit',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('Activate the author image in comments/answers?','discy'),
		'desc' => esc_html__('Author image in comments/answers enable or disable.','discy'),
		'id'   => 'answer_image',
		'std'  => "on",
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Activate Terms of Service and privacy policy page?','discy'),
		'desc' => esc_html__('Select ON if you want active Terms of Service and privacy policy page.','discy'),
		'id'   => 'terms_active_comment',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'terms_active_comment:not(0)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'name' => esc_html__('Terms of Service and Privacy Policy','discy'),
		'type' => 'info'
	);
	
	$options[] = array(
		'name' => esc_html__('Select the checked by default option','discy'),
		'desc' => esc_html__('Select ON if you want to checked it by default.','discy'),
		'id'   => 'terms_checked_comment',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name'    => esc_html__('Open the page in same page or a new page?','discy'),
		'id'      => 'terms_active_target_comment',
		'std'     => "new_page",
		'type'    => 'select',
		'options' => array("same_page" => esc_html__("Same page","discy"),"new_page" => esc_html__("New page","discy"))
	);
	
	$options[] = array(
		'name'    => esc_html__('Terms page','discy'),
		'desc'    => esc_html__('Select the terms page','discy'),
		'id'      => 'terms_page_comment',
		'type'    => 'select',
		'options' => $options_pages
	);
	
	$options[] = array(
		'name' => esc_html__("Type the terms link if you don't like a page","discy"),
		'id'   => 'terms_link_comment',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('Activate Privacy Policy','discy'),
		'desc' => esc_html__('Select ON if you want to activate Privacy Policy.','discy'),
		'id'   => 'privacy_policy_comment',
		'std'  => "on",
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'privacy_policy_comment:not(0)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'name'    => esc_html__('Open the page in same page or a new page?','discy'),
		'id'      => 'privacy_active_target_comment',
		'std'     => "new_page",
		'type'    => 'select',
		'options' => array("same_page" => esc_html__("Same page","discy"),"new_page" => esc_html__("New page","discy"))
	);
	
	$options[] = array(
		'name'    => esc_html__('Privacy Policy page','discy'),
		'desc'    => esc_html__('Select the privacy policy page','discy'),
		'id'      => 'privacy_page_comment',
		'type'    => 'select',
		'options' => $options_pages
	);
	
	$options[] = array(
		'name' => esc_html__("Type the privacy policy link if you don't like a page","discy"),
		'id'   => 'privacy_link_comment',
		'type' => 'text'
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
		'name' => esc_html__('Note: if you need all the comments/answers manually approved, From Settings/Discussion/Comment must be manually approved.','discy'),
		'type' => 'info'
	);
	
	$options[] = array(
		'name'    => esc_html__('Choose comments/answers status for unlogged user only','discy'),
		'desc'    => esc_html__('Choose comments/answers status after unlogged user publish the comments/answers.','discy'),
		'id'      => 'comment_unlogged',
		'options' => array("publish" => esc_html__("Publish","discy"),"draft" => esc_html__("Draft","discy")),
		'std'     => 'draft',
		'type'    => 'select'
	);
	
	$options[] = array(
		'name' => esc_html__('Send mail when comment/answer need a review','discy'),
		'desc' => esc_html__('Mail for comment/answer review enable or disable.','discy'),
		'id'   => 'send_email_draft_comments',
		'std'  => 'on',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Edit comments/answers','discy'),
		'type' => 'info'
	);
	
	$options[] = array(
		'name' => esc_html__('User can edit the comments/answers?','discy'),
		'id'   => 'can_edit_comment',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'can_edit_comment:not(0)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		"name" => esc_html__('User can edit the comments/answers after x hours','discy'),
		"desc" => esc_html__('If you want the user to edit it all the time leave It to 0','discy'),
		"id"   => "can_edit_comment_after",
		"type" => "sliderui",
		'std'  => 1,
		"step" => "1",
		"min"  => "0",
		"max"  => "24"
	);
	
	$options[] = array(
		'name' => esc_html__('Edit comments/answers slug','discy'),
		'desc' => esc_html__('Put the edit comments/answers slug.','discy'),
		'id'   => 'edit_comments_slug',
		'std'  => 'edit-comment',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('After editing comments/answers, auto approve or need to be approved again?','discy'),
		'desc' => esc_html__('Press ON to auto approve','discy'),
		'id'   => 'comment_approved',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name' => esc_html__('Delete comments/answers','discy'),
		'type' => 'info'
	);
	
	$options[] = array(
		'name' => esc_html__('User can delete the comments/answers?','discy'),
		'id'   => 'can_delete_comment',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name'      => esc_html__('When the users delete the comments/answers send it to the trash or delete it forever?','discy'),
		'id'        => 'delete_comment',
		'options'   => array(
			'delete' => esc_html__('Delete','discy'),
			'trash'  => esc_html__('Trash','discy'),
		),
		'std'       => 'delete',
		'condition' => 'can_delete_comment:not(0)',
		'type'      => 'radio'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name' => esc_html__('Answer settings','discy'),
		'id'   => 'answers_setting',
		'type' => 'heading-2'
	);
	
	$options[] = array(
		'name' => esc_html__('Enable or disable the editor for details in the answer','discy'),
		'id'   => 'answer_editor',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Allow for the user to answer one time per question','discy'),
		'id'   => 'answer_per_question',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name'    => esc_html__('Answer with question title style at the answers page.','discy'),
		'desc'    => esc_html__('Choose the answers with question title style at the answers page.','discy'),
		'id'      => 'answer_question_style',
		'options' => array('style_1' => 'Style 1','style_2' => 'Style 2','style_3' => 'Style 3'),
		'std'     => 'style_1',
		'type'    => 'radio'
	);
	
	$options[] = array(
		'name' => esc_html__('Add your minimum limit for the number of letters for the answer, like 15, 20, if you leave it empty it will make it not important','discy'),
		'id'   => 'answer_min_limit',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('Add your limit for the number of letters for the answer, like 140, 200, if you leave it empty it will make it unlimited','discy'),
		'id'   => 'answer_limit',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('Enable or disable the read more in the answer by jQuery','discy'),
		'id'   => 'read_more_answer',
		'type' => 'checkbox'
	);
	
	$answers_tabs = array(
		"votes"  => array("sort" => esc_html__('Voted','discy'),"value" => "votes"),
		"oldest" => array("sort" => esc_html__('Oldest','discy'),"value" => "oldest"),
		"recent" => array("sort" => esc_html__('Recent','discy'),"value" => "recent"),
		"random" => array("sort" => esc_html__('Random','discy'),"value" => ""),
	);
	
	$options[] = array(
		'name'    => esc_html__('Tabs at the answers','discy'),
		'desc'    => esc_html__('Select the tabs at the answers on the question page.','discy'),
		'id'      => 'answers_tabs',
		'type'    => 'multicheck',
		'sort'    => 'yes',
		'std'     => $answers_tabs,
		'options' => $answers_tabs
	);
	
	$options[] = array(
		'name' => esc_html__('Select ON to activate the vote at answers','discy'),
		'desc' => esc_html__('Select ON to enable the vote at the answers.','discy'),
		'id'   => 'active_vote_answer',
		'std'  => "on",
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name'      => esc_html__('Select ON to hide the dislike at answers','discy'),
		'desc'      => esc_html__('If you put it ON the dislike will not show.','discy'),
		'id'        => 'show_dislike_answers',
		'condition' => 'active_vote_answer:not(0)',
		'type'      => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Add answer anonymously','discy'),
		'desc' => esc_html__('Select ON to enable the answer anonymously.','discy'),
		'id'   => 'answer_anonymously',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => esc_html__('Video','discy'),
		'type' => 'info'
	);
	
	$options[] = array(
		'name' => esc_html__('Video in the answer form','discy'),
		'desc' => esc_html__('Select ON to enable the video in the answer form.','discy'),
		'id'   => 'answer_video',
		'type' => 'checkbox'
	);

	$options[] = array(
		'div'       => 'div',
		'condition' => 'answer_video:not(0)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'name'    => esc_html__('Video description position at answer','discy'),
		'desc'    => esc_html__('Choose the video description position.','discy'),
		'id'      => 'video_answer_position',
		'options' => array("before" => "Before content","after" => "After content"),
		'std'     => 'after',
		'type'    => 'select'
	);
	
	$options[] = array(
		'name' => esc_html__('Set the video description to 100%?','discy'),
		'desc' => esc_html__('Select ON if you want to set the video description to 100%.','discy'),
		'id'   => 'video_answer_100',
		'std'  => "on",
		'type' => 'checkbox'
	);
	
	$options[] = array(
		"name"      => esc_html__("Set the width for the video description for the answer","discy"),
		"id"        => "video_answer_width",
		'condition' => 'video_answer_100:not(on)',
		"type"      => "sliderui",
		'std'       => 260,
		"step"      => "1",
		"min"       => "50",
		"max"       => "600"
	);
	
	$options[] = array(
		"name" => esc_html__("Set the height for the video description for the answer","discy"),
		"id"   => "video_answer_height",
		"type" => "sliderui",
		'std'  => 500,
		"step" => "1",
		"min"  => "50",
		"max"  => "600"
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end',
		'div'  => 'div'
	);

	$options[] = array(
		'name' => esc_html__('Attachment','discy'),
		'type' => 'info'
	);

	$options[] = array(
		'name' => esc_html__('Attachment in the answer form','discy'),
		'desc' => esc_html__('Select ON to enable the attachment in the answer form.','discy'),
		'id'   => 'attachment_answer',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => esc_html__('Featured image','discy'),
		'type' => 'info'
	);
	
	$options[] = array(
		'name' => esc_html__('Featured image in the answer form','discy'),
		'desc' => esc_html__('Select ON to enable the featured image in the answer form.','discy'),
		'id'   => 'featured_image_answer',
		'std'  => 'on',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'featured_image_answer:not(0)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'name' => esc_html__('Select ON to show featured image in the question answers','discy'),
		'desc' => esc_html__('Select ON to enable the featured image in the question answers.','discy'),
		'id'   => 'featured_image_question_answers',
		'std'  => "on",
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Select ON to show featured image in the answers tab, answers template, answers at profile or answers is search','discy'),
		'desc' => esc_html__('Select ON to enable the featured image in the answers.','discy'),
		'id'   => 'featured_image_in_answers',
		'std'  => "on",
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Select ON to enable the lightbox for featured image','discy'),
		'desc' => esc_html__('Select ON to enable the lightbox for featured image.','discy'),
		'id'   => 'featured_image_answers_lightbox',
		'std'  => "on",
		'type' => 'checkbox'
	);
	
	$options[] = array(
		"name" => esc_html__("Set the width for the featured image for the answers","discy"),
		"id"   => "featured_image_answer_width",
		"type" => "sliderui",
		'std'  => 260,
		"step" => "1",
		"min"  => "50",
		"max"  => "600"
	);
	
	$options[] = array(
		"name" => esc_html__("Set the height for the featured image for the answers","discy"),
		"id"   => "featured_image_answer_height",
		"type" => "sliderui",
		'std'  => 185,
		"step" => "1",
		"min"  => "50",
		"max"  => "600"
	);
	
	$options[] = array(
		'name'    => esc_html__('Featured image position','discy'),
		'desc'    => esc_html__('Choose the featured image position.','discy'),
		'id'      => 'featured_answer_position',
		'options' => array("before" => "Before content","after" => "After content"),
		'std'     => 'before',
		'type'    => 'select'
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
		'name' => esc_html__('Search Settings','discy'),
		'id'   => 'search_setting',
		'icon' => 'search',
		'type' => 'heading',
	);
	
	$options[] = array(
		'type' => 'heading-2'
	);
	
	$options[] = array(
		'name' => esc_html__('Search slug','discy'),
		'desc' => esc_html__('Put the search slug.','discy'),
		'id'   => 'search_slug',
		'std'  => 'search',
		'type' => 'text'
	);
	
	if (has_wpqa()) {
		$options[] = array(
			'name' => '<a href="'.wpqa_get_search_permalink().'" target="_blank">'.esc_html__('The Link For The Search Page.','discy').'</a>',
			'type' => 'info'
		);
	}
	
	$search_attrs = array(
		"questions"         => array("sort" => esc_html__('Questions','discy'),"value" => "questions"),
		"answers"           => array("sort" => esc_html__('Answers','discy'),"value" => "answers"),
		"question-category" => array("sort" => esc_html__('Question categories','discy'),"value" => "question-category"),
		"question_tags"     => array("sort" => esc_html__('Question tags','discy'),"value" => "question_tags"),
		"groups"            => array("sort" => esc_html__('Groups','discy'),"value" => "groups"),
		"posts"             => array("sort" => esc_html__('Posts','discy'),"value" => "posts"),
		"comments"          => array("sort" => esc_html__('Comments','discy'),"value" => "comments"),
		"category"          => array("sort" => esc_html__('Post categories','discy'),"value" => "category"),
		"post_tag"          => array("sort" => esc_html__('Post tags','discy'),"value" => "post_tag"),
		"users"             => array("sort" => esc_html__('Users','discy'),"value" => "users"),
	);
	
	$options[] = array(
		'name'    => esc_html__('Select the search options','discy'),
		'desc'    => esc_html__('Select the search options on the search page.','discy'),
		'id'      => 'search_attrs',
		'type'    => 'multicheck',
		'sort'    => 'yes',
		'std'     => apply_filters("discy_search_attrs",$search_attrs),
		'options' => apply_filters("discy_search_attrs",$search_attrs)
	);
	
	$options[] = array(
		'name'    => esc_html__('Default search','discy'),
		'desc'    => esc_html__("Choose what's the default search","discy"),
		'id'      => 'default_search',
		'type'    => 'select',
		'stc'     => 'questions',
		'options' => array(
			"questions"         => esc_html__("Questions","discy"),
			"answers"           => esc_html__("Answers","discy"),
			"question-category" => esc_html__("Question categories","discy"),
			"question_tags"     => esc_html__("Question tags","discy"),
			"posts"             => esc_html__("Posts","discy"),
			"comments"          => esc_html__("Comments","discy"),
			"category"          => esc_html__("Post categories","discy"),
			"post_tag"          => esc_html__("Post tags","discy"),
			"users"             => esc_html__("Users","discy"),
		)
	);

	$options[] = array(
		'name' => esc_html__("Choose the live search enable or disable","discy"),
		'id'   => "live_search",
		'type' => "checkbox",
		'std'  => "on",
	);

	$options[] = array(
		'name' => esc_html__("Include the asked questions on the search or not","discy"),
		'id'   => "asked_questions_search",
		'type' => "checkbox",
	);

	$options[] = array(
		'name'      => esc_html__('Search result number','discy'),
		'desc'      => esc_html__('Type the search result number from here.','discy'),
		'id'        => 'search_result_number',
		'condition' => 'live_search:not(0)',
		'std'       => '5',
		'type'      => 'text'
	);
	
	$options[] = array(
		'name'    => esc_html__('Tags style at search page','discy'),
		'desc'    => esc_html__('Choose the tags style.','discy'),
		'id'      => 'tag_style_pages',
		'options' => array(
			'simple_follow' => esc_html__('Simple with follow','discy'),
			'advanced'      => esc_html__('Advanced','discy'),
			'simple'        => esc_html__('Simple','discy'),
		),
		'std'     => 'simple_follow',
		'type'    => 'radio'
	);
	
	$options[] = array(
		'name' => esc_html__('Show the user filter at search page.','discy'),
		'id'   => 'user_filter',
		'std'  => 'on',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Show the category search at category pages.','discy'),
		'id'   => 'cat_search',
		'std'  => 'on',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Show the tag search at tag pages.','discy'),
		'id'   => 'tag_search',
		'std'  => 'on',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name' => esc_html__('Sidebar','discy'),
		'id'   => 'sidebar',
		'icon' => 'align-none',
		'type' => 'heading'
	);
	
	$options[] = array(
		'type' => 'heading-2'
	);
	
	$sidebar_elements = array(
		array(
			"type" => "text",
			"id"   => "name",
			"name" => esc_html__('Sidebar name','discy')
		),
	);
	
	$options[] = array(
		'id'      => "sidebars",
		'type'    => "elements",
		'sort'    => "no",
		'button'  => esc_html__('Add a new sidebar','discy'),
		'options' => $sidebar_elements,
	);
	
	$options[] = array(
		'name'    => esc_html__('Sidebar layout','discy'),
		'id'      => "sidebar_layout",
		'std'     => "menu_sidebar",
		'type'    => "images",
		'options' => array(
			'menu_sidebar' => $imagepath.'menu_sidebar.jpg',
			'right'        => $imagepath.'sidebar_right.jpg',
			'full'         => $imagepath.'sidebar_no.jpg',
			'left'         => $imagepath.'sidebar_left.jpg',
			'centered'     => $imagepath.'centered.jpg',
			'menu_left'    => $imagepath.'menu_left.jpg',
		)
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'sidebar_layout:not(full),sidebar_layout:not(centered),sidebar_layout:not(menu_left)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'name'    => esc_html__('Sidebar','discy'),
		'id'      => "sidebar_home",
		'options' => $new_sidebars,
		'type'    => 'select'
	);
	
	$options[] = array(
		'name'    => esc_html__('Sticky sidebar','discy'),
		'id'      => 'sticky_sidebar',
		'std'     => 'side_menu_bar',
		'type'    => 'select',
		'options' => array(
			'sidebar'       => esc_html__('Sidebar','discy'),
			'nav_menu'      => esc_html__('Side menu (If enabled)','discy'),
			'side_menu_bar' => esc_html__('Sidebar & Side menu (If enabled)','discy'),
			'no_sidebar'    => esc_html__('Not active','discy'),
		)
	);
	
	$options[] = array(
		'name' => esc_html__('Widget icons enable or disable','discy'),
		'id'   => 'widget_icons',
		'std'  => 'on',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'div'       => 'div',
		'operator'  => 'or',
		'condition' => 'sidebar_layout:is(menu_sidebar),sidebar_layout:is(menu_left)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'name'    => esc_html__('Sidemenu style','discy'),
		'id'      => 'left_area',
		'std'     => 'menu',
		'type'    => 'radio',
		'options' => 
			array(
				"menu"    => esc_html__("Menu","discy"),
				"sidebar" => esc_html__("Sidebar","discy")
			)
	);
	
	$options[] = array(
		'name'      => esc_html__('Choose the left menu style','discy'),
		'id'        => "left_menu_style",
		'options'   => array('style_1' => 'Style 1','style_2' => 'Style 2','style_3' => 'Style 3'),
		'type'      => 'select',
		'condition' => 'left_area:not(sidebar)',
	);
	
	$options[] = array(
		'name'      => esc_html__('Sidebar 2','discy'),
		'id'        => "sidebar_home_2",
		'options'   => $new_sidebars,
		'type'      => 'select',
		'condition' => 'left_area:is(sidebar)',
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
		'name'    => esc_html__('Styling & Typography','discy'),
		'id'      => 'styling',
		'icon'    => 'art',
		'type'    => 'heading',
		'std'     => 'styling',
		'options' => array(
			"styling"    => esc_html__('Styling','discy'),
			"typography" => esc_html__('Typography','discy')
		)
	);
	
	$options[] = array(
		'name' => esc_html__('Styling','discy'),
		'id'   => 'styling',
		'type' => 'heading-2'
	);
	
	$options[] = array(
		'name' => esc_html__('Choose the site width','discy'),
		"id"   => "site_width",
		"type" => "sliderui",
		"std"  => "1170",
		"step" => "10",
		"min"  => "1170",
		"max"  => "1300"
	);

	$options[] = array(
		'name' => esc_html__('Discoura style','discy'),
		'desc' => esc_html__('Select ON to activate the Discoura style','discy'),
		'id'   => 'discoura_style',
		'type' => 'checkbox',
	);
	
	$options[] = array(
		'name'    => esc_html__('Site style','discy'),
		'id'      => 'site_style',
		'std'     => 'none',
		'type'    => 'radio',
		'options' => 
			array(
				"none"    => esc_html__("Normal style","discy"),
				"style_1" => esc_html__("Boxed style 1","discy"),
				"style_2" => esc_html__("Boxed style 2","discy"),
				"style_3" => esc_html__("Boxed style 3 - without left menu","discy"),
				"style_4" => esc_html__("Boxed style 4 - without left menu","discy"),
			)
	);
	
	$options[] = array(
		'name'    => esc_html__('Choose Your Skin','discy'),
		'class'   => "site_skin",
		'id'      => "site_skin",
		'std'     => "default",
		'type'    => "images",
		'options' => array(
			'default'    => $imagepath.'default.jpg',
			'violet'     => $imagepath.'violet.jpg',
			'bright_red' => $imagepath.'bright_red.jpg',
			'green'      => $imagepath.'green.jpg',
			'red'        => $imagepath.'red.jpg',
			'cyan'       => $imagepath.'cyan.jpg',
			'blue'       => $imagepath.'blue.jpg',
		)
	);
	
	$options[] = array(
		'name' => esc_html__('Primary Color','discy'),
		'id'   => 'primary_color',
		'type' => 'color'
	);
	
	$options[] = array(
		'name'    => esc_html__('Background Type','discy'),
		'id'      => 'background_type',
		'std'     => 'none',
		'type'    => 'radio',
		'options' => 
			array(
				"none"              => esc_html__("None","discy"),
				"patterns"          => esc_html__("Patterns","discy"),
				"custom_background" => esc_html__("Custom Background","discy")
			)
	);
	
	$options[] = array(
		'name'      => esc_html__('Background Color','discy'),
		'id'        => 'background_color',
		'type'      => 'color',
		'condition' => 'background_type:is(patterns)'
	);
		
	$options[] = array(
		'name'      => esc_html__('Choose Pattern','discy'),
		'id'        => "background_pattern",
		'std'       => "bg13",
		'type'      => "images",
		'condition' => 'background_type:is(patterns)',
		'class'     => "pattern_images",
		'options'   => array(
			'bg1'  => $imagepath.'bg1.jpg',
			'bg2'  => $imagepath.'bg2.jpg',
			'bg3'  => $imagepath.'bg3.jpg',
			'bg4'  => $imagepath.'bg4.jpg',
			'bg5'  => $imagepath.'bg5.jpg',
			'bg6'  => $imagepath.'bg6.jpg',
			'bg7'  => $imagepath.'bg7.jpg',
			'bg8'  => $imagepath.'bg8.jpg',
			'bg9'  => $imagepath_theme.'patterns/bg9.png',
			'bg10' => $imagepath_theme.'patterns/bg10.png',
			'bg11' => $imagepath_theme.'patterns/bg11.png',
			'bg12' => $imagepath_theme.'patterns/bg12.png',
			'bg13' => $imagepath.'bg13.jpg',
			'bg14' => $imagepath.'bg14.jpg',
			'bg15' => $imagepath_theme.'patterns/bg15.png',
			'bg16' => $imagepath_theme.'patterns/bg16.png',
			'bg17' => $imagepath.'bg17.jpg',
			'bg18' => $imagepath.'bg18.jpg',
			'bg19' => $imagepath.'bg19.jpg',
			'bg20' => $imagepath.'bg20.jpg',
			'bg21' => $imagepath_theme.'patterns/bg21.png',
			'bg22' => $imagepath.'bg22.jpg',
			'bg23' => $imagepath_theme.'patterns/bg23.png',
			'bg24' => $imagepath_theme.'patterns/bg24.png',
		)
	);
	
	$options[] = array(
		'name'      => esc_html__('Custom Background','discy'),
		'id'        => 'custom_background',
		'std'       => $background_defaults,
		'type'      => 'background',
		'options'   => $background_defaults,
		'condition' => 'background_type:is(custom_background)'
	);
	
	$options[] = array(
		'name'      => esc_html__('Full Screen Background','discy'),
		'desc'      => esc_html__('Select ON to enable Full Screen Background','discy'),
		'id'        => 'full_screen_background',
		'type'      => 'checkbox',
		'condition' => 'background_type:is(custom_background)'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name' => esc_html__('Typography','discy'),
		'id'   => 'typography',
		'type' => 'heading-2'
	);
	
	$options[] = array(
		"name"    => esc_html__('Main font','discy'),
		"id"      => "main_font",
		"type"    => "typography",
		'std'     => array("face" => "Default font","color" => "","style" => "","size" => 9),
		'options' => array("color" => false,"styles" => false,"sizes" => false)
	);
	
	$options[] = array(
		"name"    => esc_html__('Second font','discy'),
		"id"      => "second_font",
		"type"    => "typography",
		'std'     => array("face" => "Default font","color" => "","style" => "","size" => 9),
		'options' => array("color" => false,"styles" => false,"sizes" => false)
	);
	
	$options[] = array(
		"name"    => esc_html__('General Typography','discy'),
		"id"      => "general_typography",
		"type"    => "typography",
		'options' => array('faces' => false)
	);
	
	$options[] = array(
		'name' => esc_html__('General link color','discy'),
		"id"   => "general_link_color",
		"type" => "color"
	);
	
	$options[] = array(
		"name"    => esc_html__('H1','discy'),
		"id"      => "h1",
		"type"    => "typography",
		'options' => array('faces' => false,"color" => false)
	);
	
	$options[] = array(
		"name"    => esc_html__('H2','discy'),
		"id"      => "h2",
		"type"    => "typography",
		'options' => array('faces' => false,"color" => false)
	);
	
	$options[] = array(
		"name"    => esc_html__('H3','discy'),
		"id"      => "h3",
		"type"    => "typography",
		'options' => array('faces' => false,"color" => false)
	);
	
	$options[] = array(
		"name"    => esc_html__('H4','discy'),
		"id"      => "h4",
		"type"    => "typography",
		'options' => array('faces' => false,"color" => false)
	);
	
	$options[] = array(
		"name"    => esc_html__('H5','discy'),
		"id"      => "h5",
		"type"    => "typography",
		'options' => array('faces' => false,"color" => false)
	);
	
	$options[] = array(
		"name"    => esc_html__('H6','discy'),
		"id"      => "h6",
		"type"    => "typography",
		'options' => array('faces' => false,"color" => false)
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name'    => esc_html__('Social Settings','discy'),
		'id'      => 'social',
		'icon'    => 'share',
		'type'    => 'heading',
		'std'     => 'social',
		'options' => array(
			"social"          => esc_html__('Social Setting','discy'),
			"add_sort_social" => esc_html__('Add & sort social','discy'),
			"social_api"      => esc_html__('Social media API','discy')
		)
	);
	
	$options[] = array(
		'name' => esc_html__('Social Setting','discy'),
		'id'   => 'social',
		'type' => 'heading-2'
	);
	
	$social = array(
		array('name' => esc_html__('Facebook','discy'),"value" => "facebook","icon" => "facebook","default" => "yes"),
		array('name' => esc_html__('Twitter','discy'),"value" => "twitter","icon" => "twitter","default" => "yes"),
		array('name' => esc_html__('Linkedin','discy'),"value" => "linkedin","icon" => "linkedin","default" => "yes"),
		array('name' => esc_html__('Dribbble','discy'),"value" => "dribbble","icon" => "dribbble","default" => "yes"),
		array('name' => esc_html__('Youtube','discy'),"value" => "youtube","icon" => "play","default" => "yes"),
		array('name' => esc_html__('Vimeo','discy'),"value" => "vimeo","icon" => "vimeo","default" => "yes"),
		array('name' => esc_html__('Skype','discy'),"value" => "skype","icon" => "skype","default" => "yes"),
		array('name' => esc_html__('Flickr','discy'),"value" => "flickr","icon" => "flickr","default" => "yes"),
		array('name' => esc_html__('Soundcloud','discy'),"value" => "soundcloud","icon" => "soundcloud","default" => "yes"),
		array('name' => esc_html__('Instagram','discy'),"value" => "instagram","icon" => "instagrem","default" => "yes"),
		array('name' => esc_html__('Pinterest','discy'),"value" => "pinterest","icon" => "pinterest","default" => "yes"),
		array('name' => esc_html__('Rss','discy'),"value" => "rss","icon" => "rss","default" => "yes")
	);
	
	foreach ($social as $key => $value) {
		if ($value["value"] != "rss") {
			$options[] = array(
				'name' => sprintf(esc_html__('%s URL','discy'),esc_attr($value["name"])),
				'desc' => sprintf('Type the %s URL from here.',esc_attr($value["name"])),
				'id'   => $value["value"].'_icon_h',
				'std'  => '#',
				'type' => 'text'
			);
		}else {
			$options[] = array(
				'name' => esc_html__('Rss enable or disable','discy'),
				'id'   => 'rss_icon_h',
				'std'  => 'on',
				'type' => 'checkbox'
			);
			
			$options[] = array(
				'name'      => esc_html__('RSS URL if you want change the default URL','discy'),
				'desc'      => esc_html__('Type the RSS URL if you want change the default URL or leave it empty to enable the default URL.','discy'),
				'id'        => 'rss_icon_h_other',
				'condition' => 'rss_icon_h:not(0)',
				'type'      => 'text'
			);
		}
	}
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name' => esc_html__('Add a new social item','discy'),
		'id'   => 'add_sort_social',
		'type' => 'heading-2'
	);
	
	$elements = array(
		array(
			"type" => "text",
			"id"   => "name",
			"name" => esc_html__('Name','discy')
		),
		array(
			"type" => "text",
			"id"   => "url",
			"name" => esc_html__('URL','discy')
		),
		array(
			"type" => "text",
			"id"   => "icon",
			"name" => sprintf(esc_html__('Icon (use %1$s entypo %2$s like: facebook)','discy'),'<a href="https://2code.info/demo/themes/Discy/entypo/" target="_blank">','</a>')
		)
	);
	
	$options[] = array(
		'id'      => "add_social",
		'type'    => "elements",
		'button'  => esc_html__('Add Custom Social','discy'),
		'options' => $elements,
		'title'   => "name",
		'addto'   => "sort_social"
	);
	
	$options[] = array(
		'id'      => "sort_social",
		'std'     => $social,
		'type'    => "sort",
		'options' => $social,
		'delete'  => "yes",
		'getthe'  => $elements
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name' => esc_html__('Social media API','discy'),
		'id'   => 'social_api',
		'type' => 'heading-2'
	);
	
	$options[] = array(
		'name' => esc_html__('Facebook app id.','discy'),
		'id'   => 'facebook_app_id',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('Soundcloud client id.','discy'),
		'desc' => esc_html__('Type here the Soundcloud client id.','discy'),
		'id'   => 'soundcloud_client_id',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('Behance access token.','discy'),
		'desc' => esc_html__('Type here the Behance access token.','discy'),
		'id'   => 'behance_api_key',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('Google API.','discy'),
		'desc' => esc_html__('Type here the Google API.','discy'),
		'id'   => 'google_api',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('Instagram session.','discy'),
		'desc' => esc_html__('Type here the Instagram session.','discy'),
		'id'   => 'instagram_sessionid',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => sprintf(esc_html__('Dribbble app data (Make app from: https://dribbble.com/account/applications/new), At Callback URL add %1$s this link %2$s','discy'),'<a href="'.admin_url('admin.php?page=options&api=dribbble').'">','</a>'),
		'type' => 'info'
	);
	
	$options[] = array(
		'name' => esc_html__('Dribbble Client ID.','discy'),
		'id'   => 'dribbble_client_id',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('Dribbble Client Secret.','discy'),
		'id'   => 'dribbble_client_secret',
		'type' => 'text'
	);
	
	$dribbble_client_id = discy_options('dribbble_client_id');
	$options[] = array(
		'name' => '<a href="https://dribbble.com/oauth/authorize?client_id='.$dribbble_client_id.'" target="_blank">'.esc_html__('Get the access token from here.','discy').'</a>',
		'type' => 'info'
	);
	
	$options[] = array(
		'id'   => 'dribbble_access_token',
		'type' => 'hidden'
	);
	
	$options[] = array(
		'name' => esc_html__('Twitter app data.','discy'),
		'type' => 'info'
	);
	
	$options[] = array(
		'name' => esc_html__('Twitter consumer key','discy'),
		'id'   => 'twitter_consumer_key',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('Twitter consumer secret','discy'),
		'id'   => 'twitter_consumer_secret',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('Envato token.','discy'),
		'type' => 'info'
	);

	$options[] = array(
		'name' => esc_html__('Envato token','discy'),
		'id'   => 'envato_token',
		'type' => 'text'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name' => esc_html__('Advertising','discy'),
		'id'   => 'advertising',
		'icon' => 'admin-post',
		'type' => 'heading'
	);
	
	$options[] = array(
		'type' => 'heading-2'
	);
	
	$options[] = array(
		'name' => esc_html__('Advertising at 404 pages enable or disable','discy'),
		'id'   => 'adv_404',
		'std'  => 'on',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name' => esc_html__('Advertising after header','discy'),
		'type' => 'heading-2'
	);
	
	$options[] = array(
		'name'    => esc_html__('Advertising type','discy'),
		'id'      => 'header_adv_type_1',
		'std'     => 'custom_image',
		'type'    => 'radio',
		'options' => array("display_code" => esc_html__("Display code","discy"),"custom_image" => esc_html__("Custom Image","discy"))
	);
	
	$options[] = array(
		'name'      => esc_html__('Image URL','discy'),
		'desc'      => esc_html__('Upload a image, or enter URL to an image if it is already uploaded.','discy'),
		'id'        => 'header_adv_img_1',
		'condition' => 'header_adv_type_1:is(custom_image)',
		'type'      => 'upload'
	);
	
	$options[] = array(
		'name'      => esc_html__('Advertising url','discy'),
		'id'        => 'header_adv_href_1',
		'std'       => '#',
		'condition' => 'header_adv_type_1:is(custom_image)',
		'type'      => 'text'
	);
	
	$options[] = array(
		'name'      => esc_html__('Advertising Code html (Ex: Google ads)','discy'),
		'id'        => 'header_adv_code_1',
		'condition' => 'header_adv_type_1:not(custom_image)',
		'type'      => 'textarea'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name' => esc_html__('Advertising in post or question','discy'),
		'type' => 'heading-2'
	);
	
	$options[] = array(
		'name'    => esc_html__('Advertising type','discy'),
		'id'      => 'share_adv_type',
		'std'     => 'custom_image',
		'type'    => 'radio',
		'options' => array("display_code" => esc_html__("Display code","discy"),"custom_image" => esc_html__("Custom Image","discy"))
	);
	
	$options[] = array(
		'name'      => esc_html__('Image URL','discy'),
		'desc'      => esc_html__('Upload a image, or enter URL to an image if it is already uploaded.','discy'),
		'id'        => 'share_adv_img',
		'condition' => 'share_adv_type:is(custom_image)',
		'type'      => 'upload'
	);
	
	$options[] = array(
		'name'      => esc_html__('Advertising url','discy'),
		'id'        => 'share_adv_href',
		'std'       => '#',
		'condition' => 'share_adv_type:is(custom_image)',
		'type'      => 'text'
	);
	
	$options[] = array(
		'name'      => esc_html__('Advertising Code html (Ex: Google ads)','discy'),
		'id'        => 'share_adv_code',
		'condition' => 'share_adv_type:not(custom_image)',
		'type'      => 'textarea'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name' => esc_html__('Advertising after left menu','discy'),
		'type' => 'heading-2'
	);
	
	$options[] = array(
		'name'    => esc_html__('Advertising type','discy'),
		'id'      => 'left_menu_adv_type',
		'std'     => 'custom_image',
		'type'    => 'radio',
		'options' => array("display_code" => esc_html__("Display code","discy"),"custom_image" => esc_html__("Custom Image","discy"))
	);
	
	$options[] = array(
		'name'      => esc_html__('Image URL','discy'),
		'desc'      => esc_html__('Upload a image, or enter URL to an image if it is already uploaded.','discy'),
		'id'        => 'left_menu_adv_img',
		'condition' => 'left_menu_adv_type:is(custom_image)',
		'type'      => 'upload'
	);
	
	$options[] = array(
		'name'      => esc_html__('Advertising url','discy'),
		'id'        => 'left_menu_adv_href',
		'std'       => '#',
		'condition' => 'left_menu_adv_type:is(custom_image)',
		'type'      => 'text'
	);
	
	$options[] = array(
		'name'      => esc_html__('Advertising Code html (Ex: Google ads)','discy'),
		'id'        => 'left_menu_adv_code',
		'condition' => 'left_menu_adv_type:not(custom_image)',
		'type'      => 'textarea'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name' => esc_html__('Advertising after content','discy'),
		'type' => 'heading-2'
	);
	
	$options[] = array(
		'name'    => esc_html__('Advertising type','discy'),
		'id'      => 'content_adv_type',
		'std'     => 'custom_image',
		'type'    => 'radio',
		'options' => array("display_code" => esc_html__("Display code","discy"),"custom_image" => esc_html__("Custom Image","discy"))
	);
	
	$options[] = array(
		'name'      => esc_html__('Image URL','discy'),
		'desc'      => esc_html__('Upload a image, or enter URL to an image if it is already uploaded.','discy'),
		'id'        => 'content_adv_img',
		'condition' => 'content_adv_type:is(custom_image)',
		'type'      => 'upload'
	);
	
	$options[] = array(
		'name'      => esc_html__('Advertising url','discy'),
		'id'        => 'content_adv_href',
		'std'       => '#',
		'condition' => 'content_adv_type:is(custom_image)',
		'type'      => 'text'
	);
	
	$options[] = array(
		'name'      => esc_html__('Advertising Code html (Ex: Google ads)','discy'),
		'id'        => 'content_adv_code',
		'condition' => 'content_adv_type:not(custom_image)',
		'type'      => 'textarea'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name' => esc_html__('Between questions or posts','discy'),
		'type' => 'heading-2'
	);
	
	$options[] = array(
		'name' => esc_html__('Display after x posts or questions','discy'),
		'id'   => 'between_questions_position',
		'std'  => '2',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('Repeat adv?','discy'),
		'desc' => esc_html__('Select ON to enable repeat advertising.','discy'),
		'id'   => 'between_adv_type_repeat',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name'    => esc_html__('Advertising type','discy'),
		'id'      => 'between_adv_type',
		'std'     => 'custom_image',
		'type'    => 'radio',
		'options' => array("display_code" => esc_html__("Display code","discy"),"custom_image" => esc_html__("Custom Image","discy"))
	);
	
	$options[] = array(
		'name'      => esc_html__('Image URL','discy'),
		'desc'      => esc_html__('Upload a image, or enter URL to an image if it is already uploaded.','discy'),
		'id'        => 'between_adv_img',
		'condition' => 'between_adv_type:is(custom_image)',
		'type'      => 'upload'
	);
	
	$options[] = array(
		'name'      => esc_html__('Advertising url','discy'),
		'id'        => 'between_adv_href',
		'std'       => '#',
		'condition' => 'between_adv_type:is(custom_image)',
		'type'      => 'text'
	);
	
	$options[] = array(
		'name'      => esc_html__('Advertising Code html (Ex: Google ads)','discy'),
		'id'        => 'between_adv_code',
		'condition' => 'between_adv_type:not(custom_image)',
		'type'      => 'textarea'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name' => esc_html__('Between comments or answers','discy'),
		'type' => 'heading-2'
	);
	
	$options[] = array(
		'name' => esc_html__('Display after x comments or answers','discy'),
		'id'   => 'between_comments_position',
		'std'  => '2',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('Repeat adv?','discy'),
		'desc' => esc_html__('Select ON to enable repeat advertising.','discy'),
		'id'   => 'between_comments_adv_type_repeat',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name'    => esc_html__('Advertising type','discy'),
		'id'      => 'between_comments_adv_type',
		'std'     => 'custom_image',
		'type'    => 'radio',
		'options' => array("display_code" => esc_html__("Display code","discy"),"custom_image" => esc_html__("Custom Image","discy"))
	);
	
	$options[] = array(
		'name'      => esc_html__('Image URL','discy'),
		'desc'      => esc_html__('Upload a image, or enter URL to an image if it is already uploaded.','discy'),
		'id'        => 'between_comments_adv_img',
		'condition' => 'between_comments_adv_type:is(custom_image)',
		'type'      => 'upload'
	);
	
	$options[] = array(
		'name'      => esc_html__('Advertising url','discy'),
		'id'        => 'between_comments_adv_href',
		'std'       => '#',
		'condition' => 'between_comments_adv_type:is(custom_image)',
		'type'      => 'text'
	);
	
	$options[] = array(
		'name'      => esc_html__('Advertising Code html (Ex: Google ads)','discy'),
		'id'        => 'between_comments_adv_code',
		'condition' => 'between_comments_adv_type:not(custom_image)',
		'type'      => 'textarea'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);
	
	$email_settings = array(
		"general_mail"   => esc_html__('General setting','discy'),
		"users_mails"    => esc_html__('Users mails','discy'),
		"payments_mails" => esc_html__('Payments mails','discy'),
		"posts_mails"    => esc_html__('Posts and questions mails','discy'),
		"groups_mails"   => esc_html__('Groups mails','discy'),
		"comments_mails" => esc_html__('Comments mails','discy'),
		"other_mails"    => esc_html__('Other mails','discy'),
	);
	
	$options[] = array(
		'name'    => esc_html__('Mail settings','discy'),
		'id'      => 'mails_settings',
		'type'    => 'heading',
		'icon'    => 'email',
		'std'     => 'general_mail',
		'options' => $email_settings
	);
	
	$options[] = array(
		'name' => esc_html__('General setting','discy'),
		'id'   => 'general_mail',
		'type' => 'heading-2'
	);

	$options[] = array(
		'name'    => esc_html__('Email template style','discy'),
		'id'      => 'email_style',
		'std'     => 'style_1',
		'type'    => 'radio',
		'options' => array("style_1" => "Style 1","style_2" => "Style 2")
	);
	
	$options[] = array(
		'name' => esc_html__("Custom logo for mail template","discy"),
		'desc' => esc_html__("Upload your custom logo for mail template","discy"),
		'id'   => 'logo_email_template',
		'std'  => $imagepath_theme."logo.png",
		'type' => 'upload'
	);
	
	$options[] = array(
		'name'      => esc_html__('Background Color for the mail template','discy'),
		'id'        => 'background_email',
		'condition' => 'email_style:not(style_2)',
		'type'      => 'color',
		'std'       => '#272930'
	);

	$options[] = array(
		'name' => esc_html__('SMTP mail enable or disable','discy'),
		'id'   => 'mail_smtp',
		'type' => 'checkbox'
	);

	$options[] = array(
		'type'      => 'heading-2',
		'condition' => 'mail_smtp:not(0)',
		'div'       => 'div'
	);

	$options[] = array(
		'name' => esc_html__('SMTP mail host','discy'),
		'id'   => 'mail_host',
		'type' => 'text',
	);

	$options[] = array(
		'name' => esc_html__('SMTP mail port','discy'),
		'id'   => 'mail_port',
		'type' => 'text',
	);

	$options[] = array(
		'name' => esc_html__('SMTP mail username','discy'),
		'id'   => 'mail_username',
		'type' => 'text',
	);

	$options[] = array(
		'name' => esc_html__('SMTP mail password','discy'),
		'id'   => 'mail_password',
		'type' => 'password',
	);

	$options[] = array(
		'name'    => esc_html__('SMTP mail secure','discy'),
		'id'      => 'mail_secure',
		'std'     => 'ssl',
		'type'    => 'radio',
		'options' => array("ssl" => "SSL","tls" => "TLS","none" => esc_html__("No Encryption","discy"))
	);
	
	$options[] = array(
		'name'  => esc_html__('SMTP Authentication','discy'),
		'id'    => 'smtp_auth',
		'std'   => 'on',
		'type'  => 'checkbox'
	);
	
	$options[] = array(
		'name'  => esc_html__('Disable SSL Certificate Verification','discy'),
		'id'    => 'disable_ssl',
		'type'  => 'checkbox'
	);

	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);

	$options[] = array(
		'type'      => 'heading-2',
		'condition' => 'mail_smtp:not(on)',
		'div'       => 'div'
	);

	$parse = parse_url(get_site_url());
	
	$options[] = array(
		'name' => esc_html__("Add your email for mail template","discy"),
		'desc' => esc_html__("Add it professional email, like no_reply@2code.info","discy"),
		'id'   => 'email_template',
		'std'  => "no_reply@".$parse['host'],
		'type' => 'text'
	);

	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name' => esc_html__("Add your email to receive the different mails","discy"),
		'id'   => 'email_template_to',
		'std'  => get_bloginfo("admin_email"),
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('Mail footer enable or disable','discy'),
		'id'   => 'active_footer_email',
		'std'  => 'on',
		'type' => 'checkbox'
	);

	$options[] = array(
		'div'       => 'div',
		'condition' => 'active_footer_email:not(0)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'name' => esc_html__('Social for the mail in the footer enable or disable','discy'),
		'id'   => 'social_footer_email',
		'std'  => 'on',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__("Add your copyrights for your mail templates","discy"),
		'id'   => 'copyrights_for_email',
		'std'  => '&copy; 2021 Discy. All Rights Reserved<br>With Love by <a href="https://2code.info/" target="_blank">2code</a>.',
		'type' => 'textarea'
	);

	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);

	$options[] = array(
		'type'    => 'content',
		'content' => '<h4>Variables work at the all templates</h4>
		<p>[%blogname%] - The site title.</p>
		<p>[%site_url%] - The site URL.</p>
		<p>[%messages_url%] - The messages URL page.</p>',
	);
	
	$options[] = array(
		'type'    => 'content',
		'content' => '<h4>Variables work for the custom email template only</h4>
		<p>[%user_login%] - The user login name.</p>
		<p>[%user_name%] - The user name.</p>
		<p>[%user_nicename%] - The user nice name.</p>
		<p>[%display_name%] - The user display name.</p>
		<p>[%user_email%] - The user email.</p>
		<p>[%user_profile%] - The user profile URL.</p>',
	);
	
	$roles_user_only = $new_roles;
	unset($roles_user_only["wpqa_under_review"]);
	unset($roles_user_only["ban_group"]);
	unset($roles_user_only["activation"]);
	
	$options[] = array(
		'name'    => esc_html__('Choose the roles or users for the custom mail','discy'),
		'desc'    => esc_html__('Choose from here which roles or users you want to send the custom mail.','discy'),
		'id'      => 'mail_groups_users',
		'options' => array(
			'groups' => esc_html__('Roles','discy'),
			'users'  => esc_html__('Users','discy'),
		),
		'std'     => 'groups',
		'type'    => 'radio'
	);

	$options[] = array(
		'name'      => esc_html__("Choose the roles you need to send the custom mail for them.","discy"),
		'id'        => 'custom_mail_groups',
		'condition' => 'mail_groups_users:not(users)',
		'type'      => 'multicheck',
		'options'   => $new_roles,
		'std'       => array('administrator' => 'administrator','editor' => 'editor','contributor' => 'contributor','subscriber' => 'subscriber','author' => 'author'),
	);

	$options[] = array(
		'name'      => esc_html__('Specific user ids','discy'),
		'id'        => 'mail_specific_users',
		'condition' => 'mail_groups_users:is(users)',
		'type'      => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('Custom image for the custom mail','discy'),
		'id'   => 'custom_image_mail',
		'type' => 'upload'
	);

	$options[] = array(
		'name' => esc_html__('Custom mail title','discy'),
		'id'   => 'title_custom_mail',
		'std'  => 'Welcome',
		'type' => 'text'
	);
	
	$options[] = array(
		'name'     => esc_html__('Custom mail template','discy'),
		'id'       => 'email_custom_mail',
		'std'      => "<p>Hi [%display_name%]</p><p>Welcome to our site.</p><p>[%blogname%]</p><p><a href='[%site_url%]'>[%site_url%]</a></p>",
		'type'     => 'editor',
		'settings' => $wp_editor_settings
	);
	
	$options[] = array(
		'name' => esc_html__('You must save your options before send the message.','discy'),
		'type' => 'info'
	);

	$options[] = array(
		'name' => '<a href="#" class="button button-primary send-custom-mail">'.esc_html__('Send the custom mail','discy').'</a>',
		'type' => 'info'
	);

	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name' => esc_html__('Users mails','discy'),
		'id'   => 'users_mails',
		'type' => 'heading-2'
	);
	
	$options[] = array(
		'type'    => 'content',
		'content' => '<h4>Variables work for all next templates</h4>
		<p>[%user_login%] - The user login name.</p>
		<p>[%user_name%] - The user name.</p>
		<p>[%user_nicename%] - The user nice name.</p>
		<p>[%display_name%] - The user display name.</p>
		<p>[%user_email%] - The user email.</p>
		<p>[%user_profile%] - The user profile URL.</p>',
	);
	
	$options[] = array(
		'type'    => 'content',
		'content' => '<h4>Variable works at Reset password and Confirm mail</h4>
		<p>[%confirm_link_email%] - Confirm mail for the user to reset the password at reset password template and at the confirm mail template is confirm mail to active the user.</p>',
	);

	$options[] = array(
		'div'       => 'div',
		'condition' => 'confirm_email:not(0)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'name' => esc_html__('Confirm mail title','discy'),
		'id'   => 'title_confirm_link',
		'std'  => "Confirm account",
		'type' => 'text'
	);
	
	$options[] = array(
		'name'     => esc_html__('Confirm mail template','discy'),
		'id'       => 'email_confirm_link',
		'std'      => "<p>Hi there</p><p>Your registration has been successful! To confirm your account, kindly click on 'Activate' below.</p><p><a href='[%confirm_link_email%]'>Activate</a></p><p>If the link above does not work, Please use your browser to go to:</p>[%confirm_link_email%]</p>",
		'type'     => 'editor',
		'settings' => $wp_editor_settings
	);
	
	$options[] = array(
		'name' => esc_html__('Confirm mail 2 title','discy'),
		'id'   => 'title_confirm_link_2',
		'std'  => "Confirm account",
		'type' => 'text'
	);
	
	$options[] = array(
		'name'     => esc_html__('Confirm mail 2 template','discy'),
		'id'       => 'email_confirm_link_2',
		'std'      => "<p>Hi there</p><p>This is the link to activate your membership</p><p><a href='[%confirm_link_email%]'>Activate</a></p><p>If the link above does not work, Please use your browser to go to:</p>[%confirm_link_email%]</p>",
		'type'     => 'editor',
		'settings' => $wp_editor_settings
	);

	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);

	$options[] = array(
		'div'       => 'div',
		'condition' => 'send_welcome_mail:not(0)',
		'type'      => 'heading-2'
	);

	$options[] = array(
		'name' => esc_html__('New welcome mail title','discy'),
		'id'   => 'title_welcome_mail',
		'std'  => 'Welcome',
		'type' => 'text'
	);
	
	$options[] = array(
		'name'     => esc_html__('New welcome mail template','discy'),
		'id'       => 'email_welcome_mail',
		'std'      => "<p>Hi [%display_name%]</p><p>Welcome to our site.</p><p>[%blogname%]</p><p><a href='[%site_url%]'>[%site_url%]</a></p>",
		'type'     => 'editor',
		'settings' => $wp_editor_settings
	);

	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);

	$options[] = array(
		'div'       => 'div',
		'condition' => 'user_review:not(0),send_email_users_review:not(0)',
		'type'      => 'heading-2'
	);

	$options[] = array(
		'name' => esc_html__('New user for review title','discy'),
		'id'   => 'title_review_user',
		'std'  => "New user for review",
		'type' => 'text'
	);
	
	$options[] = array(
		'name'     => esc_html__('New user for review template','discy'),
		'id'       => 'email_review_user',
		'std'      => "<p>Hi there</p><p>There is a new user for the review named [%user_name%]</p><p><a href='[%users_link%]'>Review him</a></p>",
		'type'     => 'editor',
		'settings' => $wp_editor_settings
	);

	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);

	$options[] = array(
		'div'       => 'div',
		'condition' => 'user_review:not(0)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'name' => esc_html__('Approve user title','discy'),
		'id'   => 'title_approve_user',
		'std'  => "Confirm account",
		'type' => 'text'
	);
	
	$options[] = array(
		'name'     => esc_html__('Approve user template','discy'),
		'id'       => 'email_approve_user',
		'std'      => "<p>Hi there</p><p>The admin was activated your account.</p><p><a href='[%site_url%]'>[%blogname%]</a></p><p>[%site_url%]</p>",
		'type'     => 'editor',
		'settings' => $wp_editor_settings
	);

	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name' => esc_html__('Reset password title','discy'),
		'id'   => 'title_new_password',
		'std'  => "Reset your password",
		'type' => 'text'
	);
	
	$options[] = array(
		'name'     => esc_html__('Reset password template','discy'),
		'id'       => 'email_new_password',
		'std'      => "<p>Someone requested that the password be reset for the following account:</p><p>Username: '[%display_name%]' ([%user_login%]).</p><p>If this was a mistake, just ignore this mail and nothing will happen.</p><p>To reset your password, visit the following address:</p><p><a href='[%confirm_link_email%]'>Click here to reset your password</a></p><p>If the link above does not work, Please use your browser to go to:</p>[%confirm_link_email%]",
		'type'     => 'editor',
		'settings' => $wp_editor_settings
	);
	
	$options[] = array(
		'type'    => 'content',
		'content' => '<h4>Variable works at this template only</h4>
		<p>[%reset_password%] - The user password.</p>',
	);
	
	$options[] = array(
		'name' => esc_html__('Reset password 2 title','discy'),
		'id'   => 'title_new_password_2',
		'std'  => "Reset your password",
		'type' => 'text'
	);
	
	$options[] = array(
		'name'     => esc_html__('Reset password 2 template','discy'),
		'id'       => 'email_new_password_2',
		'std'      => "<p>You are: [%display_name%] ([%user_login%])</p><p>The New Password: [%reset_password%]</p>",
		'type'     => 'editor',
		'settings' => $wp_editor_settings
	);

	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name' => esc_html__('Payments mails','discy'),
		'id'   => 'payments_mails',
		'type' => 'heading-2'
	);

	$options[] = array(
		'name'      => esc_html__('Note: if you need this mail template, From Discy Settings/Payment settings/Activate one of the payments functions.','discy'),
		'condition' => 'buy_points_payment:is(0),buy_points_payment:is(0),pay_ask:is(0),pay_to_sticky:is(0)',
		'type'      => 'info'
	);
	
	$options[] = array(
		'type'      => 'heading-2',
		'operator'  => 'or',
		'condition' => 'buy_points_payment:not(0),buy_points_payment:not(0),pay_ask:not(0),pay_to_sticky:not(0)',
		'div'       => 'div'
	);
	
	$options[] = array(
		'type'    => 'content',
		'content' => '<h4>Variables work at this template only</h4>
		<p>[%item_price%] - Show the item price.</p>
		<p>[%item_name%] - Show the item name.</p>
		<p>[%item_currency%] - Show the item currency.</p>
		<p>[%payer_email%] - Show the payer email.</p>
		<p>[%first_name%] - Show the payer first name.</p>
		<p>[%last_name%] - Show the payer last name.</p>
		<p>[%item_transaction%] - Show the transaction id.</p>
		<p>[%date%] - Show the payment date.</p>
		<p>[%time%] - Show the payment time.</p>',
	);
	
	$options[] = array(
		'name' => esc_html__('New payment title','discy'),
		'id'   => 'title_new_payment',
		'std'  => "Instant Payment Notification - Received Payment",
		'type' => 'text'
	);
	
	$options[] = array(
		'name'     => esc_html__('New payment template','discy'),
		'id'       => 'email_new_payment',
		'std'      => "<p>An instant payment notification was successfully received</p><p>With [%item_price%] [%item_currency%]</p><p>From [%payer_email%] [%first_name%] - [%last_name%] on [%date%] at [%time%]</p><p>The item transaction id [%item_transaction%]</p>",
		'type'     => 'editor',
		'settings' => $wp_editor_settings
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
		'name' => esc_html__('Posts and questions mails','discy'),
		'id'   => 'posts_mails',
		'type' => 'heading-2'
	);
	
	$options[] = array(
		'type'      => 'heading-2',
		'condition' => 'active_message:not(0),send_email_message:not(0)',
		'div'       => 'div'
	);
	
	$options[] = array(
		'type'    => 'content',
		'content' => '<h4>Variables work at Send message template and New questions template to show the details for the received user</h4>
		<p>[%user_login%] - The user login name.</p>
		<p>[%user_name%] - The user name.</p>
		<p>[%user_nicename%] - The user nice name.</p>
		<p>[%display_name%] - The user display name.</p>
		<p>[%user_email%] - The user email.</p>
		<p>[%user_profile%] - The user profile URL.</p>',
	);
	
	$options[] = array(
		'type'    => 'content',
		'content' => '<h4>Variables work at Send message template and New questions template to show the details for the sender user</h4>
		<p>[%user_login_sender%] - The user login name.</p>
		<p>[%user_name_sender%] - The user name.</p>
		<p>[%user_nicename_sender%] - The user nice name.</p>
		<p>[%display_name_sender%] - The user display name.</p>
		<p>[%user_email_sender%] - The user email.</p>
		<p>[%user_profile_sender%] - The user profile URL.</p>',
	);
	
	$options[] = array(
		'type'    => 'content',
		'content' => '<h4>Variable works at this template only</h4>
		<p>[%messages_title%] - Show the message title.</p>',
	);
	
	$options[] = array(
		'name' => esc_html__('Send message title','discy'),
		'id'   => 'title_new_message',
		'std'  => "New message",
		'type' => 'text'
	);
	
	$options[] = array(
		'name'     => esc_html__('Send message template','discy'),
		'id'       => 'email_new_message',
		'std'      => "<p>Hi there</p><p>There is a new message</p><p><a href='[%messages_url%]'>[%messages_title%]</a></p>",
		'type'     => 'editor',
		'settings' => $wp_editor_settings
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);

	$options[] = array(
		'type'      => 'heading-2',
		'operator'  => 'or',
		'condition' => 'send_email_new_question:not(0),send_email_draft_questions:not(0),send_email_draft_posts:not(0),active_reports:not(0)',
		'div'       => 'div'
	);
	
	$options[] = array(
		'type'      => 'heading-2',
		'condition' => 'question_schedules:not(0)',
		'div'       => 'div'
	);
	
	$options[] = array(
		'name' => esc_html__('Recent questions as schedules title','discy'),
		'id'   => 'title_question_schedules',
		'std'  => "Recent questions",
		'type' => 'text'
	);
	
	$options[] = array(
		'name'     => esc_html__('Recent questions as schedules template','discy'),
		'id'       => 'email_question_schedules',
		'std'      => "<p>Hi there</p><p>There are new questions</p>",
		'type'     => 'editor',
		'settings' => $wp_editor_settings
	);
	
	$options[] = array(
		'name'     => esc_html__('Content after schedules mails for the recent questions','discy'),
		'id'       => 'schedule_content',
		'std'      => "<p>[%blogname%]</p><p>[%site_url%]</p>",
		'type'     => 'editor',
		'settings' => $wp_editor_settings
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);

	$options[] = array(
		'type'    => 'content',
		'content' => '<h4>Variables work for all next templates</h4>
		<p>[%post_title%] - Show the post title.</p>
		<p>[%post_link%] - Show the post link.</p>
		<p>[%the_author_post%] - Show the post author.</p>',
	);
	
	$options[] = array(
		'type'      => 'heading-2',
		'condition' => 'send_email_new_question:not(0)',
		'div'       => 'div'
	);
	
	$options[] = array(
		'name' => esc_html__('New question title','discy'),
		'id'   => 'title_new_questions',
		'std'  => "New question",
		'type' => 'text'
	);
	
	$options[] = array(
		'name'     => esc_html__('New question template','discy'),
		'id'       => 'email_new_questions',
		'std'      => "<p>Hi there</p><p>There is a new question</p><p><a href='[%post_link%]'>[%post_title%]</a></p>",
		'type'     => 'editor',
		'settings' => $wp_editor_settings
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'type'      => 'heading-2',
		'condition' => 'send_email_draft_questions:not(0)',
		'div'       => 'div'
	);
	
	$options[] = array(
		'name' => esc_html__('New question for review title','discy'),
		'id'   => 'title_new_draft_questions',
		'std'  => "New question for review",
		'type' => 'text'
	);
	
	$options[] = array(
		'name'     => esc_html__('New question for review template','discy'),
		'id'       => 'email_draft_questions',
		'std'      => "<p>Hi there</p><p>There is a new question for the review</p><p><a href='[%post_link%]'>[%post_title%]</a></p>",
		'type'     => 'editor',
		'settings' => $wp_editor_settings
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'type'      => 'heading-2',
		'condition' => 'send_email_draft_posts:not(0)',
		'div'       => 'div'
	);
	
	$options[] = array(
		'name' => esc_html__('New post for review title','discy'),
		'id'   => 'title_new_draft_posts',
		'std'  => "New post for review",
		'type' => 'text'
	);
	
	$options[] = array(
		'name'     => esc_html__('New post for review template','discy'),
		'id'       => 'email_draft_posts',
		'std'      => "<p>Hi there</p><p>There is a new post for the review</p><p><a href='[%post_link%]'>[%post_title%]</a></p>",
		'type'     => 'editor',
		'settings' => $wp_editor_settings
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'type'      => 'heading-2',
		'condition' => 'active_reports:not(0)',
		'div'       => 'div'
	);
	
	$options[] = array(
		'name' => esc_html__('Report question title','discy'),
		'id'   => 'title_report_question',
		'std'  => "Report Question",
		'type' => 'text'
	);
	
	$options[] = array(
		'name'     => esc_html__('Report question template','discy'),
		'id'       => 'email_report_question',
		'std'      => "<p>Hi there</p><p>Abuse has been reported on the use of the following question</p><p><a href='[%post_link%]'>[%post_title%]</a></p>",
		'type'     => 'editor',
		'settings' => $wp_editor_settings
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
		'name' => esc_html__('Groups mails','discy'),
		'id'   => 'groups_mails',
		'type' => 'heading-2'
	);

	$options[] = array(
		'type'      => 'heading-2',
		'operator'  => 'or',
		'condition' => 'send_email_draft_groups:not(0),send_email_draft_group_posts:not(0)',
		'div'       => 'div'
	);

	$options[] = array(
		'type'      => 'heading-2',
		'condition' => 'send_email_draft_groups:not(0)',
		'div'       => 'div'
	);
	
	$options[] = array(
		'name' => esc_html__('New group for review title','discy'),
		'id'   => 'title_new_draft_groups',
		'std'  => "New group for review",
		'type' => 'text'
	);
	
	$options[] = array(
		'name'     => esc_html__('New group for review template','discy'),
		'id'       => 'email_draft_groups',
		'std'      => "<p>Hi there</p><p>There is a new group for the review</p><p><a href='[%post_link%]'>[%post_title%]</a></p>",
		'type'     => 'editor',
		'settings' => $wp_editor_settings
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);

	$options[] = array(
		'type'      => 'heading-2',
		'condition' => 'send_email_draft_group_posts:not(0)',
		'div'       => 'div'
	);
	
	$options[] = array(
		'name' => esc_html__('New post on the group for review title','discy'),
		'id'   => 'email_draft_group_posts',
		'std'  => "New post on the group for review",
		'type' => 'text'
	);
	
	$options[] = array(
		'name'     => esc_html__('New post on the group for review template','discy'),
		'id'       => 'title_new_draft_group_posts',
		'std'      => "<p>Hi there</p><p>There is a new post on the group for the review</p><p><a href='[%post_link%]'>[%post_title%]</a></p>",
		'type'     => 'editor',
		'settings' => $wp_editor_settings
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
		'name' => esc_html__('Comments mails','discy'),
		'id'   => 'comments_mails',
		'type' => 'heading-2'
	);
	
	$options[] = array(
		'type'      => 'heading-2',
		'operator'  => 'or',
		'condition' => 'active_reports:not(0),ask_question_items:has(remember_answer),question_follow:not(0),send_email_draft_comments:not(0)',
		'div'       => 'div'
	);
	
	$options[] = array(
		'type'    => 'content',
		'content' => '<h4>Variables work at Send message template and New questions template to show the details for the received user</h4>
		<p>[%user_login%] - The user login name.</p>
		<p>[%user_name%] - The user name.</p>
		<p>[%user_nicename%] - The user nice name.</p>
		<p>[%display_name%] - The user display name.</p>
		<p>[%user_email%] - The user email.</p>
		<p>[%user_profile%] - The user profile URL.</p>',
	);
	
	$options[] = array(
		'type'    => 'content',
		'content' => '<h4>Variables work at Notified answer template and Follow question template to show the details for the sender user</h4>
		<p>[%user_login_sender%] - The user login name.</p>
		<p>[%user_name_sender%] - The user name.</p>
		<p>[%user_nicename_sender%] - The user nice name.</p>
		<p>[%display_name_sender%] - The user display name.</p>
		<p>[%user_email_sender%] - The user email.</p>
		<p>[%user_profile_sender%] - The user profile URL.</p>',
	);

	$options[] = array(
		'type'    => 'content',
		'content' => '<h4>Variables work for all next templates</h4>
		<p>[%post_title%] - Show the post title.</p>
		<p>[%post_link%] - Show the post link.</p>
		<p>[%the_author_post%] - Show the post author.</p>',
	);
	
	$options[] = array(
		'type'    => 'content',
		'content' => '<h4>Variables work at Report answer, Notified answer and Follow question</h4>
		<p>[%answer_url%] - Show the answer link.</p>
		<p>[%the_name%] - Show the answer author.</p>',
	);
	
	$options[] = array(
		'type'      => 'heading-2',
		'condition' => 'active_reports:not(0)',
		'div'       => 'div'
	);
	
	$options[] = array(
		'name' => esc_html__('Report answer title','discy'),
		'id'   => 'title_report_answer',
		'std'  => "Report Answer",
		'type' => 'text'
	);
	
	$options[] = array(
		'name'     => esc_html__('Report answer template','discy'),
		'id'       => 'email_report_answer',
		'std'      => "<p>Hi there</p><p>Abuse has been reported on the use of the following comment</p><p><a href='[%answer_url%]'>[%post_title%]</a></p>",
		'type'     => 'editor',
		'settings' => $wp_editor_settings
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'type'      => 'heading-2',
		'condition' => 'ask_question_items:has(remember_answer)',
		'div'       => 'div'
	);
	
	$options[] = array(
		'name' => esc_html__('Notified answer title','discy'),
		'id'   => 'title_notified_answer',
		'std'  => "Answer to your question",
		'type' => 'text'
	);
	
	$options[] = array(
		'name'     => esc_html__('Notified answer template','discy'),
		'id'       => 'email_notified_answer',
		'std'      => "<p>Hi there</p><p>We would tell you [%the_author_post%] That the new post was added on a common theme by [%the_name%] Entitled [%the_name%] [%post_title%]</p><p>Click on the link below to go to the topic</p><p><a href='[%answer_url%]'>[%post_title%]</a></p><p>There may be more of Posts and we hope the answer to encourage members and get them to help.</p><p>Accept from us Sincerely</p>",
		'type'     => 'editor',
		'settings' => $wp_editor_settings
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name'     => esc_html__('Notified reply on the answer title','vbegy'),
		'id'       => 'title_notified_reply',
		'std'      => "Reply to your answer",
		'type'     => 'text'
	);
	
	$options[] = array(
		'name'     => esc_html__('Notified reply on the answer template','vbegy'),
		'id'       => 'email_notified_reply',
		'std'      => "<p>Hi there</p><p>There is a new reply to your following answer</p><p><a href='[%answer_link%]'>[%post_title%]</a></p>",
		'type'     => 'editor',
		'settings' => $wp_editor_settings
	);
	
	$options[] = array(
		'type'      => 'heading-2',
		'condition' => 'question_follow:not(0)',
		'div'       => 'div'
	);
	
	$options[] = array(
		'name' => esc_html__('Follow question title','discy'),
		'id'   => 'title_follow_question',
		'std'  => "New answer on your following question",
		'type' => 'text'
	);
	
	$options[] = array(
		'name'     => esc_html__('Follow question template','discy'),
		'id'       => 'email_follow_question',
		'std'      => "<p>Hi there</p><p>There is a new answer to your following question</p><p><a href='[%answer_url%]'>[%post_title%]</a></p>",
		'type'     => 'editor',
		'settings' => $wp_editor_settings
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'type'      => 'heading-2',
		'condition' => 'send_email_draft_comments:not(0)',
		'div'       => 'div'
	);

	$options[] = array(
		'type'    => 'content',
		'content' => '<h4>Variable works for this template</h4>
		<p>[%comment_link%] - To review the comment/answer.</p>',
	);
	
	$options[] = array(
		'name' => esc_html__('New comment/answer for review title','discy'),
		'id'   => 'title_new_draft_comments',
		'std'  => "New comment for review",
		'type' => 'text'
	);
	
	$options[] = array(
		'name'     => esc_html__('New comment/answer for review template','discy'),
		'id'       => 'email_draft_comments',
		'std'      => "<p>Hi there</p><p>There is a new comment for the review on this post [%post_title%]</p><p><a href='[%comment_link%]'>Review it</a></p>",
		'type'     => 'editor',
		'settings' => $wp_editor_settings
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
		'name' => esc_html__('Other mails','discy'),
		'id'   => 'other_mails',
		'type' => 'heading-2'
	);

	$options[] = array(
		'name'      => esc_html__('Note: if you need this mail template, From Discy Settings/Question settings/Questions category settings/Activate the users to request a new category && Send mail when the category needs a review.','discy'),
		'operator'  => 'or',
		'condition' => 'send_email_add_category:is(0),allow_user_to_add_category:is(0)',
		'type'      => 'info'
	);
	
	$options[] = array(
		'type'      => 'heading-2',
		'condition' => 'send_email_add_category:not(0),allow_user_to_add_category:not(0)',
		'div'       => 'div'
	);

	$options[] = array(
		'type'    => 'content',
		'content' => '<h4>Variables work at this template only</h4>
		<p>[%category_link%] - Review the categories link.</p>
		<p>[%category_name%] - The category name.</p>',
	);

	$options[] = array(
		'name' => esc_html__('New category for review title','discy'),
		'id'   => 'title_add_category',
		'std'  => "New category for review",
		'type' => 'text'
	);
	
	$options[] = array(
		'name'     => esc_html__('New category for review template','discy'),
		'id'       => 'email_add_category',
		'std'      => "<p>Hi there</p><p>There is a new category for the review</p><p><a href='[%category_link%]'>[%category_name%]</a></p>",
		'type'     => 'editor',
		'settings' => $wp_editor_settings
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);

	$options[] = array(
		'type'    => 'content',
		'content' => '<h4>Variables work at this template only</h4>
		<p>[%user_login%] - The user login name.</p>
		<p>[%user_name%] - The user name.</p>
		<p>[%user_nicename%] - The user nice name.</p>
		<p>[%display_name%] - The user display name.</p>
		<p>[%user_email%] - The user email.</p>
		<p>[%user_profile%] - The user profile URL.</p>
		<p>[%invitation_link%] - The invitation URL.</p>',
	);

	$options[] = array(
		'name' => esc_html__('New invitation','discy'),
		'id'   => 'title_new_invitation',
		'std'  => "New invitation",
		'type' => 'text'
	);
	
	$options[] = array(
		'name'     => esc_html__('New invitation template','discy'),
		'id'       => 'email_new_invitation',
		'std'      => "<p>Hi there</p><p>There is a new invitation for you from your friend [%display_name%]</p><p><a href='[%invitation_link%]'>Join to [%blogname%] site</a></p><p><a href='[%invitation_link%]'>[%invitation_link%]</a></p>",
		'type'     => 'editor',
		'settings' => $wp_editor_settings
	);

	$options[] = array(
		'type'    => 'content',
		'content' => '<h4>Variables work at this template only</h4>
		<p>[%request_link%] - Review the request link.</p>
		<p>[%request_name%] - The request name.</p>',
	);

	$options[] = array(
		'name' => esc_html__('New request for review title','discy'),
		'id'   => 'title_new_request',
		'std'  => "New request for review",
		'type' => 'text'
	);
	
	$options[] = array(
		'name'     => esc_html__('New request for review template','discy'),
		'id'       => 'email_new_request',
		'std'      => "<p>Hi there</p><p>There is a new request for the review</p><p><a href='[%request_link%]'>[%request_name%]</a></p>",
		'type'     => 'editor',
		'settings' => $wp_editor_settings
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name'    => esc_html__('Footer settings','discy'),
		'id'      => 'footer',
		'icon'    => 'tagcloud',
		'type'    => 'heading',
		'std'     => 'footer_general',
		'options' => array(
			"footer_general"  => esc_html__('General setting','discy'),
			"footer_main"     => esc_html__('Main Footer setting','discy'),
			"footer_bottom"   => esc_html__('Bottom footer setting','discy'),
			"footer_sort"     => esc_html__('Sort footer elements','discy')
		)
	);
	
	$options[] = array(
		'name' => esc_html__('General setting','discy'),
		'id'   => 'footer_general',
		'type' => 'heading-2'
	);
	
	$options[] = array(
		'name'    => esc_html__('Footer style','discy'),
		'desc'    => esc_html__('Choose the footer style.','discy'),
		'id'      => 'footer_style',
		'std'     => 'footer',
		'type'    => 'radio',
		'options' => array("footer" => esc_html__("Normal footer","discy"),"sidebar" => esc_html__("After sidebar","discy"))
	);
	
	$options[] = array(
		'name'      => esc_html__('Footer skin','discy'),
		'desc'      => esc_html__('Choose the footer skin.','discy'),
		'id'        => 'footer_skin',
		'std'       => 'dark',
		'type'      => 'radio',
		'condition' => 'footer_style:not(sidebar)',
		'options'   => array("dark" => esc_html__("Dark","discy"),"light" => esc_html__("Light","discy"))
	);
	
	$options[] = array(
		'name'      => esc_html__('Footer menu enable or disable','discy'),
		'id'        => 'active_footer_menu',
		'std'       => 'on',
		'condition' => 'footer_style:not(footer)',
		'type'      => 'checkbox'
	);
	
	$options[] = array(
		'name'      => esc_html__("Choose from here what's menu will show after sidebar.","discy"),
		'id'        => 'footer_menu',
		'type'      => 'select',
		'condition' => 'footer_style:not(footer),active_footer_menu:not(0)',
		'options'   => $menus
	);
	
	$options[] = array(
		'name'      => esc_html__('Copyrights','discy'),
		'desc'      => esc_html__('Put the copyrights of footer.','discy'),
		'id'        => 'footer_copyrights',
		'std'       => '&copy; 2021 Discy. All Rights Reserved<br>With Love by <a href="https://2code.info/" target="_blank">2code</a>.',
		'operator'  => 'or',
		'condition' => 'footer_style:not(footer),bottom_footer:not(0)',
		'type'      => 'textarea'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name' => esc_html__('Main Footer setting','discy'),
		'id'   => 'footer_main',
		'type' => 'heading-2'
	);
	
	$options[] = array(
		'name'      => esc_html__('The main footer work when you choose the footer style as normal footer.','discy'),
		'condition' => 'footer_style:not(footer)',
		'type'      => 'info'
	);
	
	$options[] = array(
		'type'      => 'heading-2',
		'condition' => 'footer_style:not(sidebar)',
		'div'       => 'div'
	);
	
	$options[] = array(
		'name' => esc_html__('Top footer enable or disable','discy'),
		'id'   => 'top_footer',
		'std'  => 'on',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'top_footer:not(0)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'name' => esc_html__('Footer widget icons enable or disable','discy'),
		'id'   => 'footer_widget_icons',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Top footer padding top','discy'),
		"id"   => "top_footer_padding_top",
		"type" => "sliderui",
		"std"  => "0",
		"step" => "1",
		"min"  => "0",
		"max"  => "100"
	);
	
	$options[] = array(
		'name' => esc_html__('Top footer padding bottom','discy'),
		"id"   => "top_footer_padding_bottom",
		"type" => "sliderui",
		"std"  => "0",
		"step" => "1",
		"min"  => "0",
		"max"  => "100"
	);
	
	$options[] = array(
		'name'    => esc_html__('Footer Layout','discy'),
		'desc'    => esc_html__('Footer columns Layout.','discy'),
		'id'      => "footer_layout",
		'std'     => "footer_5c",
		'type'    => "images",
		'options' => array(
			'footer_1c' => $imagepath.'footer_1c.jpg',
			'footer_2c' => $imagepath.'footer_2c.jpg',
			'footer_3c' => $imagepath.'footer_3c.jpg',
			'footer_4c' => $imagepath.'footer_4c.jpg',
			'footer_5c' => $imagepath.'footer_5c.jpg')
	);
	
	$footer_elements = array(
		array(
			"type" => "color",
			"id"   => "background_color",
			"name" => esc_html__('Background color','discy')
		),
		array(
			"type"  => "slider",
			"id"    => "padding_top",
			"name"  => esc_html__('Padding top','discy'),
			"std"   => "0",
			"step"  => "1",
			"min"   => "0",
			"max"   => "100",
			"value" => "0"
		),
		array(
			"type"  => "slider",
			"id"    => "padding_bottom",
			"name"  => esc_html__('Padding bottom','discy'),
			"std"   => "0",
			"step"  => "1",
			"min"   => "0",
			"max"   => "100",
			"value" => "0"
		),
		array(
			"type"    => "images",
			"id"      => "layout",
			"name"    => esc_html__('Layout','discy'),
			'std'     => "footer_5c",
			'options' => array(
				'footer_1c' => $imagepath.'footer_1c.jpg',
				'footer_2c' => $imagepath.'footer_2c.jpg',
				'footer_3c' => $imagepath.'footer_3c.jpg',
				'footer_4c' => $imagepath.'footer_4c.jpg',
				'footer_5c' => $imagepath.'footer_5c.jpg')
		),
		array(
			"type"      => "select",
			"id"        => "first_column",
			"name"      => esc_html__('Select first column','discy'),
			'condition' => '[%id%]layout:is(footer_1c),[%id%]layout:is(footer_2c),[%id%]layout:is(footer_3c),[%id%]layout:is(footer_4c),[%id%]layout:is(footer_5c)',
			'operator'  => 'or',
			'options'   => $new_sidebars
		),
		array(
			"type"      => "select",
			"id"        => "second_column",
			"name"      => esc_html__('Select second column','discy'),
			'condition' => '[%id%]layout:is(footer_2c),[%id%]layout:is(footer_3c),[%id%]layout:is(footer_4c),[%id%]layout:is(footer_5c)',
			'operator'  => 'or',
			'options'   => $new_sidebars
		),
		array(
			"type"      => "select",
			"id"        => "third_column",
			"name"      => esc_html__('Select third column','discy'),
			'condition' => '[%id%]layout:is(footer_3c),[%id%]layout:is(footer_4c),[%id%]layout:is(footer_5c)',
			'operator'  => 'or',
			'options'   => $new_sidebars
		),
		array(
			"type"      => "select",
			"id"        => "fourth_column",
			"name"      => esc_html__('Select fourth column','discy'),
			'condition' => '[%id%]layout:is(footer_4c),[%id%]layout:is(footer_5c)',
			'operator'  => 'or',
			'options'   => $new_sidebars
		),
		array(
			"type"      => "select",
			"id"        => "fifth_column",
			"name"      => esc_html__('Select fifth column','discy'),
			'condition' => '[%id%]layout:is(footer_5c)',
			'operator'  => 'or',
			'options'   => $new_sidebars
		),
	);
	
	$options[] = array(
		'id'      => "add_footer",
		'type'    => "elements",
		'button'  => esc_html__('Add a new footer level','discy'),
		'hide'    => "yes",
		'options' => $footer_elements,
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end',
		'div'  => 'div'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name' => esc_html__('Bottom footer setting','discy'),
		'id'   => 'footer_bottom',
		'type' => 'heading-2'
	);
	
	$options[] = array(
		'name'      => esc_html__('The bottom footer work when you choose the footer style as normal footer.','discy'),
		'condition' => 'footer_style:not(footer)',
		'type'      => 'info'
	);
	
	$options[] = array(
		'type'      => 'heading-2',
		'condition' => 'footer_style:not(sidebar)',
		'div'       => 'div'
	);
	
	$options[] = array(
		'name' => esc_html__('Bottom footer enable or disable','discy'),
		'id'   => 'bottom_footer',
		'std'  => 'on',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'bottom_footer:not(0)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'name' => esc_html__('Bottom footer padding top','discy'),
		"id"   => "footer_padding_top",
		"type" => "sliderui",
		"std"  => "0",
		"step" => "1",
		"min"  => "0",
		"max"  => "100"
	);
	
	$options[] = array(
		'name' => esc_html__('Bottom footer padding bottom','discy'),
		"id"   => "footer_padding_bottom",
		"type" => "sliderui",
		"std"  => "0",
		"step" => "1",
		"min"  => "0",
		"max"  => "100"
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end',
		'div'  => 'div'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name' => esc_html__('Sort the footer elements','discy'),
		'id'   => 'footer_sort',
		'type' => 'heading-2'
	);
	
	$options[] = array(
		'name'      => esc_html__('The sort footer elements work when you choose the footer style as normal footer.','discy'),
		'condition' => 'footer_style:not(footer)',
		'type'      => 'info'
	);
	
	$options[] = array(
		'id'        => "sort_footer_elements",
		'condition' => 'footer_style:not(sidebar)',
		'std'       => array(
						array("value" => "top_footer",'name' => esc_html__('Top footer','discy'),"default" => "yes"),
						array("value" => "bottom_footer",'name' => esc_html__('Bottom footer','discy'),"default" => "yes")
					),
		'type'      => "sort",
		'options'   => array(
						array("value" => "top_footer",'name' => esc_html__('Top footer','discy'),"default" => "yes"),
						array("value" => "bottom_footer",'name' => esc_html__('Bottom footer','discy'),"default" => "yes")
					)
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name' => esc_html__('Advanced settings','discy'),
		'id'   => "advanced_setting",
		'icon' => 'upload',
		'type' => 'heading',
	);
	
	$options[] = array(
		'type' => 'heading-2'
	);
	
	$options[] = array(
		'id'   => 'uniqid_cookie',
		'std'  => (has_wpqa()?wpqa_token(15):rand(1,100000)),
		'type' => 'hidden'
	);
	
	$options[] = array(
		'name' => esc_html__('Activate the advanced queries at the site','discy'),
		'desc' => esc_html__('Select ON if you want to active the advanced queries at the site.','discy'),
		'id'   => 'advanced_queries',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Activate this option ON only if you have used Ask Me theme before.','discy'),
		'id'   => 'ask_me',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Do you need to activate the views at your site?','discy'),
		'id'   => 'active_post_stats',
		'std'  => 'on',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => 'active_post_stats:not(0)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'name' => esc_html__('Post meta stats field.','discy'),
		'desc' => esc_html__('Change this if you have used a post views plugin before.','discy'),
		'id'   => 'post_meta_stats',
		'std'  => 'post_stats',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('Do you need to activate cache for views at your site?','discy'),
		'id'   => 'cache_post_stats',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => esc_html__('Activate the visits at the site work by cookie','discy'),
		'desc' => esc_html__('Select ON if you want to active the cookie for the visits at the site.','discy'),
		'id'   => 'visit_cookie',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name' => esc_html__('User meta avatar field.','discy'),
		'desc' => esc_html__('Change this if you have used a user avatar or social plugin before.','discy'),
		'id'   => 'user_meta_avatar',
		'std'  => 'your_avatar',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('User meta cover field.','discy'),
		'desc' => esc_html__('Change this if you have used a user cover or social plugin before.','discy'),
		'id'   => 'user_meta_cover',
		'std'  => 'your_cover',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('If you want to export setting please refresh the page before that','discy'),
		'type' => 'info'
	);

	$options[] = array(
		'name' => '<a href="'.add_query_arg(array('page' => 'options','backup' => 'settings'),admin_url('admin.php')).'" class="button button-primary backup-settings">'.esc_html__('Backup your settings','discy').'</a>',
		'type' => 'info'
	);

	$options[] = array(
		'name'   => esc_html__('Export Setting','discy'),
		'desc'   => esc_html__('Copy this to saved file','discy'),
		'id'     => 'export_setting',
		'export' => $current_options_e,
		'type'   => 'export'
	);

	$options[] = array(
		'name' => esc_html__('Import Setting','discy'),
		'desc' => esc_html__('Put here the import setting','discy'),
		'id'   => 'import_setting',
		'type' => 'import'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);
	
	return $options;
}?>