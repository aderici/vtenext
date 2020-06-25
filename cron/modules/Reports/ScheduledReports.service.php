<?php
/*+***********************************************************************************
 * The contents of this file are subject to the CRMVILLAGE.BIZ CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  CRMVILLAGE.BIZ CRM Open Source
 * The Initial Developer of the Original Code is CRMVILLAGE.BIZ.
 * Portions created by CRMVILLAGE.BIZ are Copyright (C) CRMVILLAGE.BIZ.
 * All Rights Reserved.
 *************************************************************************************/
 
/* crmv@139057 */

require_once("modules/Reports/Reports.php");
require_once("modules/Reports/ScheduledReports.php");

// Turn-off PHP error reporting.
//try { error_reporting(0); } catch(Exception $e) { }

$SR = ScheduledReports::getInstance();
$SR->runScheduledReports();
