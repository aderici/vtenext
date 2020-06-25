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
global $adb, $table_prefix;
global $currentModule, $current_user;

$modObj = CRMEntity::getInstance($currentModule);

$ajaxaction = $_REQUEST["ajxaction"];
if($ajaxaction == "DETAILVIEW")
{
	$crmid = $_REQUEST["recordid"];
	$tablename = $_REQUEST["tableName"];
	$fieldname = $_REQUEST["fldName"];
	$fieldvalue = utf8RawUrlDecode($_REQUEST["fieldValue"]);

	if($crmid != ""){

		$permEdit = isPermitted($currentModule, 'DetailViewAjax', $crmid);
		$permField = getFieldVisibilityPermission($currentModule, $current_user->id, $fieldname);

		if ($permEdit != 'yes' || $permField != 0) {
			echo ":#:FAILURE";
			return;
		}

		$modObj->retrieve_entity_info($crmid,$currentModule);
		
		// crmv@160733
		//Added to avoid the comment save, when we edit other fields through ajax edit
		if($fieldname != 'comments') {
			$modObj->column_fields['comments'] = '';
		} else {
			$subaction = $_REQUEST['ciaction'];
			if ($subaction == 'request') {
				$modObj->setConfidentialRequest(array(
					'mode' => 'request',
					'pwd' => $_REQUEST['pwd'],
					'data' => trim($_REQUEST['data']),
				));
			} elseif ($subaction == 'provide') {
				$modObj->setConfidentialRequest(array(
					'mode' => 'provide',
					'data' => $_REQUEST['data'],
					'request_commentid' => intval($_REQUEST['request_commentid']),
				));
			}
		}
		// crmv@160733e
		
		//Added to avoid the comment save, when we edit other fields through ajax edit
		if($fieldname != 'comments')
			$modObj->column_fields['comments'] = '';

		$modObj->column_fields[$fieldname] = $fieldvalue;
		$modObj->id = $crmid;
		$modObj->mode = "edit";
		$modObj->save($currentModule);
		
		// crmv@137993 - email code moved to main class
		
		if($modObj->id != ""){
			if($fieldname == "comments"){
				$comments = $modObj->getCommentInformation($modObj->id);
				echo ":#:SUCCESS".$comments;
			}else{
				echo ":#:SUCCESS";
			}
		}else{
			echo ":#:FAILURE";
		}   
	}else{
		echo ":#:FAILURE";
	}
// crmv@160733
} elseif($ajaxaction == "CONFIDENTIALINFO") {
	$crmid = intval($_REQUEST["recordid"]);
	$commentid = intval($_REQUEST['commentid']);
	
	if ($crmid > 0 && $commentid > 0) {
	
		$subaction = $_REQUEST['ciaction'];
		if ($subaction == 'see') {
			$data = $modObj->getConfidentialData($commentid, $_REQUEST['pwd']);
		} elseif ($subaction == 'getrequestcomment') {
			$data = $modObj->getConfidentialDataComment($commentid);
		}
		
		if ($data === false) {
			echo ":#:FAILURE";
		} else {
			echo ":#:SUCCESS".$data;
		}
		
	} else {
		echo ":#:FAILURE";
	}
	
// crmv@160733e
} elseif($ajaxaction == "LOADRELATEDLIST" || $ajaxaction == "DISABLEMODULE"){
	require_once 'include/ListView/RelatedListViewContents.php';
}
?>
