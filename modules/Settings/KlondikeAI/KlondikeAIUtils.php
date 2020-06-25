<?php
/*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************/
/* crmv@190834 crmv@197445 */
require_once('include/Zend/Json.php');

class KlondikeAIUtils extends SDKExtendableUniqueClass {
	
	private $dbconfig = array(
		'db_server' => '',
		'db_port' => ':3306',
		'db_username' => '',
		'db_password' => '',
		'db_name' => '',
		'db_type' => 'mysqli',
		'db_status' => 'true',
		'db_charset' => 'utf8'
	);
	
	function getConnection() {
		if (empty($this->dbconfig['db_server'])) return false;
		
		$db = new PearDatabase($this->dbconfig['db_type'], ($this->dbconfig['db_type'] == 'mysqli' || $this->dbconfig['db_type'] == 'mssqlnative') ? $this->dbconfig['db_server'] : $this->dbconfig['db_hostname'], $this->dbconfig['db_name'], $this->dbconfig['db_username'], $this->dbconfig['db_password'], $this->dbconfig['db_charset']);
		$db->connect();
		return $db;
	}
	
	function getDiscoveryHeaderList() {
		return array(
			getTranslatedString('LBL_ACTIONS'),
			getTranslatedString('LBL_PROCESS_DISCOVERY_ID','Settings'),
			getTranslatedString('LBL_PROCESS_DISCOVERY_ATTR_SET','Settings'),
			getTranslatedString('LBL_PROCESS_DISCOVERY_EVENTS','Settings'),
			getTranslatedString('LBL_PROCESS_DISCOVERY_METRICS','Settings'),
			getTranslatedString('LBL_MODULE'),
		);
	}
	
	function getDiscoveryList() {
		$list = array();
		$db = $this->getConnection();
		if ($db) {
			$result = $db->query("select id, `set`, `event`, bpmn_translated, metrics, module from output");
			if ($result && $db->num_rows($result) > 0) {
				while($row=$db->fetchByAssoc($result,-1,false)) {
					
					$att_set_str = '';
					$att_set = Zend_Json::decode($row['set']);
					if (!empty($att_set)) {
						foreach($att_set as $set) {
							$att_set_str[] = $set[0].": <span class=\"dvtCellLabel\">{$set[1]}</span>";
						}
					}
					$att_set_str = implode(", ",$att_set_str);
					
					$event_str = substr($row['event'],strrpos($row['event'],', "')+3);
					$event_str = trim(rtrim($event_str,'"]'));
					
					$metrics_str = '';
					$metrics = rtrim(ltrim($row['metrics'],'{'),'}');
					$metrics = explode(', ',$metrics);
					if (!empty($metrics)) {
						foreach($metrics as $metric) {
							$metric = ltrim($metric,':');
							$metric = str_replace('=>"',': <span class="dvtCellLabel">',$metric);
							$metric = rtrim($metric,'"').'</span>';
							$metrics_str[] = $metric;
						}
					}
					$metrics_str = implode(", ",$metrics_str);
					
					$list[] = array(
						'<a href="javascript:ProcessDiscoveryScript.download(\'bpmn\',\''.$row['id'].'\')"><i class="vteicon" title="'.getTranslatedString('LBL_DOWNLOAD_BPMN','Settings').'">file_download</i></a>
						<a href="javascript:ProcessDiscoveryScript.upload(\''.$row['id'].'\')"><i class="vteicon" title="'.getTranslatedString('LBL_UPLOAD_DISCOVERED_BPMN','Settings').'">file_upload</i></a>',
						'<a href="index.php?module=Settings&action=SettingsAjax&file=ProcessDiscovery&parenttab=Settings&mode=detail&id='.$row['id'].'">'.textlength_check($row['id']).'</a>',
						$att_set_str,
						$event_str,
						$metrics_str,
						getTranslatedString($row['module'],$row['module']),
					);
				}
			}
		}
		return $list;
	}
	
