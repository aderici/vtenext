<?php
/* crmv@182114 */

namespace VteSyncLib\Connector\VTE\Model;

class HelpDesk extends GenericVTERecord {

	protected static $staticModule = 'HelpDesk';
	
	protected static $fieldMap = array(
		// VTE => CommonRecord
		'ticket_title' => 'subject',
		'ticketstatus' => 'status',
		'ticketcategories' => 'type',
		'ticketpriorities' => 'priority',
		'projectplanid' => 'projectid', // crmv@190016
		'projecttaskid' => 'projecttaskid', // crmv@190016
		'description' => 'description',
	);
	
	public static function fromRawData($data) {
		if (!empty($data['parent_id'])) {
			global $adb;
			$parent_id = vtws_getIdComponents($data['parent_id']);
			$referenceObject = \VtigerWebserviceObject::fromId($adb, $parent_id[0]);
			if ($referenceObject->getEntityName() == 'Contacts') {
				self::$fieldMap['parent_id'] = 'contactid';
			} elseif ($referenceObject->getEntityName() == 'Accounts') {
				self::$fieldMap['parent_id'] = 'accountid';
			} elseif ($referenceObject->getEntityName() == 'Leads') {
				self::$fieldMap['parent_id'] = 'leadid';
			}
		}
		return parent::fromRawData($data);
	}
	
	public function toCommonRecord() {
		if (!empty($this->fields['parent_id'])) {
			global $adb;
			$parent_id = vtws_getIdComponents($this->fields['parent_id']);
			$referenceObject = \VtigerWebserviceObject::fromId($adb, $parent_id[0]);
			if ($referenceObject->getEntityName() == 'Contacts') {
				self::$fieldMap['parent_id'] = 'contactid';
				self::$fieldMap['accountid'] = 'accountid';
				
				$focus = \CRMEntity::getInstance($referenceObject->getEntityName());
				$accountsObject = \VtigerWebserviceObject::fromName($adb, 'Accounts');
				$this->fields['accountid'] = vtws_getId($accountsObject->getEntityId(), getSingleFieldValue($focus->table_name, 'accountid', $focus->table_index, $parent_id[1]));
			}
		}
		return parent::toCommonRecord();
	}
}
