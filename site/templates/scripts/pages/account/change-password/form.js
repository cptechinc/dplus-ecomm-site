$(function() {
	
/* =============================================================
	jQuery Validation
============================================================= */
	$('form#changePasswordForm').validate({
		rules: {
			cpassword: {
				required: true,
				equalTo: "#npassword"
			},
		},
		submitHandler: function(form) {
			form.submit();
		}
	});
});