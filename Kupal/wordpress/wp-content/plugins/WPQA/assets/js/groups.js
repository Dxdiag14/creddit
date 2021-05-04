(function($) { "use strict";
	
	/* Group approval */
	
	if (jQuery(".group_allow_posts_field").length) {
		var add_group_approval_p = jQuery(".add_group_approval_p");
		var group_allow_posts = jQuery("input[name='group_allow_posts']");
		if (jQuery("input[name='group_allow_posts']:checked").val() == "all") {
			add_group_approval_p.slideDown(200);
		}else {
			add_group_approval_p.slideUp(200);
		}
		
		jQuery("input[name='group_allow_posts']").on("change",function () {
			var group_allow_posts_c = jQuery(this);
			if (group_allow_posts_c.val() == "all") {
				add_group_approval_p.slideDown(200);
			}else {
				add_group_approval_p.slideUp(200);
			}
		});
	}
	
	/* Approve, decline request, block, unblock users, and remove users */
	
	if (jQuery(".approve_request_group,.decline_request_group,.block_user_group,.unblock_user_group,.remove_user_group,.remove_moderator_group,.agree_posts_group").length) {
		wpqa_group_functions("approve_request_group","approve_request_group");
		wpqa_group_functions("decline_request_group","decline_request_group");
		wpqa_group_functions("block_user_group","block_user_group");
		wpqa_group_functions("unblock_user_group","unblock_user_group");
		wpqa_group_functions("remove_user_group","remove_user_group");
		wpqa_group_functions("remove_moderator_group","remove_moderator_group");
		wpqa_group_functions("agree_posts_group","agree_posts_group");
		
		function wpqa_group_functions(class_name,function_name) {
			jQuery(document).on("click","."+class_name,function () {
				var group_var = jQuery(this);
				if (group_var.hasClass("block_user_group")) {
					var var_confirm = wpqa_groups.sure_block_user;
				}else if (group_var.hasClass("remove_user_group")) {
					var var_confirm = wpqa_groups.sure_remove_user;
				}else if (group_var.hasClass("remove_moderator_group")) {
					var var_confirm = wpqa_groups.remove_moderator;
				}
				if ((!group_var.hasClass("block_user_group") && !group_var.hasClass("remove_user_group") && !group_var.hasClass("remove_moderator_group")) || ((group_var.hasClass("block_user_group") || group_var.hasClass("remove_user_group") || group_var.hasClass("remove_moderator_group")) && confirm(var_confirm))) {
					var group_id = group_var.data("group");
					var user_id = group_var.data("user");
					group_var.hide();
					if (group_var.parent().hasClass("group_review_button")) {
						group_var.parent().find(" > a").hide();
					}
					group_var.parent().find(".cover_loader").addClass("cover_loader_show");
					jQuery.ajax({
						url: wpqa_groups.admin_url,
						type: "POST",
						data: {action:'wpqa_'+function_name,group_id:group_id,user_id:user_id},
						success:function(result) {
							location.reload();
						}
					});
				}
				return false;
			});
		}
	}
	
	/* Join, leave groups, cancel requests, approve, decline requests, accept, or decline invite */
	
	if (jQuery(".user_out_group,.user_in_group,.request_group,.cancel_request_group,.approve_request_all_group,.decline_request_all_group,.accept_invite,.decline_invite").length) {
		wpqa_group("user_out_group","join_group");
		wpqa_group("user_in_group","leave_group");

		wpqa_group("request_group","request_group");
		wpqa_group("cancel_request_group","cancel_request_group");

		wpqa_group("approve_request_all_group","approve_request_all_group");
		wpqa_group("decline_request_all_group","decline_request_all_group");

		wpqa_group("accept_invite","accept_invite");
		wpqa_group("decline_invite","decline_invite");
		
		function wpqa_group(class_name,function_name) {
			jQuery(document).on("click","."+class_name,function () {
				var group_var = jQuery(this);
				var group_id = group_var.data("id");
				group_var.hide();
				group_var.parent().find(".hide_button_too").hide();
				group_var.parent().find(".cover_loader").addClass("cover_loader_show");
				jQuery.ajax({
					url: wpqa_groups.admin_url,
					type: "POST",
					data: {action:'wpqa_'+function_name,group_id:group_id},
					success:function(result) {
						location.reload();
					}
				});
				return false;
			});
		}
	}
	
	/* Like post */
	
	if (jQuery(".posts-likes a").length) {
		jQuery(".posts-likes a").on("click",function () {
			var posts_likes = jQuery(this);
			var post_id = posts_likes.data('id');
			posts_likes.hide();
			posts_likes.parent().find(".loader_2").show();
			jQuery.ajax({
				url: wpqa_groups.admin_url,
				type: "POST",
				data: { action : 'wpqa_posts_'+(posts_likes.hasClass("unlike-posts")?"unlike":"like"), post_id : post_id },
				success:function(data) {
					if (posts_likes.hasClass("unlike-posts")) {
						posts_likes.removeClass("unlike-posts").addClass("like-posts");
					}else {
						posts_likes.removeClass("like-posts").addClass("unlike-posts");
					}
					posts_likes.attr("original-title",(posts_likes.hasClass("unlike-posts")?wpqa_groups.like_posts_attr:wpqa_groups.unlike_posts_attr)).show().parent().find(".loader_2").hide().parent().find("span").text(data);
				}
			});
			return false;
		});
	}

	/* Show replies */
	
	if (jQuery(".show-replies").length) {
		jQuery(".show-replies").on("click",function() {
			var replies = jQuery(this);
			replies.closest(".comment").find(" > .children").slideToggle(300);
			return false;
		});
	}

	/* Add reply */

	jQuery(".embed_comments").on("click",".wpqa-reply-link",function () {
		var commentthis = jQuery(this);
		var post_id = commentthis.data("post_id");
		var comment_id = commentthis.data("id");
		var aria_label = commentthis.attr("aria-label");
		var comment_parent = commentthis.closest(".comment-body").parent();
		jQuery(".write_comment").hide(10);
		if (comment_parent.find(" > .comment-respond").length == 0) {
			comment_parent.find(".comment-body").after('<div id="respond" class="comment-respond wpqa_hide">\
				<h3 class="section-title">'+aria_label+'\
					<div class="wpqa-cancel-link cancel-comment-reply">\
						<a rel="nofollow" id="cancel-comment-reply-link" href="#respond">'+wpqa_groups.cancel_reply+'</a>\
					</div>\
				</h3>\
				<form action="'+wpqa_groups.comment_action+'" method="post" id="commentform" class="post-section comment-form answers-form">\
					<div class="wpqa_error"></div>\
					<div class="form-input form-textarea form-comment-normal">\
						<textarea id="comment" name="comment" aria-required="true" placeholder="'+wpqa_groups.reply+'"></textarea>\
						<i class="icon-pencil"></i>\
					</div>\
					<div class="clearfix"></div>\
					<p class="form-submit">\
						<input name="submit" type="submit" id="submit" class="button-default button-hide-click" value="'+wpqa_groups.submit+'">\
						<span class="clearfix"></span>\
						<span class="load_span"><span class="loader_2"></span></span>\
						<input type="hidden" name="comment_post_ID" value="'+post_id+'" id="comment_post_ID">\
						<input type="hidden" name="comment_parent" id="comment_parent" value="'+comment_id+'">\
					</p>\
				</form>\
			</div>');
		}
		comment_parent.find(" > .comment-respond").show();
		return false;
	});
	
	jQuery(document).on("click",".wpqa-cancel-link a",function () {
		jQuery(".wpqa-cancel-link,.comment #respond").remove();
		jQuery(".respond-edit-delete").hide();
		return false;
	});
	
	jQuery(document).on("click",".meta-group-comments",function () {
		jQuery(this).closest(".content_group_item").find(".write_comment").slideToggle(300);
		return false;
	});
	
	/* Delete group or posts */
	
	if (jQuery(".delete-group,.posts-delete").length) {
		jQuery(".delete-group,.posts-delete").on("click",function () {
			var var_delete = (jQuery(".posts-delete").length?wpqa_groups.sure_delete_posts:wpqa_groups.sure_delete_group);
			if (confirm(var_delete)) {
				return true;
			}else {
				return false;
			}
		});
	}

	/* Assign a new moderator or invite user */

	if (jQuery(".add-new-user").length) {
		jQuery(".add-new-user").each(function () {
			var add_new_user       = jQuery(this);
			var add_user_form      = ".add-user-form";
			var doneTypingInterval = 500;
			var typingTimer;
			add_new_user.on("keyup",function() {
				var new_user  = jQuery(this);
				var user_value = new_user.val();
				var group_id = new_user.data("id");
				var invite = (new_user.hasClass("add-new-moderator")?"":"invite");
				if (user_value == "") {
					new_user.closest(add_user_form).find(".user-results").addClass("results-empty").html("").hide();
				}else {
					var user_loader = new_user.closest(add_user_form).find(".user_loader");
					clearTimeout(typingTimer);
					typingTimer = setTimeout(function () {
						user_loader.show(10);
						jQuery.ajax({
							url: wpqa_groups.admin_url,
							type: "POST",
							data: { action : 'wpqa_new_user_group',group_id : group_id,user_value : user_value,invite : invite },
							success:function(data) {
								new_user.closest(add_user_form).find(".user-results").removeClass("results-empty").html(data).slideDown(300);
								user_loader.hide(10);
							}
						});
					},500);
				}
			});
			
			add_new_user.on('focus',function() {
				var new_user = jQuery(this);
				if (new_user.closest(add_user_form).find(".results-empty").length == 0) {
					new_user.closest(add_user_form).find(".user-results").show();
				}
			});
			
			var outputContainer = add_new_user.closest(add_user_form).find(".user-results");
			var input 			= add_new_user.get(0);
			jQuery('body').bind('click',function(e) {
				if (!jQuery.contains(outputContainer.get(0),e.target) && e.target != input) {
					outputContainer.hide();
				}
			});
		});

		jQuery(document).on("click",".add-user-to-input",function () {
			var add_user_to_input = jQuery(this);
			var user_id = add_user_to_input.data("id");
			var user_name = add_user_to_input.attr("title");
			var add_user_form = add_user_to_input.closest(".add-user-form");
			add_user_form.find(".add-new-user").val(user_name).attr("data-user",user_id);
			add_user_form.find(".button-user-col").removeClass("user-col-not-activate");
			add_user_form.find(".user-results").addClass("results-empty").html("").hide();
			return false;
		});

		jQuery(document).on("click",".new-user-button",function () {
			var new_user_button = jQuery(this);
			var add_user_form = new_user_button.closest(".add-user-form");
			var group_id = add_user_form.find(".add-new-user").data("id");
			var user_id = add_user_form.find(".add-new-user").data("user");
			var moderator = (add_user_form.find(".add-new-user").hasClass("add-new-moderator")?"moderator":"");
			jQuery.ajax({
				url: wpqa_groups.admin_url,
				type: "POST",
				data: {action:'wpqa_add_group_user',group_id:group_id,user_id:user_id,moderator:moderator},
				success:function(result) {
					location.reload();
				}
			});
			return false;
		});
	}

	/* Group rules */

	if (jQuery(".read_more_rules").length) {
		jQuery(document).on("click",".read_more_rules",function () {
			jQuery(this).parent().parent().find(".less_group_rules").hide().parent().find(".full_group_rules").show();
			return false;
		});

		jQuery(document).on("click",".read_less_rules",function () {
			jQuery(this).parent().parent().find(".full_group_rules").hide().parent().find(".less_group_rules").show();
			return false;
		});
	}
	
})(jQuery);