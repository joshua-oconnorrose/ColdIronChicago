<?php
$db_reqP = 1;
require_once('../scripts/global.php');
if (!isset($attributes['method'])){
	$attributes['method'] = "";
}
if (!isset($attributes['character_level']) || !is_numeric($attributes['character_level'])){
	$attributes['character_level'] = "0";
}
$return = array();
$return["Success"] = true;
$return["Errors"] = "";
$return["Data"] = "";
try{
	switch ($attributes["method"]) {
		case 'addevent':
			$query = sprintf("INSERT INTO player_reg( player_id, event_id)
					VALUES
					(%s,%s)",
					GetSQLValueString($attributes["player_id"], "int"),
					GetSQLValueString($attributes['eventID'], "int"));
			$insert_event=run_query($query);
			$query = sprintf("INSERT INTO player_reg_log( player_id, event_id)
					VALUES
					(%s,%s)",
					GetSQLValueString($attributes["player_id"], "int"),
					GetSQLValueString($attributes['eventID'], "int"));
			$insert_log=run_query($query);
			break;
		case 'deleteevent':
			$query = sprintf("delete from player_reg
					Where player_id = %s and event_id = %s",
					GetSQLValueString($attributes["player_id"], "int"),
					GetSQLValueString($attributes['eventID'], "int"));
			$delete_event=run_query($query);
			break;

		case 'addCharacter':
			$query = sprintf("update player_reg
					set character_id = %s
					Where player_id = %s and event_id = %s",
					GetSQLValueString($attributes["character_id"], "int"),
					GetSQLValueString($attributes["player_id"], "int"),
					GetSQLValueString($attributes['eventID'], "int"));
			$add_character=run_query($query);
			break;
		case 'createCharacter':
			$query = sprintf("INSERT INTO characters(player_id, character_name, character_level, class_summary)
					VALUES
					(%s,%s,%s,%s)",
					GetSQLValueString($_SESSION["player_id"], "int"),
					GetSQLValueString($attributes["character_name"], "text"),
					GetSQLValueString($attributes["character_level"], "int"),
					GetSQLValueString($attributes["class_summary"], "text"));
			$createCharacter=run_query($query);
			$return["Data"]= mysql_insert_id();
			break;
		case 'getCharacterList':
			$sql = 'SELECT *'
			. ' FROM characters'
			. ' WHERE activeP = 1 and  player_id ='. $_SESSION["player_id"] 
			. ' ORDER BY character_name';
			$get_characters = run_query($sql);
			$rows = array();
			while($r = mysql_fetch_assoc($get_characters)) {
				$rows[] = $r;
			}
			$return["Data"]=$rows;
			break;
		default:
			$return["Success"] = false;
			$return["Errors"] = "case fell through on ajax.php";
		}
	}
	catch (Exception $e) {
		$return["Errors"] = $e;
		$return["Success"] = false;
	}
echo json_encode($return);
?>
