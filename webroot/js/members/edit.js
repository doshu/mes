$(function() {
	
	$('select').select2();
	
	$(".custom-field-date").datetimepicker({
        format: "d/m/Y",
        i18n: dateTimePickerI18n, 
        lang:'it',
        step:5,
        timepicker:false,
        allowBlank:true
    });
});
