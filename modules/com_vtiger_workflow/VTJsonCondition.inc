<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/
	require_once("include/Zend/Json.php");

	class VTJsonCondition{
		function  __construct(){
		}
		
		function evaluate($condition, $entityCache, $id){
			$expr = Zend_Json::decode($condition);
			$result=TRUE;
			$data = $entityCache->forId($id)->getData();
			
			foreach($expr as $cond){
				preg_match('/(\w+) : \((\w+)\) (\w+)/', $cond['fieldname'], $matches);
				if(count($matches)==0){
					$result = $this->checkCondition($entityCache->forId($id), $cond); //crmv@36510
				}else{
					list($full,  $referenceField, $referenceModule, $fieldname) = $matches;
					$referenceFieldId = $data[$referenceField];
					if($referenceFieldId != 0){
						//crmv@OPER10174
						if (stripos($referenceFieldId,'x') === false) $referenceFieldId = vtws_getWebserviceEntityId($referenceModule,$referenceFieldId);
						$entity = $entityCache->forId($referenceFieldId);
						//crmv@OPER10174e
						if ($entity->getModuleName() == $referenceModule || ($entity->getModuleName() == 'Groups' && $referenceModule == 'Users')) {	//crmv@53590
							$entityData = $entity->getData();
							$cond['fieldname'] = $fieldname;
							$result = $this->checkCondition($entity, $cond); //crmv@36510
						}else{
							$result = false;
						}
					}else{
						if ($cond['operation'] == 'is' && empty($cond['value'])) return true;	//crmv@105312
						return false;
					}
				}
				if($result==false){
					return false;
				}
			}
			return true;
		}
		function startsWith($str, $subStr){
			$sl = strlen($str);
			$ssl = strlen($subStr);
			if($sl>=$ssl){
				return substr_compare($str,$subStr,0, $ssl)==0;
			}else{
				return FALSE;
			}
		}

		function endsWith($str, $subStr){
			$sl = strlen($str);
			$ssl = strlen($subStr);
			if($sl>=$ssl){
				return substr_compare($str,$subStr,$sl-$ssl, $ssl)==0;
			}else{
				return FALSE;
			}
		}
		
		//crmv@158293
		function getDisplayValue($module, $cond, &$fieldValue, $vtwsIdComponents=false) {
			global $adb, $table_prefix;
			
			// crmv@68366
			if ($cond['fieldname'] == 'mailscanner_action') {
				if (is_readable('modules/SDK/src/204/204.php')) {
					require_once('modules/SDK/src/204/204.php');
					$fieldValue = getScannerNameFromAction($fieldValue);
				}
			// crmv@68366e
			//crmv@128656 crmv@161604
			} elseif ($cond['fieldname'] == 'roleid' && $module == 'Users' && preg_match("/^H[0-9]*$/",$cond['value']) == 0) {
				$fieldValue=getRoleName($fieldValue);
			//crmv@128656e crmv@161604e
			// crmv@140584
			} elseif ($cond['fieldname'] == 'user_name' && !empty($data['groupname'])) {
				$fieldValue = $data['groupname'];
			}
			// crmv@140584e
			elseif (!empty($fieldValue)) {
				$webservice_field = WebserviceField::fromCachedWS($module, $cond['fieldname']); // crmv@193294
				if ($webservice_field->getFieldDataType() == 'owner') {
					if ($vtwsIdComponents) {
						$idParts = vtws_getIdComponents($fieldValue);
						$fieldValue = $idParts[1];
					}
					$fieldValue = getUserName($fieldValue);
				} elseif ($webservice_field->getFieldDataType() == 'reference') {
					if ($vtwsIdComponents) {
						$idParts = vtws_getIdComponents($fieldValue);
						$fieldValue = $idParts[1];
					}
					$referenceList = $webservice_field->getReferenceList();
					(count($referenceList) == 1) ? $referenceModule = $referenceList[0] : $referenceModule = getSalesEntityType($idParts[1]);
					if ($referenceModule == 'Users') {
						$fieldValue = getUserName($fieldValue);
					} elseif ($referenceModule == 'Groups') {
						$fieldValue = fetchGroupName($fieldValue);
					} else {
						$fieldValue = getEntityName($referenceModule, $fieldValue, true);
					}
				//crmv@161604
				} elseif ($webservice_field->getUIType() == '221' && preg_match("/^H[0-9]*$/",$cond['value']) == 0) {
					$fieldValue=getRoleName($fieldValue);
				}
				//crmv@161604e
			}
			
			return $fieldValue;
		}
		//crmv@158293e
		
		//crmv@36510
		function checkCondition($entityData, $cond){
			$data = $entityData->getData();
			//crmv@36510 e
			$condition = $cond['operation'];
			$fieldValue = $data[$cond['fieldname']];
			$value = html_entity_decode($cond['value']);
			$fieldValue = $this->getDisplayValue($entityData->getModuleName(), $cond, $fieldValue, true); //crmv@158293
			
			// crmv@189728
			require_once('modules/Settings/ProcessMaker/ProcessMakerUtils.php');
			$PMUtils = ProcessMakerUtils::getInstance();
			$PMUtils->debug('Conditions', "checkCondition: '$fieldValue' $condition '$value'");
			// crmv@189728e
			
			// crmv@195745
			if ($entityData->getModuleName() == 'CycleEntity' && $cond['fieldname'] == 'discount' && $value !== '') {
				// be sure to compare the same type of discounts, percentage or amount
				$valueWithPerc = (strpos($value, '%') !== false);
				$fieldvalueWithPerc = (strpos($fieldValue, '%') !== false);
				if ($valueWithPerc !== $fieldvalueWithPerc) return false;
			}
			// crmv@195745e
			
			switch($condition){
				case "equal to":
					return $fieldValue == $value;
				case "less than":
					return $fieldValue < $value;
				case "greater than":
					return $fieldValue > $value;
				case "does not equal":
					return $fieldValue != $value;
				case "less than or equal to":
					return $fieldValue <= $value;
				case "greater than or equal to":
					return $fieldValue >= $value;
				case "is":
					if(preg_match('/([^:]+):boolean$/', $value, $match)){
						$value = $match[1];
						if($value=='true'){
							return $fieldValue==='on' || $fieldValue===1 || $fieldValue==='1';
						}else{
							return $fieldValue==='off' || $fieldValue===0 || $fieldValue==='0' || $fieldValue==='' || $fieldValue===NULL; // crmv@142398
						}
					}else{
						return $fieldValue == $value;
					}
				//crmv@36510	
				case "is not":
					if (preg_match('/([^:]+):boolean$/', $value, $match)) {
						$value = $match[1];
						if ($value == 'true') {
							return $fieldValue === 'off' || $fieldValue === 0 || $fieldValue === '0' || $fieldValue === '' || $fieldValue === NULL;	//crmv@53590 crmv@142398
						} else {
							return $fieldValue === 'on' || $fieldValue === 1 || $fieldValue === '1';
						}
					} else {
						return $fieldValue != $value;
					}
				case "contains":
					if (strpos($value,",") !==false){
						$values = explode(",",$value);
						
					}
					else{
						$values = Array($value);
					}
					$found = false;
					foreach ($values as $val){
						if (strpos($fieldValue, $val) !== FALSE){
							$found = true;
							break;
						}
					}
					return $found;
				//crmv@36510 e						
				case "does not contain":
					return strpos($fieldValue, $value) === FALSE;
				case "starts with":
					return $this->startsWith($fieldValue,$value);
				case "ends with":
					return $this->endsWith($fieldValue, $value);
				case "matches":
					return preg_match($value, $fieldValue);
				//crmv@36510	
				case "has changed" :
					require_once('data/VTEntityDelta.php');	//crmv@141000
					$entityDelta = new VTEntityDelta();
					$idParts = vtws_getIdComponents($entityData->getId());
					//crmv@42329
					$oldentity = $entityDelta->getOldEntity($entityData->getModuleName(), $idParts[1]);
					if (empty($oldentity)){
						$hasChanged = true;
					}
					else{
						$hasChanged = $entityDelta->hasChanged($entityData->getModuleName(), $idParts[1], $cond['fieldname']);
					}
					//crmv@42329e
					$fieldValue = $entityDelta->getCurrentValue($entityData->getModuleName(), $idParts[1], $cond['fieldname']);	//crmv@144982
					//crmv@121981
					if (preg_match('/([^:]+):boolean$/', $value, $match)) {
						$value = $match[1];
						if ($value == 'true') {
							$value = 1;
						} else {
							$value = 0;
						}
					}
					//crmv@121981e
					$fieldValue = $this->getDisplayValue($entityData->getModuleName(), $cond, $fieldValue); //crmv@158293
					if (empty($value)) {
						return $hasChanged;
					} else {
						return $hasChanged && $fieldValue == $value;	//!!!crmv@51308!!!
					}
				//crmv@36510 e				
				// crmv@102879
				case "has exactly":
					$rows = is_array($fieldValue) ? count($fieldValue) : 0;
					return $rows == $value;
				case "has more than":
					$rows = is_array($fieldValue) ? count($fieldValue) : 0;
					return $rows > $value;
					break;
				case "has less than":
					$rows = is_array($fieldValue) ? count($fieldValue) : 0;
					return $rows < $value;
					break;
				// crmv@102879e
				default:
					//Unexpected condition
					throw new Exception("Found an unexpected condition: ".$condition);
			}
		}
	}
?>
