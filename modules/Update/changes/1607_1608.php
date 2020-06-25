<?php

global $adb, $table_prefix;

if (!function_exists('moveFieldAfter')) {
	function moveFieldAfter($module, $field, $afterField) {
		global $adb, $table_prefix;
		
		$tabid = getTabid($module);
		if (empty($tabid)) return;
		
		$res = $adb->pquery("SELECT fieldid, sequence FROM {$table_prefix}_field WHERE tabid = ? AND fieldname = ?", array($tabid, $field));
		if ($res && $adb->num_rows($res) > 0) {
			$fieldid1 = intval($adb->query_result_no_html($res, 0, 'fieldid'));
			$sequence1 = intval($adb->query_result_no_html($res, 0, 'sequence'));
		}
		
		$res = $adb->pquery("SELECT fieldid, sequence FROM {$table_prefix}_field WHERE tabid = ? AND fieldname = ?", array($tabid, $afterField));
		if ($res && $adb->num_rows($res) > 0) {
			$fieldid2 = intval($adb->query_result_no_html($res, 0, 'fieldid'));
			$sequence2 = intval($adb->query_result_no_html($res, 0, 'sequence'));
		}
		
		if ($fieldid1 > 0 && $fieldid2 > 0) {
			// get the ids to update
			$updateIds = array();
			$res = $adb->pquery("SELECT fieldid FROM {$table_prefix}_field WHERE tabid = ? AND sequence > ?", array($tabid, $sequence2));
			if ($res && $adb->num_rows($res) > 0) {
				while ($row = $adb->fetchByAssoc($res)) {
					$updateIds[] = intval($row['fieldid']);
				}
			}
			if (count($updateIds) > 0) {
				$adb->pquery("UPDATE {$table_prefix}_field set sequence = sequence + 1 WHERE fieldid IN (".generateQuestionMarks($updateIds).")", $updateIds);
			}
			$adb->pquery("UPDATE {$table_prefix}_field set sequence = ? WHERE tabid = ? AND fieldid = ?", array($sequence2+1, $tabid, $fieldid1));
		}	
	}
}

// crmv@150808

if (!SDK::isUitype(215)) {
	SDK::setUitype(215,'modules/SDK/src/215/215.php','modules/SDK/src/215/215.tpl','modules/SDK/src/215/215.js');
}

$fields = array(
	'weekstart'		=> array('module'=>'Users', 'block'=>'LBL_CALENDAR_CONFIGURATION', 'name'=>'weekstart', 	'label'=>'WeekStartDay',		'table'=>$table_prefix.'_users', 	'columntype'=>'I(5)',	'typeofdata'=>'V~O',	'uitype'=>215),
);

Update::create_fields($fields);

$adb->pquery("UPDATE {$table_prefix}_users SET weekstart = ?", array(1));

moveFieldAfter('Users', 'weekstart', 'no_week_sunday');


// crmv@149399

$table = $table_prefix.'_activity';

// add a sane datetime column to the calendar
$adb->addColumnToTable($table, 'activity_start', 'T', "DEFAULT '0000-00-00 00:00:00'");
$adb->addColumnToTable($table, 'activity_end', 'T', "DEFAULT '0000-00-00 00:00:00'");

if ($adb->isMysql()) {
	$adb->query(
		"UPDATE $table
		SET activity_start = CAST(".$adb->sql_concat(array('date_start', "' '", 'time_start'))." AS DATETIME),
		activity_end = CAST(".$adb->sql_concat(array('due_date', "' '", 'time_end'))." AS DATETIME)"
	);
} elseif ($adb->isOracle()) {
	// not tested!
	$adb->query(
		"UPDATE $table
		SET activity_start = TO_DATE(".$adb->sql_concat(array('date_start', "' '", 'time_start')).", 'YYYY-MM-DD HH24:MI'),
		activity_end = TO_DATE(".$adb->sql_concat(array('due_date', "' '", 'time_end')).", 'YYYY-MM-DD HH24:MI')"
	);
}

$indexes = $adb->database->MetaIndexes($table);
if (!array_key_exists('activity_start_idx', $indexes)) {
	$index = $adb->datadict->CreateIndexSQL('activity_start_idx', $table, 'activity_start');
	$adb->datadict->ExecuteSQLArray((Array)$index);
}
if (!array_key_exists('activity_end_idx', $indexes)) {
	$index = $adb->datadict->CreateIndexSQL('activity_end_idx', $table, 'activity_end');
	$adb->datadict->ExecuteSQLArray((Array)$index);
}



// crmv@150747

$trans = array(
	'Users' => array(
		'it_it' => array(
			'WeekStartDay' => 'Primo giorno della settimana',
		),
		'en_us' => array(
			'WeekStartDay' => 'First day of week',
		),
	),
	'ALERT_ARR' => array(
		'it_it' => array(
			'DB_ROW_LIMIT_REACHED' => 'Il database non permette di aggiungere ulteriori campi. Contatta il servizio clienti VTECRM per aumentare il limite.',
		),
		'en_us' => array(
			'DB_ROW_LIMIT_REACHED' => 'The database doesn\'t allow to add more fields. Contact VTECRM customer service to raise the limit.',
		),
	),
);

$languages = vtlib_getToggleLanguageInfo();
foreach ($trans as $module => $modlang) {
	foreach ($modlang as $lang => $translist) {
		if (array_key_exists($lang, $languages)) {
			foreach ($translist as $label => $translabel) {
				SDK::setLanguageEntry($module, $lang, $label, $translabel);
			}
		}
	}
}
