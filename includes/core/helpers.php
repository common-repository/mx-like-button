<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/*
* Require template for admin panel
*/
function mxmlb_require_template_admin( $file ) {

	require_once MXMLB_PLUGIN_ABS_PATH . 'includes/admin/templates/' . $file;

}

/*
* Require template for frontend part
*/
function mxmlb_include_template_frontend( $file ) {

	include MXMLB_PLUGIN_ABS_PATH . 'includes/frontend/templates/' . $file;

}

/*
* Select data likes
*/
function mxmlb_select_data_likes() {

	global $wpdb;

	$mxmlb_table_slugs = unserialize( MXMLB_TABLE_SLUGS );

	$table_name = $wpdb->prefix . $mxmlb_table_slugs[1];

	$get_data_likes = $wpdb->get_results( "SELECT id, post_id, user_ids, post_type FROM $table_name ORDER BY id ASC" );

	return $get_data_likes;

}

/*
* Select data likes by post id
*/
function mxmlb_select_data_likes_by_post_id( $post_id, $post_type ) {

	global $wpdb;

	$mxmlb_table_slugs = unserialize( MXMLB_TABLE_SLUGS );

	$table_name = $wpdb->prefix . $mxmlb_table_slugs[1];

	$get_data_likes = $wpdb->get_var( 
		$wpdb->prepare(
			"SELECT user_ids
			FROM $table_name
			WHERE post_id = %d
			AND post_type = %s",
			$post_id, $post_type
		)
	);

	return $get_data_likes;

}

/*
* Select data like options
*/
function mxmlb_get_like_option_by_name( $option_name ) {

	global $wpdb;

	$mxmlb_table_slugs = unserialize( MXMLB_TABLE_SLUGS );

	$table_name = $wpdb->prefix . $mxmlb_table_slugs[0];

	$option_value = $wpdb->get_row( "SELECT mx_like_option_value FROM $table_name WHERE mx_like_option_name = '$option_name'" );

	// if option not exists, create it
	if( $option_value == NULL ) {

		$default_data = serialize( array() );
		// insert
		$wpdb->insert(

			$table_name, 
			array(
				'mx_like_option_name' 	=> $option_name,
				'mx_like_option_value' 	=> $default_data
			),
			array(
				'%s',
				'%s'
			)

		);

		$option_value = $wpdb->get_row( "SELECT mx_like_option_value FROM $table_name WHERE mx_like_option_name = '$option_name'" );
	}
	

	return $option_value;

}

/*
* Update data like options
*/
function mxmlb_update_like_options( $option_name, $option_value ) {

	global $wpdb;

	$mxmlb_table_slugs = unserialize( MXMLB_TABLE_SLUGS );

	$table_name = $wpdb->prefix . $mxmlb_table_slugs[0];

	// update data
	$wpdb->update( 
		$table_name, 
		array( 
			'mx_like_option_value' 	=> $option_value
		), 
		array( 'mx_like_option_name' => $option_name ),
		array( 
			'%s'
		) 
	);

}

/*
* Display mx_like_button for posts
*/
function mxmlb_display_mx_like_button_template() {

	// like options
	$row_options = mxmlb_get_like_option_by_name( '_turn_of_for_post_types' );

	$array_of_post_types_optyons = maybe_unserialize( $row_options->mx_like_option_value );

	$post_type = get_post_type( get_the_ID() );

	if( !in_array( $post_type, $array_of_post_types_optyons ) ) {

		$html = '<div class="mx-like-box" id="mx-like-button-' . get_the_ID() . '"  data-post-type="' . $post_type . '">';

		$html .= '<div class="mx-like-place">';

			$html .= '<div class="mx-like-place-faces">';

				$html .= '<span class="mx-like" title="0">like</span>';
				$html .= '<span class="mx-heart" title="0">heart</span>';
				$html .= '<span class="mx-laughter" title="0">laughter</span>';
				$html .= '<span class="mx-wow" title="0">wow</span>';
				$html .= '<span class="mx-sad" title="0">sad</span>';
				$html .= '<span class="mx-angry" title="0">angry</span>';
			
			$html .= '</div>';
			
			$html .= '<div class="mx-like-place-count mx-display-none">0</div>';

		$html .= '</div>';

		if( is_user_logged_in() ) {

			$html .= '<button class="mx-like-main-button" data-like-type="like">';
				$html .= '<span>like</span>';
			$html .= '</button>';

			$html .= '<div class="mx-like-other-faces">';
				$html .= '<button class="mx-like-face-like" data-like-type="like">';
					$html .= '<span>like</span>';
				$html .= '</button>';
				$html .= '<button class="mx-like-face-heart" data-like-type="heart">';
					$html .= '<span>heart</span>';
				$html .= '</button>';
				$html .= '<button class="mx-like-face-laughter" data-like-type="laughter">';		
					$html .= '<span>laughter</span>';
				$html .= '</button>';
				$html .= '<button class="mx-like-face-wow" data-like-type="wow">';
					$html .= '<span>wow</span>';
				$html .= '</button>';
				$html .= '<button class="mx-like-face-sad" data-like-type="sad">';
					$html .= '<span>sad</span>';
				$html .= '</button>';
				$html .= '<button class="mx-like-face-angry" data-like-type="angry">';
					$html .= '<span>angry</span>';
				$html .= '</button>';
			$html .= '</div>';

		}		

		$html .= '</div>';

		return $html;

	} else {

		return '';

	}

}

/*
* check time for PRO version
*/
function mxmlb_pro_version_available() {

	$_hour = 3600;

	$_day = 3600 * 24;

	$five_days = $_day * 5;

	$time_instal = get_option( 'mx_like_button_pro' );

	if( mxmlb_pro_version_active() ) {

		return true;

	}

	$current_time = time();

	$time_difference = intval( $current_time ) - intval( $time_instal );

	if( $time_difference > $five_days ) {

		return false;

	}

	return true;

}

/*
* PRO version is active
*/
function mxmlb_pro_version_active() {

	$time_instal = get_option( 'mx_like_button_pro' );

	if( $time_instal == 'active' ) {

		return true;

	}

	return false;

}

/*
* check time for PRO version
*/
function mxmlb_pro_version_count() {

	$_hour = 3600;

	$_day = 3600 * 24;

	$five_days = $_day * 5;

	$time_instal = get_option( 'mx_like_button_pro' );

	$current_time = time();

	$time_difference = ( intval( $current_time ) / $_day ) - ( intval( $time_instal ) / $_day );

	$day_difference = 5 - floor( $time_difference );

	return $day_difference;

}

/*
* turn off on home page
*/
function mxmlb_turn_off_home_page() {

	$row_options = mxmlb_get_like_option_by_name( '_turn_of_for_post_types' );

	$array_of_post_types_options = maybe_unserialize( $row_options->mx_like_option_value );

	$home_page_key = 'mx-like-enable_homepage';		

	if( !in_array( $home_page_key, $array_of_post_types_options ) ) {

		if( is_home() || is_front_page() ) {
		
			return false;

		}

	}

	return true;
}