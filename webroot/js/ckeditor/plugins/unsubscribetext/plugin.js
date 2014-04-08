
CKEDITOR.plugins.add( 'unsubscribetext',
{
	init: function( editor )
	{
		editor.addCommand( 'insertUnsubscribeText', new CKEDITOR.dialogCommand( 'unsubscribeTextDialog'));
		
		editor.ui.addButton('Unsuscribe', {
			label: 'Inserisci Link Disiscrizione',
			command: 'insertUnsubscribeText',
			icon: this.path + 'images/icon.png'
		});
		
	}

});


CKEDITOR.dialog.add( 'unsubscribeTextDialog', function( editor )
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
			editor.insertText('{{unsubscribe('+encodeURIComponent(data.redirect)+')}}');
			
		}
	};
});
