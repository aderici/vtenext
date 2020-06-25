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

VTE.Settings.EditCompanyInfo = VTE.Settings.EditCompanyInfo || {

	verify_data: function(form, company_name) {
		if (form.organization_name.value == "") {
			alert(sprintf(alert_arr.CANNOT_BE_NONE, company_name));
			form.organization_name.focus();
			return false;
		} else if (form.organization_name.value.replace(/^\s+/g, '').replace(/\s+$/g, '').length == 0) {
			alert(sprintf(alert_arr.CANNOT_BE_EMPTY, company_name));
			form.organization_name.focus();
			return false;
		} else if (!upload_filter("binFile","jpg|jpeg|JPG|JPEG|png|PNG")) { //crmv@106075
			form.binFile.focus();
			return false;
		} else {
			return true;
		}
	},

};

/**
 * @deprecated
 * This function has been moved to VTE.Settings.EditCompanyInfo class.
 */

function verify_data(form, company_name) {
	return VTE.callDeprecated('verify_data', VTE.Settings.EditCompanyInfo.verify_data, arguments);
}
