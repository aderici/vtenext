{*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************}
{* crmv@185548 *}

{assign var="reload" value=$RELOAD}
{assign var="showpick2" value=$SHOWPICK2}
<table border="0" cellpadding="2" cellspacing="0" width="100%">
	<tr>
	{if !$reload}
		{if $MODE eq 'create'}
			{if !$showpick2}
				{include file="Settings/ProcessMaker/actions/TransferRelateRecord.tpl" RECORDS_INVOLVED=$RECORDPICK1 ENTITY="1" MODE=$MODE}
			{/if}
		{/if}
	{/if}
	</tr>
	<tr id="record2_container">
		{if $showpick2}
			{if $MODE neq 'create'}
				{include file="Settings/ProcessMaker/actions/TransferRelateRecord.tpl" RECORDS_INVOLVED=$RECORDPICK1 ENTITY="1" MODE=$MODE}
				</tr>
				<tr id="record_container2">
					{include file="Settings/ProcessMaker/actions/TransferRelateRecord.tpl" RECORDS_INVOLVED=$RECORDPICK2 ENTITY="2" MODE=$MODE}
				</tr>
			{/if}
		{/if}
	</tr>
	<tr id="record3_container">
	</tr>
	<tr>
		<td></td>
		<td>
			{assign var="modules_list" value=$MODULES_LIST}
			{assign var="selected_modules_list" value=$SELECTED_MODULES_LIST}
			{if $modules_list|@count eq 0 and $MODE eq 'edit'}
				{'LBL_MODULES_LIST_ERROR'|getTranslatedString}
			{else}
				<div class="checkbox" id="modules_list_container">
				{* crmv@192143 *}
				<table style="width:100%">
				<tr>
				{assign var="sequence" value="0"}
					{foreach from=$modules_list item=module}
						<td style="width:50%">
						{assign var="selected_item" value=""}
						<div class="checkbox" id="module_{$module}" {if !$SHOW_LIST} style="display:none" {/if}>
							<label>
								{if $selected_modules_list|@count neq 0}
									{if $module|in_array:$selected_modules_list}
										{assign var="selected_item" value="checked"}
									{/if}
								{/if}
								<input type="checkbox" name={$module} {$selected_item}> {if $module eq 'Calendar'} {assign var=module value='Tasks'} {/if} {$module|getTranslatedString:$module} </input>
							</label>
						</div>
						{assign var="sequence" value=$sequence+1}
						{if $sequence%2 eq 0 and $sequence neq 0}
							</td></tr><tr>
						{/if}
					{/foreach}
				</table>
				{* crmv@192143e *}
				</div>
			 {/if}
		</td>
		<td>
			{* <input type="text" value="" id="selected_modules_list" style="border:1px solid black; width:500px;"/>  *}
		</td>
	</tr>
</table>
<br>
<select id='task-fieldnames' class="notdropdown" style="display:none;">
	<option value="">{'LBL_SELECT_OPTION_DOTDOTDOT'|getTranslatedString:'com_vtiger_workflow'}</option>
</select>