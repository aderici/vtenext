<?php
/* crmv@161554 */

require('config.inc.php');
require_once('include/utils/utils.php');
require_once('include/logging.php');

global $adb, $log, $current_user, $table_prefix;

$log =& LoggerManager::getLogger('Newsletter');
$log->debug("invoked Newsletter");

if (!$current_user) {
	require_once('modules/Users/Users.php');
	$current_user = Users::getActiveAdminUser();
}

$gdprws = GDPRWS::getInstance();
$gdprws->checkNoConfirmDeletion();
