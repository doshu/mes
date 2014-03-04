function isValidCsv(file) {
	return file.type == 'text/csv' || file.name.substr(-4).toLowerCase() == '.csv';
}


function csv_replaceNewLine(text) {
	var inside = false;
	for(var i = 0; i < text.length; i++) {
		if(text[i] == '"') {
			inside = !inside;
		}
		else {
			if(text[i] == "\n" && inside) {
				text = text.substr(0, i)+', '+text.substr(i+1);
				return csv_replaceNewLine(text);
			}
		}
	}
	return text;
}

function str_getcsv(str) {

	var s = false;
	var striped = '';
	var slices = new Array();
	
	if(str[0] != '"')
		striped += str[0];
	else
		s = true;
		
	for(var i = 1; i < str.length; i++) {
		if(str[i] == "," && s == false) {
			
			if(striped[striped.length-1] == '"')
				striped = striped.substr(0, striped.length-1);
			
			slices.push(htmlspecialchars(striped));
			striped = '';
		}
		else {
			if(str[i] != '"') {
				striped += str[i];
			}
			else {
				if(str[i] == '"') {
					if(s) {
						striped += str[i];
						s = false;
					}
					else {
						s = true;
					}
				}
			}
		}
	}
	
	if(striped[striped.length-1] == '"')
		striped = striped.substr(0, striped.length-1);
	slices.push(striped);
	
	return slices;
}


function normalize_csv(csv) {
	var lengths = new Array();
	for(i in csv)
		lengths.push(csv[i].length);
	var max = Math.max.apply(this, lengths);
	
	for(i in csv) {
		for(var j = csv[i].length; j < max; j++)
			csv[i][j] = '';
	}
	
	return csv;
}

function preview(file, container) {
	var reader = new FileReader;
	reader.onload = function(e) {
		text = e.target.result;
		text = csv_replaceNewLine(text);
		text = text.split("\n");
		var csv = new Array();
		for(var line = 0; line < text.length && line < 5; line++)
			if(text[line] != "")
				csv.push(str_getcsv(text[line]));
		
		csv = normalize_csv(csv);
		
		var table = $('<table class="csv-table"></table>');
		
		for(var line = 0; line < csv.length; line++) {
			
			var row = $('<tr></tr>');
			for(var field = 0; field < csv[line].length; field++) {
				if(line == 0)
					row.append($('<th></th>').html(csv[line][field]));
				else
					row.append($('<td></td>').html(csv[line][field]));
			}
			table.append(row);
		}
		container.append(table);
	};
	reader.readAsText(file);
}

$(function() {
	$('#select-file').click(function() {
		$('#MemberFile').click();
	});
	
	$('#MemberFile').on('change', function() {
		if(isValidCsv(this.files[0])) { 
			$('#selected-filename').html('<span class="success-message">'+this.files[0].name+'</span>');
			$('#upload-file').show();
			if(window.FileReader) {
				preview(this.files[0], $('#preview'));
				$('#preview').show();
			}
		}
		else {
			$('#selected-filename').html('<span class="error-message">File non valido</span>');
			$('#upload-file').hide();
			$('#preview').hide();
		}
	});
});
