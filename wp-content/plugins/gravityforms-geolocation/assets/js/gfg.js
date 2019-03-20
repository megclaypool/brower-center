var GF_Geo = {

	// plugin's options
	options : ggfSettings,
	
	// default country code for Google API
	country_code : 'US',
	
	// navigator timeout  limit 
    navigator_timeout : 10000,

    field_locator : {},

    // map vars
	map : {
		args    : false,
		options : false,
		map     : false,
		latLng  : false,
		marker  : false
	},

	processing : {
		status  : false,
		element : false,
		coords  : []
	},

    // Navigator error messages
    navigator_error_messages : {
        1 : 'User denied the request for Geolocation.',
        2 : 'Location information is unavailable.',
        3 : 'The request to get user location timed out.',
        4 : 'An unknown error occurred',
        5 : 'Sorry! Geolocation is not supported by this browser.'
    },

    // page locatore vars
    auto_locator : {
        status   	: false,
        id       	: false,
        success  	: false,
        failed    	: false,
        auto_submit : false   
    },

    address_autocomplete : {
    	field    : false,
    	settings : false
    },

    // form submission vars
    submit_form : false,

    /**
     * Run on page load
     * 
     * @return {[type]} [description]
     */
    init : function() {

		// hide submit button if needed. when using locator button.
		if ( GF_Geo.options.locator_hide_submit == 1 ) {
			jQuery( '#gform_submit_button_' + GF_Geo.options.id ).hide();
		}
        
        // render map if exist on page
        if ( jQuery( '#ggf-map' ).length ) {
    		
    		// get map field options
    		GF_Geo.map.args = mapArgs;

    		// generate map
        	GF_Geo.render_map();
        }

		// run autolocator on page load if needed
		if ( GF_Geo.options.auto_locator.use == 1 ) {
	        	
	        // run auto locator
			GF_Geo.auto_locator( 'page_load', GF_Geo.page_locator_success, false );
		}

	    // generate autocompelte fields
		jQuery( '.ggf-autocomplete' ).each( function() {

            if ( jQuery( this ).is( '[id]' ) ) {
                GF_Geo.address_autocomplete( jQuery( this ).attr( 'id' ), false );
            }
        });

		// generate some data for the advacned address field
		if ( jQuery( '.gform-address-field' ).length ) {
			GF_Geo.init_advanced_address_field();		
		}

		// on form submission
		jQuery( '#gform_submit_button_' + GF_Geo.options.id ).on( 'click', function( event ) {

        	// procceed submission if no geo field found in the form
			if ( ! jQuery( '.ggf-geocode-field' ).length || GF_Geo.submit_form == true ) {
				return true;
			} else {
				GF_Geo.form_submission( event );
			}			
		});	

		if ( jQuery( '.ggf-field-locator-button' ).length ) {
			
			jQuery( '.ggf-field-locator-button' ).each( function() {

				var fieldId = jQuery( this ).attr( 'id' ).split( '_' );
				fieldId = fieldId[fieldId.length-1];

				GF_Geo.field_locator  = '<span class="ggf-field-locator-wrapper">';
				GF_Geo.field_locator += 	'<img class="ggf-locator-button locator-'+ fieldId + '" locator-id="' + fieldId + '" style="box-shadow: none;border-radius:0" src="' + GF_Geo.options.images_url + '/assets/images/locator.png" />';
				GF_Geo.field_locator += 	'<img class="ggf-locator-spinner spinner-' + fieldId + '" style="display:none; box-shadow: none;border-radius:0" src="' + GF_Geo.options.images_url + '/assets/images/loader.gif" />';
				GF_Geo.field_locator += '</span>';

				if ( jQuery ( this ).hasClass( 'gform-address-field' ) ) {
					jQuery( this ).find( '.ggf-advanced-address-autocomplete-wrapper' ).append( GF_Geo.field_locator );
				} else {
					jQuery( this ).find( '.ginput_container' ).append( GF_Geo.field_locator );
				}
			});
		}

		// locator button clicked 
	    jQuery( '.ggf-locator-button' ).on( 'click', function() {

	    	// get locator id
	    	field_id = jQuery( this ).attr( 'locator-id' );

	    	// run navigator
	    	GF_Geo.auto_locator( field_id, GF_Geo.locator_button_success, false );
	  	}); 

		// clear location fields when changing geocoded address fields
		if ( jQuery( '.ggf-field' ).length ) {

			// trigger input event for address text fields
			jQuery( '.ggf-field input[type="text"]' ).on( 'input', function() {
				
				// clear fields
				GF_Geo.clear_fields();
			});
		}
    },

    /**
     * Clear all location fields
     * @return {[type]} [description]
     */
    clear_fields : function() {
    	jQuery( '#ggf-text-fields-wrapper [id^="ggf-field"], [class*="ggf-cf-"] input[type="text"], [class*="ggf-cf-"] input[type="hidden"]' ).val('');
		jQuery( '[class*="ggf-cf-"] input[type="radio"], [class*="ggf-cf-"] input[type="checkbox"]' ).prop( 'checked', false );
    },

    /**
     * Submit form
     * 
     * @return {[type]} [description]
     */
    submit_form : function() {

    	setTimeout( function() {
            
            GF_Geo.submit_form = true;
		    
		    jQuery( '#gform_' + GF_Geo.options.id ).submit();	
		    
		    return false; 

        }, 1000); 
    },

    /**
     * Get user's current position
     * 
     * @param  {function} success callback function when navigator success
     * @param  {function} failed  callback function when navigator failed
     * 
     * @return {[type]}                   [description]
     */
    navigator : function( success, failed ) {

    	success = ( success == 'undefined' || success == false ) ? GF_Geo.navigator_success : success;
    	failed  = ( failed  == 'undefined' || failed  == false ) ? GF_Geo.navigator_failed  : failed;

        // if navigator exists ( in browser ) try to locate the user
        if ( navigator.geolocation ) {
           
            navigator.geolocation.getCurrentPosition( show_position, show_error, { 
            	timeout: GF_Geo.navigator_timeout 
            } );
        
        // otherwise, show an error message
        } else {
            return failed( GF_Geo.navigator_error_messages[5] );
        }

        // geocode the coordinates if current position found
        function show_position( position ) {
            GF_Geo.geocoder( [ position.coords.latitude, position.coords.longitude ], success, failed );
        }

        // show error if failed navigator
        function show_error( error ) {
        	failed( GF_Geo.navigator_error_messages[error.code] );
        }
    },

    navigator_success : function( results ) {

        GF_Geo.save_location_fields( results );

    },

    navigator_failed : function( status ) {

        alert( status );
    },

    /**
     * Geocoder function. 
     *
     * Can be used for geocoding an address or reverse geocoding coordinates.
     * 
     * @param  string | array location string if geocoding an address or array of coordinates [ lat, lng ] if reverse geocoding.
     * 
     * @param  {function} success  callback function on success
     * @param  {function} failed   callback function on failed
     * 
     * @return {[type]}          [description]
     */
    geocoder : function( location, success, failed ) {

        // get region from settings
        countryCode = ( GF_Geo.options.country_code != undefined ) ? GF_Geo.options.country_code : 'us';

        // get geocoder data
        // If reverse geocoding 
        if ( typeof location === 'object' ) {

            data = { 
                'latLng' : new google.maps.LatLng( location[0], location[1] ), 
                'region' : countryCode 
            };

        // otherwise, if geocoding an address
        } else {
            data = { 
                'address' : location, 
                'region'  : countryCode 
            };
        }

        // init google geocoder
        geocoder = new google.maps.Geocoder();

        // run geocoder
        geocoder.geocode( data, function( results, status ) {
     
            // on success
            if ( status == google.maps.GeocoderStatus.OK ) {

                return ( success != undefined && success != false ) ? success( results[0] ) : GF_Geo.geocoder_success( results[0] );

            // on failed      
            } else {

                return ( failed != undefined && failed != false ) ? failed( status ) : GF_Geo.geocoder_failed( status );
            }
        });
    },

    /**
     * Geocoder success default callback functions
     * 
     * @return {[type]} [description]
     */
    geocoder_success : function( results ) {

    },

    /**
     * Geocoder failed default callback functions
     * 
     * @return {[type]} [description]
     */
    geocoder_failed : function( status) {
        alert( "We could not find the address you entered for the following reason: " + status );
    },

    /**
     * Generate, dynamically, some fields element in the advanced address field. 
     * 
     * @return {[type]} [description]
     */
    init_advanced_address_field : function() {

    	jQuery( '.gform-address-field' ).each(function() {
			
			var thisField = jQuery( this );
			var lfClass   = '';
			var aField    = '';
			
			if ( thisField.hasClass( 'ggf-autocomplete' ) ) {

				autocompleteWrapper = thisField.find( '.ggf-advanced-address-autocomplete-wrapper' );

				tabindex = thisField.find( '.ginput_complex input[type=text]' ).not( '.ggf-advanced-address-autocomplete' ).attr( 'tabindex' );

				autocompleteWrapper.detach().prependTo( thisField.find( '.ginput_complex' ) ).show().find( 'input[type=text]' ).attr( 'tabindex', tabindex );
			}
			
			if ( thisField.hasClass( 'locator-fill') ) {
				lfClass += ' locator-fill';
				aField   = 'ggf-cf';
			}
			
			if ( thisField.hasClass( 'map-autofill') ) {
				lfClass += ' map-autofill';
				aField   = 'ggf-cf';
			}
			
			if ( thisField.hasClass( 'ggf-advanced-geocode-true' ) ) {
				aField = 'ggf-field ggf-field';
			}
			
			thisField.find( jQuery( '[id$=_1_container]' ) ).addClass( aField + '-street ggf-advanced-address-cf-field ' + lfClass );
			thisField.find( jQuery( '[id$=_2_container]' ) ).addClass( aField + '-apt ggf-advanced-address-cf-field ' + lfClass );
			thisField.find( jQuery( '[id$=_3_container]' ) ).addClass( aField + '-city ggf-advanced-address-cf-field ' + lfClass );
			thisField.find( jQuery( '[id$=_4_container]' ) ).addClass( aField + '-state ggf-advanced-address-cf-field ' + lfClass );
			thisField.find( jQuery( '[id$=_5_container]' ) ).addClass( aField + '-zipcode ggf-advanced-address-cf-field ' + lfClass );
			thisField.find( jQuery( '[id$=_6_container]' ) ).addClass( aField + '-country ggf-advanced-address-cf-field ' + aField + '-country_long ' + lfClass );	
		});
    },

    /**
     * Render map on page load
     * 
     * @return {[type]} [description]
     */
    render_map : function() {

    	// get position
		GF_Geo.map.latlng = new google.maps.LatLng( GF_Geo.map.args.latitude, GF_Geo.map.args.longitude );
		
		// map options
		GF_Geo.map.options = {
			zoom 	  : parseInt( GF_Geo.map.args.zoom_level),
			center 	  : GF_Geo.map.latlng,
			mapTypeId : google.maps.MapTypeId[ GF_Geo.map.args.map_type ]
		};
	
		// generate the map
		GF_Geo.map.map = new google.maps.Map( document.getElementById( 'ggf-map' ), GF_Geo.map.options );
		
		// generate marker
		GF_Geo.map.marker = new google.maps.Marker({
			position  : GF_Geo.map.latlng,
			map       : GF_Geo.map.map,
			draggable : true
		});
		
		// when dragging the marker on the map
		google.maps.event.addListener( GF_Geo.map.marker, 'dragend', function( event ){
			
			// save some map details
			GF_Geo.processing.status  = true;
			GF_Geo.processing.element = 'map';
			GF_Geo.processing.coords  = { 
				'lat' : event.latLng.lat(), 
				'lng' : event.latLng.lng() 
			};

			// geocode coords and get address fields
			GF_Geo.geocoder( [ event.latLng.lat(), event.latLng.lng() ], GF_Geo.map_geocoder_success, false );  
		});

		// move marker on click
		//if ( typeof GF_Geo.drag_marker_onclick != 'undefined' ) {
		//	GF_Geo.drag_marker_onclick();
		//}

		// resize map if hidden and triggered using conditional logic
		gform.addAction( 'gform_post_conditional_logic_field_action', function (formId, action, targetId, defaultValues, isInit) {
			
			// only if logic trigger set to show
		    if ( !isInit && action == 'show' ) {

		        var target   = jQuery(targetId),
		        coupon_items = target.find('div#ggf-map');

		        if ( coupon_items.length ) {

		        	// resize map
		        	google.maps.event.trigger( GF_Geo.map.map, 'resize' );
		        	// center marker
		        	GF_Geo.map.map.panTo( GF_Geo.map.marker.position );
		        }
		    }
		});
	},

	/*
	drag_marker_onclick : function() {

		google.maps.event.addListener( GF_Geo.map.map, 'click', function( event ){
			
			GF_Geo.map.marker.setPosition( event.latLng );
		
			// save some map details
			GF_Geo.processing.status  = true;
			GF_Geo.processing.element = 'map';
			GF_Geo.processing.coords  = { 
				'lat' : event.latLng.lat(), 
				'lng' : event.latLng.lng() 
			};
		
			// geocode coords and get address fields
			GF_Geo.geocoder( [ event.latLng.lat(), event.latLng.lng() ], GF_Geo.map_geocoder_success, false );  
		});
	},
	*/

	// update map
	update_map : function( lat, lng ) {
		
		//check that map exists on the form
		if ( ! jQuery( '#ggf-map' ).length ) 
			return;
		
		// get coords of new position
		GF_Geo.map.latLng = new google.maps.LatLng( lat, lng );

		// set new position
		GF_Geo.map.marker.setPosition( GF_Geo.map.latLng );

		// pan map into new position
		GF_Geo.map.map.panTo( GF_Geo.map.latLng );
	},

	// map geocoder success callback function
	map_geocoder_success : function( results ) {

		// save location data
		GF_Geo.save_location_fields( results, 'map' );
	},

    /**
     * Google places address autocomplete
     * 
     * @return void
     */
    address_autocomplete : function( field_id , success ) {
        
        // prevent form submission on enter to be able to select an address by pressing enter key
		jQuery( '#' + field_id ).keypress( function( event ){
		    if ( event.which == 13 ) {
		    	return false;
		    }
		});

		// field object
		var thisField = jQuery( '#' + field_id );

		// field settings
		var fieldID = thisField.attr( 'id' ).split( '_' );
		var fieldSettings = GF_Geo.options['fields'][ fieldID[fieldID.length-1] ];
	          
        if ( thisField.hasClass( 'gform-address-field' ) ) {

        	var input = document.getElementById( thisField.find( '.ggf-advanced-address-autocomplete' ).attr( 'id' ) );
        } else {
        	var input = document.getElementById( thisField.find( 'div :input' ).attr('id') );
        }

        // if displaying results worldwide
        if ( fieldSettings['restrictions'] == false ) {

	        var options = {};

	    // otherwise restrict to single country
        } else {
        	var options = {
	        	componentRestrictions: { 
	        		country : fieldSettings['restrictions'] 
	        	}
	        };
        }

        // init autocomplete
        var autocomplete = new google.maps.places.Autocomplete( input, options );
        
        // on place change
        google.maps.event.addListener( autocomplete, 'place_changed', function(e) {

        	// get place data
        	var place = autocomplete.getPlace();
 			
			if ( ! place.geometry ) {
				return;
			}

			// dynamically trigger change event when choice was selected
			jQuery( input ).trigger( 'change' );
			
			// if field set to be geocoded we need to udpate hidden location fields of location
			if ( thisField.hasClass( 'ggf-full-address' ) || thisField.hasClass('ggf-advanced-geocode-true') ) {
               	GF_Geo.save_location_fields( place, 'autocomplete' );
            }
			
        	// update map if needed
			if ( jQuery( '#ggf-map' ).length != 0 && fieldSettings['update_map'] == 1 ) {
								
				GF_Geo.update_map( place.geometry.location.lat(), place.geometry.location.lng() );
			}
		
			// dynamically fill advanced address fields
			if ( input.className == 'ggf-advanced-address-autocomplete' ) {

				address = place.address_components;
				thisField.find( '.ggf-advanced-address-cf-field input' ).val('');
				
				var street_number = false;
				
				for ( x in address ) {
					
					//update street_number fields
					if ( address[x].types == 'street_number' ) {

						street_number = address[x].long_name;

						thisField.find( '.ggf-cf-street_number input, .ggf-field-street_number input' ).val( street_number ).trigger( 'change' );
					}
					
					//update street_name and street fields
					if ( address[x].types == 'route' ) {

						street_name = address[x].long_name;  

						thisField.find( '.ggf-cf-street_name input, .ggf-field-street_name input' ).val( street_name ).trigger( 'change' );
						
						if ( street_number != false ) {
							
							if ( typeof GF_Geo.options.address_fields.street_fix.enabled != 'undefined' && GF_Geo.options.address_fields.street_fix.enabled == 1 ) {
								street = street_name + ' ' + street_number;
							} else {
								street = street_number + ' ' + street_name;
							}
							
							thisField.find( '.ggf-cf-street input, .ggf-field-street input' ).val( street ).trigger( 'change' );
						
						} else {
							thisField.find( '.ggf-cf-street input, .ggf-field-street input' ).val( street_name ).trigger( 'change' );
						}
					}
			
					//get city
					if ( address[x].types == 'locality,political' ) {
		            	
			            thisField.find('.ggf-cf-city input, .ggf-field-city input').val( address[x].long_name ).trigger( 'change' );
		            } 
					
					//get state
					if ( address[x].types == 'administrative_area_level_1,political' ) {
						
		                state = address[x].short_name;
		                state_long = address[x].long_name;
		                
		                //update hidden and custom location fields
		                thisField.find( '.ggf-cf-state input, .ggf-field-state input' ).val( state ).trigger( 'change' );             
		                thisField.find( '.ggf-cf-state option[value="' + state_long + '"], .ggf-field-state option[value="' + state_long + '"]').attr( 'selected','selected' );
		                thisField.find( '.ggf-cf-state_long option[value="' + state_long + '"], .ggf-field-state_long option[value="' + state_long +' "]').attr( 'selected','selected' );		       
		            } 

					//get zipcode
		            if (address[x].types == 'postal_code') {
		            			                
		                thisField.find( '.ggf-cf-zipcode input, .ggf-field-zipcode input' ).val( address[x].long_name ).trigger( 'change' );			                			                
		            } 

		            //get country
		            if (address[x].types == 'country,political') {
		            	
		                country = address[x].short_name;
		                country_long = address[x].long_name;
		                
		                thisField.find( '.ggf-cf-country input, .ggf-field-country input' ).val(country).trigger( 'change' );
		                thisField.find( '.ggf-cf-country option[value="' + country_long + '"], .ggf-field-country option[value="' + country_long + '"]' ).attr( 'selected','selected' );
		                thisField.find( '.ggf-cf-country_long option[value="' + country_long + '"], .ggf-field-country_long option[value="'+country_long+'"]' ).attr( 'selected','selected' );
		             } 
		        }
			}
	    });   
    },

    /**
     * Save location fields into cookies and current location hidden form
     * 
     * @param  {object} results location data returned forom geocoder
     * 
     * @return {[type]}         [description]
     */
    save_location_fields : function( results, element ) {

    	// clear all location fields.
    	GF_Geo.clear_fields();

        // address fields holder
        address_fields = {
            'street_number'     : '',
            'street_name'       : '',
            'street'            : '',
            'premise'           : '',
            'neighborhood'      : '',
            'city'              : '',
            'region_code'       : '',
            'region_name'       : '',
            'country'           : '',
            'postcode'          : '',
            'country_code'      : '',
            'country_name'      : '',
            'address'           : results.formatted_address,
            'formatted_address' : results.formatted_address,
            'lat'               : results.geometry.location.lat(),
            'lng'               : results.geometry.location.lng()
        };
        
        // if updating location from mao we will use the coordinates that we got directly from the dragged marker
        if ( GF_Geo.processing.status == true && GF_Geo.processing.element == 'map' ) {

        	address_fields.lat = GF_Geo.processing.coords.lat;
        	address_fields.lng = GF_Geo.processing.coords.lng;

        } else {

        	address_fields.lat = results.geometry.location.lat();
        	address_fields.lng = results.geometry.location.lng();
        }

        jQuery( '.ggf-cf-lat input[type="text"], .ggf-cf-lat input[type="hidden"], #ggf-field-lat' ).val( address_fields.lat ).trigger( 'change' );
		jQuery( '.ggf-cf-lng input[type="text"], .ggf-cf-lng input[type="hidden"], #ggf-field-lng' ).val( address_fields.lng ).trigger( 'change' );

		if ( element == 'map' ) {
			
			jQuery( '.map-autofill input[type="text"], .map-autofill input[type="hidden"]' ).val('');
			jQuery( '.map-autofill.ggf-full-address input[type="text"], .map-autofill.ggf-full-address input[type="hidden"]' ).val( address_fields.formatted_address );

		} else if ( element == 'locator' ) { 

			jQuery( '.locator-fill input[type="text"], .locator-fill input[type="hidden"]' ).val('');
			jQuery( '.locator-fill.ggf-full-address input[type="text"], .locator-fill.ggf-full-address input[type="hidden"]' ).val( address_fields.formatted_address );
		}

		jQuery( '#ggf-field-formatted_address' ).val( address_fields.formatted_address);
		jQuery( '.ggf-cf-formatted_address input[type="text"], .ggf-cf-formatted_address input[type="hidden"]').val( address_fields.formatted_address );

		address = results.address_components;

        /* check for each of the address components and if exist save it in a cookie */
        for ( x in address ) {

            // street number
            if ( address[x].types == 'street_number' && address[x].long_name != undefined ) {
            	    
                address_fields.street_number = address[x].long_name;
               
                jQuery( '#ggf-field-street_number, .ggf-cf-street_number input[type="text"], .ggf-cf-street_number input[type="hidden"]' ).val( address_fields.street_number ).trigger( 'change' );

                if ( element == 'map' ) {
    				
    				jQuery( '.map-autofill.ggf-field-street_number input[type="text"], .map-autofill.ggf-field-street_number input[type="hidden"]' ).val( address_fields.street_number ).trigger( 'change' );

    			//update fields with values from auto locator
    			} else if ( element == 'locator' ) { 
    				
    	            jQuery( '.locator-fill.ggf-field-street_number input[type="text"], .locator-fill.ggf-field-street_number input[type="hidden"]' ).val( address_fields.street_number ).trigger( 'change' );

    	        } 
            } 

            // street name and street
            if ( address[x].types == 'route' && address[x].long_name != undefined ) {  

                 //save street name in variable
                address_fields.street_name = address[x].long_name;
				
				jQuery( '#ggf-field-street_name, .ggf-cf-street_name input[type="text"], .ggf-cf-street_name input[type="hidden"]' ).val( address_fields.street_name ).trigger( 'change' );

                if ( element == 'map' ) {
    				
    				jQuery( '.map-autofill.ggf-field-street_name input[type="text"], .map-autofill.ggf-field-street_name input[type="hidden"]' ).val( address_fields.street_name ).trigger( 'change' );

    			//update fields with values from auto locator
    			} else if ( element == 'locator' ) { 
    				
    	            jQuery( '.locator-fill.ggf-field-street_name input[type="text"], .locator-fill.ggf-field-street_name input[type="hidden"]' ).val( address_fields.street_name ).trigger( 'change' );

    	        } 

	    	    //udpate street ( number + name ) fields  if street_number exists
				if ( address_fields.street_number != '' ) {

					if ( typeof GF_Geo.options.address_fields.street_fix.enabled != 'undefined' && GF_Geo.options.address_fields.street_fix.enabled == 1 ) {
						address_fields.street = address_fields.street_name + ' ' + address_fields.street_number;
					} else {
						address_fields.street = address_fields.street_number + ' ' + address_fields.street_name;
					}
								
					
			 	} else {
			 		address_fields.street = address_fields.street_name;
			 	} 	  

				jQuery( '#ggf-field-street, .ggf-cf-street input[type="text"], .ggf-cf-street input[type="hidden"]' ).val( address_fields.street ).trigger( 'change' );

                if ( element == 'map' ) {
    				
    				jQuery( '.map-autofill.ggf-field-street input[type="text"], .map-autofill.ggf-field-street input[type="hidden"]' ).val( address_fields.street ).trigger( 'change' );

    			//update fields with values from auto locator
    			} else if ( element == 'locator' ) { 
    				
    	            jQuery( '.locator-fill.ggf-field-street input[type="text"], .locator-fill.ggf-field-street input[type="hidden"]' ).val( address_fields.street ).trigger( 'change' );

    	        } 
            }

            // apt/suit number
            if ( address[x].types == 'subpremise' && address[x].long_name != undefined ) {

                address_fields.premise = address[x].long_name;

                jQuery( '#ggf-field-apt, .ggf-cf-apt input[type="text"], .ggf-cf-apt input[type="hidden"]' ).val( address_fields.permise ).trigger( 'change' );

                if ( element == 'map' ) {
    				
    				jQuery( '.map-autofill.ggf-field-apt input[type="text"], .map-autofill.ggf-field-apt input[type="hidden"]' ).val( address_fields.permise ).trigger( 'change' );

    			//update fields with values from auto locator
    			} else if ( element == 'locator' ) { 
    				
    	            jQuery( '.locator-fill.ggf-field-apt input[type="text"], .locator-fill.ggf-field-apt input[type="hidden"]' ).val( address_fields.permise ).trigger( 'change' );

    	        } 
            }
            
            // neighborhood
             if ( address[x].types == 'neighborhood,political' && address[x].long_name != undefined ) {

                address_fields.neighborhood = address[x].long_name;

                jQuery( '#ggf-field-neighborhood, .ggf-cf-neighborhood input[type="text"], .ggf-cf-neighborhood input[type="hidden"]' ).val( address_fields.neighborhood ).trigger( 'change' );

                if ( element == 'map' ) {
    				
    				jQuery( '.map-autofill.ggf-field-neighborhood input[type="text"], .map-autofill.ggf-field-neighborhood input[type="hidden"]' ).val( address_fields.neighborhood ).trigger( 'change' );

    			//update fields with values from auto locator
    			} else if ( element == 'locator' ) { 
    				
    	            jQuery( '.locator-fill.ggf-field-neighborhood input[type="text"], .locator-fill.ggf-field-neighborhood input[type="hidden"]' ).val( address_fields.neighborhood ).trigger( 'change' );

    	        } 
            }
            
            // city
            if( address[x].types == 'locality,political' && address[x].long_name != undefined ) {

                address_fields.city = address[x].long_name;

	            jQuery( '#ggf-field-city, .ggf-cf-city input[type="text"], .ggf-cf-city input[type="hidden"]' ).val( address_fields.city ).trigger( 'change' );

                if ( element == 'map' ) {
    				
    				jQuery( '.map-autofill.ggf-field-city input[type="text"], .map-autofill.ggf-field-city input[type="hidden"]' ).val( address_fields.city ).trigger( 'change' );

    			//update fields with values from auto locator
    			} else if ( element == 'locator' ) { 
    				
    	            jQuery( '.locator-fill.ggf-field-city input[type="text"], .locator-fill.ggf-field-city input[type="hidden"]' ).val( address_fields.city ).trigger( 'change' );

    	        } 
            }
            
            // region code and name
            if ( address[x].types == 'administrative_area_level_1,political' ) {

                address_fields.region_name = address[x].long_name;
                address_fields.region_code = address[x].short_name;
                
                jQuery( '#ggf-field-state, .ggf-cf-state input[type="text"], .ggf-cf-state input[type="hidden"]' ).val( address_fields.region_code ).trigger( 'change' );
                jQuery( '#ggf-field-state_long, .ggf-cf-state_long input[type="text"], .ggf-cf-state_long input[type="hidden"]' ).val( address_fields.region_name ).trigger( 'change' );

                jQuery('.ggf-cf-state select option[value="'+address_fields.region_name+'"], .ggf-cf-state_long select option[value="'+address_fields.region_name+'"]' ).attr("selected","selected");
  
                jQuery('.ggf-cf-state ul.gfield_checkbox input[value="'+address_fields.region_code+'"], .ggf-cf-state ul.gfield_radio input[value="'+address_fields.region_code+'"]').prop('checked', true);
                jQuery('.ggf-cf-state_long ul.gfield_checkbox input[value="'+address_fields.region_name+'"], .ggf-cf-state_long ul.gfield_radio input[value="'+address_fields.region_name+'"]').prop('checked', true);

                if ( element == 'map' ) {
    				
    				jQuery( '.map-autofill.ggf-field-state input[type="text"], .map-autofill.ggf-field-state input[type="hidden"]' ).val( address_fields.region_code ).trigger( 'change' );
    				jQuery( '.map-autofill.ggf-field-state_long input[type="text"], .map-autofill.ggf-field-state_long input[type="hidden"]' ).val( address_fields.region_name ).trigger( 'change' );

    				jQuery( '.map-autofill.ggf-field-state select option[value="'+address_fields.region_name+'"], .map-autofill.ggf-field-state_long select option[value="'+address_fields.region_name+'"]' ).attr( 'selected','selected' );

    				jQuery( '.map-autofill.ggf-field-state ul.gfield_radio input[value="'+address_fields.region_name+'"], .map-autofill.ggf-field-state_long ul.gfield_radio input[value="'+address_fields.region_name+'"]' ).prop( 'checked', true );

    				jQuery( '.map-autofill.ggf-field-state ul.gfield_checkbox input[value="'+address_fields.region_name+'"], .map-autofill.ggf-field-state_long ul.gfield_checbox input[value="'+address_fields.region_name+'"]' ).prop( 'checked', true );

    			//update fields with values from auto locator
    			} else if ( element == 'locator' ) { 
    				
    	            jQuery( '.locator-fill.ggf-field-state input[type="text"], .locator-fill.ggf-field-state input[type="hidden"]' ).val( address_fields.region_code ).trigger( 'change' );
    				jQuery( '.locator-fill.ggf-field-state_long input[type="text"], .locator-fill.ggf-field-state_long input[type="hidden"]' ).val( address_fields.region_name ).trigger( 'change' );
    				jQuery( '.locator-fill.ggf-field-state select option[value="'+address_fields.region_name+'"], .locator-fill.ggf-field-state_long select option[value="'+address_fields.region_name+'"]' ).attr( 'selected','selected' );
    				jQuery( '.locator-fill.ggf-field-state ul.gfield_radio input[value="'+address_fields.region_name+'"], .locator-fill.ggf-field-state_long ul.gfield_radio input[value="'+address_fields.region_name+'"]' ).prop( 'checked', true );
    				jQuery( '.locator-fill.ggf-field-state ul.gfield_checkbox input[value="'+address_fields.region_name+'"], .locator-fill.ggf-field-state_long ul.gfield_checbox input[value="'+address_fields.region_name+'"]' ).prop( 'checked', true );
    	        }                 
            }  
            
            // county
            if ( address[x].types == 'administrative_area_level_2,political' && address[x].long_name != undefined ) {

                address_fields.county = address[x].long_name;

                jQuery( '#ggf-field-county, .ggf-cf-county input[type="text"], .ggf-cf-county input[type="hidden"]' ).val( address_fields.county ).trigger( 'change' );

                if ( element == 'map' ) {
    				
    				jQuery( '.map-autofill.ggf-field-county input[type="text"], .map-autofill.ggf-field-county input[type="hidden"]' ).val( address_fields.county ).trigger( 'change' );

    			//update fields with values from auto locator
    			} else if ( element == 'locator' ) { 
    				
    	            jQuery( '.locator-fill.ggf-field-county input[type="text"], .locator-fill.ggf-field-county input[type="hidden"]' ).val( address_fields.county ).trigger( 'change' );

    	        } 
            }

            // postal code
            if ( address[x].types == 'postal_code' && address[x].long_name != undefined ) {

                address_fields.postcode = address[x].long_name;
                
                jQuery( '#ggf-field-zipcode, .ggf-cf-zipcode input[type="text"], .ggf-cf-zipcode input[type="hidden"]' ).val( address_fields.postcode ).trigger( 'change' );

                if ( element == 'map' ) {
    				
    				jQuery( '.map-autofill.ggf-field-zipcode input[type="text"], .map-autofill.ggf-field-zipcode input[type="hidden"]' ).val( address_fields.postcode ).trigger( 'change' );

    			//update fields with values from auto locator
    			} else if ( element == 'locator' ) { 
    				
    	            jQuery( '.locator-fill.ggf-field-zipcode input[type="text"], .locator-fill.ggf-field-zipcode input[type="hidden"]' ).val( address_fields.postcode ).trigger( 'change' );

    	        } 
            }
            
            // country code and name
            if ( address[x].types == 'country,political' ) {

                address_fields.country_name = address[x].long_name;
                address_fields.country_code = address[x].short_name;

                jQuery( '#ggf-field-country, .ggf-cf-country input[type="text"], .ggf-cf-country input[type="hidden"]' ).val( address_fields.country_code ).trigger( 'change' );
                jQuery( '#ggf-field-country_long, .ggf-cf-country_long input[type="text"], .ggf-cf-country_long input[type="hidden"]' ).val( address_fields.country_name ).trigger( 'change' );

                jQuery('.ggf-cf-countryselect option[value="'+address_fields.country_name+'"], .ggf-cf-country_long select option[value="'+address_fields.country_name+'"]' ).attr("selected","selected");
  
                jQuery('.ggf-cf-country ul.gfield_checkbox input[value="'+address_fields.country_code+'"], .ggf-cf-country ul.gfield_radio input[value="'+address_fields.country_code+'"]').prop('checked', true);
                jQuery('.ggf-cf-country_long ul.gfield_checkbox input[value="'+address_fields.country_name+'"], .ggf-cf-country_long ul.gfield_radio input[value="'+address_fields.country_name+'"]').prop('checked', true);

                if ( element == 'map' ) {
    				
    				jQuery( '.map-autofill.ggf-field-country input[type="text"], .map-autofill.ggf-field-country input[type="hidden"]' ).val( address_fields.country_code ).trigger( 'change' );
    				jQuery( '.map-autofill.ggf-field-country_long input[type="text"], .map-autofill.ggf-field-country_long input[type="hidden"]' ).val( address_fields.country_name ).trigger( 'change' );

    				jQuery( '.map-autofill.ggf-field-country select option[value="'+address_fields.country_name+'"], .map-autofill.ggf-field-country_long select option[value="'+address_fields.country_name+'"]' ).attr( 'selected','selected' );

    				jQuery( '.map-autofill.ggf-field-country ul.gfield_radio input[value="'+address_fields.country_name+'"], .map-autofill.ggf-field-country_long ul.gfield_radio input[value="'+address_fields.country_name+'"]' ).prop( 'checked', true );

    				jQuery( '.map-autofill.ggf-field-country ul.gfield_checkbox input[value="'+address_fields.country_name+'"], .map-autofill.ggf-field-country_long ul.gfield_checbox input[value="'+address_fields.country_name+'"]' ).prop( 'checked', true );

    			//update fields with values from auto locator
    			} else if ( element == 'locator' ) { 
    			
    	            jQuery( '.locator-fill.ggf-field-country input[type="text"], .locator-fill.ggf-field-country input[type="hidden"]' ).val( address_fields.country_code ).trigger( 'change' );
    				jQuery( '.locator-fill.ggf-field-country_long input[type="text"], .locator-fill.ggf-field-country_long input[type="hidden"]' ).val( address_fields.country_name ).trigger( 'change' );
    				jQuery( '.locator-fill.ggf-field-country select option[value="'+address_fields.country_name+'"], .locator-fill.ggf-field-country_long select option[value="'+address_fields.country_name+'"]' ).attr( 'selected','selected' );
    				jQuery( '.locator-fill.ggf-field-country ul.gfield_radio input[value="'+address_fields.country_name+'"], .locator-fill.ggf-field-country_long ul.gfield_radio input[value="'+address_fields.country_name+'"]' ).prop( 'checked', true );
    				jQuery( '.locator-fill.ggf-field-country ul.gfield_checkbox input[value="'+address_fields.country_name+'"], .locator-fill.ggf-field-country_long ul.gfield_checbox input[value="'+address_fields.country_name+'"]' ).prop( 'checked', true );
    	        }                 
            } 
        }

        return address_fields;
    },

    /**
     * Page load locator function.
     *
     * Get the user's current location on page load
     * 
     * @return {[type]} [description]
     */
    auto_locator : function( id, success, failed ) {

        // set status to true
        GF_Geo.auto_locator.status  = true;
        GF_Geo.auto_locator.id      = id;
        GF_Geo.auto_locator.success = success;
        GF_Geo.auto_locator.failed  = failed;
        
        // show spinners
        jQuery( '.ggf-field-locator-wrapper .ggf-locator-button' ).fadeOut( 'fast', function() {
        	jQuery( '.ggf-locator-spinner' ).fadeIn();
        })

        jQuery( '.ggf-locator-spinner-wrapper' ).fadeIn();

        // run navigator
        GF_Geo.navigator( GF_Geo.auto_locator_success, GF_Geo.auto_locator_failed );
    },

    /**
     * page load locator success callback function
     * 
     * @param  {object} results location fields returned from geocoder
     * 
     * @return {[type]}         [description]
     */
    auto_locator_success : function( results ) {

        // save address field to cookies and current location form
        address_fields = GF_Geo.save_location_fields( results, 'locator' );

        setTimeout( function() {
	        
	        jQuery( '.ggf-field-locator-wrapper .ggf-locator-spinner' ).fadeOut( 'fast', function() {
	        	jQuery( '.ggf-field-locator-wrapper .ggf-locator-button' ).fadeIn();
	        });

	        jQuery( '.ggf-locator-spinner-wrapper' ).fadeOut();
    	}, 500 );

    	jQuery( 'input[type=text]#input_' + GF_Geo.options.id + '_' + GF_Geo.auto_locator.id + ', #field_' + GF_Geo.options.id + '_' + GF_Geo.auto_locator.id + ' .ggf-advanced-address-autocomplete' ).val( address_fields.formatted_address );

        // run custom callback function if set 
        if ( GF_Geo.auto_locator.success != false ) {

            GF_Geo.auto_locator.success( address_fields, results );

        // otherwise, get done with the function.
        } else {

            GF_Geo.auto_locator.status  = false;
            GF_Geo.auto_locator.type    = false;
            GF_Geo.auto_locator.success = false;
            GF_Geo.auto_locator.failed  = false;
        }
    },

    /**
     * page locator failed callback fucntion
     * 
     * @param  {string} status error message
     * 
     * @return {[type]}        [description]
     */
    auto_locator_failed : function( status ) {

    	setTimeout( function() {
	        
	        jQuery( '.ggf-field-locator-wrapper .ggf-locator-spinner' ).fadeOut( 'fast', function() {
	        	jQuery( '.ggf-field-locator-wrapper .ggf-locator-button' ).fadeIn();
	        });

	        jQuery( '.ggf-locator-spinner-wrapper' ).fadeOut();
    	}, 500 );

        // run custom failed callback function if set
        if ( GF_Geo.auto_locator.failed != false ) {

            GF_Geo.auto_locator.failed( status );
        
        // otherwise, get done with the function.
        } else {

            // alert error message
            alert( status );

            GF_Geo.auto_locator.status  = false;
            GF_Geo.auto_locator.type    = false;
            GF_Geo.auto_locator.success = false;
            GF_Geo.auto_locator.failed  = false;
        }
    },

    /**
     * Page locator success callback function
     * 
     * @param  {[type]} results [description]
     * @return {[type]}         [description]
     */
    page_locator_success : function( address_fields, results ) {
    	    	
		// update map based on locator position  		
  		GF_Geo.update_map( address_fields.lat, address_fields.lng );
  		
        GF_Geo.auto_locator.status  = false;
        GF_Geo.auto_locator.type    = false;
        GF_Geo.auto_locator.success = false;
        GF_Geo.auto_locator.failed  = false;     
    },

    /**
     * Locator button success callback function
     * @param  {[type]} address_fields [description]
     * @param  {[type]} results        [description]
     * @return {[type]}                [description]
     */
    locator_button_success : function( address_fields, results ) {

    	// show success message if needed
    	if ( typeof GF_Geo.options.fields[GF_Geo.auto_locator.id] != 'undefined' ) {

    		if ( GF_Geo.options['fields'][GF_Geo.auto_locator.id]['locator_autosubmit'] == 1 ) {
    			GF_Geo.auto_locator.auto_submit = true;
    		}

    		// alert success message
	    	if ( GF_Geo.options.fields[GF_Geo.auto_locator.id]['locator_found_message_use'] != 'undefined' && GF_Geo.options['fields'][GF_Geo.auto_locator.id]['locator_found_message_use'] == 1 ) {
				alert( GF_Geo.options['fields'][GF_Geo.auto_locator.id]['locator_found_message'] );
			}
		}

		// update map based on new position  		
  		GF_Geo.update_map( address_fields.lat, address_fields.lng );
  		
  		// autosubmit form if needed
  		if ( GF_Geo.auto_locator.auto_submit == true ) {
  			return GF_Geo.submit_form();
  		}
  	
        GF_Geo.auto_locator.status  	= false;
        GF_Geo.auto_locator.type    	= false;
        GF_Geo.auto_locator.success 	= false;
        GF_Geo.auto_locator.failed  	= false; 
        GF_Geo.auto_locator.auto_submit = false;    
    },

    /**
     * Form submission function.
     *
     * Executes on form submission
     *     
     * @param  {object} form  The submitted form
     * @param  {object} event submit event
     * 
     * @return {[type]}       [description]
     */
    form_submission : function( event ) {
        		
		// check if address field need to be geocoded 
		if ( jQuery( '#ggf-field-lat' ).val() == '' || jQuery( '#ggf-field-lng' ).val() == '' ) {
			
			// prevent form submission			
			event.preventDefault();		
			
			geoAddress = [];

			if ( GF_Geo.options.address_fields.use == 1 ) {
				
				// make sure address field is not empty
				geoAddress.push( jQuery( '.ggf-full-address input[type="text"]' ).val() );
				
				geoAddress = geoAddress.join(' ');

			} else if ( GF_Geo.options.address_fields.use == 2 || GF_Geo.options.address_fields.use == 3 ) {
				
				jQuery.each( ['street','city','state','zipcode','country'], function( index, value ) {
					
					if ( jQuery( '.ggf-field-' + value ).length ) {
						
						if ( jQuery.trim( jQuery( '.ggf-field-' + value + ' input[type="text"]' ).val() ).length )  {
							geoAddress.push( jQuery( '.ggf-field-' + value + ' input[type="text"]' ).val() );
						}
					}
				});

				geoAddress = geoAddress.join(' ');
			}
			
			// do nothing if address field empty
			if ( geoAddress == undefined || geoAddress == null || ! jQuery.trim( geoAddress ).length ) {

				GF_Geo.submit_form();
			
			// otherwise, geocode the address
			} else {

				GF_Geo.geocoder( geoAddress, GF_Geo.form_geocoder_success, GF_Geo.geocoder_failed );
			}

		} else {

			GF_Geo.submit_form();
		}
    },

    /**
     * Form geocoder success callback function
     * 
     * @param  {[type]} results [description]
     * @return {[type]}         [description]
     */
    form_geocoder_success : function( results ) {

    	// save location fields
    	address_fields = GF_Geo.save_location_fields( results );
    	
    	// submit form
    	GF_Geo.submit_form();
    }
}
jQuery(document).bind( 'gform_post_render', function(){
	//init GF_Geo
	GF_Geo.init();
});