<?php
//crmv@17001
/*+********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.0
* ("License"); You may not use this file except in compliance with the License
* The Original Code is:  vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
*********************************************************************************/
// crmv@67410

global $currentModule, $current_user;
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
//crmv@17001e
//crmv@152532
} elseif($ajaxaction == "GETSTATISTICS"){
	if ($_REQUEST['src_module'] == 'Newsletter') {
		$newsletterid = intval($_REQUEST['recordid']) ?: '';
		$focus = CRMEntity::getInstance('Newsletter');
		$focus->retrieve_entity_info($newsletterid,'Newsletter');
		
		$newsletterStatistics = true;
		$record = $focus->column_fields['campaignid'];
		$statistics_newsletter_id = $newsletterid;
	} else {
		$newsletterStatistics = false;
		$record = $_REQUEST['recordid'];
		$statistics_newsletter_id = null;
	}
	echo $modObj->getStatistics($record,$newsletterStatistics,false,$statistics_newsletter_id);
} elseif($ajaxaction == "FILTERSTATISTICS"){
	echo $modObj->getStatistics($_REQUEST['record'],false,$_REQUEST['ajax'],$_REQUEST['statistics_newsletter']);
//crmv@152532e
}
?>