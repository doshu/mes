var uploaderTemplate = '<div class="row"><div class="col-lg-12 attachment-upload-el"><input type="file" class="attachment-upload-input u-hide"><button class="btn btn-success btn-xs upload-opener u-hide" type="button">Seleziona</button></div></div>';

var progressTemplate = '<div class="progress progress-spinner progress-striped active"><div class="bar" style="width: 0%;"></div></div>';
var spinnerTemplate = '<span class="spinner progress-spinner"></span>';


$(function() {
	
	CKEDITOR.replace( 'MailHtml', { 
		extraPlugins : 'variables,unsubscribe',
		variables: variableDialogSelectData,
        filebrowserImageBrowseUrl : BASEURL+'file_manager/file_managers/browse',
        filebrowserWindowWidth : '640',
    });
	
	$(document).on('click', '.upload-opener',  function() {
		$(this).parents('.attachment-upload-el').find('input[type=file].attachment-upload-input').click();
	});
	
	$(document).on('click', '.upload-remove',  function(e) {
		e.preventDefault();
		$($(this).parents('.row')[0]).remove();
	});
	
	
	
	$('.attachment-upload-input').change(upload);
	
});
