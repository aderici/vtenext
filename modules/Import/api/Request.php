<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

require_once 'modules/Import/api/UserInput.php';

class Import_API_Request extends Import_API_UserInput {

	protected $purifiedCache = array(); // crmv@156317

	function get($key) {
		if(isset($this->purifiedCache[$key])) return $this->purifiedCache[$key]; // crmv@156317
		
		if(isset($this->valuemap[$key])) {
			$value = $this->valuemap[$key];
			if(Zend_Json::decode($value) != null) {
				$value = Zend_Json::decode($value);
			}

			if(is_array($value)) {
				$purifiedValue = array();
				foreach($value as $key => $val) {
					$purifiedValue[$key] = vtlib_purify($val);
				}
			} else {
				$purifiedValue = vtlib_purify($value); // this is slow AF!
			}
			$this->purifiedCache[$key] = $purifiedValue; // crmv@156317
			return $purifiedValue;
		}
		return '';
	}

	function getString($key) {
		if(isset($this->purifiedCache[$key])) return $this->purifiedCache[$key]; // crmv@156317
		if(isset($this->valuemap[$key])) {
			$value = $this->valuemap[$key];
			if(Zend_Json::decode($value) != null) {
				$this->purifiedCache[$key] = $this->valuemap[$key]; // crmv@156317
				return $this->valuemap[$key];
			} else {
				// crmv@156317
				$purifiedValue = vtlib_purify($this->valuemap[$key]);
				$this->purifiedCache[$key] = $purifiedValue;
				return $purifiedValue;
				// crmv@156317e
			}
		}
		return '';
	}
}
