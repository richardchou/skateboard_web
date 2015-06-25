function isValidEmail (input) {
	var emailFormat = /\S+[@]\S+(\.com|\.org|\.edu|\.net|\.mil|\.gov|\.int)/;
	if (input.value.match(emailFormat) ) {
		//document.getElementById(id).innerHTML="<span style='color:#00AF00;'> Valid e-mail format </span>";
		return true;
	}
	else {
		//document.getElementById(id).innerHTML="Invalid e-mail format, please re-enter";
		return false;
	}
}

function isValidStr (input) {
	if (input.value.length > 0) {
		//document.getElementById(id).innerHTML=validInsert;
		return true;
	}
	else {
		//document.getElementById(id).innerHTML=textInsert;
		return false;
	}
	//return true;
}

function isValidPhone (input) {
	var phoneFormat = / \(? \d{3} \)? ([-\/\.])\d{3}\1\d{4}/;
	var phoneFormat2 = /\d{10}/;
	if (input.value.match(phoneFormat) || input.value.match(phoneFormat2) ) {
		return true;
	}
	else {
		return false;
	}
	
}

function isValidCard (input) {
	var cardFormat = /[0-9]{16}/;
	if (input.value.match(cardFormat) ) {
		return true;
	}
	else {
		return false;
	}
}
