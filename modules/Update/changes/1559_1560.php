<?php

// crmv@141534
// drop 2 columns at once
if (in_array('header', $adb->getColumnNames('tbl_s_newsletter_bounce'))) {
	if ($adb->isMysql()) {
		$adb->query("ALTER TABLE tbl_s_newsletter_bounce DROP COLUMN header, DROP COLUMN data");
	} elseif ($adb->isMssql()) {
		$adb->query("ALTER TABLE tbl_s_newsletter_bounce DROP COLUMN header, data");
	} elseif ($adb->isOracle()) {
		$adb->query("ALTER TABLE tbl_s_newsletter_bounce DROP (header, data)");
	} else {
		$sqlarray = $adb->datadict->DropColumnSQL('tbl_s_newsletter_bounce','header');
		$adb->datadict->ExecuteSQLArray($sqlarray);
		$sqlarray = $adb->datadict->DropColumnSQL('tbl_s_newsletter_bounce','data');
		$adb->datadict->ExecuteSQLArray($sqlarray);
	}
}

$trans = array(
	'APP_STRINGS' => array(
		'en_us' => array(
			'Page' => 'Page',
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
