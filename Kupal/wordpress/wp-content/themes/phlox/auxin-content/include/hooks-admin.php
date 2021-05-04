<?php
/**
 * Pointers (Tooltips) to introduce new theme features or display notifications in admin area
 *
 * 
 * @package    Auxin
 * @author     averta (c) 2014-2021
 * @link       http://averta.net
 */


/*-----------------------------------------------------------------------------------*/
/*  Install theme recommended plugins
/*-----------------------------------------------------------------------------------*/


add_action( 'tgmpa_register', 'auxin_theme_register_recommended_plugins' );

/**
 * Register the required plugins for this theme.
 *
 * This function is hooked into `tgmpa_register`, which is fired on the WP `init` action on priority 10.
 */
function auxin_theme_register_recommended_plugins() {
    /*
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(
        array(
            'name'      => __('Phlox Core Elements', 'phlox'),
            'slug'      => 'auxin-elements',
            'required'  => false,
            'categories'=> array('auxin', 'essential')
        ),

        array(
            'name'      => __('Phlox Portfolio', 'phlox'),
            'slug'      => 'auxin-portfolio',
            'required'  => false,
            'categories'=> array('auxin', 'essential')
        ),

        array(
            'name'      => __('Elementor', 'phlox'),
            'slug'      => 'elementor',
            'required'  => false,
            'categories'=> array('essential', 'pagebuilder')
        ),

        array(
            'name'      => __('WooCommerce', 'phlox'),
            'slug'      => 'woocommerce',
            'required'  => false,
            'categories'=> array('essential', 'e-commerce')
        ),


        array(
            'name'       => __('Page Builder', 'phlox'),
            'slug'       => 'siteorigin-panels',
            'required'   => false,
            'categories' => array('recommended', 'pagebuilder')
        ),

        array(
            'name'       => __('Page Builder Widgets Bundle', 'phlox'),
            'slug'       => 'so-widgets-bundle',
            'required'   => false,
            'categories' => array('recommended', 'pagebuilder')
        ),

        array(
            'name'       => __('Instagram Feed', 'phlox'),
            'slug'       => 'instagram-feed',
            'required'   => false,
            'categories' => array('recommended', 'social')
        ),

        array(
            'name'       => __('WordPress SEO', 'phlox'),
            'slug'       => 'wordpress-seo',
            'required'   => false,
            'categories' => array('recommended', 'optimization')
        ),

        array(
            'name'       => __('Recent Tweets Widget', 'phlox'),
            'slug'       => 'recent-tweets-widget',
            'required'   => false,
            'categories' => array('recommended', 'social')
        ),

        array(
            'name'       => __('Contact Form 7', 'phlox'),
            'slug'       => 'contact-form-7',
            'required'   => false,
            'categories' => array('recommended')
        ),

        array(
            'name'       => __('Dokan - WooCommerce Multivendor Marketplace ', 'phlox'),
            'slug'       => 'dokan-lite',
            'required'   => false,
            'categories' => array('recommended')
        ),

        array(
            'name'       => __('Variation Swatches for WooCommerce', 'phlox'),
            'slug'       => 'variation-swatches-for-woocommerce',
            'required'   => false,
            'categories' => array('recommended')
        ),

        array(
            'name'       => __('WordPress Importer', 'phlox'),
            'slug'       => 'wordpress-importer',
            'required'   => false,
            'thumbnail'  => 'default',
            'categories' => array('recommended')
        ),

        array(
            'name'       => __('Related Posts for WordPress', 'phlox'),
            'slug'       => 'related-posts-for-wp',
            'required'   => false,
            'categories' => array('recommended')
        ),

        array(
            'name'       => __('WP ULike', 'phlox'),
            'slug'       => 'wp-ulike',
            'required'   => false,
            'categories' => array('recommended')
        ),

        array(
            'name'       => __('Autoptimize', 'phlox'),
            'slug'       => 'autoptimize',
            'required'   => false,
            'categories' => array('recommended', 'optimization')
        ),

        array(
            'name'       => __('Custom Facebook Feed', 'phlox'),
            'slug'       => 'custom-facebook-feed',
            'required'   => false,
            'thumbnail'  => 'default',
            'categories' => array('recommended', 'social')
        ),

        array(
            'name'       => __('Flickr Justified Gallery', 'phlox'),
            'slug'       => 'flickr-justified-gallery',
            'required'   => false,
            'thumbnail'  => 'default',
            'categories' => array('recommended', 'social')
        ),

        array(
            'name'       => __('Image Optimization', 'phlox'),
            'slug'       => 'wp-smushit',
            'required'   => false,
            'thumbnail'  => 'default',
            'categories' => array('recommended', 'optimization')
        ),

        array(
            'name'       => __('Export/Import Theme Options', 'phlox'),
            'slug'       => 'customizer-export-import',
            'required'   => false,
            'thumbnail'  => 'default',
            'categories' => array('recommended')
        ),

        array(
            'name'       => __('Popular Posts', 'phlox'),
            'slug'       => 'wordpress-popular-posts',
            'required'   => false,
            'categories' => array('recommended')
        ),

        array(
            'name'       => __('Visual CSS Style Editor', 'phlox'),
            'slug'       => 'yellow-pencil-visual-theme-customizer',
            'required'   => false,
            'categories' => array('recommended')
        ),

        array(
            'name'       => __('EU Cookie Notce', 'phlox'),
            'slug'       => 'cookie-notice',
            'required'   => false,
            'categories' => array('recommended')
        ),

        array(
            'name'       => __('MailChimp for WordPress', 'phlox'),
            'slug'       => 'mailchimp-for-wp',
            'required'   => false,
            'categories' => array('recommended')
        ),

        array(
            'name'       => __('Widgets for SiteOrigin', 'phlox'),
            'slug'       => 'widgets-for-siteorigin',
            'required'   => false,
            'categories' => array('recommended', 'pagebuilder')
        ),

        array(
            'name'       => __('Real-time Bitcoin Converter', 'phlox'),
            'slug'       => 'real-time-bitcoin-currency-converter',
            'required'   => false,
            'categories' => array('recommended')
        ),

        array(
            'name'       => __('Custom Sidebars', 'phlox'),
            'slug'       => 'custom-sidebars',
            'required'   => false,
            'categories' => array('recommended')
        ),

        array(
            'name'       => __('Simple Taxonomy Ordering', 'phlox'),
            'slug'       => 'simple-taxonomy-ordering',
            'required'   => false,
            'categories' => array('recommended')
        )
    );

    // Add master slider as requirement if none of masterslider versions is installed
    if( ! ( defined( 'MSWP_SLUG' ) && 'masterslider' == MSWP_SLUG ) ){
        $master = array(
            array(
                'name'       => __('MasterSlider by averta', 'phlox'),
                'slug'       => 'master-slider',
                'required'   => false,
                'categories' => array('essential', 'slider')
            )
        );
        array_splice( $plugins, 2, 0, $master );
    }

    /*
     * Array of configuration settings. Amend each line as needed.
     *
     * TGMPA will start providing localized text strings soon. If you already have translations of our standard
     * strings available, please help us make TGMPA even better by giving us access to these translations or by
     * sending in a pull-request with .po file(s) with the translations.
     *
     * Only uncomment the strings in the config array if you want to customize the strings.
     */
    $config = array(
        'id'           => 'phlox',            // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => get_template_directory() . "/auxin-content/embeds/plugins/",                      // Default absolute path to bundled plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'has_notices'  => false,                   // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false,                   // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.

        'strings'      => array(
            'page_title'                      => __( 'Install Recommended Plugins', 'phlox' ),
            'menu_title'                      => __( 'Install Plugins', 'phlox' )
        )
    );

    tgmpa( $plugins, $config );
}

