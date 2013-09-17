<?php
/**
 * Feature Name:	Add Action
 * Author:			HerrLlama for Inpsyde GmbH
 * Author URI:		http://inpsyde.com
 * Licence:			GPLv3
 */

function sca_add_action( $post_id, $action_type, $time ) {
	$current_actions = get_option( '_sca_current_actions' );
	if ( empty( $current_actions ) )
		$current_actions = array();
	$current_actions[ $post_id ][ $time ][] = $action_type;
	update_option( '_sca_current_actions', $current_actions );
}