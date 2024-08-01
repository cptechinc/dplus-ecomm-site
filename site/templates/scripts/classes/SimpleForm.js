class SimpleInputs {
	constructor(id) {
		this.id   = id;
		this.fields = {

		};
		this.init();
	}

	init() {
		this.form = $('#' + this.id);
		let inputs = this;

		this.form.find('input,select').each(function(index) {
			let input = $(this);
			let fieldname = input.attr('name');
			inputs.fields[fieldname.toLowerCase()] = input;
		});
	}
}

class SimpleForm extends Form {
	constructor(id) {
		super();
		this.id = id;
		
		this.inputs = new SimpleInputs(this.id);
		this.init();
	}

	init() {
		this.form = $('#' + this.id);
		this.inputs.id = this.id;
		this.inputs.init();
	}

	serializeForm(serialize = true) {
		if (serialize == false) {
			this.form.attr('data-serialized', '');
			return true;
		}
		this.form.attr('data-serialized', this.form.serialize());
	}
}


