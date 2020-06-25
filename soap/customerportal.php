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

// crmv@168297

require_once("config.inc.php");
require_once('include/utils/utils.php');
require_once('soap/SOAPWebservices.php');
require_once('soap/VTESOAPServer.php');

/** Configure language for server response translation */
global $default_language, $current_language;
if(!isset($current_language)) $current_language = $default_language;

$log = &LoggerManager::getLogger('customerportal');

error_reporting(0);

$server = new VTESOAPServer();
$server->configureWSDL('customerportal');

$server->registerVTETypes();
$server->registerAllFromDB();

/* Begin the HTTP listener service and exit. */
if (!isset($HTTP_RAW_POST_DATA)){
	$HTTP_RAW_POST_DATA = file_get_contents('php://input');
}
$server->service($HTTP_RAW_POST_DATA);

exit();
