<?php
/* crmv@195073*/

namespace VteSyncLib\Connector\VTE\Model;

class Targets_Contacts extends GenericVTERecord {

	protected static $staticModule = 'Targets_Contacts';
	
	protected static $fieldMap = array(
		'targetid' => 'targetid',
		'contactid' => 'contactid',
	);

	// if needed, you can override methods and change fields/behaviour
}