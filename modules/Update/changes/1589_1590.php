<?php

global $adb, $table_prefix;

// crmv@139057

if(!Vtiger_Utils::CheckTable($table_prefix.'_report_scheduled')) {
	$schema = '<?xml version="1.0"?>
				<schema version="0.3">
				  <table name="'.$table_prefix.'_report_scheduled">
				  <opt platform="mysql">ENGINE=InnoDB</opt>
				    <field name="reportid" type="I" size="19">
				   	  <key/>
				    </field>
				    <field name="status" type="C" size="31">
						<NOTNULL/>
				    </field>
					<field name="last_execution" type="T">
						<default value="0000-00-00 00:00:00"/>
					</field>
					<field name="next_execution" type="T">
						<default value="0000-00-00 00:00:00"/>
					</field>
					<index name="rep_sched_status_idx">
				      <col>status</col>
				    </index>
				    <index name="rep_sched_next_ex_idx">
				      <col>next_execution</col>
				    </index>
				  </table>
				</schema>';
	$schema_obj = new adoSchema($adb->database);
	$schema_obj->ExecuteSchema($schema_obj->ParseSchemaString($schema));
}

$adb->addColumnToTable($table_prefix.'_reportconfig', 'scheduling', 'XL');


$result = $adb->pquery("select cronid from {$table_prefix}_cronjobs where cronname = ?", array('ScheduledReports'));
if ($adb->num_rows($result) == 0) {
	require_once('include/utils/CronUtils.php');
	$CU = CronUtils::getInstance();
	
	$cj = new CronJob();
	$cj->name = 'ScheduledReports';
	$cj->active = 1;
	$cj->singleRun = false;
	$cj->fileName = 'cron/modules/Reports/ScheduledReports.service.php';
	$cj->timeout = 7200;	// 2h timeout
	$cj->repeat = 60;		// run every min
	$CU->insertCronJob($cj);
}


$trans = array(
	'Reports' => array(
		'it_it' => array(
			'LBL_SCHEDULE_EMAIL' => 'Esecuzione programmata',
			'LBL_SCHEDULE_EMAIL_DESCRIPTION' => 'Programma l\'esecuzione del report e l\'invio automatico ai destinatari scelti',
			'LBL_USERS_AVAILABLE' => 'Destinatari',
			'LBL_USERS_SELECTED' => 'Destinatari selezionati',
			'LBL_REPORT_FORMAT_PDF' => 'PDF',
			'LBL_REPORT_FORMAT_EXCEL' => 'Excel',
			'LBL_REPORT_FORMAT_BOTH' => 'Entrambi',
			'LBL_REPORT_FORMAT' => 'Formato report',
			'LBL_HOURLY' => 'Ogni ora',
			'LBL_DAILY' => 'Ogni giorno',
			'LBL_WEEKLY' => 'Ogni settimana',
			'LBL_BIWEEKLY' => 'Ogni 2 settimane',
			'LBL_MONTHLY' => 'Mensilmente',
			'LBL_YEARLY' => 'Annualmente',
			'LBL_SCHEDULE_REPORT' => 'Pianifica report',
			'LBL_SCHEDULE_FREQUENCY' => 'Frequenza',
			'LBL_SCHEDULE_EMAIL_DOW' => 'Giorno della settimana',
			'LBL_SCHEDULE_EMAIL_DAY' => 'Giorno',
			'LBL_SCHEDULE_EMAIL_MONTH' => 'Mese',
			'LBL_TIME_FORMAT_MSG' => 'hh:mm',
			'LBL_NOTE' => 'Nota',
			'LBL_SCHEDULED_AS_ADMIN' => 'Il report verrà eseguito come utente amministratore, senza quindi applicare restrizioni di visibilità ai record.',
			'LBL_ONLY_ADMIN_CAN_SCHEDULE' => 'Solo utenti amministratori possono programmare il report.',
			'LBL_AUTO_GENERATED_REPORT_EMAIL' => 'Questa è una email automatica inviata da un report programmato.',
		),
		'en_us' => array(
			'LBL_SCHEDULE_EMAIL' => 'Scheduled execution',
			'LBL_SCHEDULE_EMAIL_DESCRIPTION' => 'Schedule the automatic execution and send the result to the chosen recipients',
			'LBL_USERS_AVAILABLE' => 'Recipients',
			'LBL_USERS_SELECTED' => 'Selected recipients',
			'LBL_REPORT_FORMAT_PDF' => 'PDF',
			'LBL_REPORT_FORMAT_EXCEL' => 'Excel',
			'LBL_REPORT_FORMAT_BOTH' => 'Both',
			'LBL_REPORT_FORMAT' => 'Report format',
			'LBL_HOURLY' => 'Hourly',
			'LBL_DAILY' => 'Daily',
			'LBL_WEEKLY' => 'Weekly',
			'LBL_BIWEEKLY' => 'Biweekly',
			'LBL_MONTHLY' => 'Monthly',
			'LBL_YEARLY' => 'Yearly',
			'LBL_SCHEDULE_REPORT' => 'Schedule report',
			'LBL_SCHEDULE_FREQUENCY' => 'Frequency',
			'LBL_SCHEDULE_EMAIL_DOW' => 'Day of the week',
			'LBL_SCHEDULE_EMAIL_DAY' => 'Day',
			'LBL_SCHEDULE_EMAIL_MONTH' => 'Month',
			'LBL_TIME_FORMAT_MSG' => 'hh:mm (24 hour format)',
			'LBL_NOTE' => 'Note',
			'LBL_SCHEDULED_AS_ADMIN' => 'The report will be executed as admin user, without applying visibility restrictions.',
			'LBL_ONLY_ADMIN_CAN_SCHEDULE' => 'Only administrator users can schedule the report.',
			'LBL_AUTO_GENERATED_REPORT_EMAIL' => 'This is an auto-generated email sent on behalf of a scheduled report.',
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
