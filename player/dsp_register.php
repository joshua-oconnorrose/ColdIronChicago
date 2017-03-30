<?php 
// require dbconnect?
$db_reqP = 1;
require_once('../scripts/global.php');
// security redirect
require_once('security.php');
/*
dsp_registration.php
character management done ajaxy
Thursday, August 13 2011
*/
// the following variables must be provided on the page
$title = CON_NAME. ":My Registration";
// pageID for navigation bar
$pageID = "con_mycon";
// styleTemplate
$css_template = "../default";
$attributes["free_size_p"] = 1;
if (!isset($attributes["event_id"])){
$attributes["event_id"]=0;
}
$addSelectedEventP = 0;
$conflictEvents = "";
if (isset($attributes["player_id"])){
require_once('../admin/security.php');
$show_player_id = $attributes["player_id"];
}
else{
$show_player_id = $_SESSION["player_id"];
}


// do it 'easy' to start
if ($attributes["event_id"]<>0){
	$sql = 'SELECT (select end_time from slots where slot_number = (s.slot_number - 1 + g.slot_length) and slots.con_id = ' .CON_ID. ' and slots.track_id = s.track_id) as slot_end,'
	. ' g.name, g.slot_length, s.slot_date,s.start_time,t.track_title,e.event_id,t.char_reqP'
    . ' FROM game g'
	. ' INNER JOIN event_schedule e ON g.game_id = e.game_id'
    . ' INNER JOIN slots s ON e.slot_id = s.slot_id'
	. ' INNER JOIN track t on s.track_id = t.track_id'
	. ' WHERE e.event_id ='. $attributes["event_id"] 
	. ' Order by s.slot_date,t.track_id,s.start_time'; 
	$getThisEvent = run_query($sql);
	$sql = 'SELECT (select end_time from slots where slot_number = (s.slot_number - 1 + g.slot_length) and slots.track_id = s.track_id and slots.con_id = '.CON_ID.') as slot_end,'
			. ' g.name, g.slot_length, s.slot_date, s.start_time, c.character_name, g.game_code, r.event_id, p.player_id,t.char_reqP'
			. ' FROM game g'
			. ' INNER JOIN event_schedule e ON g.game_id = e.game_id'
			. ' INNER JOIN slots s ON e.slot_id = s.slot_id'
			. ' INNER JOIN player_reg r ON e.event_id = r.event_id'
			. ' INNER JOIN players p ON r.player_id = p.player_id'
			. ' INNER JOIN characters c ON r.character_id = c.character_id'
			. ' INNER JOIN track t on s.track_id = t.track_id'
			. ' AND e.con_id ='. CON_ID
			. ' AND p.player_id = '.$show_player_id
			. ' ORDER BY s.slot_date,s.start_time,t.track_id';
	$get_reg= run_query($sql);
	
	if(mysql_num_rows($get_reg)==0){
		$addSelectedEventP = 1;
	}
	else{
		while ($reg_row = mysql_fetch_assoc($get_reg)){
			$regStart = (float)(date("Ymd",strtotime($reg_row["slot_date"])) . str_replace(":","",$reg_row["start_time"]));
			if($reg_row["slot_end"] == "00:00"){
					$regEnd = (float)(date("Ymd",strtotime($reg_row["slot_date"])) . "2400");
				}
			else{
					$regEnd = (float)(date("Ymd",strtotime($reg_row["slot_date"])) . str_replace(":","",$reg_row["slot_end"]));
				}
			while ($thisEvent_row = mysql_fetch_assoc($getThisEvent)) {
				$eventStart = (float)(date("Ymd",strtotime($thisEvent_row["slot_date"])) . str_replace(":","",$thisEvent_row["start_time"]));
				if($thisEvent_row["slot_end"] == "00:00"){
						$eventEnd = (float)(date("Ymd",strtotime($thisEvent_row["slot_date"])) + 1 . "2400");
					}
				else{
						$eventEnd = (float)(date("Ymd",strtotime($thisEvent_row["slot_date"])) . str_replace(":","",$thisEvent_row["slot_end"]));
					}
				if(($eventEnd > $regStart && $eventEnd < $regEnd)||($eventStart >= $regStart && $eventStart < $regEnd)){
					$addSelectedEventP = 0;
				}
				else{
					$addSelectedEventP = 1;
				}
			}
		}
	}
	$sql = sprintf("select 1 from player_reg where player_id = %s and event_id = %s",GetSQLValueString($show_player_id, "int"),GetSQLValueString($attributes['event_id'], "int"));
	$get_this_reg =  run_query($sql);
	
	if($addSelectedEventP==1 && mysql_num_rows($get_this_reg)==0){
		$query = sprintf("INSERT INTO player_reg( player_id, event_id)
				VALUES
				(%s,%s)",
				GetSQLValueString($show_player_id, "int"),
				GetSQLValueString($attributes['event_id'], "int"));
		$insert_event=run_query($query);
		$query = sprintf("INSERT INTO player_reg_log( player_id, event_id)
				VALUES
				(%s,%s)",
				GetSQLValueString($show_player_id, "int"),
				GetSQLValueString($attributes['event_id'], "int"));
		$insert_log=run_query($query);
	}
}


