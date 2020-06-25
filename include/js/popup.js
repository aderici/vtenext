/*********************************************************************************
  ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
   * ("License"); You may not use this file except in compliance with the License
   * The Original Code is:  vtiger CRM Open Source
   * The Initial Developer of the Original Code is vtiger.
   * Portions created by vtiger are Copyright (C) vtiger.
   * Portions created by CRMVILLAGE.BIZ are Copyright (C) CRMVILLAGE.BIZ.
   * All Rights Reserved.
  *
 ********************************************************************************/

//crmv@182677
function mypopup(params) {
	if (params != undefined) {
		var sessionValidatorCheck = true;
		var url = "copyright.php"+params;
	} else {
		var sessionValidatorCheck = false;
		var url = "copyright.php";
	}
	mywindow = openPopup(url,"mywindow","width=900, height=400",'',900,400,'','',sessionValidatorCheck);//crmv@22106
}
//crmv@182677e

function newpopup(str) {
	openPopup(str,"mywinw","menubar=1,resizable=1,scrollbars=yes");//crmv@22106
}