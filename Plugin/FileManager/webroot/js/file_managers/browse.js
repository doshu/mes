var progressTemplate = '<div class="progress-el progress-spinner"><div class="file-name"></div><div class="progress progress-striped active"><div class="bar" style="width: 0%;"></div></div></div>';
var uploadInfoTemplate ='<div class="progress-el"><div class="file-name"></div><span class="spinner progress-spinner"></span></div>';
var inputFileTemplate = '<input type="file" id="file" name="data[file]" class="u-hide"/>';

function getUrlParam(paramName) {

	var reParam = new RegExp('(?:[\?&]|&amp;)' + paramName + '=([^&]+)', 'i') ;
	var match = window.location.search.match(reParam) ;

	return (match && match.length > 1) ? match[1] : '' ;
}

var currentImage = "";


function upload() {
	
	var uploader = new doshuupload({
		url:UPLOAD_URL,
		file:this,
		filename:'data[file]'
	});
	
	//uploader.setCustomData('that', uploader);
	
	var filename = "";
	if("files" in this) { 
		filename = this.files[0].name;
	}
	else {
		filename = this.value.split('\\');
		filename = filename[filename.length - 1];
	}
	
	if(uploader.supportUploadEvent()) {
		
		var progressBar = $(progressTemplate);
		progressBar.find('.file-name').text(filename);
		uploader.setCustomData('progress', progressBar);
		$('#progressContainer').append(progressBar);
		uploader.onProgress = function(e) {
			
			if (e.lengthComputable) {
				var percentComplete = (e.loaded / e.total) * 100;
				$(this.customData.progress).find('.bar').css('width', percentComplete+'%');
			}
		};
		
	}
	else {
		
		$(this).attr('id', '');
		var nameContainer = $(uploadInfoTemplate);
		nameContainer.find('.file-name').text(filename);
		uploader.setCustomData('progress', nameContainer);
		$('#progressContainer').append(nameContainer);
		
	}
	
	
	uploader.onLoad = function(response, customData) {
	
		var container = customData.progress;
		if(response.response.status) {
			$.ajax({
				url:THUMB_TEMPLATE_URL+'\/'+base64_encode(response.response.file),
				type:'get',
				cache:false
			}).done(function(data, status, xhr) {
				$('#thumbnailContainer').prepend(data);
				container.find('.progress').remove();
				container.find('.file-name').remove();
			}).fail(function(xhr,status,error) {
				container.find('.progress').remove();
				container.find('.file-name').addClass('label label-important').append(' ('+response.response.error+')');
			});
		}
		else {
			container.find('.progress').remove();
			container.find('.file-name').addClass('label label-important').append(' ('+response.response.error+')');
		}
		$('#file').on('change', upload);
		
	};
	
	
	uploader.onError = function(response, customData) {
		var container = customData.progress;
		container.find('.progress').remove();
		container.find('.file-name').addClass('label label-important').append(' ('+response.response.error+')');
		$('#file').on('change', upload);
	}
	
	uploader.start({'data[_Token][key]':$('#_TokenKey').clone()});
	
	var newUpload = $(inputFileTemplate);
	$('#uploaderContainer span').prepend(newUpload);
	newUpload.on('change', upload);
	if(!Modernizr.xhr2)
		newUpload.removeClass('u-hide');
}

$(function() {

	
	$('#uploaderContainer').affix({
		offset: {
			top: $('#uploaderContainer').offset().top
		}
	});
	
	$(document).on('click', '#mediaContainer .thumbnail', function() {
		$('#mediaContainer .thumbnail').removeClass('active');
		$(this).addClass('active');
		currentImage = $(this);
	});
	
	$('#select').on('click', function(e) {
		e.preventDefault();
		if(currentImage.attr('data-image').length) {
			var funcNum = getUrlParam('CKEditorFuncNum');
			window.opener.CKEDITOR.tools.callFunction(funcNum, currentImage.attr('data-image'));
			window.close();
		}
	});
	
	$('#trash').on('click', function(e) {
		e.preventDefault();
		if(currentImage && currentImage.attr('data-image').length) {
			customConfirm(
				'Sei sicuro di voler eliminare l\'immagine?',
				function() {
					if(currentImage && currentImage.attr('data-image').length) {
						$.ajax({
							url:DELETE_URL,
							type:'POST',
							cache:false,
							data: {
								data: {
									file: currentImage.attr('data-image'),
									_Token: {
										key: TOKEN_KEY
									}	
								}
							},
							dataType: 'json',
							media: currentImage 
						}).done(function(data, status, xhr) {
							if(data.response) {
								$(this.media).remove();
							}
							else {
								$('#mediaContainer').prepend('<div class="error-message text-center">Errore Eliminazione File</div>');
							}
						}).fail(function(xhr,status,error) {
							$('#mediaContainer').prepend('<div class="error-message text-center">Errore Eliminazione File</div>');
						});
					}
				},
				null,
				null
			);
		}
	});
	
	$('#loadButton').on('click',  function() {
		$('#file').click();
	});
	
	$('#file').on('change', upload);
	
	//image lazyload
	$(window).on('scroll', function(e) {
		
		$('#mediaContainer .thumbnail img').each(function() {
			if($(window).scrollTop() + $(window).innerHeight() >= $(this).offset().top) {
				$(this).attr('src', $(this).attr('data-url'));
			}
		});
	});
	
	$(window).trigger('scroll');
});
