<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * Portions created by CRMVILLAGE.BIZ are Copyright (C) CRMVILLAGE.BIZ.
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/

/* crmv@151308 */

global $allow_exports, $mod_strings, $app_strings, $theme, $current_user;

$module = vtlib_purify($_REQUEST['module']);

//Security Check
if(isPermitted($module,"Export") != "yes") {
	$allow_exports="none";
}

if ($allow_exports=='none' || ( $allow_exports=='admin' && ! is_admin($current_user))) {
	$smarty = new VteSmarty();
	$smarty->assign('APP',$app_strings);
	$smarty->assign('MOD',$mod_strings);
	$smarty->assign("THEME", $theme);
	$smarty->display(vtlib_getModuleTemplate('Vtiger','OperationNotPermitted.tpl'));	
	exit;
}

$search_type = vtlib_purify($_REQUEST['search_type']);
$export_data = vtlib_purify($_REQUEST['export_data']);
$ids = explode(";", vtlib_purify($_REQUEST['idstring'])); // crmv@37463

$ExpUtils = ExportUtils::getInstance($module);
$ExpUtils->doExport($search_type, $export_data, $ids);

exit;
