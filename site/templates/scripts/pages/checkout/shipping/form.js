$(function() {
	let shippingForm = new SimpleForm('shippingForm');
	let server = CheckoutRequests.getInstance();
	
/*==============================================================
	EVENT LISTENERS
=============================================================*/
	
/*==============================================================
	Jquery Validate LISTENERS
=============================================================*/
	shippingForm.form.validate({
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