$(function() {
	let ajaxModal = $('#ajax-modal');

	ajaxModal.on('show.bs.modal', function (event) {
		let button = $(event.relatedTarget); // Button that triggered the modal
		let modal = $(this);
		let size = button.data('modal-size') ? button.data('modal-size') : 'xl';
		modal.attr('data-input', button.data('input'));

		modal.find('.modal-title').text(button.attr('title'));
		modal.resizeModal(size);
		modal.makeScrollable(button.data('modal-allow-scrollable'));
		modal.find('.modal-dialog');


		if (button.attr('data-lookupurl')) {
			modal.attr('data-url', button.attr('data-lookupurl'));
		}
		let url = button.attr('data-lookupurl') ? button.attr('data-lookupurl') : modal.attr('data-url');

		modal.find('.modal-body').loadin(url, function() {
			modal.initDatepickers();
		});
	});

	ajaxModal.on('shown.bs.modal', function (event) {
		$('#loading-modal').modal('hide');
	});

	ajaxModal.on('hidden.bs.modal', function (event) {
		let modal  = $(this);
		let input  = $(modal.attr('data-input'));
		if (input) {
			setTimeout(function(){
				input.focus();
			},100);
		}
	});

	$("body").on('click', '#ajax-modal .code-link', function(e) {
		e.preventDefault();
		let button = $(this);
		let modal  = button.closest('.modal');
		let input  = $(modal.attr('data-input'));
		input.val(button.data('code'));
		if (input.data('jqv')) {
			input.change();
		}
		modal.modal('hide');
	});

	$("body").on('click', '#ajax-modal .customer-link', function(e) {
		e.preventDefault();
		let button = $(this);
		let modal  = button.closest('.modal');
		let input  = $(modal.attr('data-input'));
		input.val(button.data('custid'));
		if (input.data('jqv')) {
			input.change();
		}
		modal.modal('hide');
	});

	$("body").on('click', '#ajax-modal .bin-link', function(e) {
		e.preventDefault();
		let button = $(this);
		let modal  = button.closest('.modal');
		let input  = $(modal.attr('data-input'));
		input.val(button.data('binid'));
		if (input.data('jqv')) {
			input.change();
		}
		modal.modal('hide');
	});

	$("body").on('click', '#ajax-modal .item-link', function(e) {
		e.preventDefault();
		let button = $(this);
		let modal  = button.closest('.modal');
		let input  = $(modal.attr('data-input'));
		input.val(button.data('itemid'));
		if (input.data('jqv')) {
			input.change();
		}
		modal.modal('hide');
	});

	$("body").on('click', '#ajax-modal .id-link', function(e) {
		e.preventDefault();
		let button = $(this);
		let modal  = button.closest('.modal');
		let input  = $(modal.attr('data-input'));
		input.val(button.data('id'));
		if (input.data('jqv')) {
			input.change();
		}
		modal.modal('hide');
	});

	$("body").on('submit', '#ajax-modal form', function(e) {
		e.preventDefault();
		let form  = $(this);
		let modal  = form.closest('.modal');
		let data   = form.formValues();
		let search = form.find('input[name=q]').val();
		let uri = URI(form.attr('action'));
		let uriQuery = uri.query(true);

		let query = Object.assign(uriQuery, data);
		uri.setQuery(query);
		
		modal.find('.modal-body').loadin(uri.toString(), function() {
			modal.attr('data-url', uri.toString());
			modal.initDatepickers();
		});
	});

	$("body").on('click', '#ajax-modal .paginator-link', function(e) {
		e.preventDefault();
		let button = $(this);
		let modal  = button.closest('.modal');
		modal.attr('data-url', button.attr('href'));
		modal.find('.modal-body').load(button.attr('href'));
	});

	$("body").on('click', '#ajax-modal a.clear-search', function(e) {
		e.preventDefault();
		let button = $(this);
		let modal  = button.closest('.modal');
		
		modal.find('.modal-body').loadin(button.attr('href'), function() {
			modal.attr('data-url', button.attr('href'));
			modal.initDatepickers();
		});
	});

	$("body").on('click', '#ajax-modal a.sort', function(e) {
		e.preventDefault();
		let button = $(this);
		let modal  = button.closest('.modal');
		modal.attr('data-url', button.attr('href'));
		modal.find('.modal-body').load(button.attr('href'));
	});

	$("body").on('change', '#ajax-modal select.show-description', function(e) {
		e.preventDefault();
		
		let input = $(this);
		let option = input.find('option[value=' + input.val() + ']');
		input.closest('.input-parent').find('.ajax-description').text(option.data('description'));
	});
});