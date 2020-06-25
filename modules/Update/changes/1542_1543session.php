<?php
require('../../../config.inc.php');
chdir($root_directory);

/* crmv@128133 */
/* 
 * Experimental script to replace all instances of $_SESSION with the new specialized class
 * The replacement is not perfect, some edge cases might not be handled correctly
 * This script will modify files, be sure you have the right permissions.
 * !!!! Run at your own risk !!!!
 *
 */
 
$it = new RecursiveDirectoryIterator('./');
$exts = Array ( 'php', 'inc', 'tpl' );
$excludeDirs = array(
	'./adodb', './include/tcpdf', './include/htmlpurifier', './include/Zend2', 
	'./include/PHPExcel', './include/html2pdf', './include/mpdf', './Smarty/templates_c',
	'./log4php', './modules/PDFMaker/mpdf', './include/fpdf', './include/nusoap',
	'./Image', './portal', './magpierss', './vtlib/ModuleDir', './include/pChart',
	'./include/HTTP_Session', './modules/Mobile', './modules/Update/changes', './plugins/script',
	'./modules/Migration', './backup/', './install', './include/install', './vte_updater'
);
$excludeFiles = array(
	'./vtigerversion.php',
	'./include/VteSession.php'
);

$assignRegExp = array(
		'/\$_SESSION\[([^]]*?)\]\s*=\s*([^=].*);/' => 'VteSession::set(\1, \2);',		// assignment
		'/\$_SESSION\[([^]]*?)\]\s*\+=\s*(.*);/' => 'VteSession::increment(\1, \2);',	// increment
		'/\$_SESSION\[([^]]*?)\]\s*-=\s*(.*);/' => 'VteSession::decrement(\1, \2);',	// decrement
		'/\$_SESSION\[([^]]*?)\]\s*\.=\s*(.*);/' => 'VteSession::concat(\1, \2);',		// string concat
		// array assignment
		'/\$_SESSION\[([^]]*?)\]\[([^]]*?)\]\[([^]]*?)\]\[([^]]*?)\]\[([^]]*?)\]\s*=\s*([^=].*);/' => 'VteSession::setArray(array(\1, \2, \3, \4, \5), \6);',		// 5 levels
		'/\$_SESSION\[([^]]*?)\]\[([^]]*?)\]\[([^]]*?)\]\[([^]]*?)\]\s*=\s*([^=].*);/' => 'VteSession::setArray(array(\1, \2, \3, \4), \5);',		// 4 levels
		'/\$_SESSION\[([^]]*?)\]\[([^]]*?)\]\[([^]]*?)\]\s*=\s*([^=].*);/' => 'VteSession::setArray(array(\1, \2, \3), \4);',		// 3 levels
		'/\$_SESSION\[([^]]*?)\]\[([^]]*?)\]\s*=\s*([^=].*);/' => 'VteSession::setArray(array(\1, \2), \3);',		// 2 levels
		// array append
		'/\$_SESSION\[([^]]*?)\]\[([^]]*?)\]\[([^]]*?)\]\[([^]]*?)\]\[([^]]*?)\]\[\]\s*=\s*([^=].*);/' => 'VteSession::appendArray(array(\1, \2, \3, \4, \5), \6);',		// 5 levels
		'/\$_SESSION\[([^]]*?)\]\[([^]]*?)\]\[([^]]*?)\]\[([^]]*?)\]\[\]\s*=\s*([^=].*);/' => 'VteSession::appendArray(array(\1, \2, \3, \4), \5);',		// 4 levels
		'/\$_SESSION\[([^]]*?)\]\[([^]]*?)\]\[([^]]*?)\]\[\]\s*=\s*([^=].*);/' => 'VteSession::appendArray(array(\1, \2, \3), \4);',		// 3 levels
		'/\$_SESSION\[([^]]*?)\]\[([^]]*?)\]\[\]\s*=\s*([^=].*);/' => 'VteSession::appendArray(array(\1, \2), \3);',		// 2 levels
		'/\$_SESSION\[([^]]*?)\]\[\]\s*=\s*([^=].*);/' => 'VteSession::append(\1, \2);',		// 1 level
		// removal
		'/unset\(\$_SESSION\[([^]]*?)\]\[([^]]*?)\]\[([^]]*?)\]\[([^]]*?)\]\[([^]]*?)\]\)/' => 'VteSession::removeArray(array(\1, \2, \3, \4, \5))',		// 5 levels
		'/unset\(\$_SESSION\[([^]]*?)\]\[([^]]*?)\]\[([^]]*?)\]\[([^]]*?)\]\)/' => 'VteSession::removeArray(array(\1, \2, \3, \4))',		// 4 levels
		'/unset\(\$_SESSION\[([^]]*?)\]\[([^]]*?)\]\[([^]]*?)\]\)/' => 'VteSession::removeArray(array(\1, \2, \3))',		// 2 levels
		'/unset\(\$_SESSION\[([^]]*?)\]\[([^]]*?)\]\)/' => 'VteSession::removeArray(array(\1, \2))',		// 2 levels
		'/unset\(\$_SESSION\[([^]]*?)\]\)/' => 'VteSession::remove(\1)',
		// empty check
		'/empty\(\$_SESSION\[([^]]*?)\]\[([^]]*?)\]\[([^]]*?)\]\[([^]]*?)\]\[([^]]*?)\]\)/' => 'VteSession::isEmptyArray(array(\1, \2, \3, \4, \5))',		// 5 levels
		'/empty\(\$_SESSION\[([^]]*?)\]\[([^]]*?)\]\[([^]]*?)\]\[([^]]*?)\]\)/' => 'VteSession::isEmptyArray(array(\1, \2, \3, \4))',		// 4 levels
		'/empty\(\$_SESSION\[([^]]*?)\]\[([^]]*?)\]\[([^]]*?)\]\)/' => 'VteSession::isEmptyArray(array(\1, \2, \3))',		// 2 levels
		'/empty\(\$_SESSION\[([^]]*?)\]\[([^]]*?)\]\)/' => 'VteSession::isEmptyArray(array(\1, \2))',		// 2 levels
		'/empty\(\$_SESSION\[([^]]*?)\]\)/' => 'VteSession::isEmpty(\1)',
		// isset check
		'/isset\(\$_SESSION\[([^]]*?)\]\[([^]]*?)\]\[([^]]*?)\]\[([^]]*?)\]\[([^]]*?)\]\)/' => 'VteSession::hasKeyArray(array(\1, \2, \3, \4, \5))',		// 5 levels
		'/isset\(\$_SESSION\[([^]]*?)\]\[([^]]*?)\]\[([^]]*?)\]\[([^]]*?)\]\)/' => 'VteSession::hasKeyArray(array(\1, \2, \3, \4))',		// 4 levels
		'/isset\(\$_SESSION\[([^]]*?)\]\[([^]]*?)\]\[([^]]*?)\]\)/' => 'VteSession::hasKeyArray(array(\1, \2, \3))',		// 2 levels
		'/isset\(\$_SESSION\[([^]]*?)\]\[([^]]*?)\]\)/' => 'VteSession::hasKeyArray(array(\1, \2))',		// 2 levels
		'/isset\(\$_SESSION\[([^]]*?)\]\)/' => 'VteSession::hasKey(\1)',
		// read array
		'/\$_SESSION\[([^]]*?)\]\[([^]]*?)\]\[([^]]*?)\]\[([^]]*?)\]\[([^]]*?)\]/' => 'VteSession::getArray(array(\1, \2, \3, \4, \5))',		// 5 levels
		'/\$_SESSION\[([^]]*?)\]\[([^]]*?)\]\[([^]]*?)\]\[([^]]*?)\]/' => 'VteSession::getArray(array(\1, \2, \3, \4))',		// 4 levels
		'/\$_SESSION\[([^]]*?)\]\[([^]]*?)\]\[([^]]*?)\]/' => 'VteSession::getArray(array(\1, \2, \3))',		// 3 levels
		'/\$_SESSION\[([^]]*?)\]\[([^]]*?)\]/' => 'VteSession::getArray(array(\1, \2))',		// 2 levels
		// read single
		'/\$_SESSION\[([^]]*?)\]/' => 'VteSession::get(\1)',
		// not handled case:
		// . nesting more than 5 levels
		// . $_SESSION[$array['something']] = ...
		// . multi line statements
	);
	
