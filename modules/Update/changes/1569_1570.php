<?php
// handler beforesave module HelpDesk
$em = new VTEventsManager($adb);
$em->registerHandler('vtiger.entity.beforesave','modules/HelpDesk/HelpDeskStatusHandler.php','HelpDeskStatusHandler');