<?php
if (!class_exists('EnterNews_author_info')) :
    /**
     * Adds EnterNews_author_info widget.
     */
    class EnterNews_author_info extends AFthemes_Widget_Base
    {
        /**
         * Sets up a new widget instance.
         *
         * @since 1.0.0
         */
        function __construct()
        {
            $this->text_fields = array('enternews-author-info-title', 'enternews-author-info-subtitle', 'enternews-author-info-image', 'enternews-author-info-name', 'enternews-author-info-desc', 'enternews-author-info-phone', 'enternews-author-info-email');
            $this->url_fields = array('enternews-author-info-facebook', 'enternews-author-info-twitter', 'enternews-author-info-instagram');

            $this->select_fields = array( 'enternews-select-background', 'enternews-select-background-type');

            $widget_ops = array(
                'classname' => 'enternews_author_info_widget aft-widget',
                'description' => __('Displays author info.', 'enternews'),
                'customize_selective_refresh' => false,
            );

            parent::__construct('enternews_author_info', __('AFTN Author Info', 'enternews'), $widget_ops);
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

            $title = isset($instance['enternews-author-info-title']) ? $instance['enternews-author-info-title'] : '';
            $title = apply_filters('widget_title', $title, $instance, $this->id_base);
            if(isset($instance['enternews-select-background']) && !empty($instance['enternews-select-background'])){
                $background = $instance['enternews-select-background'];
            }else{
                $background = 'default';
            }

            if(isset($instance['enternews-select-background-type']) && !empty($instance['enternews-select-background-type'])){
                $background_type = $instance['enternews-select-background-type'];
            }else{
                $background_type = 'solid-background';
            }

            $background .= ' ' . $background_type;

            $profile_image = isset($instance['enternews-author-info-image']) ? ($instance['enternews-author-info-image']) : '';
            $thumbnail_size = 'thumbnail';
            if ($profile_image) {
                $image_attributes = wp_get_attachment_image_src($profile_image, 'large');
                $image_src = $image_attributes[0];
                $image_class = 'data-bg data-bg-hover';

            } else {
                $image_src = '';
                $image_class = 'no-bg';
            }

            $name = isset($instance['enternews-author-info-name']) ? ($instance['enternews-author-info-name']) : '';

            $desc = isset($instance['enternews-author-info-desc']) ? ($instance['enternews-author-info-desc']) : '';
            $facebook = isset($instance['enternews-author-info-facebook']) ? ($instance['enternews-author-info-facebook']) : '';
            $twitter = isset($instance['enternews-author-info-twitter']) ? ($instance['enternews-author-info-twitter']) : '';
            $linkedin = isset($instance['enternews-author-info-linkedin']) ? ($instance['enternews-author-info-linkedin']) : '';
            $youtube = isset($instance['enternews-author-info-youtube']) ? ($instance['enternews-author-info-youtube']) : '';
            $instagram = isset($instance['enternews-author-info-instagram']) ? ($instance['enternews-author-info-instagram']) : '';
            $vk = isset($instance['enternews-author-info-vk']) ? ($instance['enternews-author-info-vk']) : '';
    
            if ( !empty($background) ){
                $args['before_widget']= enternews_update_widget_before($args, $background,'aft-widget');
            }
            echo $args['before_widget'];
            ?>
            <section class="products">

                    <?php if (!empty($title)): ?>
                        <div class="section-head">
                            <?php if (!empty($title)): ?>
                                <h4 class="widget-title header-after1">
                                    <span class="header-after">
                                        <?php echo esc_html($title); ?>
                                    </span>
                                </h4>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <div class="widget-block widget-wrapper">
                    <div class="posts-author-wrapper">

                        <?php if (!empty($image_src)) : ?>


                            <figure class="read-img pos-rel read-bg-img af-author-img <?php echo esc_attr($image_class); ?>">
                                <?php echo wp_kses_post(wp_get_attachment_image($profile_image)); ?>
                            </figure>

                        <?php endif; ?>
                        <div class="af-author-details">
                            <?php if (!empty($name)) : ?>
                                <h4 class="af-author-display-name"><?php echo esc_html($name); ?></h4>
                            <?php endif; ?>
                            <?php if (!empty($desc)) : ?>
                                <p class="af-author-display-name"><?php echo esc_html($desc); ?></p>
                            <?php endif; ?>

                            <?php if (!empty($facebook) || !empty($twitter) || !empty($linkedin) || !empty($youtube) || !empty($instagram) || !empty($vk)) : ?>
                                <div class="social-navigation aft-small-social-menu">
                                    <ul>
                                        <?php if (!empty($facebook)) : ?>
                                            <li>
                                                <a href="<?php echo esc_url($facebook); ?>" target="_blank"></a>
                                            </li>
                                        <?php endif; ?>

                                        <?php if (!empty($instagram)) : ?>
                                            <li>
                                                <a href="<?php echo esc_url($instagram); ?>" target="_blank"></a>
                                            </li>
                                        <?php endif; ?>

                                        <?php if (!empty($twitter)) : ?>
                                            <li>
                                                <a href="<?php echo esc_url($twitter); ?>" target="_blank"></a>
                                            </li>
                                        <?php endif; ?>


                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            </section>
            <?php
            //print_pre($all_posts);
            // close the widget container
            echo $args['after_widget'];

            //$instance = parent::enternews_sanitize_data( $instance, $instance );


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
            $categories = enternews_get_terms();

            if (isset($categories) && !empty($categories)) {
                // generate the text input for the title of the widget. Note that the first parameter matches text_fields array entry
                echo parent::enternews_generate_text_input('enternews-author-info-title', __('About Author', 'enternews'), __('Title', 'enternews'));

                echo parent::enternews_generate_image_upload('enternews-author-info-image', __('Profile image', 'enternews'), __('Profile image', 'enternews'));
                echo parent::enternews_generate_text_input('enternews-author-info-name', __('Name', 'enternews'), __('Name', 'enternews'));
                echo parent::enternews_generate_text_input('enternews-author-info-desc', __('Descriptions', 'enternews'), '');
                echo parent::enternews_generate_text_input('enternews-author-info-facebook', __('Facebook', 'enternews'), '');
                echo parent::enternews_generate_text_input('enternews-author-info-instagram', __('Instagram', 'enternews'), '');
                echo parent::enternews_generate_text_input('enternews-author-info-twitter', __('Twitter', 'enternews'), '');



            }
        }
    }
endif;