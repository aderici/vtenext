<?php

/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
* 
 ********************************************************************************/

/* crmv@164355 */

/** This class is used to track all the operation{s done by the particular User while using crm. 
 *  It is intended to be called when the check for audit trail is enabled.
 **/
class AuditTrail{
		
	var $default_order_by = "actiondate";
	var $default_sort_order = 'DESC';

	public function __construct() {

	}
	
	public function countEntries($userid = null) {
		global $adb, $table_prefix;
		
		$params = array();
		$sql = "SELECT COUNT(*) AS cnt FROM {$table_prefix}_audit_trial";
		
		if ($userid > 0) {
			$sql .= " WHERE userid = ?";
			$params[] = $userid;
		}
		
		$res = $adb->pquery($sql, $params);
		$count = intval($adb->query_result_no_html($res, 0, 'cnt'));
		
		return $count;
	}
	
	/**
	 * Function to get the Headers of Audit Trail Information like Module, Action, RecordID, ActionDate.
	 * Returns Header Values like Module, Action etc in an array format.
	**/
	function getAuditTrailHeader() {
		global $app_strings;
		
		$header_array = array(
			getTranslatedString('LBL_MODULE', 'APP_STRINGS'),
			getTranslatedString('LBL_ACTION', 'APP_STRINGS'),
			getTranslatedString('LBL_RECORD_ID', 'APP_STRINGS'),
			getTranslatedString('LBL_ACTION_DATE', 'APP_STRINGS'),
		);

		return $header_array;
	}

	/**
	 * Function to get the Audit Trail Information values of the actions performed by a particular User.
	 * @param integer $userid - User's ID
	 * @param $navigation_array - Array values to navigate through the number of entries.
	 * Returns the audit trail entries in an array format.
	**/
	function getAuditTrailEntries($userid, $navigation_array, $moreInfo = false)
	{
		global $log,$table_prefix;
		$log->debug("Entering getAuditTrailEntries(".$userid.") method ...");
		global $adb, $current_user;
		
		$entries_list = array();
		
		$list_query = "SELECT * FROM {$table_prefix}_audit_trial WHERE userid = ? ORDER BY ".$this->default_order_by." ".$this->default_sort_order;
	
		if($navigation_array['end_val'] != 0) {
			$showRows = $navigation_array['end_val'] - $navigation_array['start'] + 1;
			$result = $adb->limitpQuery($list_query, $navigation_array['start'],$showRows, array($userid));
			while ($row = $adb->fetchByAssoc($result)) {
				$entries = array();
				$userid = $row['userid'];
		
				$entries[] = getTranslatedString($row['module']);
				$entries[] = $row['action'];
				$entries[] = $row['recordid'] . (!$moreInfo && $row['recordid'] > 0 ? " (<a href=\"index.php?module={$row['module']}&action=DetailView&record={$row['recordid']}\" target=\"_blank\">".getTranslatedString('LBL_LIST_SHOW', 'APP_STRINGS')."</a>)" : "");
				$entries[] = getDisplayDate($row['actiondate']);
				
				if ($moreInfo) {
					$entries['more_info'] = array(
						'module' => $row['module'],
					);
				}
			
				$entries_list[] = $entries;
			}
		}
		
		return $entries_list;
	}
	
	// crmv@180826
	public function exportForUser($userid, $format = 'auto') {
		global $site_URL;
		
		$navigation_array = array('start' => 1, 'end_val' => 1000000);
		$list = $this->getAuditTrailEntries($userid, $navigation_array, true);
		$header = $this->getAuditTrailHeader();
		
		if ($format == 'auto') $format = (count($list) > 80000 ? 'csv' : 'xlsx');

		if ($format == 'xlsx') {
			$excel_type = 'Xlsx';
			$app_type = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
		} elseif ($format == 'csv') {
			$excel_type = 'CSV';
			$app_type = 'text/csv';
		}
		
		$filename = "audit_trail_".date('Ymd_His').'.'.$format;
		
		header("Content-Disposition:attachment;filename={$filename}");
		header("Content-Type:$app_type;charset=UTF-8");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" ); // to disable cache
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
		header("Cache-Control: post-check=0, pre-check=0", false);

		$objPHPExcel = new PhpOffice\PhpSpreadsheet\Spreadsheet();
		$objPHPExcel->getProperties()
			->setCreator("VTE CRM")
			->setLastModifiedBy("VTE CRM")
			->setTitle("Audit Trial ".date('Y-m-d H:i'));

		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
		$objPHPExcel->getDefaultStyle()->getFont()->setSize(10);

		$xlsStyle1 = new PhpOffice\PhpSpreadsheet\Style\Style();
		$xlsStyle1->applyFromArray(
			array('font' => array(
				'name' => 'Arial',
				'bold' => true,
				'size' => 11
			),
		));
			
		$sheet = $objPHPExcel->getActiveSheet();
		$sheet->setTitle(\PhpOffice\PhpSpreadsheet\Shared\StringHelper::Substring(getTranslatedString('LBL_AUDIT_TRAIL','Settings'),0,29));
		$sheet->duplicateStyle($xlsStyle1, 'A1:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($header)).'1');
		
		// header
		$i = 0;
		foreach ($header as $hc) {
			$sheet->setCellValueByColumnAndRow($i, 1, $hc);
			$sheet->getColumnDimensionByColumn($i)->setAutoSize(true);
			++$i;
		}
		
		// data
		$rowcount = 2;
		foreach ($list as $row) {
			if ($row[2] > 0) {
				$row[2] = rtrim($site_URL, '/').'/index.php?module='.$row['more_info']['module'].'&action=DetailView&record='.$row[2];
			}
			unset($row['more_info']);
			$dcount = 0;
			foreach ($row as $idx => $value) {
				if (preg_match('#^https?://#i', $value)) {
					$sheet->getCellByColumnAndRow($dcount++, $rowcount)
						->setValue($value)
						->getHyperlink()->setUrl($value);
				} else {
					$sheet->setCellValueByColumnAndRow($dcount++, $rowcount, $value);
				}
			}
			++$rowcount;
		}
		
		$objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, $excel_type);
		$objWriter->setPreCalculateFormulas(false);
		$objWriter->save('php://output');

	}
	// crmv@164355e crmv@180826e
}
