<?php
/************************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * Portions created by CRMVILLAGE.BIZ are Copyright (C) CRMVILLAGE.BIZ.
 * All Rights Reserved.
 ************************************************************************************/

/**
 * Provides few utility functions for installation/migration process
 * @package install
 */

class Installation_Utils {
	
	//crmv@28327
	var $password_length_min = 8;		//if equal to 0 the check is disable
	//crmv@28327e
	
	static function getInstallableOptionalModules() {
		$optionalModules = Common_Install_Wizard_Utils::getInstallableModulesFromPackages();
		return $optionalModules;
	}
	
	// crmv@151405
	static function getInstallableBetaModules() {
		$optionalModules = Common_Install_Wizard_Utils::getInstallableBetaModulesFromPackages();
		return $optionalModules;
	}
	// crmv@151405e

	// Function to install Vtlib Compliant - Optional Modules
	static function installOptionalModules($selected_modules){
		Common_Install_Wizard_Utils::installSelectedOptionalModules($selected_modules);
	}
	
	// crmv@151405
	static function installBetaModules($selected_modules){
		Common_Install_Wizard_Utils::installSelectedBetaModules($selected_modules);
	}
	// crmv@151405e

	static function getDbOptions() {
		$dbOptions = array();
		// crmv@56443 - if PHP >= 5.5, use mysqli, since mysql is deprecated
		if (function_exists('mysqli_connect') && version_compare(PHP_VERSION, '5.5') >= 0) {
			$dbOptions['mysqli'] = 'MySQL';
		} elseif (function_exists('mysql_connect')) {
			$dbOptions['mysql'] = 'MySQL';
		}
		// crmv@56443e
		// crmv@65455 - hide postgres support
		/*if(function_exists('pg_connect')) {
			$dbOptions['pgsql'] = 'Postgres';
		}*/
		// crmv@65455e
		//crmv@add oracle/mssql support
		if(function_exists('OCIPLogon')) {
			$dbOptions['oci8po'] = 'Oracle';
		}
		// crmv@155585
		if(function_exists('mssql_pconnect')) {
			$dbOptions['mssql'] = 'Sql server';
		} elseif (function_exists('sqlsrv_connect')) {
			$dbOptions['mssqlnative'] = 'Sql server';
		}
		// crmv@155585e
		//crmv@add oracle/mssql support end
		return $dbOptions;
	}
	
	static function checkDbConnection($db_type, $db_hostname, $db_hostport, $db_username, $db_password, $db_name, $create_db=false, $create_utf8_db=true, $root_user='', $root_password='') {
		global $installationStrings, $vtiger_current_version;
		
		$dbCheckResult = array();
		require_once('include/DatabaseUtil.php');
		
		$db_type_status = false; // is there a db type?
		$db_server_status = false; // does the db server connection exist?
		$db_creation_failed = false; // did we try to create a database and fail?
		$db_exist_status = false; // does the database exist?
		$db_utf8_support = false; // does the database support utf8?
		$vt_charset = ''; // set it based on the database charset support
		
		//Checking for database connection parameters
		if($db_type) {
			$conn = &NewADOConnection($db_type);
			$db_type_status = true;
			//crmv@constructy hostname
			$db_hostname = Common_Install_Wizard_Utils::constructHostname($db_type,$db_hostname,$db_hostport);
			//crmv@constructy hostname end
			//crmv@fix-oracle
			if ($db_type == 'oci8po') {
				$result_conn = @$conn->Connect($db_hostname,$db_username,$db_password,$db_name);
			} else {
				$result_conn = @$conn->Connect($db_hostname,$db_username,$db_password);
			}
						
			if($result_conn) {
			//crmv@fix-oracle e
				$db_server_status = true;
				$serverInfo = $conn->ServerInfo();
				//crmv@fix version
				$sql_server_version = Common_Install_Wizard_Utils::getSQLVersion($serverInfo);
				$mysql_server_version = $sql_server_version;
				//crmv@fix version end
				if($create_db) {
					// drop the current database if it exists
					$dropdb_conn = &NewADOConnection($db_type);
					if(@$dropdb_conn->Connect($db_hostname, $root_user, $root_password, $db_name)) {
						$query = "drop database ".$db_name;
						if (@$dropdb_conn->Execute($query))
						$dropdb_conn->Close();
					}

					// create the new database
					$db_creation_failed = true;
					$createdb_conn = &NewADOConnection($db_type);
					if($createdb_conn->Connect($db_hostname, $root_user, $root_password)) {
						//crmv@fix utf8
						if($create_utf8_db == 'true') { 
							if(Common_Install_Wizard_Utils::isMySQL($db_type))
								$options['MYSQL'] = " default character set utf8 default collate utf8_general_ci"; 
							$db_utf8_support = true;
						}	
						//crmv@fix utf8 end
						//crmv@fix create database					
						$datadict = NewDataDictionary($createdb_conn,$createdb_conn->dataProvider);
						$sql = @$datadict->CreateDatabase($db_name,$options);
						if ($sql){
							if (@$datadict->ExecuteSQLArray($sql) == 2)
								$db_creation_failed = false;
						}	
						//crmv@fix create database end
						$createdb_conn->Close();
					}
				}
				// test the connection to the database
				if($conn->Connect($db_hostname, $db_username, $db_password, $db_name))
				{
					$db_exist_status = true;
					if(!$db_utf8_support) {
						// Check if the database that we are going to use supports UTF-8
						$db_utf8_support = check_db_utf8_support($conn);
					}
				}
				$conn->Close();
			}
		}
		$dbCheckResult['db_utf8_support'] = $db_utf8_support;
		
		$error_msg = '';
		$error_msg_info = '';
		
		if(!$db_type_status || !$db_server_status) {
			$error_msg = $installationStrings['ERR_DATABASE_CONNECTION_FAILED'].'. '.$installationStrings['ERR_INVALID_MYSQL_PARAMETERS'];
			$error_msg_info = $installationStrings['MSG_LIST_REASONS'].':<br>
					-  '.$installationStrings['MSG_DB_PARAMETERS_INVALID'].'. <a href="http://www.vtiger.com/products/crm/help/'.$vtiger_current_version.'/vtiger_CRM_Database_Hostname.pdf" target="_blank">'.$installationStrings['LBL_MORE_INFORMATION'].'</a><BR>
					-  '.$installationStrings['MSG_DB_USER_NOT_AUTHORIZED'];
		}
		elseif(Common_Install_Wizard_Utils::isMySQL($db_type) && (float)$mysql_server_version < (float)'4.1') {
			$error_msg = $mysql_server_version.' -> '.$installationStrings['ERR_INVALID_MYSQL_VERSION'];
		}
		elseif($db_creation_failed) {
			$error_msg = $installationStrings['ERR_UNABLE_CREATE_DATABASE'].' '.$db_name;
			$error_msg_info = $installationStrings['MSG_DB_ROOT_USER_NOT_AUTHORIZED'];
		}
		elseif(!$db_exist_status) {
			$error_msg = $db_name.' -> '.$installationStrings['ERR_DB_NOT_FOUND'];
		}
		else {
			$dbCheckResult['flag'] = true;
			return $dbCheckResult;
		}
		$dbCheckResult['flag'] = false;
		$dbCheckResult['error_msg'] = $error_msg;
		$dbCheckResult['error_msg_info'] = $error_msg_info;
		return $dbCheckResult;
	}
	
	//crmv@28327
	function checkPasswordCriteria($user_password,$row) {
		if ($this->password_length_min == 0) {
			return true; 
		}
		if (strlen($user_password) < $this->password_length_min) {
			return false;
		}
		$findme_array = array($row['user_name'],$row['first_name'],$row['last_name']);
		foreach ($findme_array as $findme) {
			if ($findme != '' && stripos($user_password,$findme) !== false) {
				return false;
			}
		}
		return true;
	}
	//crmv@28327e
}

class Migration_Utils {
	
