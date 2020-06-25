{*/*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************/*}
 
{* crmv@140887 *}

{if empty($IN_LOGIN)}
	{literal}
	<script type="text/javascript">
		var mainContainer = jQuery('body').get(0);
		var wrapperHeight = parseInt(visibleHeight(mainContainer));
		
		jQuery('#mainContent').css('min-height', wrapperHeight + 'px');
		
		if (window.Theme) {
			Theme.hideLoadingMask();
		}
	</script>
	{/literal}
	
	</div> <!-- #mainContent -->
	</div> <!-- #mainContainer -->
{/if}

</body>
</html>
