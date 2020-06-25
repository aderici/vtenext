<?php
/***************************************************************************************
 * The contents of this file are subject to the CRMVILLAGE.BIZ VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is:  CRMVILLAGE.BIZ VTECRM
 * The Initial Developer of the Original Code is CRMVILLAGE.BIZ.
 * Portions created by CRMVILLAGE.BIZ are Copyright (C) CRMVILLAGE.BIZ.
 * All Rights Reserved.
 ***************************************************************************************/
/* crmv@129149 */

require('config.inc.php');
require_once('include/utils/utils.php');
require_once('include/logging.php');

$VTEP = VTEProperties::getInstance();
$send_mail_queue = $VTEP->getProperty('modules.emails.send_mail_queue');
if (!$send_mail_queue) return; // exit the file

ini_set('memory_limit','256M');

global $log;
$log =& LoggerManager::getLogger('Messages');
$log->debug("invoked sending notification emails procedure");

$_REQUEST['service'] = 'Messages';
$focus = CRMEntity::getInstance('Emails');
$focus->processSendNotQueue();

$log->debug("end sending notification emails procedure");