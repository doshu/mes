var functionArgsTemplate = {
	member_sice: [
		{name:'list', type:'select', options:mailinglist}, 
		{name:'from', type:'text', extra:'date'}, 
		{name:'to', type:'text', extra:'date'}
	],
	unsubscribing: [
		{name:'list', type:'select', options:mailinglist}, 
		{name:'from', type:'number', extra:'number'}, 
		{name:'to', type:'number', extra:'number'},
		{name:'from_date', type:'text', extra:'date'}, 
		{name:'to_date', type:'text', extra:'date'}
	],
	sendings: [
		{name:'from', type:'number', extra:'number'}, 
		{name:'to', type:'number', extra:'number'},
		{name:'from_date', type:'text', extra:'date'}, 
		{name:'to_date', type:'text', extra:'date'}
	],
	opened: [
		{name:'type', type:'select', extra:'', options:{perc:'%', value:'='}},
		{name:'from', type:'number', extra:'number'},
		{name:'to', type:'number', extra:'number'},
		{name:'from_date', type:'text', extra:'date'}, 
		{name:'to_date', type:'text', extra:'date'}
	],
}

function onConditionsChange() {
	var el = $(this);
	var container = $(el.parents('.conditions-el')[0]);
	var divContainer = el.parents('div.input.select')[0];
	
	
	//se è l'ultimo della lista ne aggiungo uno dopo
	if(!container.next('.conditions-el').length) {
		var newElement = $(conditionsElementTemplate);
		newElement.append('<span class="deleter"><i class="fa fa-times"></i></span>');
		var nextCode = parseInt(el.attr('data-code'))+1;
		newElement.find('select.conditions-el-select')
			.attr('data-code', nextCode)
			.attr('name', el.attr('data-name-template').replace('__code__', nextCode))
			.change(onConditionsChange);
			
		var toInsert = $('<li class="conditions-el"></li>').append(newElement);
		toInsert.find('select').select2();
		container.after(toInsert);
	}
	
	//se è stato selezionato un operatore creo un nuovo ramo se non esiste
	if(el.val() == 'and' || el.val() == 'or') {
		if(divContainer) {
			$(divContainer).children('.adder').remove();
			$(divContainer).children('.args-container').remove();
			$(divContainer).append('<span class="adder"><i class="fa fa-plus"></i></span>');
		}
		if(!container.children('.sub-conditions-el').length) {
			var subConditionsEl = $(conditionsElementTemplate);
			subConditionsEl.append('<span class="deleter"><i class="fa fa-times"></i></span>');
			var nextCode = 0;
			var newNameTemplate = el.attr('name').replace('[value]', '[subconditions][__code__][value]');
			subConditionsEl.find('select.conditions-el-select')
				.attr('data-code', nextCode)
				.attr('data-name-template', newNameTemplate)
				.attr('name', newNameTemplate.replace('__code__', nextCode))
				.change(onConditionsChange);
				
			var toInsert = $('<li class="conditions-el"></li>').append(subConditionsEl);
			toInsert = $('<ul class="list-unstyled sub-conditions-el"></ul>').append(toInsert);
			toInsert.find('select').select2();
			container.append(toInsert);
		}
	}
	else {
		container.children('.sub-conditions-el').remove();
		if(divContainer) {
			$(divContainer).children('.adder').remove();
			$(divContainer).children('.args-container').remove();
		}
		//aggiungo gli argomenti
		var funcArgs =	functionArgsTemplate[el.val()];
		var argsContainer = $('<div class="args-container"></div>');
		for(arg in funcArgs) {
			arg = funcArgs[arg];
			var argTag = '';
			switch(arg.type) {
				case 'text':
					argTag = $('<input type="text" class="form-control input-sm"/>');
				break;
				case 'number':
					if(Modernizr.inputtypes.number) {
						argTag = $('<input type="number" class="form-control input-sm" min="0"/>');
					}
					else {
						argTag = $('<input type="text" class="form-control input-sm"/>');
					}
				break;
				case 'select':
					argTag = $('<select class="form-control input-sm"></select>');
					for(option in arg.options) {
						argTag.append('<option value="'+option+'">'+arg.options[option]+'</option>');
					}
				break;
			}
			argTag.attr('name', el.attr('name').replace('[value]', '[args]['+arg.name+']'));
			argsContainer.append(argTag);
			switch(arg.extra) {
				case 'datetime':
					argTag.datetimepicker({
						format: "d/m/Y",
						i18n: dateTimePickerI18n, 
						lang:'it',
						step:5,
						allowBlank:true
					});
				break;
				case 'date':
					argTag.datetimepicker({
						format: "d/m/Y",
						i18n: dateTimePickerI18n, 
						lang:'it',
						step:5,
						allowBlank:true,
						timepicker: false
					});
				break;
			}
		}
		
		$(divContainer).append(argsContainer);
	}
}


