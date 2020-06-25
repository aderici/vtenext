{*********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * Portions created by CRMVILLAGE.BIZ are Copyright (C) CRMVILLAGE.BIZ.
 * All Rights Reserved.
 ********************************************************************************}

{* crmv@97692 *}
{* crmv@104853 *}
{* crmv@115268 *}

<script type="text/javascript">
var gVTModule = '{$smarty.request.module|@vtlib_purify}';
var fieldname = new Array({$VALIDATION_DATA_FIELDNAME});
var fieldlabel = new Array({$VALIDATION_DATA_FIELDLABEL});
var fielddatatype = new Array({$VALIDATION_DATA_FIELDDATATYPE});
var fielduitype = new Array({$VALIDATION_DATA_FIELDUITYPE}); // crmv@83877
var fieldwstype = new Array({$VALIDATION_DATA_FIELDWSTYPE}); //crmv@112297
</script>

<span id="crmspanid" style="display:none;position:absolute;" onmouseover="show('crmspanid');">
   <a class="edit" href="javascript:;">{$APP.LBL_EDIT_BUTTON}</a>
</span>

<table border=0 cellspacing=0 cellpadding=0 width=100% align=center>
<tr>
	<td valign="top" width=100%>
		<table border=0 cellspacing=0 cellpadding=0 width=100% align=center>
			<tr>
				<td valign="top">                
					<table border=0 cellspacing=0 cellpadding=0 width=100%>
						<tr>
							{* MAIN COLUMN (fields and related) *}
							<td align=left valign="top">
								<div style="padding:5px;">
									<form action="index.php" method="post" name="DetailView" id="form">
									<input type="hidden" name="__csrf_token" value="{$CSRF_TOKEN}"> {* crmv@171581 *}
										{include file='DetailViewHidden.tpl'}
										<div id="DetailViewBlocks">
											{include file="DetailViewBlocks.tpl" SHOW_DETAILS_BUTTON=false}
										</div>
									</form>
								</div>
								{include file='RelatedListsHidden.tpl'}	{* crmv@54245 *}
								<div id="RelatedLists" {if empty($RELATEDLISTS)}style="display:none;"{/if}>
									{include file='RelatedListNew.tpl' PIN=true}
								</div>
								<div id="DynamicRelatedList" style="display:none;"></div>
								</form>	{* crmv@54245 close form opened in RelatedListsHidden.tpl *}
								{* vtlib Customization: Embed DetailViewWidget block:// type if any *}
								{include file='DetailViewWidgets.tpl'}
								{* END *}
							</td>
							{* RIGHT COLUMN (buttons, widget, turbolift, ...) *}
							{if $SHOW_TURBOLIFT neq 'no'}
								<td width="22%" valign="top" style="padding:5px 5px 0px 0px;" id="turboLiftContainer"> {* crmv@43864 *}
									{include file="DetailViewActions.tpl"}
									{include file='Turbolift.tpl'}
								</td>
							{/if}
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</td>
</tr>
</table>

{include file="DropArea.tpl"}
