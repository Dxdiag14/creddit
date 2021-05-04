<?php
/**
 * Auxin admin notices
 */
class Auxin_Notices{

    protected $args        = array();
    protected $buttons     = '';

    function __construct( $args = array() ){
        $defaults   = array(
            'id'             => NULL,
            'title'          => '',
            'skin'           => 'default',
            'image'          => '',
            'screen_filter'  => array(),
            'desc'           => '',
            'initial_snooze' => '',     // snooze time in milliseconds
            'has_close'      => true,   // Whether it has close button or not
            'buttons'        => array(),
            'dismissible'    => array(
                'url_key'    => 'aux-hide-core-plugin-notice',
                'action'     => 'auxin_hide_notices_nonce',
                'expiration' => YEAR_IN_SECONDS
            )
        );
        $this->args = wp_parse_args( $args, $defaults );

        if( empty( $this->args['id'] ) ){
            return new WP_Error( 'missing_id', __( "You need to enter a unique id for notice.", 'auxin-elements' ) );
        }

        if( is_array( $this->args['dismissible'] ) ){
            $this->flush_dismissible();
        }
    }

    /**
     * get image
     *
     * @param boolean $echo
     * @param string $before
     * @param string $after
     * @return void | string
     */
    private function get_image( $before = '<div class="aux-notice-image">', $after = '</div>' ){

        if ( empty( $this->args['image'] ) || ! is_array( $this->args['image'] ) ) {
            return;
        }

        $attrs = '';
        foreach ( $this->args['image'] as $attr_name => $attr_value ) {
            $attrs .= sprintf( ' %s="%s"', $attr_name, $attr_value );
        }

        return $before . '<img '. $attrs .' />' . $after;
    }

    /**
     * get title
     *
     * @param boolean $echo
     * @param string $before
     * @param string $after
     * @return void | string
     */
    private function get_title( $before = '<h3 class="aux-notice-title">', $after = '</h3>' ){

        if ( empty( $this->args['title'] ) ) {
            return;
        }

        return $before . $this->args['title'] . $after;
    }

    /**
     * get class skin
     *
     * @param boolean $echo
     * @param string $prefix
     * @return void | string
     */
    private function get_skin( $prefix = 'aux-notice-skin-' ){
        return $prefix . $this->args['skin'];
    }

    /**
     * get description
     *
     * @param boolean $echo
     * @param string $before
     * @param string $after
     * @return void | string
     */
	private function get_description( $before = '<p class="aux-notice-description">', $after = '</p>' ){

        if ( strlen( $this->args['desc'] ) == 0 ) {
            return;
        }

        return $before . $this->args['desc'] . $after;
    }

    /**
     * get buttons
     *
     * @param boolean $echo
     * @return void
     */
	private function get_buttons(){
        if( ! is_array( $this->args['buttons'] ) || empty( $this->args['buttons'] ) ) {
            return;
        }

        $default_args = [
            'target' => '_blank',
            'border' => 'curve',
            'icon_align' => 'right',
            'uppercase' => 'no',
            'type' => 'link',
            'link' => '#',
            'expiration' => '',
            'extra_classes' => 'aux-notice-btn'
        ];

        foreach ( $this->args['buttons'] as $btn_args ) {

            $current_default_args = $default_args;

            if( !empty( $btn_args['type']  ) && 'skip' === $btn_args['type'] ){
                $current_default_args['style'] = 'outline';
                $current_default_args['color_name'] = 'ads' === $this->args['skin'] ? 'white' : 'black';
                $current_default_args['extra_classes'] .= ' aux-skip-notice';
            } else {
                $current_default_args['extra_classes'] = 'aux-notice-cta-btn';
                switch ($this->args['skin']) {
                    case 'success':
                        $current_default_args['color_name'] = 'shamrock';
                        break;

                    case 'info':
                        $current_default_args['color_name'] = 'tan-hide';
                        break;

                    case 'error':
                        $current_default_args['color_name'] = 'fire-engine-red';
                        break;

                    default:
                        break;
                }
            }

            $btn_args = wp_parse_args( $btn_args, $current_default_args );

            // Maye add custom expiration to the btn
            if( $btn_args['expiration'] ){
                $btn_args['btn_attrs'] = 'data-expiration{'. $btn_args['expiration'] .'}';
            }
            unset( $btn_args['expiration'] );

            $this->buttons .= auxin_widget_button_callback( $btn_args );
        }

        return $this->buttons;
    }

