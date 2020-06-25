<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/

// crmv@161368

global $current_user;

$userid = intval($_REQUEST['userid']);

if ($userid > 0) {
	if (is_admin($current_user)) {
		$focus = CRMEntity::getInstance('Users');
		$r = $focus->remoteWipe($userid);
		$output = array('success' => true);
	} else {
		$output = array('success' => false, 'error' => getTranslatedString('LBL_PERMISSION'));
	}
} else {
	$output = array('success' => false, 'error' => 'Invalid user');
}

echo Zend_Json::encode($output);
die();
