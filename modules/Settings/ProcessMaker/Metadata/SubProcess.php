<?php
/*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************/
/* crmv@97575 crmv@136524 crmv@161211 */
$smarty->assign("MODE",$type);
$smarty->assign("HEADER", $PMUtils->getHeaderList(true));
$skip_ids = array($id);
$sub_processes = $PMUtils->getSubprocesses($id);
if (!empty($sub_processes)) {
	foreach($sub_processes as $sub_process) {
		$skip_ids[] = $sub_process['subprocess'];
	}
}
$skip_ids = array_diff($skip_ids,array($vte_metadata_arr['subprocess']));
$smarty->assign("LIST", $PMUtils->getList(true,$skip_ids,$vte_metadata_arr['subprocess']));
$smarty->assign("SUBPROCESS", $vte_metadata_arr['subprocess']);
//crmv@185705
global $current_language;
$smarty->assign("CURRENT_LANGUAGE", $current_language);
//crmv@185705e
$sub_template = 'Settings/ProcessMaker/List.tpl';