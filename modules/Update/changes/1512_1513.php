<?php
global $adb, $table_prefix;

$moduleInstancePL = Vtecrm_Module::getInstance('ProductLines');
$adb->pquery("update {$table_prefix}_field set masseditable = ? where tabid = ? and masseditable = ? and fieldname in (?,?,?)", array(1,$moduleInstancePL->id,0,'assigned_user_id','description','yearly_budget'));