<?php
global $adb, $table_prefix;
$servicesModuleInstance = Vtiger_Module::getInstance('Services');
$timecardsModuleInstance = Vtiger_Module::getInstance('Timecards');
$adb->pquery("update {$table_prefix}_field set typeofdata = ? where tabid = ? and fieldname = ?", array('V~M',14,'assigned_user_id'));
$adb->pquery("update {$table_prefix}_field set typeofdata = ? where tabid = ? and fieldname = ?", array('V~M',$servicesModuleInstance->id,'assigned_user_id'));
$adb->pquery("update {$table_prefix}_field set typeofdata = ? where tabid = ? and fieldname = ?", array('V~M',$timecardsModuleInstance->id,'assigned_user_id'));