<?php
if (!class_exists('EnterNews_Posts_Slider')) :
    /**
     * Adds EnterNews_Posts_Slider widget.
     */
    class EnterNews_Posts_Slider extends AFthemes_Widget_Base
    {
        /**
         * Sets up a new widget instance.
         *
         * @since 1.0.0
         */
        function __construct()
        {
            $this->text_fields = array('enternews-posts-slider-title', 'enternews-excerpt-length', 'enternews-posts-slider-number');
            $this->select_fields = array('enternews-select-category', 'enternews-show-excerpt','enternews-select-background', 'enternews-select-background-type');

            $widget_ops = array(
                'classname' => 'enternews_posts_slider_widget aft-widget',
                'description' => __('Displays posts slider from selected category.', 'enternews'),
                'customize_selective_refresh' => false,
            );

            parent::__construct('enternews_posts_slider', __('AFTN Posts Slider', 'enternews'), $widget_ops);
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


            /** This filter is documented in wp-includes/default-widgets.php */
            $title = apply_filters('widget_title', $instance['enternews-posts-slider-title'], $instance, $this->id_base);
            $category = isset($instance['enternews-select-category']) ? $instance['enternews-select-category'] : 0;
            $number_of_posts = 5;
            $show_excerpt = isset($instance['enternews-show-excerpt']) ? $instance['enternews-show-excerpt'] : 'true';
            $excerpt_length = isset($instance['enternews-excerpt-length']) ? $instance['enternews-excerpt-length'] : '25';
            $background = isset($instance['enternews-select-background']) ? $instance['enternews-select-background'] : 'default';




            if ( !empty($background) ){
                $args['before_widget']= enternews_update_widget_before($args, $background,'aft-widget');
            }
            // open the widget container
            echo $args['before_widget'];
            ?>
            <?php if (!empty($title)): ?>
            <div class="em-title-subtitle-wrap">
                <?php if (!empty($title)): ?>
                    <h4 class="widget-title header-after1">
                        <span class="header-after">
                            <?php echo esc_html($title); ?>
                            </span>
                    </h4>
                <?php endif; ?>
            </div>
        <?php endif; ?>
            <?php

            $all_posts = enternews_get_posts($number_of_posts, $category);
            ?>
            <div class="widget-block widget-wrapper">
            <div class="posts-slider banner-slider-2  af-post-slider af-widget-carousel slick-wrapper">
                    <?php
                    if ($all_posts->have_posts()) :
                        while ($all_posts->have_posts()) : $all_posts->the_post();
                            global $post;
                            $url = enternews_get_freatured_image_url($post->ID, 'enternews-medium');
                            $thumbnail_size = 'enternews-medium';
                            ?>
                            <div class="slick-item">
                                <div class="big-grid ">
                                    <div class="read-single pos-rel">
                                        <div class="read-img pos-rel read-bg-img">
                                            <a class="aft-slide-items" href="<?php the_permalink(); ?>"></a>
                                            <?php if (!empty($url)): ?>
                                                <?php the_post_thumbnail($thumbnail_size); ?>
                                            <?php endif; ?>
                                            <div class="min-read-post-format">
                                                <?php echo enternews_post_format($post->ID); ?>
                                                <span class="min-read-item">
                                                <?php enternews_count_content_words($post->ID); ?>
                                            </span>
                                            </div>
                                        </div>
                                        <div class="read-details">
                                            <div class="read-categories af-category-inside-img">

                                                <?php enternews_post_categories(); ?>
                                            </div>


                                            <div class="read-title">
                                                <h4>
                                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                                </h4>
                                            </div>

                                            <div class="entry-meta">
                                                <?php enternews_post_item_meta(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        endwhile;
                    endif;
                    wp_reset_postdata();
                    ?>
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
            $options = array(
                'true' => __('Yes', 'enternews'),
                'false' => __('No', 'enternews')

            );
    
            $background = array(
                'default' => __('Default', 'enternews'),
                'secondary-background' => __('Secondary Color', 'enternews'),
    
            );




            $categories = enternews_get_terms();
            if (isset($categories) && !empty($categories)) {
                // generate the text input for the title of the widget. Note that the first parameter matches text_fields array entry
                echo parent::enternews_generate_text_input('enternews-posts-slider-title', __('Title', 'enternews'), 'Posts Slider');

                echo parent::enternews_generate_select_options('enternews-select-category', __('Select category', 'enternews'), $categories);


                echo parent::enternews_generate_select_options('enternews-select-background', __('Select Background', 'enternews'), $background, '', 'default');


            }
        }
    }
endif;