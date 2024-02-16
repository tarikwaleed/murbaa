ClassicEditor
	.create( document.querySelector( '#terms' ), {
		
		 language: {
            // The UI will be English.
            ui: 'en',

            // But the content will be edited in Arabic.
            content: 'ar'
        },
		toolbar: [ 'heading', '|'
				, 'bold', 'italic', 'link', '|'
				,'alignment:left','alignment:center', 'alignment:right', 'alignment:justify','|'
				, 'bulletedList', 'numberedList', 'undo', 'redo' ]
	} )
	.then( editor => {
		window.editor = editor;
	} )
	.catch( err => {
		alert( err.stack );
	} );
	
ClassicEditor
	.create( document.querySelector( '#policy' ), {
		
		 language: {
            // The UI will be English.
            ui: 'en',

            // But the content will be edited in Arabic.
            content: 'ar'
        },
		toolbar: [ 'heading', '|'
				, 'bold', 'italic', 'link', '|'
				,'alignment:left','alignment:center', 'alignment:right', 'alignment:justify','|'
				, 'bulletedList', 'numberedList', 'undo', 'redo' ]
	} )
	.then( editor => {
		window.editor = editor;
	} )
	.catch( err => {
		alert( err.stack );
	} );
	