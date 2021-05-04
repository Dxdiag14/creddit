<?php
/**
 * Recommended plugins
 *
 * @package EnterNews
 */

if ( ! function_exists( 'enternews_recommended_plugins' ) ) :

    /**
     * Recommend plugins.
     *
     * @since 1.0.0
     */
    function enternews_recommended_plugins() {

        $plugins = array(
            array(
                'name'     => esc_html__( 'AF Companion', 'enternews' ),
                'slug'     => 'af-companion',
                'required' => false,
            ),
            array(
                'name'     => esc_html__( 'Blockspare - Beautiful Page Building Blocks for WordPress', 'enternews' ),
                'slug'     => 'blockspare',
                'required' => false,
            ),
            array(
                'name'     => esc_html__( 'WP Post Author', 'enternews' ),
                'slug'     => 'wp-post-author',
                'required' => false,
            ),
            array(
                'name'     => esc_html__( 'Latest Posts Block Lite', 'enternews' ),
                'slug'     => 'latest-posts-block-lite',
                'required' => false,
            ),
            array(
                'name'     => esc_html__( 'Magic Content Box Lite', 'enternews' ),
                'slug'     => 'magic-content-box-lite',
                'required' => false,
            )
        );

        tgmpa( $plugins );

    }

endif;

add_action( 'tgmpa_register', 'enternews_recommended_plugins' );
