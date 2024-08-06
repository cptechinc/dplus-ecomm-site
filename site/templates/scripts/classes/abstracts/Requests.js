class AbstractRequests {
	static instance = null;

	static getInstance() {
		if (this.instance === null) {
			this.instance = new AbstractRequests();
		}
		return this.instance;
	}

	addToCart(data, callback) {
		let ajax = new AjaxRequest(config.ajax.urls.json + 'cart/add/');
		ajax.setMethod('POST');
		ajax.setData(data);
		ajax.requestJson(function(response) {
			callback(response);
		});
	}
}
