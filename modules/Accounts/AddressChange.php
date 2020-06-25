<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
* ("License"); You may not use this file except in compliance with the License
* The Original Code is:  vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
********************************************************************************/

require_once('include/utils/utils.php');

global $adb, $table_prefix;

// get the account's address
$record = intval($_REQUEST['record']);
$sql ="SELECT
	".$table_prefix."_account.accountid,
	".$table_prefix."_accountbillads.*,
	".$table_prefix."_accountshipads.*
	FROM ".$table_prefix."_account 
	INNER JOIN ".$table_prefix."_accountbillads ON ".$table_prefix."_accountbillads.accountaddressid = ".$table_prefix."_account.accountid 
	INNER JOIN ".$table_prefix."_accountshipads ON ".$table_prefix."_accountshipads.accountaddressid = ".$table_prefix."_account.accountid 
	WHERE accountid = ?";

$result = $adb->pquery($sql, array($record));
$value = $adb->fetch_row($result);

// check if the address changed
if (
	($_REQUEST['bill_city'] != $value['bill_city'] && isset($_REQUEST['bill_city']))  ||
	($_REQUEST['bill_street'] != $value['bill_street'] && isset($_REQUEST['bill_street'])) ||
	($_REQUEST['bill_country']!=$value['bill_country'] && isset($_REQUEST['bill_country']))|| 
	($_REQUEST['bill_code']!=$value['bill_code'] && isset($_REQUEST['bill_code']))||
	($_REQUEST['bill_pobox']!=$value['bill_pobox'] && isset($_REQUEST['bill_pobox'])) || 
	($_REQUEST['bill_state']!=$value['bill_state'] && isset($_REQUEST['bill_state']))||
	
	($_REQUEST['ship_country']!=$value['ship_country'] && isset($_REQUEST['ship_country']))|| 
	($_REQUEST['ship_city']!=$value['ship_city'] && isset($_REQUEST['ship_city']))||
	($_REQUEST['ship_state']!=$value['ship_state'] && isset($_REQUEST['ship_state']))||
	($_REQUEST['ship_code']!=$value['ship_code'] && isset($_REQUEST['ship_code']))||
	($_REQUEST['ship_street']!=$value['ship_street'] && isset($_REQUEST['ship_street']))|| 
	($_REQUEST['ship_pobox']!=$value['ship_pobox'] && isset($_REQUEST['ship_pobox']))
) {
	// check if there are contacts
	$sql1 = "SELECT contactid FROM {$table_prefix}_contactdetails co INNER JOIN {$table_prefix}_crmentity c ON c.crmid = co.contactid WHERE c.deleted = 0 AND co.accountid = ?";
	$result1 = $adb->pquery($sql1, array($record));
	if ($adb->num_rows($result1) > 0) {
		echo 'address_change';
	} else {
		echo 'No Changes';
	}
} else {
	echo 'No Changes';
}
