<?php

/* crmv@150024 */

$targetid = intval($_REQUEST['targetid']);
$action = $_REQUEST['subaction'];

if ($action = 'delfilter') {
	
	$type = $_REQUEST['type'];
	$objectid = intval($_REQUEST['objectid']);
	$formod = $_REQUEST['formodule'];
	
	$focus = CRMEntity::getInstance('Targets');
	$focus->id = $targetid;
	
	if ($type == 'CustomView') {
		$focus->unsetDynamicCV($targetid, $objectid, $formod);
	} elseif ($type == 'Report') {
		$focus->unsetDynamicReport($targetid, $objectid, $formod);
	}
	
	// ok, now reload the div!
	$html = $focus->getExtraDetailBlock();
	echo $html;
}
