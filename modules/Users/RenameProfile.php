<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/
/* crmv@150592 */

/* crmv@184240 */
require_once('include/utils/UserInfoUtil.php');

if (!is_admin($current_user)) {
	die('Not authorized');
}

$profileid = vtlib_purify($_REQUEST['profileid']);
if(strtolower($default_charset) == 'utf-8') {	
	$profilename = $_REQUEST['profilename'];
	$profileDesc = $_REQUEST['description'];
} else {
	$profilename = utf8RawUrlDecode($_REQUEST['profilename']);
	$profileDesc = utf8RawUrlDecode($_REQUEST['description']);
}

$userInfoUtils = UserInfoUtils::getInstance();
$userInfoUtils->renameProfile($profileid, $profilename, $profileDesc);
