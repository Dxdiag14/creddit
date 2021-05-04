<?php

/* @author    2codeThemes
*  @package   WPQA/templates
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
   exit; // Exit if accessed directly
}

do_action("wpqa_before_edit_question");

echo "<div class='wpqa-templates wpqa-edit-question-template'>".do_shortcode("[wpqa_edit_question]")."</div>";

do_action("wpqa_after_edit_question");?>