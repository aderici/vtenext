{*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************}
{* crmv@197445 *}

<link rel="stylesheet" type="text/css" href="include/js/dataTables/css/dataTables.bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="include/js/dataTables/plugins/FixedHeader/css/fixedHeader.bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="include/js/dataTables/plugins/Responsive/css/responsive.bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="include/js/dataTables/plugins/Select/css/select.bootstrap.min.css"/>

<script type="text/javascript" src="include/js/dataTables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="include/js/dataTables/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="include/js/dataTables/plugins/FixedHeader/js/dataTables.fixedHeader.min.js"></script>

<br>

{if !empty($NEW_BUTTON)}
<table border=0 cellspacing=0 cellpadding=3 width=100%>
	<tr>
		<td colspan="6" align="right">
			<form style="display: inline;" action="{$NEW_BUTTON}" method="POST">
				<input type="submit" class="crmbutton small create" value='{$APP.LBL_NEW}' title='{$APP.LBL_NEW}'>
			</form>
		</td>
	</tr>
</table>
{/if}
<table class="table table-hover dataTable" id="listTable">
	{* crmv@136524 *}
	<thead>
	{if isset($SUBPROCESS)}
	<tr>
		<th><input type="radio" name="subprocess" id="subprocess_0" value="0" checked/></th>
		<th colspan="5"><label for="subprocess_0">{'LBL_PM_NO_SUBPROCESS'|getTranslatedString:'Settings'}</label></th>
	</tr>
	{/if}
	{* crmv@136524e *}
	<tr>
	{foreach item=column from=$HEADER name=header}
		{if $smarty.foreach.header.index gt 0}
			<th>{$column}
			<br><div class="dvtCellInfo"><input class="detailedViewTextBox" type="text" placeholder="{'LBL_SEARCH_FOR'|getTranslatedString} {$column}" /></div>
			</th>
		{else}
			<th>{$column}</th>
		{/if}
	{/foreach}
	</tr>
	</thead>
	{foreach item=entity from=$LIST}
		<tr>
			{foreach item=column from=$entity name=list_columns} {* crmv@190834 *}
				<td class="listTableRow small" {if $smarty.foreach.list_columns.index eq 0}nowrap{/if}>{$column}</td> {* crmv@190834 *}
			{/foreach}
		</tr>
	{/foreach}
</table>

<script type="text/javascript">
{literal}
jQuery(document).ready(function(){
	
	var listTable = jQuery('#listTable').DataTable({
		// crmv@190834
		"pageLength": {/literal}{$LIST_TABLE_PROP.0}{literal},
		"order": [[ {/literal}{$LIST_TABLE_PROP.1}{literal}, "{/literal}{$LIST_TABLE_PROP.2}{literal}" ]],
		// crmv@190834e
		
		// searching
		searching: true,
		search: {
			caseInsensitive: true,
			smart: false,	// disabled for the moment
		},
		
		// internationalization
		language: {
			url: "include/js/dataTables/i18n/{/literal}{$CURRENT_LANGUAGE}{literal}.lang.json"
		},
		
		columns: [
	    	{"orderable":false},null,null,null,null
	  	],
	});
	
	// wait for the table to be initialized
	listTable.columns().every(function (idx) {
		var that = this,
			header = this.header();
		
		// prevent propagation
		jQuery('input', header).on('click focus', function (event) {
			return false;
		});

		// use keypress, since the th has a listener on it and fires the redraw
		jQuery('input', header).on('keypress', function (event) {
			if (event.type == 'keypress' && event.keyCode == 13 && that.search() !== this.value) {
				that.search(this.value).draw();
				return false;
			}
		});
		
	});
});
{/literal}
</script>