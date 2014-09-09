<?php
/**
 * Feature Name: Admin Columns
 * Author:       HerrLlama for wpcoding.de
 * Author URI:   http://wpcoding.de
 * Licence:      GPLv3
 */

/**
 * Adds the custom column to the post type
 * page and post
 *
 * @wp-hook	manage_posts_columns, manage_page_posts_columns
 * @param	array $defaults the current columns
 * @return	array the manipulated columns
 */
function sca_columns_head( $defaults ) {
	$defaults[ 'scheduled_content_action' ] = __( 'Scheduled Action', 'scheduled-content-actions-td' );
	return $defaults;
}

/**
 * Adds the custom column content
 * to the post type page and post
 *
 * @wp-hook	manage_page_posts_custom_column, manage_posts_custom_column
 * @param	string $column_name the current columns
 * @param	int $post_id the current post id
 * @return	void
 */
function sca_columns_content( $column_name, $post_id ) {

	if ( $column_name != 'scheduled_content_action' )
		return;

	// load available actions
	$available_actions = sca_get_actions();

	// Current Actions for this post
	$current_post_actions = array();
	$current_actions = get_option( '_sca_current_actions' );
	if ( isset( $current_actions[ $post_id ] ) )
		$current_post_actions = $current_actions[ $post_id ];

	if ( ! empty( $current_post_actions ) ) {

		foreach ( $current_post_actions as $time => $actions ) : ?>
			<p>
				<strong><?php echo date_i18n( 'd.m.Y H:i:s', $time ); ?>:</strong>
				<?php foreach ( $actions as $action ) : ?>
					<?php echo $available_actions[ $action ]; ?><br />
				<?php endforeach; ?>
			</p>
		<?php endforeach;

	} else {
		_e( 'No actions scheduled', 'scheduled-content-actions-td' );
	}
}