(function($) { "use strict";
	
	/* Add categories */

	if (jQuery(".add_categories_left_menu").length) {
		jQuery(".add_categories_left_menu").click("on",function () {
			var add_item = jQuery(this);
			var item_id = jQuery(this).data("id");
			var item_name = jQuery(this).data("name");
			var select_val = add_item.parent().find("select").val();
			var select_text = add_item.parent().find("select option:selected").text();
			if (jQuery("#"+item_id+'_'+select_val).length) {
				jQuery("#"+item_id+'_'+select_val).addClass("removered").slideUp(function() {
					jQuery(this).slideDown().removeClass("removered");
				});
			}else {
				jQuery("#"+item_id).append('<li class="categories" id="'+item_id+'_'+select_val+'"><label>'+select_text+'</label><input name="'+item_name+'[cat-'+select_val+'][value]" value="'+select_val+'" type="hidden"><div><div class="del-item-li"><i class="icon-cancel"></i></div><div class="move-poll-li"><i class="icon-menu"></i></div></div></li>');
			}
			return false;
		});
	}

	/* Remove readonly */
	
	jQuery(window).on("load",function() {
		if (jQuery(".wpqa-readonly").length) {
			setTimeout(function() {
				jQuery(".wpqa-readonly input:not(.age-datepicker)").attr("readonly",false);
			},600);
		}
	});

	/* Financial payments */
	
	if (jQuery(".financial_payments_field").length) {
		jQuery("input[name='financial_payments']").on("change",function () {
			var financial_payments_c = jQuery(this);
			var financial_payments_c_val = financial_payments_c.val();
			jQuery(".financial_payments_forms").slideUp(10);
			jQuery("."+financial_payments_c_val+"_form").slideDown(300);
		});
	}

	/* Withdrawals */
	
	if (jQuery(".points_radio").length) {
		jQuery("input[name='custom_points']").on("click",function () {
			jQuery("input[name='choose_points'][value='custom']").attr("checked","checked");
		});
		jQuery("input[name='custom_points']").on("keyup",function() {
			var custom_points  = jQuery(this);
			var custom_points_value = custom_points.val();
			var typingTimer;
			if (custom_points_value != "") {
				clearTimeout(typingTimer);
				typingTimer = setTimeout(function () {
					jQuery.ajax({
						url: wpqa_edit.admin_url,
						type: "POST",
						cache: false,
						dataType: "json",
						data: { action : 'wpqa_request_money',custom_points_value : custom_points_value },
						success:function(result) {
							if (result.success == 0) {
								jQuery(".points_chooseCustom,.points_radio_first").css({"border-color":"#F00"});
								jQuery(".custom_points").css({"color":"#F00"});
								var last_error = wpqa_edit.not_min_points;
								if (result.error == "not_enough_points") {
									var last_error = wpqa_edit.not_enough_points;
								}else if (result.error == "not_enough_money") {
									var last_error = wpqa_edit.not_enough_money;
								}
								custom_points.closest(".withdrew_content").find(".wpqa_error").text(last_error).slideDown(200);
							}else {
								custom_points.closest(".withdrew_content").find(".wpqa_error").slideUp(200);
								jQuery(".points_chooseCustom,.points_radio_first").css({"border-color":"#e1e3e3"});
								jQuery(".custom_points").css({"color":"#677075"});
								jQuery(".current_balance strong span").text(result.success);
							}
						}
					});
				},1000);
			}
		});
	}
	
})(jQuery);