<?php
global $adb, $table_prefix;

$index_table = "{$table_prefix}_running_processes_timer";
$index_name = "{$table_prefix}_rpt_index1";
$index_columns = array('mode','running_process','prev_elementid');
$dropIndexes = array($index_name);

// disable die on error for indexes
$oldDie = $adb->dieOnError;
$adb->setDieOnError(false);

if ($adb->isMysql()) {
	// fast code, only for mysql
	
	// check if they exists and drop them
	$drops = array();
	foreach ($dropIndexes as $idx) {
		$res = $adb->pquery("SHOW INDEX FROM `$index_table` WHERE KEY_NAME = ?", array($idx));
		if ($res && $adb->num_rows($res) > 0) {
			$drops[] = "DROP INDEX $idx";
		}
	}
	$alter = implode(', ', $drops).(count($drops) > 0 ? ", " : "")."ADD INDEX $index_name(".implode(', ',$index_columns).")";
	$res = $adb->query("ALTER TABLE `$index_table` {$alter}");
	
} else {
	// generic queries
	
	$indexes = $adb->database->MetaIndexes($index_table);
	
	// drop indexes
	foreach($indexes as $name => $index) {
		if (in_array($name, $dropIndexes)) {
			$sql = $adb->datadict->DropIndexSQL($name, $index_table);
			if ($sql) $adb->datadict->ExecuteSQLArray($sql);
		}
	}
	
	// create the index
	$sql = $adb->datadict->CreateIndexSQL($index_name, $index_table, $index_columns);
	if ($sql) $adb->datadict->ExecuteSQLArray($sql);
	
}

$adb->setDieOnError($oldDie);