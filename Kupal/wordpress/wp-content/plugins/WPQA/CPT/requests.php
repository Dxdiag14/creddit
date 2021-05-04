<?php

/* @author    2codeThemes
*  @package   WPQA/CPT
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* Requests post type */
function wpqa_request_post_types_init() {
	$activate_pay_to_users = wpqa_options("activate_pay_to_users");
	$allow_user_to_add_category = wpqa_options("allow_user_to_add_category");
	$category_single_multi = wpqa_options("category_single_multi");
	$filter_activate_requests = apply_filters("wpqa_filter_activate_requests",false);
	if ($filter_activate_requests == true || $activate_pay_to_users == "on" || $allow_user_to_add_category == "on" || $category_single_multi == "ajax_2") {
	    register_post_type( 'request',
	        array(
		     	'label' => esc_html__('Requests','wpqa'),
		        'labels' => array(
					'name'               => esc_html__('Requests','wpqa'),
					'singular_name'      => esc_html__('Requests','wpqa'),
					'menu_name'          => esc_html__('Requests','wpqa'),
					'name_admin_bar'     => esc_html__('Requests','wpqa'),
					'edit_item'          => esc_html__('Edit Request','wpqa'),
					'all_items'          => esc_html__('All Requests','wpqa'),
					'search_items'       => esc_html__('Search Requests','wpqa'),
					'parent_item_colon'  => esc_html__('Parent Request:','wpqa'),
					'not_found'          => esc_html__('No Requests Found.','wpqa'),
					'not_found_in_trash' => esc_html__('No Requests Found in Trash.','wpqa'),
				),
		        'description'         => '',
		        'public'              => false,
		        'show_ui'             => true,
		        'capability_type'     => 'post',
		        'capabilities'        => array('create_posts' => 'do_not_allow'),
		        'map_meta_cap'        => true,
		        'publicly_queryable'  => false,
		        'exclude_from_search' => false,
		        'hierarchical'        => false,
		        'query_var'           => false,
		        'show_in_rest'        => false,
		        'has_archive'         => false,
				'menu_position'       => 5,
				'menu_icon'           => "dashicons-megaphone",
		        'supports'            => array('editor'),
	        )
	    );
	}
}
add_action( 'wpqa_init', 'wpqa_request_post_types_init', 2 );
/* Admin columns for post types */
function wpqa_request_columns($old_columns){
	$columns = array();
	$columns["cb"]       = "<input type=\"checkbox\">";
	$columns["title_r"]  = esc_html__("Request","wpqa");
	$columns["author_r"] = esc_html__("Author","wpqa");
	$columns["date_r"]   = esc_html__("Date","wpqa");
	$columns["action_r"] = esc_html__("Action","wpqa");
	return $columns;
}
add_filter('manage_edit-request_columns','wpqa_request_columns');
function wpqa_request_custom_columns($column) {
	global $post;
	switch ( $column ) {
		case 'title_r' :
			$request_new = get_post_meta($post->ID,"request_new",true);
			echo ($request_new == 1?"<span class='request_new'></span>":"").wpqa_request_title($post->ID,$post->post_content);
		break;
		case 'author_r' :
			$user_name = get_the_author_meta('display_name',$post->post_author);
			if ($user_name != "") {
				echo '<a target="_blank" href="'.wpqa_profile_url((int)$post->post_author).'"><strong>'.$user_name.'</strong></a><a class="tooltip_s" data-title="'.esc_html__("View requests","wpqa").'" href="'.admin_url('edit.php?post_type=request&author='.$post->post_author).'"><i class="dashicons dashicons-megaphone"></i></a>
				<a target="_blank" class="tooltip_s" data-title="'.esc_html__("View user payment","wpqa").'" href="'.admin_url('user-edit.php?user_id='.$post->post_author).'#user-payment-methods"><i class="dashicons dashicons-admin-users"></i></a>';
			}else {
				esc_html_e("Deleted user","wpqa");
			}
		break;
		case 'date_r' :
			$date_format = wpqa_options("date_format");
			$date_format = ($date_format?$date_format:get_option("date_format"));
			$human_time_diff = human_time_diff(get_the_time('U'), current_time('timestamp'));
			echo ($human_time_diff." ".esc_html__("ago","wpqa")." - ".esc_html(get_the_time($date_format)));
		break;
		case 'action_r' :
			$request_status = get_post_meta($post->ID,"request_status",true);
			if ($request_status == 1) {
				esc_html_e("You have accepted this request","wpqa");
			}else if ($request_status == 2) {
				esc_html_e("You have rejected this request","wpqa");
			}else {
				if (isset($_GET["process"]) && ($_GET["process"] == "accept" || $_GET["process"] == "reject") && isset($_GET["post_id"]) && $_GET["post_id"] == $post->ID) {
					if ($_GET["process"] == "accept") {
						update_post_meta($post->ID,"request_status",1);
						if ($post->post_content == "withdrawal_points") {
							wpqa_notifications_activities($post->post_author,"","","","","accepted_withdrawal_points","notifications");
						}else if ($post->post_content == "request_category") {
							$request_related_item = (int)get_post_meta($post->ID,"request_related_item",true);
							$insert_term = wp_insert_term(get_post_meta($post->ID,"request_item",true),"question-category");
							if ($request_related_item > 0 && isset($insert_term["term_id"])) {
								wp_set_object_terms($request_related_item,$insert_term['term_id'],'question-category');
							}
							wpqa_notifications_activities($post->post_author,"","","","","accepted_category","notifications");
						}
					}else if ($_GET["process"] == "reject") {
						update_post_meta($post->ID,"request_status",2);
						if ($post->post_content == "withdrawal_points") {
							wpqa_notifications_activities($post->post_author,"","","","","rejected_withdrawal_points","notifications");
							wpqa_add_points($post->post_author,(int)get_post_meta($post->ID,"request_item",true),"+","rejected_withdrawal_points");
						}else if ($post->post_content == "request_category") {
							wpqa_notifications_activities($post->post_author,"","","","","canceled_category","notifications");
						}
					}
					do_action("wpqa_request_action",$post->ID,$_GET["process"],$post->post_author);
					delete_post_meta($post->ID,"request_new");

					$new_requests = get_option("new_requests");
					$new_requests--;
					update_option('new_requests',($new_requests < 0?0:$new_requests));
					wp_redirect(admin_url('edit.php?post_type=request'));
					die();
				}
				$show_accept = apply_filters("wpqa_show_accept_request_filter",true,$post->post_content,$post->ID);
				if ($show_accept == true) {?>
					<a class="button button-primary" href="<?php echo esc_url_raw(add_query_arg(array("process" => "accept","post_id" => $post->ID)))?>"><?php esc_html_e("Accept","wpqa")?></a>
				<?php }?>
				<a class="button" href="<?php echo esc_url_raw(add_query_arg(array("process" => "reject","post_id" => $post->ID)))?>"><?php esc_html_e("Reject","wpqa")?></a>
			<?php }
		break;
	}
}
add_action('manage_request_posts_custom_column','wpqa_request_custom_columns',2);
function wpqa_request_primary_column($default,$screen) {
	if ('edit-request' === $screen) {
		$default = 'title_r';
	}
	return $default;
}
add_filter('list_table_primary_column','wpqa_request_primary_column',10,2);
/* Request title */
function wpqa_request_title($post_id,$title) {
	if ($title == "withdrawal_points") {
		$currency_code = wpqa_options("currency_code");
		$request_item = get_post_meta($post_id,"request_item",true);
		$request_related_item = get_post_meta($post_id,"request_related_item",true);
		$title = esc_html__("Withdrawal points","wpqa")." - ".sprintf(_n('%s point','%s points',$request_item,'wpqa'),$request_item)." ".esc_html__("with","wpqa")." ".$request_related_item." ".$currency_code;
	}else if ($title == "request_category") {
		$request_item = get_post_meta($post_id,"request_item",true);
		$request_related_item = get_post_meta($post_id,"request_related_item",true);
		$title = esc_html__("Request a new category","wpqa")." - ".$request_item;
	}
	$title = apply_filters("wpqa_request_title",$title,$post_id);
	return $title;
}
add_filter('manage_edit-request_sortable_columns','wpqa_request_sortable_columns');
function wpqa_request_sortable_columns($defaults) {
	$defaults['date_r'] = 'date';
	return $defaults;
}
/* Request details */
add_filter('bulk_actions-edit-request','wpqa_bulk_actions_request');
function wpqa_bulk_actions_request($actions) {
	unset($actions['edit']);
	return $actions;
}
add_filter('bulk_post_updated_messages','wpqa_bulk_updated_messages_request',1,2);
function wpqa_bulk_updated_messages_request($bulk_messages,$bulk_counts) {
	if (get_current_screen()->post_type == "request") {
		$bulk_messages['post'] = array(
			'deleted' => _n('%s request permanently deleted.','%s requests permanently deleted.',$bulk_counts['deleted'],'wpqa'),
			'trashed' => _n('%s request trashed.','%s requests trashed.',$bulk_counts['trashed'],'wpqa'),
		);
	}
	return $bulk_messages;
}
add_filter('post_row_actions','wpqa_row_actions_request',1,2);
function wpqa_row_actions_request($actions,$post) {
	if ($post->post_type == "request") {
		unset($actions['trash']);
		unset($actions['view']);
		unset($actions['edit']);
		$actions['inline hide-if-no-js'] = "";
	}
	return $actions;
}
function wpqa_request_filter() {
	global $post_type;
	if ($post_type == 'request') {
		$from = (isset($_GET['date-from']) && $_GET['date-from'])?$_GET['date-from'] :'';
		$to = (isset($_GET['date-to']) && $_GET['date-to'])?$_GET['date-to']:'';
		$data_js = " data-js='".json_encode(array("changeMonth" => true,"changeYear" => true,"yearRange" => "2018:+00","dateFormat" => "yy-mm-dd"))."'";

		echo '<span class="site-form-date"><input class="site-date" type="text" name="date-from" placeholder="'.esc_html__("Date From","wpqa").'" value="'.esc_attr($from).'" '.$data_js.'></span>
		<span class="site-form-date"><input class="site-date" type="text" name="date-to" placeholder="'.esc_html__("Date To","wpqa").'" value="'.esc_attr($to).'" '.$data_js.'></span>';
	}
}
add_action('restrict_manage_posts','wpqa_request_filter');
function wpqa_request_posts_query($query) {
	global $post_type,$pagenow;
	if ($pagenow == 'edit.php' && $post_type == 'request') {
		if (!empty($_GET['date-from']) && !empty($_GET['date-to'])) {
			$query->query_vars['date_query'][] = array(
				'after'     => sanitize_text_field($_GET['date-from']),
				'before'    => sanitize_text_field($_GET['date-to']),
				'inclusive' => true,
				'column'    => 'post_date'
			);
		}
		if (!empty($_GET['date-from']) && empty($_GET['date-to'])) {
			$today = sanitize_text_field($_GET['date-from']);
			$today = explode("-",$today);
			$query->query_vars['date_query'] = array(
	            'year'  => $today[0],
	            'month' => $today[1],
	            'day'   => $today[2],
	        );
		}
		if (empty($_GET['date-from']) && !empty($_GET['date-to'])) {
			$today = sanitize_text_field($_GET['date-to']);
			$today = explode("-",$today);
			$query->query_vars['date_query'] = array(
	            'year'  => $today[0],
	            'month' => $today[1],
	            'day'   => $today[2],
	        );
		}
		$orderby = $query->get('orderby');
		if ($orderby == 'date_r') {
			$query->query_vars('orderby','date');
		}
	}
}
add_action('pre_get_posts','wpqa_request_posts_query');
function wpqa_months_dropdown_request($return,$post_type) {
	if ($post_type == "request") {
		$return = true;
	}
	return $return;
}
add_filter("disable_months_dropdown","wpqa_months_dropdown_request",1,2);
/* Remove filter */
function wpqa_manage_request_tablenav($which) {
	if ($which == "top") {
		global $post_type,$pagenow;
		if ($pagenow == 'edit.php' && $post_type == 'request') {
			$date_from = (isset($_GET['date-from'])?esc_attr($_GET['date-from']):'');
			$date_to = (isset($_GET['date-to'])?esc_attr($_GET['date-to']):'');
			if ($date_from != "" || $date_to != "") {
				echo '<a class="button" href="'.admin_url('edit.php?post_type=request').'">'.esc_html__("Remove filters","wpqa").'</a>';
			}
		}
	}
}
add_filter("manage_posts_extra_tablenav","wpqa_manage_request_tablenav");
/* Request menus */
add_action('admin_menu','wpqa_add_admin_request');
function wpqa_add_admin_request() {
	$activate_pay_to_users = wpqa_options("activate_pay_to_users");
	$allow_user_to_add_category = wpqa_options("allow_user_to_add_category");
	$category_single_multi = wpqa_options("category_single_multi");
	$filter_activate_requests = apply_filters("wpqa_filter_activate_requests",false);
	add_submenu_page('edit.php?post_type=request',esc_html__('New requests','wpqa'),esc_html__('New requests','wpqa'),'manage_options','edit.php?post_type=request&request=new');
	if ($activate_pay_to_users == "on") {
		add_submenu_page('edit.php?post_type=request',esc_html__('Request money','wpqa'),esc_html__('Request money','wpqa'),'manage_options','edit.php?post_type=request&request=points');
	}
	if ($allow_user_to_add_category == "on" || $category_single_multi == "ajax_2") {
		add_submenu_page('edit.php?post_type=request',esc_html__('Request category','wpqa'),esc_html__('Request category','wpqa'),'manage_options','edit.php?post_type=request&request=category');
	}
	do_action("wpqa_add_admin_request_action");
}
add_filter( "views_edit-request", "wpqa_requests_status" );
if (!function_exists('wpqa_requests_status')) :
	function wpqa_requests_status($status) {
		$activate_pay_to_users = wpqa_options("activate_pay_to_users");
		$allow_user_to_add_category = wpqa_options("allow_user_to_add_category");
		$category_single_multi = wpqa_options("category_single_multi");
		$filter_activate_requests = apply_filters("wpqa_filter_activate_requests",false);
		$get_status = (isset($_GET['request'])?esc_attr($_GET['request']):'');
		$points = $category = array();
		if ($activate_pay_to_users == "on") {
			$points = array('points' => '<a href="'.admin_url('edit.php?post_type=request&request=points').'"'.($get_status == "points"?' class="current"':'').'>'.esc_html__('Withdrawal points','wpqa').' ('.wpqa_meta_count("request_type","withdrawal_points").')</a>');
		}
		if ($allow_user_to_add_category == "on" || $category_single_multi == "ajax_2") {
			$category = array('category' => '<a href="'.admin_url('edit.php?post_type=request&request=category').'"'.($get_status == "category"?' class="current"':'').'>'.esc_html__('Category requests','wpqa').' ('.wpqa_meta_count("request_type","request_category").')</a>');
		}
		return apply_filters("wpqa_requests_status_filter",array_merge( $status, $points, $category, array(
			'new' => '<a href="'.admin_url('edit.php?post_type=request&request=new').'"'.($get_status == "new"?' class="current"':'').'>'.esc_html__('New requests','wpqa').' ('.wpqa_meta_count("request_new",1).')</a>',
		)),$get_status);
	}
