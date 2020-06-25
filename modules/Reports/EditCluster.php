<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/

/* crmv@128369 */

require_once('include/utils/utils.php');
require_once('modules/Reports/Reports.php');

global $app_strings, $mod_strings;
global $currentModule, $current_language, $current_user;
global $theme, $image_path;

$mode = '';
$reportid = intval($_REQUEST['reportid']);
$clusteridx = (isset($_REQUEST['clusteridx']) && $_REQUEST['clusteridx'] !== '' ? intval($_REQUEST['clusteridx']) : '' );
$module = vtlib_purify($_REQUEST['primodule']);

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

$smarty = new VteSmarty();
$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);
$smarty->assign("THEME_PATH", $theme_path);
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);

$CU = CRMVUtils::getInstance();
$repObj = Reports::getInstance();

$smarty->assign("REPORTID", $reportid);
$smarty->assign("CLUSTERIDX", $clusteridx);

$smarty->assign("PRIMARYMODULE",$module);
$smarty->assign("PRIMARYMODULE_LABEL",getTranslatedString($module, $module));

$repModules = $repObj->getAvailableModules();
unset($repModules['ProductsBlock']);

$smarty->assign("REPT_MODULES",$repModules);
$smarty->assign("COMPARATORS",$repObj->getAdvFilterOptions());

$smarty->assign("DATEFORMAT",$current_user->date_format);
$smarty->assign("JS_DATEFORMAT",parse_calendardate(getTranslatedString('NTC_DATE_FORMAT', 'APP_STRINGS')));

// preload some relations and fields
$preloadChain = array($module);
$preload_js = array(
	array(
		'type' => 'modules',
		'chain' => $preloadChain,
		'data' => $repObj->getModulesListForChain($reportid, $preloadChain),
	),
	array(
		'type' => 'fields',
		'fieldstype' => 'advfilter',
		'chain' => $preloadChain,
		'data' => $repObj->getAdvFiltersFieldsListForChain($reportid, $preloadChain),
	),
);
$smarty->assign("PRELOAD_JS", Zend_Json::encode($preload_js));

$JSGlobals = ( function_exists('getJSGlobalVars') ? getJSGlobalVars() : array() );
$smarty->assign('JS_GLOBAL_VARS', Zend_Json::encode($JSGlobals));

$smarty->display("modules/Reports/EditCluster.tpl");

