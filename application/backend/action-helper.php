<?php
/**
 * Feature Name: Action Helpers
 * Version:      1.2
 * Author:       Thomas 'Llama' Herzog
 * Author URI:   https://profiles.wordpress.org/dasllama
 * Licence:      GPLv3
 */

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
}

/**
 * AJAX Helper function to add an action to the scheduler
 *
 * @wp-hook	wp_ajax_sca_add_action
 * @return	void
 */
function sca_ajax_add_action() {

	// validate data
	$iActionTime = mktime( $_REQUEST[ 'dateHour' ], $_REQUEST[ 'dateMin' ], $_REQUEST[ 'dateSec' ], $_REQUEST[ 'dateMonth' ], $_REQUEST[ 'dateDay' ], $_REQUEST[ 'dateYear' ] );
	$iCurrentTime = time();
	if ( $iActionTime <= $iCurrentTime ) {
		echo json_encode( array(
			'error'		=> 1,
			'msg'		=> __( 'The time is in the past!', 'scheduled-content-actions' ),
		) );
		exit;
	}

	sca_add_action( $_REQUEST[ 'postId' ], $_REQUEST, $iActionTime );

	echo json_encode( array(
		'error'			=> 0,
		'msg'			=> '',
		'ln_date'		=> __( 'Date', 'scheduled-content-actions' ),
		'ln_action'		=> __( 'Action', 'scheduled-content-actions' ),
		'action_time'	=> $iActionTime,
		'action_date'	=> date( 'd.m.Y H:i:s', $iActionTime ),
	) );

	exit;
}

/**
 * AJAX Helper function to delete an action from the scheduler
 *
 * @wp-hook	wp_ajax_sca_delete_action
 * @return	void
 */
function sca_ajax_delete_action() {

	// validate data
	sca_delete_action( $_REQUEST[ 'postId' ], $_REQUEST[ 'type' ], $_REQUEST[ 'time' ] );

	echo json_encode( array(
		'error'		=> 0,
		'msg'		=> '',
	) );

	exit;
}

/**
 * AJAX Helper function to load the additional form data
 *
 * @wp-hook	wp_ajax_sca_load_additional_form_data
 * @return	void
 */
function sca_load_additional_form_data() {

	// check what form data we should load
	switch ( $_REQUEST[ 'type' ] ) {
		case 'add_term':
		case 'delete_term':
			sca_lafd_terms();
			break;
		case 'add_meta';
		case 'update_meta':
		case 'delete_meta':
			sca_lafd_meta();
			break;
		case 'change_title':
			sca_lafd_title();
			break;
		case 'tax':
			sca_lafd_slugs();
			break;
	}

	// let plugins load their additional form data
	do_action( 'sca_load_additional_form_data', $_REQUEST[ 'type' ] );

	exit;
}

/**
 * Form inputs for the terms called at sca_load_additional_form_data()
 *
 * @return	void
 */
function sca_lafd_terms() {

	$aTaxonomies = get_object_taxonomies( get_post_type( $_REQUEST[ 'post_id' ] ), 'objects' );
	?>
	<p>
		<label for="sca-term-taxonomy">
			<select class="large-text" name="scatermtaxonomy" id="sca-term-taxonomy">
				<option value=""><?php _e( 'Choose a taxonomy', 'scheduled-content-actions' ); ?></option>
				<?php foreach ( $aTaxonomies as $oTaxonomy ) : ?>
					<option value="<?php echo $oTaxonomy->name; ?>"><?php echo $oTaxonomy->labels->name; ?></option>
				<?php endforeach; ?>
			</select>
		</label>
	</p>
	<?php
	exit;
}

/**
 * Form inputs for the terms called at sca_load_additional_taxonomy_data()
 *
 * @return	void
 */
function sca_lafd_slugs() {

	$aCategories = get_terms( array(
		'taxonomy' => $_REQUEST[ 'tax' ]
	) );
	?>
	<p>
		<label for="sca-term-slug">
			<select class="large-text" name="scatermslug" id="sca-term-slug">
				<option value=""><?php _e( 'Choose a term', 'scheduled-content-actions' ); ?></option>
				<?php foreach ( $aCategories as $oCategory ) : ?>
					<option value="<?php echo $oCategory->slug; ?>"><?php echo $oCategory->name; ?></option>
				<?php endforeach; ?>
			</select>
		</label>
	</p>
	<?php
	exit;
}

/**
 * Form inputs for the meta called at sca_load_additional_form_data()
 *
 * @return	void
 */
function sca_lafd_meta() {

	?>
	<p>
		<label for="sca-meta-name"><strong><?php _e( 'Meta Name', 'scheduled-content-actions' ); ?></strong></label><br />
		<input type="text" name="scametaname" id="sca-meta-name" value="" class="large-text" />
	</p>
	<p>
		<label for="sca-meta-value"><strong><?php _e( 'Value', 'scheduled-content-actions' ); ?></strong></label><br />
		<input type="text" name="scametavalue" id="sca-meta-value" value="" class="large-text" />
	</p>
	<?php
	exit;
}

/**
 * Form inputs for the title called at sca_load_additional_form_data()
 *
 * @return	void
 */
function sca_lafd_title() {

	?>
	<p>
		<label for="sca-new-title"><strong><?php _e( 'New Title', 'scheduled-content-actions' ); ?></strong></label><br />
		<input type="text" name="scanewtitle" id="sca-new-title" value="" class="large-text" />
	</p>
	<?php
	exit;
}
