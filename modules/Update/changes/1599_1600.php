<?php

$trans = array(
	'APP_STRINGS' => array(
		'it_it' => array(
			'LBL_BACK_TO_LIST' => 'Ritorna alla lista'
		),
		'en_us' => array(
			'LBL_BACK_TO_LIST' => 'Back to list'
		)
	)
);

$languages = vtlib_getToggleLanguageInfo();
foreach ($trans as $module => $modlang) {
	foreach ($modlang as $lang => $translist) {
		if (array_key_exists($lang, $languages)) {
			foreach ($translist as $label => $translabel) {
				SDK::setLanguageEntry($module, $lang, $label, $translabel);
			}
		}
	}
}