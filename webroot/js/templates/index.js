$(function() {
	
	$.linkize('.table.interactive tbody tr');
	
	$('#TemplateIndexActionFormSubmit').on('click', function(e) {
		if($('#TemplateAction').val() == 'bulkDelete') {
			customConfirm(
				'Sei sicuro di voler eliminare gli elementi selezionati?',
				function() {
					$('#TemplateIndexActionForm').submit();
				}
			);
		}
		else {
			$('#TemplateIndexActionForm').submit();
		}
	});
});
