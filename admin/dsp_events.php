<?php /*
dsp_events.php 
Created on Monday, November 03, 2003
Event Management

*/
$db_reqP = 1;
require_once('../scripts/global.php');
// security redirect
require_once('security.php');

// the following variables must be provided on the page
$title = "Manage Events";
// pageID for navigation bar
$pageID = "con_events";
// styleTemplate
$css_template = "../default";
/*action processes - maybe I should use fuse*/
if (!isset($attributes['action'])){
	$attributes['action'] = "Nothing";
}
if (!isset($attributes['track_id'])){
	$attributes['track_id'] = "0";
}
$attributes['game_action'] = "Add Game";
switch ($attributes["action"]) {
    case 'Nothing':
		break;
    case 'Add Slot':
		//validate fields
		if (strlen(trim($attributes['slot_number'])) == 0){
		$err_array[] = "<li>Number is required</li>";
		}
		if (strlen(trim($attributes['slot_date'])) == 0){
		$err_array[] = "<li>Date is required</li>";
		}
		if (strlen(trim($attributes['start_time'])) == 0){
		$err_array[] = "<li>Start Time is required</li>";
		}
		if (strlen(trim($attributes['end_time'])) == 0){
		$err_array[] = "<li>End time is required</li>";
		}
		$val = strtotime(stripdash($attributes['slot_date']));
		if ($val == -1){
		$err_array[] = "<li> ".$attributes['slot_date']." is not a valid Date</li>";
		}
		else {
		$insert_date = strftime('%Y-%m-%d',$val);
		}
		$val = strtotime(stripdash($attributes['start_time']));
		if ($val == -1){
		$err_array[] = "<li> ".$attributes['start_time']." is not a valid Time</li>";
		}
		else {
		$start_time = strftime('%H:%M',$val);
		}
		$val = strtotime(stripdash($attributes['end_time']));
		if ($val == -1){
		$err_array[] = "<li> ".$attributes['end_time']." is not a valid Time</li>";
		}
		else {
		$end_time = strftime('%H:%M',$val);
		}
		if(isset($err_array)){break;}
		// put slot infor in db
		$query = sprintf("INSERT INTO slots( con_id, slot_number, start_time, end_time, slot_date, track_id )
				VALUES
				(%s,%s,%s,%s,%s,%s)",
				CON_ID,
				GetSQLValueString($attributes['slot_number'], "int"),
				GetSQLValueString($start_time,"text"),
				GetSQLValueString($end_time,"text"),
				GetSQLValueString($insert_date, "date"),
				GetSQLValueString($attributes['track_id'], "int"));
		$insertSlot=run_query($query);
		break;
	case 'edit_game':
		$sql = 'SELECT * FROM game '
			 . ' inner join event_type on game.type = event_type.type_code'
			 . ' WHERE con_id = ' .CON_ID
			 . ' AND game_id=' .$attributes['game_id'];
		$game_data = run_query($sql);
		$game=mysql_fetch_assoc($game_data);
		$attributes["name"]=$game["name"];
		$attributes["game_code"]=$game["game_code"];
		$attributes["author"]=$game["author"];
		$attributes["description"]=$game["description"];
		$attributes["type"]=$game["type"];
		$attributes["top_apl"]=$game["top_apl"];
		$attributes["bottom_apl"]=$game["bottom_apl"];
		$attributes["slot_length"]=$game["slot_length"];
		$attributes["game_action"]="Update Game";
		break;
	case 'Add Game':
		if (strlen(trim($attributes['name'])) == 0){
		$err_array[] = "<li>Name is required</li>";
		}
		if (strlen(trim($attributes['description'])) == 0){
		$err_array[] = "<li>Description is required</li>";
		}
		if(isset($err_array)){
		break;
		}
		$query = sprintf("INSERT INTO game(con_id, game_code, author, slot_length ,name,description,type,top_apl,bottom_apl )
				VALUES
				(%s,%s,%s,%s,%s,%s,%s,%s,%s)",
				GetSQLValueString(CON_ID, "int"),
				GetSQLValueString($attributes['game_code'], "text"),
				GetSQLValueString($attributes['author'], "text"),
				GetSQLValueString($attributes['slot_length'], "text"),
				GetSQLValueString($attributes['name'], "text"),
				GetSQLValueString($attributes['description'],"text"),
				GetSQLValueString($attributes['type'],"text"),
				GetSQLValueString($attributes['top_apl'], "int"),
				GetSQLValueString($attributes['bottom_apl'], "int"));
		$insert_slot=run_query($query);
		$attributes['game_code']='';
		$attributes['author']='';
		$attributes['slot_length']='';
		$attributes['name']='';
		$attributes['description']='';
		$attributes['type']='';
		$attributes['top_apl']='';
		$attributes['bottom_apl']='';
		break;
	case 'Update Game':
		if (strlen(trim($attributes['name'])) == 0){
		$err_array[] = "<li>Name is required</li>";
		}
		if (strlen(trim($attributes['description'])) == 0){
		$err_array[] = "<li>Description is required</li>";
		}
		if(isset($err_array)){
		break;
		}
		$query = sprintf(("UPDATE game set con_id=%s, game_code=%s, author=%s, slot_length=%s ,name=%s,description=%s,type=%s,top_apl=%s,bottom_apl=%s 
				WHERE game_id = %s"),
				GetSQLValueString(CON_ID, "int"),
				GetSQLValueString($attributes['game_code'], "text"),
				GetSQLValueString($attributes['author'], "text"),
				GetSQLValueString($attributes['slot_length'], "text"),
				GetSQLValueString($attributes['name'], "text"),
				GetSQLValueString($attributes['description'],"text"),
				GetSQLValueString($attributes['type'],"text"),
				GetSQLValueString($attributes['top_apl'], "int"),
				GetSQLValueString($attributes['bottom_apl'], "int"),
				GetSQLValueString($attributes['game_id'], "int"));
		$update_game=run_query($query);
		$attributes["name"]="";
		$attributes["game_code"]="";
		$attributes["author"]="";
		$attributes["description"]="";
		$attributes["type"]="";
		$attributes["top_apl"]="";
		$attributes["bottom_apl"]="";
		$attributes["slot_length"]="";
		break;
	case 'Add':
	//make sure game isn't already in that slot
		$sql = 'SELECT * '
        . ' FROM `event_schedule` '
        . ' WHERE game_id = '.$attributes['game_id'] 
				. ' AND slot_id ='.$attributes['slot_id'] ;
		$check_slot = run_query($sql);
		$check = mysql_num_rows($check_slot); 
		if ($check) {
		$err_array[] = "<li>That game is already scheduled to run in that slot</li>";
		}
		if ($attributes['game_id']==0) {
		break;
		}
		if(isset($err_array)){
		break;
		}
		$query = sprintf("INSERT INTO event_schedule( con_id, slot_id, game_id)
				VALUES
				(%s,%s,%s)",
				CON_ID,
				GetSQLValueString($attributes['slot_id'], "int"),
				GetSQLValueString($attributes['game_id'], "int"));
		$insert_event=run_query($query);
		break;
	case 'delete_event':
		$query = 'DELETE'
		     . ' FROM player_reg '
			 . ' WHERE event_id = ' .$attributes['event_id'];
		$delete_slot = run_query($query);
		if(isset($err_array)){
		break;
		}
		$query = sprintf("DELETE from event_schedule where event_id = %s",GetSQLValueString($attributes['event_id'], "int"));
		$delete_event=run_query($query);
		break;
	case 'delete_slot':
		$query = 'SELECT *'
		     . ' FROM event_schedule '
			 . ' WHERE slot_id = ' .$attributes['slot_id'];
		$check_slot = run_query($query);
		$check = mysql_num_rows($check_slot); 
		if ($check) {
		$err_array[] = "<li>You must delete all events from this slot to delete the slot</li>";
		}
		if(isset($err_array)){
		break;
		}
		$query = sprintf("DELETE from slots where slot_id = %s",
				GetSQLValueString($attributes['slot_id'], "int"));
		$delete_event=run_query($query);
		break;
	case 'import slots':
				$query = sprintf("insert into slots (con_id,slot_number,start_time,end_time,slot_date,track_id)
				select thisCon.con_id as con_id, 
				slot_number,
				start_time,
				end_time,
				DATE_ADD(importSlots.slot_date,INTERVAL datediff(thisCon.Start_Date,importCon.start_date) DAY) as slot_date,
				%s --track id
				from convention as thisCon
				join convention as importCon
				join slots as importSlots on importCon.con_id = importSlots.con_id
				where thisCon.con_id = %s --thisconID
				and importCon.con_id = %s --importConID",
				GetSQLValueString($attributes['track_id'], "int"),
				CON_ID,
				GetSQLValueString($attributes['importConID'], "int")); 
		break;
    default:
        print "case fell through on dsp_events.php";
		exit();
}

// get display data
$query = 'SELECT slot_number, start_time, end_time, slot_date,slot_id'
     . ' FROM slots '
	 . ' WHERE con_id = ' .CON_ID
	 . ' AND track_id = ' .$attributes['track_id']
	 . ' order by slot_number';
$get_slots = run_query($query);

$query =  'Select 1 from track_type where track_id ='.$attributes['track_id'];
$trackCheck = run_query($query);

$sql = 'SELECT * FROM game '
	 . ' INNER JOIN event_type on game.type = event_type.type_code';
if (mysql_num_rows($trackCheck)==1){
$sql = $sql . ' INNER JOIN track_type on game.type = track_type.type_code'
	. ' where track_id =' .$attributes['track_id'];
}
	 //. ' WHERE con_id = ' .CON_ID
$sql = $sql	 . ' order by type_order,game_code, name';
$game_list = run_query($sql);

$sql = "SELECT * FROM event_type ";
if (mysql_num_rows($trackCheck)==1){
$sql = $sql . ' INNER JOIN track_type on event_type.type_code = track_type.type_code'
	. ' where track_id =' .$attributes['track_id'];
}
$sql = $sql	 . '  order by type_order';
$type_list = run_query($sql);

$sql = 'SELECT g.name, e.event_id, s.slot_id'
        . ' FROM `event_schedule` e'
        . ' INNER JOIN game g ON e.game_id = g.game_id'
        . ' INNER JOIN slots s ON e.slot_id = s.slot_id'
	 . ' WHERE s.con_id = ' .CON_ID. ' order by g.type, g.name';
$event_list = run_query($sql);


if (mysql_num_rows($get_slots)==0) {
     
    }

require_once('con_start.php');
?>

<div align="center" class="heading"><?=CON_NAME?> - Event Management</div>
<div style="margin: 10pt;">

<?=CON_NAME?>: Events:
<?php 
display_errors($err_array);
?>

<table border="0" cellspacing="1" cellpadding="1">
<tr>
	<form action="dsp_events.php" method="post">
	<td valign="top" class="normal">
	<!--- Add Slot Form --->
	<table border="0" cellspacing="1" cellpadding="1">
	<tr>
		<td colspan="2" align="center" class="normal"><strong>Add a Slot</strong></td>
	</tr>
	<tr>
		<td nowrap class="normal">Slot Number:</td>
		<td class="normal"><input type="Text" name="slot_number" size="2"></td>
	</tr>
	<tr>
		<td nowrap class="normal">Slot Date:</td>
		<td class="normal"><input type="Text" name="slot_date" size="10"></td>
	</tr>
	<tr>
		<td nowrap class="normal">Slot Start Time:</td>
		<td class="normal"><input type="Text" name="start_time" size="10"></td>
	</tr>
	<tr>
		<td nowrap class="normal">Slot End Time:</td>
		<td class="normal"><input type="Text" name="end_time" size="10"></td>
	</tr>
	<tr>
		<td colspan="2" align="center" nowrap class="normal"><input type="submit" name="action" value="Add Slot" class="submit"></td>
	</tr>
	
			<input type="hidden" name="track_id" value="<?php echo $attributes['track_id'] ?>" />
	</form>
	<tr>
		<td colspan="2">
		<strong>Add Events</strong>
		<table>
<?php 
	if (mysql_num_rows($get_slots)==0) {
	        echo "<tr><td colspan='2'><br /><span class='error'>No Slot's added, Add slots below to schedule a game</span></td></tr>";
	    }
	else{
		while ($slot_row = mysql_fetch_assoc($get_slots)){
		?>
		<tr>
		<td colspan="2" class="note" nowrap>Slot <?=$slot_row["slot_number"]." ".date("F j, Y",strtotime($slot_row["slot_date"]))." ".date("g:i a",strtotime($slot_row["start_time"]))."-".date("g:i a",strtotime($slot_row["end_time"]))?> <a href="dsp_events.php?action=delete_slot&slot_id=<?echo $slot_row['slot_id']?>&track_id=<?echo $attributes["track_id"]?>" class="note">delete</a><td>
		</tr>
		<tr>
		<td colspan="2" class="note">
        <?php 
    		//resets data pointer to beginning
    		if (mysql_num_rows($event_list)){
					  mysql_data_seek($event_list,0);
						}
        while ($event_row = mysql_fetch_assoc($event_list)) {
          if ($event_row["slot_id"] == $slot_row["slot_id"]){
					echo "<li><a href=\"dsp_events.php?action=delete_event&track_id=".$attributes["track_id"]."&event_id=" . $event_row["event_id"]."\" class=\"note\" onclick='return confirm(\"Deleting this event will remove all registered players\\nAre you sure you want to delete this event?\")'>delete</a> ".$event_row["name"]. "</li>";
					}
        }
        ?>
			<td> 
		</tr>
		<tr>
		<form action="dsp_events.php" method="post">
		<td colspan="2" class="note">
    	<select name="game_id">
        <?php 
    		//resets data pointer to beginning
    		mysql_data_seek($game_list,0);
        echo ("<option value=0>Select Game Below</option>\n");
        while ($game_row = mysql_fetch_assoc($game_list)) {
          echo ("<option value=".$game_row["game_id"].">".$game_row["game_code"]. (strlen(trim($game_row["game_code"]))?" : ":"") .$game_row["name"]."</option>\n");
        }
        ?>
      </select>
			<input type="hidden" name="track_id" value="<?php echo $attributes['track_id'] ?>" />
			<input type="hidden" name="slot_id" value="<?php echo $slot_row['slot_id'] ?>" />
			<input type="submit" name="action" value="Add" class="submit" style="font-size:7pt; text-align: center; border-width: 1;" />
		<td>
		</form>
		</tr>
		<?php }?>
		<?php }?>
	</table>
		
		</td>
	</tr>
	</table>
	</form>
	</td>
	<td valign="top" class="normal">
	<!--- Add Game Form --->
	<form action="dsp_events.php" method="post"><table border="0" cellspacing="1" cellpadding="1">
	<tr>
		<td colspan="2" align="center" class="normal"><strong><?=$attributes["game_action"]?></strong></td>
	</tr>
	<?php if($attributes["game_action"]=='Update Game'){?>
	<tr>
		<td colspan="2" align="right"><a  class="note" href="dsp_events.php">Add a Game</a></td>
	</tr>
	<?php }?>
	<tr>
		<td class="normal">Code:</td>
		<td class="normal"><input type="Text" name="game_code" size="30" value=<?=stripslashes($attributes["game_code"])?>></td>
	</tr>
	<tr>
		<td class="normal">Name:</td>
		<td class="normal"><input type="Text" name="name" size="30" value="<?=stripslashes($attributes["name"])?>"></td>
	</tr>
	<tr valign="top">
		<td class="normal">Author</td>
		<td class="normal"><input type="Text" name="author" size="30" value="<?=stripslashes($attributes["author"])?>"></td>
	</tr>
	<tr>
		<td class="note">&nbsp;</td>
		<td class="note">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2" class="normal">Description:</td>
	</tr>
	<tr>
		<td colspan="2" class="normal"><textarea cols="45" rows="5" name="description"><?=stripslashes($attributes["description"])?></textarea></td>
	</tr>
	<tr>
		<td class="normal">Type:</td>
		<td class="normal">
    	<select name="type">
        <?php 
    		//resets data pointer to beginning
    		mysql_data_seek($type_list,0);
        while ($type_row = mysql_fetch_assoc($type_list)) {
          echo ("<option value=".$type_row["type_code"]." ".(($attributes["type"] ==$type_row["type_code"])?"SELECTED":"") .">" .$type_row["type_name"]. "</option>\n");
        }
        ?>
      </select>
	  </td>
	</tr>
	<tr>
		<td colspan="2" class="normal">Start APL:<input type="Text" name="bottom_apl" size="2" value=<?=$attributes["bottom_apl"]?>> End APL:<input type="Text" name="top_apl" size="2" value=<?=$attributes["top_apl"]?>></td>
	</tr>
	<tr>
		<td class="normal" colspan="2">Slot Length:<input type="Text" name="slot_length" size="10" value=<?=$attributes["slot_length"]?>></td>
	</tr>
	<tr>
		<td colspan="2" class="note" align="center">(Number of slots this runs)</td>
	</tr>
	<tr>
		<td colspan="2" align="center" class="normal"><input type="submit" name="action" value="<?=$attributes["game_action"]?>" class="submit"></td>
	</tr>
		<?php 
		if ($attributes["action"] == "edit_game"){?>
			<input type="hidden" name="game_id" value="<?php echo $attributes['game_id'] ?>" />
		<?php }?>
			<input type="hidden" name="track_id" value="<?php echo $attributes['track_id'] ?>" />
	</form>
	<tr>
		<td colspan="2">
	<table border="0" cellspacing="0" cellpadding="1">
<tr>
	<td class="normal"><b>Game List</b></td>
</tr>
<?php if (mysql_num_rows($game_list)){ ?>
<tr>
	<td class="normal" align="center">Select Game to edit</td>
</tr>
<tr>
	<td class="normal" valign="top">
		<?php 
    		//resets data pointer to beginning
    		mysql_data_seek($game_list,0);
        while ($game_row = mysql_fetch_assoc($game_list)) {
		if ($game_row["con_id"] == CON_ID){
          echo "<a class=\"normal\" href=\"dsp_events.php?action=edit_game&game_id=".$game_row["game_id"]."&track_id=".$attributes["track_id"]."\">".$game_row["game_code"]. (strlen(trim($game_row["game_code"]))?" : ":"") .$game_row["name"]."</a><br>";
		  }
        }
        ?>
	</td>
</tr>
<?php }?>
</table>
		</td>
	</tr>
	</table>
	</td>
</tr>
<tr>
</table>
<?php
require_once('con_end.php');
?>