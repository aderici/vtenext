<?php
global $adb, $table_prefix;

$adb->addColumnToTable('tbl_s_conditionals_rules', 'module', 'C(25)');

$result = $adb->query("select tbl_s_conditionals.ruleid, tabid
	from tbl_s_conditionals
	inner join tbl_s_conditionals_rules on tbl_s_conditionals.ruleid = tbl_s_conditionals_rules.ruleid
	inner join {$table_prefix}_field on tbl_s_conditionals.fieldid = {$table_prefix}_field.fieldid
	group by tbl_s_conditionals.ruleid, tabid");
if ($result && $adb->num_rows($result) > 0) {
	while($row=$adb->fetchByAssoc($result)) {
		$moduleInstance = Vtecrm_Module::getInstance($row['tabid']);
		$adb->pquery("update tbl_s_conditionals_rules set module = ? where ruleid = ?", array($moduleInstance->name,$row['ruleid']));
	}
}
