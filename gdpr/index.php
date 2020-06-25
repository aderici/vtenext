<?php
/*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************/

// crmv@161554 crmv@163697

require_once('config.php');

$action = filter_var($_REQUEST['action'], FILTER_SANITIZE_STRING);

$SM = GDPR\SessionManager::getInstance();
$GPDRManager = GDPR\GDPRManager::getInstance($CFG, $SM, $_REQUEST);
	
$GPDRManager->processAction($action);
