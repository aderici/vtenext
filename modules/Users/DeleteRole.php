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
 
if (!is_admin($current_user)) {
	// redirect to settings, where an error will be shown
	header("Location: index.php?module=Settings&action=index&parenttab=Settings");
	die();
}

$del_id =  $_REQUEST['delete_role_id'];
$tran_id = $_REQUEST['user_role'];

deleteRole($del_id,$tran_id);

header("Location: index.php?action=listroles&module=Settings");
