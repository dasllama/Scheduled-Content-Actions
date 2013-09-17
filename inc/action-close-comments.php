<?php
/**
 * Feature Name:	Action Close Comments
 * Author:			HerrLlama for Inpsyde GmbH
 * Author URI:		http://inpsyde.com
 * Licence:			GPLv3
 */

add_action( 'sca_do_close_comments', 'sca_close_comments' );
function sca_close_comments( $post_id ) {
	wp_update_post( array(
		'ID'				=> $post_id,
		'ping_status'		=> 'closed',
		'comment_status'	=> 'closed',
	) );
}