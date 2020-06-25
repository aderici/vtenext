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
<script
	language="JavaScript" type="text/javascript"
	src="modules/{$MODULE}/{$MODULE}.js"></script>
 {include file='SetMenu.tpl'}
 {include file='Buttons_List.tpl'} <!-- crmv@30683  -->
 {* crmv@30683 *}
		<table border=0 cellspacing=0 cellpadding=5 width=100% class="settingsSelUITopLine">
				<tr>
					<td width=50 rowspan=2 valign=top><img src="{'portal_icon.png'|@vtiger_imageurl:$THEME}" alt="{$MODSETTINGS.LBL_CUSTOMER_PORTAL}" width="48" height="48" border=0 title="{$MODSETTINGS.LBL_CUSTOMER_PORTAL}"></td>
					<td class=heading2 valign=bottom><b> {$MODSETTINGS.LBL_SETTINGS} > {$MODSETTINGS.LBL_CUSTOMER_PORTAL} </b></td> <!-- crmv@30683 -->
				</tr>
				<tr>
					<td valign=top class="small">{$MODSETTINGS.PORTAL_EXTENSION_DESCRIPTION}</td>
				</tr>
			</table>
			<br>
 {* crmv@30683e *}
<table border=0 cellspacing=0 cellpadding=0
	width="100%" align=center>
	<!-- crmv@30683 -->
	<tr>
		<td valign=top><img
			src="{'showPanelTopLeft.gif'|@vtiger_imageurl:$THEME}"></td>
		<td class="showPanelBg" valign="top" width="100%"
			style="padding: 5px;">
			<!-- crmv@30683 -->
			<div class="small" style="width: 100%; position: relative;">
				<table border=0 cellspacing=1 cellpadding=0 width="100%"
					class="lvtBg">
					<tr>
						<td>
							<table border=0 cellspacing=0 cellpadding=2 width="100%"
								class="small">
								<tr>
									<td style="padding-right: 20px" nowrap align="right"></td>
								</tr>
							</table>

							<table border=0 cellspacing=0 cellpadding=0 width="95%"
								class="small">
								<!-- Tab Links -->
								<tr>
									<td>
										<table border=0 cellspacing=0 cellpadding=3 width="100%"
											class="small">
											<tr>
												<td class="dvtTabCache" style="width: 10px" nowrap></td>
												<td class="dvtSelectedCell" align="left" nowrap>{$MOD.LBL_BASIC_SETTINGS}</td>
												<td class="dvtTabCache" style="width: 10px"></td>
												<td class="dvtUnSelectedCell" align="left" nowrap><a
													href="index.php?module=CustomerPortal&action=AdvancedSettings&parenttab={$CATEGORY}">{$MOD.LBL_ADVANCED_SETTINGS}</a>
												</td>
												<td class="dvtTabCache" width="100%">&nbsp;</td>
											</tr>
										</table>
									</td>
								</tr>

								<!-- Acutal Contents -->
								<tr>
									<td>
										<table border=0 cellspacing=0 cellpadding=10 width="100%"
											class="dvtContentSpace" style='border-bottom: 0'>
											<tr>
												<td>
													<div id='portallist'>{include
														file="modules/CustomerPortal/BasicSetttingsContents.tpl"}
													</div>
												</td>
											</tr>
										</table>
									</td>
								</tr>

							</table>

						</td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
</table>
