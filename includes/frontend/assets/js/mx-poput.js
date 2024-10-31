;( function() {

	var popup_app = {};

	jQuery( document ).ready( function( $ ){

		/*
		* popup window
		... */

		$( document ).on( 'mouseenter', '.mx-like-place-faces span', function( e ) {

			$( this ).removeAttr( 'title' );

			var likeType = $( this ).text();			

			// find post id, post type
			$( this ).parents().map( function() {

				var element = this;

				if( $( element ).hasClass( 'mx-like-box' ) ) {

					var _postType = $( element ).attr( 'data-post-type' );
					
					var _postId = $( element ).attr( 'id' ).match(/\d+/)[0];

					// check obj
					$.each( mxmlb_localize.mxmlb_object_likes[_postType][_postId], function( index, value ) {

						// add user name
						if( !value.hasOwnProperty( 'userName' ) ) {

							mxmlb_set_user_name_to_obj( $, index, _postType, _postId );

						}

					} );

					// show popup
					mxmlb_wait_for_object( $, mxmlb_localize.mxmlb_object_likes[_postType][_postId], function() {

						var showData = {
							'postType': _postType,
							'postId': _postId,
							'likeType': likeType

						};

						mxmlb_create_popup_window_first( $, e.pageX, e.pageY, showData );

					} );

					return;

				}
				
			} );			

		} );

		// hide like buttons window
		$( document ).on( 'mouseleave', '.mx-users-window', function() {

			clearTimeout( popup_app.timer );

			$( this ).remove();

		} );

		$( document ).on( 'mouseleave', '.mx-like-place-faces span', function() {

			clearTimeout( popup_app.timer );

		} );

		/*...
		* popup window
		*/

	} );

	/*
	* create popup window #1
	*/
	function mxmlb_create_popup_window_first( $, posX, posY, showData ) {

		if( !$( '.mx-users-window' ).length ) {

			var _style = 'style="left:'+(posX-10)+'px;top:'+(posY-10)+'px"'; 

			$( 'body' ).append( '<div class="mx-users-window" ' + _style + '></div>' );		

			$.each( mxmlb_localize.mxmlb_object_likes[showData.postType][showData.postId], function( index, value ) {

				if( value.typeOfLike === showData.likeType ) {

					$( '.mx-users-window' ).append( '<a href="#" onclick="return false;">' + value.userName + '</a>' );
					
				}

			} );

		}

	}

	/*
	*	Ser user ids
	*/
	function mxmlb_set_user_name_to_obj( $, userId, postType, postId ) {

		var data = {
			'action': 'mxmlb_get_user_data',
			'nonce': mxmlb_localize.mxmlb_nonce,
			'userId': userId
		};

		// ajax - create user's data
		jQuery.post( mxmlb_localize.ajaxurl, data, function( response ) {

			mxmlb_localize.mxmlb_object_likes[postType][postId][userId]['userName'] = response;

		} );

	}

	/*
	* Wait for obj
	*/
	function mxmlb_wait_for_object( $, obj, callback ) {

		var obj_ready = true;

		$.each( obj, function( index, value ) {

			if( value.userName === undefined ) {

				obj_ready = false;

				return;

			}

		} );

		if ( obj_ready ) {

			callback();

		} else {

			setTimeout(function() {

				mxmlb_wait_for_object( $, obj, callback );

			}, 100);

		}

	};

} )();