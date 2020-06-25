<?php
/* crmv@182114 */

namespace VteSyncLib\Connector\VTE\Model;

class Campaign extends GenericVTERecord {

	protected static $staticModule = 'Campaigns';
	
	protected static $fieldMap = array(
		// VTE => CommonRecord
		'campaignname' => 'name',
		'campaigntype' => 'type',
		'campaignstatus' => 'status',
		'closingdate' => 'end_date',
		'budgetcost' => 'budgetcost',
		'actualcost' => 'actualcost',
		'expectedrevenue' => 'expectedrevenue',
		'numsent' => 'numsent',
		'actualresponsecount' => 'actualresponsecount',
		'description' => 'description',
	);

	// if needed, you can override methods and change fields/behaviour	
}
