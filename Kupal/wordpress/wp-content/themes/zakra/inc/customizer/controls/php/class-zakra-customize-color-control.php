<?php
/**
 * Customize Color control class.
 *
 * @package zakra
 *
 * @see     WP_Customize_Control
 * @access  public
 */

/**
 * Class Zakra_Customize_Color_Control
 */
class Zakra_Customize_Color_Control extends Zakra_Customize_Base_Control {

	/**
	 * Zakra_Customize_Color_Control constructor.
	 *
	 * @param WP_Customize_Manager $manager Customizer bootstrap instance.
	 * @param string               $id      An specific ID of the section.
	 * @param array                $args    Section arguments.
	 */
	public function __construct( WP_Customize_Manager $manager, $id, array $args = array() ) {

		parent::__construct( $manager, $id, $args );

		add_action( 'customize_controls_enqueue_scripts', array( $this, 'localize_script' ) );

	}

	/**
	 * Backwards compatibility.
	 *
	 * @access protected
	 * @var bool
	 */
	protected $alpha = false;

	/**
	 * Customize control type.
	 *
	 * @access public
	 * @var    string
	 */
	public $type = 'zakra-color';

	/**
	 * Colorpicker palette
	 *
	 * @access public
	 * @var bool
	 */
	public $palette = true;

	/**
	 * Mode.
	 *
	 * @var string
	 */
	public $mode = 'full';

	/**
	 * Some fields require options to be set.
	 * We're whitelisting the property here
	 * and suggest you validate this in a child class.
	 *
	 * @access protected
	 * @var array
	 */
	public $choices = array();

	/**
	 * Localize alpha color picker to controls.js file.
	 *
	 * @access public
	 */
	public function localize_script() {

		/**
		 * Color picker strings from WordPress.
		 *
		 * Added since WordPress 5.5 has removed them causing alpha color not appearing issue.
		 */
		if ( version_compare( $GLOBALS['wp_version'], '5.5', '>=' ) ) {
			wp_localize_script(
				'wp-color-picker',
				'wpColorPickerL10n',
				array(
					'clear'            => esc_html__( 'Clear', 'zakra' ),
					'clearAriaLabel'   => esc_html__( 'Clear Color', 'zakra' ),
					'defaultString'    => esc_html__( 'Default', 'zakra' ),
					'defaultAriaLabel' => esc_html__( 'Select Default Color', 'zakra' ),
					'pick'             => esc_html__( 'Select Color', 'zakra' ),
					'defaultLabel'     => esc_html__( 'Color Value', 'zakra' ),
				)
			);
		}
	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @see    WP_Customize_Control::to_json()
	 * @access public
	 * @return void
	 */
	public function to_json() {

		parent::to_json();

		$this->json['palette']          = $this->palette;
		$this->json['choices']['alpha'] = ( isset( $this->choices['alpha'] ) && $this->choices['alpha'] ) ? 'true' : 'false';
		$this->json['mode']             = $this->mode;

	}

	/**
	 * Render content is still called, so be sure to override it with an empty function in your subclass as well.
	 */
	protected function render_content() {

	}

}
