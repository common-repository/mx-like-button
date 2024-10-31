<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class MXMLBDataBaseTalk
{

	/*
	* MXMLBDataBaseTalk constructor
	*/
	public function __construct()
	{

		$this->mxmlb_observe_uploading_image();

	}

	/*
	* Observe function
	*/
	public function mxmlb_observe_uploading_image()
	{

		// upload image
		add_action( 'wp_ajax_mxmlb_upload_img_for_like', array( $this, 'prepare_uploading_image' ) );

		// remove image
		add_action( 'wp_ajax_mxmlb_remove_image_from_database', array( $this, 'prepare_removing_image' ) );

		// update options from DB
		add_action( 'wp_ajax_mxmlb_update_post_type_from_database', array( $this, 'prepare_update_post_type' ) );

	}

	/*
	* Prepare for data updates
	*/
	public function prepare_uploading_image()
	{
		
		// Checked POST nonce is not empty
		if( empty( $_POST['nonce'] ) ) wp_die( '0' );

		// Checked or nonce match
		if( wp_verify_nonce( $_POST['nonce'], 'mxmlb_admin_nonce_request' ) ){

			// save image path
			$this->uploading_image_path( $_POST );

		}

		wp_die();

	}

		// Update data
		public function uploading_image_path( $_post_ )
		{

			global $wpdb;

			$mxmlb_table_slugs = unserialize( MXMLB_TABLE_SLUGS );

			$table_name = $wpdb->prefix . $mxmlb_table_slugs[0];

			$upload_images_serialize = mxmlb_get_like_option_by_name( '_upload_images' )->mx_like_option_value;

			// options
			$upload_images_array = maybe_unserialize( $upload_images_serialize );

			// $_POST
			// type of like
			$type_of_like = $_post_['type_of_like'];

			// $_FILE
			$_file_ = $_FILES['file'];

			// upload file
			if ( ! function_exists( 'wp_handle_upload' ) ) {

				require_once( ABSPATH . 'wp-admin/includes/file.php' );

			}
		
			$overrides = array(
				'test_form' => false,
				'unique_filename_callback' => array( $this, 'mx_change_img_name' ) 
			);

			$movefile = wp_handle_upload( $_file_, $overrides );

			if ( $movefile && empty($movefile['error']) ) {				

				$img_url = $movefile['url'];

				// cut path into format /wp-content/uploads/2018/11/1542189679.png"
				$matches = array();

				preg_match('/(\wp-content\/.*)/', $img_url, $matches);

				$cut_img_url = $matches[0];

				// update array
				foreach ( $upload_images_array as $key => $value ) {

					if( $type_of_like == $key ) {

						$upload_images_array[$key] = $cut_img_url;

					}
					
				}

				$upload_images_array = serialize( $upload_images_array );

				$wpdb->update(

					$table_name, 
					array(
						'mx_like_option_value' => $upload_images_array,
					), 
					array( 'mx_like_option_name' => '_upload_images' ), 
					array( 
						'%s'
					)

				);

				echo get_site_url() . '/' . $cut_img_url;

			} else {

				var_dump( $movefile );

			}			

		}

		// change img name
		public function mx_change_img_name( $dir, $name, $ext ) {

			$new_name = time();

			return $new_name.$ext;

		}

	/*
	* Prepare for data remove
	*/
	public function prepare_removing_image()
	{

		// Checked POST nonce is not empty
		if( empty( $_POST['nonce'] ) ) wp_die( '0' );

		// Checked or nonce match
		if( wp_verify_nonce( $_POST['nonce'], 'mxmlb_admin_nonce_request' ) ){

			// save image path
			$this->removing_image_from_dapabase( $_POST );

		}

		wp_die();

	}

		public function removing_image_from_dapabase( $_post_ )
		{

			global $wpdb;

			$mxmlb_table_slugs = unserialize( MXMLB_TABLE_SLUGS );

			$table_name = $wpdb->prefix . $mxmlb_table_slugs[0];

			$get_images_serialize = mxmlb_get_like_option_by_name( '_upload_images' )->mx_like_option_value;

			// options
			$images_array = maybe_unserialize( $get_images_serialize );

			// type of like
			$type_of_like = $_post_['type_of_like'];

			// find and remove
			foreach ( $images_array as $key => $value ) {

				if( $type_of_like == $key ) {

					$images_array[$key] = '';

				}
				
			}

			$upload_images_array = serialize( $images_array );

			$wpdb->update(

				$table_name, 
				array(
					'mx_like_option_value' => $upload_images_array,
				), 
				array( 'mx_like_option_name' => '_upload_images' ), 
				array( 
					'%s'
				)

			);

			echo MXMLB_PLUGIN_URL . 'includes/frontend/assets/img/' . $type_of_like . '.png';

		}

	/*
	* Prepare ability "MX LIKE BUTTON" to post types
	*/
	public function prepare_update_post_type()
	{

		// Checked POST nonce is not empty
		if( empty( $_POST['nonce'] ) ) wp_die( '0' );

		// Checked or nonce match
		if( wp_verify_nonce( $_POST['nonce'], 'mxmlb_admin_nonce_request' ) ){

			// save image path
			$this->update_post_type_ability( $_POST );

		}

		wp_die();

	}

		public function update_post_type_ability( $_post_ )
		{

			// data options
			$row_options = mxmlb_get_like_option_by_name( '_turn_of_for_post_types' );

			$array_of_post_types_optyons = maybe_unserialize( $row_options->mx_like_option_value );

			// current post type
			$current_post_type = $_post_['post_type'];

			if( in_array( $current_post_type, $array_of_post_types_optyons ) ) {

				$index = array_search( $current_post_type, $array_of_post_types_optyons );

			    if( $index !== false ) {

			        unset( $array_of_post_types_optyons[$index] );

			    }

			    $option_value = serialize( $array_of_post_types_optyons );
				
				mxmlb_update_like_options( '_turn_of_for_post_types', $option_value );

			} else {

				array_push( $array_of_post_types_optyons, $current_post_type );

				$option_value = serialize( $array_of_post_types_optyons );
				
				mxmlb_update_like_options( '_turn_of_for_post_types', $option_value );

			}		

		}

}

// New instance
new MXMLBDataBaseTalk();