<?php 
/*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************/

// crmv@163697

require_once('include/BaseClasses.php');

class BusinessUnit extends SDKExtendableUniqueClass {
	
	public $table_name = null;
	
	public function __construct() {
		global $table_prefix;
		
		$this->table_name = $table_prefix.'_organizationdetails';
	}
	
	public static function isEnabled() {
		global $adb, $table_prefix;
		
		$enabled = false;
		
		$res = $adb->pquery("SELECT fieldid FROM {$table_prefix}_field WHERE fieldname = ?", array('bu_mc'));
		$enabled = ($res && $adb->num_rows($res) > 0);
		
		return $enabled;
	}
	
	public static function isEnabledForModule($module) {
		global $adb, $table_prefix;
		
		$tabid = getTabid($module);
		if (empty($tabid)) return false;
		
		$enabled = false;
		
		$res = $adb->pquery("SELECT fieldid FROM {$table_prefix}_field WHERE tabid = ? AND fieldname = ?", array($tabid, 'bu_mc'));
		$enabled = ($res && $adb->num_rows($res) > 0);
		
		return $enabled;
	}
	
	public static function getBusinessForId($crmid) {
		global $adb, $table_prefix;
		
		$module = getSalesEntityType($crmid);
		if (!self::isEnabledForModule($module)) return false;
		
		$focus = CRMEntity::getInstance($module);
		$focus->id = $crmid;
		$s = $focus->retrieve_entity_info($crmid, $module, false);
		
		if (!empty($s)) return false;
		
		$bumc = $focus->column_fields['bu_mc'];
		$business = explode(' |##| ', $bumc);
		
		return $business;
	}
	
	public function getBusinessList() {
		global $adb, $table_prefix;
		
		$business = array();
		
		$businessRes = $adb->query("SELECT * FROM {$this->table_name}");
		if ($businessRes && $adb->num_rows($businessRes)) {
			while ($row = $adb->fetchByAssoc($businessRes, -1, false)) {
				$business[] = $this->transformRowFromDb($row);
			}
		}
		
		return $business;
	}
	
	public function getBusinessInfo($id) {
		global $adb, $table_prefix;
		
		$ret = false;
		
		$businessRes = $adb->pquery("SELECT * FROM {$this->table_name} WHERE organizationid = ?", array($id));
		if ($businessRes && $adb->num_rows($businessRes)) {
			while ($row = $adb->fetchByAssoc($businessRes, -1, false)) {
				$ret = $this->transformRowFromDb($row);
			}
		}
		
		return $ret;
	}
	
	public function getBusinessInfoByName($name) {
		global $adb, $table_prefix;
		
		$ret = false;
		
		$businessRes = $adb->pquery("SELECT * FROM {$this->table_name} WHERE organizationname = ?", array($name));
		if ($businessRes && $adb->num_rows($businessRes)) {
			while ($row = $adb->fetchByAssoc($businessRes, -1, false)) {
				$ret = $this->transformRowFromDb($row);
			}
		}
		
		return $ret;
	}
	
	protected function transformRowFromDb($row) {
		return $row;
	}
	
	protected function transformRowToDb($row) {
		return $row;
	}
	
}
