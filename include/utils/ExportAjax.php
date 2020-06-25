<?php
/*********************************************************************************

** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/

$idstring = rtrim($_REQUEST['idstring'],";");
$out = '';

if($_REQUEST['export_record'] == true) {
	
	//This conditions user can select includesearch & (all |currentpage|selecteddata) but not search
	if (($_REQUEST['search_type'] == 'includesearch' && $_REQUEST['export_data'] == 'all') && VteSession::get('export_where') == '') {
		$out = 'NOT_SEARCH_WITHSEARCH_ALL';
	} elseif(($_REQUEST['search_type'] == 'includesearch' && $_REQUEST['export_data'] == 'currentpage') && VteSession::get('export_where') == '') {
		$out = 'NOT_SEARCH_WITHSEARCH_CURRENTPAGE';
	} elseif(($_REQUEST['search_type'] == 'includesearch' && $_REQUEST['export_data'] == 'selecteddata') && $idstring == '') {
		$out = 'NO_DATA_SELECTED';
	}
	//This conditions user can select withoutsearch & (all |currentpage|selecteddata) but  search
	elseif (($_REQUEST['search_type'] == 'withoutsearch' && $_REQUEST['export_data'] == 'all') && VteSession::get('export_where') != '') {
		$out = 'SEARCH_WITHOUTSEARCH_ALL';
	} elseif(($_REQUEST['search_type'] == 'withoutsearch' && $_REQUEST['export_data'] == 'currentpage') && VteSession::get('export_where') != '') {
		$out = 'SEARCH_WITHOUTSEARCH_CURRENTPAGE';
	} elseif(($_REQUEST['search_type'] == 'withoutsearch' && $_REQUEST['export_data'] == 'selecteddata') && $idstring == '') {
		$out = 'NO_DATA_SELECTED';
	}
}

die($out);
