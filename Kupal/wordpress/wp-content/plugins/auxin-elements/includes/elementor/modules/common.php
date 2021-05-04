<?php
namespace Auxin\Plugin\CoreElements\Elementor\Modules;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Core\Schemes\Typography;
use Elementor\Control_Media;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;


class Common {

    /**
     * Instance of this class.
     *
     * @var      object
     */
    protected static $instance = null;


    function __construct(){

        // Add new controls to advanced tab globally
        add_action( "elementor/element/after_section_end", array( $this, 'add_position_controls_section'  ), 10, 3 );

        // Go pro notice for parallax options
        add_action( "elementor/element/after_section_end", array( $this, 'add_parallax_go_pro_notice'     ), 15, 3 );
        add_action( "elementor/element/after_section_end", array( $this, 'add_transition_controls_section'), 18, 3 );

        add_action( "elementor/element/after_section_end", array( $this, 'add_extra_controls_section'     ), 19, 3 );
        add_action( "elementor/element/after_section_end", array( $this, 'add_pseudo_background_controls' ), 20, 3 );
        add_action( "elementor/element/after_section_end", array( $this, 'add_custom_css_controls_section'), 25, 3 );


        // Renders attributes for all Elementor Elements
        // add_action( 'elementor/frontend/widget/before_render' , array( $this, 'render_widget_attributes' ) );

        // Render the custom CSS
        if ( ! defined('ELEMENTOR_PRO_VERSION') ) {
            add_action( 'elementor/element/parse_css', array( $this, 'add_post_css' ), 10, 2 );
        }
    }

    /**
     * Return an instance of this class.
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance() {
        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Add custom css control to all elements
     *
     * @return void
     */
    public function add_custom_css_controls_section( $widget, $section_id, $args ){

        if( 'section_custom_css_pro' !== $section_id ){
            return;
        }

        if( ! defined('ELEMENTOR_PRO_VERSION') ) {

            $widget->start_controls_section(
                'aux_core_common_custom_css_section',
                array(
                    'label'     => __( 'Custom CSS', 'auxin-elements' ),
                    'tab'       => Controls_Manager::TAB_ADVANCED
                )
            );

            $widget->add_control(
                'custom_css',
                array(
                    'type'        => Controls_Manager::CODE,
                    'label'       => __( 'Custom CSS', 'auxin-elements' ),
                    'label_block' => true,
                    'language'    => 'css'
                )
            );
            ob_start();?>
<pre>
Examples:
// To target main element
selector { color: red; }
// For child element
selector .child-element{ margin: 10px; }
</pre>
            <?php
            $example = ob_get_clean();

            $widget->add_control(
                'custom_css_description',
                array(
                    'raw'             => __( 'Use "selector" keyword to target wrapper element.', 'auxin-elements' ). $example,
                    'type'            => Controls_Manager::RAW_HTML,
                    'content_classes' => 'elementor-descriptor',
                    'separator'       => 'none'
                )
            );

            $widget->end_controls_section();
        }

    }



    /**
     * Add controls to advanced section for adding background image to pseudo elements
     *
     * @return void
     */
    public function add_pseudo_background_controls( $widget, $section_id, $args ){

        $target_sections = array('section_custom_css');

        if( ! defined('ELEMENTOR_PRO_VERSION') ) {
            $target_sections[] = 'section_custom_css_pro';
        }

        if( ! in_array( $section_id, $target_sections ) ){
            return;
        }

        if( in_array( $widget->get_name(), array('section') ) ){
            return;
        }

        // Adds general background options to pseudo elements
        // ---------------------------------------------------------------------
        $widget->start_controls_section(
            'aux_core_common_background_pseudo',
            array(
                'label'     => __( 'Pseudo Background (developers)', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_ADVANCED
            )
        );

        $widget->add_control(
            'background_pseudo_description',
            array(
                'raw'  => __( 'Adds background to pseudo elements like ::before and ::after selectors. (developers only)', 'auxin-elements' ),
                'type' => Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-descriptor'
            )
        );

        $widget->add_control(
            'background_pseudo_before_heading',
            array(
                'label'     => __( 'Background ::before', 'auxin-elements' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before'
            )
        );

        $widget->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'background_pseudo_before',
                'types'    => array( 'classic', 'gradient'),
                'selector' => '{{WRAPPER}}:before'
            )
        );

        $widget->add_control(
            'background_pseudo_after_heading',
            array(
                'label'     => __( 'Background ::after', 'auxin-elements' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before'
            )
        );

        $widget->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'background_pseudo_after',
                'types'    => array( 'classic', 'gradient'),
                'selector' => '{{WRAPPER}}:after'
            )
        );

