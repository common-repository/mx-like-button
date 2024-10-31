jQuery( document ).ready( function( $ ){

	$( '.mxmlb_form_upload_like_img' ).on( 'submit', function( e ){

		e.preventDefault();		

		if( $( this ).find( '.lb_upload_img' ).val() !== '' ) {

			var files = $( this ).find( '.lb_upload_img' ).prop('files')[0]['name'];

			var type_of_like = $( this ).find( '.lb_upload_img' ).attr( 'data-type-like' );


			var data = new FormData();

			data.append( 'action', 'mxmlb_upload_img_for_like' );

			data.append( 'nonce', mxmlb_admin_localize.mxmlb_admin_nonce );

			data.append( 'mxmlb_upload_img', files );

			data.append( 'type_of_like', type_of_like );

			data.append( 'file', $( this ).find( '.lb_upload_img' ).prop('files')[0] );

			mxmlb_upload_new_image( data, $( this ) );

		}	

	} );

	// remove image that was uploaded
	$( '.mx-btn-remove' ).on( 'click', function() {

		var type_of_like = $( this ).attr( 'data-type-like' );

		// data
		var data = {

			'action'		: 'mxmlb_remove_image_from_database',
			'nonce'			: mxmlb_admin_localize.mxmlb_admin_nonce,
			'type_of_like' 	: type_of_like

		};

		mxmlb_remove_image( data, $( this ) );

	} );

	/*
	* Post types area
	*/
	$( '#mxmlb_post_type_form' ).on( 'change', '.mx_post_type_checkbox', function() {

		$( this ).parent().toggleClass( 'mxmlb_post_type_turn_of' );

		var postType = $( this ).attr( 'data-post-type' );

		var data = {
			'action'		: 'mxmlb_update_post_type_from_database',
			'nonce'			: mxmlb_admin_localize.mxmlb_admin_nonce,
			'post_type' 	: postType
		};

		mxmlb_update_post_type_options( data );

		console.log( postType );

	} );

} );

// upload new image
function mxmlb_upload_new_image( data, form ) {

	jQuery.ajax( {

        type: 'POST',
        url: mxmlb_admin_localize.ajaxurl,
        data: data,
        contentType: false,
        processData: false,
        success: function( response ){

            if( typeof response === 'string' ) {

            	mxmlb_success_uploading_img( form, response );

            }

        }

    } );

}

// success uploading of img
function mxmlb_success_uploading_img( form, response ) {

	form.find( '.lb_upload_img' ).val( '' );

	form.parent().find( '.mx-like-preview' ).attr( 'src', response );

	form.parent().find( '.mx-btn-remove' ).removeAttr( 'style' );

}

// remove image
function mxmlb_remove_image( data, form ) {

	jQuery.post( mxmlb_admin_localize.ajaxurl, data, function( response ) {

		if( typeof response === 'string' ) {

			mxmlb_success_removing_img( form, response );

		}

	} );

}

// success removing img
function mxmlb_success_removing_img( form, default_image ) {

	form.parent().find( '.mx-like-preview' ).attr( 'src', default_image );

	form.hide();

}

// update post type options
function mxmlb_update_post_type_options( data ) {

	jQuery.post( mxmlb_admin_localize.ajaxurl, data, function( response ) {

		// console.log( response );

	} );

}
