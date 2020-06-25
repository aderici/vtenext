{*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************}
{* crmv@160837 *}
 
{include file="SmallHeader.tpl"}

<script src="{"modules/Settings/ProcessMaker/resources/ActionTaskScript.js"|resourcever}" type="text/javascript" charset="utf-8"></script>

<table border="0" cellpadding="2" cellspacing="0" width="100%">
	<tr>
		<td align=right width=15% nowrap="nowrap">
			{include file="FieldHeader.tpl" mandatory=true label=$MOD.LBL_ENTITY}
		</td>
		<td align="left">
			<div class="dvtCellInfo">
				<select name="record_involved" class="detailedViewTextBox" onchange="ProcessHelperScript.loadModuleBlocks({$ID},'{$ELEMENTID}',this.value);">
					{foreach key=k item=i from=$RECORDS_INVOLVED}
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

<div id="blocks_container"></div>