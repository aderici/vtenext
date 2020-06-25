<?php
/*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************/
/* crmv@99316 crmv@198388 */

require_once('modules/Settings/ProcessMaker/ProcessDynaForm.php');

// crmv@105933 crmv@181170
// remove some tools for the module
if ($smarty && is_array($smarty->getTemplateVars('CHECK'))) {
	$tool_buttons = $smarty->getTemplateVars('CHECK');
	unset($tool_buttons['EditView']);
	unset($tool_buttons['Import']);
	unset($tool_buttons['Merge']);
	unset($tool_buttons['DuplicatesHandling']);
	$smarty->assign('CHECK', $tool_buttons);
}
// crmv@105933e crmv@181170e

$condFields = array();
$processDynaFormObj = ProcessDynaForm::getInstance();
$enable = $processDynaFormObj->existsConditionalFpovValueActive($focus, $condFields);
if ($enable) {
	$smarty->assign('AJAXONCLICKFUNCT', 'ProcessMakerScript.checkAjaxSave');
	$smarty->assign('CONDITIONAL_FIELDS', $condFields);
}
$smarty->assign('AJAXSAVEFUNCTION', 'DynaFormScript.dtlViewAjaxSave');
$smarty->assign('TEMPLATE', $smarty_template);
$smarty_template = 'modules/Processes/DetailView.tpl';