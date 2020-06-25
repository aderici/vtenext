<?php
global $adb, $table_prefix;

$helpDeskModuleInstance = Vtiger_Module::getInstance('HelpDesk');
$projectPlanModuleInstance = Vtiger_Module::getInstance('ProjectPlan');
$projectTaskModuleInstance = Vtiger_Module::getInstance('ProjectTask');
$salesOrderModuleInstance = Vtiger_Module::getInstance('SalesOrder');

$adb->pquery("update {$table_prefix}_relatedlists set actions = ? where tabid = ? and related_tabid = ? and name = ?", array('ADD',$projectPlanModuleInstance->id,$helpDeskModuleInstance->id,'get_dependents_list'));
$adb->pquery("update {$table_prefix}_relatedlists set actions = ? where tabid = ? and related_tabid = ? and name = ?", array('ADD',$projectTaskModuleInstance->id,$helpDeskModuleInstance->id,'get_dependents_list'));
$adb->pquery("update {$table_prefix}_relatedlists set actions = ? where tabid = ? and related_tabid = ? and name = ?", array('ADD',$salesOrderModuleInstance->id,$projectTaskModuleInstance->id,'get_dependents_list'));

@unlink('Smarty/templates/themes/next/ListRoles.tpl');

if(!Vtiger_Utils::CheckTable($table_prefix.'_role_versions')) {
	$schema = '<?xml version="1.0"?>
				<schema version="0.3">
				  <table name="'.$table_prefix.'_role_versions">
				  <opt platform="mysql">ENGINE=InnoDB</opt>
				    <field name="id" type="I" size="11">
				      <KEY/>
				    </field>
				    <field name="version" type="C" size="10"/>
				    <field name="createdtime" type="T"/>
				    <field name="createdby" type="I" size="19"/>
				    <field name="modifiedtime" type="T"/>
				    <field name="modifiedby" type="I" size="19"/>
				    <field name="closed" type="I" size="1">
				      <DEFAULT value="0"/>
				    </field>
				    <field name="json" type="XL"/>
				    <index name="idx_version">
				      <col>version</col>
				    </index>
				  </table>
				</schema>';
	$schema_obj = new adoSchema($adb->database);
	$schema_obj->ExecuteSchema($schema_obj->ParseSchemaString($schema));
}
if(!Vtiger_Utils::CheckTable($table_prefix.'_role_versions_import')) {
	$schema = '<?xml version="1.0"?>
				<schema version="0.3">
				  <table name="'.$table_prefix.'_role_versions_import">
				  <opt platform="mysql">ENGINE=InnoDB</opt>
				    <field name="version" type="C" size="10">
				      <KEY/>
				    </field>
				    <field name="sequence" type="I" size="10"/>
				    <field name="json" type="XL"/>
				    <field name="status" type="C" size="10"/>
				  </table>
				</schema>';
	$schema_obj = new adoSchema($adb->database);
	$schema_obj->ExecuteSchema($schema_obj->ParseSchemaString($schema));
}
if(!Vtiger_Utils::CheckTable($table_prefix.'_role_versions_rel')) {
	$schema = '<?xml version="1.0"?>
				<schema version="0.3">
				  <table name="'.$table_prefix.'_role_versions_rel">
				  <opt platform="mysql">ENGINE=InnoDB</opt>
				    <field name="id" type="I" size="19">
				      <KEY/>
				    </field>
				    <field name="metalogid" type="I" size="19">
				      <KEY/>
				    </field>
				  </table>
				</schema>';
	$schema_obj = new adoSchema($adb->database);
	$schema_obj->ExecuteSchema($schema_obj->ParseSchemaString($schema));
}