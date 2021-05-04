(function($) { "use strict";
	
	/* Payments */

	if (jQuery(".payment-methods a").length) {
		jQuery(".payment-tabs").on("click","a",function () {
			var payment = jQuery(this);
			var payment_hide = payment.attr("href");
			var payment_button = payment.parent().parent().find("a");
			var payment_wrap = payment.closest(".payment-wrap");
			if (payment_wrap.hasClass("payment-wrap-2")) {
				payment_button.removeClass("payment-style-activate");
				payment.addClass("payment-style-activate");
			}else {
				payment_button.addClass("button-default-2").removeClass("button-default-3");
				payment.addClass("button-default-3").removeClass("button-default-2");
			}
			payment_wrap.find(".payment-method").hide(10);
			payment_wrap.find(".payment-method[data-hide="+payment_hide+"]").slideDown(300);
			return false;
		});
	}

	/* Stripe */

	if (jQuery(".wpqa-stripe-payment").length && wpqa_payment.publishable_key != "") {
		var stripe = Stripe(wpqa_payment.publishable_key);

		function isInViewport(the_element) {
			var $window = jQuery(window);
			var viewPortTop = $window.scrollTop();
			var viewPortBottom = viewPortTop + $window.height();
			var elementTop = the_element.offset().top;
			var elementBottom = elementTop + the_element.outerHeight();
			return ((elementBottom <= viewPortBottom) && (elementTop >= viewPortTop));
		}

		function wpqa_stirpe_paypemt() {
			var $cards = jQuery('.wpqa-stripe-payment');

			if ($cards.length === 0) {
				return;
			}

			$cards.each(function () {
				var $form = jQuery(this).parents('form:first');
				var formId = $form.data('id');
				var elements = stripe.elements();
				var cardElement = elements.create('card', {
					hidePostalCode: true,
					classes: {
						base: 'wpqa-stripe-payment',
						empty: 'wpqa-stripe-payment-empty',
						focus: 'wpqa-stripe-payment-focus',
						complete: 'wpqa-stripe-payment-complete',
						invalid: 'wpqa-stripe-payment-error'
					},
					style: {
						base: {
							color: '#2F2F37',
							fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Oxygen-Sans", Ubuntu, Cantarell, "Helvetica Neue", sans-serif',
							fontSmoothing: 'antialiased',
							fontSize: '15px',
							'::placeholder': {
								color: '#7F8393'
							}
						},
						invalid: {
							color: '#2F2F37',
							iconColor: '#CC3434'
						}
					}
				});
				cardElement.mount('div.wpqa-stripe-payment[data-id="'+formId+'"]');
				cardElement.addEventListener('change', function (event) {
					var $form = jQuery(this).parents('form:first');
					if (event.error) {
						jQuery('.wpqa_error', $form).text(event.error.message).animate({opacity: 'show' , height: 'show'}, 400).delay(5000).animate({opacity: 'hide' , height: 'hide'}, 400);
					}
				});

				$form.submit(function () {
					jQuery('.load_span',$form).show();
					jQuery('button[type="submit"]',$form).prop('disabled', true).hide();
					jQuery('input[name="payment-method-id"]', $form).remove();
					jQuery('input[name="payment-intent-id"]', $form).remove();
					var payment_data = {};
					var card_name_input = jQuery('input[name="name"]', $form);
					if (card_name_input.length > 0) {
						var card_name = card_name_input.val();
						if (card_name != null && card_name != '') {
							payment_data.billing_details = {
								name: card_name
							};
						}
					}
					stripe.createPaymentMethod('card',cardElement,payment_data).then(function (payment_result) {
						if (payment_result.error) {
							jQuery('.load_span',$form).hide();
							jQuery('button[type="submit"]',$form).prop('disabled', false).show();
							jQuery('.wpqa_error', $form).text(payment_result.error.message).animate({opacity: 'show' , height: 'show'}, 400).delay(5000).animate({opacity: 'hide' , height: 'hide'}, 400);
							var the_element = jQuery('.wpqa-stripe-payment', $form);
							if (the_element && the_element.offset() && the_element.offset().top) {
								if (!isInViewport(the_element)) {
									jQuery('html, body').animate({scrollTop: the_element.offset().top - 100},1000);
								}
							}
							if (the_element) {
								the_element.fadeIn(500).fadeOut(500).fadeIn(500);
							}
						}else {
							if (typeof(payment_result) !== 'undefined' && payment_result.hasOwnProperty('paymentMethod') && payment_result.paymentMethod.hasOwnProperty('id')) {
								jQuery('<input>').attr({type: 'hidden',name: 'payment-method-id',value: payment_result.paymentMethod.id}).appendTo($form);
							}
							submit_ajax($form, cardElement);
						}
					});
					return false;
				});
			});
		}

		function submit_ajax($form, card) {
			jQuery.ajax({
				type: "POST",
				url: wpqa_payment.admin_url,
				data: $form.serialize(),
				cache: false,
				dataType: "json",
				success: function (data) {
					if (data.error) {
						jQuery('.wpqa_error', $form).text(data.error).animate({opacity: 'show' , height: 'show'}, 400).delay(5000).animate({opacity: 'hide' , height: 'hide'}, 400);
						jQuery('.load_span',$form).hide();
						jQuery('button[type="submit"]',$form).prop('disabled', false).show();
					}else if (data.success) {
						var formId = $form.data('id');
						if (card != null) {
							card.clear();
						}
						jQuery('input[name="payment-method-id"]', $form).remove();
						jQuery('input[name="payment-intent-id"]', $form).remove();
						if (data.redirect) {
							setTimeout(function () {
								window.location = data.redirect;
							}, 1500);
						}
					}else if (typeof(data) !== 'undefined' && data.hasOwnProperty('confirm_card') && data.confirm_card == 1) {
						confirm_card_payment($form, card, data);
					}else if (typeof(data) !== 'undefined' && data.hasOwnProperty('resubmit_again') && data.resubmit_again == 1) {
						jQuery('.load_span',$form).show();
						jQuery('button[type="submit"]',$form).prop('disabled', true).hide();
						submit_ajax($form, card);
					}
				},error: function (jqXHR, textStatus, errorThrown) {
					// Error
				},complete: function () {
					// Done
				}
			});
		}

		function confirm_card_payment($form, card, data) {
			stripe.confirmCardPayment(data.client_secret).then(function (result) {
				if (result.error) {
					jQuery('.wpqa_error', $form).text((result.error.hasOwnProperty('message')?result.error.message:result.error)).animate({opacity: 'show' , height: 'show'}, 400).delay(5000).animate({opacity: 'hide' , height: 'hide'}, 400);
				}else {
					jQuery('input[name="payment-intent-id"]', $form).remove();
					if (typeof(result) !== 'undefined' && result.hasOwnProperty('paymentIntent') && result.paymentIntent.hasOwnProperty('id')) {
						jQuery('<input>').attr({type: 'hidden',name: 'payment-intent-id',value: result.paymentIntent.id}).appendTo($form);
					}
					jQuery('.load_span',$form).show();
					jQuery('button[type="submit"]',$form).prop('disabled', true).hide();
					submit_ajax($form, card);
				}
			});
		}

		wpqa_stirpe_paypemt();
	}
	
})(jQuery);