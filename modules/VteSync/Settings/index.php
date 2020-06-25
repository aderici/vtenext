<?php
/*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************/
 
/* crmv@176547 */

if ($_REQUEST['ajax'] === '1') {
	require('ajax.php');
} elseif ($_REQUEST['mode'] === 'create') {
	require('Create.php');
} elseif ($_REQUEST['mode'] === 'edit') {
	require('Edit.php');
} elseif ($_REQUEST['mode'] === 'save') {
	require('Save.php');
} else {
	require('List.php');
}
