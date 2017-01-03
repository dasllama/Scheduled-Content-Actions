<?php
/**
 * Plugin Name: Scheduled Content Actions
 * Plugin URI:  https://github.com/dasllama/Scheduled-Content-Actions
 * Description: This plugin provides several actions which affects the behaviour of a post entry. It also handles custom post types and products for woocommerce.
 * Version:     1.2
 * Author:      Thomas 'Llama' Herzog
 * Author URI:  https://profiles.wordpress.org/dasllama
 * License:     GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0
 * Text Domain: scheduled-content-actions
 * Domain Path: /languages
 */

// first of all, check if we are in the WordPress environment
defined( 'ABSPATH' ) or die();

// define some constants we need to get the module system working
define( 'SCA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SCA_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'SCA_BASEFILE', plugin_basename( __FILE__ ) );
define( 'SCA_APPLICATION_DIR', dirname( __FILE__ ) . '/application' );

/**
 * Basic setup of this plugin. Register the textdomain,
 * basic filters and the module system
 *
 * @wp-hook	plugins_loaded
 * @return	void
 */
function sca_init() {

	// get the plugin header so we can work with these parameters
	// instead of hardcode them
	if ( ! function_exists( 'get_plugin_data' ) )
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	$aPluginHeader = get_plugin_data( __FILE__ );

	// before anything else we need to register the textdomain
	// as long with the correct folder for the files based upon
	// the data given in the plugin header
	load_plugin_textdomain( $aPluginHeader[ 'TextDomain' ], false, dirname( __FILE__ ) . $aPluginHeader[ 'DomainPath' ] );

	// set the application directory so that we can require all
	// the files we need with less code
	$sApplicationDirectory = dirname( __FILE__ ) . '/application/';

	// we also collect the helper file to get all the stuff we need
	// to work with
	require_once( $sApplicationDirectory . 'helper.php' );

	// load the actions
	require_once( $sApplicationDirectory . 'actions.php' );
	add_action( 'sca_do_open_comments', 'sca_open_comments' );
	add_action( 'sca_do_close_comments', 'sca_close_comments' );
	add_action( 'sca_do_open_pings', 'sca_open_pings' );
	add_action( 'sca_do_close_pings', 'sca_close_pings' );
	add_action( 'sca_do_add_meta', 'sca_add_meta' );
	add_action( 'sca_do_update_meta', 'sca_update_meta' );
	add_action( 'sca_do_delete_meta', 'sca_delete_meta' );
	add_action( 'sca_do_draft_content', 'sca_draft_content' );
	add_action( 'sca_do_private_content', 'sca_private_content' );
	add_action( 'sca_do_trash_content', 'sca_trash_content' );
	add_action( 'sca_do_delete_content', 'sca_delete_content' );
	add_action( 'sca_do_stick_content', 'sca_stick_content' );
	add_action( 'sca_do_unstick_content', 'sca_unstick_content' );
	add_action( 'sca_do_add_term', 'sca_add_term' );
	add_action( 'sca_do_delete_term', 'sca_delete_term' );
	add_action( 'sca_do_change_title', 'sca_change_title' );
	add_action( 'sca_do_update_post_date', 'sca_update_post_date' );

	// load the scheduler
	require_once( $sApplicationDirectory . 'scheduler.php' );
	add_action( 'wp_loaded', 'sca_scheduler' );

	// now we load everything we need to do in the backend of WordPress
	if ( is_admin() ) {
		// add the action helper
		require_once( $sApplicationDirectory . 'backend/action-helper.php' );
		add_action( 'wp_ajax_sca_add_action', 'sca_ajax_add_action' );
		add_action( 'wp_ajax_sca_delete_action', 'sca_ajax_delete_action' );
		add_action( 'wp_ajax_sca_load_additional_form_data', 'sca_load_additional_form_data' );

		// load the scripts
		require_once( $sApplicationDirectory . 'backend/scripts.php' );
		add_action( 'admin_enqueue_scripts', 'sca_admin_enqueue_scripts' );

		// load the styles
		require_once( $sApplicationDirectory . 'backend/styles.php' );
		add_action( 'admin_enqueue_scripts', 'sca_admin_enqueue_styles' );

		// adds the metabox
		require_once( $sApplicationDirectory . 'backend/meta-box.php' );
		add_action( 'add_meta_boxes', 'sca_add_metabox' );

		// list view helper
		require_once( $sApplicationDirectory . 'backend/list-view-helper.php' );
		add_action( 'pre_get_posts', 'sca_add_filter_to_posts_query' );
		add_action( 'init', 'sca_register_view_filters');

		// adds the admin columns
		require_once( $sApplicationDirectory . 'backend/admin-columns.php' );
		add_filter( 'manage_posts_columns', 'sca_columns_head' );
		add_filter( 'manage_page_posts_columns', 'sca_columns_head', 10 );
		add_action( 'manage_page_posts_custom_column', 'sca_columns_content', 10, 2 );
		add_action( 'manage_posts_custom_column', 'sca_columns_content', 10, 2 );

		// adds the dashboard widget
		require_once( $sApplicationDirectory . 'backend/dashboard-widget.php' );
		add_action( 'wp_dashboard_setup', 'sca_add_dashboard_widgets' );
	}

} add_action( 'plugins_loaded', 'sca_init' );