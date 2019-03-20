jQuery( function( $ ) {
	// Add line numbers to textareas
	$( 'textarea.jwbp-lined' ).linedtextarea();

	// Synchronize scrolling across post titles and slug textareas
	$( '.jwbp-linked-textareas .jwbp-linked-textarea' ).scroll( function() {
		var el = $( this );

		$( this ).parents( '.jwbp-linked-textareas' ).find( '.jwbp-linked-textarea' ).each( function() {
			$( this ).scrollTop( el.scrollTop() );

			$( this ).css( 'background-position', '0 ' + ( 5 - el.scrollTop() ) + 'px' );
			el.css( 'background-position', '0 ' + ( 5 - el.scrollTop() ) + 'px' );
		} );
	} );
	
	// Toggle columns
	$( '.jwbp-select-columns input:checkbox' ).change( function() {
		var columntype = $( this ).attr( 'name' );
		
		columntype = columntype.substring( columntype.indexOf( '[' ) + 1, columntype.indexOf( ']' ) );
		
		if ( $( this ).is( ':checked' ) ) {
			$( '.jwbp-addnew .jwbp-column-' + columntype ).show();
		}
		else {
			$( '.jwbp-addnew .jwbp-column-' + columntype ).hide();
		}
	} ).trigger( 'change' );
	
	$( '.jwbp-addnew, .jwbp-addnew' ).on( 'focus', 'input, select', function() {
		if ( !$( this ).parents( 'tr' ).hasClass( 'jwbp-addnew-row-active' ) ) {
			var lastrow_el = $( this ).parents( '.jwbp-addnew' ).find( 'tr:visible:last' );
			var newrow_el = $( this ).parents( '.jwbp-addnew' ).find( 'tr.jwbp-newitem' );
			var newindex = parseInt( lastrow_el.find( 'input[name^="jwbp-addnew-data["][name$="[index]"]' ).val() ) + 1;
			var newindex_visual = newindex + 1;
			
			var newel = $( newrow_el[0].outerHTML.replace( /JWBP_NEWINDEX_VISUAL/gm, newindex_visual.toString() ).replace( /JWBP_NEWINDEX/gm, newindex.toString() ) );
			
			lastrow_el.after( newel );
			newel.removeClass( 'jwbp-newitem' );
			
			$( this ).parents( 'tr' ).addClass( 'jwbp-addnew-row-active' );
		}
	} );
} );