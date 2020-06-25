<?php
global $adb, $table_prefix;

// add an index
$check = false;
$indexes = $adb->database->MetaIndexes("{$table_prefix}_running_processes_logs");
foreach($indexes as $name => $index) {
	if (count($index['columns']) == 1 && $index['columns'][0] == 'running_process') {
		$check = true;
		break;
	}
}
if (!$check) {
	$sql = $adb->datadict->CreateIndexSQL('running_processes_logs_idx', "{$table_prefix}_running_processes_logs", 'running_process');
	if ($sql) $adb->datadict->ExecuteSQLArray($sql);
}

// add variables in config.inc
$configInc = file_get_contents('config.inc.php');
if (empty($configInc)) {
	Update::info("Unable to get config.inc.php contents, please modify it manually.");
} else {
	if (strpos($configInc, '$new_folder_storage_owner') === false) {
		// backup it (only if it doesn't exist)
		$newConfigInc = 'config.inc.1515.php';
		if (!file_exists($newConfigInc)) {
			file_put_contents($newConfigInc, $configInc);
		}
		// alter config inc (add it after default language)
		$configInc = str_replace('?>', "\n\$new_folder_storage_owner = array('user'=>'','group'=>'');	//crmv@98116\n?>", $configInc);
		if (is_writable('config.inc.php')) {
			file_put_contents('config.inc.php', $configInc);
		} else {
			Update::info("Unable to update config.inc.php, please modify it manually.");
		}
	}
}

// tabella che contiene temporaneamente i record a cui ha accesso un utente secondo i permessi avanzati dei processi
$name = "{$table_prefix}_process_adv_perm_tmp";
$schema_table = '<?xml version="1.0"?>
<schema version="0.3">
  <table name="'.$name.'">
  <opt platform="mysql">ENGINE=InnoDB</opt>
    <field name="userid" type="I" size="19">
      <KEY/>
    </field>
    <field name="crmid" type="I" size="19">
      <KEY/>
    </field>
  </table>
</schema>';
if(!Vtiger_Utils::CheckTable($name)) {
	$schema_obj = new adoSchema($adb->database);
	$schema_obj->ExecuteSchema($schema_obj->ParseSchemaString($schema_table));
}

// tabella degli attori (utenti che hanno scatenato un processo o fatto avanzare di task)
// un'utente vede quindi in list anche i processi a cui ha partecipato anche se non sono più assegnati a lui
$name = "{$table_prefix}_actor_running_processes";
$schema_table = '<?xml version="1.0"?>
<schema version="0.3">
  <table name="'.$name.'">
  <opt platform="mysql">ENGINE=InnoDB</opt>
    <field name="userid" type="I" size="19">
      <KEY/>
    </field>
    <field name="running_process" type="I" size="19">
      <KEY/>
    </field>
	<index name="running_process_idx">
      <col>running_process</col>
    </index>
  </table>
</schema>';
if(!Vtiger_Utils::CheckTable($name)) {
	$schema_obj = new adoSchema($adb->database);
	$schema_obj->ExecuteSchema($schema_obj->ParseSchemaString($schema_table));
	
	$adb->query("insert into {$table_prefix}_actor_running_processes(userid, running_process) select userid, running_process from {$table_prefix}_running_processes_logs where userid > 0 group by userid, running_process");
}

// tabella dei precedenti assegnatari dei processi
// salvo utenti o gruppi a cui era assegnato un processo e questi utenti continueranno a vedere i processi che gli erano stati assegnati anche se ora non lo sono più
$name = "{$table_prefix}_assigned_running_processes";
$schema_table = '<?xml version="1.0"?>
<schema version="0.3">
  <table name="'.$name.'">
  <opt platform="mysql">ENGINE=InnoDB</opt>
    <field name="assigned" type="I" size="19">
      <KEY/>
    </field>
    <field name="running_process" type="I" size="19">
      <KEY/>
    </field>
	<index name="running_process_idx">
      <col>running_process</col>
    </index>
  </table>
</schema>';
if(!Vtiger_Utils::CheckTable($name)) {
	$schema_obj = new adoSchema($adb->database);
	$schema_obj->ExecuteSchema($schema_obj->ParseSchemaString($schema_table));
}