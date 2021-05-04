<?php /* Text */

add_filter( 'discy_sanitize_text', 'sanitize_text_field' );

/* Password */

add_filter( 'discy_sanitize_password', 'sanitize_text_field' );

/* sliderui,sections,sort,select,select,radio,images,textarea */

$discy_sanitize = array("sliderui","sections","sort","select","select_category","multicheck_category","radio","images","textarea","elements","roles","upload_images");
foreach ($discy_sanitize as $key => $value) {
	add_filter( 'discy_sanitize_'.$value, 'discy_sanitize_enum', 10, 2);
}

/* Checkbox */

function discy_sanitize_checkbox( $input ) {
	if ( $input ) {
		$output = 'on';
	} else {
		$output = 0;
	}
	return $output;
}
add_filter( 'discy_sanitize_checkbox', 'discy_sanitize_checkbox' );

/* Multicheck */

function discy_sanitize_multicheck( $input, $option ) {
	$output = array();
	if (isset($option["sort"]) && $option["sort"] == "yes") {
		$output = $input;
	}else {
		if ( is_array( $input ) ) {
			foreach( $option['options'] as $key => $value ) {
				if (isset($input[$key]) && $value == "on") {
					$output[$key] = false;
				}
			}
			foreach( $input as $key => $value ) {
				if (isset($input[$key]) && $value == "on") {
					$output[$key] = "on";
				}else {
					$output[$key] = false;
				}
			}
		}
	}
	return $output;
}
add_filter( 'discy_sanitize_multicheck', 'discy_sanitize_multicheck', 10, 2 );

/* Color Picker */

add_filter( 'discy_sanitize_color', 'discy_sanitize_hex' );

/* Uploader */

function discy_sanitize_upload( $input ) {
	$output = '';
	$filetype = wp_check_filetype($input);
	if ( $filetype["ext"] ) {
		$output = $input;
	}
	return $output;
}
add_filter( 'discy_sanitize_upload', 'discy_sanitize_upload' );

/* Editor */

function discy_sanitize_editor($input) {
	if ( current_user_can( 'unfiltered_html' ) ) {
		$output = $input;
	}
	else {
		global $allowedtags;
		$output = wpautop(wp_kses( $input, $allowedtags));
	}
	return $output;
}
add_filter( 'discy_sanitize_editor', 'discy_sanitize_editor' );

/* Allowed Tags */

function discy_sanitize_allowedtags( $input ) {
	global $allowedtags;
	$output = wpautop( wp_kses( $input, $allowedtags ) );
	return $output;
}

/* Allowed Post Tags */

function discy_sanitize_allowedposttags( $input ) {
	global $allowedposttags;
	$output = wpautop(wp_kses( $input, $allowedposttags));
	return $output;
}
add_filter( 'discy_sanitize_info', 'discy_sanitize_allowedposttags' );

/* Check that the key value sent is valid */

function discy_sanitize_enum( $input, $option ) {
	$output = $input;
	return $output;
}

/* Background */

function discy_sanitize_background( $input ) {
	$output = wp_parse_args( $input, array(
		'color' => '',
		'image'  => '',
		'repeat'  => 'repeat',
		'position' => 'top center',
		'attachment' => 'scroll'
	) );
	
	if (isset($input['color'])) {
		$output['color'] = apply_filters( 'discy_sanitize_hex', $input['color'] );
	}
	if (isset($input['image'])) {
		$output['image'] = apply_filters( 'discy_sanitize_upload', $input['image'] );
	}
	if (isset($input['repeat'])) {
		$output['repeat'] = apply_filters( 'discy_background_repeat', $input['repeat'] );
	}
	if (isset($input['position'])) {
		$output['position'] = apply_filters( 'discy_background_position', $input['position'] );
	}
	if (isset($input['attachment'])) {
		$output['attachment'] = apply_filters( 'discy_background_attachment', $input['attachment'] );
	}
	return $output;
}
add_filter( 'discy_sanitize_background', 'discy_sanitize_background' );

function discy_sanitize_background_repeat( $value ) {
	$recognized = discy_recognized_background_repeat();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'discy_default_background_repeat', current( $recognized ) );
}
add_filter( 'discy_background_repeat', 'discy_sanitize_background_repeat' );

function discy_sanitize_background_position( $value ) {
	$recognized = discy_recognized_background_position();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'discy_default_background_position', current( $recognized ) );
}
add_filter( 'discy_background_position', 'discy_sanitize_background_position' );

function discy_sanitize_background_attachment( $value ) {
	$recognized = discy_recognized_background_attachment();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'discy_default_background_attachment', current( $recognized ) );
}
add_filter( 'discy_background_attachment', 'discy_sanitize_background_attachment' );


/* Typography */

function discy_sanitize_typography( $input, $option ) {

	$output = wp_parse_args( $input, array(
		'size'  => '',
		'face'  => '',
		'style' => '',
		'color' => ''
	) );

	$output['face']  = apply_filters( 'discy_font_face', $output['face'] );
	$output['size']  = apply_filters( 'discy_font_size', $output['size'] );
	$output['style'] = apply_filters( 'discy_font_style', $output['style'] );
	$output['color'] = apply_filters( 'discy_sanitize_color', $output['color'] );
	return $output;
}
add_filter( 'discy_sanitize_typography', 'discy_sanitize_typography', 10, 2 );