	static function verifyMigrationInfo($migrationInfo) {
		global $installationStrings, $vtiger_current_version,$table_prefix;
		
		$dbVerifyResult = array();
		$dbVerifyResult['flag'] = false;
		$configInfo = array();
		
		if (isset($migrationInfo['source_directory'])) $source_directory = $migrationInfo['source_directory'];
		if (isset($migrationInfo['root_directory'])) $configInfo['root_directory'] = $migrationInfo['root_directory'];
		if(is_dir($source_directory)){
			if(!is_file($source_directory."config.inc.php")){
				$dbVerifyResult['error_msg'] = $installationStrings['ERR_NO_CONFIG_FILE'];
				return $dbVerifyResult;
			}
			if(!is_dir($source_directory."user_privileges")){
				$dbVerifyResult['error_msg'] = $installationStrings['ERR_NO_USER_PRIV_DIR'];
				return $dbVerifyResult;
			}
			if(!is_dir($source_directory."storage")){
				$dbVerifyResult['error_msg'] = $installationStrings['ERR_NO_STORAGE_DIR'];
				return $dbVerifyResult;
			}
		} else {
			$dbVerifyResult['error_msg'] = $installationStrings['ERR_NO_SOURCE_DIR'];
			return $dbVerifyResult;
		}
		global $dbconfig;
		require_once($source_directory."config.inc.php");
		$old_db_name = $dbconfig['db_name'];
		$db_hostname = $dbconfig['db_server'];
		$db_port = $dbconfig['db_port'];
		$db_username = $dbconfig['db_username'];
		$db_password = $dbconfig['db_password'];
		$db_type = $dbconfig['db_type'];
		
		if (isset($migrationInfo['user_name'])) $user_name = $migrationInfo['user_name'];
		if (isset($migrationInfo['user_pwd'])) $user_pwd = $migrationInfo['user_pwd'];
		if (isset($migrationInfo['old_version'])) $source_version = $migrationInfo['old_version'];
		if (isset($migrationInfo['new_dbname'])) $new_db_name = $migrationInfo['new_dbname'];
		
		$configInfo['db_name'] = $new_db_name;
		$configInfo['db_type'] = $db_type;
		$configInfo['db_server'] = $db_hostname;
		$configInfo['db_port'] = $db_port;
		$configInfo['db_hostname'] = $db_hostname.$db_port;
		$configInfo['db_username'] = $db_username;
		$configInfo['db_password'] = $db_password;
		$configInfo['admin_email'] = $HELPDESK_SUPPORT_EMAIL_ID;
		$configInfo['currency_name'] = $currency_name;
		
		$dbVerifyResult['old_dbname'] = $old_db_name;
	
		$db_type_status = false; // is there a db type?
		$db_server_status = false; // does the db server connection exist?
		$old_db_exist_status = false; // does the old database exist?
		$db_utf8_support = false; // does the database support utf8?
		$new_db_exist_status = false; // does the new database exist?
		$new_db_has_tables = false; // does the new database has tables in it?
		
		require_once('include/DatabaseUtil.php');
		//Checking for database connection parameters and copying old database into new database
		
		if($db_type) {
			$conn = &NewADOConnection($db_type);
			$db_type_status = true;
			
			if(@$conn->Connect($db_hostname,$db_username,$db_password)) {
				$db_server_status = true;
				$serverInfo = $conn->ServerInfo();
				$sql_server_version = Common_Install_Wizard_Utils::getSQLVersion($serverInfo);
				//crmv@fix version
				$mysql_server_version = $sql_server_version;
				//crmv@fix version end
				// test the connection to the old database
				$olddb_conn = &NewADOConnection($db_type);
				if(@$olddb_conn->Connect($db_hostname, $db_username, $db_password, $old_db_name))
				{
					$old_db_exist_status = true;
					if(version_compare(PHP_VERSION, '5.3.0') >= 0) {
						//crmv@fix alter table
						$datadict = NewDataDictionary($conn);
						$sql = $datadict->ChangeTableSQL($table_prefix."_users","user_password C 128");
						if (!$sql || $datadict->ExecuteSQLArray($sql)!=2){
							$dbVerifyResult['error_msg'] =
								$installationStrings['LBL_PASSWORD_FIELD_CHANGE_FAILURE'];
						}
						//crmv@fix alter table end
						if(!is_array($_SESSION['migration_info']['user_messages'])) {
							unset($_SESSION['migration_info']['user_messages']);
							$_SESSION['migration_info']['user_messages'] = array();
							$_SESSION['migration_info']['user_messages'][] = array(
							'status' => "<span style='color: red;font-weight: bold'>".
									$installationStrings['LBL_IMPORTANT_NOTE']."</span>",
								'msg' => "<span style='color: #3488cc;font-weight: bold'>".
									$installationStrings['LBL_USER_PASSWORD_CHANGE_NOTE']."</span>"
							);
						}
						
						self::resetUserPasswords($olddb_conn);
						$_SESSION['migration_info']['user_pwd'] = $user_name;
						$migrationInfo['user_pwd'] = $user_name;
						$user_pwd = $user_name;
					}
					
					if(Migration_Utils::authenticateUser($olddb_conn, $user_name,$user_pwd)==true) {
						$is_admin = true;
					} else{
						$dbVerifyResult['error_msg'] = $installationStrings['ERR_NOT_VALID_USER'];
						return $dbVerifyResult;
					}
					$olddb_conn->Close();
				}
		
				// test the connection to the new database
				$newdb_conn = &NewADOConnection($db_type);
				if(@$newdb_conn->Connect($db_hostname, $db_username, $db_password, $new_db_name))
				{
					$new_db_exist_status = true;
					$noOfTablesInNewDb = Migration_Utils::getNumberOfTables($newdb_conn);
					if($noOfTablesInNewDb > 0){
						$new_db_has_tables = true;
					}
					$db_utf8_support = check_db_utf8_support($newdb_conn);
					$configInfo['vt_charset'] = ($db_utf8_support)? "UTF-8" : "ISO-8859-1";
					$newdb_conn->Close();
				}		
			}
			$conn->Close();
		}
		
		if(!$db_type_status || !$db_server_status) {
			$error_msg = $installationStrings['ERR_DATABASE_CONNECTION_FAILED'].'. '.$installationStrings['ERR_INVALID_MYSQL_PARAMETERS'];
			$error_msg_info = $installationStrings['MSG_LIST_REASONS'].':<br>
					-  '.$installationStrings['MSG_DB_PARAMETERS_INVALID'].'. <a href="http://www.vtiger.com/products/crm/help/'.$vtiger_current_version.'/vtiger_CRM_Database_Hostname.pdf" target="_blank">'.$installationStrings['LBL_MORE_INFORMATION'].'</a><BR>
					-  '.$installationStrings['MSG_DB_USER_NOT_AUTHORIZED'];
		} elseif(Common_Install_Wizard_Utils::isMySQL($db_type) && $mysql_server_version < '4.1') {
			$error_msg = $mysql_server_version.' -> '.$installationStrings['ERR_INVALID_MYSQL_VERSION'];
		} elseif(!$old_db_exist_status) {
			$error_msg = $old_db_name.' -> '.$installationStrings['ERR_DATABASE_NOT_FOUND'];
		} elseif(!$new_db_exist_status) {
			$error_msg = $new_db_name.' -> '.$installationStrings['ERR_DATABASE_NOT_FOUND'];
		} elseif(!$new_db_has_tables) {
			$error_msg = $new_db_name.' -> '.$installationStrings['ERR_MIGRATION_DATABASE_IS_EMPTY'];
		} else {			
			$web_root = ($_SERVER["HTTP_HOST"]) ? $_SERVER["HTTP_HOST"]:$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'];
			$web_root .= $_SERVER["REQUEST_URI"];
			$web_root = preg_replace("/\/install.php(.)*/i", "", $web_root);
			$site_URL = "http://".$web_root;
			$configInfo['site_URL'] = $site_URL;
			$dbVerifyResult['config_info'] = $configInfo;
			$dbVerifyResult['flag'] = true;
			return $dbVerifyResult;
		}
		$dbVerifyResult['config_info'] = $configInfo;
		$dbVerifyResult['error_msg'] = $error_msg;
		$dbVerifyResult['error_msg_info'] = $error_msg_info;
		return $dbVerifyResult;
	}	
	
