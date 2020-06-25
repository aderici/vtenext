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

VTE.Settings.FieldAccess = VTE.Settings.FieldAccess || {

	changemodules: function(selectmodule) {
		jQuery('#' + def_field).hide();
		var module = selectmodule.options[selectmodule.options.selectedIndex].value;
		document.getElementById('fld_module').value = module; 
		window.def_field = module + "_fields";
		jQuery('#' + def_field).show();
	}

};

/**
 * @deprecated
 * This function has been moved to VTE.Settings.FieldAccess class.
 */

function changemodules(selectmodule) {
	return VTE.callDeprecated('changemodules', VTE.Settings.FieldAccess.changemodules, arguments);
}
