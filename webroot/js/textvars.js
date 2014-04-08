//variableDialogSelectData must be defined
//utilizza i css di ckeditor

$.fn.createVarsSelection = function() {
	
	
	var text = this;
	var template = $('<div class="cke_top clearfix"><div class="textvars-vars row"><div class="col-lg-3 col-md-3">Seleziona Varibile &nbsp;</div></div><div class="textvars-unsubscribe row"><div class="col-lg-3 col-md-3">Redirect Disiscrizione &nbsp;</div></div></div>');
	var varsSelect = '';
	
	$(variableDialogSelectData).each(function(key, val) { 
		varsSelect += '<option value="'+val[1]+'">'+val[0]+'</option>';
	});
	
	varsSelect = $('<select>'+varsSelect+'</select>');
	varsButton = $('<button type="button" class="btn btn-xs pull-right btn-primary">Inserisci Variabile</button>');
	varsButton.on('click', function() {
		var val = varsSelect.val();
		if(val.length) {
			insertAtCursor(text.get(0), '{{'+val+'}}');
		}
	});
	
	var varsSelectContainer = $('<div class="col-lg-5 col-md-5"></div>');
	varsSelectContainer.append(varsSelect);
	var varsButtonContainer = $('<div class="col-lg-4 col-md-4"></div>');
	varsButtonContainer.append(varsButton);
	template.find('.textvars-vars').append(varsSelectContainer);
	template.find('.textvars-vars').append(varsButtonContainer);
	
	unsubText = $('<input type="text" class="form-control input-sm" placeholder="http://www.miosito.it/disiscrizione">');
	unsubButton = $('<button type="button" class="btn btn-xs pull-right btn-primary">Inserisci Link</button>');
	unsubButton.on('click', function() {
		var val = unsubText.val();
		insertAtCursor(text.get(0), '{{unsubscribe('+val+')}}');
	});
	
	var unsubTextContainer = $('<div class="col-lg-5 col-md-5"></div>');
	unsubTextContainer.append(unsubText);
	var unsubButtonContainer = $('<div class="col-lg-4 col-md-4"></div>');
	unsubButtonContainer.append(unsubButton);
	template.find('.textvars-unsubscribe').append(unsubTextContainer);
	template.find('.textvars-unsubscribe').append(unsubButtonContainer);

	var container = $('<div class="cke cke_chrome"></div>');
	container.append(template);
	$(text).hide().after(container);
	container.append($(text));
	$(text).show();
}	
