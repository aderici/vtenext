{*
/*********************************************************************************
  ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
   * ("License"); You may not use this file except in compliance with the License
   * The Original Code is:  vtiger CRM Open Source
   * The Initial Developer of the Original Code is vtiger.
   * Portions created by vtiger are Copyright (C) vtiger.
   * All Rights Reserved.
  *
 ********************************************************************************/
*}

<script language="JAVASCRIPT" type="text/javascript" src="include/js/smoothscroll.js"></script>
<script type="text/javascript" src="{"modules/Settings/resources/AuditTrail.js"|resourcever}"></script>

<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%"> <!-- crmv@30683 -->
	<tbody>
		<tr>
			<td valign="top"></td>
			<td class="showPanelBg" style="padding: 5px;" valign="top" width="100%"> <!-- crmv@30683 -->
				<form action="index.php" method="post" name="AuditTrail" id="form" onsubmit="VtigerJS_DialogBox.block();">
					<input type='hidden' name='module' value='Settings'>
					<input type='hidden' name='action' value='AuditTrail'>
					<input type='hidden' name='return_action' value='ListView'>
					<input type='hidden' name='return_module' value='Settings'>
					<input type='hidden' name='parenttab' value='Settings'>

					{include file='SetMenu.tpl'}
					{include file='Buttons_List.tpl'} {* crmv@30683 *} 
				
					<!-- DISPLAY -->
					<table border=0 cellspacing=0 cellpadding=5 width=100% class="settingsSelUITopLine">
						<tr>
							<td width=50 rowspan=2 valign=top><img src="{'audit.gif'|resourcever}" alt="{$MOD.LBL_AUDIT_TRAIL}" width="48" height="48" border=0 title="{$MOD.LBL_AUDIT_TRAIL}"></td>
							<td class=heading2 valign=bottom><b> {$MOD.LBL_SETTINGS} > {$MOD.LBL_AUDIT_TRAIL}</b></td> <!-- crmv@30683 -->
						</tr>
						<tr>
							<td valign=top>{$MOD.LBL_AUDIT_TRAIL_DESC}</td>
						</tr>
					</table>
				
					<br>

					<table border=0 cellspacing=0 cellpadding=10 width=100%>
						<tr>
							<td>
								<table border=0 cellspacing=0 cellpadding=5 width=100% class="tableHeading">
									<tr>
										<td class="big" height="40px;" width="70%"><strong>{$MOD.LBL_AUDIT_TRAIL}</strong></td>
										<td align="center" width="30%">&nbsp;
											<span id="audit_info" class="crmButton cancel" style="display:none;"></span>
										</td>
									</tr>
								</table>
				
								<table border=0 cellspacing=0 cellpadding=0 width=100% class="listRow">
									<tr>
										<td valign=top>
											<table width="100%"  border="0" cellspacing="0" cellpadding="5">
												<tr>
													<td width="20%" nowrap class="cellLabel"><strong>{$MOD.LBL_ENABLE_AUDIT_TRAIL} </strong></td>
													<td width="80%" class="cellText">
														{if $AuditStatus eq 'enabled'}
															<input type="checkbox" checked name="enable_audit" onclick="VTE.Settings.AuditTrail.auditenabled(this)" />
														{else}
															<input type="checkbox" name="enable_audit" onclick="VTE.Settings.AuditTrail.auditenabled(this)" />
														{/if}
													</td>
												</tr>
												<tr valign="top">
													<td nowrap class="cellLabel"><strong>{$MOD.LBL_USER_AUDIT}</strong></td>
													<td class="cellText">
														<select name="user_list" id="user_list" class="detailedViewTextBox input-inline">
															{$USERLIST}
														</select>	
													</td>
													<td class="cellText" align=right nowrap>
														<button class="crmbutton edit" onclick="VTE.Settings.AuditTrail.exportAuditTrail();" type="button" name="button">{$MOD.LBL_EXPORT_AUDIT_TRAIL}</button> {* crmv@164355 *}
														<button class="crmbutton edit" onclick="VTE.Settings.AuditTrail.showAuditTrail();" type="button" name="button">{$MOD.LBL_VIEW_AUDIT_TRAIL}</button>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
			
					{* SetMenu.tpl *}
					</td>
					</tr>
					</table>
					</td>
					</tr>
					</table>
				</form>
			</td>
			<td valign="top"></td>
		</tr>
	</tbody>
</table>
