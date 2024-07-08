$(function() {
	
/* =============================================================
	jQuery Validation
============================================================= */
	$('form#registerForm').validate({
		rules: {},
		submitHandler: function(form) {
			form.submit();
		}
	});
});