jQuery(document).ready(function($) {
	jQuery(window).on("load",function() {
		if (jQuery(".upload_image_button.upload_image_button_m").length) {
			var custom_uploader;
			jQuery('.upload_image_button.upload_image_button_m').click(function(e) {
				var image_var = jQuery(this);
				e.preventDefault();
				if (custom_uploader) {
					custom_uploader.open();
					return;
				}
				//Extend the wp.media object
				custom_uploader = wp.media.frames.file_frame = wp.media({
					title: discy_js.choose_image,
					button: {
						text: discy_js.choose_image
					},
					multiple: true
				});
				custom_uploader.on('select', function() {
					var selection = custom_uploader.state().get('selection');
					selection.map( function( attachment ) {
						attachment = attachment.toJSON();
						if (jQuery("#"+image_var.attr("data-id")+"-item-"+attachment.id).length == 0) {
							jQuery("#"+image_var.attr("data-id")).append("<li id='"+image_var.attr("data-id")+"-item-"+attachment.id+"' class='multi-images'>\
								<div class='multi-image'>\
									<img alt='"+attachment.url+"' src='"+attachment.url+"'><input type='hidden' name='"+image_var.attr("data-name")+"[]' value='"+attachment.id+"'>\
									<div class='image-overlay'></div>\
									<div class='image-media-bar'>\
										<a class='image-edit-media' title='"+discy_js.edit_image+"' href='post.php?post="+attachment.id+"&amp;action=edit' target='_blank'>\
											<span class='dashicons dashicons-edit'></span>\
										</a>\
										<a href='#' class='image-remove-media' title='"+discy_js.remove_image+"'>\
											<span class='dashicons dashicons-no-alt'></span>\
										</a>\
									</div>\
								</div>\
							</li>");
						}
					});
				});
				custom_uploader.open();
			});
		}
		
		jQuery(document).on("click",".image-remove-media",function () {
			jQuery(this).parent().parent().parent().addClass('removered').fadeOut(function() {
				jQuery(this).remove();
			});
			return false;
		});
		
		jQuery(".builder_select").on('mouseup',function () {
			var copyText = jQuery(this);
			copyText.select();
			document.execCommand("copy");
		});
		
		/* Change values for radio & checkboxes & text & select */
		jQuery("#builder").on('click',"input[type='radio']",function() {
			jQuery(this).parent().find(":radio").removeAttr("checked");
			jQuery(this).attr("checked","checked");
		});
		
		jQuery("#builder").on('click',"input[type='checkbox']",function() {
			if (jQuery(this).is(":checked")) {
				jQuery(this).attr("checked","checked");
			}else {
				jQuery(this).removeAttr("checked");
			}
		});
		
		function change_inputs_values() {
			change_inputs_values_text();
			change_inputs_values_select();
		}
		
		function change_inputs_values_text() {
			jQuery("#builder input[type='text'],#builder_slide input[type='text']").each(function () {
				jQuery(this).on('keyup change',function() {
					jQuery(this).attr("value",jQuery(this).val());
				});
			});
		}
		
		function change_inputs_values_select() {
			jQuery("#builder select").each(function () {
				jQuery(this).on('change',function() {
					var main_select = jQuery(this);
					var multiple_s = main_select.attr("multiple");
					if (multiple_s !== undefined && multiple_s !== false) {
						var m_select = [];
						jQuery(this).find("option").each(function(i, selectedElement) {
							m_select[i] = jQuery(selectedElement).val();
							if (jQuery.inArray(m_select[i],main_select.val()) > -1) {
								jQuery(this).attr("selected","selected");
							}else {
								jQuery(this).removeAttr("selected");
							}
						});
					}else {
						main_select.find("option").each(function () {
							if (jQuery(this).val() == main_select.val()) {
								if (jQuery(this).filter(':selected')) {
									jQuery(this).attr("selected","selected");
								}else {
									jQuery(this).removeAttr("selected");
								}
							}else {
								jQuery(this).removeAttr("selected");
							}
						});
					}
				});
			});
		}
		
		/* Sort the sections */
		function sort_sections() {
			jQuery('.discy_framework .sort-sections').each(function () {
				if (!jQuery(this).hasClass("not-sort") && jQuery(this).hasClass("sort-sections-with")) {
					jQuery(this).sortable({
						placeholder: "ui-state-highlight",
						connectWith: "ul.sort-sections-with",
						handle: ".widget-head,.widget-handle",
						cancel: ".builder-toggle-open,.builder-toggle-close,.builder_clone,.del-builder-item,.switch,.not-sort .widget-handle,.not-sort .del-builder-item",
						receive: function (event, ui) {
							ui.item.addClass('builder_moved');
							var data_js_2 = jQuery(".builder_moved").attr("data-js-2");
							var builder_id = jQuery(".builder_moved").parent().attr("data-id").replace("builder_item_","");
							if (builder_id !== data_js_2) {
								var builder_li = jQuery(".builder_moved").parent().find(" > li").length;
								var builder_moved = jQuery(".builder_moved").clone().html();
								var data_id = jQuery(".builder_moved").attr("data-id");
								var data_js = jQuery(".builder_moved").attr("data-js");
								var last_ids = builder_id+'_'+builder_li;
								
								var i_count = 1;
								while (i_count < builder_li) {
									if (jQuery("#inner_builder_"+last_ids).length) {
										builder_li++;
										var last_ids = builder_id+'_'+builder_li;
									}
									i_count++;
								}
								
								var inner_id = "inner_builder_"+last_ids;
								if (jQuery('.builder_moved .adv_code_text').length) {
									var builder_id_editor = jQuery('.builder_moved .adv_code_text').attr("data-ids");
									var content = "";
									if (jQuery("#"+builder_id_editor+"-tmce").length) {
										jQuery("#"+builder_id_editor+"-tmce").click();
									}
									if (jQuery("#"+builder_id_editor).length) {
										content = tinyMCE.get(builder_id_editor).getContent();
									}
								}
								builder_moved = builder_moved.split(data_id+"["+data_js+"]").join("builder_item["+builder_id+"][options]["+builder_li+"]");
								jQuery(".builder_moved").attr("data-js",builder_li).attr("id",inner_id).attr("data-js-2",builder_id).attr("data-id",jQuery(".builder_moved").parent().attr("data-id")+"_"+builder_li);
								jQuery(".builder_moved").html(builder_moved);
								if (jQuery('#'+inner_id).find('.adv_code_text').length) {
									jQuery('#'+inner_id).find('.adv_code_text').attr("data-ids","builder_item_"+last_ids).attr("data-names","builder_item["+builder_id+"][options]["+builder_li+"][adv_code]");
									discy_new_editor("builder_item_"+last_ids,"builder_item["+builder_id+"][options]["+builder_li+"][adv_code]",content,jQuery('#'+inner_id).find('.adv_code_text > div'));
								}
							}
							ui.item.removeClass('builder_moved');
						}
					});
				}
			});
		}
		
		/* Change the head title */
		function discy_check(selector) {
			if (selector.find("input[type='checkbox']").length) {
				selector.find("input[type='checkbox']").each(function () {
					var discy_checkbox = jQuery(this);
					var checkbox_attr = discy_checkbox.attr("id");
					if (checkbox_attr !== undefined && checkbox_attr !== false) {
						checkbox_attr = " for='"+checkbox_attr+"'";
					}else {
						checkbox_attr = "";
					}
					discy_checkbox.wrap("<label class='switch'"+checkbox_attr+"></label>");
					discy_checkbox.after("<label"+checkbox_attr+" data-on='ON' data-off='OFF'></label>");
				});
			}
		}
		
		/* Click on radio */
		jQuery("#builder").on("click",".checkbox_select .checkbox-select",function () {
			jQuery(this).parent().parent().find(":radio").removeAttr('checked');
			jQuery(this).parent().parent().find("li").removeClass("selected");
			jQuery(this).parent().addClass("selected");
			jQuery(this).parent().find(":radio").attr("checked","checked");
			return false;
		});
		
		jQuery(".checkbox_select input:checked").parent().addClass("selected");
		
		/* Checkbox */
		jQuery(".checkbox_checkbox input:checked").parent().addClass("selected");
		jQuery("#builder").on("click",".checkbox_checkbox .checkbox-select",function () {
			jQuery(this).parent().toggleClass("selected");
			jQuery(this).parent().find(":checkbox").attr('checked', !jQuery(this).parent().find(":checkbox").attr('checked'));
			return false;
		});
		
		/* Sort elements */
		jQuery("#builder,#builder_slide").sortable({placeholder: "ui-state-highlight",handle: ".widget-head",cancel: ".builder-toggle-open,.builder-toggle-close,.builder_clone,.del-builder-item,.switch"});
		
		/* Toggle open & close */
		jQuery("#builder,#builder_slide").on("click",".builder-toggle-open",function () {
			var this_toggle = jQuery(this);
			this_toggle.parent().parent().find(" > .widget-content").slideToggle(300);
			this_toggle.css("display","none");
			this_toggle.parent().parent().find(" > .widget-head .builder-toggle-close").css("display","block");
		});
		
		jQuery("#builder,#builder_slide").on("click",".builder-toggle-close",function () {
			var this_toggle = jQuery(this);
			this_toggle.parent().parent().find(" > .widget-content").slideToggle("fast");
			this_toggle.css("display","none");
			this_toggle.parent().parent().find(" > .widget-head .builder-toggle-open").css("display","block");
		});
		
		/* Clone row & element  */
		jQuery("#builder,#builder_slide").on("click",".builder_clone",function() {
			var builder_li = jQuery(this).parent().parent().parent().find(" > li").length;
			var builder_clone = jQuery(this).parent().parent().clone().html();
			var builder_id = jQuery(this).parent().parent().parent().attr("id");
			var bui_id = jQuery(this).parent().parent().attr("id").replace("builder_","");
			var data_id = jQuery(this).parent().parent().attr("data-id");
			builder_li = builder_li+1;
			if (builder_id == "builder_slide") {
				var inner_builder_id = 'builder_slide_'+builder_li;
				builder_clone = builder_clone.split(data_id).join("builder_slide_item["+builder_li+"]");
				jQuery(this).parent().parent().after('<li id="'+inner_builder_id+'" data-id="builder_slide_item['+builder_li+']">'+builder_clone+"</li>");
				jQuery('#'+inner_builder_id+' > .widget-head > span > span').text(builder_li);
				jQuery("html,body").animate({scrollTop: jQuery("#"+inner_builder_id).offset().top-35},"slow");
				jQuery("#"+inner_builder_id).hide().fadeIn();
			}else {
				var data_js = jQuery(this).parent().parent().attr("data-js");
				var data_js_2 = jQuery(this).parent().parent().attr("data-js-2");
				var last_ids = data_js_2+"_"+builder_li;
				
				var i_count = 1;
				while (i_count < builder_li) {
					if (jQuery("#builder_"+data_js_2+" #inner_builder_"+data_js_2+"_"+builder_li).length) {
						builder_li++;
						var last_ids = data_js_2+"_"+builder_li;
					}
					i_count++;
				}
				
				var inner_builder_id = 'inner_builder_'+last_ids;
				builder_clone = builder_clone.split(data_id+"["+data_js+"]").join(data_id+"["+builder_li+"]");
				jQuery(this).parent().parent().after('<li id="'+inner_builder_id+'" data-js="'+builder_li+'" data-js-2="'+data_js_2+'" data-id="builder_item['+data_js_2+'][options]">'+builder_clone+"</li>");
				jQuery("html,body").animate({scrollTop: jQuery("#inner_builder_"+last_ids).offset().top-35},"slow");
				jQuery("#inner_builder_"+last_ids).hide().fadeIn();
			}
			sort_sections();
			change_inputs_values();
			jQuery('.tooltip_s').tipsy({gravity: 's'});
		});
		
		if (jQuery(".discy_scroll").length) {
			jQuery(".discy_scroll").scrollTop(300);
		}
		
		/* Builder */
		var categories_select = jQuery('#discy_categories_select').html();
		jQuery('#discy_categories_select').remove();
		var product_cat_select = jQuery('#discy_product_cat').html();
		jQuery('#discy_product_cat').remove();
		var sidebars_select = jQuery('#discy_sidebars_select').html();
		jQuery('#discy_sidebars_select').remove();
		
		jQuery("#discy_builder_meta_tab,#discy_slideshow_post").on("click",".add-item",function () {
			var builder_item = jQuery(this).attr("data-item");
			if (builder_item == "add_slide") {
				var builder_slide_j = jQuery('#builder_slide > li').length;
				builder_slide_j++;
				var i_count = 1;
				while (i_count < builder_slide_j) {
					if (jQuery("#builder_slide_"+builder_slide_j).length) {
						builder_slide_j++;
					}
					i_count++;
				}
				
				jQuery('#discy_slideshow_post ul').append('<li id="builder_slide_'+builder_slide_j+'" data-id="builder_slide_item['+builder_slide_j+']"><div><a class="widget-handle ui-sortable-handle"><span class="dashicons dashicons-editor-justify"></span></a><a class="del-builder-item del-sidebar-item"><span class="dashicons dashicons-trash"></span></a><div title="Clone" class="builder_clone"><span class="dashicons dashicons-welcome-add-page"></span></div></div><div class="widget-content"><h4 class="heading">'+discy_js.image_url+'</h4><div class="custom-meta-input"><input id="builder_slide_item['+builder_slide_j+'][image_url]" name="builder_slide_item['+builder_slide_j+'][image_url]" type="text" class="upload upload_image_'+builder_slide_j+'"><input class="upload_image_button button upload-button-2" rel="'+builder_slide_j+'" type="button" value="Upload"><input type="hidden" class="image_id" name="builder_slide_item['+builder_slide_j+'][image_id]"></div><div class="clear"></div><h4 class="heading">Slide Link</h4><div class="custom-meta-input"><input id="builder_slide_item['+builder_slide_j+'][slide_link]" name="builder_slide_item['+builder_slide_j+'][slide_link]" value="#" type="text"></div><div class="clear"></div></div></li>');
				jQuery("html,body").animate({scrollTop: jQuery("#builder_slide_"+builder_slide_j).offset().top-35},"slow");
				jQuery('#builder_slide_'+builder_slide_j).hide().fadeIn();
			}
			
			change_inputs_values();
			jQuery('.tooltip_s').tipsy({gravity: 's'});
			return false;
		});
		
		jQuery(".discy_framework #role_add").click(function() {
			var data_id = jQuery(this).attr("data-id");
			var role_name = jQuery('#role_name').val();
			if (role_name != "" ) {
				if( role_name.length){
					var roles_j = jQuery('.discy_framework #roles_list li').length+1;
					
					var i_count = 1;
					while (i_count < roles_j) {
						if (jQuery("#li_roles_"+roles_j).length) {
							roles_j++;
						}
						i_count++;
					}
					jQuery('#roles_list').append('<li id="li_roles_'+roles_j+'">\
						<div class="widget-head">'+role_name+'<a class="del-roles-item del-builder-item del-sidebar-item"><span class="dashicons dashicons-trash"></span></a></div>\
						<div class="widget-content">\
							<div class="widget-content-div">\
								<input id="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][new]" type="hidden" name="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][new]" value="new">\
								<input id="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][group]" type="hidden" name="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][group]" value="'+role_name+'">\
								<input type="hidden" class="group_role_name" name="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][id]" value="group_'+ roles_j +'">\
								<div class="clearfix"></div>\
								<div class="section section-checkbox"><div class="option"><div class="controls"><label class="switch" for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][ask_question]"><input id="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][ask_question]" value="on" class="checkbox discy-input" type="checkbox" checked name="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][ask_question]"><label for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][ask_question]" data-on="'+discy_js.on+'" data-off="'+discy_js.off+'"></label></label><label class="explain explain-checkbox" for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][ask_question]">'+discy_js.ask_question+'</label></div></div></div>\
								<div class="section section-checkbox"><div class="option"><div class="controls"><label class="switch" for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][ask_question_payment]"><input id="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][ask_question_payment]" value="on" class="checkbox discy-input" type="checkbox" checked name="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][ask_question_payment]"><label for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][ask_question_payment]" data-on="'+discy_js.on+'" data-off="'+discy_js.off+'"></label></label><label class="explain explain-checkbox" for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][ask_question_payment]">'+discy_js.ask_question_payment+'</label></div></div></div>\
								<div class="section section-checkbox"><div class="option"><div class="controls"><label class="switch" for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][show_question]"><input id="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][show_question]" value="on" class="checkbox discy-input" type="checkbox" checked name="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][show_question]"><label for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][show_question]" data-on="'+discy_js.on+'" data-off="'+discy_js.off+'"></label></label><label class="explain explain-checkbox" for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][show_question]">'+discy_js.show_question+'</label></div></div></div>\
								<div class="section section-checkbox"><div class="option"><div class="controls"><label class="switch" for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][add_answer]"><input id="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][add_answer]" value="on" class="checkbox discy-input" type="checkbox" checked name="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][add_answer]"><label for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][add_answer]" data-on="'+discy_js.on+'" data-off="'+discy_js.off+'"></label></label><label class="explain explain-checkbox" for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][add_answer]">'+discy_js.add_answer+'</label></div></div></div>\
								<div class="section section-checkbox"><div class="option"><div class="controls"><label class="switch" for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][add_answer_payment]"><input id="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][add_answer_payment]" value="on" class="checkbox discy-input" type="checkbox" checked name="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][add_answer_payment]"><label for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][add_answer_payment]" data-on="'+discy_js.on+'" data-off="'+discy_js.off+'"></label></label><label class="explain explain-checkbox" for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][add_answer_payment]">'+discy_js.add_answer_payment+'</label></div></div></div>\
								<div class="section section-checkbox"><div class="option"><div class="controls"><label class="switch" for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][show_answer]"><input id="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][show_answer]" value="on" class="checkbox discy-input" type="checkbox" checked name="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][show_answer]"><label for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][show_answer]" data-on="'+discy_js.on+'" data-off="'+discy_js.off+'"></label></label><label class="explain explain-checkbox" for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][show_answer]">'+discy_js.show_answer+'</label></div></div></div>\
								<div class="section section-checkbox"><div class="option"><div class="controls"><label class="switch" for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][add_group]"><input id="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][add_group]" value="on" class="checkbox discy-input" type="checkbox" checked name="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][add_group]"><label for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][add_group]" data-on="'+discy_js.on+'" data-off="'+discy_js.off+'"></label></label><label class="explain explain-checkbox" for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][add_group]">'+discy_js.add_group+'</label></div></div></div>\
								<div class="section section-checkbox"><div class="option"><div class="controls"><label class="switch" for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][add_post]"><input id="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][add_post]" value="on" class="checkbox discy-input" type="checkbox" checked name="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][add_post]"><label for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][add_post]" data-on="'+discy_js.on+'" data-off="'+discy_js.off+'"></label></label><label class="explain explain-checkbox" for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][add_post]">'+discy_js.add_post+'</label></div></div></div>\
								<div class="section section-checkbox"><div class="option"><div class="controls"><label class="switch" for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][add_post_payment]"><input id="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][add_post_payment]" value="on" class="checkbox discy-input" type="checkbox" checked name="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][add_post_payment]"><label for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][add_post_payment]" data-on="'+discy_js.on+'" data-off="'+discy_js.off+'"></label></label><label class="explain explain-checkbox" for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][add_post_payment]">'+discy_js.add_post_payment+'</label></div></div></div>\
								<div class="section section-checkbox"><div class="option"><div class="controls"><label class="switch" for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][add_category]"><input id="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][add_category]" value="on" class="checkbox discy-input" type="checkbox" checked name="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][add_category]"><label for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][add_category]" data-on="'+discy_js.on+'" data-off="'+discy_js.off+'"></label></label><label class="explain explain-checkbox" for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][add_category]">'+discy_js.add_category+'</label></div></div></div>\
								<div class="section section-checkbox"><div class="option"><div class="controls"><label class="switch" for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][send_message]"><input id="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][send_message]" value="on" class="checkbox discy-input" type="checkbox" checked name="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][send_message]"><label for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][send_message]" data-on="'+discy_js.on+'" data-off="'+discy_js.off+'"></label></label><label class="explain explain-checkbox" for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][send_message]">'+discy_js.send_message+'</label></div></div></div>\
								<div class="section section-checkbox"><div class="option"><div class="controls"><label class="switch" for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][upload_files]"><input id="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][upload_files]" value="on" class="checkbox discy-input" type="checkbox" checked name="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][upload_files]"><label for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][upload_files]" data-on="'+discy_js.on+'" data-off="'+discy_js.off+'"></label></label><label class="explain explain-checkbox" for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][upload_files]">'+discy_js.upload_files+'</label></div></div></div>\
								<div class="section section-checkbox"><div class="option"><div class="controls"><label class="switch" for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][approve_question]"><input id="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][approve_question]" value="on" class="checkbox discy-input" type="checkbox" checked name="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][approve_question]"><label for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][approve_question]" data-on="'+discy_js.on+'" data-off="'+discy_js.off+'"></label></label><label class="explain explain-checkbox" for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][approve_question]">'+discy_js.approve_question+'</label></div></div></div>\
								<div class="section section-checkbox"><div class="option"><div class="controls"><label class="switch" for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][approve_group]"><input id="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][approve_group]" value="on" class="checkbox discy-input" type="checkbox" checked name="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][approve_group]"><label for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][approve_group]" data-on="'+discy_js.on+'" data-off="'+discy_js.off+'"></label></label><label class="explain explain-checkbox" for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][approve_group]">'+discy_js.approve_group+'</label></div></div></div>\
								<div class="section section-checkbox"><div class="option"><div class="controls"><label class="switch" for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][approve_answer]"><input id="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][approve_answer]" value="on" class="checkbox discy-input" type="checkbox" checked name="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][approve_answer]"><label for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][approve_answer]" data-on="'+discy_js.on+'" data-off="'+discy_js.off+'"></label></label><label class="explain explain-checkbox" for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][approve_answer]">'+discy_js.approve_answer+'</label></div></div></div>\
								<div class="section section-checkbox"><div class="option"><div class="controls"><label class="switch" for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][approve_post]"><input id="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][approve_post]" value="on" class="checkbox discy-input" type="checkbox" checked name="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][approve_post]"><label for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][approve_post]" data-on="'+discy_js.on+'" data-off="'+discy_js.off+'"></label></label><label class="explain explain-checkbox" for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][approve_post]">'+discy_js.approve_post+'</label></div></div></div>\
								<div class="section section-checkbox"><div class="option"><div class="controls"><label class="switch" for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][approve_comment]"><input id="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][approve_comment]" value="on" class="checkbox discy-input" type="checkbox" checked name="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][approve_comment]"><label for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][approve_comment]" data-on="'+discy_js.on+'" data-off="'+discy_js.off+'"></label></label><label class="explain explain-checkbox" for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][approve_comment]">'+discy_js.approve_comment+'</label></div></div></div>\
								<div class="section section-checkbox"><div class="option"><div class="controls"><label class="switch" for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][approve_question_media]"><input id="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][approve_question_media]" value="on" class="checkbox discy-input" type="checkbox" checked name="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][approve_question_media]"><label for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][approve_question_media]" data-on="'+discy_js.on+'" data-off="'+discy_js.off+'"></label></label><label class="explain explain-checkbox" for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][approve_question_media]">'+discy_js.approve_question_media+'</label></div></div></div>\
								<div class="section section-checkbox"><div class="option"><div class="controls"><label class="switch" for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][approve_answer_media]"><input id="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][approve_answer_media]" value="on" class="checkbox discy-input" type="checkbox" checked name="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][approve_answer_media]"><label for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][approve_answer_media]" data-on="'+discy_js.on+'" data-off="'+discy_js.off+'"></label></label><label class="explain explain-checkbox" for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][approve_answer_media]">'+discy_js.approve_answer_media+'</label></div></div></div>\
								<div class="section section-checkbox"><div class="option"><div class="controls"><label class="switch" for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][without_ads]"><input id="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][without_ads]" value="on" class="checkbox discy-input" type="checkbox" checked name="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][without_ads]"><label for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][without_ads]" data-on="'+discy_js.on+'" data-off="'+discy_js.off+'"></label></label><label class="explain explain-checkbox" for="'+discy_js.discy_theme+'['+data_id+'][group_'+ roles_j +'][without_ads]">'+discy_js.without_ads+'</label></div></div></div>\
							</div>\
						</div>\
					</li>');
					jQuery("html,body").animate({scrollTop: jQuery("#li_roles_"+roles_j).offset().top-35},"slow");
				}
			}else {
				alert("Please write the name.");
			}
			jQuery('#role_name').val("");
	
		});
		
		/* Add a custom addition */
		jQuery(".discy_framework .add-item.add-item-2.add-item-6").on("click",function () {
			var add_item = jQuery(this);
			var item_id = jQuery(this).data("id");
			var item_type = jQuery(this).data("type");
			var addto = jQuery(this).data("addto");
			var toadd = jQuery(this).data("toadd");
			var item_name = jQuery(this).data("name");
			var select_val = add_item.parent().find("select").val();
			var select_text = add_item.parent().find("select option:selected").text();
			
			if (jQuery("#"+item_id+'_'+select_val).length) {
				jQuery("#"+item_id+'_'+select_val).addClass("removered").slideUp(function() {
					jQuery(this).slideDown().removeClass("removered");
				});
			}else {
				jQuery("#"+addto+"-ul").append('<li class="additions-li" id="'+item_id+'_'+select_val+'"><div class="widget-head"><'+(toadd == 'yes'?'label':'span')+'>'+select_text+'</'+(toadd == 'yes'?'label':'span')+'>'+(toadd == 'yes'?'':'</div>')+(toadd == 'yes'?'<input name="'+item_name+'['+item_type+'-'+select_val+']['+item_type+']" value="yes" type="hidden">':'')+'<input name="'+item_name+'['+item_type+'-'+select_val+']'+(toadd == 'yes'?'[value]':'')+'" value="'+select_val+'" type="hidden"><div><a class="widget-handle"><span class="dashicons dashicons-editor-justify"></span></a><a class="'+(toadd == 'yes'?'del-cat-item ':'')+'del-builder-item"><span class="dashicons dashicons-trash"></span></a></div>'+(toadd == 'yes'?'</div>':'')+'</li>');
			}
		});
		
		/* Add a new poll */
		jQuery("#upload_add_ask").click(function() {
			var poll_item = jQuery('#question_poll_item li').length+1;

			var i_count = 1;
			while (i_count < poll_item) {
				if (jQuery("#poll_item_"+poll_item).length) {
					poll_item++;
				}
				i_count++;
			}

			jQuery('#question_poll_item').append('<li id="poll_item_'+poll_item+'"><div><a class="widget-handle ui-sortable-handle"><span class="dashicons dashicons-editor-justify"></span></a><a class="del-builder-item del-sidebar-item"><span class="dashicons dashicons-trash"></span></a></div><div class="widget-content"><h4 class="heading">Value</h4><div class="custom-meta-input"><input id="ask['+poll_item+'][title]" name="ask['+poll_item+'][title]" type="text"><input id="ask['+poll_item+'][id]" name="ask['+poll_item+'][id]" value="'+poll_item+'" type="hidden"></div><div class="clear"></div></div></li>');
			return false;
		});
		
		/* Delete the item */
		jQuery(".sort-sections,#builder_slide").on("click",".del-builder-item",function() {
			if (jQuery(this).hasClass("del-element-item")) {
				jQuery(this).parent().parent().parent().parent().addClass('removered').fadeOut(function() {
					jQuery(this).remove();
				});
			}else if (jQuery(this).hasClass("del-cat-item")) {
				jQuery(this).parent().parent().parent().addClass('removered').fadeOut(function() {
					jQuery(this).remove();
				});
			}else if (jQuery(this).hasClass("del-roles-item")) {
				var roles_id = jQuery(this).parent().parent();
				var roles_val = roles_id.find(".group_role_name").val();
				jQuery.post(discy_js.ajax_a,{action:"discy_delete_role",roles_val:roles_val});
				roles_id.addClass('removered').fadeOut(function() {
					jQuery(this).remove();
				});
			}else if (jQuery(this).hasClass("del-sidebar-item")) {
				jQuery(this).parent().parent().addClass('removered').fadeOut(function() {
					jQuery(this).remove();
				});
			}else {
				jQuery(this).parent().addClass('removered').fadeOut(function() {
					jQuery(this).remove();
				});
			}
			return false;
		});
		
		/* Functions */
		change_inputs_values();
		sort_sections();

		/* Templates */
		if (jQuery("#page_template").length) {
			var page_template = jQuery("#page_template");
		}else {
			var page_template = jQuery(".editor-page-attributes__template select,.components-select-control__input");
		}
		page_template.on("change",function () {
			var page_template_val = jQuery(this).val();
			if (jQuery(".discy-admin-content .nav-tab[data-template]") !== undefined && jQuery(".nav-tab[data-template]") !== false) {
				if (jQuery(".nav-tab[data-template='"+page_template_val+"']").length) {
					jQuery(".discy_framework > .discy-group").hide();
					jQuery(".discy-admin-content .nav-tab").removeClass('nav-tab-active');
					
					jQuery(".discy-admin-content .nav-tab[data-template],.discy_framework > .discy-group[data-template]").hide().addClass("hide");
					
					jQuery(".discy-admin-content .nav-tab[data-template='"+page_template_val+"']").addClass('nav-tab-active');
					jQuery(".discy-admin-content .nav-tab[data-template='"+page_template_val+"'],.discy_framework > .discy-group[data-template='"+page_template_val+"']").show().removeClass('hide');
				}else {
					jQuery(".discy-admin-content .nav-tab[data-template],.discy_framework > .discy-group[data-template]").hide().addClass("hide").removeClass('nav-tab-active');
					if (jQuery(".discy-admin-content .nav-tab.nav-tab-active").length) {
						/* Has active tab */
					}else {
						jQuery('.discy_framework > .discy-group:not(.hide):first-child').animate({
							opacity: 'show',
							height: 'show'
						}, 200, function() {
							jQuery(this).removeClass('hide');
						});
						jQuery('.discy-admin-content .nav-tab-wrapper a:not(.hide):first-child').addClass('nav-tab-active');
					}
				}
			}else {
				jQuery('.discy_framework > .discy-group:not(.hide):first-child').animate({
					opacity: 'show',
					height: 'show'
				}, 200, function() {
					jQuery(this).removeClass('hide');
				});
				jQuery('.discy-admin-content .nav-tab-wrapper a:not(.hide):first-child').addClass('nav-tab-active');
			}
		});
	});
	
	function discy_admin_add_file(event, selector) {
		
		var frame,
		$el = jQuery(this),
		discy_admin_upload,
		discy_admin_selector = selector;
		
		event.preventDefault();

		// If the media frame already exists, reopen it.
		if ( discy_admin_upload ) {
			discy_admin_upload.open();
		} else {
			// Create the media frame.
			discy_admin_upload = wp.media.frames.discy_admin_upload = wp.media({
				// Set the title of the modal.
				title: $el.data('choose'),
				// Customize the submit button.
				button: {
					// Set the text of the button.
					text: $el.data('update'),
					// Tell the button not to close the modal, since we're
					// going to refresh the page when the image is selected.
					close: false
				}
			});

			// When an image is selected, run a callback.
			discy_admin_upload.on( 'select', function() {
				// Grab the selected attachment.
				var attachment = discy_admin_upload.state().get('selection').first();
				var attachment_attr = attachment.toJSON();
				var attr_width = discy_admin_selector.find('input[type="button"]').attr("data-width");
				var attr_height = discy_admin_selector.find('input[type="button"]').attr("data-height");
				discy_admin_upload.close();
				discy_admin_selector.find('.upload').val(attachment.attributes.url).change();
				
				if (attr_height !== undefined && attr_height !== false) {
					jQuery('#'+attr_height).val(attachment_attr.height);
				}
				if (attr_width !== undefined && attr_width !== false) {
					jQuery('#'+attr_width).val(attachment_attr.width);
				}
				
				if (discy_admin_selector.hasClass("upload-button-2")) {
					discy_admin_selector.parent().find('.upload').val(attachment.attributes.url);
					discy_admin_selector.parent().find('.image_id').val(attachment.attributes.id);
				}else if (discy_admin_selector.find(".image_id")) {
					discy_admin_selector.find('.image_id').val(attachment.attributes.id);
				}
				if (attachment.attributes.type == 'image') {
					discy_admin_selector.find('.screenshot').empty().hide().append('<img src="' + attachment.attributes.url + '"><a class="remove-image">'+discy_js.remove_image+'</a>').slideDown('fast');
				}
				discy_admin_selector.find('.upload-button').unbind().addClass('remove-file').removeClass('upload-button').val(discy_js.remove_image);
				discy_admin_selector.find('.discy-background-properties').slideDown();
				discy_admin_selector.find('.remove-image, .remove-file').on('click', function() {
					discy_admin_remove_file(jQuery(this).parent().parent());
				});
			});

		}

		// Finally, open the modal.
		discy_admin_upload.open();
	}

	function discy_admin_remove_file(selector) {
		selector.find('.remove-image').hide();
		selector.find('.upload,.image_id').val('');
		selector.find('.discy-background-properties').hide();
		selector.find('.screenshot').animate({
			opacity: 'hide',
			height: 'hide'
		}, 200, function() {
			selector.find('.screenshot').addClass('hide');
		});
		selector.find('.remove-file').unbind().addClass('upload-button').removeClass('remove-file').val(discy_js.upload_image).change();
		if ( jQuery('.section-upload .upload-notice').length ) {
			jQuery('.upload-button').remove();
		}
		selector.find('.upload-button').on('click', function(event) {
			discy_admin_add_file(event,jQuery(this).parent().parent());
		});
	}
		
	var update_form_elements = function(container,element = "") {
	
		container.find('.discy-form-text .discy-form-control,.discy-form-textarea .discy-form-control').keyup(function() {
			discy_form_change.call(this, jQuery(this).val());
		});
		
		container.find('.discy-form-select .discy-form-control,.discy-form-radio .discy-form-control,.discy-form-images .discy-form-control,.discy-form-checkbox .discy-form-control').change(function() {
			var ct = jQuery(this);
			var checked = [];
			if (ct.attr('type') == "checkbox") {
				if (ct.prop('checked')) {
					checked.push(ct.val());
				}
				checked = checked == "on" ? checked : false;
			}else {
				checked = jQuery(this).val();
			}
			discy_form_change.call(this, checked);
		});
		
		container.find('.section-multicheck_sort,.section-multicheck').each(function() {
			var pt = jQuery(this),
					lastChecked = null;
		
			jQuery(this).find('.discy-form-control').on('change', function(e) {
				var checked = [];
				pt.find('.discy-form-control').each(function() {
					var ct = jQuery(this);
					if (ct.prop('checked')) {
						checked.push(ct.val());
					}
				});
				checked = (checked.length > 0 ? checked : "no");
				discy_form_change.call(this, checked);
			});
		
			jQuery(this).find('li > .widget-head > label').on('click', function(e) {
				var t = jQuery(this).find('.discy-form-control');
				if (!lastChecked) {
					lastChecked = t;
					return;
				}
		
				if (e.shiftKey) {
					var curStart = t.parents('li').index(),
							curEnd = lastChecked.parents('li').index(),
							startIndex = Math.min(curStart, curEnd),
							endIndex = Math.max(curStart, curEnd) + 1,
							i;
		
					for (i = startIndex; i < endIndex; i++) {
						if (t.parents('li').index() != i) {
							pt.find('li').eq(i).find('.discy-form-control').prop('checked', lastChecked.prop('checked'));
						}
					}
				}
				lastChecked = t;
			});
		});
		
		jQuery(".discy-form-elements").find(element+' .discy-form-text .discy-form-control,'+element+' .discy-form-textarea .discy-form-control').each(function() {
			discy_form_change.call(this, jQuery(this).val());
		});
		
		jQuery(".discy-form-elements").find(element+' .discy-form-select .discy-form-control,'+element+' .discy-form-radio .discy-form-control,'+element+' .discy-form-images .discy-form-control,'+element+' .discy-form-checkbox .discy-form-control').each(function() {
			var ct = jQuery(this);
			var checked = [];
			if (ct.attr('type') == "checkbox") {
				if (ct.prop('checked')) {
					checked.push(ct.val());
				}
				checked = checked == "on" ? checked : false;
			}else {
				checked = jQuery(this).val();
			}
			discy_form_change.call(this, checked);
		});
		
		jQuery(".discy-form-elements").find(element+' .section-multicheck_sort,'+element+' .section-multicheck').each(function() {
			var pt = jQuery(this),
					lastChecked = null;
		
			var checked = [];
			pt.find('.discy-form-control').each(function() {
				var ct = jQuery(this);
				if (ct.prop('checked')) {
					checked.push(ct.val());
				}
			});
			checked = (checked.length > 0 ? checked : "no");
			discy_form_change.call(this, checked);
		});
		
		/* Image Options */
		container.find('.discy-radio-img-img').each(function () {
			var radio_img = jQuery(this);
			radio_img.parent().find('.discy-radio-img-label,.discy-radio-img-radio').hide();
			radio_img.show().click(function() {
				var radio_img = jQuery(this);
				radio_img.parent().parent().find('.discy-radio-img-img').removeClass('discy-radio-img-selected');
				radio_img.addClass('discy-radio-img-selected');
				radio_img.parent().find(".discy-radio-img-radio").click().attr('checked','checked');
				discy_form_change.call(this, radio_img.attr("value"));
			});
		});
		
		/* form element: colorpicker */
		container.find('.discy-form-color .discy-color,.discy-form-typography .discy-color,.discy-form-background .discy-color').each(function() {
		   var t = this,
				is_set = jQuery(t).hasClass('wp-color-picker');

		   if (is_set || jQuery(t).closest("#available-widgets").length) {
				return;
		   }
		   jQuery(t).wpColorPicker();
		});
		
		/* form element: datepicker */
		container.find('.discy-form-date .discy-date,.site-form-date .site-date').each(function() {
		   var t = this,
				is_set = jQuery(t).hasClass('hasDatepicker');

		   if (is_set || jQuery(t).closest("#available-widgets").length) {
				return;
		   }
		   jQuery(t).datepicker(jQuery(t).data('js'));
		});
		
		/* form element: sort sections */
		container.find('.section .sort-sections').each(function() {
		   var t = this;

			if (!jQuery(t).hasClass("not-sort") && !jQuery(t).hasClass("sort-sections-with")) {
				jQuery(t).sortable({
					placeholder: "ui-state-highlight",
					handle: ".widget-head,.widget-handle",
					cancel: ".builder-toggle-open,.builder-toggle-close,.builder_clone,.del-builder-item,.switch,.not-sort .widget-handle,.not-sort .del-builder-item"
				});
			}
		});
		
		/* form element: multicheck */
		container.find('.discy-form-multicheck_category .widget-switch').each(function() {
			var t = this,
				is_set = jQuery(t).hasClass('widget-switch-already'),
				checkbox_attr = jQuery(t).find(" > input").attr("id"),
				checkbox_for = "";

			if (is_set) {
				return;
			}

			if (checkbox_attr !== undefined && checkbox_attr !== false) {
				checkbox_for = " for='"+checkbox_attr+"'";
			}

			jQuery(t).addClass("widget-switch-already").attr("for",checkbox_attr).find(" > input").after("<label"+checkbox_for+" data-on='ON' data-off='OFF'></label>");
		});

		/* form element: sliderui */
		container.find('.discy-form-sliderui .v_sliderui,.discy-form-slider .v_sliderui').each(function() {
		   var t = this,
		   	sId = "#" + jQuery(t).data('id'),
				to,
				d = {
				   range : "min",
				   value : parseInt(jQuery(t).data('val')),
				   min   : parseInt(jQuery(t).data('min')),
				   max   : parseInt(jQuery(t).data('max')),
				   step  : parseInt(jQuery(t).data('step')),
				   slide: function(e, ui) {
						if(typeof to != 'undefined') {
						   clearTimeout(to);
						}
						jQuery(sId).val( ui.value );
						to = setTimeout(function() {
						   discy_form_change.call(t, ui.value);
						}, 400);
				   }
				},
				i;

		   for(i in d) {
				if(typeof jQuery(t).data(i) != 'undefined') {
				   d[i] = jQuery(t).data(i);
				}
		   }

		   jQuery(t).slider(d);
		});
		
		/* form element: upload */
		container.find('.discy-form-upload .form-upload-images,.discy-form-background .form-upload-images').each(function() {
			var t = this;
    	    jQuery(t).find('.remove-image,.remove-file').on('click', function() {
    			discy_admin_remove_file(jQuery(this).parent().parent());
    	    });
    	
    	    jQuery(t).find('.upload-button').on("click", function( event ) {
    	    	discy_admin_add_file(event,jQuery(this).parent().parent());
    	    });
    	    
    	    jQuery(t).find('.upload-button-2').on( "click", function( event ) {
    	    	discy_admin_add_file(event,jQuery(this).parent().parent());
    	    });
		});

		/* form element: code */
		container.find('.discy-code-editor').each(function() {

		   var t = this,
				id = jQuery(t).attr('id'),
				name = jQuery(t).data('name'),
				mode = jQuery(t).data('mode'),
				theme = jQuery(t).data('theme'),
				el_holder = jQuery(t).siblings('textarea[name="'+name+'"]');

		   if(!el_holder.length) {
				el_holder = jQuery('<textarea>').attr({name:name}).addClass('discy-form-control').hide();
				jQuery(t).before(el_holder);
		   }

		   var discy_editor = discy.edit(id);
		   discy_editor.setTheme('discy/theme/'+ theme);
		   discy_editor.getSession().setMode('discy/mode/'+ mode);

		   discy_editor.on('change', function(ev, editor) {
				var v = editor.getValue();
				el_holder.text(v);
				discy_form_change.call(t, v);
		   });

		});
		
		/* fix wp_editor on ajax call */
		container.find('.discy-form-wp-editor').each(function() {

		   var t = jQuery(this),
				p = t.parents('.discy-form-block'),
				p_is_hidden = p.hasClass('discy_hide'),
				id = t.find('.wp-editor-area').attr('id'),
				preloaded_wp_editor_id = 'discy_preloaded_editor_id';

		   if ( typeof id == 'undefined'
				|| typeof quicktags == 'undefined'
				|| typeof tinyMCEPreInit != 'object'
				|| typeof tinyMCEPreInit.mceInit != 'object'
				|| ! tinyMCEPreInit.mceInit.hasOwnProperty(preloaded_wp_editor_id) ) {
				return;
		   }

		   p.removeClass('discy_hide');

		   var mceinit_params = jQuery.extend(true, {}, tinyMCEPreInit.mceInit[preloaded_wp_editor_id]);

		   mceinit_params.selector = '#'+id;
		   mceinit_params.resize = true;
		   mceinit_params.toolbar1 = mceinit_params.toolbar1.replace( /(fullscreen)/g, '' );
		   mceinit_params.toolbar2 = mceinit_params.toolbar2.replace( /(wp_help)/g, '' );

		   if (mceinit_params.hasOwnProperty('body_class')) {
				mceinit_params.body_class = mceinit_params.body_class.replace(preloaded_wp_editor_id, id);
		   }

		   tinyMCE.init(mceinit_params);
		   tinyMCE.execCommand('mceAddEditor', false, id);
		   quicktags({id : id});

		   if (p_is_hidden) {
				p.addClass('discy_hide');
		   }

		});
			
		return container;
    };
		
	jQuery(document).ajaxSuccess(function(e, xhr, settings) {
		var getParameterByName = function(name, url) {
				name = name.replace(/[\[\]]/g, "\\$&");
				var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
				   results = regex.exec(url);
				if (!results) return null;
				if (!results[2]) return '';
				return decodeURIComponent(results[2].replace(/\+/g, " "));
		   },
		   action = getParameterByName( 'action', settings.data );

		if (action == 'save-widget') {

		   var widget_id = getParameterByName( 'widget-id', settings.data ),
				sidebar = getParameterByName( 'sidebar', settings.data );

		   jQuery('#'+ sidebar +' .widget').each(function() {

				var t = jQuery(this),
				   id = t.attr('id');

				if (id.indexOf(widget_id) != -1) {
				   update_form_elements(t);
				}

		   });

		}

    });
	
	/* Condition */
	var objectValues = function(obj) {
		var arr = [],
				o;
	
		for (o in obj) {
			arr.push(obj[o]);
		}
		return arr;
	};
	
	get_form_data = function(container) {
		var form_data = {},
				i;
	
		container.find('.section,.wrap_class,.options-group').each(function() {
			var t = jQuery(this),
					id = t.attr('data-id'),
					type = t.attr('data-type'),
					fc = t.find('.discy-form-control');
			if (fc.length == 0) {
				return;
			}
	
			form_data[id] = {};
			var checked = [];
	
			if (fc.length > 1) {
				fc.each(function() {
					var it = jQuery(this),
							i = it.attr('value');
	
					if (typeof i != 'undefined') {
						if (type != 'checkbox' || it.prop('checked')) {
							if (it.prop('checked')) {
								checked.push(it.val());
								form_data[id] = checked;
							}else {
								form_data[id][i] = it.val();
							}
						}
					}
				});
			}else if (fc.length == 1) {
				if (type == 'checkbox') {
					if (fc.prop('checked')) {
						checked.push(fc.val());
					}
					checked = checked == "on" ? checked : false;
					form_data[id] = checked;
				}else {
					form_data[id] = fc.val();
				}
				if (type == 'wp_editor' && typeof(tinyMCE) != 'undefined') {
					var ed = tinyMCE.editors[fc.attr('id')];
					if (typeof ed != 'undefined') {
						form_data[id] = ed.getContent();
					}
				}
			}
		});
		return form_data;
	}
	
	discy_form_change = function(value) {
		//set_data_changed();
		var el = jQuery(this),
				el_form_block = el.parents('.section,.wrap_class,.options-group');
	
		if (!el_form_block.length) {
			return;
		}
		var id = el_form_block.attr('data-id');
		if (typeof id == 'undefined') {
			return;
		}
		var el_container = el.parents('.discy_framework');
		if (el.parents('.widget-content').length) {
			el_container = el.parents('.widget-content');
		}
		var form_data = get_form_data(el_container);
		el_container.find('.section,.wrap_class,.options-group').each(function() {
	
			var t = jQuery(this),
					condition = t.attr('data-condition'),
					operator = t.attr('data-operator');
	
			if (typeof condition == 'undefined') {
				return;
			}
	
			if (typeof operator == 'undefined' || ['and', 'or'].indexOf(operator) == -1) {
				operator = 'and';
			}
	
			var bool_arr = [],
					cond_arr = condition.split('),'),
					i;
	
			for (i in cond_arr) {
				if (cond_arr[i].slice(-1) != ')') {
					cond_arr[i] += ')';
				}
	
				var m = cond_arr[i].match(/^([a-z0-9_]+)\:(not|is|has|has_not)\(([a-z0-9-_\,]+)\)$/i),
						m_bool = false;
				
				if (m != null) {
					var m_id = m[1],
							m_op = m[2],
							m_val = m[3];
					if (!form_data.hasOwnProperty(m_id)) {
						form_data[m_id] = '';
					}
					
					if (['is', 'not'].indexOf(m_op) != -1) {
						if (m_val == "empty" && (m_op == 'not' || m_op == 'is')) {
							if (m_op == 'not') {
								m_bool = (form_data[m_id] != "");
							}else {
								m_bool = (form_data[m_id] == "");
							}
						}else {
							m_bool = (form_data[m_id] == m_val);
							if (m_op == 'not') {
								m_bool = !m_bool;
							}
						}
					}else if (['has', 'has_not'].indexOf(m_op) != -1) {
						if (typeof form_data[m_id] == 'string') {
							form_data[m_id] = form_data[m_id].split(',');
						}else if (typeof form_data[m_id] != 'object') {
							form_data[m_id] = [];
						}else if (!(form_data[m_id] instanceof Array)) {
							form_data[m_id] = [];
						}
						m_val = m_val.split(',');
						var j, k = [];
						for (j in m_val) {
							if (m_val != 0) {
								k.push(form_data[m_id].indexOf(m_val[j]) != -1);
							}
						}
						m_bool = (k.indexOf(false) == -1);
						if (m_op == 'has_not') {
							m_bool = !m_bool;
						}
					}
				}
				bool_arr.push(m_bool);
			}
	
			var is_hidden = false;
			if (operator == 'or') {
				is_hidden = (bool_arr.indexOf(true) == -1);
			}else {
				is_hidden = (bool_arr.indexOf(false) != -1);
			}
	
			if (is_hidden) {
				t.animate({
					opacity: 'hide',
					height: 'hide'
				}, 200, function() {
					t.addClass('hide');
				});
			}else {
				t.animate({
					opacity: 'show',
					height: 'show'
				}, 200, function() {
					t.removeClass('hide');
				});
			}
		});
	}
	
	/*
	jQuery('body').on('click', '.discy-form-control', function(e) {
		e.preventDefault();
		var t = jQuery(this),
		v = t.data('value'),
		c = 'active';
	
		if(typeof v == 'undefined') {
			return;
		}
	
		t.addClass(c).siblings('li').removeClass(c);
		t.parents('ul').siblings('.discy-form-control').val(v);
		discy_form_change.call(this, v);
	});
	*/
	
	/* Add a new element */
	jQuery(".discy_framework .add_element").on("click",function () {
		var add_element = jQuery(this);
		if (!add_element.hasClass("not_add_element")) {
			var discy_theme_var = discy_js.discy_theme;
			var data_id	 = add_element.attr("data-id");
			var data_add_to = add_element.parent().find(".all_elements ul").attr("data-to");
			var data_id_name = "["+data_id+"]";
			var data_add_to_name = "["+data_add_to+"]";
			
			if (add_element.hasClass("no_theme_options")) {
				var discy_theme_var = "";
				var data_id_name = data_id;
				var data_add_to_name = data_add_to;
			}
			
			var data_title  = add_element.attr("data-title");
			if (data_add_to !== undefined && data_add_to !== false) {
				var add_element_j = jQuery("#"+data_add_to+" li").length;
				add_element_j++;
				var data_add_to_id = data_add_to;
			}else {
				var add_element_j = add_element.parent().find("."+data_id+"_j").attr("data-js");
				var data_add_to_id = data_id;
			}
			
			var element_id = "elements_"+data_add_to_id+"_"+add_element_j;
			add_element.parent().find(".all_elements ul li").clone().attr("id",element_id).appendTo('#'+data_add_to_id);
			jQuery("html,body").animate({scrollTop: jQuery("#"+element_id).offset().top-35},"slow");
			
			if (data_title !== undefined && data_title !== false) {
				jQuery("#"+element_id+" .del-builder-item,#"+element_id+" a.widget-handle").wrapAll("<div class='widget-head' />");
				jQuery("#"+element_id+" > div:not(.widget-content)").prepend(jQuery(add_element.parent().find(".all_elements ul li input[data-title='"+data_title+"']")).val());
			}
			
			jQuery("#"+element_id+" .section,#"+element_id+" .wrap_class").each(function () {
				var this_each = jQuery(this);
				if (!this_each.hasClass("wrap_class")) {
					if (data_add_to !== undefined && data_add_to !== false) {
						var last_id = discy_theme_var+"_"+data_add_to+"_"+add_element_j+"_"+this_each.attr("data-attr");
					}else {
						var last_id = discy_theme_var+"_"+data_id+"_"+add_element_j+"_"+this_each.attr("data-attr");
					}
				}

				var condition = this_each.attr("data-condition");
				if (condition !== undefined && condition !== false) {
					this_each.attr("data-condition",condition.split("[%id%]").join(discy_theme_var+"_"+data_add_to_id+"_"+add_element_j+"_"));
				}

				if (!this_each.hasClass("wrap_class")) {
					this_each.attr("data-id",last_id).attr("id","section-"+last_id);
					
					if (this_each.find("div.v_slidersui").length) {
						this_each.find("div.v_slidersui").attr("id",last_id+"-slider").attr("data-id",last_id).addClass('v_sliderui').removeClass('v_slidersui');
					}
				}
			});
			
			jQuery("#"+element_id+" .widget-content select").each(function () {
				var this_each = jQuery(this);
				if (data_add_to !== undefined && data_add_to !== false) {
					var last_id   = discy_theme_var+"_"+data_add_to+"_"+add_element_j+"_"+this_each.attr("data-attr");
					var last_name = discy_theme_var+data_add_to_name+"["+add_element_j+"]["+this_each.attr("data-attr")+"]";
				}else {
					var last_id   = discy_theme_var+"_"+data_id+"_"+add_element_j+"_"+this_each.attr("data-attr");
					var last_name = discy_theme_var+data_id_name+"["+add_element_j+"]["+this_each.attr("data-attr")+"]";
				}
				this_each.attr("name",last_name).attr("id",last_id);
			});
			
			jQuery("#"+element_id+" .widget-content input,#"+element_id+" .widget-content textarea").each(function () {
				var this_each = jQuery(this);
				if (data_add_to !== undefined && data_add_to !== false) {
					var last_id   = discy_theme_var+"_"+data_add_to+"_"+add_element_j+"_"+this_each.attr("data-attr");
					var last_name = discy_theme_var+data_add_to_name+"["+add_element_j+"]["+this_each.attr("data-attr")+"]";
				}else {
					var last_id   = discy_theme_var+"_"+data_id+"_"+add_element_j+"_"+this_each.attr("data-attr");
					var last_name = discy_theme_var+data_id_name+"["+add_element_j+"]["+this_each.attr("data-attr")+"]";
				}
				this_each.attr("id",last_id).not('[type="button"]').attr("name",last_name);
				if (this_each.is('[type="hidden"]')) {
					this_each.val(add_element_j);
				}
				if (this_each.is('[type="radio"]')) {
					this_each.attr("id",last_id+"_"+this_each.attr("value")).next("label").attr("for",last_id+"_"+this_each.attr("value"));
				}
				if (this_each.is('[type="checkbox"]')) {
					this_each.closest(".switch").attr("for",last_id).find(" > label").attr("for",last_id);
				}
				if (this_each.is('[data-type="uniq_id"]')) {
					this_each.val(add_element_j);
				}
			});
			
			update_form_elements(jQuery('#'+element_id),'#'+element_id);
			
			if (data_add_to !== undefined && data_add_to !== false) {
				jQuery("#"+element_id).append('<input name="'+discy_theme_var+'['+data_add_to+']['+add_element_j+'][getthe]" value="'+data_add_to+'" type="hidden">');
			}
			if (!add_element.parent().find(".all_elements ul li input").is(':radio') && !add_element.parent().find(".all_elements ul li input").is(':checkbox') && !add_element.parent().find(".all_elements ul li input.upload_image_button,.upload_image_button_m")) {
				add_element.parent().find(".all_elements ul li input").val("");
			}
			if (!add_element.parent().find(".all_elements ul li textarea")) {
				add_element.parent().find(".all_elements ul li textarea").val("");
			}
			add_element_j++;
			add_element.parent().find("."+data_id+"_j").attr("data-js",add_element_j);
			
			jQuery('#'+element_id+' .discy-colors').wpColorPicker();
			var attr_js = jQuery('#'+element_id+' .builder-datepicker').data('js');
			jQuery('#'+element_id+' .builder-datepicker').removeClass("builder-datepicker").removeClass("hasDatepicker").addClass("discy-datepicker").datepicker((attr_js !== undefined && attr_js !== false?attr_js:{}));
			jQuery("#"+element_id).closest("ul").removeClass("sort-sections-empty");
		}
	});
	
	/* Meta box */
	if (jQuery("#post_type").length) {
		var post_type = jQuery("#post_type").val();
		if (post_type == "question") {
			jQuery("#commentsdiv > h2 > span").text(discy_js.answers);
			jQuery("#commentsdiv #add-new-comment a").text(discy_js.add_answer_button);
			jQuery("#commentsdiv #no-comments").text(discy_js.no_answers);
		}
	}
	
	if (jQuery('.discy-typography-face').length) {
		jQuery('.discy-typography-face').fontselect();
	}
	
	/* Datepicker */
	jQuery('.discy-datepicker,.builder-datepicker').each(function () {
		var this_date = jQuery(this);
		var attr_js = this_date.data('js');
		this_date.datepicker((attr_js !== undefined && attr_js !== false?attr_js:{}));
	});
	
	if (jQuery('#discy-admin-wrap select[multiple]').length) {
		jQuery('#discy-admin-wrap select[multiple]').select2();
	}
	
	if (jQuery('.tooltip_n').length) {
		jQuery('.tooltip_n').tipsy({gravity: 'n'});
	}
	
	if (jQuery('.tooltip_s').length) {
		jQuery('.tooltip_s').tipsy({gravity: 's'});
	}
	
	update_form_elements(jQuery('body'));

	/* Loads tabbed sections if they exist */
	if ( jQuery('#discy_meta_tabs .discy-admin-content .nav-tab-wrapper').length ) {
		discy_admin_tabs("meta");
	}else if ( jQuery('.discy-admin-content .nav-tab-wrapper').length ) {
		discy_admin_tabs();
	}

	function discy_admin_tabs(meta = '') {
		// Hides all the .discy-group sections to start
		jQuery('.discy-group').hide();
		
		// Find if a selected tab is saved in localStorage
		var discy_v = '';
		var discy_option = "discy_v"+(meta != ""?"_meta":"");
		if ( typeof(localStorage) != 'undefined' ) {
			discy_v = localStorage.getItem(discy_option);
		}

		// If active tab is saved and exists, load it's .discy-group
		if (discy_v != '' && jQuery(discy_v).length && !jQuery(discy_v + '-tab').hasClass('hide') ) {
			jQuery(discy_v).animate({
				opacity: 'show',
				height: 'show'
			}, 200, function() {
				jQuery(this).removeClass('hide');
			});
			jQuery(discy_v + '-tab').addClass('nav-tab-active');
		}else {
			jQuery('.discy-group:not(.hide):first-child').animate({
				opacity: 'show',
				height: 'show'
			}, 200, function() {
				jQuery(this).removeClass('hide');
			});
			jQuery('.discy-admin-content .nav-tab-wrapper a:not(.hide):first-child').addClass('nav-tab-active');
		}
		
		// Bind tabs clicks
		jQuery('.discy-admin-content .nav-tab-wrapper a').click(function(evt) {

			evt.preventDefault();

			// Remove active class from all tabs
			jQuery('.discy-admin-content .nav-tab-wrapper a').removeClass('nav-tab-active');

			jQuery(this).addClass('nav-tab-active').blur();

			var group = jQuery(this).attr('href');

			if (typeof(localStorage) != 'undefined' ) {
				localStorage.setItem(discy_option, jQuery(this).attr('href') );
			}

			jQuery('.discy-group').hide();
			jQuery(group).animate({
				opacity: 'show',
				height: 'show'
			}, 200, function() {
				jQuery(this).removeClass('hide');
			});

			// Editor height sometimes needs adjustment when unhidden
			jQuery('.wp-editor-wrap').each(function() {
				var editor_iframe = jQuery(this).find('iframe');
				if ( editor_iframe.height() < 30 ) {
					editor_iframe.css({'height':'auto'});
				}
			});

		});
		
		/* Discy tabs */
		var discy_tab_value = '';
		if (typeof(localStorage) != 'undefined') {
			discy_tab_value = localStorage.getItem('discy_tab_value');
		}
		
		if (discy_tab_value != '' && jQuery(".discy_tabs a[href='"+discy_tab_value+"']").length) {
			jQuery(".discy_tabs a[href='"+discy_tab_value+"']").parent().parent().parent().find(".head-group").hide(10);
			if (jQuery(".discy_tabs a[href='"+discy_tab_value+"']").parent().parent().parent().find(discy_tab_value).length) {
				jQuery(".discy_tabs a[href='"+discy_tab_value+"']").addClass("discy_active").parent().parent().parent().find(discy_tab_value).slideDown(300);
			}else {
				jQuery(discy_v).find(".discy_tabs > li:first-child a").addClass("discy_active").click();
				jQuery(jQuery(discy_v).find(".discy_tabs > li:first-child a").attr("href")).slideDown(300);
			}
		}else {
			jQuery(discy_v).find(".discy_tabs > li:first-child a").addClass("discy_active").click();
		}
		
		jQuery('.discy_tabs a').click(function(evt) {
			evt.preventDefault();
			jQuery(this).parent().parent().parent().find(".head-group").hide(10);
			jQuery(this).parent().parent().find(".discy_active").removeClass("discy_active");
			jQuery(this).addClass("discy_active").parent().parent().parent().find(jQuery(this).attr('href')).slideDown(300);
			if (typeof(localStorage) != 'undefined') {
				localStorage.setItem('discy_tab_value', jQuery(this).attr('href'));
			}
		});
		
		jQuery(".discy_tabs").each(function () {
			var data_std = jQuery(this).attr("data-std");
			if (data_std != jQuery(".discy_tabs a[href='"+discy_tab_value+"']").parent().parent().attr("data-std")) {
				jQuery(".discy_tabs a[href='"+data_std+"']").addClass("discy_active");
				jQuery(data_std).slideDown(300);
			}
		});
	}
	
	/* Save options */
	if (jQuery('input.discy_save').length) {
	    jQuery("input.discy_save").click(function() {
	    	var typingTimer;
	    	jQuery("#ajax-saving").fadeIn("slow");
	    	jQuery("#loading").show();
	    	if (jQuery(".discy_editor").length && typeof(tinyMCE) != 'undefined' && tinyMCE !== undefined && tinyMCE !== false) {
	    		tinyMCE.triggerSave();
	    	}
			
			var $data = jQuery('#main_options_form').serialize();
			jQuery('#main_options_form').find('input[type=checkbox]').each(function() {
				if ( typeof $( this ).attr( 'name' ) !== "undefined" ) {
					var chkVal = $( this ).is( ':checked' ) ? $( this ).val() : "0";
					$data += "&" + $( this ).attr( 'name' ) + "=" + chkVal;
				}
			});
			var import_setting = jQuery("#import_setting").val();
			if (import_setting != "") {
				jQuery.ajax({
					type: "POST",
					url: discy_js.ajax_a,
					data: {
						action: "discy_import_options",
						data: import_setting
					},
					success: function (data) {
						jQuery(".discy_save").blur();
						clearTimeout(typingTimer);
						typingTimer = setTimeout(function () {
							jQuery("#ajax-saving").fadeOut("slow");
							jQuery("#loading").hide();
						},500);
						jQuery("#import_setting").val("");
						location.reload();
					},error: function (jqXHR, textStatus, errorThrown) {
						// Error
					},complete: function () {
						// Done
					}
				});
			}else {
				jQuery.ajax({
					type: "POST",
					url: discy_js.ajax_a,
					data: {
						action: "discy_update_options",
						data: $data
					},
					cache: false,
					dataType: "json",
					success: function (data) {
						jQuery(".discy_save").blur();
						clearTimeout(typingTimer);
						typingTimer = setTimeout(function () {
							jQuery("#ajax-saving").fadeOut("slow");
							jQuery("#loading").hide();
						},500);
					},error: function (jqXHR, textStatus, errorThrown) {
						// Error
					},complete: function () {
						jQuery(".discy_save").blur();
						clearTimeout(typingTimer);
						typingTimer = setTimeout(function () {
							jQuery("#ajax-saving").fadeOut("slow");
							jQuery("#loading").hide();
						},500);
					}
				});
			}
	    	return false;
	    });
	}
	
	/* Reset options */
	if (jQuery('#reset_c').length) {
	    jQuery("#reset_c").click(function() {
	    	var answer = confirm(discy_js.confirm_reset);
	    	if (answer) {
	    		jQuery("#ajax-reset").fadeIn("slow");
	    		var defaults = "&action=discy_reset_options";
	    		jQuery.post(discy_js.ajax_a,defaults,function (data) {
	    			jQuery("#reset_c").blur();
	    			setTimeout(function() {
	    				jQuery("#ajax-reset").fadeOut("slow");
	    				location.reload();
	    			},200);
	    		});
	    	}
	    	return false;
	    });
	}
	
	/* Live search at the admin setting */
	if (jQuery(".discy_search").length) {
		var live_search = jQuery(".discy_search input");
		var typingTimer;
		live_search.on("keyup",function() {
			live_search = jQuery(this);
			var search_value = live_search.val();
			if (search_value == "") {
				live_search.parent().find(".search-results").addClass("results-empty").html("").hide();
			}else {
				var search_loader = live_search.parent().find(".search_loader");
				clearTimeout(typingTimer);
				typingTimer = setTimeout(function () {
					search_loader.show(10);
					jQuery(".section-search-hide").removeClass("section-search-hide").addClass("hide");
					jQuery(".search-select").removeClass("search-select");
					jQuery.ajax({
						url: discy_js.ajax_a,
						type: "POST",
						data: { action : 'discy_admin_live_search',search_value : search_value },
						success:function(data) {
							live_search.parent().find(".search-results").removeClass("results-empty").html(data).slideDown(300);
							search_loader.hide(10);
						}
					});
				},500);
			}
		});
		
		live_search.on('focus',function() {
			var live_search  = jQuery(this);
			if (live_search.parent().find(".results-empty").length == 0) {
				live_search.parent().find(".search-results").show();
			}
		});
		
		live_search.parent().on('click','a',function() {
			var thisElem  = jQuery(this),
			itemId        = thisElem.attr('href'),
			thistextid    = jQuery("#"+itemId).closest('.discy-group'),
			thisparent    = jQuery("#"+itemId).closest('.options-group'),
			thiscondition = jQuery("#"+itemId).closest('.hide[data-condition]');
			
			jQuery(".section-search-hide").removeClass("section-search-hide").addClass("hide");
			jQuery(".search-select").removeClass("search-select");
			
			jQuery('#'+thistextid.attr("id")+'-tab').addClass("nav-tab-active").click();
			jQuery(".discy_tabs").find("a[href='#"+thisparent.attr("id")+"']").click();
			jQuery("#"+itemId).addClass("search-select");
			if (jQuery("#"+itemId).hasClass("hide")) {
				jQuery("#"+itemId).removeClass("hide").addClass("section-search-hide");
			}
			if (thiscondition.hasClass("hide")) {
				thiscondition.removeClass("hide").addClass("section-search-hide");
			}
			jQuery("html,body").animate({scrollTop: jQuery("#"+itemId).offset().top-35},"slow");
			return false;
		});
		
		var outputContainer = live_search.parent().find('.search-results');
		var input           = live_search.get(0);
		jQuery('body').bind('click', function(e) {
			if (!jQuery.contains(outputContainer.get(0), e.target) && e.target != input) {
				outputContainer.hide();
			}
		});
	}
});