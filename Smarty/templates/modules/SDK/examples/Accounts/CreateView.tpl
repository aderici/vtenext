{*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************}

<p style="color:blue;"><b>Accounts create view from SDK</b></p>

{literal}
<style type="text/css">
div {
	background-color: #d0f0f0;
}
</style>
{/literal}

{include file="salesEditView.tpl"}

{literal}
<script type="text/javascript">
	var healthblock = "Hospital Information";

	function showExtraBlock() {
		var bl = document.getElementsByName('block_'+healthblock);
		if (bl) {
			for (i=0; i<bl.length; ++i) {
				bl[i].style.display = '';
			}
		}
	}

	function hideExtraBlock() {
		var bl = document.getElementsByName('block_'+healthblock);
		if (bl) {
			for (i=0; i<bl.length; ++i) {
				bl[i].style.display = 'none';
			}
		}
	}

	function onchangeIndustry() {
		var ind = document.getElementsByName('industry');
		if (ind && ind.length > 0) {
			ind = ind[0];
			if (ind.tagName.toUpperCase() == 'SELECT') {
				sel = ind.selectedIndex;
				val = ind.options.item(sel);
				if (val && val.value == 'Hospitality') 
					showExtraBlock();
				else
					hideExtraBlock();
			}
		}
	}

	// register onchange handler
	var ind = document.getElementsByName('industry');
	if (ind && ind.length > 0) {
		ind = ind[0];
		if (ind.tagName.toUpperCase() == 'SELECT') {
			ind.onchange = onchangeIndustry;
			onchangeIndustry();
		}
	}
</script>
{/literal}
