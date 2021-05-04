<?php
/**
 * Register customizer panels and sections.
 *
 * @package zakra
 */

/**
 * Section: Zakra Pro Upsell.
 */
if ( ! zakra_is_zakra_pro_active() ) :

	$wp_customize->register_section_type( 'Zakra_Customize_Upsell_Section' );

	$wp_customize->add_section(
		new Zakra_Customize_Upsell_Section(
			$wp_customize,
			'zakra_customize_upsell_section',
			array(
				'title'      => esc_html__( 'View Pro Version', 'zakra' ),
				'priority'   => 5,
				'url'        => 'https://zakratheme.com/pricing/?utm_source=zakra-customizer&utm_medium=view-pro-link&utm_campaign=zakra-pricing',
				'capability' => 'edit_theme_options',
			)
		)
	);

endif;

/**
 * Panel: Global.
 */
$wp_customize->add_panel(
	new Zakra_Customize_Panel(
		$wp_customize,
		'zakra_global',
		array(
			'priority'   => 10,
			'title'      => esc_html__( 'Global', 'zakra' ),
			'capability' => 'edit_theme_options',
		)
	)
);

// Section: Global > Container.
$wp_customize->add_section(
	new Zakra_Customize_Section(
		$wp_customize,
		'zakra_container',
		array(
			'title'    => esc_html__( 'Container', 'zakra' ),
			'panel'    => 'zakra_global',
			'priority' => 10,
		)
	)
);

// Section: Global > Colors.
$wp_customize->add_section(
	new Zakra_Customize_Section(
		$wp_customize,
		'zakra_colors',
		array(
			'title'    => esc_html__( 'Colors', 'zakra' ),
			'panel'    => 'zakra_global',
			'priority' => 20,
		)
	)
);

// Section: Global > Colors > Base Colors.
$wp_customize->add_section(
	new Zakra_Customize_Section(
		$wp_customize,
		'zakra_base_colors',
		array(
			'title'    => esc_html__( 'Base Colors', 'zakra' ),
			'panel'    => 'zakra_global',
			'section'  => 'zakra_colors',
			'priority' => 10,
		)
	)
);

// Section: Global > Colors > Link Colors.
$wp_customize->add_section(
	new Zakra_Customize_Section(
		$wp_customize,
		'zakra_link_colors',
		array(
			'title'    => esc_html__( 'Link Colors', 'zakra' ),
			'panel'    => 'zakra_global',
			'section'  => 'zakra_colors',
			'priority' => 20,
		)
	)
);

// Section: Global > Background.
$wp_customize->add_section(
	new Zakra_Customize_Section(
		$wp_customize,
		'zakra_background',
		array(
			'title'    => esc_html__( 'Background', 'zakra' ),
			'panel'    => 'zakra_global',
			'priority' => 30,
		)
	)
);

// Section: Global > Sidebar Layout.
$wp_customize->add_section(
	new Zakra_Customize_Section(
		$wp_customize,
		'zakra_sidebar_layout',
		array(
			'title'    => esc_html__( 'Sidebar Layout', 'zakra' ),
			'panel'    => 'zakra_global',
			'priority' => 40,
		)
	)
);

// Section: Global > Typography.
$wp_customize->add_section(
	new Zakra_Customize_Section(
		$wp_customize,
		'zakra_typography',
		array(
			'title'    => esc_html__( 'Typography', 'zakra' ),
			'panel'    => 'zakra_global',
			'priority' => 50,
		)
	)
);

// Section: Global > Typography > Base Typography.
$wp_customize->add_section(
	new Zakra_Customize_Section(
		$wp_customize,
		'zakra_base_typography',
		array(
			'title'    => esc_html__( 'Base Typography', 'zakra' ),
			'panel'    => 'zakra_global',
			'section'  => 'zakra_typography',
			'priority' => 10,
		)
	)
);


// Section: Typography > Headings ( h1 - h6 ) Typography.
$wp_customize->add_section(
	new Zakra_Customize_Section(
		$wp_customize,
		'zakra_headings_typography',
		array(
			'title'    => esc_html__( 'Headings ( H1 - H6 )', 'zakra' ),
			'panel'    => 'zakra_global',
			'section'  => 'zakra_typography',
			'priority' => 20,
		)
	)
);

