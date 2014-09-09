<?php
/**
 * Feature Name: Styles
 * Author:       HerrLlama for wpcoding.de
 * Author URI:   http://wpcoding.de
 * Licence:      GPLv3
 */

/**
 * Registers all the needed styles
 *
 * @wp-hook	admin_init
 * @return	void
 */
function sca_load_styles() {

	$stylename = 'admin.css';
	if ( defined( 'WP_DEBUG' ) )
		$stylename = 'admin.dev.css';

	wp_register_style(
		'sca-admin-styles',
		plugin_dir_url( __FILE__ ) . '../css/' . $stylename
	);
	wp_enqueue_style( 'sca-admin-styles' );
};