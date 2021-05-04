<?php
/**
 * Remove update notices for revolution slider
 *
 * @return void
 */
function auxin_compatibility_revslider_remove_plugins_purchase_notice(){
    remove_action( 'after_plugin_row_revslider/revslider.php', array('RevSliderAdmin', 'show_purchase_notice'), 10 );
    remove_action( 'after_plugin_row_revslider/revslider.php', array('RevSliderAdmin', 'show_update_notice'  ), 10 );
}
add_action( 'admin_notices', 'auxin_compatibility_revslider_remove_plugins_purchase_notice', 13 );
