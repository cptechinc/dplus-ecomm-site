$(function() {
	let addToCartForm = new AddToCartForm('addToCartForm');
	let server = CartRequests.getInstance();
	
/* =============================================================
	Events
============================================================= */
	$("body").on('change', '#addToCartForm input[name=itemID]', function(e) {
		e.preventDefault();
		let input = $(this);

		addToCartForm.clearItemData();
		
		server.getProductByItemid(input.val(), function(json) {
			if (json.error) {
				return false;
			}
			if (json.hasOwnProperty('product')) {
				addToCartForm.fillItemData(json.product);

				if (addToCartForm.inputs.fields.qty.val() > 0) {
					addToCartForm.updatePriceFromPricebreaks();
					addToCartForm.updateAmount();
				}
			}
		});
	});

	$("body").on('change', '#addToCartForm input[name=qty]', function(e) {
		e.preventDefault();
		let input = $(this);

		addToCartForm.updatePriceFromPricebreaks();
		addToCartForm.updateAmount();
	});
	
/*==============================================================
	Jquery Validate LISTENERS
=============================================================*/
	addToCartForm.form.validate({
		rules: {
			itemID: {
				required: true,
				remote: {
					url: config.ajax.urls.json + 'products/validate/itemid/',
					type: "get",
					data: {
						jqv: 'true',
					}
				}
			}
		},
		submitHandler: function(form) {
			form.submit();
		}
	});
});