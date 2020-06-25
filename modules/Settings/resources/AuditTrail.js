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

VTE.Settings.AuditTrail = VTE.Settings.AuditTrail || {

	auditenabled: function(ochkbox) {
		if (ochkbox.checked == true) {
			var status='enabled';
			jQuery('#audit_info').html(alert_arr.LBL_AUDIT_TRAIL_ENABLED);
			jQuery('#audit_info').show();
		} else {
			var status = 'disabled';
			jQuery('#audit_info').html(alert_arr.LBL_AUDIT_TRAIL_DISABLED);
			jQuery('#audit_info').show();
		}
		jQuery("#status").show();
		jQuery.ajax({
			url: 'index.php',
			method: 'POST',
			data: 'module=Settings&action=SettingsAjax&file=SaveAuditTrail&ajax=true&audit_trail='+status,
			success: function(result) {
				jQuery("#status").hide();
			}
		});
		setTimeout(function() {
			jQuery('#audit_info').hide();
		}, 3000);
	},

	showAuditTrail: function() {
		var userid = jQuery('#user_list').val();
		openPopup("index.php?module=Settings&action=SettingsAjax&file=ShowAuditTrail&userid="+userid,"","width=645,height=750,resizable=0,scrollbars=1,left=100");
	},

	// crmv@164355
	exportAuditTrail: function() {
		var userid = jQuery('#user_list').val();
		location.href = "index.php?module=Settings&action=SettingsAjax&file=ExportAuditTrail&userid="+userid;
	},
	// crmv@164355e

	getListViewEntries_js(module, url) {
		var userid = document.getElementById('userid').value;
		jQuery.ajax({
			url: 'index.php',
			method: 'POST',
			data: 'module=Settings&action=SettingsAjax&file=ShowAuditTrail&ajax=true&'+url+'&userid='+userid,
			success: function(result) {
				jQuery("#AuditTrailContents").html(result);
			}
		});
	}

};

/**
 * @deprecated
 * This function has been moved to VTE.Settings.AuditTrail class.
 */

function auditenabled(ochkbox) {
	return VTE.callDeprecated('auditenabled', VTE.Settings.AuditTrail.auditenabled, arguments);
}

/**
 * @deprecated
 * This function has been moved to VTE.Settings.AuditTrail class.
 */

function showAuditTrail() {
	return VTE.callDeprecated('showAuditTrail', VTE.Settings.AuditTrail.showAuditTrail, arguments);
}

/**
 * @deprecated
 * This function has been moved to VTE.Settings.AuditTrail class.
 */

function exportAuditTrail() {
	return VTE.callDeprecated('exportAuditTrail', VTE.Settings.AuditTrail.exportAuditTrail, arguments);
}
