(function($) { "use strict";
	
	/* Subscriptions */
	
	if (jQuery(".subscribe-signup").length) {
		jQuery(".subscribe-signup").each(function () {
			jQuery(document).on("click",".subscribe-signup",function () {
				var subscribe = jQuery(this);
				var subscribe_plan = subscribe.attr("data-subscribe");
				if (jQuery(".signup_form .subscribe-plan").length) {
					jQuery(".signup_form .subscribe-plan").val(subscribe_plan);
				}else {
					jQuery(".signup_form .form-submit").append('<input type="hidden" class="subscribe-plan" name="subscribe_plan" value="'+subscribe_plan+'">');
				}
				return false;
			});
		});
	}

	if (jQuery(".subscribe-section").length) {
		if (window.location.hash == "#subscribe-monthly" || window.location.hash == "#subscribe-3months" || window.location.hash == "#subscribe-6months" || window.location.hash == "#subscribe-yearly" || window.location.hash == "#subscribe-2years" || window.location.hash == "#subscribe-lifetime") {
			if (jQuery(window.location.hash).length) {
				jQuery("html,body").animate({scrollTop: jQuery(window.location.hash).offset().top},"slow");
			}
		}
	}
	
	if (jQuery(".cancel-subscription").length) {
		jQuery(".cancel-subscription").on("click","a",function () {
			if (confirm(wpqa_subscriptions.cancel_subscription)) {
				jQuery.ajax({
					url: wpqa_subscriptions.admin_url,
					type: "POST",
					data: { action : 'wpqa_cancel_subscription_ajax' },
					success:function(data) {
						location.reload();
					}
				});
			}
			return false;
		});
	}
	
	if (jQuery(".trial-plan").length) {
		jQuery(".trial-plan").on("click","a",function () {
			if (confirm(wpqa_subscriptions.trial_subscription)) {
				return true;
			}
			return false;
		});
	}
	
})(jQuery);