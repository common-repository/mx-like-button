<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class MXMLBAdminMain
{

	public $plugin_name;

	/*
	* MXMLBAdminMain constructor
	*/
	public function __construct()
	{

		$this->plugin_name = MXMLB_PLUGN_BASE_NAME;

		$this->mxmlb_include();

	}

	/*
	* Include the necessary basic files for the admin panel
	*/
	public function mxmlb_include()
	{

		// require database-talk class
		require_once 'class-database-talk.php';

	}

	/*
	* Registration of styles and scripts
	*/
	public function mxmlb_register()
	{

		// register scripts and styles
		add_action( 'admin_enqueue_scripts', array( $this, 'mxmlb_enqueue' ) );

		// register admin menu
		add_action( 'admin_menu', array( $this, 'add_admin_pages' ) );

		// add link Settings under the name of the plugin
		add_filter( "plugin_action_links_$this->plugin_name", array( $this, 'settings_link' ) );

	}

		public function mxmlb_enqueue()
		{

			wp_enqueue_style( 'mxmlb_font_awesome', MXMLB_PLUGIN_URL . 'assets/font-awesome-4.6.3/css/font-awesome.min.css' );

			wp_enqueue_style( 'mxmlb_admin_style', MXMLB_PLUGIN_URL . 'includes/admin/assets/css/style.css', array( 'mxmlb_font_awesome' ), MXMLB_PLUGIN_VERSION, 'all' );

			wp_enqueue_script( 'mxmlb_admin_script', MXMLB_PLUGIN_URL . 'includes/admin/assets/js/script.js', array( 'jquery' ), MXMLB_PLUGIN_VERSION, false );

			// localize like object
			wp_localize_script( 'mxmlb_admin_script', 'mxmlb_admin_localize', array(

				'ajaxurl' 					=> admin_url( 'admin-ajax.php' ),
				'mxmlb_admin_nonce' 		=> wp_create_nonce( 'mxmlb_admin_nonce_request' )

			) );

		}

		// register admin menu
		public function add_admin_pages()
		{

			add_menu_page( __( 'Title of the page', 'mxmlb-domain' ), __( 'Mx Like Button', 'mxmlb-domain' ), 'manage_options', 'mxmlb-mx-like-button-menu', array( $this, 'admin_index' ), MXMLB_PLUGIN_URL . '/assets/img/icon.png', 111 ); // icons https://developer.wordpress.org/resource/dashicons/#id

			// add submenu
			// add_submenu_page( 'mxmlb-mx-like-button-menu', __( 'Submenu title', 'mxmlb-domain' ), __( 'Submenu item', 'mxmlb-domain' ), 'manage_options', 'mxmlb-mx-like-button-submenu', array( $this, 'page_distributor' ) );

		}

			public function admin_index()
			{

				// 	// require main menu
				mxmlb_require_template_admin( 'main_module_menu.php' );

				switch( $_GET['p'] ) {

					case 'go_to_pro_version' :
						$action = 'go_to_pro_version.php';
						break;

					case 'change_buttons' :
						$action = 'change_buttons.php';
						break;

					default :
						$action = 'index.php';
						break;

				}

				// require pages
				mxmlb_require_template_admin( $action );

			}

		// add settings link
		public function settings_link( $links )
		{

			$settings_link = '<a href="admin.php?page=mxmlb-mx-like-button-menu">Settings</a>'; // options-general.php

			array_push( $links, $settings_link );

			return $links;

		}

}

// Initialize
$initialize_class = new MXMLBAdminMain();

// Apply scripts and styles
$initialize_class->mxmlb_register();