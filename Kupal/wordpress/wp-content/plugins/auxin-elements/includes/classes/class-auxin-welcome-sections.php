<?php


class Auxin_Welcome_Sections {

    /**
     * Instance of this class.
     *
     * @var      object
     */
    protected static $instance = null;

    /**
     * Instance of main welcome class.
     *
     * @var      object
     */
    public $welcome = null;

    /**
     * The slug name to refer to this menu
     *
     * @since 1.0
     *
     * @var string
     */
    public $page_slug;

    /**
     * List of video tutorilas.
     *
     * @var      array
     */
    protected $tutorial_list = null;


    function __construct(){;
        add_filter( 'auxin_admin_welcome_sections', array( $this, 'add_section_importer' ), 60 );
        add_filter( 'auxin_admin_welcome_sections', array( $this, 'add_section_templates'), 65 );
        add_filter( 'auxin_admin_welcome_sections', array( $this, 'add_section_plugins'  ), 70 );
        add_filter( 'auxin_admin_welcome_sections', array( $this, 'add_section_feedback' ), 100 );
        add_filter( 'auxin_admin_welcome_sections', array( $this, 'add_section_status'   ), 110 );
        add_filter( 'auxin_admin_welcome_sections', array( $this, 'add_section_updates'  ), 120 );

        if ( isset( $_GET['rate'] ) || get_option( 'auxin_show_rate_scale_notice', false ) ) {
            add_action( 'all_admin_notices', array( $this, 'add_feedback_notice') );
        }

        add_action( 'auxin_admin_before_welcome_section_content', array( $this, 'maybe_add_dashboard_notice') );

        if( ! defined('THEME_PRO') || ! THEME_PRO ) {
            add_filter( 'auxin_admin_welcome_sections', array( $this, 'add_section_go_pro'   ), 120 );
        }

        add_action( 'auxin_admin_after_welcome_section_content' , array( $this, 'append_changelog') );
        add_action( 'auxin_admin_after_welcome_section_content' , array( $this, 'append_tutorials') );

        add_filter( 'auxin_admin_welcome_video_tutorial_list'   , array( $this, 'add_video_tutorial_list' ) );
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


    /*-----------------------------------------------------------------------------------*/
    /*  Adds demos tab in theme about (welcome) page
    /*-----------------------------------------------------------------------------------*/

    public function add_section_updates( $sections ){
        $total_updates = function_exists('auxin_get_total_updates') ? auxin_get_total_updates() : 0;
        $update_count  = $total_updates ? sprintf(' <span class = "update-plugins count-%1$s"><span class="update-count">%1$s</span></span>', $total_updates ) : '';

        $sections['updates'] = array(
            'label'          => esc_html__( 'Updates', 'auxin-elements' ) . $update_count,
            'description'    => '',
            'callback'       => 'setup_updates',
            'add_admin_menu' => $total_updates ? true : false
        );

        return $sections;
    }

    public function add_section_templates( $sections ){

        $sections['templates'] = array(
            'label'          => esc_html__( 'Template Kits', 'auxin-elements' ),
            'description'    => '',
            'callback'       => 'setup_templates',
            'add_admin_menu' => true
        );

        return $sections;
    }

    public function add_section_importer( $sections ){

        if( ! empty( $sections['importer'] ) ){
            $sections['importer']['callback']       = 'setup_importer';
            $sections['importer']['add_admin_menu'] = true;
        }

        return $sections;
    }

    /**
     * Adds a notice after dashboard navigation
     *
     * @param array  $sections
     */
    public function maybe_add_dashboard_notice( $sections ){
        $api_id = ( defined('THEME_PRO' ) && THEME_PRO ) ? '3' : '4';
        $style = array( 'display' => 'block !important' );
        Auxin_Notices::get_ads_notices( $api_id, $style, 'no-updated' );
    }

    /**
     * Adds a new section to welcome page
     *
     * @param array  $sections
     */
    public function add_section_plugins( $sections ){

        $sections['plugins'] = array(
            'label'          => esc_html__( 'Plugins', 'auxin-elements' ),
            'description'    => '',
            'callback'       => 'setup_plugins',
            'add_admin_menu' => true
        );

        return $sections;
    }

    /**
     * Adds a new section to welcome page
     *
     * @param array  $sections
     */
    public function add_section_feedback( $sections ){

         $sections['feedback'] = array(
            'label'       => __( 'Feedback', 'auxin-elements' ),
            'description' => '',
            'callback'    => array( $this, 'render_feedback' )
        );

        return $sections;
    }

    /**
     * Adds a new section to welcome page
     *
     * @param array  $sections
     */
    public function add_section_status( $sections ){

      $system_notices = Auxels_System_Check::get_instance()->get_num_of_notices();
      $system_check  = $system_notices ? sprintf(' <span class = "update-plugins count-%1$s"><span class="update-count">%1$s</span></span>', $system_notices ) : '';

      $sections['status'] = array(
          'label'       => __( 'System Status', 'auxin-elements' ) . $system_check,
          'description' => '',
          'url'         => admin_url( 'site-health.php' ), // optional
      );

      return $sections;
    }

    /**
     * Adds a new section to welcome page
     *
     * @param array  $sections
     */
    public function add_section_go_pro( $sections ){

        $sections['go_pro'] = array(
            'label'          => esc_html__( 'Go Pro', 'auxin-elements' ),
            'description'    => '',
            'url'            => esc_url( 'http://phlox.pro/go-pro/?utm_source=phlox-welcome&utm_medium=phlox-free&utm_campaign=phlox-go-pro&utm_content=welcome-tab' ), // optional
            'target'         => '_blank',
            'image'          => AUXELS_ADMIN_URL . '/assets/images/welcome/rocket-pro.gif',
            'add_admin_menu' => true
        );

        return $sections;
    }

    function render_feedback(){
        // the previous rate of the client
        $previous_rate = auxin_get_option( 'user_rating' );

        $support_tab_url = self_admin_url( 'admin.php?page=auxin-welcome&tab=help' );
        ?>

        <div class="feature-section aux-welcome-page-feedback">
            <div class="aux-section-content-box">

                <div class="aux-columns-wrap">
                    <div class="aux-image-wrap"></div>
                    <div class="aux-form-wrap">
                        <form class="aux-feedback-form" action="<?php echo admin_url( 'admin.php?page=auxin-welcome&tab=feedback'); ?>" method="post" >

                            <div class="aux-rating-section">
                                <h3 class="aux-content-title"><?php echo wp_sprintf( __( 'How likely are you to recommend %s to a friend?', THEME_DOMAIN ), THEME_NAME_I18N ) ?></h3>
                                <div class="aux-theme-ratings">
                                <?php
                                    for( $i = 1; $i <= 10; $i++ ){
                                        printf(
                                            '<div class="aux-rate-cell"><input type="radio" name="theme_rate" id="theme-rating%1$s" value="%1$s" %2$s/><label class="rating" for="theme-rating%1$s">%1$s</label></div>',
                                            $i, checked( $previous_rate, $i, false )
                                        );
                                    }
                                ?>

                                </div>
                                <div class="aux-ratings-measure">
                                    <p><?php _e( "Don't like it", 'auxin-elements' ); ?></p>
                                    <p><?php _e( "Like it so much", 'auxin-elements' ); ?></p>
                                </div>
                            </div>

                            <div class="aux-feedback-section aux-hide">
                                <div class="aux-notice-box aux-notice-blue aux-rate-us-offer aux-hide">
                                    <img src="<?php echo AUXELS_ADMIN_URL.'/assets/images/welcome/rate-like.svg'; ?>" />
                                    <p><?php printf(
                                        __('Thanks for using Phlox theme. If you are enjoying this theme, please support us by %s submitting 5 star rate here%s. That would be a huge help for us to continue developing this theme.'),
                                        '<a href="http://phlox.pro/rate/'.THEME_ID.'" target="_black">',
                                        '</a>'
                                    ); ?>
                                    </p>
                                </div>
                                <h3 class="aux-feedback-form-title aux-content-title"><?php _e('Please explain why you gave this score (optional)', 'auxin-elements'); ?></h3>
                                <h4 class="aux-feedback-form-subtitle">
                                    <?php
                                    printf( __( 'Please do not use this form to get support, in this case please check the %s help section %s', 'auxin-elements' ),
                                           '<a href="' .$this->welcome->get_tab_link('help'). '">', '</a>'  ); ?>
                                </h4>
                                <textarea placeholder="Enter your feedback here" rows="10" name="feedback" class="large-text"></textarea>
                                <input type="text" placeholder="Email address (Optional)" name="email" class="text-input" />
                                <?php wp_nonce_field( 'phlox_feedback' ); ?>

                                <input type="submit" class="aux-wl-button aux-round aux-blue aux-wide" value="<?php esc_attr_e( 'Submit', 'auxin-elements' ); ?>" />

                                <div class="aux-sending-status">
                                    <img  class="ajax-progress aux-hide" src="<?php echo AUXIN_URL; ?>/css/images/elements/saving.gif" />
                                    <span class="ajax-response aux-hide" ><?php _e( 'Submitting your feedback ..', 'auxin-elements' ); ?></span>
                                </div>

                            </div>

                            <?php $this->send_feedback_mail(); ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php
    }


    private function send_feedback_mail(){
        if  ( ! ( ! isset( $_POST['phlox_feedback'] ) || ! wp_verify_nonce( $_POST['phlox_feedback'], 'feedback_send') ) ) {

            $email    = ! empty( $_POST["email"]    ) ? sanitize_email( $_POST["email"]  ) : 'Empty';
            $feedback = ! empty( $_POST["feedback"] ) ? esc_textarea( $_POST["feedback"] ) : '';

            if( $feedback ){
                wp_mail( 'info@averta.net', 'feedback from phlox dashboard', $feedback . chr(0x0D).chr(0x0A) . 'Email: ' . $email );
                $text = __( 'Thanks for your feedback', 'auxin-elements' );
            } else{
                $text = __('Please try again and fill up at least the feedback field.', 'auxin-elements');
            }

            printf('<p class="notification">%s</p>', $text);
        }
    }


    /**
     * Display changelogs on welcome page
     *
     * @param  string  $tab  The tab that we intent to append this section to.
     * @return void
     */
    function append_changelog( $tab ){

        if( 'dashboard' !== $tab ){
            return;
        }

        // sanitize the theme id
        $theme_id = sanitize_key( THEME_ID );

        $changelog_cache_id = "auxin_cache_remote_changelog__{$theme_id}";

        // get remote changelog
        if( ( false === $changelog_info = get_transient( $changelog_cache_id ) ) || isset( $_GET['flush_transient'] ) ){

            $changelog_remote = $this->get_remote_changelog( $theme_id );

            if( is_wp_error( $changelog_remote ) ){
                echo $changelog_remote->get_error_message();
                return;
            } else {
                $changelog_info = $changelog_remote;
                set_transient( $changelog_cache_id, $changelog_remote, 2 * HOUR_IN_SECONDS );
            }

        }

        // print the changelog
        if( $changelog_info ){ ?>
            <div class="aux-changelog-wrap">
                <div class="aux-changelog-header">
                    <h2><?php _e( 'Changelog', 'auxin-elements' ); ?></h2>
                    <div class="aux-welcome-socials">
                        <span><?php _e('Follow Us', 'auxin-elements' ); ?></span>
                        <div class="aux-welcome-social-items">
                            <a href="http://www.twitter.com/averta_ltd" class="aux-social-item aux-social-twitter"  target="_blank" title="<?php _e('Follow us on Twitter', 'auxin-elements' ); ?>"></a>
                            <a href="http://www.facebook.com/averta" class="aux-social-item aux-social-facebook" target="_blank" title="<?php _e('Follow us on Facebook', 'auxin-elements' ); ?>"></a>
                            <a href="https://www.instagram.com/averta.co/" class="aux-social-item aux-social-instagram"target="_blank" title="<?php _e('Follow us on Instagram', 'auxin-elements' ); ?>"></a>
                            <a href="https://themeforest.net/user/averta" class="aux-social-item aux-social-envato"   target="_blank" title="<?php _e('Follow us on Envato', 'auxin-elements' ); ?>"></a>
                            <a href="https://www.youtube.com/playlist?list=PL7X-1Jmy1jcdekHe6adxB81SBcrHOmLRS" class="aux-social-item aux-social-youtube"   target="_blank" title="<?php _e('Subscribe to Phlox YouTube channel', 'auxin-elements' ); ?>"></a>
                        </div>
                        <ul>

                        </ul>
                    </div>
                </div>
                <div class="aux-changelog-content">
                    <div class="aux-changelog-list"><?php echo $changelog_info; ?></div>
                </div>
            </div>
            <?php
        }

    }


    /**
     * Display video tutorials on welcome page
     *
     * @param  string  $tab  The tab that we intent to append this section to.
     * @return void
     */
    function append_tutorials( $tab ){

        if( 'help' !== $tab ){ return; }
        $video_list = $this->get_video_tutorial_list();
        ?>
        <div class="aux-setup-content">
            <div class="aux-video-list aux-grid-list aux-isotope-list" >

        <?php foreach ( $video_list as $video_id => $video_title ) { ?>
                <div class="aux-grid-item aux-iso-item grid_4" >
                    <div class="aux-grid-item-inner">
                        <div class="aux-grid-item-media">
                            <iframe width="440" height="248" src="https://www.youtube-nocookie.com/embed/<?php echo $video_id; ?>?rel=0&amp;showinfo=0"
                            frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                        </div>
                        <div class="aux-grid-item-footer">
                            <a class="aux-grid-footer-text-link" href="<?php echo esc_url( 'https://youtu.be/'. $video_id ); ?>" target="_blank"><h3><?php echo esc_html( $video_title ); ?><span class="dashicons dashicons-external"></span></h3></a>
                        </div>
                    </div>
                </div>
        <?php } ?>

            </div>
        </div>
        <?php
    }

    /**
     * Collect the plugin filters
     *
     * @return array    plugin filters
     */
    private function get_video_tutorial_list(){
        if( empty( $this->tutorial_list ) ){
            $this->tutorial_list = apply_filters( 'auxin_admin_welcome_video_tutorial_list', array() );
        }

        return $this->tutorial_list;
    }

    /**
     * Collect the video tutorilas
     *
     * @return array    video tutorilas list
     */
    public function add_video_tutorial_list( $list ){

        $new_list = array(
            'W8jkMN7EEdo' => 'Installing Phlox Pro',
            'porrf6QgjuU' => 'Configuring Menu General Options',
            'irSajN7JXQQ' => 'Customizing Header Menu',
            'AU6qT84scSY' => 'Adding a Burger Menu',
            'gVm9EJ6BrAI' => 'Customizing Post Formats',
            'YkXKxgWruDk' => 'Customizing Post Options',
            'UIVE7ZWbSoI' => 'Displaying Blog Slider',
            'b37PUx76ejc' => 'Customizing Blog Page Options',
            '09pnnTaYAto' => 'Organizing Blog with Category',
            'NJDnhbI23P4' => 'Displaying About Author Box',
            'W-nqEKUk0Ss' => 'Displaying Related Posts on Blog',
            'qNVie3fELr4' => 'Customizing Page Options',
            'QutPg4W642A' => 'Creating Different Pages with Custom Pages',
            '8GiqLqtsWrU' => 'Configuring Layout and Design Options',
            'IWj6vbnjrUE' => 'Specifying Content and Titles Typography',
            'hNU85eRLCQg' => 'Customizing Header Section',
            'mo7hiMIQvv0' => 'Adding a Scroll to Top Button',
            'SefEG3KOYcI' => 'Customizing the Background',
            'RzVFT4UxXtw' => 'Customizing Audio and Video Player',
            'J3GO3Lt22dw' => 'Adding a Frame for Your Website',
            'DueARmwq1q4' => 'Customizing Footer Area',
            'w65-HRbMvMo' => 'Displaying Subfooter',
            'SOcYs6wJsao' => 'Displaying Subfooter Bar',
            'bcQS7iol000' => 'Customizing your Website Login Page',
            'Pi9121CAGgY' => 'Adding Custom CSS and JavaScript',
            //'A96MVeK1RCc' => 'Installing Phlox Pro',
            'kYh0z4jo6jM' => 'Creating Audio with Elementor',
            'DiiVuwhNwnU' => 'Creating Button with Elementor',
            'oi7R8iLRvCo' => 'Creating Video with Elementor',
            'gveFqSpfcQQ' => 'Creating Contact Form with Elementor',
            'ZKMypryYnto' => 'Creating Map with Elementor',
            'sOVsUu-2DHw' => 'Contact Box with Elementor'
        );

        return array_merge( $list, $new_list );
    }

    /**
     * Retrieves the changelog remotely
     *
     * @param  string $item_name  The name of the project that we intend to get the info of
     * @return string             The changelog context
     */
    private function get_remote_changelog( $item_name = '' ){

        if( empty( $item_name ) ){
            $item_name = THEME_ID;
        }

        global $wp_version;

        $args = array(
            'user-agent' => 'WordPress/'. $wp_version.'; '. get_site_url(),
            'timeout'    => ( ( defined('DOING_CRON') && DOING_CRON ) ? 30 : 10 ),
            'body'       => array(
                'action'    => 'text',
                'cat'       => 'changelog',
                'item-name' => $item_name,
                'content'   => 'list',
                'view'      => 'html',
                'limit'     => 5
            )
        );

        $request = wp_remote_get( 'http://api.averta.net/envato/items/', $args );

        if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) !== 200 ) {
            return new WP_Error( 'no_response', 'Error while receiving remote data' );
        }

