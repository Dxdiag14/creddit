<?php
namespace Auxin\Plugin\CoreElements\Elementor\Controls;

use Elementor\Plugin;
use Elementor\Base_Data_Control;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Auxin visual select control.
 *
 * A base control for creating visual select control. Displays radio buttons styled as
 * groups of buttons with icons for each option.
 *
 * @since 1.0.0
 */
class Control_Visual_Select extends Base_Data_Control {


	/**
	 * Get select control type.
	 *
	 * Retrieve the control type, in this case `select`.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Control type.
	 */
	public function get_type() {
		return 'aux-visual-select';
	}

	/**
	 * Get select control default settings.
	 *
	 * Retrieve the default settings of the select control. Used to return the
	 * default settings while initializing the select control.
	 *
	 * @since 2.0.0
	 * @access protected
	 *
	 * @return array Control default settings.
	 */
	protected function get_default_settings() {
		return [
			'label_block' => true,
			'options'     => array(),
            'style_items' => ''
		];
	}

	public function enqueue() {
		wp_enqueue_style( 'auxin-elementor-editor' );
		wp_enqueue_script( 'auxin-elementor-visualselect' , AUXELS_ADMIN_URL . '/assets/js/solo/visualselect.js' , array( 'jquery' ) );
		wp_enqueue_script( 'auxin-elementor-editor' );
	}

	/**
	 * Render select control output in the editor.
	 *
	 * Used to generate the control HTML in the editor using Underscore JS
	 * template. The variables for the class are available using `data` JS
	 * object.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
		<div class="elementor-control-field">
			<label for="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-input-wrapper">
				<select class="visual-select-wrapper" id="<?php echo esc_attr( $control_uid ); ?>" data-setting="{{ data.name }}">
                <# _.each( data.options, function( option_params, option_value ) {
					var value       = data.controlValue;
					if ( typeof value == 'string' ) {
						var selected = ( option_value === value ) ? 'selected' : '';
					} else if ( null !== value ) {
						var value = _.values( value );
						var selected = ( -1 !== value.indexOf( option_value ) ) ? 'selected' : '';
					}

					if ( option_params.css_class ) { #>
						<option {{ selected }} data-class="{{ option_params.css_class }}" value="{{ option_value }}">{{{ option_params.label }}}</option>
					<# } else if( option_params.video_src ) { #>
						<option {{ selected }} data-video-src="{{ option_params.video_src }}" value="{{ option_value }}">{{{ option_params.label }}}</option>
					<# } else if( option_params.image ) { #>
						<option {{ selected }} data-symbol="{{ option_params.image }}" value="{{ option_value }}">{{{ option_params.label }}}</option>
					<# } else { #>
						<option {{ selected }} value="{{ option_value }}">{{{ option_params.label }}}</option>
					<# }

				}); #>
				</select>
                <# if( data.style_items ){ #>
                <style>#elementor-control-default-{{{ data._cid  }}} + .axi-visual-select .axi-select-item{  {{{ data.style_items }}}  }</style>
                <# } #>
			</div>
		</div>
		<?php
	}
}
