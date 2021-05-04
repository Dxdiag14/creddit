(function() {

    tinymce.PluginManager.add('phlox_shortcodes_button', function(editor, url) {
        editor.addButton('phlox_shortcodes_button', {
            // text : false,
            title: 'Phlox Shortcode',
            type : 'menubutton',
            icon : 'phlox-shortcode-btn',
            classes: 'widget btn mfnsc',
            menu : [ {
                text : 'Typography',
                menu :
                [ {
                    text : 'Highlight',
                    menu :
                    [   {
                            text : 'Red',
                            onclick : function() {
                                editor.insertContent('[aux_highlight style="aux-highlight-red" extra_classes=""]Insert your content here[/aux_highlight]');
                            },
                        },
                        {
                            text : 'Blue',
                            onclick : function() {
                                editor.insertContent('[aux_highlight style="aux-highlight-blue" extra_classes=""]Insert your content here[/aux_highlight]');
                            },
                        },
                        {
                            text : 'Yellow',
                            onclick : function() {
                                editor.insertContent('[aux_highlight style="aux-highlight-yellow" extra_classes=""]Insert your content here[/aux_highlight]');
                            },
                        },
                        {
                            text : 'Green',
                            onclick : function() {
                                editor.insertContent('[aux_highlight style="aux-highlight-green" extra_classes=""]Insert your content here[/aux_highlight]');
                            },
                        },
                    ] // end of highlight


                },
                    {
                        text : 'Dropcap',
                        menu :
                        [   {
                                text : 'Classic',
                                onclick : function() {
                                    editor.insertContent('[aux_dropcap style="classic" extra_classes=""]Please insert your content here[/aux_dropcap]');
                                },
                            },
                           {
                                text : 'Square',
                                onclick : function() {
                                    editor.insertContent('[aux_dropcap style="square" extra_classes=""]Please insert your content here[/aux_dropcap]');
                                },
                            },
                            {
                                text : 'Outline Square',
                                onclick : function() {
                                    editor.insertContent('[aux_dropcap style="square-outline" extra_classes=""]Please insert your content here[/aux_dropcap]');
                                },
                            },
                            {
                                text : 'Round Square',
                                onclick : function() {
                                    editor.insertContent('[aux_dropcap style="square-round" extra_classes=""]Please insert your content here[/aux_dropcap]');
                                },
                            },
                            {
                                text : 'Circle',
                                onclick : function() {
                                    editor.insertContent('[aux_dropcap style="circle" extra_classes=""]Please insert your content here[/aux_dropcap]');
                                },
                            },
                             {
                                text : 'Outline Circle',
                                onclick : function() {
                                    editor.insertContent('[aux_dropcap style="circle-outline" extra_classes=""]Please insert your content here[/aux_dropcap]');
                                },
                            },
                        ] // end of dropcap
                     },   // end of dropcap
                       {
                        text : 'Blockquote',
                        menu :
                        [   {
                                text : 'Quote Normal',
                                onclick : function() {
                                    editor.insertContent('[aux_quote type="quote-normal" text_align="left" quote_symbol="1" title="title" extra_classes=""]Insert your quote here[/aux_quote]');
                                },
                            },
                           {
                                text : 'Blockquote Normal',
                                onclick : function() {
                                    editor.insertContent('[aux_quote type="blockquote-normal" text_align="left" quote_symbol="1" title="title" extra_classes=""]Insert your quote here[/aux_quote]');
                                },
                            },
                            {
                                text : 'Blockquote Bordered',
                                onclick : function() {
                                    editor.insertContent('[aux_quote type="blockquote-bordered" text_align="left" quote_symbol="1" title="title" extra_classes=""]Insert your quote here[/aux_quote]');
                                },
                            },
                            {
                                text : 'Intro Hero',
                                onclick : function() {
                                    editor.insertContent('[aux_quote type="intro-hero" text_align="left" quote_symbol="1" title="title" extra_classes=""]Insert your quote here[/aux_quote]');
                                },
                            },
                            {
                                text : 'Intro',
                                onclick : function() {
                                    editor.insertContent('[aux_quote type="intro-normal" text_align="left" quote_symbol="1" title="title" extra_classes=""]Insert your quote here[/aux_quote]');
                                },
                            },
                             {
                                text : 'Intro with Splitter',
                                onclick : function() {
                                    editor.insertContent('[aux_quote type="intro-splitter" text_align="left" quote_symbol="1" title="title" extra_classes=""]Insert your quote here[/aux_quote]');
                                },
                            },
                             {
                                text : 'Pullquote Normal',
                                onclick : function() {
                                    editor.insertContent('[aux_quote type="pullquote-normal" text_align="left" quote_symbol="1" title="title" extra_classes=""]Insert your quote here[/aux_quote]');
                                },
                            },
                             {
                                text : 'Pullquote Colorized',
                                onclick : function() {
                                    editor.insertContent('[aux_quote type="pullquote-colorized" text_align="left" quote_symbol="1" title="title" extra_classes=""]Insert your quote here[/aux_quote]');
                                },
                            },
                        ] // end of Block Qoute
                     },   // end of Block Qoute

                 ]        // end of typography
            },            // end of typography
            {
                text : 'Content',
                menu : [ {
                    text : 'Phlox Text',
                    onclick : function() {
                                    editor.insertContent('[aux_text title_link="" text_align="left" icon="auxicon-bow-tie" icon_size="large" image_position="top" icon_color="#888" title="title" extra_classes=""]Insert your text here[/aux_text]');
                                },

                    },   // end of Phlox Text
                    {
                    text : 'Button',
                        menu :
                        [   {
                                text : 'Normal',
                                onclick : function() {
                                    editor.insertContent('[aux_button label="Button" size="medium" icon_align="left" color_name="dark-gray" border="" style="" icon="fa fa-beer" link="" target="_blank"]');
                                },
                            },
                             {
                                text : '3D',
                                onclick : function() {
                                    editor.insertContent('[aux_button label="Button" size="medium" icon_align="left" color_name="dark-gray" border="" style="3d" icon="fa fa-beer" link="" target="_blank"]');
                                },
                            },
                            {
                                text : 'Outline',
                                onclick : function() {
                                    editor.insertContent('[aux_button label="Button" size="medium" icon_align="left" color_name="dark-gray" border="" style="outline" icon="fa fa-beer" link="" target="_blank"]');
                                },
                            },
                        ] // end of Button
                     },   // end of Button
                    {
                    text : 'Map',
                    onclick : function() {
                                    editor.insertContent('[aux_gmaps type="ROADMAP" show_mapcontrols="1" zoom_wheel="0"  height="700" latitude="54" longitude="14" zoom="3" style="" title="Title" marker_info="Marker info" extra_classes=""]');
                            },

                     },   // end of map
                     {
                     text : 'Gallery',
                        menu :
                        [   {
                                text : 'Grid',
                                onclick : function() {
                                    editor.insertContent('[aux_gallery columns="2" order="ASC" orderby="menu_order ID" layout="grid" pagination="0" perpage="24" size="medium" link="lightbox" include="seprate your image ids here" extra_classes=""]');
                                },
                            },
                             {
                                text : '3D',
                                onclick : function() {
                                    editor.insertContent('[aux_gallery columns="2" order="ASC" orderby="menu_order ID" layout="justify-rows" pagination="0" perpage="24" size="medium" link="lightbox" include="seprate your image ids here" extra_classes=""]');
                                },
                            },

                        ] // end of Gallery
                     },   // end of Gallery
                     {
                     text : 'Divider',
                        menu :
                        [   {
                                text : 'Solid',
                                onclick : function() {
                                    editor.insertContent('[aux_divider width="medium" style="solid" margin_top="20" margin_bottom="20" extra_classes=""]');
                                },
                            },
                             {
                                text : 'White Space',
                                onclick : function() {
                                    editor.insertContent('[aux_divider width="medium" style="white-space" margin_top="20" margin_bottom="20" extra_classes=""]');
                                },
                            },
                            {
                                text : 'Dashed',
                                onclick : function() {
                                    editor.insertContent('[aux_divider width="medium" style="dashed" margin_top="20" margin_bottom="20" extra_classes=""]');
                                },
                            },
                            {
                                text : 'Circle',
                                onclick : function() {
                                    editor.insertContent('[aux_divider width="medium" style="circle-symbol" margin_top="20" margin_bottom="20" extra_classes=""]');
                                },
                            },
                            {
                                text : 'Diamond',
                                onclick : function() {
                                    editor.insertContent('[aux_divider width="medium" style="diamond-symbol" margin_top="20" margin_bottom="20" extra_classes=""]');
                                },
                            },

                        ] // end of Divider
                     },   // end of Divider
                      {
                     text : 'Contact Form',
                        menu :
                        [   {
                                text : 'Phlox Contact Form',
                                onclick : function() {
                                    editor.insertContent('[aux_contact_form title="Title" type="phlox" email="info@email.net" extra_classes=""]');
                                },
                            },
                             {
                                text : 'Contact Form7',
                                onclick : function() {
                                    editor.insertContent('[aux_contact_form title="Title" type="cf7" email="info@email.net" cf7_shortcode="insert contactform shortcode here" extra_classes=""]');
                                },
                            },


                        ] // end of Contact Form
                     },   // end of Contact Form
                     {
                    text : 'Search',
                    onclick : function() {
                                    editor.insertContent('[aux_search title="Title" extra_classes=""]');
                            },

                     },   // end of Search
                    {
                    text : 'Code',
                    onclick : function() {
                                    editor.insertContent('[aux_code language="javascript" theme="tomorrow" title="Title" extra_classes=""]insert your code here[/aux_code]');
                            },

                     },   // end of Code
                    {
                    text : 'Socials',
                        menu :
                        [   {
                                text : 'Horizontal',
                                onclick : function() {
                                    editor.insertContent('[aux_socials_list size="medium" direction="horizontal" title="Title"]');
                                },
                            },
                             {
                                text : 'Vertical',
                                onclick : function() {
                                    editor.insertContent('[aux_socials_list size="medium" direction="vertical" title="Title"]');
                                },
                            },


                        ] // end of Socials
                     },   // end of Socials
                     {
                    text : 'Image',
                        menu :
                        [   {
                                text : 'Normal',
                                onclick : function() {
                                    editor.insertContent('[aux_image target="_blank" title="Title" attach_id="Insert your image id here" lightbox="no" attach_id_hover="Insert your image id here" width="" height="" link="" icon="plus" extra_classes=""]');
                                },
                            },
                             {
                                text : 'Lightbox',
                                onclick : function() {
                                    editor.insertContent('[aux_image target="_blank" title="Title" attach_id="Insert your image id here" lightbox="yes" attach_id_hover="Insert your image id here" width="" height="" link="" icon="plus" extra_classes=""]');
                                },
                            },


                        ] // end of Image
                     },   // end of Image
                      {
                    text : 'About Author',
                    onclick : function() {
                                    editor.insertContent('[aux_about_widget image_style="square" title="Title" name="Name" skills="Skills" info="Insert your info here" about_image="Insert your image id here" image_style="circle/square" align_center="0" show_socials="0" extra_classes=""]');
                            },

                     },   // end of About


                 ]
            },
            {
                text : 'Layout',
                menu : [
                    {
                        text : '2 Columns',
                        onclick : function() {
                            editor.insertContent("[aux_row columns=\"2\"]<br />[aux_col]First column content. Insert your content here[/aux_col]<br />[aux_col]Second column content. Insert your content here[/aux_col]<br />[/aux_row]");
                        }
                    },
                    {
                        text : '3 Columns',
                        onclick : function() {
                            editor.insertContent("[aux_row columns=\"3\"]<br />[aux_col]First column content. Insert your content here[/aux_col]<br />[aux_col]Second column content. Insert your content here[/aux_col]<br />[aux_col]Third column content. Insert your content here[/aux_col]<br />[/aux_row]");
                        }
                    },
                    {
                        text : '4 Columns',
                        onclick : function() {
                            editor.insertContent("[aux_row columns=\"4\"]<br />[aux_col]First column content. Insert your content here[/aux_col]<br />[aux_col]Second column content. Insert your content here[/aux_col]<br />[aux_col]Third column content. Insert your content here[/aux_col]<br />[aux_col]Forth column content. Insert your content here[/aux_col]<br />[/aux_row]");
                        }
                    }
                ]
            }
        ]

        });

    });

})();
(function($, w){ $(w).resize(); })(jQuery, window);
