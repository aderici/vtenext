<?php
/* crmv@131239 crmv@126285 */
global $adb, $table_prefix;
$result = $adb->query("select reportid, stdfilters from {$table_prefix}_reportconfig where stdfilters is not null");
if ($result && $adb->num_rows($result) > 0) {
	while($row=$adb->fetchByAssoc($result,-1,false)) {
		$stdfilters = Zend_Json::decode($row['stdfilters']);
		if (isset($stdfilters[0]['fieldid']) && count($stdfilters[0]) == 1) {
			$adb->pquery("update {$table_prefix}_reportconfig set stdfilters = null where reportid = ?", array($row['reportid']));
		}
	}
}

// attivo il cron mailscanner se non ha mai girato e non ci sono scanner attivi
$result = $adb->pquery("select scannerid from {$table_prefix}_mailscanner where isvalid = ?", array(1));
if ($result && $adb->num_rows($result) == 0) {
	$adb->pquery("update {$table_prefix}_cronjobs set active = ? where cronname = ? and last_duration is null", array(1,'MailScanner'));
}

$result = $adb->pquery("select * from {$table_prefix}_ws_fieldtype where uitype = ?", array(5));
if ($adb->num_rows($result) == 0) {
	$fieldtypeid = $adb->getUniqueId($table_prefix.'_ws_fieldtype');
	$adb->pquery("insert into ".$table_prefix."_ws_fieldtype(fieldtypeid,uitype,fieldtype) values(?,?,?)",array($fieldtypeid,5,'date'));
}

//crmv@125629
$columns = array_keys($adb->datadict->MetaColumns($table_prefix."_messages_account"));
if (!in_array(strtoupper('error'),$columns)) {
	$sql = $adb->datadict->AddColumnSQL($table_prefix."_messages_account",'error C(50)');
	$adb->datadict->ExecuteSQLArray($sql);
}

SDK::setLanguageEntries('Messages', 'ERR_IMAP_AUTENTICATION', array('it_it'=>'Login fallito','en_us'=>'Login failed'));
//crmv@125629e

require_once('modules/Documents/storage/StorageBackendUtils.php');
$SBU = StorageBackendUtils::getInstance();
$SBU->syncDB();