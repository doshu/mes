$(function() {
	
	$.linkize('.table.interactive tbody tr');
	
	$('#MemberIndexActionFormSubmit').on('click', function(e) {
		if($('#MemberAction').val() == 'bulkDelete') {
			customConfirm(
				'Sei sicuro di voler eliminare gli elementi selezionati?',
				function() {
					$('#MemberIndexActionForm').submit();
				}
			);
		}
		else {
			$('#MemberIndexActionForm').submit();
		}
	});
	
});
