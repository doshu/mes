$(function() {
	var members = $.makeArray($('#toValidateList .list-group-item[data-member]'));
	
	var size = 3;
	
	var buf = 0;
	
	function producer() {
		var current = members.shift();
		if(current != undefined) {
			var q = $.ajax({
			 	type: 'POST',
			 	url: BASEURL+'members/validateMember/'+($(current).attr('data-member')),
			 	cache: false,
			 	dataType: 'json',
			 	data: {
			 		'data[_Token][key]': token
			 	},
			 	context:current
			});
			q.done(function(data) {
				var result = parseInt(data.status);
				if(result == addressIsNotValid) {
					var resultHtml = '<span class="label label-danger">Non Esiste</span>';
				}
				else if(result == addressIsValid) {
					var resultHtml = '<span class="label label-success">Esiste</span>';
				}
				else if(result == addressCannotValidate) {
					var resultHtml = '<span class="label label-warning">Impossibile Verificare</span>';
				}
				
				$(this).find('.result').empty().append(resultHtml);
				
			}).fail(function() {
				$(this).find('.result').empty().append('<span class="label label-danger">Errore durante la verifica</span>');
			});
			
			return q;
		}
		return false;
	}
	
	function consumer(p) {
		var c = this;
		c = c.bind(c);
		var n = p();
		if(n) {
			n.always(function() {
				c(p);
			});
		}
	}
	
	var ProducerConsumer = function(size, p, c) {
		for(var i = 0; i < size; i++) {
			buf++;
			var n = p();
			if(n) {
				n.always(function() {
					c = c.bind(c);
					c(p);
				});
			}
		}
	}
	
	var pc = new ProducerConsumer(size, producer, consumer);
});
