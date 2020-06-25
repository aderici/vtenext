/*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************/

/* crmv@198024 */

(function($) {
	
	// extends autocomplete to be able to have categories
	$.widget( "custom.vteautocomplete", $.ui.autocomplete, {
		_create: function(cfg) {
			this._super();
			if (this.options.useCategories) {
				this.widget().menu( "option", "items", "> :not(.ui-autocomplete-category)" );
			}
		},
		_renderMenu: function( ul, items ) {
			var me = this,
				currentCategory = "";
				
			if (!this.options.useCategories) return this._super(ul, items);
			 
			$.each( items, function( index, item ) {
				var li;
				if ( item.category != currentCategory ) {
					ul.append( "<li class='ui-autocomplete-category'>" + item.category + "</li>" );
					currentCategory = item.category;
				}
				li = me._renderItemData( ul, item );
				if ( item.category ) {
					li.addClass('ui-menu-item-indented');
				}
			});
		}
    });
	
})(jQuery);
