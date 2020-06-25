<?php
/* crmv@182114 */

namespace VteSyncLib\Connector\VTE\Model;

class Target extends GenericVTERecord {

	protected static $staticModule = 'Targets';
	
	protected static $fieldMap = array(
		// VTE => CommonRecord
		'targetname' => 'name',	
	);

	// if needed, you can override methods and change fields/behaviour	
}