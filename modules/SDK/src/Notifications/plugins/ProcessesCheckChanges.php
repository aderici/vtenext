<?php
/***************************************************************************************
 * The contents of this file are subject to the CRMVILLAGE.BIZ VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is:  CRMVILLAGE.BIZ VTECRM
 * The Initial Developer of the Original Code is CRMVILLAGE.BIZ.
 * Portions created by CRMVILLAGE.BIZ are Copyright (C) CRMVILLAGE.BIZ.
 * All Rights Reserved.
 ***************************************************************************************/
/* crmv@183872 */

global $current_user;
$queryGenerator = QueryGenerator::getInstance('Processes',$current_user);
$customView = CRMEntity::getInstance('CustomView','Processes');
$viewid = $customView->getViewIdByName('Pending','Processes',$current_user->id);
if (!empty($viewid)) {
	$queryGenerator->initForCustomViewById($viewid);
	if (!empty($queryGenerator->getModuleFields())) {
		$result = $adb->querySlave('BadgeCount',replaceSelectQuery($queryGenerator->getQuery(),'count(*) as cnt')); // crmv@185894
		if ($result) {
			echo $noofrows = $adb->query_result($result,0,'cnt');
			return; // exit the file
		}
	}
}
echo 0;
return; // exit the file