function auxin_define_plugins_categories_localized( $plugin_categories ){
    $extra = array(
        //'auxin'          => __( 'Exclusive', 'phlox' ),
        //'pagebuilder'    => __( 'Page Builder', 'phlox' ),
        'essential'      => __( 'Essentials', 'phlox' ),
        'bundled'        => __( 'Bundled', 'phlox' ),
        'recommended'    => __( 'Recommended', 'phlox' ),
        'visual-builder' => __( 'Visual Builder', 'phlox' ),
        'e-commerce'     => __( 'E-Commerce', 'phlox' ),
        'blog'           => __( 'Blog', 'phlox' ),
        'social'         => __( 'Social', 'phlox' ),
        'optimization'   => __( 'Optimization', 'phlox' )
    );

    return array_merge( $plugin_categories, $extra );
}
add_filter( 'auxin_admin_welcome_plugins_categories_localized', 'auxin_define_plugins_categories_localized' );

/*-----------------------------------------------------------------------------------*/
/*  Adds dashboard tab in theme about (welcome) page
/*-----------------------------------------------------------------------------------*/

function auxin_welcome_page_display_section_dashboard(){

    ?>
    <section class="aux-col-wrap aux-big-banners-row">
        <div class="aux-col2">
            <div class="aux-big-banner aux-importer-banner">
                <h3><?php _e( "Import Demo", 'phlox' ); ?></h3>
                <p><?php _e( "Clone a demo site in few clicks", 'phlox' ); ?></p>
                <a class="aux-wl-button aux-round aux-large aux-black" href="<?php echo auxin_welcome_page()->get_tab_link('importer'); ?>"><?php _e( "Run Importer", 'phlox' ); ?></a>
            </div>
        </div>
        <div class="aux-col2">
            <div class="aux-big-banner aux-customize-banner">
                <h3><?php echo wp_sprintf( __( "Customize %s", 'phlox' ), THEME_NAME_I18N ) ?></h3>
                <p><?php _e( "Customize any part of your website.", 'phlox' ); ?></p>
                <a class="aux-wl-button aux-round aux-large aux-black" href="<?php echo self_admin_url( 'customize.php' ); ?>"><?php _e( "Customize", 'phlox' ); ?></a>
            </div>
        </div>
    </section>
    <?php
    $support_link  = 'http://help.averta.net/?utm_source=phlox-welcome&utm_medium=phlox-free&utm_content=submit-ticket&utm_term=support';

    $doc_link   = 'https://docs.phlox.pro/';
    $doc_link  .= '?utm_source=phlox-welcome&utm_medium=phlox-free&utm_content=dashboard-doc&utm_term=documentation';
    ?>
    <section class="aux-col-wrap aux-info-blocks-row">
        <div class="aux-col3">
            <div class="aux-info-block aux-info-block-support">
                <img class="welcome-icon" src="<?php echo esc_url( AUXIN_URL ) . 'css/images/welcome/support.svg'; ?>">
                <h4><?php _e( "Need Some Help?", 'phlox' ); ?></h4>
                <p><?php _e( "We would love to be of any assistance.", 'phlox' ); ?></p>
                <div><a class="aux-wl-button aux-round aux-large aux-green" href="<?php echo esc_url( $support_link ); ?>" target="_blank"><?php _e( "Send Ticket", 'phlox' ); ?></a></div>
            </div>
        </div>
        <div class="aux-col3">
            <div class="aux-info-block aux-info-block-documentation">
                <img class="welcome-icon" src="<?php echo esc_url( AUXIN_URL ) . 'css/images/welcome/documentation.svg'; ?>">
                <h4><?php _e( "Documentation", 'phlox' ); ?></h4>
                <p><?php echo wp_sprintf( __( "Learn about any aspect of %s Theme.", 'phlox' ), THEME_NAME_I18N ) ?></p>
                <div><a class="aux-wl-button aux-round aux-large aux-orange" href="<?php echo esc_url( $doc_link ); ?>" target="_blank"><?php _e( "Start Reading", 'phlox' ); ?></a></div>
            </div>
        </div>
        <div class="aux-col3">
            <div class="aux-info-block aux-info-block-subscribe">
                <img class="welcome-icon" src="<?php echo esc_url( AUXIN_URL ) . 'css/images/welcome/subscription.svg'; ?>">
                <h4><?php _e( "Subscription", 'phlox' ); ?></h4>
                <p><?php _e( "Get the latest changes in your inbox.", 'phlox' ); ?></p>
                <div><a class="aux-wl-button aux-round aux-large aux-black disabled" href="#"><?php _e( "Coming soon", 'phlox' ); ?></a></div>
            </div>
        </div>
    </section>
    <?php
}

