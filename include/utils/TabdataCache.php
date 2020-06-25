<?php 
/*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************/

/* crmv@140903 */

class TabdataCache {

	protected static $cache = null;
	protected static $dbstorage = null;
	
	protected static $rcache = array();

	public static function get($key) {
	
		// use static cache first
		if (isset(self::$rcache[$key])) {
			return self::$rcache[$key];
		}
	
		// read cache
		$cache = Cache::getInstance('tabdata');
		$value = $cache->get();
		if ($value !== false && isset($value[$key])) {
			self::$rcache[$key] = $value[$key];
			return $value[$key];
		} else {
			// read db
			$dbkey = 'tabdata.'.$key;
			if (!self::$dbstorage) self::$dbstorage = new CacheStorageDb();
			if (self::$dbstorage->has($dbkey)) {
				$value = self::$dbstorage->get($dbkey);
				// set cache
				if ($value !== null) {
					if (php_sapi_name() != 'cli') self::rebuildCache(); // fix for autoupdate to 20.04
					return $value;
				}
			}
		}
		return null;
	}
	
	/**
	 * Set a single value for tabdata variables
	 * This function is not efficient, please use the setMulti if possible
	 */
	public static function set($key, $value) {
		$dbkey = 'tabdata.'.$key;
		if (!self::$dbstorage) self::$dbstorage = new CacheStorageDb();
		self::$dbstorage->set($dbkey, $value);
		self::rebuildCache();
	}
	
	/**
	 * Set multiple values for tabdata variables
	 */
	public static function setMulti($values) {
		$tdvalues = array();
		foreach ($values as $k => $val) {
			$tdvalues['tabdata.'.$k] = $val;
		}
		if (!self::$dbstorage) self::$dbstorage = new CacheStorageDb();
		self::$dbstorage->setMulti($tdvalues);
		self::rebuildCache();
	}
	
	public static function rebuildCache($all_users = true) { // crmv@187020
		if (!self::$dbstorage) self::$dbstorage = new CacheStorageDb();
		$values = self::$dbstorage->getAllLike('tabdata.%');
		$cvalues = array();
		foreach ($values as $k => $val) {
			$k = str_replace('tabdata.', '', $k);
			$cvalues[$k] = $val;
		}
		$cache = Cache::getInstance('tabdata');
		$cache->clear($all_users); // crmv@187020
		$cache->set($cvalues);
		self::$rcache = $cvalues;
	}
	
}
