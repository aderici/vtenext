<?php 

$cols = $adb->getColumnNames($table_prefix . '_modulehome');
if (!in_array('entries', $cols)) {
	$adb->addColumnToTable($table_prefix . '_modulehome', 'entries', 'I(3)');
}