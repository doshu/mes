


$(function() {
	
	CKEDITOR.replace( 'MailHtml', { 
		extraPlugins : 'variables,unsubscribe',
		variables: variableDialogSelectData,
        filebrowserImageBrowseUrl : BASEURL+'file_manager/file_managers/browse',
        filebrowserWindowWidth : '640',
    });
    
    $('#MailText').createVarsSelection();
	
	$(document).on('click', '.upload-opener',  function() {
		$(this).parents('.attachment-upload-el').find('input[type=file].attachment-upload-input').click();
	});
	
	$(document).on('click', '.upload-remove',  function(e) {
		e.preventDefault();
		$($(this).parents('.row')[0]).remove();
	});
	
	
	
	$('.attachment-upload-input').change(upload);
	
});
