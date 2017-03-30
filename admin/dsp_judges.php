<?php 
// require dbconnect?
$db_reqP = 1;
require_once('../scripts/global.php');
// security redirect
require_once('security.php');
/*
dsp_judges.php
judge management
Sunday, November 07, 2004
*/
// the following variables must be provided on the page
$title = CON_NAME. ":View Registered Players";
// pageID for navigation bar
$pageID = "con_judges";
// styleTemplate
$css_template = "../default";
if (!isset($attributes['action'])){
	$attributes['action'] = "Nothing";
}
if (!isset($attributes['order_by'])){
	$attributes['order_by'] = "last_name";
}
if (!isset($attributes['sort_dir'])){
	$attributes['sort_dir'] = "ASC";
}




/* Run Updates */
switch ($attributes["action"]) {
    case 'Nothing':
		break;    
	default:
        print "case fell through on dsp_judges.php";
		exit();
}

// Get judges
$sql = 'SELECT p.player_id, first_name, last_name, rpga_number, email_addy, login, join_date, confirm_p, login_count, count( pr.event_id ) AS games_reg'
        . ' FROM players p'
        . ' INNER JOIN player_reg pr ON pr.player_id = p.player_id'
		. ' LEFT JOIN event_schedule es ON pr.event_id = es.event_id'
        . ' WHERE pr.character_id = 4'
		. ' AND es.con_id =' . CON_ID
        . ' GROUP BY player_id, first_name, last_name, rpga_number, email_addy, login, join_date, confirm_p'
        . ' ORDER BY '.$attributes['order_by'].' '.$attributes['sort_dir'];
$get_players = run_query($sql);



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
<div align="center"><strong><?=CON_NAME?> Judges</strong></div>
<?php 
	if (mysql_num_rows($get_players)==0) {
	        echo "<span class='error'>No judges registered for this con yet.</span>";
	    }
	else {
?>
<?= mysql_num_rows($get_players)?> Judges Registered<br>
Select Judge Name to view Registration Details:
<table>
<tr>
	<td class="normal">Judge Name</td>
	<td class="normal">Login</td>
	<td class="normal">RPGA Number</td>
	<td class="normal">email</td>
	<td class="normal">Games</td>
</tr>
<?php 
		while ($this_row = mysql_fetch_assoc($get_players)){?>
<tr>
	<td class="normal"><a href="../player/dsp_register.php?player_id=<?=$this_row['player_id']?>"><?php echo $this_row["first_name"]." ".$this_row["last_name"]?></a></td>
	<td class="normal"><?php echo $this_row["login"] ?></td>
	<td class="normal"><?php echo $this_row["rpga_number"]?></td>
	<td class="normal"><a href="mailto:<?php echo $this_row["email_addy"]?>"><?php echo $this_row["email_addy"]?></a></td>
	<td class="normal" align="center"><?php echo $this_row["games_reg"]?></td>
</tr>
<?php }?>
</table>


<?php }?>

</div>


<?php
require_once('con_end.php');
?>