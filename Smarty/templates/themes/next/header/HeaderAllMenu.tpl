{*/*+*************************************************************************************
* The contents of this file are subject to the VTECRM License Agreement
* ("licenza.txt"); You may not use this file except in compliance with the License
* The Original Code is: VTECRM
* The Initial Developer of the Original Code is VTECRM LTD.
* Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
* All Rights Reserved.
***************************************************************************************/*}

{* crmv@140887 *}

<div class="col-sm-12">
	<ul class="tabs tabs-fixed-width" id="menuTabs">
		<li class="tab"><a href="#OtherModuleListTabContent">{$APP.LBL_MODULES}</a></li>
		<li class="tab"><a href="#AllMenuAreaTabContent">{$APP.LBL_AREAS}</a></li>
	</ul>
</div>

<div id="OtherModuleListTabContent" class="col-sm-12">
	{include file="header/HeaderAllModules.tpl"}
</div>

<div id="AllMenuAreaTabContent" class="col-sm-12">
	{include file="modules/Area/Menu.tpl" UNIFIED_SEARCH_AREAS_CLASS=" "}
</div>