$(function() {

	$("#SendingTime").datetimepicker({
        format: "d/m/Y",
        i18n: dateTimePickerI18n, 
        lang:'it',
        step:5,
        allowBlank:true
    });
    
    $('select').select2();
    
    $('#SendingEnableTime').click(function() {
    	if($(this).is(':checked')) {
    		$('#timeControls').slideDown(100);
    	}
    	else {
    		$('#timeControls').slideUp(100);
    	}
    });

    if($('#SendingEnableTime:checked').length) 
		$('#timeControls').show();
		
	$('.conditions-el-select').change(onConditionsChange);
	
	$( document ).on( "click", "#conditions .deleter", function() {
		var parent = $(this).parents('.conditions-el')[0];
		if(parent) {
			var subContainer = $(parent).parents('.sub-conditions-el')[0];
			$(parent).remove();
			if(subContainer && !$(subContainer).find('.conditions-el').length) {
				$(subContainer).remove();
			}
		}
	});
	
	$( document ).on( "click", "#conditions .adder", function() {
		var parent = $($(this).parents('.conditions-el')[0]);
		var subConditions = parent.children('.sub-conditions-el');
		if(!subConditions.length) {
			var subConditions = $('<ul class="list-unstyled sub-conditions-el"></ul>');
			subConditions.appendTo(parent);
		}
		var conditionsEl = $(conditionsElementTemplate);
		conditionsEl.append('<span class="deleter"><i class="fa fa-times"></i></span>');
		console.log(subConditions.find('> .conditions-el:last-child'));
		var nextCode = parseInt(subConditions.find('> .conditions-el:last-child select.conditions-el-select').attr('data-code'))+1;
		var newNameTemplate = parent.find('select.conditions-el-select').attr('name').replace('[value]', '[subconditions][__code__][value]');
		conditionsEl.find('.conditions-el-select')
			.attr('data-code', nextCode)
			.attr('data-name-template', newNameTemplate)
			.attr('name', newNameTemplate.replace('__code__', nextCode))
			.change(onConditionsChange);
				
		var toInsert = $('<li class="conditions-el"></li>').append(conditionsEl);
		subConditions.append(toInsert);
		toInsert.find('select').select2();
		subConditions.append(toInsert);
	});
	
	$('#top-adder').on('click', function() {
		var newElement = $(conditionsElementTemplate);
		newElement.append('<span class="deleter"><i class="fa fa-times"></i></span>');
		var last = $('#startingConditionsList > .conditions-el:last-child select.conditions-el-select');
		var nextCode = parseInt(last.attr('data-code'))+1;
		newElement.find('select.conditions-el-select')
			.attr('data-code', nextCode)
			.attr('name', last.attr('data-name-template').replace('__code__', nextCode))
			.change(onConditionsChange);
			
		var toInsert = $('<li class="conditions-el"></li>').append(newElement);
		toInsert.find('select').select2();
		$('#startingConditionsList').append(toInsert);
	});
	
	
	$('#testFilter').click(function(e) {
		e.preventDefault();
		var data = $('#SendingAddForm').serialize();
		$('#testResult').remove();
		$.ajax({
			cache:false,
			dataType:'json',
			type:'POST',
			url:$('#testFilter').attr('data-url'),
			data:data
		})
		.done(function(data, status, xhr) {
			if (data.result.status) {
				$('#testFilter').after('<span id="testResult" class="alert-success">Trovati '+data.result.recipients+' destinatari</span>');
			}
			else {
				$('#testFilter').after('<span id="testResult" class="alert-danger">'+data.result.error+'</span>');
			}
		})
		.fail(function(xhr, status, error) {
			$('#testFilter').after('<span id="testResult" class="alert-danger">Errore durante la verifica</span>');
		});
	});
	
	$('#SendingEnableConditions').click(function() {
		if ($(this).prop('checked')) {
			$('#filterContainer').show();
		}
		else {
			$('#filterContainer').hide();
		}
	})
	
});
