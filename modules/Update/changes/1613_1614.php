<?php
global $adb, $table_prefix;


// update config inc to support php7
$configInc = file_get_contents('config.inc.php');
if (empty($configInc)) {
	Update::info("Unable to get config.inc.php contents, please modify it manually.");
} else {
	// backup it (only if it doesn't exist)
	$newConfigInc = 'config.inc.1613.php';
	if (!file_exists($newConfigInc)) {
		file_put_contents($newConfigInc, $configInc);
	}
	// change value
	$addPiece = 
"// switch to the new mysql driver if available
if (\$dbconfig['db_type'] == 'mysql' && !function_exists('mysql_connect') && function_exists('mysqli_connect')) {
	\$dbconfig['db_type'] = 'mysqli';
}";
	$configInc = preg_replace('/^(\$dbconfig\[\'db_hostname\'\].*)$/m', "\\1\n\n$addPiece", $configInc);
	if (is_writable('config.inc.php')) {
		file_put_contents('config.inc.php', $configInc);
	} else {
		Update::info("Unable to update config.inc.php, please modify it manually.");
	}
}
