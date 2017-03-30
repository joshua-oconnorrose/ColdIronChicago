<?php 
// require dbconnect?
$db_reqP = 1;
require_once('../scripts/global.php');
// security redirect
require_once('security.php');
/*
dsp_registration.php
character management
Thursday, October 14, 2004
*/
// the following variables must be provided on the page
$title = CON_NAME. ":My Registration";
// pageID for navigation bar
$pageID = "con_mycon";
// styleTemplate
$css_template = "../default";
if (isset($attributes["player_id"])){
require_once('../admin/security.php');
$show_player_id = $attributes["player_id"];
}
else{
$show_player_id = $_SESSION["player_id"];
}
$sql = 'SELECT * '
        . ' FROM players '
        . ' WHERE player_id ='.$show_player_id; 
$get_player= run_query($sql);
$userData = mysql_fetch_assoc($get_player);



/* Get Schedule */
$sql = 'SELECT (select end_time from slots where slot_number = (s.slot_number - 1 + g.slot_length) and slots.con_id =' . CON_ID .' and slots.track_id = t.track_id) as slot_end,g.name, g.slot_length, s.*, c.character_name, g.game_code, r.event_id, p.*'
        . '     FROM game g'
        . '         INNER JOIN event_schedule e ON g.game_id = e.game_id'
        . '         INNER JOIN slots s ON e.slot_id = s.slot_id'
        . '         INNER JOIN player_reg r ON e.event_id = r.event_id'
        . ' 		INNER JOIN players p ON r.player_id = p.player_id'
		. ' 		INNER JOIN track t on s.track_id = t.track_id'
        . '         INNER JOIN characters c ON r.character_id = c.character_id'
		. ' 		WHERE r.player_id ='. $show_player_id 
        . ' 		AND e.con_id =' . CON_ID
        . ' 		ORDER BY p.last_name,p.player_id,s.slot_number';
        
$get_events = run_query($sql);


$attributes["show_nav_p"]=0;

require_once('con_start.php');
?>

<div style="margin: 10pt;">

<?/*echo "<pre>";
print_r($_SESSION);
echo "</pre>";*/?>
<?
if (strlen($attributes["msg"])){
echo "<span class='error'>".$attributes["msg"]."</span><p />";
}
?>
<div align="center" class="Print" style="font-size:14pt"><strong>
<?=CON_NAME?> Registration for <?=$userData["first_name"]?> <?=$userData["last_name"]?> 
<?php
if(strlen(trim($userData["rpga_number"]))){
echo "RPGA#".$userData["rpga_number"];
}
?>
</strong></div>
<div align="center"><table border="1" cellspacing="0" cellpadding="2" bordercolor="#000000">
<tr>
	<td style="width:260px"><a class="note" href="javascript:history.back()">Back</a></td>
	<td style="width:200px"><img src="images/transparent.gif" width="1" height="1" alt="" border="0"></td>
	<td style="width:200px" align="right"><a class="note" href="../my_con.php">My <?=CON_NAME?></a></td>
</tr>
<tr>
	<td class="print"><strong>Slot</strong></td>
	<td class="print"><strong>Game</strong></td>
	<td class="print" align="right"><strong>Character</strong></td>
</tr>

<?php 
	if (mysql_num_rows($get_events)!=0) {
        while ($event_row = mysql_fetch_assoc($get_events)) {?>
<tr>
	<td class="print"><?=date("M j, Y",strtotime($event_row["slot_date"]))." ".date("g:i a",strtotime($event_row["start_time"]))."-".date("g:i a",strtotime($event_row["slot_end"]))?> </td>
	<td class="print"><?=$event_row['game_code']." ".$event_row['name']?></td>
	<td class="print" align="right"><?=$event_row['character_name']?></td>
	
</tr>
<?php }}?>
<tr><td colspan="3" align="right">
<table border="0" cellspacing="1" cellpadding="1">
<tr>
	<td class="Print" colspan=2 align="right">
		<p><strong>Cost:</strong> <br>
		<p>3 Day Weekend Badge: Register at CodCon</p>


<p>RPG Event fees: $5 per event (walk ins pay $6, please register)</p>


 <p>Preregistration is over April 3, 2017.</p>

	<!--	 Preregistration Fees:
  <p>3 Day Weekend Badge: Check at <a href="http://draxtargames.com/polar-vortex-convention/">http://draxtargames.com/polar-vortex-convention/</a></p>
<p>Pre-registration Prices: $5 per slot played. Preregistered space is given priority for a seat at an event. Walk-Ins: $6 per slot and will be seated at open tables.</p>
 
<p>Roleplaying Game Judging Benefits</p>

<p>Prizes - chosen at random but every GM typically ends up with something.</p>

<p>You will recieve a free drink/snack for every slot your GM!</p>

<p>Free con badge if you judge one slot then it is $5 off for each additional slot judged. Judge 3 and play 3 for free! Similar to how CODCON is run.</p> -->
</td>
</tr>
</table>

</td></tr>
</table></div>
</div>
<?php
require_once('con_end.php');
?>