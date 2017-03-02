/* global Braintree_params */
(function($) {

	function initBraintree() {

		if (typeof braintree != 'undefined') {

			braintree.client.create({
				authorization: Braintree_params.client_token,
			}, function(err, clientInstance) {
				if (err) {
					console.error(err);
					return;
				}
				braintree.hostedFields.create({
					client: clientInstance,
					styles: {
						'input': {
							'font-size': '14px',
							'font-family': 'helvetica, tahoma, calibri, sans-serif',
							'color': '#3a3a3a',
						},
						':focus': {
							'color': 'black'
						}
					},
					fields: {
						number: {
							selector: '#braintree-card-number',
							placeholder: 'XXXX XXXX XXXX XXXX'
						},
						expirationMonth: {
							selector: '#braintree-expiration-month',
							placeholder: 'Month'
						},
						expirationYear: {
							selector: '#braintree-expiration-year',
							placeholder: 'Year'
						},
						cvv: {
							selector: '#braintree-cvv',
							placeholder: 'XXX'
						},
					}
				}, function(err, hostedFieldsInstance) {
					if (err) {
						return;
					}
					var checkout_form = jQuery('form.checkout');
					var canSubmit = false;

					checkout_form.on('checkout_place_order', function() {
						if (canSubmit) {
							canSubmit = false;
							return true;
						}
						hostedFieldsInstance.tokenize(function(err, payload) {
							jQuery('input#braintree-error').val('');
							if (err) {
								var errVal = "check";
								if (typeof err.details != "undefined") {
									errVal = '';
									jQuery.each(err.details.invalidFieldKeys, function(index, value) {
										errVal += value + ',';
									});
								} else {
									errVal = 'empty';
								}
								jQuery('input#braintree-error').val(errVal);
								canSubmit = true;
								checkout_form.submit();
							} else {
								jQuery('#braintree-payment-nonce').val(payload.nonce);
								canSubmit = true;
								checkout_form.submit();
							}
						});
						return false;
					});
				});
			});
		}
	}

	jQuery('body').on('updated_checkout', function() {
		initBraintree();
	});


}(jQuery));