<?php
if (!class_exists('EnterNews_Double_Col_Categorised_Posts')) :
    /**
     * Adds EnterNews_Double_Col_Categorised_Posts widget.
     */
    class EnterNews_Double_Col_Categorised_Posts extends AFthemes_Widget_Base
    {
        /**
         * Sets up a new widget instance.
         *
         * @since 1.0.0
         */
        function __construct()
        {
            $this->text_fields = array('enternews-categorised-posts-title-1', 'enternews-categorised-posts-title-2', 'enternews-posts-number-1', 'enternews-posts-number-2', 'enternews-excerpt-length');
            $this->select_fields = array('enternews-select-category-1', 'enternews-select-category-2', 'enternews-select-layout-1', 'enternews-select-layout-2', 'enternews-select-background', 'enternews-select-background-type', 'enternews-show-excerpt');

            $widget_ops = array(
                'classname' => 'enternews_double_col_categorised_posts aft-widget',
                'description' => __('Displays posts from 2 selected categories in double column.', 'enternews'),
                'customize_selective_refresh' => false,
            );

            parent::__construct('enternews_double_col_categorised_posts', __('AFTN Double Categories Posts', 'enternews'), $widget_ops);
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

            $title_1 = apply_filters('widget_title', $instance['enternews-categorised-posts-title-1'], $instance, $this->id_base);
            $title_2 = apply_filters('widget_title', $instance['enternews-categorised-posts-title-2'], $instance, $this->id_base);
            $category_1 = isset($instance['enternews-select-category-1']) ? $instance['enternews-select-category-1'] : '0';
            $category_2 = isset($instance['enternews-select-category-2']) ? $instance['enternews-select-category-2'] : '0';
            $layout_1 = isset($instance['enternews-select-layout-1']) ? $instance['enternews-select-layout-1'] : 'full-plus-list';
            $layout_2 = isset($instance['enternews-select-layout-2']) ? $instance['enternews-select-layout-2'] : 'list';
            $number_of_posts_1 =  3;
            $number_of_posts_2 =  3;

            $show_excerpt = isset($instance['enternews-show-excerpt']) ? $instance['enternews-show-excerpt'] : 'true';
            $excerpt_length = isset($instance['enternews-excerpt-length']) ? $instance['enternews-excerpt-length'] : '25';

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


            <div class="widget-block-wrapper">
                <div class="af-container-row clearfix">
                    <div class="col-2 float-l pad <?php echo esc_attr($layout_1); ?> grid-plus-list af-sec-post">
                        <?php if (!empty($title_1)): ?>
                            <h4 class="widget-title header-after1">
                            <span class="header-after">
                                <?php echo esc_html($title_1); ?>
                            </span>
                            </h4>
                        <?php endif; ?>
                        <div class="widget-block widget-wrapper">
                            <div class="clearfix af-double-column list-style">
                                <?php $all_posts = enternews_get_posts($number_of_posts_1, $category_1); ?>
                                <?php
                                $count_1 = 1;


                                if ($all_posts->have_posts()) :
                                    while ($all_posts->have_posts()) : $all_posts->the_post();


                                        if ($count_1 == 1) {
                                            $thumbnail_size = 'enternews-medium';

                                        } else {
                                            $thumbnail_size = 'thumbnail';
                                        }


                                        global $post;
                                        $url = enternews_get_freatured_image_url($post->ID, $thumbnail_size);

                                        if ($url == '') {
                                            $img_class = 'no-image';
                                        }


                                        ?>

                                        <?php if ($count_1 == 1): ?>
                                            <div class="col-1 float-l aft-spotlight-posts-<?php echo esc_attr($count_1); ?>">
                                                <div class="read-single color-pad">
                                                    <div class="read-img pos-rel col-4 float-l marg-15-lr read-bg-img">
                                                        <?php the_post_thumbnail($thumbnail_size); ?>
                                                        <div class="min-read-post-format">
                                                            <?php echo enternews_post_format($post->ID); ?>
                                                            <span class="min-read-item">
                                                <?php enternews_count_content_words($post->ID); ?>
                                            </span>
                                                        </div>
                                                        <div class="read-categories af-category-inside-img">
                                                            <?php enternews_post_categories(); ?>
                                                        </div>
                                                        <a href="<?php the_permalink(); ?>"></a>
                                                    </div>
                                                    <div class="read-details col-75 float-l pad color-tp-pad">

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
                                        <?php else: ?>

                                            <div class="col-1 float-l aft-spotlight-posts-<?php echo esc_attr($count_1); ?>">
                                                <div class="read-single color-pad">
                                                    <div class="read-img pos-rel col-4 float-l marg-15-lr read-bg-img">
                                                        <?php the_post_thumbnail($thumbnail_size); ?>
                                                        <div class="min-read-post-format">
                                                            <?php echo enternews_post_format($post->ID); ?>
                                                            <span class="min-read-item">
                                                <?php enternews_count_content_words($post->ID); ?>
                                            </span>
                                                        </div>
                                                        <a href="<?php the_permalink(); ?>"></a>
                                                    </div>
                                                    <div class="read-details col-75 float-l pad color-tp-pad">


                                                        <div class="read-categories ">
                                                            <?php enternews_post_categories(); ?>
                                                        </div>
                                                        <div class="read-title">
                                                            <h4>
                                                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                                            </h4>
                                                        </div>
                                                        <div class="entry-meta">
                                                            <?php enternews_get_comments_count($post->ID); ?>
                                                            <?php enternews_post_item_meta(); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <?php
                                        $count_1++;
                                    endwhile;
                                    ?>
                                <?php endif;
                                wp_reset_postdata(); ?>
                            </div>
                        </div>
                    </div>


                    <div class="col-2 float-l pad <?php echo esc_attr($layout_2); ?> grid-plus-list af-sec-post">
                        <?php if (!empty($title_2)): ?>
                            <h4 class="widget-title header-after1">
                    <span class="header-after">
                        <?php echo esc_html($title_2); ?>
                        </span>
                            </h4>
                        <?php endif; ?>

                        <div class="widget-block widget-wrapper">
                            <div class="clearfix af-double-column list-style">
                                <?php $all_posts = enternews_get_posts($number_of_posts_2, $category_2); ?>
                                <?php
                                $count_2 = 1;


                                if ($all_posts->have_posts()) :
                                    while ($all_posts->have_posts()) : $all_posts->the_post();


                                        if ($count_2 == 1) {
                                            $thumbnail_size = 'enternews-medium';

                                        } else {
                                            $thumbnail_size = 'thumbnail';
                                        }


                                        global $post;
                                        $url = enternews_get_freatured_image_url($post->ID, $thumbnail_size);

                                        if ($url == '') {
                                            $img_class = 'no-image';
                                        }


                                        ?>

                                        <?php if ($count_2 == 1): ?>
                                            <div class="col-1 float-l aft-spotlight-posts-<?php echo esc_attr($count_2); ?>">
                                                <div class="read-single color-pad">
                                                    <div class="read-img pos-rel col-4 float-l marg-15-lr read-bg-img">
                                                        <?php the_post_thumbnail($thumbnail_size); ?>
                                                        <div class="min-read-post-format">
                                                            <?php echo enternews_post_format($post->ID); ?>
                                                            <span class="min-read-item">
                                                <?php enternews_count_content_words($post->ID); ?>
                                            </span>
                                                        </div>
                                                        <a href="<?php the_permalink(); ?>"></a>
                                                        <div class="read-categories af-category-inside-img">

                                                            <?php enternews_post_categories(); ?>
                                                        </div>
                                                    </div>
                                                    <div class="read-details col-75 float-l pad color-tp-pad">

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
                                        <?php else: ?>

                                            <div class="col-1 float-l aft-spotlight-posts-<?php echo esc_attr($count_2); ?>">
                                                <div class="read-single color-pad">
                                                    <div class="read-img pos-rel col-4 float-l marg-15-lr read-bg-img">
                                                        <?php the_post_thumbnail($thumbnail_size); ?>
                                                        <div class="min-read-post-format">
                                                            <?php echo enternews_post_format($post->ID); ?>
                                                            <span class="min-read-item">
                                                <?php enternews_count_content_words($post->ID); ?>
                                            </span>
                                                        </div>
                                                        <a href="<?php the_permalink(); ?>"></a>
                                                    </div>
                                                    <div class="read-details col-75 float-l pad color-tp-pad">

                                                        <div class="read-categories ">
                                                            <?php enternews_post_categories(); ?>
                                                        </div>
                                                        <div class="read-title">
                                                            <h4>
                                                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                                            </h4>
                                                        </div>
                                                        <div class="entry-meta">
                                                            <?php enternews_get_comments_count($post->ID); ?>
                                                            <?php enternews_post_item_meta(); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <?php
                                        $count_2++;
                                    endwhile;
                                    ?>
                                <?php endif;
                                wp_reset_postdata(); ?>
                            </div>
                        </div>
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

            $background = array(
                'default' => __('Default', 'enternews'),
                'dim' => __('Dim', 'enternews'),

            );




            //print_pre($terms);
            $categories = enternews_get_terms();

            if (isset($categories) && !empty($categories)) {
                // generate the text input for the title of the widget. Note that the first parameter matches text_fields array entry
                echo parent::enternews_generate_text_input('enternews-categorised-posts-title-1', __('Title 1', 'enternews'), 'Category Posts 1');
                echo parent::enternews_generate_select_options('enternews-select-category-1', __('Select category 1', 'enternews'), $categories);

                echo parent::enternews_generate_text_input('enternews-categorised-posts-title-2', __('Title 2', 'enternews'), 'Category Posts 2');
                echo parent::enternews_generate_select_options('enternews-select-category-2', __('Select category 2', 'enternews'), $categories);

                echo parent::enternews_generate_select_options('enternews-select-background', __('Select Background', 'enternews'), $background, '', 'dim');

            }




        }

    }
endif;