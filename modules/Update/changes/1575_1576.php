<?php

// crmv@146670

$schema_table =
'<schema version="0.3">
	<table name="'.$table_prefix.'_extws">
		<opt platform="mysql">ENGINE=InnoDB</opt>
		<field name="extwsid" type="I" size="19">
			<KEY/>
		</field>
		<field name="wsname" type="C" size="63">
			<NOTNULL/>
		</field>
		<field name="wstype" type="C" size="31" />
		<field name="wsdesc" type="C" size="255" />
		<field name="method" type="C" size="31" />
		<field name="wsurl" type="C" size="1023" />
		<field name="active" type="I" size="1">
			<NOTNULL/>
			<DEFAULT value="1"/>
		</field>
		<field name="createdtime" type="T">
			<NOTNULL/>
			<DEFAULT value="0000-00-00 00:00:00"/>
		</field>
		<field name="modifiedtime" type="T">
			<NOTNULL/>
			<DEFAULT value="0000-00-00 00:00:00"/>
		</field>
		<field name="authinfo" type="XL" />
		<field name="headers" type="XL" />
		<field name="params" type="XL" />
		<field name="options" type="XL" />
	</table>
</schema>';
if(!Vtiger_Utils::CheckTable($table_prefix."_extws")) {
	$schema_obj = new adoSchema($adb->database);
	$schema_obj->ExecuteSchema($schema_obj->ParseSchemaString($schema_table));
}

require_once('vtlib/Vtecrm/SettingsBlock.php');
require_once('vtlib/Vtecrm/SettingsField.php');
$block = Vtecrm_SettingsBlock::getInstance('LBL_STUDIO');
$res = $adb->pquery("select fieldid from {$table_prefix}_settings_field where name = ?", array('LBL_EXTWS_CONFIG'));
if ($block && $res && $adb->num_rows($res) == 0) {
	$field = new Vtecrm_SettingsField();
	$field->name = 'LBL_EXTWS_CONFIG';
	$field->iconpath = 'extws_config.png';
	$field->description = 'LBL_EXTWS_CONFIG_DESC';
	$field->linkto = 'index.php?module=Settings&action=ExtWSConfig&parenttab=Settings';
	$block->addField($field);
}

$trans = array(
	'Settings' => array(
		'it_it' => array(
			'LBL_EXTWS_CONFIG' => 'Web service esterni',
			'LBL_EXTWS_CONFIG_DESC' => 'Configura Web service esterni per essere usati nei processi',
			'LBL_NO_CUSTOM_EXTWS' => 'Nessun web service configurato, premi Aggiungi per crearne uno nuovo',
			'LBL_EXTWS_NAME' => 'Nome',
			'LBL_EXTWS_NAME_DESC' => 'Un nome per questo web service',
			'LBL_EXTWS_ACTIVE' => 'Attivo',
			'LBL_EXTWS_ACTIVE_DESC' => 'Se impostato, il web service potrà essere usato nei processi',
			'LBL_EXTWS_TYPE_DESC' => 'Il tipo di webservice da chiamare',
			'LBL_REQUEST_METHOD' => 'Metodo',
			'LBL_REQUEST_METHOD_DESC' => 'Il metoto HTTP da usare per la richiesta',
			'LBL_EXTWS_ENDPOINT' => 'Indirizzo',
			'LBL_EXTWS_ENDPOINT_DESC' => 'L\'indirizzo da chiamare, come https://mywebsite.example.com/api/',
			'LBL_AUTHENTICATION' => 'Autenticazione',
			'LBL_SET_AUTHENTICATION' => 'Imposta autenticazione',
			'LBL_HEADERS' => 'Headers',
			'LBL_HEADER_NAME' => 'Nome header',
			'LBL_HEADER_VALUE' => 'Valore header',
			'LBL_ADD_HEADER' => 'Aggiungi header',
			'LBL_PARAMETERS' => 'Parametri',
			'LBL_PARAMETER_NAME' => 'Nome parametro',
			'LBL_PARAMETER_VALUE' => 'Valore parametro',
			'LBL_ADD_PARAMETER' => 'Aggiungi parametro',
			'LBL_TEST_REQUEST' => 'Prova Web service',
			'LBL_EXTWS_TEST_RESULT' => 'Risultato della chiamata',
			'LBL_RESPONSE' => 'Risposta',
			'LBL_RESULT' => 'Risultato',
			'LBL_RETURN_CODE' => 'Codice di ritorno',
			'LBL_EXTWS_VIEW_RESPONSE_AS' => 'Formatta la risposta come',
		),
		'en_us' => array(
			'LBL_EXTWS_CONFIG' => 'External Web services',
			'LBL_EXTWS_CONFIG_DESC' => 'Configure external Web services to be used with processes',
			'LBL_NO_CUSTOM_EXTWS' => 'No external web services configured, press Add to create a new one',
			'LBL_EXTWS_NAME' => 'Name',
			'LBL_EXTWS_NAME_DESC' => 'A name for this web service',
			'LBL_EXTWS_ACTIVE' => 'Active',
			'LBL_EXTWS_ACTIVE_DESC' => 'If flagged, the web service can be used in processes',
			'LBL_EXTWS_TYPE_DESC' => 'The type of web service to call',
			'LBL_REQUEST_METHOD' => 'Method',
			'LBL_REQUEST_METHOD_DESC' => 'The HTTP method to use for the request',
			'LBL_EXTWS_ENDPOINT' => 'Endpoint',
			'LBL_EXTWS_ENDPOINT_DESC' => 'The endpoint for the request, for example https://mywebsite.example.com/api/',
			'LBL_AUTHENTICATION' => 'Authentication',
			'LBL_SET_AUTHENTICATION' => 'Set authentication',
			'LBL_HEADERS' => 'Headers',
			'LBL_HEADER_NAME' => 'Header name',
			'LBL_HEADER_VALUE' => 'Header value',
			'LBL_ADD_HEADER' => 'Add header',
			'LBL_PARAMETERS' => 'Parameters',
			'LBL_PARAMETER_NAME' => 'Parameter name',
			'LBL_PARAMETER_VALUE' => 'Parameter value',
			'LBL_ADD_PARAMETER' => 'Add parameter',
			'LBL_TEST_REQUEST' => 'Test Web service',
			'LBL_EXTWS_TEST_RESULT' => 'Web service test result',
			'LBL_RESPONSE' => 'Response',
			'LBL_RESULT' => 'Result',
			'LBL_RETURN_CODE' => 'Return Code',
			'LBL_EXTWS_VIEW_RESPONSE_AS' => 'Format the response as',
		),
	),
	'ALERT_ARR' => array(
		'it_it' => array(
			'SUCCESS' => 'Success',

		),
		'en_us' => array(
			'SUCCESS' => 'Success',
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
