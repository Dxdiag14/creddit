<?php
if (!function_exists('enternews_banner_advertisement')):
    /**
     * Ticker Slider
     *
     * @since EnterNews 1.0.0
     *
     */
    function enternews_banner_advertisement($args = '1')
    {

        if (is_active_sidebar('home-advertisement-widgets-' . $args)): ?>
            <div class="banner-promotions-wrapper">
                <div class="promotion-section">
                    <?php dynamic_sidebar('home-advertisement-widgets-' . $args); ?>
                </div>
            </div>
        <?php endif;
    }
endif;

add_action('enternews_action_banner_advertisement', 'enternews_banner_advertisement', 10, 1);