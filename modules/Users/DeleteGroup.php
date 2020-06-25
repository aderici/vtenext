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

/* crmv@184240 */
 
require_once('include/utils/UserInfoUtil.php');

if (!is_admin($current_user)) {
	// redirect to settings, where an error will be shown
	header("Location: index.php?module=Settings&action=index&parenttab=Settings");
	die();
}

$del_id =  $_REQUEST['delete_group_id'];
$transfer_group_id = $_REQUEST['transfer_group_id'];
$assignType = $_REQUEST['assigntype'];

if($assignType == 'T') {
	$transferId = $_REQUEST['transfer_group_id'];
} elseif($assignType == 'U') {
	$transferId = $_REQUEST['transfer_user_id'];
}

//Updating the user2 vtiger_role vtiger_table
deleteGroup($del_id,$transferId);

header("Location: index.php?action=listgroups&module=Settings&parenttab=Settings");
