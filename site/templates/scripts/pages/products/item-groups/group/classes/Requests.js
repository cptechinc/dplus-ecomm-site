class ProductsRequests extends AbstractRequests {
	static instance = null;

	static getInstance() {
		if (this.instance === null) {
			this.instance = new ProductsRequests();
		}
		return this.instance;
	}
}
