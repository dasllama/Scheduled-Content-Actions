<?php
/**
 * Feature Name: Actions
 * Descriptions: Defines all the standard actions
 * Version:      1.2
 * Author:       Thomas 'Llama' Herzog
 * Author URI:   https://profiles.wordpress.org/dasllama
 * Licence:      GPLv3
 */

/**
 * Closes the comments of a post
 *
 * @wp-hook	sca_do_close_comments
 * @param	array $aAction the details of the action
 * @return	void
 */
function sca_close_comments( $aAction ) {
	wp_update_post( array(
		'ID'             => $aAction[ 'post_id' ],
		'comment_status' => 'closed',
	) );
}

/**
 * Opens the comments of a post
 *
 * @wp-hook	sca_do_open_comments
 * @param	array $aAction the details of the action
 * @return	void
 */
function sca_open_comments( $aAction ) {
	wp_update_post( array(
		'ID'             => $aAction[ 'post_id' ],
		'comment_status' => 'open',
	) );
}

/**
 * Closes the ping of a post
 *
 * @wp-hook	sca_do_close_pings
 * @param	array $aAction the details of the action
 * @return	void
 */
function sca_close_pings( $aAction ) {
	wp_update_post( array(
		'ID'          => $aAction[ 'post_id' ],
		'ping_status' => 'closed',
	) );
}

/**
 * Opens the ping of a post
 *
 * @wp-hook	sca_do_open_pings
 * @param	array $aAction the details of the action
 * @return	void
 */
function sca_open_pings( $aAction ) {
	wp_update_post( array(
		'ID'          => $aAction[ 'post_id' ],
		'ping_status' => 'open',
	) );
}

/**
 * Adds a meta to the post
 *
 * @wp-hook	sca_do_add_term
 * @param	array $aAction the details of the action
 * @return	void
 */
function sca_add_meta( $aAction ) {
	add_post_meta( $aAction[ 'post_id' ], $aAction[ 'meta_name' ], $aAction[ 'meta_value' ] );
}

/**
 * Updates a meta from the post
 *
 * @wp-hook	sca_do_update_meta
 * @param	array $aAction the details of the action
 * @return	void
 */
function sca_update_meta( $aAction ) {
	update_post_meta( $aAction[ 'post_id' ], $aAction[ 'meta_name' ], $aAction[ 'meta_value' ] );
}

/**
 * Deletes a meta from the post
 *
 * @wp-hook	sca_do_delete_meta
 * @param	array $aAction the details of the action
 * @return	void
 */
function sca_delete_meta( $aAction ) {
	delete_post_meta( $aAction[ 'post_id' ], $aAction[ 'meta_name' ] );
}

/**
 * Trashes a post
 *
 * @wp-hook	sca_do_trash_content
 * @param	array $aAction the details of the action
 * @return	void
 */
function sca_trash_content( $aAction ) {
	wp_trash_post( $aAction[ 'post_id' ] );
}

/**
 * Set a post to draft
 *
 * @wp-hook	sca_do_draft_content
 * @param	array $aAction the details of the action
 * @return	void
 */
function sca_draft_content( $aAction ) {
	wp_update_post( array(
		'ID'          => $aAction[ 'post_id' ],
		'post_status' => 'draft'
	) );
}

/**
 * Set a post to private
 *
 * @wp-hook	sca_do_private_content
 * @param	array $aAction the details of the action
 * @return	void
 */
function sca_private_content( $aAction ) {
	wp_update_post( array(
		'ID'          => $aAction[ 'post_id' ],
		'post_status' => 'private'
	) );
}

/**
 * Deletes a post
 *
 * @wp-hook	sca_do_stick_content
 * @param	array $aAction the details of the action
 * @return	void
 */
function sca_do_delete_content( $aAction ) {
	wp_delete_post( $aAction[ 'post_id' ], TRUE );
}

/**
 * Sticks a post
 *
 * @wp-hook	sca_do_stick_content
 * @param	array $aAction the details of the action
 * @return	void
 */
function sca_stick_content( $aAction ) {
	stick_post( $aAction[ 'post_id' ] );
}

/**
 * Unsticks a post
 *
 * @wp-hook	sca_do_unstick_content
 * @param	array $aAction the details of the action
 * @return	void
 */
function sca_unstick_content( $aAction ) {
	unstick_post( $aAction[ 'post_id' ] );
}

/**
 * Adds a term to the post
 *
 * @wp-hook	sca_do_add_term
 * @param	array $aAction the details of the action
 * @return	void
 */
function sca_add_term( $aAction ) {
	wp_add_object_terms( $aAction[ 'post_id' ], $aAction[ 'term_slug' ], $aAction[ 'term_taxonomy' ], TRUE );
}

/**
 * Deletes a term from the post
 *
 * @wp-hook	sca_do_delete_term
 * @param	array $aAction the details of the action
 * @return	void
 */
function sca_delete_term( $aAction ) {
	wp_remove_object_terms( $aAction[ 'post_id' ], $aAction[ 'term_slug' ], $aAction[ 'term_taxonomy' ] );
}

/**
 * Changes the title
 *
 * @wp-hook	sca_do_change_title
 * @param	array $aAction the details of the action
 * @return	void
 */
function sca_change_title( $aAction ) {
	wp_update_post( array(
		'ID'         => $aAction[ 'post_id' ],
		'post_title' => $aAction[ 'new_title' ]
	) );
}

/**
 * Updates the post date
 *
 * @wp-hook	sca_do_update_post_date
 * @param	array $aAction the details of the action
 * @return	void
 */
function sca_update_post_date( $aAction ) {
	wp_update_post( array(
		'ID'        => $aAction[ 'post_id' ],
		'post_date' => current_time( 'mysql' )
	) );
}