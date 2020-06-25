{* crmv@197575 *}
<link
	href="include/js/grapesjs/css/roboto-slab.css"
	rel="stylesheet"
/>

<link href="include/js/grapesjs/css/grapes.min.css" rel="stylesheet" />
<link
	href="include/js/grapesjs/grapesjs-preset-newsletter.css"
	rel="stylesheet"
/>

{literal}
<style>
	/* We can remove the border we've set at the beginnig */
	#gjs {
		border: none;
	}
	/* Theming */

	/* Primary color for the background */
	.gjs-one-bg {
		background-color: #16556F;	
	}

	/* Secondary color for the text color */
	.gjs-two-color {
		color: rgba(255, 255, 255, 0.7);
	}

	/* Tertiary color for the background */
	.gjs-three-bg {
		background-color: #ec5896;
		color: white;
	}

	.gjs-btnt.gjs-pn-active,
	.gjs-color-active,
	.gjs-pn-btn.gjs-pn-active,
	.gjs-pn-btn:active,
	.gjs-block:hover {
  		color: #d38600; 
	}
	#gjs-pn-views .gjs-pn-active {
		color: rgba(255, 255, 255, 0.9);
		border-bottom: 2px solid #d38600;
		border-radius: 0; 
	}
</style>
{/literal}

<script async src="{"include/js/jquery.js"|resourcever}"></script>
<script async src="{"include/js/general.js"|resourcever}"></script>

<div id="gjs-container">
	<div id="gjs"></div>
</div>

<textarea id="old_content" style="display:none">{$CONTENT}</textarea>


<script src="include/js/grapesjs/jquery-3.4.1.min.js"></script>
<script src="include/js/grapesjs/popper.min.js"></script>
<script src="include/js/grapesjs/bootstrap.min.js"></script>

<script src="include/js/grapesjs/grapes.min.js"></script>
<script src="include/js/grapesjs/grapesjs-preset-newsletter.min.js"></script>

<script src="modules/SDK/src/Grapes/Grapes.js"></script>

<script type="text/javascript">
	{if $ALL_VARIABLES}
		VTE.GrapesEditor.templateVariables = {$ALL_VARIABLES|replace:"'":"\'"};
	{else}
		VTE.GrapesEditor.templateVariables = {ldelim}{rdelim};
	{/if}

	VTE.GrapesEditor.tpl_id = '{$TPL_ID}';
	VTE.GrapesEditor.images_uploaded = '{$IMAGES_UPLOADED}';
	VTE.GrapesEditor.upload_endpoint = '{$UP_ENDPOINT}';
	VTE.GrapesEditor.vte_csrf_token = '{$CSRF_TOKEN}';
	VTE.GrapesEditor.images_folder = '{$IMAGES_FOLDER}';
	VTE.GrapesEditor.vte_site_url = '{$SITE_URL}';

	VTE.GrapesEditor.initialize();

	{if $TPL_SUBJECT neq ''}
		parent.jQuery('#nlw_template_subject').val("{$TPL_SUBJECT}");
	{/if}
</script>