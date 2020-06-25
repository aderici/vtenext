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

VTE.Settings.Group = VTE.Settings.Group || {

	deletegroup: function(obj, groupid) {
		jQuery("#status").show();
		jQuery.ajax({
			url: 'index.php',
			method: 'POST',
			data: 'module=Users&action=UsersAjax&file=GroupDeleteStep1&groupid='+groupid,
			success: function(result) {
				jQuery("#status").hide();
				jQuery("#tempdiv").html(result);
				showFloatingDiv('DeleteLay', obj);
			}
		});
	}

};

/**
 * @deprecated
 * This function has been moved to VTE.Settings.Group class.
 */

function transferCurrency(obj, groupid) {
	return VTE.callDeprecated('deletegroup', VTE.Settings.Group.deletegroup, arguments);
}
