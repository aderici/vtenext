<?php
/*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************/
 
/* crmv@183486 */

global $current_user;
if (!is_admin($current_user)) die('Unauthorized');

global $theme, $mod_strings, $app_strings;

require_once('modules/Update/AutoUpdater.php');

$AU = new AutoUpdater();

$smarty = new VteSmarty();

$smarty->assign('APP', $app_strings);
$smarty->assign('MOD', $mod_strings);
$smarty->assign('MODULE', $currentModule);
$smarty->assign("THEME", $theme);
$smarty->assign('IMAGE_PATH', "themes/$theme/images/");
$smarty->assign("DATE_FORMAT", $current_user->date_format);

if (!$AU->canCancelUpdate($current_user)) {
	$smarty->assign('TEXT', getTranslatedString('LBL_CANNOT_CANCEL', 'Update'));
	$smarty->display("AccessDenied.tpl");
	die();
}

$info = $AU->getInfo();

list($sdate, $stime) = explode(' ', $info['scheduled_time']);

$text = getTranslatedString('LBL_CANCEL_UPDATE_TEXT', 'Update');
$text = str_replace(array('{date}', '{hour}'), array(getDisplayDate($sdate), substr($stime, 0, 5)), $text);
$smarty->assign("CANCEL_TEXT", $text);

$smarty->display('modules/Update/CancelUpdate.tpl');
