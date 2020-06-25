<?php
/*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************/
/* crmv@197445 */

global $mod_strings, $app_strings, $theme, $current_language;

$mode = $_REQUEST['mode'];
$sub_template = '';

$smarty = new VteSmarty();
$smarty->assign("MOD",$mod_strings);
$smarty->assign("APP",$app_strings);
$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", "themes/$theme/images/");
$smarty->assign("CURRENT_LANGUAGE", $current_language);
$smarty->assign("SETTINGS_FIELD_TITLE", $mod_strings['LBL_PROCESS_DISCOVERY_AGENT']);
$smarty->assign("SETTINGS_FIELD_DESC", $mod_strings['LBL_PROCESS_DISCOVERY_AGENT_DESC']);

require_once('modules/Settings/KlondikeAI/KlondikeAIUtils.php');
$KUtils = KlondikeAIUtils::getInstance();

switch($mode) {
	case 'create':
	case 'edit':
		if ($mode == 'edit') {
			$retrieve = $KUtils->retrieveAgent(intval($_REQUEST['id']));
			$moduleName = $retrieve['module'];
			$viewid = $retrieve['viewid'];
			$smarty->assign("ID", intval($_REQUEST['id']));
		} else {
			$moduleName = '';
			$viewid = '';			
		}
		
		require_once('modules/Settings/ProcessMaker/ProcessMakerUtils.php');
		$PMUtils = ProcessMakerUtils::getInstance();
		$moduleNames = $PMUtils->getModuleList('picklist',$moduleName);
		unset($moduleNames['Events']);
		unset($moduleNames['MyNotes']);
		unset($moduleNames['ProductLines']);
		$smarty->assign("moduleNames", $moduleNames);
		
		$customView = CRMEntity::getInstance('CustomView',$moduleName);
		$customview_html = $customView->getCustomViewCombo($viewid);
		$smarty->assign("CUSTOMVIEW_OPTION",$customview_html);
		
		$smarty->assign("RETURN_FILE", $_REQUEST['action']);
		$sub_template = 'Settings/KlondikeAI/ProcessDiscoveryAgent/Edit.tpl';
		break;
	case 'save':
		$KUtils->saveAgent(intval($_REQUEST['id']),vtlib_purify($_REQUEST['moduleName']),vtlib_purify($_REQUEST['viewname']));
		header('location: index.php?module=Settings&action=ProcessDiscoveryAgent&parenttab=Settings');
		exit;
		break;
	case 'view':
		$customView = CRMEntity::getInstance('CustomView',$_REQUEST['moduleName']);
		$customview_html = $customView->getCustomViewCombo();
		echo $customview_html;
		exit;
		break;
	case 'delete':
		$KUtils->deleteAgent(intval($_REQUEST['id']));
	default:
		$smarty->assign("HEADER", $KUtils->getAgentHeaderList());
		$smarty->assign("LIST", $KUtils->getAgentList());
		$smarty->assign("NEW_BUTTON", 'index.php?module=Settings&action=ProcessDiscoveryAgent&parenttab=Settings&mode=create');
		$smarty->assign("LIST_TABLE_PROP", array(50,1,'asc'));
		$sub_template = 'Settings/KlondikeAI/ProcessDiscoveryAgent/List.tpl';
		break;
}

$smarty->assign("SUB_TEMPLATE", $sub_template);
$smarty->display('Settings/KlondikeAI/ProcessDiscoveryAgent/ProcessDiscoveryAgent.tpl');