function auxin_welcome_add_section_dashboard( $sections ){

    $sections['dashboard'] = array(
        'label'          => __( 'Dashboard', 'phlox' ),
        'description'    => '',
        'callback'       => 'auxin_welcome_page_display_section_dashboard',
        'add_admin_menu' => true
    );

    return $sections;
}

add_filter( 'auxin_admin_welcome_sections', 'auxin_welcome_add_section_dashboard', 20 );

/*-----------------------------------------------------------------------------------*/
/*  Adds customize tab in theme about (welcome) page
/*-----------------------------------------------------------------------------------*/

function auxin_welcome_add_section_customize( $sections ){

    $sections['customize'] = array(
        'label'            => esc_html__( 'Customization', 'phlox' ),
        'description'      => '',
        'url'              => self_admin_url( 'customize.php' ),
        'add_admin_menu'   => true
    );

    return $sections;
}

add_filter( 'auxin_admin_welcome_sections', 'auxin_welcome_add_section_customize', 60 );

/*-----------------------------------------------------------------------------------*/
/*  Get and inject generate styles in content of custom css file
/*-----------------------------------------------------------------------------------*/

/**
 * Get generated styles by option panel
 *
 * @return string    return generated styles
 */
function auxin_add_option_styles( $css ){

    $sorted_sections = Auxin_Option::api()->data->sorted_sections;
    $sorted_fields   = Auxin_Option::api()->data->sorted_fields;


    foreach ( $sorted_fields as $section_id => $fields ) {
        foreach ( $fields as $field_id => $field ) {
            if( isset( $field['style_callback'] ) && ! empty( $field['style_callback'] ) ){
                $css[ $field_id ] = call_user_func( $field['style_callback'], null );
            } elseif( ! empty( $field['selectors'] ) ){
                $selectors = '';
                // convert the selector to string
                if( ! empty( $field['selectors'] ) ){
                    if( is_array( $field['selectors'] ) ){
                        foreach ( $field['selectors'] as $property => $property_value ) {
                            // if just array value is set
                            if( is_numeric( $property ) ){
                                $selectors .= $property_value . ',';
                            } else {
                                $selectors .= $property . ' {'. $property_value .'} ';
                            }
                        }
                    } else {
                        $selectors = $field['selectors'];
                    }
                }

                $selectors = trim( $selectors, ', ' );

                if( '' !== $replacement_value = auxin_get_option( $field_id, '' ) ){

                    $replacement_value = trim( $replacement_value, ', ' );

                    if( ( '{' === $replacement_value[0] ) && ( $parsed_json = json_decode($replacement_value) ) && (json_last_error() == JSON_ERROR_NONE) ){
                        $css_generator = new Auxin_CSS_Generator_Option_Manager();
                        $placeholder = ! empty( $field['placeholder'] ) ? $field['placeholder'] : '';
                        $css[ $field_id ] = $css_generator->get_css( $replacement_value, $selectors , $placeholder );
                        Auxin_Fonts::get_instance()->parse_typography();
                    } else {
                        // append placeholder if is not included in selector
                        if( false === strpos( $selectors, '{' ) && ! empty( $field['placeholder'] ) ){
                            $selectors .= ' { ' . $field['placeholder'] . ' }';
                        }
                        $css[ $field_id ] = str_replace( "{{VALUE}}" , $replacement_value, $selectors );
                    }
                } else {
                    unset( $css[ $field_id ] );
                }
            } else {
                unset( $css[ $field_id ] );
            }
        }
    }

    return $css;
}

