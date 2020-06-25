<?php
/*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************/
/* crmv@92272 crmv@96450 crmv@102879 crmv@115268 crmv@106857 crmv@123745 crmv@155613 */

require_once('modules/com_vtiger_workflow/VTEntityCache.inc');
require_once('modules/com_vtiger_workflow/VTWorkflowUtils.php');
require_once('modules/com_vtiger_workflow/VTSimpleTemplate.inc');
require_once('include/Webservices/DescribeObject.php');
require_once('modules/Settings/ProcessMaker/ProcessDynaForm.php');
require_once(dirname(__FILE__).'/Base.php');

class PMActionEmail extends PMActionBase {
	
	function edit(&$smarty,$id,$elementid,$retrieve,$action_type,$action_id='') {
		global $default_charset; //crmv@178425
		$PMUtils = ProcessMakerUtils::getInstance();
		$involvedRecords = $PMUtils->getRecordsInvolved($id,true);
		if (!empty($involvedRecords)) {
			$smarty->assign('INVOLVED_RECORDS', addslashes(Zend_Json::encode($involvedRecords)));
		}
		//crmv@140599
		$records_pick = $PMUtils->getRecordsInvolvedOptions($id);	// TODO gestire anche i relazionati
		$smarty->assign("RECORDS_INVOLVED", $records_pick);
		//crmv@140599e
		if ($action_id != '') {
			$vte_metadata = Zend_Json::decode($retrieve['vte_metadata']);
			$vte_metadata_arr = array();
			if (!empty($vte_metadata[$elementid])) {
				$metadata_action = $vte_metadata[$elementid]['actions'][$action_id];
			}
			// crmv@178425 crmv@200330
			$metadata_action['sender'] = htmlentities($metadata_action['sender'], ENT_QUOTES, $default_charset);
			$metadata_action['recepient'] = htmlentities($metadata_action['recepient'], ENT_QUOTES, $default_charset);
			$metadata_action['emailcc'] = htmlentities($metadata_action['emailcc'], ENT_QUOTES, $default_charset);
			$metadata_action['emailbcc'] = htmlentities($metadata_action['emailbcc'], ENT_QUOTES, $default_charset);
			$metadata_action['emailreplyto'] = htmlentities($metadata_action['emailreplyto'], ENT_QUOTES, $default_charset);
			// crmv@178425e crmv@200330e
			$smarty->assign('METADATA', $metadata_action);
		}
		
		// crmv@188842
		if ($PMUtils->todoFunctions) {
			$involved_record_pick = $PMUtils->getRecordsInvolvedOptions($id, $metadata_action['parent_id'], false, null, null, true);
			unset($involved_record_pick['']);
			$involved_record_pick = array_merge(
				array(
					'none' => array(getTranslatedString('LBL_NONE'),($metadata_action['parent_id']=='none')?'selected':''),
					'' => array(getTranslatedString('LBL_PM_ACTION_EMAIL_AUTOMATIC_MODE','Settings'),(empty($metadata_action['parent_id']))?'selected':''),
				),
				$involved_record_pick
			);
			$smarty->assign("INVOLVED_RECORD_PICK", $involved_record_pick);
		}
		// crmv@188842e
		
		require_once('modules/com_vtiger_workflow/VTTaskManager.inc');
		require_once('modules/com_vtiger_workflow/tasks/VTEmailTask.inc');
		$task = new VTEmailTask();
		$metaVariables = $task->getMetaVariables();
		$smarty->assign("META_VARIABLES",$metaVariables);
		
		//crmv@106857
		$otherOptions = array();
		$processDynaFormObj = ProcessDynaForm::getInstance();
		$otherOptions = $processDynaFormObj->getFieldsOptions($id,true);
		$PMUtils->getAllTableFieldsOptions($id, $otherOptions);
		$PMUtils->getAllPBlockFieldsOptions($id, $otherOptions); // crmv@195745
		$smarty->assign("OTHER_OPTIONS", addslashes(Zend_Json::encode($otherOptions)));
		//crmv@106857e
		
		// crmv@146671
		$extwsOptions = $PMUtils->getExtWSFields($id);
		$smarty->assign('EXTWS_OPTIONS',addslashes(Zend_Json::encode($extwsOptions)));
		// crmv@146671e
		
		$smarty->assign('SDK_CUSTOM_FUNCTIONS',SDK::getFormattedProcessMakerFieldActions());
		
		$elementsActors = $PMUtils->getElementsActors($id,true);
		$smarty->assign('ELEMENTS_ACTORS', addslashes(Zend_Json::encode($elementsActors)));
	}
	
