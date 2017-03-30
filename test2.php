<?php 
// if there are any variables sent from forms, extract them into $var format 
// while removing any HTML entities. 
if ($_REQUEST) { extract(htmlentities(stripslashes($_REQUEST),ENT_NOQUOTES)); } 
else {
  if ($_GET) { extract(htmlentities(stripslashes($_GET),ENT_NOQUOTES)); } 
  else if ($HTTP_GET_VARS) { extract(htmlentities(stripslashes($HTTP_GET_VARS),ENT_NOQUOTES)); }
  if ($_POST) { extract(htmlentities(stripslashes($_POST),ENT_NOQUOTES)); } 
  else if ($HTTP_POST_VARS) { extract(htmlentities(stripslashes($HTTP_POST_VARS),ENT_NOQUOTES)); }

  if ($_COOKIE) { extract(htmlentities(stripslashes($_COOKIE),ENT_NOQUOTES)); }
  else if ($HTTP_COOKIE_VARS) {
extract(htmlentities(stripslashes($HTTP_COOKIE_VARS),ENT_NOQUOTES)); } 
}

// extract Environment, Server and Session variables 
if ($_ENV) { extract($_ENV); } 
  else if ($HTTP_ENV_VARS)  { extract($HTTP_ENV_VARS); }
if ($_SERVER) { extract($_SERVER); }
  else if ($HTTP_SERVER_VARS)  { extract($HTTP_SERVER_VARS); }
if ($_SESSION) { extract($_SESSION); }
  else if ($HTTP_SESSION_VARS)  { extract($HTTP_SESSION_VARS); }

 ?>