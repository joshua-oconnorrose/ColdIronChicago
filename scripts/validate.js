/*
This is for any client side validation included on the site
endeavor to make all validation scripts universal so that no actual script is included on the page itself.
*/

function isChecked(object,message) {
    if (object.checked) return true;
    else 
	alert(message);
	return false;
}