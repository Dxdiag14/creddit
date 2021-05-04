<?php

/**
 * Front page section additions.
 */


if (!function_exists('enternews_full_width_upper_footer_section')) :
    /**
     *
     * @since EnterNews 1.0.0
     *
     * @param null
     * @return null
     *
     */
    function enternews_full_width_upper_footer_section()
    {

        if (1 == enternews_get_option('frontpage_show_latest_posts')) {
            enternews_get_block('latest');
        }

    }
endif;
add_action('enternews_action_full_width_upper_footer_section', 'enternews_full_width_upper_footer_section');
