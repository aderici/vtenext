<?php
/***************************************************************************************
 * The contents of this file are subject to the CRMVILLAGE.BIZ VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is:  CRMVILLAGE.BIZ VTECRM
 * The Initial Developer of the Original Code is CRMVILLAGE.BIZ.
 * Portions created by CRMVILLAGE.BIZ are Copyright (C) CRMVILLAGE.BIZ.
 * All Rights Reserved.
 ***************************************************************************************/
/* crmv@171021 crmv@192843 */

if ($_REQUEST['file'] == 'Accounts') {
	global $currentModule, $app_strings, $mod_strings;
	$focus = CRMEntity::getInstance($currentModule);
	$smarty = new VteSmarty();
	$smarty->assign('MOD', $mod_strings);
	$smarty->assign('APP', $app_strings);
	$smarty->assign('FOCUS', $focus);
}

$fast_links = array();
$accounts = $focus->getUserAccounts();
$layout = $focus->getLayoutSettings();
if (!empty($accounts) && count($accounts) > 1) {
	$folders = $focus->getAllSpecialFolders('INBOX');
	if (!empty($folders)) {
		$query = "SELECT account, count(*) AS count FROM {$focus->table_name} WHERE deleted = 0 AND smownerid = ? AND seen = ? and mtype = ?";
		$params = array($current_user->id,0,'Webmail');
		$tmp = array();
		foreach($folders as $account => $folder) {
			$tmp[] = "(account = ? AND folder = ?)";
			$params[] = array($account,$folder['INBOX']);
		}
		$query .= ' AND ('.implode(' OR ',$tmp).')';
		$query .= " GROUP BY account";
		$folder_counts = array('all'=>0);
		$result = $adb->pquery($query,$params);
		if ($result && $adb->num_rows($result) > 0) {
			while($row=$adb->fetchByAssoc($result)) {
				$folder_counts[$row['account']] = $row['count'];
				$folder_counts['all'] = ($folder_counts['all']+$row['count']);
			}
		}
		$fast_links[] = array(
			'account'=>'all',
			'id'=>'INBOX',
			'description'=>getTranslatedString('LBL_Folder_INBOX','Messages'),
			'vteicon'=>$focus->folderImgs['INBOX'],
			'count'=>$folder_counts['all'],
			'bg_notification_color'=>'#2c80c8',
		);
	}
	if (empty($layout['merge_account_folders'])) {
		$account_description = array();
		foreach($accounts as $account) {
			$account_description[$account['id']] = $account['description'];
		}
		foreach($folders as $accoount => $folder) {
			$fast_links[] = array(
				'account'=>$accoount,
				'id'=>$folder['INBOX'],
				'description'=>$account_description[$accoount],
				'vteicon'=>$focus->folderImgs['INBOX'],
				'count'=>$folder_counts[$accoount],
				'bg_notification_color'=>'#2c80c8',
			);
		}
	}
	if (!empty($layout['merge_account_folders'])) {
		$fast_links[] = array(
			'account'=>'all',
			'id'=>'Shared',
			'description'=>getTranslatedString('LBL_Folder_Shared','Messages'),
			'vteicon'=>$focus->folderImgs['Shared'],
		);
		$fast_links[] = array(
			'account'=>'all',
			'id'=>'Links',
			'description'=>getTranslatedString('LBL_Folder_Links','Messages'),
			'vteicon'=>$focus->folderImgs['Links'],
		);
		$fast_links[] = array(
			'account'=>'all',
			'id'=>'Flagged',
			'description'=>getTranslatedString('LBL_Folder_Flagged','Messages'),
			'vteicon'=>$focus->folderImgs['Flagged'],
		);
		$folders = $focus->getAllSpecialFolders('Sent');
		if (!empty($folders)) {
			$query = "SELECT count(*) AS count FROM {$focus->table_name} WHERE deleted = 0 AND smownerid = ? AND seen = ? and mtype = ?";
			$params = array($current_user->id,0,'Webmail');
			$tmp = array();
			foreach($folders as $account => $folder) {
				$tmp[] = "(account = ? AND folder = ?)";
				$params[] = array($account,$folder['Sent']);
			}
			$query .= ' AND ('.implode(' OR ',$tmp).')';
			$result = $adb->pquery($query,$params);
			($result && $adb->num_rows($result) > 0) ? $folder_count = $adb->query_result($result,0,'count') : $folder_count = 0;
			$fast_links[] = array(
				'account'=>'all',
				'id'=>'Sent',
				'description'=>getTranslatedString('LBL_Folder_Sent','Messages'),
				'vteicon'=>$focus->folderImgs['Sent'],
				'count'=>$folder_count,
				'bg_notification_color'=>'#2c80c8',
			);
		}
		$fast_links[] = array(
			'account'=>'all',
			'id'=>'vteScheduled',
			'description'=>getTranslatedString('LBL_Folder_vteScheduled','Messages'),
			'vteicon'=>$focus->folderImgs['vteScheduled'],
		);
		$folders = $focus->getAllSpecialFolders('Spam');
		if (!empty($folders)) {
			$query = "SELECT count(*) AS count FROM {$focus->table_name} WHERE deleted = 0 AND smownerid = ? AND seen = ? and mtype = ?";
			$params = array($current_user->id,0,'Webmail');
			$tmp = array();
			foreach($folders as $account => $folder) {
				$tmp[] = "(account = ? AND folder = ?)";
				$params[] = array($account,$folder['Spam']);
			}
			$query .= ' AND ('.implode(' OR ',$tmp).')';
			$result = $adb->pquery($query,$params);
			($result && $adb->num_rows($result) > 0) ? $folder_count = $adb->query_result($result,0,'count') : $folder_count = 0;
			$fast_links[] = array(
				'account'=>'all',
				'id'=>'Spam',
				'description'=>getTranslatedString('LBL_Folder_Spam','Messages'),
				'vteicon'=>$focus->folderImgs['Spam'],
				'count'=>$folder_count,
				'bg_notification_color'=>'#2c80c8',
			);
		}
	}
}
$smarty->assign('MERGE_ACCOUNT_FOLDERS', $layout['merge_account_folders']);
$smarty->assign('FAST_LINKS', $fast_links);
$smarty->assign('ACCOUNTS', $accounts);
$smarty->assign('DIV_DIMENSION', array('Folders'=>'0%','ListViewContents'=>'24%','DetailViewContents'=>'61%','TurboliftContents'=>'15%'));
$smarty->assign('VIEW', 'list');

if ($_REQUEST['file'] == 'Accounts') {
	$smarty->display("modules/Messages/Accounts.tpl");
}