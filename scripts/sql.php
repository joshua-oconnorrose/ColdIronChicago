<?php
/*
name: sql functions
Joshua O'Connor-Rose 
Borrowed from what Macromedia DW generates and other things
to avoid the site from getting cumbersome only called when db_include is on.
*/

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "''";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "''";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "''";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}


function run_query($sql){
$query = mysql_query($sql);
	if (!$query) {
	echo "Could not successfully run query ($sql) from DB: " . mysql_error();
		        exit;
	}
	return $query;
}
?>