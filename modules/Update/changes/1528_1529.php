<?php

/* crmv@126696 */

// table for process-newsletter mapping
$name = "{$table_prefix}_running_processes_nl";
$schema_table = '<?xml version="1.0"?>
<schema version="0.3">
  <table name="'.$name.'">
  <opt platform="mysql">ENGINE=InnoDB</opt>
    <field name="newsletterid" type="I" size="19">
      <KEY/>
    </field>
    <field name="processmakerid" type="I" size="19"/>
    <field name="running_process" type="I" size="19"/>
    <field name="elementid" type="C" size="100"/>
    <field name="actionid" type="I" size="19"/>
    <field name="subject" type="C" size="100"/>
    <field name="body" type="XL"/>
    <index name="running_proc_nl_pmaker_idx">
		<col>processmakerid</col>
		<col>elementid</col>
    </index>
  </table>
</schema>';
if(!Vtiger_Utils::CheckTable($name)) {
	$schema_obj = new adoSchema($adb->database);
	$schema_obj->ExecuteSchema($schema_obj->ParseSchemaString($schema_table));
}

// create target field
$fields = array(
	'listhash'	=> array('module'=>'Targets', 'block'=>'LBL_TARGETS_INFORMATION',	'name'=>'listhash',	'label'=>'ListHash', 'typeofdata'=>'V~O', 'uitype'=>1, 'columntype' => 'C(63)', 'readonly'=>100, 'masseditable'=>0, 'quickcreate'=>0),
);
$fieldRet = Update::create_fields($fields);

$trans = array(
	'Settings' => array(
		'it_it' => array(
			'LBL_PM_ACTION_SendNewsletter' => 'Invia newsletter',
			'LBL_CREATE_NEW_CAMPAIGN' => 'Crea nuova ogni volta',
			'LBL_REUSE_CAMPAIGN' => 'Crea e poi riutilizza la stessa campagna',
			'LBL_FROM_PROCESS' => 'Dal processo',
			'LBL_SELECT_STATIC' => 'Seleziona record statico',
			'LBL_SELECT_FROM_PROCESS' => 'Seleziona dal processo',
		),
		'en_us' => array(
			'LBL_PM_ACTION_SendNewsletter' => 'Send newsletter',
			'LBL_CREATE_NEW_CAMPAIGN' => 'Create new one every time',
			'LBL_REUSE_CAMPAIGN' => 'Create and then reuse the same campaign',
			'LBL_FROM_PROCESS' => 'From process',
			'LBL_SELECT_STATIC' => 'Select static record',
			'LBL_SELECT_FROM_PROCESS' => 'Select from process',
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


/* crmv@125816 */

// remove some useless indexes

$table = $table_prefix."_crmentityrel";
$dropIndexes = array("crmentityrel_crmid_idx", "crmentityrel_inversekey_idx");
$indexes = $adb->database->MetaIndexes($table);

// drop indexes
foreach($indexes as $name => $index) {
	if (in_array($name, $dropIndexes)) {
		$sql = $adb->datadict->DropIndexSQL($name, $table);
		if ($sql) $adb->datadict->ExecuteSQLArray($sql);
	}
}

$table = $table_prefix.'_crmentityrel_ord';
// create the N-N ordered relation table
$schema = 
	'<?xml version="1.0"?>
	<schema version="0.3">
		<table name="'.$table.'">
			<opt platform="mysql">ENGINE=InnoDB</opt>
			<field name="crmid" type="I" size="19">
				<KEY/>
			</field>
			<field name="module" type="C" size="63">
				<NOTNULL/>
			</field>
			<field name="relcrmid" type="I" size="19">
				<KEY/>
			</field>
			<field name="relmodule" type="C" size="63">
				<NOTNULL/>
			</field>
			<index name="crmentityrelord_module_idx">
				<col>module</col>
			</index>
			<index name="crmentityrelord_relmodule_idx">
				<col>relmodule</col>
			</index>
			<index name="crmentityrelord_relcrmid_idx">
				<col>relcrmid</col>
			</index>
		</table>
	</schema>';
if (!Vtiger_Utils::CheckTable($table)) {
	$schema_obj = new adoSchema($adb->database);
	$schema_obj->ExecuteSchema($schema_obj->ParseSchemaString($schema));
	
	// copy the targets relations to the new table!
	$adb->pquery(
		"INSERT INTO {$table} (crmid, module, relcrmid, relmodule) 
		SELECT crmid, module, relcrmid, relmodule
		FROM {$table_prefix}_crmentityrel
		WHERE module = ? AND relmodule = ?",
		array('Targets', 'Targets')
	);
	
	// remove the relation from the old table
	$adb->pquery(
		"DELETE FROM {$table_prefix}_crmentityrel WHERE module = ? AND relmodule = ?",
		array('Targets', 'Targets')
	);
}

// change the related functions
$targetInst = Vtecrm_Module::getInstance('Targets');
if ($targetInst) {
	// delete old ones
	$adb->pquery("DELETE FROM {$table_prefix}_relatedlists WHERE tabid = ? AND related_tabid = ?", array($targetInst->id, $targetInst->id));
	// insert new ones
	$targetInst->setRelatedList($targetInst, 'Parent Targets', array(), 'get_parents_list');
	$targetInst->setRelatedList($targetInst, 'Included Targets', array('ADD', 'SELECT'), 'get_children_list');
}


$trans = array(
	'Targets' => array(
		'it_it' => array(
			'Parent Targets' => 'Target padri',
			'Included Targets' => 'Target inclusi',
		),
		'en_us' => array(
			'Parent Targets' => 'Parent Targets',
			'Included Targets' => 'Included Targets',
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
