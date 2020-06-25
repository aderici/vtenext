<?php
/*+*************************************************************************************
* The contents of this file are subject to the VTECRM License Agreement
* ("licenza.txt"); You may not use this file except in compliance with the License
* The Original Code is: VTECRM
* The Initial Developer of the Original Code is VTECRM LTD.
* Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
* All Rights Reserved.
***************************************************************************************/
//crmv@62414
global $mod_strings, $app_strings, $currentModule, $current_user, $theme;
include_once('include/utils/utils.php');

$requestedfile = vtlib_purify($_REQUEST['requestedfile']);

if (is_numeric($requestedfile)) {
	$FS = FileStorage::getInstance();
	$attachment = $FS->getAttachmentId($requestedfile);
	if ($attachment !== null) {
		$requestedfile = "index.php?module=uploads&action=downloadfile&entityid={$requestedfile}&fileid={$attachment}";
	}
}

$smarty = new VteSmarty();
$smarty->assign('APP', $app_strings);
$smarty->assign('MOD', $mod_strings);
$smarty->assign('MODULE', $currentModule);
$smarty->assign('REQUESTED_FILE', $requestedfile);

$smarty->display('modules/Messages/ViewerJS.tpl');