add_filter( 'auxin_custom_css_file_content', 'auxin_add_option_styles' );

/*-----------------------------------------------------------------------------------*/
/*  Adds support tab in theme about (welcome) page
/*-----------------------------------------------------------------------------------*/

function auxin_welcome_page_display_section_tutorials(){
    $support_link = 'http://help.averta.net/?utm_source=phlox-welcome&utm_medium=phlox-free&utm_content=tuts-forum&utm_term=support';

    $doc_link   = 'https://docs.phlox.pro/';
    $doc_link  .= '?utm_source=phlox-welcome&utm_medium=phlox-free&utm_content=tuts-doc&utm_term=documentation';
    ?>
    <div class="feature-section aux-welcome-page-tutorials">
        <div class="aux-section-content-box aux-clearfix">
            <a href="https://www.youtube.com/playlist?list=PL7X-1Jmy1jcdekHe6adxB81SBcrHOmLRS" target="_blank" title="<?php _e( 'Play all video tutorials' ,'phlox' ); ?>"><img width="111" class="aux-tutts-info-icon" src="<?php echo esc_url( AUXIN_URL ) . 'css/images/welcome/video-tuts.svg'; ?>" /></a>
            <div class="aux-tutts-info-title-wrap">
                <h3 class="aux-content-title"><?php _e('Video Tutorials', 'phlox' ); ?></h3>
                <p class="aux-content-subtitle"><?php printf( __('Take your skills with %s to the next level!', 'phlox' ), '<strong>' . THEME_NAME_I18N . '</strong>' ); ?></p>
            </div>
            <a class="aux-tutts-info-doc-wrap" href="<?php echo esc_url( $doc_link ); ?>" target="_blank">
                <img width="69" class="tuts-icon" src="<?php echo esc_url( AUXIN_URL ) . 'css/images/welcome/documentation.svg'; ?>">
                <h3 class="aux-content-title"><?php _e('Documentation', 'phlox' ); ?></h3>
                <span class="aux-text-link"><?php _e('Check out', 'phlox' ); ?></span>
            </a>
            <a class="aux-tutts-info-support-wrap" href="<?php echo esc_url( $support_link ); ?>" target="_blank">
                <img width="75" class="tuts-icon" src="<?php echo esc_url( AUXIN_URL ) . 'css/images/welcome/support.svg'; ?>">
                <h3 class="aux-content-title"><?php _e('Support Center', 'phlox' ); ?></h3>
                <span class="aux-text-link"><?php _e('Check out', 'phlox' ); ?></span>
            </a>
        </div>
    </div>
    <?php
}

