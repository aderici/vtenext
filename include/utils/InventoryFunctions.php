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

/* crmv@64542 crmv@151308 */

/**
 * Get the modules with the products block
 */
function getInventoryModules() {
	$inventory_modules = TabdataCache::get('inventory_modules'); //crmv@140903
	if (!isset($inventory_modules)) {
		// fallback on these modules in case of error
		$inventory_modules = array('Quotes', 'SalesOrder', 'PurchaseOrder', 'Invoice', 'Ddt');
	}
	return $inventory_modules;
}

/**
 * Return true if the module has the products block
 */
function isInventoryModule($modname) {
	return in_array($modname, getInventoryModules());
}

/**
 * Get the modules that can be used as products for inventory modules
 */
function getProductModules() {
	$product_modules = TabdataCache::get('product_modules'); //crmv@140903
	if (!isset($product_modules)) {
		// fallback on these modules in case of error
		$product_modules = array('Products', 'Services');
	}
	return $product_modules;
}

/**
 * Return true if the module can be used in the products block
 */
function isProductModule($modname) {
	return in_array($modname, getProductModules());
}


// some aliases for quick access
function parseUserNumber($n) { return InventoryUtils::callMethodByName(__FUNCTION__, func_get_args()); }
function formatUserNumber($n) { return InventoryUtils::callMethodByName(__FUNCTION__, func_get_args()); }


// used in workflows
function handleInventoryProductRel($entity){
	$InventoryUtils = InventoryUtils::getInstance();
	$InventoryUtils->updateInventoryProductRel($entity);
}
