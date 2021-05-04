<?php
/**
 * Customizer callback functions for active_callback.
 *
 * @package EnterNews
 */


    /*select page for trending news*/
if (!function_exists('enternews_show_watch_online_section_status')) :

    /**
     * Check if slider section page/post is active.
     *
     * @since 1.0.0
     *
     * @param WP_Customize_Control $control WP_Customize_Control instance.
     *
     * @return bool Whether the control is active to the current preview.
     */
    function enternews_show_watch_online_section_status($control)
    {

        if (true == $control->manager->get_setting('show_watch_online_section')->value()) {
            return true;
        } else {
            return false;
        }

    }

endif;


/*select page for trending news*/
if (!function_exists('enternews_flash_posts_section_status')) :

    /**
     * Check if slider section page/post is active.
     *
     * @since 1.0.0
     *
     * @param WP_Customize_Control $control WP_Customize_Control instance.
     *
     * @return bool Whether the control is active to the current preview.
     */
    function enternews_flash_posts_section_status($control)
    {

        if (true == $control->manager->get_setting('show_flash_news_section')->value()) {
            return true;
        } else {
            return false;
        }

    }

endif;


/*select page for slider*/
if (!function_exists('enternews_main_banner_section_status')) :

    /**
     * Check if slider section page/post is active.
     *
     * @since 1.0.0
     *
     * @param WP_Customize_Control $control WP_Customize_Control instance.
     *
     * @return bool Whether the control is active to the current preview.
     */
    function enternews_main_banner_section_status($control)
    {

        if (true == $control->manager->get_setting('show_main_news_section')->value()) {
            return true;
        } else {
            return false;
        }

    }

endif;
    




/*select page for slider*/
if (!function_exists('enternews_main_banner_editorials_layout_status')) :

    /**
     * Check if slider section page/post is active.
     *
     * @since 1.0.0
     *
     * @param WP_Customize_Control $control WP_Customize_Control instance.
     *
     * @return bool Whether the control is active to the current preview.
     */
    function enternews_main_banner_editorials_layout_status($control)
    {

        if (('default' == $control->manager->get_setting('select_main_banner_section_mode')->value()) || ('layout-2' == $control->manager->get_setting('select_main_banner_section_mode')->value()) || ('layout-3' == $control->manager->get_setting('select_main_banner_section_mode')->value()) || ('layout-6' == $control->manager->get_setting('select_main_banner_section_mode')->value()) || ('layout-7' == $control->manager->get_setting('select_main_banner_section_mode')->value()))  {
            return true;
        } else {
            return false;
        }

    }

endif;

/*select page for slider*/
if (!function_exists('enternews_main_banner_trending_layout_status')) :

    /**
     * Check if slider section page/post is active.
     *
     * @since 1.0.0
     *
     * @param WP_Customize_Control $control WP_Customize_Control instance.
     *
     * @return bool Whether the control is active to the current preview.
     */
    function enternews_main_banner_trending_layout_status($control)
    {

        if (('default' == $control->manager->get_setting('select_main_banner_section_mode')->value()) || ('layout-2' == $control->manager->get_setting('select_main_banner_section_mode')->value()) || ('layout-3' == $control->manager->get_setting('select_main_banner_section_mode')->value()) || ('layout-4' == $control->manager->get_setting('select_main_banner_section_mode')->value()) || ('layout-5' == $control->manager->get_setting('select_main_banner_section_mode')->value())) {
            return true;
        } else {
            return false;
        }

    }

endif;





    /*select page for slider*/
if (!function_exists('enternews_global_site_mode_dark_light_status')) :

    /**
     * Check if slider section page/post is active.
     *
     * @since 1.0.0
     *
     * @param WP_Customize_Control $control WP_Customize_Control instance.
     *
     * @return bool Whether the control is active to the current preview.
     */
    function enternews_global_site_mode_dark_light_status($control)
    {

        if (('aft-default-mode' !== $control->manager->get_setting('global_site_mode_setting')->value())) {

            return true;
        } else {
            return false;
        }

    }

endif;






/*select page for slider*/
if (!function_exists('enternews_featured_news_section_status')) :

    /**
     * Check if slider section page/post is active.
     *
     * @since 1.0.0
     *
     * @param WP_Customize_Control $control WP_Customize_Control instance.
     *
     * @return bool Whether the control is active to the current preview.
     */
    function enternews_featured_news_section_status($control)
    {

        if ( 1 == $control->manager->get_setting('show_featured_news_section')->value()) {
            return true;
        } else {
            return false;
        }

    }

endif;




