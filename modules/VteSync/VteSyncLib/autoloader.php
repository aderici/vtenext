<?php

if (!defined('VTESYNC_BASEDIR')) {
	define('VTESYNC_BASEDIR', dirname(__FILE__));
}

function VteSyncLibAutoload($class) {
	list($ns, $xx) = explode('\\', $class, 2);
	if ($ns === 'VteSyncLib') {
		$file = VTESYNC_BASEDIR.'/src/'.str_replace('\\', '/', $xx).'.php';
		if (file_exists($file)) {
			require_once($file);
		}
	}
}

spl_autoload_register('VteSyncLibAutoload');
