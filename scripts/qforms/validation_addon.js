/*
 qForm JSAPI / Dan G. Switzer, II
 http://www.pengoworks.com/qforms/

 Release under GNU License
 Copyright (C) 2001  Dan G. Switzer, II

 Donations:
 http://www.pengoworks.com/qforms/donations/
*/
qFormAPI.packages.validation=true;function _f_isAtLeastOne(_f){var sFields=this.name+((typeof _f=="string")?","+_removeSpaces(_f):"");var aFields=sFields.split(","),v=new Array(),d=new Array(),x=",";for(var i=0;i<aFields.length;i++){if(!this.qForm[aFields[i]])return alert("The field name \""+aFields[i]+"\" does not exist.");v[v.length]=this.qForm[aFields[i]].getValue();if(x.indexOf(","+aFields[i]+",")==-1){d[d.length]=this.qForm[aFields[i]].description;x+=aFields[i]+","}}if(v.join("").length==0){this.error="At least one of the following fields is required:\n "+d.join(",");for(i=0;i<aFields.length;i++){if(qFormAPI.useErrorColorCoding&&this.qForm[aFields[i]].obj.style)this.qForm[aFields[i]].obj.style[qFormAPI.styleAttribute]=qFormAPI.errorColor}}};_addValidator("isAtLeastOne",_f_isAtLeastOne,true);