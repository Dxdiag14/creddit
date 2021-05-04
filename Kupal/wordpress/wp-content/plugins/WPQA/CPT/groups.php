<?php

/* @author    2codeThemes
*  @package   WPQA/CPT
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* Group post type */
if (!function_exists('wpqa_group_post_type')) :
	function wpqa_group_post_type() {
		$active_groups = wpqa_options('active_groups');
		if ($active_groups == "on") {
			$archive_group_slug  = wpqa_options('archive_group_slug');
			$archive_group_slug  = ($archive_group_slug != ""?$archive_group_slug:"groups");

			$group_slug = wpqa_options('group_slug');
			$group_slug = ($group_slug != ""?$group_slug:"group");
		   
		    register_post_type('group',
		        array(
		        	'label' => esc_html__('Groups','wpqa'),
		            'labels' => array(
						'name'               => esc_html__('Groups','wpqa'),
						'singular_name'      => esc_html__('Groups','wpqa'),
						'menu_name'          => esc_html__('Groups','wpqa'),
						'name_admin_bar'     => esc_html__('Group','wpqa'),
						'add_new'            => esc_html__('Add New','wpqa'),
						'add_new_item'       => esc_html__('Add New Group','wpqa'),
						'new_item'           => esc_html__('New Group','wpqa'),
						'edit_item'          => esc_html__('Edit Group','wpqa'),
						'view_item'          => esc_html__('View Group','wpqa'),
						'view_items'         => esc_html__('View Groups','wpqa'),
						'all_items'          => esc_html__('All Groups','wpqa'),
						'search_items'       => esc_html__('Search Groups','wpqa'),
						'parent_item_colon'  => esc_html__('Parent Group:','wpqa'),
						'not_found'          => esc_html__('No Groups Found.','wpqa'),
						'not_found_in_trash' => esc_html__('No Groups Found in Trash.','wpqa'),
		            ),
		            'description'         => '',
		            'public'              => true,
		            'show_ui'             => true,
		            'capability_type'     => 'post',
		            'publicly_queryable'  => true,
		            'exclude_from_search' => false,
		            'hierarchical'        => false,
		            'rewrite'             => array('slug' => $group_slug,'with_front' => false),
		            'query_var'           => true,
		            'show_in_rest'        => true,
		            'has_archive'         => apply_filters("wpqa_archive_group",$archive_group_slug),
					'menu_position'       => 5,
					'menu_icon'           => "dashicons-groups",
		            'supports'            => array('title','author'),
		        )
		    );
		}
	}
endif;
add_action('wpqa_init','wpqa_group_post_type',0);
/* Admin columns for post types */
if (!function_exists('wpqa_group_columns')) :
	function wpqa_group_columns($old_columns){
		$columns = array();
		$columns["cb"]       = "<input type=\"checkbox\">";
		$columns["title"]    = esc_html__("Title","wpqa");
		$columns["author-g"] = esc_html__("Author","wpqa");
		$columns["posts"]    = esc_html__("Posts","wpqa");
		$columns["users"]    = esc_html__("Users","wpqa");
		$columns["date"]     = esc_html__("Date","wpqa");
		return $columns;
	}
endif;
add_filter('manage_edit-group_columns', 'wpqa_group_columns');
if (!function_exists('wpqa_group_custom_columns')) :
	function wpqa_group_custom_columns($column) {
		global $post;
		$group_details = wpqa_group_get_group_details( $post->ID );
		switch ( $column ) {
			case 'author-g' :
				$user_name = get_the_author_meta('display_name',$post->post_author);
				echo '<a target="_blank" href="'.wpqa_profile_url((int)$post->post_author).'"><strong>'.$user_name.'</strong></a><a class="tooltip_s" data-title="'.esc_html__("View groups","wpqa").'" href="'.admin_url('edit.php?post_type=group&author='.$post->post_author).'"><i class="dashicons dashicons-groups"></i></a>';
			break;
			case 'posts' :
				$group_posts = (int)get_post_meta($post->ID,"group_posts",true);
				echo wpqa_count_number($group_posts).' '._n("Post","Posts",$group_posts,"wpqa");
			break;
			case 'users' :
				$group_users = (int)get_post_meta($post->ID,"group_users",true);
				echo wpqa_count_number($group_users).' '._n("User","Users",$group_users,"wpqa");
			break;
		}
	}
