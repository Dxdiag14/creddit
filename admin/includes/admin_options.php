<?php /* Admin class */
class discy_admin {

	static function &_discy_admin_options($page = "options") {
		static $options = null;
		if ( !$options ) {
	        // Load options from options.php file (if it exists)
	        if ( $optionsfile = get_template_directory()."/admin/".$page.".php" ) {
	            $maybe_options = require_once $optionsfile;
	            if ( is_array( $maybe_options ) ) {
					$options = $maybe_options;
	            }else if ( $page == "widgets" && function_exists( 'discy_admin_widgets' ) ) {
	            	$options = discy_admin_widgets();
	            }else if ( $page == "term" && function_exists( 'discy_admin_terms' ) ) {
	            	$options = discy_admin_terms();
	            }else if ( $page == "meta" && function_exists( 'discy_admin_meta' ) ) {
	            	$options = discy_admin_meta();
	            }else if ( $page == "options" && function_exists( 'discy_admin_options' ) ) {
					$options = discy_admin_options();
				}
	        }
	        // Allow setting/manipulating options via filters
	        $options = apply_filters( 'discy_'.$page, $options );
		}
		return $options;
	}
}

/* Admin options */
class discy_admin_options {

    /* Hook in the scripts and styles */
    public function init($page = "options") {
    	$support_activate = discy_updater()->is_active();
		if ($support_activate) {
			// Gets options to load
			if ($page == "meta") {
				discy_admin_meta();
			}else if ($page == "widgets") {
				discy_admin_widgets();
			}else if ($page == "terms") {
				discy_admin_terms();
			}else {
	    		$options = & discy_admin::_discy_admin_options($page);
	    	}

			// Checks if options are available
	    	if ( $options ) {
				add_action('admin_menu', array( $this, 'discy_add_admin' ), 13 );
			}
		}
    }

	/* Define menu options (still limited to appearance section) */
	function discy_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function = '', $icon_url = '', $position = null ) {
	    global $menu, $admin_page_hooks, $_registered_pages, $_parent_pages;
	 
	    $menu_slug = plugin_basename( $menu_slug );
	 
	    $admin_page_hooks[$menu_slug] = sanitize_title( $menu_title );
	 
	    $hookname = get_plugin_page_hookname( $menu_slug, '' );
	 
	    if ( !empty( $function ) && !empty( $hookname ) && current_user_can( $capability ) )
	        add_action( $hookname, $function );
	 
	    if ( empty($icon_url) ) {
	        $icon_url = 'dashicons-admin-generic';
	        $icon_class = 'menu-icon-generic ';
	    } else {
	        $icon_url = set_url_scheme( $icon_url );
	        $icon_class = '';
	    }
	 
	    $new_menu = array( $menu_title, $capability, $menu_slug, $page_title, 'menu-top ' . $icon_class . $hookname, $hookname, $icon_url );
	 
	    if ( null === $position ) {
	        $menu[] = $new_menu;
	    } elseif ( isset( $menu[ "$position" ] ) ) {
	        $position = $position + substr( base_convert( md5( $menu_slug . $menu_title ), 16, 10 ) , -5 ) * 0.00001;
	        $menu[ "$position" ] = $new_menu;
	    } else {
	        $menu[ $position ] = $new_menu;
	    }
	 
	    $_registered_pages[$hookname] = true;
	 
	    // No parent as top level
	    $_parent_pages[$menu_slug] = false;
	 
	    return $hookname;
	}
	
	function discy_add_admin() {
		$support_activate = discy_updater()->is_active();
		if ($support_activate) {
			$this->discy_menu_page(discy_theme_name.' Settings', discy_theme_name ,'manage_options', 'options' , array( $this, 'options_page' ),"dashicons-admin-site" );
		}
	}

	/* Builds out the options panel */
	function options_page() {
		do_action('discy_options_page');?>
		<div id="discy-admin-wrap" class="discy-admin">
			<?php if (!function_exists('mobile_options')) {?>
				<a class="app-img" href="https://2code.info/checkout/pay_for_apps/33664/" target="_blank"><img alt="Discy Mobile Application" src="https://2code.info/mobile/last/960x100.png"></a>
				<section id="footer_call_to_action" class="gray_section call_to_action">
					<div class="container main_content_area">
						<div class="row section">
							<div class="section_container col col12">
								<div class="section_inner_container">
									<div class="row section_inner">
										<div class="col col7"> 
											<div class="main_section_left_title main_section_title">Test Application!</div>
											<div class="main_section_left_content main_section_content">Test Discy application demo on Google Play and App Store.</div>
										</div>
										<div class="col col5">
											<div class="row">
												<div class="col col6 col-app">
													<a target="_blank" title="Download Android App" href="https://play.google.com/store/apps/details?id=app.ask.application">
														<img alt="Play Store" src="https://2code.info/mobile/google_play.png">
													</a>
												</div>
												<div class="col col6 col-app">
													<a target="_blank" href="https://apps.apple.com/app/discy/id1535374585" title="Download IOS App">
														<img alt="App Store" src="https://2code.info/mobile/app_store.png">
													</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
			<?php }?>
			<form action="" id="main_options_form" method="post">
				<div class="discy-admin-header">
					<a href="<?php echo discy_theme_url_tf?>" target="_blank"><i class="dashicons-before dashicons-admin-tools"></i><?php echo discy_theme_name?></a>
					<div class="discy_search">
						<input type="search" placeholder="<?php esc_attr_e('Type Search Words','discy')?>">
						<div class="search-results results-empty"></div>
					</div>
					<input type="submit" class="button-primary discy_save" name="update_options" value="<?php esc_attr_e( 'Save Options', "discy" ); ?>">
					<div class="discy_social">
						<ul>
							<li class="discy_social_facebook"><a class="discy_social_f" href="https://www.facebook.com/2code.info" target="_blank"><i class="dashicons dashicons-facebook"></i></a></li>
							<li class="discy_social_twitter"><a class="discy_social_t" href="https://www.twitter.com/2codeThemes" target="_blank"><i class="dashicons dashicons-twitter"></i></a></li>
							<li class="discy_social_site"><a class="discy_social_e" href="https://2code.info/" target="_blank"><i class="dashicons dashicons-email-alt"></i></a></li>
							<li class="discy_social_docs"><a class="discy_social_s" href="https://2code.info/docs/discy/" target="_blank"><i class="dashicons dashicons-sos"></i></a></li>
						</ul>
					</div>
					<div class="clear"></div>
				</div>
				<div class="discy-admin-content">
					<h2 class="nav-tab-wrapper"><?php echo discy_admin_fields_class::discy_admin_tabs(); ?></h2>
					<?php settings_errors( 'options-framework' ); ?>
					<div id="discy-admin-metabox" class="metabox-holder">
						<div id="discy-admin" class="discy_framework postbox">
							<?php discy_admin_fields_class::discy_admin_fields()?>
							<div class="vpanel-loading"></div>
							<div id="ajax-saving"><i class="dashicons dashicons-yes"></i><?php esc_html_e("Saved","discy")?></div>
							<div id="ajax-reset"><i class="dashicons dashicons-info"></i><?php esc_html_e("Reseted","discy")?></div>
						</div><!-- End container -->
					</div>
					<?php do_action('discy_admin_after');?>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
				<div class="discy-admin-footer">
					<input type="submit" class="button-primary discy_save" name="update_options" value="<?php esc_attr_e( 'Save Options', "discy" ); ?>">
					<input type="hidden" name="action" value="discy_update_options">
					<div id="loading"></div>
					<input type="submit" class="reset-button button-secondary" id="reset_c" name="reset" value="<?php esc_attr_e( 'Restore Defaults', "discy" ); ?>">
					<div class="clear"></div>
				</div>
			</form>
		</div><!-- End wrap -->
		<?php
	}

	/* Get the default values for all the theme options */
	function get_default_values() {
		$output = array();
		$config = & discy_admin::_discy_admin_options();
		foreach ( (array) $config as $option ) {
			if ( ! isset( $option['id'] ) ) {
				continue;
			}
			if ( ! isset( $option['std'] ) ) {
				continue;
			}
			if ( ! isset( $option['type'] ) ) {
				continue;
			}
			$output[$option['id']] = $option['std'];
		}
		return $output;
	}
}

