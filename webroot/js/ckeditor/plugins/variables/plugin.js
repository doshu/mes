
CKEDITOR.plugins.add( 'variables',
{
	init: function( editor )
	{
		editor.addCommand( 'insertVariable', new CKEDITOR.dialogCommand( 'variableDialog'));
		
		editor.ui.addButton('Variable', {
			label: 'Inserisci Variabile',
			command: 'insertVariable',
			icon: this.path + 'images/icon.png'
		});
		
	}

});


CKEDITOR.dialog.add( 'variableDialog', function( editor )
{
	return {
		title : 'Inserisci Variabile',
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
						type : 'select',
						id : 'var',
						label : 'Seleziona Variabile',
						items : editor.config.variables,
						commit : function( data )
						{
							data.variable = this.getValue();
						}
					},
				]
			},
		],
		onOk : function()
		{
			data = {};
			this.commitContent( data );
			if(data.variable.length) {
				editor.insertHtml(' {{'+data.variable+'}} ');
			}
		}
	};
});
