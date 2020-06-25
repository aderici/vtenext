<?php

/* crmv@137476 */

$VP = VTEProperties::getInstance();
$oldVal = $VP->get('performance.list_count', true, true);
if ($oldVal === null || $oldVal === '') {
	$VP->set('performance.list_count', true);
}

// this file has been moved
@unlink('phpversionfail.php'); // crmv@138188
