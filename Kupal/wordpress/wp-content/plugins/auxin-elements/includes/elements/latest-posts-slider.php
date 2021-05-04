<?php
/**
 * Latest Post slider element
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
 */
function auxin_get_post_slider_master_array( $master_array ) {

    $master_array['aux_latest_posts_slider'] = array(
        'name'                    => __('Latest Posts Slider ', 'auxin-elements' ),
        'auxin_output_callback'   => 'auxin_latest_posts_slider_callback',
        'base'                    => 'aux_latest_posts_slider',
        'description'             => __('Slider for latest posts.', 'auxin-elements' ),
        'class'                   => 'aux-widget-post-slider',
        'show_settings_on_create' => true,
        'weight'                  => 1,
        'is_widget'               => true,
        'is_shortcode'            => true,
        'is_so'                   => true,
        'is_vc'                   => true,
        'category'                => THEME_NAME,
        'group'                   => '',
        'admin_enqueue_js'        => '',
        'admin_enqueue_css'       => '',
        'front_enqueue_js'        => '',
        'front_enqueue_css'       => '',
        'icon'                    => 'aux-element aux-pb-icons-post-slider',
        'custom_markup'           => '',
        'js_view'                 => '',
        'html_template'           => '',
        'deprecated'              => '',
        'content_element'         => '',
        'as_parent'               => '',
        'as_child'                => '',
        'params'                  => array(
            array(
                'heading'           => __('Title','auxin-elements' ),
                'description'       => __('Latest post slider title, leave it empty if you don`t need title.', 'auxin-elements'),
                'param_name'        => 'title',
                'type'              => 'textfield',
                'value'             => '',
                'holder'            => '',
                'class'             => 'title',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
             array(
                'heading'           => __('Create slides from','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'post_type',
                'type'              => 'dropdown',
                'def_value'         => 'post',
                'value'             => array(
                    'post'          => __('Posts', 'auxin-elements' ),
                    'page'          => __('Pages', 'auxin-elements' ),
                ),
                'holder'            => '',
                'class'             => 'border',
                'admin_label'       => true,
                'dependency'        => '',
                'weight'            => '',
                'group'             => __( 'Query', 'auxin-elements' ),
                'edit_field_class'  => ''
            ),
            array(
                'heading'          => __('Slides number','auxin-elements' ),
                'description'      => __('Specifies maximum number of slides in slider.', 'auxin-elements' ),
                'param_name'       => 'slides_num',
                'type'             => 'textfield',
                'value'            => '10',
                'holder'           => '',
                'class'            => '',
                'admin_label'      => false,
                'dependency'       => '',
                'weight'           => '',
                'group'            => __( 'Query', 'auxin-elements' ),
                'edit_field_class' => ''
            ),
            array(
                'heading'          => __('Exclude posts','auxin-elements' ),
                'description'      => __('Post IDs separated by comma (eg. 53,34,87,25).', 'auxin-elements' ),
                'param_name'       => 'exclude',
                'type'             => 'textfield',
                'value'            => '',
                'holder'           => '',
                'class'            => '',
                'admin_label'      => false,
                'dependency'       => '',
                'weight'           => '',
                'group'            => __( 'Query', 'auxin-elements' ),
                'edit_field_class' => ''
            ),
            array(
                'heading'          => __('Include posts','auxin-elements' ),
                'description'      => __('Post IDs separated by comma (eg. 53,34,87,25).', 'auxin-elements' ),
                'param_name'       => 'include',
                'type'             => 'textfield',
                'value'            => '',
                'holder'           => '',
                'class'            => '',
                'admin_label'      => false,
                'dependency'       => '',
                'weight'           => '',
                'group'            => __( 'Query', 'auxin-elements' ),
                'edit_field_class' => ''
            ),
            array(
                'heading'          => __('Start offset','auxin-elements' ),
                'description'      => __('Number of post to displace or pass over.', 'auxin-elements' ),
                'param_name'       => 'offset',
                'type'             => 'textfield',
                'value'            => '',
                'holder'           => '',
                'class'            => '',
                'admin_label'      => false,
                'dependency'       => '',
                'weight'           => '',
                'group'            => __( 'Query', 'auxin-elements' ),
                'edit_field_class' => ''
            ),
            array(
                'heading'          => __('Order by','auxin-elements' ),
                'description'      => '',
                'param_name'       => 'order_by',
                'type'             => 'dropdown',
                'def_value'        => 'date',
                'value'            => array(
                    'date'            => __('Date', 'auxin-elements' ),
                    'menu_order date' => __('Menu Order', 'auxin-elements' ),
                    'title'           => __('Title', 'auxin-elements' ),
                    'ID'              => __('ID', 'auxin-elements' ),
                    'rand'            => __('Random', 'auxin-elements' ),
                    'comment_count'   => __('Comments', 'auxin-elements' ),
                    'modified'        => __('Date Modified', 'auxin-elements' ),
                    'author'          => __('Author', 'auxin-elements' ),
                ),
                'holder'           => '',
                'class'            => 'border',
                'admin_label'      => false,
                'dependency'       => '',
                'weight'           => '',
                'group'            => __( 'Query', 'auxin-elements' ),
                'edit_field_class' => ''
            ),
            array(
                'heading'          => __('Order direction','auxin-elements' ),
                'description'      => '',
                'param_name'       => 'order_dir',
                'type'             => 'dropdown',
                'def_value'        => 'DESC',
                'value'            => array(
                    'DESC'         => __('Descending', 'auxin-elements' ),
                    'ASC'          => __('Ascending', 'auxin-elements' ),
                ),
                'holder'           => '',
                'class'            => 'border',
                'admin_label'      => false,
                'dependency'       => '',
                'weight'           => '',
                'group'            => __( 'Query', 'auxin-elements' ),
                'edit_field_class' => ''
            ),
            array(
                'heading'          => __('Slider skin','auxin-elements' ),
                'description'      => '',
                'param_name'       => 'skin',
                'type'             => 'dropdown',
                'def_value'        => 'aux-light-skin',
                'value'            => array(
                    'aux-light-skin'       => __('Light and boxed', 'auxin-elements' ),
                    'aux-dark-skin'        => __('Dark and boxed', 'auxin-elements' ),
                    'aux-full-light-skin'  => __('Light overlay', 'auxin-elements' ),
                    'aux-full-dark-skin'   => __('Dark overlay', 'auxin-elements' ),
                ),
                'holder'           => '',
                'class'            => 'border',
                'admin_label'      => false,
                'dependency'       => '',
                'weight'           => '',
                'group'            => '' ,
                'edit_field_class' => ''
            ),
            array(
                'heading'          => __('Insert post title','auxin-elements' ),
                'description'      => '',
                'param_name'       => 'add_title',
                'type'             => 'aux_switch',
                'value'            => '1',
                'class'            => '',
                'admin_label'      => false,
                'dependency'       => '',
                'weight'           => '',
                'group'            => '' ,
                'edit_field_class' => ''
            ),
            array(
                'heading'          => __('Insert post meta','auxin-elements' ),
                'description'      => '',
                'param_name'       => 'add_meta',
                'type'             => 'aux_switch',
                'value'            => '1',
                'class'            => '',
                'dependency'       => array(
                    'element'      => 'add_title',
                    'value'        => '1'
                ),
                'admin_label'      => false,
                'weight'           => '',
                'group'            => '' ,
                'edit_field_class' => ''
            ),

            array(
                'heading'          => __('Grab the image from','auxin-elements' ),
                'description'      => '',
                'param_name'       => 'image_from',
                'type'             => 'dropdown',
                'def_value'        => 'auto',
                'value'            => array(
                    'auto'      => __('Auto select', 'auxin-elements' ),
                    'featured'  => __('Featured image', 'auxin-elements' ),
                    'first'     => __('First image in post', 'auxin-elements' ),
                    'custom'    => __('Custom image', 'auxin-elements' ),
                ),
                'holder'           => '',
                'class'            => 'border',
                'admin_label'      => false,
                'dependency'       => '',
                'weight'           => '',
                'group'            => __( 'Query', 'auxin-elements' ),
                'edit_field_class' => ''
            ),
            array(
                'heading'          => __('Background image','auxin-elements' ),
                'description'      => '',
                'param_name'       => 'custom_image',
                'type'             => 'attach_image',
                'value'            => '',
                'class'            => '',
                'admin_label'      => false,
                'dependency'       => array(
                    'element'      => 'image_from',
                    'value'        => 'custom'
                ),
                'weight'           => '',
                'group'            => '' ,
                'edit_field_class' => ''
            ),
            array(
                'heading'           => __('Exclude posts without image','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'exclude_without_image',
                'type'              => 'aux_switch',
                'value'             => '1',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),


            array(
                'heading'           => __('Slider image width','auxin-elements' ),
                'param_name'        => 'width',
                'type'              => 'textfield',
                'value'             => '960',
                'holder'            => '',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Slider image height','auxin-elements' ),
                'param_name'        => 'height',
                'type'              => 'textfield',
                'value'             => '560',
                'holder'            => '',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Arrow navigation','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'arrows',
                'type'              => 'aux_switch',
                'value'             => '0',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Space between slides','auxin-elements' ),
                'param_name'        => 'space',
                'type'              => 'textfield',
                'value'             => '5',
                'holder'            => '',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Looped navigation','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'loop',
                'type'              => 'aux_switch',
                'value'             => '1',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Slideshow','auxin-elements' ),
                'description'       => '',
                'param_name'        => 'slideshow',
                'type'              => 'aux_switch',
                'value'             => '0',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Slideshow delay in seconds','auxin-elements' ),
                'param_name'        => 'slideshow_delay',
                'type'              => 'textfield',
                'value'             => '2',
                'holder'            => '',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => array(
                    'element'       => 'slideshow',
                    'value'         => '1'
                ),
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Extra class name','auxin-elements'),
                'description'       => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'auxin-elements'),
                'param_name'        => 'extra_classes',
                'type'              => 'textfield',
                'value'             => '',
                'def_value'         => '',
                'holder'            => '',
                'class'             => 'extra_classes',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            )
        )
    );

    return $master_array;
}

add_filter( 'auxin_master_array_shortcodes', 'auxin_get_post_slider_master_array', 10, 1 );

/**
 * Dynamic element with result in columns
 * The front-end output of this element is returned by the following function
 *
 * @param  array  $atts              The array containing the parsed values from shortcode
 *                                   containing widget info too
 * @param  string $shortcode_content The shorcode content
 * @return string                    The output of element markup
 */
function auxin_latest_posts_slider_callback( $atts, $shortcode_content = null ){

    // Defining default attributes
    $default_atts = array(
        'title'                 => '',
        'slides_num'            => '10',
        'order_by'              => 'date',
        'order_dir'             => 'DESC',
        'post_type'             => 'post',
        'add_title'             => '1',
        'add_meta'              => '1',
        'image_from'            => 'auto',
        'offset'                => '',
        'include'               => '',
        'exclude_without_image' => '1',
        'exclude'               => '',
        'custom_image'          => '',
        'skin'                  => 'aux-light-skin',
        'width'                 => '960',
        'height'                => '560',
        'loop'                  => '1',
        'space'                 => '5',
        'slideshow'             => '0',
        'slideshow_delay'       => '2',
        'arrows'                => '0',

        'extra_classes'         => '', // custom css class names for this element
        'custom_el_id'          => '', // custom id attribute for this element
        'base_class'            => 'aux-widget-post-slider'  // base class name for container
    );

    $result = auxin_get_widget_scafold( $atts, $default_atts, $shortcode_content );
    extract( $result['parsed_atts'] );

    // query --------------------------------------

    // Create wp_query to get pages
    $query_args = array(
        'post_type'           => $post_type,
        'orderby'             => $order_by,
        'order'               => $order_dir,
        'offset'              => $offset,
        'posts__not_in'       =>  $exclude,
        'include_posts__in'   => $include,
        'post_status'         => 'publish',
        'posts_per_page'      => $slides_num, // -1 causes ignoring offset
        'ignore_sticky_posts' => 1
    );

    $post_counter = 0;

    $query_res = null;
    $query_res = new WP_Query( auxin_parse_query_args( $query_args ) );

    // skip building slider if no post results found
    if( ! $query_res->have_posts() ){
        return '';
    }

    ob_start();

    // widget header ------------------------------
    echo $result['widget_header'];
    echo $result['widget_title'];

    echo '<div class="master-carousel-slider aux-latest-posts-slider aux-no-js '.$skin.'" data-empty-height="'.$height.'" data-navigation="peritem" data-space="'.$space.'" data-auto-height="true" data-delay="'.$slideshow_delay.'" data-loop="'.$loop.'" data-autoplay="'.$slideshow.'">';

    // widget custom output -----------------------


    if( $query_res->have_posts() ): while ( $query_res->have_posts() ) : $query_res->the_post();

        // break the loop if it is reached to the limit
        if ( $exclude_without_image && $post_counter == $slides_num ) {
            break;
        } else {
            $post_counter ++;
        }

        $slide_image = '';

        // get image
        if ( 'custom' == $image_from && !empty( $custom_image ) ) {
            $slide_image = auxin_get_the_resized_image( $custom_image, $width, $height, true , 100 );
        } else {
            // $slide_image = auxin_get_auto_post_thumbnail( $query_res->post->ID, $image_from, $width, $height, true, 100, true );
            $slide_image = auxin_get_the_post_responsive_thumbnail(
                $query_res->post->ID,
                array(
                    'size'            => array( 'width' => $width, 'height' => $height ),
                    'add_hw'          => false,
                    'preloadable'     => false,
                    'preload_preview' => true,
                    'image_sizes'     => 'auto',
                    'srcset_sizes'    => 'auto',
                )
            );
        }

        //skip if post doesn't have image
        if ( $exclude_without_image && empty( $slide_image ) ) {
            $post_counter --;
            continue;
        }
?>
        <div class="aux-mc-item" >
                <div class="aux-slide-media">
                    <div class="aux-media-frame aux-media-image">
                        <?php echo $slide_image; ?>
                    </div>
                </div>
                <?php if( $add_title ) { ?>
                <section class="aux-info-container">
                    <div class="aux-slide-title">
                        <h3><a href="<?php the_permalink(); ?>"><?php echo auxin_get_trimmed_string( get_the_title(), 70, '...'); ?></a></h3>
                    </div>
                    <div class="aux-slide-info">
                        <?php if ( $add_meta ) { ?>
                        <time datetime="<?php echo get_the_date( DATE_W3C ); ?>" title="<?php echo get_the_date( DATE_W3C ); ?>" ><?php echo get_the_date(); ?></time>
                        <span class="entry-tax">
                            <?php // the_category(' '); we can use this template tag, but customizable way is needed! ?>
                            <?php $tax_name = 'category';
                                  if( $cat_terms = wp_get_post_terms( $query_res->post->ID, $tax_name ) ){
                                      foreach( $cat_terms as $term ){
                                          echo '<a href="'. get_term_link( $term->slug, $tax_name ) .'" title="'.__("View all posts in ", 'auxin-elements' ). $term->name .'" rel="category" >'. $term->name .'</a>';
                                      }
                                  }
                            ?>
                        </span>
                        <?php } ?>
                    </div>
                </section>
                <?php } ?>
        </div>

<?php endwhile;

    // skip building slider if no slide is generated
    if( ! $post_counter ){
        ob_get_clean();
        return '';
    }

    if ( $arrows ) { ?>
            <div class="aux-next-arrow aux-arrow-nav aux-white aux-round aux-hover-slide">
                <span class="aux-overlay"></span>
                <span class="aux-svg-arrow aux-medium-right"></span>
                <span class="aux-hover-arrow aux-svg-arrow aux-medium-right"></span>
            </div>
            <div class="aux-prev-arrow aux-arrow-nav aux-white aux-round aux-hover-slide">
                <span class="aux-overlay"></span>
                <span class="aux-svg-arrow aux-medium-left"></span>
                <span class="aux-hover-arrow aux-svg-arrow aux-medium-left"></span>
            </div>
<?php
    }
    endif;
    wp_reset_query();

    // widget footer ------------------------------
    echo '</div><!-- aux-col-wrapper -->';

    echo $result['widget_footer'];

    return ob_get_clean();
}
