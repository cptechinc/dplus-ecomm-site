
class AddToCartForm extends SimpleForm {
	fillItemData(json = null) {
		this.clearItemData();

		let inputs = this.inputs.fields;

		inputs.price.val(json.sellprice);
		inputs.amount.val(json.sellprice);

		this.form.find('.product-field').each(function() {
			let textfield = $(this);
			let field = textfield.attr('product-field');

			if (json.hasOwnProperty(field)) {
				textfield.text(json[field]);
			}
		});
		inputs.itemid.attr('data-product', JSON.stringify(json));
	}

	clearItemData() {
		let inputs = this.inputs.fields;
		inputs.price.val(0.00);
		inputs.amount.val(0.00);
		inputs.itemid.attr('data-product', '');

		this.form.find('.product-field').each(function() {
			let textfield = $(this);
			textfield.text('');
		});
	}

	resetAmount() {
		let inputs = this.inputs.fields;
		inputs.amount.val(inputs.price.val());
	}

	updatePriceFromPricebreaks() {
		let inputs  = this.inputs.fields;
		let mrClean = Sanitizer.getInstance();
		let qty = parseFloat(mrClean.float(inputs.qty.val(), {'precision': 2}));
		let product = JSON.parse(inputs.itemid.attr('data-product'));

		if (qty == 0) {
			inputs.price.val(product.price);
			return true;
		}

		product.pricebreaks.forEach(function(pricebreak) {
			if (qty >= pricebreak.qty) {
				inputs.price.val(pricebreak.price);
			}
		});
	}

	updateAmount() {
		this.resetAmount();

		let inputs  = this.inputs.fields;
		let mrClean = Sanitizer.getInstance();
		let qty = parseFloat(mrClean.float(inputs.qty.val(), {'precision': 2}));

		if (qty == 0) {
			return true;
		}
		let price = parseFloat(mrClean.float(inputs.price.val(), {'precision': 2}));
		if (price == 0) {
			return true;
		}
		inputs.amount.val(mrClean.float(qty * price, {'precision': 2}));
		return true;
	}
}