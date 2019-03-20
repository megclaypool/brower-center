jQuery( function( $ ) {	
	// Handle change in selected posttype
	$( '#jwbp-addposts-posttype' ).change( function() {
		var el = this;
		
		var posttype = $( this ).val();
		
		$( this ).siblings( '.ajax-loading' ).css( 'visibility', 'visible' );
		
		$.post( ajaxurl, {
			action: 'jwbp_ajax_get_posttype',
			posttype: posttype
		}, function( data ) {
			$( el ).siblings( '.ajax-loading' ).css( 'visibility', 'hidden' );
			
			if ( typeof data.error == 'undefined' || !data.error ) {
				$( '#jwbp-addposts-topparent' ).replaceWith( data.posts_select_html );
				
				if ( data.posttype.hierarchical ) {
					$( '.jwbp-filter-hierarchical-1' ).show();
					$( '.jwbp-filter-hierarchical-0' ).hide();
				}
				else {
					$( '.jwbp-filter-hierarchical-1' ).hide();
					$( '.jwbp-filter-hierarchical-0' ).show();
				}
			}
		}, 'json' );
	} );
} );