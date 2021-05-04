<?php

/* @author    2codeThemes
*  @package   WPQA/templates
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
   exit; // Exit if accessed directly
}

do_action("wpqa_before_edit_post_group");

echo "<div class='wpqa-templates wpqa-edit-post-group-template wpqa-default-template'>";
	$post_id = (int)get_query_var(apply_filters('wpqa_edit_posts_group','edit_post_group'));
	echo do_shortcode("[wpqa_edit_group_posts post_id='".$post_id."']");
echo "</div>";

do_action("wpqa_after_edit_post_group");?>