    /**
     * get dismissible button
     *
     * @param boolean $echo
     * @return void
     */
    private function get_dismissible(){

        if( $this->args['dismissible'] === false ){
            return;
        }

        ob_start();

        if( $this->args['has_close'] ){
?>
        <a href="<?php echo esc_url( $this->get_nonce_url() ); ?>" class="notice-dismiss aux-skip-notice aux-close-notice">
            <span class="screen-reader-text"><?php echo _e( 'Skip', 'auxin-elements' ); ?></span>
        </a>
<?php } ?>
        <script>
            jQuery('.<?php echo esc_js( $this->get_unique_class() ); ?> .aux-skip-notice').on( 'click' , function(e) {
                e.preventDefault();
                var expiration = this.getAttribute('data-expiration') || '<?php echo esc_js( $this->args['dismissible']['expiration'] ); ?>'

                jQuery.ajax({
                    url : ajaxurl,
                    type: 'post',
                    data: {
                        action    : 'auxin_dismissed_notice',
                        id        : '<?php echo esc_js( $this->args['id'] ); ?>',
                        nonce     : '<?php echo esc_js( wp_create_nonce( '_notice_nonce' ) ); ?>',
                        expiration: expiration
                    }
                }).done(function( response ) {
                    if(response.success) {
                        jQuery(this).closest('.aux-notice-wrapper').fadeOut();
                    }
                }.bind(this));
            });
        </script>
<?php
        return ob_get_clean();
    }

