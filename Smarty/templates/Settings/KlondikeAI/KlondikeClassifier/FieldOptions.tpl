{*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************}
{* crmv@197445 *}

{if $OPT_NONE}
	<option value="">{'LBL_NONE'|getTranslatedString}</option>
{/if}
{foreach item=filteroption key=label from=$CHOOSECOLUMN}
	<optgroup label="{$label}" class="select" style="border:none">
	{foreach item=text from=$filteroption}
		{assign var=option_values value=$text.text}
		<option {$text.selected} value={$text.value}>
		{if $MOD.$option_values neq ''}
			{if $DATATYPE.0.$option_values eq 'M'}
				{$MOD.$option_values}   {$APP.LBL_REQUIRED_SYMBOL}
			{else}
				{$MOD.$option_values}
			{/if}
		{elseif $APP.$option_values neq ''}
			{if $DATATYPE.0.$option_values eq 'M'}
				{$APP.$option_values}   {$APP.LBL_REQUIRED_SYMBOL}
			{else}
				{$APP.$option_values}
			{/if}
		{else}
			{if $DATATYPE.0.$option_values eq 'M'}
				{$option_values}    {$APP.LBL_REQUIRED_SYMBOL}
			{else}
				{$option_values}
			{/if}
		{/if}
		</option>
	{/foreach}
	</optgroup>
{/foreach}