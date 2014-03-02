<?php
/**
 * Feature Name:	Admin Columns
 * Author:			HerrLlama for Inpsyde GmbH
 * Author URI:		http://inpsyde.com
 * Licence:			GPLv3
 */

add_filter( 'manage_posts_columns', 'sca_columns_head' );
add_filter( 'manage_page_posts_columns', 'sca_columns_head', 10 );
add_action( 'manage_page_posts_custom_column', 'sca_columns_content', 10, 2 );
add_action( 'manage_posts_custom_column', 'sca_columns_content', 10, 2 );

function sca_columns_head( $defaults ) {
	
	$defaults[ 'scheduled_content_action' ] = __( 'Scheduled Action', SCA_TEXTDOMAIN );
	
	return $defaults;
}

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
		_e( 'No actions scheduled', SCA_TEXTDOMAIN );
	}
}