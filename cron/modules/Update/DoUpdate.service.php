<?php

/* crmv@181161 */

if (vtlib_isModuleActive('Update')) {
	require_once('modules/Update/AutoUpdater.php');
	$AU = new AutoUpdater();
	
	if ($AU->shouldUpdateNow()) {
		$AU->startUpdate();
	}
}
