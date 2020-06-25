<?php
/***************************************************************************************
 * The contents of this file are subject to the CRMVILLAGE.BIZ VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is:  CRMVILLAGE.BIZ VTECRM
 * The Initial Developer of the Original Code is CRMVILLAGE.BIZ.
 * Portions created by CRMVILLAGE.BIZ are Copyright (C) CRMVILLAGE.BIZ.
 * All Rights Reserved.
 ***************************************************************************************/
/* crmv@173186 */
$ajaxaction = $_REQUEST["ajxaction"];
if($ajaxaction == "TOGGLELOGPROP")
{
	$logConfId = vtlib_purify($_REQUEST['log']);
	$logUtils = LogUtils::getInstance();
	$logUtils->toggleLogProp($logConfId);
	echo 'SUCCESS';
} elseif($ajaxaction == "SAVEGLOBALCONFIG")
{
	$logUtils = LogUtils::getInstance();
	$logUtils->setGlobalConfig(vtlib_purify($_REQUEST["prop"]), vtlib_purify($_REQUEST["value"]));
	echo 'SUCCESS';
}
exit;