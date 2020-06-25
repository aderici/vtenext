<?php
global $adb, $table_prefix;

$result = $adb->pquery("select fieldid from {$table_prefix}_field where tabid = ? and fieldname = ?", array(getTabid('Processes'),'related_to'));
if ($result && $adb->num_rows($result) > 0) {
	$fieldid = $adb->query_result($result,0,'fieldid');
	$adb->pquery("update {$table_prefix}_fieldmodulerel set relmodule = ? where fieldid = ? and module = ? and relmodule = ?", array('Visitreport',$fieldid,'Processes','VisitReport'));
	
	$focus = CRMEntity::getInstance('Processes');
	$focus->enable('Visitreport');
}