/*select page for slider*/
if (!function_exists('enternews_latest_news_section_status')) :

    /**
     * Check if ticker section page/post is active.
     *
     * @since 1.0.0
     *
     * @param WP_Customize_Control $control WP_Customize_Control instance.
     *
     * @return bool Whether the control is active to the current preview.
     */
    function enternews_latest_news_section_status($control)
    {

        if (true == $control->manager->get_setting('frontpage_show_latest_posts')->value()) {
            return true;
        } else {
            return false;
        }

    }

endif;


/*select page for slider*/
if (!function_exists('enternews_archive_image_status')) :

    /**
     * Check if archive no image is active.
     *
     * @since 1.0.0
     *
     * @param WP_Customize_Control $control WP_Customize_Control instance.
     *
     * @return bool Whether the control is active to the current preview.
     */
    function enternews_archive_image_status($control)
    {

        if ('archive-layout-list' == $control->manager->get_setting('archive_layout')->value()) {
            return true;
        } else {
            return false;
        }

    }

endif;

/*select page for slider*/
if (!function_exists('enternews_archive_image_gird_status')) :

    /**
     * Check if archive no image is active.
     *
     * @since 1.0.0
     *
     * @param WP_Customize_Control $control WP_Customize_Control instance.
     *
     * @return bool Whether the control is active to the current preview.
     */
    function enternews_archive_image_gird_status($control)
    {

        if ('archive-layout-grid' == $control->manager->get_setting('archive_layout')->value()) {
            return true;
        } else {
            return false;
        }

    }

endif;


/*select page for slider*/
if (!function_exists('enternews_archive_image_full_status')) :

    /**
     * Check if archive no image is active.
     *
     * @since 1.0.0
     *
     * @param WP_Customize_Control $control WP_Customize_Control instance.
     *
     * @return bool Whether the control is active to the current preview.
     */
    function enternews_archive_image_full_status($control)
    {

        if ('archive-layout-full' == $control->manager->get_setting('archive_layout')->value()) {
            return true;
        } else {
            return false;
        }

    }

endif;

/*related posts*/
if (!function_exists('enternews_related_posts_status')) :

    /**
     * Check if slider section page/post is active.
     *
     * @since 1.0.0
     *
     * @param WP_Customize_Control $control WP_Customize_Control instance.
     *
     * @return bool Whether the control is active to the current preview.
     */
    function enternews_related_posts_status($control)
    {

        if (true == $control->manager->get_setting('single_show_related_posts')->value()) {
            return true;
        } else {
            return false;
        }

    }

endif;


/*mailchimp*/
if (!function_exists('enternews_mailchimp_subscriptions_status')) :

    /**
     * Check if slider section page/post is active.
     *
     * @since 1.0.0
     *
     * @param WP_Customize_Control $control WP_Customize_Control instance.
     *
     * @return bool Whether the control is active to the current preview.
     */
    function enternews_mailchimp_subscriptions_status($control)
    {

        if (true == $control->manager->get_setting('footer_show_mailchimp_subscriptions')->value()) {
            return true;
        } else {
            return false;
        }

    }

endif;

/*select page for slider*/
if (!function_exists('enternews_footer_instagram_posts_status')) :

    /**
     * Check if slider section page/post is active.
     *
     * @since 1.0.0
     *
     * @param WP_Customize_Control $control WP_Customize_Control instance.
     *
     * @return bool Whether the control is active to the current preview.
     */
    function enternews_footer_instagram_posts_status($control)
    {

        if (true == $control->manager->get_setting('footer_show_instagram_post_carousel')->value()) {
            return true;
        } else {
            return false;
        }

    }

endif;


/*select page for slider*/
if (!function_exists('enternews_global_show_comment_count_status')) :

    /**
     * Check if slider section page/post is active.
     *
     * @since 1.0.0
     *
     * @param WP_Customize_Control $control WP_Customize_Control instance.
     *
     * @return bool Whether the control is active to the current preview.
     */
    function enternews_global_show_comment_count_status($control)
    {

        if ('yes' == $control->manager->get_setting('global_show_comment_count')->value()) {
            return true;
        } else {
            return false;
        }

    }

endif;


/*select page for slider*/
if (!function_exists('enternews_global_show_minutes_count_status')) :

    /**
     * Check if slider section page/post is active.
     *
     * @since 1.0.0
     *
     * @param WP_Customize_Control $control WP_Customize_Control instance.
     *
     * @return bool Whether the control is active to the current preview.
     */
    function enternews_global_show_minutes_count_status($control)
    {

        if ('yes' == $control->manager->get_setting('global_show_min_read')->value()) {
            return true;
        } else {
            return false;
        }

    }

endif;

