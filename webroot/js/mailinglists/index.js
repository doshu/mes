$(function() {
	
	$.linkize('.table.interactive tbody tr');
	
	$('#MailinglistIndexActionFormSubmit').on('click', function(e) {
		if($('#MailinglistAction').val() == 'bulkDelete') {
			customConfirm(
				'Sei sicuro di voler eliminare gli elementi selezionati?',
				function() {
					$('#MailinglistIndexActionForm').submit();
				}
			);
		}
		else {
			$('#MailinglistIndexActionForm').submit();
		}
	});
	
});
