<?php
/* crmv@182114 */

namespace VteSyncLib\Connector\SalesForce\Model;

class HelpDesk extends GenericSFRecord {

	protected static $staticModule = 'HelpDesk';
	
	protected static $fieldMap = array(
		// SF => CommonRecord
		'Subject' => 'subject',
		'ContactId' => 'contactid',
		'AccountId' => 'accountid',
		'Status' => 'status',
		'Type' => 'category',
		'Priority' => 'priority',
		'Description' => 'description',
	);

	// if needed, you can override methods and change fields/behaviour
}
