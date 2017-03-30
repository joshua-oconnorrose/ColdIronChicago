<?php
$db_reqP = 1;
require_once('scripts/global.php');
//defaults
//take what's come over the form
/*echo "<pre>";
print_r($attributes);
echo "</pre>";
exit;*/
if (! isset($attributes["action"])) {
$attributes["player_id"] = "";
$attributes["conf"] = 0;
$attributes["login"] = "";
$attributes["password"] = "";
}
// if user came from confirm, validate then update
// $query can be made with some sprintf thing I think
if ($attributes["conf"] == 1){
	$query = "Select * from players";
	$query .= " where player_id=".$attributes["player_id"]." and confirm_p = 0";
	$query .= " and login= '".$attributes["login"]."'";
	$query .= " and password='" . md5($attributes["password"])."'";
	// check legal
	$sql_user_check = run_query($query);
	$user_check = mysql_num_rows($sql_user_check); 
	if ($user_check == 1){
		//all logged in users have the following roles
		$attributes["roleList"] = "guest,playr";
		$userData = mysql_fetch_assoc($sql_user_check);
		// update user table set session variables and locate to MY
		$query = "Update players ";
		$query .= " set confirm_p = 1,";
		$query .= " login_count = login_count+1";
		$query .= " where player_id = ".$attributes["player_id"];
		$sql = run_query($query); 
		$_SESSION["roleList"] = "Guest,Player";
		if (ListContainsNoCase(ADMIN_LOGIN_LIST,$attributes["login"])){
		$attributes["roleList"] .= ",admin";
		}
		$_SESSION["login"] = $userData["login"];
		$_SESSION["roleList"] = $attributes["roleList"];
		$_SESSION["player_id"] = $userData["player_id"];
		$_SESSION["logged_in"] = "1";
		$_SESSION["rpga_number"] = $userData["rpga_number"];
		$location = "my_con.php";
		header("Location: ".$location);
	}
	else {
		$location = "login_form.php?msg=Confirmation Refused, Login or Password was incorrect.";
		header("Location: ".$location);
	}
}
// if user didn't come from email Validate then direct
else {
	$query = "Select * from players";
	$query .= " where confirm_p = 1";
	$query .= " and login= '".$attributes["login"]."'";
	$query .= " and password='" . md5($attributes["password"])."'";
	$sql_user_check = run_query($query);
	$user_check = mysql_num_rows($sql_user_check); 
	if ($user_check == 1){
		//set assoc array for userdata and set rq_player_id
		$userData = mysql_fetch_assoc($sql_user_check);
		$attributes["player_id"] = $userData["player_id"];
		//all logged in users have the following roles
		$attributes["roleList"] = "guest,playr";
		if (ListContainsNoCase(ADMIN_LOGIN_LIST,$attributes["login"])){
		$attributes["roleList"] .= ",admin";
		}
		//set session varibles
		$_SESSION["roleList"] = $attributes["roleList"];
		$_SESSION["login"] = $userData["login"];
		$_SESSION["player_id"] = $userData["player_id"];
		$_SESSION["logged_in"] = "1";
		$_SESSION["rpga_number"] = $userData["rpga_number"];
		/* update user table 
		get user roles
		set session variables 
		locate to MY*/
		$query = "Update players ";
		$query .= " set login_count = login_count+1";
		$query .= " where player_id =" .$attributes["player_id"];
		$sql = run_query($query); 
		$location = "my_con.php";
		header("Location: ".$location);
	}
	else {
		$location = "index.php?msg=" . urlencode("Login or Password was incorrect. Or you have not yet confirmed your registration");
		header("Location: ".$location);
	}
}
?>