	private static function authenticateUser($dbConnection, $userName,$userPassword){
		global $table_prefix;
		$userResult = $dbConnection->_Execute("SELECT * FROM ".$table_prefix."_users WHERE user_name = '$userName'");
		$noOfRows = $userResult->NumRows($userResult);
		if ($noOfRows > 0) {
			$userInfo = $userResult->GetRowAssoc(0);
			$cryptType = $userInfo['crypt_type'];	
			$userEncryptedPassword = $userInfo['user_password'];
			$userStatus =  $userInfo['status'];
			$isAdmin =  $userInfo['is_admin'];
			
			$computedEncryptedPassword = self::getEncryptedPassword($userName, $cryptType,
					$userPassword);
		
			if($userEncryptedPassword == $computedEncryptedPassword && $userStatus == 'Active' && $isAdmin == 'on'){
				return true;
			}
		}
		return false;		
	}
	
	private static function getNumberOfTables($dbConnection) {
		$metaTablesSql = $dbConnection->metaTablesSQL;
		$noOfTables = 0;	
		if(!empty($metaTablesSql)) {
			$tablesResult = $dbConnection->_Execute($metaTablesSql);
			$noOfTables = $tablesResult->NumRows($tablesResult);
		}
		return $noOfTables;
	}
	
	static function copyRequiredFiles($sourceDirectory, $destinationDirectory) {
		if (realpath($sourceDirectory) == realpath($destinationDirectory)) return;
		@Migration_Utils::getFilesFromFolder($sourceDirectory."user_privileges/",$destinationDirectory."user_privileges/",
								// Force copy these files - Overwrite if they exist in destination directory.
								array($sourceDirectory."user_privileges/default_module_view.php") 
							);	
		@Migration_Utils::getFilesFromFolder($sourceDirectory."storage/",$destinationDirectory."storage/");
		@Migration_Utils::getFilesFromFolder($sourceDirectory."test/contact/",$destinationDirectory."test/contact/");
		@Migration_Utils::getFilesFromFolder($sourceDirectory."test/logo/",$destinationDirectory."test/logo/");
		@Migration_Utils::getFilesFromFolder($sourceDirectory."test/product/",$destinationDirectory."test/product/");
		@Migration_Utils::getFilesFromFolder($sourceDirectory."test/user/",$destinationDirectory."test/user/");
	}

	private static function getFilesFromFolder($source, $dest, $forcecopy=false) {
		if(!$forcecopy) $forcecopy = Array();
		
		if ($handle = opendir($source)) {
			while (false != ($file = readdir($handle))) {
				if (is_file($source.$file)) {
					if(!file_exists($dest.$file) || in_array($source.$file, $forcecopy)){
						$file_handle = fopen($dest.$file,'w');
						fclose($file_handle);
						copy($source.$file, $dest.$file);
					}
				} elseif ($file != '.' && $file != '..' && is_dir($source.$file)) {
					if(!file_exists($dest.$file)) {
						mkdir($dest.$file.'/',0777);
					}
					Migration_Utils::getFilesFromFolder($source.$file.'/', $dest.$file.'/');
				}
			}
		}
		@closedir($handle);
	}
	
	static function getInstallableOptionalModules() {
		$optionalModules = Common_Install_Wizard_Utils::getInstallableModulesFromPackages();
		
		$skipModules = array();
		if(!empty($optionalModules['install'])) $skipModules = array_merge($skipModules,array_keys($optionalModules['install']));
		if(!empty($optionalModules['update'])) $skipModules = array_merge($skipModules,array_keys($optionalModules['update']));
		
		$customModules = Migration_Utils::getCustomModulesFromDB($skipModules);
		
		$optionalModules = array_merge($optionalModules, $customModules);
		return $optionalModules;
	}
	
	// crmv@151405
	static function getInstallableBetaModules() {
		$optionalModules = Common_Install_Wizard_Utils::getInstallableBetaModulesFromPackages();
		return $optionalModules;
	}
	// crmv@151405e
	
