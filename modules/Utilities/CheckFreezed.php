<?php 
/* crmv@171524 */

$record = intval($_REQUEST['crmid']);

$success = true;
$error = null;
$isFreezed = false;
$stompConnection = null;

$VTEP = VTEProperties::getInstance();

$triggerQueueManager = TriggerQueueManager::getInstance();
$isFreezed = $triggerQueueManager->checkFreezed($record);

$json = array('success' => $success, 'error' => $error, 'is_freezed' => $isFreezed);

echo Zend_Json::encode($json);
exit();