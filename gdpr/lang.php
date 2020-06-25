<?php
/*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************/

// crmv@161554

defined('BASEPATH') OR exit('No direct script access allowed');

global $language;

$languageFile = 'languages/lang_'.$language.'.php';

if (file_exists($languageFile) && is_readable($languageFile)) {
	$translations = require($languageFile);
}

function _T($label, $args = array()) {
	global $translations;
	return isset($translations[$label]) ? vsprintf($translations[$label], $args) : $label;
}