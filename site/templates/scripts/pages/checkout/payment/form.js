$(function() {
	let paymentForm = new PaymentForm('paymentForm');
	let server = CheckoutRequests.getInstance();
	
/*==============================================================
	EVENT LISTENERS
=============================================================*/
	$("body").on('change', 'input[name=cardnumber]', function(e) {
		$('img[data-type]').removeClass('show');

		if (paymentForm.validateCardnumber()) {
			let type = paymentForm.getCardType();
			$('input[name=cardtype]').val(type);
			$('img[data-type="'+type+'"]').addClass('show');
		}
	});
	
/*==============================================================
	Jquery Validate Methods
=============================================================*/
	jQuery.validator.addMethod("cardnumber", function(value, element) {
		return this.optional(element) || paymentForm.validateCardnumber();
	}, "Invalid Credit Card Number");

	jQuery.validator.addMethod("expiredate", function(value, element) {
		return this.optional(element) || paymentForm.validateExpiredate();
	}, "Invalid Date");

	jQuery.validator.addMethod("cvc", function(value, element) {
		return this.optional(element) || paymentForm.validateCvc();
	}, "Invalid CVC");

/*==============================================================
	Jquery Validate LISTENERS
=============================================================*/	
	paymentForm.form.validate({
		rules: {
			cardnumber: {
				required: function() {
					inputs = paymentForm.inputs.fields;
					if (inputs.hasOwnProperty('usesavedcard') == false) {
						return inputs.paymentmethod.val() == 'cc'
					}
					return inputs.paymentmethod.val() == 'cc' && inputs.usesavedcard.is(':checked') === false;
				},
				cardnumber: true,
			},
			expiredate: {
				required: function() {
					inputs = paymentForm.inputs.fields;
					if (inputs.hasOwnProperty('usesavedcard') == false) {
						return inputs.paymentmethod.val() == 'cc'
					}
					return inputs.paymentmethod.val() == 'cc' && inputs.usesavedcard.is(':checked') === false;
				},
				expiredate: true,
			},
			cvc: {
				required: function() {
					inputs = paymentForm.inputs.fields;
					if (inputs.hasOwnProperty('usesavedcard') == false) {
						return inputs.paymentmethod.val() == 'cc'
					}
					return inputs.paymentmethod.val() == 'cc' && inputs.usesavedcard.is(':checked') === false;
				},
				cvc: true,
			},
		},
		submitHandler: function(form) {
			let jForm = $(form);
			let data = jForm.formValues();

			server.process(data, function(json) {
				let container = jForm.closest('.checkout-step');

				if (json.error) {
					container.find('.success-icon').removeClass('show');
					Toast.fire({
						timer: 2000,
						icon: 'error',
						title: json.msg,
					});
					return true;
				}
				
				container.find('.success-icon').addClass('show');
				let step = parseInt(container.data('step')) + 1;
				$('.checkout-step[data-step="'+step+'"]').find('a[data-toggle=collapse]').click();

				Toast.fire({
					timer: 2000,
					icon: 'success',
					title: json.msg,
				});
			});
		}
	});
});