        $widget->end_controls_section();
    }


    /**
     * Add parallax pro notice to advanced section
     *
     * @return void
     */
    public function add_parallax_go_pro_notice( $widget, $section_id, $args ){

        if( defined('THEME_PRO') && THEME_PRO ){
            return;
        }

        if( in_array( $widget->get_name(), array('section') ) ){
            return;
        }

        // Anchor element sections
        $target_sections = array('section_custom_css');

        if( ! defined('ELEMENTOR_PRO_VERSION') ) {
            $target_sections[] = 'section_custom_css_pro';
        }

        if( ! in_array( $section_id, $target_sections ) ){
            return;
        }

        $widget->start_controls_section(
            'aux_pro_common_parallax_notice',
            array(
                'label'     => __( 'Parallax', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_ADVANCED
            )
        );

        $widget->add_control(
            'parallax_go_pro_notice',
            array(
                'type' => Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-descriptor',
                'raw' => '<div class="auxin-elementor-panel-notice">' .
                        '<i class="auxin-elementor-upgrade-notice-icon eicon-favorite" aria-hidden="true"></i>
                        <div class="auxin-elementor-panel-notice-title">' .
                            __( 'Parallax Effect', 'auxin-elements' ) .
                        '</div>
                        <div class="auxin-elementor-panel-notice-message">' .
                            __( 'Parallax options let you add parallax effect to any widget.', 'auxin-elements' ) .
                        '</div>
                        <div class="auxin-elementor-panel-notice-message">' .
                            __( 'This feature is only available on Phlox Pro.', 'auxin-elements' ) .
                        '</div>
                        <a class="auxin-elementor-panel-notice-link elementor-button elementor-button-default auxin-elementor-go-pro-link" href="http://phlox.pro/go-pro/?utm_source=elementor-panel&utm_medium=phlox-free&utm_campaign=phlox-go-pro&utm_content=parallax" target="_blank">' .
                            __( 'Get Phlox Pro', 'auxin-elements' ) .
                        '</a>
                        </div>'
            )
        );

        $widget->end_controls_section();
    }


    /**
     * Add transition controls to advanced section
     *
     * @return void
     */
    public function add_transition_controls_section( $widget, $section_id, $args ){

        // Anchor element sections
        $target_sections = array('section_custom_css');

        if( ! defined('ELEMENTOR_PRO_VERSION') ) {
            $target_sections[] = 'section_custom_css_pro';
        }

        if( ! in_array( $section_id, $target_sections ) ){
            return;
        }

        // Adds transition options to all elements
        // ---------------------------------------------------------------------
        $widget->start_controls_section(
            'aux_core_common_inview_transition',
            array(
                'label'     => __( 'Entrance Animation', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_ADVANCED
            )
        );

        $widget->add_control(
            'aux_animation_name',
            array(
                'label'   => __( 'Animation', 'auxin-elements' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    ''                           => 'None',

                    'aux-fade-in'                => 'Fade In',
                    'aux-fade-in-down'           => 'Fade In Down',
                    'aux-fade-in-down-1'         => 'Fade In Down 1',
                    'aux-fade-in-down-2'         => 'Fade In Down 2',
                    'aux-fade-in-up'             => 'Fade In Up',
                    'aux-fade-in-up-1'           => 'Fade In Up 1',
                    'aux-fade-in-up-2'           => 'Fade In Up 2',
                    'aux-fade-in-left'           => 'Fade In Left',
                    'aux-fade-in-left-1'         => 'Fade In Left 1',
                    'aux-fade-in-left-2'         => 'Fade In Left 2',
                    'aux-fade-in-right'          => 'Fade In Right',
                    'aux-fade-in-right-1'        => 'Fade In Right 1',
                    'aux-fade-in-right-2'        => 'Fade In Right 2',
                    'aux-fade-in-custom'         => 'Fade In - Custom',

                    // Slide Animation
                    'aux-slide-from-right'       => 'Slide From Right',
                    'aux-slide-from-left'        => 'Slide From Left',
                    'aux-slide-from-top'         => 'Slide From Top',
                    'aux-slide-from-bot'         => 'Slide From Bottom',

                    // Mask Animation
                    'aux-mask-from-top'          => 'Mask From Top',
                    'aux-mask-from-bot'          => 'Mask From Bottom',
                    'aux-mask-from-left'         => 'Mask From Left',
                    'aux-mask-from-right'        => 'Mask From Right',

                    'aux-rotate-in'              => 'Rotate In',
                    'aux-rotate-in-down-left'    => 'Rotate In Down Left',
                    'aux-rotate-in-down-left-1'  => 'Rotate In Down Left 1',
                    'aux-rotate-in-down-left-2'  => 'Rotate In Down Left 2',
                    'aux-rotate-in-down-right'   => 'Rotate In Down Right',
                    'aux-rotate-in-down-right-1' => 'Rotate In Down Right 1',
                    'aux-rotate-in-down-right-2' => 'Rotate In Down Right 2',
                    'aux-rotate-in-up-left'      => 'Rotate In Up Left',
                    'aux-rotate-in-up-left-1'    => 'Rotate In Up Left 1',
                    'aux-rotate-in-up-left-2'    => 'Rotate In Up Left 2',
                    'aux-rotate-in-up-right'     => 'Rotate In Up Right',
                    'aux-rotate-in-up-right-1'   => 'Rotate In Up Right 1',
                    'aux-rotate-in-up-right-2'   => 'Rotate In Up Right 2',
                    'aux-rotate-custom'          => 'Rotate In - Custom',

                    'aux-zoom-in'                => 'Zoom In',
                    'aux-zoom-in-1'              => 'Zoom In 1',
                    'aux-zoom-in-2'              => 'Zoom In 2',
                    'aux-zoom-in-3'              => 'Zoom In 3',

                    'aux-scale-up'               => 'Scale Up',
                    'aux-scale-up-1'             => 'Scale Up 1',
                    'aux-scale-up-2'             => 'Scale Up 2',

                    'aux-scale-down'             => 'Scale Down',
                    'aux-scale-down-1'           => 'Scale Down 1',
                    'aux-scale-down-2'           => 'Scale Down 2',
                    'aux-scale-custom'           => 'Scale - Custom',

                    'aux-flip-in-down'           => 'Flip In Down',
                    'aux-flip-in-down-1'         => 'Flip In Down 1',
                    'aux-flip-in-down-2'         => 'Flip In Down 2',
                    'aux-flip-in-up'             => 'Flip In Up',
                    'aux-flip-in-up-1'           => 'Flip In Up 1',
                    'aux-flip-in-up-2'           => 'Flip In Up 2',
                    'aux-flip-in-left'           => 'Flip In Left',
                    'aux-flip-in-left-1'         => 'Flip In Left 1',
                    'aux-flip-in-left-2'         => 'Flip In Left 2',
                    'aux-flip-in-left-3'         => 'Flip In Left 3',
                    'aux-flip-in-right'          => 'Flip In Right',
                    'aux-flip-in-right-1'        => 'Flip In Right 1',
                    'aux-flip-in-right-2'        => 'Flip In Right 2',
                    'aux-flip-in-right-3'        => 'Flip In Right 3',

                    'aux-pulse'                  => 'Pulse In 1' ,
                    'aux-pulse1'                 => 'Pulse In 2',
                    'aux-pulse2'                 => 'Pulse In 3',
                    'aux-pulse3'                 => 'Pulse In 4',
                    'aux-pulse4'                 => 'Pulse In 5',

                    'aux-pulse-out-1'            => 'Pulse Out 1' ,
                    'aux-pulse-out-2'            => 'Pulse Out 2' ,
                    'aux-pulse-out-3'            => 'Pulse Out 3' ,
                    'aux-pulse-out-4'            => 'Pulse Out 4' ,

                    // Specials
                    'aux-shake'                  => 'Shake',
                    'aux-bounce-in'              => 'Bounce In',
                    'aux-jack-in-box'            => 'Jack In the Box'
                ),
                'default'            => '',
                'prefix_class'       => 'aux-appear-watch-animation ',
                'label_block'        => false
            )
        );

        $widget->add_control(
            'aux_fade_in_custom_x',
            array(
                'label'     => __( 'Fade In For X', 'auxin-elements' ) . ' (px)',
                'type'      => Controls_Manager::NUMBER,
                'default'   => '',
                'min'       => -500,
                'max'       => 500,
                'step'      => 25,
                'default'   => 50,
                'selectors'    => array(
                    '{{WRAPPER}}.aux-appear-watch-animation' => '--aux-anim-fade-in-from-x:{{SIZE}}px;'
                ),
                'condition' => array(
                    'aux_animation_name' => 'aux-fade-in-custom'
                ),
                'render_type' => 'template'
            )
        );

        $widget->add_control(
            'aux_fade_in_custom_y',
            array(
                'label'     => __( 'Fade In For Y', 'auxin-elements' ) . ' (px)',
                'type'      => Controls_Manager::NUMBER,
                'default'   => '',
                'min'       => -500,
                'max'       => 500,
                'step'      => 25,
                'default'   => 50,
                'selectors' => array(
                    '{{WRAPPER}}.aux-appear-watch-animation' => '--aux-anim-fade-in-from-y:{{SIZE}}px;'
                ),
                'condition' => array(
                    'aux_animation_name' => 'aux-fade-in-custom'
                ),
                'render_type' => 'template'
            )
        );

        $widget->add_control(
            'aux_scale_custom',
            array(
                'label'     => __( 'Scale', 'auxin-elements' ) . '',
                'type'      => Controls_Manager::NUMBER,
                'default'   => '',
                'min'       => 0.1,
                'max'       => 3,
                'step'      => 0.1,
                'selectors' => array(
                    '{{WRAPPER}}.aux-appear-watch-animation' => '--aux-scale-custom:{{SIZE}};'
                ),
                'condition' => array(
                    'aux_animation_name' => 'aux-scale-custom'
                ),
                'render_type' => 'template'
            )
        );

        $widget->add_control(
            'aux_rotate_custom_deg',
            array(
                'label'     => __( 'Rotate Degree', 'auxin-elements' ) . '',
                'type'      => Controls_Manager::NUMBER,
                'default'   => '',
                'min'       => -360,
                'max'       => 360,
                'step'      => 10,
                'selectors' => array(
                    '{{WRAPPER}}.aux-appear-watch-animation' => '--aux-anim-rotate-deg:{{SIZE}}deg;'
                ),
                'condition' => array(
                    'aux_animation_name' => 'aux-rotate-custom'
                ),
                'render_type' => 'template'
            )
        );

        $widget->add_control(
            'aux_rotate_custom_origin',
            array(
                'label'     => __( 'Rotate Origin', 'auxin-elements' ) . '',
                'type'      => Controls_Manager::SELECT,
                'default'   => 'left bottom',
                'options'   => [
                    'left bottom'  => __( 'Left Bottom', 'auxin-elements' ),
                    'right bottom' => __( 'Right Bottom', 'auxin-elements' ),
                ],
                'selectors' => array(
                    '{{WRAPPER}}.aux-appear-watch-animation' => '--aux-anim-rotate-origin:{{VALUE}};'
                ),
                'condition' => array(
                    'aux_animation_name' => 'aux-rotate-custom'
                ),
                'render_type' => 'template'
            )
        );

        $widget->add_control(
            'aux_animation_duration',
            array(
                'label'     => __( 'Duration', 'auxin-elements' ) . ' (ms)',
                'type'      => Controls_Manager::NUMBER,
                'default'   => '',
                'min'       => 0,
                'step'      => 100,
                'selectors'    => array(
                    '{{WRAPPER}}' => 'animation-duration:{{SIZE}}ms;'
                ),
                'condition' => array(
                    'aux_animation_name!' => ''
                ),
                'render_type' => 'template'
            )
        );

        $widget->add_control(
            'aux_animation_delay',
            array(
                'label'     => __( 'Delay', 'auxin-elements' ) . ' (ms)',
                'type'      => Controls_Manager::NUMBER,
                'default'   => '',
                'min'       => 0,
                'step'      => 100,
                'selectors' => array(
                    '{{WRAPPER}}' => 'animation-delay:{{SIZE}}ms;'
                ),
                'condition' => array(
                    'aux_animation_name!' => ''
                )
            )
        );

        $widget->add_control(
            'aux_animation_easing',
            array(
                'label'   => __( 'Easing', 'auxin-elements' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    ''                       => 'Default',
                    'initial'                => 'Initial',

                    'linear'                 => 'Linear',
                    'ease-out'               => 'Ease Out',
                    '0.19,1,0.22,1'          => 'Ease In Out',

                    '0.47,0,0.745,0.715'     => 'Sine In',
                    '0.39,0.575,0.565,1'     => 'Sine Out',
                    '0.445,0.05,0.55,0.95'   => 'Sine In Out',

                    '0.55,0.085,0.68,0.53'   => 'Quad In',
                    '0.25,0.46,0.45,0.94'    => 'Quad Out',
                    '0.455,0.03,0.515,0.955' => 'Quad In Out',

                    '0.55,0.055,0.675,0.19'  => 'Cubic In',
                    '0.215,0.61,0.355,1'     => 'Cubic Out',
                    '0.645,0.045,0.355,1'    => 'Cubic In Out',

                    '0.895,0.03,0.685,0.22'  => 'Quart In',
                    '0.165,0.84,0.44,1'      => 'Quart Out',
                    '0.77,0,0.175,1'         => 'Quart In Out',

                    '0.895,0.03,0.685,0.22'  => 'Quint In',
                    '0.895,0.03,0.685,0.22'  => 'Quint Out',
                    '0.895,0.03,0.685,0.22'  => 'Quint In Out',

                    '0.95,0.05,0.795,0.035'  => 'Expo In',
                    '0.19,1,0.22,1'          => 'Expo Out',
                    '1,0,0,1'                => 'Expo In Out',

                    '0.6,0.04,0.98,0.335'    => 'Circ In',
                    '0.075,0.82,0.165,1'     => 'Circ Out',
                    '0.785,0.135,0.15,0.86'  => 'Circ In Out',

                    '0.6,-0.28,0.735,0.045'  => 'Back In',
                    '0.175,0.885,0.32,1.275' => 'Back Out',
                    '0.68,-0.55,0.265,1.55'  => 'Back In Out'
                ),
                'selectors' => array(
                    '{{WRAPPER}}' => 'animation-timing-function:cubic-bezier({{VALUE}});'
                ),
                'condition' => array(
                    'aux_animation_name!' => ''
                ),
                'default'      => '',
                'return_value' => ''
            )
        );

        $widget->add_control(
            'aux_animation_count',
            array(
                'label'   => __( 'Repeat Count', 'auxin-elements' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    ''  => __( 'Default', 'auxin-elements' ),
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    'infinite' => __( 'Infinite', 'auxin-elements' )
                ),
                'selectors' => array(
                    '{{WRAPPER}}' => 'animation-iteration-count:{{VALUE}};opacity:1;' // opacity is required to prevent flick between repetitions
                ),
                'condition' => array(
                    'aux_animation_name!' => ''
                ),
                'default'      => ''
            )
        );

        $widget->end_controls_section();
    }

    /**
     * Add extra controls for positioning to advanced section
     *
     * @return void
     */
    public function add_position_controls_section( $widget, $section_id, $args ){

        // Anchor element sections
        $target_sections = array('section_custom_css');

        if( ! defined('ELEMENTOR_PRO_VERSION') ) {
            $target_sections[] = 'section_custom_css_pro';
        }

        if( ! in_array( $section_id, $target_sections ) ){
            return;
        }

        // Adds general positioning options
        // ---------------------------------------------------------------------
        $widget->start_controls_section(
            'aux_core_common_position',
            array(
                'label'     => __( 'Positioning', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_ADVANCED
            )
        );

        $widget->add_responsive_control(
            'aux_position_type',
            array(
                'label'       => __( 'Position Type', 'auxin-elements' ),
                'label_block' => true,
                'type'        => Controls_Manager::SELECT,
                'options'     => array(
                    ''         => __( 'Default', 'auxin-elements'  ),
                    'static'   => __( 'Static', 'auxin-elements'   ),
                    'relative' => __( 'Relative', 'auxin-elements' ),
                    'absolute' => __( 'Absolute', 'auxin-elements' )
                ),
                'default'      => '',
                'selectors'    => array(
                    '{{WRAPPER}}' => 'position:{{VALUE}};',
                )
            )
        );

        $widget->add_responsive_control(
            'aux_position_top',
            array(
                'label'      => __('Top','auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px', 'em', '%'),
                'range'      => array(
                    'px' => array(
                        'min'  => -2000,
                        'max'  => 2000,
                        'step' => 1
                    ),
                    '%' => array(
                        'min'  => -100,
                        'max'  => 100,
                        'step' => 1
                    ),
                    'em' => array(
                        'min'  => -150,
                        'max'  => 150,
                        'step' => 1
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}}' => 'top:{{SIZE}}{{UNIT}};'
                ),
                'condition' => array(
                    'aux_position_type' => array('relative', 'absolute')
                )
            )
        );

        $widget->add_responsive_control(
            'aux_position_right',
            array(
                'label'      => __('Right','auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px', 'em', '%'),
                'range'      => array(
                    'px' => array(
                        'min'  => -2000,
                        'max'  => 2000,
                        'step' => 1
                    ),
                    '%' => array(
                        'min'  => -100,
                        'max'  => 100,
                        'step' => 1
                    ),
                    'em' => array(
                        'min'  => -150,
                        'max'  => 150,
                        'step' => 1
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}}' => 'right:{{SIZE}}{{UNIT}};'
                ),
                'condition' => array(
                    'aux_position_type' => array('relative', 'absolute')
                ),
                'return_value' => ''
            )
        );

        $widget->add_responsive_control(
            'aux_position_bottom',
            array(
                'label'      => __('Bottom','auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px', 'em', '%'),
                'range'      => array(
                    'px' => array(
                        'min'  => -2000,
                        'max'  => 2000,
                        'step' => 1
                    ),
                    '%' => array(
                        'min'  => -100,
                        'max'  => 100,
                        'step' => 1
                    ),
                    'em' => array(
                        'min'  => -150,
                        'max'  => 150,
                        'step' => 1
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}}' => 'bottom:{{SIZE}}{{UNIT}};'
                ),
                'condition' => array(
                    'aux_position_type' => array('relative', 'absolute')
                )
            )
        );

        $widget->add_responsive_control(
            'aux_position_left',
            array(
                'label'      => __('Left','auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px', 'em', '%'),
                'range'      => array(
                    'px' => array(
                        'min'  => -2000,
                        'max'  => 2000,
                        'step' => 1
                    ),
                    '%' => array(
                        'min'  => -100,
                        'max'  => 100,
                        'step' => 1
                    ),
                    'em' => array(
                        'min'  => -150,
                        'max'  => 150,
                        'step' => 1
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}}' => 'left:{{SIZE}}{{UNIT}};'
                ),
                'condition' => array(
                    'aux_position_type' => array('relative', 'absolute')
                )
            )
        );

        $widget->add_responsive_control(
            'aux_position_from_center',
            array(
                'label'      => __('From Center','auxin-elements' ),
                'description'=> __('Please avoid using "From Center" and "Left" options at the same time.','auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px', 'em', '%'),
                'range'      => array(
                    'px' => array(
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 1
                    ),
                    '%' => array(
                        'min'  => -100,
                        'max'  => 100,
                        'step' => 1
                    ),
                    'em' => array(
                        'min'  => -150,
                        'max'  => 150,
                        'step' => 1
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}}' => 'left:calc( 50% + {{SIZE}}{{UNIT}} );'
                ),
                'condition' => array(
                    'aux_position_type' => array('relative', 'absolute')
                )
            )
        );

        $widget->end_controls_section();
    }


    /**
     * Add extra options to advanced section
     *
     * @return void
     */
    public function add_extra_controls_section( $widget, $section_id, $args ){

        // Anchor element sections
        $target_sections = array('section_custom_css');

        if( ! defined('ELEMENTOR_PRO_VERSION') ) {
            $target_sections[] = 'section_custom_css_pro';
        }

        if( ! in_array( $section_id, $target_sections ) ){
            return;
        }

        // Adds extra options
        // ---------------------------------------------------------------------
        $widget->start_controls_section(
            'aux_core_general_extra',
            array(
                'label'     => __( 'Dimensions (extra)', 'auxin-elements' ),
                'tab'       => Controls_Manager::TAB_ADVANCED
            )
        );

        $widget->add_responsive_control(
            'aux_max_width',
            [
                'label'      => __('Max Width','auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%', 'vw'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'step' => 1
                    ],
                    '%' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1
                    ],
                    'em' => [
                        'min'  => 0,
                        'step' => 1
                    ],
                    'vw' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'max-width:{{SIZE}}{{UNIT}};'
                ]
            ]
        );

        $widget->add_responsive_control(
            'aux_max_height',
            [
                'label'      => __('Max Height','auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%', 'vh'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'step' => 1
                    ],
                    '%' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1
                    ],
                    'em' => [
                        'min'  => 0,
                        'step' => 1
                    ],
                    'vh' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'max-height:{{SIZE}}{{UNIT}};'
                ],
                'separator'       => 'after'
            ]
        );

        $widget->add_responsive_control(
            'aux_min_width',
            [
                'label'      => __('Min Width','auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%', 'vw'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'step' => 1
                    ],
                    '%' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1
                    ],
                    'em' => [
                        'min'  => 0,
                        'step' => 1
                    ],
                    'vw' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'min-width:{{SIZE}}{{UNIT}};'
                ]
            ]
        );

        $widget->add_responsive_control(
            'aux_min_height',
            [
                'label'      => __('Min Height','auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%', 'vh'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'step' => 1
                    ],
                    '%' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1
                    ],
                    'em' => [
                        'min'  => 0,
                        'step' => 1
                    ],
                    'vh' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'min-height:{{SIZE}}{{UNIT}};'
                ],
                'separator'       => 'after'
            ]
        );

        $widget->add_responsive_control(
            'aux_height',
            [
                'label'      => __('Height','auxin-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%', 'vh'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'step' => 1
                    ],
                    '%' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1
                    ],
                    'em' => [
                        'min'  => 0,
                        'step' => 1
                    ],
                    'vh' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'height:{{SIZE}}{{UNIT}};'
                ]
            ]
        );

        $widget->add_responsive_control(
			'flex_grow',
			[
				'label'       => __( 'Grow in width', 'auxin-elements' ),
                'label_block' => false,
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'selectors'   => [
					'{{WRAPPER}}' => 'flex-grow: {{VALUE}};'
                ],
                'separator' => 'before'
			]
		);

        $widget->end_controls_section();
    }


    /**
     * Modify the render of elementor elements
     *
     * @param  Widget_Base $widget Instance of Elementor Widget
     *
     * @return void
     */
    public function render_widget_attributes( $widget ){
        $settings = $widget->get_settings();
    }


    /**
     * Retrives the setting value or checkes whether the setting value
     * mathes with a value or not
     *
     * @param  array  $settings Settings in an array
     * @param  string $key      The setting key
     * @param  string $value    An optional value to compare with the setting value
     *
     * @return mixed           Setting value or a boolean value
     */
    private function setting_value( $settings, $key, $value = null ){
        if( ! isset( $settings[ $key ] ) ){
            return;
        }
        // Retrieves the setting value
        if( is_null( $value ) ){
            return $settings[ $key ];
        }
        // Validates the setting value
        return ! empty( $settings[ $key ] ) && $value == $settings[ $key ];
    }

    /**
     * Render Custom CSS for an Elementor Element
     *
     * @param $post_css Post_CSS_File
     * @param $element Element_Base
     */
    public function add_post_css( $post_css, $element ) {
        $element_settings = $element->get_settings();

        if ( empty( $element_settings['custom_css'] ) ) {
            return;
        }

        $css = trim( $element_settings['custom_css'] );

        if ( empty( $css ) ) {
            return;
        }
        $css = str_replace( 'selector', $post_css->get_element_unique_selector( $element ), $css );

        // Add a css comment
        $css = sprintf( '/* Start custom CSS for %s, class: %s */', $element->get_name(), $element->get_unique_selector() ) . $css . '/* End custom CSS */';

        $post_css->get_stylesheet()->add_raw_css( $css );
    }

}
