&nbsp;<p />
<table border="1pt" cellspacing="0" cellpadding="0" bordercolor="Black" width="100%">
<tr>
	<td colspan="<?echo mysql_num_rows($slot_list)+1;?>" align="center"><?echo $track_row["track_title"];?></td>
</tr>
<tr align="center">
	<td class="note">To View Games/Player/Judges<br>
	Select Slot</td>
	
     <?php while ($slot_row = mysql_fetch_assoc($slot_list)) {
      ?>
	  <td class=note><a class=note href="dsp_eventdetails.php?slot_id=<?=$slot_row['slot_id']?>">Slot <?php echo $slot_row["slot_number"];?></td>
		<?php }?>
</tr>
<tr align="center">
	<td class="note">To View Summary and Slots<br>Select Game</td>
    		
	
     <?php 
	 
	 	if(mysql_num_rows($slot_list)<>0){mysql_data_seek($slot_list,0);};
	 	while ($slot_row = mysql_fetch_assoc($slot_list)) {
      ?>
	  <td class=note><?php echo date("m/d/y",strtotime($slot_row["slot_date"]))."<br> ".date("g:i a",strtotime($slot_row["start_time"]))."-".date("g:i a",strtotime($slot_row["end_time"]));?> </td>
	  <?php }?>
</tr>
<?php 
$this_type_name="";
if(mysql_num_rows($game_list)<>0){mysql_data_seek($game_list,0);};
while ($game_row = mysql_fetch_assoc($game_list)) {
	$this_game_id = $game_row["game_id"];
    if($game_row["type_name"] != $this_type_name){
	echo "<tr bgcolor=\"#00ffff\"><td align=\"center\" class='note' colspan=".(mysql_num_rows($slot_list)+1).">".str_replace("-","",$game_row["type_name"])."</td>";
	echo"</tr>";
	$this_type_name=$game_row["type_name"];
	}
	?>
<tr>
	<td class="note"><a class="note" href="dsp_eventdetails.php?game_id=<?=$game_row['game_id']?>"><?php echo $game_row["game_code"]." ".$game_row["name"];?></a></td>
	<?php 
		//slot loop 
		$skip_build_td_count = 0;
		mysql_data_seek($slot_list,0);
	 	while ($slot_row = mysql_fetch_assoc($slot_list)) {
		$this_slot_number = $slot_row["slot_number"];
		$block_event_p = 1;
		//event loop
		if($skip_build_td_count > 0){
			$skip_build_td_count = $skip_build_td_count - 1;
		}
		else{
		if(mysql_num_rows($event_key)<>0){mysql_data_seek($event_key,0);};
		while ($event_key_row = mysql_fetch_assoc($event_key)) {
				//begin match if
	 			if (($event_key_row["slot_number"] == $this_slot_number) && ($event_key_row["game_id"] == $this_game_id)){ 
				$this_event_id = $event_key_row["event_id"];
				echo "<td valign='top' class=\"note\" align=\"center\" bgcolor=\"#83E7CB\" colspan=". $event_key_row["slot_length"].">";
				$block_event_p = 0;
					// define skip
					if($event_key_row["slot_length"]>1){
					$skip_build_td_count = $event_key_row["slot_length"]-1;
					}
					// pop with players & judges
					if($_SESSION["logged_in"]==1){
						//$register_link = "player/dsp_register.php?game_id=".$event_key_row["game_id"]."&slot_id=".$event_key_row["slot_id"]."&set_default_p=1";
						$register_link = "player/dsp_register.php?event_id=".$event_key_row["event_id"];
					}else{
						$register_link = "registration.php";
					}
					if (time() < strtotime(PREREG_CLOSED)){
					echo "<a class='note' href='".$register_link."'>Register</a><br>";
					}
					if ($event_key_row["player_reg"]!=0){
							// if admin show number of judges
							if((mysql_num_rows($judge_key))!=0){
								mysql_data_seek($judge_key,0);
								while ($judge_key_row = mysql_fetch_assoc($judge_key)){
									if($this_event_id == $judge_key_row["event_id"]){
									echo "Judges:" . $judge_key_row["judge_reg"]."<br />";
									}
								}
						}
						// show number of players
						if((mysql_num_rows($player_key))!=0){
						mysql_data_seek($player_key,0);
							while ($player_key_row = mysql_fetch_assoc($player_key)){
								if($this_event_id == $player_key_row["event_id"]){
								echo "Players:" . $player_key_row["player_reg"];
								}
							}
						}
					}
				echo "</td>";
				}
				//end event match
			}
			//end event loop
		if ($block_event_p==1){
		echo "<td class=\"note\" align=\"center\" bgcolor=\"#003399\">&nbsp;</td>"; 
		}
		}
	//end slot loop
	 }
	 $block_event_p = 1;
	 ?>
</tr>
<?php }?>
<!-- 
<tr>
<td colspan="<?=(mysql_num_rows($slot_list)+1)?>">
<table width="100%">
<tr>
	<td class="normal"><a href="javascript:history.back()">Back</a></td>
	<?php if  ($_SESSION["logged_in"]==1){?>
	<td class="normal" align="right"><a href="my_con.php">My <?=CON_NAME?></a></td>
	<?php }?>
</tr>
</table>
</td>
</tr> -->
</table>