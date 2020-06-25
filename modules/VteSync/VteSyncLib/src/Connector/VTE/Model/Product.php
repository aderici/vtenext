<?php
/* crmv@182114 */

namespace VteSyncLib\Connector\VTE\Model;

class Product extends GenericVTERecord {

	protected static $staticModule = 'Products';
	
	protected static $fieldMap = array(
		// VTE => CommonRecord
		'productname' => 'name',
		'productcode' => 'code',
		'discontinued' => 'is_active',
		'productcategory' => 'category',
		'description' => 'description',
	);

	public static function fromRawData($data) {
		$data['discontinued'] = ($data['discontinued'] == '1');
		return parent::fromRawData($data);
	}
}
