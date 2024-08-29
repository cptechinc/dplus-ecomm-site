class Sanitizer {
	static instance = null;

	static floatOptions = {
		'precision': 0,
		'blankValue': 0
	};

	static dateOptions = {
		'format': 'mm/dd/yyyy',
		'returnInvalid': false
	};

	static getInstance() {
		if (this.instance === null) {
			this.instance = new Sanitizer();
		}
		return this.instance;
	}

	int(value) {
		let val = value.toString().trim();
		
		if (value == '') {
			return 0;
		}

		if (isNaN(val)) {
			console.log(val + ' is not a number');
			return 0;
		}
		return parseInt(val);
	}

	float(value, opts = {}) {
		let options = JSON.parse(JSON.stringify(Sanitizer.floatOptions));
		this.prescribeOptions(options, opts);

		let val = value.toString().trim();

		if (isNaN(val) || val == '') {
			return options.blankValue;
		}
		let nbr = parseFloat(val);

		if (nbr == 0) {
			return options.blankValue;
		}
		return nbr.toFixed(options.precision);
	}

	number(value, opts = {}) {
		return this.float(value, opts);
	}

	numberOrBlank(value, opts = {}) {
		opts.blankValue = '';
		return this.number(value, opts);
	}

	date(value, opts = {}) {
		let options = JSON.parse(JSON.stringify(Sanitizer.dateOptions));
		this.prescribeOptions(options, opts);

		let val = value.toString().trim();

		let date = new DateFormatter(val, options.format);
		if (date.isValid() === false) {
			if (options.returnInvalid == 'value') {
				return val;
			}
			return false;
		}
		return date.format(options.format);
	}

	dateOrBlank(value, opts = {}) {
		let val = this.date(value, opts);
		return val === false ? '' : val;
	}

	prescribeOptions(options, userOptions) {
		let keys = Object.keys(userOptions);

		keys.forEach(key => {
			if (options.hasOwnProperty(key)) {
				options[key] = userOptions[key];
			}
		});
	}

}