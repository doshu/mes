/*
	jquery plugin per creare pie chart
	options.radius = raggio del pie
	options.type = [percentage oppure value] = modo si visualizzare i valori
	options.color = colore del pie
	options.background = colore del pie di sfondo
	options.textColor = colore del testo al centro
	options.fontSIze = dimensione testo al centro

*/


$.fn.doshupie = function(options) {

	var that = this[0];
	$(that).empty()
	var opt = {
		radius:200,
		type:'value',
		color:'#ff0000',
		background:'#CCC',
		textColor:'#333',
		fontSize:20
	};
	
	$.extend(opt, options);
	
	var total = $(this).attr('data-total') || 100;
	var value = $(this).attr('data-value') || 0;
	var perc = value/(total/100);
	var r = new Raphael(that, opt.radius, opt.radius);
	r.customAttributes.arcseg = function( cx, cy, radius, start_r, finish_r ) {
		var start_x = cx + Math.cos( start_r ) * radius,
		    start_y = cy + Math.sin( start_r ) * radius,
		    finish_x = cx + Math.cos( finish_r ) * radius,
		    finish_y = cy + Math.sin( finish_r ) * radius,
		    path;

        path =
        [
            [ "M", start_x, start_y ],
            [ "A", radius, radius, finish_r - start_r,
                    finish_r - start_r > Raphael.rad( 180 ) ? 1 : 0,
                    finish_r > start_r ? 1 : 0,
                    finish_x, finish_y ],
        ];

        return { path: path };
    };
    
    
	var center = opt.radius/2;
	
	var bg = r.circle(center, center, (opt.radius/2)-15).attr({
		stroke: opt.background,
		'stroke-width': 15,
	});
	
	if(perc > 0) {
		if(perc >= 100) {
			var arc = r.circle(center, center, (opt.radius/2)-15).attr({
				stroke: opt.color,
				'stroke-width': 15,
			});
		}
		else {
			var arc = r.path().attr({
				stroke: opt.color, 
				'stroke-width': 15,
				arcseg: [ center, center, (opt.radius/2)-15, (1.5 * Math.PI), (1.5 * Math.PI)+(((2 * Math.PI)*perc)/100) ] 
			});
		}
	}
	
	var text = "";
	if(opt.type == 'value') {
		text = value+'/'+total;
	}
	else if(opt.type == 'percentage') {
		text = perc+'%';
	}
	
	var svgText = r.text(center, center, text);
	svgText.attr({'font-size':opt.fontSize+'px arial'});
	
}
