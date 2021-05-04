<?php
if ( ! class_exists( 'Kirki' ) ) {
	return;
}

/*  Add Config
/* ------------------------------------ */
Kirki::add_config( 'motioner', array(
	'capability'    => 'edit_theme_options',
	'option_type'   => 'theme_mod',
) );

/*  Add Links
/* ------------------------------------ */
Kirki::add_section( 'morelink', array(
	'title'       => esc_html__( 'AlxMedia', 'motioner' ),
	'type'        => 'link',
	'button_text' => esc_html__( 'View More Themes', 'motioner' ),
	'button_url'  => 'http://alx.media/themes/',
	'priority'    => 13,
) );
Kirki::add_section( 'reviewlink', array(
	'title'       => esc_html__( 'Like This Theme?', 'motioner' ),
	'panel'       => 'options',
	'type'        => 'link',
	'button_text' => esc_html__( 'Write a Review', 'motioner' ),
	'button_url'  => 'https://wordpress.org/support/theme/motioner/reviews/#new-post',
	'priority'    => 1,
) );

/*  Add Panels
/* ------------------------------------ */
Kirki::add_panel( 'options', array(
    'priority'    => 10,
    'title'       => esc_html__( 'Theme Options', 'motioner' ),
) );

/*  Add Sections
/* ------------------------------------ */
Kirki::add_section( 'general', array(
    'priority'    => 10,
    'title'       => esc_html__( 'General', 'motioner' ),
	'panel'       => 'options',
) );
Kirki::add_section( 'blog', array(
    'priority'    => 20,
    'title'       => esc_html__( 'Blog', 'motioner' ),
	'panel'       => 'options',
) );
Kirki::add_section( 'header', array(
    'priority'    => 30,
    'title'       => esc_html__( 'Header', 'motioner' ),
	'panel'       => 'options',
) );
Kirki::add_section( 'footer', array(
    'priority'    => 40,
    'title'       => esc_html__( 'Footer', 'motioner' ),
	'panel'       => 'options',
) );
Kirki::add_section( 'layout', array(
    'priority'    => 50,
    'title'       => esc_html__( 'Layout', 'motioner' ),
	'panel'       => 'options',
) );
Kirki::add_section( 'sidebars', array(
    'priority'    => 60,
    'title'       => esc_html__( 'Sidebars', 'motioner' ),
	'panel'       => 'options',
) );
Kirki::add_section( 'social', array(
    'priority'    => 70,
    'title'       => esc_html__( 'Social Links', 'motioner' ),
	'panel'       => 'options',
) );
Kirki::add_section( 'styling', array(
    'priority'    => 80,
    'title'       => esc_html__( 'Styling', 'motioner' ),
	'panel'       => 'options',
) );

/*  Add Fields
/* ------------------------------------ */

