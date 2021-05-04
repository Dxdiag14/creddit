<?php

/* @author    2codeThemes
*  @package   WPQA/CPT
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* Reports post type */
function wpqa_report_post_types_init() {
	$active_reports = wpqa_options("active_reports");
	if ($active_reports == "on") {
	    register_post_type( 'report',
	        array(
		     	'label' => esc_html__('Reports','wpqa'),
		        'labels' => array(
					'name'               => esc_html__('Reports','wpqa'),
					'singular_name'      => esc_html__('Reports','wpqa'),
					'menu_name'          => esc_html__('Reports','wpqa'),
					'name_admin_bar'     => esc_html__('Reports','wpqa'),
					'edit_item'          => esc_html__('Edit Report','wpqa'),
					'all_items'          => esc_html__('All Reports','wpqa'),
					'search_items'       => esc_html__('Search Reports','wpqa'),
					'parent_item_colon'  => esc_html__('Parent Report:','wpqa'),
					'not_found'          => esc_html__('No Reports Found.','wpqa'),
					'not_found_in_trash' => esc_html__('No Reports Found in Trash.','wpqa'),
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
				'menu_icon'           => "dashicons-flag",
		        'supports'            => array('title','editor'),
	        )
	    );
	}
}
add_action( 'wpqa_init', 'wpqa_report_post_types_init', 0 );
/* Get report note */
function wpqa_get_report_note($views) {
	$types = array("","_answer");
	foreach ($types as $type) {
		$ask_me = wpqa_options("ask_me");
		if ($ask_me == "on") {
			$ask_option = get_option("ask_option".$type);
			$ask_option_array = get_option("ask_option".$type."_array");
			if (isset($ask_option) && $ask_option != "") {
				update_option("report_option".$type,$ask_option);
				delete_option("ask_option".$type);
			}
			if (isset($ask_option_array) && !empty($ask_option_array)) {
				update_option("report_option".$type."_array",$ask_option_array);
				delete_option("ask_option".$type."_array");
			}
		}

		$rows_per_page = get_option("posts_per_page");
		$wpqa_option = get_option("report_option".$type);
		if ($wpqa_option > 0) {
			$wpqa_option_array = get_option("report_option".$type."_array");
			if (is_array($wpqa_option_array)) {
				foreach ($wpqa_option_array as $key => $value) {
					if ($ask_me == "on") {
						$wpqa_item_option = get_option("ask_option".$type."_".$value);
						if (isset($wpqa_item_option) && !empty($wpqa_item_option)) {
							update_option("report_option".$type."_".$value,$wpqa_item_option);
							delete_option("ask_option".$type."_".$value);
						}
					}
					$wpqa_one_option[$value] = get_option("report_option".$type."_".$value);
					if ($type == "_answer") {
						$comment_no_empty = get_comment($wpqa_one_option[$value]["comment_id"]);
						if (!isset($comment_no_empty)) {
							unset($wpqa_one_option[$value]);
						}
					}else {
						$post_no_empty = get_post($wpqa_one_option[$value]["post_id"]);
						if (!isset($post_no_empty)) {
							unset($wpqa_one_option[$value]);
						}
					}
				}
			}

			$wpqa_option = get_option("report_option".$type);
			$count = $wpqa_option;
			for ($reports = 1; $reports <= $count; $reports++) {
				$wpqa_one_option = get_option("report_option".$type."_".$reports);
				if (isset($wpqa_one_option["comment_id"])) {
					$get_comment = get_comment($wpqa_one_option["comment_id"]);
					if (isset($get_comment->comment_ID)) {
						$available_post = true;
					}
				}else {
					$get_post = get_post($wpqa_one_option["post_id"]);
					if (isset($get_post->ID)) {
						$available_post = true;
					}
				}
				if (isset($available_post) && !isset($wpqa_one_option["moved_done"])) {
					$data = array(
						'post_content' => $wpqa_one_option["value"],
						'post_title'   => "",
						'post_status'  => "publish",
						'post_author'  => ($wpqa_one_option["user_id"] > 0?$wpqa_one_option["user_id"]:0),
						'post_type'    => 'report',
						'post_date'    => date("Y-m-d H:i:s",$wpqa_one_option["the_date"])
					);
					$wpqa_one_option["moved_done"] = 1;
					update_option("report_option".$type."_".$reports,$wpqa_one_option);
					$wpqa_option--;
					update_option("report_option".$type,$wpqa_option);
					$post_id = wp_insert_post($data);
					if ($post_id == 0 || is_wp_error($post_id)) {
						error_log(esc_html__("Error in post.","wpqa"));
					}else {
						$variables = array("post_id","report_new","the_author","comment_id","report_type");
						foreach ($variables as $value) {
							if ($value == "report_type") {
								update_post_meta($post_id,$value,($type == "_answer"?"answer":"question"));
							}else if ($value == "report_new" && isset($wpqa_one_option[$value]) && $wpqa_one_option[$value] != "") {
								update_post_meta($post_id,"report_new",$wpqa_one_option[$value]);
							}else if ($value == "post_id" && isset($wpqa_one_option[$value]) && $wpqa_one_option[$value] != "") {
								update_post_meta($post_id,"report_post_id",$wpqa_one_option[$value]);
							}else if ($value == "comment_id" && isset($wpqa_one_option[$value]) && $wpqa_one_option[$value] != "") {
								update_post_meta($post_id,"report_comment_id",$wpqa_one_option[$value]);
							}else if ($value == "the_author" && isset($wpqa_one_option[$value]) && $wpqa_one_option[$value] != "") {
								update_post_meta($post_id,"report_the_author",$wpqa_one_option[$value]);
							}
						}
					}
					sleep(1);
				}
			}
		}
	}
	
	update_option("new_reports",0);
	update_option("new_question_reports",0);
	do_action("wpqa_action_after_all_money");
	return $views;
}
add_filter('views_edit-report','wpqa_get_report_note');
/* Admin columns for post types */
function wpqa_report_columns($old_columns){
	$columns = array();
	$columns["cb"]       = "<input type=\"checkbox\">";
	$columns["link"]     = esc_html__("Link","wpqa");
	$columns["content"]  = esc_html__("Content","wpqa");
	$columns["type"]     = esc_html__("Report type","wpqa");
	$columns["author_r"] = esc_html__("Author","wpqa");
	$columns["date_r"]   = esc_html__("Date","wpqa");
	return $columns;
}
add_filter('manage_edit-report_columns','wpqa_report_columns');
function wpqa_report_custom_columns($column) {
	global $post;
	$report_type = get_post_meta($post->ID,"report_type",true);
	switch ( $column ) {
		case 'link' :
			if ($report_type == "answer") {
				echo '<a target="_blank" href="'.get_comment_link(get_post_meta($post->ID,"report_comment_id",true)).'">'.esc_html__("View answer","wpqa").'</a>';
			}else {
				echo '<a target="_blank" href="'.get_the_permalink(get_post_meta($post->ID,"report_post_id",true)).'">'.esc_html__("View question","wpqa").'</a>';
			}
		break;
		case 'content' :
			echo get_the_content($post->ID);
		break;
		case 'type' :
			$report_new = get_post_meta($post->ID,"report_new",true);
			echo '<span class="money-span'.($report_new == 1?" report_new":"").'">'.($report_type == "answer"?esc_html__("Answer","wpqa"):esc_html__("Question","wpqa")).'</span>';
			update_post_meta($post->ID,"report_new",0);
		break;
		case 'author_r' :
			$the_author = get_post_meta($post->ID,"report_the_author",true);
			if ($the_author != "") {
				if ($the_author == 1) {
					echo "Not user";
				}else {
					echo esc_attr($the_author);
				}
			}else {
				$user_name = get_the_author_meta('display_name',$post->post_author);
				if ($user_name != "") {
					echo '<a target="_blank" href="'.wpqa_profile_url((int)$post->post_author).'"><strong>'.$user_name.'</strong></a><a class="tooltip_s" data-title="'.esc_html__("View reports","wpqa").'" href="'.admin_url('edit.php?post_type=report&author='.$post->post_author).'"><i class="dashicons dashicons-email-alt"></i></a>';
				}else {
					esc_html_e("Deleted user","wpqa");
				}
			}
		break;
		case 'date_r' :
			$date_format = wpqa_options("date_format");
			$date_format = ($date_format?$date_format:get_option("date_format"));
			$human_time_diff = human_time_diff(get_the_time('U'), current_time('timestamp'));
			echo ($human_time_diff." ".esc_html__("ago","wpqa")." - ".esc_html(get_the_time($date_format)));
		break;
	}
}
add_action('manage_report_posts_custom_column','wpqa_report_custom_columns',2);
function wpqa_report_primary_column($default,$screen) {
	if ('edit-report' === $screen) {
		$default = 'link';
	}
	return $default;
}
add_filter('list_table_primary_column','wpqa_report_primary_column',10,2);
/* Report menus */
add_action('admin_menu','wpqa_add_admin_report');
function wpqa_add_admin_report() {
	add_submenu_page('edit.php?post_type=report',esc_html__('Questions','wpqa'),esc_html__('Questions','wpqa'),'manage_options','edit.php?post_type=report&types=questions');
	add_submenu_page('edit.php?post_type=report',esc_html__('Answers','wpqa'),esc_html__('Answers','wpqa'),'manage_options','edit.php?post_type=report&types=answers');
}
add_filter( "views_edit-report", "wpqa_reports_status" );
if (!function_exists('wpqa_reports_status')) :
	function wpqa_reports_status($status) {
		$get_status = (isset($_GET['types'])?esc_attr($_GET['types']):'');
		return array_merge( $status, array(
			'questions' => '<a href="'.admin_url('edit.php?post_type=report&types=questions').'"'.($get_status == "questions"?' class="current"':'').'>'.esc_html__('Questions reports','wpqa').' ('.wpqa_meta_count("report_type","question").')</a>',
			'answers' => '<a href="'.admin_url('edit.php?post_type=report&types=answers').'"'.($get_status == "answers"?' class="current"':'').'>'.esc_html__('Answers reports','wpqa').' ('.wpqa_meta_count("report_type","answer").')</a>',
		));
	}
endif;
add_action('current_screen','wpqa_reports_exclude',10,2);
if (!function_exists('wpqa_reports_exclude')) :
	function wpqa_reports_exclude($screen) {
		if ($screen->id != 'edit-report')
			return;
		$get_status = (isset($_GET['types'])?esc_attr($_GET['types']):'');
		if ($get_status == "questions" || $get_status == "answers") {
			add_filter('parse_query','wpqa_list_reports');
		}
	}
endif;
if (!function_exists('wpqa_list_reports')) :
	function wpqa_list_reports($clauses) {
		$get_status = (isset($_GET['types'])?esc_attr($_GET['types']):'');
		if ($get_status == "questions") {
			$clauses->query_vars['meta_key'] = "report_type";
			$clauses->query_vars['meta_value'] = "question";
			$clauses->query_vars['post_type'] = "report";
		}else if ($get_status == "answers") {
			$clauses->query_vars['meta_key'] = "report_type";
			$clauses->query_vars['meta_value'] = "answer";
			$clauses->query_vars['post_type'] = "report";
		}
	}
endif;
add_filter('manage_edit-report_sortable_columns','wpqa_report_sortable_columns');
function wpqa_report_sortable_columns($defaults) {
	$defaults['date_r'] = 'date';
	return $defaults;
}
/* Reports details */
add_filter('bulk_actions-edit-report','wpqa_bulk_actions_report');
function wpqa_bulk_actions_report($actions) {
	unset($actions['edit']);
	return $actions;
}
add_filter('bulk_post_updated_messages','wpqa_bulk_updated_messages_report',1,2);
function wpqa_bulk_updated_messages_report($bulk_messages,$bulk_counts) {
	if (get_current_screen()->post_type == "report") {
		$bulk_messages['post'] = array(
			'deleted' => _n('%s report permanently deleted.','%s reports permanently deleted.',$bulk_counts['deleted'],'wpqa'),
			'trashed' => _n('%s report trashed.','%s reports trashed.',$bulk_counts['trashed'],'wpqa'),
		);
	}
	return $bulk_messages;
}
add_filter('post_row_actions','wpqa_row_actions_report',1,2);
function wpqa_row_actions_report($actions,$post) {
	if ($post->post_type == "report") {
		unset($actions['trash']);
		unset($actions['view']);
		unset($actions['edit']);
		$actions['inline hide-if-no-js'] = "";
	}
	return $actions;
}
function wpqa_report_filter() {
	global $post_type;
	if ($post_type == 'report') {
		$report_type = (isset($_GET['report_type'])?esc_attr($_GET['report_type']):'');
		echo '<select name="report_type">
			<option value="-1">'.esc_html__("Report type","wpqa").'</option>';
			$key = "question";
			echo '<option '.selected($report_type,$key,false).' value="'.$key.'">'.esc_html__('Questions reports','wpqa').'</option>';
			$key = "answer";
			echo '<option '.selected($report_type,$key,false).' value="'.$key.'">'.esc_html__('Answers reports','wpqa').'</option>';
		echo '</select>';
		$from = (isset($_GET['date-from']) && $_GET['date-from'])?$_GET['date-from'] :'';
		$to = (isset($_GET['date-to']) && $_GET['date-to'])?$_GET['date-to']:'';
		$data_js = " data-js='".json_encode(array("changeMonth" => true,"changeYear" => true,"yearRange" => "2018:+00","dateFormat" => "yy-mm-dd"))."'";

		echo '<span class="site-form-date"><input class="site-date" type="text" name="date-from" placeholder="'.esc_html__("Date From","wpqa").'" value="'.esc_attr($from).'" '.$data_js.'></span>
		<span class="site-form-date"><input class="site-date" type="text" name="date-to" placeholder="'.esc_html__("Date To","wpqa").'" value="'.esc_attr($to).'" '.$data_js.'></span>';
	}
}
add_action('restrict_manage_posts','wpqa_report_filter');
function wpqa_report_posts_query($query) {
	global $post_type,$pagenow;
	if ($pagenow == 'edit.php' && $post_type == 'report') {
		$orderby = $query->get('orderby');
		$report_type = (isset($_GET['report_type'])?esc_attr($_GET['report_type']):'');
		if ($report_type != "" && $report_type != "-1") {
			$query->query_vars['meta_query'][] = array(
				'key'   => 'report_type',
				'value' => $report_type,
			);
		}
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
		if ($orderby == 'date_r') {
			$query->query_vars('orderby','date');
		}
	}
}
add_action('pre_get_posts','wpqa_report_posts_query');
function wpqa_months_dropdown_report($return,$post_type) {
	if ($post_type == "report") {
		$return = true;
	}
	return $return;
}
add_filter("disable_months_dropdown","wpqa_months_dropdown_report",1,2);
/* Remove filter */
function wpqa_manage_report_tablenav($which) {
	if ($which == "top") {
		global $post_type,$pagenow;
		if ($pagenow == 'edit.php' && $post_type == 'report') {
			$report_type = (isset($_GET['report_type'])?esc_attr($_GET['report_type']):'');
			$date_from = (isset($_GET['date-from'])?esc_attr($_GET['date-from']):'');
			$date_to = (isset($_GET['date-to'])?esc_attr($_GET['date-to']):'');
			if (($report_type != "" && $report_type != "-1") || $date_from != "" || $date_to != "") {
				echo '<a class="button" href="'.admin_url('edit.php?post_type=report').'">'.esc_html__("Remove filters","wpqa").'</a>';
			}
		}
	}
}
add_filter("manage_posts_extra_tablenav","wpqa_manage_report_tablenav");?>