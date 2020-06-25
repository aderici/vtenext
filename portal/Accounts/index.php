<?php
/*+*******************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * Portions created by CRMVILLAGE.BIZ are Copyright (C) CRMVILLAGE.BIZ.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ********************************************************************************/

/* crmv@173271 */
 
$block = 'Accounts';

$customerid = $_SESSION['customer_id'];

// force to view the customer's account
if($_REQUEST['id'] == '') {
	$params = Array('id'=>$customerid);
	$id = $client->call('get_check_account_id', $params, $Server_Path, $Server_Path);
} else {
	$id = $_REQUEST['id'];
}

if (!empty($id)) {
	(file_exists("$block/Detail.php")) ? $detail = "$block/Detail.php" : $detail = 'VteCore/Detail.php';
	include($detail);
}else{
	$moduleObj->displayNotAvailable();
}
