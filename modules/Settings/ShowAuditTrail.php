<?php

/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/

/* crmv@164355 */

require_once('modules/Settings/AuditTrail.php');

global $app_strings, $mod_strings;
global $current_language, $current_user;
$current_module_strings = return_module_language($current_language, 'Settings');

$log = LoggerManager::getLogger('audit_trial');

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

$focus = new AuditTrail();
$smarty = new VteSmarty();

$category = getParenttab();

$userid = intval($_REQUEST['userid']);

//Retreiving the start value from request
if(isset($_REQUEST['start']) && $_REQUEST['start'] != '') {
	$start = $_REQUEST['start'];
} else {
	$start=1;
}

$no_of_rows = $focus->countEntries($userid);

//Retreive the Navigation array
$LVU = ListViewUtils::getInstance();
$navigation_array = $LVU->getNavigationValues($start, $no_of_rows, '20');

$start_rec = $navigation_array['start'];
$end_rec = $navigation_array['end_val'];
$record_string= $app_strings['LBL_SHOWING']." " .$start_rec." - ".$end_rec." " .$app_strings['LBL_LIST_OF'] ." ".$no_of_rows;

$navigationOutput = $LVU->getTableHeaderNavigation($navigation_array, $url_string,"Settings","ShowAuditTrail",'');

$smarty->assign("MOD", $current_module_strings);
$smarty->assign("CMOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("THEME_PATH",$theme_path);
$smarty->assign("LIST_HEADER",$focus->getAuditTrailHeader());
$smarty->assign("LIST_ENTRIES",$focus->getAuditTrailEntries($userid, $navigation_array)); // crmv@164355
$smarty->assign("RECORD_COUNTS", $record_string);
$smarty->assign("NAVIGATION", $navigationOutput);
$smarty->assign("USERID", $userid);
$smarty->assign("CATEGORY",$category);

if($_REQUEST['ajax'] !='')
	$smarty->display("ShowAuditTrailContents.tpl");
else	
	$smarty->display("ShowAuditTrail.tpl");
