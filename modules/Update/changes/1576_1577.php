<?php

// crmv@140887

$VP = VTEProperties::getInstance();
$oldVal = $VP->get('performance.version_resources_cdn', true, true);
if ($oldVal === null || $oldVal === '') {
	$VP->set('performance.version_resources_cdn', '');
}