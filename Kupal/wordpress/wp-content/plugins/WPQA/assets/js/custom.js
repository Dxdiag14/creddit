(function($) { "use strict";

	/* Copy text */

	if (jQuery(".referral-invitation").length) {
		jQuery(".referral-invitation a").on("click",function() {
			var copyText = jQuery(".referral-invitation input");
			copyText.select();
			document.execCommand("copy");
			return false;
		});
	}
	
	/* Datepicker */
	
	if (jQuery(".age-datepicker").length) {
		jQuery(".age-datepicker").datepicker({changeMonth:true,changeYear:true,yearRange:"-90:+00",dateFormat:"yy-mm-dd"});
	}

	if (jQuery(".date-datepicker").length) {
		jQuery(".date-datepicker").datepicker({changeMonth:true,dateFormat:"yy-mm-dd"});
	}
	
	/* Search */
	
	if (jQuery(".search-click").length) {
		jQuery(".search-click").on('touchstart click', function(){
			jQuery(".header-search").addClass("header-search-active");
			jQuery(".header-search input[type='search']").focus();
			jQuery(".search-click").hide();
			jQuery(".main-content").on("click",function () {
				jQuery(".search-click").show();
				jQuery(".header-search").removeClass("header-search-active");
			});
		});
	}
	
	if (jQuery(".search_type.user_filter_active").length) {
		jQuery(".search_type.user_filter_active").change(function () {
			var ThisSelect = jQuery(this);
			if (ThisSelect.val() == "users") {
				jQuery(".post-search .row > .col").removeClass("col6").addClass("col4");
				ThisSelect.parent().parent().parent().find(".user-filter-div select").attr("name","user_filter");
				jQuery(".user-filter-div").animate({
					opacity: 'show',
					height: 'show'
				},200, function() {
					jQuery(".user-filter-div").removeClass('hide');
				});
			}else {
				jQuery(".user-filter-div").animate({
					opacity: 'hide',
					height: 'hide'
				},200, function() {
					jQuery(".user-filter-div").addClass('hide');
					jQuery(".post-search .row > .col").removeClass("col4").addClass("col6");
					ThisSelect.parent().parent().parent().removeAttr("name");
				});
			}
		});
	}
	
	if (jQuery(".mobile-bar-search").length) {
		jQuery(".mobile-bar-search > a").click(function () {
			jQuery(".mobile-bar-search > form").animate({
				opacity: 'show',
				height: 'show'
			},100, function() {
				jQuery(".mobile-bar-search").addClass('mobile-bar-search-active');
			});
			return false;
		});
		jQuery(".mobile-bar-search form i").click(function () {
			jQuery(".mobile-bar-search > form").animate({
				opacity: 'hide',
				height: 'hide'
			},100, function() {
				jQuery(".mobile-bar-search").removeClass('mobile-bar-search-active');
			});
		});
	}

	if (jQuery(".live-search").length) {
		jQuery(".live-search").each(function () {
			var main_live_search   = jQuery(this);
			var main_search_form   = (main_live_search.hasClass("suggest-questions")?".the-title-div":".main-search-form");
			var suggest_questions  = (main_live_search.hasClass("suggest-questions")?"suggest-questions":"");
			var doneTypingInterval = 500;
			var typingTimer;
			main_live_search.on("keyup",function() {
				var live_search  = jQuery(this);
				var search_value = live_search.val();
				if (search_value == "") {
					live_search.closest(main_search_form).find(".search-results").addClass("results-empty").html("").hide();
				}else {
					var search_type = (main_live_search.hasClass("suggest-questions")?"questions":live_search.closest(main_search_form).find(".search_type").val());
					var search_loader = live_search.closest(main_search_form).find(".search_loader");
					clearTimeout(typingTimer);
					typingTimer = setTimeout(function () {
						if (live_search.hasClass("live-search-icon")) {
							live_search.closest(main_search_form).find("i.icon-search").attr("class","icon-arrows-ccw fa-spin");
						}else {
							search_loader.show(10);
						}
						jQuery.ajax({
							url: wpqa_custom.admin_url,
							type: "POST",
							data: { action : 'wpqa_live_search',search_value : search_value,search_type : search_type,suggest_questions : suggest_questions },
							success:function(data) {
								if (data.indexOf('no_suggest_questions') > -1) {
									live_search.closest(main_search_form).find(".search-results").addClass("results-empty").html("").hide();
								}else {
									live_search.closest(main_search_form).find(".search-results").removeClass("results-empty").html(data).slideDown(300);
									if (live_search.hasClass("live-search-icon")) {
										live_search.closest(main_search_form).find("i.icon-arrows-ccw").attr("class","icon-search");
									}else {
										search_loader.hide(10);
									}
								}
							}
						});
					},500);
				}
			});
			
			main_live_search.on('focus',function() {
				var live_search = jQuery(this);
				if (live_search.closest(main_search_form).find(".results-empty").length == 0) {
					live_search.closest(main_search_form).find(".search-results").show();
				}
			});
			
			jQuery(".search_type").change(function () {
				if (jQuery(this).closest(main_search_form).find(".results-empty").length == 0) {
					jQuery(this).closest(main_search_form).find(".search-results").addClass("results-empty").html("").hide();
				}
			});
			
			var outputContainer = main_live_search.closest(main_search_form).find(".search-results");
			var input 			= main_live_search.get(0);
			jQuery('body').bind('click',function(e) {
				if (!jQuery.contains(outputContainer.get(0),e.target) && e.target != input) {
					outputContainer.hide();
				}
			});
		});
	}
	
	/* Fake file */
	
	jQuery(document).on("change",".fileinputs input[type='file']",function () {
		var file_fake = jQuery(this);
		var file_value = file_fake.val();
		file_value = file_value.replace("C:\\fakepath\\","");
		file_fake.parent().find("button").text(file_value);
	});
	
	jQuery(document).on("click",".fakefile",function () {
		jQuery(this).parent().find("input[type='file']").click();
	});

	/* Remove image */

	if (jQuery(".wpqa-remove-image").length) {
		jQuery(".wpqa-remove-image").on("click",function () {
			var image_this = jQuery(this);
			var image_name = image_this.data("name");
			var confirm_message = (image_name == "added_file"?wpqa_custom.wpqa_remove_attachment:wpqa_custom.wpqa_remove_image);
			if (confirm(confirm_message)) {
				var image_type  = image_this.data("type");
				var meta_id     = image_this.data("id");
				var image_id    = image_this.data("image");
				var image_nonce = image_this.data("nonce");
				image_this.hide();
				image_this.parent().find(".loader_4").addClass("wpqa-remove-loader");
				jQuery.ajax({
					url: wpqa_custom.admin_url,
					type: "POST",
					data: { action : 'wpqa_remove_image', wpqa_remove_image : image_nonce, image_name : image_name, image_type : image_type, meta_id : meta_id, image_id : image_id },
					success:function(data) {
						if (data == "") {
							if (image_name == "added_file") {
								image_this.parent().hide();
								if (image_this.closest(".wpqa-delete-attachment").find("li").length == 1) {
									image_this.closest(".wpqa-delete-attachment").hide().remove();
								}
							}else {
								image_this.parent().find(".wpqa-delete-image-span").hide();
							}
						}else {
							image_this.parent().find(".wpqa-delete-image-span").html(data);
						}
						image_this.parent().find(".loader_4").hide();
						image_this.remove();
					}
				});
			}
			return false;
		});
	}

	/* Delete attachment */

	if (jQuery(".delete-this-attachment").length) {
		jQuery(".delete-this-attachment").click(function () {
			var answer = confirm(wpqa_custom.wpqa_remove_attachment);
			if (answer) {
		    	var delete_attachment = jQuery(this);
		    	var attachment_id = delete_attachment.attr("href");
		    	var post_id = delete_attachment.data("id");
		    	var single_attachment = "No";
		    	delete_attachment.hide();
		    	delete_attachment.parent().find(".loader_4").addClass("wpqa-remove-loader");
		    	if (delete_attachment.hasClass("single-attachment")) {
		    		single_attachment = "Yes";
		    	}
		    	jQuery.post(wpqa_custom.admin_url,"action=wpqa_confirm_delete_attachment&attachment_id="+attachment_id+"&post_id="+post_id+"&single_attachment="+single_attachment,function (result) {
		    		delete_attachment.parent().find(".loader_4").hide();
		    		delete_attachment.parent().fadeOut(function() {
		    			jQuery(this).remove();
		    		});
		    	});
			}
			return false;
		});
	}
	
	/* Ask Question */

	jQuery(document).on("click",".button-hide-click",function () {
		var button_click = jQuery(this);
		button_click.hide().parent().find(".load_span").show().css({"display":"block"});
	});
	
	if (jQuery(".cat-ajax").length) {
		jQuery('.category-wrap').on('change','.cat-ajax',function() {
			var currentLevel = parseInt(jQuery(this).parent().parent().data('level'));
			wpqa_child_cats(jQuery(this),'wpqa-level-',currentLevel+1);
		});
	}
	
	if (jQuery(".the-category-ajax").length) {
		jQuery(".p-category ul").on("click","a",function () {
			var $this = jQuery(this);
			$this.closest(".p-category").find(".the-category-ajax").val($this.text());
			$this.closest(".p-category").find(".search-results").addClass("results-empty").html("").hide();
			return false;
		});

		jQuery(".the-category-ajax").each(function () {
			var main_category = jQuery(this);
			var typingTimer;
			var doneTypingInterval = 500;
			main_category.on("keyup",function() {
				var category  = jQuery(this);
				var search_value = category.val();
				if (search_value == "") {
					category.closest(".p-category").find(".search-results").addClass("results-empty").html("").hide();
				}else {
					var search_loader = category.closest(".p-category").find(".search_loader");
					clearTimeout(typingTimer);
					typingTimer = setTimeout(function () {
						search_loader.show(10);
						jQuery.ajax({
							url: wpqa_custom.admin_url,
							type: "POST",
							data: { action : 'wpqa_category',search_value : search_value },
							success:function(data) {
								category.closest(".p-category").find(".search-results").removeClass("results-empty").html(data).slideDown(300);
								search_loader.hide(10);
							}
						});
					},500);
				}
			});
			
			main_category.on('focus',function() {
				var category = jQuery(this);
				if (category.closest(".p-category").find(".results-empty").length == 0) {
					category.closest(".p-category").find(".search-results").show();
				}
			});
			
			var outputContainer = main_category.closest(".p-category").find(".search-results");
			var input 			= main_category.get(0);
			jQuery('body').bind('click',function(e) {
				if (!jQuery.contains(outputContainer.get(0),e.target) && e.target != input) {
					outputContainer.hide();
				}
			});
		});
	}
	
	if (jQuery(".question_tags,.post_tag").length) {
		jQuery('.question_tags,.post_tag').tag();
	}
	
	if (jQuery(".poll_options").length) {
		jQuery(".poll_options").each(function () {
			var poll_this = jQuery(this);
			var question_poll = poll_this.parent().find(".question_poll").is(":checked");
			if (question_poll == 1) {
				poll_this.slideDown(200);
			}else {
				poll_this.slideUp(200);
			}
			
			poll_this.parent().find(".question_poll").on("click",function () {
				var question_poll_c = poll_this.parent().find(".question_poll").is(":checked");
				if (question_poll_c == 1) {
					poll_this.slideDown(200);
				}else {
					poll_this.slideUp(200);
				}
			});
		});
	}
	
	if (jQuery(".question_polls_item,.question_upload_item,#categories_left_menu_items").length) {
		jQuery(".question_polls_item,.question_upload_item,#categories_left_menu_items").sortable({placeholder: "ui-state-highlight"});
	}
	
	if (jQuery(".add_poll_button_js,.question_image_poll").length) {
		jQuery(".add_poll_button_js,.question_image_poll").on("click",function() {
			var poll_this = jQuery(this);
			var poll_options = poll_this.closest(".poll_options");
			
			if (poll_this.hasClass("question_image_poll")) {
				var question_image_poll = poll_this;
				poll_options.find(".question_polls_item > li").remove();
			}else {
				var question_image_poll = poll_options.find(".question_image_poll");
			}

			var question_image_poll_c = question_image_poll.is(":checked");
			var add_poll = poll_options.find(".question_items > li").length;
			if (add_poll > 0) {
				var i_count = 0;
				while (i_count < add_poll) {
					if (poll_options.find(".question_items > #poll_li_"+add_poll).length) {
						add_poll++;
					}
					i_count++;
				}
			}else {
				add_poll++;
			}
			
			var wpqa_poll_image = (question_image_poll_c == 1 && wpqa_custom.poll_image == 'on'?'<div class="attach-li"><div class="fileinputs"><input type="file" class="file" name="ask['+add_poll+'][image]" id="ask['+add_poll+'][image]"><i class="icon-camera"></i><div class="fakefile"><button type="button">'+wpqa_custom.select_file+'</button><span>'+wpqa_custom.browse+'</span></div></div></div>':'');

			var wpqa_poll_title = (question_image_poll_c != 1 || wpqa_custom.poll_image != 'on' || (wpqa_custom.poll_image == 'on' && wpqa_custom.poll_image_title == 'on')?'<p><input class="ask" name="ask['+add_poll+'][title]" value="" type="text"><i class="icon-comment"></i></p>':'');

			var wpqa_poll_image_2 = (question_image_poll_c == 1 && wpqa_custom.poll_image == 'on'?'<div class="attach-li"><div class="fileinputs"><input type="file" class="file" name="ask[2][image]" id="ask[2][image]"><i class="icon-camera"></i><div class="fakefile"><button type="button">'+wpqa_custom.select_file+'</button><span>'+wpqa_custom.browse+'</span></div></div></div>':'');

			var wpqa_poll_title_2 = (question_image_poll_c != 1 || wpqa_custom.poll_image != 'on' || (wpqa_custom.poll_image == 'on' && wpqa_custom.poll_image_title == 'on')?'<p><input class="ask" name="ask[2][title]" value="" type="text"><i class="icon-comment"></i></p>':'');

			poll_options.find('.question_items').append('\
				<li id="poll_li_'+add_poll+'">'+wpqa_poll_image+'<div class="poll-li">'+wpqa_poll_title+'<input name="ask['+add_poll+'][id]" value="'+add_poll+'" type="hidden"><div class="del-item-li"><i class="icon-cancel"></i></div><div class="move-poll-li"><i class="icon-menu"></i></div></div></li>\
				'+(add_poll == 1?'<li id="poll_li_2">'+wpqa_poll_image_2+'<div class="poll-li">'+wpqa_poll_title_2+'<input name="ask[2][id]" value="2" type="hidden"><div class="del-item-li"><i class="icon-cancel"></i></div><div class="move-poll-li"><i class="icon-menu"></i></div></div></li>':"")+'\
				');
			
			jQuery('#poll_li_'+add_poll).hide().fadeIn();
			
			jQuery(".del-item-li").on("click",function() {
				jQuery(this).parent().parent().addClass('removered').fadeOut(function() {
					jQuery(this).remove();
				});
			});

			if (!poll_this.hasClass("question_image_poll")) {
				return false;
			}
		});
	}
	
	jQuery(document).on("click",".del-item-li",function () {
		jQuery(this).parent().parent().addClass('removered').fadeOut(function() {
			jQuery(this).remove();
		});
	});
	
	if (jQuery(".video_description_input,.video_description").length) {
		jQuery(".video_description").each(function () {
			var video_this = jQuery(this);
			var video_description = video_this.parent().find(".video_description_input").is(":checked");
			if (video_description == 1) {
				video_this.slideDown(200);
			}else {
				video_this.slideUp(200);
			}
			
			video_this.parent().find(".video_description_input").on("click",function () {
				var video_description_c = video_this.parent().find(".video_description_input").is(":checked");
				if (video_description_c == 1) {
					video_this.slideDown(200);
				}else {
					video_this.slideUp(200);
				}
			});
		});
	}
	
	if (jQuery(".video_answer_description_input,.video_answer_description").length) {
		var video_this = jQuery(".video_answer_description");
		var video_description = video_this.parent().find(".video_answer_description_input").is(":checked");
		if (video_description == 1) {
			video_this.slideDown(200);
		}else {
			video_this.slideUp(200);
		}
		
		video_this.parent().find(".video_answer_description_input").on("click",function () {
			var video_description_c = video_this.parent().find(".video_answer_description_input").is(":checked");
			if (video_description_c == 1) {
				video_this.slideDown(200);
			}else {
				video_this.slideUp(200);
			}
		});
	}
	
	if (jQuery(".add_upload_button_js").length) {
		jQuery(".add_upload_button_js").on("click",function() {
			var add_attach_1 = jQuery(this).parent().find(".question_items > li").length;
			var add_attach_2 = jQuery(this).parent().parent().parent().find(".wpqa-delete-attachment > li").length;
			var add_attach_3 = (add_attach_2 > 0?add_attach_2+1:0);
			var add_attach = add_attach_1+add_attach_3;
			if (add_attach > 0) {
				var i_count = 0;
				while (i_count < add_attach) {
					if (jQuery(this).parent().find(".question_items > #attach_li_"+add_attach).length) {
						add_attach++;
					}
					i_count++;
				}
			}else {
				add_attach++;
			}
			jQuery(this).parent().find('.question_items').append('<li id="attach_li_'+add_attach+'"><div class="attach-li"><div class="fileinputs"><input type="file" class="file" name="attachment_m['+add_attach+'][file_url]" id="attachment_m['+add_attach+'][file_url]"><i class="icon-camera"></i><div class="fakefile"><button type="button">'+wpqa_custom.select_file+'</button><span>'+wpqa_custom.browse+'</span></div><div class="del-item-li"><i class="icon-cancel"></i></div><div class="move-poll-li"><i class="icon-menu"></i></div></div></div></li>');
			jQuery(".fileinputs input[type='file']").change(function () {
				var file_fake = jQuery(this);
				file_fake.parent().find("button").text(file_fake.val());
			});
			jQuery(".fakefile").on("click",function () {
				jQuery(this).parent().find("input[type='file']").click();
			});
			jQuery('#attach_li_'+add_attach).hide().fadeIn();
			jQuery(".del-item-li").on("click",function() {
				jQuery(this).parent().parent().parent().fadeOut(function() {
					jQuery(this).remove();
				});
			});
			return false;
		});
	}
	
	if (jQuery(".the-details").length) {
		jQuery("#wp-question-details-wrap").appendTo(".the-details");
		jQuery("#wp-post-details-wrap").appendTo(".the-details");
	}
	
	/* Panel pop */
	
	if (jQuery(".panel-pop > i").length) {
		jQuery(document).on("click",".panel-pop > i",function () {
			var icon_close = jQuery(this);
			var wrappop = icon_close.parent();
			if (!wrappop.hasClass("pop-not-close")) {
				jQuery.when(wrappop.fadeOut(200)).done(function() {
					jQuery("#wpqa-message .the-title").val("");
					jQuery(".wrap-pop").remove();
				});
			}
		});
	}
	
	panel_pop("#signup-panel",".signup-panel,.button-sign-up,.mob-sign-up,.login-links-r a");
	panel_pop("#lost-password",".lost-password,.main_for_all .reset-password");
	panel_pop("#lost-password",".lost-passwords","no",".main_for_all");
	panel_pop("#login-panel",".login-panel,.button-sign-in,.mob-sign-in,.comment-reply-login");
	panel_pop("#wpqa-question",".wpqa-question");
	panel_pop("#wpqa-question-user",".ask-question-user");
	panel_pop("#wpqa-post",".wpqa-post");
	panel_pop("#wpqa-message",".wpqa-message,.message-reply a");
	panel_pop("#wpqa-report",".report_c,.report_q");
	
	function panel_pop(whatId,whatClass,whatFrom,bodyClass) {
		if (jQuery(whatId).length && !jQuery(whatClass).hasClass("wpqa-not-pop")) {
			jQuery((whatFrom == "no"?(bodyClass != ""?bodyClass+" ":"")+".wpqa_form,":"")+whatClass).on("click",(whatFrom == "no"?whatClass:""),function() {
				if (jQuery(whatClass).parent().hasClass("message-reply")) {
					var user_id    = jQuery(this).attr("data-user-id");
					var message_id = jQuery(this).attr("data-id");
					if (message_id !== undefined && message_id !== false) {
						jQuery.ajax({
							url: wpqa_custom.admin_url,
							type: "POST",
							data: { action : 'wpqa_message_reply',message_id : message_id },
							success:function(data) {
								jQuery("#wpqa-message .the-title").val(data);
							}
						});
					}
					if (user_id !== undefined && user_id !== false) {
						if (jQuery(".message_user_id").length) {
							jQuery(".message_user_id").attr("value",user_id);
						}else {
							jQuery("#wpqa-message .send-message").after('<input type="hidden" name="user_id" class="message_user_id" value="'+user_id+'">');
						}
					}
				}
				
				var data_width = jQuery(whatId).attr("data-width");
				jQuery(".panel-pop").css({"top":"-100%","display":"none"});
				if (!jQuery(".wrap-pop").hasClass("wrap-pop-not-close")) {
					jQuery(".wrap-pop").remove();
				}
				var is_RTL = jQuery('body').hasClass('rtl')?true:false;
				var cssMargin = (is_RTL == true?"margin-right":"margin-left");
				var cssValue = "-"+(data_width !== undefined && data_width !== false?data_width/2:"")+"px";
				jQuery(whatId).css("width",(data_width !== undefined && data_width !== false?data_width:"")+"px").css(cssMargin,cssValue).show().animate({"top":"7%"},200);
				jQuery("html,body").animate({scrollTop:0},200);
				if (!jQuery(".wrap-pop").hasClass("wrap-pop-not-close")) {
					jQuery(".put-wrap-pop").prepend("<div class='wrap-pop'></div>");
				}
				wrap_pop();
				return false;
			});
		}
	}
	
	function wrap_pop() {
		if (!jQuery(".wrap-pop").hasClass("wrap-pop-not-close")) {
			jQuery(".wrap-pop").on("click",function () {
				jQuery.when(jQuery(".panel-pop").fadeOut(200)).done(function() {
					jQuery(this).css({"top":"-100%","display":"none"});
					jQuery("#wpqa-message .the-title").val("");
					jQuery(".wrap-pop").remove();
				});
			});
		}
	}

	jQuery('body').bind('click', function(e) {
		var click_area = jQuery(".user-login-click,.user-notifications,.mobile-aside");
		if (!jQuery(e.target).closest(click_area).length) {
			/* User login */
			jQuery(".user-login-click").removeClass("user-click-open").find(" > ul").slideUp(200);
			
			/* User notifications */
			jQuery(".user-login-area .user-notifications > div").slideUp(200);
			jQuery(".user-notifications-seen").removeClass("user-notifications-seen");
			
			/* User messages */
			jQuery(".user-messages > div").slideUp(200);
			
			/* Mobile aside */
			jQuery('.mobile-menu-wrap').removeClass('mobile-aside-open');
		}
	});

	/* Categories */
	
	if (jQuery(".home_categories").length) {
		jQuery(".home_categories").on("change",function () {
			var url = jQuery(this).val();
			if (url) {
				window.location.href = url;
			}
			return false;
		});
	}
	
	/* Follow question */
	
	jQuery(document).on("click",".question-followers a",function() {
		var question_follow = jQuery(this);
		var question_class = question_follow.closest(".article-question.article-post.question");
		var post_id = question_class.attr('id').replace("post-","");
		question_follow.hide();
		question_follow.parent().find(".loader_2").show();
		jQuery.ajax({
			url: wpqa_custom.admin_url,
			type: "POST",
			data: { action : 'wpqa_question_'+(question_follow.hasClass("unfollow-question")?"unfollow":"follow"), post_id : post_id },
			success:function(data) {
				if (question_follow.hasClass("unfollow-question")) {
					question_follow.removeClass("unfollow-question").parent().removeClass("li-follow-question").find("i").addClass("icon-plus").removeClass("icon-minus");
				}else {
					question_follow.addClass("unfollow-question").parent().addClass("li-follow-question").find("i").removeClass("icon-plus").addClass("icon-minus");
				}
				question_follow.attr("original-title",(question_follow.hasClass("unfollow-question")?wpqa_custom.follow_question_attr:wpqa_custom.unfollow_question_attr)).show().parent().find(".loader_2").hide().parent().find("span").text(data);
			}
		});
		return false;
	});
	
	if (jQuery(".wpqa-open-click").length) {
		jQuery(".wpqa-open-click").click(function () {
			var whatsclass = jQuery(this).attr("data-class");
			if (whatsclass !== undefined && whatsclass !== false) {
				jQuery("."+whatsclass).addClass("wpqa-open-new");
			}else {
				jQuery(this).parent().find(".wpqa-open-div").addClass("wpqa-open-new");
			}
			jQuery(".wpqa-open-div:not(.wpqa-open-new)").slideUp(400);
			if (whatsclass !== undefined && whatsclass !== false) {
				jQuery("."+whatsclass).slideToggle(400);
			}else {
				jQuery(this).parent().find(".wpqa-open-div").slideToggle(400);
			}
			jQuery(".wpqa-open-new").removeClass("wpqa-open-new");
			return false;
		});
	}
	
	function vote_message(vote) {
		vote.find(".vote_result").show();
		vote.find(".li_loader").hide();
	}
	
	jQuery(document).on("click",".wpqa_vote",function() {
		var this_vote = jQuery(this);
		var type = this_vote.attr("data-type");
		var vote_type = this_vote.attr("data-vote-type");
		this_vote.parent().parent().addClass("active-vote");
		if (type == "question") {
			var vote_parent = this_vote.closest(".single-inner-content");
		}else {
			var vote_parent = this_vote.closest(".comment-text");
		}
		vote_parent.find(".vote_result").hide();
		vote_parent.find(".li_loader").show();
		vote_parent.find(".wpqa_error").slideUp(200);
		if (this_vote.hasClass("vote_not_user")) {
			vote_parent.find(".wpqa_error").text(wpqa_custom.no_vote_user).slideDown(200);
			vote_message(vote_parent);
			this_vote.parent().parent().removeClass("active-vote");
		}else if (this_vote.hasClass("vote_not_allow")) {
			if (type == "question") {
				var text_vote = wpqa_custom.no_vote_question;
			}else if (type == "comments") {
				var text_vote = wpqa_custom.no_vote_comment;
			}else {
				var text_vote = wpqa_custom.no_vote_answer;
			}
			vote_parent.find(".wpqa_error").text(text_vote).slideDown(200);
			vote_message(vote_parent);
			this_vote.parent().parent().removeClass("active-vote");
		}else if (this_vote.hasClass("vote_allow")) {
			var id = this_vote.attr('id').replace(type+'_vote_'+vote_type+'-',"");
			if (type == "question") {
				var action_vote = 'wpqa_question_vote_'+vote_type;
				var vote_more = wpqa_custom.no_vote_more;
			}else if (type == "comments") {
				var action_vote = 'wpqa_comments_vote_'+vote_type;
				var vote_more = wpqa_custom.no_vote_more_comment;
			}else {
				var action_vote = 'wpqa_comment_vote_'+vote_type;
				var vote_more = wpqa_custom.no_vote_more_answer;
			}
			jQuery.ajax({
				url: wpqa_custom.admin_url,
				type: "POST",
				data: { action : action_vote, id : id },
				success:function(data) {
					if (Math.floor(data) == data && jQuery.isNumeric(data)) {
						if (data > 0) {
							vote_parent.find(".vote_result").removeClass("vote_red");
						}else if (data == 0) {
							vote_parent.find(".vote_result").removeClass("vote_red");
						}else if (data < 0) {
							vote_parent.find(".vote_result").addClass("vote_red");
						}
					}else {
						data = data.replace("no_vote_more","");
						vote_parent.find(".wpqa_error").text(vote_more).slideDown(200);
					}
					vote_parent.find(".vote_result").html(data);
					vote_message(vote_parent);
					this_vote.parent().parent().removeClass("active-vote");
				}
			});
		}
		return false;
	});
	
	wpqa_report();
	
	function wpqa_report() {
		if (jQuery(".report_activated").length) {
			var report_type = "";
			jQuery(".report_activated > a").on("click",function() {
				report_type = jQuery(this).attr("class");
				if (jQuery(".report_id").length) {
					jQuery(".report_id").remove();
				}
				
				if (report_type == "report_c") {
					var report_v = jQuery(this);
					var report_id = report_v.attr("href");
					jQuery(".submit-report").append('<input type="hidden" class="report_id" name="report_id" value="'+report_id+'">');
				}
				return false;
			});
				
			jQuery(".submit-report").submit(function () {
				var report_v = jQuery(this);
				var explain = report_v.find("textarea");
				if (explain.val() == '') {
					explain.css("border-color","#e1e2e3");
					if (explain.val() == '') {
						explain.css("border-color","#F00");
					}
					jQuery(".wpqa_error",report_v).html('<span class="required-error">'+wpqa_custom.wpqa_error_text+'</span>').animate({opacity: 'show' , height: 'show'}, 400).delay(5000).animate({opacity: 'hide' , height: 'hide'}, 400);
					jQuery('.load_span',report_v).hide();
					jQuery('input[type="submit"]',report_v).show();
				}else {
					var fromSerialize = report_v.serialize();
					var fromWithAction = fromSerialize+"&action=wpqa_"+report_type;
					jQuery.post(wpqa_custom.admin_url,fromWithAction,function(data) {
						if (data == "deleted_report") {
							if (report_type == "report_c") {
								location.reload();
							}else {
								window.location.href = wpqa_custom.home_url;
							}
						}else {
							explain.val("").css("border-color","#e1e2e3");
							location.reload();
						}
						jQuery('.load_span',report_v).hide();
						jQuery('input[type="submit"]',report_v).show();
					});
				}
				return false;
			});
		}
	}
	
	jQuery(document).on("click",".poll_results",function() {
		var poll_area = jQuery(this).closest(".poll-area");
		poll_area.find(".poll_2").fadeOut(200);
		poll_area.find(".poll_1").delay(500).slideDown(200);

		poll_area.find(".progressbar-percent").each(function(){
			var $this = jQuery(this);
			var percent = $this.attr("attr-percent");
			$this.bind("inview", function(event, isInView, visiblePartX, visiblePartY) {
				if (isInView) {
					$this.animate({ "width" : percent + "%"}, 700);
				}
			});
		});
		return false;
	});
	
	jQuery(document).on("click",".poll_polls",function() {
		var poll_area = jQuery(this).closest(".poll-area");
		poll_area.find(".poll_1").fadeOut(200);
		poll_area.find(".poll_2").delay(500).slideDown(200);
		return false;
	});

	jQuery(document).on("click",".wpqa_poll_image img",function() {
		var wpqa_poll_image = jQuery(this);
		wpqa_poll_image.parent().parent().parent().find('input[type="radio"]').removeAttr('checked');
		wpqa_poll_image.parent().parent().parent().find('.wpqa_poll_image_select').removeClass('wpqa_poll_image_select');
		wpqa_poll_image.addClass('wpqa_poll_image_select').prev('input[type="radio"]').click().attr('checked','checked');
	});
	
	jQuery(document).on("click",".poll-submit",function() {
		var question_poll = jQuery(this);
		var poll_val = question_poll.parent().find('.required-item:checked');
		var wpqa_form = question_poll.closest(".wpqa_form");
		question_poll.parent().find("input,label").hide().parent().find(".load_span").show();
		if (poll_val.length == 0) {
			question_poll.parent().find("input,label").show().parent().find(".load_span").hide();
			question_poll.parent().parent().parent().parent().parent().parent().find(".wpqa_error").text(wpqa_custom.wpqa_error_text).slideDown(200).delay(5000).slideUp(200);
		}else {
			var poll_id        = poll_val.val().replace("poll_","");
			var question_class = question_poll.closest(".article-question.article-post.question");
			var post_id        = question_class.attr("id").replace("post-","");
			
			jQuery.ajax({
				url: wpqa_custom.admin_url,
				type: "POST",
				data: { action : 'wpqa_question_poll', poll_id : poll_id, post_id : post_id },
				success:function(data) {
					if (data == "no_poll" || data == "must_login") {
						question_poll.parent().parent().parent().parent().parent().parent().find(".wpqa_error").text((data == "must_login"?wpqa_custom.must_login:wpqa_custom.no_poll_more)).slideDown(200).delay(5000).slideUp(200);
						if (wpqa_form.find(".wpqa_poll_image").length == 0) {
							question_poll.parent().find("input,label").show();
						}
						wpqa_form.show().find(".load_span").hide();
						wpqa_form.find(".ed_button").show();
					}else {
						var poll_2 = question_poll.closest(".poll_2");
						var poll_main = poll_2.parent();
						poll_main.html(data);
						jQuery(".progressbar-percent").each(function(){
							var $this = jQuery(this);
							var percent = $this.attr("attr-percent");
							$this.bind("inview", function(event, isInView, visiblePartX, visiblePartY) {
								if (isInView) {
									$this.animate({ "width" : percent + "%"}, 700);
								}
							});
						});
					}
				}
			});
		}
		return false;
	});

	if (jQuery(".ask_anonymously").length) {
		jQuery(".ask_anonymously").each(function () {
			var ask_anonymously = jQuery(this);
			var wpqa_setting = ask_anonymously.is(":checked");
			if (wpqa_setting == 1) {
				ask_anonymously.closest(".ask_anonymously_p").find(".ask_named").hide(10);
				ask_anonymously.closest(".ask_anonymously_p").find(".ask_none").show(10);
			}else {
				ask_anonymously.closest(".ask_anonymously_p").find(".ask_named").show(10);
				ask_anonymously.closest(".ask_anonymously_p").find(".ask_none").hide(10);
			}
			
			ask_anonymously.on("click",function () {
				var ask_anonymously_c = ask_anonymously.is(":checked");
				if (ask_anonymously_c == 1) {
					ask_anonymously.closest(".ask_anonymously_p").find(".ask_named").hide(10);
					ask_anonymously.closest(".ask_anonymously_p").find(".ask_none").show(10);
				}else {
					ask_anonymously.closest(".ask_anonymously_p").find(".ask_named").show(10);
					ask_anonymously.closest(".ask_anonymously_p").find(".ask_none").hide(10);
				}
			});
		});
	}

	if (jQuery(".load-question-jquery").length) {
		jQuery(document).on("click",".load-question-jquery .post-read-more:not(.comment-read-more)",function () {
			jQuery(this).closest(".article-question").find(".excerpt-question").hide().parent().find(".content-question-jquery").show();
			if (jQuery(".question-masonry").length) {
				jQuery('.question-articles').isotope({
					filter: "*",
					animationOptions: {
						duration: 750,
						itemSelector: '.question-masonry',
						easing: "linear",
						queue: false,
					}
				});
			}
			return false;
		});

		jQuery(document).on("click",".load-question-jquery .question-read-less",function () {
			jQuery(this).closest(".article-question").find(".content-question-jquery").hide().parent().find(".excerpt-question").show();
			if (jQuery(".question-masonry").length) {
				jQuery('.question-articles').isotope({
					filter: "*",
					animationOptions: {
						duration: 750,
						itemSelector: '.question-masonry',
						easing: "linear",
						queue: false,
					}
				});
			}
			return false;
		});
	}

	if (jQuery(".read_more_answer").length) {
		jQuery(document).on("click",".read_more_answer",function () {
			jQuery(this).parent().parent().find(".less_answer_text").hide().parent().find(".full_answer_text").show();
			return false;
		});

		jQuery(document).on("click",".read_less_answer",function () {
			jQuery(this).parent().parent().find(".full_answer_text").hide().parent().find(".less_answer_text").show();
			return false;
		});
	}

	if (jQuery(".answer-question-jquery").length) {
		jQuery(document).on("click",".answer-question-jquery .meta-answer,.answer-question-jquery .best-answer-meta a",function () {
			var answer_jquery = jQuery(this);
			var main_question = answer_jquery.closest(".article-question");
			var post_id = main_question.attr("id").replace('post-',"");
			jQuery(".panel-pop").css({"top":"-100%","display":"none"});
			jQuery(".wrap-pop").remove();
			var is_RTL = jQuery('body').hasClass('rtl')?true:false;
			var cssMargin = (is_RTL == true?"margin-right":"margin-left");
			if (jQuery("#article-question-"+post_id).length > 0) {
				var data_width = jQuery("#article-question-"+post_id).attr("data-width");
				var cssValue = "-"+(data_width !== undefined && data_width !== false?data_width/2:"")+"px";
				jQuery("#article-question-"+post_id).css("width",(data_width !== undefined && data_width !== false?data_width:"")+"px").css(cssMargin,cssValue).show().animate({"top":"7%"},200);
				jQuery("html,body").animate({scrollTop: jQuery("#article-question-"+post_id).offset().top-35},"slow");
				jQuery(".put-wrap-pop").prepend("<div class='wrap-pop'></div>");
				wrap_pop();
			}else {
				jQuery("#post-"+post_id+" > .question-fixed-area").show();
				jQuery.ajax({
					url: wpqa_custom.admin_url,
					type: "POST",
					data: { action : 'wpqa_question_answer_popup', post_id : post_id },
					success:function(data) {
						jQuery(".put-wrap-pop").after(data);
						var data_width = jQuery("#article-question-"+post_id).attr("data-width");
						var cssValue = "-"+(data_width !== undefined && data_width !== false?data_width/2:"")+"px";
						jQuery("#article-question-"+post_id).css("width",(data_width !== undefined && data_width !== false?data_width:"")+"px").css(cssMargin,cssValue).show().animate({"top":"7%"},200).find(" > .load_span").hide();
						jQuery("html,body").animate({scrollTop: jQuery("#article-question-"+post_id).offset().top-35},"slow");
						jQuery(".put-wrap-pop").prepend("<div class='wrap-pop'></div>");
						wrap_pop();
						jQuery("#post-"+post_id+" > .question-fixed-area").hide();
					}
				});
			}
			return false;
		});

		jQuery(document).on("click",".question-panel-pop .add-answer-ajax",function () {
			var complete_answer = true;
			var add_answer_jquery = jQuery(this);
			var main_question = add_answer_jquery.closest(".question-panel-pop");
			var post_id = main_question.attr("id").replace('article-question-',"");
			var main_popup = add_answer_jquery.closest(".answers-form");
			var main_form = add_answer_jquery.closest(".comment-form.answers-form");
			var form_data = main_form.serialize();
			var comment_text = main_form.find("textarea").val();
			main_form.find(".wpqa_error").animate({opacity: 'hide' , height: 'hide'}, 400).remove();
			if (comment_text == '' || comment_text == '<p><br data-mce-bogus="1"></p>' || comment_text == '<p><br></p>' || comment_text == '<p></p>') {
				main_form.find(".wpqa_error").animate({opacity: 'hide' , height: 'hide'}, 400).remove();
				main_form.prepend('<div class="wpqa_error">'+wpqa_custom.wpqa_error_comment+'</div>');
				main_form.find(".wpqa_error").animate({opacity: 'show' , height: 'show'}, 400);
				main_form.find(".load_span").hide();
				add_answer_jquery.show();
				complete_answer = false;
			}

			if (wpqa_custom.comment_limit > 0 || wpqa_custom.comment_min_limit > 0) {
				var message = "";
				jQuery.ajax({
					url: wpqa_custom.admin_url,
					type: "POST",
					data: { action : 'wpqa_comment_limit', comment_text : comment_text, comment_limit : wpqa_custom.comment_limit, comment_min_limit : wpqa_custom.comment_min_limit },
					async : false,
					success: function(data){
						message = data;
					}
				});
				var wpqa_error_limit = wpqa_custom.wpqa_error_limit;
				if (message == "wpqa_error" || message == "wpqa_min_error") {
					main_form.find(".wpqa_error").animate({opacity: 'hide' , height: 'hide'}, 400).remove();
					main_form.prepend('<div class="wpqa_error">'+(message == "wpqa_error"?wpqa_custom.wpqa_error_limit+': '+wpqa_custom.comment_limit:wpqa_custom.wpqa_error_min_limit+': '+wpqa_custom.comment_min_limit)+'</div>');
					main_form.find(".wpqa_error").animate({opacity: 'show' , height: 'show'}, 400);
					main_form.find(".load_span").hide();
					add_answer_jquery.show();
					complete_answer = false;
				}
			}

			if (complete_answer == true) {
				jQuery.ajax({
					url: wpqa_custom.admin_url,
					type: "POST",
					data: form_data,
					success:function(data) {
						var closest_popup = add_answer_jquery.closest(".panel-pop-content");
						if (closest_popup.find(".commentlist").length == 0) {
							closest_popup.find(" > .alert-message").remove();
							closest_popup.prepend("<div class='page-content commentslist'><ol class='commentlist clearfix'></ol></div>");
						}
						closest_popup.find(".commentlist").prepend(data);
						main_popup.find("textarea").val("");
						main_popup.find(".load_span").hide().css({"display":"none"});
						add_answer_jquery.show();
						jQuery("html,body").animate({scrollTop: closest_popup.find(".commentlist > li:first-child").offset().top-35},"slow");
						jQuery(document).on("click",".delete-comment",function () {
							var var_delete = (jQuery(".delete-answer").length?wpqa_custom.sure_delete_answer:wpqa_custom.sure_delete_comment);
							if (confirm(var_delete)) {
								return true;
							}else {
								return false;
							}
						});
					}
				});
			}
			return false;
		});
	}
	
	/* Progress Bar */
	
	jQuery(".progressbar-percent").each(function(){
		var $this = jQuery(this);
		var percent = $this.attr("attr-percent");
		$this.bind("inview", function(event, isInView, visiblePartX, visiblePartY) {
			if (isInView) {
				$this.animate({ "width" : percent + "%"}, 700);
			}
		});
	});
	
	/* Categories Accordion */
	
	if (jQuery(".categories-toggle-accordion").length) {
		jQuery(".categories-toggle-accordion .accordion-title").each(function () {
			jQuery(this).find(" > a > i").click(function () {
				var categories = jQuery(this);
				categories.toggleClass("wpqa-minus");
				categories.parent().parent().next().slideToggle(300);
				return false;
			});
		});
	}
	
	/* Follow users */
	
	if (jQuery(".following_not,.following_you").length) {
		wpqa_follow("following_not","following_you");
		wpqa_follow("following_you","following_not");
		
		function wpqa_follow(follow,next_follow) {
			jQuery(document).on("click","."+follow,function () {
				var following_var = jQuery(this);
				var following_var_id = following_var.attr("data-rel");
				var user_follow_done = (following_var.parent().hasClass("user_follow_4")?"user_follow_done":"user_follow_yes");
				following_var.hide();
				following_var.parent().addClass("user_follow_active");
				jQuery.ajax({
					url: wpqa_custom.admin_url,
					type: "POST",
					data: {action:'wpqa_'+follow,following_var_id:following_var_id},
					success:function(result) {
						following_var.addClass(next_follow).removeClass(follow).attr("title",(follow == "following_not"?wpqa_custom.follow:wpqa_custom.unfollow)).show().parent().removeClass("user_follow_active");
						if (following_var.parent().hasClass("user_follow_2") || following_var.parent().hasClass("user_follow_3") || following_var.parent().hasClass("user_follow_4")) {
							if (following_var.find(".follow-count").length) {
								following_var.find(".follow-count").text(result);
							}
							if (following_var.closest(".wpqa-profile-cover").find(".follow-cover-count").length) {
								following_var.closest(".wpqa-profile-cover").find(".follow-cover-count").text(result);
							}
							if (follow == "following_not") {
								following_var.parent().removeClass(user_follow_done).find(".follow-value").text((follow == "following_not"?wpqa_custom.follow:wpqa_custom.unfollow));
							}else {
								following_var.parent().addClass(user_follow_done).find(".follow-value").text((follow == "following_not"?wpqa_custom.follow:wpqa_custom.unfollow));
							}
						}else {
							if (follow == "following_not") {
								following_var.parent().removeClass(user_follow_done).find("i").removeClass("icon-minus").addClass("icon-plus");
							}else {
								following_var.parent().addClass(user_follow_done).find("i").removeClass("icon-plus").addClass("icon-minus");
							}
						}
						if (jQuery(".finish-follow > a").length) {
							var post_id = jQuery(".finish-follow > a").data("post");
							jQuery.ajax({
								url: wpqa_custom.admin_url,
								type: "POST",
								data: {action:'wpqa_finish_follow',post_id:post_id},
								success:function(data) {
									jQuery(".finish-follow > a").text((data == 0?wpqa_custom.click_not_finish:wpqa_custom.click_continue));
									if (data == 0) {
										jQuery(".finish-follow").addClass("not-finish-follow");
									}else {
										jQuery(".finish-follow").removeClass("not-finish-follow");
									}
								}
							});
						}
					}
				});
				return false;
			});
		}
	}
	
	/* Follow cats */
	
	if (jQuery(".unfollow_cat,.follow_cat").length) {
		wpqa_follow_cat("unfollow_cat","follow_cat");
		wpqa_follow_cat("follow_cat","unfollow_cat");
		
		function wpqa_follow_cat(follow,next_follow) {
			jQuery(document).on("click","."+follow,function () {
				var following_var = jQuery(this);
				var tax_id = following_var.data("id");
				var tax_type = following_var.data("type");
				var cat_follow_done = "cat_follow_done";
				var closest_class = following_var.data("closest");
				var count_class = following_var.data("count");
				following_var.hide();
				following_var.parent().addClass("user_follow_active");
				jQuery.ajax({
					url: wpqa_custom.admin_url,
					type: "POST",
					data: {action:'wpqa_'+follow,tax_id:tax_id,tax_type:tax_type},
					success:function(result) {
						following_var.addClass(next_follow).removeClass(follow).attr("title",(follow == "unfollow_cat"?wpqa_custom.follow:wpqa_custom.unfollow)).show().parent().removeClass("user_follow_active");
						if (following_var.find(".follow-count").length || following_var.closest("."+closest_class).find("."+count_class).length) {
							if (following_var.find(".follow-count").length) {
								following_var.find(".follow-count").text(result);
							}
							if (following_var.closest("."+closest_class).find("."+count_class).length) {
								following_var.closest("."+closest_class).find("."+count_class).text(result);
							}
						}
						if (follow == "unfollow_cat") {
							following_var.parent().removeClass(cat_follow_done).find(".follow-cat-value").text((follow == "unfollow_cat"?wpqa_custom.follow:wpqa_custom.unfollow));
							following_var.parent().removeClass(cat_follow_done).find(".follow-cat-icon i").removeClass("icon-minus").addClass("icon-plus");
						}else {
							following_var.parent().addClass(cat_follow_done).find(".follow-cat-value").text((follow == "unfollow_cat"?wpqa_custom.follow:wpqa_custom.unfollow));
							following_var.parent().addClass(cat_follow_done).find(".follow-cat-icon i").removeClass("icon-plus").addClass("icon-minus");
						}
						if (jQuery(".finish-follow > a").length) {
							var post_id = jQuery(".finish-follow > a").data("post");
							jQuery.ajax({
								url: wpqa_custom.admin_url,
								type: "POST",
								data: {action:'wpqa_finish_follow',post_id:post_id},
								success:function(data) {
									jQuery(".finish-follow > a").text((data == 0?wpqa_custom.click_not_finish:wpqa_custom.click_continue));
									if (data == 0) {
										jQuery(".finish-follow").addClass("not-finish-follow");
									}else {
										jQuery(".finish-follow").removeClass("not-finish-follow");
									}
								}
							});
						}
					}
				});
				return false;
			});
		}
	}

	/* Ban and unban users */

	if (jQuery(".ban-user,.unban-user").length) {
		jQuery(document).on("click",".ban-user,.unban-user",function () {
			var ban_user = jQuery(this);
			var ban_nonce = ban_user.data("nonce");
			var user_id = ban_user.data("id");
			var ban_type = (ban_user.hasClass("ban-user")?"ban":"unban");
			ban_user.hide();
			ban_user.parent().find(".small_loader").show().css({"display":"block"});
			jQuery.ajax({
				url: wpqa_custom.admin_url,
				type: "POST",
				data: { action : 'wpqa_ban_user', ban_type : ban_type, ban_nonce : ban_nonce, user_id : user_id },
				success:function(data) {
					if (ban_type == "ban") {
						ban_user.find("i").removeClass("icon-cancel-circled").addClass("icon-back");
						ban_user.removeClass("ban-user").addClass("unban-user").find("span").text(wpqa_custom.unban_user);
					}else {
						ban_user.find("i").removeClass("icon-back").addClass("icon-cancel-circled");
						ban_user.removeClass("unban-user").addClass("ban-user").find("span").text(wpqa_custom.ban_user);
					}
					ban_user.show();
					ban_user.parent().find(".small_loader").hide();
				}
			});
			return false;
		});
	}
	
	/* Notifications */
	
	if (jQuery(".notifications-click").length) {
		jQuery(".notifications-click").on("click",function () {
			if (!jQuery(this).hasClass("messages-click")) {
				jQuery('.mobile-menu-wrap').removeClass('mobile-aside-open');
				jQuery(".user-messages").removeClass("user-notifications-seen").find(" > div").slideUp(200);
				jQuery(".user-login-click").removeClass("user-click-open").find(" > ul").slideUp(200);
				jQuery(this).parent().toggleClass("user-notifications-seen").find(" > div").slideToggle(200).parent().find(" > .notifications-number").remove();
				jQuery.post(wpqa_custom.admin_url,{action:"wpqa_update_notifications"});
			}
		});
	}
	
	/* Messages */
	
	if (jQuery(".messages-click").length) {
		jQuery(".messages-click").on("click",function () {
			jQuery('.mobile-menu-wrap').removeClass('mobile-aside-open');
			jQuery(".notifications-area").removeClass("user-notifications-seen").find(" > div").slideUp(200);
			jQuery(".user-login-click").removeClass("user-click-open").find(" > ul").slideUp(200);
			jQuery(this).parent().toggleClass("user-notifications-seen").find(" > div").slideToggle(200).parent().find(" > .notifications-number").remove();
		});
	}
	
	/* Delete comment */
	
	if (jQuery(".delete-comment").length) {
		jQuery(document).on("click",".delete-comment",function () {
			var var_delete = (jQuery(".delete-answer").length?wpqa_custom.sure_delete_answer:wpqa_custom.sure_delete_comment);
			if (confirm(var_delete)) {
				return true;
			}else {
				return false;
			}
		});
	}
	
	/* Ask Question */
	
	if (jQuery(".add_media").length) {
		jQuery(".add_media").on("click",function (event) {
			event.preventDefault();
			wp.media.model.settings.post.id = 0;
		});
	}

	jQuery(window).on("load",function() {

		/* Editor */

		if (jQuery('.wp-editor-wrap').length) {
			jQuery('.wp-editor-wrap').each(function() {
				var editor_iframe = jQuery(this).find('iframe');
				if (editor_iframe.height() < 150) {
					editor_iframe.css({'height':'150px'});
				}
			});
		}
	});
	
	/* Close */
	
	jQuery(document).keyup(function(event) {
		if (event.which == '27') {
			/* Panel pop */
			
			if (!jQuery(".wrap-pop").hasClass("wrap-pop-not-close")) {
				jQuery.when(jQuery(".panel-pop").fadeOut(200)).done(function() {
					jQuery(this).css({"top":"-100%","display":"none"});
					jQuery(".wrap-pop").remove();
				});
			}
			
			/* Mobile menu */
			
			jQuery('.mobile-menu-wrap').removeClass('mobile-aside-open');
			
			/* User login */
			
			jQuery(".user-login-click").removeClass("user-click-open").find(" > ul").slideUp(200);
			
			/* User notifications */
			
			jQuery(".user-login-area .user-notifications > div").slideUp(200);
			jQuery(".user-notifications-seen").removeClass("user-notifications-seen");
			
			/* User messages */
			
			jQuery(".user-messages > div").slideUp(200);
		}
	});
	
})(jQuery);

