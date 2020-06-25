<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/

/* crmv@158543 */
/* This file is obsolete and now it's used only for the calendar module. It will be removed in the future */


if ($_REQUEST["fld_module"] != 'Calendar') die('Module not permitted');

require_once('modules/Settings/LayoutBlockListUtils.php');
deleteCustomField(); // parameters are passed in the request

// redirect to the list
header("Location:index.php?module=Settings&action=CustomFieldList&fld_module=".$fld_module."&parenttab=Settings");
