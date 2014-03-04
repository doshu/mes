var okRepeat = 2000;
var emptyRepeat = 5000;
var failRepeat = 1000;


function updateSending() {

	$.ajax({
		url: BASEURL+'sendings/checkSendings.json',
		cache:false,
		type:'POST',
		dataType:'json'
	})
	.done(function(data) {
		
		var repeat = emptyRepeat;
		//if there are on sending
		if(data.dashboard.sending.length) {
			$('#sendingTable tbody').empty(); //remove all rows
			$('#sendingTable thead').show(); //show thead
			repeat = okRepeat;
		}
		else {
			$('#sendingTable thead').hide();
			$('#sendingTable tbody').empty();
			$('#sendingTable tbody').append(
				'<tr class="empty-placeholder"><td><h4 class="text-center">Nessun invio in corso</h4></td></tr>'
			);
		}
		
		var statTotal = 0;
		var statDone = 0;
		
		$(data.dashboard.sending).each(function(i) {
			
			var sended = data.dashboard.sending[i].Recipient.done || 0;
			var total = data.dashboard.sending[i].Recipient.total || 0;
			var withError = data.dashboard.sending[i].Recipient.withError || 0;
			
			statTotal += parseInt(total);
			statDone += parseInt(sended);
			
			var progress = '<span class="doneField">'+sended+'</span>'+'<span> / </span>'+'<span class="totalField">'+total+'</span>';
			var inner = $('<td>'+data.dashboard.sending[i].Mail.name+'</td><td>'+progress+'</td>');
			var row = $('<tr></tr>').attr({
				'data-sending':data.dashboard.sending[i].Sending.id,
				'data-url':BASEURL+'sendings/view/'+data.dashboard.sending[i].Sending.id,
				'data-target':'_blank'
			});
			
			if(data.dashboard.sending[i].Sending.errors) {
				row.addClass('error-message');
				$(inner[0]).prepend('<i class="icon icon-warning-sign"></i> ');
			}
			row.append(inner);
			$('#sendingTable tbody').append(row);
		});
		
		
		if(data.dashboard.waiting.length) {
			$('#waitingTable tbody').empty(); //remove all rows
			$('#waitingTable thead').show(); //show thead
			repeat = okRepeat;
		}
		else {
			$('#waitingTable thead').hide();
			$('#waitingTable tbody').empty();
			$('#waitingTable tbody').append(
				'<tr class="empty-placeholder"><td><h4 class="text-center">Nessun invio in attesa</h4></td></tr>'
			);
		}
		
		
		$(data.dashboard.waiting).each(function(i) {
			
			var inner = $(
				'<td>'+data.dashboard.waiting[i].Mail.name+'</td>\
				<td>'+data.dashboard.waiting[i].Sending.created_client+'</td>\
				<td>'+data.dashboard.waiting[i].Sending.time_client+'</td>'
			);
			var row = $('<tr></tr>').attr({
				'data-sending':data.dashboard.waiting[i].Sending.id,
				'data-url':BASEURL+'sendings/view/'+data.dashboard.waiting[i].Sending.id,
				'data-target':'_blank'
			});
			
			row.append(inner);
			$('#waitingTable tbody').append(row);
		});
		
		
		setTimeout(updateSending, repeat);
		$('#total').attr('data-total', statTotal).attr('data-value', statTotal - statDone).doshupie({color: '#FF3333'});
		
	})
	.fail(function() {
		setTimeout(updateSending, failRepeat);
	});
}


function createSpeedGraph() {

	
}

$(function() {
	
	$.linkize('.table.interactive tbody tr[data-url]');
	setTimeout(updateSending, okRepeat);
	
	createSpeedGraph();
	
	
	$('#total').doshupie({color: '#FF3333'});
});
