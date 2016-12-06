<?php
/**
 * Feature Name: Scheduler
 * Version:      1.2
 * Author:       Thomas 'Llama' Herzog
 * Author URI:   https://profiles.wordpress.org/dasllama
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

	$aCurrentActions = get_option( '_sca_current_actions' );
	if ( empty( $aCurrentActions ) )
		return;

	foreach ( $aCurrentActions as $iPostId => $aTiming ) {
		foreach ( $aTiming as $iTime => $aActions ) {

			// check if we need to do this action
			if ( $iTime > current_time( 'timestamp' ) )
				continue;

			// do the action
			foreach ( $aActions as $aAction ) {
				$aAction[ 'post_id' ] = $iPostId;
				do_action( 'sca_do_' . $aAction[ 'type' ], $aAction );
				sca_delete_action( $aAction[ 'post_id' ], $aAction[ 'type' ], $iTime );
			}
		}
	}
}