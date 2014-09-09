<?php
/**
 * Plugin Name:	Scheduled Content Actions
 * Description:	This plugin provides several actions which affects the behaviour of a post entry. It also handles custom post types and products for woocommerce/jjigoshop.
 * Version:		1.1
 * Author:		HerrLlama for wpcoding.de
 * Author URI:	http://wpcoding.de
 * Licence:		GPLv3
 */

// check wp
if ( ! function_exists( 'add_action' ) )
	return;

/**
 * Initializes the plugin, loads all the files,
 * registers all the filters and actions
 *
 * @wp-hook	plugins_loaded
 * @return	void
 */
function sca_init() {

	// everything below is just in the admin panel
	if ( ! is_admin() )
		return;

	// language
	require_once dirname( __FILE__ ) . '/inc/localization.php';

	// register all actions, needed in the scheduler
	require_once dirname( __FILE__ ) . '/inc/actions.php';
	add_action( 'wp_ajax_sca_add_action', 'sca_ajax_add_action' );
	add_action( 'wp_ajax_sca_delete_action', 'sca_ajax_delete_action' );
	add_action( 'wp_ajax_sca_load_additional_form_data', 'sca_load_additional_form_data' );

	// sticky actions
	require_once dirname( __FILE__ ) . '/inc/action-sticky.php';
	add_action( 'sca_do_stick_content', 'sca_stick_content' );
	add_action( 'sca_do_unstick_content', 'sca_unstick_content' );

	// post status actions
	require_once dirname( __FILE__ ) . '/inc/action-status.php';
	add_action( 'sca_do_draft_content', 'sca_draft_content' );
	add_action( 'sca_do_private_content', 'sca_private_content' );
	add_action( 'sca_do_trash_content', 'sca_trash_content' );
	add_action( 'sca_do_delete_content', 'sca_delete_content' );

	// comment actions
	require_once dirname( __FILE__ ) . '/inc/action-comments.php';
	add_action( 'sca_do_open_comments', 'sca_open_comments' );
	add_action( 'sca_do_close_comments', 'sca_close_comments' );

	// term actions
	require_once dirname( __FILE__ ) . '/inc/action-terms.php';
	add_action( 'sca_do_add_term', 'sca_add_term' );
	add_action( 'sca_do_delete_term', 'sca_delete_term' );

	// meta actions
	require_once dirname( __FILE__ ) . '/inc/action-meta.php';
	add_action( 'sca_do_add_meta', 'sca_add_meta' );
	add_action( 'sca_do_update_meta', 'sca_update_meta' );
	add_action( 'sca_do_delete_meta', 'sca_delete_meta' );

	// title actions
	require_once dirname( __FILE__ ) . '/inc/action-title.php';
	add_action( 'sca_do_change_title', 'sca_change_title' );

	// scheduler
	require_once dirname( __FILE__ ) . '/inc/scheduler.php';
	add_action( 'wp_loaded', 'sca_scheduler' );

	// scripts
	require_once dirname( __FILE__ ) . '/inc/scripts.php';
	add_action( 'admin_enqueue_scripts', 'sca_load_scripts' );

	// styles
	require_once dirname( __FILE__ ) . '/inc/styles.php';
	add_action( 'admin_init', 'sca_load_styles' );

	// admin columns
	require_once dirname( __FILE__ ) . '/inc/admin-columns.php';
	add_filter( 'manage_posts_columns', 'sca_columns_head' );
	add_filter( 'manage_page_posts_columns', 'sca_columns_head', 10 );
	add_action( 'manage_page_posts_custom_column', 'sca_columns_content', 10, 2 );
	add_action( 'manage_posts_custom_column', 'sca_columns_content', 10, 2 );

	// meta box
	require_once dirname( __FILE__ ) . '/inc/meta-box.php';
	add_action( 'add_meta_boxes', 'sca_add_metabox' );
} add_action( 'plugins_loaded', 'sca_init' );