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

VTE.Settings.ProxyServer = VTE.Settings.ProxyServer || {

	validate: function() {
		if (!emptyCheck("server", "Proxy Server Name", "text")) return false;
		if (!emptyCheck("port", "Port Number", "text")) return false;

		if (isNaN(document.tandc.port.value)) {
			alert(alert_arr.LBL_ENTER_VALID_PORT);
			return false;
		}

		if (!emptyCheck("server_username", "Proxy User Name", "text")) return false;
		if (!emptyCheck("server_password", "Proxy Password", "text")) return false;

		return true;
	}

};

/**
 * @deprecated
 * This function has been moved to VTE.Settings.ProxyServer class.
 */

function validate() {
	return VTE.callDeprecated('validate', VTE.Settings.ProxyServer.validate, arguments);
}
