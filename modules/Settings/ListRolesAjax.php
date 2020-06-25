<?php
/*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************/
/* crmv@150266 */

require_once('include/utils/UserInfoUtil.php');
global $adb, $table_prefix, $mod_strings, $app_strings;

$smarty = new VteSmarty();
$userInfoUtils = UserInfoUtils::getInstance();

$subMode = $_REQUEST['sub_mode'];
$displayVersion = $_REQUEST['displayVersion'];

if ($subMode == 'closeVersion') {
	$userInfoUtils->closeVersion_role();
} elseif ($subMode == 'checkExportVersion') {
	$err_string = '';
	$userInfoUtils->checkExportVersion_role($err_string);
	if ($err_string != '') die($err_string);
} elseif ($subMode == 'exportVersion') {
	$userInfoUtils->exportVersion_role();
} elseif ($subMode == 'importVersion') {
	$err_string = '';
	$result = $userInfoUtils->importVersion_role($err_string);
	if ($result === false) $smarty->assign("ERROR_STRING", addslashes($err_string));
	include('modules/Settings/listroles.php');
}

if ($displayVersion == 'true') {
	$pending_version = $userInfoUtils->getPendingVersion_role();
	$smarty->assign('PENDING_VERSION', $pending_version['version']);
	$smarty->assign('CURRENT_VERSION', $userInfoUtils->getCurrentVersionNumber_role());
	$smarty->assign('PERM_VERSION_EXPORT', $userInfoUtils->isExportPermitted_role());
	$smarty->assign('PERM_VERSION_IMPORT', $userInfoUtils->isImportPermitted_role());
	$smarty->assign('CHECK_VERSION_IMPORT', $userInfoUtils->checkImportVersion_role());

	$smarty->assign('MOD', $mod_strings);
	$smarty->assign('APP', $app_strings);
	$smarty->display('Settings/ListRolesVersion.tpl');
}