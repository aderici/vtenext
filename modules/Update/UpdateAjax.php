<?php
/*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************/
 
/* crmv@181161 crmv@182073 */

global $current_user;

$action = $_REQUEST['subaction'];

if (!is_admin($current_user)) {
	$result = array('success' => false, 'error' => 'Not authorized');
	die(Zend_Json::encode($result));
}

require_once('modules/Update/Update.php');
require_once('modules/Update/AutoUpdater.php');

// crmv@183486
if ($_REQUEST['file'] == 'DoUpdate') {
	require('DoUpdate.php');
	return;
}
// crmv@183486e

$AU = new AutoUpdater();

$result = array('success' => false);

try {

	if ($action == 'popup_seen') {
		$AU->setPopupSeen($current_user);
		$result['success'] = true;
	// crmv@199352
	} elseif ($action == 'force_check') {
		$AU->forceCron();
		$result['success'] = true;
		$result['message'] = getTranslatedString('LBL_CRON_FORCED', 'Update');
	// crmv@199352e
	} elseif ($action == 'remind_update') {
		$when = $_REQUEST['when'];
		if ($AU->canRemindUpdate($current_user)) {
			$AU->remindUpdate($current_user, $when);
			$result['success'] = true;
		} else {
			$result['error'] = getTranslatedString('LBL_ALREADY_CHOSEN', 'Update');
		}
	} elseif ($action == 'ignore_update') {
		$status = $AU->getStatus();
		if ($AU->canIgnoreUpdate($current_user)) {
			$AU->ignoreUpdate($current_user);
			$result['success'] = true;
		} else {
			$result['error'] = getTranslatedString('LBL_ALREADY_CHOSEN', 'Update');
		}
	
	} elseif ($action == 'show_diff') {
		$file = $AU->getDiffFile();
		
		if ($file) {
			$result['data'] = file_get_contents($file);
			$result['success'] = true;
		} else {
			$result['error'] = 'No log available';
		}
	
	// crmv@183486
	} elseif ($action == 'cancel_update') {
		if ($AU->canCancelUpdate($current_user)) {
			$AU->cancelUpdate($current_user);
			$result['success'] = true;
		} else {
			$result['error'] = getTranslatedString('LBL_CANNOT_CANCEL', 'Update');
		}
	// crmv@183486e
	
	} else {
		$result['error'] = 'Unknown action specified';
	}

} catch (Exception $e) {
	$result['error'] = $e->getMessage();
}


die(Zend_Json::encode($result));
