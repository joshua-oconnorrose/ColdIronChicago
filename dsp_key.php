<?php 
// require dbconnect?
$db_reqP = 0;
require_once('scripts/global.php');
/*
contact.php 
Created on Tuesday, April 22, 2003
Contact Page for Worlds of Play

Author - Joshua O'Connor-Rose

*/

// the following variables must be provided on the page
$title = CON_NAME . ": Contacts";
// pageID for navigation bar
$pageID = "con_contact";
// styleTemplate
$css_template = "default";

require_once('con_start.php');
?>

<div align="center" class="heading"><?= $title?></div>
<div style="margin: 10pt;">
<?= CON_NAME ?>: Key to Registration details
<table>

			<tr>
	<td class="note" bgcolor = "#ffffff">Registration confirmed</td>
	<td class="note" bgcolor = "#ffffcc">Thanks for Registering</td>
	</tr>
	<tr>
	<td class="note" bgcolor = "#66ffcc">Waiting List</td>
	<td class="note" bgcolor = "#66ffff">Play Likely
	</tr>
	<tr>
	<td class="note" bgcolor = "#ffcc99">Waiting List </td>
	<td class="note" bgcolor = "#ffcccc">Play Unlikely</td>
	</tr>
	<tr>
	<td class="note" bgcolor = "#ff66ff">Waiting List</td>
	<td class="note" bgcolor = "#ff99ff">Play Very Unlikely
			<br> (consider registering for a different event)</td> 
	</tr>
</table>
</div>
<?php
require_once('con_end.php');
?>