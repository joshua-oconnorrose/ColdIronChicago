<?php
if (!isset($attributes['show_nav_p'])){
	$attributes['show_nav_p'] = 1;
}
if (!isset($attributes['qforms_doc_p'])){
	$attributes['qforms_doc_p'] = 0;
}
if (!isset($attributes['free_size_p'])){
	$attributes['free_size_p'] = 0;
}
if ($attributes['free_size_p'] == 0){
$inner_width_text = "width=\"750\"";
$outer_width_text = "width=\"753\"";
}
else{
	
$inner_width_text = "width=\"100%\"";
$outer_width_text = "width=\"100%\"";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title><?= $title?></title>
	<script language="javascript" type="text/javascript" src="<?= SERVER_PATH?>scripts/jsfunctions.js"></script>
	<link rel="STYLESHEET" type="text/css" href="<?= $css_template?>.css">
<?php
	if($attributes['qforms_doc_p']){?>
	<!--// load the qForm JavaScript API //-->
<SCRIPT SRC="../scripts/qforms.js"></SCRIPT>
<!--// you do not need the code below if you plan on just
       using the core qForm API methods. //-->
<!--// [start] initialize all default extension libraries  //-->
<SCRIPT LANGUAGE="JavaScript">
<!--//
// specify the path where the "/qforms/" subfolder is located
qFormAPI.setLibraryPath("../scripts/");
// loads all default libraries
qFormAPI.include("*");
//-->
</SCRIPT>
<?php }?>
</head>
<body class="normal">
<div align="center">
<!--- page frame --->
<table <?=$outer_width_text?> cellspacing="0" cellpadding="0">
<tr><td height="50">&nbsp;</td></tr>
<tr>
<table <?=$inner_width_text?> border="0pt" cellspacing="0" cellpadding="0">
<tr>
	<?php
	if ($attributes["show_nav_p"] == 1){?>
	<td colspan="1" bgcolor="#000000"><img src="<?= SERVER_PATH?>images/transparent.gif" width="1" height="1" alt="" border="0"></td>
	<td colspan="1" bgcolor="#000000"><img src="<?= SERVER_PATH?>images/transparent.gif" width="1" height="1" alt="" border="0"></td>
	<?php }?>
	<td colspan="3" bgcolor="#000000"><img src="<?= SERVER_PATH?>images/transparent.gif" width="1" height="1" alt="" border="0"></td>
</tr>
<tr>
	<?php
	if ($attributes["show_nav_p"] == 1){?>
	<td width="1" bgcolor="#000000"><img src="<?= SERVER_PATH?>images/transparent.gif" width="1" height="1" alt="" border="0"></td>
	<!--- logo homepage link cell --->
	<td class="logo" width="160" align="Center" rowspan="3">
	<div style="margin-left:10px;margin-top:10px;margin-right:10px;margin-bottom:10px;">
		<img src="<?php echo SERVER_PATH ?>images/<?php echo IMAGE_LOGO ?>"  border="0" width="100px">
	</div>
	</td>
	<?php }?>
	<td width="1" bgcolor="#000000"><img src="<?= SERVER_PATH?>images/transparent.gif" width="1" height="1" alt="" border="0"></td>
	<!--- Site Name Cell --->
	<td class="title" align="right" valign="top" style="height:100px"><?php echo CON_NAME ?></td>
	<td width="1" bgcolor="#000000"><img src="<?= SERVER_PATH?>images/transparent.gif" width="1" height="1" alt="" border="0"></td>
</tr>
<tr style="height:1px">
	<?php
	if ($attributes["show_nav_p"] == 1){?>
	<td bgcolor="#000000"><img src="<?= SERVER_PATH?>images/transparent.gif" width="1" height="1" alt="" border="0"></td>
	<?php }?>
	<td colspan="3" bgcolor="#000000"><img src="<?= SERVER_PATH?>images/transparent.gif" width="1" height="1" alt="" border="0"></td>
</tr>
<tr>
	<?php
	if ($attributes["show_nav_p"] == 1){?>
	<td width="1" bgcolor="#000000"><img src="<?= SERVER_PATH?>images/transparent.gif" width="1" height="1" alt="" border="0"></td>
	<!--- spacer --->
	<?php }?>
	<td width="1" bgcolor="#000000"><img src="<?= SERVER_PATH?>images/transparent.gif" width="1" height="1" alt="" border="0"></td>
	<!--- Page Title and DateTime Cell --->
	<td class="note" align="right"><?="&gt;&gt;" .$title . " || " . date("F j, Y, g:i a")?></td>
	<td width="1" bgcolor="#000000"><img src="<?= SERVER_PATH?>images/transparent.gif" width="1" height="1" alt="" border="0"></td>
</tr>
<tr>
	<td colspan="5" bgcolor="#000000"><img src="<?= SERVER_PATH?>images/transparent.gif" width="1" height="1" alt="" border="0"></td>
</tr>
<tr>
	<!--- navigation cell --->
	<?php
	if ($attributes["show_nav_p"] == 1){?>
	<td width="1" bgcolor="#000000"><img src="<?= SERVER_PATH?>images/transparent.gif" width="1" height="1" alt="" border="0"></td>
	<td valign="top" bgcolor="green">
	<!--- navigation template goes here --->
	<?php 
	include('con_nav.php');
	?>
	</td>
	<?php }?>
	<td width="1" bgcolor="#000000"><img src="<?= SERVER_PATH?>images/transparent.gif" width="1" height="1" alt="" border="0"></td>
	<!--- body goes here current with of body =455 --->
	<td class="body" valign="top">