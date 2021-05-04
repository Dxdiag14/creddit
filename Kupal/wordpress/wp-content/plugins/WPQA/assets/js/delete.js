(function($) { "use strict";
	
	if (jQuery(".delete_account").length) {
		jQuery(document).on("click",".delete_account",function () {
			var delete_account = jQuery(".delete_account").is(":checked");
			if (delete_account != 1 || confirm(wpqa_delete.delete_account)) {
				return true;
			}else {
				return false;
			}
		});
	}
	
})(jQuery);