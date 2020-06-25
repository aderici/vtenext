<?php
global $adb, $table_prefix;

// crmv@114646

$adb->pquery("UPDATE {$table_prefix}_cronjobs SET repeat_sec = 60 WHERE cronname = ? AND repeat_sec = 300", array('SendReminder'));
$adb->query("UPDATE {$table_prefix}_activity_reminder SET reminder_time = 5 WHERE reminder_time < 5 AND reminder_sent = 0");