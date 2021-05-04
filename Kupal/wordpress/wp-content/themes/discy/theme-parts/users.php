<?php if (is_page()) {
	$user_group    = discy_post_meta("user_group");
	$user_sort     = discy_post_meta("user_sort");
	$user_style    = discy_post_meta("user_style");
	$masonry_style = discy_post_meta("masonry_user_style");
	$user_order    = discy_post_meta("user_order");
	$number        = discy_post_meta("users_per_page");
	$number        = (isset($number) && $number > 0?$number:apply_filters('discy_users_per_page',get_option('posts_per_page')));
}else {
	$user_group = "";
	$user_style    = discy_options("user_style_pages");
	$masonry_style = discy_options("masonry_user_style");
	$user_sort     = (isset($user_sort)?$user_sort:"user_registered");
	$user_sort     = (isset($_GET["user_filter"]) && $_GET["user_filter"] != ""?esc_html($_GET["user_filter"]):$user_sort);
	$user_order    = "DESC";
	$number        = discy_options("users_per_page");
	$number        = (isset($number) && $number > 0?$number:apply_filters('users_per_page',get_option('posts_per_page')));
}
global $wpdb;
$discy_sidebar  = discy_sidebars("sidebar_where");
$active_points  = discy_options("active_points");
$paged          = (get_query_var("paged") != ""?(int)get_query_var("paged"):(get_query_var("page") != ""?(int)get_query_var("page"):1));
$offset         = ($paged -1) * $number;
$meta_key_array = array();
$implode_array  = "";
$capabilities   = $wpdb->get_blog_prefix(get_current_blog_id()).'capabilities';

if (!empty($user_group)) {
	foreach ($user_group as $role => $name) {
		if ($name != "0") {
			$all_role_array[] = $name;
			$meta_key_array[] = "( $wpdb->usermeta.meta_key = '$capabilities' AND $wpdb->usermeta.meta_value RLIKE '$name' )";
		}else {
			unset($user_group[$role]);
		}
	}
	if (!empty($meta_key_array)) {
		$implode_array = "AND (".implode(" OR ",$meta_key_array).")";
	}
}

$user_sort    = (isset($_GET["user_filter"]) && $_GET["user_filter"] != ""?esc_html($_GET["user_filter"]):(isset($user_sort) && $user_sort != ""?$user_sort:"user_registered"));
$user_order   = (isset($_GET["user_filter"]) && ($_GET["user_filter"] == "ID" || $_GET["user_filter"] == "display_name" || $_GET["user_filter"] == "user_registered")?"ASC":$user_order);
$search_value = wpqa_search();
$search_value = apply_filters("wpqa_search_value_filter",$search_value);
$name_array   = preg_split("/[\s,]+/",$search_value);
if ($search_value != "") {
	$search_args = " AND ( $wpdb->users.user_login RLIKE '$search_value' OR $wpdb->users.user_nicename RLIKE '$search_value') OR ( ( $wpdb->usermeta.meta_key = 'user_login' AND $wpdb->usermeta.meta_value RLIKE '$search_value' ) OR ( $wpdb->usermeta.meta_key = 'display_name' AND $wpdb->usermeta.meta_value RLIKE '$search_value' ) OR ( $wpdb->usermeta.meta_key = 'nickname' AND $wpdb->usermeta.meta_value RLIKE '$search_value' ) OR ( $wpdb->users.display_name RLIKE '".$search_value."' ) OR ( $wpdb->usermeta.meta_key = 'first_name' AND $wpdb->usermeta.meta_value RLIKE '".(isset($name_array[0]) && $name_array[0] != ""?$name_array[0]:$search_value)."' ) OR ( $wpdb->usermeta.meta_key = 'last_name' AND $wpdb->usermeta.meta_value RLIKE '".(isset($name_array[0]) && $name_array[0] != ""?$name_array[0]:$search_value)."' ) ) ";
	$implode_array = " ";
}else {
	$search_args = " ";
}

