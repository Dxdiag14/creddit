<?php
if (!class_exists('EnterNews_Tabbed_Posts')) :
    /**
     * Adds EnterNews_Tabbed_Posts widget.
     */
    class EnterNews_Tabbed_Posts extends AFthemes_Widget_Base
    {
        /**
         * Sets up a new widget instance.
         *
         * @since 1.0.0
         */
        function __construct()
        {
            $this->text_fields = array('enternews-tabbed-popular-posts-title', 'enternews-tabbed-latest-posts-title', 'enternews-tabbed-categorised-posts-title', 'enternews-excerpt-length', 'enternews-posts-number');

            $this->select_fields = array('enternews-show-excerpt', 'enternews-enable-categorised-tab', 'enternews-select-category','enternews-select-background', 'enternews-select-background-type');

            $widget_ops = array(
                'classname' => 'enternews_tabbed_posts_widget aft-widget',
                'description' => __('Displays tabbed posts lists from selected settings.', 'enternews'),
                'customize_selective_refresh' => false,
            );

            parent::__construct('enternews_tabbed_posts', __('AFTN Tabbed Posts', 'enternews'), $widget_ops);
        }

        /**
         * Front-end display of widget.
         *
         * @see WP_Widget::widget()
         *
         * @param array $args Widget arguments.
         * @param array $instance Saved values from database.
         */

        public function widget($args, $instance)
        {
            $instance = parent::enternews_sanitize_data($instance, $instance);
            $tab_id = 'tabbed-' . $this->number;


            /** This filter is documented in wp-includes/default-widgets.php */

            $show_excerpt = isset($instance['enternews-show-excerpt']) ? $instance['enternews-show-excerpt'] : 'false';
            $excerpt_length = isset($instance['enternews-excerpt-length']) ? $instance['enternews-excerpt-length'] : '20';
            $number_of_posts = 5;


            $popular_title = isset($instance['enternews-tabbed-popular-posts-title']) ? $instance['enternews-tabbed-popular-posts-title'] : __('AFTN Popular', 'enternews');
            $latest_title = isset($instance['enternews-tabbed-latest-posts-title']) ? $instance['enternews-tabbed-latest-posts-title'] : __('AFTN Latest', 'enternews');


            $enable_categorised_tab = isset($instance['enternews-enable-categorised-tab']) ? $instance['enternews-enable-categorised-tab'] : 'true';
            $categorised_title = isset($instance['enternews-tabbed-categorised-posts-title']) ? $instance['enternews-tabbed-categorised-posts-title'] : __('Trending', 'enternews');
            $category = isset($instance['enternews-select-category']) ? $instance['enternews-select-category'] : '0';

            if(isset($instance['enternews-select-background']) && !empty($instance['enternews-select-background'])){
                $background = $instance['enternews-select-background'];
            }else{
                $background = 'dim';
            }

            if(isset($instance['enternews-select-background-type']) && !empty($instance['enternews-select-background-type'])){
                $background_type = $instance['enternews-select-background-type'];
            }else{
                $background_type = 'solid-background';
            }

            $background .= ' ' . $background_type;


            if ( !empty($background) ){
                $args['before_widget']= enternews_update_widget_before($args, $background,'aft-widget');
            }

            // open the widget container
            echo $args['before_widget'];
            ?>
            <div class="tabbed-container">
                <div class="tabbed-head">
                    <ul class="nav nav-tabs af-tabs tab-warpper" role="tablist">
                        <li class="tab tab-recent">
                            <a href="#<?php echo esc_attr($tab_id); ?>-recent"
                               aria-controls="<?php esc_attr_e('Recent', 'enternews'); ?>" role="tab"
                               data-toggle="tab" class="font-family-1 active">
                                <i class="fa fa-bolt" aria-hidden="true"></i>  <?php echo esc_html($latest_title); ?>
                            </a>
                        </li>
                        <li role="presentation" class="tab tab-popular">
                            <a href="#<?php echo esc_attr($tab_id); ?>-popular"
                               aria-controls="<?php esc_attr_e('Popular', 'enternews'); ?>" role="tab"
                               data-toggle="tab" class="font-family-1">
                                <i class="fa fa-clock-o" aria-hidden="true"></i>  <?php echo esc_html($popular_title); ?>
                            </a>
                        </li>

                        <?php if ($enable_categorised_tab == 'true'): ?>
                            <li class="tab tab-categorised">
                                <a href="#<?php echo esc_attr($tab_id); ?>-categorised"
                                   aria-controls="<?php esc_attr_e('Categorised', 'enternews'); ?>" role="tab"
                                   data-toggle="tab" class="font-family-1">
                                   <i class="fa fa-fire" aria-hidden="true"></i>  <?php echo esc_html($categorised_title); ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="widget-block widget-wrapper">
                <div class="tab-content">
                    <div id="<?php echo esc_attr($tab_id); ?>-recent" role="tabpanel" class="tab-pane active">
                        <?php
                        enternews_render_posts('recent', $show_excerpt, $excerpt_length, $number_of_posts);
                        ?>
                    </div>
                    <div id="<?php echo esc_attr($tab_id); ?>-popular" role="tabpanel" class="tab-pane">
                        <?php
                        enternews_render_posts('popular', $show_excerpt, $excerpt_length, $number_of_posts);
                        ?>
                    </div>
                    <?php if ($enable_categorised_tab == 'true'): ?>
                        <div id="<?php echo esc_attr($tab_id); ?>-categorised" role="tabpanel" class="tab-pane">
                            <?php
                            enternews_render_posts('categorised', $show_excerpt, $excerpt_length, $number_of_posts, $category);
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
                </div>
            </div>
            <?php
            // close the widget container
            echo $args['after_widget'];
        }

        /**
         * Back-end widget form.
         *
         * @see WP_Widget::form()
         *
         * @param array $instance Previously saved values from database.
         */
        public function form($instance)
        {
            $this->form_instance = $instance;
            $enable_categorised_tab = array(
                'true' => __('Yes', 'enternews'),
                'false' => __('No', 'enternews')

            );


            $background = array(
                'default' => __('Default', 'enternews'),
                'dim' => __('Dim', 'enternews'),
    
            );




            // generate the text input for the title of the widget. Note that the first parameter matches text_fields array entry
            ?><h4><?php _e('Latest Posts', 'enternews'); ?></h4><?php
            echo parent::enternews_generate_text_input('enternews-tabbed-latest-posts-title', __('Title', 'enternews'), __('Latest', 'enternews'));

            ?><h4><?php _e('Popular Posts', 'enternews'); ?></h4><?php
            echo parent::enternews_generate_text_input('enternews-tabbed-popular-posts-title', __('Title', 'enternews'), __('Popular', 'enternews'));

            $categories = enternews_get_terms();
            if (isset($categories) && !empty($categories)) {
                ?><h4><?php _e('Categorised Posts', 'enternews'); ?></h4>
                <?php
                echo parent::enternews_generate_select_options('enternews-enable-categorised-tab', __('Enable Categorised Tab', 'enternews'), $enable_categorised_tab);
                echo parent::enternews_generate_text_input('enternews-tabbed-categorised-posts-title', __('Title', 'enternews'), __('Trending', 'enternews'));
                echo parent::enternews_generate_select_options('enternews-select-category', __('Select category', 'enternews'), $categories);

            }
            ?><h4><?php _e('Settings for all tabs', 'enternews'); ?></h4><?php
            echo parent::enternews_generate_select_options('enternews-select-background', __('Select Background', 'enternews'), $background, '', 'dim');

        }
    }
endif;