$sessionRegExp = array(
	// session start
	'/session_start\(\)/' => 'VteSession::start()',
);

foreach(new RecursiveIteratorIterator($it) as $file) {
	//$file = preg_replace('#^./#', '', $file);
	$ext = strtolower(array_pop(explode('.', $file)));
	$dir = dirname($file);
	foreach ($excludeDirs as $edir) {
		if (substr($dir, 0, strlen($edir)) === $edir) continue 2;
	}
	if (in_array($file, $excludeFiles)) continue;
	
	// quick check for extension and session inside the file
    if (in_array($ext, $exts) && searchStringFile($file, '$_SESSION[')) {
        processSessionReplace($file, $assignRegExp);
    }
    
    // quick check for extension and session inside the file
    if (in_array($ext, $exts) && searchStringFile($file, 'session_start')) {
        processSessionReplace($file, $sessionRegExp);
    }
}


function searchStringFile($file, $string) {
	$handle = fopen($file, 'r');
	$valid = false; // init as false
	while (($buffer = fgets($handle)) !== false) {
		if (strpos($buffer, $string) !== false) {
			$valid = true;
			break; // Once you find the string, you should break out the loop.
		}      
	}
	fclose($handle);
	return $valid;
}

function processSessionReplace($file, $rlist) {
	echo "Checking file $file<br>\n";
	$content = file_get_contents($file);
	$count = 0;
	$content = preg_replace(array_keys($rlist), array_values($rlist), $content, -1, $count);
	if ($count > 0) {
		if (!checkPhpSyntax($content, $error)) {
			//echo $content;
			echo "SYNTAX ERROR FOR FILE $file: $error<br>\n";
			return false;
		}
		doFileBackup($file);
		file_put_contents($file, $content);
		echo "File Saved: $file<br>\n";
	}
}

function doFileBackup($file) {
	$bfile = preg_replace('#^./#', '', $file);
	$dest = 'backup/session_backup/'.$bfile;
	$destDir = dirname($dest);
	if (!is_dir($destDir)) {
		mkdir($destDir, 0755, true);
	}
	if (!file_exists($dest)) {
		copy($file, $dest);
	}
}

function checkPhpSyntax($content, &$error = '') {
	$descriptorspec = array(
		0 => array("pipe", "r"),
		1 => array("pipe", "w"),
		2 => array("pipe", "w")
	);
	
	$cwd = sys_get_temp_dir();
	$error = '';
	$process = proc_open('php -l', $descriptorspec, $pipes, $cwd);
	if (is_resource($process)) {
		fwrite($pipes[0], $content);
		fclose($pipes[0]);
		
		$error = stream_get_contents($pipes[1]);
		fclose($pipes[1]);
		
		//echo stream_get_contents($pipes[2]);
		fclose($pipes[2]);
		
		$return_value = proc_close($process);
		
		return ($return_value == 0);
	}
	return true;
}

