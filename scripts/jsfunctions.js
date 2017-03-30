
function showdiv(obj){
if(typeof obj == "string")
		obj = document.getElementById(obj);
	    if(obj.style.visibility != 'hidden'){
			obj.style.visibility = 'hidden';
			obj.style.lineHeight = '0pt';
		}
		else { 
			obj.style.visibility = 'visible';
			obj.style.lineHeight = '12pt';
		}
}
		
function isChecked(object,message) {
    if (object.checked) return true;
    else 
	alert(message);
	return false;
}


