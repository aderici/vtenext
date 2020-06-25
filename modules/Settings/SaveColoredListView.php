<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/

require_once("include/database/PearDatabase.php");
require_once("include/utils/utils.php");
require_once('include/utils/EntityColorUtils.php'); //crmv@134668

global $mod_strings;

$ECU = EntityColorUtils::getInstance(); //crmv@134668

//crmv@10445
if ($_REQUEST['remove_all'] == 'true'){
	$tabid = getTabid($_REQUEST['clv_module']);
	$delete = " delete from tbl_s_lvcolors where tabid = ".$tabid;	
	$adb->query($delete);
}
elseif($_REQUEST['fieldname'] != "" && $_REQUEST['clv_module'] != "") {
//crmv@10445e	
	global $adb;
	$tabid = getTabid($_REQUEST['clv_module']);
	$delete = " delete from tbl_s_lvcolors where tabid = ".$tabid;
	$adb->query($delete);
	foreach($_REQUEST as $key => $value) {
		if (preg_match("/^{$_REQUEST['fieldname']}/", $key)) {
			$arr = explode("_",$key);
			// crmv@167234 - remove useless code
			$fldid = $arr[count($arr)-1];
              
			if($fldid != "" && $value != "") {
				if ($_REQUEST["value_".$_REQUEST['fieldname'].$fldid] == 'yes') $_REQUEST["value_".$_REQUEST['fieldname'].$fldid] = 1;
				if ($_REQUEST["value_".$_REQUEST['fieldname'].$fldid] == 'no') $_REQUEST["value_".$_REQUEST['fieldname'].$fldid] = 0;
				//crmv@134668
				$fieldvalue = $ECU->transformValueForSave($_REQUEST['clv_module'],$_REQUEST['fieldname'],$_REQUEST["value_".$_REQUEST['fieldname'].$fldid]);
				$query = " insert into tbl_s_lvcolors values ($tabid,'".$_REQUEST['fieldname']."','".$fieldvalue."','".$value."')";
				//crmv@134668e
				$adb->query($query);
			}				
		}
	}
}
header("Location: index.php?module=Settings&action=ColoredListView&parenttab=Settings");
