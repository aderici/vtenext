<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/

//crmv@32611
echo getTranslatedString('LBL_MODULE','APP_STRINGS').' '.getTranslatedString('Inactive');
exit;
//crmv@32611e

global $mod_strings;
global $app_strings, $enable_backup;
global $app_list_strings;
global $adb;
global $theme;
global $table_prefix;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

$serverConfigUtils = ServerConfigUtils::getInstance();	//crmv@157490

if(isset($_REQUEST['opmode']) && $_REQUEST['opmode'] != '')
{
	$serverConfigUtils->removeConfiguration('ftp_backup');	//crmv@157490
}

$smarty = new VteSmarty();
if($_REQUEST['error'] != '')
{
	$smarty->assign("ERROR_MSG",'<b><font color="red">'.vtlib_purify($_REQUEST["error"]).'</font></b>');
}
if($_REQUEST['error1'] != '')
{
	$smarty->assign("ERROR_STR",'<b><font color="red">'.vtlib_purify($_REQUEST["error1"]).'</font></b>');
}
//crmv@157490
$serverConfig = $serverConfigUtils->getConfiguration('ftp_backup',array('server','server_username','server_password'));
$server = $serverConfig['server'];
$server_username = $serverConfig['server_username'];
$server_password = $serverConfig['server_password'];

$serverConfig = $serverConfigUtils->getConfiguration('local_backup',array('server','server_path'));
$local_server = $serverConfig['server'];
$server_path = $serverConfig['server_path'];
//crmv@157490e
if(isset($_REQUEST['bkp_server_mode']) && $_REQUEST['bkp_server_mode'] != '')
	$smarty->assign("BKP_SERVER_MODE",vtlib_purify($_REQUEST['bkp_server_mode']));
else
	$smarty->assign("BKP_SERVER_MODE",'view');

if(isset($_REQUEST['local_server_mode']) && $_REQUEST['local_server_mode'] != '')
	$smarty->assign("LOCAL_SERVER_MODE",vtlib_purify($_REQUEST['local_server_mode']));
else
	$smarty->assign("LOCAL_SERVER_MODE",'view');
	
if(isset($_REQUEST['server']))
	$smarty->assign("FTPSERVER",vtlib_purify($_REQUEST['server']));
else if (isset($server))
	$smarty->assign("FTPSERVER",$server);
if (isset($_REQUEST['server_user']))
	$smarty->assign("FTPUSER",vtlib_purify($_REQUEST['server_user']));
else if (isset($server_username))
	$smarty->assign("FTPUSER",$server_username);
if (isset($_REQUEST['password']))
	$smarty->assign("FTPPASSWORD",vtlib_purify($_REQUEST['password']));
else if (isset($server_password))
	$smarty->assign("FTPPASSWORD",$server_password);
if (isset($_REQUEST['path']))
	$smarty->assign("SERVER_BACKUP_PATH",vtlib_purify($_REQUEST['server_path']));
else if (isset($server_path))
	$smarty->assign("SERVER_BACKUP_PATH",$server_path);
$smarty->assign("MOD", return_module_language($current_language,'Settings'));
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("APP", $app_strings);
$smarty->assign("THEME", $theme);
$smarty->assign("CMOD", $mod_strings);

require_once('user_privileges/enable_backup.php');

if($enable_local_backup == 'true')	
	$local_backup_status = 'enabled';
else
	$local_backup_status = 'disabled';

if($enable_ftp_backup == 'true')	
	$ftp_backup_status = 'enabled';
else
	$ftp_backup_status = 'disabled';

$smarty->assign("FTP_BACKUP_STATUS", $ftp_backup_status);
$smarty->assign("LOCAL_BACKUP_STATUS", $local_backup_status);

require_once('include/logging.php');
require_once('modules/Users/LoginHistory.php');
require_once('modules/Users/Users.php');
require_once('config.php');
require_once('include/db_backup/backup.php');
require_once('include/db_backup/ftp.php');
require_once('include/database/PearDatabase.php');
require_once('user_privileges/enable_backup.php');

global $adb, $enable_backup;

if((isset($_REQUEST['backupnow'])))
{
	define("dbserver", $dbconfig['db_hostname']);
	define("dbuser", $dbconfig['db_username']);
	define("dbpass", $dbconfig['db_password']);
	define("dbname", $dbconfig['db_name']);  

	//crmv@157490
	$serverConfig = $serverConfigUtils->getConfiguration('local_backup',array('server_path'));
	$path = $serverConfig['server_path'];
	//crmv@157490e
    $currenttime=date("Ymd_His");
        
	if(is_dir($path) && is_writable($path))
	{        
		$fileName = $path.'/backup_'.$currenttime.'.zip';
		$createZip = new createDirZip;

		$createZip->addDirectory('user_privileges/');
		$createZip->get_files_from_folder('user_privileges/', 'user_privileges/');        

		$createZip->addDirectory('storage/');
		$createZip->get_files_from_folder('storage/', 'storage/');        

		$backup_DBFileName = "sqlbackup_".$currenttime.".sql";
		$dbdump = new DatabaseDump(dbserver, dbuser, dbpass);
		$dumpfile = 'backup/'.$backup_DBFileName;
		$dbdump->save(dbname, $dumpfile) ;

		$filedata = implode("", file('backup/'.$backup_DBFileName));	
		$createZip->addFile($filedata,$backup_DBFileName);
		
		$fd = fopen ($fileName, 'wb');
		$out = fwrite ($fd, $createZip->getZippedfile());
		fclose ($fd);
	
		$smarty->assign("BACKUP_RESULT", '<b><font color="red">'. $fileName.'</font></b>');
	}
	else
		$smarty->assign("BACKUP_RESULT", '<b><font color="red">Failed to backup</font></b>');
}

if($_REQUEST['ajax'] == 'true' && $_REQUEST['server_type'] == 'ftp_backup')
	$smarty->display("Settings/BackupServerContents.tpl");
else
	$smarty->display("Settings/BackupServer.tpl");

?>