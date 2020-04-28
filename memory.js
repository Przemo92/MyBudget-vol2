//pokazywanie aktualnej daty w okienku wyboru daty
Date.prototype.toDateInputValue = (function() {
	var local = new Date(this);
	local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
	return local.toJSON().slice(0,10);
});
document.getElementById('datePicker').value = new Date().toDateInputValue();

