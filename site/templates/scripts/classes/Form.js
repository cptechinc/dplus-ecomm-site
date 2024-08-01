class Form {
	static instance = null;

	static getInstance() {
		if (this.instance === null) {
			this.instance = new Form();
		}
		return this.instance;
	}

	/**
	 * Set / remove Readonly attribute on input
	 * @param	{Object} input 
	 * @param	{bool}	 readonly 
	 * @returns {bool}
	 */
	setReadonly(input, readonly = true) {
		if (input.attr('name') == undefined || input.attr('name') == '') {
			return false;
		}
		if (readonly === false) {
			input.removeAttr('readonly');
			return true;
		}
		input.attr('readonly', 'readonly');
	}

	/**
	 * Set / remove disabled attribute on input
	 * @param	{Object} input 
	 * @param	{bool}	 disable
	 * @returns {bool}
	 */
	setDisabled(input, disable = true) {
		if (input.attr('name') == undefined || input.attr('name') == '') {
			return false;
		}
		if (disable === false) {
			input.removeAttr('disabled');
			return true;
		}
		input.attr('disabled', 'disabled');
	}

	/**
	 * Enable tabindex attribute on input
	 * @param	{Object} input 
	 * @returns {bool}
	 */
	enableTabindex(input) {
		if (input.attr('name') == undefined || input.attr('name') == '') {
			return false;
		}
		let tabindex = isNaN(input.attr('tabindex')) ? '' : Math.abs(parseInt(input.attr('tabindex')));
		input.attr('tabindex', tabindex);
	}

	/**
	 * Disable tabindex attribute on input
	 * @param	{Object} input 
	 * @returns {bool}
	 */
	disableTabindex(input) {
		if (input.attr('name') == undefined || input.attr('name') == '') {
			return false;
		}
		let disablePrefix = '-';

		let tabindex = isNaN(input.attr('tabindex')) ? '' : Math.abs(parseInt(input.attr('tabindex')));

		if (tabindex == '') {
			tabindex = 1;
		}
		input.attr('tabindex', disablePrefix + tabindex);
	}

	/**
	 * Enable / Disable multiple Inputs
	 * @param   {array} inputs 
	 * @param   {bool}  enable 
	 * @returns {bool}
	 */
	enableDisableInputs(inputs, enable = true) {
		let formJS = this;

		if (enable === false) {
			inputs.forEach(input => {
				formJS.setReadonly(input, true);
				formJS.disableTabindex(input);
			});
			return true;
		}
		
		inputs.forEach(input => {
			formJS.setReadonly(input, false);
			formJS.enableTabindex(input);
		});
		return true;
	}

	/**
	 * Clear multiple Inputs
	 * @param   {array} inputs 
	 * @returns {bool}
	 */
	clearSelectedInputs(inputs) {
		inputs.forEach(input => {
			input.val('');
		});
	}
}