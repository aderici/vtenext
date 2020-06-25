{*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************}
 
{* crmv@80155 *}

<table class="vtetable vtetable-props">
	<tbody>
		<tr>
			<td class="cellLabel">{'LBL_NAME'|getTranslatedString:'Settings'}</td>
			<td class="cellText">{$TEMPLATENAME}</td>
		</tr>
		<tr>
			<td class="cellLabel">{'LBL_DESCRIPTION'|getTranslatedString:'Settings'}</td>
			<td class="cellText">{$DESCRIPTION}</td>
		  </tr>
		<tr>
			<td class="cellLabel">{'LBL_FOLDER'|getTranslatedString:'Settings'}</td>
			<td class="cellText">{$FOLDERNAME}</td>
		</tr>
		{* crmv@22700 *}
		<tr>
			<td class="cellLabel">{'LBL_TYPE'|getTranslatedString}</td>
			<td class="cellText">{$TEMPLATETYPE}</td>
		</tr>
		{* crmv@22700e *}
		{if $TEMPLATETYPE eq 'Email'}
			<tr>
				<td class="cellLabel">{'LBL_USE_SIGNATURE'|getTranslatedString:'Settings'}</td>
				<td class="cellText">
					{if $USE_SIGNATURE eq 1}
						<i class="vteicon checkok nohover">check</i>
					{else}
						<i class="vteicon checkko nohover">clear</i>
					{/if}
				</td>
			</tr>
			<tr>
				<td class="cellLabel">{'LBL_OVERWRITE_MESSAGE'|getTranslatedString:'Settings'}</td>
				<td class="cellText">
					{if $OVERWRITE_MESSAGE eq 1}
						<i class="vteicon checkok nohover">check</i>
					{else}
						<i class="vteicon checkko nohover">clear</i>
					{/if}
				</td>
			</tr>
		{/if}
		{if $BU_MC_ENABLED}
			<tr>
				<td class="cellLabel">Business Unit</td>
				<td class="cellText">{$BU_MC}</td>
			</tr>
		{/if}
		<tr>
			<td class="cellLabel">{'LBL_SUBJECT'|getTranslatedString:'Settings'}</td>
			<td class="cellText">{$SUBJECT}</td>
		</tr>
		<tr>
			<td class="cellLabel">{'LBL_MESSAGE'|getTranslatedString:'Settings'}</td>
			<td class="cellText">
				{* crmv@153002 *}
				<iframe src="index.php?module=Utilities&amp;action=UtilitiesAjax&amp;file=getEmailTemplateBody&amp;templateid={$TEMPLATEID}&amp;formodule={$MODULE}&amp;forrecord={$ID}" 
					frameborder="0" scrolling="yes" marginheight="0" marginwidth="0" width="100%" 
					onload="this.style.height = this.contentWindow.document.body.scrollHeight + 'px';">
				</iframe>
				{* crmv@153002e *}
			</td>
		</tr>
	</tbody>
</table>
