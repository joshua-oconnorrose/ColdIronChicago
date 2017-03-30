<?php 
/*
index.php 
Created on Thursday, March 27, 2003
Entry Page for Worlds of Play

4/22/03 - modified for con
*/
// the following variables must be provided on the page
$title = CON_NAME. ":Forgot Password";
// pageID for navigation bar
$pageID = "con_home";
// require dbconnect?
$db_reqP = 0;
// styleTemplate
$css_template = "default";

require_once('con_start.php');
?>

<div align="center" class="heading">Recover your password</div>
<div style="margin: 10pt;">

<form method="post" name="form1" action="forgotPW.php">
<table align="center">
    <tr valign="baseline">
      <td class="body" nowrap align="right">Login:</td class="body">
      <td class="body"><input type="text" name="login" value="" size="32">
      </td class="body">
    </tr>
    <tr valign="baseline">
      <td colspan="2" class="body" align="center"><input name="recover" type="submit" value="recover">
      </td class="body">
    </tr>
  </table>
</form>
This site shares registration with other conventions if you can't remember your password and feel your system email is out of date send an email to 
<a href="mailto:joshua.oconnorrose@gmail.com">Webmaster</a>
</div>
<?php
require_once('con_end.php');
?>