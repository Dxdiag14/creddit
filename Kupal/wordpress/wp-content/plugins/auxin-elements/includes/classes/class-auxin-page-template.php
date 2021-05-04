<?php
/**
 * Custom Page Templates for Plugins
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
 */
class Auxin_Page_Template {

	/**
	 * The array of templates that this plugin tracks.
     *
     * @var      array
	 */
	private $templates = array();

    /**
     * The array of template directories that are required to be tracked.
     *
     * @var      array
     */
    private $template_directories = array();

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
	 * Initializes the plugin by setting filters and administration functions.
	 */
    private function __construct() {

        // Add a filter to the save post to inject out template into the page cache
        add_filter( 'wp_insert_post_data', array( $this, 'register_templates' ) );

        // Add a filter to the template include to determine if the page has our
        add_filter( 'template_include', array( $this, 'include_template') );

        // Add a filter to the attributes metabox to inject template into the dropdown.
        add_filter( "theme_page_templates", array( $this, 'add_page_templates' ), 10, 3 );
    }

    /**
     * Add the auxin page templates to the theme templates.
     */
    public function add_page_templates( $page_templates, $wp_theme, $post ) {
        // Make sure the templates ($this->templates) are collected.
        if( ! $this->get_templates() ){
            return $page_templates;
        }

        return $page_templates + $this->templates;
    }

    /**
     * Collect and retrieve the page templates
     *
     * @return array
     */
    private function get_templates(){

        // Return the templates if they were retrieved before.
        if( ! empty( $this->templates ) ){
            return $this->templates;
        }

        /**
         * $templates   An array containing the list of template file names and labels
         *
         * @var array
         * @example array(
         *              'custom-template-name1.php'     => __( 'Custom Template Name1', 'plugin-domain' ),
         *              'custom-template-name2.php'     => __( 'Custom Template Name2', 'plugin-domain' )
         *          );
         */
        $this->templates = apply_filters( 'auxin/core_elements/page_templates/file_names', $this->templates );

        return $this->templates;
    }

    /**
     * Collect and retrieve the page template directories.
     *
     * @return array
     */
    private function get_template_directories(){

        // Return the template directories if they were retrieved before.
        if( ! empty( $this->template_directories ) ){
            return $this->template_directories;
        }

        /**
         * An array containing the list of template directories.
         *
         * @var array
         * @example array(
         *              'path/to/custom/page/template1',
         *              'path/to/custom/page/template2'
         *          );
         */
        $this->template_directories = apply_filters( 'auxin/core_elements/page_templates/directories', $this->template_directories );

        return $this->template_directories;
    }


    /**
	 * Adds custom template to the pages cache in order to trick WordPress
	 * into thinking the template file exists where it doens't really exist.
	 */
    public function register_templates( $atts ) {

		// Create the key used for the themes cache
		$cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );

		// If cache list doesn't exist, or it's empty prepare an array
		$templates = wp_get_theme()->get_page_templates();
		if ( empty( $templates ) ) {
			$templates = array();
		}

        // Make sure the templates ($this->templates) are collected.
        $this->get_templates();

        // Quit if no custom template is defined.
        if( empty( $this->templates ) || ! is_array( $this->templates ) ){
            return $templates;
        }

		// New cache, therefore remove the old one
		wp_cache_delete( $cache_key , 'themes');

		// Now add our template to the list of templates by merging our templates
		$templates = array_merge( $templates, $this->templates );

		// Add the modified cache to allow WordPress to pick it up for listing
		wp_cache_add( $cache_key, $templates, 'themes', 1800 );

		return $atts;
	}

	/**
	 * Checks if the template is assigned to the page
	 */
	public function include_template( $template ) {

        // Return the search template if we're searching (instead of the template for the first result)
		if ( is_search() ) {
			return $template;
		}

        global $post;
        if ( ! $post ) {
            return $template;
        }

        // Make sure the templates ($this->templates) are collected.
        $this->get_templates();

        // Get the saved template name id
        $template_file_id = get_post_meta( $post->ID, '_wp_page_template', true );

        // Return default template if we don't have a custom one defined
        if ( ! isset( $this->templates[ $template_file_id ] ) ) {
            return $template;
        }

        // Make sure the template directories ($this->template_directories) are collected.
        $this->get_template_directories();

        // Quit if no custom template directory is defined.
        if( empty( $this->template_directories ) || ! is_array( $this->template_directories ) ){
            return $template;
        }

        foreach ( $this->template_directories as $directory ) {
            $template_file =  trailingslashit( $directory ) . $template_file_id;

            // Just to be safe, we check if the file exist first
            if ( file_exists( $template_file ) ) {
                return $template_file;
            }
        }

		// Return template
		return $template;
	}

}
