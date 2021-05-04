<?php
/**
 * EnterNews Theme Customizer
 *
 * @package EnterNews
 */

if (!function_exists('enternews_get_option')):
/**
 * Get theme option.
 *
 * @since 1.0.0
 *
 * @param string $key Option key.
 * @return mixed Option value.
 */
function enternews_get_option($key) {

	if (empty($key)) {
		return;
	}

	$value = '';

	$default       = enternews_get_default_theme_options();
	$default_value = null;

	if (is_array($default) && isset($default[$key])) {
		$default_value = $default[$key];
	}

	if (null !== $default_value) {
		$value = get_theme_mod($key, $default_value);
	} else {
		$value = get_theme_mod($key);
	}

	return $value;
}
endif;

// Load customize default values.
require get_template_directory().'/inc/customizer/customizer-callback.php';

// Load customize default values.
require get_template_directory().'/inc/customizer/customizer-default.php';

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function enternews_customize_register($wp_customize) {

	// Load customize controls.
	require get_template_directory().'/inc/customizer/customizer-control.php';

	// Load customize sanitize.
	require get_template_directory().'/inc/customizer/customizer-sanitize.php';

	$wp_customize->get_setting('blogname')->transport         = 'postMessage';
	$wp_customize->get_setting('blogdescription')->transport  = 'postMessage';
	$wp_customize->get_setting('header_textcolor')->transport = 'postMessage';

	if (isset($wp_customize->selective_refresh)) {
		$wp_customize->selective_refresh->add_partial('blogname', array(
				'selector'        => '.site-title a',
				'render_callback' => 'enternews_customize_partial_blogname',
			));
		$wp_customize->selective_refresh->add_partial('blogdescription', array(
				'selector'        => '.site-description',
				'render_callback' => 'enternews_customize_partial_blogdescription',
			));
	}

    $default = enternews_get_default_theme_options();

    // Setting - secondary_font.
    $wp_customize->add_setting('site_title_font_size',
        array(
            'default'           => $default['site_title_font_size'],
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control('site_title_font_size',
        array(
            'label'    => esc_html__('Site Title Size', 'enternews'),
            'section'  => 'title_tagline',
            'type'     => 'number',
            'priority' => 50,
        )
    );
    // use get control
    $wp_customize->get_control( 'header_textcolor')->label = __( 'Site Title/Tagline Color', 'enternews' );
    $wp_customize->get_control( 'header_textcolor')->section = 'title_tagline';


    // Setting - select_main_banner_section_mode.
    $wp_customize->add_setting('select_header_image_mode',
        array(
            'default'           => $default['select_header_image_mode'],
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'enternews_sanitize_select',
        )
    );

    $wp_customize->add_control( 'select_header_image_mode',
        array(
            'label'       => esc_html__('Header Image Mode', 'enternews'),
            'description'       => esc_html__('Image visibility may vary as per the mode', 'enternews'),
            'section'     => 'header_image',
            'type'        => 'select',
            'choices'               => array(
                'default' => esc_html__( "Set as Background", 'enternews' ),
                'full' => esc_html__( "Show Full Image", 'enternews' ),
            ),
            'priority'    => 50
        ));

    // Setting - header overlay.
    $wp_customize->add_setting('disable_header_image_tint_overlay',
        array(
            'default'           => $default['disable_header_image_tint_overlay'],
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'enternews_sanitize_checkbox',
        )
    );

    $wp_customize->add_control('disable_header_image_tint_overlay',
        array(
            'label'    => esc_html__('Disable Image Tint/Overlay', 'enternews'),
            'section'  => 'header_image',
            'type'     => 'checkbox',
            'priority' => 50,
        )
    );


    //section title
    $wp_customize->add_setting('global_color_section_notice',
        array(
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        new EnterNews_Simple_Notice_Custom_Control(
            $wp_customize,
            'global_color_section_notice',
            array(
                'description' => esc_html__('Background Color will not be applicable for this mode.', 'enternews'),
                'section' => 'colors',
                'priority' => 10,
                'active_callback' => 'enternews_global_site_mode_dark_light_status'
            )
        )
    );



    //section title
    $wp_customize->add_setting('global_site_mode_section_title',
        array(
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        new EnterNews_Section_Title(
            $wp_customize,
            'global_site_mode_section_title',
            array(
                'label' => esc_html__('Site Mode Section ', 'enternews'),
                'section' => 'colors',
                'priority' => 5,
            )
        )
    );

    // Setting - global content alignment of news.
    $wp_customize->add_setting('global_site_mode_setting',
        array(
            'default' => $default['global_site_mode_setting'],
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'enternews_sanitize_select',
        )
    );

    $wp_customize->add_control('global_site_mode_setting',
        array(
            'label' => esc_html__('Site Mode', 'enternews'),
            'section' => 'colors',
            'type' => 'select',
            'choices' => array(
                'aft-default-mode' => esc_html__('Default', 'enternews'),
                'aft-dark-mode' => esc_html__('Dark', 'enternews'),
            ),
            'priority' => 5,
        ));


    //section title
    $wp_customize->add_setting('main_banner_color_section_title',
        array(
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        new EnterNews_Section_Title(
            $wp_customize,
            'main_banner_color_section_title',
            array(
                'label' => esc_html__('Main Banner Color Section ', 'enternews'),
                'section' => 'frontpage_main_banner_section_settings',
                'priority' => 110,
            )
        )
    );


// Setting - select_main_banner_section_mode.
    $wp_customize->add_setting('select_editors_picks_section_background',
        array(
            'default' => $default['select_editors_picks_section_background'],
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'enternews_sanitize_select',
        )
    );

    $wp_customize->add_control('select_editors_picks_section_background',
        array(
            'label' => esc_html__("Editor's Picks", 'enternews'),
            'section' => 'frontpage_main_banner_section_settings',
            'type' => 'select',
            'choices' => array(
                'default' => __('Default', 'enternews'),
                'dark' => __('Alternative', 'enternews'),

            ),
            'priority' => 110,
            //'active_callback' => 'enternews_main_banner_section_status'
        ));

    // Setting - header overlay.
    $wp_customize->add_setting('enable_container_padding',
        array(
            'default'           => $default['enable_container_padding'],
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'enternews_sanitize_checkbox',
        )
    );

    $wp_customize->add_control('enable_container_padding',
        array(
            'label'    => esc_html__('Enable Container Padding', 'enternews'),
            'section'  => 'background_image',
            'type'     => 'checkbox',
            'priority' => 50,
        )
    );

    // Setting - select_main_banner_section_mode.
    $wp_customize->add_setting('select_main_banner_section_background',
        array(
            'default' => $default['select_main_banner_section_background'],
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'enternews_sanitize_select',
        )
    );

    $wp_customize->add_control('select_main_banner_section_background',
        array(
            'label' => esc_html__('Main Slider', 'enternews'),
            'section' => 'frontpage_main_banner_section_settings',
            'type' => 'select',
            'choices' => array(
                'default' => __('Default', 'enternews'),
                'dim' => __('Dim', 'enternews'),

            ),
            'priority' => 110,
            //'active_callback' => 'enternews_main_banner_section_status'
        ));

    // Setting - select_main_banner_section_mode.
    $wp_customize->add_setting('select_trending_section_background',
        array(
            'default' => $default['select_trending_section_background'],
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'enternews_sanitize_select',
        )
    );

    $wp_customize->add_control('select_trending_section_background',
        array(
            'label' => esc_html__('Trending', 'enternews'),
            'section' => 'frontpage_main_banner_section_settings',
            'type' => 'select',
            'choices' => array(
                'default' => __('Default', 'enternews'),
                'secondary-background' => __('Secondary Color', 'enternews'),

            ),
            'priority' => 110,
            //'active_callback' => 'enternews_main_banner_section_status'
        ));

    /*theme option panel info*/
	require get_template_directory().'/inc/customizer/theme-options.php';

    // Register custom section types.
    $wp_customize->register_section_type( 'EnterNews_Customize_Section_Upsell' );

    // Register sections.
    $wp_customize->add_section(
        new EnterNews_Customize_Section_Upsell(
            $wp_customize,
            'theme_upsell',
            array(
                'title'    => esc_html__( 'EnterNews Pro', 'enternews' ),
                'pro_text' => esc_html__( 'Upgrade Now', 'enternews' ),
                'pro_url'  => 'https://www.afthemes.com/products/enternews-pro/',
                'priority'  => 1,
            )
        )
    );



}
add_action('customize_register', 'enternews_customize_register');

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function enternews_customize_partial_blogname() {
	bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function enternews_customize_partial_blogdescription() {
	bloginfo('description');
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function enternews_customize_preview_js() {
	wp_enqueue_script('enternews-customizer', get_template_directory_uri().'/js/customizer.js', array('customize-preview'), '20210418', true);
}
add_action('customize_preview_init', 'enternews_customize_preview_js');


function enternews_customizer_css() {
    wp_enqueue_script( 'enternews-customize-controls', get_template_directory_uri() . '/assets/customizer-admin.js', array( 'customize-controls' ) );

    wp_enqueue_style( 'enternews-customize-controls-style', get_template_directory_uri() . '/assets/customizer-admin.css' );
}
add_action( 'customize_controls_enqueue_scripts', 'enternews_customizer_css',0 );