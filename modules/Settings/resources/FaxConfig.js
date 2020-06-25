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

VTE.Settings.FaxConfig = VTE.Settings.FaxConfig || {

	validate_fax_server: function(form) {
		if (form.server.value == '') {
			alert(alert_arr.SERVERNAME_CANNOT_BE_EMPTY);
			return false;
		}

		return true;
	},

};

/**
 * @deprecated
 * This function has been moved to VTE.Settings.FaxConfig class.
 */

function validate_fax_server(form) {
	return VTE.callDeprecated('validate_fax_server', VTE.Settings.FaxConfig.validate_fax_server, arguments);
}
