<?php
global $adb, $table_prefix;

$cols = $adb->getColumnNames($table_prefix.'_process_gateway_conn');
if (!in_array('primary_processesid', $cols)) $adb->addColumnToTable($table_prefix.'_process_gateway_conn', 'primary_processesid', 'I(19)');