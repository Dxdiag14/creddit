<?php

/* @author    2codeThemes
*  @package   WPQA/templates/profile
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
   exit; // Exit if accessed directly
}

include wpqa_get_template("edit-head.php","profile/");

echo do_shortcode("[wpqa_edit_profile type='financial']");?>