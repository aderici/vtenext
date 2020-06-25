<?php
/* crmv@169362 */
function checkProcessesWidgetPermission($row) {
	require_once('modules/Settings/ProcessMaker/ProcessMakerUtils.php');
	$PMUtils = ProcessMakerUtils::getInstance();
	$resources = $PMUtils->getAdvancedPermissionsResources($_REQUEST['record']);
	return (!empty($resources));
}