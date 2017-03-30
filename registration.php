<?php 
/*
registration.php 
Created on Tuesday, April 22, 2003
Registration Page for Worlds of Play

Author - Joshua O'Connor-Rose
*/
$db_reqP = 1;
require_once('scripts/global.php');
require_once('Validate.php');
// the following variables must be provided on the page
$title = "Site Registration for ".CON_NAME;
// pageID for navigation bar
$pageID = "con_reg";
// styleTemplate
$css_template = "default"; 
// set page defaults
if (! isset($_SESSION["player_id"])){
$attributes["action_button"]='Register';
$attributes['login_old']= '';
}
else{
$attributes["action_button"]='Update';
	$sql = 'SELECT * '
	        . ' FROM players '
	        . ' WHERE player_id ='.$_SESSION["player_id"]; 
	$get_player= run_query($sql);
	$userData = mysql_fetch_assoc($get_player);
if(!isset($attributes["action"])){
			$attributes['login_old']= $userData['login'];
			$attributes['login']= $userData['login'];
			$attributes['first_name']= $userData['first_name'];
			$attributes['last_name']= $userData['last_name'];
			$attributes['rpga_number']= $userData['rpga_number'];
			$attributes['email_addy']= $userData['email_addy'];
	}
}

if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $editFormAction .= "?" . $HTTP_SERVER_VARS['QUERY_STRING'];
}
$today = date('Y-m-d');
// if form was submitted set defaults to what was on form
// and run processes
if (isset($attributes["action"])) {
		// validate variables
		if ($attributes["login"] != $attributes["login_old"]){
			$sql_handle_check =run_query("SELECT login FROM players WHERE login=".GetSQLValueString($attributes["login"],text)); 
			$handle_check = mysql_num_rows($sql_handle_check); 
			if ($handle_check) {
			$err_array[] = "<li>That login is already in use please use another</li>";
			$err_array[] = "<li>If you registered on a similar site try using the <a href='forgotPW.php' >forgot password</a> feature</li>";
			}
		}
		if (strlen(trim($attributes["first_name"])) == 0){
		$err_array[] = "<li>First Name is required</li>";
		}
		if (!isSet($_SESSION["player_id"]) || isSet($_SESSION["player_id"]) && strlen(trim($attributes["password"])) > 0){
			if ($attributes["password_confirm"] != $attributes["password"]) {
			$err_array[] = "<li>Your passwords did not match</li>";
			}
			if (strlen(trim($attributes["password"])) == 0){
			$err_array[] = "<li>Password is required</li>";
			}
			// this sets a value for password on update
			if (! isset($err_array) && isSet($_SESSION["player_id"])){
				$attributes["password_new"] = $attributes["password"];
			}
		}
		if (strlen(trim($attributes["last_name"])) == 0){
		$err_array[] = "<li>Last Name is required</li>";
		}
		$val = Validate::string($attributes["login"], array( 'min_length'=>4));
		if (! $val) {
		$err_array[] = "<li>Your login must be at least 4 characters long</li>";
		}
		$val = Validate::email($attributes["email_addy"]);
		if (! $val) {
		$err_array[] = "<li>" . $attributes["email_addy"] . " is not a valid email address</li>";
		}
		if (strlen($attributes["rpga_number"])){
			$val = Validate::string($attributes["rpga_number"], array('format'=>VALIDATE_NUM, 'max_length'=>10));
			if (! $val) {
			$err_array[] = "<li>" . $attributes["rpga_number"] . " is not a valid rpga number</li>";
			}
		}
		if(isset($_SESSION["player_id"])){
			$query = "Select * from players";
			$query .= " where confirm_p = 1";
			$query .= " and login= '".$attributes["login_old"]."'";
			$query .= " and password='" . md5($attributes["password_validate"])."'";
			$sql_user_check = run_query($query);
			$user_check = mysql_num_rows($sql_user_check); 
			if ($user_check <> 1){
			$err_array[] = "<li>You must provide your password to update your profile</li>";
			}
		}
		
//process page data
switch ($attributes["action"]) {
    case 'Register':
		if (! isset($err_array))
		{
		  $insertSQL = sprintf("INSERT INTO players (login, password, first_name, last_name, rpga_number, email_addy, join_date) VALUES (%s, %s, %s, %s, %s, %s, %s)",
GetSQLValueString($attributes['login'], "text"),
GetSQLValueString(md5($attributes['password']), "text"),
GetSQLValueString($attributes['first_name'], "text"),
GetSQLValueString($attributes['last_name'], "text"),
GetSQLValueString($attributes['rpga_number'], "text"),
GetSQLValueString($attributes['email_addy'], "text"),
GetSQLValueString($today, "date"));
			$Result1 = run_query($insertSQL);
			
// mail notification
	if (!DEVELOPMENT_P){
	$player_id = mysql_insert_id(); 
    $subject = "Membership"; 
    $message = "Someone, probably you, has registered you on the ".CON_NAME." registration site! 
To activate your membership, please login here: ".SITE_URL."/login_form.php?conf=1&player_id=$player_id
     
Once you activate your membership, you will be able to login with the following information: 

    Handle: ".$attributes['login']."

This is an automated response, please do not reply!"; 
     
    mail($attributes["email_addy"], $subject, $message, "From:".CON_NAME."<".ADMIN_EMAIL.">\nX-Mailer: PHP/" . phpversion()); 
	}
		  $insertGoTo = "index.php?msg=" . urlencode("Registration Successful. You will receive a confirmation email shortly.");
		  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
			$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
			$insertGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
		  }
		 header(sprintf("Location: %s", $insertGoTo));
		}
        break;
	case 'Update':
		if (isset($err_array)){
			$attributes['login']= $userData['login'];
		}
		else {
			if(!isset($attributes["password_new"])){
				$attributes["password_new"]=$attributes["password_validate"];
			}
				$updateSQL = sprintf("update players set login=%s, password=%s, first_name=%s, last_name=%s, rpga_number=%s, email_addy=%s where player_id = %s",
GetSQLValueString($attributes['login'], "text"),
GetSQLValueString(md5($attributes['password_new']), "text"),
GetSQLValueString($attributes['first_name'], "text"),
GetSQLValueString($attributes['last_name'], "text"),
GetSQLValueString($attributes['rpga_number'], "text"),
GetSQLValueString($attributes['email_addy'], "text"),
GetSQLValueString($_SESSION["player_id"], "int"));
			$Result1 = run_query($updateSQL);
			$attributes["msg"] = "Profile updated successfully";
		}
        break;
    default:
        print "case fell through on registration.php";
		exit();
	}
}
 
