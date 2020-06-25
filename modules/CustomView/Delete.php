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

/* crmv@115445 crmv@150024 */

$cvid = intval($_REQUEST["record"]);
$module = vtlib_purify($_REQUEST["dmodule"]);
$smodule = vtlib_purify($_REQUEST["smodule"]);
$parenttab = getParentTab();
(!empty($_REQUEST['return_action'])) ? $return_action = vtlib_purify($_REQUEST['return_action']) : $return_action = 'ListView';

if ($cvid > 0) {
	$customview = CRMEntity::getInstance('CustomView');
	$customview->trash($module, $cvid);
}

if(isset($smodule) && $smodule != '') {
	$smodule_url = "&smodule=".$smodule;
}

header("Location: index.php?action=$return_action&parenttab=$parenttab&module=$module".$smodule_url);
