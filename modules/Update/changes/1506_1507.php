<?php
global $adb, $table_prefix;
$result = $adb->pquery("select * from {$table_prefix}_actionmapping where actionname = ?", array('Turbolift'));
if ($result && $adb->num_rows($result) == 0) $adb->pquery("insert into {$table_prefix}_actionmapping(actionid,actionname,securitycheck) values(?,?,?)", array(4,'Turbolift',1));

require_once('include/utils/UserInfoUtil.php');
create_tab_data_file();

SDK::setUitype(213, 'modules/SDK/src/213/213.php', 'modules/SDK/src/213/213.tpl', '');