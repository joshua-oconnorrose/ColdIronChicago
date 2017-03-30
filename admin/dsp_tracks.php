<?php /*
dsp_tracks.php 
Created on Tuesday May 17, 2011
Track Management

*/
$db_reqP = 1;
require_once('../scripts/global.php');
// security redirect
require_once('security.php');

// the following variables must be provided on the page
$title = "Manage Tracks";
// pageID for navigation bar
$pageID = "con_tracks";
// styleTemplate
$css_template = "../default";
$attributes["track_action"]="Create Track";
/*action processes - maybe I should use fuse*/
if (!isset($attributes['form_action'])){
	$attributes['form_action'] = "Nothing";
}
$clearFormData = false;

switch ($attributes["form_action"]) {
    case 'Nothing':
		break;
    case 'Create Track':
		$query = sprintf(("Insert into track (con_id,track_title,sort) values (%s,%s,(select count(*) from track as this where con_id = %s)+1)"),
				GetSQLValueString(CON_ID, "int"),
				GetSQLValueString($attributes['track_title'],"text"),
				GetSQLValueString(CON_ID, "int"));
		$update_track=run_query($query);
		$newTrackID= mysql_insert_id();
		// insert track types
		foreach($attributes['trackTypeCodes'] as $val) {
		    $query = sprintf("INSERT into track_type (type_code,track_id) values (%s,%s)",
				GetSQLValueString($val, "text"),
				GetSQLValueString($newTrackID, "int"));
		$add_type=run_query($query);
		}
		$clearFormData= true;
		break;
    case 'Update Track':
		$query = sprintf(("UPDATE track set track_title=%s
				WHERE track_id = %s"),
				GetSQLValueString($attributes['track_title'],"text"),
				GetSQLValueString($attributes['track_id'], "int"));
		$update_track=run_query($query);
		// remove track_types
		$query = sprintf("DELETE from track_type where track_id = %s",
				GetSQLValueString($attributes['track_id'], "int"));
		$delete_track_type=run_query($query);
		// insert track types
		foreach($attributes['trackTypeCodes'] as $val) {
		    $query = sprintf("INSERT into track_type (track_id,type_code) values (%s,%s)",
				GetSQLValueString($attributes['track_id'], "int"),
				GetSQLValueString($val, "text"));
		$add_type=run_query($query);
		}
		$clearFormData= true;
		break;
	case 'Delete':
		$query = 'SELECT *'
		     . ' FROM slots '
			 . ' WHERE track_id  = ' .$attributes['track_id'];
		$check_slot = run_query($query);
		$check = mysql_num_rows($check_slot); 
		if ($check) {
		$err_array[] = "<li>You must delete all slots from this track to delete the track</li>";
		}
		if(isset($err_array)){
		break;
		}
		$query = sprintf("DELETE from track where track_id = %s",
				GetSQLValueString($attributes['track_id'], "int"));
		$delete_track=run_query($query);
		$query = sprintf("DELETE from track_type where track_id = %s",
				GetSQLValueString($attributes['track_id'], "int"));
		$delete_track_type=run_query($query);
		$clearFormData= true;
		break;
	case 'edit_track':
		$sql = 'SELECT * FROM track '
			 . ' WHERE con_id = ' .CON_ID
			 . ' AND track_id=' .$attributes['track_id'];
		$track_data = run_query($sql);
		$track=mysql_fetch_assoc($track_data);
		$attributes["track_title"]=$track["track_title"];
		$attributes["track_action"]="Update Track";
		$sql = 'Select type_code from track_type where track_id =' .$attributes['track_id'];
		$type_data = run_query($sql);
		while ($tcRow = mysql_fetch_assoc($type_data)){
		$arrTypeCodes[] = $tcRow['type_code'];
		}
		$attributes["trackTypeCodes"] = ArrayToList($arrTypeCodes);
		break;
	case 'move_up':
		$query = sprintf("update track set sort = sort + 1 where sort = %s - 1 and con_id = %s",
				GetSQLValueString($attributes['sort'], "int"),
				GetSQLValueString(CON_ID, "int"));
		$update_sort=run_query($query);
		$query = sprintf("update track set sort = sort - 1 where track_id = %s",
				GetSQLValueString($attributes['track_id'], "int"));
		$update_sort=run_query($query);
		break; 
	case 'move_down':
	
		$query = sprintf("update track set sort = sort - 1 where sort = %s+1 and con_id = %s",
				GetSQLValueString($attributes['sort'], "int"),
				GetSQLValueString(CON_ID, "int"));
		$update_sort=run_query($query);
		$query = sprintf("update track set sort = sort + 1 where track_id = %s",
				GetSQLValueString($attributes['track_id'], "int"));
		$update_sort=run_query($query);
		break; 
	default:
        print "case fell through on dsp_tracks.php";
		exit();
}

// get tracks data
$query = 'SELECT *'
     . ' FROM track '
	 . ' WHERE con_id = ' .CON_ID
	 . ' order by sort';
	 
$track_list = run_query($query);

$query = "Select * from event_type order by type_order";

$type_list= run_query($query);

if ($clearFormData){
$attributes['track_title']= "";
$attributes['trackTypeCodes']= "";
$attributes['track_id']= "";

}


require_once('con_start.php');


?>

<div align="center" class="heading"><?=CON_NAME?> - Manage Tracks</div>
<div style="margin: 10pt;">

<?/*echo "<pre>";
print_r($attributes);
echo "</pre>";*/?>
<?php 
display_errors($err_array);
?>
<table border="0" cellspacing="0" cellpadding="1" width="98%">
	<?php if($attributes["track_action"]=='Update Track'){?>
	<tr>
		<td colspan="2" align="right"><a  class="note" href="dsp_tracks.php">Add a Track</a></td>
	</tr>
	<?php }?>
<?php if (mysql_num_rows($track_list)){ ?>
<tr>
	<td class="normal"><b>Track List</b></td>
</tr>
<tr>
	<td class="normal" align="center">Select track to edit</td>
</tr>
<tr>
	<td class="normal" valign="top">
		<table border="0" cellpadding="1" cellspacing="1" width="98%">
		<?php 
    		//resets data pointer to beginning
    		mysql_data_seek($track_list,0);
    		$this_row = 0;
        while ($track_row = mysql_fetch_assoc($track_list)) {
        	$this_row = $this_row + 1;
          	echo "<tr>";
	        if ($this_row <> 1){
	          echo "<td><a class=\"note\" href=\"dsp_tracks.php?form_action=move_up&sort=".$track_row["sort"]."&track_id=".$track_row["track_id"]."\">Up</a></td> ";
			}
			else{echo "<td>&nbsp;</td>";}
	        if ($this_row <> mysql_num_rows($track_list)){
	          echo "<td><a class=\"note\" href=\"dsp_tracks.php?form_action=move_down&sort=".$track_row["sort"]."&track_id=".$track_row["track_id"]."\">Down</a></td> ";
			}
			else{echo "<td>&nbsp;</td>";}
			echo "<td><a class=\"normal\" href=\"dsp_tracks.php?form_action=edit_track&track_id=".$track_row["track_id"]."\">". (strlen(trim($track_row["track_title"]))?$track_row["track_title"]:"Untitled Track") ."</a></td>";
			echo "<td><a class=\"note\" href=\"dsp_tracks.php?form_action=Delete&track_id=".$track_row["track_id"]."\">Delete</a></td>";
			echo "<td><a class=\"normal\" href=\"dsp_events.php?track_id=".$track_row["track_id"]."\">Manage ".$track_row["track_title"]."</a></td>";
			echo "</tr>";
	      	
        }
        ?>
		</table>
	</td>
</tr>
<?php }?>
</table>
<form action="dsp_tracks.php" method="post">
	<table border="0" cellspacing="1" cellpadding="1" width="98%">
	<tr>
		<td colspan="2" align="center" class="normal"><strong><?=$attributes["track_action"]?></strong></td>
	</tr>
	<tr>
		<td class="normal">Track Name:</td>
		<td class="normal"><input type="Text" name="track_title" size="30" value="<?=stripslashes($attributes["track_title"])?>"></td>
	</tr>
	<tr>
		<td class="normal" colspan="2">Game Types Included<br /><span class="note">selecting none includes all types</span></td>
	
	</tr>
	<tr valign="top">
		<td class="normal">
        <?php
        	$typeCount = 0;
        	$halfWay = (int) (mysql_num_rows($type_list)/2); 
        	while ($type_row = mysql_fetch_assoc($type_list)){ 
        	$typeCount++;?>
        <input name="trackTypeCodes[]" type="checkbox" 
        	<?php echo (ListFindNoCase($attributes["trackTypeCodes"],$type_row["type_code"]))? "Checked":" ";?>
        	value="<?php echo $type_row["type_code"] ?>"><?php echo $type_row["type_name"]?></input><br />
        	<?php if($halfWay+1 == $typeCount){?></td><td class="normal"><?php }?>
        <?php } ?>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center" class="normal"><input type="submit" name="form_action" value="<?=$attributes["track_action"]?>" class="submit"></td>
	</tr>
		<?php 
		if ($attributes["track_action"] == "Update Track"){?>
			<input type="hidden" name="track_id" value="<?php echo $attributes['track_id'] ?>" />
		<?php }?>
	</table>
</form>



<?php
require_once('con_end.php');
?>