// General: Mobile Sidebar
Kirki::add_field( 'motioner_theme', array(
	'type'			=> 'switch',
	'settings'		=> 'mobile-sidebar-hide',
	'label'			=> esc_html__( 'Mobile Sidebar Content', 'motioner' ),
	'description'	=> esc_html__( 'Sidebar content on low-resolution mobile devices (320px)', 'motioner' ),
	'section'		=> 'general',
	'default'		=> 'on',
) );
// General: Recommended Plugins
Kirki::add_field( 'motioner_theme', array(
	'type'			=> 'switch',
	'settings'		=> 'recommended-plugins',
	'label'			=> esc_html__( 'Recommended Plugins', 'motioner' ),
	'description'	=> esc_html__( 'Enable or disable the recommended plugins notice', 'motioner' ),
	'section'		=> 'general',
	'default'		=> 'on',
) );
// Blog: Enable Blog Heading
Kirki::add_field( 'motioner_theme', array(
	'type'			=> 'switch',
	'settings'		=> 'heading-enable',
	'label'			=> esc_html__( 'Enable Blog Heading', 'motioner' ),
	'description'	=> esc_html__( 'Show heading on blog home', 'motioner' ),
	'section'		=> 'blog',
	'default'		=> 'off',
) );
// Blog: Heading
Kirki::add_field( 'motioner_theme', array(
	'type'			=> 'text',
	'settings'		=> 'blog-heading',
	'label'			=> esc_html__( 'Heading', 'motioner' ),
	'description'	=> esc_html__( 'Your blog heading', 'motioner' ),
	'section'		=> 'blog',
	'default'		=> '',
) );
// Blog: Subheading
Kirki::add_field( 'motioner_theme', array(
	'type'			=> 'text',
	'settings'		=> 'blog-subheading',
	'label'			=> esc_html__( 'Subheading', 'motioner' ),
	'description'	=> esc_html__( 'Your blog subheading', 'motioner' ),
	'section'		=> 'blog',
	'default'		=> '',
) );
// Blog: Excerpt Length
Kirki::add_field( 'motioner_theme', array(
	'type'			=> 'slider',
	'settings'		=> 'excerpt-length',
	'label'			=> esc_html__( 'Excerpt Length', 'motioner' ),
	'description'	=> esc_html__( 'Max number of words. Set it to 0 to disable.', 'motioner' ),
	'section'		=> 'blog',
	'default'		=> '16',
	'choices'     => array(
		'min'	=> '0',
		'max'	=> '100',
		'step'	=> '1',
	),
) );
// Blog: Frontpage Widgets Top
Kirki::add_field( 'motioner_theme', array(
	'type'			=> 'switch',
	'settings'		=> 'frontpage-widgets-top',
	'label'			=> esc_html__( 'Frontpage Widgets Top', 'motioner' ),
	'description'	=> esc_html__( '2 columns of widgets', 'motioner' ),
	'section'		=> 'blog',
	'default'		=> 'off',
) );
// Blog: Frontpage Widgets Bottom
Kirki::add_field( 'motioner_theme', array(
	'type'			=> 'switch',
	'settings'		=> 'frontpage-widgets-bottom',
	'label'			=> esc_html__( 'Frontpage Widgets Bottom', 'motioner' ),
	'description'	=> esc_html__( '2 columns of widgets', 'motioner' ),
	'section'		=> 'blog',
	'default'		=> 'off',
) );
// Blog: Comment Count
Kirki::add_field( 'motioner_theme', array(
	'type'			=> 'switch',
	'settings'		=> 'comment-count',
	'label'			=> esc_html__( 'Comment Count', 'motioner' ),
	'description'	=> esc_html__( 'Comment count with bubbles', 'motioner' ),
	'section'		=> 'blog',
	'default'		=> 'on',
) );
// Blog: Single - Authorbox
Kirki::add_field( 'motioner_theme', array(
	'type'			=> 'switch',
	'settings'		=> 'author-bio',
	'label'			=> esc_html__( 'Single - Author Bio', 'motioner' ),
	'description'	=> esc_html__( 'Shows post author description, if it exists', 'motioner' ),
	'section'		=> 'blog',
	'default'		=> 'on',
) );
// Blog: Single - Related Posts
Kirki::add_field( 'motioner_theme', array(
	'type'			=> 'radio',
	'settings'		=> 'related-posts',
	'label'			=> esc_html__( 'Single - Related Posts', 'motioner' ),
	'description'	=> esc_html__( 'Shows randomized related articles below the post', 'motioner' ),
	'section'		=> 'blog',
	'default'		=> 'categories',
	'choices'		=> array(
		'disable'	=> esc_html__( 'Disable', 'motioner' ),
		'categories'=> esc_html__( 'Related by categories', 'motioner' ),
		'tags'		=> esc_html__( 'Related by tags', 'motioner' ),
	),
) );
// Blog: Single - Post Navigation
Kirki::add_field( 'motioner_theme', array(
	'type'			=> 'radio',
	'settings'		=> 'post-nav',
	'label'			=> esc_html__( 'Single - Post Navigation', 'motioner' ),
	'description'	=> esc_html__( 'Shows links to the next and previous article', 'motioner' ),
	'section'		=> 'blog',
	'default'		=> 'sidebar',
	'choices'		=> array(
		'disable'	=> esc_html__( 'Disable', 'motioner' ),
		'sidebar'	=> esc_html__( 'Sidebar', 'motioner' ),
		'content'	=> esc_html__( 'Below content', 'motioner' ),
	),
) );
// Header: Search
Kirki::add_field( 'motioner_theme', array(
	'type'			=> 'switch',
	'settings'		=> 'header-search',
	'label'			=> esc_html__( 'Header Search', 'motioner' ),
	'description'	=> esc_html__( 'Header search button', 'motioner' ),
	'section'		=> 'header',
	'default'		=> 'on',
) );
// Header: Social Links
Kirki::add_field( 'motioner_theme', array(
	'type'			=> 'switch',
	'settings'		=> 'header-social',
	'label'			=> esc_html__( 'Header Social Links', 'motioner' ),
	'description'	=> esc_html__( 'Social link icon buttons', 'motioner' ),
	'section'		=> 'header',
	'default'		=> 'on',
) );
// Header: Profile Avatar
Kirki::add_field( 'motioner_theme', array(
	'type'			=> 'image',
	'settings'		=> 'profile-image',
	'label'			=> esc_html__( 'Profile Image', 'motioner' ),
	'description'	=> esc_html__( 'Minimum width of 320px', 'motioner' ),
	'section'		=> 'header',
	'default'		=> '',
) );
// Header: Profile Name
Kirki::add_field( 'motioner_theme', array(
	'type'			=> 'text',
	'settings'		=> 'profile-name',
	'label'			=> esc_html__( 'Profile Name', 'motioner' ),
	'description'	=> esc_html__( 'Your name appears below the image', 'motioner' ),
	'section'		=> 'header',
	'default'		=> '',
) );
// Header: Profile Description
Kirki::add_field( 'motioner_theme', array(
	'type'			=> 'textarea',
	'settings'		=> 'profile-description',
	'label'			=> esc_html__( 'Profile Description', 'motioner' ),
	'description'	=> esc_html__( 'A short description of you', 'motioner' ),
	'section'		=> 'header',
	'default'		=> '',
) );
// Footer: Ads
Kirki::add_field( 'motioner_theme', array(
	'type'			=> 'switch',
	'settings'		=> 'footer-ads',
	'label'			=> esc_html__( 'Footer Ads', 'motioner' ),
	'description'	=> esc_html__( 'Footer widget ads area', 'motioner' ),
	'section'		=> 'footer',
	'default'		=> 'off',
) );
// Footer: Widget Columns
Kirki::add_field( 'motioner_theme', array(
	'type'			=> 'radio-image',
	'settings'		=> 'footer-widgets',
	'label'			=> esc_html__( 'Footer Widget Columns', 'motioner' ),
	'description'	=> esc_html__( 'Select columns to enable footer widgets. Recommended number: 3', 'motioner' ),
	'section'		=> 'footer',
	'default'		=> '0',
	'choices'     => array(
		'0'	=> get_template_directory_uri() . '/functions/images/layout-off.png',
		'1'	=> get_template_directory_uri() . '/functions/images/footer-widgets-1.png',
		'2'	=> get_template_directory_uri() . '/functions/images/footer-widgets-2.png',
		'3'	=> get_template_directory_uri() . '/functions/images/footer-widgets-3.png',
		'4'	=> get_template_directory_uri() . '/functions/images/footer-widgets-4.png',
	),
) );
// Footer: Social Links
Kirki::add_field( 'motioner_theme', array(
	'type'			=> 'switch',
	'settings'		=> 'footer-social',
	'label'			=> esc_html__( 'Footer Social Links', 'motioner' ),
	'description'	=> esc_html__( 'Social link icon buttons', 'motioner' ),
	'section'		=> 'footer',
	'default'		=> 'on',
) );
// Footer: Custom Logo
Kirki::add_field( 'motioner_theme', array(
	'type'			=> 'image',
	'settings'		=> 'footer-logo',
	'label'			=> esc_html__( 'Footer Logo', 'motioner' ),
	'description'	=> esc_html__( 'Upload your custom logo image', 'motioner' ),
	'section'		=> 'footer',
	'default'		=> '',
) );
// Footer: Copyright
Kirki::add_field( 'motioner_theme', array(
	'type'			=> 'text',
	'settings'		=> 'copyright',
	'label'			=> esc_html__( 'Footer Copyright', 'motioner' ),
	'description'	=> esc_html__( 'Replace the footer copyright text', 'motioner' ),
	'section'		=> 'footer',
	'default'		=> '',
) );
// Footer: Credit
Kirki::add_field( 'motioner_theme', array(
	'type'			=> 'switch',
	'settings'		=> 'credit',
	'label'			=> esc_html__( 'Footer Credit', 'motioner' ),
	'description'	=> esc_html__( 'Footer credit text', 'motioner' ),
	'section'		=> 'footer',
	'default'		=> 'on',
) );
// Layout: Global
Kirki::add_field( 'motioner_theme', array(
	'type'			=> 'radio-image',
	'settings'		=> 'layout-global',
	'label'			=> esc_html__( 'Global Layout', 'motioner' ),
	'description'	=> esc_html__( 'Other layouts will override this option if they are set', 'motioner' ),
	'section'		=> 'layout',
	'default'		=> 'col-2cl',
	'choices'     => array(
		'col-2cl'	=> get_template_directory_uri() . '/functions/images/col-2cl.png',
		'col-2cr'	=> get_template_directory_uri() . '/functions/images/col-2cr.png',
	),
) );
// Layout: Home
Kirki::add_field( 'motioner_theme', array(
	'type'			=> 'radio-image',
	'settings'		=> 'layout-home',
	'label'			=> esc_html__( 'Home', 'motioner' ),
	'description'	=> esc_html__( '(is_home) Posts homepage layout', 'motioner' ),
	'section'		=> 'layout',
	'default'		=> 'inherit',
	'choices'     => array(
		'inherit'	=> get_template_directory_uri() . '/functions/images/layout-off.png',
		'col-2cl'	=> get_template_directory_uri() . '/functions/images/col-2cl.png',
		'col-2cr'	=> get_template_directory_uri() . '/functions/images/col-2cr.png',
	),
) );
// Layout: Single
Kirki::add_field( 'motioner_theme', array(
	'type'			=> 'radio-image',
	'settings'		=> 'layout-single',
	'label'			=> esc_html__( 'Single', 'motioner' ),
	'description'	=> esc_html__( '(is_single) Single post layout - If a post has a set layout, it will override this.', 'motioner' ),
	'section'		=> 'layout',
	'default'		=> 'inherit',
	'choices'     => array(
		'inherit'	=> get_template_directory_uri() . '/functions/images/layout-off.png',
		'col-2cl'	=> get_template_directory_uri() . '/functions/images/col-2cl.png',
		'col-2cr'	=> get_template_directory_uri() . '/functions/images/col-2cr.png',
	),
) );
// Layout: Archive
Kirki::add_field( 'motioner_theme', array(
	'type'			=> 'radio-image',
	'settings'		=> 'layout-archive',
	'label'			=> esc_html__( 'Archive', 'motioner' ),
	'description'	=> esc_html__( '(is_archive) Category, date, tag and author archive layout', 'motioner' ),
	'section'		=> 'layout',
	'default'		=> 'inherit',
	'choices'     => array(
		'inherit'	=> get_template_directory_uri() . '/functions/images/layout-off.png',
		'col-2cl'	=> get_template_directory_uri() . '/functions/images/col-2cl.png',
		'col-2cr'	=> get_template_directory_uri() . '/functions/images/col-2cr.png',
	),
) );
// Layout : Archive - Category
Kirki::add_field( 'motioner_theme', array(
	'type'			=> 'radio-image',
	'settings'		=> 'layout-archive-category',
	'label'			=> esc_html__( 'Archive - Category', 'motioner' ),
	'description'	=> esc_html__( '(is_category) Category archive layout', 'motioner' ),
	'section'		=> 'layout',
	'default'		=> 'inherit',
	'choices'     => array(
		'inherit'	=> get_template_directory_uri() . '/functions/images/layout-off.png',
		'col-2cl'	=> get_template_directory_uri() . '/functions/images/col-2cl.png',
		'col-2cr'	=> get_template_directory_uri() . '/functions/images/col-2cr.png',
	),
) );
// Layout: Search
Kirki::add_field( 'motioner_theme', array(
	'type'			=> 'radio-image',
	'settings'		=> 'layout-search',
	'label'			=> esc_html__( 'Search', 'motioner' ),
	'description'	=> esc_html__( '(is_search) Search page layout', 'motioner' ),
	'section'		=> 'layout',
	'default'		=> 'inherit',
	'choices'     => array(
		'inherit'	=> get_template_directory_uri() . '/functions/images/layout-off.png',
		'col-2cl'	=> get_template_directory_uri() . '/functions/images/col-2cl.png',
		'col-2cr'	=> get_template_directory_uri() . '/functions/images/col-2cr.png',
	),
) );
// Layout: Error 404
Kirki::add_field( 'motioner_theme', array(
	'type'			=> 'radio-image',
	'settings'		=> 'layout-404',
	'label'			=> esc_html__( 'Error 404', 'motioner' ),
	'description'	=> esc_html__( '(is_404) Error 404 page layout', 'motioner' ),
	'section'		=> 'layout',
	'default'		=> 'inherit',
	'choices'     => array(
		'inherit'	=> get_template_directory_uri() . '/functions/images/layout-off.png',
		'col-2cl'	=> get_template_directory_uri() . '/functions/images/col-2cl.png',
		'col-2cr'	=> get_template_directory_uri() . '/functions/images/col-2cr.png',
	),
) );
// Layout: Default Page
Kirki::add_field( 'motioner_theme', array(
	'type'			=> 'radio-image',
	'settings'		=> 'layout-page',
	'label'			=> esc_html__( 'Default Page', 'motioner' ),
	'description'	=> esc_html__( '(is_page) Default page layout - If a page has a set layout, it will override this.', 'motioner' ),
	'section'		=> 'layout',
	'default'		=> 'inherit',
	'choices'     => array(
		'inherit'	=> get_template_directory_uri() . '/functions/images/layout-off.png',
		'col-2cl'	=> get_template_directory_uri() . '/functions/images/col-2cl.png',
		'col-2cr'	=> get_template_directory_uri() . '/functions/images/col-2cr.png',
	),
) );


