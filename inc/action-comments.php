<?php
/**
 * Feature Name: Comment Actions
 * Author:       HerrLlama for wpcoding.de
 * Author URI:   http://wpcoding.de
 * Licence:      GPLv3
 */

/**
 * Closes the comments of a post
 *
 * @wp-hook	sca_do_close_comments
 * @param	array $action the details of the action
 * @return	void
 */
function sca_close_comments( $action ) {
	wp_update_post( array(
		'ID'				=> $action[ 'post_id' ],
		'ping_status'		=> 'closed',
		'comment_status'	=> 'closed',
	) );
}

/**
 * Opens the comments of a post
 *
 * @wp-hook	sca_do_open_comments
 * @param	array $action the details of the action
 * @return	void
 */
function sca_open_comments( $action ) {
	wp_update_post( array(
		'ID'				=> $action[ 'post_id' ],
		'ping_status'		=> 'open',
		'comment_status'	=> 'open',
	) );
}