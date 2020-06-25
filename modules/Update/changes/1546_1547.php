<?php

/* crmv@128369 */
global $adb, $table_prefix;

// add the column
$adb->addColumnToTable($table_prefix.'_reportconfig', 'clusters', 'XL');

$trans = array(
	'Charts' => array(
		'it_it' => array(
			'LBL_VIEW_REPORT' => 'Mostra report',
		),
		'en_us' => array(
			'LBL_VIEW_REPORT' => 'View report',
		),
	),
	'Reports' => array(
		'it_it' => array(
			'LBL_CLUSTERS' => 'Segmentazione top-down',
			'LBL_CLUSTERS_LIST' => 'Segmenti attivi',
			'LBL_SELECT_CLUSTERS' => 'Seleziona i segmenti in cui suddividere il report',
			'LBL_ADD_CLUSTER' => 'Aggiungi un segmento',
			'LBL_EDIT_CLUSTER' => 'Modifica segmento',
			'LBL_CLUSTER_NAME' => 'Nome segmento',
			'LBL_CLUSTER_FILTER_DESC' => 'Seleziona i filtri per l\'estrazione del segmento',
			'LBL_CLUSTER_COLOR' => 'Colore grafico',
		),
		'en_us' => array(
			'LBL_CLUSTERS' => 'Top-down clusters',
			'LBL_CLUSTERS_LIST' => 'Active clusters',
			'LBL_SELECT_CLUSTERS' => 'Select the clusters in which split the report',
			'LBL_ADD_CLUSTER' => 'Add a cluster',
			'LBL_EDIT_CLUSTER' => 'Edit cluster',
			'LBL_CLUSTER_NAME' => 'Cluster name',
			'LBL_CLUSTER_FILTER_DESC' => 'Select filters for cluster extraction',
			'LBL_CLUSTER_COLOR' => 'Chart color',
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
