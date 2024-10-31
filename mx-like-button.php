<?php
/*
Plugin Name: MX Like Button
Plugin URI: https://github.com/Maxim-us/mx-like-button
Description: MX Like Button" will add a mechanism of "like buttons" similar to Facebook to your website.
Author: Marko Maksym
Version: 1.4.2
Author URI: https://markomaksym.com.ua/
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/*
* Unique string - MXMLB
*/

/*
* Define MXMLB_PLUGIN_PATH
*/
if ( ! defined( 'MXMLB_PLUGIN_PATH' ) ) {

	define( 'MXMLB_PLUGIN_PATH', __FILE__ );

}

/*
* Define MXMLB_PLUGIN_URL
*/
if ( ! defined( 'MXMLB_PLUGIN_URL' ) ) {

	// Return http://my-domain.com/wp-content/plugins/mx-like-button/
	define( 'MXMLB_PLUGIN_URL', plugins_url( '/', __FILE__ ) );

}

/*
* Define MXMLB_PLUGN_BASE_NAME
*/
if ( ! defined( 'MXMLB_PLUGN_BASE_NAME' ) ) {

	// Return mx-like-button/mx-like-button.php
	define( 'MXMLB_PLUGN_BASE_NAME', plugin_basename( __FILE__ ) );

}

/*
* Include the main MXMLBMXLikeButton class
*/
if ( ! class_exists( 'MXMLBMXLikeButton' ) ) {

	require_once plugin_dir_path( __FILE__ ) . 'includes/class-final-main-class.php';

	// Create new instance
	$mxmlbmxlikebutton = new MXMLBMXLikeButton();

	// activation|deactivation class include
	$mxmlbmxlikebutton->mxmlb_basic_pugin_function();

	/*
	* Registration hooks
	*/
	// Activation
	register_activation_hook( __FILE__, array( 'MXMLBBasisPluginClass', 'activate' ) );

	// Deactivation
	register_deactivation_hook( __FILE__, array( 'MXMLBBasisPluginClass', 'deactivate' ) );

	/*
	* Translate plugin
	*/
	add_action( 'plugins_loaded', 'mxmlb_translate' );

	function mxmlb_translate()
	{

		load_plugin_textdomain( 'mxmlb-domain', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	}

	/*
	* PRO
	*/
	if( !get_option( 'mx_like_button_pro' ) || get_option( 'mx_like_button_pro' ) !== 'active' ) {

		update_option( 'mx_like_button_pro', 'active' );

	}
	

}