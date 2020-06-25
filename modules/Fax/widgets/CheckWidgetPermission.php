<?php
/* crmv@180638 */

function checkFaxWidgetPermission($row) {
	return vtlib_isModuleActive('Fax');
}
