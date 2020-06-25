<?php
/*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************/
/* crmv@173186 */
require_once('include/utils/utils.php');
global $app_strings;
global $mod_strings;
global $currentModule;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
global $current_language;

$smarty = new VteSmarty();

$logUtils = LogUtils::getInstance();
$smarty->assign("CONFIG", $logUtils->getLogConfig());
$smarty->assign("GENERAL_CONFIG", $logUtils->getGlobalConfig());
$smarty->assign("OTHER_LOGS", $logUtils->getOtherLogs()); // crmv@181096

$smarty->assign("MOD", return_module_language($current_language,'Settings'));
$smarty->assign("CMOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("THEME", $theme);
$smarty->display('Settings/LogConfig.tpl');
