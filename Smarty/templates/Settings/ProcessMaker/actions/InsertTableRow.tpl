{*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************}
{* crmv@115268 crmv@131239 crmv@140949 *}
{if $TABLETYPE eq 'ModLight'}
	{include file='Settings/ProcessMaker/actions/Create.tpl'}
	<script type="text/javascript">
		jQuery(document).ready(function() {ldelim}
			{if $SHOW_ACTION_CONDITIONS}
				jQuery.fancybox.showLoading();
				ActionConditionScript.init('{$ID}','{$ELEMENTID}','{$METAID}','{$CYCLE_FIELDNAME}',function(){ldelim}
				jQuery.fancybox.hideLoading();
			{/if}
				ActionCreateScript.loadForm('{$MODULELIGHT}','{$ID}','{$ELEMENTID}','{$ACTIONTYPE}','{$ACTIONID}',true);
			{if $SHOW_ACTION_CONDITIONS}
				{rdelim});
			{/if}
		{rdelim});
	</script>
{else}
	{include file='modules/SDK/src/Reference/Autocomplete.tpl' MODULE='Accounts'}
	<div id="editForm">
		{include file='CreateView.tpl'}
	</div>
	{include file='Settings/ProcessMaker/actions/Create.tpl'}
	<script type="text/javascript">
		jQuery(document).ready(function() {ldelim}
			{if $SHOW_ACTION_CONDITIONS}
				jQuery.fancybox.showLoading();
				ActionConditionScript.init('{$ID}','{$ELEMENTID}','{$METAID}','{$CYCLE_FIELDNAME}',function(){ldelim}
				jQuery.fancybox.hideLoading();
			{/if}
				var params = JSON.parse('{$EDITOPTIONSPARAMS}');
				jQuery.fancybox.showLoading();
				ActionTaskScript.loadFormEditOptions(ActionCreateScript,'{$EDITOPTIONSMODULE}',params);
			{if $SHOW_ACTION_CONDITIONS}
				{rdelim});
			{/if}
		{rdelim});
	</script>
{/if}