<?php

// crmv@140887

include_once "include/utils/FSUtils.php";

FSUtils::deleteFolder('modules/System');
@unlink('Smarty/templates/SysInformation.tpl');

global $adb, $table_prefix;

$adb->addColumnToTable("{$table_prefix}_settings_blocks", 'image', 'VARCHAR(100) NULL AFTER sequence');

$adb->pquery("UPDATE {$table_prefix}_settings_blocks SET image = ? WHERE label = ?", array('people', 'LBL_USER_MANAGEMENT'));
$adb->pquery("UPDATE {$table_prefix}_settings_blocks SET image = ? WHERE label = ?", array('business', 'LBL_STUDIO'));
$adb->pquery("UPDATE {$table_prefix}_settings_blocks SET image = ? WHERE label = ?", array('public', 'LBL_COMMUNICATION_TEMPLATES'));
$adb->pquery("UPDATE {$table_prefix}_settings_blocks SET image = ? WHERE label = ?", array('build', 'LBL_OTHER_SETTINGS'));
$adb->pquery("UPDATE sdk_menu_contestual SET image = ?, action = ? WHERE module = ? AND title = ?", array('euro_symbol', 'index', 'Potentials', 'Budget'));

$trans = array(
	'APP_STRINGS' => array(
		'en_us' => array(
			'LBL_NO_LASTVIEWED' => 'No recents',
			'LBL_NO_FAVORITES' => 'No favorites',
			'LBL_NO_TODOS' => 'No todos',
			'LBL_NO_NOTIFICATIONS' => 'No notifications',
			'LBL_DROP_FILES_HERE' => 'Rilascia i file qui',
		),
		'it_it' => array(
			'LBL_NO_LASTVIEWED' => 'Nessun recente',
			'LBL_NO_FAVORITES' => 'Nessun preferito',
			'LBL_NO_TODOS' => 'Nessun compito',
			'LBL_NO_NOTIFICATIONS' => 'Nessuna notifica',
			'LBL_DROP_FILES_HERE' => 'Drop files here',
		),
	),
	'ALERT_ARR' => array(
		'en_us' => array(
			'LBL_ADDTODO' => 'To Do',
		),
		'it_it' => array(
			'LBL_ADDTODO' => 'Compito',
		),
		'de_de' => array(
			'LBL_ADDTODO' => 'Aufgabe',
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
