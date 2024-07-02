$(function() {
	jQuery.validator.setDefaults({
		onkeyup: false,
		errorClass: "is-invalid",
		validClass: "is-valid",
		focusInvalid: true,
		errorPlacement: function(error, element) {
			error.addClass('invalid-feedback');
	
			if (element.closest('.input-parent').length == 0) {
				error.insertAfter(element);
				return true;
			}
			error.appendTo(element.closest('.input-parent'));
		},
	});
	
});