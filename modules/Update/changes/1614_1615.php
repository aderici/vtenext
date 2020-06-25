<?php 

@unlink('Smarty/templates/themes/next/AuditTrailList.tpl');
@unlink('Smarty/templates/themes/next/ColoredListView.tpl');
@unlink('Smarty/templates/themes/next/CreateEmailTemplate.tpl');
@unlink('Smarty/templates/themes/next/CreateProfile.tpl');
@unlink('Smarty/templates/themes/next/CurrencyDetailView.tpl');
@unlink('Smarty/templates/themes/next/CurrencyListView.tpl');
@unlink('Smarty/templates/themes/next/CustomFieldList.tpl');
@unlink('Smarty/templates/themes/next/CustomFieldMapping.tpl');
@unlink('Smarty/templates/themes/next/DefModuleView.tpl');
@unlink('Smarty/templates/themes/next/DetailView.tpl');
@unlink('Smarty/templates/themes/next/DetailViewEmailTemplate.tpl');
@unlink('Smarty/templates/themes/next/EditProfile.tpl');
@unlink('Smarty/templates/themes/next/FieldAccess.tpl');
@unlink('Smarty/templates/themes/next/GroupDetailview.tpl');
@unlink('Smarty/templates/themes/next/GroupEditView.tpl');
@unlink('Smarty/templates/themes/next/ListEmailTemplates.tpl');
@unlink('Smarty/templates/themes/next/ListGroup.tpl');
@unlink('Smarty/templates/themes/next/OrgSharingEditView.tpl');
@unlink('Smarty/templates/themes/next/ProfileDetailView.tpl');
@unlink('Smarty/templates/themes/next/RoleDetailView.tpl');
@unlink('Smarty/templates/themes/next/RoleEditView.tpl');
@unlink('Smarty/templates/themes/next/UserDetailView.tpl');
@unlink('Smarty/templates/themes/next/UserListViewContents.tpl');
@unlink('Smarty/templates/themes/next/UserProfileList.tpl');

@unlink('Smarty/templates/themes/next/Settings/DataImporter/DataImporter.tpl');
FSUtils::deleteFolder('Smarty/templates/themes/next/Settings/DataImporter');

@unlink('Smarty/templates/themes/next/Settings/ModuleMaker/ModuleMaker.tpl');
FSUtils::deleteFolder('Smarty/templates/themes/next/Settings/ModuleMaker');

@unlink('Smarty/templates/themes/next/Settings/ModuleManager/ModuleManager.tpl');
FSUtils::deleteFolder('Smarty/templates/themes/next/Settings/ModuleManager');

@unlink('Smarty/templates/themes/next/Settings/WizardMaker/WizardMaker.tpl');
FSUtils::deleteFolder('Smarty/templates/themes/next/Settings/WizardMaker');

@unlink('Smarty/templates/themes/next/Settings/AsteriskServer.tpl');
@unlink('Smarty/templates/themes/next/Settings/CompanyInfo.tpl');
@unlink('Smarty/templates/themes/next/Settings/CustomInvoiceNo.tpl');
@unlink('Smarty/templates/themes/next/Settings/CustomModEntityNo.tpl');
@unlink('Smarty/templates/themes/next/Settings/CustomNo.tpl');
@unlink('Smarty/templates/themes/next/Settings/EditCompanyInfo.tpl');
@unlink('Smarty/templates/themes/next/Settings/EmailConfig.tpl');
@unlink('Smarty/templates/themes/next/Settings/FaxConfig.tpl');
@unlink('Smarty/templates/themes/next/Settings/InventoryTerms.tpl');
@unlink('Smarty/templates/themes/next/Settings/LdapServer.tpl');
@unlink('Smarty/templates/themes/next/Settings/LoginProtectionPanel.tpl');
@unlink('Smarty/templates/themes/next/Settings/ModuleOwners.tpl');
@unlink('Smarty/templates/themes/next/Settings/SmsConfig.tpl');
@unlink('Smarty/templates/themes/next/Settings/TaxConfig.tpl');

@unlink('Smarty/templates/themes/next/modules/PDFMaker/PDFMakerActions.tpl');
FSUtils::deleteFolder('Smarty/templates/themes/next/modules/PDFMaker');

$languages = vtlib_getToggleLanguageInfo();
$trans = array('ALERT_ARR' => array());

$r = $adb->pquery("SELECT language, label, trans_label FROM sdk_language WHERE module = ? AND label = ?", array('PDFMaker', 'SELECT_TEMPLATE'));
if ($r && $adb->num_rows($r)) {
	while ($row = $adb->fetchByAssoc($r, -1, false)) {
		$language = $row['language'];
		$translabel = $row['trans_label'];
		$trans['ALERT_ARR'][$language]['select_template'] = $translabel;
	}
}

foreach ($trans as $module => $modlang) {
	foreach ($modlang as $lang => $translist) {
		if (array_key_exists($lang, $languages)) {
			foreach ($translist as $label => $translabel) {
				SDK::setLanguageEntry($module, $lang, $label, $translabel);
			}
		}
	}
}

$res = $adb->pquery("SELECT * FROM {$table_prefix}_links WHERE linkurl LIKE ?", array('getPDFListViewPopup2%'));

if ($res && $adb->num_rows($res)) {
	while ($row = $adb->fetchByAssoc($res, -1, false)) {
		$linkid = $row['linkid'];
		unset($row['linkid']);
		
		$row['linkurl'] = "VTE.PDFMakerActions.getPDFListViewPopup2(this,'$" . "MODULE$');";
		
		$upd = array();
		foreach ($row as $col => $value) {
			$upd[] = "$col = ?";
		}
		
		$sql = "UPDATE {$table_prefix}_links SET " . implode(',', $upd) . " WHERE linkid = ?";
		$adb->pquery($sql, array($row, $linkid));
	}
}

