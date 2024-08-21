
class AddressForm extends SimpleForm {
	copyBillingAddressToShipping() {
		let fields = ['name', 'company', 'address1', 'address2', 'city', 'state', 'zip'];
		let inputs = this.inputs.fields;

		fields.forEach((suffix) => {
			inputs['shipto' + suffix].val(inputs['billto' + suffix].val())
		});
	}

	fillShippingAddressFromJson(json) {
		let fields = ['id', 'name', 'company', 'address1', 'address2', 'city', 'state', 'zip'];
		let inputs = this.inputs.fields;

		fields.forEach((suffix) => {
			let input = inputs['shipto' + suffix];
			input.val(json[suffix]);
			
			if (input.attr('data-jqv')) {
				input.change();
			}
		});
	}
}