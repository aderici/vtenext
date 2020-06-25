/* crmv@168103 */

/* if you want to deprecate non core functions, do it here */

(function() {
	// deprecate scriptaculous effects
	
	if (window.Effect) {
		Effect.Fade = VTE.deprecateFn('Effect.Fade', 'Please use jQuery.fadeOut');
		Effect.Appear = VTE.deprecateFn('Effect.Appear', 'Please use jQuery.fadeIn');
		Effect.Puff = VTE.deprecateFn('Effect.Puff', 'Please use jQuery.fadeOut');
		Effect.Grow = VTE.deprecateFn('Effect.Grow');
	}
	
	// crmv@192014
	// deprecate Drag library since jQuery.draggable can do the same
	if (window.Drag) {
		Drag.init = VTE.deprecateFn('Drag.init', 'Please use jQuery.draggable');
	} else {
		// polyfill for old code calling Drag.init, will be removed in the future
		window.Drag = {
			init: function(handle, root) {
				VTE.logDeprecated('Drag.init', 'Please use jQuery.draggable, this is only a limited polyfill');
				jQuery(root).draggable({handle: handle});
			}
		}
	}
	
	// deprecate Sortable library since jQuery.sortable can do the same
	if (window.Sortable) {
		Sortable.create= VTE.deprecateFn('Sortable.create', 'Please use jQuery.sortable');
	} else {
		// polyfill for old code calling Drag.init, will be removed in the future
		window.Sortable = {
			create: function(elid, opts) {
				VTE.logDeprecated('Sortable.create', 'Please use jQuery.draggable, this is only a limited polyfill');
				jQuery('#'+elid).sortable({
					items: '> ' + (opts.tag || '*'),
					handle: opts.handle ? '.' + opts.handle : false,
					update: opts.onUpdate
				});
			},
			serialize: function(sel) {
				var list = jQuery('#'+sel).sortable('toArray').map(function(id) {
					return sel+'[]=' + id.replace(/^stuff_/, '');
				});
				return list.join('&');
			}
		}
	}
	// crmv@192014e
})();
