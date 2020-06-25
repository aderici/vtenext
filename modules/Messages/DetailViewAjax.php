<?php
/*+********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.0
* ("License"); You may not use this file except in compliance with the License
* The Original Code is:  vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
*********************************************************************************/
// crmv@67410

global $currentModule, $current_user, $adb, $table_prefix;
$modObj = CRMEntity::getInstance($currentModule);

$ajaxaction = $_REQUEST["ajxaction"];
if($ajaxaction == "DETAILVIEW")
{
	$crmid = $_REQUEST["recordid"];
	$tablename = $_REQUEST["tableName"];
	$fieldname = $_REQUEST["fldName"];
	$fieldvalue = utf8RawUrlDecode($_REQUEST["fieldValue"]); 
	if($crmid != "")
	{
		$permEdit = isPermitted($currentModule, 'DetailViewAjax', $crmid);
		$permField = getFieldVisibilityPermission($currentModule, $current_user->id, $fieldname);
		
		if ($permEdit == 'yes' && $permField == 0) {
			$modObj->retrieve_entity_info($crmid,$currentModule);
			$modObj->column_fields[$fieldname] = $fieldvalue;

			$modObj->id = $crmid;
			$modObj->mode = "edit";
			$modObj->save($currentModule);
			if($modObj->id != "") {
				echo ":#:SUCCESS";
			} else {
				echo ":#:FAILURE";
			}   
		} else {
			echo ":#:FAILURE";
		}
	} else {
		echo ":#:FAILURE";
	}
} elseif($ajaxaction == "LOADRELATEDLIST" || $ajaxaction == "DISABLEMODULE"){
	require_once 'include/ListView/RelatedListViewContents.php';
//crmv@OPER8279
} elseif($ajaxaction == "FETCHMESSAGE"){
	$account = vtlib_purify($_REQUEST['account']);
	$folder = vtlib_purify($_REQUEST['folder']);
	$xuid = vtlib_purify($_REQUEST['xuid']);
	$userid = $current_user->id;

	$focus = CRMEntity::getInstance('Messages');
	$focus->setAccount($account);
	$focus->getZendMailStorageImap($userid);
	$focus->selectFolder($folder);
	
	$messageId = $focus->getMailResource()->getNumberByUniqueId($xuid);
	$focus->saveCache(array($messageId=>$xuid));
	$saved_messages = $focus->getSavedMessages();
	echo 'SUCCESS::'.$saved_messages[0];
} elseif($ajaxaction == "GETROW"){
	$record = vtlib_purify($_REQUEST['record']);
	
	$queryGenerator = QueryGenerator::getInstance($currentModule, $current_user);
	$queryGenerator->initForDefaultCustomView();
	$queryGenerator->addField('account');
	$queryGenerator->addField('folder');
	$list_query = $queryGenerator->getQuery()." and {$table_prefix}_messages.messagesid = ".$record;
	$list_result = $adb->query($list_query);
	
	$controller = ListViewController::getInstance($adb, $current_user, $queryGenerator);
	$listview_entries = $controller->getListViewEntriesLight($modObj,$currentModule,$list_result,'');
	
	$current_account = $adb->query_result($list_result,0,'account');
	$current_folder = $adb->query_result($list_result,0,'folder');
	$modObj->setAccount($current_account);
	$specialFolders = $modObj->getSpecialFolders(false);
	
	$smarty = new VteSmarty();
	$smarty->assign("MODULE", $currentModule);
	$smarty->assign("FOCUS", $modObj);
	$smarty->assign("CURRENT_FOLDER", $current_folder);
	$smarty->assign('SPECIAL_FOLDERS', $specialFolders);
	$smarty->assign("LISTENTITY", $listview_entries);
	$smarty->display("modules/Messages/ListViewRows.tpl");
//crmv@OPER8279e
}
?>