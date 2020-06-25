<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * Portions created by CRMVILLAGE.BIZ are Copyright (C) CRMVILLAGE.BIZ.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 *******************************************************************************/
/* crmv@155145 */

$ruleid = intval($_REQUEST['ruleid']);
if ($ruleid > 0) {
	global $adb, $metaLogs;
	
	$result = $adb->pquery("select description from tbl_s_conditionals where ruleid = ?", array($ruleid));
	if ($result && $adb->num_rows($result) > 0) $rulename = $adb->query_result($result,0,'description');
	
	$adb->pquery("DELETE FROM tbl_s_conditionals WHERE ruleid = ?", array($ruleid));
	$adb->pquery("DELETE FROM tbl_s_conditionals_rules WHERE ruleid = ?", array($ruleid));
	
	if ($metaLogs) $metaLogId = $metaLogs->log($metaLogs::OPERATION_DELCONDITIONAL, $ruleid, array('rulename'=>$rulename));
	if (!empty($metaLogId)) {
		require_once('modules/Conditionals/ConditionalsVersioning.php');
		$versioning = ConditionalsVersioning::getInstance();
		$versioning->versionOperation($metaLogId);
	}
}

// crmv@77249
if ($_REQUEST['included'] == true) {
	$params = array(
		'included' => 'true',
		'skip_vte_header' => 'true',
		'skip_footer' => 'true',
		'formodule' => $_REQUEST['formodule'],
		'statusfield' => $_REQUEST['statusfield']
	);
	$otherParams = "&".http_build_query($params);
}
// crmv@77249e

header("Location: index.php?module=Conditionals&action=index&parenttab=Settings".$otherParams);
