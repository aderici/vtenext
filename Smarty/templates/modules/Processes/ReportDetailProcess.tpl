{*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************}
{* crmv@193096 *}

{include file='SmallHeader.tpl' HEADER_Z_INDEX=1 PAGE_TITLE="SKIP_TITLE" HEAD_INCLUDE="all" BUTTON_LIST_CLASS="navbar navbar-default"}

{SDK::checkJsLanguage()}	{* crmv@sdk-18430 *} {* crmv@181170 *}
{include file='CachedValues.tpl'}	{* crmv@26316 *}

{include file="modules/Processes/ProcessGraph.tpl"}
<script type="text/javascript">
jQuery('#ProcessGraph').show();
var data = JSON.parse('{$GRAPHINFO}');
{literal}
bpmnLoad(window.BpmnJS, data, function(){
	jQuery('#status').hide();
});
{/literal}
</script>