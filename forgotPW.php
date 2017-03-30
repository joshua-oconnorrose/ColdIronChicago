<?
$db_reqP = 1;
require_once('scripts/global.php');

switch($attributes['recover']){
    default:
    include 'inc_forgotPW.php';
    break;
    
    case "recover":
    recover_pw($attributes['login']);
    break;
}
function recover_pw($login){
    if(!$login){
        echo "You forgot to enter your Login<br />";
        include 'inc_forgotPW.php';
        exit();
    }
    // quick check to see if record exists    
    $sql_check = mysql_query("SELECT * FROM players WHERE login='" .$login."'");
    $sql_check_num = mysql_num_rows($sql_check);
	$userData = mysql_fetch_assoc($sql_check);
    if($sql_check_num == 0){
        echo "No records found matching your login<br />";
        include 'inc_forgotPW.php';
        exit();
    }
    // Everything looks ok, generate password, update it and send it!
    $random_password = makeRandomPassword();

    $db_password = md5($random_password);
    
    $sql = mysql_query("UPDATE players SET password='$db_password' 
                WHERE login='".$login."'");
    
    $subject = "Your Password at ".CON_NAME."!";
    $message = "Hi, we have reset your password.
    
    New Password: $random_password
    
    ".SITE_URL."
    
    Thanks!
    The Webmaster
    
    This is an automated response, please do not reply!";
    
    mail($userData['email_addy'], $subject, $message, "From: ".CON_NAME." Webmaster<webmaster@roseocon.net>\n
        X-Mailer: PHP/" . phpversion());
    echo "Your password has been sent! Please check your email!<br />";
    include 'inc_forgotPW.php';
}
?> 
