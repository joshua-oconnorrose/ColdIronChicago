<?php 
// require dbconnect?
$db_reqP = 1;
require_once('scripts/global.php');
/*
dsp_eventdetails.php 
author Joshua O'Connor-Rose joshua@worldsofplay.org
Sunday, October 10, 2004

Displays The Players and Judges registered for an event
*/
// the following variables must be provided on the page
$title = CON_NAME. ":Show Players";
// pageID for navigation bar
$pageID = "con_mycon";
// styleTemplate
$css_template = "default";
if (!isset($attributes['slot_id']) && !isset($attributes['game_id'])){
// you shouldn't be here -> go somewhere eltse
  $insertGoTo = "eventgrid.php";
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
	$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
	$insertGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
 header(sprintf("Location: %s", $insertGoTo));
}

// Get Schedule: Code
$sql = 'SELECT s.slot_id,g.game_id, es.event_id, p.first_name, p.last_name, p.email_addy, p.rpga_number,  g.game_code, g.name, s.slot_number, c.character_name, c.character_level, c.class_summary, c.character_id,g.author,g.description,g.top_apl,g.bottom_apl, (SELECT count( ereg_id ) FROM player_reg WHERE character_id =4 AND player_reg.event_id = es.event_id ) AS judge_count'
        . ' FROM event_schedule es'
        . ' LEFT JOIN player_reg pr ON pr.event_id = es.event_id'
        . ' LEFT JOIN players p ON p.player_id = pr.player_id'
        . ' LEFT JOIN slots s ON s.slot_id = es.slot_id'
        . ' LEFT JOIN characters c ON c.character_id = pr.character_id'
        . ' LEFT JOIN game g ON g.game_id = es.game_id'
        . ' LEFT JOIN event_type et ON g.type = et.type_code'; 
if (isset($attributes['slot_id'])){
	$sql .= " WHERE s.slot_id=". $attributes['slot_id']
		. ' AND s.con_id =' . CON_ID;
	$sql .= " ORDER BY et.type_order,g.name,pr.ereg_id,c.character_level,p.last_name";
	$nav_query = "?slot_id=".$attributes['slot_id'];
}
if (isset($attributes['game_id'])){
	$sql .= " WHERE g.game_id=". $attributes['game_id'];
	$sql .= " ORDER BY s.slot_number,pr.ereg_id,c.character_level,p.last_name";
	$nav_query = "?game_id=".$attributes['game_id'];
}
//echo $sql;
$get_events = run_query($sql);
		$player_row = 0;


header("Content-type: application/vnd.ms-excel");
header("Content-disposition:  attachment; filename=" . CON_NAME . 
date("Y-m-d").".xls");
?>


<?/*echo "<pre>";
print_r($_SESSION);
echo "</pre>";*/?>
<?
if (strlen($attributes["msg"])){
echo "<span class='error'>".$attributes["msg"]."</span><p />";
}
if (isset($attributes['slot_id'])){
		 $show_slot_p = 1;
}

$compare_case='';
$player_html = '';
$judge_html = '';
$loopcount = 1;
?>
<table border="1" cellspacing="1" cellpadding="1">
<tr>
	<td style="height:0px;width:30px"></td>
	<td style="height:0px;width:150px"></td>
	<td style="height:0px;width:150px"></td>
	<td style="height:0px;width:200px"></td>
	<td style="height:0px;width:50px"></td>
</tr>

