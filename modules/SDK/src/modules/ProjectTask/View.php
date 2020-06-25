<?php
/*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************/
/* crmv@104562 */
global $sdk_mode;
switch($sdk_mode) {
	case '':
		$col_fields['auto_working_days'] = 1;
		break;
}

// crmv@OPER4876 crmv@189362
switch($sdk_mode) {
	case '':
	case 'edit':
	case 'detail':
		require_once('modules/SDK/src/CalendarTracking/CalendarTrackingUtils.php');
		$ro = CalendarTracking::getFieldReadonly('ProjectTask', $fieldname, $col_fields);
		if (!empty($ro)) {
			$readonly = $ro;
			$success = true;
		}
		$cache = RCache::getInstance();
		$cache->set('sdk_conditional_fields',array('servicetype'));
		break;
	case 'popup_query':
	case 'list_related_query':
		$sdk_columns = array($table_prefix.'_projecttask.servicetype');
		include('modules/SDK/AddColumnsToQueryView.php');
		break;
	case 'popup':
	case 'related':
	case 'list':
		$sdk_columnnames = array('servicetype');
		include('modules/SDK/GetFieldsFromQueryView.php');
		require_once('modules/SDK/src/CalendarTracking/CalendarTrackingUtils.php');
		$ro = CalendarTracking::getFieldReadonly('ProjectTask', $fieldname, $sdk_columnvalues);
		if (!empty($ro)) {
			$readonly = $ro;
			$success = true;
		}
		break;
}
// crmv@OPER4876e crmv@189362e