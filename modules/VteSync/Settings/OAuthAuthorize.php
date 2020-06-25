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

$saveid = intval($_REQUEST['saveid']);

$vsync = VteSync::getInstance();

// load data from session
$data = $vsync->loadOAuthData($saveid);
$typeid = $data['typeid'];
$client_id = $data['client_id'];
$state = '';

$authUrl = $vsync->getOAuthAuthUrl($typeid, $client_id, $state);

if ($authUrl) {
	// save the state
	$data['state'] = $state;
	$vsync->replaceOAuthData($saveid, $data);

	header("Location: $authUrl");
	die();
} else {
	die('Unable to prepare the authorization url');
}
