<?php
/**
 * Customize Upgrade control class.
 *
 * @package zakra
 *
 * @since   1.4.6
 * @see     WP_Customize_Control
 * @access  public
 */

/**
 * Class Zakra_Customize_Heading_Control
 */
class Zakra_Customize_Upgrade_Control extends Zakra_Customize_Base_Control {

	/**
	 * Customize control type.
	 *
	 * @access public
	 * @var    string
	 */
	public $type = 'zakra-upgrade';

	/**
	 * Renders the Underscore template for this control.
	 *
	 * @see    WP_Customize_Control::print_template()
	 * @access protected
	 * @return void
	 */

	protected function content_template() {
		?>
        <p class="description upgrade-description">{{{ data.description }}}</p>

        <span>
            <a href="<?php echo esc_url( 'https://zakratheme.com/pricing/?utm_source=zakra-customizer&utm_medium=view-pro-link&utm_campaign=zakra-pricing' ); ?>" class="button button-primary" target="_blank">
                {{ data.label }}
            </a>
		</span>
        <?php
	}

	/**
	 * Render content is still called, so be sure to override it with an empty function in your subclass as well.
	 */
	protected function render_content() {

	}

}
