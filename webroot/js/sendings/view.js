
var okRepeat = 2000;
var failRepeat = 1000;

function updateSending() {

	$.ajax({
		url: BASEURL+'sendings/checkSendingStatus/'+sending_id+'.json',
		cache:false,
		type:'POST',
		dataType:'json'
	})
	.done(function(data) {
		if(data.sending.Recipient.total) {
			if(data.sending.Recipient.sended) {
				$('#sendedChart').attr('data-total', data.sending.Recipient.total).attr('data-value', data.sending.Recipient.sended);
				$('#sendedChart').doshupie({radius:150, color: '#FF3333'});
			}
			if(data.sending.Recipient.opened) {
				$('#openedChart').attr('data-total', data.sending.Recipient.total).attr('data-value', data.sending.Recipient.opened);
				$('#openedChart').doshupie({radius:150, color:'#330099'});
			}
		}
		
		if(data.sending.Sending.errors && !$('#sending-error-container .sending-error').length) {
			$('#sending-error-container')
				.append('<span class="label label-danger sending-error">Rilevati errori durante l\'invio</span>');
		}
		
		if(typeof data.sending.Sending.status != "undefined") {
			if(!$('#sendingStatus').attr('data-status') != data.sending.Sending.status) {
				var _class = "";
				
				switch(parseInt(data.sending.Sending.status)) {
					case statusCode.waiting:
						_class = "";
					break;
					case statusCode.sending:
						_class = "label-info";
					break;
					case statusCode.completed:
						_class = "label-success";
					break;
					case statusCode.aborted:
						_class = "label-danger";
					break;
				}
				
				
				$('#sendingStatus')
					.attr('data-status', data.sending.Sending.status)
					.removeClass()
					.addClass('label')
					.addClass(_class);
			}
		}
		
		setTimeout(updateSending, okRepeat);
	})
	.fail(function() {
		setTimeout(updateSending, failRepeat);
	});
}


$(function() {

	$('#sendedChart').doshupie({radius:150, color: '#FF3333'});
	
	$('#openedChart').doshupie({radius:150, color:'#330099'});
	
	updateSending();
	
	
	$('#geoChart').vectorMap({
		map: 'world_mill_en',
		scaleColors: ['#C8EEFF', '#0071A4'],
		normalizeFunction: 'polynomial',
		hoverOpacity: 0.7,
		hoverColor: false,
		markerStyle: {
		  initial: {
		    fill: '#FF2222',
		    stroke: '#383f47'
		  }
		},
		backgroundColor: '#383f47',
		markers: geoChartDataset,
		zoomMax: 100
	  });
});
