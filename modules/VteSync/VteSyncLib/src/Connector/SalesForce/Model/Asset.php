<?php
/* crmv@182114 */

namespace VteSyncLib\Connector\SalesForce\Model;

class Asset extends GenericSFRecord {

	protected static $staticModule = 'Assets';
	
	protected static $fieldMap = array(
		// SF => CommonRecord
		'Name' => 'name',
		'AccountId' => 'accountid',
		'Product2Id' => 'productid',
		'SerialNumber' => 'serial_number',
		'InstallDate' => 'install_date',
		'PurchaseDate' => 'purchase_date',
		'Status' => 'status',
		'Description' => 'description',
	);

	// if needed, you can override methods and change fields/behaviour
}
