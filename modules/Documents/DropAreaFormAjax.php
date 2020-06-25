<?php
/*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************/

// crmv@176893

require_once('modules/Documents/DropArea.php');

global $current_user;

$mode = 'ajax';
$action = $_REQUEST['subaction'];
$json = null;

$DA = DropArea::getInstance();

try {
	if ($action == 'get_folders') {
		$folders = $DA->getFolders();
		$json = array('success' => true, 'data' => $folders);
	} elseif ($action == 'add_folder') {
		$folderName = vtlib_purify($_REQUEST['new_folder_name']);
		$folderDesc = vtlib_purify($_REQUEST['new_folder_desc']);
		
		$folderId = $DA->addNewFolder($folderName, $folderDesc);
		$json = array('success' => ($folderId !== 0), 'folderid' => $folderId, 'foldername' => ($folderId !== 0 ? $folderName : null));
	} else {
		$json = array('success' => false, 'error' => 'Unknown action');
	}
} catch (Exception $e) {
	$json = array('success' => false, 'error' => $e->getMessage());
}

echo Zend_Json::encode($json);
exit();
