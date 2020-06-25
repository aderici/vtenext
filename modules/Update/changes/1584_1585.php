<?php

// crmv@147433 crmv@144123

$trans = array(
	'APP_STRINGS' => array(
		'it_it' => array(
			'NTC_CONTACT_DELETE_CONFIRMATION' => 'La cancellazione di questo Contatto rimuoverà anche le opportunità associate. Sicuro di voler cancellare il Contatto?',
		),
		'en_us' => array(
			'NTC_CONTACT_DELETE_CONFIRMATION' => 'Deleting this contact will remove its related Potentials. Are you sure you want to delete this contact?',
		),
	),
	'ALERT_ARR' => array(
		'it_it' => array(
			'LBL_EXTWS_DUP_RETURN_FIELDS' => 'I campi restituiti devono avere nomi diversi',
			'LBL_EXTWS_EMPTY_RETURN_FIELD' => 'Imposta un valore per tutti i campi restituiti',
			'LBL_DONT_USE' => 'Non usare',
			'DELETE_CONTACT' => 'Cancellando questo contatto verranno cancellate anche le opportunità associate. Sicuro di volerlo eliminare?',
			'DELETE_CONTACTS' => 'Cancellando questi contatti verranno cancellate anche le opportunità associate. Sicuro di volerli eliminare?',
		),
		'en_us' => array(
			'LBL_EXTWS_DUP_RETURN_FIELDS' => 'Returned fields must have distinct names',
			'LBL_EXTWS_EMPTY_RETURN_FIELD' => 'Specify an expression for all the return fields',
			'LBL_DONT_USE' => 'Don\'t use',
			'DELETE_CONTACT' => 'Deleting this contact will remove its related potentials. Are you sure you want to delete it?',
			'DELETE_CONTACTS' => 'Deleting these contacts will remove its related potentials. Are you sure you want to delete them?',
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
