<?php

require_once('modules/Geolocalization/Geolocalization.php');

/* crmv@158563 */

class GeolocalizationHandler extends VTEventHandler {

	function handleEvent($eventName, $entityData) {
		
		$moduleName = $entityData->getModuleName();
		if ($moduleName == 'Activity') $moduleName = 'Calendar';
		
		if ($eventName == 'vtiger.entity.beforesave') {
		
			if ($entityData->isNew()) {
				$entityData->oldAddress = '';
			} else {
				$geo = Geolocalization::getInstance();
				if ($geo->isModuleHandled($moduleName)) {
					$crmid  = $entityData->getId();
					$entityData->oldAddress = $geo->getAddress($moduleName, $crmid);
				}
			}
			
		}

		if ($eventName == 'vtiger.entity.aftersave') {

			$geo = Geolocalization::getInstance();

			if ($geo->isModuleHandled($moduleName) && isset($entityData->oldAddress)) {
				$data = $entityData->getData();
				$crmid  = $entityData->getId();
				$address = $geo->getAddress($moduleName, $crmid , $data);
				if ($address != $entityData->oldAddress) {
					$geo->saveAddressCoords($moduleName, $crmid, $address);
				}
			}
		}
	}

}
