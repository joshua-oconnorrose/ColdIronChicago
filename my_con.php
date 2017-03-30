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
$title = CON_NAME. ":User Home";
// pageID for navigation bar
$pageID = "con_mycon";
// styleTemplate
$css_template = "default";
// get convention settings
$query = 'SELECT *'
     . ' FROM convention '
	 . ' WHERE con_id = ' .CON_ID;
	 
$get_con = run_query($query);
	$con=mysql_fetch_assoc($get_con);
	$attributes["reg_open_p"]=$con["reg_open_p"];
// Get Schedule: Code
$sql = 'SELECT g.name, s.slot_number'
        . ' FROM game g'
        . ' INNER JOIN event_schedule e ON g.game_id = e.game_id'
        . ' INNER JOIN slots s ON e.slot_id = s.slot_id'
        . ' INNER JOIN player_reg r ON e.event_id = r.event_id'
        . ' WHERE r.player_id ='. $_SESSION["player_id"] 
		. ' ORDER BY s.slot_number';
$get_events = run_query($sql);



require_once('con_start.php');
?>

<div align="center" class="heading"><?=CON_NAME?></div>
<div style="margin: 10pt;">

<?/*echo "<pre>";
print_r($_SESSION);
echo "</pre>";*/?>
<?
if (strlen($attributes["msg"])){
echo "<span class='error'>".$attributes["msg"]."</span><p />";
}
?>
Welcome to your <?=CON_NAME?> registration.
<ul>
<?php if (ListContainsNoCase($_SESSION["roleList"],"playr")) {?>
<li>Player</li>
<ul>
	<li><a href="registration.php">Update My Profile</a></li>
	<?php if ($attributes["reg_open_p"] == 1 and time() < strtotime(PREREG_CLOSED)){?>
	<li><a href="player/dsp_register.php">Register for Events</a></li>
	<?php } else {?>
	<li><a href="player/dsp_registration.php">Print my Registration</a></li>
	<?php };?>
	<li><a href="player/dsp_characters.php">Manage Characters</a></li>
</ul>
<?php };?>
<?php if (ListContainsNoCase($_SESSION["roleList"],"admin")) {?>
<li>Admin</li>
<ul>
	<li><a href="admin/dsp_tracks.php">Manage Game Schedule</a></li>
	<li><a href="admin/dsp_players.php">View Player List</a></li>
	<li><a href="admin/dsp_judges.php">View Judge List</a></li>
	<!--- <li><a href="admin/dsp_con.php">Manage Convention Pricing</a></li> --->
</ul>
<?php };?>
</ul>
</div>
<?php
require_once('con_end.php');
?>