<?php
namespace Auxin\Plugin\CoreElements\Elementor\Elements;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Background;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Elementor 'Responsive Table' widget.
 *
 * Elementor widget that displays an 'Responsive Table' with lightbox.
 *
 * @since 1.0.0
 */
class ResponsiveTable extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'Responsive Table' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_responsive_table';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'Responsive Table' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Responsive Table', 'auxin-elements' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'Responsive Table' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-table auxin-badge';
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'Responsive Table' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_categories() {
        return array( 'auxin-core' );
    }

    public function get_script_depends() {
        return ['stacktable'];
    }

    /**
     * Register 'Responsive Table' widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {

        /*-----------------------------------------------------------------------------------*/
        /*  Content TAB
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'table_markup_section',
            array(
                'label'      => __('Table Markup', 'auxin-elements' ),
            )
        );

        $this->add_control(
            'table_markup',
            array(
                'label'       => '',
                'type'        => Controls_Manager::CODE,
                'show_label'  => false,
            )
        );

        $this->end_controls_section();


        /*-----------------------------------------------------------------------------------*/
        /*  Style TAB
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'table_body_section',
            array(
                'label' => __( 'Table Body', 'auxin-elements' ),
                'tab' => Controls_Manager::TAB_STYLE
            )
        );

        $this->add_responsive_control(
            'table_body_alignment',
            array(
                'label'       => __('Alignment','auxin-elements' ),
                'type'        => Controls_Manager::CHOOSE,
                'options'     => array(
                    'left' => array(
                        'title' => __( 'Left', 'auxin-elements' ),
                        'icon' => 'fa fa-align-left',
                    ),
                    'center' => array(
                        'title' => __( 'Center', 'auxin-elements' ),
                        'icon' => 'fa fa-align-center',
                    ),
                    'right' => array(
                        'title' => __( 'Right', 'auxin-elements' ),
                        'icon' => 'fa fa-align-right',
                    )
                ),
                'default'     => '',
                'toggle'      => true,
                'selectors'   => array(
                    '{{WRAPPER}} td' => 'text-align:{{VALUE}};',
                ),
                'separator'   => 'after'
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'      => 'table_body_background_normal',
                'selector'  => '{{WRAPPER}} td',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'table_body_typography',
                'scheme'    => Typography::TYPOGRAPHY_1,
                'selector'  => '{{WRAPPER}} td'
            )
        );

        $this->add_control(
            'table_body_color',
            array(
                'label'     => __( 'Color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} td' => 'color:{{VALUE}};'
                )
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'table_head_section',
            array(
                'label' => __( 'Table Heading', 'auxin-elements' ),
                'tab' => Controls_Manager::TAB_STYLE
            )
        );

        $this->add_responsive_control(
            'table_head_alignment',
            array(
                'label'       => __('Alignment','auxin-elements' ),
                'type'        => Controls_Manager::CHOOSE,
                'options'     => array(
                    'left' => array(
                        'title' => __( 'Left', 'auxin-elements' ),
                        'icon' => 'fa fa-align-left',
                    ),
                    'center' => array(
                        'title' => __( 'Center', 'auxin-elements' ),
                        'icon' => 'fa fa-align-center',
                    ),
                    'right' => array(
                        'title' => __( 'Right', 'auxin-elements' ),
                        'icon' => 'fa fa-align-right',
                    )
                ),
                'default'     => '',
                'toggle'      => true,
                'selectors'   => array(
                    '{{WRAPPER}} th' => 'text-align:{{VALUE}};',
                ),
                'separator'   => 'after'
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'      => 'table_head_background_normal',
                'selector'  => '{{WRAPPER}} th',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'table_head_typography',
                'scheme'    => Typography::TYPOGRAPHY_1,
                'selector'  => '{{WRAPPER}} th'
            )
        );

        $this->add_control(
            'table_head_color',
            array(
                'label'     => __( 'Color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} th' => 'color:{{VALUE}};'
                )
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'table_odd_row_section',
            array(
                'label' => __( 'Table Odd Rows', 'auxin-elements' ),
                'tab' => Controls_Manager::TAB_STYLE
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'      => 'table_odd_background_normal',
                'selector'  => '{{WRAPPER}} tr:nth-child(2n+1)',
                'separator' => 'none'
            )
        );

        $this->add_control(
            'table_odd_row_color',
            array(
                'label'     => __( 'Color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} {{WRAPPER}} tr:nth-child(2n+1)' => 'color:{{VALUE}};'
                )
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'table_even_row_section',
            array(
                'label' => __( 'Table Even Rows', 'auxin-elements' ),
                'tab' => Controls_Manager::TAB_STYLE
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'      => 'table_even_row_background_normal',
                'selector'  => '{{WRAPPER}} tr:nth-child(2n)',
                'separator' => 'none'
            )
        );

        $this->add_control(
            'table_even_row_color',
            array(
                'label'     => __( 'Color', 'auxin-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} {{WRAPPER}} tr:nth-child(2n)' => 'color:{{VALUE}};'
                )
            )
        );

        $this->end_controls_section();
    }

    /**
     * Render 'Tabs' widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {

        $settings = $this->get_settings_for_display();

        $this->add_render_attribute( 'wrapper', 'class', 'widget-container aux-widget-responsive-table' );
        $this->add_render_attribute( 'inner', 'class', 'widget-inner' );

        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?> >
            <div <?php echo $this->get_render_attribute_string( 'inner' ); ?> >
                <?php echo $settings['table_markup']; ?>
            </div>
        </div>
        <?php
    }


    /**
     * Render responsive table element in the editor.
     *
     * @access protected
     */
    protected function _content_template() {
        ?>
        <div class="widget-container widget-responsive-table">
            <div class="widget-inner">
                {{{ settings.table_markup }}}
            </div>
        </div>
        <?php
    }

}
