<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package EnterNews
 */

?>


</div>



<?php do_action('enternews_action_full_width_upper_footer_section'); ?>

<footer class="site-footer">
    <?php $enternews_footer_widgets_number = enternews_get_option('number_of_footer_widget');
        if (1 == $enternews_footer_widgets_number) {
            $col = 'col-md-12';
        } elseif (2 == $enternews_footer_widgets_number) {
            $col = 'col-md-6';
        } elseif (3 == $enternews_footer_widgets_number) {
            $col = 'col-md-4';
        } else {
            $col = 'col-md-4';
        } ?>
    <?php if (is_active_sidebar( 'footer-first-widgets-section') || is_active_sidebar( 'footer-second-widgets-section') || is_active_sidebar( 'footer-third-widgets-section') || is_active_sidebar( 'footer-fourth-widgets-section')) : ?>
    <div class="primary-footer">
        <div class="container-wrapper">
            <div class="af-container-row clearfix">
                <?php if (is_active_sidebar( 'footer-first-widgets-section') ) : ?>
                    <div class="primary-footer-area footer-first-widgets-section <?php echo esc_attr($col); ?> col-sm-12">
                        <section class="widget-area color-pad">
                                <?php dynamic_sidebar('footer-first-widgets-section'); ?>
                        </section>
                    </div>
                <?php endif; ?>

                <?php if (is_active_sidebar( 'footer-second-widgets-section') ) : ?>
                    <div class="primary-footer-area footer-second-widgets-section <?php echo esc_attr($col); ?>  col-sm-12">
                        <section class="widget-area color-pad">
                            <?php dynamic_sidebar('footer-second-widgets-section'); ?>
                        </section>
                    </div>
                <?php endif; ?>

                <?php if (is_active_sidebar( 'footer-third-widgets-section') ) : ?>
                    <div class="primary-footer-area footer-third-widgets-section <?php echo esc_attr($col); ?>  col-sm-12">
                        <section class="widget-area color-pad">
                            <?php dynamic_sidebar('footer-third-widgets-section'); ?>
                        </section>
                    </div>
                <?php endif; ?>
                <?php if (is_active_sidebar( 'footer-fourth-widgets-section') ) : ?>
                    <div class="primary-footer-area footer-fourth-widgets-section <?php echo esc_attr($col); ?>  col-sm-12">
                        <section class="widget-area color-pad">
                            <?php dynamic_sidebar('footer-fourth-widgets-section'); ?>
                        </section>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if(1 != enternews_get_option('hide_footer_menu_section')): ?>
    <?php if (has_nav_menu( 'aft-footer-nav' ) || has_nav_menu( 'aft-social-nav' )):
        $class = 'col-1';
        if (has_nav_menu( 'aft-footer-nav' ) && has_nav_menu( 'aft-social-nav' )){
            $class = 'col-2';
        }

        ?>
    <div class="secondary-footer">
        <div class="container-wrapper">
            <div class="af-container-row clearfix af-flex-container">
                <?php if (has_nav_menu( 'aft-footer-nav' )): ?>
                    <div class="float-l pad color-pad <?php echo esc_attr($class); ?>">
                        <div class="footer-nav-wrapper">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'aft-footer-nav',
                            'menu_id' => 'footer-menu',
                            'depth' => 1,
                            'container' => 'div',
                            'container_class' => 'footer-navigation'
                        )); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php if (has_nav_menu( 'aft-social-nav' )): ?>
                    <div class="float-l pad color-pad <?php echo esc_attr($class); ?>">
                        <div class="footer-social-wrapper">
                            <div class="aft-small-social-menu">
                                <?php
                                wp_nav_menu(array(
                                    'theme_location' => 'aft-social-nav',
                                    'link_before' => '<span class="screen-reader-text">',
                                    'link_after' => '</span>',
                                    'menu_id' => 'social-menu',
                                    'container' => 'div',
                                    'container_class' => 'social-navigation'
                                ));
                                ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php endif; ?>
    <div class="site-info">
        <div class="container-wrapper">
            <div class="af-container-row">
                <div class="col-1 color-pad">
                    <?php $enternews_copy_right = enternews_get_option('footer_copyright_text'); ?>
                    <?php if (!empty($enternews_copy_right)): ?>
                        <?php echo esc_html($enternews_copy_right); ?>
                    <?php endif; ?>
                    <?php $enternews_theme_credits = enternews_get_option('hide_footer_copyright_credits'); ?>
                    <?php if ($enternews_theme_credits != 1): ?>
                        <span class="sep"> | </span>
                        <?php
                        /* translators: 1: Theme name, 2: Theme author. */
                        printf(esc_html__('%1$s by %2$s.', 'enternews'), '<a href="https://afthemes.com/products/enternews">EnterNews</a>', 'AF themes');
                        ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</footer>
</div>
<a id="scroll-up" class="secondary-color">
    <i class="fa fa-angle-up"></i>
</a>
<?php wp_footer(); ?>

</body>
</html>
