{********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * Portions created by CRMVILLAGE.BIZ are Copyright (C) CRMVILLAGE.BIZ.
 * All Rights Reserved.
 ********************************************************************************}

{if $TAG_CLOUD_DISPLAY eq 'true'}
	<!-- Tag cloud display -->
	<div style="padding-top:5px;">
		<table border=0 cellspacing=0 cellpadding=0 width=100% class="tagCloud">
			<tr>
				<td class="text-center">
					<div id="tagdiv" style="display:visible;">
						<form method="POST" action="javascript:void(0);" onsubmit="return VTE.TagCloud.tagvalidate('{$ID}','{$MODULE}');">
							<input class="detailedViewTextBox input-inline" type="text" id="txtbox_tagfields" name="textbox_First Name" value="" />
							&nbsp;&nbsp;
							<i class="vteicon valign-middle">cloud_queue</i>
							<button name="button_tagfileds" type="submit" class="crmbutton save">{$APP.LBL_TAG_IT}</button>
						</form>
					</div>
				</td>
			</tr>
			<tr>
				<td class="tagCloudDisplay" valign=top><span id="tagfields">{$ALL_TAG}</span></td>
			</tr>
		</table>
	</div>
	<!-- End Tag cloud display -->
	<script type="text/javascript">
		VTE.TagCloud.getTagCloud('{$ID}', '{$MODULE}');
	</script>
{/if}
