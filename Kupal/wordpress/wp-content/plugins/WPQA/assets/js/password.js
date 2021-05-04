(function($) { "use strict";
	
	jQuery(window).on("load",function() {
		if (jQuery(".wpqa-readonly").length) {
			setTimeout(function() {
				jQuery(".wpqa-readonly input:not(.age-datepicker)").attr("readonly",false);
			},600);
		}
	});
	
})(jQuery);