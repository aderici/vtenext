<?php

namespace VteSyncLib\Connector\VTE\Model;


class Account extends GenericVTERecord {

	protected static $staticModule = 'Accounts';
	
	protected static $fieldMap = array(
		// VTE => CommonRecord
		'accountname' => 'name',
		'phone' => 'phone',
		'otherphone' => 'otherphone',
		'fax' => 'fax',
		'email1' => 'email',
		'email2' => 'otheremail',
		'website' => 'website',
		'industry' => 'industry',
		'annual_revenue' => 'annualrevenue',
		'employees' => 'employees',
		'rating' => 'rating',
		'description' => 'description',
		
		// billing address
		'bill_street' => 'billingstreet',
		'bill_city' => 'billingcity',
		'bill_code' => 'billingpostalcode',
		'bill_state' => 'billingstate',
		'bill_country' => 'billingcountry',
		// shipping address
		'ship_street' => 'shippingstreet',
		'ship_city' => 'shippingcity',
		'ship_code' => 'shippingpostalcode',
		'ship_state' => 'shippingstate',
		'ship_country' => 'shippingcountry',
	);

	// if needed, you can override methods and change fields/behaviour	
}
