<?php 
if (!ListContainsNoCase($_SESSION["roleList"],"admin"))
	{
		$location = "../index.php?msg=".urlencode("You do not have Access to this page or are not logged in; Please login.");
		header("Location: ".$location);
		exit();
	}
?>