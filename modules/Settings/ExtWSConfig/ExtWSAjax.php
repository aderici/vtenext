<?php
/*+*************************************************************************************
* The contents of this file are subject to the VTECRM License Agreement
* ("licenza.txt"); You may not use this file except in compliance with the License
* The Original Code is: VTECRM
* The Initial Developer of the Original Code is VTECRM LTD.
* Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
* All Rights Reserved.
***************************************************************************************/
/* crmv@146670 crmv@146671 */

require_once('modules/Settings/ExtWSConfig/ExtWSUtils.php');
require_once('modules/Settings/ExtWSConfig/ExtWS.php');

global $mod_strings, $app_strings, $theme;

$mode = 'ajax';
$extwsid = intval($_REQUEST['extwsid']);
$action = $_REQUEST['subaction'];
$raw = null;
$json = null;

$EWSU = new ExtWSUtils();
$EWS = new ExtWS();

if ($action == 'delete_ws') {
	$ok = $EWSU->deleteWS($extwsid);
	$json = array('success' => $ok, 'error' => ($ok ? '' : 'Unable to delete'));
} elseif ($action == 'test_ws') {
	$data = $EWSU->prepareDataFromRequest();
	$result = $EWS->call($data);
	$json = array('success' => true, 'error' => '', 'result' => $result);
} elseif ($action == 'automap_fields') {
	$error = '';
	$fields = $EWSU->automapFields($_REQUEST['data'], $error);
	$json = array('success' => !empty($fields), 'fields' => $fields, 'error' => $error);
} else {
	$json = array('success' => false, 'error' => "Unknwon action");
}

echo Zend_Json::encode($json);
exit();
