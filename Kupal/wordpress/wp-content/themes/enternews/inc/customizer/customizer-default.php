<?php
/**
 * Default theme options.
 *
 * @package EnterNews
 */

if (!function_exists('enternews_get_default_theme_options')):

/**
 * Get default theme options
 *
 * @since 1.0.0
 *
 * @return array Default theme options.
 */
function enternews_get_default_theme_options() {

    $defaults = array();
    // Preloader options section
    $defaults['enable_site_preloader'] = 1;

    // Header options section
    $defaults['header_layout'] = 'header-layout-side';
    $defaults['enable_container_padding'] = 0;

    $defaults['show_top_header_section'] = 0;
    $defaults['top_header_background_color'] = "#252525";
    $defaults['top_header_text_color'] = "#ffffff";

    $defaults['show_top_menu'] = 1;
    $defaults['show_social_menu_section'] = 1;
    $defaults['show_secondary_menu_section'] = 1;
    $defaults['enable_sticky_header_option'] = 0;
    
    $defaults['show_date_section'] = 1;

    $defaults['disable_header_image_tint_overlay'] = 0;
    $defaults['select_header_image_mode'] = 'default';


    $defaults['banner_advertisement_section'] = '';
    $defaults['banner_advertisement_section_url'] = '';
    $defaults['banner_advertisement_open_on_new_tab'] = 0;
    $defaults['banner_advertisement_scope'] = 'site-wide';


    // breadcrumb options section
    $defaults['enable_breadcrumb'] = 1;
    $defaults['select_breadcrumb_mode'] = 'default';

    // Frontpage Section.

    $defaults['show_popular_tags_section'] = 1;
    $defaults['show_popular_tags_title'] = __('Popular Tags', 'enternews');
    $defaults['number_of_popular_tags'] = 7;
    $defaults['select_popular_tags_mode'] = 'post_tag';

    $defaults['show_flash_news_section'] = 1;
    $defaults['flash_news_title'] = __('Headlines', 'enternews');
    $defaults['select_flash_news_category'] = 0;
    $defaults['number_of_flash_news'] = 5;
    $defaults['disable_animation']= 0;
    $defaults['select_flash_new_mode'] = 'flash-slide-left';
    $defaults['banner_flash_news_scope'] = 'front-page-only';

    $defaults['show_main_news_section'] = 1;
    $defaults['main_banner_section_label'] = __('Main News', 'enternews');

    $defaults['select_main_banner_section_mode'] = 'default';
    $defaults['select_main_banner_section_layout'] = 'wide';
    $defaults['main_banner_background_section'] = '';

    $defaults['select_editors_picks_section_background'] = 'dark';
    $defaults['select_main_banner_section_background'] = 'default';
    $defaults['select_trending_section_background'] = 'secondary-background';

    $defaults['select_frontpage_sidebar_section_background'] = 'default-sidebar-background';
    $defaults['select_main_sidebar_section_background'] = 'default-sidebar-background';


    $defaults['select_slider_news_category'] = 0;
    $defaults['main_banner_section_background_color'] = '#252525';
    $defaults['main_banner_section_secondary_background_color'] = '#212121';
    $defaults['main_banner_section_texts_color'] = '#ffffff';




    //Defaults carousel layout
    $defaults['select_default_carousel_column'] = 'carousel-2';
    $defaults['select_default_carousel_layout'] = 'title-under-image';

    //Defaults grid layout
    $defaults['select_default_grid_column'] = 'grid-layout-1';

    //Defaults slider layout
    $defaults['select_default_slider_mode'] = 'default';
    $defaults['select_default_slider_thumb_mode'] = 'show';

    //Banner Layout Mode
    $defaults['select_banner_layout_mode'] = 'boxed';
    $defaults['enable_gaps_between_thumbs'] = true;

    $defaults['number_of_slides'] = 5;


    $defaults['show_trending_carousel_section'] = 1;
    $defaults['trending_slider_title'] = __("Trending", 'enternews');
    $defaults['select_trending_news_category'] = 0;
    $defaults['number_of_trending_slides'] = 5;
    $defaults['select_trending_carousel_section_mode'] = 'right';
    $defaults['select_trending_post_filterby'] = 'cat';
    $defaults['select_trending_carousel_section_mode_grid'] = 'top';
    $defaults['select_trending_carousel_category'] = 0;
    $defaults['select_trending_post_tag'] = 0;



    //$defaults['show_editors_pick_section'] = 1;
    $defaults['editors_pick_section_title'] = __("Editor's Pick", 'enternews');
    $defaults['select_editors_pick_category'] = 0;
    //$defaults['number_of_editors_pick_news'] = 4;



    $defaults['frontpage_content_alignment'] = 'frontpage-layout-1';
    $defaults['frontpage_sticky_sidebar'] = 1;
    $defaults['frontpage_sticky_sidebar_position'] = 'sidebar-sticky-top';

    $defaults['show_featured_news_section'] = 1;
    $defaults['featured_news_section_title'] = __('Featured News', 'enternews');
    $defaults['select_featured_news_filterby'] = 'cat';
    $defaults['select_featured_news_category'] = 0;
    $defaults['select_featured_news_tag'] = 0;
    $defaults['number_of_featured_news'] = 4;

    //layout options
    $defaults['global_content_layout'] = 'default-content-layout';
    $defaults['global_content_alignment'] = 'align-content-left';
    $defaults['global_image_alignment'] = 'full-width-image';
    $defaults['global_post_date_author_setting'] = 'show-date-author';
    $defaults['global_hide_post_date_author_in_list'] = 1;
    $defaults['global_excerpt_length'] = 20;
    $defaults['global_read_more_texts'] = __('Read more', 'enternews');
    $defaults['global_widget_excerpt_setting'] = 'trimmed-content';
    $defaults['global_date_display_setting'] = 'default-date';

    $defaults['archive_layout'] = 'archive-layout-list';
    $defaults['archive_pagination_view'] = 'archive-default';
    $defaults['archive_image_alignment_grid'] = 'archive-image-default';
    $defaults['archive_image_alignment_list'] = 'archive-image-left';
    $defaults['archive_image_alignment'] = 'archive-image-default';
    $defaults['archive_content_view'] = 'archive-content-excerpt';
    $defaults['disable_main_banner_on_blog_archive'] = 0;

    //Related posts
    $defaults['single_show_featured_image'] = 1;
    $defaults['single_post_featured_image_view']     = 'default';


    //Related posts
    $defaults['single_show_related_posts'] = 1;
    $defaults['single_related_posts_title']     = __( 'More Stories', 'enternews' );
    $defaults['single_number_of_related_posts']  = 3;

    //Pagination.
    $defaults['site_pagination_type'] = 'default';

    //Mailchimp
    $defaults['footer_show_mailchimp_subscriptions'] = 1;
    $defaults['footer_mailchimp_subscriptions_scopes'] = 'front-page';
    $defaults['footer_mailchimp_title']     = __( 'Subscribe To  Our Newsletter', 'enternews' );
    $defaults['footer_mailchimp_shortcode']  = '';
    $defaults['footer_mailchimp_background_color']  = '#404040';
    $defaults['footer_mailchimp_text_color']  = '#ffffff';


    // Footer.
    // Latest posts
    $defaults['frontpage_show_latest_posts'] = 1;
    $defaults['frontpage_latest_posts_section_title'] = __('You may have missed', 'enternews');
    $defaults['frontpage_latest_posts_category'] = 0;
    $defaults['number_of_frontpage_latest_posts'] = 4;

    //Instagram
    $defaults['footer_show_instagram_post_carousel'] = 0;
    $defaults['footer_instagram_post_carousel_scopes'] = 'front-page';
    $defaults['instagram_username'] = 'wpafthemes';
    $defaults['instagram_access_token'] = '7513125878.1677ed0.4859561aaca443e588fb8a37bc5f1e3b';
    $defaults['number_of_instagram_posts'] = 10;
    $defaults['footer_instagram_post_carousel_thumb_size'] = 'small';


    $defaults['footer_copyright_text'] = esc_html__('Copyright &copy; All rights reserved.', 'enternews');
    $defaults['hide_footer_menu_section']  = 0;
    $defaults['hide_footer_site_title_section']  = 0;
    $defaults['hide_footer_copyright_credits']  = 0;
    $defaults['number_of_footer_widget']  = 3;


    $defaults['global_show_home_menu']           = 'yes';
    $defaults['global_home_menu_icon']           = 'fa fa-home';
    $defaults['global_show_comment_count']           = 'yes';
    $defaults['global_hide_comment_count_in_list']   = 1;
    $defaults['global_show_min_read']           = 'yes';
    $defaults['global_hide_min_read_in_list']   = 1;
    $defaults['global_show_min_read_number']   = 250;
    $defaults['aft_language_switcher']           = '';
    $defaults['show_watch_online_section']           = 1;
    $defaults['watch_online_icon']           = 'fa fa-play-circle-o';
    $defaults['aft_custom_title']           = __('Watch Online', 'enternews');
    $defaults['aft_custom_link']           = '';
    $defaults['global_show_categories']           = 'yes';
    $defaults['global_show_home_menu_border']    = 'show-menu-border';
    $defaults['global_site_mode_setting']    = 'aft-default-mode';


    $defaults['category_color_1']    = '#0776C6 ';
    $defaults['category_color_2']    = '#bb1919';
    $defaults['category_color_3']    = '#0486db';


    //font options additional value
    global $enternews_google_fonts;
    $enternews_google_fonts = array(
        'Lato:400,300,400italic,900,700'            => 'Lato',
        'Montserrat:400,700'                        => 'Montserrat',
        'Oswald:300,400,700'                        => 'Oswald',
        'Poppins:300,400,500,600,700'               => 'Poppins',
        'Roboto:100,300,400,500,700'                => 'Roboto'
    );

    //font option

    $defaults['primary_font']      = 'Lato:400,300,400italic,900,700';
    $defaults['secondary_font']    = 'Poppins:300,400,500,600,700';
    $defaults['tertiary_font']    = 'Roboto:100,300,400,500,700';

    //font size
    $defaults['site_title_font_size']    = 52;
    $defaults['primary_color']     = '#4a4a4a';
    $defaults['secondary_color']   = '#0776C6';


    // Pass through filter.
    $defaults = apply_filters('enternews_filter_default_theme_options', $defaults);

	return $defaults;

}

endif;
