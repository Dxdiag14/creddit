(function($) { "use strict";
	
	/* Referral */

	if (jQuery(".referral-form").length) {
		jQuery(".referral-form form").submit(function () {
			var thisform = jQuery(this);
			var email_var = jQuery('input[type="email"]',thisform);
			var invitation_nonce = jQuery('#invitation_nonce',thisform).val();
			var email = email_var.val();
			jQuery.ajax({
				url: wpqa_referral.admin_url,
				type: "POST",
				data: { action : 'wpqa_send_invitation', email : email, invitation_nonce : invitation_nonce },
				success:function(data) {
					if (data == "email_exist") {
						thisform.parent().find(".wpqa_error").hide(10).text(wpqa_referral.email_exist).slideDown(200).delay(5000).slideUp(200);
					}else {
						email_var.val("");
						thisform.parent().find(".wpqa_success").hide(10).text(wpqa_referral.sent_invitation).slideDown(200).delay(5000).slideUp(200);
					}
				}
			});
			return false;
		});
	}

	if (jQuery(".resend-invitation").length) {
		jQuery(".resend-invitation").on("click",function () {
			var email_var = jQuery(this);
			var email     = email_var.data("email");
			var invite    = email_var.data("invite");
			var invitation_resend_nonce = email_var.data("nonce");
			email_var.hide();
			jQuery.ajax({
				url: wpqa_referral.admin_url,
				type: "POST",
				data: { action : 'wpqa_resend_invitation', email : email, invite : invite, invitation_resend_nonce : invitation_resend_nonce },
				success:function(data) {
					email_var.val("");
					email_var.closest("li").find(".wpqa_success").hide(10).text(wpqa_referral.sent_invitation).slideDown(200).delay(5000).slideUp(200);
					email_var.show();
				}
			});
			return false;
		});
	}
	
})(jQuery);