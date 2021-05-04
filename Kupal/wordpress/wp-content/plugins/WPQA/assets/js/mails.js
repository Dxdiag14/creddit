(function($) { "use strict";
	
	if (jQuery(".unsubscribe_mails").length) {
		jQuery(document).on("click",".unsubscribe_mails",function () {
			var unsubscribe_mails = jQuery(".unsubscribe_mails").is(":checked");
			if (unsubscribe_mails == 1) {
				jQuery(".received_email_field,.new_payment_mail_field,.send_message_mail_field,.answer_on_your_question_field,.answer_question_follow_field,.notified_reply_field,.question_schedules_field").slideUp(300);
			}else {
				jQuery(".received_email_field,.new_payment_mail_field,.send_message_mail_field,.answer_on_your_question_field,.answer_question_follow_field,.notified_reply_field,.question_schedules_field").slideDown(300);
			}
		});
	}
	
})(jQuery);