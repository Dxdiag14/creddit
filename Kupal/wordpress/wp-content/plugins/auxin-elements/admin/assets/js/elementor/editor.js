/**
 * Initialize all modules
 */
;(function($, window, document, undefined){

    $( window ).on( 'elementor:init', function() {

        // Add auxin specific css class to elementor body
        $('.elementor-editor-active').addClass('auxin');

        // Make our custom css visible in the panel's front-end
        if( typeof elementorPro == 'undefined' ) {
            elementor.hooks.addFilter( 'editor/style/styleText', function( css, context ){
                if ( ! context ) {
                    return;
                }

                var model = context.model,
                    customCSS = model.get('settings').get('custom_css');
                var selector = '.elementor-element.elementor-element-' + model.get('id');

                if ('document' === model.get('elType')) {
                    selector = elementor.config.document.settings.cssWrapperSelector;
                }

                if (customCSS) {
                    css += customCSS.replace(/selector/g, selector);
                }

                return css;
            });
        }

        var AuxControlBaseDataView = elementor.modules.controls.BaseData;

        /*!
         * ================== Auxin Visual Select Controller ===================
         **/
        var AuxControlVisualSelectItemView = AuxControlBaseDataView.extend( {
            onReady: function() {
                this.ui.select.avertaVisualSelect();
            },
            onBeforeDestroy: function() {
                this.ui.select.avertaVisualSelect( 'destroy' );
            }
        } );
        elementor.addControlView( 'aux-visual-select', AuxControlVisualSelectItemView );


        /*!
         * ================== Auxin Media Select Controller ===================
         **/
        var AuxControlMediaSelectItemView = AuxControlBaseDataView.extend( {
            ui: function() {
                var ui = AuxControlBaseDataView.prototype.ui.apply( this, arguments );

                ui.controlMedia = '.aux-elementor-control-media';
                ui.mediaImage = '.aux-elementor-control-media-attachment';
                ui.frameOpeners = '.aux-elementor-control-media-upload-button, .aux-elementor-control-media-attachment';
                ui.deleteButton = '.aux-elementor-control-media-delete';

                return ui;
            },

            events: function() {
                return _.extend( AuxControlBaseDataView.prototype.events.apply( this, arguments ), {
                    'click @ui.frameOpeners': 'openFrame',
                    'click @ui.deleteButton': 'deleteImage'
                } );
            },

            applySavedValue: function() {
                var control = this.getControlValue();

                this.ui.mediaImage.css( 'background-image', control.img ? 'url(' + control.img + ')' : '' );

                this.ui.controlMedia.toggleClass( 'elementor-media-empty', ! control.img );
            },

            openFrame: function() {
                if ( ! this.frame ) {
                    this.initFrame();
                }

                this.frame.open();
            },

            deleteImage: function() {
                this.setValue( {
                    url: '',
                    img: '',
                    id : ''
                } );

                this.applySavedValue();
            },

            /**
             * Create a media modal select frame, and store it so the instance can be reused when needed.
             */
            initFrame: function() {
                this.frame = wp.media( {
                    button: {
                        text: elementor.translate( 'insert_media' )
                    },
                    states: [
                        new wp.media.controller.Library( {
                            title: elementor.translate( 'insert_media' ),
                            library: wp.media.query( { type: this.ui.controlMedia.data('media-type') } ),
                            multiple: false,
                            date: false
                        } )
                    ]
                } );

                // When a file is selected, run a callback.
                this.frame.on( 'insert select', this.select.bind( this ) );
            },

            /**
             * Callback handler for when an attachment is selected in the media modal.
             * Gets the selected image information, and sets it within the control.
             */
            select: function() {
                this.trigger( 'before:select' );

                // Get the attachment from the modal frame.
                var attachment = this.frame.state().get( 'selection' ).first().toJSON();

                if ( attachment.url ) {
                    this.setValue( {
                        url: attachment.url,
                        img: attachment.image.src,
                        id : attachment.id
                    } );

                    this.applySavedValue();
                }

                this.trigger( 'after:select' );
            },

            onBeforeDestroy: function() {
                this.$el.remove();
            }
        } );
        elementor.addControlView( 'aux-media', AuxControlMediaSelectItemView );

        /*!
         * ================== Auxin Icon Select Controller ===================
         **/
        var AuxControlSelect2 = elementor.modules.controls.Select2;

        var ControlIconSelectItemView = AuxControlSelect2.extend( {
            initialize: function() {
                AuxControlSelect2View.prototype.initialize.apply( this, arguments );

                this.filterIcons();
            },

            filterIcons: function() {
                var icons = this.model.get( 'options' ),
                    include = this.model.get( 'include' ),
                    exclude = this.model.get( 'exclude' );

                if ( include ) {
                    var filteredIcons = {};

                    _.each( include, function( iconKey ) {
                        filteredIcons[ iconKey ] = icons[ iconKey ];
                    } );

                    this.model.set( 'options', filteredIcons );
                    return;
                }

                if ( exclude ) {
                    _.each( exclude, function( iconKey ) {
                        delete icons[ iconKey ];
                    } );
                }
            },

            iconsList: function( icon ) {
                if ( ! icon.id ) {
                    return icon.text;
                }

                return jQuery(
                    '<span><i class="' + icon.id + '"></i> ' + icon.text + '</span>'
                );
            },

            getSelect2Options: function() {
                return {
                    allowClear: true,
                    templateResult: this.iconsList.bind( this ),
                    templateSelection: this.iconsList.bind( this )
                };
            }
        } );
        elementor.addControlView( 'aux-icon', ControlIconSelectItemView );

        // ControlSelect2View prototype
        var AuxControlSelect2View = AuxControlSelect2.extend( {
            getSelect2Options: function() {
                return {
                    dir: elementor.config.is_rtl ? 'rtl' : 'ltr'
                };
            },

            templateHelpers: function() {
                var helpers = AuxControlSelect2View.prototype.templateHelpers.apply( this, arguments ),
                    fonts = this.model.get( 'options' );

                helpers.getFontsByGroups = function( groups ) {
                    var filteredFonts = {};

                    _.each( fonts, function( fontType, fontName ) {
                        if ( _.isArray( groups ) && _.contains( groups, fontType ) || fontType === groups ) {
                            filteredFonts[ fontName ] = fontName;
                        }
                    } );

                    return filteredFonts;
                };

                console.log(helpers);

                return helpers;
            }
        } );

        /*!
         * ================== Auxin Query Controller ===================
         **/
        var ControlQueryPostsItemView = AuxControlSelect2.extend( {
            cache: null,

            isTitlesReceived: false,

            getSelect2Placeholder: function getSelect2Placeholder() {
                return {
                    id: '',
                    text: 'All'
                };
            },

            getControlValueByName: function getControlValueByName(controlName) {
                var name = this.model.get('group_prefix') + controlName;
                return this.elementSettingsModel.attributes[name];
            },

            getSelect2DefaultOptions: function getSelect2DefaultOptions() {
                var self = this;

                return jQuery.extend(AuxControlSelect2.prototype.getSelect2DefaultOptions.apply(this, arguments), {
                    ajax: {
                        transport: function transport(params, success, failure) {
                            var data = {
                                q: params.data.q,
                                filter_type: self.model.get('filter_type'),
                                object_type: self.model.get('object_type'),
                                include_type: self.model.get('include_type'),
                                query: self.model.get('query')
                            };

                            if ('cpt_taxonomies' === data.filter_type) {
                                data.query = {
                                    post_type: self.getControlValueByName('post_type')
                                };
                            }

                            return elementorCommon.ajax.addRequest('panel_posts_control_filter_autocomplete', {
                                data: data,
                                success: success,
                                error: failure
                            });
                        },
                        data: function data(params) {
                            return {
                                q: params.term,
                                page: params.page
                            };
                        },
                        cache: true
                    },
                    escapeMarkup: function escapeMarkup(markup) {
                        return markup;
                    },
                    minimumInputLength: 1
                });
            },

            getValueTitles: function getValueTitles() {
                var self = this,
                    ids = this.getControlValue(),
                    filterType = this.model.get('filter_type');

                if (!ids || !filterType) {
                    return;
                }

                if (!_.isArray(ids)) {
                    ids = [ids];
                }

                elementorCommon.ajax.loadObjects({
                    action: 'query_control_value_titles',
                    ids: ids,
                    data: {
                        filter_type: filterType,
                        object_type: self.model.get('object_type'),
                        include_type: self.model.get('include_type'),
                        unique_id: '' + self.cid + filterType,
                        query: self.model.get('query')
                    },
                    before: function before() {
                        self.addControlSpinner();
                    },
                    success: function success(data) {
                        self.isTitlesReceived = true;

                        self.model.set('options', data);

                        self.render();
                    }
                });
            },

            addControlSpinner: function addControlSpinner() {
                this.ui.select.prop('disabled', true);
                this.$el.find('.elementor-control-title').after('<span class="elementor-control-spinner">&nbsp;<i class="fa fa-spinner fa-spin"></i>&nbsp;</span>');
            },

            onReady: function onReady() {
                // Safari takes it's time to get the original select width
                setTimeout(AuxControlSelect2.prototype.onReady.bind(this));

                if (!this.isTitlesReceived) {
                    this.getValueTitles();
                }
            }
        } );
        elementor.addControlView( 'aux-query', ControlQueryPostsItemView );

        /*!
         * ================== Auxin Featured Color Controller ===================
         **/
        var AuxControlFeaturedColorItemView = AuxControlBaseDataView.extend( {

            onReady: function() {
                this.ui.select.AuxFeaturedColor();
            },

        } );
        elementor.addControlView( 'aux-featured-color', AuxControlFeaturedColorItemView );

        /*!
         * ================== Others ===================
         **/
        // Enables the live preview for tranistions in Elementor Editor
        function auxOnGlobalOpenEditorForTranistions ( panel, model, view ) {
            view.listenTo( model.get( 'settings' ), 'change', function( changedModel ){

                // Force to re-render the element if the Entrance Animation enabled for first time
                if( '' !== model.getSetting('aux_animation_name') && !view.$el.hasClass('aux-animated') ){
                    view.render();
                    view.$el.addClass('aux-animated');
                    view.$el.addClass('aux-animated-once');
                }

                // Check the changed setting value
                for( settingName in changedModel.changed ) {
                    if ( changedModel.changed.hasOwnProperty( settingName ) ) {

                        // Replay the animation if an animation option changed (except the animation name)
                        if( settingName !== "aux_animation_name" && ( -1 !== settingName.indexOf("aux_animation_") || -1 !== settingName.indexOf("_custom") ) ){
                            // Reply the animation
                            view.$el.removeClass( model.getSetting('aux_animation_name') );

                            setTimeout( function() {
                                view.$el.addClass( model.getSetting('aux_animation_name') );
                            }, ( model.getSetting('aux_animation_delay') || 300 ) ); // Animation Delay
                        }
                    }
                }

            }, view );
        }
        elementor.hooks.addAction( 'panel/open_editor/section', auxOnGlobalOpenEditorForTranistions );
        elementor.hooks.addAction( 'panel/open_editor/column' , auxOnGlobalOpenEditorForTranistions );
        elementor.hooks.addAction( 'panel/open_editor/widget' , auxOnGlobalOpenEditorForTranistions );

    } );

})(jQuery, window, document);
