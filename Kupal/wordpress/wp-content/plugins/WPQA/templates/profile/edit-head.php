<?php

/* @author    2codeThemes
*  @package   WPQA/templates/profile
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
   exit; // Exit if accessed directly
}

$list_child = "li";
echo '<div class="wrap-tabs"><div class="menu-tabs"><ul class="menu flex menu-tabs-desktop">';
	include wpqa_get_template("edit-tabs.php","profile/");
echo '</ul></div></div>';
$list_child = "option";
echo '<div class="wpqa_hide mobile-tabs"><span class="styled-select"><select class="home_categories">';
	include wpqa_get_template("edit-tabs.php","profile/");
echo '</select></span></div>';?>