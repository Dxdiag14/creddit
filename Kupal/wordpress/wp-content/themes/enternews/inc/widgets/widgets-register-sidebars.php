<?php
/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function enternews_widgets_init()
{
    register_sidebar(array(
        'name' => esc_html__('Main Sidebar', 'enternews'),
        'id' => 'sidebar-1',
        'description' => esc_html__('Add widgets for main sidebar.', 'enternews'),
        'before_widget' => '<div id="%1$s" class="widget enternews-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widget-title widget-title-1"><span class="header-after">',
        'after_title' => '</span></h2>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Header Ad Section 1', 'enternews'),
        'id'            => 'home-advertisement-widgets-1',
        'description'   => esc_html__('Add widgets for header ad section 1', 'enternews'),
        'before_widget' => '<div id="%1$s" class="widget enternews-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widget-title widget-title-1"><span class="header-after">',
        'after_title' => '</span></h2>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Header Ad Section 2', 'enternews'),
        'id'            => 'home-advertisement-widgets-2',
        'description'   => esc_html__('Add widgets for header ad section 2.', 'enternews'),
        'before_widget' => '<div id="%1$s" class="widget enternews-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widget-title widget-title-1"><span class="header-after">',
        'after_title' => '</span></h2>',
    ));


    register_sidebar(array(
        'name' => esc_html__('Front-page Content', 'enternews'),
        'id' => 'home-content-widgets',
        'description' => esc_html__('Add widgets to front-page contents section.', 'enternews'),
        'before_widget' => '<div id="%1$s" class="widget enternews-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widget-title"><span class="header-after">',
        'after_title' => '</span></h2>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Front-page Sidebar', 'enternews'),
        'id' => 'home-sidebar-1-widgets',
        'description' => esc_html__('Add widgets to front-page first sidebar section.', 'enternews'),
        'before_widget' => '<div id="%1$s" class="widget enternews-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widget-title widget-title-1"><span class="header-after">',
        'after_title' => '</span></h2>',
    ));



    register_sidebar(array(
        'name' => esc_html__('Footer First Section', 'enternews'),
        'id' => 'footer-first-widgets-section',
        'description' => esc_html__('Displays items on footer first column.', 'enternews'),
        'before_widget' => '<div id="%1$s" class="widget enternews-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widget-title widget-title-1"><span class="header-after">',
        'after_title' => '</span></h2>',
    ));


    register_sidebar(array(
        'name' => esc_html__('Footer Second Section', 'enternews'),
        'id' => 'footer-second-widgets-section',
        'description' => esc_html__('Displays items on footer second column.', 'enternews'),
        'before_widget' => '<div id="%1$s" class="widget enternews-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widget-title widget-title-1"><span class="header-after">',
        'after_title' => '</span></h2>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Footer Third Section', 'enternews'),
        'id' => 'footer-third-widgets-section',
        'description' => esc_html__('Displays items on footer third column.', 'enternews'),
        'before_widget' => '<div id="%1$s" class="widget enternews-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widget-title widget-title-1"><span class="header-after">',
        'after_title' => '</span></h2>',
    ));





}

add_action('widgets_init', 'enternews_widgets_init');