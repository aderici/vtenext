<?php
global $adb, $table_prefix;

//crmv@126830
$result = $adb->pquery("select * from {$table_prefix}_ticketstatus where ticketstatus = ? or ticketstatus = ?", array('Answered by customer','Risposto dal cliente'));
if ($result && $adb->num_rows($result) == 0) {
	$field = Vtecrm_Field::getInstance('ticketstatus', Vtecrm_Module::getInstance('HelpDesk'));
	$field->setPicklistValues(array('Answered by customer'));
	$adb->pquery("update {$table_prefix}_ticketstatus set PRESENCE = ? where ticketstatus = ?", array(0,'Answered by customer'));
	SDK::setLanguageEntries('HelpDesk', 'Answered by customer', array('it_it'=>'Risposto dal cliente','en_us'=>'Answered by customer'));
}
//crmv@126830e

//crmv@143630
require_once('include/utils/EmailDirectory.php');
$emailDirectory = new EmailDirectory();
$adb->pquery("delete from {$table_prefix}_email_directory where module not in (".generateQuestionMarks($emailDirectory->getModules()).")", $emailDirectory->getModules());
//crmv@143630e