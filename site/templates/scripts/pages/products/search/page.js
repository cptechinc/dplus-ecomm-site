$(function() {
	let server = ProductsRequests.getInstance();

/* =============================================================
	JQuery Validation
============================================================= */
	$('.addProductToCartForm').each(function() {
		let form = $(this);

		form.validate({
			rules: {},
			submitHandler: function(form) {
				let jForm = $(form);
				let data = jForm.formValues();
				
				server.addToCart(data, function(json) {
					if (json.error) {
						Toast.fire({
							timer: 2000,
							icon: 'error',
							title: json.msg,
						});
						return true;
					}
					Toast.fire({
						timer: 2000,
						icon: 'success',
						title: data.itemID + ' was added to cart',
					});
				});
			}
		});
	});
});