        $response = $request['body'];

        return $response;
    }

    /**
     * Add feedback popup to all admin pages
     */
    public function add_feedback_notice() {

        $previous_rate = auxin_get_option( 'user_rating', '' );

        ?>
        <div class="aux-feedback-notice aux-welcome-page-feedback aux-not-animated <?php echo esc_attr( THEME_ID ); ?>">
            <div class="aux-section-content-box">

                <div class="aux-columns-wrap">
                    <div class="aux-form-wrap">
                        <form class="aux-popup-feedback-form" action="<?php echo admin_url( 'admin.php?page=auxin-welcome&tab=feedback'); ?>" method="post" >

                            <div class="aux-rating-section">
                                <h3 class="aux-content-title"><?php echo wp_sprintf( __( 'How likely are you to recommend %s to a friend?', THEME_DOMAIN ), THEME_NAME_I18N ) ?></h3>
                                <div class="aux-ratings-measure">
                                    <p><?php _e( "Don't like it", 'auxin-elements' ); ?></p>
                                    <p><?php _e( "Neither likely nor unlikely", 'auxin-elements' ); ?></p>
                                    <p><?php _e( "Like it so much", 'auxin-elements' ); ?></p>
                                </div>
                                <div class="aux-theme-ratings">
                                <?php
                                    for( $i = 0; $i <= 10; $i++ ){
                                        printf(
                                            '<div class="aux-rate-cell %3$s"><input type="radio" name="theme_rate" id="theme-rating%1$s" value="%1$s" %2$s/><label class="rating" for="theme-rating%1$s">%1$s</label></div>',
                                            $i, checked( $previous_rate, $i, false ), ( $previous_rate == $i ? 'checked' : '' )
                                        );
                                    }
                                ?>

                                </div>
                            </div>

                            <div class="aux-feedback-section">
                                <div class="aux-rate-on-market aux-conditional-section aux-disappear">
                                    <a class='aux-close-form aux-button aux-medium aux-curve aux-rate-market' href="<?php echo esc_url( 'http://phlox.pro/rate/'.THEME_ID ); ?>" target="_blank">
                                        <span class="aux-overlay"></span>
                                        <span class="aux-text">
                                            <?php _e( 'Support by rating 5 stars', 'auxin-elements');?>
                                        </span>
                                    </a>
                                    <a class='aux-close-form aux-button aux-medium aux-curve aux-outline aux-white' href="#">
                                        <span class="aux-overlay"></span>
                                        <span class="aux-text">
                                            <?php _e( 'I already did :)', 'auxin-elements');?>
                                        </span>
                                    </a>
                                    <?php if( THEME_ID === 'phlox' ){ ?>
                                    <a class='aux-more-info' href="https://phlox.pro/go/" target="_blank"><?php printf( 'Have you heard about %s?', '<strong>'. __( 'Phlox Pro', 'auxin-elements' ) .'</strong>' ); ?></a>
                                    <?php } ?>
                                </div>

                                <div class="aux-feedback-detail aux-conditional-section aux-disappear">
                                    <h3 class="aux-content-title"><?php _e('Please explain why you gave this score (optional)', 'auxin-elements'); ?></h3>
                                    <textarea placeholder="<?php echo esc_attr( 'Enter your feedback here' ); ?>" rows="10" name="feedback" class="large-text"></textarea>
                                    <?php wp_nonce_field( 'phlox_feedback' ); ?>
                                    <a class='aux-close-form aux-button aux-medium aux-curve aux-outline btn-submit' href="#">
                                        <span class="aux-overlay"></span>
                                        <span class="aux-text">
                                            <?php _e( 'Submit', 'auxin-elements');?>
                                        </span>
                                    </a>
                                </div>

                                <div class="aux-feedback-more aux-conditional-section">
                                    <a class="aux-remind-later" href="#"><?php _e( 'Remind Me Later', 'auxin-elements' ); ?></a>
                                </div>

                                <div class="aux-sending-status aux-hide">
                                    <img  class="ajax-progress" src="<?php echo AUXIN_URL; ?>/css/images/elements/saving.gif" />
                                </div>

                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
