<?php
if (!class_exists('EnterNews_Posts_Express_Grid')) :
    /**
     * Adds EnterNews_Posts_Express_Grid widget.
     */
    class EnterNews_Posts_Express_Grid extends AFthemes_Widget_Base
    {
        /**
         * Sets up a new widget instance.
         *
         * @since 1.0.0
         */
        function __construct()
        {
            $this->text_fields = array('enternews-categorised-posts-title', 'enternews-excerpt-length', 'enternews-posts-number');
            $this->select_fields = array('enternews-select-category', 'enternews-show-excerpt', 'enternews-select-background', 'enternews-select-background-type');

            $widget_ops = array(
                'classname' => 'enternews_posts_express_grid grid-layout aft-widget',
                'description' => __('Displays posts from selected category in a grid.', 'enternews'),
                'customize_selective_refresh' => false,
            );

            parent::__construct('enternews_posts_express_grid', __('AFTN Posts Express Grid', 'enternews'), $widget_ops);
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
            $title = apply_filters('widget_title', $instance['enternews-categorised-posts-title'], $instance, $this->id_base);

            if(isset($instance['enternews-select-background']) && !empty($instance['enternews-select-background'])){
                $background = $instance['enternews-select-background'];
            }else{
                $background = 'secondary-background';
            }

            if(isset($instance['enternews-select-background-type']) && !empty($instance['enternews-select-background-type'])){
                $background_type = $instance['enternews-select-background-type'];
            }else{
                $background_type = 'solid-background';
            }

            $background .= ' ' . $background_type;


            $category = isset($instance['enternews-select-category']) ? $instance['enternews-select-category'] : '0';
            $number_of_posts = 3;
            $show_excerpt = isset($instance['enternews-show-excerpt']) ? $instance['enternews-show-excerpt'] : 'false';
            $excerpt_length = isset($instance['enternews-excerpt-length']) ? $instance['enternews-excerpt-length'] : '25';

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
                <div class="grid-style big-grid-small af-container-row clearfix">
                    <?php
                    $count = 1;
                    if ($all_posts->have_posts()) :
                        while ($all_posts->have_posts()) : $all_posts->the_post();
                            global $post;

                            if (($count == 1)) {
                                $thumbnail_size = 'enternews-medium';
                                $col_class = 'col-66';
                                $cat_class = 'af-category-inside-img';

                            } else {
                                $thumbnail_size = 'medium';
                                $col_class = 'col-3 title-over-image';
                                $cat_class = 'af-category-inside-img';
                            }
                            $url = enternews_get_freatured_image_url($post->ID, $thumbnail_size);
                            if (($count == 1)) {
                                ?>


                                <div class="pad float-l af-sec-post <?php echo esc_attr($col_class); ?>">
                                    <div class="big-grid">
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

                                                <?php if ($show_excerpt != 'false'): ?>
                                                    <div class="read-descprition full-item-discription">
                                                        <div class="post-description">
                                                            <?php echo wp_kses_post(enternews_get_excerpt(15, null, $post->ID)); ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <?php
                            } else{ ?>
                    <div class="pad float-l af-sec-post <?php echo esc_attr($col_class); ?>">

                        <div class="read-single color-pad" data-mh="af-grid-posts">
                            <div class="read-img pos-rel read-bg-img">
                                <?php the_post_thumbnail($thumbnail_size); ?>

                                <a href="<?php the_permalink(); ?>"></a>

                                <div class="read-details color-tp-pad no-color-pad">
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
                           <?php }
                            $count++;
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
         * @param array $instance Previously saved values from database.
         * @see WP_Widget::form()
         *
         */
        public function form($instance)
        {
            $this->form_instance = $instance;


            $background = array(
                'default' => __('Default', 'enternews'),
                'secondary-background' => __('Secondary Background', 'enternews'),

            );




            $categories = enternews_get_terms();

            if (isset($categories) && !empty($categories)) {
                // generate the text input for the title of the widget. Note that the first parameter matches text_fields array entry
                echo parent::enternews_generate_text_input('enternews-categorised-posts-title', __('Title', 'enternews'), __('Posts Express Grid', 'enternews'));
                echo parent::enternews_generate_select_options('enternews-select-category', __('Select category', 'enternews'), $categories);


                echo parent::enternews_generate_select_options('enternews-select-background', __('Select Background', 'enternews'), $background, '', 'secondary-background');
            }

            //print_pre($terms);


        }

    }
endif;