$sql = 'SELECT (select end_time from slots where slot_number = (s.slot_number - 1 + g.slot_length) and slots.con_id = ' .CON_ID. ' and slots.track_id = s.track_id) as slot_end,'
	. ' g.name, g.slot_length, g.game_code, s.slot_date,s.start_time,t.track_title,e.event_id,t.char_reqP'
    . ' FROM game g'
	. ' INNER JOIN event_schedule e ON g.game_id = e.game_id'
    . ' INNER JOIN slots s ON e.slot_id = s.slot_id'
	. ' INNER JOIN track t on s.track_id = t.track_id'
	. ' WHERE e.con_id ='. CON_ID 
	. ' Order by s.slot_date,t.track_id,s.start_time,g.type,g.game_code'; 
$get_events= run_query($sql);


$sql = 'SELECT (select end_time from slots where slot_number = (s.slot_number - 1 + g.slot_length) and slots.track_id = s.track_id and slots.con_id = '.CON_ID.') as slot_end,'
		. ' g.name, g.slot_length, s.slot_date, s.start_time, c.character_name, g.game_code, r.event_id, p.player_id,t.char_reqP'
		. ' FROM game g'
		. ' INNER JOIN event_schedule e ON g.game_id = e.game_id'
		. ' INNER JOIN slots s ON e.slot_id = s.slot_id'
		. ' INNER JOIN player_reg r ON e.event_id = r.event_id'
		. ' INNER JOIN players p ON r.player_id = p.player_id'
		. ' INNER JOIN characters c ON r.character_id = c.character_id'
		. ' INNER JOIN track t on s.track_id = t.track_id'
		. ' AND e.con_id ='. CON_ID
		. ' AND p.player_id = '.$show_player_id
		. ' ORDER BY s.slot_date,s.start_time,t.track_id';
$get_reg= run_query($sql);


while ($reg_row = mysql_fetch_assoc($get_reg)){
	$regStart = (float)(date("Ymd",strtotime($reg_row["slot_date"])) . str_replace(":","",$reg_row["start_time"]));
	if($reg_row["slot_end"] == "00:00"){
			$regEnd = (float)(date("Ymd",strtotime($reg_row["slot_date"])) . "2400");
		}
	else{
			$regEnd = (float)(date("Ymd",strtotime($reg_row["slot_date"])) . str_replace(":","",$reg_row["slot_end"]));
		}
	mysql_data_seek($get_events,0);	
	while ($event_row = mysql_fetch_assoc($get_events)) {
		$eventStart = (float)(date("Ymd",strtotime($event_row["slot_date"])) . str_replace(":","",$event_row["start_time"]));
		if($event_row["slot_end"] == "00:00"){
				$eventEnd = (float)(date("Ymd",strtotime($event_row["slot_date"])) . "2400");
			}
		else{
				$eventEnd = (float)(date("Ymd",strtotime($event_row["slot_date"])) . str_replace(":","",$event_row["slot_end"]));
			}
		//echo $eventEnd .'>'. $regStart .'&&'. $eventEnd .'<'. $regEnd .'||'. $eventStart . '>=' . $regStart .'&&'. $eventStart .'<'.$regEnd .'<br />';
		if(($eventEnd > $regStart && $eventEnd < $regEnd)||($eventStart >= $regStart && $eventStart < $regEnd)){
				//echo("blockme<br>");
			//if(ListFind($conflictEvents,$event_row["event_id"]) <> 0){
				//echo("true<br>");
				$conflictEvents = listAppend($conflictEvents,$event_row["event_id"]);
			//}
		}
	}
}
if(mysql_num_rows($get_reg)<>0){
	mysql_data_seek($get_reg,0);
}
// Get characters
$sql = 'SELECT *'
        . ' FROM characters'
        . ' WHERE activeP = 1 and  player_id ='. $show_player_id
		. ' ORDER BY character_name';
