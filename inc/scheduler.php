<?php
/**
 * Feature Name: Scheduler
 * Author:       HerrLlama for wpcoding.de
 * Author URI:   http://wpcoding.de
 * Licence:      GPLv3
 */

/**
 * Loads the actions and checks if the
 * plugin has something to do.
 *
 * @wp-hook	wp_loaded
 * @return	void
 */
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
				$action[ 'post_id' ] = $post_id;
				do_action( 'sca_do_' . $action[ 'type' ], $action );
				sca_delete_action( $action[ 'post_id' ], $action[ 'type' ], $time );
			}
		}
	}
}