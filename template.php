<?php 
/*
template.php 
Created on 3/26/03
design for the overall look and feel 
of the chicagoJudges site

4/22/03
Changed for Worlds of Play
*/

# the following variables must be provided on the page
$title = "Worlds Of Play Template";
# pageID for navigation bar
$pageID = "con_template";
# require dbconnect?
$db_reqP = 0;
# styleTemplate
$css_template = "default";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title><?= $title?></title>
	<link rel="STYLESHEET" type="text/css" href="<?= $css_template?>.css">
</head>
<body class="normal">
<div align="center">
<!--- page frame --->
<table width="600" cellspacing="0" cellpadding="0">
<tr><td height="50">&nbsp;</td></tr>
<tr>
<table width="603" border="0pt" cellspacing="0" cellpadding="0">
<tr>
	<td colspan="5" bgcolor="#000000"><img src="images/transparent.gif" width="1" height="1" alt="" border="0"></td>
</tr>
<tr>
	<td width="1" bgcolor="#000000"><img src="images/transparent.gif" width="1" height="1" alt="" border="0"></td>
	<!--- logo homepage link cell --->
	<td class="logo" width="100" align="Center">logo</td>
	<td width="1" bgcolor="#000000"><img src="images/transparent.gif" width="1" height="1" alt="" border="0"></td>
	<!--- Site Name Cell --->
	<td class="title" width="500" align="center">Chicago Judges</td>
	<td width="1" bgcolor="#000000"><img src="images/transparent.gif" width="1" height="1" alt="" border="0"></td>
</tr>
<tr>
	<td bgcolor="#000000"><img src="images/transparent.gif" width="1" height="1" alt="" border="0"></td>
	<td><img src="images/transparent.gif" width="1" height="1" alt="" border="0"></td>
	<td colspan="3" bgcolor="#000000"><img src="images/transparent.gif" width="1" height="1" alt="" border="0"></td>
</tr>
<tr>
	<td width="1" bgcolor="#000000"><img src="images/transparent.gif" width="1" height="1" alt="" border="0"></td>
	<!--- spacer --->
	<td>&nbsp;
	</td>
	<td width="1" bgcolor="#000000"><img src="images/transparent.gif" width="1" height="1" alt="" border="0"></td>
	<!--- Page Title and DateTime Cell --->
	<td class="note" align="right"><?="&gt;&gt;" .$title . " || " . date("F j, Y, g:i a")?></td>
	<td width="1" bgcolor="#000000"><img src="images/transparent.gif" width="1" height="1" alt="" border="0"></td>
</tr>
<tr>
	<td colspan="5" bgcolor="#000000"><img src="images/transparent.gif" width="1" height="1" alt="" border="0"></td>
</tr>
<tr>
	<!--- navigation cell --->
	<td width="1" bgcolor="#000000"><img src="images/transparent.gif" width="1" height="1" alt="" border="0"></td>
	<td bgcolor="#CCCCFF">
	<!--- navigation template goes here --->
	</td>
	<td width="1" bgcolor="#000000"><img src="images/transparent.gif" width="1" height="1" alt="" border="0"></td>
	<!--- body goes here current with of body =455 --->
	<td class="normal" valign="top">
	<div align="center">
	<table>
	<tr><td>
	
	</td></tr>
	</table>
	</div>
	</td>
	<td width="1" bgcolor="#000000"><img src="images/transparent.gif" width="1" height="1" alt="" border="0"></td>
</tr>
<tr>
	<td width="1" bgcolor="#000000"><img src="images/transparent.gif" width="1" height="1" alt="" border="0"></td>
	<td bgcolor="#CCCCFF">&nbsp;</td>
	<td width="1" bgcolor="#000000"><img src="images/transparent.gif" width="1" height="1" alt="" border="0"></td>
	<td align="right" class="note">
	&copy; Joshua O'Connor-Rose 2003
	<!--- footer goes here --->
	</td>
	<td width="1" bgcolor="#000000"><img src="images/transparent.gif" width="1" height="1" alt="" border="0"></td>
</tr>
<tr>
	<td colspan="5" bgcolor="#000000"><img src="images/transparent.gif" width="1" height="1" alt="" border="0"></td>
</tr>
</table>

</td>
</tr>
</table>
</div>
<!--- end page frame --->
</body>
</html>
