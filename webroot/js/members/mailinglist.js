$(function() {
	
	$.linkize('.table.interactive tbody tr');
	
	$('#MemberAddQuickForm').ajaxForm(
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
	
	
	$('#MemberMailinglistActionFormSubmit').on('click', function(e) {
		if($('#MemberAction').val() == 'bulkDelete') {
			customConfirm(
				'Sei sicuro di voler eliminare gli elementi selezionati?',
				function() {
					$('#MemberMailinglistActionForm').submit();
				}
			);
		}
		else if($('#MemberAction').val() == 'bulkUnsubscribe') {
			customConfirm(
				'Sei sicuro di voler disiscrivere i membri selezionati?',
				function() {
					$('#MemberMailinglistActionForm').submit();
				}
			);
		}
		else {
			$('#MemberMailinglistActionForm').submit();
		}
	});
	
});
