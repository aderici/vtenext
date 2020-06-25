<?php
/*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************/
 
/* crmv@181161 */

class UpdatePopupHandler extends VTEventHandler {

	function handleEvent($eventName, $user) {
	
		return; // update popup has been removed!!!
		
		if (!is_admin($user)) return;
		if (!vtlib_isModuleActive('Update')) return;
		
		$VP = VTEProperties::getInstance();
		
		$docheck = $VP->get('update.check_updates');
		if ($docheck != 1) return;
	
		// ok, we can check the status
		
		require_once('modules/Update/AutoUpdater.php');
		
		$AU = new AutoUpdater();
		if ($AU->shouldShowPopup($user)) {
		
			$version = $AU->getInfo('new_version');
			if (!$version) return; // some error during version retrieval
			
			$smarty = new VteSmarty();
			
			$title = getTranslatedString('LBL_POPUP_TITLE', 'Update');
			$title = str_replace('{version}', $version, $title);
			$smarty->assign('POPUP_TITLE', $title);
			$smarty->assign('POPUP_SUBTITLE', '');
			
			$smarty->assign('REMINDER_OPTIONS', $AU->getReminderOptions());
			$smarty->assign('POPUP_DELAY', 2000); // show after 2 secs
			
			$smarty->display('modules/Update/Popup.tpl');
		}
	}
	
}
