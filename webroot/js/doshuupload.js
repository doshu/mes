/*

	@opt = {
		url,
		file,
		fileName,
		onProgress,
		onLoad,
		onError
	}

*/
function doshuupload(opt) {

	Date.now = Date.now || function() { return +new Date; };

	this.supportUploadEvent = function() {
		return this.xhr.upload && window.FormData?true:false;
	}
	
	this.supportFormData = function() {
		return window.FormData?true:false;
	}
	
	this.setCustomData = function(data, val) {
		this.customData[data] = val;
		this.customUploadData[data] = val;
	}
	

	this.start = function(additionalData) {
	
		var that = this;
	
		if(this.supportFormData()) {
		
			this.xhr.customData = this.customData;
			if(this.supportUploadEvent())
				this.xhr.upload.customData = this.customUploadData;
		
			this.xhr.open('POST', this.url, true);
		
			if(this.supportUploadEvent()) {
				this.xhr.upload.onprogress = this.onProgress;
			}
			
			this.xhr.onload = function() {
				if(this.status == 200) {
					try {
						var response = JSON.parse(this.responseText);
						that.onLoad(response, that.customData);
					}
					catch(e) {
						that.onError(null, that.customData);
					}
				}
				else {
					that.onError(null, that.customData);
				}
			}
			
			this.xhr.onabort = this.xhr.onerror = function() {
				that.onError(null, that.customData);
			}
			
		
			var fd = new FormData();
			
			fd.append(this.filename, this.file.files[0]);
			if(additionalData) {
				for(data in additionalData) {
					fd.append(data, additionalData[data].val());
				}
			}
			
			this.xhr.send(fd);
		}
		else {
			
			
			var iframeId = 'doshu_'+Date.now();
			
			var iframe = $('<iframe id="' + iframeId + '" name="' + iframeId + '" class="doshu-upload-iframe" src="javascript:(function () { document.open();document.domain=\''+document.domain+'\';document.close();' + '})();"></iframe>');
			
			
			document.body.appendChild(iframe[0]);
			
			var multipart = 'multipart/form-data';

			
			var form = $('<form></form>')
				.prop('action', this.iframeUrl)
				.prop('method', 'post')
				.prop('enctype', multipart)
				.prop('encoding', multipart)
				.prop('target', iframeId);
			
			
			form = $(form);
			
			$(this.file).prop('name', this.filename);
			
			form.append($(this.file));
			if(additionalData) {
				for(data in additionalData) {
					form.append(additionalData[data]);
				}
			}
			
			
			$(document.body).append(form);
			
			this.form = form;
			
			
			
			iframe.on('error', this.onError);
			
			iframe.on('load', function() {
				
				try {
					var response = JSON.parse($(this).contents().text());
					//$(that.file).unwrap(that.form);
					$(this).remove();
					$(that.form).remove();
					try {
					that.onLoad(response, that.customData);
					}
					catch(e) {
						alert(e);
					}
				}
				catch(e) {
					
					that.onError(null, that.customData);
				}
			});
			
			form.submit();
		}
	}

	this.xhr = new XMLHttpRequest();
	this.url = opt.url;
	this.iframeUrl = opt.iframeUrl || opt.url;
	this.file = opt.file;
	this.filename = opt.filename || "file";
	this.onProgress = opt.onProgress;
	this.onLoad = opt.onLoad;
	this.onError = opt.onError;
	this.customData = {};
	this.customUploadData = {};
	
	
}
