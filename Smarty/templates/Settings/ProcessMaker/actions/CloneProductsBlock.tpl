{*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************}
{* crmv@195745 *}

{include file='CachedValues.tpl'}	{* crmv@26316 *}

<table border="0" cellpadding="2" cellspacing="0" width="100%">
	<tr>
		<td align=right width=15% nowrap="nowrap">
			{include file="FieldHeader.tpl" mandatory=true label=$MOD.LBL_SOURCE_ENTITY}
		</td>
		<td align="left">
			<div class="dvtCellInfo">
				<select name="from_record" class="detailedViewTextBox">
					{foreach key=k item=i from=$FROM_RECORD}
						{* crmv@135190 *}
						{if isset($i.group)}
							<optgroup label="{$i.group}">
								{foreach key=kk item=ii from=$i.values}
									<option value="{$kk}" {$ii.1}>{$ii.0}</option>
								{/foreach}
							</optgroup>
						{else}
							<option value="{$k}" {$i.1}>{$i.0}</option>
						{/if}
						{* crmv@135190e *}
					{/foreach}
				</select>
			</div>
		</td>
		<td align=right width=15% nowrap="nowrap">&nbsp;</td>
	</tr>
	
	<tr>
		<td align=right width=15% nowrap="nowrap">
			{include file="FieldHeader.tpl" mandatory=true label=$MOD.LBL_DEST_ENTITY}
		</td>
		<td align="left">
			<div class="dvtCellInfo">
				<select name="to_record" class="detailedViewTextBox">
					{foreach key=k item=i from=$TO_RECORD}
						{* crmv@135190 *}
						{if isset($i.group)}
							<optgroup label="{$i.group}">
								{foreach key=kk item=ii from=$i.values}
									<option value="{$kk}" {$ii.1}>{$ii.0}</option>
								{/foreach}
							</optgroup>
						{else}
							<option value="{$k}" {$i.1}>{$i.0}</option>
						{/if}
						{* crmv@135190e *}
					{/foreach}
				</select>
			</div>
		</td>
		<td align=right width=15% nowrap="nowrap">&nbsp;</td>
	</tr>
</table>
<br>
