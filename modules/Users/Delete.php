<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/

/* crmv@184240 */

global $table_prefix;

$record = intval($_REQUEST['record']);
$activityid = intval($_REQUEST['return_id']);

if (isPermitted('Calendar', 'EditView', $record) != 'yes') {
	// redirect to settings, where an error will be shown
	header("Location: index.php?module=Settings&action=index&parenttab=Settings");
	die();
}

$sql= 'delete from '.$table_prefix.'_salesmanactivityrel where smid=? and activityid = ?';
$adb->pquery($sql, array($record, $activityid));

if($_REQUEST['return_module'] == 'Calendar')
	$mode ='&activity_mode=Events';

header("Location: index.php?module=".vtlib_purify($_REQUEST['return_module'])."&action=".vtlib_purify($_REQUEST['return_action']).$mode."&record=".$activityid."&relmodule=".vtlib_purify($_REQUEST['module']));