$get_characters = run_query($sql);

require_once('con_start.php');
?>

		<link type="text/css" href="<?= SERVER_PATH?>css/start/jquery-ui-1.8.14.custom.css" rel="stylesheet" />	
		<script type="text/javascript" src="<?= SERVER_PATH?>js/jquery-1.5.1.min.js"></script>
		<script type="text/javascript" src="<?= SERVER_PATH?>js/jquery-ui-1.8.14.custom.min.js"></script>
		<script type="text/javascript" src="dsp_registration.js"></script>
		<style type="text/css">
			/*demo page css*/
			body{ font: 80.5% "Trebuchet MS", sans-serif; margin: 50px;}
			.demoHeaders { margin-top: 2em; }
			#dialog_link {padding: .4em 1em .4em 20px;text-decoration: none;position: relative;}
			#dialog_link span.ui-icon {margin: 0 5px 0 0;position: absolute;left: .2em;top: 50%;margin-top: -8px;}
			ul#icons {margin: 0; padding: 0;}
			ul#icons li {margin: 2px; position: relative; padding: 4px 0; cursor: pointer; float: left;  list-style: none;}
			ul#icons span.ui-icon {float: left; margin: 0 4px;}
			.highlight {background-color: gold ;}
			.dateList {padding-left:.1em;}
			.eventList a {cursor:pointer;}
			ul {list-style-type:none; margin: 0px; padding:2px;}
			label, input { display:block; }
			input.text { margin-bottom:12px; width:95%; padding: .4em; }
			fieldset { padding:0; border:0; margin-top:25px; }
			h1 { font-size: 1.2em; margin: .6em 0; }
			div#characters-contain { margin: 20px 0; }
			div#characters-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
			div#characters-contain table td, div#characters-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
			.ui-dialog .ui-state-error { padding: .3em; }
			
		</style>
<div id="content">
<h2>Select Your Events</h2>
<p>
	To register for an event, select the date, then the track then the event you want to register for in the date display below.<br />
	If you selected a game in which there is character selection simply select the <button style="font-size:55%" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only" role="button" aria-disabled="false" title="Update or Add Character"><span class="ui-button-icon-primary ui-icon ui-icon-person"></span><span class="ui-button-text">Update or Add Character</span></button> to add or update your character selection.
	</br>
	<a class="note" href="dsp_registration.php">Print Out My Registration</a>