function motioner_kirki_sidebars_select() { 
 	$sidebars = array(); 
 	if ( isset( $GLOBALS['wp_registered_sidebars'] ) ) { 
 		$sidebars = $GLOBALS['wp_registered_sidebars']; 
 	} 
 	$sidebars_choices = array(); 
 	foreach ( $sidebars as $sidebar ) { 
 		$sidebars_choices[ $sidebar['id'] ] = $sidebar['name']; 
 	} 
 	if ( ! class_exists( 'Kirki' ) ) { 
 		return; 
 	}
	// Sidebars: Select
	Kirki::add_field( 'motioner_theme', array(
		'type'			=> 'select',
		'settings'		=> 's1-home',
		'label'			=> esc_html__( 'Home', 'motioner' ),
		'description'	=> esc_html__( '(is_home) Primary', 'motioner' ),
		'section'		=> 'sidebars',
		'choices'		=> $sidebars_choices, 
		'default'		=> '',
		'placeholder'	=> esc_html__( 'Select a sidebar', 'motioner' ),
	) );
	Kirki::add_field( 'motioner_theme', array(
		'type'			=> 'select',
		'settings'		=> 's1-single',
		'label'			=> esc_html__( 'Single', 'motioner' ),
		'description'	=> esc_html__( '(is_single) Primary - If a single post has a unique sidebar, it will override this.', 'motioner' ),
		'section'		=> 'sidebars',
		'choices'		=> $sidebars_choices, 
		'default'		=> '',
		'placeholder'	=> esc_html__( 'Select a sidebar', 'motioner' ),
	) );
	Kirki::add_field( 'motioner_theme', array(
		'type'			=> 'select',
		'settings'		=> 's1-archive',
		'label'			=> esc_html__( 'Archive', 'motioner' ),
		'description'	=> esc_html__( '(is_archive) Primary', 'motioner' ),
		'section'		=> 'sidebars',
		'choices'		=> $sidebars_choices, 
		'default'		=> '',
		'placeholder'	=> esc_html__( 'Select a sidebar', 'motioner' ),
	) );
	Kirki::add_field( 'motioner_theme', array(
		'type'			=> 'select',
		'settings'		=> 's1-archive-category',
		'label'			=> esc_html__( 'Archive - Category', 'motioner' ),
		'description'	=> esc_html__( '(is_category) Primary', 'motioner' ),
		'section'		=> 'sidebars',
		'choices'		=> $sidebars_choices, 
		'default'		=> '',
		'placeholder'	=> esc_html__( 'Select a sidebar', 'motioner' ),
	) );
	Kirki::add_field( 'motioner_theme', array(
		'type'			=> 'select',
		'settings'		=> 's1-search',
		'label'			=> esc_html__( 'Search', 'motioner' ),
		'description'	=> esc_html__( '(is_search) Primary', 'motioner' ),
		'section'		=> 'sidebars',
		'choices'		=> $sidebars_choices, 
		'default'		=> '',
		'placeholder'	=> esc_html__( 'Select a sidebar', 'motioner' ),
	) );
	Kirki::add_field( 'motioner_theme', array(
		'type'			=> 'select',
		'settings'		=> 's1-404',
		'label'			=> esc_html__( 'Error 404', 'motioner' ),
		'description'	=> esc_html__( '(is_404) Primary', 'motioner' ),
		'section'		=> 'sidebars',
		'choices'		=> $sidebars_choices, 
		'default'		=> '',
		'placeholder'	=> esc_html__( 'Select a sidebar', 'motioner' ),
	) );
	Kirki::add_field( 'motioner_theme', array(
		'type'			=> 'select',
		'settings'		=> 's1-page',
		'label'			=> esc_html__( 'Default Page', 'motioner' ),
		'description'	=> esc_html__( '(is_page) Primary - If a page has a unique sidebar, it will override this.', 'motioner' ),
		'section'		=> 'sidebars',
		'choices'		=> $sidebars_choices, 
		'default'		=> '',
		'placeholder'	=> esc_html__( 'Select a sidebar', 'motioner' ),
	) );
	
 } 
