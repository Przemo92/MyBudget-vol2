Date.prototype.toDateInputValue = (function() {
	var local = new Date(this);
	local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
	return local.toJSON().slice(0,10);
});

document.getElementById('datePicker').value = new Date().toDateInputValue();

function appearComment() {
  // Get the checkbox
  var checkBox = document.getElementById("myCheck");
  // Get the output text
  var text = document.getElementById("komentarz");

  // If the checkbox is checked, display the output text
  if (checkBox.checked == true){
    komentarz.style.display = "block";
	} 	else {
		komentarz.style.display = "none";
	}
}