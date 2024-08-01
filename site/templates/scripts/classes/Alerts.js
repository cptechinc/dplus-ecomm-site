class Alerts {
	static instance = null;

	static getInstance() {
		if (this.instance === null) {
			this.instance = new Alerts();
		}
		return this.instance;
	}

	unsavedChanges(callback) {
		swal2.fire({
			title: 'Changes have occurred!',
			text: 'Do you want to save?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: '<i class="fa fa-floppy-o" aria-hidden="true"></i> Yes',
			cancelButtonText: 'No',
		}).then((result) => {
			if (result.value) {
				callback(true);
				return true;
			} else if (result.dismiss === Swal.DismissReason.cancel) {
				callback(false);
				return false;
			}
		});
	}

	delete(callback) {
		swal2.fire({
			title: 'Confirm Deletion',
			text: "Are you sure you want to delete?",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonClass: 'btn btn-success',
			cancelButtonClass: 'btn btn-danger'
		}).then((result) => {
			if (result.value) {
				callback(true);
				return true;
			} else if (result.dismiss === Swal.DismissReason.cancel) {
				callback(false);
				return false;
			}
		});
	}

	recordIsLocked(callback) {
		swal2.fire({
			title: 'Record is Locked',
			text: 'Another user is locking this record',
			icon: 'warning',
			confirmButtonText: 'Ok',
		}).then((result) => {
			callback(result.value);
		});
	}
}