function auxin_welcome_add_section_support( $sections ){

    $sections['help'] = array(
        'label'          => __( 'Tutorials', 'phlox' ),
        'description'    => '',
        'callback'       => 'auxin_welcome_page_display_section_tutorials',
        'add_admin_menu' => true
    );

    return $sections;
}

add_filter( 'auxin_admin_welcome_sections', 'auxin_welcome_add_section_support', 80 );

/*-----------------------------------------------------------------------------------*/
/*  Adds importer tab in theme about (welcome) page
/*-----------------------------------------------------------------------------------*/

function auxin_welcome_page_display_section_import(){
    ?>
    <div class="aux-welcome-page-import">
        <div class="aux-section-content-box">
            <h3 class="aux-content-title"><?php _e('Please install "Phlox Core Elements" plugin to enable this feature.', 'phlox' ); ?></h3>
        </div>
    </div>
    <?php
}

function auxin_welcome_add_section_importer( $sections ){

    $sections['importer'] = array(
        'label'       => esc_html__( 'Demo Importer', 'phlox' ),
        'description' => '',
        'callback'    => 'auxin_welcome_page_display_section_import'
    );

    return $sections;
}
add_filter( 'auxin_admin_welcome_sections', 'auxin_welcome_add_section_importer', 60 );

/*-----------------------------------------------------------------------------------*/
/*  Turn off the automatic update check for Master Slider Pro
/*-----------------------------------------------------------------------------------*/

