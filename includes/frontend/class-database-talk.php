<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class MXMLBDataBaseTalkFrontend
{

	/*
	* MXMLBDataBaseTalkFrontend constructor
	*/
	public function __construct()
	{

		$this->mxmlb_observe_create_like_obj();

	}

	/*
	* Observe function
	*/
	public function mxmlb_observe_create_like_obj()
	{

		// motion like object
		add_action( 'wp_ajax_mxmlb_mounting_like_obj', array( $this, 'prepare_mounting_like_obj' ) );

		// delete like object
		add_action( 'wp_ajax_mxmlb_delete_like_obj', array( $this, 'prepare_delete_like_obj' ) );

		// get user data
		add_action( 'wp_ajax_mxmlb_get_user_data', array( $this, 'prepare_get_user_data' ) );
		
	}

	/*
	* Prepare get user data
	*/
	public function prepare_get_user_data()
	{

		// Checked POST nonce is not empty
		if( empty( $_POST['nonce'] ) ) wp_die( '0' );

		// Checked or nonce match
		if( wp_verify_nonce( $_POST['nonce'], 'mxmlb_nonce_request' ) ) {

			$this->_get_user_name( $_POST );

		}

		wp_die();

	}

		public function _get_user_name( $_post )
		{

			$user_meta = get_user_meta( $_post['userId'] );

			$user_name = $user_meta['first_name'][0] . ' ' . $user_meta['last_name'][0];

			if( $user_name == ' ' ) {

				$user_name = $user_meta['nickname'][0];

			}

			echo $user_name;

		} 

	/*
	* Prepare for data updates
	*/
	public function prepare_mounting_like_obj()
	{
		
		// Checked POST nonce is not empty
		if( empty( $_POST['nonce'] ) ) wp_die( '0' );

		// Checked or nonce match
		if( wp_verify_nonce( $_POST['nonce'], 'mxmlb_nonce_request' ) ) {

			// db query
			global $wpdb;

			$mxmlb_table_slugs = unserialize( MXMLB_TABLE_SLUGS );

			$table_name = $wpdb->prefix . $mxmlb_table_slugs[1];

			$post_id = intval( $_POST['mxmlb_object_likes']['post_id'] );

			$post_type = $_POST['mxmlb_object_likes']['postType'];

			// if post id is exists
			$post_count = count( mxmlb_select_data_likes_by_post_id( $post_id, $post_type ) );			

			if( $post_count == 0 ) {

				// Create data
				$this->crate_like_obj( $_POST['mxmlb_object_likes'] );

			} else {

				// Update data
				$this->update_like_obj( $_POST['mxmlb_object_likes'], $post_type, $post_id );	

			}

		}

		wp_die();

	}

		// create data
		public function crate_like_obj( $object_likes )
		{
			
			// db query
			global $wpdb;

			$mxmlb_table_slugs = unserialize( MXMLB_TABLE_SLUGS );

			$table_name = $wpdb->prefix . $mxmlb_table_slugs[1];

			// check user choise
			$user_ids = array(
				$object_likes['user_ids']['id'] => array( 'typeOfLike' => $object_likes['user_ids']['typeOfLike'] )
			);			

			$user_ids = serialize( $user_ids );

			// post type
			$post_type = $object_likes['postType'];

			// insert
			$wpdb->insert(

				$table_name, 
				array(
					'post_id' 	=> $object_likes['post_id'],
					'user_ids' 	=> $user_ids,
					'post_type' => $post_type
				),
				array(
					'%d',
					'%s',
					'%s'
				)

			);

		}

		// update data
		public function update_like_obj( $object_likes, $post_type, $post_id )
		{

			// db query
			global $wpdb;

			$mxmlb_table_slugs = unserialize( MXMLB_TABLE_SLUGS );

			$table_name = $wpdb->prefix . $mxmlb_table_slugs[1];

			// select data
			$user_ids_row = $wpdb->get_row(
				$wpdb->prepare(
					"SELECT id, user_ids, post_type
					FROM $table_name
					WHERE post_id = %d
					AND post_type = %s",
					$post_id, $post_type
				)
			);

			// row id
			$row_id = $user_ids_row->id;

			// get current user
			$get_current_user_id = intval( $object_likes['user_ids']['id'] );

			// get user_ids from database
			$array_of_user_ids = maybe_unserialize( $user_ids_row->user_ids );

			// key_user_not_exists is using to check if current user are liked this post
			$key_user_not_exists = false;

			// find current user in existing array
			foreach ( $array_of_user_ids as $key => $value) {

				if( $key == $get_current_user_id ) {

					// function of update user choise
					$array_of_user_ids[$key]['typeOfLike'] = $object_likes['user_ids']['typeOfLike'];

					$key_user_not_exists = true;

				}
				
			}

			// add new like to the database
			if( $key_user_not_exists == false ) {				

				$array_of_user_ids[$get_current_user_id] = array(

					'typeOfLike' => $object_likes['user_ids']['typeOfLike']

				);

			}

			// serialize data
			$user_ids = serialize( $array_of_user_ids );

			// post type
			$post_type = $object_likes['postType'];

			// update data
			$wpdb->update( 
				$table_name, 
				array( 
					'user_ids' 	=> $user_ids
				), 
				array( 'id' => $row_id ),
				array( 
					'%s'
				) 
			);

		}

	/*
	* Prepare for data delete
	*/
	public function prepare_delete_like_obj()
	{

		// Checked POST nonce is not empty
		if( empty( $_POST['nonce'] ) ) wp_die( '0' );

		// Checked or nonce match
		if( wp_verify_nonce( $_POST['nonce'], 'mxmlb_nonce_request' ) ) {			
			
			$this->delete_like_obj( $_POST );

		}		

	}

		// delete data
		public function delete_like_obj( $_post_ )
		{

			// post id
			$post_id = $_post_['mxmlb_object_likes']['post_id'];

			// post type
			$post_type = $_POST['mxmlb_object_likes']['postType'];

			// user id
			$user_id = intval( $_post_['mxmlb_object_likes']['user_ids']['id'] );

			// db query
			global $wpdb;

			$mxmlb_table_slugs = unserialize( MXMLB_TABLE_SLUGS );

			$table_name = $wpdb->prefix . $mxmlb_table_slugs[1];

			// select data
			$user_ids_row = $wpdb->get_row(
				$wpdb->prepare(
					"SELECT id, user_ids, post_type
					FROM $table_name
					WHERE post_id = %d
					AND post_type = %s",
					$post_id, $post_type
				)
			);

			var_dump($user_ids_row);

			// row id
			$row_id = $user_ids_row->id;

			// get current user
			$get_current_user_id = intval( $_post_['mxmlb_object_likes']['user_ids']['id'] );			

			// get user_ids from database
			$array_of_user_ids = maybe_unserialize( $user_ids_row->user_ids );
			
			// check current user
			if( $user_id == $get_current_user_id ) {

				// delete like object by user id
				unset( $array_of_user_ids[$user_id] );				

				// serialize data
				$user_ids = serialize( $array_of_user_ids );

				if( count( $array_of_user_ids ) == 0 ) {

					// delete row from database
					$wpdb->delete( $table_name,
						array( 'id' => $row_id )
					);

				} else {

					// update data
					$wpdb->update( 
						$table_name, 
						array( 
							'user_ids' 	=> $user_ids
						), 
						array( 'id' => $row_id ),
						array( 
							'%s'
						) 
					);

				}				

			}

		}

}

// New instance
new MXMLBDataBaseTalkFrontend();