<?php

/* @author    2codeThemes
*  @package   WPQA/templates
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
   exit; // Exit if accessed directly
}

do_action("wpqa_before_lost_password");

echo "<div class='wpqa-templates wpqa-lost-password-template'>".do_shortcode("[wpqa_lost_pass]")."</div>";

do_action("wpqa_after_lost_password");?>