// CREATE DEFAULT SWEET ALERT
const swal = Swal.mixin({
	customClass: {
		confirmButton: 'btn btn-success mr-3',
		cancelButton: 'btn btn-danger',
		inputClass: 'form-control',
		selectClass: 'form-control',
	},
	buttonsStyling: false,
	cancelButtonText: 'No',
	confirmButtonText: 'Yes',
	focusConfirm: false,
	focusCancel: true,
	allowEnterKey: true,
	onBeforeOpen: () => {
		$('.modal').removeAttr('tabindex');
	},
	onClose: () => {
		$('.modal').attr('tabindex', '-1');
	}
});

const Toast = Swal.mixin({
	toast: true,
	position: 'top',
	showConfirmButton: false,
	timer: 3000,
	timerProgressBar: true,
	background: '#FFF',
});