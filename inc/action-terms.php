<?php
/**
 * Feature Name: Term Actions
 * Author:       HerrLlama for wpcoding.de
 * Author URI:   http://wpcoding.de
 * Licence:      GPLv3
 */

/**
 * Adds a term to the post
 *
 * @wp-hook	sca_do_add_term
 * @param	array $action the details of the action
 * @return	void
 */
function sca_add_term( $action ) {
	wp_add_object_terms( $action[ 'post_id' ], $action[ 'term_slug' ], $action[ 'term_taxonomy' ] );
}

/**
 * Deletes a term from the post
 *
 * @wp-hook	sca_do_delete_term
 * @param	array $action the details of the action
 * @return	void
 */
function sca_delete_term( $action ) {
	wp_remove_object_terms( $action[ 'post_id' ], $action[ 'term_slug' ], $action[ 'term_taxonomy' ] );
}