</p>
<div id="eventDiv" style="float:left;width:45%;">
		<div id="list_main">
			<?php
			$thisSlotDate = "";
			$closeDateDiv = 0;
			$closeTrackDiv = 0;
			$thisTrackTitle = "";
			mysql_data_seek($get_events,0);	
			while ($event_row = mysql_fetch_assoc($get_events)) {
				if ($event_row["slot_date"]<>$thisSlotDate){
					$thisTrackTitle="";
					$thisSlotDate = $event_row["slot_date"];
					if($closeDateDiv == 1){echo "</div>";};
					$closeDateDiv = 1;
					if($closeTrackDiv == 1){echo "</ul></div>";};
					$closeTrackDiv = 0;
					?>
					<h6 class="slotDate"><a href="#"><?php echo date("m/d/Y",strtotime($thisSlotDate)) ?></a></h6>
					<div class="dateList">
				<?php }
				if ($event_row["track_title"]<>$thisTrackTitle){
					$thisTrackTitle = $event_row["track_title"];
					if($closeTrackDiv == 1){echo "</ul></div>";};
					$closeTrackDiv = 1;
					?>
					<h6 class="trackTitle"><a href="#"><?php echo $event_row["track_title"]; ?></a></h6>
					<div class="eventList">
					<var title="slotDate"><?php echo date("Ymd",strtotime($thisSlotDate)); ?></var>
					<ul>
				<?php } 
				if(! listFind($conflictEvents,$event_row["event_id"])){?>
					<li><a data-includeChar="<?php echo $event_row["char_reqP"] ; ?>" data-playerID="<?php echo $show_player_id;?>"><var title="eventID"><?php echo $event_row["event_id"] ; ?></var><span class="startTime" ><?php echo date("g:i a",strtotime($event_row["start_time"]))?></span> - <span class="endTime"><?php echo date("g:i a",strtotime($event_row["slot_end"]));?></span> <?php echo $event_row["game_code"];?> - <?php echo $event_row["name"];?></a></li>
				<?php } 
				else { ?>
				 	<li style="display: none;"><a data-includeChar="<?php echo $event_row["char_reqP"] ; ?>" data-playerID="<?php echo $show_player_id;?>"><var title="eventID"><?php echo $event_row["event_id"] ; ?></var><span class="startTime" ><?php echo date("g:i a",strtotime($event_row["start_time"]))?></span> - <span class="endTime"><?php echo date("g:i a",strtotime($event_row["slot_end"]));?></span> <?php echo $event_row["game_code"];?> - <?php echo $event_row["name"];?></a></li>
				<?php }
			}
			if($closeTrackDiv == 1){echo "</ul></div>";};
			if($closeDateDiv == 1){echo "</div>";};
			?>
		</div>
	</div>
	<div id="MySchedule" style="float:left;width:50%;margin: 5pt" data-playerID="<?php echo $show_player_id;?>">
	
			<ul id="myScheduleList">
			<strong>My Events</strong>
			<?php
			$thisSlotDate = "";
			$closeDateRow = 0;
			while ($reg_row = mysql_fetch_assoc($get_reg)) {
				if ($reg_row["slot_date"]<>$thisSlotDate){
					$thisSlotDate = $reg_row["slot_date"];
					if($closeDateRow == 1){echo "</ul></li>";};
					$closeDateRow=1;
					?>
					<li value="<?php echo date("Ymd",strtotime($thisSlotDate)); ?>"><?php echo date("m/d/Y",strtotime($thisSlotDate)) ?>
						<ul>
				<?php }?>
				<li data-eventID="<?php echo $reg_row["event_id"] ; ?>">
					<button style='font-size:55%'>Delete</button>
					<var title="eventID"><?php echo $reg_row["event_id"] ; ?></var>
					<span class="startTime" ><?php echo date("g:i a",strtotime($reg_row["start_time"])) ?></span> 
					- <span class="endTime"><?php echo date("g:i a",strtotime($reg_row["slot_end"])) ?></span> 
					<?php echo $reg_row["game_code"];?> - <?php echo $reg_row["name"] ?>
						<?php if($reg_row["character_name"] == "None Selected"){ ?>
							<span class="characterName"> <button style='font-size:55%'>Update or Add Character</button> </span>
						<?php } 
							else { ?>
							<span class="characterName"> - <?php echo $reg_row["character_name"] ?> <button style='font-size:55%'>Update or Add Character</button></span>
						<?php } ?>
					<?php //if($reg_row["char_reqP"] == 1){ ?>
					<?php //} ?>
				</li>
			<?php }
			if($closeDateRow == 1){echo "</ul></li>";};
			?>
			</ul>
	</div>
</div>
<div id="character-form" title="Select Character" data-eventID="0">
	<div id="characters-contain" class="ui-widget">
	<h1>Characters:</h1>
	<table id="characters" class="ui-widget ui-widget-content">
		<tbody>
			<tr data-characterID="4" data-playerID="<?php echo $show_player_id?>">
				<td>Judge</td>
				<td>Judge This</td>
				<td>NA</td>
				<td><button style='font-size:55%'>select</button></td>
			</tr>
			<?php while ($char_row = mysql_fetch_assoc($get_characters)) { ?>
			<tr data-characterID="<?php echo $char_row['character_id']?>" data-playerID="<?php echo $show_player_id?>">
				<td><?php echo $char_row["character_name"]?></td>
				<td><?php echo $char_row["class_summary"]?></td>
				<td><?php echo $char_row["character_level"]?></td>
				<td><button style='font-size:55%'>select</button></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
	</div>
	<form>
	<h5>Quick Add Character</h5>	
	<p class="validateTips">All form fields are required.</p>
	<fieldset>
		<label for="character_name">Name</label>
		<input type="text" name="character_name" id="character_name" class="text ui-widget-content ui-corner-all" />
		<label for="character_level">Level</label>
		<input type="text" name="character_level" id="character_level" value="" class="text ui-widget-content ui-corner-all" />
		<label for="class_summary">Class/Role</label>
		<input type="text" name="class_summary" id="class_summary" value="" class="text ui-widget-content ui-corner-all" />
		<button>Add Character</button>
	</fieldset>
	</form>
</div>

<?php
require_once('con_end.php');
?>