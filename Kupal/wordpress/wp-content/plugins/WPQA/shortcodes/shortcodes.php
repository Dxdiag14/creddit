<?php

/* @author    2codeThemes
*  @package   WPQA/shortcodes
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

require_once plugin_dir_path(dirname(__FILE__)).'shortcodes/category.php';
require_once plugin_dir_path(dirname(__FILE__)).'shortcodes/comment.php';
require_once plugin_dir_path(dirname(__FILE__)).'shortcodes/group.php';
require_once plugin_dir_path(dirname(__FILE__)).'shortcodes/posts.php';
require_once plugin_dir_path(dirname(__FILE__)).'shortcodes/login.php';
require_once plugin_dir_path(dirname(__FILE__)).'shortcodes/password.php';
require_once plugin_dir_path(dirname(__FILE__)).'shortcodes/post.php';
require_once plugin_dir_path(dirname(__FILE__)).'shortcodes/profile.php';
require_once plugin_dir_path(dirname(__FILE__)).'shortcodes/question.php';
require_once plugin_dir_path(dirname(__FILE__)).'shortcodes/register.php';
/* Add category shortcode */
add_shortcode('wpqa_add_category','wpqa_add_category_attr');
/* Group shortcode */
add_shortcode('wpqa_group','wpqa_group');
/* Edit group shortcode */
add_shortcode('wpqa_edit_group','wpqa_edit_group_attr');
/* Posts shortcode */
add_shortcode('wpqa_group_posts','wpqa_group_posts');
/* Edit posts shortcode */
add_shortcode('wpqa_edit_group_posts','wpqa_edit_posts_attr');
/* Edit comment shortcode */
add_shortcode('wpqa_edit_comment','wpqa_edit_comment_attr');
/* Login shortcode */
add_shortcode('wpqa_login','wpqa_login');
/* Lost password shortcode */
add_shortcode('wpqa_lost_pass','wpqa_lost_pass');
/* Add post shortcode */
add_shortcode('wpqa_add_post','wpqa_add_post_attr');
/* Edit post shortcode */
add_shortcode('wpqa_edit_post','wpqa_edit_post_attr');
/* Edit profile shortcode */
add_shortcode('wpqa_edit_profile','wpqa_edit_profile');
/* Question shortcode */
add_shortcode('wpqa_question','wpqa_question');
/* Edit question shortcode */
add_shortcode('wpqa_edit_question','wpqa_edit_question_attr');
/* Signup shortcode */
add_shortcode('wpqa_signup','wpqa_signup_attr');
/* Send message shortcode */
add_shortcode('wpqa_send_message','wpqa_send_message_shortcode');?>