<?php
/**
 * Feature Name:	Action Unstick
 * Author:			HerrLlama for Inpsyde GmbH
 * Author URI:		http://inpsyde.com
 * Licence:			GPLv3
 */

add_action( 'sca_do_unstick_content', 'sca_unstick_content' );
function sca_unstick_content( $post_id ) {
	unstick_post( $post_id );
}