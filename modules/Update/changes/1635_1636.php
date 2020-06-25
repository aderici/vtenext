<?php
@unlink('Smarty/templates/themes/next/modules/Messages/Settings/AccountsPicklist.tpl');
@unlink('Smarty/templates/themes/next/modules/Messages/Settings/BottomSettings.tpl');
@unlink('Smarty/templates/themes/next/modules/Messages/Settings/EditAccount.tpl');
@unlink('Smarty/templates/themes/next/modules/Messages/Settings/EditFilter.tpl');
@unlink('Smarty/templates/themes/next/modules/Messages/Settings/EditPop3.tpl');
@unlink('Smarty/templates/themes/next/modules/Messages/Settings/Filters.tpl');
@unlink('Smarty/templates/themes/next/modules/Messages/Settings/FolderPicklist.tpl');
@unlink('Smarty/templates/themes/next/modules/Messages/Settings/Folders.tpl');
@unlink('Smarty/templates/themes/next/modules/Messages/Settings/Layout.tpl');
@unlink('Smarty/templates/themes/next/modules/Messages/Settings/List.tpl');
@unlink('Smarty/templates/themes/next/modules/Messages/Settings/Pop3.tpl');
@unlink('Smarty/templates/themes/next/modules/Messages/Settings/ScanResults.tpl');

require_once('include/utils/VTEProperties.php');
$VTEP = VTEProperties::getInstance();
$VTEP->setProperty('outlook_sdk',1);

global $adb, $table_prefix;
$res = $adb->query("select * from {$table_prefix}_field where fieldname = 'bu_mc'");
if ($res && $adb->num_rows($res) > 0) {
	Update::info("Please update the BUMC package files.");
}