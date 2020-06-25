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

namespace GDPR;

defined('BASEPATH') OR exit('No direct script access allowed');

class Redirect {
	
	public static function to($action = null, $params = array()) {
		global $CFG, $GPDRManager;

		$contactId = $GPDRManager->getContactId();
		$accessToken = $GPDRManager->getAccessToken();
		
		if ($action) {
			if (is_numeric($action)) {
				switch ($action) {
					case 404:
						header('HTTP/1.0 404 Not Found');
						include('actions/404.php');
						exit();
				}
			}
			
			$params['action'] = $action;
			
			if (!empty($accessToken)) $params['accesstoken'] = $accessToken;
			
			$params = http_build_query($params);
			
			header('Location: index.php?' . $params);
			exit();
		}
	}
	
}
