/**
 * Feature Name: Scripts
 * Author:       HerrLlama for wpcoding.de
 * Author URI:   http://wpcoding.de
 * Licence:      GPLv3
 */

( function( $ ) {
	var ScheduledContentActionsScripts = {
		init: function() {
			
			// bind additional form action
			ScheduledContentActionsScripts.bindAdditionalFormAction();
			// bind add action
			ScheduledContentActionsScripts.bindAddAction();
			// bind delete action
			ScheduledContentActionsScripts.bindDeleteAction();
		},
		
		bindAdditionalFormAction: function() {
			
			$( document ).on( 'change', '#sca-type', function() {
				
				// reset box
				$( '.sca-additional-form-data' ).html( '' );
				
				// get the type
				var type = $( this ).val();
				
				// check if the given type is there to work with terms or metas
				var additional_information_required = [ '', 'delete_term', 'add_term', 'add_meta', 'delete_meta', 'update_meta', 'change_title' ];
				if ( jQuery.inArray( type, additional_information_required ) > 0 ) {
					
					// load the additional form data via the action type
					var postVars = {
						action: 'sca_load_additional_form_data',
						type: type
					}
					$.post( ajaxurl, postVars, function( response ) {
						$( '.sca-additional-form-data' ).html( '<hr>' + response );
					} );
				}
				return false;
			} );
			
		},
		
		bindAddAction: function() {
			$( document ).on( 'click', '#sca-newaction-submit', function() {
				
				// button
				var button = $( this );
				
				// check type
				var type = $( this ).parent().parent().find( '#sca-type' ).val();
				if ( type == '' ) {
					$( '.sca-new-action-container' ).find( '.sca-type-container' ).addClass( 'error' );
					return false;
				}
				
				// remove errors
				$( '.sca-new-action-container' ).find( '.sca-type-container' ).removeClass( 'error' );
				
				var postData = {
					action: 'sca_add_action',
					type: type,
					label: $( this ).parent().parent().find( '#sca-type option:selected' ).text(),
					postId: $( this ).parent().parent().find( '#sca-post-id' ).val(),
					dateDay: $( this ).parent().parent().find( '#sca-date-day' ).val(),
					dateMonth: $( this ).parent().parent().find( '#sca-date-month' ).val(),
					dateYear: $( this ).parent().parent().find( '#sca-date-year' ).val(),
					dateHour: $( this ).parent().parent().find( '#sca-date-hour' ).val(),
					dateMin: $( this ).parent().parent().find( '#sca-date-min' ).val(),
					dateSec: $( this ).parent().parent().find( '#sca-date-sec' ).val()
				};
				
				if ( type == 'add_term' || type == 'delete_term' ) {
					postData.termTaxonomy = $( '#sca-term-taxonomy' ).val();
					postData.termSlug = $( '#sca-term-slug' ).val();
					postData.label += ' - ' + sca_vars.label_taxonomy + ': ' + postData.termTaxonomy  + ' ' + sca_vars.label_term + ': ' + postData.termSlug;
				} else if ( type == 'add_meta' || type == 'update_meta' || type == 'delete_meta' ) {
					postData.metaName = $( '#sca-meta-name' ).val();
					postData.metaValue = $( '#sca-meta-value' ).val();
					postData.label += ' - ' + sca_vars.label_meta_name + ': ' + postData.metaName  + ' ' + sca_vars.label_meta_value + ': ' + postData.metaValue;
				} else if ( type == 'change_title' ) {
					postData.newTitle = $( '#sca-new-title' ).val();
					postData.label = sca_vars.label_title + ': ' + postData.newTitle;
				}
				
				$.post( ajaxurl, postData, function( response ) {
				
					var jresponse = $.parseJSON( response );
					if ( jresponse.error == '1' ) {
						$( '.sca-new-action-container' ).prepend( '<div class="error"><p>' + jresponse.msg + '</p></div>' );
					} else {
						if ( button.parent().parent().find( 'div.error' ).length )
							button.parent().parent().find( 'div.error' ).remove();
						
						if ( $( '#sca' ).length ) {
							if ( $( '#sca' ).find( '.td-' + jresponse.action_time ).length ) {
								$( '#sca' ).find( '.td-' + jresponse.action_time ).append( '<div class="sca-action"><a href="#" class="remove-action" data-postid="' + postData.postId + '" data-time="' + jresponse.action_time + '" data-action="' + postData.type + '">&nbsp;</a>&nbsp;' + postData.label + '</div>' );
							} else {
								$( '#sca' ).find( 'tbody' ).append( '<tr><td class="left">' + jresponse.action_date + '</td><td class="td-' + jresponse.action_time + '"><div class="sca-action"><a href="#" class="remove-action" data-postid="' + postData.postId + '" data-time="' + jresponse.action_time + '" data-action="' + postData.type + '">&nbsp;</a>&nbsp;' + postData.label + '</div></td></tr>' );
							}
						} else {
							$( '.sca-current-action-container' ).html( '<table id="sca"><thead><tr><th class="left">' + jresponse.ln_date + '</th><th>' + jresponse.ln_action  + '</th></tr></thead><tbody><tr><td class="left">' + jresponse.action_date + '</td><td class="td-' + jresponse.action_time + '"><div class="sca-action"><a href="#" class="remove-action" data-postid="' + postData.postId + '" data-time="' + jresponse.action_time + '" data-action="' + postData.type + '">&nbsp;</a>&nbsp;' + postData.label + '</div></td></tr></tbody></table>' );
						}
					}
				} );
				
				// prevent clicking reload
				return false;
			} );
		},
		
		bindDeleteAction: function() {
			$( document ).on( 'click', '.remove-action', function() {
				var ele = $( this );
				var postData = {
					action: 'sca_delete_action',
					type: $( this ).data( 'action' ),
					postId: $( this ).data( 'postid' ),
					time: $( this ).data( 'time' )
				};
				$.post( ajaxurl, postData, function( response ) {
					ele.parent().css( 'background', '#f00' );
					ele.parent().delay( 500 ).slideUp( 'normal', function() {
						var td = $( this ).parent();
						var tr = td.parent( 'tr' );
						tr.css( 'display', 'none' );
						tr.remove();
						if ( $( '#sca' ).find( 'tr' ).length == 0 ) {
							$( '.sca-current-action-container' ).html( '' );
						}
					} );
				} );
				return false;
			} );
		},
	};
	$( document ).ready( function( $ ) {
		ScheduledContentActionsScripts.init();
	} );
} )( jQuery );