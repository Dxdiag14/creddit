<?php

add_action( 'after_setup_theme', 'alx_ext_custom_options', 11 );

function alx_ext_custom_options() {
	if ( ! class_exists( 'Kirki' ) ) {
		return;
	}
	
	// Config: Alx Extensions
	Kirki::add_config( 'alx_extensions', array(
		'capability'    => 'edit_theme_options',
		'option_type'   => 'theme_mod',
	) );
	
	// Panel: Theme Extensions
	Kirki::add_panel( 'alx_extensions_panel', array(
		'priority'    => 11,
		'title'       => esc_html__( 'Theme Extensions', 'alx' ),
	) );
	
	// Section: Social Share
	Kirki::add_section( 'alx_extensions_social', array(
		'priority'    => 10,
		'title'       => esc_html__( 'Social Share', 'alx' ),
		'panel'       => 'alx_extensions_panel',
	) );
	// Section: Image Upscale
	Kirki::add_section( 'alx_extensions_upscale', array(
		'priority'    => 11,
		'title'       => esc_html__( 'Image Upscale', 'alx' ),
		'panel'       => 'alx_extensions_panel',
	) );
	// Section: Custom Sidebars
	Kirki::add_section( 'alx_extensions_custom_sidebars', array(
		'priority'    => 12,
		'title'       => esc_html__( 'Custom Sidebars', 'alx' ),
		'panel'       => 'alx_extensions_panel',
	) );
	// Section: Custom Widgets
	Kirki::add_section( 'alx_extensions_widgets', array(
		'priority'    => 13,
		'title'       => esc_html__( 'Custom Widgets', 'alx' ),
		'panel'       => 'alx_extensions_panel',
	) );
	// Section: Meta Boxes
	Kirki::add_section( 'alx_extensions_meta_boxes', array(
		'priority'    => 14,
		'title'       => esc_html__( 'Meta Boxes', 'alx' ),
		'panel'       => 'alx_extensions_panel',
	) );
		
	// Social Share: Enable
	Kirki::add_field( 'enable_social_share', array(
		'type'			=> 'switch',
		'settings'		=> 'enable-social-share',
		'label'			=> esc_html__( 'Enable Social Share', 'alx' ),
		'section'		=> 'alx_extensions_social',
		'default'		=> 'on',
	) );
	// Social Share: Twitter Username
	Kirki::add_field( 'twitter_username', array(
		'type'			=> 'text',
		'settings'		=> 'twitter-username',
		'label'			=> esc_html__( 'Twitter Username', 'alx' ),
		'description'	=> esc_html__( 'Your @username will be added to share-tweets of your posts (optional)', 'alx' ),
		'section'		=> 'alx_extensions_social',
		'default'		=> '',
	) ); 	
	// Image Upscale: Enable
	Kirki::add_field( 'enable_image_upscale', array(
		'type'			=> 'switch',
		'settings'		=> 'enable-image-upscale',
		'label'			=> esc_html__( 'Enable Image Upscale', 'alx' ),
		'description'	=> esc_html__( 'Scales up small images and crops to the right thumbnail sizes (recommended)', 'alx' ),
		'section'		=> 'alx_extensions_upscale',
		'default'		=> 'on',
	) );
	// Custom Sidebars: Create Sidebars
	Kirki::add_field( 'alx_sidebar', array(
		'type'			=> 'repeater',
		'label'			=> esc_html__( 'Create Sidebars', 'alx' ),
		'description'	=> esc_html__( 'You must save and refresh the page to see your new sidebars.', 'alx' ),
		'tooltip'		=> esc_html__( 'Make sure that you save and refresh the page if you can not see the sidebars you have created.', 'alx' ),
		'section'		=> 'alx_extensions_custom_sidebars',
		'row_label'		=> array(
			'type'	=> 'text',
			'value'	=> esc_html__('sidebar', 'alx' ),
		),
		'settings'		=> 'sidebar-areas',
		'default'		=> '',
		'fields'		=> array(
			'title'	=> array(
				'type'        => 'text',
				'label'       => esc_html__( 'Sidebar Title', 'alx' ),
				'description' => esc_html__( 'The widget box title', 'alx' ),
				'default'     => '',
			),
			'id'	=> array(
				'type'        => 'text',
				'label'       => esc_html__( 'Sidebar ID', 'alx' ),
				'description' => esc_html__( 'This ID must be unique', 'alx' ),
				'default'     => 'sidebar-',
			),
		)
	) );
	// Custom Widgets: Alx Posts
	Kirki::add_field( 'alx_posts', array(
		'type'			=> 'switch',
		'settings'		=> 'widget-alx-posts',
		'label'			=> esc_html__( 'Alx Posts', 'alx' ),
		'section'		=> 'alx_extensions_widgets',
		'default'		=> 'on',
	) );
	// Custom Widgets: Alx Tabs
	Kirki::add_field( 'alx_tabs', array(
		'type'			=> 'switch',
		'settings'		=> 'widget-alx-tabs',
		'label'			=> esc_html__( 'Alx Tabs', 'alx' ),
		'section'		=> 'alx_extensions_widgets',
		'default'		=> 'on',
	) );
	// Meta Boxes: Post Format Audio
	Kirki::add_field( 'alx_meta_box_audio', array(
		'type'			=> 'switch',
		'settings'		=> 'alx-meta-box-audio',
		'label'			=> esc_html__( 'Post Format: Audio', 'alx' ),
		'section'		=> 'alx_extensions_meta_boxes',
		'default'		=> 'on',
	) );
	// Meta Boxes: Post Format Video
	Kirki::add_field( 'alx_meta_box_video', array(
		'type'			=> 'switch',
		'settings'		=> 'alx-meta-box-video',
		'label'			=> esc_html__( 'Post Format: Video', 'alx' ),
		'section'		=> 'alx_extensions_meta_boxes',
		'default'		=> 'on',
	) );
}