endif;
add_action('current_screen','wpqa_requests_exclude',10,2);
if (!function_exists('wpqa_requests_exclude')) :
	function wpqa_requests_exclude($screen) {
		if ($screen->id != 'edit-request')
			return;
		$get_status = (isset($_GET['request'])?esc_attr($_GET['request']):'');
		if ($get_status == "new" || $get_status == "points" || $get_status == "category") {
			add_filter('parse_query','wpqa_list_requests');
		}
	}
endif;
if (!function_exists('wpqa_list_requests')) :
	function wpqa_list_requests($clauses) {
		$get_status = (isset($_GET['request'])?esc_attr($_GET['request']):'');
		if ($get_status == "new") {
			$clauses->query_vars['meta_key'] = "request_new";
			$clauses->query_vars['meta_value'] = 1;
			$clauses->query_vars['post_type'] = "request";
		}else if ($get_status == "points") {
			$clauses->query_vars['meta_key'] = "request_type";
			$clauses->query_vars['meta_value'] = "withdrawal_points";
			$clauses->query_vars['post_type'] = "request";
		}else if ($get_status == "category") {
			$clauses->query_vars['meta_key'] = "request_type";
			$clauses->query_vars['meta_value'] = "request_category";
			$clauses->query_vars['post_type'] = "request";
		}
	}
