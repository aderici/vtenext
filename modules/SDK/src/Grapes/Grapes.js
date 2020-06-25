/* crmv@197575 */

window.VTE = window.VTE || {};

VTE.GrapesEditor = VTE.GrapesEditor || {

	editor: null,
	busy: false,

	initialized: false,

	templateVariables: null,
	
	draft_time: 60000,
	tpl_id: null,
	images_folder: null,
	upload_endpoint: null,
	images_uploaded: [],
	vte_csrf_token: null,
	vte_site_url: null,

	initialize: function() {
		var me = this;
		
		if (me.initialized) return;

		me.initialized = false;

		me.showLoader();
		me.initEvents();
	},

	initEvents: function() {
		var me = this;

		jQuery(document).ready(me.onDocumentLoaded);
		jQuery(document).on('resize', me.onDocumentResize);
		
	},

	onDocumentLoaded: function() {
		var me = VTE.GrapesEditor;

		me.initGrapesEditor();
		me.updateBodyPadding();
		me.hideLoader();

		//saving Draft every X
		/*
		setInterval(function(){
			
			me.saveTplDraft(me.tpl_id, me.editor.runCommand('gjs-get-inlined-html'));
			
		}, me.draft_time);
		*/

		me.initialized = true;
	},

	onDocumentResize: function() {
		var me = VTE.GrapesEditor;

		me.updateBodyPadding();
	},

	initGrapesEditor: function() {
		var me = this;

		var editor = grapesjs.init({
			container: "#gjs",
			storageManager: false,
			plugins: [ 'gjs-preset-newsletter'],
			pluginsOpts: {
				'gjs-preset-newsletter': {
					modalTitleImport: "Import template"					
				},
			},
			noticeOnUnload: false,
			assetManager: {
				upload: me.upload_endpoint,
				uploadName: 'newfile',
				multiUpload: false,
				embedAsBase64: false,
				disableUpload: false,
				params: {"__csrf_token": me.vte_csrf_token, "currentpath":me.images_folder, "mode":"add"}
			},
			i18n: {
				detectLocale: true, // by default, the editor will detect the language
				localeFallback: 'en', // default fallback
			}
		});

		editor.Keymaps.add('ns:redo', '⌘+y, ctrl+y', 'core:redo');
		editor.Keymaps.add('ns:fullscreen', '⌘+shift+f, ctrl+shift+f', 'core:fullscreen');
		//console.log('keys: ', editor.Keymaps.getAll());
		//console.log('commands: ', editor.Commands.getAll());
		
		//Possibility of customisation
		//jQuery('.fa-cog').hide();	//remove Component Settings
		//jQuery('.fa-bars').hide();	//remove Open Layer Manager

		var blockManager = editor.BlockManager;
		blockManager.add('olist', {
			label: 'Ordered List',
			attributes: {
				class: "fa fa-list-ol"
			},
			content: '<ol><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li></ol>',
		});
		blockManager.add('ulist', {
			label: 'Unordered List',
			attributes: {
				class: "fa fa-list-ul"
			},
			content: '<ul><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li></ul>',
		});
		blockManager.add('unsub-link', {
			label: 'Unsubscription Link',
			attributes: {
				class: "fa fa-link"
			},
			content: '<div>$Newsletter||tracklink#unsubscription$</div>',
		});
		blockManager.add('preview-link', {
			label: 'Preview Link',
			attributes: {
				class: "fa fa-link"
			},
			content: '<div>$Newsletter||tracklink#preview$</div>',
		});
		
		blockManager.get('button').set({
			content: '<a style="color: white; font-size: 14px; font-weight: bold; line-height: 24px; padding: 12px 24px; text-align: center; text-decoration: none !important; border-radius: 4px; display: inline-block; background-color: #41637e;" href="">Button</a>'
		})
		

		//ADD GDPR COMPONENTS
		/*
		$custom||gdpr_access_verify_link$	GDPR Access - Verify link	
		$custom||gdpr_access_login_link$	GDPR Access - Access link	
		$custom||gdpr_update_confirm_link$	GDPR Update - Confirm link	
		$custom||gdpr_support_request_sender$	GDPR Support Request - Sender	
		$custom||gdpr_support_request_subject$	GDPR Support Request - Subject	
		$custom||gdpr_support_request_description$	GDPR Support Request - Description	
		*/

		editor.DomComponents.getWrapper().set('content', '');
		if(me.tpl_id != null){
			editor.setComponents(jQuery('#old_content').val());
		}
		
		//selezione in automatico del tab Settings al trascinamento di un link
		editor.on('component:mount', function(model) {
			if(model.is('link')){
				editor.select(model);
				const openBl = editor.Panels.getButton('views', 'open-tm');
				openBl && openBl.set('active', 1)
			}
		});

		// The upload is started
		editor.on('asset:upload:start', () => {
			parent.VtigerJS_DialogBox.progress();
		});
		
		// The upload is ended (completed or not)
		editor.on('asset:upload:end', (response) => {
			parent.VtigerJS_DialogBox.hideprogress();
		});

		// Error handling
		editor.on('asset:upload:error', (err) => {
			console.log("Error on uploading image, probably cross-origin: \n"+err);
			alert(sprintf(parent.alert_arr.GRAPES_CO_WARNING, me.vte_site_url))
		});
		
		// Do something on response
		editor.on('asset:upload:response', (response) => {
			var textarea = response;
			if(textarea != ''){
				var res = eval('('+jQuery(textarea).html()+')');
				if(res['Code'] != null && res['Code'] == 0){
					var image_file = me.vte_site_url+'/'+res['Path']+res['Name'];
					var folder = '';
					me.editor.AssetManager.add({src: image_file, category: folder});
				}
			}
		});

		me.editor = editor;
		
		me.customizeEditor(editor);
		me.loadAssets(me.images_uploaded);
	},

	customizeEditor: function(editor) {
		var me = this;

		editor.RichTextEditor.add("template-vars-module", {
			icon: `
				<div class="template-vars-wrapper">
					<span class="template-vars-label">Module</span>
					<select class="gjs-field template-vars-input template-vars-module" style="background-color: white;"></select>
				</div>
			`,
			event: "change",
			result: me.onChangeTemplateVariablesModule
		});

		me.populateModuleInput();

		editor.RichTextEditor.add("template-vars-field", {
			icon: `
				<div class="template-vars-wrapper">
					<span class="template-vars-label">Field</span>
					<select class="gjs-field template-vars-input template-vars-field" style="background-color: white;"></select>
				</div>
			`,
			event: "change",
			result: me.onChangeTemplateVariablesField
		});

		editor.RichTextEditor.add("template-vars-button", {
			icon: `Insert`,
			event: "click",
			result: me.onClickTemplateVariablesButton
		});

		me.addEditCodeButton(document, editor);
	},

	populateModuleInput: function() {
		var me = this;

		var moduleInput = me.getTemplateVarsInput('template-vars-module');
		var options = Object.keys(me.templateVariables) || [];
		
		var items = '';
		items += '<option value="-- None --">-- None --</option>';
		jQuery.each(options, function(key, value) {
			items += '<option value="' + value + '">' + value + '</option>';
		});

		moduleInput.html(items);
	},

	onChangeTemplateVariablesModule: function(rte, action) {
		var me = VTE.GrapesEditor;
		
		var moduleInput = me.getTemplateVarsInput('template-vars-module');
		var selectedValue = moduleInput.val();

		var options = me.templateVariables[selectedValue] || [];
		
		var items = '';
		items += '<option value="-- None --">-- None --</option>';
		jQuery.each(options, function(key, value) {
			items += '<option value="' + value[1] + '">' + value[0] + '</option>';
		});

		var fieldInput = me.getTemplateVarsInput('template-vars-field');
		fieldInput.html(items).val('');
	},

	onChangeTemplateVariablesField: function(rte, action) {
	},

	onClickTemplateVariablesButton: function(rte, action) {
		var me = VTE.GrapesEditor;

		var fieldInput = me.getTemplateVarsInput('template-vars-field');
		rte.insertHTML(fieldInput.val());
	},

	showLoader: function() {
		this.busy = true;
		jQuery('body').addClass('loading');
	},

	hideLoader: function() {
		this.busy = false;
		jQuery('body').removeClass('loading');
	},

	updateBodyPadding: function() {
		var navbarHeight = jQuery('.vtewiznavbar').outerHeight();
		jQuery('body').css('padding-top', parseInt(navbarHeight) + 'px');
	},

	getTemplateVarsInput: function(name) {
		var me = this;

		var action = me.editor.RichTextEditor.get(name);
		if (action) {
			var btn = action.btn;
			return jQuery(btn).find('.template-vars-input');
		}

		return null;
	},

	addEditCodeButton: function(document, editor){

		var pfx = editor.getConfig().stylePrefix;
		var modal = editor.Modal;
		var cmdm = editor.Commands;
		var codeViewer = editor.CodeManager.getViewer('CodeMirror').clone();
		var pnm = editor.Panels;
		var container = document.createElement('div');
		var btnEdit = document.createElement('button');

		codeViewer.set({
			codeName: 'htmlmixed',
			readOnly: 0,
			theme: 'hopscotch',
			autoBeautify: true,
			autoCloseTags: true,
			autoCloseBrackets: true,
			lineWrapping: true,
			styleActiveLine: true,
			smartIndent: true,
			indentWithTabs: true
		});

		btnEdit.innerHTML = 'Edit Code';
		btnEdit.className = pfx + 'btn-prim ' + pfx + 'btn-import';
		btnEdit.onclick = function() {
			var code = codeViewer.editor.getValue();
			editor.DomComponents.getWrapper().set('content', '');
			editor.setComponents(code.trim());
			modal.close();
		};

		cmdm.add('html-edit', {
			run: function(editor, sender) {
				sender && sender.set('active', 0);
				var viewer = codeViewer.editor;
				modal.setTitle('Edit code');
				if (!viewer) {
					var txtarea = document.createElement('textarea');
					container.appendChild(txtarea);
					container.appendChild(btnEdit);
					codeViewer.init(txtarea);
					viewer = codeViewer.editor;
				}
				var InnerHtml = editor.getHtml();
				var Css = editor.getCss();
				modal.setContent('');
				modal.setContent(container);
				codeViewer.setContent(InnerHtml + "<style>" + Css + '</style>');
				modal.open();
				viewer.refresh();
			}
		});

		pnm.addButton('options',
			[
				{
					id: 'edit',
					className: 'fa fa-edit',
					command: 'html-edit',
					attributes: {
						title: 'Edit Code'
					}
				}
			]
		);

	},

	loadAssets: function(files){
		var me = this;
		
		files = eval(files) || [];

		for(i = 0; i < files.length; i++){
			me.editor.AssetManager.add(files[i]);
		}		

	},
	
	saveTplDraft: function(templateid, content){
		
		var params = {
			'mode': 'save_draft',
			'templateid': templateid				
		}
		
		jQuery.ajax({
			url: 'index.php?module=SDK&action=SDKAjax&file=src/Grapes/Grapes&' + jQuery.param(params),
			data: {
				'content': content
			},
			type: 'POST',
			success: function(result){
				console.log('success: ', result);
			},
			fail: function(){
				console.log('fail');
			}
		});		
	},

	showGrapesDiv: function(list_id, templateid){
		
		jQuery('#nlwTopButtons').hide();
		jQuery('#nlw_templateDetails').hide();
		jQuery('#nlw_templateEditCont').show();
		
		jQuery('#nlw_templateEditId').val(templateid);
		jQuery('#nlw_template_name').val('');
		jQuery('#nlw_template_description').val('');
		//jQuery('#nlw_template_subject').val('');
		
		jQuery('#grapes_editor').attr('src', 'index.php?module=SDK&action=SDKAjax&file=src/Grapes/Grapes&mode=load_body&is_wizard=1&templateid='+templateid);
	}

};