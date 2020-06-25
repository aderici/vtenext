/*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************/
/* crmv@190834 */

if (typeof(ProcessDiscoveryScript) == 'undefined') {
	ProcessDiscoveryScript = {
		
		download: function(format, id) {
			var me = this,
				url = 'index.php?module=Settings&action=SettingsAjax&file=ProcessDiscovery&mode=download&format='+format+'&id='+id;
			
			__download = function() {
				location.href = url;
			}
			__download();
		},
		upload: function(id) {
			jQuery.ajax({
				'url': 'index.php?module=Settings&action=SettingsAjax&file=ProcessDiscovery&mode=upload&id='+id,
				'type': 'POST',
				dataType: 'JSON',
				success: function(data) {
					if (data.success) location.href = 'index.php?module=Settings&action=SettingsAjax&file=ProcessMaker&parenttab=Settings&mode=detail&id='+data.id;
					else alert('Error');
				},
				error: function() {}
			});
		},

	}
}
