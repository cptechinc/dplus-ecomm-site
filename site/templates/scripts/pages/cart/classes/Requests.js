class CartRequests extends AbstractRequests {
	static instance = null;

	static getInstance() {
		if (this.instance === null) {
			this.instance = new CartRequests();
		}
		return this.instance;
	}

	getProductByItemid(itemID, callback) {
		let ajax = new AjaxRequest(config.ajax.urls.json + 'products/product/itemid/');
		ajax.setData({'itemID': itemID});
		ajax.requestJson(function(response) {
			callback(response);
		});
	}
}
