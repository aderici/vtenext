<?php

/* crmv@181161 */

if (vtlib_isModuleActive('Update')) {
	$VP = VTEProperties::getInstance();
	
	$docheck = $VP->get('update.check_updates');
	if ($docheck == 1) {
		require_once('modules/Update/AutoUpdater.php');
		$class = new AutoUpdater();
		$class->statusHandler();
	}
}