// Section: Global > Typography > Links.
$wp_customize->add_section(
	new Zakra_Customize_Section(
		$wp_customize,
		'zakra_link_typography',
		array(
			'title'    => esc_html__( 'Links', 'zakra' ),
			'panel'    => 'zakra_global',
			'section'  => 'zakra_typography',
			'priority' => 30,
		)
	)
);

// Section: Global > Button.
$wp_customize->add_section(
	new Zakra_Customize_Section(
		$wp_customize,
		'zakra_button',
		array(
			'title'    => esc_html__( 'Button', 'zakra' ),
			'panel'    => 'zakra_global',
			'priority' => 60,
		)
	)
);

/**
 * Panel: Header.
 */
$wp_customize->add_panel(
	new Zakra_Customize_Panel(
		$wp_customize,
		'zakra_header',
		array(
			'title'      => esc_html__( 'Header', 'zakra' ),
			'capability' => 'edit_theme_options',
			'priority'   => 20,
		)
	)
);

// Section: Header > Site Identity.
$wp_customize->add_section(
	new Zakra_Customize_Section(
		$wp_customize,
		'title_tagline',
		array(
			'title'    => esc_html__( 'Site Identity', 'zakra' ),
			'panel'    => 'zakra_header',
			'priority' => 10,
		)
	)
);

// Section: Header > Site Identity.
$wp_customize->add_section(
	new Zakra_Customize_Section(
		$wp_customize,
		'header_image',
		array(
			'title'    => esc_html__( 'Header Media', 'zakra' ),
			'panel'    => 'zakra_header',
			'priority' => 20,
		)
	)
);

// Section: Header > Header Top Bar.
$wp_customize->add_section(
	new Zakra_Customize_Section(
		$wp_customize,
		'zakra_header_top',
		array(
			'title'    => esc_html__( 'Header Top Bar', 'zakra' ),
			'panel'    => 'zakra_header',
			'priority' => 30,
		)
	)
);

// Section: Header > Header Main Area.
$wp_customize->add_section(
	new Zakra_Customize_Section(
		$wp_customize,
		'zakra_header_main',
		array(
			'title'    => esc_html__( 'Header Main Area', 'zakra' ),
			'panel'    => 'zakra_header',
			'priority' => 40,
		)
	)
);

// Section: Header > Header Button.
$wp_customize->add_section(
	new Zakra_Customize_Section(
		$wp_customize,
		'zakra_header_button',
		array(
			'title'    => esc_html__( 'Header Button', 'zakra' ),
			'panel'    => 'zakra_header',
			'priority' => 50,
		)
	)
);

/*
 * Section: Menu.
 */
$wp_customize->add_section(
	new Zakra_Customize_Section(
		$wp_customize,
		'zakra_menu',
		array(
			'title'    => esc_html__( 'Menu', 'zakra' ),
			'panel'    => 'zakra_header',
			'priority' => 70,
		)
	)
);

// Section: Menu > Primary Menu.
$wp_customize->add_section(
	new Zakra_Customize_Section(
		$wp_customize,
		'zakra_primary_menu',
		array(
			'title'    => esc_html__( 'Primary Menu', 'zakra' ),
			'section'  => 'zakra_menu',
			'panel'    => 'zakra_header',
			'priority' => 10,
		)
	)
);

// Section: Menu > Primary menu : Dropdown.
$wp_customize->add_section(
	new Zakra_Customize_Section(
		$wp_customize,
		'zakra_primary_menu_item',
		array(
			'title'    => esc_html__( 'Primary Menu : Menu Item', 'zakra' ),
			'section'  => 'zakra_menu',
			'panel'    => 'zakra_header',
			'priority' => 20,
		)
	)
);