	static function getCustomModulesFromDB($skipModules) {		
		global $optionalModuleStrings, $adb,$table_prefix;
		
		require_once('vtlib/Vtiger/Package.php');
		require_once('vtlib/Vtiger/Module.php');
		require_once('vtlib/Vtiger/Version.php');
		
		$customModulesResult = $adb->pquery('SELECT tabid, name FROM '.$table_prefix.'_tab WHERE customized=1 AND 
											name NOT IN ('. generateQuestionMarks($skipModules).')', $skipModules);
		$noOfCustomModules = $adb->num_rows($customModulesResult);
		$customModules = array();
		for($i=0;$i<$noOfCustomModules;++$i) {
			$tabId = $adb->query_result($customModulesResult,$i,'tabid');
			$moduleName = $adb->query_result($customModulesResult,$i,'name');
			$moduleDetails = array();
			$moduleDetails['description'] = $optionalModuleStrings[$moduleName.'_description'];
			$moduleDetails['selected'] = false;
			$moduleDetails['enabled'] = false;

			if(Vtiger_Utils::checkTable($table_prefix.'_tab_info')) {
				$tabInfo = getTabInfo($tabId);
				if(Vtiger_Version::check($tabInfo['vtiger_min_version'],'>=') && Vtiger_Version::check($tabInfo['vtiger_max_version'],'<')) {
					$moduleDetails['selected'] = true;
					$moduleDetails['enabled'] = false;
				}				
			}
			$customModules['copy'][$moduleName] = $moduleDetails;
		}
		return $customModules;
	}

	// Function to install Vtlib Compliant - Optional Modules
	static function installOptionalModules($selectedModules, $sourceDirectory, $destinationDirectory){
		Migration_Utils::copyCustomModules($selectedModules, $sourceDirectory, $destinationDirectory);
		Common_Install_Wizard_Utils::installSelectedOptionalModules($selectedModules, $sourceDirectory, $destinationDirectory);
	}
	
	// crmv@151405
	static function installBetaModules($selectedModules, $sourceDirectory, $destinationDirectory){
		Migration_Utils::copyCustomModules($selectedModules, $sourceDirectory, $destinationDirectory);
		Common_Install_Wizard_Utils::installSelectedBetaModules($selectedModules, $sourceDirectory, $destinationDirectory);
	}
	// crmv@151405e
	
	private static function copyCustomModules($selectedModules, $sourceDirectory, $destinationDirectory) {		
		global $adb,$table_prefix;
		$selectedModules = explode(":",$selectedModules);
		
		$customModulesResult = $adb->pquery('SELECT tabid, name FROM '.$table_prefix.'_tab WHERE customized = 1', array());
		$noOfCustomModules = $adb->num_rows($customModulesResult);
		for($i=0;$i<$noOfCustomModules;++$i) {
			$moduleName = $adb->query_result($customModulesResult,$i,'name');					
			Migration_Utils::copyModuleFiles($moduleName, $sourceDirectory, $destinationDirectory);
			if(!in_array($moduleName,$selectedModules)) {
				vtlib_toggleModuleAccess((string)$moduleName, false);
			}
		}
	}
	
	static function copyModuleFiles($moduleName, $sourceDirectory, $destinationDirectory) {
		$sourceDirectory = realpath($sourceDirectory);
		$destinationDirectory = realpath($destinationDirectory);
		if (!empty($moduleName) && !empty($sourceDirectory) && !empty($destinationDirectory) && $sourceDirectory != $destinationDirectory) {
			if(file_exists("$sourceDirectory/modules/$moduleName")) {
				if(!file_exists("$destinationDirectory/modules/$moduleName")) {
					mkdir("$destinationDirectory/modules/$moduleName".'/',0777);
				}
				Migration_Utils::getFilesFromFolder("{$sourceDirectory}/modules/$moduleName/","{$destinationDirectory}/modules/$moduleName/");
			}
			if(file_exists("$sourceDirectory/Smarty/templates/modules/$moduleName")) {
				if(!file_exists("$destinationDirectory/Smarty/templates/modules/$moduleName")) {
					mkdir("$destinationDirectory/Smarty/templates/modules/$moduleName".'/',0777);
				}
				Migration_Utils::getFilesFromFolder("{$sourceDirectory}/Smarty/templates/modules/$moduleName/","{$destinationDirectory}/Smarty/templates/modules/$moduleName/");
			}
			if(file_exists("$sourceDirectory/cron/modules/$moduleName")) {
				if(!file_exists("$destinationDirectory/cron/modules/$moduleName")) {
					mkdir("$destinationDirectory/cron/modules/$moduleName".'/',0777);
				}
				Migration_Utils::getFilesFromFolder("{$sourceDirectory}/cron/modules/$moduleName/","{$destinationDirectory}/cron/modules/$moduleName/");
			}
		}
	}
	
	function migrate($migrationInfo){
		global $installationStrings,$table_prefix;
		$completed = false;
		
		set_time_limit(0);//ADDED TO AVOID UNEXPECTED TIME OUT WHILE MIGRATING
		
		global $dbconfig;
		require ($migrationInfo['root_directory'] . '/config.inc.php');
		$dbtype		= $dbconfig['db_type'];
		$host		= $dbconfig['db_server'].$dbconfig['db_port'];
		$dbname		= $dbconfig['db_name'];
		$username	= $dbconfig['db_username'];
		$passwd		= $dbconfig['db_password'];
				
		global $adb,$migrationlog;
		$adb = new PearDatabase($dbtype,$host,$dbname,$username,$passwd);
		//crmv@alter db only mysql
		if (Common_Install_Wizard_Utils::isMySQL($dbtype)){
			$query = " ALTER DATABASE ".$adb->escapeDbName($dbname)." DEFAULT CHARACTER SET utf8";
			$adb->query($query);
		}
		//crmv@alter db only mysql end
		$source_directory = $migrationInfo['source_directory'];
		if(file_exists($source_directory.'user_privileges/CustomInvoiceNo.php')) {
			require_once($source_directory.'user_privileges/CustomInvoiceNo.php');
		}
	
		$migrationlog =& LoggerManager::getLogger('MIGRATION');
		if (isset($migrationInfo['old_version'])) $source_version = $migrationInfo['old_version'];
		if(!isset($source_version) || empty($source_version)) {
			//If source version is not set then we cannot proceed
			echo "<br> ".$installationStrings['LBL_SOURCE_VERSION_NOT_SET'];
			exit;
		}
	
		$reach = 0;
		include($migrationInfo['root_directory']."/modules/Migration/versions.php");
		foreach($versions as $version => $label) {
			if($version == $source_version || $reach == 1) {
				$reach = 1;
				$temp[] = $version;
			}
		}
		$temp[] = $current_version;

		global $adb, $dbname;
		$_SESSION['adodb_current_object'] = $adb;
		
		@ini_set('zlib.output_compression', 0);
		@ini_set('output_buffering','off');
		ob_implicit_flush(true);
		echo '<table width="98%" border="1px" cellpadding="3" cellspacing="0" height="100%">';
		if(is_array($_SESSION['migration_info']['user_messages'])) {
			foreach ($_SESSION['migration_info']['user_messages'] as $infoMap) {
				echo "<tr><td>".$infoMap['status']."</td><td>".$infoMap['msg']."</td></tr>";
			}
		}
		echo "<tr><td colspan='2'><b>{$installationStrings['LBL_GOING_TO_APPLY_DB_CHANGES']}...</b></td></tr>";
	
		for($patch_count=0;$patch_count<count($temp);$patch_count++) {
			//Here we have to include all the files (all db differences for each release will be included)
			$filename = "modules/Migration/DBChanges/".$temp[$patch_count]."_to_".$temp[$patch_count+1].".php";
			$empty_tag = "<tr><td colspan='2'>&nbsp;</td></tr>";
			$start_tag = "<tr><td colspan='2'><b><font color='red'>&nbsp;";
			$end_tag = "</font></b></td></tr>";
	
			if(is_file($filename)) {
				echo $empty_tag.$start_tag.$temp[$patch_count]." ==> ".$temp[$patch_count+1]. " " .$installationStrings['LBL_DATABASE_CHANGES'] ." -- ". $installationStrings['LBL_STARTS'] .".".$end_tag;
		
				include($filename);//include the file which contains the corresponding db changes
		
				echo $start_tag.$temp[$patch_count]." ==> ".$temp[$patch_count+1]. " " .$installationStrings['LBL_DATABASE_CHANGES'] ." -- ". $installationStrings['LBL_ENDS'] .".".$end_tag;
			}
		}	
		//crmv@vte migrazione
		$filename = "modules/Migration/DBChanges/last_step.php";
		$label = 'Last Step';
		if(is_file($filename)) {
				$empty_tag = "<tr><td colspan='2'>&nbsp;</td></tr>";
				$start_tag = "<tr><td colspan='2'><b><font color='red'>&nbsp;";
				$end_tag = "</font></b></td></tr>";			
				echo $empty_tag.$start_tag.$label. " " .$installationStrings['LBL_DATABASE_CHANGES'] ." -- ". $installationStrings['LBL_STARTS'] .".".$end_tag;
		
				include($filename);//include the file which contains the corresponding db changes
		
				echo $start_tag.$label. " " .$installationStrings['LBL_DATABASE_CHANGES'] ." -- ". $installationStrings['LBL_ENDS'] .".".$end_tag;
		}
		//crmv@vte migrazione end
		//Here we have to update the version in table. so that when we do migration next time we will get the version
		$res = $adb->query('SELECT * FROM '.$table_prefix.'_version');
		global $enterprise_current_version;
		require($migrationInfo['root_directory'].'/vtigerversion.php');
		if($adb->num_rows($res)) {
			$res = ExecuteQuery("UPDATE ".$table_prefix."_version SET old_version='$versions[$source_version]',current_version='$vtiger_current_version'");
			$completed = true;
		} else {
			ExecuteQuery("INSERT INTO ".$table_prefix."_version (id, old_version, current_version) values (".$adb->getUniqueID($table_prefix.'_version').", '$versions[$source_version]', '$vtiger_current_version');");
			$completed = true;
		}
		echo '</table><br><br>';
		return $completed;
	}

	public static function resetUserPasswords($con) {
		global $table_prefix;
		$sql = 'select user_name, id, crypt_type from '.$table_prefix.'_users';
		$result = $con->_Execute($sql, false);
		$rowList = $result->GetRows();
		foreach ($rowList as $row) {
			$cryptType = $row['crypt_type'];
			if(strtolower($cryptType) == 'md5' && version_compare(PHP_VERSION, '5.3.0') >= 0) {
				$cryptType = 'PHP5.3MD5';
			}
			$encryptedPassword = self::getEncryptedPassword($row['user_name'], $cryptType,
					$row['user_name']);
			$userId = $row['id'];
			$sql = "update ".$table_prefix."_users set user_password=?,crypt_type=? where id=?";
			$updateResult = $con->Execute($sql, array($encryptedPassword, $cryptType, $userId));
			if(!is_object($updateResult)) {
				$_SESSION['migration_info']['user_messages'][] = array(
					'status' => "<span style='color: red;font-weight: bold'>Failed: </span>",
					'msg' => "$sql<br />".var_export(array($encryptedPassword, $userId))
				);
			}
		}
	}
	public static function resetUserPasswords2($con) {
		global $table_prefix;
		$sql = 'select user_name, id, crypt_type from '.$table_prefix.'_users';
		$result = $con->query($sql);
		while ($row = $con->FetchByAssoc($result,-1,false)) {
			$cryptType = $row['crypt_type'];
			if(strtolower($cryptType) == 'md5' && version_compare(PHP_VERSION, '5.3.0') >= 0) {
				$cryptType = 'PHP5.3MD5';
			}
			$encryptedPassword = self::getEncryptedPassword($row['user_name'], $cryptType,
					$row['user_name']);
			$userId = $row['id'];
			$sql = "update ".$table_prefix."_users set user_password=?,crypt_type=? where id=?";
			$updateResult = $con->pquery($sql, array($encryptedPassword, $cryptType, $userId));
			if(!is_object($updateResult)) {
				$_SESSION['migration_info']['user_messages'][] = array(
					'status' => "<span style='color: red;font-weight: bold'>Failed: </span>",
					'msg' => "$sql<br />".var_export(array($encryptedPassword, $userId))
				);
			}
		}
	}

	public static function getEncryptedPassword($userName, $cryptType, $userPassword) {
		$salt = substr($userName, 0, 2);
		// For more details on salt format look at: http://in.php.net/crypt
		if($cryptType == 'MD5') {
			$salt = '$1$' . $salt . '$';
		} elseif($cryptType == 'BLOWFISH') {
			$salt = '$2$' . $salt . '$';
		} elseif($cryptType == 'PHP5.3MD5') {
			//only change salt for php 5.3 or higher version for backward
			//compactibility.
			//crypt API is lot stricter in taking the value for salt.
			$salt = '$1$' . str_pad($salt, 9, '0');
		}
		$computedEncryptedPassword = crypt($userPassword, $salt);
		return $computedEncryptedPassword;
	}
}

class ConfigFile_Utils {
	
