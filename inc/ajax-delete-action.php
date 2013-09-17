<?php
/**
 * Feature Name:	AJAX Delete Action
 * Author:			HerrLlama for Inpsyde GmbH
 * Author URI:		http://inpsyde.com
 * Licence:			GPLv3
 */

add_action( 'wp_ajax_sca_delete_action', 'sca_ajax_delete_action' );
function sca_ajax_delete_action() {

	// validate data
	sca_delete_action( $_REQUEST[ 'postId' ], $_REQUEST[ 'type' ], $_REQUEST[ 'time' ] );

	echo json_encode( array(
		'error'		=> 0,
		'msg'		=> '',
	) );

	exit;
}