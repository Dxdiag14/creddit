<?php

/**
 * Option Panel
 *
 * @package EnterNews
 */

$default = enternews_get_default_theme_options();
/*theme option panel info*/
require get_template_directory() . '/inc/customizer/frontpage-options.php';


// Setting - secondary_color.
$wp_customize->add_setting('secondary_color',
    array(
        'default' => $default['secondary_color'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    )
);

$wp_customize->add_control(

    new WP_Customize_Color_Control(
        $wp_customize,
        'secondary_color',
        array(
            'label' => esc_html__('Secondary Color', 'enternews'),
            'section' => 'colors',
            'type' => 'color',
            'priority' => 5
        )
    )
);

//============= Font Options ===================
// font Section.
$wp_customize->add_section('font_typo_section',
    array(
        'title' => esc_html__('Fonts & Typography', 'enternews'),
        'priority' => 10,
        'capability' => 'edit_theme_options',
        'panel' => 'theme_option_panel',
    )
);



global $enternews_google_fonts;


// Setting - primary_font.
$wp_customize->add_setting('primary_font',
    array(
        'default' => $default['primary_font'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'enternews_sanitize_select',
    )
);
$wp_customize->add_control('primary_font',
    array(
        'label' => esc_html__('Primary Font', 'enternews'),
        'section' => 'font_typo_section',
        'type' => 'select',
        'choices' => $enternews_google_fonts,
        'priority' => 100,
    )
);

// Setting - secondary_font.
$wp_customize->add_setting('secondary_font',
    array(
        'default' => $default['secondary_font'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'enternews_sanitize_select',
    )
);
$wp_customize->add_control('secondary_font',
    array(
        'label' => esc_html__('Secondary Font', 'enternews'),
        'section' => 'font_typo_section',
        'type' => 'select',
        'choices' => $enternews_google_fonts,
        'priority' => 110,
    )
);

// Setting - secondary_font.
$wp_customize->add_setting('tertiary_font',
    array(
        'default' => $default['tertiary_font'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'enternews_sanitize_select',
    )
);
$wp_customize->add_control('tertiary_font',
    array(
        'label' => esc_html__('Tertiary Font', 'enternews'),
        'section' => 'font_typo_section',
        'type' => 'select',
        'choices' => $enternews_google_fonts,
        'priority' => 110,
    )
);


// Add Theme Options Panel.
$wp_customize->add_panel('theme_option_panel',
    array(
        'title' => esc_html__('Theme Options', 'enternews'),
        'priority' => 200,
        'capability' => 'edit_theme_options',
    )
);


// Preloader Section.
$wp_customize->add_section('site_preloader_settings',
    array(
        'title' => esc_html__('Preloader Options', 'enternews'),
        'priority' => 4,
        'capability' => 'edit_theme_options',
        'panel' => 'theme_option_panel',
    )
);

// Setting - preloader.
$wp_customize->add_setting('enable_site_preloader',
    array(
        'default' => $default['enable_site_preloader'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'enternews_sanitize_checkbox',
    )
);

$wp_customize->add_control('enable_site_preloader',
    array(
        'label' => esc_html__('Enable preloader', 'enternews'),
        'section' => 'site_preloader_settings',
        'type' => 'checkbox',
        'priority' => 10,
    )
);


// Breadcrumb Section.
$wp_customize->add_section('site_breadcrumb_settings',
    array(
        'title'      => esc_html__('Breadcrumb Options', 'enternews'),
        'priority'   => 50,
        'capability' => 'edit_theme_options',
        'panel'      => 'theme_option_panel',
    )
);

// Setting - breadcrumb.
$wp_customize->add_setting('enable_breadcrumb',
    array(
        'default'           => $default['enable_breadcrumb'],
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'enternews_sanitize_checkbox',
    )
);

$wp_customize->add_control('enable_breadcrumb',
    array(
        'label'    => esc_html__('Show breadcrumbs', 'enternews'),
        'section'  => 'site_breadcrumb_settings',
        'type'     => 'checkbox',
        'priority' => 10,
    )
);


// Setting - global content alignment of news.
$wp_customize->add_setting('select_breadcrumb_mode',
    array(
        'default'           => $default['select_breadcrumb_mode'],
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'enternews_sanitize_select',
    )
);

$wp_customize->add_control( 'select_breadcrumb_mode',
    array(
        'label'       => esc_html__('Select Breadcrumbs', 'enternews'),
        'description' => esc_html__("Please ensure that you have enabled the plugin's breadcrumbs before choosing other than Default", 'enternews'),
        'section'     => 'site_breadcrumb_settings',
        'type'        => 'select',
        'choices'               => array(
            'default' => esc_html__( 'Default', 'enternews' ),
            'yoast' => esc_html__( 'Yoast SEO', 'enternews' ),
            'rankmath' => esc_html__( 'Rank Math', 'enternews' ),
            'bcn' => esc_html__( 'NavXT', 'enternews' ),
        ),
        'priority'    => 100,
    ));
    
    /**
     * Layout options section
     *
     * @package EnterNews
     */

// Layout Section.
    $wp_customize->add_section('site_layout_settings',
        array(
            'title' => esc_html__('Global Settings', 'enternews'),
            'priority' => 9,
            'capability' => 'edit_theme_options',
            'panel' => 'theme_option_panel',
        )
    );


// Setting - global content alignment of news.
    $wp_customize->add_setting('global_content_alignment',
        array(
            'default' => $default['global_content_alignment'],
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'enternews_sanitize_select',
        )
    );
    
    $wp_customize->add_control('global_content_alignment',
        array(
            'label' => esc_html__('Global Content Alignment', 'enternews'),
            'section' => 'site_layout_settings',
            'type' => 'select',
            'choices' => array(
                'align-content-left' => esc_html__('Content - Primary sidebar', 'enternews'),
                'align-content-right' => esc_html__('Primary sidebar - Content', 'enternews'),
                'full-width-content' => esc_html__('Full width content', 'enternews')
            ),
            'priority' => 130,
        ));

// Setting - global content alignment of news.
    $wp_customize->add_setting('global_show_categories',
        array(
            'default' => $default['global_show_categories'],
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'enternews_sanitize_select',
        )
    );
    
    $wp_customize->add_control('global_show_categories',
        array(
            'label' => esc_html__('Post Categories', 'enternews'),
            'section' => 'site_layout_settings',
            'type' => 'select',
            'choices' => array(
                'yes' => esc_html__('Show', 'enternews'),
                'no' => esc_html__('Hide', 'enternews'),
            
            ),
            'priority' => 130,
        ));


// Setting - global content alignment of news.
    $wp_customize->add_setting('global_widget_excerpt_setting',
        array(
            'default' => $default['global_widget_excerpt_setting'],
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'enternews_sanitize_select',
        )
    );
    
    $wp_customize->add_control('global_widget_excerpt_setting',
        array(
            'label' => esc_html__('Widget Excerpt Mode', 'enternews'),
            'section' => 'site_layout_settings',
            'type' => 'select',
            'choices' => array(
                'trimmed-content' => esc_html__('Trimmed Content', 'enternews'),
                'default-excerpt' => esc_html__('Default Excerpt', 'enternews'),
            
            ),
            'priority' => 130,
        ));


    /**
     * Header section
     *
     * @package EnterNews
     */

// Frontpage Section.
    $wp_customize->add_section('header_options_settings',
        array(
            'title' => esc_html__('Header Options', 'enternews'),
            'priority' => 49,
            'capability' => 'edit_theme_options',
            'panel' => 'theme_option_panel',
        )
    );


// Setting - show_site_title_section.
    $wp_customize->add_setting('show_date_section',
        array(
            'default' => $default['show_date_section'],
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'enternews_sanitize_checkbox',
        )
    );
    $wp_customize->add_control('show_date_section',
        array(
            'label' => esc_html__('Show date on top header', 'enternews'),
            'section' => 'header_options_settings',
            'type' => 'checkbox',
            'priority' => 10
        )
    );


// Setting - sticky_header_option.
    $wp_customize->add_setting('enable_sticky_header_option',
        array(
            'default' => $default['enable_sticky_header_option'],
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'enternews_sanitize_checkbox',
        )
    );
    $wp_customize->add_control('enable_sticky_header_option',
        array(
            'label' => esc_html__('Enable Sticky Header', 'enternews'),
            'section' => 'header_options_settings',
            'type' => 'checkbox',
            'priority' => 11
        )
    );

// Setting - global content alignment of news.
$wp_customize->add_setting('global_show_home_menu',
    array(
        'default' => $default['global_show_home_menu'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'enternews_sanitize_select',
    )
);

$wp_customize->add_control('global_show_home_menu',
    array(
        'label' => esc_html__('Show Home Menu Icon', 'enternews'),
        'section' => 'header_options_settings',
        'type' => 'select',
        'choices' => array(
            'yes' => esc_html__('Show', 'enternews'),
            'no' => esc_html__('Hide', 'enternews'),

        ),
        'priority' => 11,
    ));

//=================================
//Watch Online Section.
//=================================


//section title
$wp_customize->add_setting('custom_link_section_title',
    array(
        'sanitize_callback' => 'sanitize_text_field',
    )
);

$wp_customize->add_control(
    new EnterNews_Section_Title(
        $wp_customize,
        'custom_link_section_title',
        array(
            'label' => esc_html__('Custom Link Section ', 'enternews'),
            'section' => 'header_options_settings',
            'priority' => 100,

        )
    )
);


$wp_customize->add_setting('show_watch_online_section',
    array(
        'default' => $default['show_watch_online_section'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'enternews_sanitize_checkbox',
    )
);

$wp_customize->add_control('show_watch_online_section',
    array(
        'label' => esc_html__('Enable Watch Online Section', 'enternews'),
        'section' => 'header_options_settings',
        'type' => 'checkbox',
        'priority' => 100,

    )
);




// Setting - sticky_header_option.
$wp_customize->add_setting('aft_custom_title',
    array(
        'default' => $default['aft_custom_title'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control('aft_custom_title',
    array(
        'label' => esc_html__('Title', 'enternews'),
        'section' => 'header_options_settings',
        'type' => 'text',
        'priority' => 130,
        'active_callback' => 'enternews_show_watch_online_section_status'
    )
);

// Setting - sticky_header_option.
$wp_customize->add_setting('aft_custom_link',
    array(
        'default' => $default['aft_custom_link'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control('aft_custom_link',
    array(
        'label' => esc_html__('Button Link', 'enternews'),
        'section' => 'header_options_settings',
        'type' => 'text',
        'priority' => 130,
        'active_callback' => 'enternews_show_watch_online_section_status'
    )
);

/**
 * Sidebar options section
 *
 * @package EnterNews
 */

// Sidebar Section.
$wp_customize->add_section('site_sidebar_settings',
    array(
        'title'      => esc_html__('Sidebar Settings', 'enternews'),
        'priority'   => 50,
        'capability' => 'edit_theme_options',
        'panel'      => 'theme_option_panel',
    )
);

// Setting - frontpage_sticky_sidebar.
$wp_customize->add_setting('frontpage_sticky_sidebar',
    array(
        'default'           => $default['frontpage_sticky_sidebar'],
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'enternews_sanitize_checkbox',
    )
);

$wp_customize->add_control('frontpage_sticky_sidebar',
    array(
        'label'    => esc_html__('Make Sidebar Sticky', 'enternews'),
        'section'  => 'site_sidebar_settings',
        'type'     => 'checkbox',
        'priority' => 130,
        //'active_callback' => 'frontpage_content_alignment_status'
    )
);

// Setting - global content alignment of news.
$wp_customize->add_setting('frontpage_sticky_sidebar_position',
    array(
        'default'           => $default['frontpage_sticky_sidebar_position'],
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'enternews_sanitize_select',
    )
);

$wp_customize->add_control( 'frontpage_sticky_sidebar_position',
    array(
        'label'       => esc_html__('Sidebar Sticky Position', 'enternews'),
        'section'     => 'site_sidebar_settings',
        'type'        => 'select',
        'choices'               => array(
            'sidebar-sticky-top' => esc_html__( 'Top', 'enternews' ),
            'sidebar-sticky-bottom' => esc_html__( 'Bottom', 'enternews' ),
        ),
        'priority'    => 130,
        //'active_callback' => 'frontpage_sticky_sidebar_status'
    ));






//========== comment count options ===============

// Global Section.
$wp_customize->add_section('site_comment_count_settings',
    array(
        'title' => esc_html__('Comment Count', 'enternews'),
        'priority' => 50,
        'capability' => 'edit_theme_options',
        'panel' => 'theme_option_panel',
    )
);

// Setting - global content alignment of news.
$wp_customize->add_setting('global_show_comment_count',
    array(
        'default' => $default['global_show_comment_count'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'enternews_sanitize_select',
    )
);

$wp_customize->add_control('global_show_comment_count',
    array(
        'label' => esc_html__('Comment Count', 'enternews'),
        'section' => 'site_comment_count_settings',
        'type' => 'select',
        'choices' => array(
            'yes' => esc_html__('Show', 'enternews'),
            'no' => esc_html__('Hide', 'enternews'),

        ),
        'priority' => 130,
    ));




//========== minutes read count options ===============

// Global Section.
$wp_customize->add_section('site_min_read_settings',
    array(
        'title' => esc_html__('Minutes Read Count', 'enternews'),
        'priority' => 50,
        'capability' => 'edit_theme_options',
        'panel' => 'theme_option_panel',
    )
);


// Setting - global content alignment of news.
$wp_customize->add_setting('global_show_min_read',
    array(
        'default' => $default['global_show_min_read'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'enternews_sanitize_select',
    )
);

$wp_customize->add_control('global_show_min_read',
    array(
        'label' => esc_html__('Minutes Read Count', 'enternews'),
        'section' => 'site_min_read_settings',
        'type' => 'select',
        'choices' => array(
            'yes' => esc_html__('Show', 'enternews'),
            'no' => esc_html__('Hide', 'enternews'),

        ),
        'priority' => 130,
    ));



//========== date and author options ===============

// Global Section.
$wp_customize->add_section('site_post_date_author_settings',
    array(
        'title' => esc_html__('Date and Author', 'enternews'),
        'priority' => 50,
        'capability' => 'edit_theme_options',
        'panel' => 'theme_option_panel',
    )
);

// Setting - global content alignment of news.
$wp_customize->add_setting('global_post_date_author_setting',
    array(
        'default' => $default['global_post_date_author_setting'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'enternews_sanitize_select',
    )
);


$wp_customize->add_control('global_post_date_author_setting',
    array(
        'label' => esc_html__('Date and Author', 'enternews'),
        'section' => 'site_post_date_author_settings',
        'type' => 'select',
        'choices' => array(
            'show-date-author' => esc_html__('Show Date and Author', 'enternews'),
            'hide-date-author' => esc_html__('Hide All', 'enternews'),
        ),
        'priority' => 130,
    ));

//========== single posts options ===============

// Single Section.
$wp_customize->add_section('site_single_posts_settings',
    array(
        'title' => esc_html__('Single Post', 'enternews'),
        'priority' => 50,
        'capability' => 'edit_theme_options',
        'panel' => 'theme_option_panel',
    )
);

// Setting - related posts.
$wp_customize->add_setting('single_show_featured_image',
    array(
        'default' => $default['single_show_featured_image'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'enternews_sanitize_checkbox',
    )
);

$wp_customize->add_control('single_show_featured_image',
    array(
        'label' => __('Show Featured Image', 'enternews'),
        'section' => 'site_single_posts_settings',
        'type' => 'checkbox',
        'priority' => 100,
    )
);

//========== related posts  options ===============

// Single Section.
$wp_customize->add_section('site_single_related_posts_settings',
    array(
        'title' => esc_html__('Related Posts', 'enternews'),
        'priority' => 50,
        'capability' => 'edit_theme_options',
        'panel' => 'theme_option_panel',
    )
);

// Setting - related posts.
$wp_customize->add_setting('single_show_related_posts',
    array(
        'default' => $default['single_show_related_posts'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'enternews_sanitize_checkbox',
    )
);

$wp_customize->add_control('single_show_related_posts',
    array(
        'label' => __('Show on single posts', 'enternews'),
        'section' => 'site_single_related_posts_settings',
        'type' => 'checkbox',
        'priority' => 100,
    )
);

// Setting - related posts.
$wp_customize->add_setting('single_related_posts_title',
    array(
        'default' => $default['single_related_posts_title'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    )
);

$wp_customize->add_control('single_related_posts_title',
    array(
        'label' => __('Title', 'enternews'),
        'section' => 'site_single_related_posts_settings',
        'type' => 'text',
        'priority' => 100,
        'active_callback' => 'enternews_related_posts_status'
    )
);


/**
 * Archive options section
 *
 * @package EnterNews
 */

// Archive Section.
$wp_customize->add_section('site_archive_settings',
    array(
        'title' => esc_html__('Archive Settings', 'enternews'),
        'priority' => 50,
        'capability' => 'edit_theme_options',
        'panel' => 'theme_option_panel',
    )
);

//Setting - archive content view of news.
$wp_customize->add_setting('archive_layout',
    array(
        'default' => $default['archive_layout'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'enternews_sanitize_select',
    )
);

$wp_customize->add_control('archive_layout',
    array(
        'label' => esc_html__('Archive layout', 'enternews'),
        'description' => esc_html__('Select layout for archive', 'enternews'),
        'section' => 'site_archive_settings',
        'type' => 'select',
        'choices' => array(
            'archive-layout-list' => esc_html__('List', 'enternews'),
            'archive-layout-grid' => esc_html__('Grid', 'enternews'),
        ),
        'priority' => 130,
    ));

// Setting - archive content view of news.
$wp_customize->add_setting('archive_image_alignment_list',
    array(
        'default' => $default['archive_image_alignment_list'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'enternews_sanitize_select',
    )
);

$wp_customize->add_control('archive_image_alignment_list',
    array(
        'label' => esc_html__('Image alignment', 'enternews'),
        'description' => esc_html__('Select image alignment for archive', 'enternews'),
        'section' => 'site_archive_settings',
        'type' => 'select',
        'choices' => array(
            'archive-image-left' => esc_html__('Left', 'enternews'),
            'archive-image-right' => esc_html__('Right', 'enternews'),

        ),
        'priority' => 130,
        'active_callback' => 'enternews_archive_image_status'
    ));


// Setting - archive content view of news.
$wp_customize->add_setting('archive_image_alignment_grid',
    array(
        'default' => $default['archive_image_alignment_grid'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'enternews_sanitize_select',
    )
);

$wp_customize->add_control('archive_image_alignment_grid',
    array(
        'label' => esc_html__('Image alignment', 'enternews'),
        'description' => esc_html__('Select image alignment for archive', 'enternews'),
        'section' => 'site_archive_settings',
        'type' => 'select',
        'choices' => array(
            'archive-image-default' => esc_html__('Default', 'enternews'),
            'archive-image-up-alternate' => esc_html__('Alternate', 'enternews'),

        ),
        'priority' => 130,
        'active_callback' => 'enternews_archive_image_gird_status'
    ));


//========== footer latest blog carousel options ===============

// Footer Section.
$wp_customize->add_section('frontpage_latest_posts_settings',
    array(
        'title' => esc_html__('You May Have Missed', 'enternews'),
        'priority' => 50,
        'capability' => 'edit_theme_options',
        'panel' => 'theme_option_panel',
    )
);
// Setting - latest blog carousel.
$wp_customize->add_setting('frontpage_show_latest_posts',
    array(
        'default' => $default['frontpage_show_latest_posts'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'enternews_sanitize_checkbox',
    )
);

$wp_customize->add_control('frontpage_show_latest_posts',
    array(
        'label' => __('Show Latest Posts Section above Footer', 'enternews'),
        'section' => 'frontpage_latest_posts_settings',
        'type' => 'checkbox',
        'priority' => 100,
    )
);


// Setting - featured_news_section_title.
$wp_customize->add_setting('frontpage_latest_posts_section_title',
    array(
        'default' => $default['frontpage_latest_posts_section_title'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control('frontpage_latest_posts_section_title',
    array(
        'label' => esc_html__('Posts Section Title', 'enternews'),
        'section' => 'frontpage_latest_posts_settings',
        'type' => 'text',
        'priority' => 100,
        'active_callback' => 'enternews_latest_news_section_status'

    )
);



//========== footer section options ===============
// Footer Section.
$wp_customize->add_section('site_footer_settings',
    array(
        'title' => esc_html__('Footer', 'enternews'),
        'priority' => 50,
        'capability' => 'edit_theme_options',
        'panel' => 'theme_option_panel',
    )
);

// Setting - global content alignment of news.
$wp_customize->add_setting('footer_copyright_text',
    array(
        'default' => $default['footer_copyright_text'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    )
);

$wp_customize->add_control('footer_copyright_text',
    array(
        'label' => __('Copyright Text', 'enternews'),
        'section' => 'site_footer_settings',
        'type' => 'text',
        'priority' => 100,
    )
);