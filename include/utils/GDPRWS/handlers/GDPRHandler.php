<?php
/*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************/

// crmv@161554

class GDPRHandler extends VTEventHandler {

	function handleEvent($eventName, $data) {
		global $adb, $table_prefix;
		
		$id = $data->getId();
		$module = $data->focus->modulename;
		
		$GDPRWS = GDPRWS::getInstance();
		if (!$GDPRWS->isEnabledForModule($module)) {
			return false;
		}
		
		if ($eventName == 'vtiger.entity.beforesave') {
			if (!$data->isNew()) {
				$oldNotifyChange = getSingleFieldValue($data->focus->table_name, 'gdpr_notifychange', $data->focus->table_index, $id);
				if ($oldNotifyChange === '1') {
					if ($data->get('gdpr_notifychange') === '1') {
						$GDPRWS->sendContactNotifyChangeEmail($module, $id);
					}
				}
			}
		}
	}

}
