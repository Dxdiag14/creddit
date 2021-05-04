(function($) { "use strict";
	
	/* Message */
	
	if (jQuery(".message-delete a").length) {
		jQuery(".message-delete").on("click","a",function () {
			if (confirm(wpqa_message.sure_delete_message)) {
				return true;
			}else {
				return false;
			}
		});
	}
	
	if (jQuery(".view-message").length) {
		jQuery(document).on("click",".view-message",function () {
			var view_message    = jQuery(this);
			var message_id      = view_message.attr("data-id");
			var message_content = view_message.parent().parent().find(".message-content");
			view_message.find(".message-open-close").removeClass("icon-minus").addClass("icon-plus");
			if (view_message.hasClass("view-message-open")) {
				message_content.slideUp(300);
				view_message.removeClass("view-message-open");
			}else {
				if (message_content.find(" > div").length) {
					message_content.slideDown(300);
					view_message.addClass("view-message-open").find(".message-open-close").removeClass("icon-plus").addClass("icon-minus");
				}else {
					view_message.addClass("view-message-open").parent().parent().find(".small_loader").addClass("small_loader_display");
					jQuery.ajax({
						url: wpqa_message.admin_url,
						type: "POST",
						data: { action : 'wpqa_message_view',message_id : message_id },
						success:function(data) {
							view_message.parent().find(".message-new").removeClass("message-new");
							view_message.parent().parent().find(".small_loader").removeClass("small_loader_display");
							view_message.find(".message-open-close").removeClass("icon-plus").addClass("icon-minus");
							message_content.html(data).slideDown(300);
							view_message.find(".message-new").removeClass("message-new");
						}
					});
				}
			}
			return false;
		});
	}
	
	if (jQuery(".block_message").length) {
		jQuery(document).on("click",".block_message",function () {
			var block_message = jQuery(this);
			var user_id       = block_message.attr("data-id");
			var block_message_nonce = block_message.data("nonce");
			jQuery(".block_message_"+user_id).hide();
			jQuery.ajax({
				url: wpqa_message.admin_url,
				type: "POST",
				data: { action : (block_message.hasClass("unblock_message")?'wpqa_unblock_message':'wpqa_block_message'),user_id : user_id,block_message_nonce : block_message_nonce },
				success:function(data) {
					if (block_message.hasClass("unblock_message")) {
						jQuery(".block_message_"+user_id).removeClass("unblock_message").text(wpqa_message.block_message_text).show();
					}else {
						jQuery(".block_message_"+user_id).addClass("unblock_message").text(wpqa_message.unblock_message_text).show();
					}
				}
			});
			return false;
		});
	}
	
})(jQuery);