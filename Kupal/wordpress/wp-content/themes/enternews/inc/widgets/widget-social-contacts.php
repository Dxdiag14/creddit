<?php
if (!class_exists('EnterNews_Social_Contacts')) :
    /**
     * Adds EnterNews_Social_Contacts widget.
     */
    class EnterNews_Social_Contacts extends AFthemes_Widget_Base
    {
        /**
         * Sets up a new widget instance.
         *
         * @since 1.0.0
         */
        function __construct()
        {
            $this->text_fields = array('enternews-social-contacts-title');
            $this->select_fields = array('enternews-select-background', 'enternews-select-background-type');

            $widget_ops = array(
                'classname' => 'enternews_social_contacts_widget aft-widget',
                'description' => __('Displays social contacts lists from selected settings.', 'enternews'),

            );

            parent::__construct('enternews_social_contacts', __('AFTN Social Contacts', 'enternews'), $widget_ops);
        }

        /**
         * Front-end display of widget.
         *
         * @param array $args Widget arguments.
         * @param array $instance Saved values from database.
         * @see WP_Widget::widget()
         *
         */

        public function widget($args, $instance)
        {
            $instance = parent::enternews_sanitize_data($instance, $instance);
            /** This filter is documented in wp-includes/default-widgets.php */
            $title = apply_filters('widget_title', $instance['enternews-social-contacts-title'], $instance, $this->id_base);
            $title = isset($title) ? $title : __('AFTN Social', 'enternews');
            $subtitle = isset($instance['enternews-social-contacts-subtitle']) ? $instance['enternews-social-contacts-subtitle'] : '';
            $background = !empty($instance['enternews-select-background']) ? $instance['enternews-select-background'] : 'default';


            if (!empty($background)) {
                $args['before_widget'] = enternews_update_widget_before($args, $background, 'aft-widget');
            }


            // open the widget container
            echo $args['before_widget'];
            ?>
            <?php if (!empty($title)): ?>
            <div class="em-title-subtitle-wrap">
                <h4 class="widget-title header-after1">
                        <span class="header-after">
                            <?php echo esc_html($title); ?>
                            </span>
                </h4>

            </div>
        <?php endif; ?>
            <div class="widget-block widget-wrapper">
                <div class="social-widget-menu">
                    <?php
                    if (has_nav_menu('aft-social-nav')) {
                        wp_nav_menu(array(
                            'theme_location' => 'aft-social-nav',
                            'link_before' => '<span class="screen-reader-text">',
                            'link_after' => '</span>',
                        ));
                    } ?>
                </div>
            </div>
            <?php if (!has_nav_menu('aft-social-nav')) : ?>
            <p>
                <?php esc_html_e('Social menu is not set. You need to create menu and assign it to Social Menu on Menu Settings.', 'enternews'); ?>
            </p>
        <?php endif;

            // close the widget container
            echo $args['after_widget'];
        }

        /**
         * Back-end widget form.
         *
         * @param array $instance Previously saved values from database.
         * @see WP_Widget::form()
         *
         */
        public function form($instance)
        {
            $this->form_instance = $instance;
            $background = array(
                'default' => __('Default', 'enternews'),
                'secondary-background' => __('Secondary Color', 'enternews'),

            );


            // generate the text input for the title of the widget. Note that the first parameter matches text_fields array entry
            echo parent::enternews_generate_text_input('enternews-social-contacts-title', 'Title', 'AFTN Social');
            echo parent::enternews_generate_select_options('enternews-select-background', __('Select Background', 'enternews'), $background, '', 'default');

        }


    }
endif;