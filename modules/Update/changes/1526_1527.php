<?php
global $adb, $table_prefix;

SDK::setLanguageEntries('Settings', 'LBL_PM_ACTION_RelateStatic', array(
	'it_it'=>'Collega entità statiche',
	'en_us'=>'Link static entities',
));

if(!Vtiger_Utils::CheckTable($table_prefix.'_messages_inline_cache')) {
	$schema = '<?xml version="1.0"?>
				<schema version="0.3">
				  <table name="'.$table_prefix.'_messages_inline_cache">
		  			<opt platform="mysql">ENGINE=InnoDB</opt>
		  			<field name="messagesid" type="I" size="19">
		  			  <KEY/>
		  			</field>
		  			<field name="contentid" type="I" size="19">
		  			  <KEY/>
		  			</field>
				    <field name="cachedate" type="T">
				      <DEFAULT value="0000-00-00 00:00:00"/>
				    </field>
					<field name="content" type="B"/>
				  </table>
				</schema>';
	$schema_obj = new adoSchema($adb->database);
	$schema_obj->ExecuteSchema($schema_obj->ParseSchemaString($schema));
}

$result = $adb->pquery("select * from {$table_prefix}_cronjobs where cronname = ?", array('CleanInlineCache'));
if ($adb->num_rows($result) == 0) {
	require_once('include/utils/CronUtils.php');
	$CU = CronUtils::getInstance();
	
	$cj = new CronJob();
	$cj->name = 'CleanInlineCache';
	$cj->active = 1;
	$cj->singleRun = false;
	$cj->fileName = 'cron/modules/Messages/CleanInlineCache.service.php';
	$cj->timeout = 300;		// 5min timeout
	$cj->repeat = 14400;	// run every 4 hour
	$CU->insertCronJob($cj);
}