add_action( 'init', 'motioner_kirki_sidebars_select', 999 ); 

// Social Links: List
Kirki::add_field( 'motioner_theme', array(
	'type'			=> 'repeater',
	'label'			=> esc_html__( 'Create Social Links', 'motioner' ),
	'description'	=> esc_html__( 'Create and organize your social links', 'motioner' ),
	'section'		=> 'social',
	'tooltip'		=> esc_html__( 'Font Awesome names:', 'motioner' ) . ' <a href="https://fontawesome.com/icons?d=gallery&s=brands&m=free" target="_blank"><strong>' . esc_html__( 'View All', 'motioner' ) . ' </strong></a>',
	'row_label'		=> array(
		'type'	=> 'text',
		'value'	=> esc_html__('social link', 'motioner' ),
	),
	'settings'		=> 'social-links',
	'default'		=> '',
	'fields'		=> array(
		'social-title'	=> array(
			'type'			=> 'text',
			'label'			=> esc_html__( 'Title', 'motioner' ),
			'description'	=> esc_html__( 'Ex: Facebook', 'motioner' ),
			'default'		=> '',
		),
		'social-icon'	=> array(
			'type'			=> 'text',
			'label'			=> esc_html__( 'Icon Name', 'motioner' ),
			'description'	=> esc_html__( 'Font Awesome icons. Ex: fa-facebook ', 'motioner' ) . ' <a href="https://fontawesome.com/icons?d=gallery&s=brands&m=free" target="_blank"><strong>' . esc_html__( 'View All', 'motioner' ) . ' </strong></a>',
			'default'		=> 'fa-',
		),
		'social-link'	=> array(
			'type'			=> 'link',
			'label'			=> esc_html__( 'Link', 'motioner' ),
			'description'	=> esc_html__( 'Enter the full url for your icon button', 'motioner' ),
			'default'		=> 'http://',
		),
		'social-color'	=> array(
			'type'			=> 'color',
			'label'			=> esc_html__( 'Icon Color', 'motioner' ),
			'description'	=> esc_html__( 'Set a unique color for your icon (optional)', 'motioner' ),
			'default'		=> '',
		),
		'social-target'	=> array(
			'type'			=> 'checkbox',
			'label'			=> esc_html__( 'Open in new window', 'motioner' ),
			'default'		=> false,
		),
	)
) );
// Styling: Enable
Kirki::add_field( 'motioner_theme', array(
	'type'			=> 'switch',
	'settings'		=> 'dynamic-styles',
	'label'			=> esc_html__( 'Dynamic Styles', 'motioner' ),
	'description'	=> esc_html__( 'Turn on to use the styling options below', 'motioner' ),
	'section'		=> 'styling',
	'default'		=> 'on',
) );
// Styling: Boxed Layout
Kirki::add_field( 'motioner_theme', array(
	'type'			=> 'switch',
	'settings'		=> 'boxed',
	'label'			=> esc_html__( 'Boxed Layout', 'motioner' ),
	'description'	=> esc_html__( 'Use a boxed layout', 'motioner' ),
	'section'		=> 'styling',
	'default'		=> 'off',
) );
// Styling: Font
Kirki::add_field( 'motioner_theme', array(
	'type'			=> 'select',
	'settings'		=> 'font',
	'label'			=> esc_html__( 'Font', 'motioner' ),
	'description'	=> esc_html__( 'Select font for the theme', 'motioner' ),
	'section'		=> 'styling',
	'default'		=> 'roboto',
	'choices'     => array(
		'titillium-web'			=> esc_html__( 'Titillium Web, Latin (Self-hosted)', 'motioner' ),
		'titillium-web-ext'		=> esc_html__( 'Titillium Web, Latin-Ext', 'motioner' ),
		'droid-serif'			=> esc_html__( 'Droid Serif, Latin', 'motioner' ),
		'source-sans-pro'		=> esc_html__( 'Source Sans Pro, Latin-Ext', 'motioner' ),
		'lato'					=> esc_html__( 'Lato, Latin', 'motioner' ),
		'raleway'				=> esc_html__( 'Raleway, Latin', 'motioner' ),
		'ubuntu'				=> esc_html__( 'Ubuntu, Latin-Ext', 'motioner' ),
		'ubuntu-cyr'			=> esc_html__( 'Ubuntu, Latin / Cyrillic-Ext', 'motioner' ),
		'roboto'				=> esc_html__( 'Roboto, Latin-Ext', 'motioner' ),
		'roboto-cyr'			=> esc_html__( 'Roboto, Latin / Cyrillic-Ext', 'motioner' ),
		'roboto-condensed'		=> esc_html__( 'Roboto Condensed, Latin-Ext', 'motioner' ),
		'roboto-condensed-cyr'	=> esc_html__( 'Roboto Condensed, Latin / Cyrillic-Ext', 'motioner' ),
		'roboto-slab'			=> esc_html__( 'Roboto Slab, Latin-Ext', 'motioner' ),
		'roboto-slab-cyr'		=> esc_html__( 'Roboto Slab, Latin / Cyrillic-Ext', 'motioner' ),
		'playfair-display'		=> esc_html__( 'Playfair Display, Latin-Ext', 'motioner' ),
		'playfair-display-cyr'	=> esc_html__( 'Playfair Display, Latin / Cyrillic', 'motioner' ),
		'open-sans'				=> esc_html__( 'Open Sans, Latin-Ext', 'motioner' ),
		'open-sans-cyr'			=> esc_html__( 'Open Sans, Latin / Cyrillic-Ext', 'motioner' ),
		'pt-serif'				=> esc_html__( 'PT Serif, Latin-Ext', 'motioner' ),
		'pt-serif-cyr'			=> esc_html__( 'PT Serif, Latin / Cyrillic-Ext', 'motioner' ),
		'arial'					=> esc_html__( 'Arial', 'motioner' ),
		'georgia'				=> esc_html__( 'Georgia', 'motioner' ),
		'verdana'				=> esc_html__( 'Verdana', 'motioner' ),
		'tahoma'				=> esc_html__( 'Tahoma', 'motioner' ),
	),
) );
// Styling: Header Logo Max-height
Kirki::add_field( 'motioner_theme', array(
	'type'			=> 'slider',
	'settings'		=> 'logo-max-height',
	'label'			=> esc_html__( 'Header Logo Image Max-height', 'motioner' ),
	'description'	=> esc_html__( 'Your logo image should have the double height of this to be high resolution', 'motioner' ),
	'section'		=> 'styling',
	'default'		=> '50',
	'choices'     => array(
		'min'	=> '40',
		'max'	=> '200',
		'step'	=> '1',
	),
) );
// Styling: Container Width
Kirki::add_field( 'motioner_theme', array(
	'type'			=> 'slider',
	'settings'		=> 'container-width',
	'label'			=> esc_html__( 'Website Max-width', 'motioner' ),
	'description'	=> esc_html__( 'Max-width of the container.', 'motioner' ),
	'section'		=> 'styling',
	'default'		=> '1200',
	'choices'     => array(
		'min'	=> '1024',
		'max'	=> '1920',
		'step'	=> '1',
	),
) );
// Styling: Article Width
Kirki::add_field( 'motioner_theme', array(
	'type'			=> 'slider',
	'settings'		=> 'article-width',
	'label'			=> esc_html__( 'Article Max-width', 'motioner' ),
	'description'	=> esc_html__( 'Max-width of the articles.', 'motioner' ),
	'section'		=> 'styling',
	'default'		=> '920',
	'choices'     => array(
		'min'	=> '720',
		'max'	=> '1920',
		'step'	=> '1',
	),
) );
// Styling: Primary Color
Kirki::add_field( 'motioner_theme', array(
	'type'			=> 'color',
	'settings'		=> 'color-1',
	'label'			=> esc_html__( 'Primary Color', 'motioner' ),
	'section'		=> 'styling',
	'default'		=> '#dc3262',
) );
// Styling: Gradient Left
Kirki::add_field( 'motioner_theme', array(
	'type'			=> 'color',
	'settings'		=> 'gradient-left',
	'label'			=> esc_html__( 'Gradient Left', 'motioner' ),
	'section'		=> 'styling',
	'default'		=> '#bd1d9d',
) );
// Styling: Gradient Right
Kirki::add_field( 'motioner_theme', array(
	'type'			=> 'color',
	'settings'		=> 'gradient-right',
	'label'			=> esc_html__( 'Gradient Right', 'motioner' ),
	'section'		=> 'styling',
	'default'		=> '#d64141',
) );
