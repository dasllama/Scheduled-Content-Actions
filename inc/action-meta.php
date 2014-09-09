<?php
/**
 * Feature Name: Meta Actions
 * Author:       HerrLlama for wpcoding.de
 * Author URI:   http://wpcoding.de
 * Licence:      GPLv3
 */

/**
 * Adds a meta to the post
 *
 * @wp-hook	sca_do_add_term
 * @param	array $action the details of the action
 * @return	void
 */
function sca_add_meta( $action ) {
	add_post_meta( $action[ 'post_id' ], $action[ 'meta_name' ], $action[ 'meta_value' ] );
}

/**
 * Updates a meta from the post
 *
 * @wp-hook	sca_do_update_meta
 * @param	array $action the details of the action
 * @return	void
 */
function sca_update_meta( $action ) {
	update_post_meta( $action[ 'post_id' ], $action[ 'meta_name' ], $action[ 'meta_value' ] );
}

/**
 * Deletes a meta from the post
 *
 * @wp-hook	sca_do_delete_meta
 * @param	array $action the details of the action
 * @return	void
 */
function sca_delete_meta( $action ) {
	delete_post_meta( $action[ 'post_id' ], $action[ 'meta_name' ] );
}