<?php
/**
 * Feature Name: Meta-Box
 * Author:       HerrLlama for wpcoding.de
 * Version:      1.2
 * Author:       Thomas 'Llama' Herzog
 * Author URI:   https://profiles.wordpress.org/dasllama
 * Licence:      GPLv3
 */

/**
 * Adds the meta box
 *
 * @wp-hook	add_meta_boxes
 * @return	void
 */
function sca_add_metabox() {
	add_meta_box( 'sca-box', __( 'Scheduled Content Actions', 'scheduled-content-actions' ), 'sca_metabox' );
}

/**
 * Displays the content of the metabox registered
 * and called at sca_add_metabox
 *
 * @param	object $oCurrentPost the current post
 * @return	void
 */
function sca_metabox( $oCurrentPost ) {

	$aAvailableActions = sca_get_actions();
	// new actions for this post
	$iTimezone = current_time( 'timestamp' );
	?>
	<div class="sca-action-box sca-metabox sca-new-action-container">
		<h3><?php _e( 'Add an action', 'scheduled-content-actions' ); ?></h3>
		<div class="inside">
			<p>
				<select name="scatype" id="sca-type" class="large-text">
					<option value=""><?php _e( 'Choose an action', 'scheduled-content-actions' ); ?></option>
					<?php foreach ( $aAvailableActions as $sAction => $sLabel ) : ?>
						<option value="<?php echo $sAction; ?>"><?php echo $sLabel; ?></option>
					<?php endforeach; ?>
				</select>
			</p>
			<div class="sca-additional-form-data"></div>
			<div class="sca-additional-taxonomy-data"></div>
			<hr>
			<p>
				<label><strong><?php _e( 'Choose the date', 'scheduled-content-actions' ); ?></strong></label><br />
				<input type="number" class="small-text" id="sca-date-day" name="scadateday" min="1" max="31" step="1" value="<?php echo date( 'd' ); ?>" />.
				<select id="sca-date-month" name="scadatemonth">
					<?php for ( $i = 1; $i <= 12; $i++ ) : ?>
						<option <?php echo ( date( 'm' ) == $i ? 'selected="selected"' : '' ); ?> value="<?php echo $i; ?>"><?php echo date_i18n( 'F', strtotime( '01.' . $i . '.2013' ) ); ?></option>
					<?php endfor; ?>
				</select>.
				<input type="number" class="small-text" id="sca-date-year" name="scadateyear" min="<?php echo date( 'Y' ); ?>" step="1" value="<?php echo date( 'Y' ); ?>" />
			</p>
			<p>
				<label><strong><?php _e( 'Choose the time', 'scheduled-content-actions' ); ?></strong></label><br />
				<input type="number" class="small-text" id="sca-date-hour" name="scadatehour" min="0" max="24" step="1" value="<?php echo date( 'H', $iTimezone ); ?>" />:
				<input type="number" class="small-text" id="sca-date-min" name="scadatemin" min="0" max="60" step="1" value="<?php echo date( 'i', $iTimezone ); ?>" />:
				<input type="number" class="small-text" id="sca-date-sec" name="scadatesec" min="0" max="60" step="1" value="<?php echo date( 's', $iTimezone ); ?>" />
			</p>
			<hr>
			<p><input type="submit" name="scanewaction" id="sca-newaction-submit" class="button-primary alignright" value="<?php _e( 'Save' ); ?>"><br class="clearfix"></p>
			<input type="hidden" name="scapostid" id="sca-post-id" value="<?php echo $oCurrentPost->ID; ?>">
		</div>
	</div>

	<div class="sca-current-action-container">
	<?php
	// Current Actions for this post
	$aCurrentPostActions = array();
	$aCurrentActions = get_option( '_sca_current_actions' );
	if ( isset( $aCurrentActions[ $oCurrentPost->ID ] ) )
		$aCurrentPostActions = $aCurrentActions[ $oCurrentPost->ID ];
	if ( ! empty( $aCurrentPostActions ) ) {
		?>
		<table id="sca">
			<thead>
				<tr>
					<th class="left"><?php _e( 'Date', 'scheduled-content-actions' ); ?></th>
					<th><?php _e( 'Action', 'scheduled-content-actions' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $aCurrentPostActions as $iTime => $aActions ) : ?>
					<tr>
						<td class="left"><?php echo date_i18n( 'd.m.Y H:i:s', $iTime ); ?></td>
						<td class="td-<?php echo $iTime; ?>">
							<?php foreach ( $aActions as $sAction ) : ?>
								<?php
									$sLabel = $aAvailableActions[ $sAction[ 'type' ] ];
									if ( $sAction[ 'type' ] == 'add_term' || $sAction[ 'type' ] == 'delete_term' )
										$sLabel .= ' - ' . __( 'Taxonomy', 'scheduled-content-actions' ) . ': ' . $sAction[ 'term_taxonomy' ] . ' ' . __( 'Term', 'scheduled-content-actions' ) . ': ' . $sAction[ 'term_slug' ];
									else if ( $sAction[ 'type' ] == 'add_meta' || $sAction[ 'type' ] == 'update_meta' || $sAction[ 'type' ] == 'delete_meta' )
										$sLabel .= ' - ' . __( 'Meta Name', 'scheduled-content-actions' ) . ': ' . $sAction[ 'meta_name' ] . ' ' . __( 'Meta Value', 'scheduled-content-actions' ) . ': ' . $sAction[ 'meta_value' ];
									else if ( $sAction[ 'type' ] == 'change_title' )
										$sLabel .= ' - ' . __( 'Title', 'scheduled-content-actions' ) . ': ' . $sAction[ 'new_title' ]
								?>
								<div class="sca-action">
									<a href="#" class="remove-action" data-postid="<?php echo $oCurrentPost->ID; ?>" data-time="<?php echo $iTime; ?>" data-action="<?php echo $sAction[ 'type' ]; ?>">&nbsp;</a>
									<?php echo $sLabel; ?>
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
