<?php
global $adb, $table_prefix;

/* crmv@127526 */

// useful functions
if (!function_exists('getPrimaryKeyName')) {
	function getPrimaryKeyName($tablename) {
		global $adb, $dbconfig;
		$ret = '';
		if ($adb->isMysql()) {
			// for mysql just check if it exists
			$res = $adb->query("SHOW KEYS FROM {$tablename} WHERE Key_name = 'PRIMARY'");
			if ($res && $adb->num_rows($res) > 0) $ret = 'PRIMARY';
		} elseif ($adb->isMssql()) {
			$res = $adb->pquery("SELECT CONSTRAINT_NAME as cn from INFORMATION_SCHEMA.TABLE_CONSTRAINTS where CONSTRAINT_CATALOG = ? and TABLE_NAME = ? and CONSTRAINT_TYPE = 'PRIMARY KEY'", array($dbconfig['db_name'], $tablename));
			if ($res) $ret = $adb->query_result_no_html($res, 0, 'cn');
		} elseif ($adb->isOracle()) {
			$res = $adb->pquery("SELECT CONSTRAINT_NAME as cn FROM all_constraints cons     WHERE cons.table_name = ? AND cons.constraint_type = 'P'", array(strtoupper($tablename)));
			if ($res) $ret = $adb->query_result_no_html($res, 0, 'cn');
		}
		return $ret;
	}
}

if (!function_exists('dropPrimaryKey')) {
	function dropPrimaryKey($tablename) {
		global $adb;
		if ($adb->isMysql()) {
			$keyname = getPrimaryKeyName($tablename);
			if ($keyname == 'PRIMARY') $adb->query("ALTER TABLE {$tablename} DROP PRIMARY KEY");
		} elseif ($adb->isMssql() || $adb->isOracle()) {
			$keyname = getPrimaryKeyName($tablename);
			$adb->query("ALTER TABLE {$tablename} DROP CONSTRAINT {$keyname}");
		} else {
			echo "Drop Primary key not supported for this database";
		}
	}
}


// change the newsletter queue table

// add the column
$adb->addColumnToTable('tbl_s_newsletter_queue', 'queueid', 'INT(19) NOTNULL DEFAULT 0');
// populate with ids
$i = 0;
$res = $adb->query("SELECT newsletterid, crmid FROM tbl_s_newsletter_queue");
while ($row = $adb->fetchByAssoc($res, -1, false)) {
	$adb->pquery("UPDATE tbl_s_newsletter_queue SET queueid = ? WHERE newsletterid = ? AND crmid = ?", array(++$i, $row['newsletterid'], $row['crmid']));
}
// set as primary key
dropPrimaryKey('tbl_s_newsletter_queue');
$adb->query("ALTER TABLE tbl_s_newsletter_queue ADD PRIMARY KEY (queueid)");

// update the seq_table
$adb->getUniqueID('tbl_s_newsletter_queue');
if (Vtiger_Utils::CheckTable("tbl_s_newsletter_queue")) {
	$res = $adb->query("SELECT MAX(queueid) as id FROM tbl_s_newsletter_queue");
	$maxid = $adb->query_result_no_html($res, 0, 'id');
	if ($maxid > 0) {
		$res = $adb->pquery("UPDATE tbl_s_newsletter_queue_seq SET id = ?", array($maxid));
	}
}

$indexes = $adb->database->MetaIndexes('tbl_s_newsletter_queue');

// add the unique index
if (!array_key_exists('nlqueue_nlid_idx', $indexes)) {
	$index = $adb->datadict->CreateIndexSQL('nlqueue_nlid_idx', 'tbl_s_newsletter_queue', 'newsletterid,crmid', array('UNIQUE'));
	$adb->datadict->ExecuteSQLArray((Array)$index);
}

// remove useless index
$dropIndexes = array('NewIndex1');
foreach($indexes as $name => $index) {
	if (in_array($name, $dropIndexes)) {
		$sql = $adb->datadict->DropIndexSQL($name, 'tbl_s_newsletter_queue');
		if ($sql) $adb->datadict->ExecuteSQLArray($sql);
	}
}


$trans = array(
	'Newsletter' => array(
		'it_it' => array(
			'Scheduled' => 'Pianificata',
			'Sent' => 'Inviata',
			'Error' => 'Errore',
		),
		'en_us' => array(
			'Scheduled' => 'Scheduled',
			'Sent' => 'Sent',
			'Error' => 'Error',
		),
	),
);
$languages = vtlib_getToggleLanguageInfo();
foreach ($trans as $module=>$modlang) {
	foreach ($modlang as $lang=>$translist) {
		if (array_key_exists($lang,$languages)) {
			foreach ($translist as $label=>$translabel) {
				SDK::setLanguageEntry($module, $lang, $label, $translabel);
			}
		}
	}
}
