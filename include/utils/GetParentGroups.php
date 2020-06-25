<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * Portions created by CRMVILLAGE.BIZ are Copyright (C) CRMVILLAGE.BIZ.
 * All Rights Reserved.
*
 ********************************************************************************/

/* crmv@193294 optimized calls with request cache */
 
/** Class to retreive all the Parent Groups of the specified Group
 *
 */
class GetParentGroups { 

	public $parent_groups = Array();

	/** to get all the parent vtiger_groups of the specified group
	 * @params $groupId --> Group Id :: Type Integer
         * @returns updates the parent group in the varibale $parent_groups of the class
         */
	public function getAllParentGroups($groupid)
	{
		global $adb,$log, $table_prefix;
		$log->debug("Entering getAllParentGroups(".$groupid.") method...");
		
		$pgroups = $this->getGroups($groupid);
		foreach ($pgroups as $group_id) {
			if (!in_array($group_id,$this->parent_groups)) {
				$this->parent_groups[] = $group_id;
				$this->getAllParentGroups($group_id);
			}
		}
		
		$log->debug("Exiting getAllParentGroups method...");
	}
	
	protected function getGroups($groupid) {
		global $adb, $table_prefix;
		
		static $cache = array();
		if (!isset($cache[$groupid])) {
			$list = array();
			$query="select groupid from ".$table_prefix."_group2grouprel where containsgroupid=?";
			$result = $adb->pquery($query, array($groupid));
			if ($result && $adb->num_rows($result) > 0) {
				while($row=$adb->fetchByAssoc($result, -1, false)) {
					$list[] = $row['groupid'];
				}
			}
			$cache[$groupid] = $list;
		}
		return $cache[$groupid];
	}
}
