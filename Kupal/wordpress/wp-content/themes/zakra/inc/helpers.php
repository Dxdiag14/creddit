<?php
/**
 * Zakra helper functions.
 *
 * @package zakra
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'zakra_is_woocommerce_active' ) ) {
	/**
	 * Check if WooCommerce plugin is active.
	 *
	 * @see https://docs.woocommerce.com/document/query-whether-woocommerce-is-activated/
	 */
	function zakra_is_woocommerce_active() {
		if ( class_exists( 'woocommerce' ) ) {
			return true;
		} else {
			return false;
		}
	}
}

if ( ! function_exists( 'zakra_is_zakra_pro_active' ) ) {
	/**
	 * Function to return the boolean value if `Zakra Pro` plugin is activated or not.
	 *
	 * @return bool
	 */
	function zakra_is_zakra_pro_active() {
		if ( class_exists( 'zakra_pro' ) ) {
			return true;
		} else {
			return false;
		}
	}
}

if ( ! function_exists( 'zakra_is_header_transparent_enabled' ) ) {
	/**
	 * Check if header transparent is enabled.
	 */
	function zakra_is_header_transparent_enabled() {
		// Zakra Pro Customizer.
		$customizer_result = apply_filters( 'zakra_header_transparency_filter', false );

		// Meta box.
		$meta_result = get_post_meta( zakra_get_post_id(), 'zakra_transparent_header', true );

		$transparency = false;

		if ( zakra_is_zakra_pro_active() && ( is_404() || is_search() || is_archive() ) && true == get_theme_mod( 'zakra_transparent_header_custom_enable', false ) ) {
			$transparency = true;
		} elseif ( zakra_is_zakra_pro_active() && ( is_front_page() && is_home() ) && true == get_theme_mod( 'zakra_transparent_header_latest_posts_enable', false ) ) {
			$transparency = true;
		} elseif ( '1' == $meta_result || true === $meta_result ) { // Enabled in meta.
			$transparency = true;
		} elseif ( 'customizer' == $meta_result && true == $customizer_result ) { // Enabled in Customizer
			$transparency = true;
		}

		return apply_filters( 'zakra_header_transparency_enable', $transparency );
	}
}

if ( ! function_exists( 'zakra_is_page_title_enabled' ) ) {
	/**
	 * Check if page header is enabled.
	 */
	function zakra_is_page_title_enabled() {
		$result = get_theme_mod( 'zakra_page_title_enabled', 'page-header' );

		// If invalid: return default.
		if ( ! in_array( $result, array( 'page-header', 'content-area' ) ) ) {
			return 'page-header';
		}

		return apply_filters( 'zakra_page_title_enabled', $result );
	}
}

if ( ! function_exists( 'zakra_is_breadcrumbs_enabled' ) ) {
	/**
	 * Check if breadcrumbs is enabled.
	 */
	function zakra_is_breadcrumbs_enabled() {

		// Return false if disabled via Customizer.
		$result = get_theme_mod( 'zakra_breadcrumbs_enabled', true );

		// If invalid: return default.
		if ( ! is_bool( $result ) ) {
			return true;
		}

		return apply_filters( 'zakra_breadcrumbs_enabled', $result );
	}
}

if ( ! function_exists( 'zakra_is_header_top_enabled' ) ) {
	/**
	 * Check if header top is enabled.
	 */
	function zakra_is_header_top_enabled() {
		$result = get_theme_mod( 'zakra_header_top_enabled', false );

		// If invalid: return default.
		if ( ! is_bool( $result ) ) {
			return false;
		}

		// Return false if disabled via Customizer.
		return apply_filters( 'zakra_header_top_enabled', $result );
	}
}

if ( ! function_exists( 'zakra_is_footer_widgets_enabled' ) ) {
	/**
	 * Check if header top is enabled.
	 */
	function zakra_is_footer_widgets_enabled() {
		$result = get_theme_mod( 'zakra_footer_widgets_enabled', true );

		// If invalid: return default.
		if ( ! is_bool( $result ) ) {
			return true;
		}

		// Return false if disabled via Customizer.
		return apply_filters( 'zakra_footer_widgets_enabled', $result );
	}
}

if ( ! function_exists( 'zakra_is_footer_bar_enabled' ) ) {
	/**
	 * Check if footer bar is enabled.
	 */
	function zakra_is_footer_bar_enabled() {

		return apply_filters( 'zakra_footer_bar_enabled', '__return_true' );
	}
}

