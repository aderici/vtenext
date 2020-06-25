{*/*+*************************************************************************************
* The contents of this file are subject to the VTECRM License Agreement
* ("licenza.txt"); You may not use this file except in compliance with the License
* The Original Code is: VTECRM
* The Initial Developer of the Original Code is VTECRM LTD.
* Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
* All Rights Reserved.
***************************************************************************************/*}

{* crmv@161554 *}

<input type="hidden" name="accesstoken" value="{$ACCESS_TOKEN}" />
				
{foreach from=$STRUCTURE item=BLOCK}
	{assign var=fieldCounter value=0}
	
	<fieldset class="form-group">
		<legend>
			<strong>{$BLOCK.label|_T}</strong><br>
		</legend>

		{foreach from=$BLOCK.fields item=FIELD name=blockFields}
			{assign var=fieldName value=$FIELD.name}
			{assign var=fieldLabel value=$FIELD.label}
	
			{if $fieldCounter eq 0}
				<div class="form-group">
				<div class="form-row">
			{/if}
	
			{assign var=fieldCounter value=$fieldCounter+1}
			
			<div class="col-md-6">
				<label for="{$fieldName}">{$fieldLabel|_T}</label>
				<input type="text" class="form-control" id="{$fieldName}" name="{$fieldName}" readonly="" disabled="" />
			</div>
			
			{if $fieldCounter eq 2}
				</div>
				</div>
				{assign var=fieldCounter value=0}
			{/if}
		{/foreach}
	</fieldset>
{/foreach}

<script type="text/javascript">
	{if $CONTACT_DATA}
		var contactData = {$CONTACT_DATA|replace:"'":"\'"};
	{else}
		var contactData = {ldelim}{rdelim};
	{/if}
</script>
