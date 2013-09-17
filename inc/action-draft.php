<?php
/**
 * Feature Name:	Action Draft
 * Author:			HerrLlama for Inpsyde GmbH
 * Author URI:		http://inpsyde.com
 * Licence:			GPLv3
 */

add_action( 'sca_do_draft_content', 'sca_draft_content' );
function sca_draft_content( $post_id ) {
	wp_update_post( array(
		'ID'			=> $post_id,
		'post_status'	=> 'draft'
	) );
}