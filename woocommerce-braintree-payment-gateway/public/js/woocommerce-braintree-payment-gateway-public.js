/* global Braintree_params */
(function ($) {

    function initBraintree() {

	if (typeof braintree != 'undefined') {

	    braintree.client.create({
		authorization: Braintree_params.client_token,
	    }, function (err, clientInstance) {
		if (err) {
		    console.error(err);
		    return;
		}
		braintree.threeDSecure.create({
		    client: clientInstance
		}, function (threeDSecureErr, threeDSecureInstance) {
		    if (threeDSecureErr) {
			console.error(err);
			return;
		    }

		    threeDSecure = threeDSecureInstance;
		});

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
		}, function (err, hostedFieldsInstance) {
		    if (err) {
			return;
		    }
		    var checkout_form = jQuery('form.checkout');
		    var canSubmit = false;

		    checkout_form.on('checkout_place_order', function () {
			if (canSubmit) {
			    canSubmit = false;
			    return true;
			}
			$('button#place_order').hide();
			$('#braintree-spinner-container').insertAfter('button#place_order');
			$('#braintree-spinner-container').show();

			hostedFieldsInstance.tokenize(function (err, payload) {
			    jQuery('input#braintree-error').val('');
			    if (err) {
				var errVal = "check";
				if (typeof err.details != "undefined") {
				    errVal = '';
				    jQuery.each(err.details.invalidFieldKeys, function (index, value) {
					errVal += value + ',';
				    });
				} else {
				    errVal = 'empty';
				}
				jQuery('input#braintree-error').val(errVal);
				$('#braintree-spinner-container').hide();
				$('button#place_order').show();
				canSubmit = true;
				checkout_form.submit();
			    } else {
				jQuery('#braintree-payment-nonce').val(payload.nonce);
				console.log("Starting 3DS verify...");
				var bt3DSContainer = document.getElementById('braintree-3ds-modal-container');
				var bt3DSModalContent = document.getElementById('braintree-3ds-modal-content');
				threeDSecure.verifyCard({
				    amount: Braintree_params.total_amount,
				    nonce: payload.nonce,
				    addFrame: function (err, iframe) {
					console.log('Adding 3DS frame');
					bt3DSModalContent.appendChild(iframe);
					$('div.braintree-3ds-modal-container').fadeIn();
				    },
				    removeFrame: function () {
					console.log('Removing 3DS frame');
					$('div.braintree-3ds-modal-container').fadeOut();
				    }
				}, function (err, response) {
				    if (err) {
					console.error(err);
				    } else {
//					console.log(response);
					jQuery('#braintree-payment-nonce').val(response.nonce);
				    }
				    console.log('3DS check done');
				    $('#braintree-spinner-container').hide();
				    $('button#place_order').show();
				    canSubmit = true;
				    checkout_form.submit();
				});
			    }
			});
			return false;
		    });
		});
	    });
	    $('.braintree-3ds-modal-close-btn').click(function () {
		$('div.braintree-3ds-modal-container').fadeOut();
		$('#braintree-spinner-container').hide();
		$('button#place_order').show();
	    });
	}
    }

    jQuery('body').on('updated_checkout', function () {
	initBraintree();
    });


}(jQuery));