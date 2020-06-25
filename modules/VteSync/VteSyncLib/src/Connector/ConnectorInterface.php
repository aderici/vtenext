<?php
/*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************/

namespace VteSyncLib\Connector;

interface ConnectorInterface {

	public function __construct($config = array(), $storage = null);
	
	public function connect();
	public function isConnected();
	
	public function pull($module, $userinfo, \DateTime $date = null, $maxEntries = 100);
	public function push($module, $userinfo, &$records);
	public function pushMeta($module, $metaDiff);
	
	public function getObject($module, $id);
	public function objectExists($module, $id);
	public function setObject($module, $id, $object);
	public function deleteObject($module, $id);
	
	public function getStorage();
	public function setStorage(\VteSyncLib\Storage\StorageInterface $storage);

	public function canHandleModule($module);
	
}
