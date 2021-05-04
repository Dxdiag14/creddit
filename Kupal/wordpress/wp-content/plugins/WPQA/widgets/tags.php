<?php

/* @author    2codeThemes
*  @package   WPQA/widgets
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
   exit; // Exit if accessed directly
}

/* Tag Cloud */
function wpqa_widget_tag_cloud_args($args,$instance) {
	$number_tags = (isset($instance['number_tags'])?$instance['number_tags']:'');
	$new_args = array('number' => $number_tags);
	$args = wp_parse_args($args,$new_args);
return $args;
}
add_filter('widget_tag_cloud_args','wpqa_widget_tag_cloud_args',1,2);
?>