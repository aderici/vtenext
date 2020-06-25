<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the 
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Users/Logout.php,v 1.8 2005/03/21 04:51:21 ray Exp $
 * Description:  TODO: To be written.
 ********************************************************************************/

require_once('include/logging.php');
require_once('modules/Users/LoginHistory.php');
require_once('modules/Users/Users.php');
require_once('config.php');
require_once('include/db_backup/backup.php');
require_once('include/db_backup/ftp.php');
require_once('include/database/PearDatabase.php');
require_once('user_privileges/enable_backup.php');

global $adb, $enable_backup,$current_user;

if($enable_ftp_backup == 'true' && is_admin($current_user) == true)
{
	$ftpserver = '';
	$ftpuser = '';
	$ftppassword = '';
	//crmv@157490
	$serverConfigUtils = ServerConfigUtils::getInstance();
	$serverConfig = $serverConfigUtils->getConfiguration('ftp_backup',array('server','server_username','server_password'));
	if (!empty($serverConfig)) {
		$ftpserver = $serverConfig['server'];
		$ftpuser = $serverConfig['server_username'];
		$ftppassword = $serverConfig['server_password'];
	}
	//crmv@157490e
	//Taking the Backup of DB
	$currenttime=date("Ymd_His");
	if($ftpserver != '' && $ftpuser != '' && $ftppassword != '')
	{
		$createZip = new createDirZip;
		$fileName = '/backup_'.$currenttime.'.zip';

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
		
		$source_file=$fileName;	
		ftpBackupFile($source_file, $ftpserver, $ftpuser, $ftppassword);
		if(file_exists($source_file)) unlink($source_file);	

	}
}
if($enable_local_backup == 'true' && is_admin($current_user) == true)
{
		define("dbserver", $dbconfig['db_hostname']);
		define("dbuser", $dbconfig['db_username']);
		define("dbpass", $dbconfig['db_password']);
		define("dbname", $dbconfig['db_name']);  

        //crmv@157490
        $serverConfigUtils = ServerConfigUtils::getInstance();
        $path = $serverConfigUtils->getConfiguration('local_backup',array('server_path'),'server_type',true);
        //crmv@157490e
        $currenttime=date("Ymd_His");
        
		if(is_dir($path) && is_writable($path))
		{        
			$createZip = new createDirZip;
			$fileName = $path.'/backup_'.$currenttime.'.zip';
	
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
		}
}

// crmv@91082 - Recording Logout Info
$loghistory = LoginHistory::getInstance();
$loghistory->user_logout($current_user->user_name);
// crmv@91082e


$local_log =& LoggerManager::getLogger('Logout');

if ($current_user) $current_user->deleteCookieSaveLogin();	//crmv@29377

VteSession::destroy(); // crmv@128133

// unset savelogin cookie
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off');
setcookie('savelogindata', false, 0, "", "", $isHttps, true); //crmv@29377 crmv@80972

define("IN_LOGIN", true);

// go to the login screen.
header("Location: index.php?action=Login&module=Users");
?>
