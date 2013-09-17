<?php
/**
 * Plugin Name:	Scheduled Content Actions
 * Description:	This plugin provides several actions which affects the behaviour of a post entry. It also handles custom post types and products for woocommerce/jjigoshop.
 * Version:		1.0
 * Author:		HerrLlama for Inpsyde GmbH
 * Author URI:	http://inpsyde.com
 * Licence:		GPLv3
 */

// check wp
if ( ! function_exists( 'add_action' ) )
	return;

// constants
define( 'SCA_TEXTDOMAIN', 'scheduled-content-actions-td' );

// kickoff
add_action( 'plugins_loaded', 'sca_init' );
function sca_init() {
	
	// language
	require_once dirname( __FILE__ ) . '/inc/localization.php';

	// register all actions, needed in the scheduler
	require_once dirname( __FILE__ ) . '/inc/actions.php';

	// standard actions
	require_once dirname( __FILE__ ) . '/inc/add-action.php';
	require_once dirname( __FILE__ ) . '/inc/delete-action.php';
	
	// content actions
	require_once dirname( __FILE__ ) . '/inc/action-draft.php';
	require_once dirname( __FILE__ ) . '/inc/action-stick.php';
	require_once dirname( __FILE__ ) . '/inc/action-unstick.php';
	require_once dirname( __FILE__ ) . '/inc/action-trash.php';
	require_once dirname( __FILE__ ) . '/inc/action-delete.php';
	require_once dirname( __FILE__ ) . '/inc/action-open-comments.php';
	require_once dirname( __FILE__ ) . '/inc/action-close-comments.php';
	
	// scheduler
	require_once dirname( __FILE__ ) . '/inc/scheduler.php';
	
	// everything below is just in the admin panel
	if ( ! is_admin() )
		return;
	
	// scripts and styles
	require_once dirname( __FILE__ ) . '/inc/scripts.php';
	require_once dirname( __FILE__ ) . '/inc/styles.php';
	
	// ajax actions
	require_once dirname( __FILE__ ) . '/inc/ajax-add-action.php';
	require_once dirname( __FILE__ ) . '/inc/ajax-delete-action.php';

	// meta box
	require_once dirname( __FILE__ ) . '/inc/meta-box.php';
}