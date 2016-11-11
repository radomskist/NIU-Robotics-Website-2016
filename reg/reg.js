function checkform() {
	/*
	author Steven Radomski
	email radomskist@yahoo.com
	Copyright (c) 2016, Steven Radomski 
	*/
	var zid = document.forms["regform"]["zid"].value;
	zid = zid.toUpperCase();
	var errors = new String("");

	if(document.forms["regform"]["password"].value != document.forms["regform"]["cpassword"].value)
		errors += "Passwords do no match\n";

	if(zid.length != 8) {
		errors += "ZID must be 8 characters long\n";
	}

	if(zid[0] != 'Z' && zid[0] != 'E') {
		errors += "ZID must begin with Z or E\n";
	}

	if(zid.length != 8 || isNaN(zid.substring(1,8))) {
		errors += "ZID can only have the first character be a letter\n";
	}

	if((document.forms["regform"]["firstname"].value.length != 0) && (!isNaN(document.forms["regform"]["firstname"].value)) || ((document.forms["regform"]["firstname"].value.length != 0) && !isNaN(document.forms["regform"]["lastname"].value))) {
		errors += "Names cannot have numbers\n";
	}

	//http://www.w3schools.com/js/tryit.asp?filename=tryjs_form_validate_email
	var emailfield = document.forms["regform"]["email"].value;
	if(emailfield.length != 0) {
		var atpos = emailfield.indexOf("@");
  		var dotpos = emailfield.lastIndexOf(".");

	    if (atpos<1 || dotpos<atpos+2) {
        	errors += "Email is invalid\n";
  		}
	}


	if(errors.length != 0) {
		alert(errors);
		return false;
	}
	return true;
}

function clienterror(errornum) {
	alert("\tALERT!\nClient side error!\n   Error code: " + errornum);

}

function success() {
	alert("Successfully Registered!");

}
