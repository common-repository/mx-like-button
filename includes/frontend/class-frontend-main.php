<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class MXMLBFrontEndMain
{

	public $plugin_name;

	/*
	* MXMLBAdminMain constructor
	*/
	public function __construct()
	{

		$this->plugin_name = MXMLB_PLUGN_BASE_NAME;

		$this->mxmlb_include();

		// test
		add_action( 'wp_footer', array( $this, 'mxmlb_get_data_of_likes' ) );

	}

	/*
	* Include the necessary basic files for the frontend panel
	*/
	public function mxmlb_include()
	{

		// require database-talk class
		require_once MXMLB_PLUGIN_ABS_PATH . 'includes/frontend/class-database-talk.php';

	}

	/*
	* Registration of styles and scripts
	*/
	public function mxmlb_register()
	{

		// enqueue
		add_action( 'wp_enqueue_scripts', array( $this, 'mxmlb_enqueue' ) );

		// add custom icons to the buttons
		add_action( 'wp_head', array( $this, 'mxmlb_change_like_button_images' ) );

	}

		// wp_enqueue
		public function mxmlb_enqueue()
		{

			wp_enqueue_style( 'mxmlb_font_awesome', MXMLB_PLUGIN_URL . 'assets/font-awesome-4.6.3/css/font-awesome.min.css' );

			wp_enqueue_style( 'mxmlb_style', MXMLB_PLUGIN_URL . 'includes/frontend/assets/css/style.css', array( 'mxmlb_font_awesome' ), MXMLB_PLUGIN_VERSION, 'all' );

			wp_enqueue_script( 'mxmlb_script', MXMLB_PLUGIN_URL . 'includes/frontend/assets/js/script.js', array( 'jquery' ), MXMLB_PLUGIN_VERSION, false );

			if( mxmlb_pro_version_available() ) {

				// like options
				$row_options = mxmlb_get_like_option_by_name( '_turn_of_for_post_types' );

				$array_of_post_types_options = maybe_unserialize( $row_options->mx_like_option_value );

				$post_type = 'mx-like-popup';

				if( !in_array( $post_type, $array_of_post_types_options ) ) {

					wp_enqueue_script( 'mxmlb_popup_script', MXMLB_PLUGIN_URL . 'includes/frontend/assets/js/mx-poput.js', array( 'mxmlb_script' ), MXMLB_PLUGIN_VERSION, false );

				}

			}			

			// localize like object
			wp_localize_script( 'mxmlb_script', 'mxmlb_localize', array(

				'mxmlb_object_likes' 		=> $this->mxmlb_get_data_of_likes(),
				'mxmlb_current_user_data' 	=> array( 'id' => get_current_user_id() ),
				'ajaxurl' 					=> admin_url( 'admin-ajax.php' ),
				'mxmlb_nonce' 				=> wp_create_nonce( 'mxmlb_nonce_request' )

			) );

		}

	/**********************************************
	* Hooks. Creation like button into activity stream
	*/
	public function mxmlb_show_like_button_hooks()
	{

		add_action( 'bp_activity_entry_meta', array( $this, 'mxmlb_show_like_button_activity' ) );

		// activity reply
		add_action( 'bp_activity_comment_options', array( $this, 'mxmlb_show_like_button_activity_reply' ) );

	}

		// like buttons reply
		public function mxmlb_show_like_button_activity_reply()
		{

			if( mxmlb_pro_version_available() ) {

				// like options
				$row_options = mxmlb_get_like_option_by_name( '_turn_of_for_post_types' );

				$array_of_post_types_options = maybe_unserialize( $row_options->mx_like_option_value );

				$post_type = 'bp-comments';

				if( !in_array( $post_type, $array_of_post_types_options ) ) {

					mxmlb_include_template_frontend( 'mx-like-box-reply.php' );

				}

			}			

		}

		// like button activities
		public function mxmlb_show_like_button_activity()
		{

			// like options
			$row_options = mxmlb_get_like_option_by_name( '_turn_of_for_post_types' );

			$array_of_post_types_options = maybe_unserialize( $row_options->mx_like_option_value );

			$post_type = 'bp';

			if( !in_array( $post_type, $array_of_post_types_options ) ) {

				mxmlb_include_template_frontend( 'mx-like-box.php' );

			}

		}

	/*
	* Get data of likes
	*/
	public function mxmlb_get_data_of_likes()
	{

		$array_likes_data = 0;		

		if( count( mxmlb_select_data_likes() ) >= 1 ) {
			
			// main array
			$array_likes_data = array();

			// set post types
			$array_likes_data = $this->set_existing_post_types( mxmlb_select_data_likes() );

			// each array
			foreach ( mxmlb_select_data_likes() as $key => $value ) {

				$array_likes_data[$value->post_type][$value->post_id] = array();

				// 
				// $array_likes_data[$value->post_id] = array();

				// users array
				$array_likes_data[$value->post_type][$value->post_id] = array();

				// user ids data
				$user_ids = maybe_unserialize( $value->user_ids );	

				// push user to array
				foreach ( $user_ids as $_key => $_value ) {
					
					$new_user_like = array(

						$_key => array(

							'typeOfLike' 	=> $_value['typeOfLike']

						)

					);

					$array_likes_data[$value->post_type][$value->post_id] = $array_likes_data[$value->post_type][$value->post_id] + $new_user_like;

				}
			
			}			

		}

		// var_dump( $array_likes_data );

		return $array_likes_data;
	}

	// set post types
	public function set_existing_post_types( $arr )
	{

		$array_of_post_types = array();

		foreach ( $arr as $key => $value ) {			

			$array_of_post_types[$value->post_type] = array();

		}

		return $array_of_post_types;

	}

	// change image of buttons
	public function mxmlb_change_like_button_images()
	{

		$upload_images_serialize = mxmlb_get_like_option_by_name( '_upload_images' )->mx_like_option_value;

		$images_array = maybe_unserialize( $upload_images_serialize ); ?>

		<style>

		<?php foreach ( $images_array as $key => $value ) : ?>

			<?php if( $value !== '' ) : ?>

				<?php if( $key == 'like' ) : ?>

					.mx-like-box button.mx-like-main-button span {
						background-image: url(<?php echo get_site_url() . '/' . $value; ?>);
					}

				<?php endif; ?>
				
				span.mx-<?php echo $key; ?>,
				.mx-like-face-<?php echo $key; ?> span {
				    background-image: url(<?php echo get_site_url() . '/' . $value; ?>);
				}

			<?php endif; ?>			
			
		<?php endforeach; ?>

		</style>

	<?php }

	/**********************************************
	* Hooks. Creation like button into post
	*/
	public function mxmlb_show_like_button_hooks_into_post()
	{

		add_filter( 'the_content', array( $this, 'mxmlb_show_like_button_into_post' ) );

	}

		public function mxmlb_show_like_button_into_post( $content )
		{

			
			if( mxmlb_turn_off_home_page() ) {

				$content .= mxmlb_display_mx_like_button_template();

			}

			return $content;

		}
		

}

// Initialize
$initialize_class = new MXMLBFrontEndMain();

// Apply scripts and styles
$initialize_class->mxmlb_register();

// show like button into activity
$initialize_class->mxmlb_show_like_button_hooks();

// show like button into post
$initialize_class->mxmlb_show_like_button_hooks_into_post();