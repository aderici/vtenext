{*/*+*************************************************************************************
* The contents of this file are subject to the VTECRM License Agreement
* ("licenza.txt"); You may not use this file except in compliance with the License
* The Original Code is: VTECRM
* The Initial Developer of the Original Code is VTECRM LTD.
* Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
* All Rights Reserved.
***************************************************************************************/*}

{* crmv@140887 *}

<div id="Buttons_List_3">
	<table id="bl3" border=0 cellspacing=0 cellpadding=2 width=100% class="small">
		<tr>
			<td>
                {include file="Buttons_List_Contestual.tpl"}
			</td>
		</tr>
	</table>
</div>

<script type="text/javascript">
	calculateButtonsList3();
	{if $smarty.request.query eq true && $smarty.request.searchtype eq 'BasicSearch' && !empty($smarty.request.search_text)}
		clearText(jQuery('#basic_search_text'));
		jQuery('#basic_search_text').data('restored', false); // crmv@104119
		jQuery('#basic_search_text').val('{$smarty.request.search_text}');
		basic_search_submitted = true;
	{/if}
</script>
