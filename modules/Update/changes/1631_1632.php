<?php 

// crmv@152713

global $theme, $default_theme;

if (empty($theme)) $theme = $default_theme;

$TU = ThemeUtils::getInstance($theme);
$TU->install();

$CU = CronUtils::getInstance();

$cj = new CronJob();
$cj->name = 'Wallpaper';
$cj->active = 1;
$cj->singleRun = false;
$cj->fileName = 'cron/modules/Wallpaper/Wallpaper.service.php';
$cj->timeout = 7200;	// 2h timeout
$cj->repeat = 21600;	// run every 6 hours
$CU->insertCronJob($cj);

$adb->pquery("UPDATE sdk_menu_contestual SET image = ?, action = 'index' WHERE title = ?", array('warning', 'NEWSLETTER_G_UNSUBSCRIBE'));
$adb->pquery("UPDATE sdk_menu_contestual SET action = 'index' WHERE module = ? AND title = ?", array('Potentials', 'Budget'));
$adb->pquery("UPDATE sdk_menu_contestual SET action = 'index' WHERE module = ? AND title = ?", array('PDFMaker', 'LBL_ADD_TEMPLATE'));