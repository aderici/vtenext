<?php

// crmv@131904

$trans = array(
	'Users' => array(
		'en_us' => array(
			'LBL_RECOVERY_SYSTEM1' => 'Welcome to the password recovery system.<br />If you are not the user',
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
