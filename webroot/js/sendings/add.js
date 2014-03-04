$(function() {

	$("#SendingTime").datetimepicker({
        format: "d/m/Y",
        i18n: dateTimePickerI18n, 
        lang:'it',
        step:5,
        allowBlank:true
    });
    
    $('select').select2();
    
    $('#SendingEnableTime').click(function() {
    	if($(this).is(':checked')) {
    		$('#timeControls').slideDown(100);
    	}
    	else {
    		$('#timeControls').slideUp(100);
    	}
    });

    if($('#SendingEnableTime:checked').length) 
		$('#timeControls').show();
});
