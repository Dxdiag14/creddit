<?php

/* @author    2codeThemes
*  @package   WPQA/widgets
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$wpqa = new WPQA;
require_once plugin_dir_path(dirname(__FILE__)).'widgets/activities.php';
require_once plugin_dir_path(dirname(__FILE__)).'widgets/buttons.php';
require_once plugin_dir_path(dirname(__FILE__)).'widgets/comments.php';
require_once plugin_dir_path(dirname(__FILE__)).'widgets/groups.php';
require_once plugin_dir_path(dirname(__FILE__)).'widgets/login.php';
require_once plugin_dir_path(dirname(__FILE__)).'widgets/notifications.php';
require_once plugin_dir_path(dirname(__FILE__)).'widgets/profile_strength.php';
require_once plugin_dir_path(dirname(__FILE__)).'widgets/posts.php';
require_once plugin_dir_path(dirname(__FILE__)).'widgets/questions_categories.php';
require_once plugin_dir_path(dirname(__FILE__)).'widgets/related_questions.php';
require_once plugin_dir_path(dirname(__FILE__)).'widgets/rules.php';
require_once plugin_dir_path(dirname(__FILE__)).'widgets/signup.php';
require_once plugin_dir_path(dirname(__FILE__)).'widgets/stats.php';
require_once plugin_dir_path(dirname(__FILE__)).'widgets/tabs.php';
require_once plugin_dir_path(dirname(__FILE__)).'widgets/tags.php';
require_once plugin_dir_path(dirname(__FILE__)).'widgets/users.php';