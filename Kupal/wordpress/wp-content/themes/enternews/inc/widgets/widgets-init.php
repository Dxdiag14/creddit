<?php

// Load widget base.
require_once get_template_directory() . '/inc/widgets/widgets-base.php';

/* Theme Widget sidebars. */
require get_template_directory() . '/inc/widgets/widgets-register-sidebars.php';

/* Theme Widget sidebars. */
require get_template_directory() . '/inc/widgets/widgets-common-functions.php';

/* Theme Widgets*/

require get_template_directory() . '/inc/widgets/widget-posts-double-category.php';
require get_template_directory() . '/inc/widgets/widget-posts-grid.php';
require get_template_directory() . '/inc/widgets/widget-posts-list.php';
require get_template_directory() . '/inc/widgets/widget-posts-express-grid.php';
require get_template_directory() . '/inc/widgets/widget-posts-tabbed.php';
require get_template_directory() . '/inc/widgets/widget-social-contacts.php';
require get_template_directory() . '/inc/widgets/widget-author-info.php';
require get_template_directory() . '/inc/widgets/widget-posts-slider.php';



/* Register site widgets */
if ( ! function_exists( 'enternews_widgets' ) ) :
    /**
     * Load widgets.
     *
     * @since 1.0.0
     */
    function enternews_widgets() {

        register_widget( 'EnterNews_Double_Col_Categorised_Posts' );
        register_widget( 'EnterNews_Posts_Grid' );
        register_widget( 'EnterNews_Posts_List' );
        register_widget( 'EnterNews_Posts_Express_Grid' );
        register_widget( 'EnterNews_Tabbed_Posts' );
        register_widget( 'EnterNews_Social_Contacts' );
        register_widget( 'EnterNews_author_info' );
        register_widget( 'EnterNews_Posts_Slider' );

    }
endif;
add_action( 'widgets_init', 'enternews_widgets' );
