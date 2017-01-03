<?php
/**
 * Feature Name: List View Helper
 * Author:       HerrLlama for wpcoding.de
 * Version:      1.2
 * Author:       Thomas 'Llama' Herzog
 * Author URI:   https://profiles.wordpress.org/dasllama
 * Licence:      GPLv3
 */

/**
 * Registers the filters for the views
 *
 * @wp-hook	init
 * @return	void
 */
function sca_register_view_filters() {
	$aPostTypes = get_post_types();
	foreach ( $aPostTypes as $sPostType )
		add_action( 'views_edit-' . $sPostType, 'sca_add_filter_to_views' );
}

/**
 * Adds own filters to the issues view
 *
 * @wp-hook	views_edit-$post_type
 * @param	array $aViews
 * @return	array
 */
function sca_add_filter_to_views( $aViews ) {
	// get the current url to add our query arg to the link
	$sCurrentUrl = "//" . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ];

	// get the current post type
	if ( isset( $_GET[ 'post_type' ] ) )
		$sCurrentPostType = $_GET[ 'post_type' ];
	else
		$sCurrentPostType = 'post';

	// get the amount of posts found
	$oQueryPosts = new WP_Query( array(
		'post_type'  => $sCurrentPostType,
		'meta_key'   => '_sca_has_actions',
		'meta_value' => '1'
	) );

	// we don't need to add this filter if we have no
	// scheduled actions
	if ( $oQueryPosts->found_posts == 0 )
		return $aViews;

	// add our own link to the view
	$aViews[ 'scheduled' ] = '<a ' . ( isset( $_GET[ 'scahasaction' ] ) && $_GET[ 'scahasaction' ] == 'true' ? 'class="current"' : '' ) . ' href="' . add_query_arg( 'scahasaction', 'true', $sCurrentUrl ) . '">' . __( 'Scheduled Content', 'scheduled-content-actions' ) . ' <span class="count">(' . $oQueryPosts->found_posts . ')</span></a>';

	return $aViews;
}

/**
 * Checks if we need to add the scahasaction to the query
 *
 * @wp-hook	pre_get_posts
 * @param  	object &$query
 * @return	void
 */
function sca_add_filter_to_posts_query( &$query ) {

	// check if we have the has actions query argument
	if ( isset( $_GET[ 'scahasaction' ] ) && ! empty( $_GET[ 'scahasaction' ] ) ) {
		$query->query[ '_sca_has_actions' ] = $_GET[ 'scahasaction' ];
		$query->query_vars[ 'meta_key' ] = '_sca_has_actions';
		$query->query_vars[ 'meta_value' ] = '1';
	}
}