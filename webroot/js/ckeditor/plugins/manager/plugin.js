CKEDITOR.plugins.add('manager', {
	init: function(editor) {
		setTimeout(function() { isManagerLoaded(editor); }, 10);
	}
});

function isManagerLoaded(editor) {
	
	if(editor.loaded) {
		$('.cke_button__image').on('click', function() {
			console.log(editor);
			$(editor.container.$).append('<style>.cke_dialog_contents table tr {  }</style>');
			console.log($(editor.container.$).find('.cke_dialog_contents table tr').eq(0));
		});
	}
	else
		setTimeout(function() { isManagerLoaded(editor); }, 10);
}
