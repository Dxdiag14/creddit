/*!
 * Visual Select - A jQuery plugin for replacing HTML select element with a visual selection tool.
 *
 * @version     1.3.0
 * @requires    jQuery 1.9+
 * @author      Averta [averta.net]
 * @package     Axiom Framework
 * @copyright   Copyright © 2017 Averta, all rights reserved
 */

;(function ( $, window, document, undefined ) {

    "use strict";

    // Create the defaults once
    var pluginName = 'AuxFeaturedColor',
        defaults = {},
        attributesMap = {};

    // The actual plugin constructor
    function Plugin( element, options ) {
        this.element = element;
        this.$element = $(element);
        this.options = $.extend( {}, defaults, options) ;

        // read attributes
        for ( var key in attributesMap ) {
            var value = attributesMap[ key ],
                dataAttr = this.$element.data( key );

            if ( dataAttr === undefined ) {
                continue;
            }

            this.options[ value ] = dataAttr;
        }

        this._defaults = defaults;
        this._name = pluginName;

        this.init();
    }

    $.extend(Plugin.prototype, {

        init : function(){
            var self = this,
                st = self.options;

            this.$element.select2({
                dropdownCssClass: 'aux-featured-color-dropdown',
                minimumResultsForSearch: Infinity,
                templateResult: this.generateOptions,
                templateSelection: this.generateOptions
            });
        },

        generateOptions: function( data ) {
            var $option       = $(data.element),
                dataColor     = $option.data('color'),
                $colorWrapper = $('<span />').attr('class', 'aux-featured-color-item-wrapper'),
                $color        = $('<span />').attr('class', 'aux-featured-color-item'),
                $colorLabel   = $('<span />').attr('class', 'aux-featured-color-label').html( data.text );
            
            // Color Shape 
            if( typeof dataColor !== 'undefined' ) {

                var $colorShape = $('<span />').attr('class', 'aux-featured-color-shape');

                if ( ! dataColor ) {
                    $colorShape.addClass('aux-featured-color-empty')
                } else {
                    $colorShape.css( 'background-color', dataColor );
                }

                $colorShape.appendTo( $color );
            }

            $colorLabel.appendTo( $color );
            $color.appendTo( $colorWrapper );

            // Color Hex String 
            if( typeof dataColor !== 'undefined' ) {
                var $colorHex = $('<span />').attr('class', 'aux-featured-color-hex');

                if ( dataColor ) {
                    $colorHex.html( dataColor);
                }

                $colorHex.appendTo($colorWrapper);

            }
            
            return $colorWrapper;  
        }

    });


    $.fn[pluginName] = function ( options ) {
        var args = arguments;

        // Is the first parameter an object (options), or was omitted,
        // instantiate a new instance of the plugin.
        if (options === undefined || typeof options === 'object') {
            return this.each(function () {

                // Only allow the plugin to be instantiated once,
                // so we check that the element has no plugin instantiation yet
                if (!$.data(this, 'plugin_' + pluginName)) {

                    // if it has no instance, create a new one,
                    // pass options to our plugin constructor,
                    // and store the plugin instance
                    // in the elements jQuery data object.
                    $.data(this, 'plugin_' + pluginName, new Plugin( this, options ));
                }
            });

        // If the first parameter is a string and it doesn't start
        // with an underscore or "contains" the `init`-function,
        // treat this as a call to a public method.
        } else if (typeof options === 'string' && options[0] !== '_' && options !== 'init') {

            // Cache the method call
            // to make it possible
            // to return a value
            var returns;

            this.each(function () {
                var instance = $.data(this, 'plugin_' + pluginName);

                // Tests that there's already a plugin-instance
                // and checks that the requested public method exists
                if (instance instanceof Plugin && typeof instance[options] === 'function') {

                    // Call the method of our plugin instance,
                    // and pass it the supplied arguments.
                    returns = instance[options].apply( instance, Array.prototype.slice.call( args, 1 ) );
                }

                // Allow instances to be destroyed via the 'destroy' method
                if (options === 'destroy') {
                  $.data(this, 'plugin_' + pluginName, null);
                }
            });

            // If the earlier cached method
            // gives a value back return the value,
            // otherwise return this to preserve chainability.
            return returns !== undefined ? returns : this;
        }
    }

}(jQuery, window, document));
