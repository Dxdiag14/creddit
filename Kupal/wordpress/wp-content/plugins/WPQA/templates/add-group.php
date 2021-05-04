<?php

/* @author    2codeThemes
*  @package   WPQA/templates
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
   exit; // Exit if accessed directly
}

do_action("wpqa_before_add_group");

echo "<div class='wpqa-templates wpqa-add-group-template wpqa-default-template'>".do_shortcode("[wpqa_group]")."</div>";

do_action("wpqa_after_add_group");?>