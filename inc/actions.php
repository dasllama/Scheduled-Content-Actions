<?php
/**
 * Feature Name: Action Helpers
 * Author:       HerrLlama for wpcoding.de
 * Author URI:   http://wpcoding.de
 * Licence:      GPLv3
 */

/**
 * Registers all the possible actions
 *
 * @return array the actions
 */
function sca_get_actions() {
	return apply_filters( 'sca_get_actions' , array(
		'stick_content'		=> __( 'Stick Post', 'scheduled-content-actions-td' ),
		'unstick_content'	=> __( 'Unstick Post', 'scheduled-content-actions-td' ),
		'private_content'	=> __( 'Private Post', 'scheduled-content-actions-td' ),
		'draft_content'		=> __( 'Draft Post', 'scheduled-content-actions-td' ),
		'trash_content'		=> __( 'Trash Post', 'scheduled-content-actions-td' ),
		'delete_content'	=> __( 'Delete Post', 'scheduled-content-actions-td' ),
		'open_comments'		=> __( 'Open Comments', 'scheduled-content-actions-td' ),
		'close_comments'	=> __( 'Close Comments', 'scheduled-content-actions-td' ),
		'change_title'		=> __( 'Change Title', 'scheduled-content-actions-td' ),
		'add_term'			=> __( 'Add Term', 'scheduled-content-actions-td' ),
		'delete_term'		=> __( 'Delete Term', 'scheduled-content-actions-td' ),
		'add_meta'			=> __( 'Add Meta', 'scheduled-content-actions-td' ),
		'update_meta'		=> __( 'Update Meta', 'scheduled-content-actions-td' ),
		'delete_meta'		=> __( 'Delete Meta', 'scheduled-content-actions-td' ),
	) );
}

/**
 * Helper function to add an action
 *
 * @param	int $post_id the current post id
 * @param	array $request_data the data which came from the AJAX request
 * @param	int $time the time when the action takes place
 *
 * @return	void
 */
function sca_add_action( $post_id, $request_data, $time ) {

	$action_type = $request_data[ 'type' ];

	// get current actions
	$current_actions = get_option( '_sca_current_actions' );
	if ( empty( $current_actions ) )
		$current_actions = array();

	// build the action array
	$action = array( 'type' => $action_type );
	if ( $action_type == 'add_term' || $action_type == 'delete_term' ) {
		$action[ 'term_taxonomy' ] = $request_data[ 'termTaxonomy' ];
		$action[ 'term_slug' ] = $request_data[ 'termSlug' ];
	} else if ( $action_type == 'add_meta' || $action_type == 'update_meta' || $action_type == 'delete_meta' ) {
		$action[ 'meta_name' ] = $request_data[ 'metaName' ];
		$action[ 'meta_value' ] = $request_data[ 'metaValue' ];
	} else if ( $action_type == 'change_title') {
		$action[ 'new_title' ] = $request_data[ 'newTitle' ];
	}

	$current_actions[ $post_id ][ $time ][] = $action;
	update_option( '_sca_current_actions', $current_actions );
}

/**
 * Helper function to delete an action
 *
 * @param	int $post_id the current post id
 * @param	string $r_action_type the action type which should be deleted
 * @param	int $r_time the time when the action takes place
 *
 * @return	void
 */
function sca_delete_action( $post_id, $r_action_type, $r_time ) {

	$current_post_actions = array();
	$current_actions = get_option( '_sca_current_actions' );
	if ( isset( $current_actions[ $post_id ] ) )
		$current_post_actions = $current_actions[ $post_id ];

	$new_post_actions = array();
	foreach ( $current_post_actions as $time => $actions ) {
		if ( $time != $r_time ) {
			$new_post_actions[ $time ] = $actions;
			continue;
		} else {
			$new_time_actions = array();
			foreach ( $actions as $action )
			if ( $action[ 'type' ] != $r_action_type )
				$new_time_actions[] = $action;

			if ( ! empty( $new_time_actions ) )
				$new_post_actions[ $time ] = $new_time_actions;
		}
	}
	$current_actions[ $post_id ] = $new_post_actions;
	update_option( '_sca_current_actions', $current_actions );
}

/**
 * AJAX Helper function to add an action to the scheduler
 *
 * @wp-hook	wp_ajax_sca_add_action
 * @return	void
 */
function sca_ajax_add_action() {

	// validate data
	$action_time = mktime( $_REQUEST[ 'dateHour' ], $_REQUEST[ 'dateMin' ], $_REQUEST[ 'dateSec' ], $_REQUEST[ 'dateMonth' ], $_REQUEST[ 'dateDay' ], $_REQUEST[ 'dateYear' ] );
	$current_time = time();
	if ( $action_time <= $current_time ) {
		echo json_encode( array(
			'error'		=> 1,
			'msg'		=> __( 'The time is in the past!', 'scheduled-content-actions-td' ),
		) );
		exit;
	}

	sca_add_action( $_REQUEST[ 'postId' ], $_REQUEST, $action_time );

	echo json_encode( array(
		'error'			=> 0,
		'msg'			=> '',
		'ln_date'		=> __( 'Date', 'scheduled-content-actions-td' ),
		'ln_action'		=> __( 'Action', 'scheduled-content-actions-td' ),
		'action_time'	=> $action_time,
		'action_date'	=> date( 'd.m.Y H:i:s', $action_time ),
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
	}

	exit;
}

/**
 * Form inputs for the terms called at sca_load_additional_form_data()
 *
 * @return	void
 */
function sca_lafd_terms() {

	$taxonomies = get_taxonomies( NULL, 'objects' );
	?>
	<p>
		<label for="sca-term-taxonomy">
			<select class="large-text" name="scatermtaxonomy" id="sca-term-taxonomy">
				<option value=""><?php _e( 'Choose a taxonomy', 'scheduled-content-actions-td' ); ?></option>
				<?php foreach ( $taxonomies as $taxonomy ) : ?>
					<option value="<?php echo $taxonomy->name; ?>"><?php echo $taxonomy->labels->name; ?></option>
				<?php endforeach; ?>
			</select>
		</label>
	</p>
	<p>
		<label for="sca-term-slug"><strong><?php _e( 'Term Slug', 'scheduled-content-actions-td' ); ?></strong></label><br />
		<input type="text" name="scatermslug" id="sca-term-slug" value="" class="large-text" />
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
		<label for="sca-meta-name"><strong><?php _e( 'Meta Name', 'scheduled-content-actions-td' ); ?></strong></label><br />
		<input type="text" name="scametaname" id="sca-meta-name" value="" class="large-text" />
	</p>
	<p>
		<label for="sca-meta-value"><strong><?php _e( 'Value', 'scheduled-content-actions-td' ); ?></strong></label><br />
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
		<label for="sca-new-title"><strong><?php _e( 'New Title', 'scheduled-content-actions-td' ); ?></strong></label><br />
		<input type="text" name="scanewtitle" id="sca-new-title" value="" class="large-text" />
	</p>
	<?php
	exit;
}