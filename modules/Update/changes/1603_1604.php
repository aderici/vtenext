<?php

global $adb, $table_prefix;

// useful functions
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

// create the special relation table
if(!Vtiger_Utils::CheckTable($table_prefix.'_targets_cvrel')) {
	$schema = '<?xml version="1.0"?>
				<schema version="0.3">
				  <table name="'.$table_prefix.'_targets_cvrel">
				  <opt platform="mysql">ENGINE=InnoDB</opt>
				    <field name="targetid" type="I" size="19">
						<KEY/>
				    </field>
					<field name="objectid" type="I" size="19">
						<KEY/>
					</field>
					<field name="cvtype" type="C" size="31">
						<KEY/>
				    </field>
				    <field name="formodule" type="C" size="31">
						<KEY/>
				    </field>
				    <field name="status" type="I" size="19">
						<DEFAULT value="0"/>
					</field>
				    <field name="last_sync" type="T">
						<default value="0000-00-00 00:00:00"/>
				    </field>
					<index name="targets_cvrel_type_idx">
				      <col>cvtype</col>
				    </index>
				  </table>
				</schema>';
	$schema_obj = new adoSchema($adb->database);
	$schema_obj->ExecuteSchema($schema_obj->ParseSchemaString($schema));
}

if(!Vtiger_Utils::CheckTable($table_prefix.'_targets_cvrel_sync')) {
	$schema = '<?xml version="1.0"?>
				<schema version="0.3">
				  <table name="'.$table_prefix.'_targets_cvrel_sync">
				  <opt platform="mysql">ENGINE=InnoDB</opt>
				    <field name="targetid" type="I" size="19">
						<KEY/>
				    </field>
				    <field name="formodule" type="C" size="31">
						<KEY/>
				    </field>
				    <field name="crmid" type="I" size="19">
						<KEY/>
					</field>
				  </table>
				</schema>';
	$schema_obj = new adoSchema($adb->database);
	$schema_obj->ExecuteSchema($schema_obj->ParseSchemaString($schema));
}


$result = $adb->pquery("SELECT target_typeid FROM {$table_prefix}_target_type WHERE target_type = ?", array('TargetTypeDynamic'));
if ($result && $adb->num_rows($result) == 0) {
	$field = Vtecrm_Field::getInstance('target_type', Vtecrm_Module::getInstance('Targets'));
	if ($field) {
		$field->setPicklistValues(array('TargetTypeDynamic'));
	}
}

$fields = array(
	'target_sync_type' => array('module'=>'Targets','block'=>'LBL_TARGETS_INFORMATION','name'=>'target_sync_type',	'label'=>'TargetSyncType',	'uitype'=>'15',	 'columntype'=>'C(63)','typeofdata'=>'V~O', 'helpinfo' => 'LBL_TARGETS_SYNC_TYPE_INFO', 'picklist' => array('TargetSyncIncremental', 'TargetSyncComplete')),
);

Update::create_fields($fields);

moveFieldAfter('Targets', 'target_sync_type', 'target_type');


$result = $adb->pquery("SELECT cronid FROM {$table_prefix}_cronjobs WHERE cronname = ?", array('DynamicTargets'));
if ($adb->num_rows($result) == 0) {
	require_once('include/utils/CronUtils.php');
	$CU = CronUtils::getInstance();
	
	$cj = new CronJob();
	$cj->name = 'DynamicTargets';
	$cj->active = 1;
	$cj->singleRun = false;
	$cj->fileName = 'cron/modules/Targets/DynamicTargets.service.php';
	$cj->timeout = 7200;	// 2h timeout
	$cj->repeat = 21600;	// run every 6 hours
	$CU->insertCronJob($cj);
}


// change the pricebook productrel table

$table = $table_prefix.'_pricebookproductrel';

