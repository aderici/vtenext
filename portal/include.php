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

require("PortalConfig.php"); // crmv@198415
require_once('nusoap/nusoap.php'); // crmv@148761

global $Server_Path;
global $client;

$client = new nusoap_client($Server_Path."/vteservice.php?service=customerportal", false, $proxy_host, $proxy_port, $proxy_username, $proxy_password); // crmv@80441 crmv@148761 crmv@181168

//We have to overwrite the character set
$client->soap_defencoding = $default_charset;

