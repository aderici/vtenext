{*/*+*************************************************************************************
* The contents of this file are subject to the VTECRM License Agreement
* ("licenza.txt"); You may not use this file except in compliance with the License
* The Original Code is: VTECRM
* The Initial Developer of the Original Code is VTECRM LTD.
* Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
* All Rights Reserved.
***************************************************************************************/*}

{* crmv@140887 *}
{* crmv@187403 *}

<div id="leftPanel" data-minified="{$MENU_TOGGLE_STATE}">
	<div class="vteLeftHeader">
		<div class="brandLogo">
			<img class="img-responsive headerLogo" src="{$LOGOHEADER}" />
		</div>
		
		<span class="toogleMenu">
			<img class="toggleImg" src="{$LOGOTOGGLE}" />
			<i class="togglePin vteicon2 fa-thumb-tack md-link {if $MENU_TOGGLE_STATE eq 'disabled'}active{/if}"></i>
		</span>
	</div>
	
	{if $MODULE_NAME eq 'Settings' || $CATEGORY eq 'Settings' || $MODULE_NAME eq 'com_vtiger_workflow'}
		{include file="header/MenuSettings.tpl"}
	{else}
		{include file="header/MenuModules.tpl"}
	{/if}
</div>
