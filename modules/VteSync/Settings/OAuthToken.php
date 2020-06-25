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

$code = $_REQUEST['code'];

$vsync = VteSync::getInstance();

if ($code) {
	$state = $_GET['state'];
	$saveid = $vsync->searchOAuthData($state);
	$data = $vsync->loadOAuthData($saveid);
	if (!$saveid || !$vsync->checkOAuthState($state, $data)) {
		if ($saveid > 0) $vsync->clearOAuthData($saveid);
		die('Invalid state');
	}
	
	if (!$vsync->getAccessToken($code, $saveid)) {
		die();
	}
	
	// ok, we have the token!
	echo '<html><body><script type="text/javascript">window.opener.VteSyncConfig.setAuthorizeStatus(true, "'.$saveid.'"); window.close()</script></body></html>';
	
} else {
	die('Authorization code not provided');
}
//$authUrl = $vsync->getOAuthAuthUrl($typeid, $client_id);
