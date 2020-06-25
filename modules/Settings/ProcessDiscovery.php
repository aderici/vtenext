<?php
/*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************/
/* crmv@190834 */

global $mod_strings, $app_strings, $theme, $upload_badext, $default_charset, $current_user, $current_language;	//crmv@147720

require_once('modules/Settings/KlondikeAI/KlondikeAIUtils.php');
$KUtils = KlondikeAIUtils::getInstance();
$mode = $_REQUEST['mode'];
$sub_template = '';

$smarty = new VteSmarty();
$smarty->assign("MOD",$mod_strings);
$smarty->assign("APP",$app_strings);
$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", "themes/$theme/images/");
$smarty->assign("CURRENT_LANGUAGE", $current_language);
$smarty->assign("SETTINGS_FIELD_TITLE", $mod_strings['LBL_PROCESS_DISCOVERY']);
$smarty->assign("SETTINGS_FIELD_DESC", $mod_strings['LBL_PROCESS_DISCOVERY_DESC']);
$smarty->assign("DISCOVERY_MODE", true);

switch($mode) {
	case 'download':
		$id = vtlib_purify($_REQUEST['id']);
		$format = vtlib_purify($_REQUEST['format']);
		$data = $KUtils->retrieveDiscovery($id);
		if ($format == 'bpmn') {
			$filename = $id.'.bpmn';
			$fileContent = $data['bpmn_translated'];
		}
		$fileType = 'application/octet-stream';
		function_exists('mb_strlen') ? $filesize = mb_strlen($fileContent, '8bit') : $filesize = strlen($fileContent);
		
		header("Content-type: $fileType");
		header("Content-length: $filesize");
		header("Cache-Control: private");
		header("Content-Disposition: attachment; filename={$filename}");
		header("Content-Description: PHP Generated Data");
		echo $fileContent; exit;
		break;
	case 'upload':
	case 'import':
		$id = vtlib_purify($_REQUEST['id']);
		$data = $KUtils->retrieveDiscovery($id);
		
		require_once('modules/Settings/ProcessMaker/ProcessMakerUtils.php');
		$PMUtils = ProcessMakerUtils::getInstance();
		$data = array(
			'name' => $id,
			'description' => $data['event'],
			'bpmn' => $data['bpmn_translated'],
		);
		$pm_id = $PMUtils->save($data,'',false,false);
		echo Zend_Json::encode(array('success'=>(!empty($pm_id)),'id'=>$pm_id));
		exit;
		break;
	case 'detail':
		$id = vtlib_purify($_REQUEST['id']);
		
		$data = $KUtils->retrieveDiscovery($id);
		$smarty->assign("DATA", $data);
		$smarty->assign("TABLE_NAME", ''); // ?
		$smarty->assign("default_charset", $default_charset);
		
		include_once('vtlib/Vtecrm/Link.php');
		$COMMONHDRLINKS = Vtiger_Link::getAllByType(Vtiger_Link::IGNORE_MODULE, Array('HEADERSCRIPT'));
		$smarty->assign('HEADERSCRIPTS', $COMMONHDRLINKS['HEADERSCRIPT']);
		$smarty->assign('HEAD_INCLUDE',"icons,jquery,jquery_plugins,jquery_ui,fancybox,prototype,jscalendar,sdk_headers");

		$buttons = '
		<div class="morphsuitlink" style="float:left; height:34px; font-size:14px; padding-top:7px; padding-left:10px">
			'.$mod_strings['LBL_SETTINGS'].'</a> &gt; '.$mod_strings['LBL_PROCESS_MAKER'].'
		</div>
		<div style="float:right; padding-right:5px" class="processes_btn_div">
			<div id="status" style="display:none; float:left; position:relative; top:6px; right:5px;"><i class="dataloader light" data-loader="circle"></i></div> <!-- crmv@167915 -->
			<input type="button" onclick="window.location.href=\'index.php?module=Settings&action=ProcessDiscovery\'" class="crmbutton small edit" value="'.$app_strings['LBL_BACK'].'" title="'.$app_strings['LBL_BACK'].'">
			<img id="logo" src="'.get_logo('header').'" alt="{$APP.LBL_BROWSER_TITLE}" title="'.$app_strings['LBL_BROWSER_TITLE'].'" border=0 style="padding:1px 0px 3px 0px; max-height:34px">
		</div>';
		$smarty->assign("BUTTON_LIST", $buttons);
		
		$smarty->display('Settings/ProcessMaker/Detail.tpl');
		exit;
		break;
	default:
		$smarty->assign("HEADER", $KUtils->getDiscoveryHeaderList());
		$smarty->assign("LIST", $KUtils->getDiscoveryList());
		$smarty->assign("LIST_TABLE_PROP", array(50,5,'asc'));
		$sub_template = 'Settings/ProcessMaker/List.tpl';
		break;
}

$smarty->assign("SUB_TEMPLATE", $sub_template);
$smarty->display('Settings/ProcessMaker/ProcessMaker.tpl');