	private $rootDirectory;
	private $dbHostname;
	private $dbPort;
	private $dbUsername;
	private $dbPassword;
	private $dbName;
	private $dbType;
	private $siteUrl;
	private $cacheDir;
	private $vtCharset;
	private $currencyName;
	private $adminEmail;
	
	function __construct($configFileParameters) {
		if (isset($configFileParameters['root_directory']))
			$this->rootDirectory = $configFileParameters['root_directory'];
			
		if (isset($configFileParameters['db_hostname'])) {
			//crmv@fix connection string
			if ($_REQUEST['mode'] == 'migration') {
				$this->dbHostname = $configFileParameters['db_server'];
			} else {
				$this->dbHostname = $configFileParameters['db_hostname'];
			}
			$this->dbPort = $configFileParameters['db_hostport'];
			//crmv@fix connection string end
		}
		if (isset($configFileParameters['db_username'])) $this->dbUsername = $configFileParameters['db_username'];
		if (isset($configFileParameters['db_password'])) $this->dbPassword = $configFileParameters['db_password'];
		if (isset($configFileParameters['db_name'])) $this->dbName = $configFileParameters['db_name'];
		if (isset($configFileParameters['db_type'])) $this->dbType = $configFileParameters['db_type'];
		if (isset($configFileParameters['site_URL'])) $this->siteUrl = $configFileParameters['site_URL']; 
		if (isset($configFileParameters['admin_email'])) $this->adminEmail = $configFileParameters['admin_email'];
		if (isset($configFileParameters['currency_name'])) $this->currencyName = $configFileParameters['currency_name'];
		if (isset($configFileParameters['vt_charset'])) $this->vtCharset = $configFileParameters['vt_charset'];
		//crmv@fix connection string
		// update default port with the right separator
		if ($this->dbPort)
			$this->dbPort = ConfigFile_Utils::getDbDefaultPortSeparator($this->dbType).$this->dbPort;
		else
			$this->dbPort = ConfigFile_Utils::getDbDefaultPortSeparator($this->dbType).ConfigFile_Utils::getDbDefaultPort($this->dbType);
		//crmv@fix connection string end
		$this->cacheDir = 'cache/';
	}
	//crmv@add mssql support
	static function getDbDefaultPort($dbType) {
		if(Common_Install_Wizard_Utils::isMySQL($dbType)) {
			return "3306";
		}
		if(Common_Install_Wizard_Utils::isPostgres($dbType)) {
			return "5432";
		}
		if(Common_Install_Wizard_Utils::isOracle($dbType)) {
			return '1521';
		}
		if(Common_Install_Wizard_Utils::isMssql($dbType)) {
			return '1433';
		}
	}
	//crmv@add mssql support end
	//crmv@fix connection string
	static function getDbDefaultPortSeparator($dbType) {
		if(Common_Install_Wizard_Utils::isMySQL($dbType)) {
			return ":";
		}
		if(Common_Install_Wizard_Utils::isPostgres($dbType)) {
			return ":";
		}
		if(Common_Install_Wizard_Utils::isOracle($dbType)) {
			return ":";
		}
		if(Common_Install_Wizard_Utils::isMssql($dbType)) {
			//crmv@57238
			if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
				return ",";
			}else{
				return ":";
			}
			//crmv@57238e
		}
	}
	//crmv@fix connection string end
	function createConfigFile() {

		if (is_file('config.inc.php'))
		    $is_writable = is_writable('config.inc.php');
		else
			$is_writable = is_writable('.');
	
		/* open template configuration file read only */
		$templateFilename = 'config.template.php';
		$templateHandle = fopen($templateFilename, "r");
		if($templateHandle) {
			/* open include configuration file write only */
			$includeFilename = 'config.inc.php';
	      	$includeHandle = fopen($includeFilename, "w");
			if($includeHandle) {
			   	while (!feof($templateHandle)) {
	  				$buffer = fgets($templateHandle);
	
		 			/* replace _DBC_ variable */
		  			$buffer = str_replace( "_DBC_SERVER_", $this->dbHostname, $buffer);
		  			$buffer = str_replace( "_DBC_PORT_", $this->dbPort, $buffer);
		  			$buffer = str_replace( "_DBC_USER_", $this->dbUsername, $buffer);
		  			$buffer = str_replace( "_DBC_PASS_", $this->dbPassword, $buffer);
		  			$buffer = str_replace( "_DBC_NAME_", $this->dbName, $buffer);
		  			$buffer = str_replace( "_DBC_TYPE_", $this->dbType, $buffer);
		
		  			$buffer = str_replace( "_SITE_URL_", $this->siteUrl, $buffer);
		
		  			/* replace dir variable */
		  			$buffer = str_replace( "_VT_ROOTDIR_", $this->rootDirectory, $buffer);
		  			$buffer = str_replace( "_VT_CACHEDIR_", $this->cacheDir, $buffer);
		  			$buffer = str_replace( "_VT_TMPDIR_", $this->cacheDir."images/", $buffer);
		  			$buffer = str_replace( "_VT_UPLOADDIR_", $this->cacheDir."upload/", $buffer);
			      	$buffer = str_replace( "_DB_STAT_", "true", $buffer);
			      	//crmv@add db options
			      	$buffer = str_replace( "_DB_CHARSET_", "utf8", $buffer);
			      	$buffer = str_replace( "_DB_DIEONERROR_", false, $buffer);
					//crmv@add db options end
					/* replace charset variable */
					$buffer = str_replace( "_VT_CHARSET_", $this->vtCharset, $buffer);
		
			      	/* replace master currency variable */
		  			$buffer = str_replace( "_MASTER_CURRENCY_", $this->currencyName, $buffer);
		
			      	/* replace the application unique key variable */
		      		// crmv@167234
					$string = time().rand(1,9999999).md5($this->rootDirectory);
		      		$buffer = str_replace( "_VT_APP_UNIQKEY_", md5($string) , $buffer);
					// crmv@167234e
					
					$buffer = str_replace( "_CSRF_SECRET_", $this->csrf_generate_secret(), $buffer); // crmv@171581
					
					/* replace support email variable */
					$buffer = str_replace( "_USER_SUPPORT_EMAIL_", $this->adminEmail, $buffer);
					
					if ($_REQUEST['mode'] == 'migration') {
						$buffer = str_replace( "\$table_prefix = 'vte';", "\$table_prefix = 'vtiger';", $buffer);
					}
					
					// crmv@195213
					$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
					$cf_prefix = '';
					for ($i = 0; $i < 3; $i++) {
						$index = rand(0, strlen($characters) - 1);
						$cf_prefix .= $characters[$index];
					}
					$buffer = str_replace( "_CF_PREFIX_", $cf_prefix, $buffer);
					// crmv@195213e
		
		      		fwrite($includeHandle, $buffer);
	      		}	
	  			fclose($includeHandle);
	  		}	
	  		fclose($templateHandle);
	  	}
	  	
	  	if ($templateHandle && $includeHandle) { 
	  		return true;
	  	} 
	  	return false;
	}
	
	// crmv@171581
	public function csrf_generate_secret($len = 32) {
		$r = '';
		for ($i = 0; $i < $len; $i++) {
			$r .= chr(mt_rand(0, 255));
		}
		$r .= time() . microtime();
		return sha1($r);
	}
	// crmv@171581e
	
	// crmv@178158 - removed unused function
}

class Common_Install_Wizard_Utils {
	
	public static $login_expire_time = 2592000; // crmv@27520 (one month)
	
	public static $recommendedDirectives = array (
		'safe_mode' => 'Off',
		'display_errors' => 'On',
		'file_uploads' => 'On',
		'register_globals' => 'On',
		'output_buffering' => 'On',
		'max_execution_time' => '600',
		'memory_limit' => '128',
		'error_reporting' => 'E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED', // crmv@146653
		'log_errors' => 'On',	//crmv@146653
		'mod_rewrite' => 'On',	//crmv@24713m
		'APCu' => 'On', // crmv@181165
		'BCMath' => 'On', // crmv@171524
		//'session.gc_maxlifetime' => 2592000,  // crmv@27520 (one month)
	);
	
