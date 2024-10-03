$(function() {
	$("body").on('show.bs.collapse', '#paymentLinkOrders', function(e) {
		swal.fire({
			title: 'Creating Multi-order Payment Link',
			text: 'Click on add to add invoice',
			icon: 'info',
			confirmButtonText: 'Ok',
		}).then((result) => {
			callback(result.value);
		});

		$('.invoice-list .add-invoice').addClass('show');
	});


	$("body").on('click', '.invoice-list .add-invoice', function(e) {
		let button = $(this);
		let list = $('#paymentLinkOrders .selected-invoices-list')
		let example = list.find('.example');
		let copy = example.clone();
		let input = copy.find('input');
		input.attr('name', "invnbr[]");
		input.val(button.data('invnbr'));

		copy.removeClass('example');
		copy.addClass('show');
		
		copy.appendTo(list);
		button.removeClass('show');

	});

	$("body").on('click', '#paymentLinkOrders .remove-invoice', function(e) {
		let button = $(this);
		let container = button.closest('.invoice-container');
		let invnbr = container.find('input[name="invnbr[]"]').val();
		let buttonAdd = $('.invoice-list .add-invoice[data-invnbr="' + invnbr + '"]');
		container.remove();
		buttonAdd.addClass('show');
	});
});