<?php
/*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************/
/* crmv@150592 */

require_once('include/utils/UserInfoUtil.php');
global $adb, $table_prefix, $mod_strings, $app_strings;

$smarty = new VteSmarty();
$userInfoUtils = UserInfoUtils::getInstance();

$subMode = $_REQUEST['sub_mode'];
$displayVersion = $_REQUEST['displayVersion'];

if ($subMode == 'closeVersion') {
	$userInfoUtils->closeVersion_profile();
} elseif ($subMode == 'checkExportVersion') {
	$err_string = '';
	$userInfoUtils->checkExportVersion_profile($err_string);
	if ($err_string != '') die($err_string);
} elseif ($subMode == 'exportVersion') {
	$userInfoUtils->exportVersion_profile();
} elseif ($subMode == 'importVersion') {
	$err_string = '';
	$result = $userInfoUtils->importVersion_profile($err_string);
	if ($result === false) $smarty->assign("ERROR_STRING", addslashes($err_string));
	include('modules/Settings/ListProfiles.php');
}

if ($displayVersion == 'true') {
	$pending_version = $userInfoUtils->getPendingVersion_profile();
	$smarty->assign('PENDING_VERSION', $pending_version['version']);
	$smarty->assign('CURRENT_VERSION', $userInfoUtils->getCurrentVersionNumber_profile());
	$smarty->assign('PERM_VERSION_EXPORT', $userInfoUtils->isExportPermitted_profile());
	$smarty->assign('PERM_VERSION_IMPORT', $userInfoUtils->isImportPermitted_profile());
	$smarty->assign('CHECK_VERSION_IMPORT', $userInfoUtils->checkImportVersion_profile());
	
	$smarty->assign('MOD', $mod_strings);
	$smarty->assign('APP', $app_strings);
	$smarty->display('Settings/ListRolesVersion.tpl');
}