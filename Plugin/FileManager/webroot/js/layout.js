function human_filesize(bytes) {
    var size = ['B','kB','MB','GB','TB','PB','EB','ZB','YB'];
    var factor = Math.floor((bytes.toString().length - 1) / 3);
    return (bytes / Math.pow(1024, factor)).toFixed(2)+size[factor];
}

var alertTemplate = '<div id="DoshuAlert" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h1>Attenzione!</h1></div><div class="modal-body"><p></p></div><div class="modal-footer"><button class="btn refuse" data-dismiss="modal" aria-hidden="true">No</button><button class="btn btn-primary accept">Sì</button></div></div>';


function customConfirm(message, yes, no, data) {
	var alertHtml = $(alertTemplate);
	alertHtml.find('.modal-body p').html(message);
	$('body').append(alertHtml);
	$('#DoshuAlert').modal('show');
	if(typeof(yes) == "function") {
		alertHtml.find('button.accept').click(data, function(e) {
			yes(e.data);
			$('#DoshuAlert').modal('hide').remove();
		});
	}
	if(typeof(no) == "function") {
		alertHtml.find('button.refuse').click(data, function(e) {
			no(e.data);
		});
	}
}

//funzione che trasforma un form in ajax
//prende come this il selettore jquery ma opera solo sul primo
$.fn.ajaxForm = function(success) {
	
	form = $(this[0]);
	form.submit({sucessCallback:success, failCallback:fail}, function(e) {
		e.preventDefault;
		e.stopImmediatePropagation();
		var action = $(this).attr('action');
		var method = $(this).attr('method') || 'post';
		//var data = new FormData(this);
		var data = $(form).serialize();
		//alert(data);
		$.ajax({
			cache:false,
			url:action,
			type:method,
			data:data,
			success:e.data.sucessCallback,
			error:e.data.failCallback,
			processData: true
    		//contentType: false
		});
		return false;
	});
}


//funzione che prende un elemento e se ha gli attributo data-url lo trasforma in un link
$.linkize = function(selector) {
	
	$(document).on('mouseup', selector, function(e) {
		var tmpLink = $('<form method="get"></form>');
		tmpLink.attr('action', $(this).attr('data-url'));
		if($(this).attr('data-target')) {
			tmpLink.attr('target', $(this).attr('data-target'));
		}
		else {
			if(e.ctrlKey) {
				tmpLink.attr('target', '_blank');
			}
			else if(e.which == 2) {
				tmpLink.attr('target', '_blank');
			}
		}
		tmpLink.hide();
		$('body').append(tmpLink);
		tmpLink.submit();
		tmpLink.remove();
	});

}

$(function() {

	//$('.alert').delay(4000).fadeOut();
	$('a[data-toggle=tooltip]').tooltip()
	
	
	//elimino l'onclick di default messo da cake sui postlink
	$('a[data-to-confirm]').each(function() {
		$(this).attr('onclick', '');
	});
	
	$('a[data-to-confirm]').click(function(e) {
		e.preventDefault();
		var alertHtml = $(alertTemplate);
		alertHtml.find('.modal-body p').html($(this).attr('data-to-confirm'));
		var assocForm = $(this).prev('form').attr('id');
		$('body').append(alertHtml);
		$('#DoshuAlert').modal('show');
		alertHtml.find('button.accept').attr('data-assoc-form', assocForm).click(function() {
			$('#'+$(this).attr('data-assoc-form')).submit();
			$('#DoshuAlert').modal('hide').remove();
		});
	});
});
