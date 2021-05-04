<?php
/**
 * Override default customizer options.
 *
 * @package zakra
 */

// Settings.
$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

if ( isset( $wp_customize->selective_refresh ) ) {
	$wp_customize->selective_refresh->add_partial(
		'blogname',
		array(
			'selector'        => '.site-title a',
			'render_callback' => array( 'Zakra_Customizer_Partials', 'customize_partial_blogname' ),
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'blogdescription',
		array(
			'selector'        => '.site-description',
			'render_callback' => array( 'Zakra_Customizer_Partials', 'customize_partial_blogdescription' ),
		)
	);
}

/**
 * Override controls.
 */
// Outside container > background control.
$wp_customize->get_control( 'background_color' )->section  = 'zakra_background';
$wp_customize->get_control( 'background_color' )->priority = 20;

$wp_customize->get_control( 'background_image' )->section  = 'zakra_background';
$wp_customize->get_control( 'background_image' )->priority = 25;

$wp_customize->get_control( 'background_preset' )->section  = 'zakra_background';
$wp_customize->get_control( 'background_preset' )->priority = 30;

$wp_customize->get_control( 'background_position' )->section  = 'zakra_background';
$wp_customize->get_control( 'background_position' )->priority = 35;

$wp_customize->get_control( 'background_size' )->section  = 'zakra_background';
$wp_customize->get_control( 'background_size' )->priority = 40;

$wp_customize->get_control( 'background_repeat' )->section  = 'zakra_background';
$wp_customize->get_control( 'background_repeat' )->priority = 45;

$wp_customize->get_control( 'background_attachment' )->section  = 'zakra_background';
$wp_customize->get_control( 'background_attachment' )->priority = 50;

// Site Identity.
$wp_customize->get_control( 'custom_logo' )->priority         = 6;
$wp_customize->get_control( 'site_icon' )->priority           = 12;
$wp_customize->get_control( 'blogname' )->priority            = 14;
$wp_customize->get_control( 'blogdescription' )->priority     = 16;
$wp_customize->get_control( 'display_header_text' )->priority = 18;

// Header Media.
$wp_customize->get_control( 'header_video' )->priority = 6;
$wp_customize->get_control( 'header_image' )->priority = 11;

// Modify WooCommerce default section priorities.
if ( class_exists( 'WooCommerce' ) ) {
	$wp_customize->get_panel( 'woocommerce' )->priority = 36;
}


