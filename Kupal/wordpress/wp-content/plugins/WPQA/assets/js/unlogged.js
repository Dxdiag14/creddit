(function($) { "use strict";
	
	/* Login & Password & Signup */
	
	wpqa_forms(".login-form","login");
	wpqa_forms(".wpqa-lost-password","password");
	wpqa_forms(".signup_form","signup");
	
	function wpqa_forms(whatClass,whatAction) {
		if (jQuery(whatClass).length && !jQuery(whatClass).hasClass("wpqa-no-ajax")) {
			jQuery(whatClass).submit(function() {
				var thisform = jQuery(this);
				jQuery('input[type="submit"]',thisform).hide();
				jQuery('.load_span',thisform).show().css({"display":"block"});
				jQuery('.required-item',thisform).each(function () {
					var required = jQuery(this);
					required.css("border-color","#e1e2e3");
					if (required.val() == '' && required.attr("type") != "file") {
						required.css("border-color","#F00");
						return false;
					}
				});
				
				if (jQuery('.wpqa_captcha',thisform).length) {
					var wpqa_captcha = jQuery('.wpqa_captcha',thisform);
					var url = wpqa_unlogged.wpqa_dir+"captcha/captcha.php";
					var postStr = wpqa_captcha.attr("name")+"="+encodeURIComponent(wpqa_captcha.val());
					
					wpqa_captcha.css("border-color","#e1e2e3");
					
					if (wpqa_captcha.val() == "") {
						jQuery(".wpqa_error",thisform).html('<span class="required-error required-error-c">'+wpqa_unlogged.wpqa_error_text+'</span>').animate({opacity: 'show' , height: 'show'}, 400).delay(5000).animate({opacity: 'hide' , height: 'hide'}, 400);
						wpqa_captcha.css("border-color","#F00");
						jQuery('.load_span',thisform).hide().css({"display":"none"});
						jQuery('input[type="submit"]',thisform).show();
						return false;
					}else if (wpqa_captcha.hasClass("captcha_answer")) {
						if (wpqa_captcha.val() != wpqa_unlogged.captcha_answer) {
							jQuery(".wpqa_error",thisform).html('<span class="required-error required-error-c">'+wpqa_unlogged.wpqa_error_captcha+'</span>').animate({opacity: 'show' , height: 'show'}, 400).delay(5000).animate({opacity: 'hide' , height: 'hide'}, 400);
							wpqa_captcha.css("border-color","#F00");
							jQuery('.load_span',thisform).hide().css({"display":"none"});
							jQuery('input[type="submit"]',thisform).show();
							return false;
						}
					}else {
						var message = "";
						jQuery.ajax({
							url:   url,
							type:  "POST",
							data:  postStr,
							async: false,
							success: function(data) {
								message = data;
							}
						});
						if (message == "wpqa_captcha_0") {
							jQuery(".wpqa_error",thisform).html('<span class="required-error required-error-c">'+wpqa_unlogged.wpqa_error_captcha+'</span>').animate({opacity: 'show' , height: 'show'}, 400).delay(5000).animate({opacity: 'hide' , height: 'hide'}, 400);
							wpqa_captcha.css("border-color","#F00");
							jQuery('.load_span',thisform).hide().css({"display":"none"});
							jQuery('input[type="submit"]',thisform).show();
							return false;
						}
					}
				}

				var data = thisform.serialize();
				
				jQuery.ajax({
					url: wpqa_unlogged.admin_url,
					type: "POST",
		            dataType: "json",
					data: data,
					success:function(result) {
						if (result.success == 1) {
							if (whatAction == "password") {
								jQuery('input[type="email"]',thisform).val("");
								jQuery('.wpqa_captcha',thisform).val("");
								jQuery(".wpqa_success",thisform).html(result.done).animate({opacity: 'show' , height: 'show'}, 400).delay(5000).animate({opacity: 'hide' , height: 'hide'}, 400);
							}else {
								window.location.href = result.redirect;
							}
						}else if (result.error) {
							jQuery(".wpqa_error",thisform).html('<span class="required-error">'+result.error+'</span>').animate({opacity: 'show' , height: 'show'}, 400).delay(5000).animate({opacity: 'hide' , height: 'hide'}, 400);
						}else {
							return true;
						}
						jQuery('.load_span',thisform).hide().css({"display":"none"});
						jQuery('input[type="submit"]',thisform).show();
					},
					error: function(errorThrown) {
						// Error
					}
				});
				return false;
			});
		}
	}

	un_login_panel("#signup-panel",".signup-panel-un");
	un_login_panel("#lost-password",".main_users_only .reset-password,.lost-password-login");
	un_login_panel("#lost-password",".lost-passwords","no",".main_users_only");
	un_login_panel("#login-panel",".login-panel-un");
	
	function un_login_panel(whatId,whatClass,whatFrom,bodyClass) {
		if (jQuery(whatId).length && !jQuery(whatClass).hasClass("wpqa-not-pop")) {
			jQuery((whatFrom == "no"?(bodyClass != ""?bodyClass+" ":"")+".wpqa_form,":"")+whatClass).on("click",(whatFrom == "no"?whatClass:""),function() {
				var data_width = jQuery(whatId).attr("data-width");
				jQuery(".panel-un-login").hide(10);
				jQuery(whatId).animate({opacity: 'show' , height: 'show'}, 400);
				return false;
			});
		}
	}
	
})(jQuery);