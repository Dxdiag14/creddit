<?php


class Auxin_SVG_Support_AllowedAttributes extends \enshrined\svgSanitize\data\AllowedAttributes {

	/**
	 * Returns an array of attributes
	 *
	 * @return array
	 */
	public static function getAttributes() {

		/**
		 * var  array Attributes that are allowed.
		 */
		return apply_filters( 'svg_allowed_attributes', parent::getAttributes() );
	}
}