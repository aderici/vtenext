<?php
/*+***********************************************************************************
 * The contents of this file are subject to the CRMVILLAGE.BIZ CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  CRMVILLAGE.BIZ CRM Open Source
 * The Initial Developer of the Original Code is CRMVILLAGE.BIZ.
 * Portions created by CRMVILLAGE.BIZ are Copyright (C) CRMVILLAGE.BIZ.
 * All Rights Reserved.
 *************************************************************************************/
 
/* crmv@164465 */

// for now only vteprop is supported
$VP = VTEProperties::getInstance();
if (!$VP->checkCacheValidity()) {
	$VP->rebuildCache();
}

// you can add here other caches to update