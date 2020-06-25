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

VTE.Settings.DefModuleView = VTE.Settings.DefModuleView || {

	// crmv@192033
	viewenabled: function(ochkbox) {
		if (ochkbox.checked == true) {
			var status = 'enabled';
			jQuery('#view_info').html('Singlepane View Enabled').show();
		} else {
			var status = 'disabled';
			jQuery('#view_info').html('Singlepane View Disabled').show();
		}
		
		jQuery("#status").show();
		jQuery.ajax({
			url: 'index.php',
			method: 'post',
			data: 'module=Users&action=UsersAjax&file=SaveDefModuleView&ajax=true&audit_trail='+status,
			success: function(result) {
				jQuery("#status").hide();
			}
		});
		
		setTimeout("hide('view_info')", 3000);
	}
	// crmv@192033e

};

/**
 * @deprecated
 * This function has been moved to VTE.Settings.DefModuleView class.
 */

function viewenabled(ochkbox) {
	return VTE.callDeprecated('viewenabled', VTE.Settings.DefModuleView.viewenabled, arguments);
}