/* Admin fields */
class discy_admin_fields_class {

	/**
	 * Generates the tabs that are used in the options menu
	 */
	static function discy_admin_tabs($page = "options",$options_arrgs = array(),$post_id = "") {
		$counter = 0;
		$options = $options_arrgs;
		if (empty($options_arrgs)) {
			$options = & discy_admin::_discy_admin_options($page);
		}
		if (isset($options) && is_array($options) && !empty($options)) {
			$menu = $class = '';
			$wp_page_template = ($page == "meta" && isset($post_id)?discy_post_meta("_wp_page_template",$post_id,false):"");
			foreach ( $options as $value ) {
				// Heading for Navigation
				if ( isset($value['type']) && $value['type'] == "heading" ) {
					$counter++;
					$class = ! empty( $value['id'] ) ? $value['id'] : $value['name'];
					$class = preg_replace( '/[^a-zA-Z0-9._\-]/', '', strtolower($class) ).'-tab';
					if ( ! array_key_exists( 'template', $value ) || ! is_string( $value['template'] ) ) {
						$value['template'] = '';
					}
					$template = empty( $value['template'] ) ? '' : ' data-template="'. esc_attr( $value['template'] ) .'"';
					if (isset($value['template']) && $value['template'] != "" && $value['template'] != $wp_page_template) {
						$class .= ' hide';
					}
					$menu .= '<a'.$template.' id="options-group-'.$counter.'-tab" class="nav-tab '.$class.'" title="'.esc_attr($value['name']).'" href="'.esc_attr('#options-group-'.$counter).'"><span class="options-name'.(isset($value['new']) && $value['new'] != ""?' options-name-new':'').'">'.esc_html($value['name']).(isset($value['new']) && $value['new'] != ''?'<span>'.esc_html__('New','discy').'</span>':'').'</span>'.(isset($value['icon']) && $value['icon'] != ''?'<span class="dashicons dashicons-'.esc_attr($value['icon']).'"></span>':'').'</a>';
				}
			}
			return $menu;
		}
	}

	/**
	 * Generates the options fields that are used in the form.
	 */
	static function discy_admin_fields($settings = array(),$option_name = "",$page = "options",$post_term = null,$options_arrgs = array()) {
	
		discy_options_fields($settings,$option_name,$page,$post_term,$options_arrgs);

		// Outputs closing div if there tabs
		if ( $page == "options" || $page == "meta" ) {
			echo '</div>';
		}
	}

}?>