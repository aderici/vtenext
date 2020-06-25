<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/
/* crmv@392267 crmv@171577 */
require_once 'include/events/VTEntityData.inc';

class VTEntityDelta extends VTEventHandler {
	private static $oldEntity;
	private static $newEntity;
	private static $entityDelta;
	
	function  __construct() {}
	
	function handleEvent($eventName, $entityData) {
		
		$adb = PearDatabase::getInstance();
		$moduleName = $entityData->getModuleName();
		$recordId = $entityData->getId();
		
		if($eventName == 'vtiger.entity.beforesave') {
			if(!empty($recordId)) {
				$this->setOldEntity($moduleName, $recordId); //crmv@185058
			}
		}
		
		if($eventName == 'vtiger.entity.aftersave'){
			if(!empty($recordId)) {
				self::$newEntity[$moduleName][$recordId] = VTEntityData::fromEntityId($adb, $recordId);
				$this->computeDelta($moduleName, $recordId);
			}
		}
	}
	
	function computeDelta($moduleName, $recordId) {
		
		$delta = array();
		
		$oldData = array();
		$oldEntity = $this->getOldEntity($moduleName, $recordId);
		if(!empty($oldEntity)) {
			$oldData = $oldEntity->getData();
		}
		$newEntity = $this->getNewEntity($moduleName, $recordId);
		$newData = $newEntity->getData();
		/** Detect field value changes **/
		foreach($newData as $fieldName => $fieldValue) {
			$isModified = false;
			if(empty($oldData[$fieldName])) {
				if(!empty($newData[$fieldName])) {
					$isModified = true;
				}
			} elseif($oldData[$fieldName] != $newData[$fieldName]) {
				$isModified = true;
			}
			if($isModified) {
				$delta[$fieldName] = array('oldValue' => $oldData[$fieldName], 'currentValue' => $newData[$fieldName]);
			}
		}
		//crmv@181690
		/* crmv@136148
		if (!isset(self::$entityDelta[$moduleName][$recordId])) self::$entityDelta[$moduleName][$recordId] = array();
		self::$entityDelta[$moduleName][$recordId] = array_merge(self::$entityDelta[$moduleName][$recordId], $delta);
		*/
		self::$entityDelta[$moduleName][$recordId] = $delta;
		//crmv@181690e
	}
	
	function getEntityDelta($moduleName, $recordId) {
		if (!isset(self::$entityDelta[$moduleName][$recordId]) && in_array($moduleName,array('Calendar','Events'))) {
			return self::$entityDelta['Activity'][$recordId];
		} else {
			return self::$entityDelta[$moduleName][$recordId];
		}
	}
	
	function getOldValue($moduleName, $recordId, $fieldName) {
		$entityDelta = $this->getEntityDelta($moduleName, $recordId);
		return $entityDelta[$fieldName]['oldValue'];
	}
	
	function getCurrentValue($moduleName, $recordId, $fieldName) {
		$entityDelta = $this->getEntityDelta($moduleName, $recordId);
		return $entityDelta[$fieldName]['currentValue'];
	}
	
	function getOldEntity($moduleName, $recordId) {
		if (!isset(self::$oldEntity[$moduleName][$recordId]) && in_array($moduleName,array('Calendar','Events'))) {
			return self::$oldEntity['Activity'][$recordId];
		} else {
			return self::$oldEntity[$moduleName][$recordId];
		}
	}
	
	// crmv@185058 crmv@171524
	function setOldEntity($moduleName, $recordId, $entity=null) {
		global $adb;
		if (!empty($entity))
			self::$oldEntity[$moduleName][$recordId] = $entity;
		else
			self::$oldEntity[$moduleName][$recordId] = VTEntityData::fromEntityId($adb, $recordId);
	}
	// crmv@185058e crmv@171524e
	
	function getNewEntity($moduleName, $recordId) {
		if (!isset(self::$newEntity[$moduleName][$recordId]) && in_array($moduleName,array('Calendar','Events'))) {
			return self::$newEntity['Activity'][$recordId];
		} else {
			return self::$newEntity[$moduleName][$recordId];
		}
	}
	
	// crmv@171524
	function setNewEntity($moduleName, $recordId, $entity) {
		self::$newEntity[$moduleName][$recordId] = $entity;
	}
	// crmv@171524e
	
	function hasChanged($moduleName, $recordId, $fieldName) {
		$oldEntity = $this->getOldEntity($moduleName, $recordId);
		if(empty($oldEntity)) {
			return false;
		}
		$entityDelta = $this->getEntityDelta($moduleName, $recordId);
		$fieldDelta = $entityDelta[$fieldName];
		return $fieldDelta['oldValue'] != $fieldDelta['currentValue'];
	}
	
}