add_filter( 'masterslider_disable_auto_update', '__return_true' );

/*-----------------------------------------------------------------------------------*/
/*  Check theme requirements and throw a notice if the requirements are not met
/*-----------------------------------------------------------------------------------*/

if( version_compare( PHP_VERSION, '5.4.0', '<') || version_compare( $GLOBALS['wp_version'], '4.7-alpha', '<' ) ){
    add_action( 'admin_notices', 'auxin_theme_requirements_notice' );
}

/**
 * Adds a message for theme requirements.
 *
 * @global string $wp_version WordPress version.
 */
function auxin_theme_requirements_notice() {
    $message = sprintf( __( 'This theme requires at least WordPress version 4.7 and PHP 5.4. You are running WordPress version %s and PHP version %s. Please upgrade and try again.', 'phlox' ), $GLOBALS['wp_version'], PHP_VERSION );
    printf( '<div class="error"><p>%s</p></div>', $message );
}

/**
 * Update the deprecated option IDs
 *
 */
function auxin_update_deprecated_theme_options(){

    $option_fileds = Auxin_option::api()->data->fields;
    $auxin_array_options = get_option( THEME_ID.'_theme_options' , array() );

    foreach ( $option_fileds as $option_filed ) {
        if( ! empty( $option_filed['id_deprecated'] ) ){
            if( ! isset( $auxin_array_options[ $option_filed['id'] ] ) ){
                if( isset( $auxin_array_options[ $option_filed['id_deprecated'] ] ) ){
                    auxin_update_option( $option_filed['id'], $auxin_array_options[ $option_filed['id_deprecated'] ] );
                }
            }
        }
    }

}
add_action( 'auxin_theme_updated', 'auxin_update_deprecated_theme_options' );


/**
 * Maybe update the header and footer legacy options based on deprecated options
 *
 * @deprecated version 2.5.0
 *
 * @return void
 */
function auxin_maybe_update_elementor_use_legacy_options() {

    if( ! class_exists( '\Elementor\Plugin' ) || ! class_exists( 'AUXELS' ) ){
        return;
    }

    if( false !== auxin_get_transient( 'auxin_has_use_legacy_header_footer_ported' ) ){
        return;
    }

    if ( ! is_null( auxin_get_option( 'site_header_use_legacy', null ) ) && ! is_null( auxin_get_option( 'site_footer_use_legacy', null ) ) ) {
        return;
    }

    $has_saved_auxin_option = auxin_get_options();

    $site_header_override_template = auxin_get_option( 'site_header_override_template', null );
    $site_footer_override_template = auxin_get_option( 'site_footer_override_template', null );

    /* Port use header legacy option ----------- */

    // Turn on the Legacy mode if the override header template option was disabled before

    // If override template option was not found
    if( is_null( $site_header_override_template ) ){
        // Turn on legacy if it is not clean install
        if( $has_saved_auxin_option ){
            auxin_update_option( 'site_header_use_legacy', true );
        }
    } else if( ! auxin_is_true( $site_header_override_template ) ){
        auxin_update_option( 'site_header_use_legacy', true );
    }

    /* Port use footer legacy option ----------- */

    // Turn on the Legacy mode if the override footer template option was disabled before

    // If override template option was not found
    if( is_null( $site_footer_override_template ) ){
        // Turn on legacy if it is not clean install
        if( $has_saved_auxin_option ){
            auxin_update_option( 'site_footer_use_legacy', true );
        }
    } else if( ! auxin_is_true( $site_footer_override_template ) ){
        auxin_update_option( 'site_footer_use_legacy', true );
    }

    /* Port header sticky option -------------- */

    // Turn off the sticky mode if the override header template option was enabled before
    if( is_null( $site_header_override_template ) || auxin_is_true( $site_header_override_template ) ){
        auxin_update_option( 'site_header_top_sticky', false );
    }

    auxin_set_transient( 'auxin_has_use_legacy_header_footer_ported', THEME_VERSION, YEAR_IN_SECONDS );
}

