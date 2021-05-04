<?php
/**
 * The template for displaying home page.
 * @package EnterNews
 */

get_header();
if ( 'posts' == get_option( 'show_on_front' ) ) {
    include( get_home_template() );
} else {

    /**
     * enternews_action_sidebar_section hook
     * @since EnterNews 1.0.0
     *
     * @hooked enternews_front_page_section -  20
     * @sub_hooked enternews_front_page_section -  20
     */
    do_action('enternews_front_page_section');


}
get_footer();