<?php
namespace Auxin\Plugin\CoreElements\Elementor\Controls;

use Elementor\Plugin;
use Elementor\Base_Data_Control;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Auxin icon select control.
 *
 * A base control for creating icon select control. Displays radio buttons styled as
 * groups of buttons with icons for each option.
 *
 * @since 1.0.0
 */
class Control_Icon_Select extends Base_Data_Control {


	/**
	 * Get select control type.
	 *
	 * Retrieve the control type, in this case `aux-icon`.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Control type.
	 */
	public function get_type() {
		return 'aux-icon';
	}

	/**
	 * Get icons control default settings.
	 *
	 * Retrieve the default settings of the icons control. Used to return the default
	 * settings while initializing the icons control.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @return array Control default settings.
	 */
	protected function get_default_settings() {
		return [
			'icons' => Auxin()->Font_Icons->get_icons_list( 'fontastic' ),
			'include' => '',
			'exclude' => '',
		];
	}

	public function enqueue() {
		wp_enqueue_style('auxin-front-icon');
		wp_enqueue_style( 'auxin-elementor-editor' );
		wp_enqueue_script( 'auxin-elementor-editor' );
	}

	/**
	 * Render icons control output in the editor.
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
			<label for="<?php echo $control_uid; ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-input-wrapper">
				<select id="<?php echo $control_uid; ?>" class="elementor-control-icon" data-setting="{{ data.name }}" data-placeholder="<?php echo __( 'Select Icon', 'elementor' ); ?>">
					<option value=""><?php echo __( 'Select Icon', 'elementor' ); ?></option>
					<# _.each( data.icons, function( icon, key ) {
					var icon_value = icon.classname.replace(/\./g, "");
					#>
					<option value="{{ icon_value }}">{{{ icon.name }}}</option>
					<# } ); #>
				</select>
			</div>
		</div>
		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{ data.description }}</div>
		<# } #>
		<?php
	}
}
