<?php
global $adb, $table_prefix;

require_once('modules/Settings/ProcessMaker/ProcessMakerUtils.php');

// add column version to _processmaker
$PMUtils = ProcessMakerUtils::getInstance();
$default_version = $PMUtils->startVersionNumber;
$cols = $adb->getColumnNames($table_prefix.'_processmaker');
if (!in_array('version', $cols)) {
	$adb->addColumnToTable($table_prefix.'_processmaker', 'version', 'C(10)');
	$adb->pquery("update {$table_prefix}_processmaker set version = ?", array($default_version));
}
$cols = $adb->getColumnNames($table_prefix.'_processmaker_versions');
if (!in_array('version', $cols)) {
	$adb->addColumnToTable($table_prefix.'_processmaker_versions', 'version', 'C(10)');
	$adb->pquery("update {$table_prefix}_processmaker_versions set version = ?", array($default_version));
}

SDK::setLanguageEntries('Settings', 'LBL_INCREMENT_VERSION', array('it_it'=>'Incrementa versione','en_us'=>'Increment version'));
SDK::setLanguageEntries('Settings', 'LBL_DOWNLOAD_BPMN', array('it_it'=>'Scarica BPMN','en_us'=>'Download BPMN'));
SDK::setLanguageEntries('Settings', 'LBL_DOWNLOAD_VTEBPMN', array('it_it'=>'Scarica VTE BPMN','en_us'=>'Download VTE BPMN'));
SDK::setLanguageEntries('Settings', 'LBL_UPLOAD_VTEBPMN', array('it_it'=>'Importa VTE BPMN','en_us'=>'Upload VTE BPMN'));
