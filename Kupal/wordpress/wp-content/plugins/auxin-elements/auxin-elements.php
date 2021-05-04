<?php
/**
 * Extra features, shortcodes and widgets for themes with auxin framework (i.e Phlox Theme)
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
 *
 * Plugin Name:       Phlox Core Elements
 * Plugin URI:        https://wordpress.org/plugins/auxin-elements/
 * Description:       Exclusive and comprehensive plugin that extends the functionality of Phlox theme by adding new Elements, widgets and options.
 * Version:           2.7.12
 * Author:            averta
 * Author URI:        http://averta.net
 * Text Domain:       auxin-elements
 * License:           GPL2
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Domain Path:       /languages
 * Tested up to:      5.7.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die('No Naughty Business Please !');
}

// Abort loading if WordPress is upgrading
if ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) {
    return;
}

/**
 * Check plugin requirements
 * ===========================================================================*/

// Don't check the requirements if it's frontend or AUXIN_DUBUG set to false
if( is_admin() ||
    false === get_transient( 'auxels_plugin_requirements_check' ) ||
    ! file_exists( get_template_directory() . '/auxin-content/init/dependency.php' )
){

    if( ! class_exists('Auxin_Plugin_Requirements') ){
        require_once( plugin_dir_path( __FILE__ ) . 'includes/classes/class-auxin-plugin-requirements.php' );
    }

    $plugin_requirements = new Auxin_Plugin_Requirements();
    $plugin_requirements->requirements = array(

        'plugins' => array(),
        'themes' => array(
            array(
                'name'                 => __('Phlox Pro', 'auxin-elements'), // The theme name.
                'id'                   => 'phlox-pro', // The theme id name.
                'version'              => '5.1.5', // E.g. 1.0.0. If set, the active theme must be this version or higher.
                'is_callable'          => '', // If set, this callable will be be checked for availability to determine if a theme is active.
                'theme_requires_const' => 'AUXELS_REQUIRED_VERSION',
                'file_required'        => array( get_template_directory() . '/auxin-content/init/dependency.php', get_template_directory() . '/auxin-content/init/constant.php' )
            ),
            array(
                'name'                 => __('Phlox', 'auxin-elements'), // The theme name.
                'id'                   => 'phlox', // The theme id name.
                'update_link'          => 'themes.php?theme=phlox',
                'version'              => '2.3.8', // E.g. 1.0.0. If set, the active theme must be this version or higher.
                'is_callable'          => '', // If set, this callable will be be checked for availability to determine if a theme is active.
                'theme_requires_const' => 'AUXELS_REQUIRED_VERSION',
                'file_required'        => array( get_template_directory() . '/auxin-content/init/dependency.php', get_template_directory() . '/auxin-content/init/constant.php' )
            )
        ),

        'config' => array(
            'plugin_name'     =>  __('Phlox Core Elements', 'auxin-elements'), // Current plugin name.
            'plugin_basename' => plugin_basename( __FILE__ ),
            'plugin_dir_path' => plugin_dir_path( __FILE__ ),
            'debug'           => false
        )

    );

    // Check the requirements
    $validation = $plugin_requirements->validate();

    // If the requirements were not met, dont initialize the plugin
    if( true !== $validation ){
        return;
    // cache the validation result and skip the extra checks on frontend for cache period.
    } else {
        set_transient( 'auxels_plugin_requirements_check', true, 15 * MINUTE_IN_SECONDS );
    }
}

// Flush dependency check on absence of core element plugin
add_action( 'plugins_loaded', function(){
    if( ! function_exists( 'AUXELS' ) ){
        delete_transient( 'auxels_plugin_requirements_check' );
        delete_transient( 'auxpfo_plugin_requirements_check' );
        delete_transient( 'auxshp_plugin_requirements_check' );
        delete_transient( 'auxnew_plugin_requirements_check' );
        delete_transient( 'auxpro_plugin_requirements_check' );
    }
});

/**
 * Initialize the plugin
 * ===========================================================================*/

require_once( plugin_dir_path( __FILE__ ) . 'includes/define.php' 	  );
require_once( plugin_dir_path( __FILE__ ) . 'public/class-auxels.php' );

// Register hooks that are fired when the plugin is activated or deactivated.
register_activation_hook  ( __FILE__, array( 'AUXELS', 'activate'   ) );
register_deactivation_hook( __FILE__, array( 'AUXELS', 'deactivate' ) );

/*============================================================================*/
