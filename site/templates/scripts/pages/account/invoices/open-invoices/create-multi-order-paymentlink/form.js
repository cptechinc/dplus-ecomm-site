$(function() {
	/* =============================================================
		jQuery Validation
	============================================================= */
		$('form#multiOrderPaymentLinkForm').validate({
			rules: {
				'invnbr[]': {
					required: true,
				},
			},
			submitHandler: function(form) {
				let data = new FormData(form);
				let invnbrs = [];

				data.forEach(function(value, key) {
					if (key != "invnbr[]") {
						return false;
					}
					if (value == '') {
						return false;
					}
					invnbrs.push(value);
				});

				if (invnbrs.length) {
					form.submit();
				}
			}
		});
	});