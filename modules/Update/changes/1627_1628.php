<?php 


$trans = array(
	'ChangeLog' => array(
		'it_it' => array(
			'LBL_RECORD_DOWNLOADED' => 'ha scaricato il file',
		),
		'en_us' => array(
			'LBL_RECORD_DOWNLOADED' => 'downloaded the file',
		),
	),
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