	function retrieveDiscovery($id) {
		$db = $this->getConnection();
		if ($db) {
			$result = $db->pquery("select id, `set`, `event`, bpmn_translated, metrics, module from output where id = ?", array($id));
			if ($result && $db->num_rows($result) > 0) {
				$data = $db->fetchByAssoc($result,-1,false);
				
				$event_str = substr($data['event'],strrpos($data['event'],', "')+3);
				$event_str = trim(rtrim($event_str,'"]'));
				$data['event'] = $event_str;
				
				return $data;
			}
		}
		return false;
	}
	
	function getAgentHeaderList() {
		global $app_strings;
		return array(
			getTranslatedString('LBL_ACTIONS'),
			$app_strings['LBL_MODULE'],
			getTranslatedString('LBL_FILTER'),
		);
	}
	
	function getAgentList() {
		global $adb, $table_prefix, $app_strings;
		$list = array();
		$result = $adb->query("select {$table_prefix}_process_discovery_agent.*, viewname, entitytype
			from {$table_prefix}_process_discovery_agent
			inner join {$table_prefix}_customview on {$table_prefix}_customview.cvid = {$table_prefix}_process_discovery_agent.viewid");
		if ($result && $adb->num_rows($result) > 0) {
			while($row=$adb->fetchByAssoc($result,-1,false)) {
				$module = $row['entitytype'];
				$viewname = $row['viewname'];
				if ($viewname == 'All') $viewname = $app_strings['COMBO_ALL'];
				if ($module == 'Calendar' && in_array($viewname,array('Events','Tasks'))) $viewname = $app_strings[$viewname];
				
				$list[] = array(
					'<a href="index.php?module=Settings&action=ProcessDiscoveryAgent&parenttab=Settings&mode=edit&id='.$row['id'].'"><i class="vteicon" title="'.getTranslatedString('LBL_EDIT').'">create</i></a>
					<a href="index.php?module=Settings&action=ProcessDiscoveryAgent&parenttab=Settings&mode=delete&id='.$row['id'].'"><i class="vteicon" title="'.getTranslatedString('LBL_DELETE').'">clear</i></a>',
					getTranslatedString($module,$module),
					$viewname,
				);
			}
		}
		return $list;
	}
	
	function retrieveAgent($id) {
		global $adb, $table_prefix;
		$result = $adb->pquery("select {$table_prefix}_process_discovery_agent.*, viewname, entitytype
			from {$table_prefix}_process_discovery_agent
			inner join {$table_prefix}_customview on {$table_prefix}_customview.cvid = {$table_prefix}_process_discovery_agent.viewid
			where {$table_prefix}_process_discovery_agent.id = ?", array($id));
		if ($result && $adb->num_rows($result) > 0) {
			return array(
				'id' => $adb->query_result($result,0,'id'),
				'tabid' => $adb->query_result($result,0,'tabid'),
				'viewid' => $adb->query_result($result,0,'viewid'),
				'viewname' => $adb->query_result($result,0,'viewname'),
				'module' => $adb->query_result($result,0,'entitytype'),
			);
		}
	}
	
	function saveAgent($id, $module, $viewid) {
		global $adb, $table_prefix;
		if (empty($id)) {
			$id = $adb->getUniqueID($table_prefix.'_process_discovery_agent');
			$adb->pquery("insert into {$table_prefix}_process_discovery_agent values(?,?,?)", array($id, getTabid($module), $viewid));
		} else {
			$adb->pquery("update {$table_prefix}_process_discovery_agent set tabid = ?, viewid = ? where id = ?", array(getTabid($module), $viewid, $id));
		}
	}

	function deleteAgent($id) {
		global $adb, $table_prefix;
		$adb->pquery("delete from {$table_prefix}_process_discovery_agent where id = ?", array($id));
	}
	
	function getClassifierHeaderList() {
		global $app_strings;
		return array(
			getTranslatedString('LBL_ACTIONS'),
			$app_strings['LBL_MODULE'],
			getTranslatedString('LBL_FILTER'),
			getTranslatedString('LBL_TRAINING_COLUMNS'),
			getTranslatedString('LBL_TRAINING_TARGET'),
		);
	}
	
	function getClassifierList() {
		global $adb, $table_prefix, $app_strings;
		$list = array();
		$result = $adb->query("select {$table_prefix}_klondike_classifier.*, viewname, entitytype
			from {$table_prefix}_klondike_classifier
			inner join {$table_prefix}_customview on {$table_prefix}_customview.cvid = {$table_prefix}_klondike_classifier.viewid");
		if ($result && $adb->num_rows($result) > 0) {
			while($row=$adb->fetchByAssoc($result,-1,false)) {
				$module = $row['entitytype'];
				$viewname = $row['viewname'];
				if ($viewname == 'All') $viewname = $app_strings['COMBO_ALL'];
				if ($module == 'Calendar' && in_array($viewname,array('Events','Tasks'))) $viewname = $app_strings[$viewname];
				
				$training_columns = Zend_Json::decode($row['training_columns']);
				$result1 = $adb->pquery("select fieldlabel from {$table_prefix}_field where tabid = ? and fieldname in (".generateQuestionMarks($training_columns).")", array($row['tabid'],$training_columns));
				if ($result1 && $adb->num_rows($result1) > 0) {
					$training_columns = array();
					while($row1=$adb->fetchByAssoc($result1,-1,false)) {
						$training_columns[] = getTranslatedString($row1['fieldlabel'],$module);
					}
					$training_columns = implode(', ',$training_columns);
				}
				
				$result1 = $adb->pquery("select fieldlabel from {$table_prefix}_field where tabid = ? and fieldname = ?", array($row['tabid'],$row['training_target']));
				if ($result1 && $adb->num_rows($result1) > 0) {
					$training_target = getTranslatedString($adb->query_result($result1,0,'fieldlabel'),$module);
				}
				
				$list[] = array(
					'<a href="index.php?module=Settings&action=KlondikeClassifier&parenttab=Settings&mode=edit&id='.$row['id'].'"><i class="vteicon" title="'.getTranslatedString('LBL_EDIT').'">create</i></a>
					<a href="index.php?module=Settings&action=KlondikeClassifier&parenttab=Settings&mode=delete&id='.$row['id'].'"><i class="vteicon" title="'.getTranslatedString('LBL_DELETE').'">clear</i></a>',
					getTranslatedString($module,$module),
					$viewname,
					$training_columns,
					$training_target
				);
			}
		}
		return $list;
	}
	
	function retrieveClassifier($id) {
		global $adb, $table_prefix;
		$result = $adb->pquery("select {$table_prefix}_klondike_classifier.*, viewname, entitytype
			from {$table_prefix}_klondike_classifier
			inner join {$table_prefix}_customview on {$table_prefix}_customview.cvid = {$table_prefix}_klondike_classifier.viewid
			where {$table_prefix}_klondike_classifier.id = ?", array($id));
		if ($result && $adb->num_rows($result) > 0) {
			return array(
				'id' => $adb->query_result($result,0,'id'),
				'tabid' => $adb->query_result($result,0,'tabid'),
				'viewid' => $adb->query_result($result,0,'viewid'),
				'viewname' => $adb->query_result($result,0,'viewname'),
				'module' => $adb->query_result($result,0,'entitytype'),
				'training_columns' => Zend_Json::decode($adb->query_result_no_html($result,0,'training_columns')),
				'training_target' => $adb->query_result($result,0,'training_target'),
			);
		}
	}
	
	function saveClassifier($id, $module, $viewid, $training_columns, $training_target) {
		global $adb, $table_prefix;
		$tc = array();
		foreach($training_columns as $training_column) {
			$tmp = explode(':',$training_column);
			$tc[] = $tmp[2];
		}
		$tc = Zend_Json::encode($tc);
		list(,,$tt) = explode(':',$training_target);
		if (empty($id)) {
			$id = $adb->getUniqueID($table_prefix.'_klondike_classifier');
			$adb->pquery("insert into {$table_prefix}_klondike_classifier values(?,?,?,?,?)", array($id, getTabid($module), $viewid, $tc, $tt));
		} else {
			$adb->pquery("update {$table_prefix}_klondike_classifier set tabid = ?, viewid = ?, training_columns = ?, training_target = ? where id = ?", array(getTabid($module), $viewid, $tc, $tt, $id));
		}
	}

	function deleteClassifier($id) {
		global $adb, $table_prefix;
		$adb->pquery("delete from {$table_prefix}_klondike_classifier where id = ?", array($id));
	}
}