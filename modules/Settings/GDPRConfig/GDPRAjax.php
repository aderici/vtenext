<?php
/*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************/

// crmv@163697

require_once('modules/Settings/GDPRConfig/GDPRUtils.php');

$mode = 'ajax';
$action = $_REQUEST['subaction'];
$json = null;

$GDPRU = new GDPRUtils();

if ($action == 'save_general_settings') {
	$ok = $GDPRU->saveGeneralSettings($_REQUEST);
	$json = array('success' => $ok, 'error' => ($ok ? '' : 'Unable to save'));
} else {
	$json = array('success' => false, 'error' => "Unknwon action");
}

echo Zend_Json::encode($json);
exit();
