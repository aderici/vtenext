<?php
/* crmv@43611 */

require_once('include/ListView/SimpleListView.php');

$listid = vtlib_purify($_REQUEST['listid']);
$mod = vtlib_purify($_REQUEST['mod']);

if (!empty($mod)) {
	$slv = SimpleListView::getInstance($mod);
	$slv->listid = $listid;
	$slv->ajaxHandler();
}
