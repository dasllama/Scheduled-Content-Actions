<?php
/**
 * Feature Name: Meta-Box
 * Author:       HerrLlama for wpcoding.de
 * Author URI:   http://wpcoding.de
 * Licence:      GPLv3
 */

/**
 * Adds the meta box
 *
 * @wp-hook	add_meta_boxes
 * @return	void
 */
function sca_add_metabox() {
	add_meta_box( 'sca-box', __( 'Scheduled Post Actions', 'scheduled-content-actions-td' ), 'sca_metabox' );
}

/**
 * Displays the content of the metabox registered
 * and called at sca_add_metabox
 *
 * @param	object $post the current post
 * @return	void
 */
function sca_metabox( $post ) {

	$available_actions = sca_get_actions();
	// new actions for this post
	$timezone = current_time( 'timestamp' );
	?>
	<div class="sca-action-box sca-metabox sca-new-action-container">
		<h3><?php _e( 'Add an action', 'scheduled-content-actions-td' ); ?></h3>
		<div class="inside">
			<p>
				<select name="scatype" id="sca-type" class="large-text">
					<option value=""><?php _e( 'Choose an action', 'scheduled-content-actions-td' ); ?></option>
					<?php foreach ( $available_actions as $action => $label ) : ?>
						<option value="<?php echo $action; ?>"><?php echo $label; ?></option>
					<?php endforeach; ?>
				</select>
			</p>
			<div class="sca-additional-form-data"></div>
			<hr>
			<p>
				<label><strong><?php _e( 'Choose the date', 'scheduled-content-actions-td' ); ?></strong></label><br />
				<input type="number" class="small-text" id="sca-date-day" name="scadateday" min="1" max="31" step="1" value="<?php echo date( 'd' ); ?>" />.
				<select id="sca-date-month" name="scadatemonth">
					<?php for ( $i = 1; $i <= 12; $i++ ) : ?>
						<option <?php echo ( date( 'm' ) == $i ? 'selected="selected"' : '' ); ?> value="<?php echo $i; ?>"><?php echo date_i18n( 'F', strtotime( '01.' . $i . '.2013' ) ); ?></option>
					<?php endfor; ?>
				</select>.
				<input type="number" class="small-text" id="sca-date-year" name="scadateyear" min="<?php echo date( 'Y' ); ?>" step="1" value="<?php echo date( 'Y' ); ?>" />
			</p>
			<p>
				<label><strong><?php _e( 'Choose the time', 'scheduled-content-actions-td' ); ?></strong></label><br />
				<input type="number" class="small-text" id="sca-date-hour" name="scadatehour" min="0" max="24" step="1" value="<?php echo date( 'H', $timezone ); ?>" />:
				<input type="number" class="small-text" id="sca-date-min" name="scadatemin" min="0" max="60" step="1" value="<?php echo date( 'i', $timezone ); ?>" />:
				<input type="number" class="small-text" id="sca-date-sec" name="scadatesec" min="0" max="60" step="1" value="<?php echo date( 's', $timezone ); ?>" />
			</p>
			<hr>
			<p><input type="submit" name="scanewaction" id="sca-newaction-submit" class="button-primary alignright" value="<?php _e( 'Save' ); ?>"><br class="clearfix"></p>
			<input type="hidden" name="scapostid" id="sca-post-id" value="<?php echo $post->ID; ?>">
		</div>
	</div>

	<div class="sca-current-action-container">
	<?php
	// Current Actions for this post
	$current_post_actions = array();
	$current_actions = get_option( '_sca_current_actions' );
	if ( isset( $current_actions[ $post->ID ] ) )
		$current_post_actions = $current_actions[ $post->ID ];
	if ( ! empty( $current_post_actions ) ) {
		?>
		<table id="sca">
			<thead>
				<tr>
					<th class="left"><?php _e( 'Date', 'scheduled-content-actions-td' ); ?></th>
					<th><?php _e( 'Action', 'scheduled-content-actions-td' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $current_post_actions as $time => $actions ) : ?>
					<tr>
						<td class="left"><?php echo date_i18n( 'd.m.Y H:i:s', $time ); ?></td>
						<td class="td-<?php echo $time; ?>">
							<?php foreach ( $actions as $action ) : ?>
								<?php
									$label = $action[ 'type' ];
									if ( $action[ 'type' ] == 'add_term' || $action[ 'type' ] == 'delete_term' )
										$label .= ' - ' . __( 'Taxonomy', 'scheduled-content-actions-td' ) . ': ' . $action[ 'term_taxonomy' ] . ' ' . __( 'Term', 'scheduled-content-actions-td' ) . ': ' . $action[ 'term_slug' ];
									else if ( $action[ 'type' ] == 'add_meta' || $action[ 'type' ] == 'update_meta' || $action[ 'type' ] == 'delete_meta' )
										$label .= ' - ' . __( 'Meta Name', 'scheduled-content-actions-td' ) . ': ' . $action[ 'meta_name' ] . ' ' . __( 'Meta Value', 'scheduled-content-actions-td' ) . ': ' . $action[ 'meta_value' ];
									else if ( $action[ 'type' ] == 'change_title' )
										$label .= ' - ' . __( 'Title', 'scheduled-content-actions-td' ) . ': ' . $action[ 'new_title' ]
								?>
								<div class="sca-action">
									<a href="#" class="remove-action" data-postid="<?php echo $post->ID; ?>" data-time="<?php echo $time; ?>" data-action="<?php echo $action[ 'type' ]; ?>">&nbsp;</a>
									<?php echo $label; ?>
								</div>
							<?php endforeach; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php
	}
	?></div><?php
}