function discy_sanitize_font_size( $value ) {
	$recognized = discy_recognized_font_sizes();
	$value_check = preg_replace('/px/','', $value);
	if ( in_array( (int) $value_check, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'discy_default_font_size', $recognized );
}
add_filter( 'discy_font_size', 'discy_sanitize_font_size' );

function discy_sanitize_font_style( $value ) {
	$recognized = discy_recognized_font_styles();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'discy_default_font_style', current( $recognized ) );
}
add_filter( 'discy_font_style', 'discy_sanitize_font_style' );


function discy_sanitize_font_face( $value ) {
	return $value;
}
add_filter( 'discy_font_face', 'discy_sanitize_font_face' );

/**
 * Get recognized background repeat settings
 *
 * @return   array
 *
 */
function discy_recognized_background_repeat() {
	$default = array(
		'no-repeat' => esc_html__( 'No Repeat', "discy" ),
		'repeat-x'  => esc_html__( 'Repeat Horizontally', "discy" ),
		'repeat-y'  => esc_html__( 'Repeat Vertically', "discy" ),
		'repeat'    => esc_html__( 'Repeat All', "discy" ),
		);
	return apply_filters( 'discy_recognized_background_repeat', $default );
}

/**
 * Get recognized background positions
 *
 * @return   array
 *
 */
function discy_recognized_background_position() {
	$default = array(
		'top left'      => esc_html__( 'Top Left', "discy" ),
		'top center'    => esc_html__( 'Top Center', "discy" ),
		'top right'     => esc_html__( 'Top Right', "discy" ),
		'center left'   => esc_html__( 'Middle Left', "discy" ),
		'center center' => esc_html__( 'Middle Center', "discy" ),
		'center right'  => esc_html__( 'Middle Right', "discy" ),
		'bottom left'   => esc_html__( 'Bottom Left', "discy" ),
		'bottom center' => esc_html__( 'Bottom Center', "discy" ),
		'bottom right'  => esc_html__( 'Bottom Right', "discy")
		);
	return apply_filters( 'discy_recognized_background_position', $default );
}

/**
 * Get recognized background attachment
 *
 * @return   array
 *
 */
function discy_recognized_background_attachment() {
	$default = array(
		'scroll' => esc_html__( 'Scroll Normally', "discy" ),
		'fixed'  => esc_html__( 'Fixed in Place', "discy")
		);
	return apply_filters( 'discy_recognized_background_attachment', $default );
}

/**
 * Sanitize a color represented in hexidecimal notation.
 *
 * @param    string    Color in hexidecimal notation. "#" may or may not be prepended to the string.
 * @param    string    The value that this function should return if it cannot be recognized as a color.
 * @return   string
 *
 */

function discy_sanitize_hex( $hex, $default = '' ) {
	if ( discy_validate_hex( $hex ) ) {
		return $hex;
	}
	return $default;
}

/**
 * Get recognized font sizes.
 *
 * Returns an indexed array of all recognized font sizes.
 * Values are integers and represent a range of sizes from
 * smallest to largest.
 *
 * @return   array
 */

function discy_recognized_font_sizes() {
	$sizes = range( 9, 71 );
	$sizes = apply_filters( 'discy_recognized_font_sizes', $sizes );
	$sizes = array_map( 'absint', $sizes );
	return $sizes;
}

/**
 * Get recognized font faces.
 *
 * Returns an array of all recognized font faces.
 * Keys are intended to be stored in the database
 * while values are ready for display in in html.
 *
 * @return   array
 *
 */
function discy_recognized_font_faces() {
	$default = array(
		'arial'     => 'Arial',
		'verdana'   => 'Verdana, Geneva',
		'trebuchet' => 'Trebuchet',
		'georgia'   => 'Georgia',
		'times'     => 'Times New Roman',
		'tahoma'    => 'Tahoma, Geneva',
		'palatino'  => 'Palatino',
		'helvetica' => 'Helvetica*'
		);
	return apply_filters( 'discy_recognized_font_faces', $default );
}

/**
 * Get recognized font styles.
 *
 * Returns an array of all recognized font styles.
 * Keys are intended to be stored in the database
 * while values are ready for display in in html.
 *
 * @return   array
 *
 */
function discy_recognized_font_styles() {
	$default = array(
		'default'     => esc_html__("Style","discy"),
		'normal'      => esc_html__( 'Normal', "discy" ),
		'italic'      => esc_html__( 'Italic', "discy" ),
		'bold'        => esc_html__( 'Bold', "discy" ),
		'bold italic' => esc_html__( 'Bold Italic', "discy" )
		);
	return apply_filters( 'discy_recognized_font_styles', $default );
}

/**
 * Is a given string a color formatted in hexidecimal notation?
 *
 * @param    string    Color in hexidecimal notation. "#" may or may not be prepended to the string.
 * @return   bool
 *
 */

function discy_validate_hex( $hex ) {
	$hex = trim( $hex );
	/* Strip recognized prefixes. */
	if ( 0 === strpos( $hex, '#' ) ) {
		$hex = substr( $hex, 1 );
	}
	elseif ( 0 === strpos( $hex, '%23' ) ) {
		$hex = substr( $hex, 3 );
	}
	/* Regex match. */
	if ( 0 === preg_match( '/^[0-9a-fA-F]{6}$/', $hex ) ) {
		return false;
	}
	else {
		return true;
	}
}?>