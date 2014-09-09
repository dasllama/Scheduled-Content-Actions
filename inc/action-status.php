<?php
/**
 * Feature Name: Actions for the post status
 * Author:       HerrLlama for wpcoding.de
 * Author URI:   http://wpcoding.de
 * Licence:      GPLv3
 */

/**
 * Trashes a post
 *
 * @wp-hook	sca_do_trash_content
 * @param	array $action the details of the action
 * @return	void
 */
function sca_trash_content( $action ) {
	wp_trash_post( $action[ 'post_id' ] );
}

/**
 * Set a post to draft
 *
 * @wp-hook	sca_do_draft_content
 * @param	array $action the details of the action
 * @return	void
 */
function sca_draft_content( $action ) {
	wp_update_post( array(
		'ID'			=> $action[ 'post_id' ],
		'post_status'	=> 'draft'
	) );
}

/**
 * Set a post to private
 *
 * @wp-hook	sca_do_private_content
 * @param	array $action the details of the action
 * @return	void
 */
function sca_private_content( $action ) {
	wp_update_post( array(
		'ID'			=> $action[ 'post_id' ],
		'post_status'	=> 'private'
	) );
}

/**
 * Deletes a post
 *
 * @wp-hook	sca_do_stick_content
 * @param	array $action the details of the action
 * @return	void
 */
function sca_do_delete_content( $action ) {
	wp_delete_post( $action[ 'post_id' ], TRUE );
}