<?php
/**
 * Feature Name: Scripts
 * Descriptions: Adds the dashboard widget
 * Version:      1.2
 * Author:       Thomas 'Llama' Herzog
 * Author URI:   https://profiles.wordpress.org/dasllama
 * Licence:      GPLv3
 */

/**
 * Adds our widget to the dashboard.
 *
 * @wp-hook	wp_dashboard_setup
 * @return	void
 */
function sca_add_dashboard_widgets() {
	wp_add_dashboard_widget(
		'sca_dashboard_widget',
		__( 'Scheduled Content Actions', 'scheduled-content-actions' ),
		'sca_dashboard_widget_content'
	);
}

/**
 * Displays the widget content called at
 * 'sca_add_dashboard_widgets'
 *
 * @return	void
 */
function sca_dashboard_widget_content() {

	// available action
	$aAvailableActions = sca_get_actions();

	// Current Actions
	$aCurrentPostActions = array();
	$aCurrentActions = get_option( '_sca_current_actions' );

	if ( ! empty( $aCurrentActions ) ) {
		?>
		<table id="sca">
			<thead>
				<tr>
					<th class="left"><?php _e( 'Date', 'scheduled-content-actions' ); ?></th>
					<th><?php _e( 'Post', 'scheduled-content-actions' ); ?></th>
					<th><?php _e( 'Action', 'scheduled-content-actions' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $aCurrentActions as $iPostId => $aActions ) : ?>
					<?php foreach ( $aActions as $iTime => $aAction ) : ?>
						<tr>
							<td><?php echo date_i18n( get_option( 'date_format' ), $iTime ); ?></td>
							<td><a href="<?php echo get_edit_post_link( $iPostId ); ?>"><?php echo get_the_title( $iPostId ); ?></a></td>
							<td>
								<?php foreach ( $aAction as $aTheAction ) : ?>
									<?php echo $aAvailableActions[ $aTheAction[ 'type' ] ] ?><br />
								<?php endforeach; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php
	}
}