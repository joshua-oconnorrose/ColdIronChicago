<?php /*
dsp_types.php 
Created on Tuesday May 17, 2011
Track Management

*/
$db_reqP = 1;
require_once('../scripts/global.php');
// security redirect
require_once('security.php');

// the following variables must be provided on the page
$title = "Manage Types";
// pageID for navigation bar
$pageID = "con_types";
// styleTemplate
$css_template = "../default";
$attributes["type_action"]="Create Type";
/*action processes - maybe I should use fuse*/
if (!isset($attributes['form_action'])){
	$attributes['form_action'] = "Nothing";
}
else{

}
switch ($attributes["form_action"]) {
    case 'Nothing':
		break;
    case 'Create Type':
		$query = sprintf(("Insert into event_type (type_name,type_code,type_order) values (%s,%s,(select count(*) from event_type as one)+1)"),
				GetSQLValueString($attributes['type_name'],"text"),
				GetSQLValueString($attributes['type_code'],"text"));
		$update_type=run_query($query);
		break;
    case 'Update Type':
		$query = sprintf(("UPDATE event_type set type_name=%s
				WHERE type_code = %s"),
				GetSQLValueString($attributes['type_name'],"text"),
				GetSQLValueString($attributes['type_code'], "text"));
		$update_type=run_query($query);
		$attributes["type_name"]="";
		$attributes["type_code"]="";
		break;
	case 'edit_type':
		$sql = 'SELECT * FROM event_type '
			 . ' WHERE type_code=\'' .$attributes['type_code'].'\'';
		$type_data = run_query($sql);
		$type=mysql_fetch_assoc($type_data);
		$attributes["type_name"]=$type["type_name"];
		$attributes["type_code"]=$type["type_code"];
		$attributes["type_action"]="Update Type";
		break;
	case 'move_up':
		$query = sprintf("update event_type set type_order = type_order + 1 where type_order = %s - 1",
				GetSQLValueString($attributes['type_order'], "int"));
		$update_type_order=run_query($query);
		$query = sprintf("update event_type set type_order = type_order - 1 where type_code = %s",
				GetSQLValueString($attributes['type_code'], "text"));
		$update_type_order=run_query($query);
		$attributes["type_code"]="";
		break; 
	case 'move_down':
	
		$query = sprintf("update event_type set type_order = type_order - 1 where type_order = %s+1",
				GetSQLValueString($attributes['type_order'], "int"));
		$update_type_order=run_query($query);
		$query = sprintf("update event_type set type_order = type_order + 1 where type_code = %s",
				GetSQLValueString($attributes['type_code'], "text"));
		$update_type_order=run_query($query);
		$attributes["type_code"]="";
		break; 
	default:
        print "case fell through on dsp_types.php";
		exit();
}

// get types data
$query = 'SELECT *'
     . ' FROM event_type '
	 . ' order by type_order';
	 
$type_list = run_query($query);

	
require_once('con_start.php');


?>

<div align="center" class="heading"><?=CON_NAME?> - Manage Types</div>
<div style="margin: 10pt;">
<?php 
display_errors($err_array);
?>
<form action="dsp_types.php" method="post">
	<table border="0" cellspacing="1" cellpadding="1" width="98%">
	<tr>
		<td colspan="2" align="center" class="normal"><strong><?=$attributes["type_action"]?></strong></td>
	</tr>
	<tr>
		<td class="normal">Type Name:</td>
		<td class="normal"><input type="Text" name="type_name" size="30" value="<?=stripslashes($attributes["type_name"])?>"></td>
	</tr>
		<?php 
		if ($attributes["type_action"] == "Update Type"){?>
			<input type="hidden" name="type_code" value="<?php echo $attributes['type_code'] ?>" />
		<?php } else {?>
	<tr>
		<td class="normal">Type Code:</td>
		<td class="normal"><input type="Text" name="type_code" size="30" value="<?=stripslashes($attributes["type_code"])?>"></td>
	</tr>
		<?php }?>
	<tr>
		<td class="normal">Game Types Included</td>
		<td class="normal"></td>
	</tr>
	<tr>
		<td colspan="2" align="center" class="normal"><input type="submit" name="form_action" value="<?=$attributes["type_action"]?>" class="submit"></td>
	</tr>
	</table>
</form>
<table border="0" cellspacing="0" cellpadding="1" width="98%">
	<?php if($attributes["type_action"]=='Update Type'){?>
	<tr>
		<td colspan="2" align="right"><a  class="note" href="dsp_types.php">Add a Type</a></td>
	</tr>
	<?php }?>
<?php if (mysql_num_rows($type_list)){ ?>
<tr>
	<td class="normal"><b>Type List</b></td>
</tr>
<tr>
	<td class="normal" align="center">Select type to edit</td>
</tr>
<tr>
	<td class="normal" valign="top">
		<table border="0" cellpadding="1" cellspacing="1" width="98%">
		<?php 
    		//resets data pointer to beginning
    		mysql_data_seek($type_list,0);
    		$this_row = 0;
        while ($type_row = mysql_fetch_assoc($type_list)) {
        	$this_row = $this_row + 1;
          	echo "<tr>";
	        if ($this_row <> 1){
	          echo "<td><a class=\"note\" href=\"dsp_types.php?form_action=move_up&type_order=".$type_row["type_order"]."&type_code=".$type_row["type_code"]."\">Up</a></td> ";
			}
			else{echo "<td>&nbsp;</td>";}
	        if ($this_row <> mysql_num_rows($type_list)){
	          echo "<td><a class=\"note\" href=\"dsp_types.php?form_action=move_down&type_order=".$type_row["type_order"]."&type_code=".$type_row["type_code"]."\">Down</a></td> ";
			}
			else{echo "<td>&nbsp;</td>";}
			echo "<td><a class=\"normal\" href=\"dsp_types.php?form_action=edit_type&type_code=".$type_row["type_code"]."\">". (strlen(trim($type_row["type_name"]))?$type_row["type_name"]:"Untitled Type") ."</a></td>";
			echo "</tr>";
	      	
        }
        ?>
		</table>
	</td>
</tr>
<?php }?>
</table>


<?php
require_once('con_end.php');
?>