/* Captcha */

function wpqa_get_captcha(captcha_file,captcha_id) {
	jQuery("#"+captcha_id).attr("src",captcha_file+'&'+Math.random()).parent().find(".wpqa_captcha").val("");
}

/* Child categories */

function wpqa_child_cats(dropdown,result_div,level) {
	var cat         = dropdown.val();
	var results_div = result_div + level;
	var field_attr  = dropdown.attr('data-taxonomy');
	jQuery.ajax({
		type: 'post',
		url: wpqa_custom.admin_url,
		data: {
			action: 'wpqa_child_cats',
			catID: cat,
			field_attr: field_attr
		},
		beforeSend: function() {
			dropdown.parent().parent().parent().next('.loader_2').addClass("category_loader_show");
			dropdown.parent().parent().parent().addClass("no-load");
		},
		complete: function() {
			dropdown.parent().parent().parent().next('.loader_2').removeClass("category_loader_show");
			dropdown.parent().parent().parent().removeClass("no-load");
		},
		success: function(html) {
			dropdown.parent().parent().nextAll().each(function() {
				jQuery(this).remove();
			});
			
			if (html != "") {
				dropdown.addClass('hasChild').parent().parent().parent().append('<span id="'+result_div+level+'" data-level="'+level+'"></span>');
				dropdown.parent().parent().parent().find('#'+results_div).html(html).slideDown('fast');
			}
		}
	});
}