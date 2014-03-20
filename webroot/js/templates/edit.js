


$(function() {
	
	//CKEDITOR.replace( 'MailHtml' );
	
	CKEDITOR.replace( 'TemplateHtml', { 
		extraPlugins : 'variables,unsubscribe',
		variables: variableDialogSelectData,
        filebrowserImageBrowseUrl : BASEURL+'file_manager/file_managers/browse',
        filebrowserWindowWidth : '640',
    });
	
});
