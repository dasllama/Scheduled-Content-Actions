<?php
/**
 * Feature Name:	Action Stick
 * Author:			HerrLlama for Inpsyde GmbH
 * Author URI:		http://inpsyde.com
 * Licence:			GPLv3
 */

add_action( 'sca_do_stick_content', 'sca_stick_content' );
function sca_stick_content( $post_id ) {
	stick_post( $post_id );
}