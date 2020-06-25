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

if (!$GPDRManager->isPrivacyPolicyConfirmed()) {
	GDPR\Redirect::to('settings');
}

if (!$GPDRManager->hasDuplicates()) {
	GDPR\Redirect::to('detailview');
}

$smarty = new GDPR\SmartyConfig();

$contactId = $GPDRManager->getContactId();
$accessToken = $GPDRManager->getAccessToken();
$contactEmail = $GPDRManager->getContactEmail();

$smarty->assign('BROWSER_TITLE', _T('browser_title_merge'));
$smarty->assign('CONTACT_ID', $contactId);
$smarty->assign('ACCESS_TOKEN', $accessToken);
$smarty->assign('CONTACT_EMAIL', $contactEmail);

$duplicates = $GPDRManager->getContactDuplicates();
$smarty->assign('CONTACT_DUPLICATES', $duplicates);

$smarty->display('Merge.tpl');
