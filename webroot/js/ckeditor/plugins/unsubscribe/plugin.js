
CKEDITOR.plugins.add( 'unsubscribe',
{
	init: function( editor )
	{
		editor.addCommand( 'insertUnsubscribe', new CKEDITOR.dialogCommand( 'unsubscribeDialog'));
		
		editor.ui.addButton('Unsuscribe', {
			label: 'Inserisci Link Disiscrizione',
			command: 'insertUnsubscribe',
			icon: this.path + 'images/icon.png'
		});
		
	}

});


CKEDITOR.dialog.add( 'unsubscribeDialog', function( editor )
{
	return {
		title : 'Inserisci Link Disiscrizione',
		minWidth : 400,
		minHeight : 200,
		contents :
		[
			{
				id : 'general',
				label : 'Settings',
				elements :
				[
				 	{
						type : 'text',
						id : 'anchor',
						label : 'Testo del Link',
						commit : function( data )
						{
							data.anchor = this.getValue();
						}
					},
					{
						type : 'text',
						id : 'redirecr',
						label : 'Indirizzo a cui redirigere dopo la disiscrizione',
						commit : function( data )
						{
							data.redirect = this.getValue();
						}
					},
				]
			},
		],
		onOk : function()
		{
			data = {};
			this.commitContent( data );
			var anchor = data.anchor.length?data.anchor:('{{unsubscribe('+encodeURIComponent(data.redirect)+')}}');
			editor.insertHtml('<a href="{{unsubscribe('+encodeURIComponent(data.redirect)+')}}">'+anchor+'</a>');
			
		}
	};
});
