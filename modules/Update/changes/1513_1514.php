<?php

// crmv@124066

$trans = array(
	'ALERT_ARR' => array(
		'it_it' => array(
			'LBL_CONFIRM_CLOSE_POPUP' => 'Chiudere il popup?',
		),
		'en_us' => array(
			'LBL_CONFIRM_CLOSE_POPUP' => 'Are you sure you want to close the popup?',
		),
	),
);
$languages = vtlib_getToggleLanguageInfo();
foreach ($trans as $module=>$modlang) {
	foreach ($modlang as $lang=>$translist) {
		if (array_key_exists($lang,$languages)) {
			foreach ($translist as $label=>$translabel) {
				SDK::setLanguageEntry($module, $lang, $label, $translabel);
			}
		}
	}
}