<?php
global $adb, $table_prefix;

// crmv@144893
$result = $adb->pquery("select cronid from {$table_prefix}_cronjobs where cronname = ?", array('UpdateResources'));
if ($adb->num_rows($result) == 0) {
	require_once('include/utils/CronUtils.php');
	$CU = CronUtils::getInstance();
	
	$cj = new CronJob();
	$cj->name = 'UpdateResources';
	$cj->active = 1;
	$cj->singleRun = false;
	$cj->fileName = 'cron/modules/Resources/UpdateResources.service.php';
	$cj->timeout = 180;		// 3min timeout
	$cj->repeat = 120;		// run every 2 min
	$CU->insertCronJob($cj);
}
