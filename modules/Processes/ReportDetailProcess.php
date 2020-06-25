<?php
/*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************/
/* crmv@193096 */

global $mod_strings, $app_strings, $theme;
$smarty = new VteSmarty();

$processmakerid = intval($_REQUEST['id']);

$PMUtils = ProcessMakerUtils::getInstance();
$data = $PMUtils->retrieve($processmakerid);

include_once('vtlib/Vtecrm/Link.php');
$COMMONHDRLINKS = Vtiger_Link::getAllByType(Vtiger_Link::IGNORE_MODULE, Array('HEADERSCRIPT'));
$smarty->assign('HEADERSCRIPTS', $COMMONHDRLINKS['HEADERSCRIPT']);
$smarty->assign('HEAD_INCLUDE',"icons,jquery,jquery_plugins,jquery_ui,fancybox,prototype,jscalendar,sdk_headers");

$buttons = '
<div class="morphsuitlink" style="float:left; height:34px; font-size:14px; padding-top:7px; padding-left:10px">
	'.$data['name'].'
</div>
<div style="float:right; padding-right:5px">
	<input type="button" onclick="window.close()" class="crmbutton small edit" value="'.$app_strings['LBL_CLOSE'].'" title="'.$app_strings['LBL_CLOSE'].'">
	<img id="logo" src="'.get_logo('header').'" alt="{$APP.LBL_BROWSER_TITLE}" title="'.$app_strings['LBL_BROWSER_TITLE'].'" border=0 style="padding:1px 0px 3px 0px; max-height:34px">
</div>';
$smarty->assign("BUTTON_LIST", $buttons);

$focus = CRMEntity::getInstance('Processes');
$focus->column_fields['processmaker'] = $processmakerid;
$graphInfo = $focus->getProcessGraphInfo();

$smarty->assign('MOD',$mod_strings);
$smarty->assign('APP',$app_strings);
$smarty->assign('THEME',$theme);
$smarty->assign('MODE','graph');
$smarty->assign('ENABLE_ROLLBACK',false);
$smarty->assign('ID',$processmakerid);
$smarty->assign('GRAPHINFO',addslashes(Zend_Json::encode($graphInfo)));

$smarty->display('modules/Processes/ReportDetailProcess.tpl');