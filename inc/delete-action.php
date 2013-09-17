<?php
/**
 * Feature Name:	Delete Action
 * Author:			HerrLlama for Inpsyde GmbH
 * Author URI:		http://inpsyde.com
 * Licence:			GPLv3
 */

function sca_delete_action( $post_id, $r_action_type, $r_time ) {

	$current_post_actions = array();
	$current_actions = get_option( '_sca_current_actions' );
	if ( isset( $current_actions[ $post_id ] ) )
		$current_post_actions = $current_actions[ $post_id ];

	$new_post_actions = array();
	foreach ( $current_post_actions as $time => $actions ) {
		if ( $time != $r_time ) {
			$new_post_actions[ $time ] = $actions;
			continue;
		} else {
			$new_time_actions = array();
			foreach ( $actions as $action )
				if ( $action != $r_action_type )
					$new_time_actions[] = $action;

			if ( ! empty( $new_time_actions ) )
				$new_post_actions[ $time ] = $new_time_actions;
		}
	}
	$current_actions[ $post_id ] = $new_post_actions;
	update_option( '_sca_current_actions', $current_actions );
}