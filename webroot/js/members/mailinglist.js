$(function() {
	
	$.linkize('.table.interactive tbody tr');
	
	$('#MemberMailinglistForm').ajaxForm(
		function(data) {
			data = JSON.parse(data);
			if(data.result.status) {
				var result = $('<span class="success-message" style="height:30px; display:inline-block; vertical-align:middle;">'+data.result['message']+'</span>');
				$('#newMemberTable').removeClass('hide');
				//var created = Date.parse(data.result.new.Member.created);
				//created = date('d/m/Y H:i:s', created/1000);
				var created = data.result['new'].Member.createdUserTimeZone;
				$('#newMemberTable tbody').append('<tr data-url="'+BASEURL+'members/view/'+data.result['new'].Member.id+'" data-target="_blank"><td>'+data.result['new'].Member.email+'</td><td>'+created+'</td></tr>');
				$('#membersCount').html(parseInt($('#membersCount').html())+1);
			}
			else {
				var result = $('<span class="error-message" style="height:30px; display:inline-block; vertical-align:middle;">'+data.result['message']+'</span>');
			}
			$('#quickAddResult').empty().append(result).show();
			$('#MemberEmail').val('');
			setTimeout("$('#quickAddResult').fadeOut(1000, function() { $('#quickAddResult').empty(); })", 4000);
		},
		function() {
			var result = $('<span class="error-message" style="height:30px; display:inline-block; vertical-align:middle;">Errore Inserimento</span>');
			
			$('#quickAddResult').empty().append(result).show();
			$('#MemberEmail').val('');
			setTimeout("$('#quickAddResult').fadeOut(1000, function() { $('#quickAddResult').empty(); })", 4000);
		}
	);
});
