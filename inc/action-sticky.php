<?php
/**
 * Feature Name: Actions for the sticky post
 * Author:       HerrLlama for wpcoding.de
 * Author URI:   http://wpcoding.de
 * Licence:      GPLv3
 */

/**
 * Sticks a post
 *
 * @wp-hook	sca_do_stick_content
 * @param	array $action the details of the action
 * @return	void
 */
function sca_stick_content( $action ) {
	stick_post( $action[ 'post_id' ] );
}

/**
 * Unsticks a post
 *
 * @wp-hook	sca_do_unstick_content
 * @param	array $action the details of the action
 * @return	void
 */
function sca_unstick_content( $action ) {
	unstick_post( $action[ 'post_id' ] );
}