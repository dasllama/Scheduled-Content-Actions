<?php
/**
 * Feature Name:	Scripts
 * Author:			HerrLlama for Inpsyde GmbH
 * Author URI:		http://inpsyde.com
 * Licence:			GPLv3
 */

add_action( 'admin_enqueue_scripts', 'sca_load_scripts' );
function sca_load_scripts() {

	$script_suffix = '.js';
	if ( defined( 'WP_DEBUG' ) )
		$script_suffix = '.dev.js';

	wp_register_script(
		'sca-admin-scripts',
		plugin_dir_url( __FILE__ ) . '../js/admin' . $script_suffix,
		array(
			'jquery',
		)
	);

	wp_enqueue_script( 'sca-admin-scripts' );
}