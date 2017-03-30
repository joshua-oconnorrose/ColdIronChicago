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
$title = "Convention Settings";
// pageID for navigation bar
$pageID = "con_settings";
// styleTemplate
$css_template = "../default";
/*action processes - maybe I should use fuse*/
if (!isset($attributes['action'])){
	$attributes['action'] = "Nothing";
}
else{
	$val = strtotime(stripdash($attributes['prereg_closed']));
		if ($val == -1){
		$err_array[] = "<li> ".$attributes['prereg_closed']." is not a valid Date</li>";
		}
		else {
		$insert_date = strftime('%Y-%m-%d 23:59:59',$val);
		}
}
switch ($attributes["action"]) {
    case 'Nothing':
		break;
    case 'Update Convention':
		$query = sprintf(("UPDATE convention set reg_open_p=%s, prereg_closed = %s
				WHERE con_id = %s"),
				GetSQLValueString($attributes['reg_open_p'], "tinyint"),
				GetSQLValueString($insert_date, "date"),
				GetSQLValueString(CON_ID, "int"));
		$update_game=run_query($query);
		break;
    default:
        print "case fell through on dsp_con.php";
		exit();
}

// get display data
$query = 'SELECT *'
     . ' FROM convention '
	 . ' WHERE con_id = ' .CON_ID;
	 
$get_con = run_query($query);
	$con=mysql_fetch_assoc($get_con);
	$attributes["reg_open_p"]=$con["reg_open_p"];
	$attributes["prereg_closed"]=$con["prereg_closed"];

// get reg count
$sql = 'SELECT count(*) as reg_count '
        . ' FROM player_reg p'
        . ' join event_schedule s on p.event_id = s.event_id'
        . ' and s.con_id = ' .CON_ID;
$get_reg_count = run_query($sql);
	$reg_count=mysql_fetch_assoc($get_reg_count);
	$attributes["reg_count"]=$reg_count["reg_count"];
	
	
require_once('con_start.php');


?>

<div align="center" class="heading"><?=CON_NAME?> - Convention Settings</div>
<div style="margin: 10pt;">
<?php 
display_errors($err_array);
?>
<form action="dsp_con.php" method="post">
	<table border="0" cellspacing="1" cellpadding="1" width="98%">
	<tr>
		<td valign="top" class="normal" colspan="2">
		Event Grid (Registration) open to Public?<br>
		<input type="Radio" class="radio" name="reg_open_p" value="1" <?=($con["reg_open_p"]==1 ? "Checked" : "")?>> Yes 
		<input type="Radio" class="radio" name="reg_open_p" value="0" <?=($con["reg_open_p"]==0 ? "Checked" : "")?>> No 
		
		</td>
	</tr>
	<tr>
		<td nowrap class="normal">Prereg Closed:(Last day of registration)</td>
		<td class="normal"><input type="Text" name="prereg_closed" value="<?=date("F j, Y",strtotime($attributes["prereg_closed"]))?>" size="10"></td>
	</tr>
	<tr>
		<td valign="top" class="normal" align="center">
			<input type="submit" class="submit" name="action" value="Update Convention">
		</td>
	</tr>
	</table>
</form>
<ul>
<?php if ($attributes["reg_count"] != 0){?>
Other Tools:
	<li><a href="../cftest/download_invoice.cfm/invoice.pdf?con_id=<?=CON_ID?>">Convention Invoices</a></li>
<?php } ?>
</ul>


<?php
require_once('con_end.php');
?>