// Section: Menu > Primary menu : Dropdown item.
$wp_customize->add_section(
	new Zakra_Customize_Section(
		$wp_customize,
		'zakra_primary_menu_dropdown_item',
		array(
			'title'    => esc_html__( 'Primary Menu : Dropdown Item', 'zakra' ),
			'section'  => 'zakra_menu',
			'panel'    => 'zakra_header',
			'priority' => 40,
		)
	)
);

/**
 * Panel: Content.
 */
$wp_customize->add_panel(
	new Zakra_Customize_Panel(
		$wp_customize,
		'zakra_content',
		array(
			'title'      => esc_html__( 'Content', 'zakra' ),
			'capability' => 'edit_theme_options',
			'priority'   => 30,
		)
	)
);

// Section: Content > Page Header.
$wp_customize->add_section(
	new Zakra_Customize_Section(
		$wp_customize,
		'zakra_page_header',
		array(
			'title'    => esc_html__( 'Page Header', 'zakra' ),
			'panel'    => 'zakra_content',
			'priority' => 10,
		)
	)
);

// Section: Content > Blog/Archive.
$wp_customize->add_section(
	new Zakra_Customize_Section(
		$wp_customize,
		'zakra_archive_blog',
		array(
			'title'    => esc_html__( 'Blog/Archive', 'zakra' ),
			'panel'    => 'zakra_content',
			'priority' => 20,
		)
	)
);

// Section: Content > Single Post.
$wp_customize->add_section(
	new Zakra_Customize_Section(
		$wp_customize,
		'zakra_single_blog_post',
		array(
			'title'    => esc_html__( 'Single Post', 'zakra' ),
			'panel'    => 'zakra_content',
			'priority' => 30,
		)
	)
);

// Section: Content > Meta.
$wp_customize->add_section(
	new Zakra_Customize_Section(
		$wp_customize,
		'zakra_meta',
		array(
			'title'    => esc_html__( 'Meta', 'zakra' ),
			'panel'    => 'zakra_content',
			'priority' => 40,
		)
	)
);

// Section: Content > Sidebar.
$wp_customize->add_section(
	new Zakra_Customize_Section(
		$wp_customize,
		'zakra_sidebar',
		array(
			'title'    => esc_html__( 'Sidebar', 'zakra' ),
			'panel'    => 'zakra_content',
			'priority' => 60,
		)
	)
);

/*
 * Panel: Footer.
 */
$wp_customize->add_panel(
	new Zakra_Customize_Panel(
		$wp_customize,
		'zakra_footer',
		array(
			'title'    => esc_html__( 'Footer', 'zakra' ),
			'priority' => 35,
		)
	)
);

// Section: Footer > Footer widgets.
$wp_customize->add_section(
	new Zakra_Customize_Section(
		$wp_customize,
		'zakra_footer_widgets',
		array(
			'title'    => esc_html__( 'Footer Widgets', 'zakra' ),
			'panel'    => 'zakra_footer',
			'priority' => 10,
		)
	)
);

// Section: Footer > Footer bar.
$wp_customize->add_section(
	new Zakra_Customize_Section(
		$wp_customize,
		'zakra_footer_bottom_bar',
		array(
			'title'    => esc_html__( 'Footer Bottom Bar', 'zakra' ),
			'panel'    => 'zakra_footer',
			'priority' => 20,
		)
	)
);

// Section: Footer > Scroll to top.
$wp_customize->add_section(
	new Zakra_Customize_Section(
		$wp_customize,
		'zakra_footer_scroll_to_top',
		array(
			'title'    => esc_html__( 'Scroll to Top', 'zakra' ),
			'panel'    => 'zakra_footer',
			'priority' => 30,
		)
	)
);

// Section: WooCommerce > Sidebar Layout.
$wp_customize->add_section(
	new Zakra_Customize_Section(
		$wp_customize,
		'zakra_woocommerce_sidebar_layout',
		array(
			'title'    => esc_html__( 'Sidebar Layout', 'zakra' ),
			'panel'    => 'woocommerce',
			'priority' => 4,
		)
	)
);

// Separator.
$wp_customize->add_section(
	new Zakra_Customize_Section(
		$wp_customize,
		'zakra-section-separator',
		array(
			'priority' => 37,
		)
	)
);
