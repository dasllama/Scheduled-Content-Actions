<?php
/**
 * Feature Name:	Action Trash
 * Author:			HerrLlama for Inpsyde GmbH
 * Author URI:		http://inpsyde.com
 * Licence:			GPLv3
 */

add_action( 'sca_do_trash_content', 'sca_trash_content' );
function sca_trash_content( $post_id ) {
	wp_trash_post( $post_id );
}