<?php
namespace Auxin\Plugin\CoreElements\Elementor;


/**
 * Auxin Elementor Elements
 *
 * Custom Elementor extension.
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Main Auxin Elementor Elements Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
final class Elements {

    /**
     * Plugin Version
     *
     * @since 1.0.0
     *
     * @var string The plugin version.
     */
    const VERSION = '1.0.0';

    /**
     * Minimum Elementor Version
     *
     * @since 1.0.0
     *
     * @var string Minimum Elementor version required to run the plugin.
     */
    const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

    /**
     * Minimum PHP Version
     *
     * @since 1.0.0
     *
     * @var string Minimum PHP version required to run the plugin.
     */
    const MINIMUM_PHP_VERSION = '5.4.0';

    /**
     * Default elementor dir path
     *
     * @since 1.0.0
     *
     * @var string The defualt path to elementor dir on this plugin.
     */
    private $dir_path = '';

    /**
     * Instance
     *
     * @since 1.0.0
     *
     * @access private
     * @static
     *
     * @var Auxin_Elementor_Core_Elements The single instance of the class.
    */
    private static $_instance = null;

    /**
     * Instance
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @since 1.0.0
     *
     * @access public
     * @static
     *
     * @return Auxin_Elementor_Core_Elements An instance of the class.
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
          self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function __construct() {
        add_action( 'plugins_loaded', array( $this, 'init' ) );
    }

    /**
     * Initialize the plugin
     *
     * Load the plugin only after Elementor (and other plugins) are loaded.
     *
     * Fired by `plugins_loaded` action hook.
     *
     * @since 1.0.0
     *
     * @access public
    */
    public function init() {

        // Check if Elementor installed and activated
        if ( ! did_action( 'elementor/loaded' ) ) {
            return;
        }

        // Check for required Elementor version
        if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
            add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );
            return;
        }

        // Check for required PHP version
        if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
            add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
            return;
        }

        // Define elementor dir path
        $this->dir_path = AUXELS_INC_DIR . '/elementor';

        // Include core files
        $this->includes();

        // Add required hooks
        $this->hooks();
    }

    /**
     * Include Files
     *
     * Load required core files.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function includes() {
        $this->load_modules();
    }

    /**
     * Add hooks
     *
     * Add required hooks for extending the Elementor.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function hooks() {

        // Register controls, widgets, and categories
        add_action( 'elementor/elements/categories_registered' , [ $this, 'register_categories' ] );
        add_action( 'elementor/widgets/widgets_registered'     , [ $this, 'register_widgets'    ] );
        add_action( 'elementor/controls/controls_registered'   , [ $this, 'register_controls'   ] );

        // Register Widget Styles
        add_action( 'elementor/frontend/after_enqueue_styles'  , [ $this, 'widget_styles' ] );

        // Register Widget Scripts
        add_action( 'elementor/frontend/after_register_scripts', [ $this, 'widget_scripts' ] );

        // Register Admin Scripts
        add_action( 'elementor/editor/before_enqueue_scripts'  , [ $this, 'editor_scripts' ] );

        // Register additional font icons
        add_filter('elementor/icons_manager/additional_tabs'   , [ $this, 'add_auxin_font_icons' ] );

        // Change options on auxin load
        add_action( 'auxin_admin_loaded'                       , [ $this, 'auxin_admin_loaded' ] );

        // Flush CSS cache on auxin theme or plugin update
        add_action( 'auxin_updated'                            , [ $this, 'clear_cache' ] );

        // Add additional fonts to elementor fonts
        add_filter( 'elementor/fonts/additional_fonts', [ $this, 'additional_fonts' ], 1, 1 );
    }

    /**
     * Register widgets
     *
     * Register all auxin widgets which are in widgets list.
     *
     * @access public
     */
    public function register_widgets( $widgets_manager ) {

        $widgets = [

            /*  Dynamic Elements
            /*-------------------------------------*/
            '10' => [
                'file'  => $this->dir_path . '/widgets/recent-posts-grid-carousel.php',
                'class' => 'Elements\RecentPostsGridCarousel'
            ],
            '20' => [
                'file'  => $this->dir_path . '/widgets/recent-posts-masonry.php',
                'class' => 'Elements\RecentPostsMasonry'
            ],
            '30' => [
                'file'  => $this->dir_path . '/widgets/recent-posts-land-style.php',
                'class' => 'Elements\RecentPostsLand'
            ],
            '40' => [
                'file'  => $this->dir_path . '/widgets/recent-posts-timeline.php',
                'class' => 'Elements\RecentPostsTimeline'
            ],
            '50' => [
                'file'  => $this->dir_path . '/widgets/recent-posts-tiles.php',
                'class' => 'Elements\RecentPostsTiles'
            ],
            '60' => [
                'file'  => $this->dir_path . '/widgets/recent-posts-tiles-carousel.php',
                'class' => 'Elements\RecentPostsTilesCarousel'
            ],
            '70' => [
                'file'  => $this->dir_path . '/widgets/recent-products.php',
                'class' => 'Elements\RecentProducts'
            ],

            '80' => [
                'file'  => $this->dir_path . '/widgets/recent-comments.php',
                'class' => 'Elements\RecentComments'
            ],

            /*  General Elements
            /*-------------------------------------*/
            '88'  => [
                'file'  => $this->dir_path . '/widgets/heading-modern.php',
                'class' => 'Elements\ModernHeading'
            ],
            '89'  => [
                'file'  => $this->dir_path . '/widgets/icon.php',
                'class' => 'Elements\Icon'
            ],
            '90'  => [
                'file'  => $this->dir_path . '/widgets/image.php',
                'class' => 'Elements\Image'
            ],
            '100' => [
                'file'  => $this->dir_path . '/widgets/gallery.php',
                'class' => 'Elements\Gallery'
            ],
            '105' => [
                'file'  => $this->dir_path . '/widgets/text.php',
                'class' => 'Elements\Text'
            ],
            '110' => [
                'file'  => $this->dir_path . '/widgets/divider.php',
                'class' => 'Elements\Divider'
            ],
            '115' => [
                'file'  => $this->dir_path . '/widgets/button.php',
                'class' => 'Elements\Button'
            ],
            '120' => [
                'file'  => $this->dir_path . '/widgets/accordion.php',
                'class' => 'Elements\Accordion'
            ],
            '125' => [
                'file'  => $this->dir_path . '/widgets/tabs.php',
                'class' => 'Elements\Tabs'
            ],
            '130' => [
                'file'  => $this->dir_path . '/widgets/audio.php',
                'class' => 'Elements\Audio'
            ],
            '140' => [
                'file'  => $this->dir_path . '/widgets/video.php',
                'class' => 'Elements\Video'
            ],
            '145' => [
                'file'  => $this->dir_path . '/widgets/quote.php',
                'class' => 'Elements\Quote'
            ],
            '150' => [
                'file'  => $this->dir_path . '/widgets/testimonial.php',
                'class' => 'Elements\Testimonial'
            ],
            '155' => [
                'file'  => $this->dir_path . '/widgets/contact-form.php',
                'class' => 'Elements\ContactForm'
            ],
            '160' => [
                'file'  => $this->dir_path . '/widgets/contact-box.php',
                'class' => 'Elements\ContactBox'
            ],
            '165' => [
                'file'  => $this->dir_path . '/widgets/touch-slider.php',
                'class' => 'Elements\TouchSlider'
            ],
            '170' => [
                'file'  => $this->dir_path . '/widgets/before-after.php',
                'class' => 'Elements\BeforeAfter'
            ],
            '175' => [
                'file'  => $this->dir_path . '/widgets/staff.php',
                'class' => 'Elements\Staff'
            ],
            '180' => [
                'file'  => $this->dir_path . '/widgets/gmap.php',
                'class' => 'Elements\Gmap'
            ],
            '185' => [
                'file'  => $this->dir_path . '/widgets/custom-list.php',
                'class' => 'Elements\CustomList'
            ],
            '190' => [
                'file'  => $this->dir_path . '/widgets/mailchimp.php',
                'class' => 'Elements\MailChimp'
            ],
            '200' => [
                'file'  => $this->dir_path . '/widgets/theme-elements/current-time.php',
                'class' => 'Elements\Theme_Elements\Current_Time'
            ],
            '205' => [
                'file'  => $this->dir_path . '/widgets/theme-elements/search.php',
                'class' => 'Elements\Theme_Elements\SearchBox'
            ],
            '210' => [
                'file'  => $this->dir_path . '/widgets/theme-elements/site-title.php',
                'class' => 'Elements\Theme_Elements\SiteTitle'
            ],
            '215' => [
                'file'  => $this->dir_path . '/widgets/theme-elements/menu.php',
                'class' => 'Elements\Theme_Elements\MenuBox'
            ],
            '220' => [
                'file'  => $this->dir_path . '/widgets/theme-elements/logo.php',
                'class' => 'Elements\Theme_Elements\Logo'
            ],
            '225' => [
                'file'  => $this->dir_path . '/widgets/carousel-navigation.php',
                'class' => 'Elements\CarouselNavigation'
            ],
            '230' => [
                'file'  => $this->dir_path . '/widgets/theme-elements/copyright.php',
                'class' => 'Elements\Theme_Elements\Copyright'
            ],
            '235' => [
                'file'  => $this->dir_path . '/widgets/svg.php',
                'class' => 'Elements\Simple__SVG'
            ],
            '240' => [
                'file'  => $this->dir_path . '/widgets/theme-elements/modern-search.php',
                'class' => 'Elements\Theme_Elements\ModernSearch'
            ],
            '245' => [
                'file'  => $this->dir_path . '/widgets/theme-elements/breadcrumbs.php',
                'class' => 'Elements\Theme_Elements\Breadcrumbs'
            ],
            '250' => [
                'file'  => $this->dir_path . '/widgets/modern-button.php',
                'class' => 'Elements\ModernButton'
            ],
            '255' => [
                'file'  => $this->dir_path . '/widgets/responsive-table.php',
                'class' => 'Elements\ResponsiveTable'
            ]
        ];

        if ( class_exists('WooCommerce') ) {
            $widgets['195'] = [
                'file'  => $this->dir_path . '/widgets/theme-elements/shopping-cart.php',
                'class' => 'Elements\Theme_Elements\Shopping_Cart'
            ];
        }

        // sort the widgets by priority number
        ksort( $widgets );

        // making the list of widgets filterable
        $widgets = apply_filters( 'auxin/core_elements/elementor/widgets_list', $widgets, $widgets_manager );

        foreach ( $widgets as $widget ) {
            if( ! empty( $widget['file'] ) && ! empty( $widget['class'] ) ){
                include_once( $widget['file'] );
                if( class_exists( $widget['class'] ) ){
                    $class_name = $widget['class'];
                } elseif( class_exists( __NAMESPACE__ . '\\' . $widget['class'] ) ){
                    $class_name = __NAMESPACE__ . '\\' . $widget['class'];
                } else {
                    auxin_error( sprintf( __('Element class "%s" not found.', 'auxin-elements' ), $class_name ) );
                    continue;
                }
                $widgets_manager->register_widget_type( new $class_name() );
            }
        }
    }

    /**
     * Load Modules
     *
     * Load all auxin elementor modules.
     *
     * @since 1.0.0
     *
     * @access public
     */
    private function load_modules() {

        $modules = array(
            array(
                'file'  => $this->dir_path . '/modules/theme-builder/classes/locations-manager.php',
                'class' => 'Modules\ThemeBuilder\Classes\Locations_Manager',
                'instance' => false
            ),
            array(
                'file'  => $this->dir_path . '/modules/theme-builder/classes/preview-manager.php',
                'class' => 'Modules\ThemeBuilder\Classes\Preview_Manager',
                'instance' => false
            ),
            array(
                'file'  => $this->dir_path . '/modules/theme-builder/module.php',
                'class' => 'Modules\ThemeBuilder\Module'
            ),
            array(
                'file'  => $this->dir_path . '/modules/theme-builder/theme-page-document.php',
                'class' => 'Modules\ThemeBuilder\Theme_Document',
                'instance' => false
            ),
            array(
                'file'  => $this->dir_path . '/modules/query-control/module.php',
                'class' => 'Modules\QueryControl\Module'
            ),
            array(
                'file'  => $this->dir_path . '/modules/common.php',
                'class' => 'Modules\Common'
            ),
            array(
                'file'  => $this->dir_path . '/modules/section.php',
                'class' => 'Modules\Section'
            ),
            array(
                'file'  => $this->dir_path . '/modules/column.php',
                'class' => 'Modules\Column'
            ),
            array(
                'file'  => $this->dir_path . '/modules/documents/header.php',
                'class' => 'Modules\Documents\Header'
            ),
            array(
                'file'  => $this->dir_path . '/modules/documents/footer.php',
                'class' => 'Modules\Documents\Footer'
            ),
            array(
                'file'  => $this->dir_path . '/modules/templates-types-manager.php',
                'class' => 'Modules\Templates_Types_Manager'
            )
        );

        if( is_admin() ){
            $modules[] = [
                'file'  => $this->dir_path . '/modules/settings/base/manager.php',
                'class' => 'Settings\Base\Manager',
                'instance' => false
            ];
            $modules[] = [
                'file'  => $this->dir_path . '/modules/settings/general/manager.php',
                'class' => 'Settings\General\Manager'
            ];
            $modules[] = [
                'file'  => $this->dir_path . '/modules/settings/page/manager.php',
                'class' => 'Settings\Page\Manager'
            ];
        }

        foreach ( $modules as $module ) {
            if( ! empty( $module['file'] ) && ! empty( $module['class'] ) ){
                include_once( $module['file'] );

                if( isset( $module['instance'] ) ) {
                    continue;
                }

                if( class_exists( __NAMESPACE__ . '\\' . $module['class'] ) ){
                    $class_name = __NAMESPACE__ . '\\' . $module['class'];
                } else {
                    auxin_error( sprintf( __('Module class "%s" not found.', 'auxin-elements' ), $class_name ) );
                    continue;
                }
                new $class_name();
            }
        }
    }


    /**
     * Register controls
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function register_controls( $controls_manager ) {

        $controls = array(
            'aux-visual-select' => array(
                'file'  => $this->dir_path . '/controls/visual-select.php',
                'class' => 'Controls\Control_Visual_Select',
                'type'  => 'single'
            ),
            'aux-media' => array(
                'file'  => $this->dir_path . '/controls/media-select.php',
                'class' => 'Controls\Control_Media_Select',
                'type'  => 'single'
            ),
            'aux-icon' => array(
                'file'  => $this->dir_path . '/controls/icon-select.php',
                'class' => 'Controls\Control_Icon_Select',
                'type'  => 'single'
            ),
            'aux-featured-color' => array(
                'file'  => $this->dir_path . '/controls/featured-color.php',
                'class' => 'Controls\Control_Featured_Color',
                'type'  => 'single'
            )
        );

        foreach ( $controls as $control_type => $control_info ) {
            if( ! empty( $control_info['file'] ) && ! empty( $control_info['class'] ) ){
                include_once( $control_info['file'] );

                if( class_exists( $control_info['class'] ) ){
                    $class_name = $control_info['class'];
                } elseif( class_exists( __NAMESPACE__ . '\\' . $control_info['class'] ) ){
                    $class_name = __NAMESPACE__ . '\\' . $control_info['class'];
                }

                if( $control_info['type'] === 'group' ){
                    $controls_manager->add_group_control( $control_type, new $class_name() );
                } else {
                    $controls_manager->register_control( $control_type, new $class_name() );
                }

            }
        }
    }

    /**
     * Register categories
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function register_categories( $categories_manager ) {

        $categories_manager->add_category(
            'auxin-core',
            array(
                'title' => sprintf( __( '%s - General', 'auxin-elements' ), '<strong>'. THEME_NAME_I18N .'</strong>' ),
                'icon' => 'eicon-font',
            )
        );

        $categories_manager->add_category(
            'auxin-pro',
            array(
                'title' => sprintf( __( '%s - Featured', 'auxin-elements' ), '<strong>'. THEME_NAME_I18N .'</strong>' ),
                'icon' => 'eicon-font',
            )
        );

        $categories_manager->add_category(
            'auxin-dynamic',
            array(
                'title' => sprintf( __( '%s - Posts', 'auxin-elements' ), '<strong>'. THEME_NAME_I18N .'</strong>' ),
                'icon' => 'eicon-font',
            )
        );

        $categories_manager->add_category(
            'auxin-portfolio',
            array(
                'title' => sprintf( __( '%s - Portfolio', 'auxin-elements' ), '<strong>'. THEME_NAME_I18N .'</strong>' ),
                'icon' => 'eicon-font',
            )
        );

    }

    /**
     * Enqueue styles.
     *
     * Enqueue all the frontend styles.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function widget_styles() {
        // Add auxin custom styles
        wp_enqueue_style( 'auxin-elementor-widgets' , AUXELS_ADMIN_URL . '/assets/css/elementor-widgets.css' );
        wp_enqueue_style( 'wp-mediaelement' );

    }

    /**
     * Enqueue scripts.
     *
     * Enqueue all the frontend scripts.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function widget_scripts() {
        $dependencies = array('jquery', 'auxin-plugins');

        if( defined('MSWP_AVERTA_VERSION') ){
            $dependencies[] = 'masterslider-core';
        }
        wp_enqueue_script( 'auxin-elementor-widgets' , AUXELS_ADMIN_URL . '/assets/js/elementor/widgets.js' , $dependencies, AUXELS_VERSION, TRUE );
        wp_enqueue_script('wp-mediaelement');
    }

    /**
     * Enqueue scripts.
     *
     * Enqueue all the backend scripts.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function editor_scripts() {
        // Auxin Icons
        wp_register_style( 'auxin-front-icon' , THEME_URL . 'css/auxin-icon.css', null, AUXELS_VERSION );
        // Elementor Custom Style
        wp_register_style(  'auxin-elementor-editor', AUXELS_ADMIN_URL . '/assets/css/elementor-editor.css', array(), AUXELS_VERSION );
        // Elementor Custom Scripts
        wp_register_script( 'auxin-elementor-editor', AUXELS_ADMIN_URL . '/assets/js/elementor/editor.js', array( 'jquery-elementor-select2' ), AUXELS_VERSION );
    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required Elementor version.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function admin_notice_minimum_elementor_version() {

        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

        $message = sprintf(
          esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'auxin-elements' ),
          '<strong>' . esc_html__( 'Phlox Core Elements', 'auxin-elements' ) . '</strong>',
          '<strong>' . esc_html__( 'Elementor', 'auxin-elements' ) . '</strong>',
           self::MINIMUM_ELEMENTOR_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required PHP version.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function admin_notice_minimum_php_version() {

        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

        $message = sprintf(
          /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
          esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'auxin-elements' ),
          '<strong>' . esc_html__( 'Phlox Core Elements', 'auxin-elements' ) . '</strong>',
          '<strong>' . esc_html__( 'PHP', 'auxin-elements' ) . '</strong>',
           self::MINIMUM_PHP_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    /**
     * Set options on auxin load
     *
     * Change The Default Settings of Elementor.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function auxin_admin_loaded() {

        if( false !== auxin_get_transient( 'auxin_has_checked_to_disable_default_elementor_colors_fonts' ) ){
            return;
        }

        if( ! function_exists( 'auxin_get_options' ) ){
            return;
        }

        // If it's a fresh installation
        if( ! auxin_get_options() ){
            update_option( 'elementor_disable_color_schemes', 'yes' );
            update_option( 'elementor_disable_typography_schemes', 'yes' );
            update_option( 'elementor_page_title_selector', '.page-title' );
            update_option( 'elementor_allow_svg', '1' );
        }

        auxin_set_transient( 'auxin_has_checked_to_disable_default_elementor_colors_fonts', THEME_VERSION, 3 * YEAR_IN_SECONDS );
    }

    /**
     * Add Auxin Icons to elementor icons pack
     *
     * @param array $tabs  Icon library tabs
     * @return void
     */
    public function add_auxin_font_icons( $tabs = [] ) {

        // Phlox Icon set 1
        $icons_list = array();
        $icons = Auxin()->Font_Icons->get_icons_list( 'fontastic' );

        if( is_array( $icons ) ){
            foreach ( $icons as $icon ) {
                $icons_list[] = str_replace( '.auxicon-', '', $icon->classname );
            }
        }

        $tabs['auxicon'] = [
            'name' => 'auxin-front-icon',
            'label' => __( 'Phlox Icons - Set 1', 'auxin-elements' ),
            'url' => THEME_URL . 'css/auxin-icon.css',
            'enqueue' => [ THEME_URL . 'css/auxin-icon.css' ],
            'prefix' => 'auxicon-',
            'displayPrefix' => 'auxicon',
            'labelIcon' => 'auxicon-sun',
            'ver' => '1.0.0',
            'icons' => $icons_list
        ];

        // Phlox Icon set 2
        $icons_list2 = array();
        $icons2 = Auxin()->Font_Icons->get_icons_list( 'auxicon2' );

        if( is_array( $icons2 ) ){
            foreach ( $icons2 as $icon ) {
                $icons_list2[] = str_replace( '.auxicon2-', '', $icon->classname );
            }
        }

        $tabs['auxicon2'] = [
            'name' => 'auxin-front-icon2',
            'label' => __( 'Phlox Icons - Set 2', 'auxin-elements' ),
            'url' => THEME_URL . 'css/auxin-icon.css',
            'enqueue' => [ THEME_URL . 'css/auxin-icon.css' ],
            'prefix' => 'auxicon2-',
            'displayPrefix' => 'auxicon2',
            'labelIcon' => 'auxicon-sun',
            'ver' => '1.0.0',
            'icons' => $icons_list2
        ];

        return $tabs;
    }

    /**
	 * Clear cache.
	 *
	 * Delete all meta containing files data. And delete the actual
	 * files from the upload directory.
	 *
	 */
    public function clear_cache(){
        // \Elementor\Plugin::instance()->files_manager->clear_cache();
    }

    public function additional_fonts( $fonts ) {
        $fonts['Space Grotesk'] = 'googlefonts';
		return $fonts;
	}
}

Elements::instance();
