<?php

// crmv@143804
$cond = 'isCalendarTrackingEnabled:modules/SDK/src/CalendarTracking/CalendarTrackingUtils.php';
$adb->pquery("UPDATE sdk_menu_fixed SET cond = ? WHERE title = ? AND (cond IS NULL OR cond = '')", array($cond, 'LBL_TRACK_MANAGER'));

//crmv@130458
$adb->pquery("update {$table_prefix}_field set displaytype = ?, readonly = ? where tablename = ? and fieldname in (?,?,?,?)", array(1,99,"{$table_prefix}_servicecontracts",'end_date','planned_duration','actual_duration','progress'));
$adb->pquery("update {$table_prefix}_field set generatedtype = ? where tablename = ? and fieldname = ?", array(2,"{$table_prefix}_servicecontracts",'end_date'));
