<?php

/* crmv@150748 */

/**
 * Autoload a class from include/utils, with the file name equals to the class name.
 * Some special cases are handled as well
 */
function VTEAutoloadUtils($class) {
	// crmv@151308
	// support some special cases
	// TODO: make order in all classes to avoid special cases
	switch ($class) {
		case 'GDPRWS':
			$file = 'include/utils/GDPRWS/'.$class.'.php';
			break;
		case 'RelationManager':
		case 'ModuleRelation':
		case 'FakeModules':
			$file = 'include/utils/RelationManager/'.$class.'.php';
			break;
		// crmv@164120
		case 'ChangeLog':
			$file = 'modules/ChangeLog/ChangeLog.php';
			break;
		// crmv@164120e
		// crmv@164122
		case 'ModNotifications':
			$file = 'modules/ModNotifications/ModNotifications.php';
			break;
		// crmv@164122e
		default:
			$file = 'include/utils/'.str_replace('.', '', $class).'.php';
			break;
	}
	// crmv@151308e
	
	if (file_exists($file)) {
		require_once($file);
	}
}

spl_autoload_register('VTEAutoloadUtils');