// add the column
$adb->addColumnToTable($table, 'pbrelid', 'INT(19) NOTNULL DEFAULT 0');
// populate with ids
$i = 0;
$res = $adb->query("SELECT pricebookid, productid FROM $table");
while ($row = $adb->fetchByAssoc($res, -1, false)) {
	$adb->pquery("UPDATE $table SET pbrelid = ? WHERE pricebookid = ? AND productid = ?", array(++$i, $row['pricebookid'], $row['productid']));
}
// set as primary key
dropPrimaryKey($table);
$adb->query("ALTER TABLE $table ADD PRIMARY KEY (pbrelid)");

// update the seq_table
$adb->getUniqueID($table);
if (Vtiger_Utils::CheckTable("$table")) {
	$res = $adb->query("SELECT MAX(pbrelid) as id FROM $table");
	$maxid = $adb->query_result_no_html($res, 0, 'id');
	if ($maxid > 0) {
		$res = $adb->pquery("UPDATE {$table}_seq SET id = ?", array($maxid));
	}
}

$indexes = $adb->database->MetaIndexes($table);

// add the unique index
if (!array_key_exists('pbprodrel_pb_prod_idx', $indexes)) {
	$index = $adb->datadict->CreateIndexSQL('pbprodrel_pb_prod_idx', $table, 'pricebookid,productid', array('UNIQUE'));
	$adb->datadict->ExecuteSQLArray((Array)$index);
}

// remove useless index
$dropIndexes = array('pricebookproductrel_pbid_idx');
foreach($indexes as $name => $index) {
	if (in_array($name, $dropIndexes)) {
		$sql = $adb->datadict->DropIndexSQL($name, $table);
		if ($sql) $adb->datadict->ExecuteSQLArray($sql);
	}
}


$trans = array(
	'Targets' => array(
		'it_it' => array(
			'TargetTypeDynamic' => 'Dinamico',
			'TargetSyncType' => 'Tipo sincronizzazione',
			'TargetSyncIncremental' => 'Incrementale',
			'TargetSyncComplete' => 'Sincronizzata',
			'LBL_TARGETS_SYNC_TYPE_INFO' => 'Per target dinamici: Incrementale aggiunge solamente record, Sincronizzata rimuove i record non presenti in nessun filtro e report.',
			'LBL_DYNAMIC_FILTERS' => 'Filtri dinamici',
			'LBL_APPLIED_FILTERS' => 'Filtri applicati',
			'LBL_APPLIED_REPORTS' => 'Report applicati',
			'LBL_NO_DYNAMIC_FILTERS' => 'Nessun filtro o report applicati. Puoi aggiungerne tramite le relazioni a destra.',
			'LBL_DYNAMIC_FILTERS_AS_ADMIN' => 'I filtri e report applicati verranno eseguiti come utente amministratore, quindi senza restrizioni di visibilità.',
		),
		'en_us' => array(
			'TargetTypeDynamic' => 'Dynamic',
			'TargetSyncType' => 'Synchronization type',
			'TargetSyncIncremental' => 'Incremental',
			'TargetSyncComplete' => 'Mirrored',
			'LBL_TARGETS_SYNC_TYPE_INFO' => 'For dynamic targets: Incremental only adds records, Complete also removes not present in any filter and report.',
			'LBL_DYNAMIC_FILTERS' => 'Dynamic filters',
			'LBL_APPLIED_FILTERS' => 'Applied filters',
			'LBL_APPLIED_REPORTS' => 'Applied reports',
			'LBL_NO_DYNAMIC_FILTERS' => 'No filters or reports applied. You can add one with the relation on the right.',
			'LBL_DYNAMIC_FILTERS_AS_ADMIN' => 'Filters and reports applied will be executed with administrative privileges, without visibility restrictions.',
		),
	),
	// crmv@150533
	'PriceBooks' => array(
		'it_it' => array(
			'PriceBooksPrices' => 'Prezzi di listino',
		),
		'en_us' => array(
			'PriceBooksPrices' => 'Price Book prices',
		),
	),
	// crmv@150533e
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
