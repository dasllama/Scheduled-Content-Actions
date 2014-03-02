<?php
/**
 * Feature Name:	Meta-Box
 * Author:			HerrLlama for Inpsyde GmbH
 * Author URI:		http://inpsyde.com
 * Licence:			GPLv3
 */

add_action( 'add_meta_boxes', 'sca_add_metabox' );
function sca_add_metabox() {
	add_meta_box( 'sca-box', __( 'Scheduled Post Actions', SCA_TEXTDOMAIN ), 'sca_metabox' );
}
function sca_metabox( $post ) {

	$available_actions = sca_get_actions();
	?><div class="sca-current-action-container"><?php
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
					<th class="left"><?php _e( 'Date', SCA_TEXTDOMAIN ); ?></th>
					<th><?php _e( 'Action', SCA_TEXTDOMAIN ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $current_post_actions as $time => $actions ) : ?>
					<tr>
						<td class="left"><?php echo date_i18n( 'd.m.Y H:i:s', $time ); ?></td>
						<td class="td-<?php echo $time; ?>">
							<?php foreach ( $actions as $action ) : ?>
								<div class="sca-action">
									<a href="#" class="remove-action" data-postid="<?php echo $post->ID; ?>" data-time="<?php echo $time; ?>" data-action="<?php echo $action; ?>">&nbsp;</a>
									<?php echo $available_actions[ $action ]; ?>
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

	// new actions for this post
	$timezone = current_time( 'timestamp' );
	?>
	<p><strong><?php _e( 'Add a new scheduled action below.', SCA_TEXTDOMAIN ); ?></strong></p>
	<div class="sca-new-action-container">
		<input type="hidden" name="scapostid" id="sca-post-id" value="<?php echo $post->ID; ?>">
		<?php _e( 'Perform', SCA_TEXTDOMAIN ); ?>
		<div class="sca-type-container">
			<select name="scatype" id="sca-type">
				<option value=""><?php _e( 'Choose an action', SCA_TEXTDOMAIN ); ?></option>
				<?php foreach ( $available_actions as $action => $label ) : ?>
					<option value="<?php echo $action; ?>"><?php echo $label; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<?php _e( 'on', SCA_TEXTDOMAIN ); ?>
		<input type="number" class="small-text" id="sca-date-day" name="scadateday" min="1" max="31" step="1" value="<?php echo date( 'd' ); ?>" />.
		<select id="sca-date-month" name="scadatemonth">
			<?php for ( $i = 1; $i <= 12; $i++ ) : ?>
				<option <?php echo ( date( 'm' ) == $i ? 'selected="selected"' : '' ); ?> value="<?php echo $i; ?>"><?php echo date_i18n( 'F', strtotime( '01.' . $i . '.2013' ) ); ?></option>
			<?php endfor; ?>
		</select>.
		<input type="number" class="small-text" id="sca-date-year" name="scadateyear" min="<?php echo date( 'Y' ); ?>" step="1" value="<?php echo date( 'Y' ); ?>" />
		<?php _e( 'at', SCA_TEXTDOMAIN ); ?>
		<input type="number" class="small-text" id="sca-date-hour" name="scadatehour" min="0" max="24" step="1" value="<?php echo date( 'H', $timezone ); ?>" />:
		<input type="number" class="small-text" id="sca-date-min" name="scadatemin" min="0" max="60" step="1" value="<?php echo date( 'i', $timezone ); ?>" />:
		<input type="number" class="small-text" id="sca-date-sec" name="scadatesec" min="0" max="60" step="1" value="<?php echo date( 's', $timezone ); ?>" />
		<input type="submit" name="scanewaction" id="sca-newaction-submit" class="button" value="<?php _e( 'Save' ); ?>">
	</div>
	<?php
}