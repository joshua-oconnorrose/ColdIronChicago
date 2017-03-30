<?php 
/*
cj_nav.php 
Created on Thursday, March 27, 2003
Navigation page for Chicago Judges

4/22/03
Change to suit con Home
*/

$className = "navigation";

$navigation = array (  12  => array ( 	"sort" => 12,
										"location" => SERVER_PATH ."contact.php",
                                       	"id" => "con_contact",
                                       	"name" => "Contact"),
               			11  => array ( 	"sort" => 11,
										"location" => SERVER_PATH . "index.php",
                                       	"id" => "con_home",
                                       	"name" => "Home"),
						13  => array ( 	"sort" => 13,
										"location" => SERVER_PATH ."lodging_misc.php",
                                       	"id" => "con_lodging",
                                       	"name" => "Hotel &amp; Travel"),
						14  => array ( 	"sort" => 18,
										"location" => SERVER_PATH ."eventgrid.php",
                                       	"id" => "con_grid",
                                       	"name" => "Events Grid")
						 );
$query = 'SELECT other'
     . ' FROM convention_text '
	 . ' WHERE con_id = ' .CON_ID;
	$getother = run_query($query);
	$otherText = mysql_fetch_assoc($getother);
if (strlen($otherText['other'])<>0){
	$navigation[] = array ( 	"sort" => 14,
							"location" => SERVER_PATH ."otherEvents.php",
                            "id" => "con_other",
                            "name" => "Special Events");
}
						
if  ($_SESSION["logged_in"] == 1){
$navigation[] = array ( "sort" => 22,
						 "location" => SERVER_PATH ."logout.php",
                         "id" => "con_logout",
                         "name" => "Log Out");
$navigation[] = array ( "sort" => 23,
						 "location" => SERVER_PATH ."my_con.php",
                         "id" => "con_mycon",
                         "name" => "My " . CON_NAME);
	if (ListContainsNoCase($_SESSION["roleList"],"admin")) {
	$navigation[] = array ( "sort" => 31,
							 "location" => "",
	                         "id" => "hr",
	                         "name" => "Admin");
	$navigation[] = array ( "sort" => 32,
							 "location" => SERVER_PATH ."admin/dsp_con.php",
	                         "id" => "con_settings",
	                         "name" => "Convention Settings");
	$navigation[] = array ( "sort" => 33,
							 "location" => SERVER_PATH ."admin/dsp_tracks.php",
	                         "id" => "con_events",
	                         "name" => "Manage Events");
	$navigation[] = array ( "sort" => 34,
							 "location" => SERVER_PATH ."admin/dsp_players.php",
	                         "id" => "con_player",
	                         "name" => "Players");
	$navigation[] = array ( "sort" => 35,
							 "location" => SERVER_PATH ."admin/dsp_judges.php",
	                         "id" => "con_judges",
	                         "name" => "Judges");
	}
}
else{		
$navigation[] = array ( 	"sort" => 20,
							"location" => SERVER_PATH ."registration.php",
                            "id" => "con_reg",
                            "name" => "Register");
}
sort($navigation) ;
foreach($navigation as $v1) {
		if ($v1[id] == $pageID){
		$className = "selected";
		} else {
		$className = "navigation";
		}
		if ($v1[id] == "hr"){
		echo "<hr class='$className'><span class='navTitle'>$v1[name]</span><br>";
		}
		else{
        echo  
"<a class=\"" . $className . "\" href=\"" . $v1[location] ."\">" . $v1[name] . "</a><br />\n";
		}
}

// show login form if not logged in
if  (! $_SESSION["logged_in"]==1) {
?>
<span class="navigation">
<form action="<?=SERVER_PATH ."act_login.php"?>" method="post" style="margin-left:5px">
<input type="text" name="login" style="font-size: 8pt; width: 90px;background-color:White;"><br>Login<br>
<input type="password" name="password" style="font-size: 8pt; width: 90px;background-color:White;"><br>
Password&nbsp;<input type="submit" name="action" value="Go&gt;" class="submit" style="font-size:7pt; text-align: center; border-width: 1;"></span>
</form>
<a href="forgotPW.php" class="navigation">Forgot Password?</a>
<?php ;}?>
