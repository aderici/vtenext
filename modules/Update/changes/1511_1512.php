<?php

// crmv@123658

require_once('modules/SLA/SLA.php');
$SLA = new SLA();
$SLA->migrateConfig();

// crmv@124036

$adb->pquery("UPDATE {$table_prefix}_field SET fieldname = ? WHERE fieldname = ? AND uitype = ?", array('createdtime', 'CreatedTime', 70));