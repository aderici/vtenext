<?php
/* crmv@161021 */
global $sdk_mode;
switch($sdk_mode) {
	case 'edit':
	case 'detail':
		if (!isset($focusEmployees)) $focusEmployees = CRMEntity::getInstance('Employees');
		if (!empty($col_fields['role']) && in_array($fieldname,array_keys($focusEmployees->synchronizeUserMapping))) {
			$readonly = 99;
			$success = true;
		}
		break;
}