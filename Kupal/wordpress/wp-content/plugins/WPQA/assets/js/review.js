(function($) { "use strict";
	
	/* Review question and post */

	if (jQuery(".review-post").length) {
		jQuery(document).on("click",".review-post,.a-pending-post,.d-pending-post,.b-pending-post,.u-pending-post",function () {
			var pending_post = jQuery(this);
			var main_post = pending_post.closest(".article-post");
			var post_id = main_post.attr("id").replace('post-',"");
			if (pending_post.hasClass("review-post")) {
				main_post.find(".all_not_signle_post_content,.review-post").slideUp(200);
				main_post.find(".all_signle_post_content,.a-pending-post,.d-pending-post,.e-pending-post,.b-pending-post,.u-pending-post").slideDown(200);
			}else {
				var pending_nonce = pending_post.data("nonce");
				var all_section = main_post.closest(".post-articles");
				var pending_type = "delete";
				if (pending_post.hasClass("a-pending-post")) {
					var pending_type = "approve";
				}else if (pending_post.hasClass("b-pending-post")) {
					var pending_type = "ban";
				}else if (pending_post.hasClass("u-pending-post")) {
					var pending_type = "unban";
				}
				if ((pending_post.hasClass("b-pending-post") && confirm(wpqa_review.sure_ban)) || pending_post.hasClass("u-pending-post") || (pending_post.hasClass("d-pending-post") && pending_post.hasClass("delete-pending-question") && confirm(wpqa_review.sure_delete)) || (pending_post.hasClass("d-pending-post") && pending_post.hasClass("delete-pending-post") && confirm(wpqa_review.sure_delete_post)) || pending_post.hasClass("a-pending-post")) {
					main_post.find(" > .load_span").show().css({"display":"block"});
					main_post.find(".single-inner-content").hide();
					jQuery.ajax({
						url: wpqa_review.admin_url,
						type: "POST",
						data: { action : 'wpqa_pending_post', pending_type : pending_type, pending_nonce : pending_nonce, post_id : post_id },
						success:function(data) {
							if (pending_type == "ban" || pending_type == "unban") {
								if (pending_type == "ban") {
									pending_post.find("i").removeClass("icon-cancel-circled").addClass("icon-back");
									pending_post.removeClass("b-pending-post").addClass("u-pending-post").find("span").text(wpqa_review.unban_user);
								}else {
									pending_post.find("i").removeClass("icon-back").addClass("icon-cancel-circled");
									pending_post.removeClass("u-pending-post").addClass("b-pending-post").find("span").text(wpqa_review.ban_user);
								}
								main_post.find(" > .load_span").show().hide();
								main_post.find(".single-inner-content").show();
							}else {
								main_post.animate({opacity: 'hide' , height: 'hide'}, 400).remove();
								main_post.find(" > .load_span").show().css({"display":"block"});
								if (all_section.find(".article-post").length == 0) {
									all_section.append("<div class='alert-message warning'><i class='icon-flag'></i><p>"+(jQuery("#section-pending-questions").length > 0?wpqa_review.no_questions:wpqa_review.no_posts)+"</p></div>")
								}
							}
						}
					});
				}
			}
			return false;
		});
	}
	
})(jQuery);