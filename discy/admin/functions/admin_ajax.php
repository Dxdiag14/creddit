<?php /* Fetch options */
function discy_parse_str($string) {
	if ('' == $string) {
		return false;
	}
	$result = array();
	$pairs  = explode('&',$string);
	foreach ($pairs as $key => $pair) {
		parse_str($pair,$params);
		$k = key($params);
		if (!isset($result[$k])) {
			$result += $params;
		}else {
			$result[$k] = discy_array_merge_distinct($result[$k],$params[$k]);
		}
	}

	return $result;
}
function discy_array_merge_distinct(array $array1,array $array2) {
	$merged = $array1;
	foreach ($array2 as $key => $value) {
		if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
			$merged[$key] = discy_array_merge_distinct($merged[$key],$value);
		}else if (is_numeric($key) && isset($merged[$key])) {
			$merged[] = $value;
		}else {
			$merged[$key] = $value;
		}
	}
	return $merged;
}
/* Update options */
function discy_update_options() {
	$_POST['data'] = stripslashes($_POST['data']);
	$values = discy_parse_str($_POST['data']);
	do_action("discy_update_options",$values);
	$setting_options = $values[discy_options];
	unset($setting_options['export_setting']);
	$setting_options = apply_filters("discy_options_values",$setting_options);
	/* Save */
	update_option(discy_options,$setting_options);
	update_option("FlushRewriteRules",true);
	die();
}
add_action( 'wp_ajax_discy_update_options', 'discy_update_options' );
/* Import options */
function discy_import_options() {
	$values = $_POST['data'];
	if ($values != "") {
		$data = base64_decode($values);
		$data = json_decode($data,true);
		$array_options = array(discy_options,"sidebars");
		foreach ($array_options as $option) {
			if (isset($data[$option])) {
				update_option($option,$data[$option]);
			}else{
				delete_option($option);
			}
		}
		echo 2;
		update_option("FlushRewriteRules",true);
		die();
	}
	update_option("FlushRewriteRules",true);
	die();
}
add_action( 'wp_ajax_discy_import_options', 'discy_import_options' );
/* Reset options */
function discy_reset_options() {
	$options = discy_admin_options();
	foreach ($options as $option) {
		if (isset($option['id']) && isset($option['std'])) {
			$option_res[$option['id']] = $option['std'];
		}
	}
	update_option(discy_options,$option_res);
	update_option("FlushRewriteRules",true);
	die();
}
add_action('wp_ajax_discy_reset_options','discy_reset_options');
/* Delete role */
function discy_delete_role() {
	$roles_val = $_POST["roles_val"];
	if (get_role($roles_val)) {
		remove_role($roles_val);
	}
}
add_action('wp_ajax_discy_delete_role','discy_delete_role');
/* Admin live search */
function discy_admin_live_search() {
	$search_value = esc_attr($_POST['search_value']);
	if ($search_value != "") {
		$search_value_ucfirst = ucfirst(esc_attr($_POST['search_value']));
		$discy_admin_options = discy_admin_options();
		$k = 0;
		if (isset($discy_admin_options) && is_array($discy_admin_options)) {?>
			<ul>
				<?php foreach ($discy_admin_options as $key => $value) {
					if (isset($value["type"]) && $value["type"] != "content" && $value["type"] != "info" && $value["type"] != "heading" && $value["type"] != "heading-2" && $value['type'] != "heading-3" && ((isset($value["name"]) && $value["name"] != "" && (strpos($value["name"],$search_value) !== false || strpos($value["name"],$search_value_ucfirst) !== false)) || (isset($value["desc"]) && $value["desc"] != "" && (strpos($value["desc"],$search_value) !== false || strpos($value["desc"],$search_value_ucfirst) !== false)))) {
						$find_resluts = true;
						$k++;
						if ((isset($value["name"]) && $value["name"] != "" && (strpos($value["name"],$search_value) !== false || strpos($value["name"],$search_value_ucfirst) !== false))) {?>
							<li><a href="section-<?php echo esc_html($value["id"])?>"><?php echo str_ireplace($search_value,"<strong>".$search_value."</strong>",esc_html($value["name"]))?></a></li>
						<?php }else {?>
							<li><a href="section-<?php echo esc_html($value["id"])?>"><?php echo str_ireplace($search_value,"<strong>".$search_value."</strong>",esc_html($value["desc"]))?></a></li>
						<?php }
						if ($k == 10) {
							break;
						}
					}
				}
				if (!isset($find_resluts)) {?>
					<li><?php esc_html_e("Sorry, no results.","discy")?></li>
				<?php }?>
			</ul>
		<?php }
	}
	die();
}
add_action('wp_ajax_discy_admin_live_search','discy_admin_live_search');
/* Categories_ajax */
function discy_categories_ajax() {
	$name = (isset($_POST["name"])?esc_attr($_POST["name"]):"");
	$name_2 = (isset($_POST["name_2"])?esc_attr($_POST["name_2"]):"");
	$tabs = (isset($_POST["tabs"])?esc_attr($_POST["tabs"]):"");
	if ($tabs == "yes") {
		echo '<li><label class="selectit"><input value="on" type="checkbox" name="'.$name.'[show_all_categories]">'.esc_html__('Show All Categories',"discy").'</label></li>';
	}
	echo discy_categories_checklist(array("name" => $name.$name_2,"id" => $name.$name_2));
	die();
}
add_action('wp_ajax_discy_categories_ajax','discy_categories_ajax');?>