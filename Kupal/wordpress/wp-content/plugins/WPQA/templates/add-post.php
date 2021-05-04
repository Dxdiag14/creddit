<?php

/* @author    2codeThemes
*  @package   WPQA/templates
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
   exit; // Exit if accessed directly
}

do_action("wpqa_before_add_post");

echo "<div class='wpqa-templates wpqa-add-post-template'>".do_shortcode("[wpqa_add_post]")."</div>";

do_action("wpqa_after_add_post");?>