<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
*
 ********************************************************************************/

require_once("PortalConfig.php");
require_once("Smarty_setup.php"); // crmv@198415
require_once("include/utils/utils.php");

SDK::loadGlobalPhp(); // crmv@168297

$smarty = new VTECRM_Smarty();

if (!empty($_REQUEST['login_language'])){
	// crmv@127527
	global $default_language, $languages;
	if (array_key_exists($_REQUEST['login_language'], $languages)) {
		$default_language = $_REQUEST['login_language'];
	}
	// crmv@127527e
}
$smarty->assign('LOGINLANGUAGE',$default_language);

loadTranslations(); // crmv@168297

if($_REQUEST['mail_send_message'] != '')
{
	$mail_send_message = explode("@@@",$_REQUEST['mail_send_message']);
	
	$smarty->assign('MAILSENDMESSAGE', $mail_send_message);

}

elseif($_REQUEST['param'] == 'forgot_password')
{
// 	$list = GetForgotPasswordUI();
// 	GetForgotPasswordUI();
	$smarty->assign('FORGOTPASSWORD', true);
	
//         echo $list;
}
elseif($_REQUEST['param'] == 'sign_up')
{
	echo 'Sign Up..........';
	exit;
}

$smarty->display('supportpage.tpl');
?>
