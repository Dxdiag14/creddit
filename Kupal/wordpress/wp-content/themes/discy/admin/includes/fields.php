<?php /* Get uploader */
function discy_options_uploader ($_id,$_value,$_desc = '',$_name = '',$no_id_name = '',$options = array()) {
	// Gets the unique option id
	$option_name = (strpos(apply_filters('wpqa_server','REQUEST_URI'),'page=options') !== false?discy_options:discy_meta);
	$output = $id = $class = $int = $value = $value_id = $name = '';
	$id = strip_tags( strtolower( $_id ) );
	
	// If a value is passed and we don't have a stored value, use the value that's passed through.
	if (is_array($_value) && !empty($_value) && array_key_exists('url',$_value) && array_key_exists('id',$_value)) {
		$value = $_value["url"];
		$value_id = $_value["id"];
	}else if (!is_array($_value) && $_value != '' && $value == '') {
		$value = $_value;
	}
	
	if (isset($value) && $value != "" && is_numeric($value)) {
		$value_id = $value;
		$value = wp_get_attachment_url($value_id);
	}
	
	if ($_name != '') {
		$name = $_name;
	}else {
		$name = $option_name.'['.$id.']';
	}
	$image_attrs = (isset($options['height'])?'data-height="'.$options['height'].'" ':'');
	$image_attrs = (isset($options['width'])?'data-width="'.$options['width'].'" ':'').$image_attrs;
	
	if ( $value ) {
		$class = ' has-file';
	}
	$output .= '<div class="form-upload-images">
	<input class="image_id" type="hidden" '.($no_id_name == 'no'?'data-attr="'.esc_attr($name).'][id"':'name="'.esc_attr($name).'[id]"').' value="' . (int)$value_id . '">
	<input '.($no_id_name == 'no'?'attr-id="'.$id.'"':'id="'.$id.'"').' class="upload' . esc_attr($class) . '" type="text" '.($no_id_name == 'no'?'data-attr="'.esc_attr($name).'][url"':'name="'.esc_attr($name).'[url]"').' value="' . esc_attr($value) . '" placeholder="' . esc_attr__('No file chosen', "discy") .'">' . "\n";
	if ( function_exists( 'wp_enqueue_media' ) ) {
		if ( ( $value == '' ) ) {
			$output .= '<input '.$image_attrs.($no_id_name == 'no'?'data-attr="upload-'.esc_attr($id).'"':'id="upload-'.esc_attr($id).'"').' class="upload-button button" type="button" value="' . esc_attr__( 'Upload', "discy" ) . '">' . "\n";
		}else {
			$output .= '<input '.$image_attrs.($no_id_name == 'no'?'data-attr="remove-'.esc_attr($id).'"':'id="remove-'.esc_attr($id).'"').' class="remove-file button" type="button" value="' . esc_attr__( 'Remove', "discy" ) . '">' . "\n";
		}
	}else {
		$output .= '<p><i>' . esc_html__( 'Upgrade your version of WordPress for full media support.', "discy" ) . '</i></p>';
	}
	$output .= '</div>';
	if ( $_desc != '' ) {
		$output .= '<span class="discy-metabox-desc">' . $_desc . '</span>' . "\n";
	}

	$output .= '<div class="screenshot" '.($no_id_name == 'no'?'data-attr="'.$id.'-image"':'id="'.$id.'-image"').'>' . "\n";

	if ( $value != '' ) {
		$remove = '<a class="remove-image">'.esc_html__("Remove","discy").'</a>';
		$image = preg_match( '/(^.*\.jpg|jpeg|png|gif|ico*)/i', $value );
		if ( $image ) {
			$output .= '<img src="' . $value . '" alt="' . $value . '">' . $remove;
		}else {
			$parts = explode( "/", $value );
			for( $i = 0; $i < sizeof( $parts ); ++$i ) {
				$title = $parts[$i];
			}

			// No output preview if it's not an image.
			$output .= '';

			// Standard generic output if it's not an image.
			$title = esc_html__( 'View File', "discy" );
			$output .= '<div class="no-image"><span class="file_link"><a href="' . esc_url($value) . '" target="_blank" rel="external">'.$title.'</a></span></div>';
		}
	}
	$output .= '</div>' . "\n";
	return $output;
}
/* Get fields */
function discy_options_fields($settings = array(),$option_name = "",$page = "options",$post_term,$options_arrgs = array()) {
	global $allowedtags;
	$wp_page_template = ($page == "meta" && isset($post_term)?discy_post_meta("_wp_page_template",$post_term,false):"");
	if ($option_name == "") {
		$discy_admin_settings = get_option(discy_options);
		// Gets the unique option id
		if ( isset( $discy_admin_settings['id'] ) ) {
			$option_name = $discy_admin_settings['id'];
		}else {
			$option_name = discy_options;
		}
		if ($page == "options") {
			$settings = get_option($option_name);
		}
	}
	
	$options = $options_arrgs;
	if (empty($options_arrgs)) {
		$options = & discy_admin::_discy_admin_options($page);
	}
	
	$counter = 0;
	$menu = '';
	$values = array();
	
	foreach ( $options as $value ) {

		$val = $val_terms = $select_value = $output = '';

		// Wrap all options
		if ($value['type'] != "heading") {

			// Keep all ids lowercase with no spaces
			if (isset($value['id'])) {
				$value_name_id = $value['id'];
				$value['id'] = preg_replace('/[^a-zA-Z0-9._\-]/', '', strtolower($value['id']) );
				//$value['id'] = $value['id'];
				$id = 'section-'.($page == "widgets"?$post_term->get_field_id( $value['id'] ):$value['id']);
			}

			$class = 'section';
			$wrap_class = 'wrap_class';
			$options_group = 'options-group';
			if ( isset( $value['type'] ) ) {
				$class .= ' section-'.$value['type'].' discy-form-'.$value['type'];
			}
			if ( isset( $value['class'] ) ) {
				$class .= ' '.$value['class'];
			}
			
			if ( ! array_key_exists( 'operator', $value ) || ! in_array( $value['operator'], array( 'and', 'or' ) ) ) {
				$value['operator'] = 'and';
			}

			if ( ! array_key_exists( 'condition', $value ) || ! is_string( $value['condition'] ) ) {
				$value['condition'] = '';
			}
			
			if ($value['type'] != "heading-2" && $value['type'] != "heading-3" && $value['type'] != "content" && $value['type'] != "info") {
				// Set default value to $val
				if ( isset( $value['std'] ) ) {
					$val = $value['std'];
				}
				
				$field_id = esc_attr(($page == 'widgets'?$post_term->get_field_id($value['id']):(isset($value['id'])?$value['id']:'')));
				if ($page == "options" && isset($value['unset'])) {
					$field_name = "";
				}else {
					$field_name = esc_attr(($page == 'widgets'?$post_term->get_field_name($value['id']):($page == 'meta' || $page == 'terms' || $page == 'author'?$field_id:$option_name.'['.$field_id.']')));
				}
			}
			
			// If the option is already saved, override $val
			if ($value['type'] != 'heading' && $value['type'] != "heading-2" && $value['type'] != "heading-3" && $value['type'] != 'info' && $value['type'] != "content" && !isset($value['readonly'])) {
				if (isset($post_term) || (isset($settings[($value['id'])]) && isset($value['id']) && ($value['type'] != "editor" || ($value['type'] == "editor" && $settings[($value['id'])] != "")))) {
					if ($page == "widgets") {
						$val = (isset($settings[$value['id']])?$settings[$value['id']]:(isset($val)?$val:""));
					}else if ($page == "meta" && isset($post_term)) {
						if (isset($value['save']) && $value['save'] == "option") {
							$val_terms = get_option($field_name);
						}else {
							$val_terms = discy_post_meta($field_name,$post_term,false);
						}
					}else if ($page == "terms" && isset($post_term)) {
						$val_terms = discy_term_meta($field_name,$post_term,false);
					}else if ($page == "author" && isset($post_term)) {
						$val_terms = get_user_meta($post_term,$field_name,true);
					}else if ($page == "options") {
						$val = $settings[$field_id];
					}
					
					if ('' !== $val_terms && array() !== $val_terms && false !== $val_terms) {
						$val = $val_terms;
					}
					
					// Striping slashes of non-array options
					if (!is_array($val)) {
						$val = stripslashes($val);
					}
				}
			}
			
			$val = ($page == "widgets" && isset($value['id']) && isset($value['type']) && $value['type'] == "checkbox" && isset($settings[$value['id']])?$settings[$value['id']]:$val);

			$val = (isset($value['val'])?$value['val']:$val);
			
			if ($value['type'] != 'heading' && $value['type'] != "heading-2" && $value['type'] != "heading-3" && $value['type'] != 'info' && $value['type'] != "content" && !isset($value['readonly']) && array_key_exists( 'id', $value )) {
				$values[ $value['id'] ] = ($value['type'] == 'checkbox' && $val == ""?0:$val);
			}
			
			if ( ! discy_field_is_visible( $value['condition'], $value['operator'], $options, $values ) ) {
				$class .= ' hide';
				$wrap_class .= ' hide';
				$options_group .= ' hide';
			}
			
			$condition = empty( $value['condition'] ) ? '' : ' data-condition="'. esc_attr( $value['condition'] ) .'"';
			$operator = empty( $condition ) ? '' : ' data-operator="'. esc_attr( $value['operator'] ) .'"';
			
			if ($value['type'] != "heading-2" && $value['type'] != "heading-3" && $value['type'] != "content" && $value['type'] != "info" && $value['type'] != 'hidden') {
				$output .= '<div data-type="'.$value['type'].'"'.(isset($value['id'])?' data-id="'.esc_attr( $value['id'] ).'"':'').( $condition ).( $operator ).' id="'.esc_attr( $id ).'" class="'.esc_attr( $class ).'"'.(isset($value['margin']) && $value['margin'] != ""?" style='margin:".$value['margin']."'":"").'>'."\n";
				if ( isset( $value['name'] ) ) {
					$output .= '<h4 class="heading">'.$value['name'].'</h4>'."\n";
				}
				if ( $value['type'] != 'editor' && $value['type'] != 'upload' && $value['type'] != 'background' && $value['type'] != 'sidebar' && $value['type'] != 'badges' && $value['type'] != 'coupons' && $value['type'] != 'roles' ) {
					$output .= '<div class="option">'."\n".'<div class="controls'.(isset($value['limit-height'])?' limit-height':'').'">'."\n";
				}else if ( $value['type'] == 'upload' || $value['type'] == 'background' ) {
					$output .= '<div class="option">'."\n".'<div class="controls controls-upload">'."\n";
				}else if ( $value['type'] == 'sidebar' ) {
					$output .= '<div class="option">'."\n".'<div class="controls controls-sidebar">'."\n";
				}else if ( $value['type'] == 'badges' ) {
					$output .= '<div class="option">'."\n".'<div class="controls controls-badges">'."\n";
				}else if ( $value['type'] == 'coupons' ) {
					$output .= '<div class="option">'."\n".'<div class="controls controls-coupons">'."\n";
				}else if ( $value['type'] == 'roles' ) {
					$output .= '<div class="option">'."\n".'<div class="controls controls-role">'."\n";
				}else {
					$output .= '<div class="option">'."\n".'<div>'."\n";
				}
			}
		}

		// If there is a description save it for labels
		$explain_value = '';
		if ( isset( $value['desc'] ) ) {
			$explain_value = $value['desc'];
		}

		if ( has_filter( 'discy_' . $value['type'] ) ) {
			$output .= apply_filters( 'discy_' . $value['type'], $option_name, $value, $val );
		}
		
		if (isset($field_name) && isset($value['type'])) {
			$val = apply_filters('discy_'.$value['type'].'_'.$field_name,$val);
		}
		
		if (isset($value['type'])) {
			$output = apply_filters('discy_'.$value['type'].'_field',$output,$value,$val,(isset($option_name)?$option_name:""),(isset($field_name)?$field_name:""),(isset($field_id)?$field_id:""));
		}
		switch ( $value['type'] ) {
		// Content
		case 'content':
			if ( isset( $value['content'] ) ) {
				$output .= '<div class="'.esc_attr( $class ).'" id="'.(isset($value['id']) && $value['id'] != ""?$value['id']:"").'" '.( $condition ).( $operator ).'>'.$value['content'].'</div>';
			}
			break;
		
		// Hidden input
		case 'hidden':
			$output .= '<input name="'.$field_name.'" type="hidden" value="' . esc_attr( $val ) . '">';
			break;
		
		// Text input
		case 'text':
			$output .= '<input'.($field_id != ''?' id="'.$field_id.'"':'').' class="discy-input discy-form-control" name="'.$field_name.'" type="text" value="' . esc_attr( $val ) . '"'.(isset($value['readonly'])?' readonly':'').'>';
			break;

		// Date input
		case 'date':
			$output .= '<input readonly="readonly" id="'.$field_id.'" class="discy-input discy-date discy-form-control"'.(isset($value['js']) && $value['js'] != ""?" data-js='".json_encode($value['js'])."'":"").' name="'.$field_name.'" type="text" value="' . esc_attr( $val ) . '">';
			break;
		
		// Password input
		case 'password':
			$output .= '<input id="'.$field_id.'" class="discy-input discy-form-control" name="'.$field_name.'" type="password" value="' . esc_attr( $val ) . '">';
			break;

		// Textarea
		case 'textarea':
			$rows = '8';

			if ( isset( $value['settings']['rows'] ) ) {
				$custom_rows = $value['settings']['rows'];
				if ( is_numeric( $custom_rows ) ) {
					$rows = $custom_rows;
				}
			}

			$val = stripslashes( $val );
			$output .= '<textarea id="'.$field_id.'" class="discy-input discy-form-control" name="'.$field_name.'" rows="' . $rows . '">' . esc_textarea( $val ) . '</textarea>';
			break;
		
		// Select custom additions
		case 'custom_addition':
			if (isset($value['addto']) && $value['addto'] != "") {
				$field_id = $value['addto'];
			}else {
				$field_id = $value['id'];
			}
			$field_type = (isset($value['addition'])?$value['addition']:'cat');
			if (isset($value['options'])) {
				$select_options = '<select id="">';
				foreach ($value['options'] as $key_options => $value_options) {
					$select_options .= '<option value="'.$key_options.'">'.$value_options.'</option>';
				}
				$select_options .= '</select>';
			}else {
				$select_options = wp_dropdown_categories(array(
					'taxonomy'          => (isset($value['taxonomy']) && $value['taxonomy'] != ""?$value['taxonomy']:'question-category'),
				    'orderby'           => 'name',
				    'echo'              => 0,
				    'hide_empty'        => 0,
				    'hierarchical'      => 1,
				    'id'                => (isset($field_id) && $field_id != ""?$field_id:""),
				    'name'              => "",
				    'show_option_none'  => (isset($value['show_option']) && $value['show_option'] != ""?esc_html($value['show_option']):esc_html__('Show Categories','discy')),
				    'option_none_value' => (isset($value['option_none']) && $value['option_none'] != ""?esc_html($value['option_none']):0),
				));
			}
			$output .= '
			<div class="styled-select">'.$select_options.'</div>
			<div class="addition_tabs">';
				if (empty($value['addto'])) {
					$output .= '<ul id="'.(isset($field_id) && $field_id != ""?$field_id:"").'-ul" class="sort-sections sort-sections-ul">';
						$i = 0;
						if (isset($val) && is_array($val)) {
							foreach ($val as $key_a => $value_a) {
								if (isset($value['values'])) {
									$object = $value['values'];
									$object_name = $object[$value_a];
								}else {
									$object = get_term_by('id',$value_a,(isset($value['taxonomy']) && $value['taxonomy'] != ""?$value['taxonomy']:'question-category'));
									$object_name = $object->name;
								}
								$i++;
								$output .= '<li class="li class="additions-li"" id="'.(isset($field_id) && $field_id != ""?$field_id:"").'_additions_li_'.$value_a.'"><div class="widget-head">
									<span>'.((isset($value['option_none']) && $value['option_none'] != "" && $value_a == $value['option_none']) || $value_a == "0"?esc_html__('All Categories','discy'):$object_name).'</span></div><input name="'.(isset($field_id) && $field_id != ""?$field_id:"").'['.$field_type.'-'.$value_a.']" value="'.$value_a.'" type="hidden">
									<div>
										<a class="widget-handle"><span class="dashicons dashicons-editor-justify"></span></a>
										<a class="del-builder-item"><span class="dashicons dashicons-trash"></span></a>
									</div>
								</li>';
							}
						}
					$output .= '</ul>';
				}
			$output .= '</div>
			<div class="clear"></div>
			<div class="add-item add-item-2 add-item-6 add-item-7" data-type="'.$field_type.'" data-toadd="'.(isset($value['toadd']) && $value['toadd'] != ""?$value['toadd']:"").'" data-addto="'.(isset($field_id) && $field_id != ""?$field_id:"").'" data-id="'.(isset($field_id) && $field_id != ""?$field_id:"").'_additions" data-name="'.(isset($field_id) && $field_id != ""?esc_attr(($page == 'widgets'?$post_term->get_field_name($value['id']):($page == 'meta' || $page == 'terms' || $page == 'author'?$field_id:$option_name.'['.$field_id.']'))):"").'">'.(isset($value["button"])?$value["button"]:esc_html__("Add category","discy")).'</div>
			<div class="clear"></div>';
			break;
		
		// Select category
		case 'select_category':
			if (isset($value['selected']) && $value['selected'] == "s_f_category") {
				$category = current(wp_get_object_terms($post_term,'question-category'));
				if (!isset($category->name)) $category = '';
			}
			$output .= '<div class="styled-select">'.
				wp_dropdown_categories(array(
					'show_option_none'  => (isset($value['option_none']) && $value['option_none'] != ""?$value['option_none']:0),
				    'orderby'           => 'name',
				    'hide_empty'        => 0,
				    'hierarchical'      => 1,
				    'echo'              => 0,
				    'class'             => (isset($value['class']) && $value['class'] != ""?$value['class']:""),
				    'name'              => $field_name,
				    'id'                => $field_id,
				    'selected'          => (isset($category->term_id) && $category->term_id != ""?$category->term_id:(isset($val) && $val != ""?$val:"")),
				    'taxonomy'          => (isset($value['taxonomy']) && $value['taxonomy'] != ""?$value['taxonomy']:"category")
				)).
			"</div>";
			break;
		
		// Multicheck category
		case 'multicheck_category':
			$output .= '<div class="discy_checklist discy_scroll"><ul class="categorychecklist discy_categorychecklist">'.
			discy_categories_checklist(array("taxonomy" => (isset($value['taxonomy']) && $value['taxonomy'] != ""?$value['taxonomy']:"category"),"id" => $field_id,"name" => $field_name,"selected_cats" => (isset($val) && is_array($val)?$val:""))).
			'</ul></div>';
			break;
		
		// Slider
		case 'sliderui':
			$output .= discy_option_sliderui($value['min'],$value['max'],$value['step'],(isset($value['edit']) && $value['edit'] != ""?$value['edit']:""),$val,$field_id,$field_name);
			break;
		
		// Sections
		case 'sections':
			$output .= '<ul id="'.$value['id'].'" class="sort-sections">';
				$order_sections_li = $val;
				if (empty($order_sections_li)) {
					$order_sections_li = array(1 => "author",2 => "next_previous",3 => "advertising",4 => "related",5 => "comments");
				}
				$order_sections = $order_sections_li;
				$i = 0;
				
				$array_not_found = array("next_previous","advertising","author","related","comments");
				foreach ($array_not_found as $key_not => $value_not) {
					if (!in_array($value_not,$order_sections)) {
						array_push($order_sections,$value_not);
					}
				}
				
				foreach ($order_sections as $key_r => $value_r) {
					$i++;
					if ($value_r == "") {
						unset($order_sections[$key_r]);
					}else {
						$output .= '<li id="'.esc_attr($value_r).'">
							<div class="widget-head">
								<span>';
									if ($value_r == "next_previous") {
										$output .= esc_attr("Next and Previous articles");
									}else if ($value_r == "advertising") {
										$output .= esc_attr("Advertising");
									}else if ($value_r == "author") {
										$output .= esc_attr("About the author");
									}else if ($value_r == "related") {
										$output .= esc_attr("Related articles");
									}else if ($value_r == "comments") {
										$output .= esc_attr("Comments");
									}
								$output .= '</span>
								<a class="widget-handle"><span class="dashicons dashicons-editor-justify"></span></a>
							</div>
							<input name="'.esc_attr( $option_name . '[' . $value['id'] . ']['.esc_attr($i).']' ).'" value="';if ($value_r == "next_previous") {$output .= esc_attr("next_previous");}else if ($value_r == "advertising") {$output .= esc_attr("advertising");}else if ($value_r == "author") {$output .= esc_attr("author");}else if ($value_r == "related") {$output .= esc_attr("related");}else if ($value_r == "comments") {$output .= esc_attr("comments");}$output .= '" type="hidden">
						</li>';
					}
				}
			$output .= '</ul>';
			break;
		
		// Sort
		case 'sort':
			$output .= '<ul id="'.$value['id'].'" class="sort-sections sort-sections-ul">';
				$sort_sections = $val;
				if (empty($sort_sections) || (count($sort_sections) <> count($value['options']))) {
					if (isset($value['merge']) && !empty($value['merge']) && is_array($value['merge'])) {
						foreach ($value['merge'] as $key_merge => $value_merge) {
							$sort_sections = (!in_array($value_merge,$sort_sections)?array_merge($sort_sections,array($value_merge)):$sort_sections);
						}
					}
				}else {
					if (isset($value['merge']) && !empty($value['merge']) && is_array($value['merge'])) {
						foreach ($value['merge'] as $key_merge => $value_merge) {
							$sort_sections = (!in_array($value_merge,$sort_sections)?array_merge($sort_sections,array($value_merge)):$sort_sections);
						}
					}
				}
				$i = 0;
				
				$array_not_found = $value['options'];
				foreach ($array_not_found as $key_not => $value_not) {
					if (!in_array($value_not,$sort_sections) && !array_key_exists('default',$value_not)) {
						array_push($sort_sections,$value_not);
					}
				}
				
				if (isset($sort_sections) && is_array($sort_sections)) {
					foreach ($sort_sections as $key => $value_for) {
						$i++;
						$output .= '<li id="elements_'.$value['id'].'_'.esc_attr($i).'">
							<div class="widget-head"><span>'.ucfirst(isset($value_for["name"]) && is_array($value_for["name"]) && isset($value_for["name"]["value"])?esc_attr($value_for["name"]["value"]):esc_attr($value_for["name"])).'</span><a class="widget-handle"><span class="dashicons dashicons-editor-justify"></span></a>'.(isset($value["delete"]) && $value["delete"] == "yes" && isset($value_for['getthe']) && $value_for['getthe'] != ""?'<a class="del-builder-item del-sidebar-item"><span class="dashicons dashicons-trash"></span></a>':'').'</div>';
							if (isset($value_for['getthe']) && $value_for['getthe'] != "") {
								$output .= '<div class="widget-content">';
							}
							
							foreach ($value_for as $key_a => $value_a) {
								if ($key_a != "getthe" && isset($value_for['getthe']) && $value_for['getthe'] != "") {
									$output .= '<h4>'.$key_a.'</h4>';
								}
								if (is_array($value_for[$key_a]) && array_key_exists("type",$value_for[$key_a]) && $value_for[$key_a]["type"] != "" && $value_for[$key_a]["type"] != "text" && $key_a != "getthe") {
									if ($value_for[$key_a]["type"] == "textarea") {
										$output .= '<textarea name="'.esc_attr( $field_name.'['.esc_attr($i).']['.$key_a.'][value]' ).'" rows="8" class="discy-input">'.$value_for[$key_a]["value"].'</textarea>';
									}
								}else {
									$output .= '<input name="'.esc_attr( $field_name.'['.esc_attr($i).']['.$key_a.']'.(isset($value_for["default"]) && $value_for["default"] == "yes"?"":"[value]") ).'" value="'.(isset($value_for[$key_a]) && is_array($value_for[$key_a])?esc_attr($value_for[$key_a]["value"]):esc_attr($value_for[$key_a])).'" type="'.($key_a != "getthe" && isset($value_for['getthe']) && $value_for['getthe'] != ""?"text":"hidden").'">';
								}
								if (!isset($value_for["default"]) && $key_a != "getthe") {
									$output .= '<input name="'.esc_attr( $field_name.'['.esc_attr($i).']['.$key_a.']'.(isset($value_for["default"]) && $value_for["default"] == "yes"?"":"[value]") ).'" value="'.(isset($value_for[$key_a]) && is_array($value_for[$key_a])?esc_attr($value_for[$key_a]["value"]):esc_attr($value_for[$key_a])).'" type="'.($key_a != "getthe" && isset($value_for['getthe']) && $value_for['getthe'] != ""?"text":"hidden").'">';
								}
								if ($key_a != "getthe" && $key_a != "default" && empty($value_for["default"])) {
									$output .= '<input name="'.esc_attr( $field_name.'['.esc_attr($i).']['.$key_a.']'.(isset($value_for["default"]) && $value_for["default"] == "yes"?"":"[type]") ).'" value="'.(isset($value_for["name"]) && is_array($value_for["name"])?esc_attr($value_for["name"]["type"]):"text").'" type="hidden">';
								}
							}
							if (isset($value_for['getthe']) && $value_for['getthe'] != "") {
								$output .= '</div';
							}
						$output .= '</li>';
					}
				}
			$output .= '</ul>';
			break;
		
		// Elements
		case 'elements':
			$output .= '<div class="all_elements">
				<ul class="sort-sections not-sort not-add-item '.(isset($value['hide']) && $value['hide'] == "yes"?"discy_hidden":"discy_not_hidden").'"'.(isset($value['addto']) && $value['addto'] != ""?" data-to='".$value['addto']."'":"").'>
					<li>';
						if (isset($value["title"]) && $value["title"] != "") {
							$output .= '<a class="widget-handle"><span class="dashicons dashicons-editor-justify"></span></a><a class="del-builder-item del-sidebar-item"><span class="dashicons dashicons-trash"></span></a>';
						}else {
							$output .= '<div><a class="widget-handle"><span class="dashicons dashicons-editor-justify"></span></a><a class="del-builder-item del-sidebar-item"><span class="dashicons dashicons-trash"></span></a></div>';
						}
						$output .= '<div class="widget-content">';
							foreach ($value['options'] as $key_e => $value_e) {
								$class = 'section';
								$wrap_class = 'wrap_class';
								$options_group = 'options-group';
								if ( isset( $value_e['type'] ) ) {
									$class .= ' section-'.$value_e['type'].' discy-form-'.$value_e['type'];
								}
								if ( isset( $value_e['class'] ) ) {
									$class .= ' '.$value_e['class'];
								}
								
								if ( ! array_key_exists( 'operator', $value_e ) || ! in_array( $value_e['operator'], array( 'and', 'or' ) ) ) {
									$value_e['operator'] = 'and';
								}
				
								if ( ! array_key_exists( 'condition', $value_e ) || ! is_string( $value_e['condition'] ) ) {
									$value_e['condition'] = '';
								}
								
								$condition = empty( $value_e['condition'] ) ? '' : ' data-condition="'. esc_attr( $value_e['condition'] ) .'"';
								$operator = empty( $condition ) ? '' : ' data-operator="'. esc_attr( $value_e['operator'] ) .'"';
								
								if ($value_e["type"] != "heading-2" && $value_e['type'] != "heading-3") {
									$output .= '<div data-attr="'.$value_e['id'].'" data-type="'.$value_e["type"].'" '.( $condition ).( $operator ).' class="'.esc_attr( $class ).'">'.(isset($value_e["name"]) && $value_e["name"] != ''?'<h4 class="heading">'.$value_e["name"].'</h4>':'');
								}
									if ($value_e["type"] == "images") {
										$output .= '<div class="image_element">'.
										discy_option_images($field_id,'','',$value_e["options"],$value_e["std"],'',$option_name,'no',$value_e["id"]).
										'</div>';
									}else if ($value_e["type"] == "upload") {
										$output .= "<div class='controls controls-upload'>".discy_options_uploader($value_e["id"],"",null,$value_e["id"],"no")."</div>";
									}else if ($value_e["type"] == "select") {
										$output .= '<div class="styled-select"><select data-attr="'.$value_e["id"].(isset($value['addto']) && $value['addto'] != ""?"][value":"").'" class="discy-input" '.(isset($value_e['multiple']) && $value_e['multiple'] != ""?"multiple":"").'>
										'.(isset($value_e['first'])?'<option>'.$value_e['first'].'</option>':'');
										foreach ($value_e['options'] as $key => $option ) {
											$output .= '<option'. (isset($value_e['multiple']) && $value_e['multiple'] != ""?(isset($value_e['std']) && is_array($value_e['std']) && in_array($key,$value_e['std'])?' selected="selected"':""):(isset($value_e['std'])?selected( $value_e['std'], $key, false ):"")) .' value="' . esc_attr( $key ) . '">' . esc_html( $option ) . '</option>';
										}
										$output .= '</select></div>';
									}else if ($value_e["type"] == "radio") {
										foreach ($value_e['options'] as $key => $option ) {
											$output .= '<input '.(isset($value_e['std'])?checked( $value_e['std'], $key, false ):"").' data-attr="'.$value_e["id"].(isset($value['addto']) && $value['addto'] != ""?"][value":"").'" class="discy-input discy-radio discy-form-control" type="radio" value="'. esc_attr( $key ) . '"><label>' . esc_html( $option ) . '</label>';
										}
									}else if ($value_e["type"] == "textarea") {
										$rows = '8';
										if ( isset( $value['settings']['rows'] ) ) {
											$custom_rows = $value['settings']['rows'];
											if ( is_numeric( $custom_rows ) ) {
												$rows = $custom_rows;
											}
										}
										$output .= '<textarea data-attr="'.$value_e["id"].'" class="discy-input" rows="'.$rows.'">'.(isset($value_s[$value_e['id']])?stripslashes($value_s[$value_e['id']]):"").'</textarea>';
									}else if ($value_e["type"] == "heading-2" || $value_e['type'] == "heading-3") {
										if ( isset($value_e['end']) && $value_e['end'] == "end" ) {
											if ( isset($value_e['div']) && $value_e['div'] == "div" ) {
												$output .= '</div>'."\n";
											}else {
												$output .= '</div></div>'."\n";
											}
										}else {
											if ( isset($value_e['div']) && $value_e['div'] == "div" ) {
												$output .= '<div class="'.$wrap_class.'" id="'.(isset($value_e['id']) && $value_e['id'] != ""?"wrap_".$value_e['id']:"").'"'.( $condition ).( $operator ).'>';
											}else {
												$class = '';
												$class = ! empty($value_e['id'])?$value_e['id']:(isset($value_e['name']) && $value_e['name'] != ""?$value_e['name']:"");
												$class = preg_replace('/[^a-zA-Z0-9._\-]/', '', strtolower($class) );
												$output .= '<div'.(isset($value_e['id'])?' id="head-'.$value_e['id'].'"':'').' class="'.$options_group.(isset($value_e['id'])?' head-group head-'.$value_e['id']:'').'"'.( $condition ).( $operator ).'>';
												if ( isset($value_e['name']) ) {
													$output .= '<h4 class="vpanel-head-2">' . esc_html( $value_e['name'] ) . '</h4>' . "\n";
												}
												$output .= '<div class="discy-group-2 ' . $class . '">';
											}
										}
									}else if ($value_e["type"] == "checkbox") {
										$output .= '<label class="switch" for="">
											<input data-attr="'.$value_e["id"].'" class="checkbox discy-input discy-form-control" value="on" type="checkbox">
											<label for="" data-on="'.esc_attr__("ON","discy").'" data-off="'.esc_attr__("OFF","discy").'"></label>
										</label>';
									}else {
										if ($value_e["type"] == "slider") {
											$output .= '<div class="section-sliderui">';
										}
										$output .= '<input'.(isset($value['title']) && $value['title'] != ""?" data-title='".$value['title']."'":"").($value_e["type"] == "color"?" class='discy-colors'":"").($value_e["type"] == "date"?" class='builder-datepicker'".(isset($value_e['js']) && $value_e['js'] != ""?" data-js='".json_encode($value_e['js'])."'":""):"").($value_e["type"] == "slider"?" value='".(isset($value_e['value']) && $value_e['value'] != ""?$value_e['value']:"")."' class='mini'":"").' data-attr="'.$value_e["id"].(isset($value['addto']) && $value['addto'] != ""?"][value":"").'" data-value="'.(isset($value_e['value']) && $value_e['value'] != ""?$value_e['value']:"").'" type="'.($value_e["type"] == "hidden_id" || $value_e["type"] == "uniq_id"?"hidden":"text").'">';
										if ($value_e["type"] == "slider") {
											$data = 'data-id="slider-id" data-val="'.$value_e['value'].'" data-min="'.$value_e['min'].'" data-max="'.$value_e['max'].'" data-step="'.$value_e['step'].'"';
											$output .= '<div id="slider-id-slider" class="v_slidersui" '. $data .'></div></div>';
										}
									}
									if (isset($value['addto']) && $value['addto'] != "") {
										$output .= '<input data-attr="'.$value_e['id'].'][type" value="'.$value_e["type"].'" type="hidden">';
									}
								if ($value_e["type"] != "heading-2" && $value_e['type'] != "heading-3") {
									$output .= '<div class="clear"></div></div>';
								}
							}
						$output .= '</div>
					</li>
				</ul>
			</div>
			
			<ul class="sort-sections sort-sections-with sort-sections-ul'.(isset($val) && is_array($val) && !empty($val) && !isset($value['addto'])?'':' sort-sections-empty').'" id="'.(isset($field_id) && $field_id != ""?$field_id:"").'">';
				$i = 0;
				if (isset($val) && is_array($val) && !empty($val) && !isset($value['addto'])) {
					foreach ($val as $value_s) {
						$i++;
						$output .= '<li id="elements_'.$field_id.'_'.$i.'">';
							if (isset($value["title"]) && $value["title"] != "") {
								$output .= '<div class="widget-head"><span>'.esc_attr($value_s["name"]).'</span><a class="widget-handle"><span class="dashicons dashicons-editor-justify"></span></a><a class="del-builder-item del-sidebar-item"><span class="dashicons dashicons-trash"></span></a></div>';
							}else {
								$output .= '<div><a class="widget-handle"><span class="dashicons dashicons-editor-justify"></span></a><a class="del-builder-item del-sidebar-item"><span class="dashicons dashicons-trash"></span></a></div>';
							}
							$output .= '<div class="widget-content">';
								foreach ($value['options'] as $key_l => $value_l) {
									$class = 'section';
									$wrap_class = 'wrap_class';
									$options_group = 'options-group';
									if ( isset( $value_l['type'] ) ) {
										$class .= ' section-'.$value_l['type'].' discy-form-'.$value_l['type'];
									}
									if ( isset( $value_l['class'] ) ) {
										$class .= ' '.$value_l['class'];
									}
									
									if ( ! array_key_exists( 'operator', $value_l ) || ! in_array( $value_l['operator'], array( 'and', 'or' ) ) ) {
										$value_l['operator'] = 'and';
									}
					
									if ( ! array_key_exists( 'condition', $value_l ) || ! is_string( $value_l['condition'] ) ) {
										$value_l['condition'] = '';
									}
									
									$condition = empty( $value_l['condition'] ) ? '' : ' data-condition="'.  str_ireplace('[%id%]', $option_name."_".$field_id."_".$i."_", esc_attr( $value_l['condition'] ))  .'"';
									$operator = empty( $condition ) ? '' : ' data-operator="'. esc_attr( $value_l['operator'] ) .'"'; 
									if ($value_l["type"] != "heading-2" && $value_l['type'] != "heading-3") {
										$output .= '<div data-type="'.$value_l["type"].'" data-id="'.$option_name."_".$field_id."_".$i."_".$value_l['id'].'" id="section-'.$option_name."_".$field_id."_".$i."_".$value_l['id'].'"'.( $condition ).( $operator ).' class="'.esc_attr( $class ).'">'.(isset($value_l["name"]) && $value_l["name"] != ''?'<h4 class="heading">'.$value_l["name"].'</h4>':'');
									}
										if ($value_l["type"] == "images") {
											$output .= '<div class="image_element">'.
											discy_option_images(esc_attr($option_name).'_'.$field_id.'_'.$i.'_'.$value_l['id'],'','',$value_l["options"],$value_s[$value_l['id']],'',$field_name.'['.$i.']['.$value_l['id'].']','',$value_l["id"],'no').
											'</div>';
										}else if ($value_l["type"] == "upload") {
											$output .= "<div class='controls controls-upload'>".discy_options_uploader($field_id.'_'.$i.'_'.$value_l['id'],(isset($value_s[$value_l['id']])?$value_s[$value_l['id']]:""),null,$field_name.'['.$i.']['.$value_l['id'].']')."</div>";
										}else if ($value_l["type"] == "select") {
											$output .= '<div class="styled-select"><select data-attr="'.$value_l["id"].'" class="discy-input" '.(isset($value_l['multiple']) && $value_l['multiple'] != ""?"multiple":"").' name="'.$field_name.'['.$i.']['.$value_l['id'].']'.(isset($value_l['multiple']) && $value_l['multiple'] != ""?"[]":"").'" id="'.$option_name."_".$field_id.'_'.$i.'_'.$value_l['id'].'">
											'.(isset($value_l['first'])?'<option>'.$value_l['first'].'</option>':'');
											foreach ($value_l['options'] as $key => $option ) {
												$output .= '<option'. (isset($value_l['multiple']) && $value_l['multiple'] != ""?(isset($value_s[$value_l['id']]) && is_array($value_s[$value_l['id']]) && in_array($key,$value_s[$value_l['id']])?' selected="selected"':""):(isset($value_s[$value_l['id']])?selected( $value_s[$value_l['id']], $key, false ):"")) .' value="' . esc_attr( $key ) . '">' . esc_html( $option ) . '</option>';
											}
											$output .= '</select></div>';
										}else if ($value_l["type"] == "radio") {
											foreach ($value_l['options'] as $key => $option ) {
												$output .= '<input name="'.$field_name.'['.$i.']['.$value_l['id'].']" id="'.$option_name."_".$field_id.'_'.$i.'_'.$value_l['id'].'_'.$key.'" data-attr="'.$value_l["id"].(isset($value['addto']) && $value['addto'] != ""?"][value":"").'" class="discy-input discy-radio discy-form-control" type="radio" value="'. esc_attr( $key ) . '" '.(isset($value_s[$value_l['id']])?checked( $value_s[$value_l['id']], $key, false ):"").'><label for="'.$option_name."_".$field_id.'_'.$i.'_'.$value_l['id'].'_'.$key.'">' . esc_html( $option ) . '</label>';
											}
										}else if ($value_l["type"] == "textarea") {
											$rows = '8';
											if ( isset( $value['settings']['rows'] ) ) {
												$custom_rows = $value['settings']['rows'];
												if ( is_numeric( $custom_rows ) ) {
													$rows = $custom_rows;
												}
											}
											$output .= '<textarea data-attr="'.$value_l["id"].'" class="discy-input" rows="'.$rows.'" name="'.$field_name.'['.$i.']['.$value_l['id'].']" id="'.$option_name."_".$field_id.'_'.$i.'_'.$value_l['id'].'">'.stripslashes(isset($value_s[$value_l['id']])?$value_s[$value_l['id']]:"").'</textarea>';
										}else if ($value_l["type"] == "slider") {
											$output .= '<div class="section-sliderui">'.
											discy_option_sliderui($value_l["min"],$value_l["max"],$value_l["step"],'',$value_s[$value_l['id']],$field_id.']['.$i.']['.$value_l['id'],$field_name.'['.$i.']['.$value_l['id'].']',esc_attr($option_name).'_'.$field_id.'_'.$i.'_'.$value_l['id']).
											'</div>';
										}else if ($value_l["type"] == "heading-2" || $value_l['type'] == "heading-3") {
											if ( isset($value_l['end']) && $value_l['end'] == "end" ) {
												if ( isset($value_l['div']) && $value_l['div'] == "div" ) {
													$output .= '</div>'."\n";
												}else {
													$output .= '</div></div>'."\n";
												}
											}else {
												if ( isset($value_l['div']) && $value_l['div'] == "div" ) {
													$output .= '<div class="'.$wrap_class.'" id="'.(isset($value_l['id']) && $value_l['id'] != ""?"wrap_".$value_l['id']:"").'"'.( $condition ).( $operator ).'>';
												}else {
													$class = '';
													$class = ! empty($value_l['id'])?$value_l['id']:(isset($value_l['name']) && $value_l['name'] != ""?$value_l['name']:"");
													$class = preg_replace('/[^a-zA-Z0-9._\-]/', '', strtolower($class) );
													$output .= '<div'.(isset($value_l['id'])?' id="head-'.$value_l['id'].'"':'').' class="'.$options_group.(isset($value_l['id'])?' head-group head-'.$value_l['id']:'').'"'.( $condition ).( $operator ).'>';
													if ( isset($value_l['name']) ) {
														$output .= '<h4 class="vpanel-head-2">' . esc_html( $value_l['name'] ) . '</h4>' . "\n";
													}
													$output .= '<div class="discy-group-2 ' . $class . '">';
												}
											}
										}else if ($value_l["type"] == "checkbox") {
											$output .= '<label class="switch" for="'.$field_id.'_'.$i.'_'.$value_l['id'].'">
												<input id="'.$field_id.'_'.$i.'_'.$value_l['id'].'" class="checkbox discy-input discy-form-control" value="on" type="checkbox" name="'.$field_name.'['.$i.']['.$value_l['id'].']" '.checked( (isset($value_s[$value_l['id']])?$value_s[$value_l['id']]:""), "on", false).'>
												<label for="'.$field_id.'_'.$i.'_'.$value_l['id'].'" data-on="'.esc_attr__("ON","discy").'" data-off="'.esc_attr__("OFF","discy").'"></label>
											</label>';
										}else {
											$output .= '<input'.($value_l["type"] == "color"?" class='discy-color'":"").($value_l["type"] == "date"?" class='discy-datepicker'".(isset($value_l['js']) && $value_l['js'] != ""?" data-js='".json_encode($value_l['js'])."'":""):"").' name="'.$field_name.'['.$i.']['.$value_l['id'].']" type="'.($value_l["type"] == "hidden_id" || $value_l["type"] == "uniq_id"?"hidden":"text").'" value="'.stripslashes(htmlspecialchars(isset($value_s[$value_l['id']])?$value_s[$value_l['id']]:"")).'">';
										}
									if ($value_l["type"] != "heading-2" && $value_l['type'] != "heading-3") {
										$output .= '<div class="clear"></div></div>';
									}
								}
							$output .= '</div>
						</li>';
					}
				}
			$output .= '</ul>
			<div class="clear"></div>
			<input class="add_element'.($page == "meta" || $page == "terms" || $page == "author"?" no_theme_options":"").(isset($value['addto']) && $value['addto'] != ""?" add_element_to":"").'" type="button" value="'.(isset($value['button']) && $value['button'] != ""?$value['button']:esc_html__("+ Add a new element","discy")).'"'.(isset($field_id) && $field_id != ""?" data-id='".$field_id."'":"").(isset($value['title']) && $value['title'] != ""?" data-title='".$value['title']."'":"").'>
			<span data-js="'.esc_js($i+1).'" class="'.$field_id.'_j"></span>';
			break;
		
		// Upload images
		case 'upload_images';
			$output .= '<div class="images-uploaded">
				<a data-id="'.$field_id.'" data-name="'.$field_name.'" class="upload_image_button upload_image_button_m" href="#">'.esc_html__("Upload","discy").'</a>
				<div class="clear"></div>
				<ul id="'.$field_id.'">';
					$val = (isset($val) && is_array($val)?$val:array());
					if (isset($val) && is_array($val)) {
						foreach ($val as $value_image) {
							$image_url = wp_get_attachment_image($value_image,"thumbnail");
							$output .= "<li id='".$field_id."-item-".$value_image."' class='multi-images'>
								<div class='multi-image'>
									".$image_url."
									<input name='".$field_name."[]' type='hidden' value='".$value_image."'>
									<div class='image-overlay'></div>
									<div class='image-media-bar'>
										<a class='image-edit-media' title='".esc_attr__("Edit","discy")."' href='post.php?post=".$value_image."&amp;action=edit' target='_blank'>
											<span class='dashicons dashicons-edit'></span>
										</a>
										<a href='#' class='image-remove-media' title='".esc_attr__("Remove","discy")."'>
											<span class='dashicons dashicons-no-alt'></span>
										</a>
									</div>
								</div>
							</li>";
						}
					}
				$output .= '</ul>
			</div>';
			break;
		
		// Role
		case 'roles':
			global $wp_roles;
			$val = (isset($val) && is_array($val)?$val:array());
			$k = 0;
			$default_roles = $wp_roles->roles;
			$role = array_merge_recursive($default_roles,$val);
			if (isset($role["administrator"])) {
				unset($role["administrator"]);
			}
			if (isset($role["ban_group"])) {
				unset($role["ban_group"]);
			}
			$output .= '
			<input id="role_name" type="text" name="role_name" value="">
			<input id="role_add" data-id="'.$field_id.'" type="button" class="add_element not_add_element" value="'.esc_html__("Add a new role","discy").'">
			<div class="clear"></div>
			<ul id="roles_list" class="not-sort sort-sections sort-sections-ul roles_list">';
				if ($role) {
					foreach ($role as $key_rol => $value_rol) {$k++;
						$last_id = (isset($value_rol["id"])?esc_attr($value_rol["id"]):$key_rol);
						$output .= '<li><div class="widget-head">'.(isset($value_rol["group"])?esc_attr($value_rol["group"]):(isset($value_rol["name"])?esc_attr($value_rol["name"]):esc_attr($value_rol["id"]))).(isset($value_rol["new"]) && $value_rol["new"] == "new"?'<a class="del-roles-item del-builder-item del-sidebar-item"><span class="dashicons dashicons-trash"></span></a>':'').'</div>
							<div class="widget-content">
								<div class="widget-content-div">';
									if (isset($value_rol["new"]) && $value_rol["new"] == "new") {
										$output .= '<input id="'.$field_id.'['.$last_id.'][group]" type="hidden" name="'.$field_name.'['.$last_id.'][group]" value="'.esc_attr($value_rol["group"]).'">
										<input id="'.$field_id.'['.$last_id.'][new]" type="hidden" name="'.$field_name.'['.$last_id.'][new]" value="'.(isset($value_rol["new"]) && $value_rol["new"] != ""?esc_attr($value_rol["new"]):"new").'">';
									}
									$output .= '<input type="hidden" class="group_role_name" name="'.$field_name.'['.$last_id.'][id]" value="'.(isset($key_rol) && is_string($key_rol) && isset($value_rol["id"]) && isset($wp_roles->roles[$key_rol]) && is_array($wp_roles->roles[$key_rol])?esc_attr($value_rol["id"]):$last_id).'">
									<div class="clearfix"></div>';
									$roles_array = array(
										"ask_question"           => esc_html__("Select ON to allow users to ask questions.","discy"),
										"ask_question_payment"   => esc_html__("Select ON to allow users to ask a question without payment.","discy"),
										"show_question"          => esc_html__("Select ON to allow users to show the question.","discy"),
										"add_answer"             => esc_html__("Select ON to allow users to add an answer.","discy"),
										"add_answer_payment"     => esc_html__("Select ON to allow users to add an answer without payment.","discy"),
										"show_answer"            => esc_html__("Select ON to allow users to show the answer.","discy"),
										"add_group"              => esc_html__("Select ON to allow users to add a group.","discy"),
										"add_post"               => esc_html__("Select ON to allow users to add post.","discy"),
										"add_post_payment"       => esc_html__("Select ON to allow users to add post without payment.","discy"),
										"add_category"           => esc_html__("Select ON to allow users to add category.","discy"),
										"send_message"           => esc_html__("Select ON to allow users to send message.","discy"),
										"upload_files"           => esc_html__("Select ON to allow users to be able to upload files.","discy"),
										"approve_question"       => esc_html__("Select ON to auto approve the questions for the user.","discy"),
										"approve_answer"         => esc_html__("Select ON to auto approve the answers for the user.","discy"),
										"approve_group"          => esc_html__("Select ON to auto approve the group for the user.","discy"),
										"approve_post"           => esc_html__("Select ON to auto approve the posts for the user.","discy"),
										"approve_comment"        => esc_html__("Select ON to auto approve the comments for the user.","discy"),
										"approve_question_media" => esc_html__("Select ON to auto approve the questions for the user when media has been attached.","discy"),
										"approve_answer_media"   => esc_html__("Select ON to auto approve the answers for the user when media has been attached.","discy"),
										"without_ads"            => esc_html__("Select ON to remove ads for the user.","discy"),
									);
									if (isset($roles_array) && !empty($roles_array)) {
										foreach ($roles_array as $roles_key => $roles_value) {
											$output .= '<div class="section section-checkbox">
												<div class="option">
													<div class="controls">
														<label class="switch" for="'.$field_id.'['.$last_id.']['.$roles_key.']">
															<input id="'.$field_id.'['.$last_id.']['.$roles_key.']" value="on" class="checkbox discy-input" type="checkbox" name="'.$field_name.'['.$last_id.']['.$roles_key.']" '. (isset($value_rol[$roles_key])?checked($value_rol[$roles_key],"on",false):"on") .'>
															<label for="'.$field_id.'['.$last_id.']['.$roles_key.']" data-on="'.esc_attr__("ON","discy").'" data-off="'.esc_attr__("OFF","discy").'"></label>
														</label>
														<label class="explain explain-checkbox" for="'.$field_id.'['.$last_id.']['.$roles_key.']">'.$roles_value.'</label>
													</div>
												</div>
											</div>';
										}
									}
								$output .= '</div>
							</div>
						</li>';
					}
				}
			$output .= '</ul><div class="clear"></div>';
			break;
		
		// Select Box
		case 'select':
			$output .= '<div class="styled-select"><select class="discy-input discy-form-control" '.(isset($value['multiple']) && $value['multiple'] != ""?"multiple":"").' name="' . esc_attr( $field_name.(isset($value['multiple']) && $value['multiple'] != ""?"[]":"") ) . '" id="'.$field_id.'">
			'.(isset($value['first'])?'<option>'.$value['first'].'</option>':'');
			foreach ($value['options'] as $key => $option ) {
				$output .= '<option'. (isset($value['multiple']) && $value['multiple'] != ""?(isset($val) && is_array($val) && in_array($key,$val)?' selected="selected"':""):selected( $val, $key, false )) .' value="' . esc_attr( $key ) . '">' . esc_html( $option ) . '</option>';
			}
			$output .= '</select></div>';
			break;

		// Select advanced
		case 'select_advanced':
			$output .= '<select class="discy-input" '.(isset($value['multiple']) && $value['multiple'] != ""?"multiple":"").' name="' . esc_attr( $field_name.'[]' ) . '" id="'.$field_id.'">';
			foreach ($value['options'] as $key => $option ) {
				$output .= '<option'. selected( $val, $key, false ) .' value="' . esc_attr( $key ) . '">' . esc_html( $option ) . '</option>';
			}
			$output .= '</select>';
			break;
		
		// Radio Box
		case 'radio':
			foreach ($value['options'] as $key => $option) {
				$id = $option_name . '-' . $field_id .'-'. $key;
				$output .= '<input class="discy-input discy-radio discy-form-control'.(isset($value['class'])?" ".esc_attr($value['class']):'').'" type="radio" name="'.$field_name.'" id="' . esc_attr( $id ) . '" value="'. esc_attr( $key ) . '" '. checked( $val, $key, false) .'><label for="' . esc_attr( $id ) . '">' . esc_html( $option ) . '</label>';
			}
			break;

		// Image Selectors
		case 'images':
			$output .= discy_option_images($field_id,(isset($value['width']) && $value['width'] != ""?$value['width']:""),(isset($value['height']) && $value['height'] != ""?$value['height']:""),$value['options'],$val,(isset($value['class']) && $value['class'] != ""?" ".$value['class']:""),$field_name,"","","no");
			break;

		// Checkbox
		case 'checkbox':
			$output .= '<label class="switch" for="'.$field_id.'">
				<input id="'.$field_id.'" class="checkbox discy-input discy-form-control" value="on" type="checkbox" name="'.$field_name.'" '.checked( $val, "on", false).'>
				<label for="'.$field_id.'" data-on="'.esc_attr__("ON","discy").'" data-off="'.esc_attr__("OFF","discy").'"></label>
			</label>
			<label class="explain explain-checkbox" for="'.$field_id.'">' . wp_kses( $explain_value, $allowedtags) . '</label>';
			break;

		// Multicheck
		case 'multicheck':
			$value_option = array();
			$output .= '<ul id="'.(isset($value['id']) && $value['id'] != ""?$value['id']:"").'-ul"'.(isset($value['sort']) && $value['sort'] == "yes"?' class="sort-sections sort-sections-ul"':'').'>';
			if (isset($value['sort']) && $value['sort'] == "yes") {
				$k_sort = 0;
				if (isset($val) && !empty($val) && is_array($val)) {
					$value_option = $val;
				}else {
					$value_option = $value['options'];
				}
			}else {
				$value_option = $value['options'];
			}
			
			if ($value['options'] != $val) {
				if (isset($val) && is_array($val)) {
					foreach ($val as $key_s => $key_s) {
						if (!isset($value['options'][$key_s]) && !isset($val[$key_s]["cat"]) && !isset($val[$key_s]["page"]) && !isset($val[$key_s]["builder"])) {
							unset($value_option[$key_s]);
						}
					}
				}
				if (isset($value['options']) && is_array($value['options'])) {
					foreach ($value['options'] as $key_s => $value_s) {
						if (!isset($val[$key_s])) {
							$value_option = array_merge($value_option,array($key_s => $value_s));
						}
					}
				}
			}
			
			foreach ($value_option as $key => $option) {
				$o_option = $option;
				$output = apply_filters("discy_show_multicheck_field",$output,$value_option,$key,$o_option,$val,$option_name,$field_name,$field_id);
				if (!isset($o_option["builder"])) {
					$checked = '';
					if (isset($value['values']) && ((isset($val[$key]["cat"]) && $val[$key]["cat"] == "yes") || (isset($val[$key]["page"]) && $val[$key]["page"] == "yes"))) {
						$label = $value['values'][$option["value"]];
						$option = preg_replace('/[^a-zA-Z0-9._\-]/', '', (isset($value["strtolower"]) && $value["strtolower"] == "not"?$key:strtolower($key)));
					}else if (isset($value['sort']) && $value['sort'] == "yes") {
						$k_sort++;
						$option = preg_replace('/[^a-zA-Z0-9._\-]/', '', (isset($value["strtolower"]) && $value["strtolower"] == "not"?$key:strtolower($key)));
						if (isset($val[$key]["cat"]) && $val[$key]["cat"] == "yes") {
							if ($val[$option]["value"] == 0 || $val[$option]["value"] === 0) {
								$val[$option]["value"] = "q-0";
							}
							if (is_numeric($val[$option]["value"])) {
								$label = get_term($val[$option]["value"]);
								$label = (isset($label->name)?$label->name:"");
							}else if ($val[$option]["value"] === "q-0") {
								$label = esc_html__("All Question Categories","discy");
							}else {
								$label = esc_html__("All Categories","discy");
							}
						}else if (isset($val[$key]["page"]) && $val[$key]["page"] == "yes") {
							if (is_numeric($val[$option]["value"])) {
								$label = get_the_title($val[$option]["value"]);
							}
						}else {
							$label = (isset($value['options'][$option]["sort"])?$value['options'][$option]["sort"]:"");
						}
					}else {
						$label = $o_option;
						$option = preg_replace('/[^a-zA-Z0-9._\-]/', '', (isset($value["strtolower"]) && $value["strtolower"] == "not"?$key:strtolower($key)));
					}
					
					$id = $option_name . '-' . $field_id . '-'. $option;
					$name = $field_name.'[' . $option .']';
					
					if ( isset($val[$option]) ) {
						if (isset($value['sort']) && $value['sort'] == "yes") {
							if (isset($val[$option]["value"])) {
								$checked = checked($val[$option]["value"], $option, false);
							}
						}else {
							if (isset($val[$option])) {
								$checked = checked($val[$option], $option, false);
							}
						}
					}
					$output .= '<li'.(isset($value['sort']) && $value['sort'] == "yes" && ((isset($val[$key]["cat"]) && $val[$key]["cat"] == "yes") || (isset($val[$key]["page"]) && $val[$key]["page"] == "yes"))?" class='additions-li' id='".$value['id']."_additions_li_".$val[$key]["value"]."'":'').'>';
						if (isset($value['sort']) && $value['sort'] == "yes") {
							$output .= '<div class="widget-head"><div><a class="widget-handle"><span class="dashicons dashicons-editor-justify"></span></a>'.((isset($val[$key]["cat"]) && $val[$key]["cat"] == "yes") || (isset($val[$key]["page"]) && $val[$key]["page"] == "yes")?'<a class="del-cat-item del-builder-item"><span class="dashicons dashicons-trash"></span></a>':'').'</div>';
							if ((isset($val[$key]["cat"]) && $val[$key]["cat"] == "yes") || (isset($val[$key]["page"]) && $val[$key]["page"] == "yes")) {
								if (isset($val[$key]["cat"]) && $val[$key]["cat"] == "yes") {
									$item_type = 'cat';
								}else {
									$item_type = 'page';
								}
								$name_sort = (isset($value['id']) && $value['id'] != ""?esc_attr(($page == 'widgets'?$post_term->get_field_name($value['id']):($page == 'meta' || $page == 'terms' || $page == 'author'?$value['id']:$option_name.'['.$value['id'].']'))):"");
								$output .= '<input name="'.$name_sort.'['.$item_type.'-'.$val[$key]["value"].']['.$item_type.']" value="yes" type="hidden"><input name="'.$name_sort.'['.$item_type.'-'.$val[$key]["value"].'][value]" value="'.$val[$key]["value"].'" type="hidden">';
							}else {
								$output .= '<input type="hidden" name="'.esc_attr( $name.'[sort]' ).'" value="'.esc_html( $label ).'">';
							}
						}
						if (isset($o_option["default"]) || (isset($val[$key]) && is_array($val[$key]) && (array_key_exists('cat',$val[$key]) || array_key_exists('page',$val[$key])))) {
							if (isset($o_option["default"])) {
								$output .= '<input value="'.$option.'" type="hidden" name="'.esc_attr( $name.(isset($value['sort']) && $value['sort'] == "yes"?'[value]':'') ).'">
								<input value="yes" type="hidden" name="'.esc_attr( $name.'[default]' ).'">';
							}
						}else {
							$output .= '<label class="switch" for="'.esc_attr($id).'">
								<input value="0" type="hidden" name="'.esc_attr( $name.(isset($value['sort']) && $value['sort'] == "yes"?'[value]':'') ).'">
								<input id="'.esc_attr($id).'" value="'.$option.'" class="checkbox discy-input discy-form-control" type="checkbox" name="'.esc_attr( $name.(isset($value['sort']) && $value['sort'] == "yes"?'[value]':'') ).'" '. $checked .'>
								<label for="'.esc_attr($id).'" data-on="'.esc_attr__("ON","discy").'" data-off="'.esc_attr__("OFF","discy").'"></label>
							</label>';
						}
						$output .= '<label for="'.esc_attr($id).'">' . esc_html( $label ) . '</label>';
						if (isset($value['sort']) && $value['sort'] == "yes") {
							$output .= '</div>';
						}
					$output .= '</li>';
				}
			}
			$output .= '</ul>';
			break;

		// Color picker
		case 'color':
			$default_color = '';
			if ( isset($value['std']) ) {
				if ( $val !=  $value['std'] )
					$default_color = ' data-default-color="' .$value['std'] . '" ';
			}
			$output .= '<input name="'.$field_name.'" id="'.$field_id.'" class="discy-color '.(isset($value['class'])?esc_attr($value['class']):'').'"  type="text" value="' . esc_attr( $val ) . '"' . $default_color .'>';
			break;

		// Uploader
		case 'upload':
			$output .= discy_options_uploader($field_id,$val,null,$field_name,null,(isset($value['options'])?$value['options']:array()));
			break;

		// Typography
		case 'typography':
			
			unset( $font_size, $font_style, $font_face, $font_color );
			$font_size = $font_face = $font_style = $font_color = '';

			$typography_defaults = array(
				'size' => '',
				'face' => '',
				'style' => '',
				'color' => ''
			);

			$typography_stored = wp_parse_args( $val, $typography_defaults );

			$typography_options = array(
				'sizes' => discy_recognized_font_sizes(),
				'faces' => discy_recognized_font_faces(),
				'styles' => discy_recognized_font_styles(),
				'color' => true
			);

			if ( isset( $value['options'] ) ) {
				$typography_options = wp_parse_args( $value['options'], $typography_options );
			}

			// Font Size
			if ( $typography_options['sizes'] ) {
				$font_size = '<select class="discy-typography discy-typography-size" name="' . esc_attr( $field_name.'[size]' ) . '" id="' . esc_attr( $field_id . '_size' ) . '">';
				$sizes = $typography_options['sizes'];
				$font_size .= '<option value="" ' . selected( "default", "default", false ) . '>'.esc_html__("Size","discy").'</option>';
				foreach ( $sizes as $i ) {
					$size = $i . 'px';
					$font_size .= '<option value="' . esc_attr( $size ) . '" ' . (isset($typography_stored['size']) && is_string($typography_stored['size'])?selected( $typography_stored['size'], $size, false ):"") . '>' . esc_html( $size ) . '</option>';
				}
				$font_size .= '</select>';
			}

			// Font Face
			if ( $typography_options['faces'] ) {
				$font_face = '<input class="discy-typography discy-typography-face" name="' . esc_attr( $field_name.'[face]' ) . '" id="' . esc_attr( $field_id . '_face' ) . '" value="'.$typography_stored['face'].'">';
			}

			// Font Styles
			if ( $typography_options['styles'] ) {
				$font_style = '<select class="discy-typography discy-typography-style" name="'.$field_name.'[style]" id="'. $field_id.'_style">';
				$styles = $typography_options['styles'];
				foreach ( $styles as $key => $style ) {
					$font_style .= '<option value="' . esc_attr( $key ) . '" ' . selected( $typography_stored['style'], $key, false ) . '>'. $style .'</option>';
				}
				$font_style .= '</select>';
			}

			// Font Color
			if ( $typography_options['color'] ) {
				$default_color = '';
				if ( isset($value['std']['color']) ) {
					if ( $val !=  $value['std']['color'] )
						$default_color = ' data-default-color="' .$value['std']['color'] . '" ';
				}
				$font_color = '<input name="' . esc_attr( $field_name.'[color]' ) . '" id="' . esc_attr( $field_id . '_color' ) . '" class="discy-color discy-typography-color"  type="text" value="' . esc_attr( $typography_stored['color'] ) . '"' . $default_color .'>';
			}

			// Allow modification/injection of typography fields
			$typography_fields = compact( 'font_size', 'font_face', 'font_style', 'font_color' );
			$typography_fields = apply_filters( 'discy_typography_fields', $typography_fields, $typography_stored, $option_name, $value );
			$output .= implode( '', $typography_fields );

			break;

		// Background
		case 'background':
			$background = $val;
			if (isset($value['options']) && is_array($value['options']) && isset($value['options']['color'])) {
				// Background Color
				$default_color = '';
				if ( isset( $value['std']['color'] ) ) {
					if ( $val !=  $value['std']['color'] )
						$default_color = ' data-default-color="' .$value['std']['color'] . '" ';
				}
				$output .= '<input name="' . esc_attr( $field_name.'[color]' ) . '" id="' . esc_attr( $field_id . '_color' ) . '" class="discy-color discy-background-color"  type="text" value="'.(isset($background['color'])?esc_attr($background['color']):"").'"' . $default_color .'>';
			}
			
			// Background Image
			$background_image = (isset($background['image']) && $background['image'] != ""?$background['image']:"");
			if (isset($value['options']) && is_array($value['options']) && isset($value['options']['image'])) {
				$output .= discy_options_uploader($field_id,$background_image,null,esc_attr($field_name.'[image]'));
			}
			$class = 'discy-background-properties '.(isset($value['class'])?esc_attr($value['class']):'').'';
			if ( !empty($background_image) ) {
				$class .= ' hide';
			}
			$output .= '<div class="' . esc_attr( $class ) . '">';
			if (isset($value['options']) && is_array($value['options']) && isset($value['options']['repeat'])) {
				// Background Repeat
				$output .= '<select class="discy-background discy-background-repeat" name="' . esc_attr( $field_name.'[repeat]'  ) . '" id="' . esc_attr( $field_id . '_repeat' ) . '">';
				$repeats = discy_recognized_background_repeat();
	
				foreach ($repeats as $key => $repeat) {
					$output .= '<option value="' . esc_attr( $key ) . '" ' . selected((isset($background['repeat'])?esc_attr($background['repeat']):""), $key, false ) . '>'. esc_html( $repeat ) . '</option>';
				}
				$output .= '</select>';
			}
			if (isset($value['options']) && is_array($value['options']) && isset($value['options']['position'])) {
				// Background Position
				$output .= '<select class="discy-background discy-background-position" name="' . esc_attr( $field_name.'[position]' ) . '" id="' . esc_attr( $field_id . '_position' ) . '">';
				$positions = discy_recognized_background_position();
	
				foreach ($positions as $key=>$position) {
					$output .= '<option value="' . esc_attr( $key ) . '" ' . selected((isset($background['position'])?esc_attr($background['position']):""), $key, false ) . '>'. esc_html( $position ) . '</option>';
				}
				$output .= '</select>';
			}
			if (isset($value['options']) && is_array($value['options']) && isset($value['options']['attachment'])) {
				// Background Attachment
				$output .= '<select class="discy-background discy-background-attachment" name="' . esc_attr( $field_name.'[attachment]' ) . '" id="' . esc_attr( $field_id . '_attachment' ) . '">';
				$attachments = discy_recognized_background_attachment();
	
				foreach ($attachments as $key => $attachment) {
					$output .= '<option value="' . esc_attr( $key ) . '" ' . selected((isset($background['attachment'])?esc_attr($background['attachment']):""), $key, false ) . '>' . esc_html( $attachment ) . '</option>';
				}
				$output .= '</select>';
			}
			$output .= '</div>';

			break;

		// export
		case 'export':
			$output .= '<textarea id="'.$field_id.'" class="discy-input builder_select" rows="8">' . esc_textarea($value['export']) . '</textarea>';
			break;
		
		// import
		case 'import':
			$output .= '<textarea id="'.$field_id.'" class="discy-input" name="'.$field_name.'" rows="8"></textarea>';
			break;
			
		// Editor
		case 'editor':
			$output .= '<div class="explain">'.wp_kses( $explain_value, $allowedtags ).'</div>'."\n";
			$rich_editing = get_user_meta(get_current_user_id(), 'rich_editing', true);
			if ($rich_editing == true) {
				$output .= '<div class="discy_editor"></div>';
			}
			echo ($output);
			$default_editor_settings = array(
				'textarea_name' => $field_name,
				'media_buttons' => "discy_editor",
				'tinymce' => array( 'plugins' => 'wordpress' )
			);
			$editor_settings = array();
			if ( isset( $value['settings'] ) ) {
				$editor_settings = $value['settings'];
			}
			$editor_settings = apply_filters("discy_editor_settings",$editor_settings,$field_id);
			$editor_settings = array_merge($default_editor_settings,$editor_settings);
			wp_editor($val,$field_id,$editor_settings);
			$output = '';
			break;
		
		// Info
		case 'info':
			$output .= '<div data-type="'.$value['type'].'"'.(isset($value['id'])?' data-id="'.esc_attr( $value['id'] ).'"':"").( $condition ).( $operator ).(isset($value['id'])?' id="'.esc_attr( $value['id'] ).'"':'').' class="'.esc_attr( $class ).'">'."\n";
			if ( isset($value['name']) ) {
				$output .= '<div class="alert-message'.(isset($value['alert']) && $value['alert'] != ""?" ".$value['alert']:"").'"><p><span>' . $value['name'] . '</span></p></div>' . "\n";
			}
			if ( isset( $value['desc'] ) ) {
				$output .= apply_filters('discy_sanitize_info', $value['desc'] ) . "\n";
			}
			$output .= '</div>' . "\n";
			break;

		// Heading for Navigation
		case 'heading':
			$counter++;
			if ( $counter >= 2 ) {
				$output .= '</div>'."\n";
			}
			$class = '';
			$class = ! empty($value['id'])?$value['id']:$value['name'];
			$class = preg_replace('/[^a-zA-Z0-9._\-]/', '', strtolower($class) );
			if ( ! array_key_exists( 'template', $value ) || ! is_string( $value['template'] ) ) {
				$value['template'] = '';
			}
			$template = empty( $value['template'] ) ? '' : ' data-template="'. esc_attr( $value['template'] ) .'"';
			if (isset($value['template']) && $value['template'] != "" && $value['template'] != $wp_page_template) {
				$class .= ' hide';
			}
			$output .= '<div'.$template.' id="options-group-'.$counter.'" class="discy-group '.$class.'">
			<h3>'.($value['icon'] != ''?'<span class="dashicons dashicons-'.$value['icon'].'"></span>':''). esc_html( $value['name'] ) . '</h3>' . "\n";
			if (isset($value['options'])) {
				$output .= '<ul class="discy_tabs"'.(isset($value['std']) && $value['std'] != ""?' data-std="#head-' . esc_attr( $value['std'] ) . '"':'').'>';
				$k_a = 0;
				foreach ( $value['options'] as $key_h => $value_h ) {
					$k_a++;
					$output .= '<li><a title="' . esc_attr( $value_h ) . '" href="' . esc_attr( '#head-'.  $key_h ) . '">' . esc_html( $value_h ) . '</a></li>';
				}
				$output .= '</ul>';
			}
			break;
			
		case 'heading-2':
			if ( isset($value['end']) && $value['end'] == "end" ) {
				if ( isset($value['div']) && $value['div'] == "div" ) {
					$output .= '</div>'."\n";
				}else {
					$output .= '</div></div>'."\n";
				}
			}else {
				if ( isset($value['div']) && $value['div'] == "div" ) {
					$output .= '<div class="'.$wrap_class.'" id="'.(isset($value['id']) && $value['id'] != ""?"wrap_".$value['id']:"").'"'.( $condition ).( $operator ).'>';
					if ( isset($value['name']) ) {
						$output .= '<h4 class="vpanel-head-2">' . esc_html( $value['name'] ) . '</h4>' . "\n";
					}
				}else {
					$class = '';
					$class = ! empty($value['id'])?$value['id']:(isset($value['name']) && $value['name'] != ""?$value['name']:"");
					$class = preg_replace('/[^a-zA-Z0-9._\-]/', '', strtolower($class) );
					$output .= '<div'.(isset($value['id'])?' id="head-'.$value['id'].'"':'').' class="'.$options_group.(isset($value['id'])?' head-group head-'.$value['id']:'').'"'.( $condition ).( $operator ).'>';
					if ( isset($value['name']) ) {
						$output .= '<h4 class="vpanel-head-2">' . esc_html( $value['name'] ) . '</h4>' . "\n";
					}
					$output .= '<div class="discy-group-2 ' . $class . '">';
				}
			}
			break;
		}

		if (isset($value['type'])) {
			if ($value['type'] != "heading" && $value['type'] != "heading-2" && $value['type'] != "heading-3" && $value['type'] != "info" && $value['type'] != "content" && $value['type'] != 'hidden') {
				$output .= '</div>';
				if ( $value['type'] != "checkbox" && $value['type'] != "editor" && $explain_value != "" ) {
					$output .= '<div class="explain discy_help"><div class="tooltip_s" title="'.wp_kses( $explain_value, $allowedtags).'"><i class="dashicons dashicons-info"></i></div></div>'."\n";
				}
				$output .= '</div></div>'."\n";
			}
		}

		echo ($output);
	}
}?>