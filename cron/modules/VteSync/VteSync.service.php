<?php
/*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************/
 
/* crmv@176547 */

// optional syncid to pass
$syncid = intval($_REQUEST['syncid']);

require_once('modules/VteSync/VteSync.php');
$vsync = VteSync::getInstance();
$vsync->runCron($syncid);
