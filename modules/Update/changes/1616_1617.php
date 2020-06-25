<?php

global $adb, $table_prefix;

if (isModuleInstalled('RecycleBin')) {
	$_SESSION['modules_to_update']['RecycleBin'] = 'packages/vte/optional/RecycleBin.zip';
}

// crmv@144125

if(!Vtiger_Utils::CheckTable($table_prefix.'_entity_displayname')) {
	$schema = '<?xml version="1.0"?>
				<schema version="0.3">
				  <table name="'.$table_prefix.'_entity_displayname">
				  <opt platform="mysql">ENGINE=InnoDB</opt>
				    <field name="crmid" type="I" size="19">
				      <KEY/>
				    </field>
				    <field name="setype" type="C" size="31">
						<NOTNULL/>
				    </field>
				    <field name="displayname" type="C" size="255"/>
				    <field name="lastupdate" type="T"/>
				    <index name="edn_setype_idx">
				      <col>setype</col>
				    </index>
				    <index name="edn_displayname_idx">
				      <col>displayname</col>
				    </index>
				  </table>
				</schema>';
	$schema_obj = new adoSchema($adb->database);
	$schema_obj->ExecuteSchema($schema_obj->ParseSchemaString($schema));
}

$ENU = EntityNameUtils::getInstance();
$ENU->rebuildForAll();

Update::info('A new cache for record names has been added. Please review your erpconnector imports');
Update::info('and activate the updateEntityNameCache function to update the cache after every import.');
Update::info('You can find an example in plugins/erpconnectorDir/classes.php');
Update::info("");
