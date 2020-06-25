<?php

// crmv@146671

$schema_table =
'<schema version="0.3">
	<table name="'.$table_prefix.'_process_extws">
		<opt platform="mysql">ENGINE=InnoDB</opt>
		<field name="running_process" type="I" size="19">
			<KEY/>
		</field>
		<field name="metaid" type="I" size="19">
			<KEY/>
		</field>
		<field name="results" type="XL" />
		<field name="done" type="I" size="1" />
	</table>
</schema>';
if(!Vtiger_Utils::CheckTable($table_prefix."_process_extws")) {
	$schema_obj = new adoSchema($adb->database);
	$schema_obj->ExecuteSchema($schema_obj->ParseSchemaString($schema_table));
}

$schema_table =
'<schema version="0.3">
	<table name="'.$table_prefix.'_process_extws_meta">
		<opt platform="mysql">ENGINE=InnoDB</opt>
		<field name="id" type="I" size="19">
			<KEY/>
		</field>
		<field name="processid" type="I" size="19">
			<KEY/>
		</field>
		<field name="elementid" type="C" size="255" />
		<field name="text" type="C" size="255" />
		<field name="type" type="C" size="50" />
		<field name="extwsid" type="I" size="19" />
		<index name="process_extws_m_pid_idx">
			<col>processid</col>
		</index>
	</table>
</schema>';
if(!Vtiger_Utils::CheckTable($table_prefix."_process_extws_meta")) {
	$schema_obj = new adoSchema($adb->database);
	$schema_obj->ExecuteSchema($schema_obj->ParseSchemaString($schema_table));
}

$adb->addColumnToTable($table_prefix.'_extws', 'results', 'XL');

$trans = array(
	'Settings' => array(
		'it_it' => array(
			'LBL_PM_ACTION_CallExtWS' => 'Chiama Web service esterno',
			'LBL_CHOOSE_EXTWS' => 'Web service',
			'LBL_EXTWS_RESULTS' => 'Campi restituiti',
			'LBL_EXTWS_RESULT_NAME' => 'Nome campo',
			'LBL_EXTWS_RESULT_VALUE' => 'Valore da estrarre',
			'LBL_EXTWS_ADD_RESULT' => 'Aggiungi campo',
			'LBL_EXTWS_RESULTS_DESC' => 'Campi da estrarre dal risultato del Web service. Per ora solo semplici oggetti json e xml sono supportati.',
			'LBL_AUTOMAP_FIELDS' => 'Mappa campi automaticamente',
			'LBL_NO_VALID_JSON' => 'Il risultato non è in formato JSON valido',
			'LBL_NO_VALID_XML' => 'Il risultato non è in formato XML valido',
			'LBL_NO_VALID_DATA_FORMAT' => 'Il risultato è in un formato non supportato',
			'LBL_ADDITIONAL_PARAMETERS' => 'Parametri aggiuntivi',
			'LBL_ADDITIONAL_RESULTS' => 'Campi restituiti aggiuntivi',
			'LBL_EXTWS_RESULTFIELD_OUTCOME' => 'Esito positivo',
			'LBL_EXTWS_RESULTFIELD_CODE' => 'Codice della risposta',
			'LBL_EXTWS_RESULTFIELD_MESSAGE' => 'Messaggio della risposta',
		),
		'en_us' => array(
			'LBL_PM_ACTION_CallExtWS' => 'Call external Web service',
			'LBL_CHOOSE_EXTWS' => 'Web service',
			'LBL_EXTWS_RESULTS' => 'Returned fields',
			'LBL_EXTWS_RESULT_NAME' => 'Field name',
			'LBL_EXTWS_RESULT_VALUE' => 'Value to extract',
			'LBL_EXTWS_ADD_RESULT' => 'Add field',
			'LBL_EXTWS_RESULTS_DESC' => 'Fields to extract from the Web service result. Only simple flat json and xml objects are supported.',
			'LBL_AUTOMAP_FIELDS' => 'Map fields automatically',
			'LBL_NO_VALID_JSON' => 'Result is not in valid JSON format',
			'LBL_NO_VALID_XML' => 'Result is not in valid XML format',
			'LBL_NO_VALID_DATA_FORMAT' => 'Result is in an unknown format',
			'LBL_ADDITIONAL_PARAMETERS' => 'Additional parameters',
			'LBL_ADDITIONAL_RESULTS' => 'Additional returned fields',
			'LBL_EXTWS_RESULTFIELD_OUTCOME' => 'Positive result',
			'LBL_EXTWS_RESULTFIELD_CODE' => 'Response code',
			'LBL_EXTWS_RESULTFIELD_MESSAGE' => 'Response message',
		),
	),
	'ALERT_ARR' => array(
		'it_it' => array(
			'LBL_EXTWS_NO_RETURN_FIELDS' => 'Devi configurare almeno un campo restituito. Puoi aggiungerli manualmente o automaticamte tramite il pulsante Prova Web service',
		),
		'en_us' => array(
			'LBL_EXTWS_NO_RETURN_FIELDS' => 'You have to configure at least one returned field. You can add them manually or use the Test Web service button to do it automatically',
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