<?php
while ($event_row = mysql_fetch_assoc($get_events)){
	// set switches
	if($show_slot_p==1){
	$compare_case = $event_row["name"];
	$table_title = "Slot ".$event_row["slot_number"];
	$section_header = $event_row["game_code"]." ".$event_row["name"];
	}
	else{
	$compare_case = $event_row["slot_number"];
	$table_title = $event_row["name"];
	$section_header = "SLOT ".$event_row["slot_number"];
	}
	if ($compare_case != $compare_against){
		if($loopcount == 1){
			echo "<tr><td class=\"heading\" align=\"center\" colspan=\"5\">". $table_title  ."</td></tr>";
			if($show_slot_p==0){
			echo "<tr><td class=\"note\" align=\"center\" colspan=\"5\">By ". $event_row["author"]  . " APL ".$event_row["bottom_apl"]."-".$event_row["top_apl"]."</td></tr>";
			echo "<tr><td class=\"normal\" align=\"left\" colspan=\"5\"><div style=\"margin-top=0pt;margin: 10pt;\">". $event_row["description"]  ."</div></td></tr>";
			
			}
		}
		if($loopcount != 1){
			echo "<tr><td class=\"normal\" colspan=\"5\">Judges:</td></tr>";
			if(strlen(trim($judge_html))){
				echo $judge_html;}
			else{
				echo "<tr><td class=\"normal\" colspan=\"1\">&nbsp;</td><td class=\"normal\" colspan=\"4\">No Judges Registered</td></tr>";
			}
			$judge_html = '';
			$judge_row = 0;
			echo "<tr><td class=\"normal\" colspan=\"5\">Players:</td></tr>";
			
			if(strlen(trim($player_html))){
			echo "<td class=\"normal\" colspan=\"1\">&nbsp;</td>";
			echo "<td class=\"normal\" colspan=\"1\"><b>Player</b></td>";
			echo "<td class=\"normal\" colspan=\"1\"><b>Character</b></td>";
			echo "<td class=\"normal\" colspan=\"1\"><b>Class</b></td>";
			echo "<td class=\"normal\" colspan=\"1\"><b>Level</b></td>";
				echo $player_html;}
			else{
				echo "<tr><td class=\"normal\" colspan=\"1\">&nbsp;</td><td class=\"normal\" colspan=\"4\">No Players Registered</td></tr>";
			}
		$player_html = '';
		$player_row = 0;
		}
		$compare_against = $compare_case;
		if($_SESSION["logged_in"]==1){
			$register_link = "player/dsp_register.php?game_id=".$event_row["game_id"]."&slot_id=".$event_row["slot_id"]."&set_default_p=1";
		}else{
			$register_link = "registration.php";
		}
		
					if (time() < strtotime(PREREG_CLOSED)){
		?>
		 <tr>
			<?php echo "<td colspan=\"5\"><a class='normal' href='".$register_link."'><b style=\"color:blue\">$section_header</b></a></td>";?>
		</tr>
		<?php
		}
		else{
		?>
		 <tr>
			<?php echo "<td colspan=\"5\"><br><b style=\"color:blue\">$section_header</b></td>";?>
		</tr>
		<?php
		
		}
		
	}
		if($event_row["character_id"]==4){
			$judge_html .="<tr bgcolor=". ($judge_row%2?"\"#ffffff\"":"\"#ffffcc\"") .">";
			$judge_html .="<td class=\"normal\" colspan=\"1\">&nbsp;</td>";
			$judge_html .="<td class=\"normal\" colspan=\"4\">". $event_row["first_name"]. " " .$event_row["last_name"] ."</td>";
			$judge_html .="</tr>";
			$judge_row = $judge_row + 1;
			}
		elseif(isset($event_row["character_id"])){
			$player_row = $player_row+1;
			if($player_row > $event_row['judge_count']*6+18){
			$bgcolor = ($player_row%2?"\"#ff66ff\"":"\"#ff99ff\"");
			}
			else if($player_row > (($event_row['judge_count']*6)+12)){
			$bgcolor = ($player_row%2?"\"#ff66ff\"":"\"#ff99ff\"");
			}
			else if($player_row > (($event_row['judge_count']*6)+6)){
			$bgcolor = ($player_row%2?"\"#ffcc99\"":"\"#ffcccc\"");
			}
			else if($player_row > (($event_row['judge_count']*6))){
			$bgcolor = ($player_row%2?"\"#66ffcc\"":"\"#66ffff\"");
			}
			else {
			$bgcolor = ($player_row%2?"\"#ffffff\"":"\"#ffffcc\"");
			}
			$player_html.="<tr bgcolor=". $bgcolor .">";
			$player_html.="<td class=\"normal\" colspan=\"1\">&nbsp;</td>";
			$player_html.="<td class=\"normal\">".$event_row['first_name']. " " .$event_row['last_name']."</td>";
			$player_html.="<td class=\"normal\">".$event_row['character_name']."</td>";
			$player_html.="<td class=\"normal\">".$event_row['class_summary']."</td>";
			$player_html.="<td class=\"normal\">".$event_row['character_level']."</td>";
			$player_html.="</tr>";
			}
	$loopcount = $loopcount+1;
}
			echo "<tr><td class=\"normal\" colspan=\"5\">Judges:</td></tr>";
			if(strlen(trim($judge_html))){
				echo $judge_html;}
			else{
				echo "<tr><td class=\"normal\" colspan=\"1\">&nbsp;</td><td class=\"normal\" colspan=\"4\">No Judges Registered</td></tr>";
			}
			$judge_html = '';
			echo "<tr><td class=\"normal\" colspan=\"5\">Players:</td></tr>";
			if(strlen(trim($player_html))){
			echo "<td class=\"normal\" colspan=\"1\">&nbsp;</td>";
			echo "<td class=\"normal\" colspan=\"1\"><b>Player</b></td>";
			echo "<td class=\"normal\" colspan=\"1\"><b>Character</b></td>";
			echo "<td class=\"normal\" colspan=\"1\"><b>Class</b></td>";
			echo "<td class=\"normal\" colspan=\"1\"><b>Level</b></td>";
				echo $player_html;}
			else{
				echo "<tr><td class=\"normal\" colspan=\"1\">&nbsp;</td><td class=\"normal\" colspan=\"4\">No Players Registered</td></tr>";
			}

?>
</table>