?>

<?php
require_once('con_start.php');
?>

<div align="center" class="heading">Register for <?php echo CON_NAME?></div>
<div style="margin: 10pt;">
<p>
Let us know what you want to play.
</p>
<span class="note">*indicates a required field.</span>
<?php 
if (isset($err_array)){
echo "<div class='error'>";
echo "<br><strong>Errors Exist</strong>:<ul>";
foreach($err_array as $message)
{echo $message;}
echo "</ul></div>";
}
?>
<?
if (strlen($attributes["msg"])){
echo "<span class='error'>".$attributes["msg"]."</span><p />";
}
?>
<div align="center">
<form method="post" name="form1" action="registration.php">
<table align="center">
    <tr valign="baseline">
      <td class="body" nowrap align="right">*Login:</td class="body">
      <td class="body"><input type="text" name="login" value="<?php echo $attributes["login"] ?>" size="32">
		<input type="Hidden" name="login_old" value="<?php echo $attributes["login_old"] ?>">
      </td class="body">
    </tr>
	<?php if(!isSet($_SESSION["player_id"])){ ?>
    <tr valign="baseline">
      <td class="body" nowrap align="right">*Password:</td class="body">
      <td class="body"><input type="password" name="password" value="" size="32">
      </td class="body">
    </tr>
    <tr valign="baseline">
      <td class="body" nowrap align="right">*Password Confirm:</td class="body">
      <td class="body"><input type="password" name="password_confirm" value="" size="32">
      </td class="body">
    </tr>
	<?php }else{ ?>
    <tr valign="baseline">
      <td class="body" nowrap align="right">*Password:</td class="body">
      <td class="body"><input type="password" name="password_validate" value="" size="32">
      </td class="body">
    </tr>
    <tr valign="baseline">
      <td class="body" nowrap align="right">New Password:</td class="body">
      <td class="body"><input type="password" name="password" value="" size="32">
      </td class="body">
    </tr>
    <tr valign="baseline">
      <td class="body" nowrap align="right">New Password Confirm:</td class="body">
      <td class="body"><input type="password" name="password_confirm" value="" size="32">
      </td class="body">
    </tr>
	<?php } ?>
    <tr valign="baseline">
      <td class="body" nowrap align="right">*First Name:</td class="body">
      <td class="body"><input type="text" name="first_name" value="<?php echo stripslashes($attributes["first_name"]) ?>" size="32">
      </td class="body">
    </tr>
    <tr valign="baseline">
      <td class="body" nowrap align="right">*Last Name:</td class="body">
      <td class="body"><input type="text" name="last_name" value="<?php echo stripslashes($attributes["last_name"]) ?>" size="32">
      </td class="body">
    </tr>
    <tr valign="baseline">
      <td class="body" nowrap align="right">DCI Number:</td class="body">
      <td class="body"><input type="text" name="rpga_number" value="<?php echo  $attributes["rpga_number"] ?>" size="32">
      </td class="body">
    </tr>
    <tr valign="baseline">
      <td class="body" nowrap align="right">*Email Address:</td class="body">
      <td class="body"><input type="text" name="email_addy" value="<?php echo $attributes["email_addy"] ?>" size="32">
      </td class="body">
    </tr>
    <tr valign="baseline">
      <td class="body" nowrap align="right">&nbsp;</td class="body">
      <td class="body"><input name="action" type="submit" class="submit" value="<?php echo $attributes["action_button"];?>">
      </td class="body">
    </tr>
  </table>


</form>
</div>
</div>
<?php
require_once('con_end.php');
?>