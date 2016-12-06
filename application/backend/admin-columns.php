<?php
/**
 * Feature Name: Admin Columns
 * Version:      1.2
 * Author:       Thomas 'Llama' Herzog
 * Author URI:   https://profiles.wordpress.org/dasllama
 * Licence:      GPLv3
 */

/**
 * Adds the custom column to the post type
 * page and post
 *
 * @wp-hook	manage_posts_columns, manage_page_posts_columns
 * @param	array $aDefaultColumns the current columns
 * @return	array the manipulated columns
 */
function sca_columns_head( $aDefaultColumns ) {
	$aDefaultColumns[ 'scheduled_content_action' ] = __( 'Scheduled Action', 'scheduled-content-actions' );
	return $aDefaultColumns;
}

/**
 * Adds the custom column content
 * to the post type page and post
 *
 * @wp-hook	manage_page_posts_custom_column, manage_posts_custom_column
 * @param	string $sColumnName the current columns
 * @param	int $iPostId the current post id
 * @return	void
 */
function sca_columns_content( $sColumnName, $iPostId ) {

	if ( $sColumnName != 'scheduled_content_action' )
		return;

	// load available actions
	$aAvailableActions = sca_get_actions();

	// Current Actions for this post
	$sCurrentPostActions = array();
	$aCurrentActions = get_option( '_sca_current_actions' );
	if ( isset( $aCurrentActions[ $iPostId ] ) )
		$sCurrentPostActions = $aCurrentActions[ $iPostId ];

	if ( ! empty( $sCurrentPostActions ) ) {
		foreach ( $sCurrentPostActions as $iTime => $aActions ) : ?>
			<p>
				<strong><?php echo date_i18n( 'd.m.Y H:i:s', $iTime ); ?>:</strong>
				<?php foreach ( $aActions as $aAction ) : ?>
					<?php echo $aAvailableActions[ $aAction[ 'type' ] ]; ?><br />
				<?php endforeach; ?>
			</p>
		<?php endforeach;

	} else {
		_e( 'No actions scheduled', 'scheduled-content-actions' );
	}
}