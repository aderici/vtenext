<?php
/*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************/
 
/* crmv@198024 */


$output = ['success' => false];

$forModule = $_REQUEST['formodule'];
$forField = $_REQUEST['forfield'];
$confid = intval($_REQUEST['confproductid']);

if ($forModule == 'Products' && $forField = 'confproductid' && isPermitted($forModule, 'EditView') == 'yes') {
	if ($confid > 0 && vtlib_isModuleActive('ConfProducts') && isPermitted('ConfProducts', 'DetailView', $confid) == 'yes') {
		$focus = CRMEntity::getInstance('ConfProducts');
		
		// TODO
		$html = $focus->getHtmlBlock($forModule, $forField, $confid);
				
		$output['success'] = true;
		$output['html'] = $html;
	} else {
		$output['error'] = 'Not Permitted';
	}
} else {
	$output['error'] = 'Not Permitted';
}

echo Zend_Json::encode($output);
die();
