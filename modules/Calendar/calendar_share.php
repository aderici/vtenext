<?php
/*********************************************************************************
 ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * Portions created by CRMVILLAGE.BIZ are Copyright (C) CRMVILLAGE.BIZ.
 * All Rights Reserved.
 ********************************************************************************/

/* crmv@20209 crmv@36511 crmv@3085m crmv@187823 crmv@181170 */

global $mode;

$record = intval($_REQUEST['record']);

$cal_class = CRMEntity::getInstance('Calendar');
echo $cal_class->getCalendarShareContent($record, $mode);
