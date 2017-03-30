<?php
// require dbconnect?
$db_reqP = 1;
require_once('../scripts/global.php');
// security redirect
require_once('security.php');

$sql = 'SELECT DISTINCT player_id'
        . ' FROM player_reg p'
        . ' JOIN event_schedule e ON p.event_id = e.event_id'
        . ' where con_id =' . CON_ID;
$get_player= run_query($sql);
// We'll be outputting a Word Document
header("Content-type: application/msword");

// It will be called worddoc.doc
header("Content-Disposition: attachment; filename=worddoc.doc");?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'>
<head>
<!--[if gte mso 9]>
<xml>
        <w:WordDocument>  
		<w:View>Print</w:View> 
        <w:Zoom>90</w:Zoom>  
        <w:DoNotOptimizeForBrowser/>
        </w:WordDocument>
</xml>
<![endif]-->
<?php	if (mysql_num_rows($get_player)!=0) {
        while ($event_row = mysql_fetch_assoc($get_player)) {
			/* RKKiBar HTTP include
			  Author : fx of 0xbadc0ded  */
			
			$barurl  = "wiscons.roseocon.net";
			$barpage = "player/dsp_registration.php?player_id=".$event_row['player_id']. "&bypass=1";
			
			$dirtysock = fsockopen($barurl, 80, $errno, $errstr, 30);
			if (!$dirtysock) {
			  echo "Unable to do HTTP include : $errstr ($errno)<br />\n";
			} else {
			  $out = "GET /$barpage HTTP/1.1\r\n";
			  $out .= "Host: $barurl\r\n";
			  $out .= "Connection: Close\r\n\r\n";
			
			  fwrite($dirtysock, $out);
			  while (!feof($dirtysock)) {
			       $code .= fgets($dirtysock, 128);
			  }
			  fclose($dirtysock);
			}
			
			$bardata = explode('text/html',$code);
			echo $bardata[1];
 }}?>

