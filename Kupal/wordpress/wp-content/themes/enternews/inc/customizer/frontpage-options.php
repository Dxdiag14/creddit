<?php

/**
 * Option Panel
 *
 * @package EnterNews
 */

$default = enternews_get_default_theme_options();

/**
 * Frontpage options section
 *
 * @package EnterNews
 */


// Add Frontpage Options Panel.
$wp_customize->add_panel('frontpage_option_panel',
    array(
        'title' => esc_html__('Frontpage Options', 'enternews'),
        'priority' => 199,
        'capability' => 'edit_theme_options',
    )
);

//=================================
// Trending Posts Section.
//=================================
$wp_customize->add_section('enternews_flash_posts_section_settings',
    array(
        'title' => esc_html__('Ticker News', 'enternews'),
        'priority' => 50,
        'capability' => 'edit_theme_options',
        'panel' => 'frontpage_option_panel',
    )
);

$wp_customize->add_setting('show_flash_news_section',
    array(
        'default' => $default['show_flash_news_section'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'enternews_sanitize_checkbox',
    )
);

$wp_customize->add_control('show_flash_news_section',
    array(
        'label' => esc_html__('Enable Ticker News', 'enternews'),
        'section' => 'enternews_flash_posts_section_settings',
        'type' => 'checkbox',
        'priority' => 22,

    )
);

// Setting - number_of_slides.
$wp_customize->add_setting('flash_news_title',
    array(
        'default' => $default['flash_news_title'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    )
);

$wp_customize->add_control('flash_news_title',
    array(
        'label' => esc_html__('Exclusive News Title', 'enternews'),
        'section' => 'enternews_flash_posts_section_settings',
        'type' => 'text',
        'priority' => 23,
        'active_callback' => 'enternews_flash_posts_section_status'

    )
);


// Setting - drop down category for slider.
$wp_customize->add_setting('select_flash_news_category',
    array(
        'default' => $default['select_flash_news_category'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    )
);


$wp_customize->add_control(new EnterNews_Dropdown_Taxonomies_Control($wp_customize, 'select_flash_news_category',
    array(
        'label' => esc_html__('Exclusive Posts Category', 'enternews'),
        'description' => esc_html__('Posts to be shown on trending posts ', 'enternews'),
        'section' => 'enternews_flash_posts_section_settings',
        'type' => 'dropdown-taxonomies',
        'taxonomy' => 'category',
        'priority' => 23,
        'active_callback' => 'enternews_flash_posts_section_status'
    )));




/**
 * Main Banner Slider Section
 * */

// Main banner Sider Section.
$wp_customize->add_section('frontpage_main_banner_section_settings',
    array(
        'title' => esc_html__('Main Banner', 'enternews'),
        'priority' => 50,
        'capability' => 'edit_theme_options',
        'panel' => 'frontpage_option_panel',
    )
);

// Setting - show_main_news_section.
$wp_customize->add_setting('show_main_news_section',
    array(
        'default' => $default['show_main_news_section'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'enternews_sanitize_checkbox',
    )
);

$wp_customize->add_control('show_main_news_section',
    array(
        'label' => esc_html__('Enable Main Banner', 'enternews'),
        'section' => 'frontpage_main_banner_section_settings',
        'type' => 'checkbox',
        'priority' => 100,

    )
);

// Setting - select_main_banner_section_mode.
$wp_customize->add_setting('select_main_banner_section_layout',
    array(
        'default' => $default['select_main_banner_section_layout'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'enternews_sanitize_select',
    )
);

$wp_customize->add_control('select_main_banner_section_layout',
    array(
        'label' => esc_html__('Select Banner Layout', 'enternews'),
        'section' => 'frontpage_main_banner_section_settings',
        'type' => 'select',
        'choices' => array(
            'wide' => esc_html__("Wide", 'enternews'),
            'boxed' => esc_html__("Boxed", 'enternews'),
        ),
        'priority' => 100,
        'active_callback' => 'enternews_main_banner_section_status'
    ));




// Setting banner_advertisement_section.
$wp_customize->add_setting('main_banner_background_section',
    array(
        'default' => $default['main_banner_background_section'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    )
);


$wp_customize->add_control(
    new WP_Customize_Cropped_Image_Control($wp_customize, 'main_banner_background_section',
        array(
            'label' => esc_html__('Main Banner Background Image', 'enternews'),
            'description' => sprintf(esc_html__('Recommended Size %1$s px X %2$s px', 'enternews'), 1024, 800),
            'section' => 'frontpage_main_banner_section_settings',
            'width' => 1024,
            'height' => 800,
            'flex_width' => true,
            'flex_height' => true,
            'priority' => 100,
            'active_callback' => 'enternews_main_banner_section_status'
        )
    )
);

// Setting - select_main_banner_section_mode.
$wp_customize->add_setting('select_main_banner_section_mode',
    array(
        'default' => $default['select_main_banner_section_mode'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'enternews_sanitize_select',
    )
);

$wp_customize->add_control('select_main_banner_section_mode',
    array(
        'label' => esc_html__('Select Banner Style', 'enternews'),
        'section' => 'frontpage_main_banner_section_settings',
        'type' => 'select',
        'choices' => array(
            'default' => esc_html__("Editor's Pick - Main News - Trending", 'enternews'),
            'layout-2' => esc_html__("Trending - Main News - Editor's Pick", 'enternews'),
            'layout-3' => esc_html__("Main News - Editor's Pick - Trending", 'enternews'),
        ),
        'priority' => 100,
        'active_callback' => 'enternews_main_banner_section_status'
    ));





//Editorials Starts
$wp_customize->add_setting('editorials_section_title',
    array(
        'sanitize_callback' => 'sanitize_text_field',
    )
);

$wp_customize->add_control(
    new EnterNews_Section_Title(
        $wp_customize,
        'editorials_section_title',
        array(
            'label' => esc_html__('Editorials Section ', 'enternews'),
            'section' => 'frontpage_main_banner_section_settings',
            'priority' => 100,
            'active_callback' => function ($control) {
                return (
                    enternews_main_banner_section_status($control)
                    &&
                    enternews_main_banner_editorials_layout_status($control)
                );
            }

        )
    )
);

// Setting - sticky_header_option.
$wp_customize->add_setting('editors_pick_section_title',
    array(
        'default' => $default['editors_pick_section_title'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control('editors_pick_section_title',
    array(
        'label' => esc_html__('Section Title', 'enternews'),
        'section' => 'frontpage_main_banner_section_settings',
        'type' => 'text',
        'priority' => 100,
        'active_callback' => function ($control) {
            return (
                enternews_main_banner_section_status($control)
                &&
                enternews_main_banner_editorials_layout_status($control)
            );
        }
    )
);

// Setting - drop down category for slider.
$wp_customize->add_setting('select_editors_pick_category',
    array(
        'default' => $default['select_editors_pick_category'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    )
);

$wp_customize->add_control(new EnterNews_Dropdown_Taxonomies_Control($wp_customize, 'select_editors_pick_category',
    array(
        'label' => esc_html__('Category', 'enternews'),
        'description' => esc_html__('Posts to be shown on Editorials slider section', 'enternews'),
        'section' => 'frontpage_main_banner_section_settings',
        'type' => 'dropdown-taxonomies',
        'taxonomy' => 'category',
        'priority' => 100,
        'active_callback' => function ($control) {
            return (
                enternews_main_banner_section_status($control)
                &&
                enternews_main_banner_editorials_layout_status($control)
            );
        }
    )));


//section title
$wp_customize->add_setting('main_banner_section_title',
    array(
        'sanitize_callback' => 'sanitize_text_field',
    )
);

$wp_customize->add_control(
    new EnterNews_Section_Title(
        $wp_customize,
        'main_banner_section_title',
        array(
            'label' => esc_html__('Main Slider Section ', 'enternews'),
            'section' => 'frontpage_main_banner_section_settings',
            'priority' => 100,
            'active_callback' => function ($control) {
                return (
                enternews_main_banner_section_status($control)

                );
            }

        )
    )
);


// Setting - sticky_header_option.
$wp_customize->add_setting('main_banner_section_label',
    array(
        'default' => $default['main_banner_section_label'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control('main_banner_section_label',
    array(
        'label' => esc_html__('Section Title', 'enternews'),
        'section' => 'frontpage_main_banner_section_settings',
        'type' => 'text',
        'priority' => 100,
        'active_callback' => function ($control) {
            return (
            enternews_main_banner_section_status($control)

            );
        }
    )
);




// Setting - drop down category for slider.
$wp_customize->add_setting('select_slider_news_category',
    array(
        'default' => $default['select_slider_news_category'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    )
);


$wp_customize->add_control(new EnterNews_Dropdown_Taxonomies_Control($wp_customize, 'select_slider_news_category',
    array(
        'label' => esc_html__('Category', 'enternews'),
        'description' => esc_html__('Posts to be shown on Main News Slider', 'enternews'),
        'section' => 'frontpage_main_banner_section_settings',
        'type' => 'dropdown-taxonomies',
        'taxonomy' => 'category',
        'priority' => 100,
        'active_callback' => function ($control) {
            return (
            enternews_main_banner_section_status($control)

            );
        }
    )));





//Trending Slider Starts
$wp_customize->add_setting('trending_carousel_section_title',
    array(
        'sanitize_callback' => 'sanitize_text_field',
    )
);

$wp_customize->add_control(
    new EnterNews_Section_Title(
        $wp_customize,
        'trending_carousel_section_title',
        array(
            'label' => esc_html__('Trending Section ', 'enternews'),
            'section' => 'frontpage_main_banner_section_settings',
            'priority' => 100,
            'active_callback' => function ($control) {
                return (
                    enternews_main_banner_section_status($control)
                    &&
                    enternews_main_banner_trending_layout_status($control)
                );
            }

        )
    )
);

// Setting - sticky_header_option.
$wp_customize->add_setting('trending_slider_title',
    array(
        'default' => $default['trending_slider_title'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control('trending_slider_title',
    array(
        'label' => esc_html__('Section Title', 'enternews'),
        'section' => 'frontpage_main_banner_section_settings',
        'type' => 'text',
        'priority' => 100,
        'active_callback' => function ($control) {
            return (
                enternews_main_banner_section_status($control)
                &&
                enternews_main_banner_trending_layout_status($control)
            );
        }
    )
);






// Setting - drop down category for slider.
$wp_customize->add_setting('select_trending_carousel_category',
    array(
        'default' => $default['select_trending_carousel_category'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    )
);

$wp_customize->add_control(new EnterNews_Dropdown_Taxonomies_Control($wp_customize, 'select_trending_carousel_category',
    array(
        'label' => esc_html__('Category', 'enternews'),
        'description' => esc_html__('Posts to be shown on Trending slider section', 'enternews'),
        'section' => 'frontpage_main_banner_section_settings',
        'type' => 'dropdown-taxonomies',
        'taxonomy' => 'category',
        'priority' => 100,
        'active_callback' => function ($control) {
            return (
                enternews_main_banner_section_status($control)
                &&
                enternews_main_banner_trending_layout_status($control)


            );
        }
    )));





// Disable main banner in blog
$wp_customize->add_setting('disable_main_banner_on_blog_archive',
    array(
        'default' => $default['disable_main_banner_on_blog_archive'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'enternews_sanitize_checkbox',
    )
);

$wp_customize->add_control('disable_main_banner_on_blog_archive',
    array(
        'label' => esc_html__('Disable Main Banner section on Static Posts page', 'enternews'),
        'section' => 'frontpage_main_banner_section_settings',
        'type' => 'checkbox',
        'priority' => 111,
        'active_callback' => 'enternews_main_banner_section_status'
    )
);


/**
 * Main Banner Slider Section
 * */

// Main banner Sider Section.
$wp_customize->add_section('frontpage_featured_section_settings',
    array(
        'title' => esc_html__('Featured News', 'enternews'),
        'priority' => 50,
        'capability' => 'edit_theme_options',
        'panel' => 'frontpage_option_panel',
    )
);

// Setting - show_main_news_section.
$wp_customize->add_setting('show_featured_news_section',
    array(
        'default' => $default['show_featured_news_section'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'enternews_sanitize_checkbox',
    )
);

$wp_customize->add_control('show_featured_news_section',
    array(
        'label' => esc_html__('Enable Featured News', 'enternews'),
        'section' => 'frontpage_featured_section_settings',
        'type' => 'checkbox',
        'priority' => 100,

    )
);

// Setting - sticky_header_option.
$wp_customize->add_setting('featured_news_section_title',
    array(
        'default' => $default['featured_news_section_title'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control('featured_news_section_title',
    array(
        'label' => esc_html__('Section Title', 'enternews'),
        'section' => 'frontpage_featured_section_settings',
        'type' => 'text',
        'priority' => 100,
        'active_callback' => function ($control) {
            return (
            enternews_featured_news_section_status($control)
            );
        }

    )
);

// Setting - drop down category for slider.
$wp_customize->add_setting('select_featured_news_category',
    array(
        'default' => $default['select_featured_news_category'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    )
);

$wp_customize->add_control(new EnterNews_Dropdown_Taxonomies_Control($wp_customize, 'select_featured_news_category',
    array(
        'label' => esc_html__('Category', 'enternews'),
        'description' => esc_html__('Posts to be shown on featured news section', 'enternews'),
        'section' => 'frontpage_featured_section_settings',
        'type' => 'dropdown-taxonomies',
        'taxonomy' => 'category',
        'priority' => 100,
        'active_callback' => function ($control) {
            return (
                enternews_featured_news_section_status($control)


            );
        }
    )));




// Frontpage Layout Section.
$wp_customize->add_section('frontpage_layout_settings',
    array(
        'title' => esc_html__('Frontpage Layout Settings', 'enternews'),
        'priority' => 10,
        'capability' => 'edit_theme_options',
        'panel' => 'frontpage_option_panel',
    )
);


// Setting - show_main_news_section.
$wp_customize->add_setting('frontpage_content_alignment',
    array(
        'default' => $default['frontpage_content_alignment'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'enternews_sanitize_select',
    )
);


$wp_customize->add_control('frontpage_content_alignment',
    array(
        'label' => esc_html__('Frontpage Content Position', 'enternews'),
        'description' => esc_html__('Select frontpage content alignment', 'enternews'),
        'section' => 'frontpage_layout_settings',
        'type' => 'select',
        'choices' => array(
            'frontpage-layout-1' => esc_html__('Content - Sidebar', 'enternews'),
            'frontpage-layout-2' => esc_html__('Sidebar - Content', 'enternews'),
            'frontpage-layout-3' => esc_html__('Full Content', 'enternews')
        ),
        'priority' => 10,
    ));

// Setting - frontpage_sticky_sidebar.
$wp_customize->add_setting('frontpage_sticky_sidebar',
    array(
        'default' => $default['frontpage_sticky_sidebar'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'enternews_sanitize_checkbox',
    )
);

$wp_customize->add_control('frontpage_sticky_sidebar',
    array(
        'label' => esc_html__('Make Frontpage Sidebar Sticky', 'enternews'),
        'section' => 'frontpage_layout_settings',
        'type' => 'checkbox',
        'priority' => 10,
        //'active_callback' => 'enternews_frontpage_content_alignment_status'
    )
);
