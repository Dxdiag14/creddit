<?php

vc_add_param( 'vc_row', array(
  'type'    => 'textfield',
  'class'   => '',
  'heading'   => 'Anchor ID',
  'param_name'=> 'anchor_id',
  'value'   => ''
));


/* Gallery/Slideshow
---------------------------------------------------------- */
vc_map( array(
    'name'      => __( 'Image Gallery', 'auxin-elements' ),
    'base'      => 'aux_gallery',
    'icon'      => 'icon-wpb-images-stack',
    'category'  => __( 'auxin-elements', 'auxin-elements' ),
    'description' => __( 'Auxin image gallery', 'auxin-elements' ),
    'params' => array(
        array(
            'type'          => 'textfield',
            'heading'       => __( 'Widget title', 'auxin-elements' ),
            'param_name'    => 'title',
            'description'   => __( 'Enter text used as widget title (Note: located above content element).', 'auxin-elements' ),
        ),
        array(
            'type'          => 'dropdown',
            'heading'       => __( 'Gallery type', 'auxin-elements' ),
            'param_name'    => 'type',
            'value'         => array(
                __( 'Masonry'   , 'auxin-elements' ) => 'massonry',
                __( 'Packery'   , 'auxin-elements' ) => 'packery',
                __( 'Justified' , 'auxin-elements' ) => 'justified',
                __( 'Grid'      , 'auxin-elements' ) => 'grid'
                //__( 'Fix Grid'  , 'auxin-elements' ) => 'fix_grid',
            ),
            'description'   => __( 'Select gallery type.', 'auxin-elements' ),
        ),
        array(
            'type'          => 'attach_images',
            'heading'       => __( 'Images', 'auxin-elements' ),
            'param_name'    => 'ids',
            'value'         => '',
            'description'   => __( 'Select images from media library.', 'auxin-elements' )
        ),
        array(
            'type'          => 'textfield',
            'heading'       => __( 'Image size', 'auxin-elements' ),
            'param_name'    => 'img_size',
            'value'         => 'thumbnail',
            'description'   => __( 'Enter image size. Example: thumbnail, medium, large, full or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'auxin-elements' ),
            'dependency'    => array(
                'element'   => 'source',
                'value'     => 'media_library',
            ),
        ),
        array(
            'type'          => 'dropdown',
            'heading'       => __( 'On click action', 'auxin-elements' ),
            'param_name'    => 'onclick',
            'value' => array(
                __( 'None', 'auxin-elements' )                 => '',
                __( 'Link to large image', 'auxin-elements' )  => 'img_link_large',
                __( 'Open Lightbox', 'auxin-elements' )        => 'link_image',
                __( 'Open custom link', 'auxin-elements' )     => 'custom_link',
            ),
            'description'   => __( 'Select action for click action.', 'auxin-elements' ),
            'std'           => 'link_image',
        ),
        array(
            'type'          => 'textfield',
            'heading'       => __( 'Extra class name', 'auxin-elements' ),
            'param_name'    => 'el_class',
            'description'   => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'auxin-elements' ),
        ),
        array(
            'type'          => 'css_editor',
            'heading'       => __( 'CSS box', 'auxin-elements' ),
            'param_name'    => 'css',
            'group'         => __( 'Design Options', 'auxin-elements' ),
        )
    )
));


