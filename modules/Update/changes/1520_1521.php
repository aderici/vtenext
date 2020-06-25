<?php
global $adb, $table_prefix;

// crmv@127968

$type = $adb->datadict->ActualType('C');
Vtiger_Utils::AlterTable("{$table_prefix}_loginhistory","user_name $type(50)");
