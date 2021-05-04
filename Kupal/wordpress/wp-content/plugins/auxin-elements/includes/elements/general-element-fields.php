<?php
/**
 * General fields to be applied to all VC Elements
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
 */

function auxin_add_vc_extra_fields_for_all( $master_array ){

    foreach ( $master_array as $element_base => $element ) {

        if( isset( $element['is_vc'] ) && false !== auxin_is_true( $element['is_vc'] )  ){

            $master_array[ $element_base ]['params'][] = array(
                'type'          => 'dropdown',
                'heading'       => __( 'Transition', 'auxin-elements' ),
                'description'   => __( 'Choose the type of tranistion while the element enters in view.', 'auxin-elements' ),
                'param_name'    => 'inview_transition',
                'class'         => '',
                'group'         => __( 'Animation', 'auxin-elements' ),
                'value'         => array(
                    'none'                        => __( 'None' , 'auxin-elements' ),
                    'aux-invu-short-left'         => __( 'Short from left'  , 'auxin-elements' ),
                    'aux-invu-short-right'        => __( 'Short from right' , 'auxin-elements' ),
                    'aux-invu-short-bottom'       => __( 'Short from bottom', 'auxin-elements' ),
                    'aux-invu-short-top'          => __( 'Short from top'   , 'auxin-elements' ),
                    'aux-invu-medium-left'        => __( 'Medium from left'  , 'auxin-elements' ),
                    'aux-invu-medium-right'       => __( 'Medium from right' , 'auxin-elements' ),
                    'aux-invu-medium-bottom'      => __( 'Medium from bottom', 'auxin-elements' ),
                    'aux-invu-medium-top'         => __( 'Medium from top'   , 'auxin-elements' ),
                    'aux-invu-long-left'          => __( 'Long from left'  , 'auxin-elements' ),
                    'aux-invu-long-right'         => __( 'Long from right' , 'auxin-elements' ),
                    'aux-invu-long-bottom'        => __( 'Long from bottom', 'auxin-elements' ),
                    'aux-invu-long-top'           => __( 'Long from top'   , 'auxin-elements' ),
                    'aux-invu-scale-down-small'   => __( 'Small scale down'  , 'auxin-elements' ),
                    'aux-invu-scale-down-medium'  => __( 'Medium scale down' , 'auxin-elements' ),
                    'aux-invu-scale-down-large'   => __( 'Large scale down', 'auxin-elements' ),
                    'aux-invu-scale-up-small'     => __( 'Small scale up'  , 'auxin-elements' ),
                    'aux-invu-scale-up-medium'    => __( 'Medium scale up' , 'auxin-elements' ),
                    'aux-invu-scale-up-large'     => __( 'Large scale up', 'auxin-elements' )
                )
            );

            $master_array[ $element_base ]['params'][] = array(
                'type'          => 'textfield',
                'heading'       => __( 'Duration', 'auxin-elements' ),
                'description'   => __( 'The transition duration in milliseconds.', 'auxin-elements' ),
                'param_name'    => 'inview_duration',
                'class'         => '',
                'group'         => __( 'Animation', 'auxin-elements' ),
                'value'         => 600
            );

            $master_array[ $element_base ]['params'][] = array(
                'type'          => 'textfield',
                'heading'       => __( 'Delay', 'auxin-elements' ),
                'description'   => __( 'The delay before starting the animation in milliseconds.', 'auxin-elements' ),
                'param_name'    => 'inview_delay',
                'group'         => __( 'Animation', 'auxin-elements' )
            );

            $master_array[ $element_base ]['params'][] = array(
                'type'          => 'dropdown',
                'heading'       => __( 'Repeat', 'auxin-elements' ),
                'description'   => __( 'Choose "yes" to repeat the animation, or choose "no" to display the animation only once.', 'auxin-elements' ),
                'param_name'    => 'inview_repeat',
                'group'         => __( 'Animation', 'auxin-elements' ),
                'value'         => array(
                    'no'  => __( 'No' , 'auxin-elements' ),
                    'yes' => __( 'Yes', 'auxin-elements' )
                ),
                'def_value'     => 'no'
            );

            $master_array[ $element_base ]['params'][] = array(
                'type'          => 'textfield',
                'heading'       => __( 'Offset', 'auxin-elements' ),
                'description'   => __( 'The vertical offset in pixels in order to start playing the animation.', 'auxin-elements' ),
                'param_name'    => 'inview_offset',
                'group'         => __( 'Animation', 'auxin-elements' )
            );

        }

    }

    return $master_array;
}

add_filter( 'auxin_master_array_shortcodes', 'auxin_add_vc_extra_fields_for_all', 110, 1 );

