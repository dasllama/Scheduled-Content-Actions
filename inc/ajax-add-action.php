<?php
/**
 * Feature Name:	AJAX Add Action
 * Author:			HerrLlama for Inpsyde GmbH
 * Author URI:		http://inpsyde.com
 * Licence:			GPLv3
 */

add_action( 'wp_ajax_sca_add_action', 'sca_ajax_add_action' );
function sca_ajax_add_action() {

	// validate data
	$action_time = mktime( $_REQUEST[ 'dateHour' ], $_REQUEST[ 'dateMin' ], $_REQUEST[ 'dateSec' ], $_REQUEST[ 'dateMonth' ], $_REQUEST[ 'dateDay' ], $_REQUEST[ 'dateYear' ] );
	$current_time = time();
	if ( $action_time <= $current_time ) {
		echo json_encode( array(
			'error'		=> 1,
			'msg'		=> __( 'The time is in the past!', SCA_TEXTDOMAIN ),
		) );
		exit;
	}

	sca_add_action( $_REQUEST[ 'postId' ], $_REQUEST[ 'type' ], $action_time );

	echo json_encode( array(
		'error'			=> 0,
		'msg'			=> '',
		'ln_date'		=> __( 'Date', SCA_TEXTDOMAIN ),
		'ln_action'		=> __( 'Action', SCA_TEXTDOMAIN ),
		'action_time'	=> $action_time,
		'action_date'	=> date( 'd.m.Y H:i:s', $action_time ),
	) );

	exit;
}