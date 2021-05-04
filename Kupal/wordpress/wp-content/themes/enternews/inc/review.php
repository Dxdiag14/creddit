<?php
/**
 * EnterNews Theme Review Notice Class.
 *
 * @author  AF themes
 * @package EnterNews
 * @since   2.1.2
 */

// Exit if directly accessed.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class to display the theme review notice for this theme after certain period.
 *
 * Class EnterNews_Theme_Review_Notice
 */
class EnterNews_Theme_Review_Notice {

    /**
     * Constructor function to include the required functionality for the class.
     *
     * EnterNews_Theme_Review_Notice constructor.
     */
    public function __construct() {

        add_action( 'after_setup_theme', array( $this, 'enternews_theme_rating_notice' ) );
        add_action( 'switch_theme', array( $this, 'enternews_theme_rating_notice_data_remove' ) );
        

    }

    /**
     * Set the required option value as needed for theme review notice.
     */
    public function enternews_theme_rating_notice() {

        // Set the installed time in `enternews_theme_installed_time` option table.
        $option = get_option( 'enternews_theme_installed_time' );
        if ( ! $option ) {
            update_option( 'enternews_theme_installed_time', time() );
        }

        add_action( 'admin_notices', array( $this, 'enternews_theme_review_notice' ), 0 );
        add_action( 'admin_init', array( $this, 'enternews_ignore_theme_review_notice' ), 0 );
        add_action( 'admin_init', array( $this, 'enternews_ignore_theme_review_notice_partially' ), 0 );

    }

    /**
     * Display the theme review notice.
     */
    public function enternews_theme_review_notice() {

        global $current_user;
        $user_id                  = $current_user->ID;
        $ignored_notice           = get_user_meta( $user_id, 'enternews_ignore_theme_review_notice', true );
        $ignored_notice_partially = get_user_meta( $user_id, 'nag_enternews_ignore_theme_review_notice_partially', true );

        /**
         * Return from notice display if:
         *
         * 1. The theme installed is less than 15 day ago.
         * 2. If the user has ignored the message partially for 5 day.
         * 3. Dismiss always if clicked on 'Already Done' button.
         */
        if ( ( get_option( 'enternews_theme_installed_time' ) > strtotime( '-15 days' ) ) || ( $ignored_notice_partially > strtotime( '-5 days' ) ) || ( $ignored_notice ) ) {
            return;
        }
        ?>

        <div class="notice updated theme-review-notice" style="position:relative;">
            <p>
                <?php
                printf(
                    /* Translators: %1$s current user display name. */
                    esc_html__(
                        'Howdy, %1$s! We\'ve noticed that you\'ve been using %2$s for some time now, we hope you are loving it! We would appreciate it if you can %3$sgive us a 5 star rating on WordPress.org%4$s! We\'ll continue to develop exciting new features for free in the future by sharing the love!', 'enternews'
                    ),
                    '<strong>' . esc_html( $current_user->display_name ) . '</strong>',
                    'EnterNews',
                    '<a href="https://wordpress.org/support/theme/enternews/reviews/?filter=5#new-post" target="_blank">',
                    '</a>'
                );
                ?>
            </p>

            <div class="links">
                <a href="https://wordpress.org/support/theme/enternews/reviews/?filter=5#new-post" class="btn button-primary" target="_blank">
                    <span class="dashicons dashicons-thumbs-up"></span>
                    <span><?php esc_html_e( 'Sure thing', 'enternews' ); ?></span>
                </a>

                <a href="?nag_enternews_ignore_theme_review_notice_partially=0" class="btn button-secondary">
                    <span class="dashicons dashicons-calendar"></span>
                    <span><?php esc_html_e( 'Remind me later', 'enternews' ); ?></span>
                </a>

                <a href="?nag_enternews_ignore_theme_review_notice=0" class="btn button-secondary">
                    <span class="dashicons dashicons-smiley"></span>
                    <span><?php esc_html_e( 'I\'ve already done.', 'enternews' ); ?></span>
                </a>

                <a href="<?php echo esc_url( 'https://afthemes.com/supports/' ); ?>" class="btn button-secondary" target="_blank">
                    <span class="dashicons dashicons-edit"></span>
                    <span><?php esc_html_e( 'Got any support queries?', 'enternews' ); ?></span>
                </a>
            </div>

            <a class="notice-dismiss" style="text-decoration:none;" href="?nag_enternews_ignore_theme_review_notice=0"></a>
        </div>

        <?php
    }

    /**
     * Function to remove the theme review notice permanently as requested by the user.
     */
    public function enternews_ignore_theme_review_notice() {

        global $current_user;
        $user_id = $current_user->ID;

        /* If user clicks to ignore the notice, add that to their user meta */
        if ( isset( $_GET['nag_enternews_ignore_theme_review_notice'] ) && '0' == $_GET['nag_enternews_ignore_theme_review_notice'] ) {
            add_user_meta( $user_id, 'enternews_ignore_theme_review_notice', 'true', true );
        }

    }

    /**
     * Function to remove the theme review notice partially as requested by the user.
     */
    public function enternews_ignore_theme_review_notice_partially() {

        global $current_user;
        $user_id = $current_user->ID;

        /* If user clicks to ignore the notice, add that to their user meta */
        if ( isset( $_GET['nag_enternews_ignore_theme_review_notice_partially'] ) && '0' == $_GET['nag_enternews_ignore_theme_review_notice_partially'] ) {
            update_user_meta( $user_id, 'nag_enternews_ignore_theme_review_notice_partially', time() );
        }

    }

    /**
     * Remove the data set after the theme has been switched to other theme.
     */
    public function enternews_theme_rating_notice_data_remove() {

        $get_all_users        = get_users();
        $theme_installed_time = get_option( 'enternews_theme_installed_time' );

        // Delete options data.
        if ( $theme_installed_time ) {
            delete_option( 'enternews_theme_installed_time' );
        }

        // Delete user meta data for theme review notice.
        foreach ( $get_all_users as $user ) {
            $ignored_notice           = get_user_meta( $user->ID, 'enternews_ignore_theme_review_notice', true );
            $ignored_notice_partially = get_user_meta( $user->ID, 'nag_enternews_ignore_theme_review_notice_partially', true );

            // Delete permanent notice remove data.
            if ( $ignored_notice ) {
                delete_user_meta( $user->ID, 'enternews_ignore_theme_review_notice' );
            }

            // Delete partial notice remove data.
            if ( $ignored_notice_partially ) {
                delete_user_meta( $user->ID, 'nag_enternews_ignore_theme_review_notice_partially' );
            }
        }

    }

    
}

new EnterNews_Theme_Review_Notice();
