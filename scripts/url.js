/*
  URL Object - Copyright 2000, 2001 Jeff Howden
  jeff@members.evolt.org - http://members.evolt.org/jeff/
*/

var qs = location.search.substring(1);
var nv = qs.split('&');
var url = new Object();
for(i = 0; i < nv.length; i++)
{
  eq = nv[i].indexOf('=');
  url[nv[i].substring(0,eq).toLowerCase()] = unescape(nv[i].substring(eq + 1));
}
