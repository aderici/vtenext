{*/*+*************************************************************************************
* The contents of this file are subject to the VTECRM License Agreement
* ("licenza.txt"); You may not use this file except in compliance with the License
* The Original Code is: VTECRM
* The Initial Developer of the Original Code is VTECRM LTD.
* Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
* All Rights Reserved.
***************************************************************************************/*}

{* crmv@140887 *}

{include file="ThemeHeader.tpl"}

<div id="mainContainer" data-minified="{$MENU_TOGGLE_STATE}" data-toggled="{$MENU_TOGGLE_STATE}">
	{if $HIDE_MENUS neq true}
		{include file="header/MenuPanel.tpl"}
		{include file="header/FastPanel.tpl"}
	{/if}
		
	<div id="mainContent" data-minified="{$MENU_TOGGLE_STATE}">
	
	{if $HIDE_MENUS neq true}
		<div id="status" class="linearLoadingIndicator" style="display:none;">{include file="LoadingIndicator.tpl" LINEAR=true}</div>
		<div id="fastPanel" class="fastPanel"></div>
	{else}
		{if $smarty.request.fastmode neq 1}
			{if $smarty.request.useical eq 'true'}
				{assign var="PAGE_TITLE" value='LBL_PREVIEW_INVITATION'|@getTranslatedString:$MODULE} 
				{assign var="CAL_MODE" value='on'}
				{assign var="OP_MODE" value='calendar_preview_buttons'}
				{include file='SmallHeader.tpl' SKIP_HTML_STRUCTURE=true HEADER_Z_INDEX=100}
			{else}
				{if isset($smarty.request.page_title)}
					{assign var="PAGE_TITLE" value=$smarty.request.page_title|@getTranslatedString:$MODULE}
					{assign var="OP_MODE" value=$smarty.request.op_mode}
				{else}
					{if $smarty.request.activity_mode eq 'Events'}
						{assign var="PAGE_TITLE" value='LBL_ADD'|@getTranslatedString:$MODULE}
					{else}
						{assign var="PAGE_TITLE" value='LBL_ADD_TODO'|@getTranslatedString:$MODULE}
					{/if}

					{assign var="CAL_MODE" value='on'}
					{assign var="OP_MODE" value='calendar_buttons'}
				{/if}
				{include file='SmallHeader.tpl' SKIP_HTML_STRUCTURE=true HEADER_Z_INDEX=100}
				{include file='Buttons_List4.tpl'}
			{/if}
		{/if}
		
		<div id="status" class="linearLoadingIndicator" style="display:none;">{include file="LoadingIndicator.tpl" LINEAR=true}</div>
	{/if}

	<div id="Buttons_List_3" class="level4Bg" style="display:none;"></div>
