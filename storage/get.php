<?php

/* crmv@198833 */

function outputForbidden() {
	header("HTTP/1.1 403 Forbidden");
	exit();
}

// get the file parameter
$file = $_GET['file'];
if (empty($file)) outputForbidden();

// this trick is necessary to avoid sending headers with the session cookie
@ini_set('session.use_only_cookies', false);
@ini_set('session.use_cookies', false);
@ini_set('session.use_trans_sid', false);
@ini_set('session.cache_limiter', null);

$cookiename = session_name();
$sessid = $_COOKIE[$cookiename];

if (empty($sessid)) outputForbidden();

session_id($sessid);
session_start();

$authid = $_SESSION['authenticated_user_id'];
if (empty($authid)) outputForbidden();

// check app key
$appkey = $_SESSION['app_unique_key'];
if (empty($appkey)) outputForbidden();

// close session now, to release the lock, since the download might require some time
session_write_close();

require('../config.inc.php');
if ($application_unique_key != $appkey) outputForbidden();

// check if file is in storage
$fullpath = __DIR__ .'/'.str_replace('..', '', $file);
if (!is_readable($fullpath)) outputForbidden();

// exclude bad extensions
$ext = pathinfo($fullpath, PATHINFO_EXTENSION);
if (is_array($upload_badext) && in_array($ext, $upload_badext)) outputForbidden();

// output!
$fp = fopen($fullpath, 'rb');

// send the right headers
$type = mime_content_type($fullpath);
header("Content-Type: ".$type);
header("Content-Length: " . filesize($fullpath));

fpassthru($fp);
exit;
