<?php
$moduleInstance = Vtiger_Module::getInstance('Newsletter');
Vtiger_Link::addLink($moduleInstance->id,'HEADERSCRIPT','StatisticsScript','modules/Campaigns/Statistics.js');

@unlink('modules/Campaigns/Statistics.php');
@unlink('modules/Newsletter/Statistics.php');
@unlink('Smarty/templates/themes/next/modules/Campaigns/Statistics.tpl');