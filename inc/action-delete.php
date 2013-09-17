<?php
/**
 * Feature Name:	Action Delete
 * Author:			HerrLlama for Inpsyde GmbH
 * Author URI:		http://inpsyde.com
 * Licence:			GPLv3
 */

add_action( 'sca_do_delete_content', 'sca_delete_content' );
function sca_delete_content( $post_id ) {
	wp_delete_post( $post_id, TRUE );
}