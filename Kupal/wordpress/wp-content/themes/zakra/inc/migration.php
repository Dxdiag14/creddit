<?php
/**
 * Migrations scripts for Zakra theme.
 *
 * @package    ThemeGrill
 * @subpackage Zakra
 * @since 1.4.7
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function zakra_migrations() {

	// Bail out if the migration is already done.
	if ( get_option( 'zakra_migrations' ) ) {
		return;
	}

	// Update id: `zakra_typography_page_title` to `zakra_typography_post_page_title`
	$old_page_title_typography = get_theme_mod( 'zakra_typography_page_title' );

	if ( $old_page_title_typography ) {
		set_theme_mod( 'zakra_typography_post_page_title', $old_page_title_typography );
		remove_theme_mod( 'zakra_typography_page_title' );
	}

	// Migrate Page Header Text Color to Typography.
	$old_page_title_color       = get_theme_mod( 'zakra_page_header_text_color' );
	$old_page_title_font_size   = get_theme_mod( 'zakra_page_title_font_size' );
	$post_page_title_typography = get_theme_mod(
		'zakra_typography_post_page_title',
		apply_filters(
			'zakra_typography_post_page_title_filter',
			array(
				'font-family' => '-apple-system, blinkmacsystemfont, segoe ui, roboto, oxygen-sans, ubuntu, cantarell, helvetica neue, helvetica, arial, sans-serif',
				'variant'     => '500',
				'line-height' => '1.3',
				'color'       => '#16181a',
			)
		)
	);

	if ( $old_page_title_color ) {
		$post_page_title_typography['color'] = $old_page_title_color;
		set_theme_mod( 'zakra_typography_post_page_title', $post_page_title_typography );
		remove_theme_mod( 'zakra_page_header_text_color' );
	}

	if ( $old_page_title_font_size ) {
		$post_page_title_typography['font-size'] = $old_page_title_font_size['slider'] . $old_page_title_font_size['suffix'];
		set_theme_mod( 'zakra_typography_post_page_title', $post_page_title_typography );
		remove_theme_mod( 'zakra_page_title_font_size' );
	}

	// Migrate headings colors from typography to heading colors.
	$headings_typography = get_theme_mod(
		'zakra_base_typography_heading',
		apply_filters(
			'zakra_base_typography_heading_filter',
			array(
				'font-family' => '-apple-system, blinkmacsystemfont, segoe ui, roboto, oxygen-sans, ubuntu, cantarell, helvetica neue, helvetica, arial, sans-serif',
				'variant'     => '400',
				'line-height' => '1.3',
				'color'       => '#16181a',
			)
		)
	);

	$h1_typography = get_theme_mod(
		'zakra_typography_h1',
		apply_filters(
			'zakra_typography_h1_filter',
			array(
				'font-family' => '-apple-system, blinkmacsystemfont, segoe ui, roboto, oxygen-sans, ubuntu, cantarell, helvetica neue, helvetica, arial, sans-serif',
				'variant'     => '500',
				'font-size'   => '2.5rem',
				'line-height' => '1.3',
				'color'       => '#16181a',
			)
		)
	);

	$h2_typography = get_theme_mod(
		'zakra_typography_h2',
		apply_filters(
			'zakra_typography_h2_filter',
			array(
				'font-family' => '-apple-system, blinkmacsystemfont, segoe ui, roboto, oxygen-sans, ubuntu, cantarell, helvetica neue, helvetica, arial, sans-serif',
				'variant'     => '500',
				'font-size'   => '2.25rem',
				'line-height' => '1.3',
				'color'       => '#16181a',
			)
		)
	);

	$h3_typography = get_theme_mod(
		'zakra_typography_h3',
		apply_filters(
			'zakra_typography_h3_filter',
			array(
				'font-family' => '-apple-system, blinkmacsystemfont, segoe ui, roboto, oxygen-sans, ubuntu, cantarell, helvetica neue, helvetica, arial, sans-serif',
				'variant'     => '500',
				'font-size'   => '2rem',
				'line-height' => '1.3',
				'color'       => '#16181a',
			)
		)
	);

	$h4_typography = get_theme_mod(
		'zakra_typography_h4',
		apply_filters(
			'zakra_typography_h4_filter',
			array(
				'font-family' => '-apple-system, blinkmacsystemfont, segoe ui, roboto, oxygen-sans, ubuntu, cantarell, helvetica neue, helvetica, arial, sans-serif',
				'variant'     => '500',
				'font-size'   => '1.75rem',
				'line-height' => '1.3',
				'color'       => '#16181a',
			)
		)
	);

	$h5_typography = get_theme_mod(
		'zakra_typography_h5',
		apply_filters(
			'zakra_typography_h5_filter',
			array(
				'font-family' => '-apple-system, blinkmacsystemfont, segoe ui, roboto, oxygen-sans, ubuntu, cantarell, helvetica neue, helvetica, arial, sans-serif',
				'variant'     => '500',
				'font-size'   => '1.313rem',
				'line-height' => '1.3',
				'color'       => '#16181a',
			)
		)
	);

	$h6_typography = get_theme_mod(
		'zakra_typography_h6',
		apply_filters(
			'zakra_typography_h6_filter',
			array(
				'font-family' => '-apple-system, blinkmacsystemfont, segoe ui, roboto, oxygen-sans, ubuntu, cantarell, helvetica neue, helvetica, arial, sans-serif',
				'variant'     => '500',
				'font-size'   => '1.125rem',
				'line-height' => '1.3',
				'color'       => '#16181a',
			)
		)
	);

	$old_headings_color = $headings_typography['color'];

	$old_h1_color = $h1_typography['color'];

	$old_h2_color = $h2_typography['color'];

	$old_h3_color = $h3_typography['color'];

	$old_h4_color = $h4_typography['color'];

	$old_h5_color = $h5_typography['color'];

	$old_h6_color = $h6_typography['color'];

	if ( $old_headings_color ) {
		set_theme_mod( 'zakra_color_h1', $old_headings_color );
		unset( $headings_typography['color'] );
		set_theme_mod( 'zakra_base_typography_heading', $h1_typography );
	}

	if ( $old_h1_color ) {
		set_theme_mod( 'zakra_color_h1', $old_h1_color );
		unset( $h1_typography['color'] );
		set_theme_mod( 'zakra_typography_h1', $h1_typography );
	}

	if ( $old_h2_color ) {
		set_theme_mod( 'zakra_color_h2', $old_h2_color );
		unset( $h2_typography['color'] );
		set_theme_mod( 'zakra_typography_h2', $h2_typography );
	}

	if ( $old_h3_color ) {
		set_theme_mod( 'zakra_color_h3', $old_h3_color );
		unset( $h3_typography['color'] );
		set_theme_mod( 'zakra_typography_h3', $h3_typography );
	}

	if ( $old_h4_color ) {
		set_theme_mod( 'zakra_color_h4', $old_h4_color );
		unset( $h4_typography['color'] );
		set_theme_mod( 'zakra_typography_h4', $h4_typography );
	}

	if ( $old_h5_color ) {
		set_theme_mod( 'zakra_color_h5', $old_h5_color );
		unset( $h5_typography['color'] );
		set_theme_mod( 'zakra_typography_h5', $h5_typography );
	}

	if ( $old_h6_color ) {
		set_theme_mod( 'zakra_color_h6', $old_h6_color );
		unset( $h6_typography['color'] );
		set_theme_mod( 'zakra_typography_h6', $h6_typography );
	}

	// Set flag to not repeat the migration process, ie, run it only once.
	update_option( 'zakra_migrations', true );

}

add_action( 'after_setup_theme', 'zakra_migrations' );
