<?php
/***************************************************************************************
 * The contents of this file are subject to the CRMVILLAGE.BIZ VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is:  CRMVILLAGE.BIZ VTECRM
 * The Initial Developer of the Original Code is CRMVILLAGE.BIZ.
 * Portions created by CRMVILLAGE.BIZ are Copyright (C) CRMVILLAGE.BIZ.
 * All Rights Reserved.
 ***************************************************************************************/

if ($_REQUEST['file'] == 'Folders') {
	global $currentModule, $app_strings, $mod_strings;
	$focus = CRMEntity::getInstance($currentModule);
	$smarty = new VteSmarty();
	$smarty->assign('MOD', $mod_strings);
	$smarty->assign('APP', $app_strings);
	$smarty->assign('FOCUS', $focus);
	$focus->setAccount($_REQUEST['account']);
	$current_account = $_REQUEST['account'];
}

$smarty->assign('DIV_DIMENSION', array('Folders'=>'0%','ListViewContents'=>'24%','PreDetailViewContents'=>'60%','DetailViewContents'=>'61%','TurboliftContents'=>'15%'));
$smarty->assign('VIEW', 'list');

try {
	($focus->force_check_imap_connection) ? $check = $focus->getZendMailStorageImap() : $check = true;	//crmv@125629
	if ($current_account != 'all' && $check) {	//crmv@125629
		$smarty->assign('FOLDERS', $focus->getFoldersList());
	}
} catch (Exception $e) {}

if ($_REQUEST['file'] == 'Folders') {
	$smarty->display("modules/Messages/Folders.tpl");
}
?>