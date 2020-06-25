<?php
global $adb, $table_prefix;

$result = $adb->pquery("select * from {$table_prefix}_cronjobs where cronname = ?", array('CleanMessageStorage'));
if ($adb->num_rows($result) == 0) {
	require_once('include/utils/CronUtils.php');
	$CU = CronUtils::getInstance();
	
	$cj = new CronJob();
	$cj->name = 'CleanMessageStorage';
	$cj->active = 1;
	$cj->singleRun = false;
	$cj->fileName = 'cron/modules/Messages/CleanStorage.service.php';
	$cj->timeout = 300;		// 5min timeout
	$cj->repeat = 3600;		// run every hour
	$CU->insertCronJob($cj);
}

SDK::setLanguageEntries('Messages', 'LBL_IMAP_SEARCH_ERROR_1', array('it_it'=>'Intervallo di ricerca non impostato','en_us'=>'Search interval is not set'));
SDK::setLanguageEntries('Messages', 'LBL_IMAP_SEARCH_ERROR_2', array('it_it'=>'Nessuna condizione di ricerca impostata','en_us'=>'Search condition is not defined'));
SDK::setLanguageEntries('Messages', 'LBL_IMAP_SEARCH_ERROR_3', array('it_it'=>'Nessuna condizione di ricerca impostata','en_us'=>'Search condition is not defined'));
SDK::setLanguageEntries('Messages', 'LBL_IMAP_SEARCH_ERROR_4', array('it_it'=>'Alcune condizioni di ricerca non sono supportate via imap','en_us'=>'Some search comditions are not supported by imap.'));