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
		'open_pings'       => __( 'Open Pings', 'scheduled-content-actions' ),
		'close_pings'      => __( 'Close Pings', 'scheduled-content-actions' ),
		'change_title'     => __( 'Change Title', 'scheduled-content-actions' ),
		'add_term'         => __( 'Add Term', 'scheduled-content-actions' ),
		'delete_term'      => __( 'Delete Term', 'scheduled-content-actions' ),
		'add_meta'         => __( 'Add Meta', 'scheduled-content-actions' ),
		'update_meta'      => __( 'Update Meta', 'scheduled-content-actions' ),
		'delete_meta'      => __( 'Delete Meta', 'scheduled-content-actions' ),
	) );
}

/**
 * Helper function to add an action
 *
 * @param	int $iPostId the current post id
 * @param	array $aRequestData the data which came from the AJAX request
 * @param	int $iTime the time when the action takes place
 *
 * @return	void
 */
function sca_add_action( $iPostId, $aRequestData, $iTime ) {

	// set the action type
	$sActionType = $aRequestData[ 'type' ];

	// get current actions
	$aCurrentActions = get_option( '_sca_current_actions' );
	if ( empty( $aCurrentActions ) )
		$aCurrentActions = array();

	// build the action array
	$aAction = array( 'type' => $sActionType );
	if ( $sActionType == 'add_term' || $sActionType == 'delete_term' ) {
		$aAction[ 'term_taxonomy' ] = $aRequestData[ 'termTaxonomy' ];
		$aAction[ 'term_slug' ] = $aRequestData[ 'termSlug' ];
	} else if ( $sActionType == 'add_meta' || $sActionType == 'update_meta' || $sActionType == 'delete_meta' ) {
		$aAction[ 'meta_name' ] = $aRequestData[ 'metaName' ];
		$aAction[ 'meta_value' ] = $aRequestData[ 'metaValue' ];
	} else if ( $sActionType == 'change_title') {
		$aAction[ 'new_title' ] = $aRequestData[ 'newTitle' ];
	}

	// let plugins handle their additional form data
	$aAction = apply_filters( 'sca_add_action', $aAction, $aRequestData );

	// add the action to the set
	$aCurrentActions[ $iPostId ][ $iTime ][] = $aAction;
	update_option( '_sca_current_actions', $aCurrentActions );

	// update the post meta to determinate that this has
	// a scheduled content action
	update_post_meta( $iPostId, '_sca_has_actions', '1' );
}

/**
 * Helper function to delete an action
 *
 * @param	int $iPostId the current post id
 * @param	string $sActionType the action type which should be deleted
 * @param	int $iTime the time when the action takes place
 *
 * @return	void
 */
function sca_delete_action( $iPostId, $sActionType, $iTime ) {

	$aCurrentPostActions = array();
	$aCurrentActions = get_option( '_sca_current_actions' );
	if ( isset( $aCurrentActions[ $iPostId ] ) )
		$aCurrentPostActions = $aCurrentActions[ $iPostId ];

	$aNewPostActions = array();
	foreach ( $aCurrentPostActions as $iTime => $aActions ) {
		if ( $iTime != $iTime ) {
			$aNewPostActions[ $iTime ] = $aActions;
			continue;
		} else {
			$aNewTimeActions = array();
			foreach ( $aActions as $aAction )
			if ( $aAction[ 'type' ] != $sActionType )
				$aNewTimeActions[] = $aAction;

			if ( ! empty( $aNewTimeActions ) )
				$aNewPostActions[ $iTime ] = $aNewTimeActions;
		}
	}

	$aCurrentActions[ $iPostId ] = $aNewPostActions;
	update_option( '_sca_current_actions', $aCurrentActions );

	// check if we still have actions for this post to
	// clarify if we still need the post meta
	if ( empty( $aCurrentActions[ $iPostId ] ) && count( $aCurrentActions[ $iPostId ] ) < 1 )
		delete_post_meta( $iPostId, '_sca_has_actions' );
}