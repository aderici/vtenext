<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/
global $currentModule;

$ajaxaction = $_REQUEST["ajxaction"];
// crmv@164122
if ($ajaxaction == "GETNOTIFICATION") {
	$modObj = CRMEntity::getInstance($currentModule);
	
	$record = intval($_REQUEST['record']);
	$setSeen = $_REQUEST['seen'];

	if ($record > 0 && $modObj->canUserEditRecord($record)) { // crmv@164122
		if ($setSeen != '') {
			// crmv@164122
			if ($setSeen  == '1') {
				$modObj->setRecordSeen($record);
			} else {
				$modObj->setRecordUnseen($record);
			}
			// crmv@164122e
		}

		// crmv@164122 - removed line
		
		$widgetInstance = $modObj->getWidget('DetailViewBlockCommentWidget');
		$unseenCount = $modObj->getUnseenCount();	//crmv@64325
		echo $unseenCount.':#:SUCCESS'.$widgetInstance->processItem($widgetInstance->getModel($record));
	} else {
		echo ':#:FAILURE';
	}

// crmv@43194e
}
