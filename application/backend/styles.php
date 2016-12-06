<?php
/**
 * Feature Name: Styles
 * Descriptions: Adds some styles to the frontend
 * Version:      1.2
 * Author:       Thomas 'Llama' Herzog
 * Author URI:   https://profiles.wordpress.org/dasllama
 * Licence:      GPLv3
 */

/**
 * Enqueue styles and scripts.
 *
 * @wp-hook admin_enqueue_scripts
 * @return  Void
 */
function sca_admin_enqueue_styles() {

	$aStyles = sca_get_admin_styles();
	foreach( $aStyles as $key => $style ){
		wp_enqueue_style(
			$key,
			$style[ 'src' ],
			$style[ 'deps' ],
			$style[ 'version' ],
			$style[ 'media' ]
		);
	}
}

/**
 * Returning our frontend-Styles
 *
 * @return  Array
 */
function sca_get_admin_styles(){

	// set the basic data
	$aStyles = array();
	$sSuffix = sca_get_script_suffix();
	$sVersion = sca_get_plugin_version();

	// adding the main-CSS
	$aStyles[ 'sca-backend' ] = array(
		'src'       => sca_get_asset_directory_url( 'css' ) . 'backend' . $sSuffix . '.css',
	    'deps'      => NULL,
	    'version'   => SCRIPT_DEBUG === TRUE ? time() : $sVersion,
	    'media'     => NULL
	);

	return apply_filters( 'sca_get_admin_styles', $aStyles );
}