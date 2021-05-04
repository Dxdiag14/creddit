<?php
/**
 * Class to add permalink setting for post types of theme
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
*/

// no direct access allowed
if ( ! defined('ABSPATH') ) {
    die();
}

 /**
  *
  */
 class Auxin_Permalink {

     public $prefix             = "auxin_permalink";
     public $theme_name         = "averta";
     public $theme_id           = "averta";
     public $option_group       = "permalink";
     public $default_post_types = array();


     function __construct() {

        if( defined('THEME_NAME_I18N') ) $this->theme_name = THEME_NAME_I18N;
        if( defined('THEME_ID'  ) ) $this->theme_id   = THEME_ID;

        $this->default_post_types = auxin_registered_post_types(true );
     }


     /**
      * Register and init with hooks
      *
      * @return void
      */
     public function setup(){
         // setup hooks
         add_action( 'admin_init', array( $this, 'extend_permalinks_page'   ) );
         add_action( 'admin_init', array( $this, 'flush_rewrite_rules_queue') );
         add_action( 'load-options-permalink.php' , array( $this, 'on_permalink_page' ), 15 );
     }


     /**
      * Triggers on permalink setting page
      *
      * @return void
      */
     public function on_permalink_page(){

        if( ! $this->default_post_types ){
            return;
        }

        // add auxin permalink fields section
        $this->add_section();

        foreach ( $this->default_post_types as $post_type ) {

            $this->add_update_hooks( $post_type );
            $this->add_posttype_fields( $post_type );

            // store posted custom permalink slugs
            if( isset( $_POST['submit'] ) && isset( $_POST['_wp_http_referer'] ) ){

                if( strpos( $_POST['_wp_http_referer'],'options-permalink.php' ) !== FALSE ) {
                    $this->store_permalink_options( $post_type );
                }
            }

        }
    }


    /**
     * Store the permalink options for a post type
     *
     * @param  string  $posty_type The post type
     * @return void
     */
    private function store_permalink_options( $post_type ){

        $single_option_name  = $this->get_structure( array( 'post_type' => $post_type, 'page_type' => 'single' ) );

        // get post type structure
        $structure = trim( esc_attr( $_POST[ $single_option_name ] ) );

        // default permalink structure
        if( ! $structure ) $structure = $post_type;

        $structure = trim( $structure, '/' );

        set_theme_mod( $single_option_name, $structure );

        // get post type object if available
        $post_type_object = get_post_type_object( $post_type );

        // if post type has archive enabled
        if( ! empty( $post_type_object ) && $post_type_object->has_archive ){

            $archive_option_name = $this->get_structure( array( 'post_type' => $post_type, 'page_type' => 'archive' ) );

            // get post type structure
            $structure = trim( esc_attr( $_POST[$archive_option_name] ) );

            // default permalink structure
            if( ! $structure ) $structure = $post_type."/all";

            $structure = trim( $structure, '/' );

            set_theme_mod( $archive_option_name, $structure );
        }

        if( $taxonomies = get_object_taxonomies( $post_type, 'objects' ) ){

            foreach ( $taxonomies as $tax => $tax_object ) {

                if( empty( $tax_object->rewrite ) || empty( $tax_object->rewrite['slug'] ) ){
                    continue;
                }

                $tax_option_name = $this->get_structure( array( 'post_type' => $post_type, 'page_type' => $tax ) );

                // get post type structure
                $structure = trim( esc_attr( $_POST[ $tax_option_name ] ) );

                // default permalink structure
                if( ! $structure ) $structure = $tax_object->rewrite['slug'];

                $structure = trim( $structure, '/' );

                set_theme_mod( $tax_option_name, $structure );
            }

        }

    }


    /**
     * Flushes the pending rewrite rules
     *
     */
    public function pending_rewrite_rules( $mod_value ){
        set_theme_mod( $this->prefix."_pending_rewrite_rules", 1 );

        return $mod_value;
    }


    /**
     * Flushes the queue of rewrite rules
     *
     * @return void
     */
    public function flush_rewrite_rules_queue () {
        if( get_theme_mod( $this->prefix."_pending_rewrite_rules", 1 ) ){
            flush_rewrite_rules();
            set_theme_mod( $this->prefix."_pending_rewrite_rules", 0 );
        }
    }


    /**
     * Extends the permalink page by adding new fields for post types
     *
     * @return void
     */
    public function extend_permalinks_page(){
        // Get enabled post types of theme
        $this->set_current_post_types();
        // This method fires for just one time
        $this->set_default_permalink_slugs();
    }


    private function set_current_post_types(){
        $auxin_active_post_types = auxin_get_possible_post_types(true);
        $this->default_post_types = array_keys( $auxin_active_post_types );
    }


    /**
     * Adds new section in permalink page
     */
    private function add_section(){

        add_settings_section(
            'auxin_posttypes_permalink_setting_section',
            sprintf( '<hr /><br />'.__( '%s Permalink Setting', 'auxin-elements' ), $this->theme_name ),
            array( $this, 'posttypes_permalink_section_callback_function' ),
            $this->option_group
        );
    }



    /**
     * Sets the default permalink slugs
     *
     */
    private function set_default_permalink_slugs(){

        if( get_theme_mod( $this->prefix.'_permalink_options_initialized', 0 ) )
            return;


        foreach ( $this->default_post_types as $post_type ) {

            $single_option_name  = $this->get_structure( array( 'post_type' => $post_type, 'page_type' => 'single' ) );

            // get post type structure
            $structure = get_theme_mod( $single_option_name, '' );

            // default permalink structure
            if( ! $structure ) {
                $structure = ( strpos( $post_type, 'aux_' ) !== FALSE ) ? trim( $post_type, 'aux_' ) : $post_type;
            }

            $structure = trim( $structure, '/' );
            set_theme_mod( $single_option_name, $structure );

            // get post type object if available
            $post_type_object = get_post_type_object( $post_type );

            // if post type has archive enabled
            if( ! empty( $post_type_object ) && $post_type_object->has_archive ){

                $archive_option_name = $this->get_structure( array( 'post_type' => $post_type, 'page_type' => 'archive' ) );

                // get post type structure
                $structure = get_theme_mod( $archive_option_name, '' );

                // default permalink structure
                if( ! $structure ) {
                    $structure  = ( strpos( $post_type, 'aux_' ) !== FALSE ) ? trim( $post_type, 'aux_' ) : $post_type;
                    $structure .= '/all';
                }

                $structure = trim( $structure, '/' );
                set_theme_mod( $archive_option_name, $structure );
            }

            if( $taxonomies = get_object_taxonomies( $post_type, 'objects' ) ){

                foreach ( $taxonomies as $tax => $tax_object ) {

                    if( empty( $tax_object->rewrite ) || empty( $tax_object->rewrite['slug'] ) ){
                        continue;
                    }

                    $tax_option_name = $this->get_structure( array( 'post_type' => $post_type, 'page_type' => $tax ) );

                    // get post type structure
                    $structure = get_theme_mod( $tax_option_name, '' );

                    // default permalink structure
                    if( ! $structure ) {
                        $structure = $tax_object->rewrite['slug'];
                    }

                    $structure = trim( $structure, '/' );
                    set_theme_mod( $tax_option_name, $structure );
                }

            }

        }

        set_theme_mod( $this->prefix.'_permalink_options_initialized', 1 );
    }


    /**
     * Flushes the queued rewrite rules
     *
     * @param string $post_type   The post type name
     */
    public function add_update_hooks( $post_type ){
        $hook_suffix = $this->get_structure( array( 'post_type' => $post_type, 'page_type' => 'single' ) );
        add_filter( 'pre_set_theme_mod_'. $hook_suffix , array( $this, 'pending_rewrite_rules' ), 10, 2 );

        if( $this->post_type_has_archive( $post_type ) ){
            $hook_suffix = $this->get_structure( array( 'post_type' => $post_type, 'page_type' => 'archive' ) );
            add_filter( 'pre_set_theme_mod_'. $hook_suffix , array( $this, 'pending_rewrite_rules' ), 10, 2 );
        }

        if( $taxonomies = get_object_taxonomies( $post_type, 'objects' ) ){
            foreach ( $taxonomies as $tax => $tax_object ) {
                if( empty( $tax_object->rewrite ) || empty( $tax_object->rewrite['slug'] ) ){
                    continue;
                }
                $hook_suffix = $this->get_structure( array( 'post_type' => $post_type, 'page_type' => $tax ) );
                add_filter( 'pre_set_theme_mod_'. $hook_suffix , array( $this, 'pending_rewrite_rules' ), 10, 2 );
            }
        }
    }



    private function add_posttype_fields( $post_type ){

        $post_type_label = $post_type;

        if( $post_object = get_post_type_object( $post_type ) ){
            $post_type_label = $post_object->labels->singular_name;
        }

        add_settings_field( 'auxin_'.$post_type.'_structure',
            sprintf(__('%s base', 'auxin-elements' ), $post_type_label ),
            array( $this, 'posttypes_permalink_fields_callback_function' ),
            $this->option_group,
            'auxin_posttypes_permalink_setting_section',
            array( 'post_type' => $post_type, 'page_type' => 'single' )
        );

        register_setting( $this->option_group,'auxin_'.$post_type.'_structure' );

        if( $this->post_type_has_archive( $post_type ) ){

            add_settings_field( 'auxin_'.$post_type.'_archive_structure',
                sprintf(__('%s archive base', 'auxin-elements' ), $post_type_label ),
                array( $this, 'posttypes_permalink_fields_callback_function'),
                $this->option_group,
                'auxin_posttypes_permalink_setting_section',
                array( 'post_type' => $post_type, 'page_type' => 'archive' )
            );

            register_setting( $this->option_group,'auxin_'.$post_type.'_archive_structure' );
        }

        if( $taxonomies = get_object_taxonomies( $post_type, 'objects' ) ){

                foreach ( $taxonomies as $tax => $tax_object ) {
                    if( empty( $tax_object->rewrite ) || empty( $tax_object->rewrite['slug'] ) ){
                        continue;
                    }
                    add_settings_field(
                        "auxin_{$post_type}_{$tax}_structure",
                        sprintf(__('%s base', 'auxin-elements' ), $tax_object->label ),
                        array( $this, 'posttypes_permalink_fields_callback_function'),
                        $this->option_group,
                        'auxin_posttypes_permalink_setting_section',
                        array( 'post_type' => $post_type, 'page_type' => $tax )
                );

                register_setting( $this->option_group, "auxin_{$post_type}_{$tax}_structure" );
            }
        }

    }

    /**
     * Get permalink option structure
     *
     * @param  array $args
     * @return string       The permalink structure
     */
    private function get_structure( $args ){
        $defaults = array(
            'post_type' => '',
            'page_type' => 'single' // single, archive, $taxonomy
        );
        $args = wp_parse_args( $args, $defaults );

        if( empty( $args['post_type'] ) ){
            _doing_it_wrong( __FUNCTION__, 'Post type is required.', '2' );
        }

        if( empty( $args['suffix'] ) ){
            $args['suffix'] = 'single' == $args['page_type'] ? '' : '_'.$args['page_type'];
        }

        return str_replace('-', '_',  $this->prefix.'_'. $args['post_type']. $args['suffix'] .'_structure' );
    }

    /**
     * Generates the section for permalink options
     *
     * @return void
     */
    public function posttypes_permalink_section_callback_function(){
        _e('These settings control the permalinks used for theme\'s post types. These settings only apply when <strong>not using "default" permalink structure.</strong>.', 'auxin-elements' );
        echo "<br /><br />";
    }

    /**
     * Generates the input field for permalink options
     *
     * @param  array $args
     * @return void
     */
    public function posttypes_permalink_fields_callback_function( $args ) {
        $output_suffix = $args['page_type'] !== 'single' ? '' : '<code>/' . __( 'sample-post', 'auxin-elements' ).'/</code>';

        $option_id     = $this->get_structure( $args );
        $val           = get_theme_mod( $option_id );

        printf( '<code>%1$s/</code><input id="%2$s" name="%2$s" type="text" value="%3$s" />%4$s', home_url(), $option_id, $val, $output_suffix );
    }

    /**
     * Whether the post type has archive page or not
     *
     * @param  string $post_type The post type name
     * @return bool              The post type has archive or not
     */
    private function post_type_has_archive( $post_type ){
        $post_type_object = get_post_type_object( $post_type );
        return ! empty( $post_type_object ) && $post_type_object->has_archive;
    }

}

