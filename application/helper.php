<?php
/**
 * Feature Name: Helper
 * Descriptions: Defines all global helper functions we need
 * Version:      1.2
 * Author:       Thomas 'Llama' Herzog
 * Author URI:   https://profiles.wordpress.org/dasllama
 * Licence:      GPLv3
 */

/**
 * Gets the current plugin version
 *
 * @return	string the current plugin version
 */
function sca_get_plugin_version() {
	$aPluginData = get_plugin_data( WP_PLUGIN_DIR . '/' . SCA_BASEFILE );
	return $aPluginData[ 'Version' ];
}

/**
 * Getting the Script and Style suffix
 * Adds a conditional ".min" suffix to the
 * file name when WP_DEBUG is NOT set to TRUE.
 *
 * @return	string
 */
function sca_get_script_suffix() {

	$bScriptDebug = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;
	$sSuffix = $bScriptDebug ? '' : '.min';

	// XXX for current development
	// XXX remove this and minify assets
	$sSuffix = '';
	// XXX remove this and minify assets
	// XXX for current development

	return $sSuffix;
}

/**
 * Gets the specific asset directory url
 *
 * @param	string $sPath the relative path to the wanted subdirectory. If
 *				no path is selected, the root asset directory will be returned
 * @return	string the url of the wcpd asset directory
 */
function sca_get_asset_directory_url( $sPath = '' ) {

	// set base url
	$sAssetUrl = SCA_PLUGIN_URL . 'assets/';
	if ( $sPath != '' )
		$sAssetUrl .= $sPath . '/';
	return $sAssetUrl;
}

/**
 * Gets the specific asset directory path
 *
 * @param	string $sPath the relative path to the wanted subdirectory. If
 *				no path is selected, the root asset directory will be returned
 * @return	string the url of the wcpd asset directory
 */
function sca_get_asset_directory( $sPath = '' ) {

	// set base url
	$sAssetDirectory = SCA_PLUGIN_PATH . 'assets/';
	if ( $sPath != '' )
		$sAssetDirectory .= $sPath . '/';
	return $sAssetDirectory;
}

/**
 * Registers all the possible actions
 *
 * @return array the actions
 */
function sca_get_actions() {
	return apply_filters( 'sca_get_actions' , array(
		'stick_content'    => __( 'Stick Post', 'scheduled-content-actions' ),
		'unstick_content'  => __( 'Unstick Post', 'scheduled-content-actions' ),
		'private_content'  => __( 'Private Post', 'scheduled-content-actions' ),
		'draft_content'    => __( 'Draft Post', 'scheduled-content-actions' ),
		'trash_content'    => __( 'Trash Post', 'scheduled-content-actions' ),
		'delete_content'   => __( 'Delete Post', 'scheduled-content-actions' ),
		'update_post_date' => __( 'Update Post Date', 'scheduled-content-actions' ),
		'open_comments'    => __( 'Open Comments', 'scheduled-content-actions' ),
		'close_comments'   => __( 'Close Comments', 'scheduled-content-actions' ),
		'open_pings'       => __( 'Open Comments', 'scheduled-content-actions' ),
		'close_pings'      => __( 'Close Comments', 'scheduled-content-actions' ),
		'change_title'     => __( 'Change Title', 'scheduled-content-actions' ),
		'add_term'         => __( 'Add Term', 'scheduled-content-actions' ),
		'delete_term'      => __( 'Delete Term', 'scheduled-content-actions' ),
		'add_meta'         => __( 'Add Meta', 'scheduled-content-actions' ),
		'update_meta'      => __( 'Update Meta', 'scheduled-content-actions' ),
		'delete_meta'      => __( 'Delete Meta', 'scheduled-content-actions' ),
	) );
}