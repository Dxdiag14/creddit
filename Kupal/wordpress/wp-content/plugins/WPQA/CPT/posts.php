<?php

/* @author    2codeThemes
*  @package   WPQA/CPT
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* Posts post type */
if (!function_exists('wpqa_posts_post_type')) :
	function wpqa_posts_post_type() {
		$active_groups = wpqa_options('active_groups');
		if ($active_groups == "on") {
			$posts_slug = wpqa_options('posts_slug');
			$posts_slug = ($posts_slug != ""?$posts_slug:"posts");
		   
		    register_post_type('posts',
		        array(
		        	'label' => esc_html__('Posts','wpqa'),
		            'labels' => array(
						'name'               => esc_html__('Posts','wpqa'),
						'singular_name'      => esc_html__('Posts','wpqa'),
						'menu_name'          => esc_html__('Posts','wpqa'),
						'name_admin_bar'     => esc_html__('Post','wpqa'),
						'add_new'            => esc_html__('Add New','wpqa'),
						'add_new_item'       => esc_html__('Add New Post','wpqa'),
						'new_item'           => esc_html__('New Post','wpqa'),
						'edit_item'          => esc_html__('Edit Post','wpqa'),
						'view_item'          => esc_html__('View Post Group','wpqa'),
						'view_items'         => esc_html__('View Posts','wpqa'),
						'all_items'          => esc_html__('All Posts','wpqa'),
						'search_items'       => esc_html__('Search Posts','wpqa'),
						'parent_item_colon'  => esc_html__('Parent Post:','wpqa'),
						'not_found'          => esc_html__('No Posts Found.','wpqa'),
						'not_found_in_trash' => esc_html__('No Posts Found in Trash.','wpqa'),
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
		            'rewrite'             => array('slug' => $posts_slug,'with_front' => false),
		            'query_var'           => false,
		            'show_in_rest'        => false,
		            'has_archive'         => false,
					'menu_position'       => 5,
					'show_in_menu'        => 'edit.php?post_type=group',
		            'supports'            => array('editor','comments','author'),
		        )
		    );
		}
	}
endif;
add_action('wpqa_init','wpqa_posts_post_type',0);
/* Posts menus */
add_action('admin_menu','wpqa_add_admin_posts');
function wpqa_add_admin_posts() {
	add_submenu_page('edit.php?post_type=group',esc_html__('Group Comments','wpqa'),esc_html__('Group Comments','wpqa'),'manage_options','edit-comments.php?comment_status=group-comments');
}
/* Admin columns for post types */
if (!function_exists('wpqa_posts_columns')) :
	function wpqa_posts_columns($old_columns){
		$columns = array();
		$columns["cb"]       = "<input type=\"checkbox\">";
		$columns["title"]    = esc_html__("Title","wpqa");
		$columns["author-p"] = esc_html__("Author","wpqa");
		$columns["group"]    = esc_html__("Group","wpqa");
		$columns["comments"] = "<span class='vers comment-grey-bubble' title='".esc_attr__("Comments","wpqa")."'><span class='screen-reader-text'>".esc_html__("Comments","wpqa")."</span></span>";
		$columns["date"]     = esc_html__("Date","wpqa");
		return $columns;
	}
endif;
add_filter('manage_edit-posts_columns', 'wpqa_posts_columns');
if (!function_exists('wpqa_posts_custom_columns')) :
	function wpqa_posts_custom_columns($column) {
		global $post;
		$posts_details = wpqa_posts_get_posts_details( $post->ID );
		switch ( $column ) {
			case 'author-p' :
				$user_name = get_the_author_meta('display_name',$post->post_author);
				echo '<a target="_blank" href="'.wpqa_profile_url((int)$post->post_author).'"><strong>'.$user_name.'</strong></a><a class="tooltip_s" data-title="'.esc_html__("View posts","wpqa").'" href="'.admin_url('edit.php?post_type=posts&author='.$post->post_author).'"><i class="dashicons dashicons-groups"></i></a>';
			break;
			case 'group' :
				remove_filter('the_title','wpqa_posts_new_title',100,2);
				$group_id = (int)get_post_meta($post->ID,"group_id",true);
				$get_permalink = get_permalink($group_id);
				if ($get_permalink != "") {
					echo '<a target="_blank" href="'.$get_permalink.'"><strong>'.get_the_title($group_id).'</strong></a>';
				}else {
					echo '<span aria-hidden="true">—</span><span class="screen-reader-text">'.esc_html__("Deleted group","wpqa").'</span>';
				}
				add_filter('the_title','wpqa_posts_new_title',100,2);
			break;
			case 'users' :
				$user_name = get_the_author_meta('display_name',$post->post_author);
				echo '<a target="_blank" href="'.wpqa_profile_url((int)$post->post_author).'"><strong>'.$user_name.'</strong></a><a class="tooltip_s" data-title="'.esc_html__("View posts","wpqa").'" href="'.admin_url('edit.php?post_type=posts&author='.$post->post_author).'"><i class="dashicons dashicons-groups"></i></a>';
			break;
		}
	}
endif;
add_action('manage_posts_posts_custom_column','wpqa_posts_custom_columns',2);
/* Get posts details */
if (!function_exists('wpqa_posts_get_posts_details')) :
	function wpqa_posts_get_posts_details( $post_id ) { 
		$status = current(wp_get_object_terms( $post_id, 'site_status' ));
		return $post_id;
	}
endif;
/* Message update */
if (!function_exists('wpqa_posts_updated_messages')) :
	function wpqa_posts_updated_messages($messages) {
	  global $post,$post_ID;
	  $messages['posts'] = array(
	    0 => '',
	    1 => sprintf(esc_html__('Updated. %1$s View post %2$s','wpqa'),'<a href="'.esc_url(get_permalink($post_ID)).'">','</a>'),
	  );
	  return $messages;
	}
endif;
add_filter('post_updated_messages','wpqa_posts_updated_messages');
/* Posts title */
add_action('admin_head-edit.php','wpqa_edit_posts_change_title');
function wpqa_edit_posts_change_title() {
	global $post;
	if ((isset($_GET["post_type"]) && $_GET["post_type"] == "posts") || (isset($post->post_type) && $post->post_type == "posts")) {
	    add_filter('the_title','wpqa_posts_new_title',100,2);
	}
}
function wpqa_posts_new_title($title,$id) {
	global $post;
	if ($post->post_type == "posts") {
		return wpqa_excerpt(10,"words","return");
	}
}?>