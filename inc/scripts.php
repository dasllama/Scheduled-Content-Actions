<?php
/**
 * Feature Name: Scripts
 * Author:       HerrLlama for wpcoding.de
 * Author URI:   http://wpcoding.de
 * Licence:      GPLv3
 */

/**
 * Registers all the needed scripts and its
 * localization
 *
 * @wp-hook	admin_enqueue_scripts
 * @return	void
 */
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

	wp_localize_script( 'sca-admin-scripts', 'sca_vars', array(
		'label_taxonomy' => __( 'Taxonomy', 'scheduled-content-actions-td' ),
		'label_term' => __( 'Term', 'scheduled-content-actions-td' ),
		'label_meta_name' => __( 'Meta Name', 'scheduled-content-actions-td' ),
		'label_meta_value' => __( 'Meta Value', 'scheduled-content-actions-td' ),
		'label_title' => __( 'Change Title', 'scheduled-content-actions-td' ),
	) );
}