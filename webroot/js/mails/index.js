$(function() {
	
	$.linkize('.table.interactive tbody tr');
	
	$('#MailIndexActionFormSubmit').on('click', function(e) {
		if($('#MailAction').val() == 'bulkDelete') {
			customConfirm(
				'Sei sicuro di voler eliminare gli elementi selezionati?',
				function() {
					$('#MailIndexActionForm').submit();
				}
			);
		}
		else {
			$('#MailIndexActionForm').submit();
		}
	});
});
