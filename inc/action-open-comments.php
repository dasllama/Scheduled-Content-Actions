<?php
/**
 * Feature Name:	Action Open Comments
 * Author:			HerrLlama for Inpsyde GmbH
 * Author URI:		http://inpsyde.com
 * Licence:			GPLv3
 */

add_action( 'sca_do_open_comments', 'sca_open_comments' );
function sca_open_comments( $post_id ) {
	wp_update_post( array(
		'ID'				=> $post_id,
		'ping_status'		=> 'open',
		'comment_status'	=> 'open',
	) );
}