	// crmv@127567 crmv@140903
	public static $writableFilesAndFolders = array (
		'Configuration File' => './config.inc.php',
		'Installation File' => './install.php',
		'Cache Directory' => './cache/',
		'Image Cache Directory' => './cache/images/',
		'Import Cache Directory' => './cache/import/',
		'Vtlib Cache Directory' => './cache/vtlib/',
		'Vtlib Cache HTML Directory' => './cache/vtlib/HTML',
		'Storage Directory' => './storage/',
		'Install Directory' => './install/',
		'User Privileges Directory' => './user_privileges/',
		'Smarty Cache Directory' => './Smarty/cache/',
		'Smarty Compile Directory' => './Smarty/templates_c/',
		'Email Templates Directory' => './modules/Emails/templates/',
		'Modules Directory' => './modules/',
		'Cron Modules Directory' => './cron/modules/',
		'Backup Directory' => './backup/',
		'Smarty Modules Directory' => './Smarty/templates/modules/',
		'Logo Directory' => './storage/logo/',
		'Logs Directory' => './logs/',
		'SmartOptimizer Cache Directory' => './smartoptimizer/cache/',	//crmv@24713m
	);
	// crmv@127567e crmv@140903e
	
	public static $gdInfoAlternate = 'function gd_info() {
		$array = Array(
	               "GD Version" => "",
	               "FreeType Support" => 0,
	               "FreeType Support" => 0,
	               "FreeType Linkage" => "",
	               "T1Lib Support" => 0,
	               "GIF Read Support" => 0,
	               "GIF Create Support" => 0,
	               "JPG Support" => 0,
	               "PNG Support" => 0,
	               "WBMP Support" => 0,
	               "XBM Support" => 0
	             );
		       $gif_support = 0;
		
		       ob_start();
		       eval("phpinfo();");
		       $info = ob_get_contents();
		       ob_end_clean();
		
		       foreach(explode("\n", $info) as $line) {
		           if(strpos($line, "GD Version")!==false)
		               $array["GD Version"] = trim(str_replace("GD Version", "", strip_tags($line)));
		           if(strpos($line, "FreeType Support")!==false)
		               $array["FreeType Support"] = trim(str_replace("FreeType Support", "", strip_tags($line)));
		           if(strpos($line, "FreeType Linkage")!==false)
		               $array["FreeType Linkage"] = trim(str_replace("FreeType Linkage", "", strip_tags($line)));
		           if(strpos($line, "T1Lib Support")!==false)
		               $array["T1Lib Support"] = trim(str_replace("T1Lib Support", "", strip_tags($line)));
		           if(strpos($line, "GIF Read Support")!==false)
		               $array["GIF Read Support"] = trim(str_replace("GIF Read Support", "", strip_tags($line)));
		           if(strpos($line, "GIF Create Support")!==false)
		               $array["GIF Create Support"] = trim(str_replace("GIF Create Support", "", strip_tags($line)));
		           if(strpos($line, "GIF Support")!==false)
		               $gif_support = trim(str_replace("GIF Support", "", strip_tags($line)));
		           if(strpos($line, "JPG Support")!==false)
		               $array["JPG Support"] = trim(str_replace("JPG Support", "", strip_tags($line)));
		           if(strpos($line, "PNG Support")!==false)
		               $array["PNG Support"] = trim(str_replace("PNG Support", "", strip_tags($line)));
		           if(strpos($line, "WBMP Support")!==false)
		               $array["WBMP Support"] = trim(str_replace("WBMP Support", "", strip_tags($line)));
		           if(strpos($line, "XBM Support")!==false)
		               $array["XBM Support"] = trim(str_replace("XBM Support", "", strip_tags($line)));
		       }
		
		       if($gif_support==="enabled") {
		           $array["GIF Read Support"]  = 1;
		           $array["GIF Create Support"] = 1;
		       }
		
		       if($array["FreeType Support"]==="enabled"){
		           $array["FreeType Support"] = 1;    }
		
		       if($array["T1Lib Support"]==="enabled")
		           $array["T1Lib Support"] = 1;
		
		       if($array["GIF Read Support"]==="enabled"){
		           $array["GIF Read Support"] = 1;    }
		
		       if($array["GIF Create Support"]==="enabled")
		           $array["GIF Create Support"] = 1;
		
		       if($array["JPG Support"]==="enabled")
		           $array["JPG Support"] = 1;
		
		       if($array["PNG Support"]==="enabled")
		           $array["PNG Support"] = 1;
		
		       if($array["WBMP Support"]==="enabled")
		           $array["WBMP Support"] = 1;
		
		       if($array["XBM Support"]==="enabled")
		           $array["XBM Support"] = 1;
		
