<?php
/*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************/
/* crmv@155145 */

require_once('modules/Conditionals/ConditionalsVersioning.php');
global $adb, $table_prefix, $mod_strings, $app_strings;

$smarty = new VteSmarty();
$versioning = ConditionalsVersioning::getInstance();

$subMode = $_REQUEST['sub_mode'];
$displayVersion = $_REQUEST['displayVersion'];

if ($subMode == 'closeVersion') {
	$versioning->closeVersion();
} elseif ($subMode == 'checkExportVersion') {
	$err_string = '';
	$versioning->checkExportVersion($err_string);
	if ($err_string != '') die($err_string);
} elseif ($subMode == 'exportVersion') {
	$versioning->exportVersion();
} elseif ($subMode == 'importVersion') {
	$err_string = '';
	$result = $versioning->importVersion($err_string);
	if ($result === false) $smarty->assign("ERROR_STRING", addslashes($err_string));
	include('modules/Conditionals/ListView.php');
} elseif ($subMode == 'checkDuplicates') {
	$ruleid = vtlib_purify($_REQUEST['ruleid']);
	$rulename = vtlib_purify($_REQUEST['rulename']);
	
	$query = 'select description from tbl_s_conditionals where description = ?';
	$params = array($rulename);
	if (!empty($ruleid)) {
		$query .= ' and ruleid <> ?';
		$params[] = $ruleid;
	}
	$result = $adb->pquery($query, $params);
	if($adb->num_rows($result) > 0) {
		echo 'duplicated';
	} else {
		echo '';
	}
	die;
}

if ($displayVersion == 'true') {
	$pending_version = $versioning->getPendingVersion();
	$smarty->assign('PENDING_VERSION', $pending_version['version']);
	$smarty->assign('CURRENT_VERSION', $versioning->getCurrentVersionNumber());
	$smarty->assign('PERM_VERSION_EXPORT', $versioning->isExportPermitted());
	$smarty->assign('PERM_VERSION_IMPORT', $versioning->isImportPermitted());
	$smarty->assign('CHECK_VERSION_IMPORT', $versioning->checkImportVersion());
	
	$smarty->assign('MOD', $mod_strings);
	$smarty->assign('APP', $app_strings);
	$smarty->display('modules/Conditionals/Version.tpl');
}