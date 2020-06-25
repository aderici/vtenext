{*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************}
{* crmv@92272 *}

<table border="0" cellpadding="2" cellspacing="0" width="100%">
	<tr>
		<td align=right width=15% nowrap="nowrap">
			{include file="FieldHeader.tpl" mandatory=true label=$MOD.LBL_ENTITY}
		</td>
		<td align="left">
			{* crmv@192142 *}
			<div class="dvtCellInfo">
				<select name="record_involved" class="detailedViewTextBox">
					{foreach key=k item=i from=$RECORDS_INVOLVED}
						{if isset($i.group)}
							<optgroup label="{$i.group}">
								{foreach key=kk item=ii from=$i.values}
									<option value="{$kk}" {$ii.1}>{$ii.0}</option>
								{/foreach}
							</optgroup>
						{else}
							<option value="{$k}" {$i.1}>{$i.0}</option>
						{/if}
					{/foreach}
				</select>
			</div>
			{* crmv@192142e *}
		</td>
		<td align=right width=15% nowrap="nowrap">&nbsp;</td>
	</tr>
</table>
<br>
<select id='task-fieldnames' class="notdropdown" style="display:none;">
	<option value="">{'LBL_SELECT_OPTION_DOTDOTDOT'|getTranslatedString:'com_vtiger_workflow'}</option>
</select>