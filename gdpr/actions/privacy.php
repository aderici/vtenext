<?php
/*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************/

// crmv@161554

defined('BASEPATH') OR exit('No direct script access allowed');

global $CFG, $GPDRManager;

$smarty = new GDPR\SmartyConfig();

$privacyPolicyRequest = $GPDRManager->getPrivacyPolicy();
$privacyPolicy = $privacyPolicyRequest['privacy_policy'];

if (!$privacyPolicyRequest['success']) {
	$GPDRManager->showError(_T($privacyPolicyRequest['error']), '', true);
}

$contactId = $GPDRManager->getContactId();

$smarty->assign('BROWSER_TITLE', _T('browser_title_privacy'));
$smarty->assign('CONTACT_ID', $contactId);
$smarty->assign('PRIVACY_POLICY', $privacyPolicy);

$smarty->display('PrivacyPolicy.tpl');
