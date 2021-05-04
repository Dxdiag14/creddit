<?php

/* @author    2codeThemes
*  @package   WPQA/CPT
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* Points post type */
function wpqa_point_post_types_init() {
	$active_points = wpqa_options("active_points");
	if ($active_points == "on") {
	    register_post_type( 'point',
	        array(
		     	'label' => esc_html__('Points','wpqa'),
		        'labels' => array(
					'name'               => esc_html__('Points','wpqa'),
					'singular_name'      => esc_html__('Points','wpqa'),
					'menu_name'          => esc_html__('Points','wpqa'),
					'name_admin_bar'     => esc_html__('Points','wpqa'),
					'edit_item'          => esc_html__('Edit Point','wpqa'),
					'all_items'          => esc_html__('All Points','wpqa'),
					'search_items'       => esc_html__('Search Points','wpqa'),
					'parent_item_colon'  => esc_html__('Parent Point:','wpqa'),
					'not_found'          => esc_html__('No Points Found.','wpqa'),
					'not_found_in_trash' => esc_html__('No Points Found in Trash.','wpqa'),
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
				'menu_icon'           => "dashicons-star-filled",
		        'supports'            => array('title','editor'),
	        )
	    );
	}
}
//add_action( 'wpqa_init', 'wpqa_point_post_types_init', 2 );
/* Admin columns for post types */
function wpqa_point_columns($old_columns){
	$columns = array();
	$columns["cb"]        = "<input type=\"checkbox\">";
	$columns["content_p"] = esc_html__("Point","wpqa");
	$columns["author_p"]  = esc_html__("Author","wpqa");
	$columns["date_p"]    = esc_html__("Date","wpqa");
	return $columns;
}
add_filter('manage_edit-point_columns','wpqa_point_columns');
function wpqa_point_custom_columns($column) {
	global $post;
	switch ( $column ) {
		case 'content_p' :
			$point_result = wpqa_point_result($post,"admin");
			echo wpqa_show_points($point_result,"","");
			if (!empty($point_result["comment_id"])) {
				$get_comment = get_comment($point_result["comment_id"]);
				if (!empty($get_comment)) {
					echo '<a target="_blank" href="'.get_comment_link($point_result["comment_id"]).'"><a class="tooltip_s" data-title="'.esc_html__("View answer","wpqa").'" href="'.admin_url('edit.php?post_type=point&author='.$post->post_author).'"><i class="dashicons dashicons-admin-comments"></i></a></a>';
				}
			}else if (!empty($point_result["post_id"])) {
				$get_the_permalink = get_the_permalink($point_result["post_id"]);
				$get_post_status = get_post_status($point_result["post_id"]);
				if ($get_post_status != "trash" && !empty($get_the_permalink)) {
					echo '<a target="_blank" href="'.get_the_permalink($point_result["post_id"]).'"><a class="tooltip_s" data-title="'.esc_html__("View question","wpqa").'" href="'.admin_url('edit.php?post_type=point&author='.$post->post_author).'"><i class="dashicons dashicons-editor-help"></i></a></a>';
				}
			}
		break;
		case 'author_p' :
			$user_name = get_the_author_meta('display_name',$post->post_author);
			if ($user_name != "") {
				echo '<a target="_blank" href="'.wpqa_profile_url((int)$post->post_author).'"><strong>'.$user_name.'</strong></a><a class="tooltip_s" data-title="'.esc_html__("View points","wpqa").'" href="'.admin_url('edit.php?post_type=point&author='.$post->post_author).'"><i class="dashicons dashicons-star-filled"></i></a>';
			}else {
				esc_html_e("Deleted user","wpqa");
			}
		break;
		case 'date_p' :
			$date_format = wpqa_options("date_format");
			$date_format = ($date_format?$date_format:get_option("date_format"));
			$human_time_diff = human_time_diff(get_the_time('U'), current_time('timestamp'));
			echo ($human_time_diff." ".esc_html__("ago","wpqa")." - ".esc_html(get_the_time($date_format)));
		break;
	}
}
add_action('manage_point_posts_custom_column','wpqa_point_custom_columns',2);
function wpqa_point_primary_column($default,$screen) {
	if ('edit-point' === $screen) {
		$default = 'content_p';
	}
	return $default;
}
add_filter('list_table_primary_column','wpqa_point_primary_column',10,2);
add_filter('manage_edit-point_sortable_columns','wpqa_point_sortable_columns');
function wpqa_point_sortable_columns($defaults) {
	$defaults['date_p'] = 'date';
	return $defaults;
}
/* Point details */
add_filter('bulk_actions-edit-point','wpqa_bulk_actions_point');
function wpqa_bulk_actions_point($actions) {
	unset($actions['edit']);
	return $actions;
}
add_filter('bulk_post_updated_messages','wpqa_bulk_updated_messages_point',1,2);
function wpqa_bulk_updated_messages_point($bulk_messages,$bulk_counts) {
	if (get_current_screen()->post_type == "point") {
		$bulk_messages['post'] = array(
			'deleted' => _n('%s point permanently deleted.','%s points permanently deleted.',$bulk_counts['deleted'],'wpqa'),
			'trashed' => _n('%s point trashed.','%s points trashed.',$bulk_counts['trashed'],'wpqa'),
		);
	}
	return $bulk_messages;
}
add_filter('post_row_actions','wpqa_row_actions_point',1,2);
function wpqa_row_actions_point($actions,$post) {
	if ($post->post_type == "point") {
		unset($actions['trash']);
		unset($actions['view']);
		unset($actions['edit']);
		$actions['inline hide-if-no-js'] = "";
	}
	return $actions;
}
function wpqa_point_filter() {
	global $post_type;
	if ($post_type == 'point') {
		$from = (isset($_GET['date-from']) && $_GET['date-from'])?$_GET['date-from'] :'';
		$to = (isset($_GET['date-to']) && $_GET['date-to'])?$_GET['date-to']:'';
		$data_js = " data-js='".json_encode(array("changeMonth" => true,"changeYear" => true,"yearRange" => "2018:+00","dateFormat" => "yy-mm-dd"))."'";

		echo '<span class="site-form-date"><input class="site-date" type="text" name="date-from" placeholder="'.esc_html__("Date From","wpqa").'" value="'.esc_attr($from).'" '.$data_js.'></span>
		<span class="site-form-date"><input class="site-date" type="text" name="date-to" placeholder="'.esc_html__("Date To","wpqa").'" value="'.esc_attr($to).'" '.$data_js.'></span>';
	}
}
add_action('restrict_manage_posts','wpqa_point_filter');
function wpqa_point_posts_query($query) {
	global $post_type,$pagenow;
	if ($pagenow == 'edit.php' && $post_type == 'point') {
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
		if ($orderby == 'date_p') {
			$query->query_vars('orderby','date');
		}
	}
}
add_action('pre_get_posts','wpqa_point_posts_query');
function wpqa_months_dropdown_point($return,$post_type) {
	if ($post_type == "point") {
		$return = true;
	}
	return $return;
}
add_filter("disable_months_dropdown","wpqa_months_dropdown_point",1,2);
/* Remove filter */
function wpqa_manage_point_tablenav($which) {
	if ($which == "top") {
		global $post_type,$pagenow;
		if ($pagenow == 'edit.php' && $post_type == 'point') {
			$date_from = (isset($_GET['date-from'])?esc_attr($_GET['date-from']):'');
			$date_to = (isset($_GET['date-to'])?esc_attr($_GET['date-to']):'');
			if ($date_from != "" || $date_to != "") {
				echo '<a class="button" href="'.admin_url('edit.php?post_type=point').'">'.esc_html__("Remove filters","wpqa").'</a>';
			}
		}
	}
}
add_filter("manage_posts_extra_tablenav","wpqa_manage_point_tablenav");
/* Post publish */
if (!function_exists('wpqa_post_publish')) :
	function wpqa_post_publish($post,$post_author,$area = '') {
		$post_id = (int)$post->ID;
		$post_type = $post->post_type;
		$point_add_post = (int)wpqa_options("point_add_".$post_type);
		$active_points = wpqa_options("active_points");
		if ($post_author > 0 && $point_add_post > 0 && $active_points == "on") {
			update_post_meta($post_id,"get_points_before","yes");
			wpqa_add_points($post_author,$point_add_post,"+","add_".$post_type,$post_id);
		}
		do_action("wpqa_after_post_publish",$post,$post_author,$area);
	}
endif;
/* Add points for the user */
if (!function_exists('wpqa_add_points')) :
	function wpqa_add_points($user_id,$points,$relation,$message,$post_id = 0,$comment_id = 0,$another_user_id = 0,$points_type = "points",$items = true) {
		$points = apply_filters("wpqa_add_points_filter",$points,$user_id,$relation,$message,$post_id,$comment_id,$another_user_id,$points_type,$items);
		if ($points > 0) {
			$active_points_specific = wpqa_options("active_points_specific");
			if ($active_points_specific == "on" && $points_type == "points") {
				$start_of_week = get_option("start_of_week");
				if ($start_of_week == 0) {
					$start_of_week = "Sunday";
				}else if ($start_of_week == 1) {
					$start_of_week = "Monday";
				}else if ($start_of_week == 2) {
					$start_of_week = "Tuesday";
				}else if ($start_of_week == 3) {
					$start_of_week = "Wednesday";
				}else if ($start_of_week == 4) {
					$start_of_week = "Thursday";
				}else if ($start_of_week == 5) {
					$start_of_week = "Friday";
				}else if ($start_of_week == 6) {
					$start_of_week = "Saturday";
				}
				$points_type_specific = "points_date_".date("j-n-Y");
				$points_user = (int)get_user_meta($user_id,$points_type_specific,true);
				$points_user = (int)($relation == "+"?$points_user+$points:$points_user-$points);
				update_user_meta($user_id,$points_type_specific,($points_user > 0?$points_user:0));

				$points_type_specific = "points_date_".date("Y-m-d H:i:s",strtotime($start_of_week.' this week'));
				$points_user = (int)get_user_meta($user_id,$points_type_specific,true);
				$points_user = (int)($relation == "+"?$points_user+$points:$points_user-$points);
				update_user_meta($user_id,$points_type_specific,($points_user > 0?$points_user:0));

				$points_type_specific = "points_date_".date("n-Y");
				$points_user = (int)get_user_meta($user_id,$points_type_specific,true);
				$points_user = (int)($relation == "+"?$points_user+$points:$points_user-$points);
				update_user_meta($user_id,$points_type_specific,($points_user > 0?$points_user:0));

				$points_type_specific = "points_date_".date("Y");
				$points_user = (int)get_user_meta($user_id,$points_type_specific,true);
				$points_user = (int)($relation == "+"?$points_user+$points:$points_user-$points);
				update_user_meta($user_id,$points_type_specific,($points_user > 0?$points_user:0));
			}
			if ($items == true) {
				$_points = (int)get_user_meta($user_id,$user_id."_".$points_type,true);
				$_points++;
				update_user_meta($user_id,$user_id."_".$points_type,$_points);
				add_user_meta($user_id,$user_id."_".$points_type."_".$_points,array(date_i18n('Y/m/d',current_time('timestamp')),date_i18n('g:i a',current_time('timestamp')),$points,$relation,$message,($post_id > 0?$post_id:""),($comment_id > 0?$comment_id:""),"time" => current_time('timestamp'),"user_id" => ($another_user_id > 0?$another_user_id:"")));
			}
			$points_user = (int)get_user_meta($user_id,$points_type,true);
			$points_user = (int)($relation == "+"?$points_user+$points:$points_user-$points);
			update_user_meta($user_id,$points_type,($points_user > 0?$points_user:0));

			$active_points_category = wpqa_options("active_points_category");
			if ($active_points_category == "on" && $points_type == "points") {
				$categories = wp_get_post_terms($post_id,'question-category',array('fields' => 'ids'));
				$categories = (is_array($categories) && !empty($categories)?$categories:get_post_meta($post_id,"question_category",true));
				if (isset($categories) && is_array($categories) && !empty($categories)) {
					foreach ($categories as $category) {
						$categories_user_points = get_user_meta($user_id,"categories_user_points",true);
						if (empty($categories_user_points)) {
							update_user_meta($user_id,"categories_user_points",array($category));
						}else if (is_array($categories_user_points) && !in_array($category,$categories_user_points)) {
							update_user_meta($user_id,"categories_user_points",array_merge($categories_user_points,array($category)));
						}
						$_points_category = (int)get_user_meta($user_id,$user_id."_points_category".$category,true);
						$_points_category++;

						update_user_meta($user_id,$user_id."_points_category".$category,($_points_category > 0?$_points_category:0));
						add_user_meta($user_id,$user_id."_points_category_".$category.$_points_category,array(date_i18n('Y/m/d',current_time('timestamp')),date_i18n('g:i a',current_time('timestamp')),$points,$relation,$message,($post_id > 0?$post_id:""),($comment_id > 0?$comment_id:""),"time" => current_time('timestamp'),"user_id" => ($another_user_id > 0?$another_user_id:"")));

						$points_category_user = (int)get_user_meta($user_id,"points_category".$category,true);
						$points_category_user = (int)($relation == "+"?$points_category_user+$points:$points_category_user-$points);
						update_user_meta($user_id,"points_category".$category,($points_category_user > 0?$points_category_user:0));

						if ($active_points_specific == "on") {
							$start_of_week = get_option("start_of_week");
							if ($start_of_week == 0) {
								$start_of_week = "Sunday";
							}else if ($start_of_week == 1) {
								$start_of_week = "Monday";
							}else if ($start_of_week == 2) {
								$start_of_week = "Tuesday";
							}else if ($start_of_week == 3) {
								$start_of_week = "Wednesday";
							}else if ($start_of_week == 4) {
								$start_of_week = "Thursday";
							}else if ($start_of_week == 5) {
								$start_of_week = "Friday";
							}else if ($start_of_week == 6) {
								$start_of_week = "Saturday";
							}

							$points_type_specific = "points_category".$category."_date_".date("j-n-Y");
							$points_user = (int)get_user_meta($user_id,$points_type_specific,true);
							$points_user = (int)($relation == "+"?$points_user+$points:$points_user-$points);
							update_user_meta($user_id,$points_type_specific,($points_user > 0?$points_user:0));

							$points_type_specific = "points_category".$category."_date_".date("Y-m-d H:i:s",strtotime($start_of_week.' this week'));
							$points_user = (int)get_user_meta($user_id,$points_type_specific,true);
							$points_user = (int)($relation == "+"?$points_user+$points:$points_user-$points);
							update_user_meta($user_id,$points_type_specific,($points_user > 0?$points_user:0));

							$points_type_specific = "points_category".$category."_date_".date("n-Y");
							$points_user = (int)get_user_meta($user_id,$points_type_specific,true);
							$points_user = (int)($relation == "+"?$points_user+$points:$points_user-$points);
							update_user_meta($user_id,$points_type_specific,($points_user > 0?$points_user:0));

							$points_type_specific = "points_category".$category."_date_".date("Y");
							$points_user = (int)get_user_meta($user_id,$points_type_specific,true);
							$points_user = (int)($relation == "+"?$points_user+$points:$points_user-$points);
							update_user_meta($user_id,$points_type_specific,($points_user > 0?$points_user:0));
						}
					}
				}
			}
		}
	}
endif;
/* Insert a new point */
function wpqa_insert_points($user_id = "",$another_user_id = "",$username = "",$post_id = "",$comment_id = "",$text = "",$more_text = "",$array = array()) {
	$active_points = wpqa_options("active_points");
	if ($active_points == "on") {
		$data = array(
			'post_title'  => $text,
			'post_status' => "publish",
			'post_author' => $user_id,
			'post_type'   => "point"
		);
		$point_id = wp_insert_post($data);
		if ($point_id == 0 || is_wp_error($point_id)) {
			error_log(esc_html__("Error in post.","wpqa"));
		}else {
			$variables = array();
			if ($another_user_id != "") {
				$variables["point_another_user_id"] = $another_user_id;
			}
			if ($username != "") {
				$variables["_username"] = $username;
			}
			if ($post_id != "") {
				$variables["point_post_id"] = $post_id;
			}
			if ($comment_id != "") {
				$variables["point_comment_id"] = $comment_id;
			}
			if ($more_text != "") {
				$variables["point_more_text"] = $more_text;
			}
			if (is_array($variables) && !empty($variables)) {
				foreach ($variables as $key => $value) {
					update_post_meta($point_id,$key,$value);
				}
			}
		}
	}
}
/* Get point result */
function wpqa_point_result($post,$admin = "") {
	$another_user_id = get_post_meta($post->ID,"point_another_user_id",true);
	$username = get_post_meta($post->ID,"point_username",true);
	$post_id = get_post_meta($post->ID,"point_post_id",true);
	$comment_id = get_post_meta($post->ID,"point_comment_id",true);
	$more_text = get_post_meta($post->ID,"point_more_text",true);

	$point_result = array();
	$point_result["text"] = get_the_title($post->ID);
	$point_result["user_id"] = $post->post_author;
	$date_format = wpqa_options("date_format");
	$date_format = ($date_format?$date_format:get_option("date_format"));
	$time_format = wpqa_options("time_format");
	$time_format = ($time_format?$time_format:get_option("time_format"));
	$point_result["time"] = sprintf(esc_html__('%1$s at %2$s','wpqa'),get_the_time($date_format,$post->ID),get_the_time($time_format,$post->ID));
	if ($another_user_id != "") {
		$point_result["another_user_id"] = $another_user_id;
	}
	if ($username != "") {
		$point_result["username"] = $username;
	}
	if ($post_id != "") {
		$point_result["post_id"] = $post_id;
	}
	if ($comment_id != "") {
		$point_result["comment_id"] = $comment_id;
	}
	if ($more_text != "") {
		$point_result["more_text"] = $more_text;
	}
	return $point_result;
}?>