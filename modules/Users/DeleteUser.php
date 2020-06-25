<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/

// crmv@37463
global $adb, $table_prefix;
global $current_user;
$del_id =  intval($_REQUEST['delete_user_id']);
$tran_id = intval($_REQUEST['transfer_user_id']);

if (isPermitted('Users', 'Delete') != 'yes') {
	header("Location: index.php?action=ListView&module=Users");
	die();
}
// crmv@37463e

//crmv@161021
$focus = CRMEntity::getInstance('Employees');
if ($focus->synchronizeUser) $focus->syncUserEmployee($del_id,'delete');
//crmv@161021e

// crmv@184231
$focusUsers = CRMEntity::getInstance('Users');
$focusUsers->deleteUser($del_id, $tran_id);
// crmv@184231e

//if check to delete user from detail view
if(isset($_REQUEST["ajax_delete"]) && $_REQUEST["ajax_delete"] == 'false')
	header("Location: index.php?action=ListView&module=Users");
else
	header("Location: index.php?action=UsersAjax&module=Users&file=ListView&ajax=true&deleteuser=true");
