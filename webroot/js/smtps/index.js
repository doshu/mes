$(function() {
	
	$.linkize('.table.interactive tbody tr');
	
	$('#SmtpIndexActionFormSubmit').on('click', function(e) {
		if($('#SmtpAction').val() == 'bulkDelete') {
			customConfirm(
				'Sei sicuro di voler eliminare gli elementi selezionati?',
				function() {
					$('#SmtpIndexActionForm').submit();
				}
			);
		}
		else {
			$('#SmtpIndexActionForm').submit();
		}
	});
	
});
