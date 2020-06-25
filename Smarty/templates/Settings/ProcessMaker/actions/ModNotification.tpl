{* crmv@183346 *}
{include file='CachedValues.tpl'}
{include file='modules/SDK/src/Reference/Autocomplete.tpl'}

{include file='Settings/ProcessMaker/actions/Create.tpl' SKIP_EDITFORM=1}

<div id="editForm">
{include file='salesEditView.tpl' HIDE_BUTTON_LIST=1}
</div>

<script type="text/javascript">
jQuery(document).ready(function() {ldelim}
	ActionModNotificationScript.loadForm('ModNotifications','{$ID}','{$ELEMENTID}','{$ACTIONTYPE}','{$ACTIONID}');
{rdelim});
</script>
