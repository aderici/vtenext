<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/
global $app_strings, $mod_strings, $current_language, $currentModule, $theme,$current_user,$adb,$log;

require_once('modules/Webforms/Webforms.php');
require_once('modules/Webforms/model/WebformsModel.php');

Webforms::checkAdminAccess($current_user);
$webformFields=Webforms::getFieldInfos($_REQUEST["targetmodule"]);

$smarty = new VteSmarty();

$category = getParentTab();

$smarty->assign('WEBFORM',new Webforms_Model());
$smarty->assign('WEBFORMFIELDS',$webformFields);
$smarty->assign("THEME", $theme);
$smarty->assign('MOD', $mod_strings);
$smarty->assign('APP', $app_strings);
$smarty->assign('MODULE', $currentModule);
$smarty->assign('CATEGORY', $category);
$smarty->assign('CHECK', $tool_buttons);
$smarty->assign('IMAGE_PATH', "themes/$theme/images/");
$smarty->assign('CALENDAR_LANG','en');
$smarty->assign('LANGUAGE',$current_language);
$smarty->assign('DATE_FORMAT', $current_user->date_format);
//crmv@162158
require_once('modules/com_vtiger_workflow/VTTaskManager.inc');
require_once('modules/com_vtiger_workflow/tasks/VTEmailTask.inc');
$task = new VTEmailTask();
$metaVariables = $task->getMetaVariables(false,true);
$smarty->assign('META_VARIABLES', $metaVariables);
//crmv@162158e
$smarty->display(vtlib_getModuleTemplate($currentModule,'FieldsView.tpl'));
?>
