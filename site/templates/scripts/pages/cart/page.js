$(function() {
/* =============================================================
	Events
============================================================= */
	$("body").on('change', 'input.item-qty', function(e) {
		e.preventDefault();
		let input = $(this);
		
		input.closest('form').submit();
	});
});