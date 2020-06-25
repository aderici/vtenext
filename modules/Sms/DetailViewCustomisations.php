<?php
/***************************************************************************************
 * The contents of this file are subject to the CRMVILLAGE.BIZ VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is:  CRMVILLAGE.BIZ VTECRM
 * The Initial Developer of the Original Code is CRMVILLAGE.BIZ.
 * Portions created by CRMVILLAGE.BIZ are Copyright (C) CRMVILLAGE.BIZ.
 * All Rights Reserved.
 ***************************************************************************************/

/* crmv@152701 */

global $currentModule;

// readonly record
$tool_buttons = Button_Check($currentModule);
$tool_buttons['EditView'] = 'no';	//crmv@16834

$smarty->assign('EDIT_PERMISSION', 'notpermitted');
$smarty->assign('EDIT_DUPLICATE', 'notpermitted');
$smarty->assign('CHECK', $tool_buttons);
