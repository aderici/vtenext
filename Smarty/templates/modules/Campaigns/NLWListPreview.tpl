{* crmv@151466 *}

<table class="table table-hover table-striped" width="100%" cellspacing="0">
	<thead>
		<tr class="popupLinkListTitleRow">
			<th class="popupLinkListTitleCell">{$APP.LBL_ENTITY_TYPE}</th>
			<th class="popupLinkListTitleCell">{$APP.LBL_LIST_NAME}</th>
			<th class="popupLinkListTitleCell">{$APP.LBL_EMAIL}</th>
		</tr>
	</thead>
	<tbody>
		{foreach item="REC"" from=$RECIPIENTS}
		<tr onclick="nlwShowPreview('{$REC.crmid}')">
			<td class="popupLinkListDataCell">{$REC.module_label}</td>
			<td class="popupLinkListDataCell">{$REC.entityname}</td>
			<td class="popupLinkListDataCell">{$REC.email}</td>
		</tr>
		{/foreach}
	</tbody>
</table>