		       return $array;
		
		}';
		
	function getRecommendedDirectives() {
		return self::$recommendedDirectives;
	}		
	
	/** Function to check the file access is made within web root directory. */
	static function checkFileAccess($filepath) {
		global $root_directory, $installationStrings;
		// Set the base directory to compare with
		$use_root_directory = $root_directory;
		if(empty($use_root_directory)) {
			$use_root_directory = realpath(dirname(__FILE__).'/../../..');
		}
	
		$realfilepath = realpath($filepath);
	
		/** Replace all \\ with \ first */
		$realfilepath = str_replace('\\\\', '\\', $realfilepath);
		$rootdirpath  = str_replace('\\\\', '\\', $use_root_directory);
	
		/** Replace all \ with / now */
		$realfilepath = str_replace('\\', '/', $realfilepath);
		$rootdirpath  = str_replace('\\', '/', $rootdirpath);
		
		if(stripos($realfilepath, $rootdirpath) !== 0) {
			die($installationStrings['ERR_RESTRICTED_FILE_ACCESS']);
		}
	}
	
	static function getFailedPermissionsFiles() {
		$writableFilesAndFolders = Common_Install_Wizard_Utils::$writableFilesAndFolders;
		$failedPermissions = array();
		require_once ('include/utils/VtlibUtils.php');
		foreach ($writableFilesAndFolders as $index => $value) {
			if (!vtlib_isWriteable($value)) {
				$failedPermissions[$index] = $value;
			}
		}
		return $failedPermissions;
	}
	
	static function getCurrentDirectiveValue() {
		$directiveValues = array();
		if (ini_get('safe_mode') == '1' || stripos(ini_get('safe_mode'), 'On') > -1)
			$directiveValues['safe_mode'] = 'On';
		if (ini_get('display_errors') != '1' || stripos(ini_get('display_errors'), 'Off') > -1)
			$directiveValues['display_errors'] = 'Off';
		if (ini_get('file_uploads') != '1' || stripos(ini_get('file_uploads'), 'Off') > -1)
			$directiveValues['file_uploads'] = 'Off';
		if (ini_get('register_globals') == '1' || stripos(ini_get('register_globals'), 'On') > -1)
			$directiveValues['register_globals'] = 'On';
		if (ini_get(('output_buffering') < '4096' && ini_get('output_buffering') != '0') || stripos(ini_get('output_buffering'), 'Off') > -1)
			$directiveValues['output_buffering'] = 'Off';
		if (ini_get('max_execution_time') < 600)
			$directiveValues['max_execution_time'] = ini_get('max_execution_time');
		if (ini_get('memory_limit') < 128)
			$directiveValues['memory_limit'] = ini_get('memory_limit');
		$errorReportingValue = E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT; // crmv@146653
		if (ini_get('error_reporting') != $errorReportingValue)
			$directiveValues['error_reporting'] = 'NOT RECOMMENDED';
		
		// crmv@146653
		if (ini_get('log_errors') == '' || ini_get('log_errors') == '0' || stripos(ini_get('log_errors'), 'Off') > -1)
			$directiveValues['log_errors'] = 'Off';
		// crmv@146653e
		//crmv@27520
//		if (ini_get('session.gc_maxlifetime') < self::$login_expire_time)
//			$directiveValues['session.gc_maxlifetime'] = ini_get('session.gc_maxlifetime');
		//crmv@27520e
		//crmv@24713m crmv@187692
		$mod_rewrite = false;
		$modules = array();
		if (function_exists('apache_get_modules')) {
			$modules = apache_get_modules();
		} else {
			$out = array();
			$ret = 0;
			exec('apachectl -M', $out, $ret);
			if ($ret == 0) {
				foreach ($out as $line) {
					if ($line[0] == ' ') {
						$modname = trim(preg_replace('/\(.*\)/', '', $line));
						$modname = 'mod_'.str_replace('_module', '', $modname);
						$modules[] = $modname;
					}
				}
			}
		}
		if (is_array($modules) && count($modules) > 0) {
			$mod_rewrite = in_array('mod_rewrite', $modules);
		} else {
			$mod_rewrite =  getenv('HTTP_MOD_REWRITE')=='On' ? true : false ;
		}
		if (!$mod_rewrite) {
			$directiveValues['mod_rewrite'] = 'Off';
		}
		//crmv@24713me crmv@187692e
		// crmv@181165
		if (!function_exists('apcu_add')) {
			$directiveValues['APCu'] = 'Off';
		}
		// crmv@181165e
		// crmv@171524
		if (!function_exists('bcadd')) {
			$directiveValues['BCMath'] = 'Off';
		}
		// crmv@171524e
		return $directiveValues;
	}
	// Fix for ticket 6605 : detect mysql extension during installation
	static function check_mysql_extension() {
		//crmv@98338
		if (function_exists('mysqli_connect') && version_compare(PHP_VERSION, '5.5') >= 0) {
			$mysql_extension = true;
		}
		elseif(function_exists('mysql_connect')) {
		//crmv@98338e
			$mysql_extension = true;
		}
		else {
			$mysql_extension = false;
		}
		return $mysql_extension;
	}
	
	static function isMySQL($dbType) { 
		return (stripos($dbType ,'mysql') === 0);
	}
	
    static function isOracle($dbType) { 
    	return (stripos($dbType ,'oci8') === 0); 
    }
    //crmv@add mssql support
    static function isMssql($dbType) { 
    	return (stripos($dbType ,'mssql') === 0); 
    }
    //crmv@add mssql support end
    static function isPostgres($dbType) { 
    	return $dbType == 'pgsql'; 
    }
	
	// crmv@151405
	
	public static function getInstallableModulesFromPackages() {
		$packageDir = 'packages/vte/optional/';
		$optionalModules = (array) self::getInstallableModulesFromDirectory($packageDir);
		
		return $optionalModules;
	}

	public static function getInstallableBetaModulesFromPackages() {
		$packageDir = 'packages/vte/beta/vte/';
		$betaModules = (array) self::getInstallableModulesFromDirectory($packageDir);
		
		foreach ($betaModules as $option => &$modules) {
			if (is_array($modules)) {
				foreach ($modules as $module => &$details) {
					$details['selected'] = false;
					unset($details);
				}
			}
			unset($modules);
		}
		
		return $betaModules;
	}
	
	public static function getInstallableModulesFromDirectory($packageDir) {
		global $optionalModuleStrings;
		global $install_tmp;
		$install_tmp = true;
		require_once('vtlib/Vtiger/Package.php');
		require_once('vtlib/Vtiger/Module.php');
		require_once('vtlib/Vtiger/Version.php');

		$handle = opendir($packageDir);
		$installableModules = array();
		while (false !== ($file = readdir($handle))) {
			$packageNameParts = explode(".", $file);
			
			if ($packageNameParts[count($packageNameParts) - 1] != 'zip') {
				continue;
			}
			
			array_pop($packageNameParts);
			$packageName = implode("", $packageNameParts);
			
			if (!empty($packageName)) {
				$packagepath = $packageDir.$file;
				$package = new Vtiger_Package();
				$moduleName = $package->getModuleNameFromZip($packagepath);
				
				if ($package->isModuleBundle()) {
					$bundleOptionalModule = array();
					$unzip = new Vtiger_Unzip($packagepath);
					$unzip->unzipAllEx($package->getTemporaryFilePath());
					$moduleInfoList = $package->getAvailableModuleInfoFromModuleBundle();
					
					foreach ($moduleInfoList as $moduleInfo) {
						$moduleInfo = (array) $moduleInfo;
						$packagepath = $package->getTemporaryFilePath($moduleInfo['filepath']);
						$subModule = new Vtiger_Package();
						$subModule->getModuleNameFromZip($packagepath);
						$bundleOptionalModule = self::getOptionalModuleDetails($subModule, $bundleOptionalModule);
					}
					
					$moduleDetails = array();
					$moduleDetails['description'] = $optionalModuleStrings[$moduleName . '_description'];
					$moduleDetails['selected'] = true;
					$moduleDetails['enabled'] = true;
					
					$migrationAction = 'install';
					if (count($bundleOptionalModule['update']) > 0) {
						$moduleDetails['enabled'] = false;
						$migrationAction = 'update';
					}
					
					$installableModules[$migrationAction]['module'][$moduleName] = $moduleDetails;
				} else {
					if ($package->isLanguageType()) {
						$package = new Vtiger_Language();
						$package->getModuleNameFromZip($packagepath);
					}
					
					$installableModules = self::getOptionalModuleDetails($package, $installableModules);
				}
			}
		}
		
		if (is_array($installableModules['install']['language']) && is_array($installableModules['install']['module'])) {
			$installableModules['install'] = array_merge($installableModules['install']['module'], $installableModules['install']['language']);
		} elseif (is_array($installableModules['install']['language']) && !is_array($installableModules['install']['module'])) {
			$installableModules['install'] = $installableModules['install']['language'];
		} else {
			$installableModules['install'] = $installableModules['install']['module'];
		}
		
		if (is_array($installableModules['update']['language']) && is_array($installableModules['update']['module'])) {
			$installableModules['update'] = array_merge($installableModules['update']['module'], $installableModules['update']['language']);
		} elseif (is_array($installableModules['update']['language']) && !is_array($installableModules['update']['module'])) {
			$installableModules['update'] = $installableModules['update']['language'];
		} else {
			$installableModules['update'] = $installableModules['update']['module'];
		}
		
		return $installableModules;
	}
	
	// crmv@151405e
	
	/**
	 *
	 * @param String $packagepath - path to the package file.
	 * @return Array
	 */
	static function getOptionalModuleDetails($package, $optionalModulesInfo) {
		global $optionalModuleStrings,$table_prefix;
		
		$moduleUpdateVersion = $package->getVersion();
		$moduleForVtigerVersion = $package->getDependentVtigerVersion();
		$moduleMaxVtigerVersion = $package->getDependentMaxVtigerVersion();
		if($package->isLanguageType()) {
			$type = 'language';
		} else {
			$type = 'module';
		}
		$moduleDetails = null;
		$moduleName = $package->getModuleName();
		if($moduleName != null) {
			$moduleDetails = array();
			$moduleDetails['description'] = $optionalModuleStrings[$moduleName.'_description'];

			if(Vtiger_Version::check($moduleForVtigerVersion,'>=') && ($moduleMaxVtigerVersion == '' || Vtiger_Version::check($moduleMaxVtigerVersion,'<'))) {
				$moduleDetails['selected'] = true;
				$moduleDetails['enabled'] = true;
			} else {
				$moduleDetails['selected'] = false;
				$moduleDetails['enabled'] = false;
			}

			$migrationAction = 'install';
			if(!$package->isLanguageType()) {
				$moduleInstance = null;
				if(Vtiger_Utils::checkTable($table_prefix.'_tab')) {
					$moduleInstance = Vtiger_Module::getInstance($moduleName);
				}
				if($moduleInstance) {
					$migrationAction = 'update';
					if(version_compare($moduleUpdateVersion, $moduleInstance->version, '>=')) {
						$moduleDetails['enabled'] = false;
					}
				}
			} else {
				if(Vtiger_Utils::CheckTable($table_prefix.Vtiger_Language::TABLENAME)) {
					$languageList = array_keys(Vtiger_Language::getAll());
					$prefix = $package->getPrefix();
					if(in_array($prefix, $languageList)) {
						$migrationAction = 'update';
					}
				}
			}
			$optionalModulesInfo[$migrationAction][$type][$moduleName] = $moduleDetails;
		}
		return $optionalModulesInfo;
	}	
	
	// Function to install/update mandatory modules
	public static function installMandatoryModules($skip_modules=array()) {
		require_once('vtlib/Vtiger/Package.php');
		require_once('vtlib/Vtiger/Module.php');
		require_once('include/utils/utils.php');
		//crmv@change packets path
		if ($handle = opendir('packages/vte/mandatory')) {		 
		//crmv@change packets path end	   
		    while (false !== ($file = readdir($handle))) {
				$packageNameParts = explode(".",$file);
				if($packageNameParts[count($packageNameParts)-1] != 'zip'){
					continue;
				}
				array_pop($packageNameParts);
				$packageName = implode("",$packageNameParts);
		        if (!empty($packageName)) {
		        	//crmv@cahnge path
		        	$packagepath = "packages/vte/mandatory/$file";
		        	//crmv@cahnge path end
					$package = new Vtiger_Package();
	        		$module = $package->getModuleNameFromZip($packagepath);
	        		if($module != null) {
	        			if (!empty($skip_modules) && in_array($module,$skip_modules)) {
	        				continue;
	        			}
	        			$moduleInstance = Vtiger_Module::getInstance($module);
				        if($moduleInstance) {
		        			updateVtlibModule($module, $packagepath);
		        		} else {
		        			installVtlibModule($packageName, $packagepath);
		        		}
	        		}
		        }
		    }
		    closedir($handle);
		}
	}

	public static function getMandatoryModuleList() {
		require_once('vtlib/Vtiger/Package.php');
		require_once('vtlib/Vtiger/Module.php');
		require_once('include/utils/utils.php');

		$moduleList = array();
		//crmv@change packets path
		if ($handle = opendir('packages/vte/mandatory')) {
		//crmv@change packets path end	
		    while (false !== ($file = readdir($handle))) {
				$packageNameParts = explode(".",$file);
				if($packageNameParts[count($packageNameParts)-1] != 'zip'){
					continue;
				}
				array_pop($packageNameParts);
				$packageName = implode("",$packageNameParts);
		        if (!empty($packageName)) {
		        	//crmv@change packets path
		        	$packagepath = "packages/vte/mandatory/$file";
		        	//crmv@change packets path end
					$package = new Vtiger_Package();
	        		$moduleList[] = $package->getModuleNameFromZip($packagepath);
		        }
		    }
		    closedir($handle);
		}
		return $moduleList;
	}

	// crmv@151405
	
	public static function installSelectedOptionalModules($selected_modules, $source_directory = '', $destination_directory = '') {
		$packageDir = 'packages/vte/optional/';
		self::installSelectedModules($packageDir, $selected_modules, $source_directory, $destination_directory);
	}

	public static function installSelectedBetaModules($selected_beta_modules, $source_directory = '', $destination_directory = '') {
		$packageDir = 'packages/vte/beta/vte/';
		self::installSelectedModules($packageDir, $selected_beta_modules, $source_directory, $destination_directory);
	}

	public static function installSelectedModules($packageDir, $selected_modules, $source_directory = '', $destination_directory = '') {
		require_once('vtlib/Vtiger/Package.php');
		require_once('vtlib/Vtiger/Module.php');
		require_once('include/utils/utils.php');

		$selected_modules = explode(":", $selected_modules);
		
		$languagePacks = array();
		
		if ($handle = opendir($packageDir)) {
			while (false !== ($file = readdir($handle))) {
				$filename_arr = explode(".", $file);
				
				if ($filename_arr[count($filename_arr) - 1] != 'zip') {
					continue;
				}
				
				$packagename = $filename_arr[0];
				$packagepath = $packageDir.$file;
				
				$package = new Vtiger_Package();
				$module = $package->getModuleNameFromZip($packagepath);
				
				if (!empty($packagename) && in_array($module, $selected_modules)) {
					if ($package->isLanguageType($packagepath)) {
						$languagePacks[$module] = $packagepath;
						continue;
					}
					
					if ($module != null) {
						if ($package->isModuleBundle()) {
							$unzip = new Vtiger_Unzip($packagepath);
							$unzip->unzipAllEx($package->getTemporaryFilePath());
							$moduleInfoList = $package->getAvailableModuleInfoFromModuleBundle();
							
							foreach ($moduleInfoList as $moduleInfo) {
								$moduleInfo = (array) $moduleInfo;
								$packagepath = $package->getTemporaryFilePath($moduleInfo['filepath']);
								$subModule = new Vtiger_Package();
								$subModuleName = $subModule->getModuleNameFromZip($packagepath);
								$moduleInstance = Vtiger_Module::getInstance($subModuleName);
								if ($moduleInstance) {
									updateVtlibModule($subModuleName, $packagepath);
								} else {
									installVtlibModule($subModuleName, $packagepath);
								}
							}
						} else {
							$moduleInstance = Vtiger_Module::getInstance($module);
							if ($moduleInstance) {
								updateVtlibModule($module, $packagepath);
							} else {
								installVtlibModule($module, $packagepath);
							}
						}
					}
				}
			}
			
			closedir($handle);
		}
		
		foreach ($languagePacks as $module => $packagepath) {
			installVtlibModule($module, $packagepath);
			continue;
		}
	}
	
	// crmv@151405e
	
	//Function to to rename the installation file and folder so that no one destroys the setup
	public static function renameInstallationFiles() {
		$renamefile = uniqid(rand(), true);
		
		$ins_file_renamed = true;
		if(!@rename("install.php", $renamefile."install.php.txt")) {
			if (@copy ("install.php", $renamefile."install.php.txt")) {
				if(!@unlink("install.php")) {
					$ins_file_renamed = false;			
				}
			} else {
				$ins_file_renamed = false;
			}
		}
		
		$ins_dir_renamed = true;
		if(!@rename("install/", $renamefile."install/")) {
			if (@copy ("install/", $renamefile."install/")) {
				if(!@unlink("install/")) {
					$ins_dir_renamed = false;			
				}
			} else {
				$ins_dir_renamed = false;
			}
		}
		
		$result = array();
		$result['renamefile'] = $renamefile;
		$result['install_file_renamed'] = $ins_file_renamed;
		$result['install_directory_renamed'] = $ins_dir_renamed;
		
		return $result;
	}

	public static function getSQLVersion($serverInfo) {
		if(!is_array($serverInfo)) {
			$version = explode('-',$serverInfo);
			$mysql_server_version=$version[0];
		} else {
			$mysql_server_version = $serverInfo['version'];
		}
		return $mysql_server_version;
	}
	//crmv@fix hostname
	public static function constructHostname($dbtype,$hostname,$port){
		if ($dbtype == 'mysqli' || $dbtype == 'mssqlnative') { // crmv@155585
			return $hostname;
		} else {
			if ($port == '') $port =ConfigFile_Utils::getDbDefaultPort($dbtype);
			$separator =ConfigFile_Utils::getDbDefaultPortSeparator($dbtype);
			return $hostname.$separator.$port;
		}
	}
	//crmv@fix hostname end

	public static function disableMorph() {
		if (file_exists('DisableMorphsuit.php')) {
			ob_start();
			@include_once('DisableMorphsuit.php');
			ob_end_clean();
			@unlink('DisableMorphsuit.php');
		}
	}
}

