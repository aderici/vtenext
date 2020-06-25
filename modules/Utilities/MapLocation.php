<?php
/*+*************************************************************************************
* The contents of this file are subject to the VTECRM License Agreement
* ("licenza.txt"); You may not use this file except in compliance with the License
* The Original Code is: VTECRM
* The Initial Developer of the Original Code is VTECRM LTD.
* Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
* All Rights Reserved.
***************************************************************************************/

// crmv@194390

require_once('modules/Geolocalization/Geolocalization.php');

$crmid = intval($_REQUEST['recordid']);
$module = getSalesEntityType($crmid);
$addressType = vtlib_purify($_REQUEST['address_type']);
$action = $_REQUEST['subaction'];
$json = null;

$mapLocationController = new MapLocationController();

if ($action == 'get_address_query') {
	$addressQuery = $mapLocationController->getAddressQuery($module, $crmid, $addressType);
	$addressQuery = urlencode($addressQuery);
	$ok = !empty($addressQuery);
	$json = array('success' => $ok, 'address_query' => $addressQuery, 'error' => ($ok ? '' : getTranslatedString('UNABLE_GENERATE_ADDRESS')));
} else {
	$json = array('success' => false, 'error' => "Unknwon action");
}

echo Zend_Json::encode($json);
exit();

class MapLocationController {

	public function getAddressQuery($module, $crmid, $addressType) {
		$GEO = Geolocalization::getInstance();

		$addressQuery = "";
		$addressFields = $this->getAddressFields($module, $addressType);

		if (count($addressFields) > 0) {
			$GEO->setAddressFieldForModule($module, $addressFields);
			$addressQuery = $GEO->getAddress($module, $crmid);
		}
		
		return $addressQuery;
	}

	public function getAddressFields($module, $addressType) {
		$addressFields = array();

		if ($module === 'Accounts') {
			if ($addressType === 'Main') {
				$addressFields = array('bill_street', 'bill_pobox', 'bill_city', 'bill_state', 'bill_country', 'bill_code');
			} elseif ($addressType === 'Other') {
				$addressFields = array('ship_street', 'ship_pobox', 'ship_city', 'ship_state', 'ship_country', 'ship_code');
			}
		} elseif ($module === 'Contacts') {
			if ($addressType === 'Main') {
				$addressFields = array('mailingstreet', 'mailingpobox', 'mailingcity', 'mailingstate', 'mailingcountry', 'mailingzip');
			} elseif ($addressType === 'Other') {
				$addressFields = array('otherstreet', 'otherpobox', 'othercity', 'otherstate', 'othercountry', 'otherzip');
			}
		} elseif ($module === 'Leads') {
			$addressFields = array('lane', 'pobox', 'city', 'state', 'country', 'code');
		}

		return $addressFields;
	}

}
