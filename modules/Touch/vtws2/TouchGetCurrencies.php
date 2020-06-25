<?php

/* crmv@134732 */

class TouchGetCurrencies extends TouchWSClass {

	function process(&$request) {
		global $touchInst, $touchCache;
		
		$all = ($request['getall'] == 'true');

		$currencies = array();
		
		$IUtils = InventoryUtils::getInstance();
		$curr = $IUtils->getAllCurrencies($all ? 'all' : 'available');
		
		// transform the array
		if (is_array($curr)) {
			foreach ($curr as $c) {
				$currencies[] = array(
					'currencyid' => intval($c['curid']),
					'name' => $c['currencylabel'],
					'code' => $c['currencycode'],
					'symbol' => html_entity_decode($c['currencysymbol'], ENT_COMPAT, 'UTF-8'),
					'symbol_html' => $c['currencysymbol'],
					'rate' => floatval($c['conversionrate']),
				);
			}
		}

		return $this->success(array('currencies' => $currencies, 'total' => count($currencies)));
	}

}