    /**
     * Update dismissible transient
     *
     * @return void
     */
    private function flush_dismissible(){
        if ( isset( $_GET[ $this->args['dismissible']['url_key'] ] ) && isset( $_GET[ '_notice_nonce' ] ) && $_GET[ $this->args['dismissible']['url_key'] ] === $this->args['id'] ) {
            if ( ! wp_verify_nonce( $_GET[ '_notice_nonce' ],  $this->args['dismissible']['action'] ) ) {
                wp_die( __( 'Authorization failed. Please refresh the page and try again.', 'auxin-elements' ) );
            }
            auxin_set_transient( $this->get_transient_key(), 1, $this->args['dismissible']['expiration'] );
            $this->args['dismissible'] = false;
        }
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    private function get_nonce_url(){
        $actionurl = add_query_arg( $this->args['dismissible']['url_key'], $this->args['id'] );
        return wp_nonce_url( $actionurl, $this->args['dismissible']['action'], '_notice_nonce' );
    }

    /**
     * check dismissible
     *
     * @return boolean
     */
    private function is_dismissible(){
        if( ! is_array( $this->args['dismissible'] ) || auxin_get_transient( $this->get_transient_key() ) ){
            return true;
        }
        return false;
    }

    /**
     * Check snooze time
     *
     * @return boolean
     */
    private function is_snoozed(){
        if( ! empty( $this->args['initial_snooze'] ) ){
            $transient_key = $this->get_transient_key() . '-snooze';
            $snooze_time   = auxin_get_transient( $transient_key );
            if( $snooze_time && $snooze_time > strtotime( "now" ) ){
                return true;
            } elseif( $snooze_time === false ) {
                auxin_set_transient( $transient_key, strtotime( $this->args['initial_snooze'] . " seconds" ) );
                return true;
            }
        }
        return false;
    }

    /**
     * Check screen filter
     *
     * @return boolean
     */
    private function is_visible_screen(){
        $current_screen = get_current_screen();
        if( ! empty( $this->args['screen_filter'] ) && ! in_array(  $current_screen->id, $this->args['screen_filter'] ) ) {
            return true;
        }
        return false;
    }

    /**
     * Retrieves a transient key.
     */
    private function get_transient_key(){
        return 'auxin-notice-' . $this->args['id'];
    }

    /**
     * Retrieves a unique id for main wrapper.
     */
    private function get_unique_class(){
        return 'auxin-notice-id-' . $this->args['id'];
    }

    /**
     * Retrieves custom styles for main wrapper
     */
    private function get_custom_styles(){

        if ( ! isset( $this->args['wrapper_extra_styles'] ) || empty( $this->args['wrapper_extra_styles'] ) ) {
            return false;
        } else {
            $styles  = '';

            foreach( $this->args['wrapper_extra_styles'] as $property => $value ) {
                if ( 'custom' === $property ) {
                    $styles .= $value;
                } else {
                    $styles  .=  $property . ':' . $value . ';';
                }
            }

            return 'style="'. $styles . '"';
            
        }

    }


    /**
     * render output
     *
     * @param boolean $echo
     * @return void
     */
	public function render( $updated = '' ){

        if( $this->is_dismissible() || $this->is_visible_screen() || $this->is_snoozed() ) {
            return;
        }
        $updated = empty( $updated ) ? 'updated' : '';
        echo sprintf(
            '<div class="%s auxin-message aux-notice-control aux-notice-wrapper %s %s" %s>%s %s %s <p class="aux-notice-submit submit">%s %s</p></div>',
            $updated,
            $this->get_unique_class(),
            $this->get_skin(),
            $this->get_custom_styles(),
            $this->get_image(),
            $this->get_title(),
            $this->get_description(),
            $this->get_buttons(),
            $this->get_dismissible()
        );

    }

    public static function get_ads_notices( $api_id = '26', $style = array(), $updated = '' ) {
        $transient_id = ( $api_id == '3' || $api_id == '4' ) ? 'auxin_dashboard_ads_request' : 'auxin_ads_request';
        if ( false === $data = get_transient( $transient_id )  ) {
            $body = auxin_remote_get( 'http://api.averta.net/rest/ara/v1/notifications?notification-space='. $api_id, null);
            $data = json_decode( $body );
            set_transient( $transient_id, $data , 12 * HOUR_IN_SECONDS );
        }
        
        if ( empty( $data ) ) {
            return false;
        }
    
        $ads_list = [];
    
        foreach( $data as $ads_data ) {
            $args = [
                'id'        => 'auxin_ads_' . $ads_data->id,
                'title'     => $ads_data->title->rendered,
                'desc'      => $ads_data->message,
                'skin'      => 'ads',
                'has_close' => false,
                'wrapper_extra_styles' => [
                    'background-color' => $ads_data->{'background-color'},
                    'background-image' => 'url(' . $ads_data->{'background-image'} . ')',
                    'color' => $ads_data->color,
                    'custom' =>  $ads_data->boxStyles
                ],
                'buttons'   => [
                    [
                        'label'      => $ads_data->button->label,
                        'target'     => '_blank',
                        'link'       => $ads_data->button->url,
                        'custom_styles' => $ads_data->button->style
                    ],
                    [
                        'label'      => __('Dismiss', 'auxin-elements'),
                        'type'       => 'skip',
                        'expiration' => HOUR_IN_SECONDS * 2
                    ]
                ],
            ];
            if ( ! empty( $style ) ) {
                foreach( $style as $prop => $value ) {
                    $args['wrapper_extra_styles'][ $prop ] = $value;
                }
            }
            $ads_list['auxin_dashboard_ads_' . $ads_data->id] = new self( $args );
        }
        foreach ( $ads_list as $ads ) {
            if( $ads instanceof self ){
                $ads->render( $updated );
            }
        }
    }
}
