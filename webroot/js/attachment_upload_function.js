var uploaderTemplate = '<div class="row"><div class="col-lg-12 attachment-upload-el"><input type="file" class="attachment-upload-input u-hide"><button class="btn btn-success btn-xs upload-opener u-hide" type="button">Seleziona</button></div></div>';

var progressTemplate = '<div class="progress progress-spinner progress-striped active"><div class="progress-bar" role="progressbar" style="width: 0%;"></div></div>';
var spinnerTemplate = '<span class="spinner progress-spinner"></span>';


function printError(response, customData) {
	var parent = $(customData.that).parents('.attachment-upload-el');
	parent.find('.progress').remove();
	parent.find('.filename').addClass('error-message');
	parent.find('.attachment-upload-input').remove();
	
	if(response && response.response.error)
		parent.append('<span class="error-message pull-right">'+response.response.error+'</span>');
	else
		parent.append('<span class="error-message pull-right">Errore Upload</span>');
}


function upload(e) {
		
	//rimuovo il pulsante
	$(this).parents('.attachment-upload-el').find('.upload-opener').remove();
	//scrivo il nome del file
	if("files" in this) { 
		$(this).parents('.attachment-upload-el')
			.append('<span class="filename">'+this.files[0].name+' ('+human_filesize(this.files[0].size)+')</span>');
	}
	else {
		var filename = this.value.split('\\');
		filename = filename[filename.length - 1];
		$(this).parents('.attachment-upload-el')
			.append('<span class="filename">'+filename+'</span>');
	}
	
	var uploader = new doshuupload({
		url:BASEURL+'attachments/upload.json',
		file:this
	});
	
	if(uploader.supportUploadEvent()) {
		$(this).parents('.attachment-upload-el').append(progressTemplate);
		uploader.onProgress = function(e) {
			if (e.lengthComputable) {
				var percentComplete = (e.loaded / e.total) * 100;
				$(this.customData.that).parents('.attachment-upload-el').find('.progress .progress-bar').css('width', percentComplete+'%');
			}
		};
	}
	else {
		$(this).parents('.attachment-upload-el').append(spinnerTemplate);
	}
	
	uploader.setCustomData('that', this);
	uploader.setCustomData('upload-el-container', $(this).parents('.attachment-upload-el'));
	
	uploader.onLoad = function(response, customData) {
	
		customData['upload-el-container'].last().append($(customData.that));
		$('.attachment-upload-input').change(upload);
	
		if(response.response.status) {
			$(customData.that).parents('.attachment-upload-el').find('.progress-spinner').remove();
			$(customData.that).parents('.attachment-upload-el')
				.append('<a href="#" class="upload-remove error-message pull-right">Rimuovi</a>');
				
			//fatto l'upload trasformo l'input file in hidden e scrivo il risultato
			var newHidden = $('<input type="hidden"/>');
			newHidden.attr('name', 'data[Attachment][path][]')
				.val(response.response.attachment)
				.insertAfter(customData.that);
				
			$(customData.that).remove();
				
				
		}
		else {
			//altrimenti stampo errori
			printError(response, customData);
		}

	};
	
	uploader.onError = function(response, customData) {
		printError(response, customData);
	}
	
	
	
	uploader.start();
	
	$('.attachment-upload-container').append(uploaderTemplate);
	if(Modernizr.xhr2) { 
		$('.attachment-upload-container').find('.upload-opener').removeClass('u-hide');
	} 
	else { 
		$('.attachment-upload-container').find('.attachment-upload-input').removeClass('u-hide');
	}
}
