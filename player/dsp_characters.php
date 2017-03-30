<?php 
// require dbconnect?
$db_reqP = 1;
require_once('../scripts/global.php');
// security redirect
require_once('security.php');
/*
dsp_characters.php
character management
Sunday, September 26, 2004
*/
// the following variables must be provided on the page
$title = CON_NAME. ":Manage my characters";
// pageID for navigation bar
$pageID = "con_mycon";
// styleTemplate
$css_template = "../default";
if (!isset($attributes['action'])){
	$attributes['action'] = "Nothing";
}
$attributes['sub_action'] = "Add Character";

/* Run Updates */
switch ($attributes["action"]) {
    case 'Nothing':
		break;
	case 'edit_char':
		$sql = 'SELECT * FROM characters '
			 . ' WHERE activeP = 1 and character_id=' .$attributes['character_id'];
		$game_data = run_query($sql);
		$game=mysql_fetch_assoc($game_data);
		$attributes["character_name"]=$game["character_name"];
		$attributes["character_level"]=$game["character_level"];
		$attributes["class_summary"]=$game["class_summary"];
		$attributes["char_goals"]=$game["char_goals"];
		$attributes['sub_action'] = "Update Character";
		break;
	case 'Update Character':
		if (strlen(trim($attributes['character_name'])) == 0){
		$err_array[] = "<li>Name is required</li>";
		}
		if (strlen(trim($attributes['class_summary'])) == 0){
		$err_array[] = "<li>Class is required</li>";
		}
		if(isset($err_array)){
		break;
		}
		$query = sprintf("UPDATE characters
					set character_name=%s, 
					character_level=%s, 
					class_summary=%s,
					char_goals=%s
				WHERE
					character_id = %s",
				GetSQLValueString($attributes["character_name"], "text"),
				GetSQLValueString($attributes["character_level"], "int"),
				GetSQLValueString($attributes["class_summary"], "text"),
				GetSQLValueString($attributes["char_goals"], "text"),
				GetSQLValueString($attributes["character_id"], "int"));
		$insert_slot=run_query($query);
		$attributes["character_name"]="";
		$attributes["character_level"]="";
		$attributes["class_summary"]="";
		$attributes["char_goals"]="";
		break;
	case 'Add Character':
		if (strlen(trim($attributes['character_name'])) == 0){
		$err_array[] = "<li>Name is required</li>";
		}
		if (strlen(trim($attributes['class_summary'])) == 0){
		$err_array[] = "<li>Class is required</li>";
		}
		if(isset($err_array)){
		break;
		}
		$query = sprintf("INSERT INTO characters(player_id, character_name, character_level, class_summary, char_goals)
				VALUES
				(%s,%s,%s,%s,%s)",
				GetSQLValueString($_SESSION["player_id"], "int"),
				GetSQLValueString($attributes["character_name"], "text"),
				GetSQLValueString($attributes["character_level"], "int"),
				GetSQLValueString($attributes["class_summary"], "text"),
				GetSQLValueString($attributes["char_goals"], "text"));
		$insert_slot=run_query($query);
		$attributes["character_name"]="";
		$attributes["character_level"]="";
		$attributes["class_summary"]="";
		$attributes["char_goals"]="";
		break;
	case 'delete_char':
	/*
		$query = 'SELECT *'
		     . ' FROM player_reg '
			 . ' WHERE character_id = ' .$attributes['character_id'];
		$check_slot = run_query($query);
		$check = mysql_num_rows($check_slot); 
		if ($check) {
		$err_array[] = "<li>This characters is registered for an event. Delete that slot and choose another character to delete this one.</li>";
		}
		if(isset($err_array)){
		break;
		}
		*/
		$query = sprintf("Update characters set activeP = 0 where character_id = %s",GetSQLValueString($attributes['character_id'], "int"));
		$delete_event=run_query($query);
		break;
    default:
        print "case fell through on dsp_characters.php";
		exit();
}

// Get characters
$sql = 'SELECT *'
        . ' FROM characters'
        . ' WHERE activeP = 1 and  player_id ='. $_SESSION["player_id"] 
		. ' ORDER BY character_name';
$get_characters = run_query($sql);



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
<div align="center">Welcome to your <?=CON_NAME?> characters.</div>
<?php 
	if (mysql_num_rows($get_characters)==0) {
	        echo "<span class='error'>No characters added. Use form below to add characters.</span>";
	    }
	else {
?>
<table>
<tr>
	<td class="normal">Character</td>
	<td class="normal">Class</td>
	<td class="normal">Level</td>
	<td class="normal"></td>
</tr>
<?php 
		while ($char_row = mysql_fetch_assoc($get_characters)){?>
<tr>
	<td class="normal"><a href="dsp_characters.php?action=edit_char&character_id=<?=$char_row['character_id']?>"><?php echo $char_row["character_name"]?></a></td>
	<td class="normal"><?php echo $char_row["class_summary"]?></td>
	<td class="normal"><?php echo $char_row["character_level"]?></td>
	<td class="normal"><a href="dsp_characters.php?action=edit_char&character_id=<?=$char_row['character_id']?>">Edit</a></td>
	<td class="normal"><a href="dsp_characters.php?action=delete_char&character_id=<?=$char_row['character_id']?>"  onclick="return confirm('Are you sure you want to Delete this Character?');">Delete</a></td>
</tr>
<?php }?>
</table>


<?php }?>
<?php 
display_errors($err_array);
?>
<form action="dsp_characters.php" method="post">

<div align="center"><table>
<tr>
	<td class="normal">Character Name</td>
	<td class="normal"><input type="text" name="character_name" value="<?php echo stripslashes($attributes["character_name"]) ?>" size="32"></td>
</tr>
<tr>
	<td class="normal">Character Level</td>
	<td class="normal">
	<select name="character_level">
	<?php  for ($i=0; $i<=30; $i++){
		echo "<option value=$i".(($attributes["character_level"] ==$i)?" SELECTED":"").">$i</option>";
	}
	?>
	
	</select>
	</td>
</tr>
<tr>
	<td class="normal" colspan="2">Classes</td>
</tr>
<tr>
	<td class="note" colspan="2" align="center"><span style="color=blue">Classes with levels or description of party role</span></td>
</tr>
<tr>

	<td class="normal" colspan="2"><textarea name="class_summary" cols="50" rows="2"><?php echo stripslashes($attributes["class_summary"]) ?></textarea></td>
</tr>
<tr>
	<td class="normal" colspan="2">&nbsp;</td>
</tr>
<tr>
	<td class="note" colspan="2" align="center"><span style="color:blue">Character Goals<br></span><span class="note">(This information will only be displayed to event coordinators)</span></td>
</tr>
<tr>

	<td class="normal" colspan="2"><textarea name="char_goals" cols="50" rows="20"><?php echo stripslashes($attributes["char_goals"]) ?></textarea></td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" name="action" value="<?=$attributes["sub_action"]?>" class="submit"></td>
</tr>
</table></div>
<input type="Hidden" name="character_id" value="<?=$attributes["character_id"]?>">
</form>
</div>


<?php
require_once('con_end.php');
?>