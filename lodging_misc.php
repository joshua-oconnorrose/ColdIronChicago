<?php 
// require dbconnect?
$db_reqP = 1;
require_once('scripts/global.php');
/*
lodging_misc.php 
Sunday, November 07, 2004
lodging and misc for cows or convention

Author - Joshua O'Connor-Rose

*/

// the following variables must be provided on the page
$title = CON_NAME . ": Lodging";
// pageID for navigation bar
$pageID = "con_lodging";
// styleTemplate
$css_template = "default";

require_once('con_start.php');
	if(!isset($attributes["form_action"])){
				$attributes['form_action'] = "Nothing";
		}
	if(!isset($attributes["display_fck"])){
				$attributes['display_fck'] = 0;
		}
switch ($attributes["form_action"]) {
    case 'Nothing':
		break;
	case 'edit':
			$attributes['display_fck'] = 1;
			$attributes['display_text'] = 0;
		break;
    case 'Update':
		$updateSQL = sprintf("update convention_text set lodging=%s where con_id = %s",
			GetSQLValueString(stripslashes($attributes['text']), "text"),
			GetSQLValueString(CON_ID, "int"));
			$Result1 = run_query($updateSQL);
		break;
		}

$query = 'SELECT lodging'
     . ' FROM convention_text '
	 . ' WHERE con_id = ' .CON_ID;
	$get_text = run_query($query);
	$textData = mysql_fetch_assoc($get_text);
	$attributes['text'] = $textData['lodging'];
		
	if(!isset($attributes["display_text"]))$attributes['display_text'] = 1;

$query = 'SELECT *'
     . ' FROM convention '
	 . ' WHERE con_id = ' .CON_ID;
	 
$get_con = run_query($query);
	$con=mysql_fetch_assoc($get_con);
	$attributes["reg_open_p"]=$con["reg_open_p"];
?>

<div align="center" class="heading"><?=CON_NAME?></div>

<div style="margin: 10pt;">
<? if ($attributes["reg_open_p"] == 1 || (ListContainsNoCase($_SESSION["roleList"],"admin"))) {
		if (strlen($attributes["msg"])){
		echo "<span class='error'>".$attributes["msg"]."</span><p />";
		}
		
		if (ListContainsNoCase($_SESSION["roleList"],"admin"))
			{
				include_once("fckeditor/fckeditor.php") ;
				if ($attributes['display_fck']){?>
				  <form action="lodging_misc.php" method="post">
					<?php
					$oFCKeditor = new FCKeditor('text') ;
					$oFCKeditor->BasePath = '/fckeditor/' ;
					$oFCKeditor->ToolbarSet  = 'Convention' ;
					$oFCKeditor->Value = $attributes["text"] ;
					$oFCKeditor->Width = "100%" ;
					$oFCKeditor->Height = "600" ;
					$oFCKeditor->Create() ;
					?>
			    <br>
			    <input type="submit" value="Update" name="form_action">
			  </form>
			  	<?
				}
				else{
				echo "<br><a href='lodging_misc.php?form_action=edit'>Edit This Page</a>";
				}
			}
		if ($attributes["display_text"]){
			echo $attributes["text"];
		}
	
	}
	else {
	echo "<p>We're Sorry. Registration has not yet started for " . CON_NAME. ".</p>";
	}
?>

<?php
require_once('con_end.php');
?>