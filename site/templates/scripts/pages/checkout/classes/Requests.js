class CheckoutRequests extends AbstractRequests {
	static instance = null;

	static getInstance() {
		if (this.instance === null) {
			this.instance = new CheckoutRequests();
		}
		return this.instance;
	}

	process(data, callback) {
		let ajax = new AjaxRequest(config.ajax.urls.json + 'checkout/');
		ajax.setMethod('POST');
		ajax.setData(data);
		ajax.requestJson(function(response) {
			callback(response);
		});
	}
}
