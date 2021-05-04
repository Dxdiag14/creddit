<?php
/**
 * Remove update notices for layer slider
 *
 * @return void
 */
function auxin_compatibility_layerslider_remove_plugins_purchase_notice(){
    remove_action( 'after_plugin_row_LayerSlider/layerslider.php', 'layerslider_plugins_purchase_notice', 10 );
    remove_action('admin_notices', 'layerslider_important_notice');
    remove_action('admin_notices', 'layerslider_update_notice');
    remove_action('admin_notices', 'layerslider_unauthorized_update_notice');
}
add_action( 'admin_notices', 'auxin_compatibility_layerslider_remove_plugins_purchase_notice', 12 );