	function execute($engine,$actionid) {
		global $adb, $table_prefix;
		
		$action = $engine->vte_metadata['actions'][$actionid];
		
		$engine->log("Action Email","action $actionid - {$action['action_title']}");
		
		// crmv@192497
		list($wsid, $record) = explode('x', $engine->id);
		$record_type = getSalesEntityType($record);
		$objrec = CRMEntity::getInstance($record_type);
		$record_status = $objrec->checkRetrieve($record, $record_type, false);
		if(in_array($record_status, array('LBL_RECORD_DELETE','LBL_RECORD_NOT_FOUND'))) {
			return;
		}
		// crmv@192497e
		
		// crmv@188842
		$parent_id = '';
		$PMUtils = ProcessMakerUtils::getInstance();
		if ($action['parent_id'] != 'none') {
			if (!empty($action['parent_id'])) {
				list($rel_metaid,$rel_module,$rel_reference,$rel_meta_processid,$rel_relatedModule) = explode(':',$action['parent_id']);
				$parent_id = $engine->getCrmid($rel_metaid,null,$rel_reference,$rel_meta_processid);
				$parent_id_module = getSalesEntityType($parent_id);
				if (!empty($rel_relatedModule)) {
					if ($rel_relatedModule != $parent_id_module) {
						$parent_id = '';
						$engine->log("Action Email","action $actionid - {$action['action_title']} Relation FAILED parent_id {$action['parent_id']} do not found");
					}
				}
			} else {
				// automatic mode: link email to the primary record of the process (the record on the start task)
				//crmv@185355
				$processid = $engine->processid;
				$running_process = $engine->running_process;
				// check if is a subprocess use the father process
				$result = $adb->pquery("select subp.processid, metarec.id
					from {$table_prefix}_subprocesses subp
					inner join {$table_prefix}_processmaker_metarec metarec on metarec.processid = subp.processid
					where subp.subprocess = ? and metarec.start = ?", array($engine->processid,1));
				if ($result && $adb->num_rows($result) > 0) {
					$parent_processid = $adb->query_result($result,0,'processid');
					$parent_processid_metaid = $adb->query_result($result,0,'id');
					$running_process = $PMUtils->getRelatedRunningProcess($engine->running_process,$engine->processid,$parent_processid_metaid);
					$processid = getSingleFieldValue("{$table_prefix}_running_processes", 'processmakerid', 'id', $running_process);
				}
				$result = $adb->pquery("select rec.crmid
					from {$table_prefix}_processmaker_metarec metarec
					inner join {$table_prefix}_processmaker_rec rec on rec.id = metarec.id
					where metarec.start = ? and metarec.processid = ? and rec.running_process = ?", array(1,$processid,$running_process));
				//crmv@185355e
				if ($result && $adb->num_rows($result) > 0) {
					$parent_id = $adb->query_result($result,0,'crmid');
					$parent_module = getSalesEntityType($parent_id);
					$parentInstance = Vtecrm_Module::getInstance($parent_module);
					$messagesInstance = Vtecrm_Module::getInstance('Messages');
					$result = $adb->pquery("select relation_id from {$table_prefix}_relatedlists where tabid = ? and related_tabid = ?", array($parentInstance->id, $messagesInstance->id));
					if ($adb->num_rows($result) == 0) {
						// if there is no relationship with the Messages I look for the records connected through uitype 10 if there is a module connected to the messages
						$parent_id_old = $parent_id; $parent_id = '';
						$result1 = $adb->pquery("select fieldname, relmodule
							from {$table_prefix}_field
							inner join {$table_prefix}_fieldmodulerel on {$table_prefix}_field.fieldid = {$table_prefix}_fieldmodulerel.fieldid
							where tabid = ?", array($parentInstance->id));
						if ($result1 && $adb->num_rows($result1) > 0) {
							while($row=$adb->fetchByAssoc($result1)) {
								$parentInstance2 = Vtecrm_Module::getInstance($row['relmodule']);
								$result2 = $adb->pquery("select relation_id from {$table_prefix}_relatedlists where tabid = ? and related_tabid = ?", array($parentInstance2->id, $messagesInstance->id));
								if ($adb->num_rows($result2) > 0) {
									$focusTmp = CRMEntity::getInstance($parent_module);
									$focusTmp->retrieve_entity_info_no_html($parent_id_old,$parent_module);
									if (!empty($focusTmp->column_fields[$row['fieldname']])) {
										$parent_id = $focusTmp->column_fields[$row['fieldname']];
										break;
									}
								}
							}
						}
					}
				}
			}
		}
		// crmv@188842e
		
		$params = array(
			'subject'=>$action['subject'],
			'description'=>$action['content'],
			'mfrom'=>$action['sender'],
			'mto'=>$action['recepient'],
			'mcc'=>$action['emailcc'],
			'mbcc'=>$action['emailbcc'],
			'mreplyto'=>$action['emailreplyto'], // crmv@200330
			'mtype'=>'Link',
			'mvisibility'=>'Public', // crmv@189760
			'parent_id'=>$parent_id.'@1|',
		);
		
		//crmv@178425
		$messageFocus = CRMEntity::getInstance('Messages');
		$parsedAddress = $messageFocus->parseAddressList($action['sender']);
		$params['mfrom'] = $parsedAddress[0]['email'];
		$params['mfrom_n'] = $parsedAddress[0]['name'];
		//crmv@178425e
		
		// crmv@126696
		$this->replaceParams($engine, $params, $actionid); //crmv@183346
		
		if(strlen(trim($params['mto'], " \t\n,")) == 0 && strlen(trim($params['mcc'], " \t\n,")) == 0 && strlen(trim($params['mbcc'], " \t\n,")) == 0) {
			$engine->log("Action Email","action $actionid FAILED: recepients empty");
			return;
		}
		// crmv@126696e
		
		if (!empty($params['mreplyto'])) $params['send_mail_newsletter_params']['reply_to'] = $params['mreplyto']; // crmv@200330
		
		$focus = CRMentity::getInstance('Messages');
		$mail_status = $focus->send($params,false,true); // crmv@129149
		if ($mail_status == 1) {
			$engine->log("Action Email","action $actionid SUCCESS");
			if (!empty($parent_id)) {
				$focus->saveCacheLink($params);
				$engine->log("Action Email","related email to $parent_id");
			} else {
				$engine->log("Action Email","unable to relate email to a record");
			}
		} else {
			$engine->log("Action Email","action $actionid FAILED: $mail_status");
		}
	}
	
	// crmv@126696
	function replaceParams($engine, &$params, $actionid, $referenceFields=array(), $ownerFields=array()) { //crmv@183346
		// init variabiles to replace tags
		global $log, $adb;
		static $cacheWsEntities = array();
		
		$util = new VTWorkflowUtils();
		$admin = $util->adminUser();
		$entityCache = new VTEntityCache($admin);
		$util->revertUser();
		// end
		
		$PMUtils = ProcessMakerUtils::getInstance();
		
		(!empty($this->cycleRow['id'])) ? $cycleIndex = $this->cycleRow['id'] : $cycleIndex = $this->cycleIndex;
		
		// replace tags
		foreach($params as $fieldname => &$value) {
			$value = $engine->replaceTags($fieldname,$value,$referenceFields,$ownerFields,$actionid,$cycleIndex); //crmv@183346
			if (in_array($fieldname,array('mto','mcc','mbcc','mreplyto'))) { // crmv@200330
				$value = explode(',',$value);
				if (!empty($value)) {
					$tmp = array();
					foreach($value as $t) {
						$tmp[] = trim($t);
					}
					$value = implode(',',array_filter($tmp));
				}
			}
		}
		
		if (!empty($params['description'])) {
			$ct = new VTSimpleTemplate($params['description']);
			$params['description'] = $ct->render($entityCache,'');
			//$params['description'] = nl2br($params['description']);	// TODO spostare dentro al ciclo prima di ogni replace
		}
	}
	// crmv@126696e
}
