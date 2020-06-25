<?php
/*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************/

/* crmv@192078 */

class InvitationController {

	public function process(&$request) {
		global $application_unique_key;
		global $current_language, $default_language, $app_strings, $mod_strings;
		global $current_user;
		
		$appkey = $request['app_key'];
		$record = intval($_REQUEST['record']);
		$inviteeid = intval($request['userid']);
		$partecipation = intval($request['partecipation']);
		$from = $request['from'];
		
		if ($appkey !== $application_unique_key) {
			exit;
		}
		
		// set the current user
		$user_id = $this->getUserId($request) ?: 1;
		
		$current_user = CRMEntity::getInstance('Users');
		$current_user->retrieveCurrentUserInfoFromFile($user_id);
		$current_language = $current_user->column_fields['default_language'];
		if ($current_language == '') {
			$current_language = $default_language;
		}
		
		// setup language vars
		if (isModuleInstalled('SDK')) {
			$app_strings = return_application_language($current_language);
			$mod_strings = return_module_language($current_language, 'Calendar');
		}
		
		// from already present
		$_REQUEST['activityid'] = $record;
		$_REQUEST['partecipation'] = $partecipation;
		$_REQUEST['userid'] = $inviteeid;
		require('modules/Calendar/SavePartecipation.php');

		require_once("modules/Emails/mail.php");

		$focus_event = CRMEntity::getInstance('Calendar');
		$focus_event->id = $record;
		$focus_event->retrieve_entity_info($focus_event->id,'Events');
		
		$invites = getTranslatedString('INVITATION','Calendar');
		if ($partecipation == 2) {
			$answer = getTranslatedString('LBL_YES','Calendar');
		} elseif ($partecipation == 1) {
			$answer = getTranslatedString('LBL_NO','Calendar');
		}
		$subject = $invites.': '.$focus_event->column_fields['subject'];
		$description = getEmailInvitationDescription($focus_event->column_fields,$inviteeid,$record,$answer,$from);

		$this->displayResult($description);
	}

	public function getUserId(&$request) {
		$from = $request['from'];
		if ($from == 'invite_con') {
			$user_id = Users::getActiveAdminId();
		} else {
			$user_id = $request['userid'];
		}
	}
	
	public function displayResult($body) {
		global $current_language;
		
		$smarty = new VteSmarty();
		$smarty->assign('PATH','../');
		
		$smarty->assign('CURRENT_LANGUAGE',$current_language);
		$smarty->assign('BODY',$body);
		$smarty->display('NoLoginMsg.tpl');
	}
	
}
