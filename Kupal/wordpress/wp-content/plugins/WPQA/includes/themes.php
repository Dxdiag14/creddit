<?php

/* @author    2codeThemes
*  @package   WPQA/includes
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* WPQA name of the theme */
function wpqa_theme_name() {
	$get_theme_name = get_option("get_theme_name");
	return $get_theme_name;
}

/* Has Discy theme */
if (!function_exists('has_discy')) {
	function has_discy() {
		$get_theme_name = get_option("get_theme_name");
		return ($get_theme_name == "discy"?true:false);
	}
}

/* WPQA theme sidebars */
function wpqa_sidebars() {
	if (has_discy()) {
		$return = discy_sidebars("sidebar_where");
	}
	return (isset($return)?$return:"");
}

/* Theme ads */
function wpqa_ads($adv_type_meta,$adv_code_meta,$adv_href_meta,$adv_img_meta,$li = false,$page = false,$class = false,$author_cat = false,$question_columns = false) {
	if (has_discy()) {
		$return = discy_ads($adv_type_meta,$adv_code_meta,$adv_href_meta,$adv_img_meta,$li,$page,$class,$author_cat,$question_columns);
	}
	return (isset($return)?$return:"");
}

/* Theme meta */
function wpqa_theme_meta($date = "",$category = "",$comment = "",$asked = "",$icons = "",$views = "",$post_id = 0,$post = object) {
	if (has_discy()) {
		discy_meta($date,$category,$comment,$asked,$icons,$views,$post_id,$post);
	}
}

/* Theme admin author */
function wpqa_admin_author($user_id = "") {
	if (has_discy()) {
		$return = discy_admin_author($user_id);
	}
	return (isset($return) && is_array($return) && !empty($return)?$return:array());
}

/* Theme author options */
function wpqa_author_options($settings = array(),$option_name = "",$page = "options",$post_term = null,$options_arrgs = array()) {
	if (has_discy()) {
		discy_admin_fields_class::discy_admin_fields($settings,$option_name,$page,$post_term,$options_arrgs);
	}
}?>