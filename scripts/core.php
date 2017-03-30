<?php
function makeRandomPassword() { 
  $salt = "abchefghjkmnpqrstuvwxyz0123456789"; 
  srand((double)microtime()*1000000); 
      $i = 0; 
      while ($i <= 7) { 
            $num = rand() % 33; 
            $tmp = substr($salt, $num, 1); 
            $pass = $pass . $tmp; 
            $i++; 
      } 
      return $pass; 
} 

function display_errors($errArray){
	if (isset($errArray)){
		echo "<div class='error'>";
		echo "<br><strong>Errors Exist</strong>:<ul>";
		foreach($errArray as $message)
		{echo $message;}
		echo "</ul></div>";
	}
}
?>
