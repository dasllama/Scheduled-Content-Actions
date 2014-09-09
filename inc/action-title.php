<?php
/**
 * Feature Name: Title Actions
 * Author:       HerrLlama for wpcoding.de
 * Author URI:   http://wpcoding.de
 * Licence:      GPLv3
 */

/**
 * Changes the title
 *
 * @wp-hook	sca_do_change_title
 * @param	array $action the details of the action
 * @return	void
 */
function sca_change_title( $action ) {
	wp_update_post( array(
		'ID'			=> $action[ 'post_id' ],
		'post_title'	=> $action[ 'new_title' ]
	) );
}