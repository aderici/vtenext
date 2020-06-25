<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): mmbrich
 ********************************************************************************/

/* crmv@150024 */

global $php_max_execution_time;
set_time_limit($php_max_execution_time);

$targetid = intval($_REQUEST['return_id']);
$cvModule = vtlib_purify($_REQUEST["list_type"]);
$cvid = intval($_REQUEST["cvid"]);

if ($cvid > 0) {
	$focus = CRMEntity::getInstance('Targets');
	$focus->loadCVList($targetid, $cvModule, $cvid);
}

header("Location: index.php?module=Targets&action=TargetsAjax&file=CallRelatedList&ajax=true&record=".$targetid);
