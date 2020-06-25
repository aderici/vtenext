<?php
global $adb, $table_prefix;

// crmv@128656
$res = $adb->pquery("SELECT fieldtypeid FROM {$table_prefix}_ws_fieldtype WHERE uitype = ?", array(98));
if ($res && $adb->num_rows($res) == 0) {
	$seq_id = $adb->getUniqueID($table_prefix."_ws_fieldtype");
	$sql = "INSERT INTO ".$table_prefix."_ws_fieldtype (fieldtypeid,uitype,fieldtype) VALUES (?,?,?)";
	$params = array($seq_id,98,'string');
	$adb->pquery($sql, $params);
}

