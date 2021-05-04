<?php $support_activate = discy_updater()->is_active();
if ($support_activate) {
	/* Remove demo meta */
	add_action('save_post','discy_remove_demo_meta');
	function discy_remove_demo_meta($post_id) {
		delete_post_meta($post_id,'theme_import_demo');
	}
	/* Check if the One Click Demo Import plugin is active */
	if (class_exists('OCDI_Plugin')) {
		add_action('admin_init','discy_disable_ocdi_plugin');
	}
	function discy_disable_ocdi_plugin() {
		if (wp_doing_ajax()) {
			return;
		}
		deactivate_plugins(plugin_basename('one-click-demo-import/one-click-demo-import.php'));
	}
	/* Branding */
	add_filter('pt-ocdi/disable_pt_branding','__return_true');
	/* Demo page setting */
	add_filter('pt-ocdi/plugin_page_setup','discy_plugin_page_setup');
	function discy_plugin_page_setup($default_settings) {
		$default_settings['parent_slug'] = 'options';
		$default_settings['page_title']  = esc_html__('Demo Import','discy');
		$default_settings['menu_title']  = esc_html__('Demo Import','discy');
		$default_settings['capability']  = 'manage_options';
		$default_settings['menu_slug']   = 'demo-import';
		return $default_settings;
	}
	/* Confirm dialog */
	add_filter('pt-ocdi/confirmation_dialog_options','my_theme_ocdi_confirmation_dialog_options',10,1);
	function my_theme_ocdi_confirmation_dialog_options($options) {
		return array_merge($options,array(
			'width'       => 600,
			'height'      => 600,
			'dialogClass' => 'discy-demo-dialog',
			'resizable'   => false,
			'modal'       => false,
		));
	}
	/* Demo files */
	add_filter('pt-ocdi/import_files','discy_import_files');
	function discy_import_files() {
		$demos = get_transient('discy_import_demos');
		if (isset($demos) && is_array($demos) && !empty($demos)) {
			return $demos;
		}
		$file_path = "https://2code.info/demos.php?demo=Discy";
		if ($file_path != "") {
			$response = wp_remote_get($file_path,20);
			$values = (is_array($response) && isset($response["body"])?$response["body"]:"");
			$demos = json_decode($values,true);
			set_transient('discy_import_demos', $demos, 60*60*24);
		}
		return (isset($demos) && is_array($demos) && !empty($demos)?$demos:array());
	}
	/* Before import the demo */
	add_action("pt-ocdi/before_widgets_import","discy_before_widgets_import");
	function discy_before_widgets_import($selected_import) {
		//wp_set_sidebars_widgets(get_option("old_sidebar_widgets"));
		$sidebar_widgets = wp_get_sidebars_widgets();
		update_option("old_sidebar_widgets",$sidebar_widgets);
		update_option("sidebars_widgets",'');
	}
	/* After import the demo */
	add_action('pt-ocdi/after_import','discy_after_import_setup');
	function discy_after_import_setup($selected_import) {
		// Demo name
		update_option("demo_import_name",$selected_import['import_file_name']);

		// Old options
		update_option("old_import_demo_options",get_option("discy_options"));

		// Old menus
		update_option("old_nav_menu_locations",get_theme_mod('nav_menu_locations'));

		// Update options
		$file_path = $selected_import['import_options_file_url'];
		if ($file_path != "") {
			$response = wp_remote_get($file_path,20);
			$values = (isset($response["body"])?$response["body"]:"");
			if ($values != "") {
				$data = base64_decode($values);
				$data = json_decode($data,true);
				$array_options = array("discy_options","sidebars");
				foreach ($array_options as $option) {
					if (isset($data[$option])) {
						update_option($option,$data[$option]);
					}else {
						delete_option($option);
					}
				}
				update_option("FlushRewriteRules",true);
			}
		}

		// Assign menus to their locations.
		$main_menu_1 = get_term_by('name','EXPLORE not login','nav_menu');
		$main_menu_2 = get_term_by('name','EXPLORE','nav_menu');
		$main_menu_3 = get_term_by('name','Header','nav_menu');
		$main_menu_4 = get_term_by('name','Header login','nav_menu');
		set_theme_mod('nav_menu_locations',array('header_menu_login' => $main_menu_4->term_id,'header_menu' => $main_menu_3->term_id,'discy_explore_login' => $main_menu_2->term_id,'discy_explore' => $main_menu_1->term_id));

		$array_menu = wp_get_nav_menu_items($main_menu_2->term_id);
		$own_url = 'https://2code.info/demo/themes/Discy/Main/';
		$own_url_2 = 'https://2code.info/demo/themes/Discy/RTL/';
		if (is_array($array_menu) && !empty($array_menu)) {
			foreach ($array_menu as $key => $value) {
				if (strpos($value->url,$own_url) !== false || strpos($value->url,$own_url_2) !== false) {
					update_post_meta($value->ID,'_menu_item_url',str_ireplace(array($own_url,$own_url_2),esc_url(home_url('/')),$value->url));
				}
			}
		}

		$array_menu = wp_get_nav_menu_items($main_menu_1->term_id);
		if (is_array($array_menu) && !empty($array_menu)) {
			foreach ($array_menu as $key => $value) {
				if (strpos($value->url,$own_url) !== false || strpos($value->url,$own_url_2) !== false) {
					update_post_meta($value->ID,'_menu_item_url',str_ireplace(array($own_url,$own_url_2),esc_url(home_url('/')),$value->url));
				}
			}
		}

		// Assign front page and posts page (blog page).
		$front_page_id = get_page_by_title('Home');
		update_option('show_on_front','page');
		update_option('page_on_front',$front_page_id->ID);

		// Delete default wordpress data
		$hello_post_id = get_page_by_title('Hello world!',OBJECT,'post');
		$hello_post_id_ar = get_page_by_title('أهلاً بالعالم !',OBJECT,'post');

		// remove hello world post
		if (isset($hello_post_id->ID)) {
			wp_delete_post($hello_post_id->ID,true);
		}

		// remove hello world post
		if (isset($hello_post_id_ar->ID)) {
			wp_delete_post($hello_post_id_ar->ID,true);
		}

		$sample_page_id = get_page_by_title('Sample Page',OBJECT,'page');
		$sample_page_id_ar = get_page_by_title('مثال على صفحة',OBJECT,'page');

		// remove sample page
		if (isset( $sample_page_id->ID)) {
			wp_delete_post($sample_page_id->ID,true);
		}

		// remove sample page
		if (isset($sample_page_id_ar->ID)) {
			wp_delete_post($sample_page_id_ar->ID,true);
		}

		$sticky_post_id = get_page_by_path('is-this-statement-i-see-him-last-night-can-be-understood-as-i-saw-him-last-night',OBJECT,'question');
		if (isset($sticky_post_id->ID)) {
			$post_id = $sticky_post_id->ID;
			update_post_meta($post_id,"sticky",1);
			$sticky_posts = get_option('sticky_posts');
			if (is_array($sticky_posts)) {
				if (!in_array($post_id,$sticky_posts)) {
					$array_merge = array_merge($sticky_posts,array($post_id));
					update_option("sticky_posts",$array_merge);
				}
			}else {
				update_option("sticky_posts",array($post_id));
			}
			$sticky_questions = get_option('sticky_questions');
			if (is_array($sticky_questions)) {
				if (!in_array($post_id,$sticky_questions)) {
					$array_merge = array_merge($sticky_questions,array($post_id));
					update_option("sticky_questions",$array_merge);
				}
			}else {
				update_option("sticky_questions",array($post_id));
			}
		}
	}
	/* Header in the demo page */
	add_action("pt-ocdi/plugin_page_header","discy_plugin_page_header");
	function discy_plugin_page_header() {
		echo '<div id="discy-registration-wrap" class="discy-demos-container"><div class="discy-dash-container discy-dash-container-medium"><div class="postbox"><h2><span class="dashicons dashicons-yes library-icon-key"></span><span>'.esc_html__('Choose the demo which you want to import','discy').'</span></h2><div class="inside"><div class="main">';
	}
	/* Footer in the demo page */
	add_action("pt-ocdi/plugin_page_footer","discy_plugin_page_footer");
	function discy_plugin_page_footer() {
		echo '</div></div></div></div></div>';
	}
	/* Title in the demo page */
	add_filter('pt-ocdi/plugin_intro_text','discy_plugin_page_title');
	add_filter("pt-ocdi/plugin_page_title","discy_plugin_page_title");
	function discy_plugin_page_title() {
		echo '';
	}
}?>