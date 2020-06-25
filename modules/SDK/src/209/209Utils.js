/* crmv@167371 */
if (typeof(Utils209) == 'undefined') {
	var Utils209 = {

		load: function() {
			if (window.wheelzoom) wheelzoom(document.querySelectorAll('.img_zoom'), {zoom: 0.1, maxZoom: 10});
		},
		
		doZoom: function(action,id){
			if(action == 'in'){
				jQuery('#'+id)[0].doZoomIn();
			}else if(action == 'out'){
				jQuery('#'+id)[0].doZoomOut();
			}
		}
	}
	
	jQuery(document).ready(function(){
		setTimeout(function(){
			Utils209.load();
		},200);
	});
}