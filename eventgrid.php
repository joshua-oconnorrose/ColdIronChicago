<?php 
$db_reqP = 1;
require_once('scripts/global.php');
/*
eventgrid.php 
Created on Thursday, March 27, 2003
display convention event grid

4/22/03 - modified for con
*/
// the following variables must be provided on the page
$title = CON_NAME. ":Events";
// pageID for navigation bar
$pageID = "con_grid";
// styleTemplate
$css_template = "default";
// get game list




// ok so now I need to add tracks the simple solution is to run through the tracks and filter by track id so let's try simple

// get tracks data
$query = 'SELECT *'
     . ' FROM track '
	 . ' WHERE con_id = ' .CON_ID
	 . ' order by sort';
	 
$track_list = run_query($query);
$track_loop_count= 0;

while ($track_row = mysql_fetch_assoc($track_list)) {
$track_loop_count++;
$sql = 'SELECT DISTINCT g.game_code,g.name, g.type, g.bottom_apl, g.top_apl, g.game_id, et.type_name'
        . ' FROM game g'
        . ' INNER JOIN event_type et ON et.type_code = g.type'
        . ' INNER JOIN event_schedule es ON g.game_id = es.game_id'
        . ' INNER JOIN slots s ON es.slot_id = s.slot_id'
		. ' WHERE s.track_id =' . $track_row["track_id"]
		. ' AND es.con_id =' . CON_ID
        . ' ORDER BY et.type_order,g.game_code, g.name'; 
$game_list = run_query($sql);


// get slot list
$sql = 'SELECT *'
        . ' FROM slots'
		. ' WHERE con_id =' . CON_ID
		. ' AND track_id =' . $track_row["track_id"]
        . ' ORDER BY slot_number'; 
$slot_list = run_query($sql);

// get event key
$sql = 'SELECT g.game_id, s.slot_id ,slot_number, slot_length, es.event_id, count( pr.event_id ) AS player_reg'
        . ' FROM event_schedule es'
		. ' INNER JOIN slots s on s.slot_id=es.slot_id'
		. ' INNER JOIN game g on g.game_id=es.game_id'
        . ' LEFT JOIN player_reg pr ON pr.event_id = es.event_id'
        . ' LEFT JOIN characters c ON c.character_id = pr.character_id'
		//. ' WHERE g.con_id =' . CON_ID
		. ' WHERE s.con_id =' . CON_ID
		. ' AND es.con_id =' . CON_ID
		. ' AND track_id =' . $track_row["track_id"]
        . ' GROUP BY game_id, slot_number, es.event_id'; 
$event_key= run_query($sql);

// get judges
$sql = 'SELECT es.event_id, count( pr.event_id ) AS judge_reg'
        . ' FROM event_schedule es'
        . ' LEFT JOIN player_reg pr ON pr.event_id = es.event_id'
        . ' WHERE pr.character_id = 4'
        . ' GROUP BY es.event_id'; 
		
// get players
$judge_key = run_query($sql);

$sql = 'SELECT es.event_id, count( pr.event_id ) AS player_reg'
        . ' FROM event_schedule es'
        . ' LEFT JOIN player_reg pr ON pr.event_id = es.event_id'
        . ' WHERE pr.character_id <> 4'
        . ' GROUP BY es.event_id'; 
$player_key = run_query($sql);

// get display data
$query = 'SELECT *'
     . ' FROM convention '
	 . ' WHERE con_id = ' .CON_ID;
	 
$get_con = run_query($query);
	$con=mysql_fetch_assoc($get_con);
	$attributes["reg_open_p"]=$con["reg_open_p"];

$this_game_id = 0;
$this_slot_number = 0;
$attributes["show_nav_p"]=0;
$attributes["free_size_p"]=1;
if($track_loop_count == 1){
	require('con_start.php');
?>

<!-- to make the code Easier to Read I removed the display logic from the page -->

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
<?=CON_NAME?> Events List
<table border="1pt" cellspacing="0" cellpadding="1" bordercolor="Black" width="100%">
<tr>
	<td colspan="<?=(mysql_num_rows($slot_list)+1)?>">
		<table width="100%">
		<tr>
			<td class="normal"><a href="javascript:history.back()">Back</a></td>
			<?php if  ($_SESSION["logged_in"]==1){?>
			<td class="normal" align="right"><a href="my_con.php">My <?=CON_NAME?></a></td>
			<?php }?>
		</tr>
		</table>
	</td>
</tr>
</table>
<?}?>
<? if ($attributes["reg_open_p"] == 1 || (ListContainsNoCase($_SESSION["roleList"],"admin"))) {
	require('inc_grid.php');
	}
	else {
	if($track_loop_count == 1){echo "<p>We're Sorry. Registration has not yet started for " . CON_NAME. ".</p>";};
	}
}?>

</div>

<?php
require_once('con_end.php');
?>