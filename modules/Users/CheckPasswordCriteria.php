<?php

// crmv@198545
if (!isset($root_directory)) {
	require_once('../../config.inc.php');
	chdir($root_directory);
}
require_once('include/utils/utils.php'); 
// crmv@198545e

//crmv@35153
$installation_mode = false;
if (empty($_SESSION)) {
	VteSession::start();
}
if (VteSession::get('morph_mode') == 'installation') {
	$installation_mode = true;
	// crmv@198545 - removed code
}
//crmv@35153e
//crmv@28327
$focus = CRMEntity::getInstance('Users');
if ($_REQUEST['row'] != '') {
	$row = Zend_Json::decode($_REQUEST['row']);
} else {
	$focus->retrieve_entity_info($_REQUEST['record'],'Users');
	$row = $focus->column_fields;
}
if (!$focus->checkPasswordCriteria($_REQUEST['password'],$row)) {
	echo 'no';
}
exit;
//crmv@28327e