//Function used to execute the query and display the success/failure of the query
function ExecuteQuery($query,$params = Array()) {
	global $adb, $installationStrings, $conn;
	global $migrationlog;

	//For third option migration we have to use the $conn object because the queries should be executed in 4.2.3 db
	$status = $adb->pquery($query,$params);
	if(is_object($status)) {
		echo '
			<tr width="100%">
				<td width="10%"><font color="green"> '.$installationStrings['LBL_SUCCESS'].' </font></td>
				<td width="80%">'.$query.'</td>
			</tr>';
		$migrationlog->debug("Query Success ==> $query");
	} else {
		echo '
			<tr width="100%">
					<td width="5%"><font color="red"> '.$installationStrings['LBL_FAILURE'].' </font></td>
				<td width="70%">'.$query.'</td>
			</tr>';
		$migrationlog->debug("Query Failed ==> $query \n Error is ==> [".$adb->database->ErrorNo()."]".$adb->database->ErrorMsg());
	}
	return $status;
}

//crmv@18123
function get_logo_install($mode){
	include_once('vteversion.php'); // crmv@181168
	global $enterprise_mode;
	$logo_path = 'themes/logos/';
	if ($mode == 'favicon')
		$extension = 'ico';
	else		
		$extension = 'png';
	$logo_path.=$enterprise_mode."_".$mode.".".$extension;
	return $logo_path;
}
//crmv@18123e
?>