if ( ! function_exists( 'zakra_footer_copyright' ) ) {
	/**
	 * Get Copyright text.
	 *
	 * @param string $section 'one' or 'two' only should be passed as param.
	 *
	 * @return array|string|string[]|null
	 */
	function zakra_footer_copyright( $section ) {

		if ( 'one' === $section ) {
			$default = sprintf(
				/* translators: 1: Current Year, 2: Site Name, 3: Theme Link, 4: WordPress Link. */
				esc_html__( 'Copyright &copy; %1$s %2$s. Powered by %3$s and %4$s.', 'zakra' ),
				'{the-year}',
				'{site-link}',
				'{theme-link}',
				'{wp-link}'
			);
		} else {
			$default = '';
		}

		$content      = get_theme_mod( 'zakra_footer_bar_section_' . $section . '_html', $default );
		$theme_author = apply_filters(
			'zakra_theme_author',
			array(
				'theme_name'       => __( 'Zakra', 'zakra' ),
				'theme_author_url' => 'https://zakratheme.com/',
			)
		);
		$site_link    = '<a href="' . esc_url( home_url( '/' ) ) . '" title="' . esc_attr( get_bloginfo( 'name', 'display' ) ) . '" >' . get_bloginfo( 'name', 'display' ) . '</a>';
		$theme_link   = '<a href="' . esc_url( $theme_author['theme_author_url'] ) . '" target="_blank" title="' . esc_attr( $theme_author['theme_name'] ) . '" rel="nofollow">' . $theme_author['theme_name'] . '</a>';
		$wp_link      = '<a href="' . esc_url( 'https://wordpress.org/' ) . '" target="_blank" title="' . esc_attr__( 'WordPress', 'zakra' ) . '" rel="nofollow">' . __( 'WordPress', 'zakra' ) . '</a>';

		if ( $content || is_customize_preview() ) {
			$content = str_replace( '{the-year}', date_i18n( 'Y' ), $content );
			$content = str_replace( '{site-link}', $site_link, $content );
			$content = str_replace( '{theme-link}', $theme_link, $content );
			$content = str_replace( '{wp-link}', $wp_link, $content );
		}

		return $content;
	}
}

if ( ! function_exists( 'zakra_search_icon_menu_item' ) ) {
	/**
	 * Renders search icon menu item.
	 */
	function zakra_search_icon_menu_item() {
		$output     = '';
		$data_attrs = apply_filters( 'zakra_header_search_icon_data_attrs', '' );

		if ( true === get_theme_mod( 'tg_header_menu_search_enabled', true ) ) {
			$output  = '<li class="' . zakra_css_class( 'zakra_header_search_class', false ) . '"' . apply_filters( 'zakra_header_search_data_attrs', '' ) . '>';
			$output .= apply_filters( 'zakra_search_icon', '<a href="#" ' . $data_attrs . ' ><i class="tg-icon tg-icon-search"></i></a>' );

			$output .= get_search_form( false );
			$output .= '</li>';
			$output .= '<!-- /.tg-header-search -->';
		}

		return $output;
	}
}

if ( ! function_exists( 'zakra_get_layout_type' ) ) {
	/**
	 * Get layout type.
	 *
	 * @return string A layout type.
	 */
	function zakra_get_layout_type() {

		global $post;
		$layout = 'tg-site-layout--right'; // Set default.

		if ( $post ) {

			// Meta value.
			$layout_meta_arr = get_post_meta( zakra_get_post_id(), 'zakra_layout' );
			$layout_meta     = isset( $layout_meta_arr[0] ) ? $layout_meta_arr[0] : 'tg-site-layout--customizer';

			// Get layout from Customizer.
			if ( 'tg-site-layout--customizer' === $layout_meta ) {
				if ( is_single() ) {
					$layout = get_theme_mod( 'zakra_structure_post', 'tg-site-layout--right' );
				} elseif ( is_page() ) {
					$layout = get_theme_mod( 'zakra_structure_page', 'tg-site-layout--right' );
				} elseif ( is_archive() ) {
					$layout = get_theme_mod( 'zakra_structure_archive', 'tg-site-layout--right' );
				} else {
					$layout = get_theme_mod( 'zakra_structure_default', 'tg-site-layout--right' );
				}
			} else { // Get layout from Meta box.
				$layout = $layout_meta;
			}
		}

		return $layout;
	}
}

/**
 * Compare user's current version of plugin.
 */
if ( ! function_exists( 'zakra_plugin_version_compare' ) ) {
	function zakra_plugin_version_compare( $plugin_slug, $version_to_compare ) {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$installed_plugins = get_plugins();

		// Plugin not installed.
		if ( ! isset( $installed_plugins[ $plugin_slug ] ) ) {
			return false;
		}

		$tdi_user_version = $installed_plugins[ $plugin_slug ]['Version'];

		return version_compare( $tdi_user_version, $version_to_compare, '<' );
	}
}

if ( ! function_exists( 'zakra_strpos' ) ) :

	/**
	 * Find the position of the first occurrence of a substring in an array.
	 * @param       $haystack
	 * @param array $needles
	 * @param int   $offset
	 *
	 * @return bool
	 */
	function zakra_strpos( $haystack, $needles = array(), $offset = 0 ) {

		if ( ! is_array( $needles ) ) {
			$needles = array( $needles );
		}

		foreach ( $needles as $needle ) {

			if ( strpos( $haystack, $needle, $offset ) !== false ) {
				return true;
			}
		}

		return false;
	}
endif;
