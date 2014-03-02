<?php
/**
 * Feature Name:	Scheduler
 * Author:			HerrLlama for Inpsyde GmbH
 * Author URI:		http://inpsyde.com
 * Licence:			GPLv3
 */

add_action( 'wp_loaded', 'sca_scheduler' );
function sca_scheduler() {
	
	$current_actions = get_option( '_sca_current_actions' );
	if ( empty( $current_actions ) )
		return;
	
	foreach ( $current_actions as $post_id => $timing ) {
		foreach ( $timing as $time => $actions ) {
			
			// check if we need to do this action
			if ( $time > current_time( 'timestamp' ) )
				continue;
			
			// do the action
			foreach ( $actions as $action ) {
				do_action( 'sca_do_' . $action, $post_id );
				sca_delete_action( $post_id, $action, $time );
			}
		}
	}
}