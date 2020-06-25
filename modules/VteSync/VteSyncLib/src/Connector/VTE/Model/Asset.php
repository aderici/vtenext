<?php
/* crmv@182114 */

namespace VteSyncLib\Connector\VTE\Model;

class Asset extends GenericVTERecord {

	protected static $staticModule = 'Assets';
	
	protected static $fieldMap = array(
		// VTE => CommonRecord
		'assetname' => 'name',
		'account' => 'accountid',
		'product' => 'productid',
		'serialnumber' => 'serial_number',
		'dateinservice' => 'install_date',
		'datesold' => 'purchase_date',
		'assetstatus' => 'status',
		'description' => 'description',
	);

	// if needed, you can override methods and change fields/behaviour	
}
