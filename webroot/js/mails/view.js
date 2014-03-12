$(function() {
	
	$.linkize('.table.interactive tbody tr');
	
	$('#MailViewActionFormSubmit').on('click', function(e) {
		if($('#SendingAction').val() == 'bulkDelete') {
			customConfirm(
				'Sei sicuro di voler eliminare gli elementi selezionati?',
				function() {
					$('#MailViewActionForm').submit();
				}
			);
		}
		else {
			$('#MailViewActionFormt').submit();
		}
	});
	
});
