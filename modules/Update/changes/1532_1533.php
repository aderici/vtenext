<?php

/* crmv@134727 */

// table for report generation time

$name = "{$table_prefix}_report_stats";
$schema_table = '<?xml version="1.0"?>
<schema version="0.3">
  <table name="'.$name.'">
  <opt platform="mysql">ENGINE=InnoDB</opt>
    <field name="reportid" type="I" size="19">
      <KEY/>
    </field>
    <field name="userid" type="I" size="19">
      <KEY/>
    </field>
    <field name="generatedtime" type="T">
		<DEFAULT value="0000-00-00 00:00:00"/>
    </field>
    <field name="rows" type="I" size="19"/>
    <index name="report_stats_user_idx">
		<col>userid</col>
    </index>
  </table>
</schema>';
if(!Vtiger_Utils::CheckTable($name)) {
	$schema_obj = new adoSchema($adb->database);
	$schema_obj->ExecuteSchema($schema_obj->ParseSchemaString($schema_table));
}
