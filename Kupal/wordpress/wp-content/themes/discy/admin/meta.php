<?php
function discy_is_post_type($post_types = array("post")) {
	if (isset($post_types) && is_array($post_types)) {
		$screen = get_current_screen();
		if (in_array($screen->post_type,$post_types)) {
			return true;
		}
	}
}
/* Meta options */
function discy_admin_meta() {
	global $post;
	// Background Defaults
	$background_defaults = array(
		'color' => '',
		'image' => '',
		'repeat' => 'repeat',
		'position' => 'top center',
		'attachment'=>'scroll'
	);

	// Pull all the pages into an array
	$options_pages = array();
	$options_pages_obj = get_pages('sort_column=post_parent,menu_order');
	$options_pages[''] = esc_html__('Select a page:','discy');
	foreach ($options_pages_obj as $page) {
		$options_pages[$page->ID] = $page->post_title;
	}
	
	// Pull all the sidebars into an array
	$sidebars = get_option('sidebars');
	$new_sidebars = array('default'=> 'Default');
	foreach ($GLOBALS['wp_registered_sidebars'] as $sidebar) {
		$new_sidebars[$sidebar['id']] = $sidebar['name'];
	}
	
	// Pull all the menus into an array
	$menus = array();
	$all_menus = get_terms('nav_menu',array('hide_empty' => true));
	foreach ($all_menus as $menu) {
	    $menus[$menu->term_id] = $menu->name;
	}
	
	// Share
	$share_array = array(
		"share_facebook" => array("sort" => "Facebook","value" => "share_facebook"),
		"share_twitter"  => array("sort" => "Twitter","value" => "share_twitter"),
		"share_linkedin" => array("sort" => "LinkedIn","value" => "share_linkedin"),
		"share_whatsapp" => array("sort" => "WhatsApp","value" => "share_whatsapp"),
	);
	
	// Question meta
	$question_meta_std = array(
		"author_by"         => "author_by",
		"question_date"     => "question_date",
		"category_question" => "category_question",
		"question_answer"   => "question_answer",
		"question_views"    => "question_views",
		"bump_meta"         => "bump_meta",
	);
	
	$question_meta_options = array(
		"author_by"         => esc_html__('Author by','discy'),
		"question_date"     => esc_html__('Date meta','discy'),
		"category_question" => esc_html__('Category question','discy'),
		"question_answer"   => esc_html__('Answer meta','discy'),
		"question_views"    => esc_html__('Views stats','discy'),
		"bump_meta"         => esc_html__('Bump question meta','discy'),
	);
	
	// Post meta
	$post_meta_std = array(
		"category_post" => "category_post",
		"title_post"    => "title_post",
		"author_by"     => "author_by",
		"post_date"     => "post_date",
		"post_comment"  => "post_comment",
		"post_views"    => "post_views",
	);
	
	$post_meta_options = array(
		"category_post" => esc_html__('Category post - Work at 1 column only','discy'),
		"title_post"    => esc_html__('Title post','discy'),
		"author_by"     => esc_html__('Author by - Work at 1 column only','discy'),
		"post_date"     => esc_html__('Date meta','discy'),
		"post_comment"  => esc_html__('Comment meta','discy'),
		"post_views"    => esc_html__("Views stats","discy"),
	);
	
	// If using image radio buttons, define a directory path
	$imagepath =  get_template_directory_uri(). '/admin/images/';
	$imagepath_theme =  get_template_directory_uri(). '/images/';

	$options = array();

	$options = apply_filters('discy_options_before_meta_options',$options,$post);
	
	if (discy_is_post_type(array("question"))) {
		$options[] = array(
			'name' => esc_html__('Question settings','discy'),
			'id'   => 'question_settings',
			'icon' => 'editor-help',
			'type' => 'heading'
		);
		
		$options[] = array(
			'type' => 'heading-2'
		);
		
		$question_poll = discy_post_meta("question_poll","",false);
		$get_question_user_id = discy_post_meta("user_id","",false);
		$show_the_anonymously = apply_filters("discy_show_the_anonymously",true);
		
		$question_html = '<div class="custom-meta-field">';
			if ($show_the_anonymously == true && $post->post_author == 0) {
				$anonymously_question = discy_post_meta("anonymously_question","",false);
				$anonymously_user = discy_post_meta("anonymously_user","",false);
				if (($anonymously_question == "on" || $anonymously_question == 1) && $anonymously_user != "") {
					$question_username = esc_html__('Anonymous','discy');
					$question_email = 0;
				}else {
					$question_username = discy_post_meta("question_username","",false);
					$question_email = discy_post_meta("question_email","",false);
					$question_username = ($question_username != ""?$question_username:esc_html__('Anonymous','discy'));
					$question_email = ($question_email != ""?$question_email:"");
				}
				$question_html .= '<ul>
					<li><div class="clear"></div><br><span class="dashicons dashicons-admin-users"></span> '.esc_attr($question_username).'</li>';
					if ($question_email != "") {
						$question_html .= '<li><div class="clear"></div><br><span class="dashicons dashicons-email-alt"></span> '.esc_attr($question_email).'</li>';
					}
				$question_html .= '</ul>';
			}
			
			if ($get_question_user_id != "") {
				$display_name = get_the_author_meta('display_name',$get_question_user_id);
				if (isset($display_name) && $display_name != "") {
					$question_html .= '<ul>
						<li><div class="clear"></div><br><span class="dashicons dashicons-admin-users"></span> '.esc_html__('This question has asked to','discy').' <a target="_blank" href="'. get_author_posts_url($get_question_user_id).'">'.esc_attr($display_name).'</a></li>
					</ul>
					<div class="no-user-question"></div>';
				}
			}else {
				$added_file = discy_post_meta("added_file","",false);
				if ($added_file != "") {
					$question_html .= '<ul><li><div class="clear"></div><br><a href="'.wp_get_attachment_url($added_file).'">'.esc_html__('Attachment','discy').'</a> - <a class="delete-this-attachment single-attachment" href="'.$added_file.'">'.esc_html__('Delete','discy').'</a></li></ul>';
				}
				$attachment_m = discy_post_meta("attachment_m","",false);
				if (isset($attachment_m) && is_array($attachment_m) && !empty($attachment_m)) {
					$question_html .= '<ul>';
						foreach ($attachment_m as $key => $value) {
							$question_html .= '<li><div class="clear"></div><br><a href="'.wp_get_attachment_url($value["added_file"]).'">'.esc_html__('Attachment','discy').'</a> - <a class="delete-this-attachment" href="'.$value["added_file"].'">'.esc_html__('Delete','discy').'</a></li>';
						}
					$question_html .= '</ul>';
				}
			}
		$question_html .= '</div>';
		
		$options[] = array(
			'type'    => 'content',
			'content' => $question_html,
		);
		
		if ($get_question_user_id == "") {
	       	$question_html = '<div class="custom-meta-field">';
		        $asks = discy_post_meta("ask","",false);
		        $wpqa_poll = discy_post_meta("wpqa_poll","",false);
		        if ($question_poll != "" && $question_poll == "on") {
		        	if (isset($wpqa_poll) && is_array($wpqa_poll)) {
		        		$i = 0;
		        		$question_html .= '<div class="custom-meta-label"><label>'.esc_html__('Stats of Users','discy').'</label></div><div class="clear"></div><br>';
		        		foreach ($wpqa_poll as $wpqa_polls):$i++;
		        			$question_html .= (isset($asks[$wpqa_polls['id']]['title']) && $asks[$wpqa_polls['id']]['title'] != ''?esc_html( $asks[$wpqa_polls['id']]['title'] ).' --- ':'').(isset($wpqa_polls['value']) && $wpqa_polls['value'] != 0?stripslashes( $wpqa_polls['value'] ):0)." Votes <br>";
			        		if (isset($wpqa_polls['user_ids']) && is_array($wpqa_polls['user_ids'])) {
			        			foreach ($wpqa_polls['user_ids'] as $key => $value) {
			        				if ($value > 0) {
			        					$user_name = get_the_author_meta("display_name",$value);
			        					if (isset($user_name) && $user_name != "") {
			        						$question_html .= '<div class="vpanel_checkbox_input"><p class="description">'.$user_name.' '.esc_html__('Has vote for','discy').' '.(isset($asks[$wpqa_polls['id']]['title']) && $asks[$wpqa_polls['id']]['title'] != ''?esc_html( $asks[$wpqa_polls['id']]['title'] ):'').'</p></div>';
			        					}
			        				}else {
			        					$question_html .= '<div class="vpanel_checkbox_input"><p class="description">'.esc_html__('Unregistered user has vote for','discy').' '.(isset($asks[$wpqa_polls['id']]['title']) && $asks[$wpqa_polls['id']]['title'] != ''?esc_html( $asks[$wpqa_polls['id']]['title'] ):'').'</p></div>';
			        				}
			        			}
			        			$question_html .= '<br>';
			        		}
			        	endforeach;
		        	}
		        }
			$question_html .= '</div>';
			
			$options = apply_filters('discy_options_before_question_poll',$options);

			if ($post->ID > 0) {
				$html_content = '<a class="button fix-comments" data-post="'.$post->ID.'" href="'.admin_url("post.php?post=".$post->ID."&action=edit").'">'.esc_html__("Fix the answers count","discy").'</a>';
				$options[] = array(
					'name' => $html_content,
					'type' => 'info'
				);
			}

			$pay_answer = discy_options("pay_answer");
			if ($pay_answer == "on") {
				$payment_type_answer = discy_options("payment_type_answer");
				$activate_currencies = discy_options("activate_currencies");
				$multi_currencies = discy_options("multi_currencies");
				$options[] = array(
					'name' => esc_html__('Do you need to activate the custom option for this question to allow the user pay to answer?','discy'),
					'id'   => 'custom_pay_answer',
					'type' => 'checkbox'
				);

				$options[] = array(
					'type'      => 'heading-2',
					'condition' => 'custom_pay_answer:not(0)',
					'div'       => 'div'
				);

				$options[] = array(
					'name' => esc_html__('Do you need to activate the pay to answer in this question?','discy'),
					'id'   => 'pay_answer',
					'std'  => 'on',
					'type' => 'checkbox'
				);

				$options[] = array(
					'type'      => 'heading-2',
					'condition' => 'pay_answer:not(0)',
					'div'       => 'div'
				);

				if ($payment_type_answer != "points" && $activate_currencies != "on") {
					$options[] = array(
						"name" => esc_html__("What's the price to add a new answer?","discy"),
						"desc" => esc_html__("Type here price to add a new answer","discy"),
						"id"   => "pay_answer_payment",
						"type" => "text",
						'std'  => 10
					);
				}

				if ($activate_currencies == "on" && is_array($multi_currencies) && !empty($multi_currencies)) {
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

				if ($payment_type_answer == "points" || $payment_type_answer == "payments_points") {
					$options[] = array(
						"name" => esc_html__("How many points to add a new answer?","discy"),
						"desc" => esc_html__("Type here points of the payment to add a new answer","discy"),
						"id"   => "answer_payment_points",
						"type" => "text",
						'std'  => 20
					);
				}

				$options[] = array(
					'type' => 'heading-2',
					'end'  => 'end',
					'div'  => 'div'
				);

				$options[] = array(
					'type' => 'heading-2',
					'end'  => 'end',
					'div'  => 'div'
				);
			}

			$options[] = array(
				'name' => esc_html__("Question settings","discy"),
				'type' => 'info'
			);

			$options[] = array(
				'name' => esc_html__('Is this question is a poll?','discy'),
				'id'   => 'question_poll',
				'type' => 'checkbox'
			);

			$options = apply_filters('discy_options_after_question_poll',$options);

			$options[] = array(
				'type'      => 'heading-2',
				'condition' => 'question_poll:not(0),question_poll:not(2)',
				'div'       => 'div'
			);
			
			$options[] = array(
				'name' => esc_html__('Poll title','discy'),
				'desc' => esc_html__('Put here the poll title if you need to add custom title.','discy'),
				'id'   => prefix_meta."question_poll_title",
				'type' => 'text',
			);

			$question_poll_array = array(
				array(
					"type" => "text",
					"id"   => "title",
					"name" => esc_html__('Title','discy'),
				),
				array(
					"type" => "hidden_id",
					"id"   => "id"
				),
			);
			
			$poll_image = discy_options("poll_image");
			$question_poll_image = array();
			if ($poll_image == "on") {
				$question_poll_image = array(
					array(
						"type" => "upload",
						"id"   => "image",
						"name" => esc_html__('Image','discy'),
					)
				);
			}
			
			$options[] = array(
				'id'        => "ask",
				'type'      => "elements",
				'button'    => esc_html__('Add a new option to poll','discy'),
				'not_theme' => 'not',
				'hide'      => "yes",
				'options'   => array_merge($question_poll_array,$question_poll_image)
			);
			
			$options[] = array(
				'type'    => 'content',
				'content' => $question_html,
			);
			
			$options[] = array(
				'type' => 'heading-2',
				'end'  => 'end',
				'div'  => 'div'
			);
		}
		
		if ($get_question_user_id == "") {
			$hide_question_categories = apply_filters('discy_hide_question_categories',false);
			$category_single_multi = discy_options("category_single_multi");
			if ($hide_question_categories == false && $category_single_multi != "multi") {
				$options[] = array(
					'name'        => esc_html__('Choose from here the question category.','discy'),
					'id'          => prefix_meta.'question_category',
					'option_none' => esc_html__('Select a Category','discy'),
					'type'        => 'select_category',
					'taxonomy'    => 'question-category',
					'selected'    => 's_f_category'
				);
			}
			
			$options[] = array(
				'name' => esc_html__('Video description','discy'),
				'desc' => esc_html__('Add a Video to describe the problem better.','discy'),
				'id'   => 'video_description',
				'type' => 'checkbox',
			);
			
			$options[] = array(
				'name'      => esc_html__('Video type','discy'),
				'id'        => 'video_type',
				'type'      => 'select',
				'options'   => array(
					'youtube'  => esc_html__("Youtube","discy"),
					'vimeo'    => esc_html__("Vimeo","discy"),
					'daily'    => esc_html__("Dailymotion","discy"),
					'facebook' => esc_html__("Facebook","discy"),
				),
				'std'       => 'youtube',
				'condition' => 'video_description:not(0)',
				'desc'      => esc_html__('Choose from here the video type.','discy'),
			);
			
			$options[] = array(
				'name'      => esc_html__('Video ID','discy'),
				'desc'      => esc_html__('Put the Video ID here: https://www.youtube.com/watch?v=sdUUx5FdySs Ex: "sdUUx5FdySs".','discy'),
				'id'        => "video_id",
				'condition' => 'video_description:not(0)',
				'type'      => 'text',
			);
			
			$ask_question_items = discy_options("ask_question_items");
			if (isset($ask_question_items["featured_image"]["value"]) && $ask_question_items["featured_image"]["value"] == "featured_image") {
				$options[] = array(
					'name' => esc_html__('Custom featured image size','discy'),
					'desc' => esc_html__('Select ON to set the custom featured image size.','discy'),
					'id'   => prefix_meta.'custom_featured_image_size',
					'type' => 'checkbox'
				);
				
				$options[] = array(
					'type'      => 'heading-2',
					'condition' => prefix_meta.'custom_featured_image_size:not(0)',
					'div'       => 'div'
				);
				
				$options[] = array(
					"name" => esc_html__("Featured image width","discy"),
					"id"   => prefix_meta."featured_image_width",
					"type" => "sliderui",
					"std"  => "260",
					"step" => "1",
					"min"  => "50",
					"max"  => "600"
				);
				
				$options[] = array(
					"name" => esc_html__("Featured image height","discy"),
					"id"   => prefix_meta."featured_image_height",
					"type" => "sliderui",
					"std"  => "185",
					"step" => "1",
					"min"  => "50",
					"max"  => "600"
				);
				
				$options[] = array(
					'type' => 'heading-2',
					'end'  => 'end',
					'div'  => 'div'
				);
			}
		}
		
		$options[] = array(
			'name' => esc_html__('Notification by e-mail','discy'),
			'desc' => esc_html__('Get notified by email when someone answers this question','discy'),
			'id'   => 'remember_answer',
			'type' => 'checkbox',
		);
		
		$options[] = array(
			'name' => esc_html__('Private question?','discy'),
			'desc' => esc_html__('This question is a private question?','discy'),
			'id'   => 'private_question',
			'type' => 'checkbox',
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'end'  => 'end'
		);

		$options = apply_filters('discy_options_before_answer_tabs',$options);

		$options[] = array(
			'type' => 'heading-2',
			'name' => esc_html__('Custom answer tabs','discy')
		);
		
		$options[] = array(
			'name' => esc_html__('Choose a custom setting for the answer tabs','discy'),
			'id'   => prefix_meta.'custom_answer_tabs',
			'type' => 'checkbox'
		);

		$answers_tabs = array(
			"votes"  => array("sort" => esc_html__('Voted','discy'),"value" => "votes"),
			"oldest" => array("sort" => esc_html__('Oldest','discy'),"value" => "oldest"),
			"recent" => array("sort" => esc_html__('Recent','discy'),"value" => "recent"),
			"random" => array("sort" => esc_html__('Random','discy'),"value" => ""),
		);
		
		$options[] = array(
			'name'      => esc_html__('Tabs at the answers','discy'),
			'desc'      => esc_html__('Select the tabs at the answers on the question page.','discy'),
			'id'        => prefix_meta.'answers_tabs',
			'type'      => 'multicheck',
			'sort'      => 'yes',
			'std'       => $answers_tabs,
			'options'   => $answers_tabs,
			'condition' => prefix_meta.'custom_answer_tabs:not(0)'
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'end'  => 'end'
		);
	}
	
	if (discy_is_post_type(array("post","page","question","group"))) {
		if (discy_is_post_type(array("post","page","question"))) {
			$options[] = array(
				'name' => esc_html__('Call to action','discy'),
				'id'   => 'call_to_action',
				'icon' => 'welcome-widgets-menus',
				'type' => 'heading'
			);
			
			$options[] = array(
				'type' => 'heading-2'
			);

			$options[] = array(
				'name' => esc_html__('Custom call to action','discy'),
				'id'   => prefix_meta.'custom_call_action',
				'type' => 'checkbox'
			);
			
			$options[] = array(
				'div'       => 'div',
				'type'      => 'heading-2',
				'condition' => prefix_meta.'custom_call_action:not(0)'
			);

			$options[] = array(
				'name' => esc_html__('Activate the call to action','discy'),
				'desc' => esc_html__('Select ON to enable the Call to action.','discy'),
				'id'   => prefix_meta.'call_action',
				'type' => 'checkbox',
				'std'  => 'on'
			);
			
			$options[] = array(
				'div'       => 'div',
				'type'      => 'heading-2',
				'condition' => prefix_meta.'call_action:not(0)'
			);
			
			$options[] = array(
				'name'    => esc_html__('Action skin','discy'),
				'desc'    => esc_html__('Choose the action skin.','discy'),
				'id'      => prefix_meta.'action_skin',
				'std'     => 'dark',
				'type'    => 'radio',
				'options' => array("light" => esc_html__("Light","discy"),"dark" => esc_html__("Dark","discy"),"colored" => esc_html__("Colored","discy"))
			);
			
			$options[] = array(
				'name'    => esc_html__('Action style','discy'),
				'desc'    => esc_html__('Choose action style from here.','discy'),
				'id'      => prefix_meta.'action_style',
				'options' => array(
					'style_1'  => 'Style 1',
					'style_2'  => 'Style 2',
				),
				'std'     => 'style_1',
				'type'    => 'radio'
			);
			
			$options[] = array(
				'name'    => esc_html__('Action image or video','discy'),
				'id'      => prefix_meta.'action_image_video',
				'options' => array(
					'image' => esc_html__('Image','discy'),
					'video' => esc_html__('Video','discy'),
				),
				'std'     => 'image',
				'type'    => 'radio'
			);
			
			$options[] = array(
				'div'       => 'div',
				'condition' => prefix_meta.'action_image_video:not(video)',
				'type'      => 'heading-2'
			);
			
			$options[] = array(
				'name'    => esc_html__('Upload the background','discy'),
				'id'      => prefix_meta.'action_background',
				'type'    => 'background',
				'options' => array('color' => '','image' => ''),
				'std'     => array(
					'image' => $imagepath_theme."action.png"
				)
			);
			
			$options[] = array(
				"name" => esc_html__('Choose the background opacity','discy'),
				"desc" => esc_html__('Choose the background opacity from here','discy'),
				"id"   => prefix_meta."action_opacity",
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
				'condition' => prefix_meta.'action_image_video:is(video)',
				'type'      => 'heading-2'
			);

			$options[] = array(
				'name'    => esc_html__('Video type','discy'),
				'id'      => prefix_meta.'action_video_type',
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
				'id'        => prefix_meta."action_custom_embed",
				'type'      => 'textarea',
				'cols'      => "40",
				'rows'      => "8",
				'condition' => prefix_meta.'action_video_type:is(embed)'
			);
			
			$options[] = array(
				'name'      => esc_html__('Video ID','discy'),
				'id'        => prefix_meta.'action_video_id',
				'desc'      => esc_html__('Put the Video ID here: https://www.youtube.com/watch?v=JuyB7NO0EYY Ex: "JuyB7NO0EYY"','discy'),
				'type'      => 'text',
				'operator'  => 'or',
				'condition' => prefix_meta.'action_video_type:is(youtube),'.prefix_meta.'action_video_type:is(vimeo),'.prefix_meta.'action_video_type:is(daily),'.prefix_meta.'action_video_type:is(facebook)'
			);
			
			$options[] = array(
				'name'      => esc_html__('Mp4 video','discy'),
				'id'        => prefix_meta.'action_video_mp4',
				'desc'      => esc_html__('Put mp4 video here','discy'),
				'type'      => 'text',
				'condition' => prefix_meta.'action_video_type:is(html5)'
			);
			
			$options[] = array(
				'name'      => esc_html__('M4v video','discy'),
				'id'        => prefix_meta.'action_video_m4v',
				'desc'      => esc_html__('Put m4v video here','discy'),
				'type'      => 'text',
				'condition' => prefix_meta.'action_video_type:is(html5)'
			);
			
			$options[] = array(
				'name'      => esc_html__('Webm video','discy'),
				'id'        => prefix_meta.'action_video_webm',
				'desc'      => esc_html__('Put webm video here','discy'),
				'type'      => 'text',
				'condition' => prefix_meta.'action_video_type:is(html5)'
			);
			
			$options[] = array(
				'name'      => esc_html__('Ogv video','discy'),
				'id'        => prefix_meta.'action_video_ogv',
				'desc'      => esc_html__('Put ogv video here','discy'),
				'type'      => 'text',
				'condition' => prefix_meta.'action_video_type:is(html5)'
			);
			
			$options[] = array(
				'name'      => esc_html__('Wmv video','discy'),
				'id'        => prefix_meta.'action_video_wmv',
				'desc'      => esc_html__('Put wmv video here','discy'),
				'type'      => 'text',
				'condition' => prefix_meta.'action_video_type:is(html5)'
			);
			
			$options[] = array(
				'name'      => esc_html__('Flv video','discy'),
				'id'        => prefix_meta.'action_video_flv',
				'desc'      => esc_html__('Put flv video here','discy'),
				'type'      => 'text',
				'condition' => prefix_meta.'action_video_type:is(html5)'
			);
			
			$options[] = array(
				'type' => 'heading-2',
				'div'  => 'div',
				'end'  => 'end'
			);
			
			$options[] = array(
				'name' => esc_html__('The headline','discy'),
				'desc' => esc_html__('Type the Headline from here','discy'),
				'id'   => prefix_meta.'action_headline',
				'type' => 'text',
				'std'  => "Share & grow the world's knowledge!"
			);
			
			$options[] = array(
				'name'     => esc_html__('The paragraph','discy'),
				'desc'     => esc_html__('Type the Paragraph from here','discy'),
				'id'       => prefix_meta.'action_paragraph',
				'type'     => apply_filters('discy_action_paragraph','textarea'),
				'std'      => 'We want to connect the people who have knowledge to the people who need it, to bring together people with different perspectives so they can understand each other better, and to empower everyone to share their knowledge.'
			);
			
			$options[] = array(
				'name'    => esc_html__('Action button','discy'),
				'desc'    => esc_html__('Choose Action button style from here.','discy'),
				'id'      => prefix_meta.'action_button',
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
				'condition' => prefix_meta.'action_button:is(custom)',
				'type'      => 'heading-2'
			);
			
			$options[] = array(
				'name'    => esc_html__('Open the page in same page or a new page?','discy'),
				'id'      => prefix_meta.'action_button_target',
				'std'     => "new_page",
				'type'    => 'select',
				'options' => array("same_page" => esc_html__("Same page","discy"),"new_page" => esc_html__("New page","discy"))
			);
			
			$options[] = array(
				'name' => esc_html__('Type the button link','discy'),
				'id'   => prefix_meta.'action_button_link',
				'type' => 'text'
			);
			
			$options[] = array(
				'name' => esc_html__('Type the button text','discy'),
				'id'   => prefix_meta.'action_button_text',
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
				'id'      => prefix_meta.'action_logged',
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
				'div'  => 'div',
				'end'  => 'end'
			);

			$options[] = array(
				'type' => 'heading-2',
				'end'  => 'end'
			);

			$options[] = array(
				'name' => esc_html__('Popup Notification','discy'),
				'id'   => 'popup_notification',
				'icon' => 'lightbulb',
				'type' => 'heading'
			);
			
			$options[] = array(
				'type' => 'heading-2'
			);

			$options[] = array(
				'name' => esc_html__('Custom popup notification','discy'),
				'id'   => prefix_meta.'custom_popup_notification',
				'type' => 'checkbox'
			);
			
			$options[] = array(
				'div'       => 'div',
				'type'      => 'heading-2',
				'condition' => prefix_meta.'custom_popup_notification:not(0)'
			);
			
			$options[] = array(
				'name' => esc_html__('Note: the last popup notification only will show not all the popup notifications.','discy'),
				'type' => 'info'
			);

			$options[] = array(
				'name'    => esc_html__('Show the popup notification for the user one time only or forever','discy'),
				'id'      => prefix_meta.'popup_notification_time',
				'options' => array(
					'one_time' => esc_html__('One time','discy'),
					'for_ever' => esc_html__('Forever','discy'),
				),
				'std'     => 'one_time',
				'type'    => 'radio'
			);

			$options[] = array(
				'name'    => esc_html__('Choose the roles or users for the popup notification','discy'),
				'desc'    => esc_html__('Choose from here which roles or users you want to send the popup notification.','discy'),
				'id'      => prefix_meta.'popup_notification_groups_users',
				'options' => array(
					'groups' => esc_html__('Roles','discy'),
					'users'  => esc_html__('Users','discy'),
				),
				'std'     => 'groups',
				'type'    => 'radio'
			);

			global $wp_roles;
			$new_roles = array();
			foreach ($wp_roles->roles as $key => $value) {
				$new_roles[$key] = $value['name'];
			}

			$options[] = array(
				'name'      => esc_html__("Choose the roles you need to send the popup notification.","discy"),
				'id'        => prefix_meta.'popup_notification_groups',
				'condition' => prefix_meta.'popup_notification_groups_users:not(users)',
				'type'      => 'multicheck',
				'options'   => $new_roles,
				'std'       => array('administrator' => 'administrator','editor' => 'editor','contributor' => 'contributor','subscriber' => 'subscriber','author' => 'author'),
			);

			$options[] = array(
				'name'      => esc_html__('Specific user ids','discy'),
				'id'        => prefix_meta.'popup_notification_specific_users',
				'condition' => prefix_meta.'popup_notification_groups_users:is(users)',
				'type'      => 'text'
			);
			
			$options[] = array(
				'name'     => esc_html__('Popup notification text','discy'),
				'id'       => prefix_meta.'popup_notification_text',
				'type'     => 'editor',
				'settings' => array("media_buttons" => true,"textarea_rows" => 10)
			);

			$options[] = array(
				'name'    => esc_html__('Open the page in same page or a new page?','discy'),
				'id'      => prefix_meta.'popup_notification_button_target',
				'std'     => "new_page",
				'type'    => 'select',
				'options' => array("same_page" => esc_html__("Same page","discy"),"new_page" => esc_html__("New page","discy"))
			);
			
			$options[] = array(
				'name' => esc_html__('Type the button link','discy'),
				'id'   => prefix_meta.'popup_notification_button_url',
				'type' => 'text'
			);
			
			$options[] = array(
				'name' => esc_html__('Type the button text','discy'),
				'id'   => prefix_meta.'popup_notification_button_text',
				'type' => 'text'
			);
			
			$options[] = array(
				'name' => esc_html__('You must save your options before sending the popup notification.','discy'),
				'type' => 'info'
			);

			$html_content = '<a href="#" class="button button-primary send-popup-notification" data-post="'.$post->ID.'">'.esc_html__('Send the popup notification','discy').'</a>';
			
			$options[] = array(
				'name' => $html_content,
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

			$options[] = array(
				'name' => esc_html__('Slider','discy'),
				'id'   => 'sliders',
				'icon' => 'images-alt2',
				'type' => 'heading'
			);
			
			$options[] = array(
				'type' => 'heading-2'
			);

			$options[] = array(
				'name' => esc_html__('Custom slider','discy'),
				'id'   => prefix_meta.'custom_sliders',
				'type' => 'checkbox'
			);
			
			$options[] = array(
				'div'       => 'div',
				'type'      => 'heading-2',
				'condition' => prefix_meta.'custom_sliders:not(0)'
			);

			$options[] = array(
				'name' => esc_html__('Activate the slider or not','discy'),
				'desc' => esc_html__('Select ON to enable the posts area.','discy'),
				'id'   => prefix_meta.'slider_h',
				'type' => 'checkbox',
				'std'  => 'on'
			);
			
			$options[] = array(
				'div'       => 'div',
				'condition' => prefix_meta.'slider_h:not(0)',
				'type'      => 'heading-2'
			);

			$options[] = array(
				'name'    => esc_html__('Slider works for "Unlogged users", "Logged users" or both','discy'),
				'id'      => prefix_meta.'slider_h_logged',
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
				'id'      => prefix_meta.'custom_slider',
				'options' => array(
					'slider' => esc_html__('Theme slider','discy'),
					'custom' => esc_html__('Custom slider','discy'),
				),
				'std'     => 'slider',
				'type'    => 'radio',
			);

			$options[] = array(
				'div'       => 'div',
				'condition' => prefix_meta.'custom_slider:is(slider)',
				'type'      => 'heading-2'
			);

			$options[] = array(
				'name' => esc_html__('Slider height','discy'),
				"id"   => prefix_meta."slider_height",
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
				'id'        => prefix_meta."add_slides",
				'type'      => "elements",
				'button'    => esc_html__('Add a new slide','discy'),
				'hide'      => "yes",
				'not_theme' => "not",
				'options'   => $slide_elements,
			);
			
			$options[] = array(
				'type' => 'heading-2',
				'div'  => 'div',
				'end'  => 'end'
			);
			
			$options[] = array(
				'id'        => prefix_meta."custom_slides",
				'type'      => "textarea",
				'name'      => esc_html__('Add your custom slide or shortcode','discy'),
				'condition' => prefix_meta.'custom_slider:is(custom)',
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
		}

		$options = apply_filters('discy_options_before_pages_options',$options);

		if (discy_is_post_type(array("group"))) {
			$options[] = array(
				'name' => esc_html__('Group Options','discy'),
				'id'   => 'group_settings',
				'icon' => 'groups',
				'type' => 'heading'
			);
			
			$options[] = array(
				'type' => 'heading-2'
			);

			$options[] = array(
				'name'    => esc_html__('Group privacy','discy'),
				'id'      => 'group_privacy',
				'std'     => 'public',
				'type'    => 'radio',
				'options' => 
					array(
						"public"  => esc_html__("Public group","discy"),
						"private" => esc_html__("Private group","discy")
					)
			);

			$options[] = array(
				'name'    => esc_html__('Group invitation','discy'),
				'id'      => 'group_invitation',
				'std'     => 'all',
				'type'    => 'radio',
				'options' => 
					array(
						"all"              => esc_html__("All group members","discy"),
						"admin_moderators" => esc_html__("Admin and moderators","discy"),
						"admin"            => esc_html__("Admin only","discy")
					)
			);

			$options[] = array(
				'name'    => esc_html__('Group posts','discy'),
				'id'      => 'group_allow_posts',
				'std'     => 'all',
				'type'    => 'radio',
				'options' => 
					array(
						"all"              => esc_html__("All group members","discy"),
						"admin_moderators" => esc_html__("Admin and moderators","discy"),
						"admin"            => esc_html__("Admin only","discy")
					)
			);

			$options[] = array(
				'name' => esc_html__('Activate comments in this group?','discy'),
				'desc' => esc_html__('Select ON to active the comments in this group','discy'),
				'id'   => 'group_comments',
				'std'  => 'on',
				'type' => 'checkbox'
			);

			$options[] = array(
				'name' => esc_html__('Upload the group photo, that represents this group','discy'),
				'id'   => "group_image",
				'type' => 'upload',
			);

			$options[] = array(
				'name' => esc_html__('Upload the group cover','discy'),
				'id'   => "group_cover",
				'type' => 'upload',
			);

			$options[] = array(
				'name'     => esc_html__('Group rules','discy'),
				'id'       => 'group_rules',
				'type'     => 'editor',
				'settings' => array("media_buttons" => true,"textarea_rows" => 10)
			);

			$options[] = array(
				'type' => 'heading-2',
				'end'  => 'end'
			);
		}

		$options[] = array(
			'name' => esc_html__('Pages Options','discy'),
			'id'   => 'page_settings',
			'icon' => 'admin-site',
			'type' => 'heading'
		);
		
		$options[] = array(
			'type' => 'heading-2'
		);
		
		if (discy_is_post_type(array("page"))) {
			$options[] = array(
				'name' => esc_html__('Activate this page for "Unlogged users", "Unconfirmed users" and "Unreviewed users"?','discy'),
				'desc' => esc_html__('Select ON to activate this page for "Unlogged users", "Unconfirmed users" and "Unreviewed users"','discy'),
				'id'   => prefix_meta.'login_only',
				'type' => 'checkbox'
			);

			$options[] = array(
				'name' => esc_html__('Activate this page for banned users?','discy'),
				'desc' => esc_html__('Select ON to activate this page for banned users','discy'),
				'id'   => prefix_meta.'banned_only',
				'type' => 'checkbox'
			);
		}

		$options = apply_filters('discy_options_before_sidebar_layout',$options);
		
		$options[] = array(
			'name'    => esc_html__('Sidebar layout','discy'),
			'id'      => prefix_meta."sidebar",
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
			'name'      => esc_html__('Select your sidebar','discy'),
			'id'        => prefix_meta.'what_sidebar',
			'options'   => $new_sidebars,
			'type'      => 'select',
			'condition' => prefix_meta.'sidebar:not(full),'.prefix_meta.'sidebar:not(centered),'.prefix_meta.'sidebar:not(menu_left)'
		);

		$left_area = discy_options("left_area");
		if ($left_area == "sidebar") {
			$options[] = array(
				'name'      => esc_html__('Select your sidebar 2 if you activated it for the left menu','discy'),
				'id'        => prefix_meta.'what_sidebar_2',
				'options'   => $new_sidebars,
				'type'      => 'select',
				'condition' => prefix_meta.'sidebar:not(full),'.prefix_meta.'sidebar:not(centered),'.prefix_meta.'sidebar:not(right),'.prefix_meta.'sidebar:not(left)'
			);
		}else {
			$menu_array = array(esc_html__('Select a menu:','discy'))+$menus;
			$options[] = array(
				'name'      => esc_html__('Select your menu','discy'),
				'id'        => prefix_meta.'left_menu',
				'options'   => $menu_array,
				'type'      => 'select',
				'condition' => prefix_meta.'sidebar:not(full),'.prefix_meta.'sidebar:not(centered),'.prefix_meta.'sidebar:not(right),'.prefix_meta.'sidebar:not(left)'
			);
		}
		
		$options[] = array(
			'name'    => esc_html__('Choose Your Skin','discy'),
			'class'   => "site_skin",
			'id'      => prefix_meta."skin",
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
			'id'   => prefix_meta."primary_color",
			'type' => 'color'
		);
		
		$options[] = array(
			'name'    => esc_html__('Background Type','discy'),
			'id'      => prefix_meta.'background_type',
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
			'id'        => prefix_meta."background_color",
			'type'      => 'color',
			'condition' => prefix_meta.'background_type:is(patterns)'
		);
			
		$options[] = array(
			'name'      => esc_html__('Choose Pattern','discy'),
			'id'        => prefix_meta."background_pattern",
			'std'       => "bg13",
			'type'      => "images",
			'condition' => prefix_meta.'background_type:is(patterns)',
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
			'id'        => prefix_meta.'custom_background',
			'std'       => $background_defaults,
			'options'   => $background_defaults,
			'type'      => 'background',
			'condition' => prefix_meta.'background_type:is(custom_background)'
		);
			
		$options[] = array(
			'name'      => esc_html__('Full Screen Background','discy'),
			'desc'      => esc_html__('Select ON to enable Full Screen Background','discy'),
			'id'        => prefix_meta.'full_screen_background',
			'type'      => 'checkbox',
			'condition' => prefix_meta.'background_type:is(custom_background)'
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'end'  => 'end'
		);
	}
	
	if (discy_is_post_type(array("post"))) {
		$options[] = array(
			'name' => esc_html__('Post head Options','discy'),
			'id'   => 'post_head_options',
			'icon' => 'schedule',
			'type' => 'heading'
		);
		
		$options[] = array(
			'type' => 'heading-2'
		);

		$options = apply_filters('discy_meta_before_head_post',$options);

		if ($post->ID > 0) {
			$html_content = '<a class="button fix-comments" data-post="'.$post->ID.'" href="'.admin_url("post.php?post=".$post->ID."&action=edit").'">'.esc_html__("Fix the comments count","discy").'</a>';
			$options[] = array(
				'name' => $html_content,
				'type' => 'info'
			);
		}
		
		$options[] = array(
			'name'    => esc_html__('Head post','discy'),
			'id'      => 'what_post',
			'type'    => 'select',
			'options' => array(
				'none'			 => esc_html__("None","discy"),
				'image'			 => esc_html__("Featured Image","discy"),
				'image_lightbox' => esc_html__("Image With Lightbox","discy"),
				'google'		 => esc_html__("Google Map","discy"),
				'slideshow'		 => esc_html__("Slideshow","discy"),
				'video'			 => esc_html__("Video","discy"),
				/*
				'quote'			 => esc_html__("Quote","discy"),
				'link'			 => esc_html__("Link","discy"),
				'twitter'		 => esc_html__("Twitter","discy"),
				'facebook'		 => esc_html__("Facebook","discy"),
				'instagram'		 => esc_html__("Instagram","discy"),
				*/
				'soundcloud'	 => esc_html__("Soundcloud","discy"),
				'audio'	         => esc_html__("Audio","discy"),
			),
			'std'     => 'image',
			'desc'    => esc_html__('Choose from here the post type','discy'),
		);
		
		$options[] = array(
			'type'      => 'heading-2',
			'operator'  => 'or',
			'condition' => 'what_post:is(image),what_post:is(image_lightbox)',
			'div'       => 'div'
		);
		
		$options[] = array(
			'name'    => esc_html__('Featured image style','discy'),
			'desc'    => esc_html__('Featured image style from here.','discy'),
			'id'      => prefix_meta.'featured_image_style',
			'std'     => 'default',
			'options' => array(
				'default'     => 'Default',
				'style_270'   => '270x180',
				'style_140'   => '140x140',
				'custom_size' => esc_html__('Custom size','discy'),
			),
			'type'    => 'radio'
		);
		
		$options[] = array(
			'type'      => 'heading-2',
			'condition' => prefix_meta.'featured_image_style:is(custom_size)',
			'div'       => 'div'
		);
		
		$options[] = array(
			"name"  => esc_html__('Featured image width','discy'),
			"id"    => prefix_meta."featured_image_width",
			'class' => 'width_50',
			"type"  => "sliderui",
			"step"  => "1",
			"min"   => "140",
			"max"   => "500"
		);
		
		$options[] = array(
			"name"  => esc_html__('Featured image height','discy'),
			"id"    => prefix_meta."featured_image_height",
			'class' => 'width_50',
			"type"  => "sliderui",
			"step"  => "1",
			"min"   => "140",
			"max"   => "500"
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'end'  => 'end',
			'div'  => 'div'
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'end'  => 'end',
			'div'  => 'div'
		);
		
		$options[] = array(
			'name'      => esc_html__('Google map','discy'),
			'desc'      => esc_html__('Put your google map html','discy'),
			'id'        => prefix_meta."google",
			'type'      => 'textarea',
			'condition' => 'what_post:is(google)',
			'cols'      => "40",
			'rows'      => "8"
		);
		
		$options[] = array(
			'name'      => esc_html__('Audio URL MP3','discy'),
			'desc'      => esc_html__('Put your audio URL MP3','discy'),
			'id'        => prefix_meta."audio",
			'type'      => 'text',
			'condition' => 'what_post:is(audio)',
		);
		
		$options[] = array(
			'name'      => esc_html__('Slideshow ?','discy'),
			'id'        => prefix_meta.'slideshow_type',
			'type'      => 'select',
			'options'   => array(
				'custom_slide'  => esc_html__("Custom Slideshow","discy"),
				'upload_images' => esc_html__("Upload your images","discy"),
			),
			'std'       => 'custom_slide',
			'condition' => 'what_post:is(slideshow)'
		);
		
		$slide_elements = array(
			array(
				"type" => "upload",
				"id"   => "image_url",
				"name" => esc_html__('Image URL','discy')
			),
			array(
				"type" => "text",
				"id"   => "slide_link",
				"name" => esc_html__('Slide Link','discy')
			)
		);
		
		$options[] = array(
			'id'        => prefix_meta.'slideshow_post',
			'type'      => "elements",
			'not_theme' => "not",
			'hide'      => "yes",
			'button'    => esc_html__('Add a new slide','discy'),
			'options'   => $slide_elements,
			'condition' => 'what_post:is(slideshow),'.prefix_meta.'slideshow_type:is(custom_slide)',
		);
		
		$options[] = array(
			'name'      => esc_html__('Upload your images','discy'),
			'id'        => prefix_meta."upload_images",
			'type'      => 'upload_images',
			'condition' => 'what_post:is(slideshow),'.prefix_meta.'slideshow_type:is(upload_images)',
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => 'what_post:is(video)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name'    => esc_html__('Video type','discy'),
			'id'      => prefix_meta.'video_post_type',
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
			'id'        => prefix_meta."custom_embed",
			'type'      => 'textarea',
			'cols'      => "40",
			'rows'      => "8",
			'condition' => prefix_meta.'video_post_type:is(embed)'
		);
		
		$options[] = array(
			'name'      => esc_html__('Video ID','discy'),
			'id'        => prefix_meta.'video_post_id',
			'desc'      => esc_html__('Put the Video ID here: https://www.youtube.com/watch?v=JuyB7NO0EYY Ex: "JuyB7NO0EYY"','discy'),
			'type'      => 'text',
			'operator'  => 'or',
			'condition' => prefix_meta.'video_post_type:is(youtube),'.prefix_meta.'video_post_type:is(vimeo),'.prefix_meta.'video_post_type:is(daily),'.prefix_meta.'video_post_type:is(facebook)'
		);
		
		$options[] = array(
			'name'      => esc_html__('Video Image','discy'),
			'desc'      => esc_html__('Upload a image, or enter URL to an image if it is already uploaded.','discy'),
			'id'        => prefix_meta.'video_image',
			'type'      => 'upload',
			'condition' => prefix_meta.'video_post_type:is(html5)'
		);
		
		$options[] = array(
			'name'      => esc_html__('Mp4 video','discy'),
			'id'        => prefix_meta.'video_mp4',
			'desc'      => esc_html__('Put mp4 video here','discy'),
			'type'      => 'text',
			'condition' => prefix_meta.'video_post_type:is(html5)'
		);
		
		$options[] = array(
			'name'      => esc_html__('M4v video','discy'),
			'id'        => prefix_meta.'video_m4v',
			'desc'      => esc_html__('Put m4v video here','discy'),
			'type'      => 'text',
			'condition' => prefix_meta.'video_post_type:is(html5)'
		);
		
		$options[] = array(
			'name'      => esc_html__('Webm video','discy'),
			'id'        => prefix_meta.'video_webm',
			'desc'      => esc_html__('Put webm video here','discy'),
			'type'      => 'text',
			'condition' => prefix_meta.'video_post_type:is(html5)'
		);
		
		$options[] = array(
			'name'      => esc_html__('Ogv video','discy'),
			'id'        => prefix_meta.'video_ogv',
			'desc'      => esc_html__('Put ogv video here','discy'),
			'type'      => 'text',
			'condition' => prefix_meta.'video_post_type:is(html5)'
		);
		
		$options[] = array(
			'name'      => esc_html__('Wmv video','discy'),
			'id'        => prefix_meta.'video_wmv',
			'desc'      => esc_html__('Put wmv video here','discy'),
			'type'      => 'text',
			'condition' => prefix_meta.'video_post_type:is(html5)'
		);
		
		$options[] = array(
			'name'      => esc_html__('Flv video','discy'),
			'id'        => prefix_meta.'video_flv',
			'desc'      => esc_html__('Put flv video here','discy'),
			'type'      => 'text',
			'condition' => prefix_meta.'video_post_type:is(html5)'
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);
		/*
		$options[] = array(
			'name'      => esc_html__('Quote content','discy'),
			'id'        => prefix_meta.'quote_content',
			'desc'      => esc_html__('Put here the quote content','discy'),
			'type'      => 'textarea',
			'cols'      => "40",
			'rows'      => "8",
			'condition' => 'what_post:is(quote)'
		);
		
		$options[] = array(
			'name'      => esc_html__('Author','discy'),
			'id'        => prefix_meta.'quote_author',
			'desc'      => esc_html__('Put here the quote author','discy'),
			'type'      => 'text',
			'condition' => 'what_post:is(quote)'
		);
		
		$options[] = array(
			'name'      => esc_html__('Quote style','discy'),
			'id'        => prefix_meta.'quote_style',
			'type'      => 'select',
			'options'   => array(
				'full' => esc_html__("Full post","discy"),
				'box'  => esc_html__("Block box","discy"),
			),
			'std'       => 'full',
			'desc'      => esc_html__('Choose from here the quote style','discy'),
			'condition' => 'what_post:is(quote)'
		);
		
		$options[] = array(
			'name'      => esc_html__('Quote icon color','discy'),
			'id'        => prefix_meta.'quote_icon_color',
			'desc'      => esc_html__('Put here the quote icon color','discy'),
			'type'      => 'color',
			'condition' => 'what_post:is(quote)'
		);
		
		$options[] = array(
			'name'      => esc_html__('Quote color','discy'),
			'id'        => prefix_meta.'quote_color',
			'desc'      => esc_html__('Put here the quote color','discy'),
			'type'      => 'color',
			'condition' => 'what_post:is(quote)'
		);
		
		$options[] = array(
			'name'      => esc_html__('Link title','discy'),
			'id'        => prefix_meta.'link_title',
			'desc'      => esc_html__('Put here the link title','discy'),
			'type'      => 'text',
			'condition' => 'what_post:is(link)'
		);
		
		$options[] = array(
			'name'      => esc_html__('Link','discy'),
			'id'        => prefix_meta.'link',
			'desc'      => esc_html__('Put here the link','discy'),
			'type'      => 'text',
			'condition' => 'what_post:is(link)'
		);
		
		$options[] = array(
			'name'      => esc_html__('Link target','discy'),
			'id'        => prefix_meta.'link_target',
			'type'      => 'select',
			'options'   => array(
				'style_1' => esc_html__("Same window","discy"),
				'style_2' => esc_html__("New window","discy"),
			),
			'std'       => 'style_1',
			'desc'      => esc_html__('Choose from here the Link target','discy'),
			'condition' => 'what_post:is(link)'
		);
		
		$options[] = array(
			'name'      => esc_html__('Link style','discy'),
			'id'        => prefix_meta.'link_style',
			'type'      => 'select',
			'options'   => array(
				'full' => esc_html__("Full post","discy"),
				'box'  => esc_html__("Block box","discy"),
			),
			'std'       => 'full',
			'desc'      => esc_html__('Choose from here the link style','discy'),
			'condition' => 'what_post:is(link)'
		);
		
		$options[] = array(
			'name'      => esc_html__('Link icon color','discy'),
			'id'        => prefix_meta.'link_icon_color',
			'desc'      => esc_html__('Put here the link icon color','discy'),
			'type'      => 'color',
			'condition' => 'what_post:is(link)'
		);
		
		$options[] = array(
			'name'      => esc_html__('Link color','discy'),
			'id'        => prefix_meta.'link_color',
			'desc'      => esc_html__('Put here the link color','discy'),
			'type'      => 'color',
			'condition' => 'what_post:is(link)'
		);
		
		$options[] = array(
			'name'      => esc_html__('Link icon hover color','discy'),
			'id'        => prefix_meta.'link_icon_hover_color',
			'desc'      => esc_html__('Put here the link icon hover color','discy'),
			'type'      => 'color',
			'condition' => 'what_post:is(link)'
		);
		
		$options[] = array(
			'name'      => esc_html__('Link hover color','discy'),
			'id'        => prefix_meta.'link_hover_color',
			'desc'      => esc_html__('Put here the link hover color','discy'),
			'type'      => 'color',
			'condition' => 'what_post:is(link)'
		);
		*/
		$options[] = array(
			'name'      => esc_html__('Soundcloud embed','discy'),
			'id'        => prefix_meta.'soundcloud_embed',
			'desc'      => esc_html__('Put here the soundcloud embed','discy'),
			'type'      => 'text',
			'condition' => 'what_post:is(soundcloud)'
		);
		
		$options[] = array(
			'name'      => esc_html__('Soundcloud height','discy'),
			'id'        => prefix_meta.'soundcloud_height',
			'desc'      => esc_html__('Put here the soundcloud height','discy'),
			'type'      => 'text',
			'std'       => '150',
			'condition' => 'what_post:is(soundcloud)'
		);
		/*
		$options[] = array(
			'name'      => esc_html__('Twitter embed','discy'),
			'id'        => prefix_meta.'twitter_embed',
			'desc'      => esc_html__('Put here the twitter embed','discy'),
			'type'      => 'text',
			'condition' => 'what_post:is(twitter)'
		);
		
		$options[] = array(
			'name'      => esc_html__('Facebook embed','discy'),
			'id'        => prefix_meta.'facebook_embed',
			'desc'      => esc_html__('Put here the facebook embed','discy'),
			'type'      => 'textarea',
			'condition' => 'what_post:is(facebook)'
		);
		
		$options[] = array(
			'name'      => esc_html__('Instagram embed','discy'),
			'id'        => prefix_meta.'instagram_embed',
			'desc'      => esc_html__('Put here the instagram embed','discy'),
			'type'      => 'textarea',
			'condition' => 'what_post:is(instagram)'
		);
		
		$options[] = array(
			'type'      => 'heading-2',
			'operator'  => 'or',
			'condition' => 'what_post:is(quote),what_post:is(link),what_post:is(soundcloud),what_post:is(facebook),what_post:is(twitter),what_post:is(instagram)',
			'div'       => 'div'
		);
		
		$options[] = array(
			"name"       => esc_html__('Padding top','discy'),
			"id"         => prefix_meta."padding_top",
			'class'      => 'width_50',
			"type"       => "slider",
			'std'        => '30',
			"js_options" => array(
				"step" => "on",
				"min"  => 0,
				"max"  => 200,
			),
		);
		
		$options[] = array(
			"name"       => esc_html__('Padding right','discy'),
			"id"         => prefix_meta."padding_right",
			'class'      => 'width_50',
			"type"       => "slider",
			'std'        => '30',
			"js_options" => array(
				"step" => "on",
				"min"  => 0,
				"max"  => 200,
			),
		);
		
		$options[] = array(
			"name"       => esc_html__('Padding bottom','discy'),
			"id"         => prefix_meta."padding_bottom",
			'class'      => 'width_50',
			"type"       => "slider",
			'std'        => '30',
			"js_options" => array(
				"step" => "on",
				"min"  => 0,
				"max"  => 200,
			),
		);
		
		$options[] = array(
			"name"       => esc_html__('Padding left','discy'),
			"id"         => prefix_meta."padding_left",
			'class'      => 'width_50',
			"type"       => "slider",
			'std'        => '30',
			"js_options" => array(
				"step" => "on",
				"min"  => 0,
				"max"  => 200,
			),
		);
		
		$options[] = array(
			'name'    => esc_html__('Background','discy'),
			'id'      => prefix_meta.'post_head_background',
			'std'     => $background_defaults,
			'options' => $background_defaults,
			'type'    => 'background'
		);
		
		$options[] = array(
			'name' => esc_html__('Full Screen Background','discy'),
			'desc' => esc_html__('Select ON to enable Full Screen Background','discy'),
			'id'   => prefix_meta.'post_head_background_full',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name' => esc_html__('Transparent Background color ?','discy'),
			'id'   => prefix_meta.'post_head_background_transparent',
			'type' => 'checkbox',
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);
		*/
		$options[] = array(
			'type' => 'heading-2',
			'end'  => 'end'
		);
	}

	if (discy_is_post_type(array("page"))) {
		$options = apply_filters("discy_meta_before_home",$options);

		$options[] = array(
			'name'      => esc_html__('Home settings','discy'),
			'id'        => 'home_setting',
			'icon'      => 'admin-home',
			'type'      => 'heading',
			'template'  => 'template-home.php'
		);
		
		$options[] = array(
			'type' => 'heading-2'
		);
		
		$options[] = array(
			'name' => esc_html__('Show the ask question box?','discy'),
			'id'   => prefix_meta.'ask_question_box',
			'type' => 'checkbox',
			'std'  => 'on'
		);

		$options[] = array(
			'name'  => esc_html__('Show the tabs at the left menu?','discy'),
			'id'    => 'tabs_menu',
			'type'  => 'checkbox',
			'save'  => 'option'
		);
		
		$options[] = array(
			'name'     => esc_html__('Choose the categories show','discy'),
			'id'       => prefix_meta.'categories_show',
			'type'     => 'custom_addition',
			'taxonomy' => 'question-category',
			'addto'    => prefix_meta.'home_tabs',
			'toadd'    => 'yes',
		);
		
		$home_tabs = array(
			"feed"                 => array("sort" => esc_html__('Feed','discy'),"value" => "feed"),
			"recent-questions"     => array("sort" => esc_html__('Recent Questions','discy'),"value" => "recent-questions"),
			"questions-for-you"    => array("sort" => esc_html__('Questions For You','discy'),"value" => ""),
			"most-answers"         => array("sort" => esc_html__('Most Answered','discy'),"value" => "most-answers"),
			"answers"              => array("sort" => esc_html__('Answers','discy'),"value" => "answers"),
			"no-answers"           => array("sort" => esc_html__('No Answers','discy'),"value" => "no-answers"),
			"most-visit"           => array("sort" => esc_html__('Most Visited','discy'),"value" => "most-visit"),
			"most-vote"            => array("sort" => esc_html__('Most Voted','discy'),"value" => "most-vote"),
			"random"               => array("sort" => esc_html__('Random Questions','discy'),"value" => "random"),
			"question-bump"        => array("sort" => esc_html__('Bump Question','discy'),"value" => ""),
			"new-questions"        => array("sort" => esc_html__('New Questions','discy'),"value" => ""),
			"sticky-questions"     => array("sort" => esc_html__('Sticky Questions','discy'),"value" => ""),
			"polls"                => array("sort" => esc_html__('Poll Questions','discy'),"value" => ""),
			"followed"             => array("sort" => esc_html__('Followed Questions','discy'),"value" => ""),
			"favorites"            => array("sort" => esc_html__('Favorites Questions','discy'),"value" => ""),
			"answers-might-like"   => array("sort" => esc_html__('Answers You Might Like','discy'),"value" => ""),
			"answers-for-you"      => array("sort" => esc_html__('Answers For You','discy'),"value" => ""),
			"recent-posts"         => array("sort" => esc_html__('Recent Posts','discy'),"value" => ""),
			"posts-visited"        => array("sort" => esc_html__('Most Visited Posts','discy'),"value" => ""),
			
			"feed-2"               => array("sort" => esc_html__('Feed With Time','discy'),"value" => ""),
			"recent-questions-2"   => array("sort" => esc_html__('Recent Questions With Time','discy'),"value" => ""),
			"questions-for-you-2"  => array("sort" => esc_html__('Questions For You With Time','discy'),"value" => ""),
			"most-answers-2"       => array("sort" => esc_html__('Most Answered With Time','discy'),"value" => ""),
			"answers-2"            => array("sort" => esc_html__('Answers With Time','discy'),"value" => ""),
			"no-answers-2"         => array("sort" => esc_html__('No Answers With Time','discy'),"value" => ""),
			"most-visit-2"         => array("sort" => esc_html__('Most Visited With Time','discy'),"value" => ""),
			"most-vote-2"          => array("sort" => esc_html__('Most Voted With Time','discy'),"value" => ""),
			"random-2"             => array("sort" => esc_html__('Random Questions With Time','discy'),"value" => ""),
			"question-bump-2"      => array("sort" => esc_html__('Bump Question With Time','discy'),"value" => ""),
			"new-questions-2"      => array("sort" => esc_html__('New Questions With Time','discy'),"value" => ""),
			"sticky-questions-2"   => array("sort" => esc_html__('Sticky Questions With Time','discy'),"value" => ""),
			"polls-2"              => array("sort" => esc_html__('Poll Questions With Time','discy'),"value" => ""),
			"followed-2"           => array("sort" => esc_html__('Followed Questions With Time','discy'),"value" => ""),
			"favorites-2"          => array("sort" => esc_html__('Favorites Questions With Time','discy'),"value" => ""),
			"answers-might-like-2" => array("sort" => esc_html__('Answers You Might Like With Time','discy'),"value" => ""),
			"answers-for-you-2"    => array("sort" => esc_html__('Answers For You With Time','discy'),"value" => ""),
			"recent-posts-2"       => array("sort" => esc_html__('Recent Posts With Time','discy'),"value" => ""),
			"posts-visited-2"      => array("sort" => esc_html__('Most Visited Posts With Time','discy'),"value" => ""),
		);

		$home_tabs = apply_filters("discy_meta_home_tabs",$home_tabs);
		
		$options[] = array(
			'name'    => esc_html__('Select the tabs you want to show','discy'),
			'id'      => prefix_meta.'home_tabs',
			'type'    => 'multicheck',
			'sort'    => 'yes',
			'std'     => $home_tabs,
			'options' => $home_tabs
		);
		
		$options[] = array(
			'name'      => esc_html__('Show the categories filter?','discy'),
			'id'        => prefix_meta.'categories_filter',
			'condition' => 'tabs_menu:not(on)',
			'type'      => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => prefix_meta.'home_tabs:has(feed)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);

		$options[] = array(
			'div'       => 'div',
			'condition' => prefix_meta.'home_tabs:has(recent-questions)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name'    => esc_html__('Display by','discy'),
			'id'      => prefix_meta."question_display_r",
			'type'    => 'select',
			'options' => array(
				'lasts'	             => esc_html__('Lasts','discy'),
				'single_category'    => esc_html__('Single category','discy'),
				'categories'         => esc_html__('Multiple categories','discy'),
				'exclude_categories' => esc_html__('Exclude categories','discy'),
				'custom_posts'	     => esc_html__('Custom questions','discy'),
			),
			'std'     => 'lasts',
		);
		
		$options[] = array(
			'name'      => esc_html__('Single category','discy'),
			'id'        => prefix_meta.'question_single_category_r',
			'type'      => 'select_category',
			'condition' => prefix_meta.'question_display_r:is(single_category)',
			'taxonomy'  => 'question-category',
		);
		
		$options[] = array(
			'name'      => esc_html__('Question categories','discy'),
			'desc'      => esc_html__('Select the question categories.','discy'),
			'id'        => prefix_meta."question_categories_r",
			'type'      => 'multicheck_category',
			'condition' => prefix_meta.'question_display_r:is(categories)',
			'taxonomy'  => 'question-category',
		);
		
		$options[] = array(
			'name'      => esc_html__('Exclude Question categories','discy'),
			'desc'      => esc_html__('Select the exclude question categories.','discy'),
			'id'        => prefix_meta."question_exclude_categories_r",
			'type'      => 'multicheck_category',
			'condition' => prefix_meta.'question_display_r:is(exclude_categories)',
			'taxonomy'  => 'question-category',
		);
		
		$options[] = array(
			'name'      => esc_html__('Question ids','discy'),
			'desc'      => esc_html__('Type the question ids.','discy'),
			'id'        => prefix_meta."question_questions_r",
			'condition' => prefix_meta.'question_display_r:is(custom_posts)',
			'type'      => 'text',
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);
		
		$options[] = array(
			'name'    => esc_html__('Pagination style','discy'),
			'desc'    => esc_html__('Choose pagination style from here.','discy'),
			'id'      => prefix_meta.'pagination_home',
			'options' => array(
				'standard'        => esc_html__('Standard','discy'),
				'pagination'      => esc_html__('Pagination','discy'),
				'load_more'       => esc_html__('Load more','discy'),
				'infinite_scroll' => esc_html__('Infinite scroll','discy'),
				'none'            => esc_html__('None','discy'),
			),
			'std'   => 'pagination',
			'type'  => 'radio',
		);
		
		$options[] = array(
			'name' => esc_html__('Items per page','discy'),
			'desc' => esc_html__('Put the items per page.','discy'),
			'id'   => prefix_meta.'posts_per_page',
			'std'  => '10',
			'type' => 'text'
		);
		
		$options[] = array(
			'name'    => esc_html__('Order','discy'),
			'id'      => prefix_meta.'order_page_h',
			'std'     => "DESC",
			'type'    => 'radio',
			'options' => array(
				'DESC' => esc_html__('Descending','discy'),
				'ASC'  => esc_html__('Ascending','discy'),
			),
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'end'  => 'end'
		);
		
		$options[] = array(
			'type'      => 'heading-2',
			'condition' => prefix_meta.'home_tabs:has(feed),'.prefix_meta.'home_tabs:has(feed-2)',
			'operator'  => 'or',
			'name'      => esc_html__('Custom setting for feed tabs','discy')
		);

		$options = apply_filters('discy_options_question_feed',$options,"home_");

		$options[] = array(
			'type' => 'heading-2',
			'end'  => 'end'
		);

		$options[] = array(
			'type'      => 'heading-2',
			'condition' => prefix_meta.'home_tabs:has(feed-2),'.prefix_meta.'home_tabs:has(recent-questions-2),'.prefix_meta.'home_tabs:has(questions-for-you-2),'.prefix_meta.'home_tabs:has(most-answers-2),'.prefix_meta.'home_tabs:has(question-bump-2),'.prefix_meta.'home_tabs:has(new-questions-2),'.prefix_meta.'home_tabs:has(sticky-questions-2),'.prefix_meta.'home_tabs:has(polls-2),'.prefix_meta.'home_tabs:has(followed-2),'.prefix_meta.'home_tabs:has(favorites-2),'.prefix_meta.'home_tabs:has(answers-2),'.prefix_meta.'home_tabs:has(answers-might-like-2),'.prefix_meta.'home_tabs:has(answers-for-you-2),'.prefix_meta.'home_tabs:has(most-visit-2),'.prefix_meta.'home_tabs:has(most-vote-2),'.prefix_meta.'home_tabs:has(random-2),'.prefix_meta.'home_tabs:has(no-answers-2),'.prefix_meta.'home_tabs:has(recent-posts-2),'.prefix_meta.'home_tabs:has(posts-visited-2)',
			'operator'  => 'or',
			'name'      => esc_html__('Time frame for the tabs','discy')
		);

		$options[] = array(
			'name'      => esc_html__('Specific date for feed tab.','discy'),
			'desc'      => esc_html__('Select the specific date for feed tab.','discy'),
			'id'        => prefix_meta."date_feed",
			'std'       => "all",
			'type'      => "radio",
			'condition' => prefix_meta.'home_tabs:has(feed-2)',
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
			'name'      => esc_html__('Specific date for recent questions tab.','discy'),
			'desc'      => esc_html__('Select the specific date for recent questions tab.','discy'),
			'id'        => prefix_meta."date_recent_questions",
			'std'       => "all",
			'type'      => "radio",
			'condition' => prefix_meta.'home_tabs:has(recent-questions-2)',
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
			'name'      => esc_html__('Specific date for questions for you tab.','discy'),
			'desc'      => esc_html__('Select the specific date for questions for you tab.','discy'),
			'id'        => prefix_meta."date_questions_for_you",
			'std'       => "all",
			'type'      => "radio",
			'condition' => prefix_meta.'home_tabs:has(questions-for-you-2)',
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
			'id'        => prefix_meta."date_most_answered",
			'std'       => "all",
			'type'      => "radio",
			'condition' => prefix_meta.'home_tabs:has(most-answers-2)',
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
			'id'        => prefix_meta."date_question_bump",
			'std'       => "all",
			'type'      => "radio",
			'condition' => prefix_meta.'home_tabs:has(question-bump-2)',
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
			'id'        => prefix_meta."date_answers",
			'std'       => "all",
			'type'      => "radio",
			'condition' => prefix_meta.'home_tabs:has(answers-2)',
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
			'name'      => esc_html__('Specific date for answers you might like tab.','discy'),
			'desc'      => esc_html__('Select the specific date for answers you might like tab.','discy'),
			'id'        => prefix_meta."date_answers_might_like",
			'std'       => "all",
			'type'      => "radio",
			'condition' => prefix_meta.'home_tabs:has(answers-might-like-2)',
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
			'name'      => esc_html__('Specific date for answers for you tab.','discy'),
			'desc'      => esc_html__('Select the specific date for answers for you tab.','discy'),
			'id'        => prefix_meta."date_answers_for_you",
			'std'       => "all",
			'type'      => "radio",
			'condition' => prefix_meta.'home_tabs:has(answers-for-you-2)',
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
			'id'        => prefix_meta."date_most_visited",
			'std'       => "all",
			'type'      => "radio",
			'condition' => prefix_meta.'home_tabs:has(most-visit-2)',
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
			'id'        => prefix_meta."date_most_voted",
			'std'       => "all",
			'type'      => "radio",
			'condition' => prefix_meta.'home_tabs:has(most-vote-2)',
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
			'id'        => prefix_meta."date_no_answers",
			'std'       => "all",
			'type'      => "radio",
			'condition' => prefix_meta.'home_tabs:has(no-answers-2)',
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
			'name'      => esc_html__('Specific date for recent posts tab.','discy'),
			'desc'      => esc_html__('Select the specific date for recent posts tab.','discy'),
			'id'        => prefix_meta."date_recent_posts",
			'std'       => "all",
			'type'      => "radio",
			'condition' => prefix_meta.'home_tabs:has(recent-posts-2)',
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
			'name'      => esc_html__('Specific date for posts visited tab.','discy'),
			'desc'      => esc_html__('Select the specific date for posts visited tab.','discy'),
			'id'        => prefix_meta."date_posts_visited",
			'std'       => "all",
			'type'      => "radio",
			'condition' => prefix_meta.'home_tabs:has(posts-visited-2)',
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
			'id'        => prefix_meta."date_random_questions",
			'std'       => "all",
			'type'      => "radio",
			'condition' => prefix_meta.'home_tabs:has(random-2)',
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
			'id'        => prefix_meta."date_new_questions",
			'std'       => "all",
			'type'      => "radio",
			'condition' => prefix_meta.'home_tabs:has(new-questions-2)',
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
			'id'        => prefix_meta."date_sticky_questions",
			'std'       => "all",
			'type'      => "radio",
			'condition' => prefix_meta.'home_tabs:has(sticky-questions-2)',
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
			'id'        => prefix_meta."date_poll_questions",
			'std'       => "all",
			'type'      => "radio",
			'condition' => prefix_meta.'home_tabs:has(polls-2)',
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
			'id'        => prefix_meta."date_followed_questions",
			'std'       => "all",
			'type'      => "radio",
			'condition' => prefix_meta.'home_tabs:has(followed-2)',
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
			'id'        => prefix_meta."date_favorites_questions",
			'std'       => "all",
			'type'      => "radio",
			'condition' => prefix_meta.'home_tabs:has(favorites-2)',
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
			'end'  => 'end'
		);

		$meta_tabs_condition = apply_filters("discy_meta_tabs_condition",prefix_meta.'home_tabs:has(feed),'.prefix_meta.'home_tabs:has(recent-questions),'.prefix_meta.'home_tabs:has(questions-for-you),'.prefix_meta.'home_tabs:has(most-answers),'.prefix_meta.'home_tabs:has(question-bump),'.prefix_meta.'home_tabs:has(new-questions),'.prefix_meta.'home_tabs:has(sticky-questions),'.prefix_meta.'home_tabs:has(polls),'.prefix_meta.'home_tabs:has(followed),'.prefix_meta.'home_tabs:has(favorites),'.prefix_meta.'home_tabs:has(answers),'.prefix_meta.'home_tabs:has(answers-might-like),'.prefix_meta.'home_tabs:has(answers-for-you),'.prefix_meta.'home_tabs:has(most-visit),'.prefix_meta.'home_tabs:has(most-vote),'.prefix_meta.'home_tabs:has(random),'.prefix_meta.'home_tabs:has(no-answers),'.prefix_meta.'home_tabs:has(recent-posts),'.prefix_meta.'home_tabs:has(posts-visited),'.prefix_meta.'home_tabs:has(recent-questions-2),'.prefix_meta.'home_tabs:has(questions-for-you-2),'.prefix_meta.'home_tabs:has(most-answers-2),'.prefix_meta.'home_tabs:has(question-bump-2),'.prefix_meta.'home_tabs:has(new-questions-2),'.prefix_meta.'home_tabs:has(sticky-questions-2),'.prefix_meta.'home_tabs:has(polls-2),'.prefix_meta.'home_tabs:has(followed-2),'.prefix_meta.'home_tabs:has(favorites-2),'.prefix_meta.'home_tabs:has(answers-2),'.prefix_meta.'home_tabs:has(answers-might-like-2),'.prefix_meta.'home_tabs:has(answers-for-you-2),'.prefix_meta.'home_tabs:has(most-visit-2),'.prefix_meta.'home_tabs:has(most-vote-2),'.prefix_meta.'home_tabs:has(random-2),'.prefix_meta.'home_tabs:has(no-answers-2),'.prefix_meta.'home_tabs:has(recent-posts-2),'.prefix_meta.'home_tabs:has(posts-visited-2)');

		$options[] = array(
			'type'      => 'heading-2',
			'condition' => $meta_tabs_condition,
			'operator'  => 'or',
			'name'      => esc_html__('Custom setting for the slugs','discy')
		);

		$options[] = array(
			'name'      => esc_html__('Feed slug','discy'),
			'id'        => prefix_meta.'feed_slug',
			'std'       => 'feed',
			'condition' => prefix_meta.'home_tabs:has(feed)',
			'type'      => 'text'
		);

		$options[] = array(
			'name'      => esc_html__('Recent questions slug','discy'),
			'id'        => prefix_meta.'recent_questions_slug',
			'std'       => 'recent-questions',
			'condition' => prefix_meta.'home_tabs:has(recent-questions)',
			'type'      => 'text'
		);

		$options[] = array(
			'name'      => esc_html__('Questions for you slug','discy'),
			'id'        => prefix_meta.'questions_for_you_slug',
			'std'       => 'questions-for-you',
			'condition' => prefix_meta.'home_tabs:has(questions-for-you)',
			'type'      => 'text'
		);
		
		$options[] = array(
			'name'      => esc_html__('Most answered slug','discy'),
			'id'        => prefix_meta.'most_answers_slug',
			'std'       => 'most-answered',
			'condition' => prefix_meta.'home_tabs:has(most-answers)',
			'type'      => 'text'
		);
		
		$options[] = array(
			'name'      => esc_html__('Bump question slug','discy'),
			'id'        => prefix_meta.'question_bump_slug',
			'std'       => 'question-bump',
			'condition' => prefix_meta.'home_tabs:has(question-bump)',
			'type'      => 'text'
		);
		
		$options[] = array(
			'name'      => esc_html__('New questions slug','discy'),
			'id'        => prefix_meta.'question_new_slug',
			'std'       => 'new',
			'condition' => prefix_meta.'home_tabs:has(new-questions)',
			'type'      => 'text'
		);
		
		$options[] = array(
			'name'      => esc_html__('Question sticky slug','discy'),
			'id'        => prefix_meta.'question_sticky_slug',
			'std'       => 'sticky',
			'condition' => prefix_meta.'home_tabs:has(sticky-questions)',
			'type'      => 'text'
		);
		
		$options[] = array(
			'name'      => esc_html__('Question polls slug','discy'),
			'id'        => prefix_meta.'question_polls_slug',
			'std'       => 'polls',
			'condition' => prefix_meta.'home_tabs:has(polls)',
			'type'      => 'text'
		);
		
		$options[] = array(
			'name'      => esc_html__('Question followed slug','discy'),
			'id'        => prefix_meta.'question_followed_slug',
			'std'       => 'followed',
			'condition' => prefix_meta.'home_tabs:has(followed)',
			'type'      => 'text'
		);
		
		$options[] = array(
			'name'      => esc_html__('Question favorites slug','discy'),
			'id'        => prefix_meta.'question_favorites_slug',
			'std'       => 'favorites',
			'condition' => prefix_meta.'home_tabs:has(favorites)',
			'type'      => 'text'
		);
		
		$options[] = array(
			'name'      => esc_html__('Answers slug','discy'),
			'id'        => prefix_meta.'answers_slug',
			'std'       => 'answers',
			'condition' => prefix_meta.'home_tabs:has(answers)',
			'type'      => 'text'
		);
		
		$options[] = array(
			'name'      => esc_html__('Answers you might like slug','discy'),
			'id'        => prefix_meta.'answers_might_like_slug',
			'std'       => 'answers-might-like',
			'condition' => prefix_meta.'home_tabs:has(answers-might-like)',
			'type'      => 'text'
		);
		
		$options[] = array(
			'name'      => esc_html__('Answers for you slug','discy'),
			'id'        => prefix_meta.'answers_for_you_slug',
			'std'       => 'answers-for-you',
			'condition' => prefix_meta.'home_tabs:has(answers-for-you)',
			'type'      => 'text'
		);
		
		$options[] = array(
			'name'      => esc_html__('Most visited slug','discy'),
			'id'        => prefix_meta.'most_visit_slug',
			'std'       => 'most-visited',
			'condition' => prefix_meta.'home_tabs:has(most-visit)',
			'type'      => 'text'
		);
		
		$options[] = array(
			'name'      => esc_html__('Most voted slug','discy'),
			'id'        => prefix_meta.'most_vote_slug',
			'std'       => 'most-voted',
			'condition' => prefix_meta.'home_tabs:has(most-vote)',
			'type'      => 'text'
		);
		
		$options[] = array(
			'name'      => esc_html__('Random slug','discy'),
			'id'        => prefix_meta.'random_slug',
			'std'       => 'random',
			'condition' => prefix_meta.'home_tabs:has(random)',
			'type'      => 'text'
		);
		
		$options[] = array(
			'name'      => esc_html__('No answers slug','discy'),
			'id'        => prefix_meta.'no_answers_slug',
			'std'       => 'no-answers',
			'condition' => prefix_meta.'home_tabs:has(no-answers)',
			'type'      => 'text'
		);
		
		$options[] = array(
			'name'      => esc_html__('Recent posts slug','discy'),
			'id'        => prefix_meta.'recent_posts_slug',
			'std'       => 'recent-posts',
			'condition' => prefix_meta.'home_tabs:has(recent-posts)',
			'type'      => 'text'
		);
		
		$options[] = array(
			'name'      => esc_html__('Posts visited slug','discy'),
			'id'        => prefix_meta.'posts_visited_slug',
			'std'       => 'posts-visited',
			'condition' => prefix_meta.'home_tabs:has(posts-visited)',
			'type'      => 'text'
		);

		$options[] = array(
			'name'      => esc_html__('Feed with time slug','discy'),
			'id'        => prefix_meta.'feed_slug_2',
			'std'       => 'feed-time',
			'condition' => prefix_meta.'home_tabs:has(feed-2)',
			'type'      => 'text'
		);

		$options[] = array(
			'name'      => esc_html__('Recent questions with time slug','discy'),
			'id'        => prefix_meta.'recent_questions_slug_2',
			'std'       => 'recent-questions-time',
			'condition' => prefix_meta.'home_tabs:has(recent-questions-2)',
			'type'      => 'text'
		);

		$options[] = array(
			'name'      => esc_html__('Questions for you with time slug','discy'),
			'id'        => prefix_meta.'questions_for_you_slug_2',
			'std'       => 'questions-for-you-time',
			'condition' => prefix_meta.'home_tabs:has(questions-for-you-2)',
			'type'      => 'text'
		);
		
		$options[] = array(
			'name'      => esc_html__('Most answered with time slug','discy'),
			'id'        => prefix_meta.'most_answers_slug_2',
			'std'       => 'most-answered-time',
			'condition' => prefix_meta.'home_tabs:has(most-answers-2)',
			'type'      => 'text'
		);
		
		$options[] = array(
			'name'      => esc_html__('Bump question with time slug','discy'),
			'id'        => prefix_meta.'question_bump_slug_2',
			'std'       => 'question-bump-time',
			'condition' => prefix_meta.'home_tabs:has(question-bump-2)',
			'type'      => 'text'
		);
		
		$options[] = array(
			'name'      => esc_html__('New questions with time slug','discy'),
			'id'        => prefix_meta.'question_new_slug_2',
			'std'       => 'new-time',
			'condition' => prefix_meta.'home_tabs:has(new-questions-2)',
			'type'      => 'text'
		);
		
		$options[] = array(
			'name'      => esc_html__('Question sticky with time slug','discy'),
			'id'        => prefix_meta.'question_sticky_slug_2',
			'std'       => 'sticky-time',
			'condition' => prefix_meta.'home_tabs:has(sticky-questions-2)',
			'type'      => 'text'
		);
		
		$options[] = array(
			'name'      => esc_html__('Question polls with time slug','discy'),
			'id'        => prefix_meta.'question_polls_slug_2',
			'std'       => 'polls-time',
			'condition' => prefix_meta.'home_tabs:has(polls-2)',
			'type'      => 'text'
		);
		
		$options[] = array(
			'name'      => esc_html__('Question followed with time slug','discy'),
			'id'        => prefix_meta.'question_followed_slug_2',
			'std'       => 'followed-time',
			'condition' => prefix_meta.'home_tabs:has(followed-2)',
			'type'      => 'text'
		);
		
		$options[] = array(
			'name'      => esc_html__('Question favorites with time slug','discy'),
			'id'        => prefix_meta.'question_favorites_slug_2',
			'std'       => 'favorites-time',
			'condition' => prefix_meta.'home_tabs:has(favorites-2)',
			'type'      => 'text'
		);
		
		$options[] = array(
			'name'      => esc_html__('Answers with time slug','discy'),
			'id'        => prefix_meta.'answers_slug_2',
			'std'       => 'answers-time',
			'condition' => prefix_meta.'home_tabs:has(answers-2)',
			'type'      => 'text'
		);
		
		$options[] = array(
			'name'      => esc_html__('Answers you might like with time slug','discy'),
			'id'        => prefix_meta.'answers_might_like_slug_2',
			'std'       => 'answers-might-like-time',
			'condition' => prefix_meta.'home_tabs:has(answers-might-like-2)',
			'type'      => 'text'
		);
		
		$options[] = array(
			'name'      => esc_html__('Answers for you with time slug','discy'),
			'id'        => prefix_meta.'answers_for_you_slug_2',
			'std'       => 'answers-for-you-time',
			'condition' => prefix_meta.'home_tabs:has(answers-for-you-2)',
			'type'      => 'text'
		);
		
		$options[] = array(
			'name'      => esc_html__('Most visited with time slug','discy'),
			'id'        => prefix_meta.'most_visit_slug_2',
			'std'       => 'most-visited-time',
			'condition' => prefix_meta.'home_tabs:has(most-visit-2)',
			'type'      => 'text'
		);
		
		$options[] = array(
			'name'      => esc_html__('Most voted with time slug','discy'),
			'id'        => prefix_meta.'most_vote_slug_2',
			'std'       => 'most-voted-time',
			'condition' => prefix_meta.'home_tabs:has(most-vote-2)',
			'type'      => 'text'
		);
		
		$options[] = array(
			'name'      => esc_html__('Random with time slug','discy'),
			'id'        => prefix_meta.'random_slug_2',
			'std'       => 'random-time',
			'condition' => prefix_meta.'home_tabs:has(random-2)',
			'type'      => 'text'
		);
		
		$options[] = array(
			'name'      => esc_html__('No answers with time slug','discy'),
			'id'        => prefix_meta.'no_answers_slug_2',
			'std'       => 'no-answers-time',
			'condition' => prefix_meta.'home_tabs:has(no-answers-2)',
			'type'      => 'text'
		);
		
		$options[] = array(
			'name'      => esc_html__('Recent posts with time slug','discy'),
			'id'        => prefix_meta.'recent_posts_slug_2',
			'std'       => 'recent-posts-time',
			'condition' => prefix_meta.'home_tabs:has(recent-posts-2)',
			'type'      => 'text'
		);
		
		$options[] = array(
			'name'      => esc_html__('Posts visited with time slug','discy'),
			'id'        => prefix_meta.'posts_visited_slug_2',
			'std'       => 'posts-visited-time',
			'condition' => prefix_meta.'home_tabs:has(posts-visited-2)',
			'type'      => 'text'
		);

		$options = apply_filters('discy_meta_tabs',$options);
		
		$options[] = array(
			'type' => 'heading-2',
			'end'  => 'end'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => prefix_meta.'home_tabs:has(feed),'.prefix_meta.'home_tabs:has(recent-questions),'.prefix_meta.'home_tabs:has(questions-for-you),'.prefix_meta.'home_tabs:has(most-answers),'.prefix_meta.'home_tabs:has(question-bump),'.prefix_meta.'home_tabs:has(new-questions),'.prefix_meta.'home_tabs:has(sticky-questions),'.prefix_meta.'home_tabs:has(polls),'.prefix_meta.'home_tabs:has(followed),'.prefix_meta.'home_tabs:has(favorites),'.prefix_meta.'home_tabs:has(most-visit),'.prefix_meta.'home_tabs:has(most-vote),'.prefix_meta.'home_tabs:has(random),'.prefix_meta.'home_tabs:has(no-answers),'.prefix_meta.'home_tabs:has(feed-2),'.prefix_meta.'home_tabs:has(recent-questions-2),'.prefix_meta.'home_tabs:has(questions-for-you-2),'.prefix_meta.'home_tabs:has(most-answers-2),'.prefix_meta.'home_tabs:has(question-bump-2),'.prefix_meta.'home_tabs:has(new-questions-2),'.prefix_meta.'home_tabs:has(sticky-questions-2),'.prefix_meta.'home_tabs:has(polls-2),'.prefix_meta.'home_tabs:has(followed-2),'.prefix_meta.'home_tabs:has(favorites-2),'.prefix_meta.'home_tabs:has(most-visit-2),'.prefix_meta.'home_tabs:has(most-vote-2),'.prefix_meta.'home_tabs:has(random-2),'.prefix_meta.'home_tabs:has(no-answers-2)',
			'operator'  => 'or',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'name' => esc_html__('Custom setting for the questions','discy')
		);
		
		$options[] = array(
			'name' => esc_html__('Choose a custom setting for the questions','discy'),
			'id'   => prefix_meta.'custom_home_question',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => prefix_meta.'custom_home_question:not(0)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name'    => esc_html__('Question style','discy'),
			'desc'    => esc_html__('Choose question style from here.','discy'),
			'id'      => prefix_meta.'question_columns_h',
			'options' => array(
				'style_1' => esc_html__('1 column','discy'),
				'style_2' => esc_html__('2 columns','discy')." - ".esc_html__('Works with sidebar, full width, and left menu only.','discy'),
			),
			'std'   => 'style_1',
			'type'  => 'radio',
		);
		
		$options[] = array(
			'name'      => esc_html__("Activate the masonry style?","discy"),
			'id'        => prefix_meta.'masonry_style_h',
			'type'      => 'checkbox',
			'condition' => prefix_meta.'question_columns_h:is(style_2)',
		);
		
		$options[] = array(
			'name' => esc_html__('Activate the author image in questions loop?','discy'),
			'desc' => esc_html__('Enable or disable author image in questions loop?','discy'),
			'id'   => prefix_meta.'author_image_h',
			'std'  => "on",
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name' => esc_html__('Activate the vote in loop?','discy'),
			'desc' => esc_html__('Enable or disable vote in loop?','discy'),
			'id'   => prefix_meta.'vote_question_loop_h',
			'std'  => "on",
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name'      => esc_html__('Select ON to hide the dislike at questions loop','discy'),
			'desc'      => esc_html__('If you put it ON the dislike will not show.','discy'),
			'id'        => prefix_meta.'question_loop_dislike_h',
			'type'      => 'checkbox',
			'condition' => prefix_meta.'vote_question_loop_h:not(0)',
		);
		
		$options[] = array(
			'name' => esc_html__('Select ON to show the poll in questions loop','discy'),
			'id'   => prefix_meta.'question_poll_loop_h',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name' => esc_html__('Select ON to hide the excerpt in questions','discy'),
			'id'   => prefix_meta.'excerpt_questions_h',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => prefix_meta.'excerpt_questions_h:is(0)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name' => esc_html__('Excerpt question','discy'),
			'desc' => esc_html__('Put here the excerpt question.','discy'),
			'id'   => prefix_meta.'question_excerpt_h',
			'std'  => 40,
			'type' => 'text'
		);
		
		$options[] = array(
			'name' => esc_html__('Select ON to activate the read more button in questions','discy'),
			'id'   => prefix_meta.'read_more_question_h',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name'      => esc_html__('Select ON to activate the read more by jQuery in questions','discy'),
			'id'        => prefix_meta.'read_jquery_question_h',
			'type'      => 'checkbox',
			'condition' => prefix_meta.'read_more_question_h:not(0)',
		);
		
		$options[] = array(
			'name' => esc_html__('Select ON to activate to see some answers and add a new answer by jQuery in questions','discy'),
			'id'   => prefix_meta.'answer_question_jquery_h',
			'type' => 'checkbox',
		);
		
		$options[] = array(
			'name' => esc_html__('Activate the follow button at questions loop','discy'),
			'desc' => esc_html__('Select ON if you want to activate the follow button at questions loop.','discy'),
			'id'   => prefix_meta.'question_follow_loop_h',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);
		
		$options[] = array(
			'name' => esc_html__('Enable or disable Tags at loop?','discy'),
			'id'   => prefix_meta.'question_tags_loop_h',
			'std'  => 'on',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name' => esc_html__('Activate the answer at the loop by best answer, most voted, last answer or first answer','discy'),
			'id'   => prefix_meta.'question_answer_loop_h',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => prefix_meta.'question_answer_loop_h:not(0)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name'    => esc_html__('Answer type','discy'),
			'desc'    => esc_html__("Choose what's the answer you need to show from here.","discy"),
			'id'      => prefix_meta.'question_answer_show_h',
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
			'id'      => prefix_terms.'question_answer_place_h',
			'options' => array(
				'before' => esc_html__('Before question meta','discy'),
				'after'  => esc_html__('After question meta','discy'),
			),
			'std'     => 'after',
			'type'    => 'radio'
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);
		
		$options[] = array(
			'name'    => esc_html__('Select the meta options','discy'),
			'id'      => prefix_meta.'question_meta_h',
			'type'    => 'multicheck',
			'std'     => $question_meta_std,
			'options' => $question_meta_options
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
			'div'  => 'div',
			'end'  => 'end'
		);

		$options[] = array(
			'div'       => 'div',
			'condition' => prefix_meta.'home_tabs:has(recent-posts),'.prefix_meta.'home_tabs:has(recent-posts-2),'.prefix_meta.'home_tabs:has(posts-visited),'.prefix_meta.'home_tabs:has(posts-visited-2)',
			'operator'  => 'or',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'name' => esc_html__('Custom setting for the posts','discy')
		);
		
		$options[] = array(
			'name' => esc_html__('Choose a custom setting for the posts','discy'),
			'id'   => prefix_meta.'custom_home_blog',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => prefix_meta.'custom_home_blog:not(0)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name'    => esc_html__('Post style','discy'),
			'desc'    => esc_html__('Choose post style from here.','discy'),
			'id'      => prefix_meta.'post_style_h',
			'options' => array(
				'style_1' => esc_html__('1 column','discy'),
				'style_2' => esc_html__('List style','discy'),
				'style_3' => esc_html__('Columns','discy'),
			),
			'std'   => 'style_1',
			'type'  => 'radio',
		);
		
		$options[] = array(
			'name' => esc_html__('Hide the featured image in the loop','discy'),
			'desc' => esc_html__('Select ON to hide the featured image in the loop.','discy'),
			'id'   => prefix_meta.'featured_image_h',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'id'        => prefix_meta."sort_meta_title_image_h",
			'condition' => prefix_meta.'post_style_h:is(style_3)',
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
			'id'   => prefix_meta.'read_more_h',
			'std'  => "on",
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name' => esc_html__('Excerpt post','discy'),
			'desc' => esc_html__('Put here the excerpt post.','discy'),
			'id'   => prefix_meta.'post_excerpt_h',
			'std'  => 40,
			'type' => 'text'
		);
		
		$options[] = array(
			'name'    => esc_html__('Select the meta options','discy'),
			'id'      => prefix_meta.'post_meta_h',
			'type'    => 'multicheck',
			'std'     => $post_meta_std,
			'options' => $post_meta_options
		);
		
		$options[] = array(
			'name'      => esc_html__('Select the share options','discy'),
			'id'        => prefix_meta.'post_share_h',
			'condition' => prefix_meta.'post_style_h:not(style_3)',
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
			'div'  => 'div',
			'end'  => 'end'
		);

		$options[] = array(
			'div'       => 'div',
			'condition' => prefix_meta.'home_tabs:has(answers),'.prefix_meta.'home_tabs:has(answers-might-like),'.prefix_meta.'home_tabs:has(answers-for-you),'.prefix_meta.'home_tabs:has(answers-2),'.prefix_meta.'home_tabs:has(answers-might-like-2),'.prefix_meta.'home_tabs:has(answers-for-you-2)',
			'operator'  => 'or',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'name' => esc_html__('Custom setting for the answers','discy')
		);
		
		$options[] = array(
			'name'    => esc_html__('Order by','discy'),
			'desc'    => esc_html__('Select the answers order by.','discy'),
			'id'      => prefix_meta."orderby_answers_h",
			'std'     => "recent",
			'type'    => "radio",
			'options' => array(
				'recent' => esc_html__('Recent','discy'),
				'oldest' => esc_html__('Oldest','discy'),
				'votes'  => esc_html__('Voted','discy'),
			)
		);
		
		$options[] = array(
			'name' => esc_html__('Choose a custom setting for the answers','discy'),
			'id'   => prefix_meta.'custom_home_answer',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => prefix_meta.'custom_home_answer:not(0)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name' => esc_html__('Activate the author image in answers?','discy'),
			'desc' => esc_html__('Author image in answers enable or disable.','discy'),
			'id'   => prefix_meta.'answers_image_h',
			'std'  => "on",
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name' => esc_html__('Select ON to activate the vote at answers','discy'),
			'desc' => esc_html__('Select ON to enable the vote at the answers.','discy'),
			'id'   => prefix_meta.'active_vote_answer_h',
			'std'  => "on",
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name'      => esc_html__('Select ON to hide the dislike at answers','discy'),
			'desc'      => esc_html__('If you put it ON the dislike will not show.','discy'),
			'id'        => prefix_meta.'show_dislike_answers_h',
			'type'      => 'checkbox',
			'condition' => prefix_meta.'active_vote_answer_h:not(0)',
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
			'div'  => 'div',
			'end'  => 'end'
		);
		
		$options[] = array(
			'name'     => esc_html__('Blog setting','discy'),
			'id'       => 'loop_setting',
			'icon'     => 'admin-page',
			'type'     => 'heading',
			'template' => 'template-blog.php'
		);
		
		$options[] = array(
			'type' => 'heading-2'
		);

		$options[] = array(
			'name'    => esc_html__('Specific date.','discy'),
			'desc'    => esc_html__('Select the specific date.','discy'),
			'id'      => prefix_meta."specific_date_b",
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
		);
		
		$options[] = array(
			'name'    => esc_html__('Order by','discy'),
			'desc'    => esc_html__('Select the post order by.','discy'),
			'id'      => prefix_meta."orderby_post_b",
			'std'     => "recent",
			'type'    => "radio",
			'options' => array(
				'recent'  => esc_html__('Recent','discy'),
				'popular' => esc_html__('Most Commented','discy'),
				'random'  => esc_html__('Random','discy'),
			)
		);
		
		$options[] = array(
			'name'    => esc_html__('Order','discy'),
			'id'      => prefix_meta.'order_post',
			'std'     => "DESC",
			'type'    => 'radio',
			'options' => array(
				'DESC' => esc_html__('Descending','discy'),
				'ASC'  => esc_html__('Ascending','discy'),
			),
		);
		
		$options[] = array(
			'name'    => esc_html__('Display by','discy'),
			'id'      => prefix_meta."post_display_b",
			'type'    => 'select',
			'options' => array(
				'lasts'	             => esc_html__('Lasts','discy'),
				'single_category'    => esc_html__('Single category','discy'),
				'categories'         => esc_html__('Multiple categories','discy'),
				'exclude_categories' => esc_html__('Exclude categories','discy'),
				'custom_posts'	     => esc_html__('Custom posts','discy'),
			),
			'std'     => 'lasts',
		);
		
		$options[] = array(
			'name'      => esc_html__('Single category','discy'),
			'id'        => prefix_meta.'post_single_category_b',
			'type'      => 'select_category',
			'condition' => prefix_meta.'post_display_b:is(single_category)',
		);
		
		$options[] = array(
			'name'      => esc_html__('Post categories','discy'),
			'desc'      => esc_html__('Select the post categories.','discy'),
			'id'        => prefix_meta."post_categories_b",
			'type'      => 'multicheck_category',
			'condition' => prefix_meta.'post_display_b:is(categories)',
		);
		
		$options[] = array(
			'name'      => esc_html__('Post exclude categories','discy'),
			'desc'      => esc_html__('Select the post exclude categories.','discy'),
			'id'        => prefix_meta."post_exclude_categories_b",
			'type'      => 'multicheck_category',
			'condition' => prefix_meta.'post_display_b:is(exclude_categories)',
		);
		
		$options[] = array(
			'name'      => esc_html__('Post ids','discy'),
			'desc'      => esc_html__('Type the post ids.','discy'),
			'id'        => prefix_meta."post_posts_b",
			'type'      => 'text',
			'condition' => prefix_meta.'post_display_b:is(custom_posts)',
		);
		
		$options[] = array(
			'name' => esc_html__('Choose a custom setting for this blog page','discy'),
			'id'   => prefix_meta.'custom_blog_setting',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => prefix_meta.'custom_blog_setting:not(0)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name'    => esc_html__('Post style','discy'),
			'desc'    => esc_html__('Choose post style from here.','discy'),
			'id'      => prefix_meta.'post_style_b',
			'options' => array(
				'style_1' => esc_html__('1 column','discy'),
				'style_2' => esc_html__('List style','discy'),
				'style_3' => esc_html__('Columns','discy'),
			),
			'std'   => 'style_1',
			'type'  => 'radio',
		);
		
		$options[] = array(
			'name' => esc_html__('Hide the featured image in the loop','discy'),
			'desc' => esc_html__('Select ON to hide the featured image in the loop.','discy'),
			'id'   => prefix_meta.'featured_image_b',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'id'        => prefix_meta."sort_meta_title_image_b",
			'condition' => prefix_meta.'post_style_b:is(style_3)',
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
			'id'   => prefix_meta.'read_more_b',
			'std'  => "on",
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name' => esc_html__('Excerpt post','discy'),
			'desc' => esc_html__('Put here the excerpt post.','discy'),
			'id'   => prefix_meta.'post_excerpt_b',
			'std'  => 40,
			'type' => 'text'
		);
		
		$options[] = array(
			'name' => esc_html__('Posts number','discy'),
			'desc' => esc_html__('put the posts number','discy'),
			'id'   => prefix_meta.'post_number_b',
			'type' => 'text',
			'std'  => "10"
		);
		
		$options[] = array(
			'name'    => esc_html__('Pagination style','discy'),
			'desc'    => esc_html__('Choose pagination style from here.','discy'),
			'id'      => prefix_meta.'post_pagination_b',
			'options' => array(
				'standard'        => esc_html__('Standard','discy'),
				'pagination'      => esc_html__('Pagination','discy'),
				'load_more'       => esc_html__('Load more','discy'),
				'infinite_scroll' => esc_html__('Infinite scroll','discy'),
				'none'            => esc_html__('None','discy'),
			),
			'std'     => 'pagination',
			'type'    => 'radio',
		);
		
		$options[] = array(
			'name'    => esc_html__('Select the meta options','discy'),
			'id'      => prefix_meta.'post_meta_b',
			'type'    => 'multicheck',
			'std'     => $post_meta_std,
			'options' => $post_meta_options
		);
		
		$options[] = array(
			'name'      => esc_html__('Select the share options','discy'),
			'id'        => prefix_meta.'post_share_b',
			'condition' => prefix_meta.'post_style_b:not(style_3)',
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
			'name'     => esc_html__('Comment settings','discy'),
			'id'       => 'comments_settings',
			'icon'     => 'admin-comments',
			'type'     => 'heading',
			'template' => 'template-comments.php'
		);
		
		$options[] = array(
			'type' => 'heading-2'
		);
		
		$options[] = array(
			'name'    => esc_html__('Comment type','discy'),
			'desc'    => esc_html__('Select the comment type.','discy'),
			'id'      => prefix_meta."comment_type",
			'std'     => "answers",
			'type'    => "radio",
			'options' => array(
				'answers'  => esc_html__('Answers','discy'),
				'comments' => esc_html__('Comments','discy'),
			)
		);

		$options[] = array(
			'name'    => esc_html__('Specific date.','discy'),
			'desc'    => esc_html__('Select the specific date.','discy'),
			'id'      => prefix_meta."specific_date_c",
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
		);
		
		$options[] = array(
			'name'    => esc_html__('Order by','discy'),
			'desc'    => esc_html__('Select the comments order by.','discy'),
			'id'      => prefix_meta."orderby_answers_a",
			'std'     => "date",
			'type'    => "radio",
			'options' => array(
				'date'  => esc_html__('Date','discy'),
				'votes' => esc_html__('Voted - Works at answers only.','discy'),
			)
		);
		
		$options[] = array(
			'name'    => esc_html__('Order','discy'),
			'id'      => prefix_meta.'order_answers',
			'std'     => "DESC",
			'type'    => 'radio',
			'options' => array(
				'DESC' => esc_html__('Descending','discy'),
				'ASC'  => esc_html__('Ascending','discy'),
			),
		);
		
		$options[] = array(
			'name' => esc_html__('Choose a custom setting for the comments','discy'),
			'id'   => prefix_meta.'custom_answers',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => prefix_meta.'custom_answers:not(0)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name' => esc_html__('Comments per page','discy'),
			'desc' => esc_html__('put the comments per page','discy'),
			'id'   => prefix_meta.'answers_number',
			'type' => 'text',
			'std'  => "10"
		);
		
		$options[] = array(
			'name'    => esc_html__('Pagination style','discy'),
			'desc'    => esc_html__('Choose pagination style from here.','discy'),
			'id'      => prefix_meta.'answers_pagination',
			'options' => array(
				'standard'        => esc_html__('Standard','discy'),
				'pagination'      => esc_html__('Pagination','discy'),
				'load_more'       => esc_html__('Load more','discy'),
				'infinite_scroll' => esc_html__('Infinite scroll','discy'),
				'none'            => esc_html__('None','discy'),
			),
			'std'     => 'pagination',
			'type'    => 'radio',
		);
		
		$options[] = array(
			'name' => esc_html__('Activate the author image in comments?','discy'),
			'desc' => esc_html__('Author image in comments enable or disable.','discy'),
			'id'   => prefix_meta.'answers_image_a',
			'std'  => "on",
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => prefix_meta.'comment_type:not(comments)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name' => esc_html__('Select ON to activate the vote at answers','discy'),
			'desc' => esc_html__('Select ON to enable the vote at the answers.','discy'),
			'id'   => prefix_meta.'active_vote_answer_a',
			'std'  => "on",
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name'      => esc_html__('Select ON to hide the dislike at answers','discy'),
			'desc'      => esc_html__('If you put it ON the dislike will not show.','discy'),
			'id'        => prefix_meta.'show_dislike_answers_a',
			'type'      => 'checkbox',
			'condition' => prefix_meta.'active_vote_answer_a:not(0)',
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
			'name'     => esc_html__('Groups settings','discy'),
			'id'       => 'groupss_settings',
			'icon'     => 'groups',
			'type'     => 'heading',
			'template' => 'template-groups.php'
		);
		
		$options[] = array(
			'type' => 'heading-2'
		);
		
		$options[] = array(
			'name'    => esc_html__('Display by','discy'),
			'desc'    => esc_html__('Select the groups display by.','discy'),
			'id'      => prefix_meta.'group_display_g',
			'options' => array(
				'all'     => esc_html__('All groups','discy'),
				'private' => esc_html__('Private groups','discy'),
				'public'  => esc_html__('Public groups','discy'),
			),
			'std'     => 'all',
			'type'    => 'radio',
		);

		$options[] = array(
			'name'    => esc_html__('Order by','discy'),
			'desc'    => esc_html__('Select the groups order by.','discy'),
			'id'      => prefix_meta.'group_order_g',
			'options' => array(
				'date'  => esc_html__('Date','discy'),
				'users' => esc_html__('Users','discy'),
				'posts' => esc_html__('Posts','discy'),
			),
			'std'     => 'date',
			'type'    => 'radio',
		);
		
		$options[] = array(
			'name' => esc_html__('Choose a custom setting for this groups page','discy'),
			'id'   => prefix_meta.'custom_group_setting',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => prefix_meta.'custom_group_setting:not(0)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name' => esc_html__('Groups per page','discy'),
			'desc' => esc_html__('put the groups per page','discy'),
			'id'   => prefix_meta.'group_number',
			'type' => 'text',
			'std'  => "10"
		);
		
		$options[] = array(
			'name'    => esc_html__('Pagination style','discy'),
			'desc'    => esc_html__('Choose pagination style from here.','discy'),
			'id'      => prefix_meta.'group_pagination',
			'options' => array(
				'standard'        => esc_html__('Standard','discy'),
				'pagination'      => esc_html__('Pagination','discy'),
				'load_more'       => esc_html__('Load more','discy'),
				'infinite_scroll' => esc_html__('Infinite scroll','discy'),
				'none'            => esc_html__('None','discy'),
			),
			'std'     => 'pagination',
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
			'name'     => esc_html__('Question settings','discy'),
			'id'       => 'questions_settings',
			'icon'     => 'editor-help',
			'type'     => 'heading',
			'template' => 'template-question.php'
		);
		
		$options[] = array(
			'type' => 'heading-2'
		);
		
		$options[] = array(
			'name'    => esc_html__('Display questions by','discy'),
			'desc'    => esc_html__('Select the question display by.','discy'),
			'id'      => prefix_meta."orderby_question_q",
			'std'     => "feed",
			'type'    => "select",
			'options' => array(
				'feed'          => esc_html__('Custom Feed','discy'),
				'recent'        => esc_html__('Recent','discy'),
				'popular'       => esc_html__('Most Answered','discy'),
				'random'        => esc_html__('Random','discy'),
				'most_visited'  => esc_html__('Most Visited','discy'),
				'most_voted'    => esc_html__('Most Voted','discy'),
				'no_answer'     => esc_html__('No Answers','discy'),
				'question_bump' => esc_html__('Bump Question','discy'),
				'new'           => esc_html__('New Questions - Without Sticky','discy'),
				'sticky'        => esc_html__('Sticky Questions','discy'),
				'polls'         => esc_html__('Poll Questions','discy'),
				'followed'      => esc_html__('Followed Questions','discy'),
				'favorites'     => esc_html__('Favorites Questions','discy'),
			)
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => prefix_meta.'orderby_question_q:is(feed)',
			'name'      => esc_html__('Custom setting for feed','discy'),
			'type'      => 'heading-2'
		);

		$options = apply_filters('discy_options_question_feed',$options);

		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);

		$options[] = array(
			'name'    => esc_html__('Specific date.','discy'),
			'desc'    => esc_html__('Select the specific date.','discy'),
			'id'      => prefix_meta."specific_date_q",
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
		);
		
		$options[] = array(
			'name'    => esc_html__('Order','discy'),
			'id'      => prefix_meta.'order_question',
			'std'     => "DESC",
			'type'    => 'radio',
			'options' => array(
				'DESC' => esc_html__('Descending','discy'),
				'ASC'  => esc_html__('Ascending','discy'),
			),
		);
		
		$options[] = array(
			'name'    => esc_html__('Display by','discy'),
			'id'      => prefix_meta."question_display_q",
			'type'    => 'select',
			'options' => array(
				'lasts'	             => esc_html__('Lasts','discy'),
				'single_category'    => esc_html__('Single category','discy'),
				'categories'         => esc_html__('Multiple categories','discy'),
				'exclude_categories' => esc_html__('Exclude categories','discy'),
				'custom_posts'	     => esc_html__('Custom questions','discy'),
			),
			'std'     => 'lasts',
		);
		
		$options[] = array(
			'name'      => esc_html__('Single category','discy'),
			'id'        => prefix_meta.'question_single_category_q',
			'type'      => 'select_category',
			'condition' => prefix_meta.'question_display_q:is(single_category)',
			'taxonomy'  => 'question-category',
		);
		
		$options[] = array(
			'name'      => esc_html__('Question categories','discy'),
			'desc'      => esc_html__('Select the question categories.','discy'),
			'id'        => prefix_meta."question_categories_q",
			'type'      => 'multicheck_category',
			'condition' => prefix_meta.'question_display_q:is(categories)',
			'taxonomy'  => 'question-category',
		);
		
		$options[] = array(
			'name'      => esc_html__('Exclude Question categories','discy'),
			'desc'      => esc_html__('Select the exclude question categories.','discy'),
			'id'        => prefix_meta."question_exclude_categories_q",
			'type'      => 'multicheck_category',
			'condition' => prefix_meta.'question_display_q:is(exclude_categories)',
			'taxonomy'  => 'question-category',
		);
		
		$options[] = array(
			'name'      => esc_html__('Question ids','discy'),
			'desc'      => esc_html__('Type the question ids.','discy'),
			'id'        => prefix_meta."question_questions_q",
			'condition' => prefix_meta.'question_display_q:is(custom_posts)',
			'type'      => 'text',
		);
		
		$options[] = array(
			'name' => esc_html__('Choose a custom setting for this questions page','discy'),
			'id'   => prefix_meta.'custom_question_setting',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => prefix_meta.'custom_question_setting:not(0)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name' => esc_html__('Questions per page','discy'),
			'desc' => esc_html__('put the questions per page','discy'),
			'id'   => prefix_meta.'question_number',
			'type' => 'text',
			'std'  => "10"
		);
		
		$options[] = array(
			'name'    => esc_html__('Pagination style','discy'),
			'desc'    => esc_html__('Choose pagination style from here.','discy'),
			'id'      => prefix_meta.'question_pagination',
			'options' => array(
				'standard'        => esc_html__('Standard','discy'),
				'pagination'      => esc_html__('Pagination','discy'),
				'load_more'       => esc_html__('Load more','discy'),
				'infinite_scroll' => esc_html__('Infinite scroll','discy'),
				'none'            => esc_html__('None','discy'),
			),
			'std'     => 'pagination',
			'type'    => 'radio',
		);
		
		$options[] = array(
			'name'    => esc_html__('Question style','discy'),
			'desc'    => esc_html__('Choose question style from here.','discy'),
			'id'      => prefix_meta.'question_columns',
			'options' => array(
				'style_1' => esc_html__('1 column','discy'),
				'style_2' => esc_html__('2 columns','discy')." - ".esc_html__('Works with sidebar, full width, and left menu only.','discy'),
			),
			'std'     => 'style_1',
			'type'    => 'radio',
		);
		
		$options[] = array(
			'name'      => esc_html__("Activate the masonry style?","discy"),
			'id'        => prefix_meta.'masonry_style',
			'type'      => 'checkbox',
			'condition' => prefix_meta.'question_columns:is(style_2)',
		);
		
		$options[] = array(
			'name' => esc_html__('Activate the author image in questions loop?','discy'),
			'desc' => esc_html__('Enable or disable author image in questions loop?','discy'),
			'id'   => prefix_meta.'author_image',
			'std'  => "on",
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name' => esc_html__('Activate the vote in loop?','discy'),
			'desc' => esc_html__('Enable or disable vote in loop?','discy'),
			'id'   => prefix_meta.'vote_question_loop',
			'std'  => "on",
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name'      => esc_html__('Select ON to hide the dislike at questions loop','discy'),
			'desc'      => esc_html__('If you put it ON the dislike will not show.','discy'),
			'id'        => prefix_meta.'question_loop_dislike',
			'type'      => 'checkbox',
			'condition' => prefix_meta.'vote_question_loop:not(0)',
		);
		
		$options[] = array(
			'name' => esc_html__('Select ON to show the poll in questions loop','discy'),
			'id'   => prefix_meta.'question_poll_loop',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name' => esc_html__('Select ON to hide the excerpt in questions','discy'),
			'id'   => prefix_meta.'excerpt_questions',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => prefix_meta.'excerpt_questions:is(0)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name' => esc_html__('Excerpt question','discy'),
			'desc' => esc_html__('Put here the excerpt question.','discy'),
			'id'   => prefix_meta.'question_excerpt',
			'std'  => 40,
			'type' => 'text'
		);
		
		$options[] = array(
			'name' => esc_html__('Select ON to activate the read more button in questions','discy'),
			'id'   => prefix_meta.'read_more_question',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name'      => esc_html__('Select ON to activate the read more by jQuery in questions','discy'),
			'id'        => prefix_meta.'read_jquery_question',
			'type'      => 'checkbox',
			'condition' => prefix_meta.'read_more_question:not(0)',
		);
		
		$options[] = array(
			'name' => esc_html__('Select ON to activate to see some answers and add a new answer by jQuery in questions','discy'),
			'id'   => prefix_meta.'answer_question_jquery',
			'type' => 'checkbox',
		);
		
		$options[] = array(
			'name' => esc_html__('Activate the follow button at questions loop','discy'),
			'desc' => esc_html__('Select ON if you want to activate the follow button at questions loop.','discy'),
			'id'   => prefix_meta.'question_follow_loop',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);
		
		$options[] = array(
			'name' => esc_html__('Enable or disable Tags at loop?','discy'),
			'id'   => prefix_meta.'question_tags_loop',
			'std'  => 'on',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'name' => esc_html__('Activate the answer at the loop by best answer, most voted, last answer or first answer','discy'),
			'id'   => prefix_meta.'question_answer_loop',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => prefix_meta.'question_answer_loop:not(0)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name'    => esc_html__('Answer type','discy'),
			'desc'    => esc_html__("Choose what's the answer you need to show from here.","discy"),
			'id'      => prefix_meta.'question_answer_show',
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
			'id'      => prefix_terms.'question_answer_place',
			'options' => array(
				'before' => esc_html__('Before question meta','discy'),
				'after'  => esc_html__('After question meta','discy'),
			),
			'std'     => 'after',
			'type'    => 'radio'
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);
		
		$options[] = array(
			'name'    => esc_html__('Select the meta options','discy'),
			'id'      => prefix_meta.'question_meta_q',
			'type'    => 'multicheck',
			'std'     => $question_meta_std,
			'options' => $question_meta_options
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
			'name'     => esc_html__('FAQ settings','discy'),
			'id'       => 'faqs_settings',
			'icon'     => 'info',
			'type'     => 'heading',
			'template' => 'template-faqs.php'
		);
		
		$options[] = array(
			'type' => 'heading-2'
		);
		
		$options[] = array(
			'id'        => prefix_meta."faqs",
			'type'      => "elements",
			'button'    => esc_html__('Add a new faq','discy'),
			'not_theme' => 'not',
			'hide'      => "yes",
			'options'   => array(
				array(
					"type" => "text",
					"id"   => "text",
					"name" => esc_html__('Title','discy'),
				),
				array(
					"type" => "textarea",
					"id"   => "textarea",
					"name" => esc_html__('Content','discy'),
				),
			),
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'end'  => 'end'
		);
		
		$options[] = array(
			'name'     => esc_html__('User settings','discy'),
			'id'       => 'users_settings',
			'icon'     => 'admin-users',
			'type'     => 'heading',
			'template' => 'template-users.php'
		);
		
		$options[] = array(
			'type' => 'heading-2'
		);
		
		$options[] = array(
			'name' => esc_html__('Show search at users page','discy'),
			'desc' => esc_html__('Show search at users page from the breadcrumb','discy'),
			'id'   => prefix_meta."user_search",
			'type' => 'checkbox',
			'std'  => 'on',
		);
		
		$options[] = array(
			'name' => esc_html__('Show filter at users page','discy'),
			'desc' => esc_html__('Show filter at users page from the breadcrumb','discy'),
			'id'   => prefix_meta."user_filter",
			'type' => 'checkbox',
			'std'  => 'on',
		);
		
		$options[] = array(
			'name' => esc_html__('Users per page','discy'),
			'desc' => esc_html__('Put the users per page.','discy'),
			'id'   => prefix_meta.'users_per_page',
			'std'  => '10',
			'type' => 'text'
		);
		
		$options[] = array(
			'name'    => esc_html__('Choose the user roles show','discy'),
			'id'      => prefix_meta.'user_group',
			'type'    => 'multicheck',
			'std'     => array("editor","administrator","author","contributor","subscriber"),
			'options' => discy_options_roles(),
		);
		
		$options[] = array(
			'name'    => esc_html__('Order by','discy'),
			'id'      => prefix_meta.'user_sort',
			'std'     => "register",
			'type'    => 'select',
			'options' => array(
				'user_registered' => esc_html__('Register','discy'),
				'display_name'    => esc_html__('Name','discy'),
				'ID'              => esc_html__('ID','discy'),
				'question_count'  => esc_html__('Questions','discy'),
				'answers'         => esc_html__('Answers','discy'),
				'the_best_answer' => esc_html__('Best Answers','discy'),
				'points'          => esc_html__('Points','discy'),
				'post_count'      => esc_html__('Posts','discy'),
				'comments'        => esc_html__('Comments','discy'),
				'followers'       => esc_html__('Followers','discy'),
			),
		);
		
		$options[] = array(
			'name'    => esc_html__('Users style','discy'),
			'desc'    => esc_html__('Choose the users style.','discy'),
			'id'      => prefix_meta.'user_style',
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
			'id'        => prefix_meta.'masonry_user_style',
			'type'      => 'checkbox',
			'condition' => prefix_meta.'user_style:is(small_grid),'.prefix_meta.'user_style:is(columns),'.prefix_meta.'user_style:is(small),'.prefix_meta.'user_style:is(grid)',
			'operator'  => 'or',
		);
		
		$options[] = array(
			'name'    => esc_html__('Order','discy'),
			'id'      => prefix_meta.'user_order',
			'std'     => "DESC",
			'type'    => 'radio',
			'options' => array(
				'DESC' => esc_html__('Descending','discy'),
				'ASC'  => esc_html__('Ascending','discy'),
			),
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'end'  => 'end'
		);
		
		$options[] = array(
			'name'     => esc_html__('Category settings','discy'),
			'id'       => 'categories_settings',
			'icon'     => 'category',
			'type'     => 'heading',
			'template' => 'template-categories.php'
		);
		
		$options[] = array(
			'type' => 'heading-2'
		);
		
		$options[] = array(
			'name' => esc_html__('Show search at categories page','discy'),
			'desc' => esc_html__('Show search at categories page from the breadcrumb','discy'),
			'id'   => prefix_meta."cat_search",
			'type' => 'checkbox',
			'std'  => 'on',
		);
		
		$options[] = array(
			'name' => esc_html__('Show filter at categories page','discy'),
			'desc' => esc_html__('Show filter at categories page from the breadcrumb','discy'),
			'id'   => prefix_meta."cat_filter",
			'type' => 'checkbox',
			'std'  => 'on',
		);
		
		$options[] = array(
			'name' => esc_html__('Categories per page','discy'),
			'desc' => esc_html__('Put the categories per page.','discy'),
			'id'   => prefix_meta.'cats_per_page',
			'std'  => '50',
			'type' => 'text'
		);
		
		$options[] = array(
			'name'    => esc_html__('Categories type','discy'),
			'id'      => prefix_meta.'cats_tax',
			'std'     => "question",
			'type'    => 'radio',
			'options' => array(
				'question' => esc_html__('Question categories','discy'),
				'post'     => esc_html__('Post categories','discy'),
			),
		);

		$options[] = array(
			'name'      => esc_html__('Categories style at categories page','discy'),
			'desc'      => esc_html__('Choose the categories style.','discy'),
			'id'        => prefix_meta.'cat_style_pages',
			'options'   => array(
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
			'std'       => 'simple_follow',
			'condition' => prefix_meta.'cats_tax:is(question)',
			'type'      => 'radio'
		);
		
		$options[] = array(
			'name'    => esc_html__('Order by','discy'),
			'id'      => prefix_meta.'cat_sort',
			'std'     => "count",
			'type'    => 'radio',
			'options' => array(
				'count'     => esc_html__('Popular','discy'),
				'followers' => esc_html__('Followers','discy'),
				'name'      => esc_html__('Name','discy'),
			),
		);
		
		$options[] = array(
			'name'    => esc_html__('Order','discy'),
			'id'      => prefix_meta.'cat_order',
			'std'     => "DESC",
			'type'    => 'radio',
			'options' => array(
				'DESC' => esc_html__('Descending','discy'),
				'ASC'  => esc_html__('Ascending','discy'),
			),
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'end'  => 'end'
		);
		
		$options[] = array(
			'name'     => esc_html__('Tag settings','discy'),
			'id'       => 'tags_settings',
			'icon'     => 'tag',
			'type'     => 'heading',
			'template' => 'template-tags.php'
		);
		
		$options[] = array(
			'type' => 'heading-2'
		);
		
		$options[] = array(
			'name' => esc_html__('Show search at tags page','discy'),
			'desc' => esc_html__('Show search at tags page from the breadcrumb','discy'),
			'id'   => prefix_meta."tag_search",
			'type' => 'checkbox',
			'std'  => 'on',
		);
		
		$options[] = array(
			'name' => esc_html__('Show filter at tags page','discy'),
			'desc' => esc_html__('Show filter at tags page from the breadcrumb','discy'),
			'id'   => prefix_meta."tag_filter",
			'type' => 'checkbox',
			'std'  => 'on',
		);
		
		$options[] = array(
			'name' => esc_html__('Tags per page','discy'),
			'desc' => esc_html__('Put the tags per page.','discy'),
			'id'   => prefix_meta.'tags_per_page',
			'std'  => '50',
			'type' => 'text'
		);
		
		$options[] = array(
			'name'    => esc_html__('Tags type','discy'),
			'id'      => prefix_meta.'tags_tax',
			'std'     => "question",
			'type'    => 'radio',
			'options' => array(
				'question' => esc_html__('Question tags','discy'),
				'post'     => esc_html__('Post tags','discy'),
			),
		);
		
		$options[] = array(
			'name'    => esc_html__('Order by','discy'),
			'id'      => prefix_meta.'tag_sort',
			'std'     => "count",
			'type'    => 'radio',
			'options' => array(
				'count'     => esc_html__('Popular','discy'),
				'followers' => esc_html__('Followers','discy'),
				'name'      => esc_html__('Name','discy'),
			),
		);
		
		$options[] = array(
			'name'    => esc_html__('Tags style','discy'),
			'desc'    => esc_html__('Choose the tags style.','discy'),
			'id'      => prefix_meta.'tag_style',
			'options' => array(
				'simple_follow' => esc_html__('Simple with follow','discy'),
				'advanced'      => esc_html__('Advanced','discy'),
				'simple'        => esc_html__('Simple','discy'),
			),
			'std'     => 'simple_follow',
			'type'    => 'radio'
		);
		
		$options[] = array(
			'name'    => esc_html__('Order','discy'),
			'id'      => prefix_meta.'tag_order',
			'std'     => "DESC",
			'type'    => 'radio',
			'options' => array(
				'DESC' => esc_html__('Descending','discy'),
				'ASC'  => esc_html__('Ascending','discy'),
			),
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'end'  => 'end'
		);
		
		$options[] = array(
			'name'     => esc_html__('Sitemap settings','discy'),
			'id'       => 'sitemaps_settings',
			'icon'     => 'portfolio',
			'type'     => 'heading',
			'template' => 'template-sitemaps.php'
		);
		
		$options[] = array(
			'type' => 'heading-2'
		);
		
		$options[] = array(
			'name'    => esc_html__('Choose sections to show','discy'),
			'id'      => prefix_meta.'sitemaps_sections',
			'type'    => 'multicheck',
			'std'     => array("posts","archive_m","archive_y","categories","pages","authors","tags"),
			'options' => array(
				'posts'      => 'Latest Posts',
				'archive_m'  => 'Archive by Month',
				'archive_y'  => 'Archive by Year',
				'categories' => 'Categories',
				'pages'      => 'Pages',
				'authors'    => 'Authors',
				'tags'       => 'Tags',
			),
		);
		
		$options[] = array(
			'name'    => esc_html__('Toggle or accordion?','discy'),
			'id'      => prefix_meta."toggle_accordion",
			'type'    => 'select',
			'options' => array(
				'toggle'	=> esc_html__('Toggle','discy'),
				'accordion'	=> esc_html__('Accordion','discy'),
			),
			'std'     => 'toggle',
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'end'  => 'end'
		);

		$options[] = array(
			'name'     => esc_html__('Badges & Point settings','discy'),
			'id'      => 'badges',
			'icon'    => 'star-filled',
			'type'     => 'heading',
			'template' => 'template-badges.php'
		);
		
		$options[] = array(
			'type' => 'heading-2'
		);
		
		$options[] = array(
			'name'    => esc_html__('Points columns?','discy'),
			'id'      => prefix_meta."badges_points_columns",
			'type'    => 'radio',
			'options' => array(
				'2col'	=> esc_html__('2 columns','discy'),
				'3col'	=> esc_html__('3 columns','discy'),
			),
			'std'     => '2col',
		);

		$options = apply_filters('discy_options_after_badges',$options);
		
		$options[] = array(
			'type' => 'heading-2',
			'end'  => 'end'
		);
		
		$options[] = array(
			'name'     => esc_html__('Landing settings','discy'),
			'id'       => 'landing_settings',
			'icon'     => 'portfolio',
			'type'     => 'heading',
			'template' => 'template-landing.php'
		);
		
		$options[] = array(
			'type' => 'heading-2'
		);

		$options[] = array(
			'name' => esc_html__('Custom logo for the landing page','discy'),
			'desc' => esc_html__('Select ON to set the custom logo for the landing page.','discy'),
			'id'   => prefix_meta.'custom_logo',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'type'      => 'heading-2',
			'condition' => prefix_meta.'custom_logo:not(0)',
			'div'       => 'div'
		);

		$options[] = array(
			'name'    => esc_html__('Logo for the landing page','discy'),
			'id'      => prefix_meta.'logo_landing',
			'type'    => 'upload',
			'options' => array("height" => prefix_meta."logo_landing_height","width" => prefix_meta."logo_landing_width"),
		);

		$options[] = array(
			'name' => esc_html__('Logo retina for the landing popup','discy'),
			'id'   => prefix_meta.'logo_landing_retina',
			'type' => 'upload'
		);
		
		$options[] = array(
			'name' => esc_html__('Logo height','discy'),
			"id"   => prefix_meta."logo_landing_height",
			"type" => "sliderui",
			'std'  => '45',
			"step" => "1",
			"min"  => "0",
			"max"  => "80"
		);
		
		$options[] = array(
			'name' => esc_html__('Logo width','discy'),
			"id"   => prefix_meta."logo_landing_width",
			"type" => "sliderui",
			'std'  => '137',
			"step" => "1",
			"min"  => "0",
			"max"  => "170"
		);

		$options[] = array(
			'type' => 'heading-2',
			'end'  => 'end',
			'div'  => 'div'
		);
		
		$options[] = array(
			'name'    => esc_html__('Add from here the page to work when the landing page is selected at the home page, and the user is already logged in.','discy'),
			'id'      => prefix_meta.'home_page',
			'type'    => 'select',
			'options' => $options_pages
		);
		
		$options[] = array(
			'name'    => esc_html__('Page style','discy'),
			'desc'    => esc_html__('Choose page style from here.','discy'),
			'id'      => prefix_meta.'register_style',
			'options' => array(
				'style_1' => 'Style 1',
				'style_2' => 'Style 2',
			),
			'std'     => 'style_1',
			'type'    => 'radio'
		);
		
		$options[] = array(
			'name'    => esc_html__('Upload the background','discy'),
			'desc'    => esc_html__('Upload the background for the un-register page','discy'),
			'id'      => prefix_meta.'register_background',
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
			"id"   => prefix_meta."register_opacity",
			"type" => "sliderui",
			'std'  => 30,
			"step" => "5",
			"min"  => "0",
			"max"  => "100"
		);
		
		$options[] = array(
			'name'    => esc_html__("Choose from here which menu will show for un-registered users.","discy"),
			'id'      => prefix_meta.'register_menu',
			'type'    => 'select',
			'options' => $menus
		);
		
		$options[] = array(
			'name' => esc_html__('The headline','discy'),
			'desc' => esc_html__('Type the Headline from here','discy'),
			'id'   => prefix_meta.'register_headline',
			'type' => 'text',
			'std'  => "Join the world's  biggest Q & A network!"
		);
		
		$options[] = array(
			'name' => esc_html__('The paragraph','discy'),
			'desc' => esc_html__('Type the Paragraph from here','discy'),
			'id'   => prefix_meta.'register_paragraph',
			'type' => 'textarea',
			'std'  => "Login to our social questions & Answers Engine to ask questions answer people's questions & connect with other people."
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'end'  => 'end'
		);

		$options = apply_filters('discy_options_after_landing',$options);
	}
	
	if (discy_is_post_type(array("post","page","question"))) {
		$options[] = array(
			'name' => esc_html__('Custom page settings','discy'),
			'id'   => 'custom_page_settings',
			'icon' => 'admin-page',
			'type' => 'heading'
		);
		
		if (discy_is_post_type(array("page","post"))) {
			$options[] = array(
				'type' => 'heading-2',
				'name' => esc_html__('Custom sections','discy')
			);
			
			$options[] = array(
				'name' => esc_html__('Choose a custom sections','discy'),
				'id'   => prefix_meta.'custom_sections',
				'type' => 'checkbox'
			);
			
			$options[] = array(
				'div'       => 'div',
				'condition' => prefix_meta.'custom_sections:not(0)',
				'type'      => 'heading-2'
			);
			
			if (discy_is_post_type(array("page"))) {
				$order_sections = array(
					"author"      => array("sort" => esc_html__('About the author','discy'),"value" => "author"),
					"advertising" => array("sort" => esc_html__('Advertising','discy'),"value" => "advertising"),
					"comments"    => array("sort" => esc_html__('Comments','discy'),"value" => "comments"),
				);
			}else {
				$order_sections = array(
					"author"        => array("sort" => esc_html__('About the author','discy'),"value" => "author"),
					"next_previous" => array("sort" => esc_html__('Next and Previous articles','discy'),"value" => "next_previous"),
					"advertising"   => array("sort" => esc_html__('Advertising','discy'),"value" => "advertising"),
					"related"       => array("sort" => esc_html__('Related articles','discy'),"value" => "related"),
					"comments"      => array("sort" => esc_html__('Comments','discy'),"value" => "comments"),
				);
			}
			
			$options[] = array(
				'name'    => esc_html__('Sort your sections','discy'),
				'id'      => prefix_meta.'order_sections',
				'type'    => 'multicheck',
				'sort'    => 'yes',
				'std'     => $order_sections,
				'options' => $order_sections
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
		}
		
		$options[] = array(
			'type' => 'heading-2',
			'name' => esc_html__('Custom page setting','discy')
		);
		
		$options[] = array(
			'name' => esc_html__('Choose a custom page setting','discy'),
			'id'   => prefix_meta.'custom_page_setting',
			'type' => 'checkbox'
		);
		
		$options[] = array(
			'div'       => 'div',
			'condition' => prefix_meta.'custom_page_setting:not(0)',
			'type'      => 'heading-2'
		);
		
		$options[] = array(
			'name'    => esc_html__('Sticky sidebar','discy'),
			'id'      => prefix_meta.'sticky_sidebar_s',
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
			'name' => esc_html__('Breadcrumbs','discy'),
			'desc' => esc_html__('Select ON to enable the breadcrumbs.','discy'),
			'id'   => prefix_meta.'breadcrumbs',
			'std'  => 'on',
			'type' => 'checkbox'
		);
		
		if (discy_is_post_type(array("post","page"))) {
			$options[] = array(
				'name' => esc_html__('Hide the featured image in the single page','discy'),
				'desc' => esc_html__('Select ON to hide the featured image in the single page.','discy'),
				'id'   => prefix_meta.'featured_image',
				'type' => 'checkbox'
			);
		}
		
		if (discy_is_post_type(array("page"))) {
			$options[] = array(
				'name' => esc_html__('Title enable or disable','discy'),
				'id'   => prefix_meta.'post_title',
				'std'  => "on",
				'type' => 'checkbox'
			);
			
			$options[] = array(
				'name'      => esc_html__('Title style','discy'),
				'desc'      => esc_html__('Choose title style from here.','discy'),
				'id'        => prefix_meta.'post_title_style',
				'std'       => 'style_1',
				'options'   => array(
					'style_1' => 'Style 1',
					'style_2' => 'Style 2',
				),
				'condition' => prefix_meta.'post_title:not(0)',
				'type'      => 'radio'
			);
			
			$options[] = array(
				'name'      => esc_html__('Title icon','discy'),
				'desc'      => esc_html__('Type the title icon from here like "icon-mail".','discy'),
				'id'        => prefix_meta.'post_title_icon',
				'type'      => 'text',
				'condition' => prefix_meta.'post_title:not(0),'.prefix_meta.'post_title_style:is(style_2)'
			);
		}
		
		if (discy_is_post_type(array("question"))) {
			$options[] = array(
				'name' => esc_html__('Activate the author image in single?','discy'),
				'desc' => esc_html__('Author image in single enable or disable.','discy'),
				'id'   => prefix_meta.'author_image_single',
				'std'  => "on",
				'type' => 'checkbox'
			);
			
			$options[] = array(
				'name' => esc_html__('Activate the vote in single?','discy'),
				'desc' => esc_html__('Vote in single enable or disable.','discy'),
				'id'   => prefix_meta.'vote_question_single',
				'std'  => "on",
				'type' => 'checkbox'
			);
			
			$options[] = array(
				'name'      => esc_html__('Select ON to hide the dislike at questions single','discy'),
				'desc'      => esc_html__('If you put it ON the dislike will not show.','discy'),
				'id'        => prefix_meta.'question_single_dislike',
				'condition' => prefix_meta.'vote_question_single:not(0)',
				'type'      => 'checkbox'
			);
			
			$options[] = array(
				'name' => esc_html__('Activate close and open questions','discy'),
				'desc' => esc_html__('Select ON if you want activate close and open questions.','discy'),
				'id'   => prefix_meta.'question_close',
				'std'  => "on",
				'type' => 'checkbox'
			);
		}
		
		if (discy_is_post_type(array("post","question"))) {
			$options[] = array(
				'name' => esc_html__('Tags enable or disable','discy'),
				'id'   => prefix_meta.'post_tags',
				'std'  => "on",
				'type' => 'checkbox'
			);
		}
		
		if (discy_is_post_type(array("question","post"))) {
			if (discy_is_post_type(array("question"))) {
				if ($get_question_user_id != "") {
					$meta_std = array(
						"author_by"       => "author_by",
						"post_date"       => "post_date",
						"asked_to"        => "asked_to",
						"question_views"  => "question_views",
						"question_answer" => "question_answer",
					);
					
					$meta_options = array(
						"author_by"       => esc_html__('Author by','discy'),
						"post_date"       => esc_html__('Date meta','discy'),
						"asked_to"        => esc_html__('Asked to','discy'),
						"question_answer" => esc_html__('Answer meta','discy'),
						"question_views"  => esc_html__('Views stats','discy'),
					);
				}else {
					$meta_std = array(
						"author_by"       => "author_by",
						"post_date"       => "post_date",
						"category_post"   => "category_post",
						"question_views"  => "question_views",
						"question_answer" => "question_answer",
					);
					
					$meta_options = array(
						"author_by"       => esc_html__('Author by','discy'),
						"post_date"       => esc_html__('Date meta','discy'),
						"category_post"   => esc_html__('Category question','discy'),
						"question_answer" => esc_html__('Answer meta','discy'),
						"question_views"  => esc_html__('Views stats','discy'),
					);
				}
			}else {
				$meta_std = $post_meta_std;
				
				$meta_options = array(
					"category_post" => esc_html__('Category post','discy'),
					"title_post"    => esc_html__('Title post','discy'),
					"author_by"     => esc_html__('Author by','discy'),
					"post_date"     => esc_html__('Date meta','discy'),
					"post_comment"  => esc_html__('Comment meta','discy'),
					"post_views"    => esc_html__("Views stats","discy"),
				);
			}
			
			$options[] = array(
				'name'    => esc_html__('Select the meta options','discy'),
				'id'      => prefix_meta.'post_meta',
				'type'    => 'multicheck',
				'std'     => $meta_std,
				'options' => $meta_options
			);
		}
		
		if (discy_is_post_type(array("question"))) {
			$options[] = array(
				'name' => esc_html__('Activate user can add the question to favorites','discy'),
				'desc' => esc_html__('Select ON if you want the user can add the questions to favorites.','discy'),
				'id'   => prefix_meta.'question_favorite',
				'std'  => "on",
				'type' => 'checkbox'
			);
			
			$options[] = array(
				'name' => esc_html__('Activate user can follow the questions','discy'),
				'desc' => esc_html__('Select ON if you want the user can follow the questions.','discy'),
				'id'   => prefix_meta.'question_follow',
				'std'  => "on",
				'type' => 'checkbox'
			);
		}
		
		$options[] = array(
			'name'    => esc_html__('Select the share options','discy'),
			'id'      => prefix_meta.'post_share',
			'type'    => 'multicheck',
			'sort'    => 'yes',
			'std'     => $share_array,
			'options' => $share_array
		);
		
		if (discy_is_post_type(array("question"))) {
			$options[] = array(
				'name' => esc_html__('Navigation enable or disable','discy'),
				'desc' => esc_html__('Navigation ( next and previous ) enable or disable.','discy'),
				'id'   => prefix_meta.'post_navigation',
				'std'  => "on",
				'type' => 'checkbox'
			);
			
			$options[] = array(
				'name'      => esc_html__('Navigation question for the same category only?','discy'),
				'desc'      => esc_html__('Navigation question (next and previous questions) for the same category only?','discy'),
				'id'        => prefix_meta.'question_nav_category',
				'condition' => prefix_meta.'post_navigation:not(0)',
				'std'       => 'on',
				'type'      => 'checkbox'
			);
			
			$options[] = array(
				'name' => esc_html__('Answers enable or disable','discy'),
				'desc' => esc_html__('Answers enable or disable.','discy'),
				'id'   => prefix_meta.'post_comments',
				'std'  => "on",
				'type' => 'checkbox'
			);
			
			$options[] = array(
				'name' => esc_html__('Related questions after content enable or disable','discy'),
				'desc' => esc_html__('Select ON if you want to activate the related questions after the content.','discy'),
				'id'   => prefix_meta.'question_related',
				'type' => 'checkbox'
			);
		}
		
		if (discy_is_post_type(array("post"))) {
			$options[] = array(
				'name'      => esc_html__('Navigation post for the same category only?','discy'),
				'desc'      => esc_html__('Navigation post (next and previous posts) for the same category only?','discy'),
				'id'        => prefix_meta.'post_nav_category',
				'condition' => prefix_meta.'order_sections:has(next_previous)',
				'std'       => 'on',
				'type'      => 'checkbox'
			);
		}
		
		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'end'  => 'end'
		);
		
		if (discy_is_post_type(array("post"))) {
			$options[] = array(
				'type'      => 'heading-2',
				'condition' => prefix_meta.'custom_page_setting:not(0),'.prefix_meta.'order_sections:has(related)',
				'name'      => esc_html__('Related posts','discy')
			);
			
			$options[] = array(
				'name'    => esc_html__('Related style','discy'),
				'desc'    => esc_html__('Choose related style from here.','discy'),
				'id'      => prefix_meta.'related_style',
				'std'     => 'style_1',
				'options' => array(
					'style_1' => 'Style 1',
					'links'   => 'Style 2',
				),
				'type'    => 'radio'
			);
			
			$options[] = array(
				'name' => esc_html__('Related posts number','discy'),
				'desc' => esc_html__('Type related posts number from here','discy'),
				'id'   => prefix_meta.'related_number',
				'std'  => 4,
				'type' => 'text'
			);
			
			$options[] = array(
				'name'    => esc_html__('Query type','discy'),
				'desc'    => esc_html__('Select what will the related posts show.','discy'),
				'id'      => prefix_meta.'query_related',
				'std'     => 'categories',
				'options' => array(
					'categories' => esc_html__('Posts in the same categories','discy'),
					'tags'       => esc_html__('Posts in the same tags (If not find any tags will show by the same categories)','discy'),
					'author'     => esc_html__('Posts by the same author','discy'),
				),
				'type'    => 'radio'
			);
			
			$options[] = array(
				'name' => esc_html__('Excerpt title in related','discy'),
				'desc' => esc_html__('Type excerpt title in related from here.','discy'),
				'id'   => prefix_meta.'excerpt_related_title',
				'std'  => 10,
				'type' => 'text'
			);
			
			$options[] = array(
				'name'      => esc_html__('Comment in related enable or disable','discy'),
				'id'        => prefix_meta.'comment_in_related',
				'std'       => "on",
				'condition' => prefix_meta.'related_style:is(style_1)',
				'type'      => 'checkbox'
			);
			
			$options[] = array(
				'name'      => esc_html__('Date in related enable or disable','discy'),
				'id'        => prefix_meta.'date_in_related',
				'std'       => "on",
				'condition' => prefix_meta.'related_style:is(style_1)',
				'type'      => 'checkbox'
			);
			
			$options[] = array(
				'type' => 'heading-2',
				'end'  => 'end'
			);
		}

		if (discy_is_post_type(array("question"))) {
			$options[] = array(
				'type'      => 'heading-2',
				'condition' => prefix_meta.'custom_page_setting:not(0),'.prefix_meta.'question_related:not(0)',
				'name'      => esc_html__('Related questions','discy')
			);
			
			$options[] = array(
				'name' => esc_html__('Related questions number','discy'),
				'desc' => esc_html__('Type the number of related questions from here','discy'),
				'id'   => prefix_meta.'related_number',
				'std'  => 5,
				'type' => 'text'
			);
			
			$options[] = array(
				'name'    => esc_html__('Query type','discy'),
				'desc'    => esc_html__('Select how many related questions will show.','discy'),
				'id'      => prefix_meta.'query_related',
				'std'     => 'categories',
				'options' => array(
					'categories' => esc_html__('Questions in the same categories','discy'),
					'tags'       => esc_html__('Questions in the same tags (If not found, questions with the same categories will be shown)','discy'),
					'author'     => esc_html__('Questions by the same author','discy'),
				),
				'type'    => 'radio'
			);
			
			$options[] = array(
				'name' => esc_html__('Excerpt title in related','discy'),
				'desc' => esc_html__('Type excerpt title in related from here.','discy'),
				'id'   => prefix_meta.'excerpt_related_title',
				'std'  => 20,
				'type' => 'text'
			);
			
			$options[] = array(
				'type' => 'heading-2',
				'end'  => 'end'
			);
		}
	}
	
	if (discy_is_post_type(array("post","page","question"))) {
		$options[] = array(
			'name' => esc_html__('Advertising','discy'),
			'id'   => 'advertising',
			'icon' => 'admin-post',
			'type' => 'heading'
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'name' => esc_html__('Advertising after header 1','discy')
		);
		
		$options[] = array(
			'name'    => esc_html__('Advertising type','discy'),
			'id'      => prefix_meta.'header_adv_type_1',
			'std'     => 'custom_image',
			'type'    => 'radio',
			'options' => array("display_code" => esc_html__("Display code","discy"),"custom_image" => esc_html__("Custom Image","discy"))
		);
		
		$options[] = array(
			'name'      => esc_html__('Image URL','discy'),
			'desc'      => esc_html__('Upload a image, or enter URL to an image if it is already uploaded.','discy'),
			'id'        => prefix_meta.'header_adv_img_1',
			'condition' => prefix_meta.'header_adv_type_1:is(custom_image)',
			'type'      => 'upload'
		);
		
		$options[] = array(
			'name'      => esc_html__('Advertising url','discy'),
			'id'        => prefix_meta.'header_adv_href_1',
			'std'       => '#',
			'condition' => prefix_meta.'header_adv_type_1:is(custom_image)',
			'type'      => 'text'
		);
		
		$options[] = array(
			'name'      => esc_html__('Advertising Code html ( Ex: Google ads)','discy'),
			'id'        => prefix_meta.'header_adv_code_1',
			'condition' => prefix_meta.'header_adv_type_1:is(display_code)',
			'type'      => 'textarea'
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'end'  => 'end'
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'name' => esc_html__('Advertising inner single page sections','discy')
		);
		
		$options[] = array(
			'name'    => esc_html__('Advertising type','discy'),
			'id'      => prefix_meta.'share_adv_type',
			'std'     => 'custom_image',
			'type'    => 'radio',
			'options' => array("display_code" => esc_html__("Display code","discy"),"custom_image" => esc_html__("Custom Image","discy"))
		);
		
		$options[] = array(
			'name'      => esc_html__('Image URL','discy'),
			'desc'      => esc_html__('Upload a image, or enter URL to an image if it is already uploaded.','discy'),
			'id'        => prefix_meta.'share_adv_img',
			'type'      => 'upload',
			'condition' => prefix_meta.'share_adv_type:is(custom_image)'
		);
		
		$options[] = array(
			'name'      => esc_html__('Advertising url','discy'),
			'id'        => prefix_meta.'share_adv_href',
			'std'       => '#',
			'type'      => 'text',
			'condition' => prefix_meta.'share_adv_type:is(custom_image)'
		);
		
		$options[] = array(
			'name'      => esc_html__('Advertising Code html ( Ex: Google ads)','discy'),
			'id'        => prefix_meta.'share_adv_code',
			'type'      => 'textarea',
			'condition' => prefix_meta.'share_adv_type:is(display_code)'
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'end'  => 'end'
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'name' => esc_html__('Advertising after left menu','discy')
		);
		
		$options[] = array(
			'name'    => esc_html__('Advertising type','discy'),
			'id'      => prefix_meta.'left_menu_adv_type',
			'std'     => 'custom_image',
			'type'    => 'radio',
			'options' => array("display_code" => esc_html__("Display code","discy"),"custom_image" => esc_html__("Custom Image","discy"))
		);
		
		$options[] = array(
			'name'      => esc_html__('Image URL','discy'),
			'desc'      => esc_html__('Upload a image, or enter URL to an image if it is already uploaded.','discy'),
			'id'        => prefix_meta.'left_menu_adv_img',
			'type'      => 'upload',
			'condition' => prefix_meta.'left_menu_adv_type:is(custom_image)'
		);
		
		$options[] = array(
			'name'      => esc_html__('Advertising url','discy'),
			'id'        => prefix_meta.'left_menu_adv_href',
			'std'       => '#',
			'type'      => 'text',
			'condition' => prefix_meta.'left_menu_adv_type:is(custom_image)'
		);
		
		$options[] = array(
			'name'      => esc_html__('Advertising Code html ( Ex: Google ads)','discy'),
			'id'        => prefix_meta.'left_menu_adv_code',
			'type'      => 'textarea',
			'condition' => prefix_meta.'left_menu_adv_type:is(display_code)'
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'end'  => 'end'
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'name' => esc_html__('Advertising after content','discy')
		);
		
		$options[] = array(
			'name'    => esc_html__('Advertising type','discy'),
			'id'      => prefix_meta.'content_adv_type',
			'std'     => 'custom_image',
			'type'    => 'radio',
			'options' => array("display_code" => esc_html__("Display code","discy"),"custom_image" => esc_html__("Custom Image","discy"))
		);
		
		$options[] = array(
			'name'      => esc_html__('Image URL','discy'),
			'desc'      => esc_html__('Upload a image, or enter URL to an image if it is already uploaded.','discy'),
			'id'        => prefix_meta.'content_adv_img',
			'type'      => 'upload',
			'condition' => prefix_meta.'content_adv_type:is(custom_image)'
		);
		
		$options[] = array(
			'name'      => esc_html__('Advertising url','discy'),
			'id'        => prefix_meta.'content_adv_href',
			'std'       => '#',
			'type'      => 'text',
			'condition' => prefix_meta.'content_adv_type:is(custom_image)'
		);
		
		$options[] = array(
			'name'      => esc_html__('Advertising Code html ( Ex: Google ads)','discy'),
			'id'        => prefix_meta.'content_adv_code',
			'type'      => 'textarea',
			'condition' => prefix_meta.'content_adv_type:is(display_code)'
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'end'  => 'end'
		);
	}
	
	if (discy_is_post_type(array("page","post","question"))) {
		$options[] = array(
			'name' => esc_html__('Custom CSS code','discy'),
			'id'   => 'css_meta',
			'icon' => 'editor-code',
			'type' => 'heading'
		);
		
		$options[] = array(
			'type' => 'heading-2'
		);
		
		$options[] = array(
			'name' => esc_html__('Custom CSS','discy'),
			'desc' => esc_html__('Put the Custom CSS.','discy'),
			'id'   => prefix_meta.'footer_css',
			'rows' => 10,
			'type' => 'textarea'
		);
		
		$options[] = array(
			'type' => 'heading-2',
			'end'  => 'end'
		);
	}

	$options = apply_filters('discy_all_meta_options',$options,$post);
	
	return $options;
}
/* Meta feed */
add_filter("discy_options_question_feed","discy_options_question_feed",1,2);
function discy_options_question_feed($options,$home = '') {
	$options[] = array(
		'name'  => esc_html__('Show the recent questions for unlogged users on feed page or show message must login to see the feed?','discy'),
		'id'    => prefix_meta.'login_'.$home.'feed',
		'type'  => 'checkbox',
	);

	$feed = array(
		"users" => array("sort" => esc_html__('Users','discy'),"value" => "users"),
		"cats"  => array("sort" => esc_html__('Categories','discy'),"value" => "cats"),
		"tags"  => array("sort" => esc_html__('Tags ','discy'),"value" => "tags"),
	);
	
	$options[] = array(
		'name'    => esc_html__('Select the sections you want to show at feed','discy'),
		'id'      => prefix_meta.$home.'feed',
		'type'    => 'multicheck',
		'sort'    => 'yes',
		'std'     => $feed,
		'options' => $feed
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => prefix_meta.$home.'feed:has(users)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'type' => 'info',
		'name' => esc_html__('Users section setting','discy')
	);
	
	$options[] = array(
		'name' => esc_html__('Number of other users to be followed for the user to make their feed','discy'),
		"id"   => prefix_meta."users_".$home."feed",
		"type" => "sliderui",
		'std'  => '6',
		"step" => "1",
		"min"  => "0",
		"max"  => "10"
	);

	$options[] = array(
		'name'  => esc_html__('Show the users in slider?','discy'),
		'id'    => prefix_meta.'users_slider_'.$home.'feed',
		'type'  => 'checkbox',
	);

	$options[] = array(
		'name'  => esc_html__('Show load more for the users?','discy'),
		'id'    => prefix_meta.'users_more_'.$home.'feed',
		'type'  => 'checkbox',
	);

	$options[] = array(
		'name'      => esc_html__('Custom link for the users page?','discy'),
		'id'        => prefix_meta.'custom_link_users_'.$home.'feed',
		'condition' => prefix_meta.'users_more_'.$home.'feed:not(0)',
		'type'      => 'text',
	);

	$options[] = array(
		'name'    => esc_html__('Order by','discy'),
		'id'      => prefix_meta.'user_sort_'.$home.'feed',
		'std'     => "points",
		'type'    => 'select',
		'options' => array(
			'points'          => esc_html__('Points','discy'),
			'the_best_answer' => esc_html__('Best Answers','discy'),
			'answers'         => esc_html__('Answers','discy'),
			'question_count'  => esc_html__('Questions','discy'),
			'followers'       => esc_html__('Followers','discy'),
		),
	);

	$options[] = array(
		'name'    => esc_html__('Users style','discy'),
		'id'      => prefix_meta.'user_style_'.$home.'feed',
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
		'id'        => prefix_meta.'masonry_user_style_'.$home.'feed',
		'type'      => 'checkbox',
		'condition' => prefix_meta.'user_style_'.$home.'feed:is(small_grid),'.prefix_meta.'user_style_'.$home.'feed:is(columns),'.prefix_meta.'user_style_'.$home.'feed:is(small),'.prefix_meta.'user_style_'.$home.'feed:is(grid)',
		'operator'  => 'or',
	);
	
	$options[] = array(
		'name' => esc_html__('Users per page','discy'),
		'id'   => prefix_meta.'users_per_'.$home.'feed',
		'std'  => '6',
		'type' => 'text'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => prefix_meta.$home.'feed:has(cats)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'type' => 'info',
		'name' => esc_html__('Categories section setting','discy')
	);
	
	$options[] = array(
		'name' => esc_html__('Number of categories to be followed by the user to make their feed','discy'),
		"id"   => prefix_meta."categories_".$home."feed",
		"type" => "sliderui",
		'std'  => '6',
		"step" => "1",
		"min"  => "1",
		"max"  => "10"
	);

	$options[] = array(
		'name'  => esc_html__('Show the categories in slider?','discy'),
		'id'    => prefix_meta.'cats_slider_'.$home.'feed',
		'type'  => 'checkbox',
	);

	$options[] = array(
		'name'  => esc_html__('Show load more for the categories?','discy'),
		'id'    => prefix_meta.'cats_more_'.$home.'feed',
		'type'  => 'checkbox',
	);

	$options[] = array(
		'name'      => esc_html__('Custom link for the categories page?','discy'),
		'id'        => prefix_meta.'custom_link_cats_'.$home.'feed',
		'condition' => prefix_meta.'cats_more_'.$home.'feed:not(0)',
		'type'      => 'text',
	);

	$options[] = array(
		'name'    => esc_html__('Order by','discy'),
		'id'      => prefix_meta.'cat_sort_'.$home.'feed',
		'std'     => "count",
		'type'    => 'select',
		'options' => array(
			'count'     => esc_html__('Questions','discy'),
			//'answers'   => esc_html__('Answers','discy'),
			'followers' => esc_html__('Followers','discy'),
		),
	);

	$options[] = array(
		'name'    => esc_html__('Categories style','discy'),
		'id'      => prefix_meta.'cat_style_'.$home.'feed',
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
		'name' => esc_html__('Categories per page','discy'),
		'id'   => prefix_meta.'cat_per_'.$home.'feed',
		'std'  => '6',
		'type' => 'text'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);
	
	$options[] = array(
		'div'       => 'div',
		'condition' => prefix_meta.$home.'feed:has(tags)',
		'type'      => 'heading-2'
	);
	
	$options[] = array(
		'type' => 'info',
		'name' => esc_html__('Tags section setting','discy')
	);
	
	$options[] = array(
		'name' => esc_html__('Number of tags to be followed by the user make their feed','discy'),
		"id"   => prefix_meta."tags_".$home."feed",
		"type" => "sliderui",
		'std'  => '6',
		"step" => "1",
		"min"  => "0",
		"max"  => "10"
	);

	$options[] = array(
		'name'  => esc_html__('Show the tags in slider?','discy'),
		'id'    => prefix_meta.'tags_slider_'.$home.'feed',
		'type'  => 'checkbox',
	);

	$options[] = array(
		'name'  => esc_html__('Show load more for the tags?','discy'),
		'id'    => prefix_meta.'tags_more_'.$home.'feed',
		'type'  => 'checkbox',
	);

	$options[] = array(
		'name'      => esc_html__('Custom link for the tags page?','discy'),
		'id'        => prefix_meta.'custom_link_tags_'.$home.'feed',
		'condition' => prefix_meta.'tags_more_'.$home.'feed:not(0)',
		'type'      => 'text',
	);

	$options[] = array(
		'name'    => esc_html__('Order by','discy'),
		'id'      => prefix_meta.'tag_sort_'.$home.'feed',
		'std'     => "count",
		'type'    => 'select',
		'options' => array(
			'count'     => esc_html__('Questions','discy'),
			//'answers'   => esc_html__('Answers','discy'),
			'followers' => esc_html__('Followers','discy'),
		),
	);
	
	$options[] = array(
		'name' => esc_html__('Tags per page','discy'),
		'id'   => prefix_meta.'tag_per_'.$home.'feed',
		'std'  => '6',
		'type' => 'text'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'div'  => 'div',
		'end'  => 'end'
	);

	return $options;
}