if ($user_sort == "the_best_answer" || $user_sort == "post_count" || $user_sort == "question_count" || $user_sort == "answers" || $user_sort == "comments") {
	if ($user_sort == "the_best_answer") {
		$query = $wpdb->prepare("SELECT DISTINCT SQL_CALC_FOUND_ROWS $wpdb->users.ID FROM $wpdb->users INNER JOIN $wpdb->usermeta ON ( $wpdb->users.ID = $wpdb->usermeta.user_id ) LEFT OUTER JOIN ( SELECT user_id, COUNT(*) as total FROM $wpdb->comments INNER JOIN $wpdb->commentmeta ON ( $wpdb->comments.comment_id = $wpdb->commentmeta.comment_id) WHERE $wpdb->comments.comment_approved = 1 AND $wpdb->commentmeta.meta_key = 'best_answer_comment' GROUP BY user_id ) c ON ($wpdb->users.ID = c.user_id) WHERE %s=1".$search_args.$implode_array." ORDER BY total $user_order LIMIT $offset,$number",1);
	}else if ($user_sort == "post_count" || $user_sort == "question_count") {
		$query = $wpdb->prepare("SELECT DISTINCT SQL_CALC_FOUND_ROWS $wpdb->users.ID FROM $wpdb->users INNER JOIN $wpdb->usermeta ON ( $wpdb->users.ID = $wpdb->usermeta.user_id ) LEFT OUTER JOIN ( SELECT post_author, COUNT(*) as post_count FROM $wpdb->posts WHERE ( ( post_type = '".($user_sort == "question_count"?"question":"post")."' AND ( post_status = 'publish' OR post_status = 'private' ) ) ) GROUP BY post_author ) p ON ($wpdb->users.ID = p.post_author) WHERE %s=1".$search_args.$implode_array." ORDER BY post_count $user_order LIMIT $offset,$number",1);
	}else {
		$query = $wpdb->prepare("SELECT DISTINCT SQL_CALC_FOUND_ROWS $wpdb->users.ID FROM $wpdb->users INNER JOIN $wpdb->usermeta ON ( $wpdb->users.ID = $wpdb->usermeta.user_id ) LEFT OUTER JOIN ( SELECT user_id, COUNT(*) as total FROM $wpdb->comments INNER JOIN $wpdb->posts ON ( $wpdb->comments.comment_post_ID = $wpdb->posts.ID ) WHERE $wpdb->posts.post_type = '".($user_sort == "answers"?"question":"post")."' AND ( $wpdb->posts.post_status = 'publish' OR $wpdb->posts.post_status = 'private' ) GROUP BY user_id ) c ON ($wpdb->users.ID = c.user_id) WHERE %s=1".$search_args.$implode_array." ORDER BY total $user_order LIMIT $offset,$number",1);
	}
	$query = $wpdb->get_results($query);
	$total_query = $wpdb->get_var('SELECT FOUND_ROWS()');
	$total_pages = ceil($total_query/$number);
}else if (($user_sort == "points" && $active_points == "on") || $user_sort == "followers") {
	add_action('pre_user_query','wpqa_custom_search_users');
	$user_key = ($user_sort == "followers"?"count_following_you":$user_sort);
	$args = array(
		'role__in'       => (isset($user_group) && is_array($user_group)?$user_group:array()),
		'meta_query'     => ($search_value != ""?array("relation" => "AND",($user_sort == "followers"?"followers_order":"points_order") => array("key" => $user_key,"value" => 0,"compare" => ">="),array('relation' => 'OR',array("key" => "first_name","value" => $search_value,"compare" => "RLIKE"))):array("relation" => "or",array("key" => $user_key,"compare" => "NOT EXISTS"),array("key" => $user_key,"value" => 0,"compare" => ">="))),
		'orderby'        => 'meta_value_num',
		'order'          => $user_order,
		'offset'         => $offset,
		'search'         => ($search_value != ""?'*'.$search_value.'*':''),
		'number'         => $number,
		'fields'         => 'ID',
	);
	$query = new WP_User_Query($args);
	$total_query = $query->get_total();
	$total_pages = ceil($total_query/$number);
	$get_results = true;
}else {
	add_action('pre_user_query','wpqa_custom_search_users');
	if ($user_sort != "user_registered" && $user_sort != "display_name" && $user_sort != "ID") {
		$user_sort = "user_registered";
	}
	$args = array(
		'role__in'       => (isset($user_group) && is_array($user_group)?$user_group:array()),
		'meta_query'     => ($search_value != ""?array('relation' => 'OR',array("key" => "first_name","value" => $search_value,"compare" => "RLIKE")):array()),
		'orderby'        => $user_sort,
		'order'          => $user_order,
		'offset'         => $offset,
		'search'         => ($search_value != ""?'*'.$search_value.'*':''),
		'number'         => $number,
		'fields'         => 'ID',
	);
	
	$query = new WP_User_Query($args);
	$total_query = $query->get_total();
	$total_pages = ceil($total_query/$number);
	$get_results = true;
}
$user_col = "col6";
if (($user_style == "columns" && ($discy_sidebar == "sidebar" || $discy_sidebar == "centered" || $discy_sidebar == "menu_left")) || ($user_style == "small_grid" && $discy_sidebar != "full")) {
	$user_col = "col4";
}else if ($discy_sidebar == "full") {
	$user_col = "col3";
}
$query = (isset($get_results)?$query->get_results():$query);
echo "<div class='user-section user-section-".$user_style.($user_style == "small_grid" || $user_style == "grid" || $user_style == "small" || $user_style == "columns"?" row".($masonry_style == "on"?" users-masonry":""):"").($user_style != "normal"?" user-not-normal":"").(isset($query) && !empty($query)?"":" discy_hide")."'>";
	if (isset($query) && !empty($query)) {
		foreach ($query as $user) {
			$user = (isset($user->ID)?$user->ID:$user);
			$owner_user = false;
			if (get_current_user_id() == $user) {
				$owner_user = true;
			}
			echo ($user_style == "small_grid" || $user_style == "grid" || $user_style == "small" || $user_style == "columns"?"<div class='col ".$user_col.($masonry_style == "on"?" user-masonry":"")."'>":"");
				do_action("wpqa_author",array("user_id" => $user,"author_page" => $user_style,"owner" => $owner_user,"type_post" => ($user_sort == "post_count" || $user_sort == "comments"?"post":$user_sort)));
			echo ($user_style == "small_grid" || $user_style == "grid" || $user_style == "small" || $user_style == "columns"?"</div>":"");
		}
	}else {
		$no_user = true;
	}
echo "</div>";

$current_page = max(1,$paged);
if ($total_pages > 1) {
	$pagination_args = array(
		'format'    => (has_wpqa() && wpqa_is_search()?'':'page/%#%/'),
		'current'   => $current_page,
		'total'     => $total_pages,
		'prev_text' => '<i class="icon-left-open"></i>',
		'next_text' => '<i class="icon-right-open"></i>',
	);
	if (!get_option('permalink_structure')) {
		$pagination_args['base'] = esc_url_raw(add_query_arg('paged','%#%'));
	}
	if (has_wpqa() && wpqa_is_search()) {
		$pagination_args['format'] = '?page=%#%';
	}
	echo '<div class="main-pagination"><div class="pagination">'.paginate_links($pagination_args).'</div></div><div class="clearfix"></div>';
}
remove_action('pre_user_query','wpqa_custom_search_users');

if (isset($no_user) && $no_user == true) {
	include locate_template("theme-parts/search-none.php");
}?>