<?php
/* crmv@144893 */

require('config.inc.php');
require_once('include/utils/ResourceVersion.php');

$RV = ResourceVersion::getInstance();

// if you want to force the update of a specific file, use the following statement
//$RV->createResource('path/to/file.js');

$RV->enableCacheWrite();
$RV->updateResources();
