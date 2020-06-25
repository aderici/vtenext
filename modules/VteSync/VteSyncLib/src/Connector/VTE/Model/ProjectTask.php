<?php
/* crmv@190016 */

namespace VteSyncLib\Connector\VTE\Model;

class ProjectTask extends GenericVTERecord {

	protected static $staticModule = 'ProjectTask';
	
	protected static $fieldMap = array(
		// VTE => CommonRecord
		'projecttaskname' => 'subject',
		'projecttaskpriority' => 'priority',
		'Project' => 'projectid',
		'projectid' => 'projectid',
		'startdate' => 'start_date',
		'enddate' => 'end_date',
		'description' => 'description',
		'assigned_user_id'=> 'assignee',
	);
	
	// if needed, you can override methods and change fields/behaviour	
}
