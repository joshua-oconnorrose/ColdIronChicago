<?php 
$db_reqP = 1;
require_once('scripts/global.php');
/*
login_form.php 
Created on Thursday, May 29, 2003
login for con

4/22/03 - modified for con
*/
// the following variables must be provided on the page
$title = "Login Form";
// pageID for navigation bar
$pageID = "con_login";
// require dbconnect?
$db_reqP = 0;
// styleTemplate
$css_template = "default";

//set defaults
if (! isset($attributes["player_id"])) {
$attributes["player_id"] = "";
$attributes["conf"] = 0;
$attributes["login"] = "";
$attributes["password"] = "";
}
require_once('con_start.php');
?>

<div align="center" class="heading">Welcome to <?=CON_NAME?></div>
<div style="margin: 10pt;">

<?=CON_NAME?>:
<p>
Registration Confirmation.
</p>
<form method="post" name="form1" action="act_login.php">
<table align="center">
    <tr valign="baseline">
      <td class="body" nowrap align="right">Login:</td class="body">
      <td class="body"><input type="text" name="login" value="" size="32">
      </td class="body">
    </tr>
    <tr valign="baseline">
      <td class="body" nowrap align="right">Password:</td class="body">
      <td class="body"><input type="password" name="password" value="" size="32">
      </td class="body">
    </tr>
    <tr valign="baseline">
      <td colspan="2" class="body" align="center"><input name="action" type="submit" value="Log In">
      </td class="body">
    </tr>
  </table>
<input type="Hidden" name="conf" value="<?php echo $attributes["conf"]?>">
<input type="Hidden" name="player_id" value="<?php echo $attributes["player_id"]?>">
</form>
</div>
<?php
require_once('con_end.php');
?>