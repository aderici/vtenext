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

<table width="100%"  border="0" cellspacing="0" cellpadding="0" >
	<tr><td colspan="2" class="mailSubHeader" height=25><b>{$MOD.LBL_FEEDS_LIST} {$TITLE}</b></td></tr>
	<tr class="hdrNameBg">
		<td valign=top height=25>
			<button type="button" name="delete" class="crmbutton delete" onClick="VTE.Rss.DeleteRssFeeds('{$ID}');">{$MOD.LBL_DELETE_BUTTON}</button>
		</td>
		<td align="right">
			<button type="button" name="setdefault" class="crmbutton create" onClick="VTE.Rss.makedefaultRss('{$ID}');">{$MOD.LBL_SET_DEFAULT_BUTTON}</button>
		</td>
	</tr>
	<tr><td colspan="2" align="left"><div id="rssScroll">{$RSSDETAILS}</div></td></tr>
</table>
