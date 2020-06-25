{*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************}
{* crmv@39110 crmv@56051 *}
{if $sdk_mode eq 'detail'}
	{include file="FieldHeader.tpl" uitype=$keyid mandatory=$keymandatory label=$label}
	<div class="dvtCellInfoOff">
		<textarea name="{$keyfldname}" tabindex="{$vt_tab}" class="detailedViewTextBox" style="display:none">{$keyval}</textarea> {* crmv@186723 *}
		{* crmv@200267 *}
		<script type="text/javascript" src="include/ckeditor/ckeditor.js"></script>
		<script type="text/javascript">
			jQuery(document).ready(function() {ldelim}
				{* crmv@118732 *}
				try {ldelim}
					if (CKEDITOR.instances['{$keyfldname}']) CKEDITOR.instances['{$keyfldname}'].destroy(true);	//crmv@56883
				{rdelim} catch (e) {ldelim}
				{rdelim}
				{* crmv@118732e *}
				CKEDITOR.replace('{$keyfldname}', {ldelim}
					readOnly: true,
					removePlugins: 'toolbar, elementspath'
				{rdelim});
			{rdelim});
		</script>
		{* crmv@200267e *}
	</div>
{elseif $sdk_mode eq 'edit'}
	{if $readonly eq 99}
		{include file="FieldHeader.tpl" uitype=$uitype mandatory=$keymandatory label=$fldlabel}
		<div class="{$DIVCLASS}">
			<textarea name="{$fldname}" tabindex="{$vt_tab}" class="detailedViewTextBox" style="display:none">{$fldvalue}</textarea>
			{$fldvalue}
		</div>
	{elseif $readonly eq 100}
		<textarea name="{$fldname}" tabindex="{$vt_tab}" class="detailedViewTextBox" style="display:none">{$fldvalue}</textarea>
	{else}
		{include file="FieldHeader.tpl" uitype=$uitype mandatory=$keymandatory label=$fldlabel massedit=$MASS_EDIT}
		<div class="{$DIVCLASS}">
			{if $MOBILE eq 'yes'}
				{assign var=cols value="25"}
			{else}
				{assign var=cols value="90"}
			{/if}
			<textarea class="detailedViewTextBox" tabindex="{$vt_tab}" onFocus="this.className='detailedViewTextBoxOn'" name="{$fldname}"  onBlur="this.className='detailedViewTextBox'" cols="{$cols}" rows="8">{$fldvalue}</textarea>
			{if $FCKEDITOR_DISPLAY eq 'true'}
				{* crmv@42752 *}
				<script type="text/javascript">
					/* this is to have it working inside popups */
					window.CKEDITOR_BASEPATH = 'include/ckeditor/';
				</script>
				{* crmv@42752e *}
				<script type="text/javascript" src="include/ckeditor/ckeditor.js"></script>
				<script type="text/javascript">
					var current_language_arr = "{$AUTHENTICATED_USER_LANGUAGE}".split("_"); // crmv@181170
					var curr_lang = current_language_arr[0];
					jQuery(document).ready(function() {ldelim}
						{* crmv@118732 *}
						try {ldelim}
							if (CKEDITOR.instances['{$fldname}']) CKEDITOR.instances['{$fldname}'].destroy(true);	//crmv@56883
						{rdelim} catch (e) {ldelim}
						{rdelim}
						{* crmv@118732e *}
						CKEDITOR.replace('{$fldname}', {ldelim}
							filebrowserBrowseUrl: 'include/ckeditor/filemanager/index.html',
							toolbar : 'Basic',
							language : curr_lang
						{rdelim});
					{rdelim});
				</script>
			{/if}
		</div>
	{/if}
{/if}
