//pokazywanie aktualnej daty w okienku wyboru daty
Date.prototype.toDateInputValue = (function() {
	var local = new Date(this);
	local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
	return local.toJSON().slice(0,10);
});
document.getElementById('datePicker').value = new Date().toDateInputValue();

//wyznaczanie zakresu dat do bilansu

//var today = new Date();

//var day = today.getDate();
//var month = today.getMonth()+1;
//var year = today.getFullYear();

//if(dzien<10)
//{
 // dzien="0"+dzien;
//}
//if(miesiac<10)
//{
//  miesiac="0"+miesiac;
//}


