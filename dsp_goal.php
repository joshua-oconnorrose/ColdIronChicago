<?php 
// require dbconnect?
$db_reqP = 1;
require_once('scripts/global.php');
/*
my_con.php
Home page for logged in users
Monday, November 03, 2003
*/

if (!isSet($_SESSION["player_id"]))
	{
		$location = "../index.php";
		header("Location: ".$location);
		exit();
	}
	
// the following variables must be provided on the page
$title = CON_NAME. ":Show Goals";
// pageID for navigation bar
$pageID = "con_mycon";
// styleTemplate
$css_template = "default";
// get convention settings

		$sql = 'SELECT * FROM characters '
			 . ' WHERE character_id=' .$attributes['character_id'];
		$game_data = run_query($sql);
		$game=mysql_fetch_assoc($game_data);
		$attributes["character_name"]=$game["character_name"];
		$attributes["character_level"]=$game["character_level"];
		$attributes["class_summary"]=$game["class_summary"];
		$attributes["char_goals"]=$game["char_goals"];
		$attributes['sub_action'] = "Update Character";
		$formattedString = str_replace("\n","<br>",$game["char_goals"]);

require_once('con_start.php');
?>

<div align="center" class="heading"><?=CON_NAME?></div>
<div style="margin: 10pt;">
<h3> <?=$attributes["character_name"]?> Goals</h3>
<p>
<?=$formattedString ?>
</p>
</div>
<?php
require_once('con_end.php');
?>