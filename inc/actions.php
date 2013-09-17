<?php
/**
 * Feature Name:	Actions
 * Author:			HerrLlama for Inpsyde GmbH
 * Author URI:		http://inpsyde.com
 * Licence:			GPLv3
 */

function sca_get_actions() {
	return apply_filters( 'sca_get_actions' , array(
		'stick_content'		=> __( 'Stick Content', SCA_TEXTDOMAIN ),
		'unstick_content'	=> __( 'Unstick Content', SCA_TEXTDOMAIN ),
		'draft_content'		=> __( 'Draft Content', SCA_TEXTDOMAIN ),
		'trash_content'		=> __( 'Trash Content', SCA_TEXTDOMAIN ),
		'delete_content'	=> __( 'Delete Content', SCA_TEXTDOMAIN ),
		'open_comments'		=> __( 'Open Comments', SCA_TEXTDOMAIN ),
		'close_comments'	=> __( 'Close Comments', SCA_TEXTDOMAIN ),
	) );
}