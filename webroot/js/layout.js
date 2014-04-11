

function human_filesize(bytes) {
    var size = ['B','KB','MB','GB','TB','PB','EB','ZB','YB'];
    var factor = Math.floor((bytes.toString().length - 1) / 3);
    return (bytes / Math.pow(1024, factor)).toFixed(2)+size[factor];
}

var alertTemplate = '<div class="modal fade" id="DoshuAlert" role="dialog" style="display:none;"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title">Attenzione!</h4></div><div class="modal-body"><p></p></div><div class="modal-footer"><button type="button" class="btn btn-default refuse" data-dismiss="modal">No</button><button type="button" class="btn btn-primary accept">Sì</button></div></div></div></div>';


function customConfirm(message, yes, no, data) {
	var alertHtml = $(alertTemplate);
	alertHtml.find('.modal-body p').html(message);
	$('body').append(alertHtml);
	$('#DoshuAlert').modal('show');
	if(typeof(yes) == "function") {
		alertHtml.find('button.accept').click(data, function(e) {
			yes(e.data);
			$('#DoshuAlert').on('hidden.bs.modal', function() {
				$(this).remove();
			});
			$('#DoshuAlert').modal('hide');
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
$.fn.ajaxForm = function(success, fail) {
	
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
	
	$(document).on('click', selector, function(e) {
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


function insertAtCursor(element, text) {
	if(typeof(element) == 'string') {
		element = $(element).get(0);
	}
	
	//IE support
	if (document.selection) {
		element.focus();
		sel = document.selection.createRange();
		sel.text = text;
	}
	//MOZILLA and others
	else if (element.selectionStart || element.selectionStart == '0') {
		var startPos = element.selectionStart;
		var endPos = element.selectionEnd;
		
		element.value = element.value.substring(0, startPos)
			+ text
			+ element.value.substring(endPos, element.value.length);
	} else {
		element.value += text;
	}
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
	
	$(".datepicker").datetimepicker({
        format: "d/m/Y",
        i18n: dateTimePickerI18n, 
        lang:'it',
        step:5,
        timepicker:false,
        allowBlank:true,
        scrollInput:false
    });
    
    
    $(".datetimepicker").datetimepicker({
        format: "d/m/Y H:i:s",
        i18n: dateTimePickerI18n, 
        lang:'it',
        step:5,
        allowBlank:true,
        scrollInput:false
    });
    
    
    $('.grid-el-select').on('click', function(e) {
    	e.stopImmediatePropagation();
    	e.stopPropagation();
    });
    
    $('.grid-el-select').on('change', function(e) {
    	var gridHelper = $('.grid-helper[data-table='+$(this).parents('table[id]').attr('id')+']');
    	var current = $(gridHelper).find('.selected-list').val();
		current = current.length?
			$.map(current.split(','), function(v) {
				return v.toString().trim();
			}):[];
			
    	var index = $.inArray($(this).val(), current);
    	if($(this).prop('checked')) {
    		if(index == -1) {
    			current.push($(this).val());
    		}	
    	}
    	else {
    		console.log(index);
    		if(index > -1) {
    			current.splice(index, 1);
    		}
    	}
    	$(gridHelper).find('.selected-list').val(current.join(','));
    });
    
    $('.select-visible-trigger').on('click', function(e) {
    	e.preventDefault();
    	var gridHelper = $(this).parents('.grid-helper')[0];
    	var useTable = $('#'+$(gridHelper).attr('data-table'));
    	if(useTable.length) {
    		useTable.find('.grid-el-select').prop('checked', true).change();
    	}
    });
    
    
    $('.unselect-visible-trigger').on('click', function(e) {
    	e.preventDefault();
    	var gridHelper = $(this).parents('.grid-helper')[0];
    	var useTable = $('#'+$(gridHelper).attr('data-table'));
    	if(useTable.length) {
    		useTable.find('.grid-el-select').prop('checked', false).change();
    	}
    });
    
    $('.bulk-form').on('submit', function(e) {
    	if(!$(this).find('.selected-list').val().length) {
    		e.preventDefault();
    		alert('Seleziona almeno un elemento');
    		return false;
    	}
    });
    
    
    $('.grey-container').each(function() {
    	var offsetTop = $(this).offset().top - $(this).height();
    	$(this).affix({
			offset: {
				top: offsetTop
			}
		});
		$(this).on('affix.bs.affix', function() {
			$(this).css('width', $('#main-container').width());
			var placeholder = $('<div></div>');
			placeholder.height($(this).innerHeight());
			placeholder.width($(this).width());
			placeholder.css('margin-bottom', '15px');
			$(this).after(placeholder);
			$.data(this, 'placeholder_affix', placeholder);
		});
		$(this).on('affix-top.bs.affix', function() {
			$(this).css('width', '100%');
			$($.data(this, 'placeholder_affix')).remove();
		});
		
		if($(this).hasClass('affix')) {
			$(this).trigger('affix.bs.affix');
		}
    });
    
    
    $('#showMenuButton').click(function() {
    	$('#wrapper').toggleClass('sidebar-display');
    });
    
});
