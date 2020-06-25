<?php

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

