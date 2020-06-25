<?php 
/*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************/

// crmv@161554 crmv@163697

defined('BASEPATH') OR exit('No direct script access allowed');

global $CFG, $GPDRManager;

$GPDRManager->clear();

$contactId = $GPDRManager->getContactId();

$authTokenResult = $GPDRManager->getAuthToken();
if (!$authTokenResult['success']) {
	if ($authTokenResult['error'] === 'OPERATION_DENIED') {
		$GPDRManager->showOperationDenied($authTokenResult, true);
	} else {
		$GPDRManager->showError(_T($authTokenResult['error']), '', true);
	}
}

$smarty = new GDPR\SmartyConfig();

$authToken = $authTokenResult['token'];

$contactEmail = $GPDRManager->getContactEmail();

$smarty->assign('BROWSER_TITLE', _T('browser_title_verify'));
$smarty->assign('CONTACT_ID', $contactId);
$smarty->assign('CONTACT_EMAIL', $contactEmail);
$smarty->assign('AUTH_TOKEN', $authToken);

$smarty->display('Verify.tpl');
