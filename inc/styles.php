<?php
/**
 * Feature Name:	Styles
 * Author:			HerrLlama for Inpsyde GmbH
 * Author URI:		http://inpsyde.com
 * Licence:			GPLv3
 */

add_action( 'admin_init', 'sca_load_styles' );
function sca_load_styles() {

	$stylename = 'admin.css';
	if ( defined( 'WP_DEBUG' ) )
		$stylename = 'admin.dev.css';

	wp_register_style(
		'sca-admin-styles',
		plugin_dir_url( __FILE__ ) . '../css/' . $stylename
	);
	wp_enqueue_style( 'sca-admin-styles' );
} );