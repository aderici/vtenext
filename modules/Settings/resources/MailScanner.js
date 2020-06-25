/*+*************************************************************************************
* The contents of this file are subject to the VTECRM License Agreement
* ("licenza.txt"); You may not use this file except in compliance with the License
* The Original Code is: VTECRM
* The Initial Developer of the Original Code is VTECRM LTD.
* Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
* All Rights Reserved.
***************************************************************************************/

window.VTE = window.VTE || {};

VTE.Settings = VTE.Settings || {};

VTE.Settings.MailScannerInfo = VTE.Settings.MailScannerInfo || {

	performScanNow: function(app_key, scannername) {
		jQuery("#status").show();
		jQuery.ajax({
			url: 'index.php',
			method: 'POST',
			data: 'module=Settings&action=SettingsAjax&file=MailScanner&mode=scannow&service=MailScanner&app_key=' + encodeURIComponent(app_key) + '&scannername=' + encodeURIComponent(scannername),
			success: function(result) {
				jQuery("#status").hide();
			}
		});
	}

};

/**
 * @deprecated
 * This function has been moved to VTE.Settings.MailScannerInfo class.
 */

function performScanNow(app_key, scannername) {
	return VTE.callDeprecated('performScanNow', VTE.Settings.MailScannerInfo.performScanNow, arguments);
}