endif;
add_action('manage_group_posts_custom_column','wpqa_group_custom_columns',2);
/* Get group details */
if (!function_exists('wpqa_group_get_group_details')) :
	function wpqa_group_get_group_details( $post_id ) { 
		$status = current(wp_get_object_terms( $post_id, 'site_status' ));
		return $post_id;
	}
endif;
/* Message update */
if (!function_exists('wpqa_group_updated_messages')) :
	function wpqa_group_updated_messages($messages) {
	  global $post,$post_ID;
	  $messages['group'] = array(
	    0 => '',
	    1 => sprintf(esc_html__('Updated. %1$s View group %2$s','wpqa'),'<a href="'.esc_url(get_permalink($post_ID)).'">','</a>'),
	  );
	  return $messages;
	}
endif;
add_filter('post_updated_messages','wpqa_group_updated_messages');
/* Groups status */
add_filter( "views_edit-group", "wpqa_groups_status" );
if (!function_exists('wpqa_groups_status')) :
	function wpqa_groups_status($status) {
		$get_status = (isset($_GET['types'])?esc_attr($_GET['types']):'');
		
		$query_group_private = wpqa_meta_count("group_privacy","private");
		$query_group_public = wpqa_meta_count("group_privacy","public");
		
		return array_merge( $status, array(
			'private' => '<a href="'.admin_url('edit.php?post_type=group&types=private').'"'.($get_status == "private"?' class="current"':'').'>'.esc_html__('Private','wpqa').' ('.$query_group_private.')</a>',
			'public' => '<a href="'.admin_url('edit.php?post_type=group&types=public').'"'.($get_status == "public"?' class="current"':'').'>'.esc_html__('Public','wpqa').' ('.$query_group_public.')</a>',
		));
	}
endif;
add_action('current_screen','wpqa_groups_exclude',10,2);
if (!function_exists('wpqa_groups_exclude')) :
	function wpqa_groups_exclude($screen) {
		if ($screen->id != 'edit-group')
			return;
		$get_status = (isset($_GET['types'])?esc_attr($_GET['types']):'');
		if ($get_status == "private" || $get_status == "public") {
			add_filter('parse_query','wpqa_list_groups');
		}
	}
endif;
if (!function_exists('wpqa_list_groups')) :
	function wpqa_list_groups($clauses) {
		$get_status = (isset($_GET['types'])?esc_attr($_GET['types']):'');
		if ($get_status == "private") {
			$clauses->query_vars['meta_key'] = "group_privacy";
			$clauses->query_vars['meta_value'] = "private";
			$clauses->query_vars['post_type'] = "group";
		}else if ($get_status == "public") {
			$clauses->query_vars['meta_key'] = "group_privacy";
			$clauses->query_vars['meta_value'] = "public";
			$clauses->query_vars['post_type'] = "group";
		}
	}
endif;
add_filter('manage_edit-group_sortable_columns','wpqa_group_sortable_columns');
function wpqa_group_sortable_columns($defaults) {
	$defaults['posts'] = 'group_posts';
	$defaults['users'] = 'group_users';
	return $defaults;
}
add_action('pre_get_posts','wpqa_group_filter');
function wpqa_group_filter($query) {
	global $post_type,$pagenow;
	if ($pagenow == 'edit.php' && $post_type == 'group') {
		$orderby = $query->get('orderby');
		if ($orderby == 'group_posts') {
			$query->query_vars('meta_key','group_posts');
			$query->query_vars('orderby','meta_value_num');
			$query->query_vars['meta_query'][] = array(
				'key'     => 'group_posts',
				'value'   => 0,
				'type'    => 'NUMERIC',
				'compare' => '>',
			);
		}else if ($orderby == 'group_users') {
			$query->query_vars('meta_key','group_users');
			$query->query_vars('orderby','meta_value_num');
			$query->query_vars['meta_query'][] = array(
				'key'     => 'group_users',
				'value'   => 0,
				'type'    => 'NUMERIC',
				'compare' => '>',
			);
		}
	}
}?>