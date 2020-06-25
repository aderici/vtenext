<?php
/*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************/

// crmv@161554

namespace GDPR;

defined('BASEPATH') OR exit('No direct script access allowed');

class SessionManager {
	
	private static $instance = null;

	public function set($key, $value = '') {
		if (is_array($key)) return self::setMulti($key);
		$_SESSION[$key] = $value;
	}

	public function get($key) {
		return $_SESSION[$key];
	}

	public function remove($key) {
		unset($_SESSION[$key]);
	}

	public function hasKey($key) {
		return isset($_SESSION[$key]);
	}

	public function setMulti($keys) {
		if (is_array($keys)) {
			foreach ($keys as $key => $value) {
				$_SESSION[$key] = $value;
			}
		}
	}

	public function removeMulti($keys) {
		if (is_array($keys)) {
			foreach ($keys as $key) {
				unset($_SESSION[$key]);
			}
		}
	}
	
	public static function getInstance() {
		if (!isset(self::$instance)) {
			self::$instance = new SessionManager();
		}
		return self::$instance;
	}

}
