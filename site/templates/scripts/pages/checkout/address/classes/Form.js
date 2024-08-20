
class AddressForm extends SimpleForm {
	copyBillingAddressToShipping() {
		let fields = ['name', 'company', 'address1', 'address2', 'city', 'state', 'zip'];
		let inputs = this.inputs.fields;

		console.log(inputs);

		fields.forEach((suffix) => {
			inputs['shipto' + suffix].val(inputs['billto' + suffix].val())
		});
	}
}