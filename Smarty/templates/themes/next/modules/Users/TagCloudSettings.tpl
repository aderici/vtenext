<table class="tableHeading" width="100%">
	<tr>
		<td class="big">
			<strong>8. {$UMOD.LBL_TAGCLOUD_DISPLAY}</strong>	{* crmv@164190 *}
		</td>
		<td class="small" align="right">&nbsp;</td>
	</tr>
</table>

<table class="table">
	<tr>
		<td align="right" width="25%">{$UMOD.LBL_TAG_CLOUD}</td>
		
		{if $TAGCLOUDVIEW eq 'true'} 
			{assign var="tagcloudview_true_check" value="checked"} 
			{assign var="tagcloudview_false_check" value=""} 
		{else} 
			{assign var="tagcloudview_true_check" value=""} 
			{assign var="tagcloudview_false_check" value="checked"} 
		{/if}
		
		<td align="center" width="15%">
			<div class="togglebutton">
				<label>
					<input name="tagcloudview" value="true" type="checkbox"{$tagcloudview_true_check}>
				</label>
			</div>
		</td>
		
		<td align="right" width="25%">&nbsp;{* here for spacing only *}</td>
		<td align="center" width="15%">&nbsp;</td>
	</tr>
</table>
