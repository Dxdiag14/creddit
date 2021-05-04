<?php
/**
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
*/

// no direct access allowed
if ( ! defined('ABSPATH') ) {
    die();
}

/**
 * WP Cli Auxin Commands
 */
class Auxin_CLI_Commands {
    
    /**
     * flush cache
     * 
     * @synopsis [--network] 
     */
    public function flush_cache( $args, $assoc_args ) {
        if ( ! class_exists( 'autoptimizeCache' ) ) {
            WP_CLI::error( 'Autoptimize plugin need to be active.' );
            return;
        }

        if ( ! isset( $assoc_args['network'] ) || ! is_multisite() ) {
                autoptimizeCache::clearall();
        } else {
            $sites = get_sites();
            foreach ( $sites as $site ) {
                switch_to_blog( $site->blog_id );
                autoptimizeCache::clearall();
                restore_current_blog();
            }
        }

        WP_CLI::success( 'Flushing cache proccess done.' );
    }
}

WP_CLI::add_command( 'auxin', 'Auxin_CLI_Commands');    

