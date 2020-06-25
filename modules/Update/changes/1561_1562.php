<?php

// crmv@142678

$campInst = Vtecrm_Module::getInstance('Campaigns');
$result = $adb->pquery("SELECT relation_id, name FROM {$table_prefix}_relatedlists WHERE tabid = ? AND related_tabid = ? AND name LIKE 'get_statistics_%'", array($campInst->id, 0));
if ($result) {
	while ($row = $adb->fetchByAssoc($result, -1, false)) {
		SDK::setTurboliftCount($row['relation_id'], $row['name']);
	}
}
