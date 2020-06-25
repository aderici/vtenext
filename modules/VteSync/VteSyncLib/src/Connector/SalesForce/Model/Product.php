<?php
/* crmv@182114 */

namespace VteSyncLib\Connector\SalesForce\Model;

class Product extends GenericSFRecord {

	protected static $staticModule = 'Products';
	
	protected static $fieldMap = array(
		// SF => CommonRecord
		'Name' => 'name',
		'ProductCode' => 'code',
		'IsActive' => 'is_active',
		'Family' => 'category',
		'Description' => 'description',
	);

	// if needed, you can override methods and change fields/behaviour
}
