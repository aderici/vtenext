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
$smarty->assign("SETTINGS_FIELD_TITLE", $mod_strings['LBL_KLONDIKE_CLASSIFIER']);
$smarty->assign("SETTINGS_FIELD_DESC", $mod_strings['LBL_KLONDIKE_CLASSIFIER_DESC']);
$smarty->assign("MODE", $mode);

require_once('modules/Settings/KlondikeAI/KlondikeAIUtils.php');
$KUtils = KlondikeAIUtils::getInstance();

switch($mode) {
	case 'create':
		$smarty->assign("OPT_NONE",true);
	case 'edit':
		if ($mode == 'edit') {
			$retrieve = $KUtils->retrieveClassifier(intval($_REQUEST['id']));
			$moduleName = $retrieve['module'];
			$viewid = $retrieve['viewid'];
			$training_columns = $retrieve['training_columns'];
			$training_target = $retrieve['training_target'];
			$smarty->assign("ID", intval($_REQUEST['id']));
		} else {
			$moduleName = '';
			$viewid = '';
			$training_columns = array();
			$training_target = '';
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
		
		$modulecollist = $customView->getModuleColumnsList($moduleName);
		$choosecolhtml_tc = $choosecolhtml_tt = $customView->getByModule_ColumnsHTML($moduleName,$modulecollist);
		foreach($choosecolhtml_tc as $blocklabel => &$fields) {
			foreach($fields as &$field) {
				list(,,$fieldname) = explode(':',$field['value']);
				if (in_array($fieldname,$training_columns)) {
					$field['selected'] = 'selected';
				}
			}			
		}
		foreach($choosecolhtml_tt as $blocklabel => &$fields) {
			foreach($fields as &$field) {
				list(,,$fieldname) = explode(':',$field['value']);
				if ($fieldname == $training_target) {
					$field['selected'] = 'selected';
				}
			}			
		}
		$smarty->assign("CHOOSECOLUMN_TC",$choosecolhtml_tc);
		$smarty->assign("CHOOSECOLUMN_TT",$choosecolhtml_tt);
		
		$smarty->assign("RETURN_FILE", $_REQUEST['action']);
		$sub_template = 'Settings/KlondikeAI/KlondikeClassifier/Edit.tpl';
		break;
	case 'save':
		$KUtils->saveClassifier(intval($_REQUEST['id']),vtlib_purify($_REQUEST['moduleName']),vtlib_purify($_REQUEST['viewname']),$_REQUEST['training_columns'],$_REQUEST['training_target']);
		header('location: index.php?module=Settings&action=KlondikeClassifier&parenttab=Settings');
		exit;
		break;
	case 'load':
		$moduleName = $_REQUEST['moduleName'];
		
		$customView = CRMEntity::getInstance('CustomView',$moduleName);
		$customview_html = $customView->getCustomViewCombo();
		
		$modulecollist = $customView->getModuleColumnsList($moduleName);
		$choosecolhtml = $customView->getByModule_ColumnsHTML($moduleName,$modulecollist);
		
		$smarty1 = new VteSmarty();
		$smarty1->assign("CHOOSECOLUMN",$choosecolhtml);
		$training_columns_html = $smarty1->fetch('Settings/KlondikeAI/KlondikeClassifier/FieldOptions.tpl');
		
		$smarty1 = new VteSmarty();
		$smarty1->assign("OPT_NONE",true);
		$smarty1->assign("CHOOSECOLUMN",$choosecolhtml);
		$training_target_html = $smarty1->fetch('Settings/KlondikeAI/KlondikeClassifier/FieldOptions.tpl');
		
		echo Zend_Json::encode(array(
			'view' => $customview_html,
			'training_columns' => $training_columns_html,
			'training_target' => $training_target_html,
		));
		exit;
		break;
	case 'delete':
		$KUtils->deleteClassifier(intval($_REQUEST['id']));
	default:
		$smarty->assign("HEADER", $KUtils->getClassifierHeaderList());
		$smarty->assign("LIST", $KUtils->getClassifierList());
		$smarty->assign("NEW_BUTTON", 'index.php?module=Settings&action=KlondikeClassifier&parenttab=Settings&mode=create');
		$smarty->assign("LIST_TABLE_PROP", array(50,1,'asc'));
		$sub_template = 'Settings/KlondikeAI/KlondikeClassifier/List.tpl';
		break;
}

$smarty->assign("SUB_TEMPLATE", $sub_template);
$smarty->display('Settings/KlondikeAI/KlondikeClassifier/KlondikeClassifier.tpl');