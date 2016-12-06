<?php
/**
 * Feature Name: Scripts
 * Descriptions: Adds some javascripts to the frontend
 * Version:      1.2
 * Author:       Thomas 'Llama' Herzog
 * Author URI:   https://profiles.wordpress.org/dasllama
 * Licence:      GPLv3
 */

/**
 * Enqueue styles and scripts.
 *
 * @wp-hook	admin_enqueue_scripts
 * @return	void
 */
function sca_admin_enqueue_scripts() {

	// get the scripts and walk them
	$aScripts = sca_get_admin_scripts();
	foreach ( $aScripts as $sHandle => $aScript ) {
		wp_enqueue_script(
			$sHandle,
			$aScript[ 'src' ],
			$aScript[ 'deps' ],
			$aScript[ 'version' ],
			$aScript[ 'in_footer' ]
		);

		// checking for localize script args
		if ( array_key_exists( 'localize', $aScript ) && ! empty( $aScript[ 'localize' ] ) ) {
			foreach ( $aScript[ 'localize' ] as $sName => $aArgs ) {
				wp_localize_script(
					$sHandle,
					$sName,
					$aArgs
				);
			}
		}
	}
}

/**
 * Returning our scripts
 *
 * @return	array
 */
function sca_get_admin_scripts(){

	// set the basic data
	$aScripts = array();
	$sSuffix = sca_get_script_suffix();
	$sVersion = sca_get_plugin_version();

	// adding the scripts
	$aScripts[ 'sca-backend' ] = array(
		'src'       => sca_get_asset_directory_url( 'js' ) . 'backend' . $sSuffix . '.js',
		'deps'      => array( 'jquery' ),
		'version'   => SCRIPT_DEBUG === TRUE ? time() : $sVersion,
		'in_footer' => TRUE,
		'localize'  => array(
			'sca_vars' => array(
				'label_taxonomy'   => __( 'Taxonomy', 'scheduled-content-actions' ),
				'label_term'       => __( 'Term', 'scheduled-content-actions' ),
				'label_meta_name'  => __( 'Meta Name', 'scheduled-content-actions' ),
				'label_meta_value' => __( 'Meta Value', 'scheduled-content-actions' ),
				'label_title'      => __( 'Change Title', 'scheduled-content-actions' ),
			)
		),
	);

	return apply_filters( 'sca_get_admin_scripts', $aScripts );
}