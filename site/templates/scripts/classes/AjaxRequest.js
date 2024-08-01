class AjaxRequest {

	constructor(url) {
		this.url = url;
		this.method = 'GET';
		this.data = {};
		this.dataType = 'json';
		this.async    = true;
	}

	setData(data) {
		this.data = data;
	}

	setMethod(method) {
		this.method = method;
	}

	setAsync(async) {
		this.async = async;
	}

	request(callback) {
		let url = this.url;

		$.ajax({
			url: url,
			method: this.method,
			async: this.async,
			beforeSend: function(xhr) {
				if (this.method) {
					xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
				}
			},
			data: this.data,
			dataType: this.dataType,
			success: function(json) {
				callback(json);
			},
			error: function(xhr){
			},
		});
	}

	requestJson(callback) {
		this.request(function(response) {
			if (typeof response === 'object' || Array.isArray(response)) {
				callback(response);
				return true;
			}
			callback({'error': true, 'success': false, 'msg': 'No Response'});
			return true;
		});
	}
}