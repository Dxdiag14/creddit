<?php /* Widget options */
function discy_admin_widgets() {
	$active_points = discy_options("active_points");
	
	// If using image radio buttons, define a directory path
	$imagepath_theme =  get_template_directory_uri(). '/images/';
	
	$options = array();

	$options = apply_filters("discy_widget_options",$options);
	
	$options['adv250x250-widget'] = array(
		array(
			'name' => esc_html__('Title','discy'),
			'id'   => 'title',
			'type' => 'text',
			'std'  => 'Adv 250x250'
		),
		array(
			'name'    => esc_html__('Advertising type','discy'),
			'id'      => 'adv_type',
			'std'     => 'custom_image',
			'type'    => 'radio',
			'options' => array("display_code" => esc_html__("Display code","discy"),"custom_image" => esc_html__("Custom Image","discy"))
		),
		array(
			'name'      => esc_html__('Image URL','discy'),
			'id'        => 'adv_img',
			'type'      => 'upload',
			'condition' => 'adv_type:is(custom_image)'
		),
		array(
			'name'      => esc_html__('Advertising url','discy'),
			'id'        => 'adv_href',
			'type'      => 'text',
			'condition' => 'adv_type:is(custom_image)'
		),
		array(
			'name'      => esc_html__('Advertising Code html ( Ex: Google ads)','discy'),
			'id'        => 'adv_code',
			'type'      => 'textarea',
			'condition' => 'adv_type:is(display_code)'
		),
	);
	
	$options['adv120x600-widget'] = array(
		array(
			'name' => esc_html__('Title','discy'),
			'id'   => 'title',
			'type' => 'text',
			'std'  => 'Adv 120x600'
		),
		array(
			'name'    => esc_html__('Advertising type','discy'),
			'id'      => 'adv_type',
			'std'     => 'custom_image',
			'type'    => 'radio',
			'options' => array("display_code" => esc_html__("Display code","discy"),"custom_image" => esc_html__("Custom Image","discy"))
		),
		array(
			'name'      => esc_html__('Image URL','discy'),
			'id'        => 'adv_img',
			'type'      => 'upload',
			'condition' => 'adv_type:is(custom_image)'
		),
		array(
			'name'      => esc_html__('Advertising url','discy'),
			'id'        => 'adv_href',
			'type'      => 'text',
			'condition' => 'adv_type:is(custom_image)'
		),
		array(
			'name'      => esc_html__('Advertising Code html ( Ex: Google ads)','discy'),
			'id'        => 'adv_code',
			'type'      => 'textarea',
			'condition' => 'adv_type:is(display_code)'
		),
	);
	
	$options['adv234x60-widget'] = array(
		array(
			'name' => esc_html__('Title','discy'),
			'id'   => 'title',
			'type' => 'text',
			'std'  => 'Adv 234x60'
		),
		array(
			'name'    => esc_html__('Advertising type','discy'),
			'id'      => 'adv_type',
			'std'     => 'custom_image',
			'type'    => 'radio',
			'options' => array("display_code" => esc_html__("Display code","discy"),"custom_image" => esc_html__("Custom Image","discy"))
		),
		array(
			'name'      => esc_html__('Image URL','discy'),
			'id'        => 'adv_img',
			'type'      => 'upload',
			'condition' => 'adv_type:is(custom_image)'
		),
		array(
			'name'      => esc_html__('Advertising url','discy'),
			'id'        => 'adv_href',
			'type'      => 'text',
			'condition' => 'adv_type:is(custom_image)'
		),
		array(
			'name'      => esc_html__('Advertising Code html ( Ex: Google ads)','discy'),
			'id'        => 'adv_code',
			'type'      => 'textarea',
			'condition' => 'adv_type:is(display_code)'
		),
	);
	
	$options['adv120x240-widget'] = array(
		array(
			'name' => esc_html__('Title','discy'),
			'id'   => 'title',
			'type' => 'text',
			'std'  => 'Adv 120x240'
		),
		
		array(
			'name'    => esc_html__('Advertising type 1','discy'),
			'id'      => 'adv_type_1',
			'std'     => 'custom_image',
			'type'    => 'radio',
			'options' => array("display_code" => esc_html__("Display code","discy"),"custom_image" => esc_html__("Custom Image","discy"))
		),
		array(
			'name'      => esc_html__('Image URL','discy'),
			'id'        => 'adv_img_1',
			'type'      => 'upload',
			'condition' => 'adv_type_1:is(custom_image)'
		),
		array(
			'name'      => esc_html__('Advertising url','discy'),
			'id'        => 'adv_href_1',
			'type'      => 'text',
			'condition' => 'adv_type_1:is(custom_image)'
		),
		array(
			'name'      => esc_html__('Advertising Code html ( Ex: Google ads)','discy'),
			'id'        => 'adv_code_1',
			'type'      => 'textarea',
			'condition' => 'adv_type_1:is(display_code)'
		),
		
		array(
			'name'    => esc_html__('Advertising type 2','discy'),
			'id'      => 'adv_type_2',
			'std'     => 'custom_image',
			'type'    => 'radio',
			'options' => array("display_code" => esc_html__("Display code","discy"),"custom_image" => esc_html__("Custom Image","discy"))
		),
		array(
			'name'      => esc_html__('Image URL','discy'),
			'id'        => 'adv_img_2',
			'type'      => 'upload',
			'condition' => 'adv_type_2:is(custom_image)'
		),
		array(
			'name'      => esc_html__('Advertising url','discy'),
			'id'        => 'adv_href_2',
			'type'      => 'text',
			'condition' => 'adv_type_2:is(custom_image)'
		),
		array(
			'name'      => esc_html__('Advertising Code html ( Ex: Google ads)','discy'),
			'id'        => 'adv_code_2',
			'type'      => 'textarea',
			'condition' => 'adv_type_2:is(display_code)'
		),
		
		array(
			'name'    => esc_html__('Advertising type 3','discy'),
			'id'      => 'adv_type_3',
			'std'     => 'custom_image',
			'type'    => 'radio',
			'options' => array("display_code" => esc_html__("Display code","discy"),"custom_image" => esc_html__("Custom Image","discy"))
		),
		array(
			'name'      => esc_html__('Image URL','discy'),
			'id'        => 'adv_img_3',
			'type'      => 'upload',
			'condition' => 'adv_type_3:is(custom_image)'
		),
		array(
			'name'      => esc_html__('Advertising url','discy'),
			'id'        => 'adv_href_3',
			'type'      => 'text',
			'condition' => 'adv_type_3:is(custom_image)'
		),
		array(
			'name'      => esc_html__('Advertising Code html ( Ex: Google ads)','discy'),
			'id'        => 'adv_code_3',
			'type'      => 'textarea',
			'condition' => 'adv_type_3:is(display_code)'
		),
		
		array(
			'name'    => esc_html__('Advertising type 4','discy'),
			'id'      => 'adv_type_4',
			'std'     => 'custom_image',
			'type'    => 'radio',
			'options' => array("display_code" => esc_html__("Display code","discy"),"custom_image" => esc_html__("Custom Image","discy"))
		),
		array(
			'name'      => esc_html__('Image URL','discy'),
			'id'        => 'adv_img_4',
			'type'      => 'upload',
			'condition' => 'adv_type_4:is(custom_image)'
		),
		array(
			'name'      => esc_html__('Advertising url','discy'),
			'id'        => 'adv_href_4',
			'type'      => 'text',
			'condition' => 'adv_type_4:is(custom_image)'
		),
		array(
			'name'      => esc_html__('Advertising Code html ( Ex: Google ads)','discy'),
			'id'        => 'adv_code_4',
			'type'      => 'textarea',
			'condition' => 'adv_type_4:is(display_code)'
		),
	);
	
	$options['adv125x125-widget'] = array(
		array(
			'name' => esc_html__('Title','discy'),
			'id'   => 'title',
			'type' => 'text',
			'std'  => 'Adv 125x125'
		),
		
		array(
			'name'    => esc_html__('Advertising type 1','discy'),
			'id'      => 'adv_type_1',
			'std'     => 'custom_image',
			'type'    => 'radio',
			'options' => array("display_code" => esc_html__("Display code","discy"),"custom_image" => esc_html__("Custom Image","discy"))
		),
		array(
			'name'      => esc_html__('Image URL','discy'),
			'id'        => 'adv_img_1',
			'type'      => 'upload',
			'condition' => 'adv_type_1:is(custom_image)'
		),
		array(
			'name'      => esc_html__('Advertising url','discy'),
			'id'        => 'adv_href_1',
			'type'      => 'text',
			'condition' => 'adv_type_1:is(custom_image)'
		),
		array(
			'name'      => esc_html__('Advertising Code html ( Ex: Google ads)','discy'),
			'id'        => 'adv_code_1',
			'type'      => 'textarea',
			'condition' => 'adv_type_1:is(display_code)'
		),
		
		array(
			'name'    => esc_html__('Advertising type 2','discy'),
			'id'      => 'adv_type_2',
			'std'     => 'custom_image',
			'type'    => 'radio',
			'options' => array("display_code" => esc_html__("Display code","discy"),"custom_image" => esc_html__("Custom Image","discy"))
		),
		array(
			'name'      => esc_html__('Image URL','discy'),
			'id'        => 'adv_img_2',
			'type'      => 'upload',
			'condition' => 'adv_type_2:is(custom_image)'
		),
		array(
			'name'      => esc_html__('Advertising url','discy'),
			'id'        => 'adv_href_2',
			'type'      => 'text',
			'condition' => 'adv_type_2:is(custom_image)'
		),
		array(
			'name'      => esc_html__('Advertising Code html ( Ex: Google ads)','discy'),
			'id'        => 'adv_code_2',
			'type'      => 'textarea',
			'condition' => 'adv_type_2:is(display_code)'
		),
		
		array(
			'name'    => esc_html__('Advertising type 3','discy'),
			'id'      => 'adv_type_3',
			'std'     => 'custom_image',
			'type'    => 'radio',
			'options' => array("display_code" => esc_html__("Display code","discy"),"custom_image" => esc_html__("Custom Image","discy"))
		),
		array(
			'name'      => esc_html__('Image URL','discy'),
			'id'        => 'adv_img_3',
			'type'      => 'upload',
			'condition' => 'adv_type_3:is(custom_image)'
		),
		array(
			'name'      => esc_html__('Advertising url','discy'),
			'id'        => 'adv_href_3',
			'type'      => 'text',
			'condition' => 'adv_type_3:is(custom_image)'
		),
		array(
			'name'      => esc_html__('Advertising Code html ( Ex: Google ads)','discy'),
			'id'        => 'adv_code_3',
			'type'      => 'textarea',
			'condition' => 'adv_type_3:is(display_code)'
		),
		
		array(
			'name'    => esc_html__('Advertising type 4','discy'),
			'id'      => 'adv_type_4',
			'std'     => 'custom_image',
			'type'    => 'radio',
			'options' => array("display_code" => esc_html__("Display code","discy"),"custom_image" => esc_html__("Custom Image","discy"))
		),
		array(
			'name'      => esc_html__('Image URL','discy'),
			'id'        => 'adv_img_4',
			'type'      => 'upload',
			'condition' => 'adv_type_4:is(custom_image)'
		),
		array(
			'name'      => esc_html__('Advertising url','discy'),
			'id'        => 'adv_href_4',
			'type'      => 'text',
			'condition' => 'adv_type_4:is(custom_image)'
		),
		array(
			'name'      => esc_html__('Advertising Code html ( Ex: Google ads)','discy'),
			'id'        => 'adv_code_4',
			'type'      => 'textarea',
			'condition' => 'adv_type_4:is(display_code)'
		),
	);
	
	$options['social-widget'] = array(
		array(
			'name' => esc_html__('Title','discy'),
			'id'   => 'title',
			'type' => 'text',
			'std'  => 'Follow'
		),
	);
	
	$options['subscribe-widget'] = array(
		array(
			'name' => esc_html__('Title','discy'),
			'id'   => 'title',
			'type' => 'text',
			'std'  => 'Subscribe'
		),
		array(
			'name' => esc_html__('Feedburner ID','discy'),
			'id'   => 'feedburner',
			'type' => 'text'
		),
	);
	
	$options['ask-widget'] = array(
		array(
			'name' => esc_html__('Title','discy'),
			'id'   => 'title',
			'type' => 'text',
			'std'  => 'Ask question'
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
			'std'       => 'question',
		),
		array(
			'div'       => 'div',
			'condition' => 'button:is(custom)',
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
	
	$options['activities-widget'] = array(
		array(
			'name' => esc_html__('Title','discy'),
			'id'   => 'title',
			'type' => 'text',
			'std'  => 'Activities log'
		),
		array(
			'name' => esc_html__('Item number','discy'),
			'id'   => 'item_number',
			'type' => 'text',
			'std'  => '5'
		),
		array(
			'name' => esc_html__('Display the more button?','discy'),
			'id'   => 'more_button',
			'type' => 'checkbox',
			'std'  => 'on'
		),
	);
	
	$options['notifications-widget'] = array(
		array(
			'name' => esc_html__('Title','discy'),
			'id'   => 'title',
			'type' => 'text',
			'std'  => 'Notifications'
		),
		array(
			'name' => esc_html__('Item number','discy'),
			'id'   => 'item_number',
			'type' => 'text',
			'std'  => '5'
		),
		array(
			'name' => esc_html__('Display the more button?','discy'),
			'id'   => 'more_button',
			'type' => 'checkbox',
			'std'  => 'on'
		),
	);
	
	$options['signup-widget'] = array(
		array(
			'name' => esc_html__('Title','discy'),
			'id'   => 'title',
			'type' => 'text',
			'std'  => 'Signup'
		),
	);
	
	$options['login-widget'] = array(
		array(
			'name' => esc_html__('Title','discy'),
			'id'   => 'title',
			'type' => 'text',
			'std'  => 'Login'
		),
	);
	
	$options['rules-widget'] = array(
		array(
			'name' => esc_html__('Title','discy'),
			'id'   => 'title',
			'type' => 'text',
			'std'  => 'Group Rules'
		),
	);
	
	$options['questions_categories-widget'] = array(
		array(
			'name' => esc_html__('Title','discy'),
			'id'   => 'title',
			'type' => 'text',
			'std'  => 'Questions Categories'
		),
		array(
			'name'    => esc_html__('Style','discy'),
			'id'      => 'category_type',
			'options' => array(
				"with_icon"     => esc_html__("With icons","discy"),
				"icon_color"    => esc_html__("With icons and colors","discy"),
				'with_icon_1'   => esc_html__('With icons 2','discy'),
				'with_icon_2'   => esc_html__('With colored icons','discy'),
				'with_icon_3'   => esc_html__('With colored icons and box','discy'),
				'with_icon_4'   => esc_html__('With colored icons and box 2','discy'),
				"simple_follow" => esc_html__("Simple with follow","discy"),
				'with_cover_1'  => esc_html__('With cover','discy'),
				'with_cover_2'  => esc_html__('With cover and icon','discy'),
				'with_cover_3'  => esc_html__('With cover and small icon','discy'),
				"simple"        => esc_html__("Simple","discy"),
				"links"         => esc_html__("Links","discy")
			),
			'std'     => 'with_icon',
			'type'    => 'radio'
		),
		array(
			'name' => esc_html__('Number of categories, put 0 for all categories','discy'),
			'id'   => 'cat_number',
			'type' => 'text',
			'std'  => 0
		),
		array(
			'name'      => esc_html__('Order by','discy'),
			'id'        => 'cat_sort',
			'std'       => "count",
			'type'      => 'select',
			'condition' => 'category_type:not(links)',
			'options'   => array(
				'count'     => esc_html__('Questions','discy'),
				//'answers'   => esc_html__('Answers','discy'),
				'followers' => esc_html__('Followers','discy'),
			),
		),
		array(
			'div'       => 'div',
			'condition' => 'category_type:is(links)',
			'type'      => 'heading-2'
		),
		array(
			'name' => esc_html__('Show questions counts?','discy'),
			'id'   => 'questions_counts',
			'type' => 'checkbox',
			'std'  => 'on'
		),
		array(
			'name' => esc_html__('Show the child categories accordion','discy'),
			'id'   => 'show_child',
			'type' => 'checkbox'
		),
		array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		),
	);
	
	$options['widget_counter'] = array(
		array(
			'name' => esc_html__('Title','discy'),
			'id'   => 'title',
			'type' => 'text',
			'std'  => 'Social Statistics'
		),
		array(
			'name' => esc_html__('Facebook Page ID/Name','discy'),
			'id'   => 'facebook',
			'type' => 'text',
			'std'  => '2code.info'
		),
		array(
			'name' => esc_html__('Twitter','discy'),
			'id'   => 'twitter',
			'type' => 'text',
			'std'  => '2codeThemes'
		),
		array(
			'name' => esc_html__('Channel id','discy'),
			'id'   => 'youtube',
			'type' => 'text',
			'std'  => 'UCht9cayN2rRaXk5VgMJtAsA'
		),
		array(
			'name' => esc_html__('Vimeo Page ID/Name','discy'),
			'id'   => 'vimeo',
			'type' => 'text',
			'std'  => 'vimeo'
		),
		array(
			'name' => esc_html__('Dribbble Page ID/Name','discy'),
			'id'   => 'dribbble',
			'type' => 'text',
			'std'  => 'begha'
		),
		array(
			'name' => esc_html__('Pinterest','discy'),
			'id'   => 'pinterest',
			'type' => 'text',
			'std'  => 'https://www.pinterest.com/envato/'
		),
		array(
			'name' => esc_html__('Instagram','discy'),
			'id'   => 'instagram',
			'type' => 'text',
			'std'  => 'kaboompics'
		),
		array(
			'name' => esc_html__('Envato username','discy'),
			'id'   => 'envato',
			'type' => 'text',
			'std'  => '2codeThemes'
		),
		array(
			'name' => esc_html__('Behance','discy'),
			'id'   => 'behance',
			'type' => 'text',
			'std'  => 'begha'
		),
		array(
			'name' => esc_html__('Soundcloud','discy'),
			'id'   => 'soundcloud',
			'type' => 'text',
			'std'  => 'envato'
		),
		array(
			'name' => esc_html__('Github','discy'),
			'id'   => 'github',
			'type' => 'text',
			'std'  => 'kailoon'
		),
		array(
			'name' => esc_html__('Your socials numbers is saved in the cache each hour if you want delete the cache now click on Save.','discy'),
			'type' => 'info',
		),
	);
	
	$options['facebook-widget'] = array(
		array(
			'name' => esc_html__('Title','discy'),
			'id'   => 'title',
			'type' => 'text',
			'std'  => 'Facebook'
		),
		array(
			'name' => esc_html__('Facebook link','discy'),
			'id'   => 'facebook_link',
			'type' => 'text',
			'std'  => 'https://www.facebook.com/2code.info'
		),
		array(
			'name' => esc_html__('Width','discy'),
			'id'   => 'width',
			'type' => 'text',
			'std'  => '229'
		),
		array(
			'name' => esc_html__('Height','discy'),
			'id'   => 'height',
			'type' => 'text',
			'std'  => '214'
		),
		array(
			'name' => esc_html__('Background','discy'),
			'id'   => 'background',
			'type' => 'color',
			'std'  => '#FFFFFF'
		),
		array(
			'name' => esc_html__('Border color','discy'),
			'id'   => 'border_color',
			'type' => 'color',
			'std'  => '#dedede'
		),
	);
	
	$options['about-widget'] = array(
		array(
			'name' => esc_html__('Title','discy'),
			'id'   => 'title',
			'type' => 'text',
			'std'  => 'Discy'
		),
		array(
			'name' => esc_html__('Image URL logo','discy'),
			'id'   => 'logo',
			'type' => 'upload',
			'std'  => $imagepath_theme.'logo-footer.png'
		),
		array(
			'name' => esc_html__("Margin top for the logo","discy"),
			"id"   => "margin_logo",
			"type" => "sliderui",
			'std'  => '0',
			"step" => "1",
			"min"  => "0",
			"max"  => "70"
		),
		array(
			'name' => esc_html__('About text','discy'),
			'id'   => 'text',
			'type' => 'textarea',
			'std'  => 'Discy is a social questions & Answers Engine which will help you establis your community and connect with other people.'
		),
		array(
			'name' => esc_html__("Margin top for the text","discy"),
			"id"   => "padding_text",
			"type" => "sliderui",
			'std'  => '0',
			"step" => "1",
			"min"  => "0",
			"max"  => "70"
		),
	);
	
	$options['video-widget'] = array(
		array(
			'name' => esc_html__('Title','discy'),
			'id'   => 'title',
			'type' => 'text',
			'std'  => 'Video'
		),
		array(
			'name' => esc_html__('Height','discy'),
			'id'   => 'height',
			'type' => 'text',
			'std'  => '200'
		),
		array(
			'name'    => esc_html__('Video Type','discy'),
			'id'      => 'video_type',
			'options' => array("youtube" => esc_html__("Youtube","discy"),"vimeo" => esc_html__("Vimeo","discy"),"daily" => esc_html__("Dailymotion","discy"),"facebook" => esc_html__("Facebook video","discy"),"embed" => esc_html__("Embed Code","discy")),
			'std'     => 'draft',
			'type'    => 'select'
		),
		array(
			'name'      => esc_html__('Video id','discy'),
			'desc'      => esc_html__('Put the Video ID here: https://www.youtube.com/watch?v=JuyB7NO0EYY Ex: "JuyB7NO0EYY"','discy'),
			'id'        => 'video_id',
			'type'      => 'text',
			'condition' => 'video_type:not(embed)',
		),
		array(
			'name'      => esc_html__('Embed Code','discy'),
			'id'        => 'embed_code',
			'type'      => 'textarea',
			'condition' => 'video_type:is(embed)',
		),
	);
	
	$options['comments-post-widget'] = array(
		array(
			'name' => esc_html__('Title','discy'),
			'id'   => 'title',
			'type' => 'text',
			'std'  => 'Comments'
		),
		array(
			'name'    => esc_html__('Post or question','discy'),
			'id'      => 'post_or_question',
			'options' => array("post" => esc_html__("Posts","discy"),"question" => esc_html__("Questions","discy")),
			'std'     => 'post',
			'type'    => 'radio'
		),
		array(
			'name'    => esc_html__('Specific date.','discy'),
			'desc'    => esc_html__('Select the specific date.','discy'),
			'id'      => "specific_date",
			'std'     => "all",
			'type'    => "radio",
			'options' => array(
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
		),
		array(
			'name' => esc_html__('Display images?','discy'),
			'id'   => 'show_images',
			'type' => 'checkbox',
			'std'  => 'on'
		),
		array(
			'name' => esc_html__('Number of comments to show','discy'),
			'id'   => 'comments_number',
			'type' => 'text',
			'std'  => '5'
		),
		array(
			'name' => esc_html__('The number of words excerpt','discy'),
			'id'   => 'comment_excerpt',
			'type' => 'text',
			'std'  => '10'
		),
		array(
			'name' => esc_html__('Display date?','discy'),
			'id'   => 'display_date',
			'type' => 'checkbox',
			'std'  => 'on'
		),
	);
	
	$user_sort = array(
		"user_registered" => "Register",
		"display_name"    => "Name",
		"ID"              => "ID",
		"question_count"  => "Questions",
		"answers"         => "Answers",
		"the_best_answer" => "Best Answers",
		"points"          => "Points",
		"post_count"      => "Posts",
		"comments"        => "Comments"
	);
	
	if ($active_points != "on") {
		unset($user_sort["points"]);
	}
	
	$options['users-widget'] = array(
		array(
			'name' => esc_html__('Title','discy'),
			'id'   => 'title',
			'type' => 'text',
			'std'  => 'Users'
		),
		array(
			'name' => esc_html__('Add crown for the users','discy'),
			'id'   => 'crown_king',
			'type' => 'checkbox',
		),
		array(
			'name' => esc_html__('Number of users to show','discy'),
			'id'   => 'user_number',
			'type' => 'text',
			'std'  => '3'
		),
		array(
			'name'    => esc_html__('Choose the user roles show','discy'),
			'id'      => 'user_group',
			'options' => discy_options_roles(),
			'std'     => array("administrator","editor","author","contributor","subscriber"),
			'type'    => 'multicheck'
		),
		array(
			'name' => esc_html__('This option will work only if you activated, Question settings/Questions category settings/Activate the points by category.','discy'),
			'type' => 'info',
		),
		array(
			'name' => esc_html__('Display widget at categories only with sort by points?','discy'),
			'id'   => 'points_categories',
			'type' => 'checkbox',
		),
		array(
			'name'      => esc_html__('Order by','discy'),
			'id'        => 'user_sort',
			'options'   => $user_sort,
			'std'       => 'user_registered',
			'condition' => 'points_categories:is(0)',
			'type'      => 'select'
		),
		array(
			'div'       => 'div',
			'condition' => 'user_sort:is(points)',
			'type'      => 'heading-2'
		),
		array(
			'name' => esc_html__('This option will work only if you activated, Question settings/General settings/Activate the points sort with specific days.','discy'),
			'type' => 'info',
		),
		array(
			'name' => esc_html__('Do you need to sort your users by points for specific day, week, month or year?','discy'),
			'id'   => 'specific_points',
			'type' => 'checkbox',
		),
		array(
			'name'      => esc_html__('Specific time','discy'),
			'id'        => 'specific_time',
			'options'   => array("day" => esc_html__("Day","discy"),"week" => esc_html__("Week","discy"),"month" => esc_html__("Month","discy"),"year" => esc_html__("Year","discy")),
			'std'       => 'day',
			'condition' => 'specific_points:not(0)',
			'type'      => 'radio'
		),
		array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		),
		array(
			'name'    => esc_html__('Order','discy'),
			'id'      => 'user_order',
			'options' => array("DESC" => esc_html__("Descending","discy"),"ASC" => esc_html__("Ascending","discy")),
			'std'     => 'DESC',
			'type'    => 'select'
		),
		array(
			'name'      => esc_html__('Do you need to activate icon for the main storable?, it works for Questions, Answers, Best Answers, Points, Posts, or Comments.','discy'),
			'id'        => 'show_icon',
			'type'      => 'checkbox',
			'condition' => 'points_categories:is(0)',
		),
	);
	
	$options['related-widget'] = array(
		array(
			'name' => esc_html__('This widget will show at single questions only to show the related questions.','discy'),
			'type' => 'info',
		),
		array(
			'name' => esc_html__('Title','discy'),
			'id'   => 'title',
			'type' => 'text',
			'std'  => 'Related Questions'
		),
		array(
			'name' => esc_html__('Number of items to show','discy'),
			'id'   => 'related_number',
			'type' => 'text',
			'std'  => '5'
		),
		array(
			'name'    => esc_html__('Question style','discy'),
			'id'      => 'question_style',
			'options' => array("style_1" => esc_html__("Style 1","discy"),"style_2" => esc_html__("Style 2","discy")),
			'std'     => 'style_1',
			'type'    => 'radio'
		),
		array(
			'name' => esc_html__('Display author image?','discy'),
			'id'   => 'show_images',
			'type' => 'checkbox',
			'std'  => 'on'
		),
		array(
			'name' => esc_html__('The excerpt title','discy'),
			'id'   => 'excerpt_title',
			'type' => 'text',
			'std'  => '10'
		),
		array(
			'div'       => 'div',
			'condition' => 'question_style:is(style_2)',
			'type'      => 'heading-2'
		),
		array(
			'name' => esc_html__('Display image?','discy'),
			'id'   => 'display_image',
			'type' => 'checkbox',
			'std'  => 'on'
		),
		array(
			'name' => esc_html__('Display video if there?','discy'),
			'id'   => 'display_video',
			'type' => 'checkbox',
			'std'  => 'on'
		),
		array(
			'name' => esc_html__('Display date?','discy'),
			'id'   => 'display_date',
			'type' => 'checkbox',
			'std'  => 'on'
		),
		array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		),
		array(
			'name' => esc_html__('Display answers?','discy'),
			'id'   => 'display_answers',
			'type' => 'checkbox',
			'std'  => 'on'
		),
		array(
			'name'    => esc_html__('Query type','discy'),
			'id'      => 'query_related',
			'options' => array(
				'categories' => esc_html__('Questions in the same categories','discy'),
				'tags'       => esc_html__('Questions in the same tags (If not found, questions with the same categories will be shown)','discy'),
				'author'     => esc_html__('Questions by the same author','discy'),
			),
			'std'     => 'categories',
			'type'    => 'radio'
		),
	);
	
	$options_pages = array();
	$options_pages_obj = get_pages('sort_column=post_parent,menu_order');
	$options_pages[''] = 'Select a page:';
	foreach ($options_pages_obj as $page) {
		$options_pages[$page->ID] = $page->post_title;
	}

	$options['widget_profile_strength'] = array(
		array(
			'name' => esc_html__('Title','discy'),
			'id'   => 'title',
			'type' => 'text',
			'std'  => 'Profile Strength'
		),
		array(
			'name' => esc_html__('The setting for this widget from Discy Setting/User setting/Edit Profile.','discy'),
			'type' => 'info',
		),
	);
	
	$options['widget_posts'] = array(
		array(
			'name' => esc_html__('Title','discy'),
			'id'   => 'title',
			'type' => 'text',
			'std'  => 'Recent posts'
		),
		array(
			'name'    => esc_html__('Post or question','discy'),
			'id'      => 'post_or_question',
			'options' => array("post" => esc_html__("Posts","discy"),"question" => esc_html__("Questions","discy")),
			'std'     => 'post',
			'type'    => 'radio'
		),
		array(
			'name'      => esc_html__('Post style','discy'),
			'id'        => 'post_style',
			'condition' => 'post_or_question:is(post)',
			'options'   => array("style_1" => esc_html__("Style 1","discy"),"style_2" => esc_html__("Style 2","discy")),
			'std'       => 'style_1',
			'type'      => 'radio'
		),
		array(
			'name'      => esc_html__('Question style','discy'),
			'id'        => 'question_style',
			'condition' => 'post_or_question:is(question)',
			'options'   => array("style_1" => esc_html__("Style 1","discy"),"style_2" => esc_html__("Style 2","discy")),
			'std'       => 'style_1',
			'type'      => 'radio'
		),
		array(
			'name'      => esc_html__('Display author image?','discy'),
			'id'        => 'show_images',
			'type'      => 'checkbox',
			'condition' => 'post_or_question:is(question),question_style:is(style_1)',
			'std'       => 'on'
		),
		array(
			'name'      => esc_html__('Display author image?','discy'),
			'id'        => 'show_images_post',
			'type'      => 'checkbox',
			'condition' => 'post_or_question:is(post),post_style:is(style_1)',
			'std'       => 'on'
		),
		array(
			'name' => esc_html__('The excerpt title','discy'),
			'id'   => 'excerpt_title',
			'type' => 'text',
			'std'  => '10'
		),
		array(
			'name'    => esc_html__('Order by','discy'),
			'id'      => 'orderby',
			'options' => array("recent" => esc_html__("Recent","discy"),"random" => esc_html__("Random","discy"),"popular" => esc_html__("Most Answered","discy"),"most_visited" => esc_html__("Most visited","discy"),"most_voted" => esc_html__("Most voted - Questions only","discy"),"no_response" => esc_html__("No response","discy")),
			'std'     => 'recent',
			'type'    => 'select'
		),
		array(
			'name' => esc_html__('Number of items to show','discy'),
			'id'   => 'posts_per_page',
			'type' => 'text',
			'std'  => '5'
		),
		
		array(
			'div'       => 'div',
			'condition' => 'post_or_question:is(post)',
			'type'      => 'heading-2'
		),
		array(
			'div'       => 'div',
			'condition' => 'post_style:is(style_2)',
			'type'      => 'heading-2'
		),
		array(
			'name' => esc_html__('Display image?','discy'),
			'id'   => 'display_image',
			'type' => 'checkbox',
			'std'  => 'on'
		),
		array(
			'name' => esc_html__('Display video if there?','discy'),
			'id'   => 'display_video',
			'type' => 'checkbox',
			'std'  => 'on'
		),
		array(
			'name' => esc_html__('Display date?','discy'),
			'id'   => 'display_date',
			'type' => 'checkbox',
			'std'  => 'on'
		),
		array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		),
		array(
			'name' => esc_html__('Display comments?','discy'),
			'id'   => 'display_comment',
			'type' => 'checkbox',
			'std'  => 'on'
		),
		array(
			'name' => esc_html__('Excerpt post','discy'),
			'id'   => 'excerpt_post',
			'std'  => '0',
			'type' => 'text'
		),
		array(
			'div'       => 'div',
			'condition' => 'post_style:is(style_2)',
			'type'      => 'heading-2'
		),
		array(
			'name' => esc_html__('Activate the more post button','discy'),
			'desc' => esc_html__('Select ON to enable the button.','discy'),
			'id'   => 'blog_h_button',
			'std'  => 'on',
			'type' => 'checkbox'
		),
		array(
			'div'       => 'div',
			'condition' => 'blog_h_button:not(0)',
			'type'      => 'heading-2'
		),
		array(
			'name' => esc_html__('The text for the button','discy'),
			'desc' => esc_html__('Type from here the text for the button','discy'),
			'id'   => 'blog_h_button_text',
			'type' => 'text',
			'std'  => 'Explore Our Blog'
		),
		array(
			'name'    => esc_html__('Blog page','discy'),
			'desc'    => esc_html__('Select the blog page','discy'),
			'id'      => 'blog_h_page',
			'type'    => 'select',
			'options' => $options_pages
		),
		array(
			'name' => esc_html__("Type the blog link if you don't like a page","discy"),
			'id'   => 'blog_h_link',
			'type' => 'text'
		),
		array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		),
		array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		),
		array(
			'name'    => esc_html__('Specific date.','discy'),
			'desc'    => esc_html__('Select the specific date.','discy'),
			'id'      => "specific_date_post",
			'std'     => "all",
			'type'    => "radio",
			'options' => array(
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
		),
		array(
			'name'    => esc_html__('Display','discy'),
			'id'      => 'display',
			'options' => array("lasts" => esc_html__("Latest Posts","discy"),"category" => esc_html__("Single Category","discy"),"categories" => esc_html__("Multiple Categories","discy"),"exclude_categories" => esc_html__("Exclude Categories","discy"),"custom_posts" => esc_html__("Custom Posts","discy")),
			'std'     => 'recent',
			'type'    => 'select'
		),
		array(
			'name'      => esc_html__('Category','discy'),
			'id'        => 'category',
			'type'      => 'select_category',
			'condition' => 'display:is(category)'
		),
		array(
			'name'      => esc_html__('Categories','discy'),
			'id'        => 'categories',
			'type'      => 'multicheck_category',
			'condition' => 'display:is(categories)'
		),
		array(
			'name'      => esc_html__('Exclude Categories','discy'),
			'id'        => 'exclude_categories',
			'type'      => 'multicheck_category',
			'condition' => 'display:is(exclude_categories)'
		),
		array(
			'name'      => esc_html__('Custom posts','discy'),
			'id'        => 'custom_posts',
			'type'      => 'text',
			'condition' => 'display:is(custom_posts)'
		),
		array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		),
		
		array(
			'div'       => 'div',
			'condition' => 'post_or_question:is(question)',
			'type'      => 'heading-2'
		),
		array(
			'div'       => 'div',
			'condition' => 'question_style:is(style_2)',
			'type'      => 'heading-2'
		),
		array(
			'name' => esc_html__('Display image?','discy'),
			'id'   => 'display_image_question',
			'type' => 'checkbox',
			'std'  => 'on'
		),
		array(
			'name' => esc_html__('Display video if there?','discy'),
			'id'   => 'display_video_question',
			'type' => 'checkbox',
			'std'  => 'on'
		),
		array(
			'name' => esc_html__('Display date?','discy'),
			'id'   => 'display_date_question',
			'type' => 'checkbox',
			'std'  => 'on'
		),
		array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		),
		array(
			'name' => esc_html__('Display answers?','discy'),
			'id'   => 'display_answer',
			'type' => 'checkbox',
			'std'  => 'on'
		),
		array(
			'name'    => esc_html__('Specific date.','discy'),
			'desc'    => esc_html__('Select the specific date.','discy'),
			'id'      => "specific_date",
			'std'     => "all",
			'type'    => "radio",
			'options' => array(
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
		),
		array(
			'name'    => esc_html__('Display','discy'),
			'id'      => 'display_question',
			'options' => array("lasts" => esc_html__("Latest Questions","discy"),"category" => esc_html__("Single Category","discy"),"categories" => esc_html__("Multiple Categories","discy"),"exclude_categories" => esc_html__("Exclude Categories","discy"),"custom_posts" => esc_html__("Custom Questions","discy")),
			'std'     => 'recent',
			'type'    => 'select'
		),
		array(
			'name'      => esc_html__('Category','discy'),
			'id'        => 'category_question',
			'type'      => 'select_category',
			'taxonomy'  => 'question-category',
			'condition' => 'display_question:is(category)'
		),
		array(
			'name'      => esc_html__('Categories','discy'),
			'id'        => 'categories_question',
			'type'      => 'multicheck_category',
			'taxonomy'  => 'question-category',
			'condition' => 'display_question:is(categories)'
		),
		array(
			'name'      => esc_html__('Exclude Categories','discy'),
			'id'        => 'exclude_categories_question',
			'type'      => 'multicheck_category',
			'taxonomy'  => 'question-category',
			'condition' => 'display_question:is(exclude_categories)'
		),
		array(
			'name'      => esc_html__('Custom questions','discy'),
			'id'        => 'custom_questions',
			'type'      => 'text',
			'condition' => 'display_question:is(custom_posts)'
		),
		array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		),
	);
	
	$options['tabs-widget'] = array(
		array(
			'name' => esc_html__('Title','discy'),
			'id'   => 'title',
			'type' => 'text',
			'std'  => 'Tabs'
		),
		array(
			'name'    => esc_html__('Post or question','discy'),
			'id'      => 'post_or_question',
			'options' => array("post" => esc_html__("Posts","discy"),"question" => esc_html__("Questions","discy")),
			'std'     => 'post',
			'type'    => 'radio'
		),
		array(
			'name'      => esc_html__("Select What's tabs show","discy"),
			'id'        => 'tabs',
			'type'      => 'multicheck',
			'sort'      => 'yes',
			'std'       => array(
				"display_posts"    => array("sort" => esc_html__("Display posts?","discy"),"value" => "display_posts"),
				"display_comments" => array("sort" => esc_html__("Display comments?","discy"),"value" => "display_comments"),
				"display_tags"     => array("sort" => esc_html__("Display tags?","discy"),"value" => "display_tags"),
			),
			'options'   => array(
				"display_posts"    => array("sort" => esc_html__("Display posts?","discy"),"value" => "display_posts"),
				"display_comments" => array("sort" => esc_html__("Display comments?","discy"),"value" => "display_comments"),
				"display_tags"     => array("sort" => esc_html__("Display tags?","discy"),"value" => "display_tags"),
			),
			'condition' => 'post_or_question:is(post)'
		),
		array(
			'name'      => esc_html__("Select What's tabs show","discy"),
			'id'        => 'tabs_questions',
			'type'      => 'multicheck',
			'sort'      => 'yes',
			'std'       => array(
				"display_posts"    => array("sort" => esc_html__("Display questions?","discy"),"value" => "display_posts"),
				"display_comments" => array("sort" => esc_html__("Display answers?","discy"),"value" => "display_comments"),
				"display_tags"     => array("sort" => esc_html__("Display tags?","discy"),"value" => "display_tags"),
			),
			'options'   => array(
				"display_posts"    => array("sort" => esc_html__("Display questions?","discy"),"value" => "display_posts"),
				"display_comments" => array("sort" => esc_html__("Display answers?","discy"),"value" => "display_comments"),
				"display_tags"     => array("sort" => esc_html__("Display tags?","discy"),"value" => "display_tags"),
			),
			'condition' => 'post_or_question:is(question)'
		),
		array(
			'div'       => 'div',
			'condition' => 'post_or_question:is(post),tabs:has(display_posts)',
			'type'      => 'heading-2'
		),
		array(
			'name'    => esc_html__('Post style','discy'),
			'id'      => 'post_style',
			'options' => array("style_1" => esc_html__("Style 1","discy"),"style_2" => esc_html__("Style 2","discy")),
			'std'     => 'style_1',
			'type'    => 'radio'
		),
		array(
			'name' => esc_html__('Number of posts to show','discy'),
			'id'   => 'posts_per_page',
			'type' => 'text',
			'std'  => '5',
		),
		array(
			'name'      => esc_html__('Display author image?','discy'),
			'id'        => 'show_images_post',
			'type'      => 'checkbox',
			'condition' => 'post_style:is(style_1)',
			'std'       => 'on',
		),
		array(
			'name' => esc_html__('The excerpt title','discy'),
			'id'   => 'excerpt_title_post',
			'type' => 'text',
			'std'  => '10',
		),
		array(
			'name'    => esc_html__('Order by','discy'),
			'id'      => 'orderby_post',
			'options' => array("recent" => esc_html__("Recent","discy"),"random" => esc_html__("Random","discy"),"popular" => esc_html__("Most Commented","discy"),"most_visited" => esc_html__("Most visited","discy"),"no_response" => esc_html__("No response","discy")),
			'std'     => 'popular',
			'type'    => 'select'
		),
		array(
			'name' => esc_html__('Display comments meta?','discy'),
			'id'   => 'display_comment_meta',
			'type' => 'checkbox',
			'std'  => 'on'
		),
		
		array(
			'div'       => 'div',
			'condition' => 'post_or_question:is(post)',
			'type'      => 'heading-2'
		),
		array(
			'div'       => 'div',
			'condition' => 'post_style:is(style_2)',
			'type'      => 'heading-2'
		),
		array(
			'name' => esc_html__('Display image?','discy'),
			'id'   => 'display_image',
			'type' => 'checkbox',
			'std'  => 'on'
		),
		array(
			'name' => esc_html__('Display video if there?','discy'),
			'id'   => 'display_video',
			'type' => 'checkbox',
			'std'  => 'on'
		),
		array(
			'name' => esc_html__('Display date?','discy'),
			'id'   => 'display_date_2',
			'type' => 'checkbox',
			'std'  => 'on'
		),
		array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		),
		array(
			'name' => esc_html__('Excerpt post','discy'),
			'id'   => 'excerpt_post',
			'std'  => '0',
			'type' => 'text'
		),
		array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		),
		array(
			'name'    => esc_html__('Display','discy'),
			'id'      => 'display',
			'options' => array("lasts" => esc_html__("Latest Posts","discy"),"category" => esc_html__("Single Category","discy"),"categories" => esc_html__("Multiple Categories","discy"),"exclude_categories" => esc_html__("Exclude Categories","discy"),"custom_posts" => esc_html__("Custom Posts","discy")),
			'std'     => 'recent',
			'type'    => 'select'
		),
		array(
			'name'      => esc_html__('Category','discy'),
			'id'        => 'category',
			'type'      => 'select_category',
			'condition' => 'display:is(category)'
		),
		array(
			'name'      => esc_html__('Categories','discy'),
			'id'        => 'categories',
			'type'      => 'multicheck_category',
			'condition' => 'display:is(categories)'
		),
		array(
			'name'      => esc_html__('Exclude Categories','discy'),
			'id'        => 'exclude_categories',
			'type'      => 'multicheck_category',
			'condition' => 'display:is(exclude_categories)'
		),
		array(
			'name'      => esc_html__('Custom posts','discy'),
			'id'        => 'custom_posts',
			'type'      => 'text',
			'condition' => 'display:is(custom_posts)'
		),
		array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		),
		
		array(
			'div'       => 'div',
			'condition' => 'post_or_question:is(question),tabs_questions:has(display_posts)',
			'type'      => 'heading-2'
		),
		array(
			'name'    => esc_html__('Question style','discy'),
			'id'      => 'question_style',
			'options' => array("style_1" => esc_html__("Style 1","discy"),"style_2" => esc_html__("Style 2","discy")),
			'std'     => 'style_1',
			'type'    => 'radio'
		),
		array(
			'name' => esc_html__('Number of questions to show','discy'),
			'id'   => 'questions_per_page',
			'type' => 'text',
			'std'  => '5',
		),
		array(
			'name'      => esc_html__('Display author image?','discy'),
			'id'        => 'show_images',
			'type'      => 'checkbox',
			'condition' => 'question_style:is(style_1)',
			'std'       => 'on',
		),
		array(
			'name' => esc_html__('The excerpt title','discy'),
			'id'   => 'excerpt_title',
			'type' => 'text',
			'std'  => '10',
		),
		array(
			'name'    => esc_html__('Order by','discy'),
			'id'      => 'orderby',
			'options' => array("recent" => esc_html__("Recent","discy"),"random" => esc_html__("Random","discy"),"popular" => esc_html__("Most Answered","discy"),"most_visited" => esc_html__("Most visited","discy"),"most_voted" => esc_html__("Most voted","discy"),"no_response" => esc_html__("No response","discy")),
			'std'     => 'popular',
			'type'    => 'select',
		),
		array(
			'div'       => 'div',
			'condition' => 'question_style:is(style_2)',
			'type'      => 'heading-2'
		),
		array(
			'name' => esc_html__('Display image?','discy'),
			'id'   => 'display_image_question',
			'type' => 'checkbox',
			'std'  => 'on'
		),
		array(
			'name' => esc_html__('Display video if there?','discy'),
			'id'   => 'display_video_question',
			'type' => 'checkbox',
			'std'  => 'on'
		),
		array(
			'name' => esc_html__('Display date?','discy'),
			'id'   => 'display_date_2_question',
			'type' => 'checkbox',
			'std'  => 'on'
		),
		array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		),
		array(
			'name' => esc_html__('Display answers meta?','discy'),
			'id'   => 'display_answer_meta',
			'type' => 'checkbox',
			'std'  => 'on'
		),
		array(
			'name'    => esc_html__('Specific date.','discy'),
			'desc'    => esc_html__('Select the specific date.','discy'),
			'id'      => "specific_date",
			'std'     => "all",
			'type'    => "radio",
			'options' => array(
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
		),
		array(
			'name'    => esc_html__('Display','discy'),
			'id'      => 'display_question',
			'options' => array("lasts" => esc_html__("Latest Questions","discy"),"category" => esc_html__("Single Category","discy"),"categories" => esc_html__("Multiple Categories","discy"),"exclude_categories" => esc_html__("Exclude Categories","discy"),"custom_posts" => esc_html__("Custom Questions","discy")),
			'std'     => 'recent',
			'type'    => 'select'
		),
		array(
			'name'      => esc_html__('Category','discy'),
			'id'        => 'category_question',
			'type'      => 'select_category',
			'taxonomy'  => 'question-category',
			'condition' => 'display_question:is(category)'
		),
		array(
			'name'      => esc_html__('Categories','discy'),
			'id'        => 'categories_question',
			'type'      => 'multicheck_category',
			'taxonomy'  => 'question-category',
			'condition' => 'display_question:is(categories)'
		),
		array(
			'name'      => esc_html__('Exclude Categories','discy'),
			'id'        => 'exclude_categories_question',
			'type'      => 'multicheck_category',
			'taxonomy'  => 'question-category',
			'condition' => 'display_question:is(exclude_categories)'
		),
		array(
			'name'      => esc_html__('Custom questions','discy'),
			'id'        => 'custom_questions',
			'type'      => 'text',
			'condition' => 'display_question:is(custom_posts)'
		),
		array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		),
		
		array(
			'div'       => 'div',
			'condition' => 'post_or_question:is(post),tabs:has(display_comments)',
			'type'      => 'heading-2'
		),
		array(
			'name' => esc_html__('Comments','discy'),
			'type' => 'info',
		),
		array(
			'name'    => esc_html__('Specific date.','discy'),
			'desc'    => esc_html__('Select the specific date.','discy'),
			'id'      => "specific_date_comments",
			'std'     => "all",
			'type'    => "radio",
			'options' => array(
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
		),
		array(
			'name' => esc_html__('The number of words excerpt comments','discy'),
			'id'   => 'excerpt_comment',
			'type' => 'text',
			'std'  => '10',
		),
		array(
			'name' => esc_html__('Number of comments to show','discy'),
			'id'   => 'comments_number',
			'type' => 'text',
			'std'  => '5',
		),
		array(
			'name' => esc_html__('Display author image?','discy'),
			'id'   => 'images_comment',
			'type' => 'checkbox',
			'std'  => 'on',
		),
		array(
			'name' => esc_html__('Display date?','discy'),
			'id'   => 'display_date_post',
			'type' => 'checkbox',
			'std'  => 'on',
		),
		array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		),
		
		array(
			'div'       => 'div',
			'condition' => 'post_or_question:is(question),tabs_questions:has(display_comments)',
			'type'      => 'heading-2'
		),
		array(
			'name' => esc_html__('Answers','discy'),
			'type' => 'info',
		),
		array(
			'name'    => esc_html__('Specific date.','discy'),
			'desc'    => esc_html__('Select the specific date.','discy'),
			'id'      => "specific_date_answers",
			'std'     => "all",
			'type'    => "radio",
			'options' => array(
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
		),
		array(
			'name' => esc_html__('The number of words excerpt answers','discy'),
			'id'   => 'excerpt_answer',
			'type' => 'text',
			'std'  => '10',
		),
		array(
			'name' => esc_html__('Number of answers to show','discy'),
			'id'   => 'answers_number',
			'type' => 'text',
			'std'  => '5',
		),
		array(
			'name' => esc_html__('Display author image?','discy'),
			'id'   => 'images_answer',
			'type' => 'checkbox',
			'std'  => 'on',
		),
		array(
			'name' => esc_html__('Display date?','discy'),
			'id'   => 'display_date',
			'type' => 'checkbox',
			'std'  => 'on',
		),
		array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		),
	);

	$stats_array = array(
		"questions"    => array("sort" => esc_html__("Questions","discy"),"value" => "questions"),
		"answers"      => array("sort" => esc_html__("Answers","discy"),"value" => "answers"),
		"posts"        => array("sort" => esc_html__("Posts","discy"),"value" => "posts"),
		"comments"     => array("sort" => esc_html__("Comments","discy"),"value" => "comments"),
		"best_answers" => array("sort" => esc_html__("Best Answers","discy"),"value" => "best_answers"),
		"users"        => array("sort" => esc_html__("Users","discy"),"value" => "users"),
	);
	$stats_array = apply_filters("discy_widget_stats_array",$stats_array);
	
	$options['stats-widget'] = array(
		array(
			'name' => esc_html__('Title','discy'),
			'id'   => 'title',
			'type' => 'text'
		),
		array(
			'name'    => esc_html__('Choose the stats show','discy'),
			'id'      => 'stats',
			'type'    => 'multicheck',
			'sort'    => 'yes',
			'std'     => $stats_array,
			'options' => $stats_array
		),
		array(
			'name'    => esc_html__('Query type','discy'),
			'id'      => 'style',
			'options' => array(
				'style_1' => 'Style 1',
				'style_2' => 'Style 2',
			),
			'std'     => 'style_1',
			'type'    => 'select'
		),
		array(
			'name' => esc_html__('Display divider?','discy'),
			'id'   => 'divider',
			'type' => 'checkbox',
			'std'  => 'on'
		),
	);
	
	$options['twitter-widget'] = array(
		array(
			'name' => esc_html__('Title','discy'),
			'id'   => 'title',
			'type' => 'text',
			'std'  => 'Latest Tweets'
		),
		array(
			'name' => esc_html__('Number of tweets to show','discy'),
			'id'   => 'no_of_tweets',
			'type' => 'text',
			'std'  => '5'
		),
		array(
			'name' => esc_html__('Twitter username','discy'),
			'id'   => 'accounts',
			'type' => 'text',
			'std'  => '2codeThemes'
		),
	);
	
	$options['groups-widget'] = array(
		array(
			'name' => esc_html__('Title','discy'),
			'id'   => 'title',
			'type' => 'text',
			'std'  => 'Groups'
		),
		array(
			'name' => esc_html__('Number of groups to show','discy'),
			'id'   => 'no_of_groups',
			'type' => 'text',
			'std'  => '5'
		),
		array(
			'name'    => esc_html__('Display by','discy'),
			'desc'    => esc_html__('Select the groups display by.','discy'),
			'id'      => 'group_display',
			'options' => array(
				'all'     => esc_html__('All groups','discy'),
				'private' => esc_html__('Private groups','discy'),
				'public'  => esc_html__('Public groups','discy'),
			),
			'std'     => 'all',
			'type'    => 'radio',
		),
		array(
			'name'    => esc_html__('Order by','discy'),
			'desc'    => esc_html__('Select the groups order by.','discy'),
			'id'      => 'group_order',
			'options' => array(
				'date'  => esc_html__('Date','discy'),
				'users' => esc_html__('Users','discy'),
				'posts' => esc_html__('Posts','discy'),
			),
			'std'     => 'date',
			'type'    => 'radio',
		),
	);
	
	$options['tag_cloud'] = array(
		array(
			'name' => esc_html__('Number of tags to show','discy'),
			'id'   => 'number_tags',
			'type' => 'text',
			'std'  => '21'
		),
	);
	
	return $options;
}
/* Show widget fields */
add_action( 'init', 'discy_options_widgets' );
function discy_options_widgets () {
	$options = discy_admin_widgets();
	foreach ( $options as $widget_id => $fields ) {
		$fields = apply_filters( $widget_id . '_widget_fields_args', $fields );
		
		discy_widget_options(array (
			'id'     => $widget_id,
			'fields' => $fields
		));
	}
}
/* Widget fields */
function discy_widget_options( $args ) {
	add_action( 'in_widget_form', 'discy_widget_fields', 10, 3 );
	add_filter( 'widget_update_callback', 'discy_save_widget', 10, 4 );
}
/* Add extra form fields to the widget */
function discy_widget_fields( $widget, $return, $instance ) {
	$options = discy_admin_widgets();
	if ( array_key_exists( $widget->id_base, $options ) ) {?>
		<div class="discy_widgets discy_framework">
			<?php discy_options_fields($instance,"","widgets",$widget,$options[$widget->id_base]);?>
		</div>
	<?php }
}
/* Save widget fields on widget save */
function discy_save_widget( $instance, $new_instance, $old_instance, $widget ) {
	$options = discy_admin_widgets();
	if (array_key_exists($widget->id_base,$options)) {
		foreach ($options[$widget->id_base] as $key => $value) {
			if (isset($value["id"]) && isset($value["type"]) && $value["type"] == "checkbox" && !isset($new_instance[$value["id"]])) {
				$new_instance[$value["id"]] = 0;
			}
		}
		
		if (array_key_exists("twitter-widget",$options) && $widget->id_base == "twitter-widget") {
			delete_transient('discy_twitter_widget_'.$widget->id.$instance['accounts']);
		}else if (array_key_exists("widget_counter",$options) && $widget->id_base == "widget_counter") {
			delete_transient('discy_facebook_followers');
			delete_transient('discy_twitter_followers');
			delete_transient('discy_vimeo_followers');
			delete_transient('discy_vimeo_page_url');
			delete_transient('discy_dribbble_followers');
			delete_transient('discy_dribbble_page_url');
			delete_transient('discy_youtube_followers');
			delete_transient('discy_pinterest_followers');
			delete_transient('discy_instagram_followers');
			delete_transient('discy_instagram_page_url');
			delete_transient('discy_soundcloud_followers');
			delete_transient('discy_soundcloud_page_url');
			delete_transient('discy_behance_followers');
			delete_transient('discy_behance_page_url');
			delete_transient('discy_envato_followers');
			delete_transient('discy_envato_page_url');
			delete_transient('discy_github_followers');
			delete_transient('discy_github_page_url');
		}
		return $new_instance;
	}
	return $instance;
}?>