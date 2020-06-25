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

require_once('modules/VteSync/VteSync.php');

global $current_user;
if (!is_admin($current_user)) die('Not authorized');

$vsync = VteSync::getInstance();

$syncid = intval($_POST['syncid']);
$saveid = intval($_POST['oauth2_saveid']);

// TODO validate
$error = "";
$valid = $vsync->validateSave($_POST, $syncid > 0 ? 'update' : 'create', $error);

if ($valid) {
	if ($syncid > 0) {
		$r = $vsync->updateSync($syncid, $_POST, $saveid);
	} else {
		// insert
		$r = $vsync->insertSync($_POST['synctype'], $_POST, $saveid);
	}

	if ($r && $saveid > 0) {
		$vsync->clearOAuthData($saveid);
	} elseif (!$r) {
		$error = "Unable to save";
	}
}

if ($error) {
	// bad, but errors are nicely caught by ajax presave
	die($error);
} else {
	header('Location: index.php?module=Settings&action=VteSync&parenttab=Settings');
}


