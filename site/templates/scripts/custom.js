$(function() {
	$('.datepicker').each(function(index) {
		let container = $(this);
		let val = container.find('input.date-input').val();

		container.datepicker({
			date: val,
			allowPastDates: true,
		});
	});
});

let callback = function() {
/*==============================================================
	EVENT LISTENERS
=============================================================*/
	document.querySelectorAll("input.format-phone-us").forEach(input => {
		input.addEventListener("keyup", function(evt) {
			let phoneNumber = this;
			let charCode = (evt.which) ? evt.which : evt.keyCode;
			phoneNumber.value = formatPhoneUs(phoneNumber.value);
		});
	});
}

if (document.readyState === "complete" || (document.readyState !== "loading" && !document.documentElement.doScroll) ) {
	console.log('ready');
	callback();
} else {
	document.addEventListener("DOMContentLoaded", callback);
}

/*==============================================================
	STRING FUNCTIONS
=============================================================*/

function formatPhoneUs(input) {
	// Strip all characters from the input except digits
	input = input.replace(/\D/g,'');

	// Trim the remaining input to ten characters, to preserve phone number format
	input = input.substring(0,10);

	// Based upon the length of the string, we add formatting as necessary
	let size = input.length;

	if (size == 0){
		input = input;
	} else if(size < 4){
		input = input;
	} else if(size < 7){
		input = input.substring(0,3)+'-'+input.substring(3,6);
	} else {
		input = input.substring(0,3)+'-'+input.substring(3,6)+'-'+input.substring(6,10);
	}
	return input;
}

function onlyNumeric(evt) {
	// Only ASCII character in that range allowed
	let ASCIICode = (evt.which) ? evt.which : evt.keyCode

	if (ASCIICode == 46) {
		return true;
	}

	if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57)) {
		return false;
	}
	return true;
}