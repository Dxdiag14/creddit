<?php

/*-----------------------------------------------------------------------------------*/
/*  Latest from Blog
/*-----------------------------------------------------------------------------------*/

add_shortcode( 'aux_timeline'    , 'auxin_shortcode_timeline' );

function auxin_shortcode_timeline( $atts, $content = null ) {

   // extract attrs to vars
   extract( shortcode_atts(
        array(
            'size'        =>  100, // section size
            'title'       => '', // widget header title
            'col'         => '33', // one .. six-column
            'view_more'   => 'yes',
            'more_label'  => 'view all',
            'num'         =>  5,  // fetch num
            'excerpt_len' => '120',
            'cat_id'      => '', // cat id to display. '' gets all cats
            'view_thumb'  => 'yes', // display thumbnail or not
            'thumb_mode'  => 'left', // 'top': normal thumb on top , 'left': normal thumb on left, 'icon': icon size thumb
            'date_type'   => 'big',
            'orderby'     => '',
            'order'       => '',
            'layout'      => 'center'
        )
        , $atts, 'aux_timeline' )
    );

    // sanitize sql order and orderby
    $orderby = $orderby ? sanitize_sql_orderby( $orderby ) : 'date';
    $order   = $order   ? auxin_sanitize_sql_order(   $order   ) : 'DESC';

    // validate number fetched items
    $num = is_numeric( $num ) ? $num : -1;

    // get thumbnail diemntion --------------------------------------
    $vimeo_height = 273;

    // chane thumbnail size to 60x60 if it is mini mode
    if($thumb_mode == "mini") {
        $dimentions[0] = 60;
        $dimentions[1] = 60;
    }else{
        // actual col size
        // get number of grid column
        $wrapper_size = empty($size)?100:$size;
        $col_actual = ($wrapper_size / 100) * (int)$col;
        $col_num = floor(100 / $col_actual);
        $col_num = $col_num > 4?4:$col_num; // max column num is 4
        // get thumbnsil size name
        $image_size_name = "i".$col_num;

        // get suite thumb size
        $thumb_size = $image_size_name;
        $dimentions = auxin_get_image_size( $thumb_size.'_1' );

        // the left mode is half size of top mode, so for making image retina
        // bigger size is not needed
        if($thumb_mode == "top"){
            // retinafy thumbnail
            $dimentions[0] =  1.5 * $dimentions[0];
            $dimentions[1] =  1.5 * ($dimentions[1] - 10);

        }else{ // if the image is on left
            $dimentions[1] -= 10;
        }

    }


    // just view custom taxonomies if tax id is set ----------------
    $tax_args = array('taxonomy' => 'category', 'terms' => $cat_id );
    if(empty($cat_id) || $cat_id == "all" ) $tax_args = "";

    // create wp_query to get latest items
    $args = array(
        'post_type'         => 'post',
        'orderby'           => $orderby,
        'order'             => $order,
        'post_status'       => 'publish',
        'posts_per_page'    => $num,
        'ignore_sticky_posts'=> 1,
        'tax_query'         => array($tax_args)
    );

    $th_query = null;
    $th_query = new WP_Query($args);



    ob_start();
?>

        <section class="widget-blog widget-container widget-timeline" >

           <?php

           if( ! empty($title) )
                echo get_widget_title( $title ); ?>


           <div class="widget-inner">
                <div class="aux-timeline aux-<?php echo esc_attr( $layout ); ?>" data-layout="<?php echo esc_attr( $layout ); ?>" >

<?php if( $th_query->have_posts() ):  while ($th_query->have_posts()) : $th_query->the_post(); ?>




<?php
    $post_format = get_post_format($th_query->post->ID);
    $has_attach  = FALSE;
    $the_attach  = "";
    $show_title  = true;

    switch ($post_format) {
        case 'aside':

            break;
        case 'gallery':

            // if the mode is not mini size
            if($thumb_mode != "mini") {
                // if post has featured image, use featured image instead of gallery images
                $has_attach = has_post_thumbnail($th_query->post->ID);
                if($has_attach) {
                    $the_attach = auxin_get_the_post_thumbnail($th_query->post->ID, $dimentions[0], $dimentions[1], true, 75);

                    $the_media = '<div class="imgHolder">'.
                                    '<a href="'.auxin_get_the_attachment_url($th_query->post->ID, "full").'" data-rel="prettyPhoto">'.
                                        $the_attach.
                                    '</a>'.
                                 '</div>';

                // display gallery images as slider if featured image is not set
                } else {

                    $slider  = '[flexslider slideshow="no" effect="slide" nav_type="none" easing="easeInOutQuad" ]';
                    $has_attach = false;

                    for ($i=1; $i <= 5; $i++) {
                        $img_url = get_post_meta($th_query->post->ID, "axi_gallery_image".$i, true);
                        if(!empty($img_url) ){
                            $has_attach = true;
                            $img_url = auxin_get_the_absolute_image_url($img_url);
                            $slider .= '[simple_slide src="'.auxin_get_the_resized_image_src($img_url, $dimentions[0], $dimentions[1], true, 75 ).'"  ]';
                        }
                    }

                    $slider .= '[/flexslider]';
                    if(!$has_attach) break;

                    $the_media = do_shortcode($slider);
                }

                break;

            // if thumb mode is mini, just echo small image intead of slider
            }else {
                $has_attach = has_post_thumbnail($th_query->post->ID);
                // if post has featured image, use featured image instead of gallery image
                if( $has_attach ) {
                    $the_attach = auxin_get_the_post_thumbnail_src($th_query->post->ID, $dimentions[0], $dimentions[1], true, 75);

                // display first gallery image as slider if featured image is not set
                }else {
                    $the_attach = get_post_meta($th_query->post->ID, "axi_gallery_image1", true);
                    $has_attach = !empty($the_attach);
                    if(!$has_attach) break;
                    $the_attach = auxin_get_the_absolute_image_url($the_attach);
                }

                $the_media = '<div class="imgHolder">'.
                                '<a href="'.get_permalink().'" >'.
                                    '<img src="'.$the_attach.'" alt="" />'.
                                '</a>'.
                             '</div>';
                break;
            }

            break;
        case 'image':
            $has_attach = has_post_thumbnail();
            if(!$has_attach) break;
            $the_attach = auxin_get_the_post_thumbnail($th_query->post->ID, $dimentions[0], $dimentions[1], true, 75);

            $the_media = '<div class="imgHolder">'.
                            '<a href="'.auxin_get_the_attachment_url($th_query->post->ID, "full").'" data-rel="prettyPhoto">'.
                                $the_attach.
                            '</a>'.
                         '</div>';
            break;

        case 'link':
            $the_link = get_post_meta($th_query->post->ID, "the_link", true);
            $show_title = TRUE;
            $has_attach = false;
            if(!$has_attach) break;
            break;

        case 'video':
            $video_link = get_post_meta($th_query->post->ID, "youtube", true);
            $mp4        = get_post_meta($th_query->post->ID, "mp4" , true);
            $ogg        = get_post_meta($th_query->post->ID, "ogg" , true);
            $webm       = get_post_meta($th_query->post->ID, "webm", true);
            $flv        = get_post_meta($th_query->post->ID, "flv" , true);
            $poster     = get_post_meta($th_query->post->ID, "poster", true);
            $skin       = get_post_meta($th_query->post->ID, "skin"  , true);

            // if it is mini size just display a thumbnail
            if($thumb_mode == "mini") {
                if(has_post_thumbnail()){
                    $the_attach = auxin_get_the_post_thumbnail($th_query->post->ID, $dimentions[0], $dimentions[1], true, 75);
                    $the_media = '<div class="imgHolder">'.
                                    '<a href="'.get_permalink().'">'.
                                        $the_attach.
                                    '</a>'.
                                 '</div>';
                }
                break;
            }

            // if the feature image is set, display feature image instead
            $has_attach = has_post_thumbnail();
            if($has_attach) {
                $the_attach = auxin_get_the_post_thumbnail($th_query->post->ID, $dimentions[0], $dimentions[1], true, 75);

                $the_media = '<div class="imgHolder">'.
                                '<a href="'.get_permalink().'" >'.
                                    $the_attach.
                                '</a>'.
                             '</div>';
                break;
            }

            $has_attach = (!empty($video_link) || !empty($mp4) || !empty($ogg) || !empty($webm) || !empty($flv));
            if(!$has_attach) break;

            $the_attach = do_shortcode('[video_element fit="yes" height="'.$vimeo_height.'" url="'.$video_link.'" mp4="'.$mp4.'" ogg="'.$ogg.'" webm="'.$webm.'" flv="'.$flv.'" poster="'.$poster.'" skin="'.$skin.'" uid="axi_vid'.$th_query->post->ID.'" size="0" ]');

            $the_media = $the_attach;
            echo '<style type="text/css"> div.jp-video div.jp-jplayer { min-height: '.(($dimentions[1]/2)-75).'px; }</style>';
            unset($video_link, $mp4,$ogg,$webm,$flv,$poster);
            break;

        case 'audio':
            $mp3        = get_post_meta($th_query->post->ID, "mp3" , true);
            $oga        = get_post_meta($th_query->post->ID, "oga" , true);
            $skin       = get_post_meta($th_query->post->ID, "audio_skin"  , true);
            $soundcloud = get_post_meta($th_query->post->ID, "soundcloud"  , true);

            if($thumb_mode == "mini") {
                if(has_post_thumbnail()){
                    $the_attach = auxin_get_the_post_thumbnail($th_query->post->ID, $dimentions[0], $dimentions[1], true, 75);
                    $the_media = '<div class="imgHolder">'.
                                    '<a href="'.get_permalink().'">'.
                                        $the_attach.
                                    '</a>'.
                                 '</div>';
                }
                break;
            }

            $has_attach = (!empty($mp3) || !empty($oga) || !empty($soundcloud));
            if(!$has_attach) break;
            if(!empty($mp3) || !empty($oga))
                $the_attach = do_shortcode('[audio mp3="'.$mp3.'" ogg="'.$oga.'" skin="'.$skin.'" uid="axi_au'.$th_query->post->ID.'" size="0" ]');
            else
                $the_attach = do_shortcode($soundcloud);
            $the_media = $the_attach;
            unset($mp3,$oga,$skin, $soundcloud);
            break;


        case 'quote':
            $quote  = get_the_excerpt();
            $author = get_post_meta($th_query->post->ID, "the_author", true);
            $show_title = false;
            $has_attach = false;
            $quote  = auxin_get_trimmed_string($quote,$excerpt_len, " ...");
            $quote .= "<br/>- <cite>".$author."</cite>";
            $the_attach = do_shortcode('[blockquote size="0" indent="no" ]'.$quote.'[/blockquote]');
            unset($quote);
            break;

        default:
            $has_attach = has_post_thumbnail();
            if(!$has_attach) {
                $the_attach = auxin_get_first_image_from_string(get_the_content());
                $has_attach = !empty($the_attach);
            }else {
                $the_attach = auxin_get_the_post_thumbnail($th_query->post->ID, $dimentions[0], $dimentions[1], true, 75);
            }

            if(!$has_attach) break;

            $the_media = '<div class="imgHolder">'.
                            '<a href="'.get_permalink().'">'.
                                $the_attach.
                            '</a>'.
                         '</div>';
            break;
    }

?>

                    <article class="aux-block <?php echo "date-type-".$date_type." "; echo ($thumb_mode != "top")? $thumb_mode : "thumb_top" ; ?>">
                       <figure>
                           <?php if ( $has_attach  && ($view_thumb == "yes") ) {
                                echo $the_media;
                            } ?>


                            <figcaption>
                                <div class="entry-header">
                                    <h4 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                    <div class="entry-format">
                                        <a href="<?php the_permalink(); ?>" class="post-format format-<?php echo get_post_format(); ?>"> </a>
                                        <?php if($date_type == "big") { ?>
                                        <div class="cell-date">
                                            <em> </em><em> </em>
                                            <time datetime="<?php echo get_the_date( DATE_W3C ); ?>" title="<?php echo get_the_date( DATE_W3C ); ?>" >
                                                <strong><?php the_time('d')?></strong>
                                                <span><?php the_time('M')?></span>
                                            </time>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="entry-content">
                                    <?php if($date_type == "inline" && $post_format != "quote") { ?>
                                    <time datetime="<?php echo get_the_date( DATE_W3C ); ?>" title="<?php echo get_the_date( DATE_W3C ); ?>" ><?php the_date(); ?></time>
                                    <?php } ?>

                                    <?php if($post_format == "quote") {
                                        echo $the_attach;
                                    } elseif($excerpt_len > 0) { ?>
                                    <p><?php auxin_the_trimmed_string(get_the_excerpt(),$excerpt_len); ?></p>
                                    <?php } ?>
                                </div>
                            </figcaption>
                       </figure>
                   </article><!-- end-timeline-block -->

<?php   endwhile; endif;
    wp_reset_query();
?>
                </div><!-- end-timeline-wrapper -->
            </div><!-- widget-inner -->

            <?php if($view_more == "yes" ) {
                $view_all_link = esc_url( auxin_get_option( 'blog_view_all_btn_link', home_url() ) );
            ?>
            <a href="<?php echo $view_all_link; ?>" class="more right" ><?php echo $more_label; ?></a>
            <?php } unset( $view_all_link ); ?>

        </section><!-- widget-blog -->

<?php
    return ob_get_clean();
}


/**
 * Adds aux_row shortcode for adding row for columns
 */
function auxin_aux_row_shortcode( $atts , $content = null ) {

    if( empty( $atts['tablet-columns'] ) && ! empty( $atts['columns'] ) ){
        $atts['tablet-columns'] = ceil( (int)$atts['columns'] / 2 );
    }

    // parse attributes
    $parsed_atts = shortcode_atts(
        array(
            'columns'        => 3,
            'tablet-columns' => '',
            'mobile-columns' => 1
        ),
        $atts,
        'aux_row'
    );

    // collect custom layout css classes
    $classes = '';

    if( $parsed_atts['columns'] ){
        $classes .= esc_attr( 'aux-col' . $parsed_atts['columns'] ) . ' ';
    }
    if( $parsed_atts['tablet-columns'] ){
        $classes .= esc_attr( 'aux-tb-col' . $parsed_atts['tablet-columns'] ) . ' ';
    }
    if( $parsed_atts['mobile-columns'] ){
        $classes .= esc_attr( 'aux-mb-col' . $parsed_atts['mobile-columns'] ) . ' ';
    }

    // Return code
    return '<div class="aux-row '. trim( $classes ) .'">'. do_shortcode( $content ) .'</div>';
}
add_shortcode( 'aux_row', 'auxin_aux_row_shortcode' );


/**
 * Adds aux_col shortcode for adding simple column shortcode for layout purposes
 */
function auxin_aux_col_shortcode( $atts , $content = null ) {
    // Return code
    return '<div class="aux-col">'. do_shortcode( $content ) .'</div>';
}
add_shortcode( 'aux_col', 'auxin_aux_col_shortcode' );
