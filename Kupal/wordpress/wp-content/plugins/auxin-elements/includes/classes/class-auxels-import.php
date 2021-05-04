<?php
/**
 * Class for importing and exporting theme options
 */
class Auxels_Import {

    /**
     * Instance of this class.
     *
     * @var      object
     */
    protected static $instance = null;


    function __construct(){
        // Add a radio option for exporting auxin options to wp export options.
        add_action( 'export_filters', array( $this, 'export_filters' ) );
        // Process the check field for auxin options while export submitted
        add_filter( 'export_args', array( $this, 'export_args' ) );

        // adding options to export file
        add_action( 'rss2_head', array( $this, 'add_xml_tag_option_in_export' ) );
        // Import the options based on parsed data from xml file
        add_action( 'import_start', array( $this, 'import_start' ) );
    }

    /**
     * Add a radio option for exporting auxin options to available export options.
     *
     * @return void
     */
    public function export_filters() {
        ?>
        <hr />
        <p><label>
        <input type="checkbox" name="auxin-options" checked="checked" aria-describedby="all-content-desc" />
        <?php _e( 'Include theme options', 'auxin-elements' ); ?>
        </label></p>
        <?php
    }

    /**
     * Process the check field for auxin options
     *
     * @param  array $args
     * @return mixed
     */
    public function export_args( $args ){

        if ( ! empty( $_GET['auxin-options'] ) ) {
            $args['auxin-options'] = true;
        }

        return $args;
    }


    /**
     * Generate option page for wp options in xml format
     */
    public function add_xml_tag_option_in_export(){
        global $wpdb;

        $options_ref = $this->get_export_option_list();

        foreach ( $options_ref as $option_export_name => $option_import_name ) {
            $sql = $wpdb->prepare("SELECT option_name, option_value FROM $wpdb->options WHERE option_name = %s", $option_import_name );
            $result = $wpdb->get_results( $sql );

            if( ! empty( $result[0]->option_value ) ){
            ?>
    <wp:option>
        <wp:option_key><?php echo $this->wxr_cdata( $option_export_name ); ?></wp:option_key>
        <wp:option_value><?php echo $this->wxr_cdata( $result[0]->option_value ); ?></wp:option_value>
    </wp:option>
            <?php
            }

        }
    }

    /**
     * Retrieves the list of options we intended to include in export file
     */
    public function get_export_option_list(){
        return array(
            'theme_options' => THEME_ID . '_theme_options'
        );
    }


    /**
     * Import the options based on parsed data from xml file
     */
    public function import_start(){
        global $wp_import;

        $options_ref = $this->get_export_option_list();

        $file = get_attached_file( $wp_import->id );
        // if export file is not uploaded and is a link to a file in theme files
        if( empty( $file ) && property_exists( $wp_import, 'import_file' ) ){
            $file = $wp_import->import_file;
        }

        include 'class-auxels-import-parser.php';

        $parser  = new Auxels_Import_Parser;
        $options = $parser->parse( $file );

        foreach ( $options as $option_key => $option_value ){
            if( ! empty( $options_ref[ $option_key ] ) ){
                update_option( $options_ref[ $option_key ], maybe_unserialize( $option_value ) );
            }
        }

    }

    /**
     * Print the data in cdata and utf8 format
     * @param string $str
     * @return string
     */
    function wxr_cdata( $str ) {
        if ( ! seems_utf8( $str ) ) {
            $str = utf8_encode( $str );
        }

        $str = '<![CDATA[' . str_replace( ']]>', ']]]]><![CDATA[>', $str ) . ']]>';

        return $str;
    }


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

}

new Auxels_Import();