add_action( 'admin_init', 'auxin_maybe_update_elementor_use_legacy_options', 9 );


/**
 * Maybe port the deprecated Elementor header template to the new option
 *
 * @deprecated version 2.5.0
 *
 * @return void
 */
function auxin_maybe_port_deprecated_elementor_header_template() {
    /*
     site_elementor_header_template
     site_header_override_template
     site_header_use_legacy
     site_header_override_template
    */

    if( ! class_exists( '\Elementor\Plugin' ) || ! class_exists( 'AUXELS' ) ){
        return;
    }

    if( false !== auxin_get_transient( 'auxin_has_elementor_header_template_ported' ) ){
        return;
    }

    $site_elementor_header_template = auxin_get_option( 'site_elementor_header_template' );

    if( ! empty( $site_elementor_header_template ) && is_numeric( $site_elementor_header_template ) ){
        return;
    }

    $deprecated_option = auxin_get_option( 'site_header_template' );

    if ( ! empty( trim( $deprecated_option ) ) && ! is_numeric( $deprecated_option ) ) {
        $template = get_page_by_path( $deprecated_option, OBJECT, 'elementor_library' );
        if( $template instanceof WP_Post ){
            auxin_update_option( 'site_elementor_header_template', $template->ID );
            Auxin_Demo_Importer::get_instance()->maybe_flush_post( $template->ID );
        }
    }

    auxin_set_transient( 'auxin_has_elementor_header_template_ported', THEME_VERSION, YEAR_IN_SECONDS );
}

add_action( 'admin_init', 'auxin_maybe_port_deprecated_elementor_header_template', 12 );


/**
 * Maybe port the deprecated Elementor footer template to the new option
 *
 * @deprecated version 2.5.0
 *
 * @return void
 */
function auxin_maybe_port_deprecated_elementor_footer_template() {
    /*
     site_elementor_footer_template
     site_footer_override_template
     site_footer_use_legacy
     site_footer_override_template
    */

    if( ! class_exists( '\Elementor\Plugin' ) || ! class_exists( 'AUXELS' ) ){
        return;
    }

    if( false !== auxin_get_transient( 'auxin_has_elementor_footer_template_ported' ) ){
        return;
    }

    $site_elementor_footer_template = auxin_get_option( 'site_elementor_footer_template' );

    if( ! empty( $site_elementor_footer_template ) && is_numeric( $site_elementor_footer_template ) ){
        return;
    }

    $deprecated_option = auxin_get_option( 'site_footer_template' );

    if ( ! empty( trim( $deprecated_option ) ) && ! is_numeric( $deprecated_option ) ) {
        $template = get_page_by_path( $deprecated_option, OBJECT, 'elementor_library' );
        if( $template instanceof WP_Post ){
            auxin_update_option( 'site_elementor_footer_template', $template->ID );
            Auxin_Demo_Importer::get_instance()->maybe_flush_post( $template->ID );
        }
    }

    auxin_set_transient( 'auxin_has_elementor_footer_template_ported', THEME_VERSION, YEAR_IN_SECONDS );
}

add_action( 'admin_init', 'auxin_maybe_port_deprecated_elementor_footer_template', 12 );
