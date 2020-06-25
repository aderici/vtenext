{*/*+*************************************************************************************
* The contents of this file are subject to the VTECRM License Agreement
* ("licenza.txt"); You may not use this file except in compliance with the License
* The Original Code is: VTECRM
* The Initial Developer of the Original Code is VTECRM LTD.
* Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
* All Rights Reserved.
***************************************************************************************/*}

{* crmv@140887 *}

{if !empty($QCMODULE)}

<ul id="quickModules" class="vte-collection with-header">
	
	<li class="collection-header"><h4>{'LBL_QUICK_CREATE'|getTranslatedString}</h4></li>
	
	{foreach from=$QCMODULE item=detail name=qcmodule}
		{assign var="moduleName" value=$detail.1}
		{assign var="moduleNameLower" value=$moduleName|strtolower}
		{assign var="moduleFirstLetter" value=$moduleName|substr:0:1|strtoupper}

		<li class="collection-item avatar">
			<div class="circle">
				<i class="icon-module icon-{$moduleNameLower} nohover" data-first-letter="{$moduleFirstLetter}"></i>
			</div>
			<div class="main-title"><a href="#" onclick="NewQCreate('{$detail.1}');">{$detail.0}</a></div>
		</li>
	{/foreach}
	
</ul>

{else}

<div class="vte-collection-empty">
	<div class="collection-item">
		<div class="circle">
			<i class="vteicon nohover">flash_on</i>
		</div>
		<h4 class="title">{"LBL_NO_QUICKCREATED"|getTranslatedString}</h4>
	</div>
</div>
	
{/if}
