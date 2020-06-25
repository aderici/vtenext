<?php
global $adb, $table_prefix;
$adb->addColumnToTable($table_prefix.'_messages_inline_cache', 'parameters', 'X');