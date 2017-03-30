<?php
session_start();
//remove the local session
session_destroy();
//remove the client session
if(isset($_COOKIE[session_name()]))
{
session_start();   // To be able to use session_destroy
session_destroy(); // To delete the old session file
unset($_COOKIE[session_name()]);
}
//redirect user with header
 header("Location: index.php");
?>