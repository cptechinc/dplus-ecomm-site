$(function() {
	
/* =============================================================
	jQuery Validation
============================================================= */
	$('form#forgotPasswordForm').validate({
		rules: {},
		submitHandler: function(form) {
			form.submit();
		}
	});
});