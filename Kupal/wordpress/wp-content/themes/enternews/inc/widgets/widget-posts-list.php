<?php
if (!class_exists('EnterNews_Posts_List')) :
    /**
     * Adds EnterNews_Posts_List widget.
     */
    class EnterNews_Posts_List extends AFthemes_Widget_Base
    {
        /**
         * Sets up a new widget instance.
         *
         * @since 1.0.0
         */
        function __construct()
        {
            $this->text_fields = array('enternews-categorised-posts-title', 'enternews-excerpt-length', 'enternews-posts-number');
            $this->select_fields = array('enternews-select-category', 'enternews-select-background', 'enternews-show-excerpt', 'enternews-select-background-type');

            $widget_ops = array(
                'classname' => 'enternews_posts_list list-layout aft-widget',
                'description' => __('Displays posts from selected category in a list.', 'enternews'),
                'customize_selective_refresh' => false,
            );

            parent::__construct('enternews_posts_list', __('AFTN Posts List', 'enternews'), $widget_ops);
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
            $title = apply_filters('widget_title', $instance['enternews-categorised-posts-title'], $instance, $this->id_base);

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




            $number_of_posts = 6;

            ?>
                <?php
               
                    if ( !empty($background) ){
                $args['before_widget']= enternews_update_widget_before($args, $background,'aft-widget');
            }
                 
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
                    <div class="af-container-row clearfix">
                        <?php
                        $count = 1;
                        if ($all_posts->have_posts()) :
                            while ($all_posts->have_posts()) : $all_posts->the_post();
                                global $post;
                                $url = enternews_get_freatured_image_url($post->ID, 'thumbnail');
                                $thumbnail_size = 'thumbnail';

                                ?>

                                <div class="col-2 pad float-l af-double-column list-style" data-mh="af-feat-list">
                                    <div class="read-single color-pad">
                                        <div class="read-img pos-rel col-4 float-l read-bg-img">
                                            <?php the_post_thumbnail($thumbnail_size); ?>

                                            <a href="<?php the_permalink(); ?>"></a>
                                        </div>
                                        <div class="read-details col-75 float-l pad color-tp-pad">
                                            <div class="read-categories">

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
                                <?php
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

                ?>


      <?php  }

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

            $background_type = array(

                'solid-background' => __('Solid Background', 'enternews'),
                'solid-border' => __('Solid Border', 'enternews'),
                'dashed-border' => __('Dashed Border', 'enternews'),


            );


            $categories = enternews_get_terms();

            if (isset($categories) && !empty($categories)) {
                // generate the text input for the title of the widget. Note that the first parameter matches text_fields array entry
                echo parent::enternews_generate_text_input('enternews-categorised-posts-title', __('Title', 'enternews'), __('Posts List', 'enternews'));
                echo parent::enternews_generate_select_options('enternews-select-category', __('Select category', 'enternews'), $categories);
                echo parent::enternews_generate_select_options('enternews-select-background', __('Select Background', 'enternews'), $background, '', 'secondary-background');

            }

            //print_pre($terms);


        }

    }
endif;