endif;
/* Insert a new request */
function wpqa_new_request($user_id = "",$type_of_item = "",$item = "",$related_item = "",$text = "",$more_text = "",$another_user_id = "",$username = "") {
	$data = array(
		'post_content'  => $text,
		'post_status' => "publish",
		'post_author' => $user_id,
		'post_type'   => "request"
	);
	$request_id = wp_insert_post($data);
	if ($request_id == 0 || is_wp_error($request_id)) {
		error_log(esc_html__("Error in post.","wpqa"));
	}else {
		$variables = array(
			"request_new" => 1,
		);
		if ($type_of_item != "") {
			$variables["request_type"] = $type_of_item;
		}
		if ($item != "") {
			$variables["request_item"] = $item;
		}
		if ($related_item != "") {
			$variables["request_related_item"] = $related_item;
		}
		if ($more_text != "") {
			$variables["request_more_text"] = $more_text;
		}
		if ($another_user_id != "") {
			$variables["request_another_user_id"] = $another_user_id;
		}
		if ($username != "") {
			$variables["request_username"] = $username;
		}
		if (is_array($variables) && !empty($variables)) {
			foreach ($variables as $key => $value) {
				update_post_meta($request_id,$key,$value);
			}
		}
	}
	/* New */
	$new_requests = get_option("new_requests");
	if (isset($new_requests) && $new_requests != "" && $new_requests > 0) {
		$new_requests++;
	}else {
		$new_requests = 1;
	}
	update_option('new_requests',$new_requests);
	return $request_id;
}?>