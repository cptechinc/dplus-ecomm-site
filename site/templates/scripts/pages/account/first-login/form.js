$(function() {
	
/* =============================================================
	jQuery Validation
============================================================= */
	$('form#firstLoginForm').validate({
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