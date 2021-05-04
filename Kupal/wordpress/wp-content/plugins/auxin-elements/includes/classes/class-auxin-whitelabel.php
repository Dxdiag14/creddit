<?php

/**
 * Class Auxin_WhiteLabel
 */
Class Auxin_WhiteLabel {

    public $options;

    /**
     * Instance of this class.
     *
     * @var      object
     */
    protected static $instance = null;

    /**
     * Return an instance of this class.
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Set up the class
     */
    function __construct() {

        if( ! is_admin() || ! defined( 'AUX_WHITELABEL' ) || ! AUX_WHITELABEL  ){
            return;
        }

        add_action( 'init', array( $this, 'update_hooks' ) );
        $this->init();
    }

    public function init(){
        $this->options = get_option( THEME_ID.'_theme_options');
        if( !empty( $this->options['auxin_whitelabel_theme_name'] ) ){
            define( 'THEME_NAME_I18N' , $this->options['auxin_whitelabel_theme_name'] );
        }
    }

    public function update_hooks(){
        add_action( 'admin_menu', function(){
            if( auxin_is_true( auxin_get_option( 'auxin_whitelabel_hide_menu', '0' ) ) ){
                remove_menu_page( 'auxin-welcome' );
            }
        }, 1000);

        add_filter( 'auxin_admin_notices_instances', function( $notice_list ){
            if( auxin_is_true( auxin_get_option( 'auxin_whitelabel_hide_notices', '0' ) ) ){
                return array();
            }
            return $notice_list;
        }, 1000);

        add_filter( 'auxin_display_theme_badge', function( $theme_badge ){
            if( auxin_is_true( auxin_get_option( 'auxin_whitelabel_hide_theme_badge', '0' ) ) ){
                return;
            }
            return $theme_badge;
        }, 1000);

        add_filter( 'wp_prepare_themes_for_js', function( $prepared_themes ){
            $theme_name    = get_option('stylesheet');
            if( isset( $prepared_themes[ $theme_name ] ) && strpos( $theme_name, THEME_ID ) !== false  ){
                if( ! empty( $this->options['auxin_whitelabel_theme_name'] ) ){
                    $prepared_themes[$theme_name]['name'] = $this->options['auxin_whitelabel_theme_name'];
                }
                if( ! empty( $this->options['auxin_whitelabel_theme_author_name'] ) ){
                    $prepared_themes[$theme_name]['author'] = $this->options['auxin_whitelabel_theme_author_name'];
                    $prepared_themes[$theme_name]['authorAndUri'] = sprintf( '<a href="%s">%s</a>', $this->options['auxin_whitelabel_theme_author_url'], $this->options['auxin_whitelabel_theme_author_name'] );
                }
                if( ! empty( $this->options['auxin_whitelabel_theme_description'] ) ){
                    $prepared_themes[$theme_name]['description'] = $this->options['auxin_whitelabel_theme_description'];
                }
                if( ! empty( $this->options['auxin_whitelabel_theme_screenshot'] ) ){
                    $prepared_themes[$theme_name]['screenshot'][0] = wp_get_attachment_url( $this->options['auxin_whitelabel_theme_screenshot'] );
                }
            }
            return $prepared_themes;
        }, 1000);

        add_filter( 'auxin_admin_welcome_sections', function( $sections ){
            if( auxin_is_true( auxin_get_option( 'auxin_whitelabel_hide_dashboard_section', '0' ) ) && isset( $sections['dashboard'] ) ){
                unset( $sections['dashboard'] );
            }
            if( auxin_is_true( auxin_get_option( 'auxin_whitelabel_hide_customization_section', '0' ) ) && isset( $sections['customize'] ) ){
                unset( $sections['customize'] );
            }
            if( auxin_is_true( auxin_get_option( 'auxin_whitelabel_hide_tutorials_section', '0' ) ) && isset( $sections['help'] ) ){
                unset( $sections['help'] );
            }
            if( auxin_is_true( auxin_get_option( 'auxin_whitelabel_hide_demo_importer_section', '0' ) ) && isset( $sections['importer'] ) ){
                unset( $sections['importer'] );
            }
            if( auxin_is_true( auxin_get_option( 'auxin_whitelabel_hide_plugins_section', '0' ) ) && isset( $sections['plugins'] ) ){
                unset( $sections['plugins'] );
            }
            if( auxin_is_true( auxin_get_option( 'auxin_whitelabel_hide_feedback_section', '0' ) ) && isset( $sections['feedback'] ) ){
                unset( $sections['feedback'] );
            }
            if( auxin_is_true( auxin_get_option( 'auxin_whitelabel_hide_template_kits_section', '0' ) ) && isset( $sections['templates'] ) ){
                unset( $sections['templates'] );
            }
            return $sections;
        }, 1000);

    }

}