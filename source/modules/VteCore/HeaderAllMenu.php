<?php
/*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************/
/* crmv@124738 */

require_once('include/utils/PageHeader.php');

// by using a class, I can extend it and provide customizations easily
$VPH = VTEPageHeader::getInstance();
$VPH->displayAllMenu();