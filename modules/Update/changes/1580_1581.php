<?php
global $adb, $table_prefix;
if(!Vtiger_Utils::CheckTable($table_prefix.'_tab_versions')) {
	$schema = '<?xml version="1.0"?>
				<schema version="0.3">
				  <table name="'.$table_prefix.'_tab_versions">
				  <opt platform="mysql">ENGINE=InnoDB</opt>
					<field name="id" type="I" size="11">
				   	  <key/>
				    </field>
				    <field name="tabid" type="I" size="19"/>
				    <field name="version" type="C" size="10"/>
					<field name="createdtime" type="T">
						<default value="0000-00-00 00:00:00"/>
					</field>
					<field name="createdby" type="I" size="19"/>
					<field name="modifiedtime" type="T">
						<default value="0000-00-00 00:00:00"/>
					</field>
					<field name="modifiedby" type="I" size="19"/>
					<field name="closed" type="I" size="1">
						<notnull/>
						<default value="0"/>
				    </field>
					<field name="xml" type="XL"/>
				    <index name="idx_tab_version">
				      <col>tabid</col>
				      <col>version</col>
				    </index>
				  </table>
				</schema>';
	$schema_obj = new adoSchema($adb->database);
	$schema_obj->ExecuteSchema($schema_obj->ParseSchemaString($schema));
}
if(!Vtiger_Utils::CheckTable($table_prefix.'_tab_versions_import')) {
	$schema = '<?xml version="1.0"?>
				<schema version="0.3">
				  <table name="'.$table_prefix.'_tab_versions_import">
				  <opt platform="mysql">ENGINE=InnoDB</opt>
				    <field name="tabid" type="I" size="19">
				   	  <key/>
				    </field>
				    <field name="version" type="C" size="10">
				   	  <key/>
				    </field>
					<field name="sequence" type="I" size="10"/>
					<field name="xml" type="XL"/>
					<field name="status" type="C" size="10"/>
				  </table>
				</schema>';
	$schema_obj = new adoSchema($adb->database);
	$schema_obj->ExecuteSchema($schema_obj->ParseSchemaString($schema));
}
if(!Vtiger_Utils::CheckTable($table_prefix.'_tab_versions_rel')) {
	$schema = '<?xml version="1.0"?>
				<schema version="0.3">
				  <table name="'.$table_prefix.'_tab_versions_rel">
				  <opt platform="mysql">ENGINE=InnoDB</opt>
				    <field name="id" type="I" size="19">
				   	  <key/>
				    </field>
				    <field name="metalogid" type="I" size="19">
				   	  <key/>
				    </field>
				  </table>
				</schema>';
	$schema_obj = new adoSchema($adb->database);
	$schema_obj->ExecuteSchema($schema_obj->ParseSchemaString($schema));
}