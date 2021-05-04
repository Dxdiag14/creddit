<?php $post_id_main = (isset($post_id_main)?$post_id_main:"");
$group_pagination   = discy_options("group_pagination");
if (isset($wp_page_template) && $wp_page_template == "template-groups.php") {
	$custom_group_setting = discy_post_meta("custom_group_setting",$post_id_main);
	if ($custom_group_setting == "on") {
		$page_tamplate    = true;
		$group_pagination = discy_post_meta("group_pagination",$post_id_main);
		$group_number     = discy_post_meta("group_number",$post_id_main);
		$group_display    = discy_post_meta("group_display_g",$post_id_main);
		$group_order      = discy_post_meta("group_order_g",$post_id_main);
	}
}
$group_number  = (isset($group_number) && $group_number != ""?$group_number:get_option("posts_per_page"));
$group_display = (isset($group_display) && $group_display != ""?$group_display:"all");
$group_order   = (isset($group_order) && $group_order != ""?$group_order:"date");
if ($group_order == "users") {
	$group_orderby = "group_users";
}else if ($group_order == "posts") {
	$group_orderby = "group_posts";
}
$orderby_array = (isset($group_orderby)?array('orderby' => 'meta_value_num','order' => 'DESC',"meta_query" => array(array('type' => 'numeric',"key" => $group_orderby,"value" => 0,"compare" => ">="))):array());?>