class PaymentForm extends SimpleForm {
	constructor(id) {
		super(id);

		this.config = {
			allowedCreditCards: Object.keys(CHECKOUT.config.allowedcardtypes)
		}
	}

	init() {
		super.init();

		let inputs = this.inputs.fields;
		inputs.cardnumber.payment('formatCardNumber');
		inputs.expiredate.payment('formatCardExpiry');
		inputs.cvc.payment('formatCardCVC');
	}

	validateCardnumber() {
		let input = this.inputs.fields.cardnumber;
		return $.payment.validateCardNumber(input.val());
	}

	validateCvc() {
		let input = this.inputs.fields.cvc;
		return $.payment.validateCardCVC(input.val());
	}

	validateExpiredate() {
		let input = this.inputs.fields.expiredate;
		let date = input.payment('cardExpiryVal');
		return $.payment.validateCardExpiry(date.month, date.year);;
	}

	getCardType() {
		let input = this.inputs.fields.cardnumber;
		return $.payment.cardType(input.val());
	}
}