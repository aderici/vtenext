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

<button type="button" name="next" class="crmbutton create" onclick="location.href='index.php?module={$FOR_MODULE}&action=Import&return_module={$FOR_MODULE}&return_action=index'">
	{'LBL_IMPORT_MORE'|@getTranslatedString:$MODULE}
</button>

<button type="button" name="next" class="crmbutton cancel" onclick="return window.open('index.php?module={$FOR_MODULE}&action={$FOR_MODULE}Ajax&file=Import&mode=listview&start=1&foruser={$OWNER_ID}','test','width=700,height=650,resizable=1,scrollbars=0,top=150,left=200');">
	{'LBL_VIEW_LAST_IMPORTED_RECORDS'|@getTranslatedString:$MODULE}
</button>

{if $MERGE_ENABLED eq '0'}
	<button type="button" name="next" class="crmbutton delete" onclick="location.href='index.php?module={$FOR_MODULE}&action=Import&mode=undo_import&foruser={$OWNER_ID}'">
		{'LBL_UNDO_LAST_IMPORT'|@getTranslatedString:$MODULE}
	</button>
{/if}

<button type="button" name="cancel" class="crmbutton edit" onclick="location.href='index.php?module={$FOR_MODULE}&action=index'">
	{'LBL_FINISH_BUTTON_LABEL'|@getTranslatedString:$MODULE}
</button>
